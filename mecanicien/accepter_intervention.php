<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$id_mecanicien   = $_SESSION['user_id'];
$id_intervention = $_POST['id_intervention'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && $id_intervention) {
    try {
        // Auto-heal: ensure mechanic exists in mecanicien table
        $checkMeca = $pdo->prepare("SELECT id_user FROM mecanicien WHERE id_user = ?");
        $checkMeca->execute([$id_mecanicien]);
        if (!$checkMeca->fetch()) {
            $insertMeca = $pdo->prepare("
                INSERT INTO mecanicien (id_user, specialite, disponibilite, localisation) 
                VALUES (?, 'Général', 1, 'Non spécifié')
            ");
            $insertMeca->execute([$id_mecanicien]);
        }

        // Fetch current status
        $stmt = $pdo->prepare("SELECT statut FROM intervention WHERE id_intervention = ?");
        $stmt->execute([$id_intervention]);
        $intervention = $stmt->fetch();

        if ($intervention && strtolower(trim($intervention['statut'])) === 'en attente') {
            $update = $pdo->prepare("
                UPDATE intervention 
                SET statut = 'en cours',
                    id_mecanicien = ?,
                    date_acceptation = NOW()
                WHERE id_intervention = ?
            ");
            $update->execute([$id_mecanicien, $id_intervention]);
        }
    } catch (PDOException $e) {
        error_log("Erreur d'acceptation d'intervention : " . $e->getMessage());
    }
}

header("Location: dashboard.php");
exit();