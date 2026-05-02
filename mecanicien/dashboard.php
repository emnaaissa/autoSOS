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
$stmt_interv = $pdo->query("SELECT COUNT(*) FROM intervention WHERE statut = 'en attente'");
$interventions_en_attente = $stmt_interv->fetchColumn();

// Note moyenne
$stmt_note = $pdo->prepare("SELECT AVG(a.note) FROM avis a JOIN intervention i ON a.id_intervention = i.id_intervention WHERE i.id_mecanicien = ?");
$stmt_note->execute([$meca_id]);
$calc_note = $stmt_note->fetchColumn();
$note_moyenne = $calc_note ? number_format($calc_note, 1) . '/5' : '0/5';

// Alertes urgences (aperçu)
$stmt_urgences = $pdo->query("SELECT * FROM intervention WHERE statut = 'en attente' ORDER BY date_demande DESC LIMIT 3");
$urgences = $stmt_urgences->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mécanicien - autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
        <h1 class="text-2xl font-bold mb-10 text-blue-400 italic">auto<span class="text-red-500">SOS</span></h1>
        <ul class="space-y-4">
            <li class="bg-blue-600 p-3 rounded-lg"><a href="dashboard.php"><i class="fas fa-home mr-3"></i> Vue d'ensemble</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="interventions.php"><i class="fas fa-tools mr-3"></i> Interventions</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="historique.php"><i class="fas fa-history mr-3"></i> Historique</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="revenus.php"><i class="fas fa-wallet mr-3"></i> Revenus</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition mt-20"><a href="../auth/logout.php" class="text-red-400"><i class="fas fa-sign-out-alt mr-3"></i> Déconnexion</a></li>
        </ul>
    </nav>

    <main id="refresh-zone" class="md:ml-64 p-4 md:p-8">

        <header class="flex justify-between items-center mb-8 pl-12 md:pl-0 pt-4 md:pt-0">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Bonjour, <?= htmlspecialchars($meca['prenom']) ?>! 👋</h2>
                <p class="text-gray-500">Voici un résumé de votre activité.</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <i class="fas fa-bell text-gray-600 text-xl cursor-pointer hover:text-blue-600"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full animate-pulse"><?= $interventions_en_attente ?></span>
                </div>
                <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-white font-bold border-2 border-indigo-500">
                    <?= strtoupper(substr($meca['prenom'], 0, 1) . substr($meca['nom'], 0, 1)) ?>
                </div>
            </div>
        </header>

        <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-2 shadow-[0_0_8px_rgba(34,197,94,0.8)]"></div>
                <span class="text-gray-700 font-bold">Statut: En ligne</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase">Missions du jour</p>
                        <h3 class="text-2xl font-bold"><?= $missions_du_jour ?></h3>
                    </div>
                    <i class="fas fa-check-circle text-blue-200 text-3xl"></i>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase">Urgences en attente</p>
                        <h3 class="text-2xl font-bold text-red-600"><?= $interventions_en_attente ?></h3>
                    </div>
                    <i class="fas fa-exclamation-triangle text-red-200 text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase">Note moyenne</p>
                        <h3 class="text-2xl font-bold"><?= $note_moyenne ?></h3>
                    </div>
                    <i class="fas fa-star text-yellow-200 text-3xl"></i>
                </div>
            </div>
        </div>

        <?php if (count($urgences) > 0): ?>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 animate-fade-in border border-red-100">
            <div class="p-5 border-b bg-red-50 flex justify-between items-center">
                <h3 class="font-bold text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i>Demandes d'urgence récentes</h3>
                <a href="interventions.php" class="text-xs font-bold text-red-600 bg-red-100 px-3 py-1 rounded-full hover:bg-red-200 transition">VOIR TOUT</a>
            </div>
            <div class="divide-y">
                <?php foreach ($urgences as $urg): ?>
                <a href="interventions.php" class="p-4 flex items-center justify-between hover:bg-gray-50 transition cursor-pointer block border-l-4 border-transparent hover:border-red-500 group">
                    <div class="flex items-center space-x-4">
                        <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center text-red-500 group-hover:scale-110 transition"><i class="fas fa-bell text-xl"></i></div>
                        <div>
                            <p class="font-bold text-gray-800"><?= htmlspecialchars($urg['type_intervention']) ?></p>
                            <p class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($urg['localisation']) ?></p>
                        </div>
                    </div>
                    <div class="text-red-300 group-hover:text-red-500 transition"><i class="fas fa-chevron-right text-xl"></i></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </main>

    <div class="fixed bottom-0 left-0 w-full bg-white border-t flex justify-around p-3 md:hidden z-50">
        <a href="dashboard.php" class="text-blue-600"><i class="fas fa-home text-xl"></i></a>
        <a href="interventions.php" class="text-gray-400 hover:text-blue-600"><i class="fas fa-tools text-xl"></i></a>
        <button class="text-gray-400"><i class="fas fa-plus-circle text-3xl text-gray-300 -mt-8 bg-white rounded-full p-2 border shadow-lg cursor-not-allowed"></i></button>
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