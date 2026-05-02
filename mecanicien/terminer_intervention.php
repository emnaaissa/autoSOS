<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$id_mecanicien = $_SESSION['user_id'];
$id_intervention = $_POST['id_intervention'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && $id_intervention) {
    try {
        $update = $pdo->prepare("UPDATE intervention SET statut = 'terminé' WHERE id_intervention = ? AND id_mecanicien = ?");
        $update->execute([$id_intervention, $id_mecanicien]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la clôture : " . $e->getMessage());
    }
}

header("Location: dashboard.php");
exit();
?>
