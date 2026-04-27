<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$id_user      = $_SESSION['user_id'];
$id_vehicule  = $_POST['id_vehicule']       ?? null;
$type         = $_POST['type_intervention'] ?? null;
$description  = $_POST['description_probleme'] ?? null;
$localisation = $_POST['localisation']      ?? null;

// Cast to float or null — prevents MySQL saving 0.00 for empty strings
$latitude  = !empty($_POST['latitude'])  ? (float) $_POST['latitude']  : null;
$longitude = !empty($_POST['longitude']) ? (float) $_POST['longitude'] : null;

$stmt = $pdo->prepare("
    INSERT INTO intervention 
    (id_client, id_vehicule, date_demande, statut, localisation, latitude, longitude, description_probleme, type_intervention)
    VALUES (?, ?, NOW(), 'en attente', ?, ?, ?, ?, ?)
");

$stmt->execute([
    $id_user,
    $id_vehicule,
    $localisation,
    $latitude,
    $longitude,
    $description,
    $type
]);

header("Location: historique.php");
exit();
