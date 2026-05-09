<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$id_mecanicien = $_SESSION['user_id'];
$id_intervention = $_POST['id_intervention'] ?? null;
$montant = $_POST['montant'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && $id_intervention && $montant) {
    try {
        $pdo->beginTransaction();

        $updateInterv = $pdo->prepare("
            UPDATE intervention 
            SET statut = 'terminé', date_intervention = NOW() 
            WHERE id_intervention = ? AND id_mecanicien = ?
        ");
        $updateInterv->execute([$id_intervention, $id_mecanicien]);

        $stmtPay = $pdo->prepare("
            INSERT INTO paiement (id_intervention, montant, statut, date_paiement) 
            VALUES (?, ?, 'en attente', NOW())
            ON DUPLICATE KEY UPDATE montant = VALUES(montant)
        ");
        $stmtPay->execute([$id_intervention, $montant]);

        $pdo->commit();
        header("Location: dashboard.php?success=1");
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Erreur lors de la clôture : " . $e->getMessage());
        header("Location: dashboard.php?error=1");
    }
} else {
    header("Location: dashboard.php");
}
exit();
