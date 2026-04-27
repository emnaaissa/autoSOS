<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../config/db.php");

$id_user = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID invalide");
}

// 1. CHECK OWNERSHIP + GET PHOTO
$stmt = $pdo->prepare("SELECT photo FROM vehicule WHERE id_vehicule = ? AND id_client = ?");
$stmt->execute([$id, $id_user]);
$vehicule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vehicule) {
    die("Véhicule non trouvé ou accès refusé");
}

// 2. DELETE IMAGE FILE
if (!empty($vehicule['photo'])) {
    $filePath = "../" . $vehicule['photo'];

    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// 3. DELETE FROM DATABASE
$stmt = $pdo->prepare("DELETE FROM vehicule WHERE id_vehicule = ? AND id_client = ?");
$stmt->execute([$id, $id_user]);

// 4. REDIRECT
header("Location: vehicules.php");
exit();