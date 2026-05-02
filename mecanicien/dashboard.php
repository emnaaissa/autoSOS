<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$meca_id = $_SESSION['user_id'];

// Infos du mécanicien
$stmt_meca = $pdo->prepare("SELECT u.nom, u.prenom FROM user u JOIN mecanicien m ON u.id_user = m.id_user WHERE u.id_user = ?");
$stmt_meca->execute([$meca_id]);
$meca = $stmt_meca->fetch() ?: ['nom' => 'Mécano', 'prenom' => ''];

// Missions du jour
$stmt_missions = $pdo->prepare("SELECT COUNT(*) FROM intervention WHERE id_mecanicien = ? AND statut IN ('en cours', 'Terminée')");
$stmt_missions->execute([$meca_id]);
$missions_du_jour = $stmt_missions->fetchColumn();

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

// NEW: Interventions en cours du mecanicien
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
    <title>Dashboard Mécanicien - autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 min-h-screen">

    <nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
        <h1 class="text-2xl font-bold mb-10 text-blue-400 italic">auto<span class="text-red-500">SOS</span></h1>
        <ul class="space-y-4">
            <li class="bg-blue-600 p-3 rounded-lg"><a href="#"><i class="fas fa-home mr-3"></i> Vue d'ensemble</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="#"><i class="fas fa-tools mr-3"></i> Interventions</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="#"><i class="fas fa-history mr-3"></i> Historique</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="#"><i class="fas fa-wallet mr-3"></i> Revenus</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition mt-20"><a href="../auth/logout.php" class="text-red-400"><i class="fas fa-sign-out-alt mr-3"></i> Déconnexion</a></li>
        </ul>
    </nav>

    <main class="md:ml-64 p-4 md:p-8">

        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Bonjour, <?= htmlspecialchars($meca['prenom'] . ' ' . $meca['nom']) ?> !</h2>
                <p class="text-gray-500 text-sm">Vous êtes actuellement <span class="text-green-500 font-semibold italic">En ligne</span></p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="relative p-2 bg-white rounded-full shadow-sm">
                    <i class="fas fa-bell text-gray-600"></i>
                    <span class="absolute top-0 right-0 h-3 w-3 bg-red-500 border-2 border-white rounded-full"></span>
                </button>
                <img src="https://ui-avatars.com/api/?name=Meca+SOS&background=0D8ABC&color=fff" class="h-10 w-10 rounded-full border-2 border-blue-500">
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase">Missions du jour</p>
                        <h3 class="text-2xl font-bold"><?= htmlspecialchars($missions_du_jour) ?></h3>
                    </div>
                    <i class="fas fa-calendar-check text-blue-200 text-3xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase">Gains (Semaine)</p>
                        <h3 class="text-2xl font-bold">450 DT</h3>
                    </div>
                    <i class="fas fa-coins text-green-200 text-3xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase">Note moyenne</p>
                        <h3 class="text-2xl font-bold">4.8/5</h3>
                    </div>
                    <i class="fas fa-star text-yellow-200 text-3xl"></i>
                </div>
            </div>
        </div>

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
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-4">
                            <div class="bg-gray-100 p-3 rounded-lg"><i class="fas fa-car text-gray-600 text-xl"></i></div>
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
                    <div class="p-4 text-center text-gray-500">Aucune demande en attente.</div>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <div class="fixed bottom-0 left-0 w-full bg-white border-t flex justify-around p-3 md:hidden">
        <button class="text-blue-600"><i class="fas fa-home text-xl"></i></button>
        <button class="text-gray-400"><i class="fas fa-tools text-xl"></i></button>
        <button class="text-gray-400"><i class="fas fa-plus-circle text-3xl text-blue-600 -mt-8 bg-white rounded-full p-2 border shadow-lg"></i></button>
        <button class="text-gray-400"><i class="fas fa-history text-xl"></i></button>
        <button class="text-gray-400"><i class="fas fa-user text-xl"></i></button>
    </div>

</body>

</html>