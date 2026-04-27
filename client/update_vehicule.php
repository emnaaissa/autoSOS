<?php
session_start();
include("../config/db.php");

$id_user = $_SESSION['user_id'];

$id = $_POST['id'];
$marque = $_POST['marque'];
$modele = $_POST['modele'];
$immatriculation = $_POST['immatriculation'];
$type = $_POST['type'];

// GET OLD IMAGE
$stmt = $pdo->prepare("SELECT photo FROM vehicule WHERE id_vehicule=? AND id_client=?");
$stmt->execute([$id, $id_user]);
$vehicule = $stmt->fetch(PDO::FETCH_ASSOC);

$photoPath = $vehicule['photo'];

// NEW IMAGE UPLOAD
if (!empty($_FILES['photo']['name'])) {

    $uploadDir = "../assets/uploads/vehicules/";
    $fileName = uniqid() . "_" . basename($_FILES['photo']['name']);
    $target = $uploadDir . $fileName;

    move_uploaded_file($_FILES['photo']['tmp_name'], $target);

    // DELETE OLD IMAGE
    if (!empty($vehicule['photo'])) {
        unlink("../" . $vehicule['photo']);
    }

    $photoPath = "assets/uploads/vehicules/" . $fileName;
}

// UPDATE
$stmt = $pdo->prepare("
    UPDATE vehicule
    SET marque=?, modele=?, immatriculation=?, type=?, photo=?
    WHERE id_vehicule=? AND id_client=?
");

$stmt->execute([
    $marque,
    $modele,
    $immatriculation,
    $type,
    $photoPath,
    $id,
    $id_user
]);

header("Location: vehicules.php");