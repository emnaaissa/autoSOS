<!-- dashboard.php -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Sidebar de Navigation -->
    <nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
        <div class="mb-10 text-center">
            <h1 class="text-2xl font-bold text-blue-400 italic">auto<span class="text-red-500">SOS</span></h1>
            <span class="text-[10px] bg-blue-900 text-blue-300 px-2 py-1 rounded uppercase tracking-widest font-bold">Administration</span>
        </div>
        <ul class="space-y-2">
            <li class="bg-blue-600 p-3 rounded-lg">
                <a href="dashboard.php" class="flex items-center"><i class="fas fa-chart-line mr-3"></i> Statistiques</a>
            </li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition">
                <a href="gestion_utilisateurs.php" class="flex items-center"><i class="fas fa-users mr-3"></i> Utilisateurs</a>
            </li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition">
                <a href="gestion_mecaniciens.php" class="flex items-center"><i class="fas fa-wrench mr-3"></i> Mécaniciens</a>
            </li>

            <li class="hover:bg-slate-800 p-3 rounded-lg transition">
                <a href="../auth/logout.php"><i class="fas fa-power-off mr-3"></i> Déconnexion</a>
            </li>
        </ul>
    </nav>

    <!-- Contenu Principal -->
    <main class="md:ml-64 p-8">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Supervision Système</h2>
                <p class="text-slate-500 italic text-sm">État global de la plateforme en temps réel</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-400 uppercase">Admin Connecté</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin&background=1e293b&color=fff" class="h-10 w-10 rounded-full border-2 border-slate-900">
            </div>
        </header>

        <!-- Cartes de Statistiques Rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-500">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Clients</p>
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-3xl font-black text-slate-800" id="stat-users">--</h3>
                    <i class="fas fa-user-group text-blue-200 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-orange-500">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Mécaniciens</p>
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-3xl font-black text-slate-800" id="stat-mecas">--</h3>
                    <i class="fas fa-screwdriver-wrench text-orange-200 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-green-500">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Revenus (DT)</p>
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-3xl font-black text-slate-800" id="stat-revenue">--</h3>
                    <i class="fas fa-vault text-green-200 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-red-500">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">SOS Finis</p>
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-3xl font-black text-slate-800" id="stat-interventions">--</h3>
                    <i class="fas fa-check-circle text-red-200 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Section Logs d'Activités -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
                <h4 class="text-lg font-black text-slate-800 uppercase italic mb-4">Dernières Interventions</h4>
                <div class="overflow-hidden">
                    <p class="text-gray-500 text-sm">Les détails complets sont accessibles via les menus Utilisateurs et Mécaniciens.</p>
                    <!-- Espace pour un graphique ou une liste simplifiée -->
                    <div class="mt-6 h-48 bg-slate-50 rounded-xl border-dashed border-2 border-slate-200 flex items-center justify-center">
                        <span class="text-slate-400 text-sm font-medium italic">Graphique de tendance bientôt disponible</span>
                    </div>
                </div>
            </div>

            <!-- Terminal de Logs -->
            <div class="bg-slate-900 rounded-2xl shadow-xl p-6 text-white">
                <h4 class="text-lg font-bold mb-6 flex items-center">
                    <i class="fas fa-terminal mr-3 text-blue-400"></i> Logs Système
                </h4>
                <div class="space-y-4">
                    <div class="border-l-2 border-blue-500 pl-4 py-1">
                        <p class="text-xs text-slate-400">Intervention #7</p>
                        <p class="text-sm font-bold italic">Terminée avec succès</p>
                    </div>
                    <div class="border-l-2 border-green-500 pl-4 py-1">
                        <p class="text-xs text-slate-400">Paiement</p>
                        <p class="text-sm font-bold italic">+40.00 DT reçus</p>
                    </div>
                </div>
                <button class="w-full mt-8 py-3 bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-700 transition">Exporter Rapport PDF</button>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Fonction pour charger les chiffres dynamiquement
        function loadStats() {
            $.get('api/get_stats.php', function(data) {
                $('#stat-users').text(data.total_clients);
                $('#stat-mecas').text(data.total_mecas);
                $('#stat-revenue').text(data.revenue + ' DT');
                $('#stat-interventions').text(data.interventions);
            }, 'json');
        }

        $(document).ready(loadStats);
    </script>
</body>

</html>