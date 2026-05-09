<!-- gestion_utilisateurs.php -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Clients - autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Sidebar (Cohérente avec tout le panel) -->
    <nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
        <div class="mb-10 text-center">
            <h1 class="text-2xl font-bold text-blue-400 italic">auto<span class="text-red-500">SOS</span></h1>
            <span class="text-[10px] bg-blue-900 text-blue-300 px-2 py-1 rounded uppercase tracking-widest font-bold">Administration</span>
        </div>
        <ul class="space-y-2">
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="dashboard.php" class="flex items-center"><i class="fas fa-chart-line mr-3"></i> Statistiques</a></li>
            <li class="bg-blue-600 p-3 rounded-lg"><a href="gestion_utilisateurs.php" class="flex items-center"><i class="fas fa-users mr-3"></i> Utilisateurs</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="gestion_mecaniciens.php" class="flex items-center"><i class="fas fa-wrench mr-3"></i> Mécaniciens</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="../auth/logout.php" class="flex items-center"><i class="fas fa-power-off mr-3"></i> Déconnexion</a></li>
        </ul>
    </nav>

    <main class="md:ml-64 p-8">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase">Gestion des Clients</h2>
                <p class="text-slate-500 italic text-sm">Liste des utilisateurs standards enregistrés</p>
            </div>
        </header>

        <!-- Container de la Table (Design calqué sur Gestion Mécaniciens) -->
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="p-4 bg-slate-50 border-b flex justify-between items-center">
                <input type="text" id="searchUser" placeholder="Rechercher un client..." class="border rounded-lg px-4 py-2 text-sm w-64 focus:ring-2 focus:ring-blue-500 outline-none">
                <button onclick="loadClients()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Actualiser
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-900 text-white text-[11px] uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nom / Prénom</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Téléphone</th>
                            <th class="px-6 py-4">Adresse</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="clientTableBody" class="divide-y text-sm">
                        <!-- Chargé dynamiquement via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadClients() {
            $.ajax({
                url: 'api/get_clients.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let rows = '';
                    if (data.length === 0) {
                        rows = '<tr><td colspan="6" class="p-8 text-center text-gray-400 italic">Aucun client trouvé.</td></tr>';
                    } else {
                        data.forEach(c => {
                            rows += `
                                <tr class="hover:bg-gray-50 transition" id="client-${c.id_user}">
                                    <td class="px-6 py-4 text-gray-400 font-mono">#${c.id_user}</td>
                                    <td class="px-6 py-4 font-bold text-slate-700">${c.nom} ${c.prenom}</td>
                                    <td class="px-6 py-4 text-gray-600">${c.email}</td>
                                    <td class="px-6 py-4 text-blue-600 font-medium">${c.telephone || 'N/A'}</td>
                                    <td class="px-6 py-4 text-gray-500 italic">${c.adresse || 'Non renseignée'}</td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="deleteClient(${c.id_user})" class="text-red-400 hover:text-red-600 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
                        });
                    }
                    $('#clientTableBody').html(rows);
                }
            });
        }

        function deleteClient(id) {
            if (confirm("Supprimer définitivement ce compte client ?")) {
                $.post('api/delete_user.php', {
                    id: id
                }, function(res) {
                    if (res.status === 'success') {
                        $(`#client-${id}`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        alert("Erreur lors de la suppression.");
                    }
                }, 'json');
            }
        }

        // Chargement initial
        $(document).ready(loadClients);

        // Recherche dynamique simple
        $('#searchUser').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $("#clientTableBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>
</body>

</html>