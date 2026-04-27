<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../config/db.php");
include("../includes/sidebar_client.php");

$id_user = $_SESSION['user_id'];

// GET CURRENT USER DATA (PDO)
$stmt = $pdo->prepare("SELECT nom, prenom FROM user WHERE id_user = ?");
$stmt->execute([$id_user]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">

<!-- SIDEBAR -->
<?php include("../includes/sidebar_client.php"); ?>

<!-- CONTENT -->
<main class="md:ml-64 p-8">

    <h2 class="text-2xl font-bold mb-6 text-slate-800">
        Paramètres du compte
    </h2>

    <form method="POST"
          action="update_profile.php"
          class="bg-white p-6 rounded-xl shadow w-full max-w-md">

        <label class="text-sm text-gray-500">Nom</label>
        <input type="text"
               name="nom"
               value="<?= htmlspecialchars($user['nom'] ?? '') ?>"
               class="w-full mb-4 p-2 border rounded">

        <label class="text-sm text-gray-500">Prénom</label>
        <input type="text"
               name="prenom"
               value="<?= htmlspecialchars($user['prenom'] ?? '') ?>"
               class="w-full mb-4 p-2 border rounded">

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Mettre à jour
        </button>

    </form>

</main>

</body>
</html>