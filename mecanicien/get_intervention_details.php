<?php
session_start();
include("../config/db.php");

$id = $_GET['id'] ?? null;
if (!$id) exit("ID manquant");

$stmt = $pdo->prepare("
    SELECT i.*, v.marque, v.modele, v.immatriculation, c.nom, c.prenom, c.telephone 
    FROM intervention i 
    LEFT JOIN vehicule v ON i.id_vehicule = v.id_vehicule 
    LEFT JOIN user c ON i.id_client = c.id_user 
    WHERE i.id_intervention = ?
");
$stmt->execute([$id]);
$inv = $stmt->fetch();

if (!$inv) exit("Intérouvable");
?>

<div class="space-y-6">
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-50 p-3 rounded-lg border">
            <p class="text-xs text-gray-500 uppercase font-bold">Client</p>
            <p class="font-bold"><?= htmlspecialchars($inv['prenom'] . ' ' . $inv['nom']) ?></p>
            <a href="tel:<?= $inv['telephone'] ?>" class="text-blue-600 text-sm"><i class="fas fa-phone mr-1"></i> <?= $inv['telephone'] ?></a>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border">
            <p class="text-xs text-gray-500 uppercase font-bold">Véhicule</p>
            <p class="font-bold"><?= htmlspecialchars($inv['marque'] . ' ' . $inv['modele']) ?></p>
            <p class="text-xs text-gray-600"><?= htmlspecialchars($inv['immatriculation']) ?></p>
        </div>
    </div>

    <div class="bg-gray-50 p-3 rounded-lg border">
        <p class="text-xs text-gray-500 uppercase font-bold">Problème</p>
        <p class="text-red-600 font-bold text-sm mb-1"><?= htmlspecialchars($inv['type_intervention']) ?></p>
        <p class="text-gray-700 text-sm"><?= nl2br(htmlspecialchars($inv['description_probleme'])) ?></p>
    </div>

    <div class="flex flex-col space-y-2">
        <p class="text-xs text-gray-500 uppercase font-bold">Localisation</p>
        <p class="text-sm"><i class="fas fa-map-marker-alt text-red-500 mr-1"></i> <?= htmlspecialchars($inv['localisation']) ?></p>
        <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $inv['localisation'] ?>" target="_blank" class="text-blue-500 text-xs font-bold underline">Ouvrir l'itinéraire</a>
    </div>

    <div class="pt-4 border-t flex justify-end">
        <button onclick="closeModal()" class="bg-slate-800 text-white px-6 py-2 rounded-lg font-bold">Fermer</button>
    </div>
</div>