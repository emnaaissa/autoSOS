<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$id_client = $_SESSION['user_id'];
$id_intervention = $_POST['id_intervention'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && $id_intervention) {
    try {
        $stmt = $pdo->prepare("SELECT statut FROM intervention WHERE id_intervention = ? AND id_client = ?");
        $stmt->execute([$id_intervention, $id_client]);
        $intervention = $stmt->fetch();

        // On vérifie que ce n'est pas encore pris en charge
        if ($intervention && strtolower(trim($intervention['statut'])) === 'en attente') {
            $update = $pdo->prepare("UPDATE intervention SET statut = 'annulé' WHERE id_intervention = ?");
            $update->execute([$id_intervention]);
        }
    } catch (PDOException $e) {
        error_log("Erreur d'annulation : " . $e->getMessage());
    }
}

header("Location: historique.php");
exit();
?>
