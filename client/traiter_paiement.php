<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_interv = $_POST['id_intervention'];
    $mode = $_POST['mode_paiement'];

    // On met à jour la ligne de paiement existante (celle créée par le mécanicien)
    $stmt = $pdo->prepare("UPDATE paiement SET statut = 'Payé', mode_paiement = ?, date_paiement = NOW() WHERE id_intervention = ?");
    $stmt->execute([$mode, $id_interv]);

    header("Location: historique.php?msg=success");
}
