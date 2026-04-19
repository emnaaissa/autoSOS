<?php
require_once('../config/db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Idéalement : password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telephone = $_POST['telephone'];
    $role = $_POST['role'];

    try {
        $pdo->beginTransaction(); // DÉBUT DE LA TRANSACTION

        // 1. Insertion dans la table USER
        $sqlUser = "INSERT INTO USER (nom, prenom, email, mot_de_passe, telephone) VALUES (?, ?, ?, ?, ?)";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([$nom, $prenom, $email, $password, $telephone]);

        // Récupérer l'ID qui vient d'être généré
        $newUserId = $pdo->lastInsertId();

        // 2. Insertion selon le rôle choisi
        if ($role === 'client') {
            $sqlRole = "INSERT INTO CLIENT (id_user) VALUES (?)";
            $stmtRole = $pdo->prepare($sqlRole);
            $stmtRole->execute([$newUserId]);
        } elseif ($role === 'mecanicien') {
            $specialite = $_POST['specialite'] ?? 'Général';
            $loc = $_POST['localisation'] ?? '';
            $sqlRole = "INSERT INTO MECANICIEN (id_user, specialite, localisation, disponibilite) VALUES (?, ?, ?, 1)";
            $stmtRole = $pdo->prepare($sqlRole);
            $stmtRole->execute([$newUserId, $specialite, $loc]);
        }

        $pdo->commit(); // TOUT EST OK, ON ENREGISTRE
        echo json_encode(["status" => "success", "message" => "Compte créé avec succès"]);
    } catch (Exception $e) {
        $pdo->rollBack(); // ERREUR, ON ANNULE TOUT
        echo json_encode(["status" => "error", "message" => "Erreur lors de l'inscription : " . $e->getMessage()]);
    }
}
