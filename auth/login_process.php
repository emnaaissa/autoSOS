<?php
session_start();
require_once('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM USER WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $password === $user['mot_de_passe']) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['nom'] = $user['nom'];

        $role = "";

        $checkAdmin = $pdo->prepare("SELECT id_user FROM ADMIN WHERE id_user = ?");
        $checkAdmin->execute([$user['id_user']]);

        $checkMeca = $pdo->prepare("SELECT id_user FROM MECANICIEN WHERE id_user = ?");
        $checkMeca->execute([$user['id_user']]);

        $checkClient = $pdo->prepare("SELECT id_user FROM CLIENT WHERE id_user = ?");
        $checkClient->execute([$user['id_user']]);

        if ($checkAdmin->fetch()) {
            $role = "admin";
        } elseif ($checkMeca->fetch()) {
            $role = "mecanicien";
        } elseif ($checkClient->fetch()) {
            $role = "client";
        }

        $_SESSION['role'] = $role;
        echo json_encode(["status" => "success", "role" => $role]);
    } else {
        echo json_encode(["status" => "error", "message" => "Identifiants invalides"]);
    }
}
