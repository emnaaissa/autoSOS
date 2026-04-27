

<?php
session_start();
include("../config/db.php");

$id_user = $_SESSION['user_id'];

$marque = $_POST['marque'];
$modele = $_POST['modele'];
$immatriculation = $_POST['immatriculation'];
$type = $_POST['type'];

$photoPath = null;

if (!empty($_FILES['photo']['name'])) {
    $fileName = time() . "_" . $_FILES['photo']['name'];
    $target = "../assets/uploads/vehicules/" . $fileName;

    move_uploaded_file($_FILES['photo']['tmp_name'], $target);

    $photoPath = "assets/uploads/vehicules/" . $fileName;
}

$stmt = $pdo->prepare("
    INSERT INTO vehicule (marque, modele, immatriculation, type, photo, id_client)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->execute([$marque, $modele, $immatriculation, $type, $photoPath, $id_user]);

header("Location: vehicules.php");

