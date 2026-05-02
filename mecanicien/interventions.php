<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$meca_id = $_SESSION['user_id'];

// Interventions en attente
$stmt_interv = $pdo->prepare("
    SELECT i.*, v.marque, v.modele
    FROM intervention i
    LEFT JOIN vehicule v ON i.id_vehicule = v.id_vehicule
    WHERE i.statut = 'en attente'
    ORDER BY i.date_demande DESC
");
$stmt_interv->execute();
$interventions = $stmt_interv->fetchAll();

// Interventions en cours du mecanicien
$stmt_encours = $pdo->prepare("
    SELECT i.*, v.marque, v.modele, c.telephone as client_tel, c.nom as client_nom
    FROM intervention i
    LEFT JOIN vehicule v ON i.id_vehicule = v.id_vehicule
    LEFT JOIN user c ON i.id_client = c.id_user
    WHERE i.statut = 'en cours' AND i.id_mecanicien = ?
    ORDER BY i.date_demande DESC
");
$stmt_encours->execute([$meca_id]);
$interventions_en_cours = $stmt_encours->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interventions - autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
        <h1 class="text-2xl font-bold mb-10 text-blue-400 italic">auto<span class="text-red-500">SOS</span></h1>
        <ul class="space-y-4">
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="dashboard.php"><i class="fas fa-home mr-3"></i> Vue d'ensemble</a></li>
            <li class="bg-blue-600 p-3 rounded-lg"><a href="interventions.php"><i class="fas fa-tools mr-3"></i> Interventions</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="historique.php"><i class="fas fa-history mr-3"></i> Historique</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="revenus.php"><i class="fas fa-wallet mr-3"></i> Revenus</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition mt-20"><a href="../auth/logout.php" class="text-red-400"><i class="fas fa-sign-out-alt mr-3"></i> Déconnexion</a></li>
        </ul>
    </nav>

    <main id="refresh-zone" class="md:ml-64 p-4 md:p-8">

        <header class="mb-8 pl-12 md:pl-0 pt-4 md:pt-0">
            <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-tools text-gray-500 mr-2"></i> Tableau des interventions</h2>
        </header>

        <!-- MISSIONS EN COURS -->
        <?php if (count($interventions_en_cours) > 0): ?>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 border-l-4 border-blue-500">
            <div class="p-5 border-b flex justify-between items-center bg-blue-50">
                <h3 class="font-bold text-blue-800"><i class="fas fa-tools mr-2"></i>Mes interventions en cours</h3>
            </div>
            <div class="divide-y">
                <?php foreach ($interventions_en_cours as $inv): ?>
                <div class="p-4 flex flex-col md:flex-row md:items-center justify-between hover:bg-gray-50 transition gap-4">
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-100 p-3 rounded-lg flex-shrink-0"><i class="fas fa-wrench text-blue-600 text-xl"></i></div>
                        <div>
                            <p class="font-bold">
                                <?= htmlspecialchars($inv['type_intervention']) ?> 
                                <?= !empty($inv['marque']) ? ' - ' . htmlspecialchars($inv['marque'] . ' ' . $inv['modele']) : '' ?>
                            </p>
                            <p class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($inv['localisation']) ?></p>
                            <p class="text-xs text-blue-600 font-bold mt-1"><i class="fas fa-phone"></i> <?= htmlspecialchars($inv['client_tel'] ?? '') ?> (<?= htmlspecialchars($inv['client_nom'] ?? 'Client') ?>)</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <form action="terminer_intervention.php" method="POST" class="inline m-0 p-0">
                            <input type="hidden" name="id_intervention" value="<?= $inv['id_intervention'] ?>">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 whitespace-nowrap">✔ Terminer</button>
                        </form>
                        <a href="details_intervention.php?id=<?= $inv['id_intervention'] ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold flex items-center justify-center">Détails</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="p-5 border-b bg-red-50 flex justify-between items-center">
                <h3 class="font-bold text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i>Demandes d'urgence à proximité</h3>
                <span class="text-xs font-bold text-red-600 animate-pulse">DIRECT</span>
            </div>
            <div class="divide-y">
                <?php if (count($interventions) > 0): ?>
                    <?php foreach ($interventions as $inv): ?>
                    <div class="p-4 flex flex-col md:flex-row md:items-center justify-between hover:bg-gray-50 transition gap-4">
                        <div class="flex items-start space-x-4">
                            <div class="bg-gray-100 p-3 rounded-lg flex-shrink-0"><i class="fas fa-car text-gray-600 text-xl"></i></div>
                            <div>
                                <p class="font-bold">
                                    <?= htmlspecialchars($inv['type_intervention'] ?? 'Intervention') ?> 
                                    <?= !empty($inv['marque']) ? ' - ' . htmlspecialchars($inv['marque'] . ' ' . $inv['modele']) : '' ?>
                                </p>
                                <p class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($inv['localisation']) ?></p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <form action="accepter_intervention.php" method="POST" class="inline m-0 p-0">
                                <input type="hidden" name="id_intervention" value="<?= $inv['id_intervention'] ?>">
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-green-600 whitespace-nowrap">Accepter</button>
                            </form>
                            <a href="details_intervention.php?id=<?= $inv['id_intervention'] ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold flex items-center justify-center">Détails</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-10 text-center text-gray-500 italic"><i class="fas fa-shield-alt text-3xl mb-4 opacity-50 block"></i>Aucune intervention en attente.</div>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <!-- Mobile menu -->
    <div class="fixed bottom-0 left-0 w-full bg-white border-t flex justify-around p-3 md:hidden">
        <a href="dashboard.php" class="text-gray-400 hover:text-blue-600"><i class="fas fa-home text-xl"></i></a>
        <a href="interventions.php" class="text-blue-600"><i class="fas fa-tools text-xl"></i></a>
        <button class="text-gray-400 cursor-not-allowed"><i class="fas fa-plus-circle text-3xl text-gray-300 -mt-8 bg-white rounded-full p-2 border shadow-sm"></i></button>
        <a href="historique.php" class="text-gray-400 hover:text-blue-600"><i class="fas fa-history text-xl"></i></a>
        <a href="revenus.php" class="text-gray-400 hover:text-blue-600"><i class="fas fa-wallet text-xl"></i></a>
    </div>

    <script>
        $(document).ready(function(){
            setInterval(function(){
                $("#refresh-zone").load(location.href + " #refresh-zone > *");
            }, 5000);
        });
    </script>

</body>
</html>
