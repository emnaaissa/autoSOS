<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }
include("../config/db.php");

$meca_id = $_SESSION['user_id'];

// Calcul revenus
$stmt = $pdo->prepare("
    SELECT SUM(p.montant) as total, COUNT(i.id_intervention) as nb_missions 
    FROM intervention i 
    LEFT JOIN paiement p ON i.id_intervention = p.id_intervention 
    WHERE i.id_mecanicien = ? AND i.statut = 'terminé'
");
$stmt->execute([$meca_id]);
$stats = $stmt->fetch();
$total_revenue = $stats['total'] ?? 0;
$total_missions = $stats['nb_missions'] ?? 0;

$stmtList = $pdo->prepare("
    SELECT i.date_intervention, i.type_intervention, p.montant, p.statut as p_statut 
    FROM intervention i 
    LEFT JOIN paiement p ON i.id_intervention = p.id_intervention 
    WHERE i.id_mecanicien = ? AND i.statut = 'terminé'
    ORDER BY i.date_demande DESC
");
$stmtList->execute([$meca_id]);
$revenus = $stmtList->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Revenus Mécanicien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
        <h1 class="text-2xl font-bold mb-10 text-blue-400 italic">auto<span class="text-red-500">SOS</span></h1>
        <ul class="space-y-4">
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="dashboard.php"><i class="fas fa-home mr-3"></i> Vue d'ensemble</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="dashboard.php"><i class="fas fa-tools mr-3"></i> Interventions</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="historique.php"><i class="fas fa-history mr-3"></i> Historique</a></li>
            <li class="bg-blue-600 p-3 rounded-lg"><a href="revenus.php"><i class="fas fa-wallet mr-3"></i> Revenus</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition mt-20"><a href="../auth/logout.php" class="text-red-400"><i class="fas fa-sign-out-alt mr-3"></i> Déconnexion</a></li>
        </ul>
    </nav>
    <main class="md:ml-64 p-4 md:p-8">
        <header class="mb-8 pl-12 md:pl-0 pt-4 md:pt-0">
            <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-wallet text-gray-500 mr-2"></i> Mes Revenus</h2>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500 uppercase font-bold">Gains Totaux</p><h3 class="text-3xl font-black text-green-600"><?= number_format($total_revenue, 2) ?> DT</h3></div>
                    <i class="fas fa-hand-holding-usd text-green-100 text-5xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500 uppercase font-bold">Missions Réalisées</p><h3 class="text-3xl font-black text-blue-600"><?= $total_missions ?></h3></div>
                    <i class="fas fa-check-double text-blue-100 text-5xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border">
            <div class="p-5 border-b bg-gray-50"><h3 class="font-bold text-gray-700"><i class="fas fa-list-alt mr-2 text-gray-400"></i>Détail des rémunérations</h3></div>
            <div class="divide-y">
                <?php if (count($revenus) > 0): ?>
                    <?php foreach ($revenus as $rev): ?>
                    <div class="p-5 flex items-center justify-between hover:bg-gray-50 transition">
                        <div>
                            <p class="font-bold text-gray-800"><?= htmlspecialchars($rev['type_intervention']) ?></p>
                            <p class="text-sm text-gray-500"><i class="far fa-calendar-alt mr-1"></i><?= htmlspecialchars($rev['date_intervention'] ?? '') ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg <?= empty($rev['montant']) ? 'text-gray-400' : 'text-green-600' ?>">
                                <?= empty($rev['montant']) ? 'En attente ⏳' : number_format($rev['montant'], 2) . ' DT' ?>
                            </p>
                            <?php if(!empty($rev['p_statut'])): ?>
                                <span class="text-xs px-2 py-1 <?= strtolower($rev['p_statut']) == 'payé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?> rounded font-bold uppercase mt-1 inline-block">
                                    <?= htmlspecialchars($rev['p_statut']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="p-10 text-center text-gray-500 italic">Aucun revenu généré pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>

    </main>
</body>
</html>
