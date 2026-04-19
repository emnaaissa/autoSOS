<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">

    <nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
        <div class="mb-10 text-center">
            <h1 class="text-2xl font-bold text-blue-400 italic">auto<span class="text-red-500">SOS</span></h1>
            <span class="text-[10px] bg-blue-900 text-blue-300 px-2 py-1 rounded uppercase tracking-widest font-bold">Panel Admin</span>
        </div>
        <ul class="space-y-2">
            <li class="bg-blue-600 p-3 rounded-lg"><a href="#" class="flex items-center"><i class="fas fa-chart-line mr-3"></i> Statistiques</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="#" class="flex items-center"><i class="fas fa-users mr-3"></i> Utilisateurs</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="#" class="flex items-center"><i class="fas fa-wrench mr-3"></i> Mécaniciens</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="#" class="flex items-center"><i class="fas fa-hand-holding-dollar mr-3"></i> Transactions</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="#" class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> Signalements</a></li>
            <li class="absolute bottom-10 left-6 hover:text-red-400 transition"><a href="../auth/logout.php"><i class="fas fa-power-off mr-3"></i> Déconnexion</a></li>
        </ul>
    </nav>

    <main class="md:ml-64 p-4 md:p-8">

        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Supervision Système</h2>
                <p class="text-slate-500 italic text-sm">État global de la plateforme en temps réel</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-400 uppercase">Admin Principal</p>
                    <p class="text-sm font-black text-slate-700">Emna Aissa</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin&background=1e293b&color=fff" class="h-10 w-10 rounded-full border-2 border-slate-900">
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-500">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Utilisateurs</p>
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-3xl font-black text-slate-800">1,284</h3>
                    <i class="fas fa-user-group text-blue-200 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-orange-500">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">SOS Actifs</p>
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-3xl font-black text-slate-800">12</h3>
                    <i class="fas fa-satellite-dish text-orange-200 text-2xl animate-pulse"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-green-500">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">CA Total</p>
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-3xl font-black text-slate-800">8.4k <span class="text-sm">DT</span></h3>
                    <i class="fas fa-vault text-green-200 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-red-500">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Alertes</p>
                <div class="flex items-center justify-between mt-2">
                    <h3 class="text-3xl font-black text-red-600">02</h3>
                    <i class="fas fa-triangle-exclamation text-red-200 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center bg-slate-50">
                    <h4 class="text-lg font-black text-slate-800 uppercase italic">Gestion des Comptes Utilisateurs</h4>
                    <div class="flex space-x-2">
                        <input type="text" id="searchUser" placeholder="Rechercher..." class="text-sm border rounded-lg px-3 py-1 outline-none focus:ring-2 focus:ring-blue-500">
                        <button onclick="loadUsers()" class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-900 text-white text-[10px] uppercase font-bold">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Utilisateur</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Rôle</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody" class="divide-y text-sm">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-slate-900 rounded-2xl shadow-xl p-6 text-white">
                <h4 class="text-lg font-bold mb-6 flex items-center">
                    <i class="fas fa-terminal mr-3 text-blue-400"></i> Logs Activités
                </h4>
                <div class="space-y-4">
                    <div class="border-l-2 border-blue-500 pl-4 py-1">
                        <p class="text-xs text-slate-400">Il y a 2 min</p>
                        <p class="text-sm font-bold italic">Nouvel SOS lancé à Ariana</p>
                    </div>
                    <div class="border-l-2 border-green-500 pl-4 py-1">
                        <p class="text-xs text-slate-400">Il y a 15 min</p>
                        <p class="text-sm font-bold italic">Paiement reçu : 45.000 DT</p>
                    </div>
                    <div class="border-l-2 border-red-500 pl-4 py-1">
                        <p class="text-xs text-slate-400">Il y a 1h</p>
                        <p class="text-sm font-bold italic">Signalement : Mécano #42</p>
                    </div>
                </div>
                <button class="w-full mt-8 py-3 bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-700 transition">Télécharger le rapport</button>
            </div>

        </div>
    </main>

    <div class="fixed bottom-0 left-0 w-full bg-white border-t flex justify-around p-3 md:hidden z-50">
        <button class="text-blue-600"><i class="fas fa-chart-line text-xl"></i></button>
        <button class="text-slate-400"><i class="fas fa-users text-xl"></i></button>
        <button class="text-slate-400"><i class="fas fa-hand-holding-dollar text-xl"></i></button>
        <button class="text-slate-400"><i class="fas fa-exclamation-circle text-xl"></i></button>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        window.loadUsers = function() {
            $.ajax({
                url: 'get_users.php',
                type: 'GET',
                dataType: 'json',

                success: function(data) {
                    console.log("Données reçues :", data);

                    if (data.error) {
                        $('#userTableBody').html(`<tr><td colspan="5" class="p-4 text-center text-red-500 font-bold">Erreur SQL : ${data.error}</td></tr>`);
                        return;
                    }

                    if (!Array.isArray(data)) {
                        console.error("Format reçu invalide :", data);
                        $('#userTableBody').html(`<tr><td colspan="5" class="p-4 text-center text-orange-500">Réponse serveur invalide (voir console)</td></tr>`);
                        return;
                    }

                    let rows = '';
                    if (data.length === 0) {
                        rows = '<tr><td colspan="5" class="p-4 text-center text-slate-500 italic">Aucun utilisateur trouvé dans la base.</td></tr>';
                    } else {
                        data.forEach(u => {
                            rows += `
                    <tr class="border-b hover:bg-slate-50 transition" id="user-${u.id}">
                        <td class="px-6 py-4 font-mono text-gray-400 text-xs">#${u.id}</td>
                        <td class="px-6 py-4 font-bold text-slate-700">${u.nom} ${u.prenom}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">${u.email}</td>
                        <td class="px-6 py-4"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-[10px] font-black uppercase italic">Client</span></td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="deleteUser(${u.id})" class="text-red-400 hover:text-red-600 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                        });
                    }
                    $('#userTableBody').html(rows);
                },

                error: function(xhr) {
                    console.error("Erreur HTTP :", xhr.status);
                    $('#userTableBody').html(`<tr><td colspan="5" class="p-4 text-center text-red-500">Impossible de joindre get_users.php</td></tr>`);
                }
            });
        };

        function deleteUser(id) {
            if (confirm("Supprimer ?")) {
                $.post('delete_user.php', {
                    id: id
                }, () => {
                    $(`#user-${id}`).remove();
                });
            }
        }

        $(document).ready(function() {
            loadUsers();
        });
    </script>
</body>

</body>

</html>