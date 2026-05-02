<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }
include("../config/db.php");

$meca_id = $_SESSION['user_id'];

// Historique (Terminées)
$stmt = $pdo->prepare("
    SELECT i.*, v.marque, v.modele, c.nom as client_nom 
    FROM intervention i 
    LEFT JOIN vehicule v ON i.id_vehicule = v.id_vehicule 
    LEFT JOIN user c ON i.id_client = c.id_user 
    WHERE i.id_mecanicien = ? AND i.statut = 'terminé' 
    ORDER BY i.date_intervention DESC, i.date_demande DESC
");
$stmt->execute([$meca_id]);
$historiques = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Mécanicien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
        <h1 class="text-2xl font-bold mb-10 text-blue-400 italic">auto<span class="text-red-500">SOS</span></h1>
        <ul class="space-y-4">
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="dashboard.php"><i class="fas fa-home mr-3"></i> Vue d'ensemble</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="interventions.php"><i class="fas fa-tools mr-3"></i> Interventions</a></li>
            <li class="bg-blue-600 p-3 rounded-lg"><a href="historique.php"><i class="fas fa-history mr-3"></i> Historique</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="revenus.php"><i class="fas fa-wallet mr-3"></i> Revenus</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition mt-20"><a href="../auth/logout.php" class="text-red-400"><i class="fas fa-sign-out-alt mr-3"></i> Déconnexion</a></li>
        </ul>
    </nav>
    <main class="md:ml-64 p-4 md:p-8">
        <header class="mb-8 pl-12 md:pl-0 pt-4 md:pt-0">
            <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-history text-gray-500 mr-2"></i> Mon Historique d'Interventions</h2>
        </header>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="divide-y">
                <?php if (count($historiques) > 0): ?>
                    <?php foreach ($historiques as $inv): ?>
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition border-l-4 border-green-500">
                        <div class="flex items-center space-x-4">
                            <div class="bg-green-100 p-3 rounded-lg flex-shrink-0"><i class="fas fa-check-circle text-green-600 text-xl"></i></div>
                            <div>
                                <p class="font-bold">
                                    <?= htmlspecialchars($inv['type_intervention']) ?> 
                                    - <?= htmlspecialchars($inv['client_nom']) ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i> <?= htmlspecialchars($inv['date_intervention'] ?? $inv['date_demande']) ?>
                                    <i class="fas fa-map-marker-alt ml-3 mr-1"></i> <?= htmlspecialchars($inv['localisation']) ?>
                                </p>
                            </div>
                        </div>
                        <a href="details_intervention.php?id=<?= $inv['id_intervention'] ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold ml-2">Détails</a>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-10 text-center text-gray-500"><i class="fas fa-box-open text-3xl mb-4 opacity-50 block"></i>Vous n'avez pas encore d'historique d'interventions terminées.</div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
