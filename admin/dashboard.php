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

    <!-- Sidebar -->
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
                <a href="../auth/logout.php" class="flex items-center"><i class="fas fa-power-off mr-3"></i> Déconnexion</a>
            </li>
        </ul>
    </nav>

    <!-- Main -->
    <main class="md:ml-64 p-8">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Supervision Système</h2>
                <p class="text-slate-500 italic text-sm">État global de la plateforme en temps réel</p>
            </div>
            <div class="flex items-center space-x-4">
                <p class="text-xs font-bold text-slate-400 uppercase hidden sm:block">Admin Connecté</p>
                <img src="https://ui-avatars.com/api/?name=Admin&background=1e293b&color=fff" class="h-10 w-10 rounded-full border-2 border-slate-900">
            </div>
        </header>

        <!-- ── ROW 1 : Clients / Mecas / Commission / SOS Total ── -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

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
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Commission (12%)</p>
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-3xl font-black text-slate-800" id="stat-commission">--</h3>
                    <i class="fas fa-vault text-green-200 text-2xl"></i>
                </div>
                <p class="text-[10px] text-slate-400 mt-2">sur paiements reçus</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-slate-400">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total SOS</p>
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-3xl font-black text-slate-800" id="stat-total-int">--</h3>
                    <i class="fas fa-bolt text-slate-200 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- ── ROW 2 : Intervention breakdown ── -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-green-400 flex items-center gap-5">
                <div class="bg-green-100 h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Terminées</p>
                    <h3 class="text-3xl font-black text-slate-800" id="stat-done">--</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-400 flex items-center gap-5">
                <div class="bg-blue-100 h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-spinner text-blue-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">En cours</p>
                    <h3 class="text-3xl font-black text-slate-800" id="stat-pending">--</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-yellow-400 flex items-center gap-5">
                <div class="bg-yellow-100 h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">En attente</p>
                    <h3 class="text-3xl font-black text-slate-800" id="stat-unpicked">--</h3>
                    <p class="text-[10px] text-slate-400">non attribuées</p>
                </div>
            </div>
        </div>

        <!-- ── ROW 3 : Performance + Logs ── -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Performance timings -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
                <h4 class="text-lg font-black text-slate-800 uppercase italic mb-6">Performance des Interventions</h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Pickup speed -->
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-purple-100 h-9 w-9 rounded-lg flex items-center justify-center">
                                <i class="fas fa-stopwatch text-purple-500"></i>
                            </div>
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Délai de prise en charge</p>
                        </div>
                        <p class="text-3xl font-black text-slate-800" id="stat-pickup">--</p>
                        <p class="text-xs text-slate-400 mt-1">Moyenne · demande → acceptation</p>
                    </div>

                    <!-- Handling time -->
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-indigo-100 h-9 w-9 rounded-lg flex items-center justify-center">
                                <i class="fas fa-gauge-high text-indigo-500"></i>
                            </div>
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Durée de traitement</p>
                        </div>
                        <p class="text-3xl font-black text-slate-800" id="stat-handling">--</p>
                        <p class="text-xs text-slate-400 mt-1">Moyenne · acceptation → fin</p>
                    </div>
                </div>

                <!-- Mini funnel bar -->
                <div class="mt-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Répartition des SOS</p>
                    <div class="flex rounded-full overflow-hidden h-4 bg-slate-100" id="funnel-bar">
                        <!-- filled dynamically -->
                    </div>
                    <div class="flex gap-4 mt-2 text-[10px] font-bold uppercase text-slate-500">
                        <span class="flex items-center gap-1"><span class="inline-block w-2 h-2 rounded-full bg-green-400"></span>Terminées</span>
                        <span class="flex items-center gap-1"><span class="inline-block w-2 h-2 rounded-full bg-blue-400"></span>En cours</span>
                        <span class="flex items-center gap-1"><span class="inline-block w-2 h-2 rounded-full bg-yellow-400"></span>En attente</span>
                    </div>
                </div>
            </div>

            <!-- Terminal de Logs -->
            <div class="bg-slate-900 rounded-2xl shadow-xl p-6 text-white flex flex-col justify-between">
                <div>
                    <h4 class="text-lg font-bold mb-6 flex items-center">
                        <i class="fas fa-terminal mr-3 text-blue-400"></i> Logs Système
                    </h4>
                    <div class="space-y-4" id="log-entries">
                        <div class="border-l-2 border-slate-700 pl-4 py-1">
                            <p class="text-xs text-slate-500 italic">Chargement…</p>
                        </div>
                    </div>
                </div>
                <button onclick="loadStats()" class="w-full mt-8 py-3 bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-700 transition">
                    <i class="fas fa-rotate mr-2"></i>Actualiser
                </button>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadStats() {
            $.get('api/get_stats.php', function(data) {
                if (data.error) { console.error(data.error); return; }

                $('#stat-users').text(data.total_clients);
                $('#stat-mecas').text(data.total_mecas);
                $('#stat-commission').text(data.commission + ' DT');
                $('#stat-total-int').text(data.total_int);
                $('#stat-done').text(data.done_int);
                $('#stat-pending').text(data.pending_int);
                $('#stat-unpicked').text(data.unpicked_int);
                $('#stat-pickup').text(data.avg_pickup);
                $('#stat-handling').text(data.avg_handling);

                // Funnel bar
                const total = data.total_int || 1;
                const donePct    = (data.done_int    / total * 100).toFixed(1);
                const pendPct    = (data.pending_int / total * 100).toFixed(1);
                const unpickPct  = (data.unpicked_int/ total * 100).toFixed(1);
                const restPct    = Math.max(0, 100 - donePct - pendPct - unpickPct).toFixed(1);

                $('#funnel-bar').html(`
                    <div class="bg-green-400 transition-all" style="width:${donePct}%" title="Terminées: ${data.done_int}"></div>
                    <div class="bg-blue-400 transition-all"  style="width:${pendPct}%"  title="En cours: ${data.pending_int}"></div>
                    <div class="bg-yellow-400 transition-all" style="width:${unpickPct}%" title="En attente: ${data.unpicked_int}"></div>
                    <div class="bg-slate-200 transition-all" style="width:${restPct}%"></div>
                `);

                // Logs
                const logs = [
                    { color: 'border-green-500', label: 'Interventions terminées', value: data.done_int },
                    { color: 'border-blue-500',  label: 'En cours',                value: data.pending_int },
                    { color: 'border-yellow-400',label: 'En attente',              value: data.unpicked_int },
                    { color: 'border-green-400', label: 'Commission perçue',        value: data.commission + ' DT' },
                    { color: 'border-purple-400',label: 'Délai prise en charge',    value: data.avg_pickup },
                    { color: 'border-indigo-400',label: 'Durée traitement moy.',    value: data.avg_handling },
                ];
                $('#log-entries').html(logs.map(l => `
                    <div class="border-l-2 ${l.color} pl-4 py-1">
                        <p class="text-xs text-slate-400">${l.label}</p>
                        <p class="text-sm font-bold italic">${l.value}</p>
                    </div>
                `).join(''));

            }, 'json');
        }

        $(document).ready(loadStats);
    </script>
</body>

</html>