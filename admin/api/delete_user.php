<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // La suppression dans 'user' déclenche le CASCADE vers 'client', 'vehicule', etc.
        $stmt = $pdo->prepare("DELETE FROM user WHERE id_user = ?");
        $stmt->execute([$id]);

        echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID manquant"]);
}
exit;
