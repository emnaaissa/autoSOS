<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../config/db.php");

$id_user = $_SESSION['user_id'];

// FETCH VEHICULES (PDO)
$stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id_client = ?");
$stmt->execute([$id_user]);
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Véhicules</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">

<!-- SIDEBAR -->
<?php include("../includes/sidebar_client.php"); ?>

<!-- MAIN -->
<main class="md:ml-64 p-6 md:p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Mes Véhicules</h2>

        <button onclick="toggleForm()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Ajouter
        </button>
    </div>

    <!-- ADD FORM -->
    <div id="formAdd" class="hidden bg-white p-6 rounded-xl shadow mb-6 max-w-md">

        <form method="POST"
              action="add_vehicule.php"
              enctype="multipart/form-data">

            <input name="marque" placeholder="Marque"
                   class="w-full mb-3 p-2 border rounded" required>

            <input name="modele" placeholder="Modèle"
                   class="w-full mb-3 p-2 border rounded" required>

            <input name="immatriculation" placeholder="Immatriculation"
                   class="w-full mb-3 p-2 border rounded" required>

            <input name="type" placeholder="Type"
                   class="w-full mb-3 p-2 border rounded">

            <input type="file" name="photo"
                   class="w-full mb-4">

            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full">
                Ajouter
            </button>
        </form>

    </div>

    <!-- LIST -->
    <?php if (count($vehicules) == 0): ?>
        <div class="bg-white p-6 rounded shadow text-gray-500">
            Aucun véhicule enregistré
        </div>
    <?php endif; ?>

    <div class="space-y-4">

        <?php foreach ($vehicules as $row): ?>

            <div class="flex items-center bg-white p-4 rounded-xl shadow hover:bg-slate-50 transition">

                <!-- IMAGE -->
                <?php if (!empty($row['photo'])): ?>
                    <img src="../<?= $row['photo'] ?>"
                         class="w-16 h-16 object-cover rounded-lg mr-4">
                <?php else: ?>
                    <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded-lg mr-4">
                        <i class="fas fa-car text-gray-500"></i>
                    </div>
                <?php endif; ?>

                <!-- INFO -->
                <div class="flex-1">
                    <p class="font-bold text-slate-800">
                        <?= htmlspecialchars($row['marque']) ?>
                        <?= htmlspecialchars($row['modele']) ?>
                    </p>

                    <p class="text-sm text-gray-500">
                        <?= htmlspecialchars($row['immatriculation']) ?>
                    </p>
                </div>

                <!-- ACTIONS -->
                <div class="space-x-3 text-sm">
                    <a href="edit_vehicule.php?id=<?= $row['id_vehicule'] ?>"
                       class="text-blue-600 hover:underline">
                        Modifier
                    </a>

                    <a href="delete_vehicule.php?id=<?= $row['id_vehicule'] ?>"
                       onclick="return confirm('Supprimer ce véhicule ?')"
                       class="text-red-600 hover:underline">
                        Supprimer
                    </a>
                </div>

            </div>

        <?php endforeach; ?>

    </div>

</main>

<!-- SCRIPT -->
<script>
function toggleForm() {
    document.getElementById("formAdd").classList.toggle("hidden");
}
</script>

</body>
</html>