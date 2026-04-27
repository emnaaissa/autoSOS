<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../config/db.php");

$id_user = $_SESSION['user_id'];

/* ========================
   FETCH DATA (PDO)
======================== */

// GET USER FIRST NAME
$stmt = $pdo->prepare("SELECT prenom FROM user WHERE id_user = ?");
$stmt->execute([$id_user]);
$prenom = htmlspecialchars($stmt->fetchColumn() ?? '');

// COUNT SOS
$stmt = $pdo->prepare("SELECT COUNT(*) FROM intervention WHERE id_client = ?");
$stmt->execute([$id_user]);
$totalSOS = $stmt->fetchColumn();

// COUNT VEHICULES
$stmt = $pdo->prepare("SELECT COUNT(*) FROM vehicule WHERE id_client = ?");
$stmt->execute([$id_user]);
$totalVehicules = $stmt->fetchColumn();

// GET LAST VEHICULE
$stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id_client = ? ORDER BY id_vehicule DESC LIMIT 1");
$stmt->execute([$id_user]);
$vehicule = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">

<?php include("../includes/sidebar_client.php"); ?>

<main class="md:ml-64 p-4 md:p-8">

    <!-- HEADER -->
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Bonjour, <?= $prenom ?> 👋</h2>
            <p class="text-slate-500 italic">Prêt à reprendre la route avec autoSOS</p>
        </div>
        <a href="demande_sos.php"
           class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-black shadow-lg flex items-center justify-center animate-pulse">
            <i class="fas fa-bullhorn mr-3"></i> DEMANDER UNE ASSISTANCE
        </a>
    </header>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- SOS -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">SOS Lancés</p>
                    <h3 class="text-3xl font-black text-slate-800">
                        <?= $totalSOS ?>
                    </h3>
                </div>
                <div class="bg-blue-50 p-4 rounded-full text-blue-500">
                    <i class="fas fa-truck-pickup text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- VEHICULES -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Véhicules</p>
                    <h3 class="text-3xl font-black text-slate-800">
                        <?= $totalVehicules ?>
                    </h3>
                </div>
                <div class="bg-slate-50 p-4 rounded-full text-slate-900">
                    <i class="fas fa-car-side text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- STATUS -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Statut Compte</p>
                    <h3 class="text-lg font-black text-green-600 uppercase">Actif</h3>
                </div>
                <div class="bg-green-50 p-4 rounded-full text-green-500">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- CONTENT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- VEHICLE -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border">

            <div class="flex justify-between items-center mb-6">
                <h4 class="text-lg font-bold text-slate-800">Mon dernier véhicule</h4>
                <a href="vehicules.php" class="text-blue-600 text-sm font-bold">+ Gérer</a>
            </div>

            <?php if ($vehicule): ?>

                <div class="flex items-center p-4 border rounded-xl hover:bg-slate-50">

                    <!-- IMAGE -->
                    <?php if (!empty($vehicule['photo'])): ?>
                        <img src="../<?= $vehicule['photo'] ?>" class="w-16 h-16 rounded mr-4 object-cover">
                    <?php else: ?>
                        <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded mr-4">
                            <i class="fas fa-car text-gray-500"></i>
                        </div>
                    <?php endif; ?>

                    <div>
                        <p class="font-black text-slate-800">
                            <?= htmlspecialchars($vehicule['marque']) ?>
                            <?= htmlspecialchars($vehicule['modele']) ?>
                        </p>

                        <p class="text-sm text-slate-500">
                            <?= htmlspecialchars($vehicule['immatriculation']) ?>
                        </p>
                    </div>

                </div>

            <?php else: ?>

                <p class="text-gray-500">Aucun véhicule enregistré</p>

            <?php endif; ?>

        </div>

        <!-- INFO PANEL -->
        <div class="bg-slate-900 rounded-2xl p-6 text-white">

            <h4 class="text-lg font-bold mb-6">
                Zone de couverture
            </h4>

            <div class="text-center">
                <p class="text-3xl font-black text-blue-400">12</p>
                <p class="text-xs text-slate-400 mt-1">
                    Mécanos disponibles
                </p>
            </div>

        </div>

    </div>

</main>

</body>
</html>