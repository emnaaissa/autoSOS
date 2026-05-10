<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

try {
    // Total clients
    $totalClients = $pdo->query("SELECT COUNT(*) FROM client")->fetchColumn();

    // Total mécaniciens
    $totalMecas = $pdo->query("SELECT COUNT(*) FROM mecanicien")->fetchColumn();

    // Commission (12% sur les paiements Payé)
    $commission = $pdo->query("
        SELECT COALESCE(SUM(montant * 0.12), 0) FROM paiement WHERE statut = 'Payé'
    ")->fetchColumn();

    // Interventions par statut
    $totalInt     = $pdo->query("SELECT COUNT(*) FROM intervention")->fetchColumn();
    $doneInt      = $pdo->query("SELECT COUNT(*) FROM intervention WHERE statut = 'terminé'")->fetchColumn();
    $pendingInt   = $pdo->query("SELECT COUNT(*) FROM intervention WHERE statut = 'en cours'")->fetchColumn();
    $unpickedInt  = $pdo->query("SELECT COUNT(*) FROM intervention WHERE statut = 'en attente'")->fetchColumn();

    // Vitesse moyenne de prise en charge (date_demande → date_acceptation) en minutes
    $avgPickup = $pdo->query("
        SELECT AVG(TIMESTAMPDIFF(MINUTE, date_demande, date_acceptation))
        FROM intervention
        WHERE date_demande IS NOT NULL AND date_acceptation IS NOT NULL
    ")->fetchColumn();

    // Durée moyenne de traitement (date_acceptation → date_fin) en minutes
    $avgHandling = $pdo->query("
        SELECT AVG(TIMESTAMPDIFF(MINUTE, date_acceptation, date_fin))
        FROM intervention
        WHERE date_acceptation IS NOT NULL AND date_fin IS NOT NULL
    ")->fetchColumn();

    // Formater en heures/minutes lisibles
    function formatDuration($minutes) {
        if ($minutes === null || $minutes == 0) return 'N/A';
        $minutes = round($minutes);
        if ($minutes < 60) return $minutes . ' min';
        return floor($minutes / 60) . 'h ' . ($minutes % 60) . 'min';
    }

    echo json_encode([
        'total_clients'   => (int) $totalClients,
        'total_mecas'     => (int) $totalMecas,
        'commission'      => number_format((float) $commission, 2),
        'total_int'       => (int) $totalInt,
        'done_int'        => (int) $doneInt,
        'pending_int'     => (int) $pendingInt,
        'unpicked_int'    => (int) $unpickedInt,
        'avg_pickup'      => formatDuration($avgPickup),
        'avg_handling'    => formatDuration($avgHandling),
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;