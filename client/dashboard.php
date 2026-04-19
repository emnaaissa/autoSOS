<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">

    <nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
        <h1 class="text-2xl font-bold mb-10 text-blue-400 italic">auto<span class="text-red-500">SOS</span></h1>
        <ul class="space-y-2">
            <li class="bg-blue-600 p-3 rounded-lg"><a href="#" class="flex items-center"><i class="fas fa-columns mr-3"></i> Dashboard</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="#" class="flex items-center"><i class="fas fa-car mr-3"></i> Mes Véhicules</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="#" class="flex items-center"><i class="fas fa-notes-medical mr-3"></i> Historique SOS</a></li>
            <li class="hover:bg-slate-800 p-3 rounded-lg transition"><a href="#" class="flex items-center"><i class="fas fa-user-gear mr-3"></i> Paramètres</a></li>
            <li class="absolute bottom-10 left-6 hover:text-red-400 transition"><a href="../auth/logout.php"><i class="fas fa-power-off mr-3"></i> Déconnexion</a></li>
        </ul>
    </nav>

    <main class="md:ml-64 p-4 md:p-8">

        <header class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Mon Espace Client</h2>
                <p class="text-slate-500 italic">Prêt à reprendre la route avec autoSOS</p>
            </div>
            <button class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-black shadow-lg shadow-red-200 transition-all flex items-center justify-center animate-pulse">
                <i class="fas fa-bullhorn mr-3"></i> DEMANDER UNE ASSISTANCE
            </button>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">SOS Lancés</p>
                        <h3 class="text-3xl font-black text-slate-800">03</h3>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-full text-blue-500">
                        <i class="fas fa-truck-pickup text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-slate-900">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Véhicules</p>
                        <h3 class="text-3xl font-black text-slate-800">01</h3>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-full text-slate-900">
                        <i class="fas fa-car-side text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Statut Compte</p>
                        <h3 class="text-lg font-black text-green-600 uppercase">Actif</h3>
                    </div>
                    <div class="bg-green-50 p-4 rounded-full text-green-500">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-lg font-bold text-slate-800">Mes Véhicules Enregistrés</h4>
                    <button class="text-blue-600 hover:underline text-sm font-bold">+ Ajouter</button>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center p-4 border rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        <div class="bg-slate-100 p-4 rounded-lg mr-4">
                            <i class="fas fa-car text-slate-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-black text-slate-800">Volkswagen Golf 7</p>
                            <p class="text-sm text-slate-500">Matricule : <span class="font-mono uppercase">123 TUN 4567</span></p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-3 py-1 rounded-full uppercase">Principal</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-2xl shadow-xl p-6 text-white">
                <h4 class="text-lg font-bold mb-6 flex items-center">
                    <i class="fas fa-satellite-dish mr-3 text-blue-400"></i> Zone de couverture
                </h4>
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">Mécanos en ligne</span>
                        <span class="bg-green-500 h-2 w-2 rounded-full animate-ping"></span>
                    </div>
                    <div class="text-center py-4 border-y border-slate-800">
                        <p class="text-3xl font-black text-blue-400">12</p>
                        <p class="text-xs text-slate-400 uppercase tracking-widest mt-1">Disponibles autour de vous</p>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed text-center italic">
                        "Votre sécurité est notre priorité. Nos experts interviennent en moyenne en moins de 30 minutes."
                    </p>
                </div>
            </div>

        </div>
    </main>

    <div class="fixed bottom-0 left-0 w-full bg-white border-t flex justify-around p-3 md:hidden z-50">
        <button class="text-blue-600"><i class="fas fa-columns text-xl"></i></button>
        <button class="text-slate-400"><i class="fas fa-car text-xl"></i></button>
        <button class="bg-red-600 text-white w-14 h-14 rounded-full shadow-lg -mt-10 border-4 border-gray-100 flex items-center justify-center">
            <i class="fas fa-bullhorn text-xl"></i>
        </button>
        <button class="text-slate-400"><i class="fas fa-history text-xl"></i></button>
        <button class="text-slate-400"><i class="fas fa-user-gear text-xl"></i></button>
    </div>

</body>

</html>