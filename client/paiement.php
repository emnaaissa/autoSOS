<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }
include("../config/db.php");

$id_client = $_SESSION['user_id'];
$id_intervention = $_GET['id'] ?? ($_POST['id_intervention'] ?? null);

if (!$id_intervention) { header("Location: historique.php"); exit(); }

// Vérifier si le paiement existe déjà
$check = $pdo->prepare("SELECT statut FROM paiement WHERE id_intervention = ?");
$check->execute([$id_intervention]);
if ($check->fetch()) {
    header("Location: historique.php?msg=deja_paye");
    exit();
}

$stmt = $pdo->prepare("SELECT type_intervention FROM intervention WHERE id_intervention = ? AND id_client = ? AND statut = 'terminé'");
$stmt->execute([$id_intervention, $id_client]);
$inv = $stmt->fetch();

if (!$inv) { header("Location: historique.php?msg=invalid_intervention"); exit(); }

// Génération de prix fixe selon la panne
$prix = 0;
switch (strtolower($inv['type_intervention'])) {
    case 'batterie': $prix = 80.00; break;
    case 'pneu crevé': $prix = 40.00; break;
    case 'remorquage': $prix = 150.00; break;
    case 'moteur': $prix = 250.00; break;
    default: $prix = 100.00;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mode = $_POST['mode_paiement'] ?? 'Carte';
    
    // Insérer le paiement
    $insert = $pdo->prepare("INSERT INTO paiement (montant, date_paiement, mode_paiement, statut, id_intervention) VALUES (?, CURDATE(), ?, 'Payé', ?)");
    $insert->execute([$prix, $mode, $id_intervention]);

    header("Location: historique.php?msg=paiement_succes");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement - autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-fade-in">
        
        <div class="bg-indigo-600 p-8 text-white relative text-center">
            <h2 class="text-3xl font-black italic">auto<span class="text-red-400">SOS</span></h2>
            <p class="opacity-80 mt-1 font-medium">Facturation et Paiement sécurisé</p>
        </div>
        
        <div class="p-8">
            <div class="text-center mb-8 bg-gray-50 p-6 rounded-xl border border-gray-100 shadow-inner">
                <p class="text-gray-400 font-bold uppercase text-xs tracking-wider">Intervention Terminée</p>
                <p class="text-gray-800 font-bold mb-4"><?= htmlspecialchars($inv['type_intervention']) ?></p>
                <p class="text-gray-400 text-sm mb-1 uppercase font-bold tracking-widest">Montant Total</p>
                <div class="text-6xl font-black text-slate-800"><?= number_format($prix, 2) ?> <span class="text-3xl text-gray-300">DT</span></div>
            </div>

            <form method="POST">
                <input type="hidden" name="id_intervention" value="<?= htmlspecialchars($id_intervention) ?>">
                
                <div class="space-y-4 mb-8">
                    <label class="block border-2 border-transparent bg-white rounded-xl p-4 cursor-pointer shadow-sm hover:border-indigo-200 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="radio" name="mode_paiement" value="Carte" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300" checked>
                                <span class="ml-3 font-bold text-gray-700">Carte Bancaire / e-Dinar</span>
                            </div>
                            <i class="fab fa-cc-visa text-3xl text-indigo-800"></i>
                        </div>
                    </label>

                    <label class="block border-2 border-transparent bg-white rounded-xl p-4 cursor-pointer shadow-sm hover:border-green-200 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="radio" name="mode_paiement" value="Espèces" class="w-5 h-5 text-green-600 focus:ring-green-500 border-gray-300">
                                <span class="ml-3 font-bold text-gray-700">Espèces au mécanicien</span>
                            </div>
                            <i class="fas fa-money-bill-wave text-2xl text-green-500"></i>
                        </div>
                    </label>
                </div>

                <div class="flex flex-col space-y-3 pt-4 border-t">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-xl shadow-lg transition-transform transform active:scale-95">CONFIRMER LE PAIEMENT</button>
                    <a href="historique.php" class="w-full text-center text-gray-400 font-bold py-3 hover:text-gray-600 transition">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
