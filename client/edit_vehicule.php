<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../config/db.php");

$id_user = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID manquant");
}

// FETCH VEHICULE (SECURE)
$stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id_vehicule = ? AND id_client = ?");
$stmt->execute([$id, $id_user]);
$vehicule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vehicule) {
    die("Véhicule introuvable");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier Véhicule</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">

<?php include("../includes/sidebar_client.php"); ?>

<main class="md:ml-64 p-8">

    <h2 class="text-2xl font-bold mb-6">Modifier Véhicule</h2>

    <form method="POST"
          action="update_vehicule.php"
          enctype="multipart/form-data"
          class="bg-white p-6 rounded-xl shadow max-w-md">

        <input type="hidden" name="id" value="<?= $vehicule['id_vehicule'] ?>">

        <input name="marque"
               value="<?= htmlspecialchars($vehicule['marque']) ?>"
               class="w-full mb-3 p-2 border rounded"
               required>

        <input name="modele"
               value="<?= htmlspecialchars($vehicule['modele']) ?>"
               class="w-full mb-3 p-2 border rounded"
               required>

        <input name="immatriculation"
               value="<?= htmlspecialchars($vehicule['immatriculation']) ?>"
               class="w-full mb-3 p-2 border rounded"
               required>

        <input name="type"
               value="<?= htmlspecialchars($vehicule['type']) ?>"
               class="w-full mb-3 p-2 border rounded">

        <!-- CURRENT IMAGE -->
        <?php if (!empty($vehicule['photo'])): ?>
            <img src="../<?= $vehicule['photo'] ?>"
                 class="w-24 h-24 object-cover rounded mb-3">
        <?php endif; ?>

        <input type="file" name="photo" class="mb-4">

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full">
            Mettre à jour
        </button>

    </form>

</main>

</body>
</html>