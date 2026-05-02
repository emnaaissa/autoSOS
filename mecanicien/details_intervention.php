<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }
include("../config/db.php");

$id_intervention = $_GET['id'] ?? null;
if (!$id_intervention) { header("Location: dashboard.php"); exit(); }

$stmt = $pdo->prepare("
    SELECT i.*, v.marque, v.modele, v.immatriculation, v.photo, c.nom as client_nom, c.prenom as client_prenom, c.telephone as client_tel 
    FROM intervention i 
    LEFT JOIN vehicule v ON i.id_vehicule = v.id_vehicule 
    LEFT JOIN user c ON i.id_client = c.id_user 
    WHERE i.id_intervention = ?
");
$stmt->execute([$id_intervention]);
$inv = $stmt->fetch();

if (!$inv) { header("Location: dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails Intervention - autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden animate-fade-in">
        <div class="bg-slate-900 p-6 text-white flex justify-between items-center">
            <h2 class="text-2xl font-bold"><i class="fas fa-info-circle mr-2"></i> Détails de l'intervention</h2>
            <a href="dashboard.php" class="text-gray-400 hover:text-white transition"><i class="fas fa-times text-xl"></i></a>
        </div>
        <div class="p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <p class="text-sm text-gray-500 uppercase font-bold">Client</p>
                    <p class="font-bold text-lg"><?= htmlspecialchars($inv['client_prenom'] . ' ' . $inv['client_nom']) ?></p>
                    <p class="text-blue-600"><i class="fas fa-phone mr-1"></i> <a href="tel:<?= htmlspecialchars($inv['client_tel']) ?>"><?= htmlspecialchars($inv['client_tel']) ?></a></p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <p class="text-sm text-gray-500 uppercase font-bold">Véhicule</p>
                    <?php if(!empty($inv['marque'])): ?>
                        <p class="font-bold text-lg"><?= htmlspecialchars($inv['marque'] . ' ' . $inv['modele']) ?></p>
                        <p class="text-gray-600"><i class="fas fa-hashtag mr-1"></i> <?= htmlspecialchars($inv['immatriculation']) ?></p>
                    <?php else: ?>
                        <p class="italic text-gray-500">Non renseigné</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border flex flex-col space-y-2">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold">Localisation</p>
                    <p class="font-bold"><i class="fas fa-map-marker-alt text-red-500 mr-2"></i> <?= htmlspecialchars($inv['localisation']) ?></p>
                    <?php if(!empty($inv['latitude'])): ?>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $inv['latitude'] ?>,<?= $inv['longitude'] ?>" target="_blank" class="text-blue-500 text-sm mt-1 inline-block"><i class="fas fa-directions"></i> Itinéraire Google Maps</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border">
                <p class="text-sm text-gray-500 uppercase font-bold mb-2">Description du problème</p>
                <div class="bg-white p-3 border rounded text-gray-700 relative">
                    <span class="absolute -top-3 -left-3 bg-red-100 text-red-600 p-1 px-2 rounded font-bold text-xs"><i class="fas fa-exclamation-triangle mr-1"></i> <?= htmlspecialchars($inv['type_intervention'] ?? '') ?></span>
                    <p class="pt-2"><?= nl2br(htmlspecialchars($inv['description_probleme'] ?? 'Aucune description fournie.')) ?></p>
                </div>
            </div>
            
            <div class="flex justify-between items-center pt-4">
                <span class="px-3 py-1 rounded bg-gray-200 text-sm font-bold text-gray-600 uppercase">Statut: <?= htmlspecialchars($inv['statut']) ?></span>
                <a href="dashboard.php" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-6 rounded-lg shadow transition">Retour</a>
            </div>

        </div>
    </div>
</body>
</html>
