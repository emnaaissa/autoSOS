<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../config/db.php");

$id_user = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id_client = ?");
$stmt->execute([$id_user]);
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande SOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../assets/js/location.js"></script>
</head>

<body class="bg-gray-100">

<?php include("../includes/sidebar_client.php"); ?>

<main class="md:ml-64 p-8">

    <h2 class="text-2xl font-bold mb-6 text-slate-800">
        🚨 Demander une assistance
    </h2>

    <form method="POST" action="create_intervention.php"
          class="bg-white p-6 rounded-xl shadow max-w-lg space-y-4">

        <!-- VEHICLE -->
        <select name="id_vehicule" required class="w-full p-2 border rounded">
            <option value="">Choisir un véhicule</option>
            <?php foreach ($vehicules as $v): ?>
                <option value="<?= $v['id_vehicule'] ?>">
                    <?= htmlspecialchars($v['marque']) ?> <?= htmlspecialchars($v['modele']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- TYPE -->
        <select name="type_intervention" required class="w-full p-2 border rounded">
            <option value="">Type de panne</option>
            <option>Batterie</option>
            <option>Pneu crevé</option>
            <option>Remorquage</option>
            <option>Moteur</option>
        </select>

        <!-- DESCRIPTION -->
        <textarea name="description_probleme"
                  placeholder="Décrivez le problème..."
                  class="w-full p-2 border rounded"></textarea>

        <!-- LOCATION -->
        <div class="space-y-2">

            <label class="text-sm font-medium text-gray-600">Position GPS</label>

            <input id="locationInput"
                   name="localisation"
                   placeholder="Récupération de la position en cours..."
                   class="w-full p-2 border rounded bg-gray-50 text-sm"
                   readonly>

            <!-- Hidden fields that store the actual GPS coords -->
            <input type="hidden" id="latitudeInput"  name="latitude">
            <input type="hidden" id="longitudeInput" name="longitude">

            <button type="button"
                    onclick="getLocation()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm">
                📍 Actualiser ma position
            </button>

        </div>

        <button type="submit"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded">
            🚨 Envoyer la demande
        </button>

    </form>

</main>

</body>
</html>
