<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../config/db.php");

$id_user = $_SESSION['user_id'];
$id_vehicule = $_POST['id'] ?? null;
$marque = $_POST['marque'] ?? '';
$modele = $_POST['modele'] ?? '';
$immatriculation = $_POST['immatriculation'] ?? '';
$type = $_POST['type'] ?? '';

if (!$id_vehicule) {
    die("ID du véhicule manquant.");
}

try {
    $stmtCheck = $pdo->prepare("SELECT photo FROM vehicule WHERE id_vehicule = ? AND id_client = ?");
    $stmtCheck->execute([$id_vehicule, $id_user]);
    $currentVehicule = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$currentVehicule) {
        die("Accès refusé ou véhicule inexistant.");
    }

    $photoPath = $currentVehicule['photo'];

    if (!empty($_FILES['photo']['name'])) {
        $fileName = time() . "_" . $_FILES['photo']['name'];
        $targetDir = "../assets/uploads/vehicules/";
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {

            if (!empty($currentVehicule['photo'])) {
                $oldFilePath = "../" . $currentVehicule['photo'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $photoPath = "assets/uploads/vehicules/" . $fileName;
        }
    }

    $sql = "UPDATE vehicule 
            SET marque = ?, modele = ?, immatriculation = ?, type = ?, photo = ? 
            WHERE id_vehicule = ? AND id_client = ?";

    $stmtUpdate = $pdo->prepare($sql);
    $stmtUpdate->execute([
        $marque,
        $modele,
        $immatriculation,
        $type,
        $photoPath,
        $id_vehicule,
        $id_user
    ]);

    header("Location: vehicules.php?msg=updated");
    exit();
} catch (PDOException $e) {
    die("Erreur lors de la mise à jour : " . $e->getMessage());
}
