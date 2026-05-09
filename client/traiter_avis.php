<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_client = $_SESSION['user_id'];
    $id_interv = $_POST['id_intervention'];
    $note = (int)$_POST['note'];
    $comm = $_POST['commentaire'];

    $insert = $pdo->prepare("INSERT INTO avis (note, commentaire, date, id_intervention, id_user) VALUES (?, ?, CURDATE(), ?, ?)");
    $insert->execute([$note, $comm, $id_interv, $id_client]);

    header("Location: historique.php?msg=avis_succes");
}
