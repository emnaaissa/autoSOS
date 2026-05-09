<?php
session_start();
include("../config/db.php");

$id_client = $_SESSION['user_id'];
$id_interv = $_GET['id'] ?? null;

// On récupère l'intervention ET le montant déjà défini par le mécanicien
$stmt = $pdo->prepare("
    SELECT i.type_intervention, p.montant 
    FROM intervention i
    JOIN paiement p ON i.id_intervention = p.id_intervention
    WHERE i.id_intervention = ? AND i.id_client = ?
");
$stmt->execute([$id_interv, $id_client]);
$data = $stmt->fetch();

if (!$data) exit("<p class='p-4 text-center'>Erreur : Montant non défini par le mécanicien.</p>");

$prix = $data['montant'];
?>

<div class="bg-indigo-600 p-6 text-white text-center">
    <h2 class="text-2xl font-black italic">auto<span class="text-red-400">SOS</span></h2>
    <p class="text-sm opacity-80">Paiement sécurisé</p>
</div>

<div class="p-8">
    <div class="text-center mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Montant fixé par le mécanicien</p>
        <div class="text-5xl font-black text-slate-800"><?= number_format($prix, 2) ?> <span class="text-2xl text-gray-400">DT</span></div>
        <p class="text-sm text-gray-500 mt-2 italic"><?= htmlspecialchars($data['type_intervention']) ?></p>
    </div>

    <form action="traiter_paiement.php" method="POST">
        <input type="hidden" name="id_intervention" value="<?= $id_interv ?>">

        <div class="space-y-3 mb-6">
            <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                <input type="radio" name="mode_paiement" value="Carte" checked class="w-4 h-4 text-indigo-600">
                <span class="ml-3 font-bold text-gray-700 flex-1">Carte Bancaire</span>
                <i class="fab fa-cc-visa text-2xl text-indigo-800"></i>
            </label>

            <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                <input type="radio" name="mode_paiement" value="Espèces" class="w-4 h-4 text-green-600">
                <span class="ml-3 font-bold text-gray-700 flex-1">Espèces au mécanicien</span>
                <i class="fas fa-money-bill-wave text-xl text-green-500"></i>
            </label>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-indigo-700 transition">
            CONFIRMER LE PAIEMENT
        </button>
        <button type="button" onclick="fermerPaiement()" class="w-full text-center text-gray-400 text-sm mt-4 font-bold">Annuler</button>
    </form>
</div>