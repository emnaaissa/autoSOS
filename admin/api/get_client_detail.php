<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { echo json_encode(['error' => 'ID manquant']); exit; }

try {
    // Infos de base
    $stmt = $pdo->prepare("
        SELECT u.nom, u.prenom, u.email, u.telephone, c.adresse
        FROM user u
        JOIN client c ON u.id_user = c.id_user
        WHERE u.id_user = ?
    ");
    $stmt->execute([$id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$client) { echo json_encode(['error' => 'Client introuvable']); exit; }

    // Interventions du client
    $stmt = $pdo->prepare("
        SELECT i.id_intervention, i.date_intervention, i.statut,
               i.type_intervention, i.localisation,
               u.nom AS meca_nom, u.prenom AS meca_prenom,
               m.specialite,
               p.montant, p.mode_paiement, p.statut AS paiement_statut,
               v.marque, v.modele, v.immatriculation
        FROM intervention i
        LEFT JOIN mecanicien m ON i.id_mecanicien = m.id_user
        LEFT JOIN user u ON m.id_user = u.id_user
        LEFT JOIN paiement p ON i.id_intervention = p.id_intervention
        LEFT JOIN vehicule v ON i.id_vehicule = v.id_vehicule
        WHERE i.id_client = ?
        ORDER BY i.date_intervention DESC
    ");
    $stmt->execute([$id]);
    $interventions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Avis laissés par ce client
    $stmt = $pdo->prepare("
        SELECT a.note, a.commentaire, a.date,
               u.nom AS meca_nom, u.prenom AS meca_prenom
        FROM avis a
        LEFT JOIN intervention i ON a.id_intervention = i.id_intervention
        LEFT JOIN mecanicien m ON i.id_mecanicien = m.id_user
        LEFT JOIN user u ON m.id_user = u.id_user
        WHERE a.id_user = ?
        ORDER BY a.date DESC
    ");
    $stmt->execute([$id]);
    $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Totaux
    $totalDepense = array_sum(array_column(
        array_filter($interventions, fn($i) => $i['paiement_statut'] === 'Payé'),
        'montant'
    ));

    echo json_encode([
        'client'        => $client,
        'interventions' => $interventions,
        'avis'          => $avis,
        'stats'         => [
            'total_depense'  => number_format((float)$totalDepense, 2),
            'total_sos'      => count($interventions),
            'total_avis'     => count($avis),
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;