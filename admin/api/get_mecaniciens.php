<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

try {
    $sql = "SELECT u.id_user, u.nom, u.prenom, m.specialite, m.disponibilite, m.localisation 
            FROM user u 
            INNER JOIN mecanicien m ON u.id_user = m.id_user 
            ORDER BY u.id_user DESC";

    $stmt = $pdo->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
