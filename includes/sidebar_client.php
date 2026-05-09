<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<nav class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-white hidden md:block p-6">
    <h1 class="text-2xl font-bold mb-10 text-blue-400 italic">
        auto<span class="text-red-500">SOS</span>
        <span class="text-[10px] bg-blue-900 text-blue-300 px-2 py-1 rounded uppercase tracking-widest font-bold">Client</span>

    </h1>

    <ul class="space-y-2">

        <li class="<?= $current == 'dashboard.php' ? 'bg-blue-600' : 'hover:bg-slate-800' ?> p-3 rounded-lg">
            <a href="dashboard.php"><i class="fas fa-columns mr-3"></i> Dashboard</a>
        </li>

        <li class="<?= $current == 'vehicules.php' ? 'bg-blue-600' : 'hover:bg-slate-800' ?> p-3 rounded-lg">
            <a href="vehicules.php"><i class="fas fa-car mr-3"></i> Mes Véhicules</a>
        </li>

        <li class="<?= $current == 'historique.php' ? 'bg-blue-600' : 'hover:bg-slate-800' ?> p-3 rounded-lg">
            <a href="historique.php"><i class="fas fa-notes-medical mr-3"></i> Historique SOS</a>
        </li>

        <li class="<?= $current == 'parametres.php' ? 'bg-blue-600' : 'hover:bg-slate-800' ?> p-3 rounded-lg">
            <a href="parametres.php"><i class="fas fa-user-gear mr-3"></i> Paramètres</a>
        </li>

        <li class="absolute bottom-10 left-6 hover:text-red-400">
            <a href="../auth/logout.php">
                <i class="fas fa-power-off mr-3"></i> Déconnexion
            </a>
        </li>

    </ul>
</nav>