<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Mécaniciens - autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">

    <nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
        <div class="mb-10 text-center">
            <h1 class="text-2xl font-bold text-blue-400 italic">auto<span class="text-red-500">SOS</span></h1>
            <span class="text-[10px] bg-blue-900 text-blue-300 px-2 py-1 rounded uppercase tracking-widest font-bold">Administration</span>
        </div>
        <ul class="space-y-2">
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="dashboard.php" class="flex items-center"><i class="fas fa-chart-line mr-3"></i> Statistiques</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="gestion_utilisateurs.php" class="flex items-center"><i class="fas fa-users mr-3"></i> Utilisateurs</a></li>
            <li class="bg-blue-600 p-3 rounded-lg"><a href="gestion_mecaniciens.php" class="flex items-center"><i class="fas fa-wrench mr-3"></i> Mécaniciens</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="../auth/logout.php" class="flex items-center"><i class="fas fa-power-off mr-3"></i> Déconnexion</a></li>
        </ul>
    </nav>

    <main class="md:ml-64 p-8">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase">Gestion des Mécaniciens</h2>
                <p class="text-slate-500 italic text-sm">Liste des experts certifiés autoSOS</p>
            </div>
        </header>

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="p-4 bg-slate-50 border-b flex justify-between">
                <input type="text" id="searchMeca" placeholder="Rechercher un mécanicien..." class="border rounded-lg px-4 py-2 text-sm w-64">
                <button onclick="loadMecaniciens()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-sync-alt"></i> Actualiser
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-900 text-white text-[11px] uppercase">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nom / Prénom</th>
                            <th class="px-6 py-4">Spécialité</th>
                            <th class="px-6 py-4">Localisation</th>
                            <th class="px-6 py-4 text-center">Statut</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="mecaTableBody" class="divide-y text-sm">
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadMecaniciens() {
            $.get('api/get_mecaniciens.php', function(data) {
                let rows = '';
                data.forEach(m => {
                    const status = m.disponibilite == 1 ?
                        '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-[10px] font-bold uppercase">Disponible</span>' :
                        '<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-[10px] font-bold uppercase">Occupé</span>';

                    rows += `
                    <tr class="hover:bg-gray-50 transition" id="meca-${m.id_user}">
                        <td class="px-6 py-4 text-gray-400 font-mono">#${m.id_user}</td>
                        <td class="px-6 py-4 font-bold text-slate-700">${m.nom} ${m.prenom}</td>
                        <td class="px-6 py-4 text-blue-600 font-medium">${m.specialite}</td>
                        <td class="px-6 py-4 text-gray-500">${m.localisation}</td>
                        <td class="px-6 py-4 text-center">${status}</td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="deleteMeca(${m.id_user})" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
                });
                $('#mecaTableBody').html(rows || '<tr><td colspan="6" class="p-4 text-center">Aucun mécanicien.</td></tr>');
            }, 'json');
        }

        function deleteMeca(id) {
            if (confirm("Supprimer ce mécanicien ?")) {
                $.post('api/delete_meca.php', {
                    id: id
                }, () => $(`#meca-${id}`).remove());
            }
        }

        $(document).ready(loadMecaniciens);
    </script>
</body>

</html>