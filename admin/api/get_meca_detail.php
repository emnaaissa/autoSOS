<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { echo json_encode(['error' => 'ID manquant']); exit; }

try {
    // Infos de base
    $stmt = $pdo->prepare("
        SELECT u.nom, u.prenom, u.email, u.telephone, m.specialite, m.disponibilite, m.localisation
        FROM user u JOIN mecanicien m ON u.id_user = m.id_user
        WHERE u.id_user = ?
    ");
    $stmt->execute([$id]);
    $meca = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$meca) { echo json_encode(['error' => 'Mécanicien introuvable']); exit; }

    // Interventions
    $stmt = $pdo->prepare("
        SELECT i.id_intervention, i.date_intervention, i.statut,
               i.type_intervention, i.localisation,
               u.nom AS client_nom, u.prenom AS client_prenom,
               p.montant, p.mode_paiement, p.statut AS paiement_statut
        FROM intervention i
        LEFT JOIN client c ON i.id_client = c.id_user
        LEFT JOIN user u ON c.id_user = u.id_user
        LEFT JOIN paiement p ON i.id_intervention = p.id_intervention
        WHERE i.id_mecanicien = ?
        ORDER BY i.date_intervention DESC
    ");
    $stmt->execute([$id]);
    $interventions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Avis
    $stmt = $pdo->prepare("
        SELECT a.note, a.commentaire, a.date,
               u.nom AS client_nom, u.prenom AS client_prenom
        FROM avis a
        LEFT JOIN user u ON a.id_user = u.id_user
        LEFT JOIN intervention i ON a.id_intervention = i.id_intervention
        WHERE i.id_mecanicien = ?
        ORDER BY a.date DESC
    ");
    $stmt->execute([$id]);
    $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Totaux
    $totalRevenu = array_sum(array_column(
        array_filter($interventions, fn($i) => $i['paiement_statut'] === 'Payé'),
        'montant'
    ));
    $totalOrders  = count($interventions);
    $avgNote      = count($avis) ? round(array_sum(array_column($avis, 'note')) / count($avis), 1) : null;

    echo json_encode([
        'meca'         => $meca,
        'interventions'=> $interventions,
        'avis'         => $avis,
        'stats'        => [
            'total_revenu' => number_format($totalRevenu, 2),
            'total_orders' => $totalOrders,
            'avg_note'     => $avgNote,
            'total_avis'   => count($avis),
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;