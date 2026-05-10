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
                <p class="text-slate-500 italic text-sm">Cliquez sur une ligne pour voir le détail</p>
            </div>
        </header>

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="p-4 bg-slate-50 border-b flex justify-between items-center">
                <input type="text" id="searchUser" placeholder="Rechercher un client..." class="border rounded-lg px-4 py-2 text-sm w-64 focus:ring-2 focus:ring-blue-500 outline-none" oninput="filterTable()">
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
                    <tbody id="clientTableBody" class="divide-y text-sm"></tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- ===== MODAL DÉTAIL CLIENT ===== -->
    <div id="client-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">

            <!-- Header -->
            <div class="bg-slate-900 rounded-t-2xl p-6 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black text-white uppercase tracking-tight" id="modal-name">--</h3>
                    <p class="text-slate-400 text-sm mt-1" id="modal-adresse">--</p>
                </div>
                <button onclick="closeModal()" class="text-slate-400 hover:text-white text-2xl transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Stats rapides -->
            <div class="grid grid-cols-3 divide-x border-b">
                <div class="p-5 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Dépensé</p>
                    <p class="text-2xl font-black text-green-600 mt-1" id="modal-depense">--</p>
                </div>
                <div class="p-5 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">SOS Demandés</p>
                    <p class="text-2xl font-black text-red-500 mt-1" id="modal-sos">--</p>
                </div>
                <div class="p-5 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Avis Laissés</p>
                    <p class="text-2xl font-black text-orange-500 mt-1" id="modal-avis-count">--</p>
                </div>
            </div>

            <div class="p-6 space-y-6">

                <!-- Infos contact -->
                <div class="flex flex-wrap gap-4 text-sm text-slate-600 bg-slate-50 rounded-xl p-4">
                    <span><i class="fas fa-envelope mr-2 text-slate-400"></i><span id="modal-email">--</span></span>
                    <span><i class="fas fa-phone mr-2 text-slate-400"></i><span id="modal-tel">--</span></span>
                    <span><i class="fas fa-map-marker-alt mr-2 text-slate-400"></i><span id="modal-loc">--</span></span>
                </div>

                <!-- Interventions -->
                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest text-slate-700 mb-3 flex items-center">
                        <i class="fas fa-triangle-exclamation mr-2 text-red-500"></i>Historique SOS
                    </h4>
                    <div id="modal-interventions" class="space-y-2"></div>
                </div>

                <!-- Avis -->
                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest text-slate-700 mb-3 flex items-center">
                        <i class="fas fa-star mr-2 text-orange-400"></i>Avis Laissés
                    </h4>
                    <div id="modal-avis" class="space-y-2"></div>
                </div>

            </div>
        </div>
    </div>
    <!-- ===== FIN MODAL ===== -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // ── Table ──────────────────────────────────────────────
        function loadClients() {
            $.ajax({
                url: 'api/get_clients.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let rows = '';
                    if (!data.length) {
                        rows = '<tr><td colspan="6" class="p-8 text-center text-gray-400 italic">Aucun client trouvé.</td></tr>';
                    } else {
                        data.forEach(c => {
                            rows += `
                            <tr class="hover:bg-blue-50 transition cursor-pointer" id="client-${c.id_user}"
                                onclick="openClientModal(${c.id_user}, event)">
                                <td class="px-6 py-4 text-gray-400 font-mono">#${c.id_user}</td>
                                <td class="px-6 py-4 font-bold text-slate-700">${c.nom} ${c.prenom}</td>
                                <td class="px-6 py-4 text-gray-600">${c.email}</td>
                                <td class="px-6 py-4 text-blue-600 font-medium">${c.telephone || 'N/A'}</td>
                                <td class="px-6 py-4 text-gray-500 italic">${c.adresse || 'Non renseignée'}</td>
                                <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                                    <button onclick="deleteClient(${c.id_user})"
                                        class="text-red-400 hover:text-red-600 transition">
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
                $.post('api/delete_user.php', { id }, function(res) {
                    if (res.status === 'success') {
                        $(`#client-${id}`).fadeOut(300, function() { $(this).remove(); });
                    } else {
                        alert("Erreur lors de la suppression.");
                    }
                }, 'json');
            }
        }

        function filterTable() {
            const q = $('#searchUser').val().toLowerCase();
            $('#clientTableBody tr').each(function() {
                $(this).toggle($(this).text().toLowerCase().includes(q));
            });
        }

        // ── Modal ──────────────────────────────────────────────
        function openClientModal(id, event) {
            if ($(event.target).closest('button').length) return;

            const modal = document.getElementById('client-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Reset
            ['modal-name','modal-adresse','modal-depense','modal-sos',
             'modal-avis-count','modal-email','modal-tel','modal-loc'].forEach(el => {
                document.getElementById(el).textContent = '…';
            });
            document.getElementById('modal-interventions').innerHTML =
                '<p class="text-slate-400 text-sm">Chargement…</p>';
            document.getElementById('modal-avis').innerHTML =
                '<p class="text-slate-400 text-sm">Chargement…</p>';

            $.getJSON('api/get_client_detail.php?id=' + id, function(data) {
                if (data.error) { alert(data.error); return; }
                const c = data.client, s = data.stats;

                document.getElementById('modal-name').textContent       = c.prenom + ' ' + c.nom;
                document.getElementById('modal-adresse').textContent    = c.adresse || 'Adresse non renseignée';
                document.getElementById('modal-depense').textContent    = s.total_depense + ' DT';
                document.getElementById('modal-sos').textContent        = s.total_sos;
                document.getElementById('modal-avis-count').textContent = s.total_avis;
                document.getElementById('modal-email').textContent      = c.email;
                document.getElementById('modal-tel').textContent        = c.telephone || '—';
                document.getElementById('modal-loc').textContent        = c.adresse || '—';

                // Interventions
                const intDiv = document.getElementById('modal-interventions');
                if (!data.interventions.length) {
                    intDiv.innerHTML = '<p class="text-slate-400 text-sm italic">Aucune intervention.</p>';
                } else {
                    intDiv.innerHTML = data.interventions.map(i => {
                        const statusClass =
                            i.statut === 'terminé'  ? 'bg-green-100 text-green-700' :
                            i.statut === 'en cours' ? 'bg-blue-100 text-blue-700'  :
                                                      'bg-slate-200 text-slate-600';
                        const meca = (i.meca_prenom && i.meca_nom)
                            ? `<span class="text-slate-400 text-xs"><i class="fas fa-wrench mr-1"></i>${i.meca_prenom} ${i.meca_nom}</span>`
                            : '';
                        const vehicule = (i.marque && i.modele)
                            ? `<span class="text-slate-400 text-xs"><i class="fas fa-car mr-1"></i>${i.marque} ${i.modele} (${i.immatriculation ?? '—'})</span>`
                            : '';
                        return `
                        <div class="bg-slate-50 rounded-xl px-4 py-3 text-sm space-y-1">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800 font-mono">#${i.id_intervention}</span>
                                    <span class="text-slate-500">${i.type_intervention ?? '—'}</span>
                                </div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <span class="text-xs text-slate-400">${i.date_intervention ?? '—'}</span>
                                    ${i.montant
                                        ? `<span class="font-bold text-green-600">${i.montant} DT</span>
                                           <span class="text-xs text-slate-400">${i.mode_paiement ?? ''}</span>`
                                        : '<span class="text-slate-400 text-xs">Non payé</span>'}
                                    <span class="px-2 py-0.5 rounded-full text-xs font-bold ${statusClass}">${i.statut}</span>
                                </div>
                            </div>
                            <div class="flex gap-4 flex-wrap">${meca}${vehicule}</div>
                        </div>`;
                    }).join('');
                }

                // Avis
                const avisDiv = document.getElementById('modal-avis');
                if (!data.avis.length) {
                    avisDiv.innerHTML = '<p class="text-slate-400 text-sm italic">Aucun avis laissé.</p>';
                } else {
                    avisDiv.innerHTML = data.avis.map(a => {
                        const stars = '★'.repeat(a.note) + '☆'.repeat(5 - a.note);
                        return `
                        <div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-3">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-slate-700 text-sm">
                                    <i class="fas fa-wrench mr-1 text-slate-400"></i>
                                    ${a.meca_prenom ?? ''} ${a.meca_nom ?? ''}
                                </span>
                                <span class="text-orange-400 font-black text-sm tracking-wider">${stars}</span>
                            </div>
                            <p class="text-slate-600 text-sm italic">"${a.commentaire ?? '—'}"</p>
                            <p class="text-slate-400 text-xs mt-1">${a.date ?? ''}</p>
                        </div>`;
                    }).join('');
                }

            }).fail(function() {
                alert('Erreur lors du chargement des données.');
            });
        }

        function closeModal() {
            const modal = document.getElementById('client-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close on backdrop click
        document.getElementById('client-modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        $(document).ready(loadClients);
    </script>
</body>

</html>