<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

try {
    // Sélectionne uniquement les utilisateurs qui ont une entrée dans la table 'client'
    $query = "SELECT u.id_user, u.nom, u.prenom, u.email, u.telephone, c.adresse 
              FROM user u 
              INNER JOIN client c ON u.id_user = c.id_user 
              ORDER BY u.id_user DESC";

    $stmt = $pdo->query($query);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($clients);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
exit;
