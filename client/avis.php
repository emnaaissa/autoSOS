<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }
include("../config/db.php");

$id_client = $_SESSION['user_id'];
$id_intervention = $_GET['id'] ?? ($_POST['id_intervention'] ?? null);

if (!$id_intervention) { header("Location: historique.php"); exit(); }

// Vérifier si un avis existe déjà
$check = $pdo->prepare("SELECT id_avis FROM avis WHERE id_intervention = ?");
$check->execute([$id_intervention]);
if ($check->fetch()) {
    header("Location: historique.php?msg=deja_evalue");
    exit();
}

// Ensure intervention is totally finished with mechanic assigned
$stmt = $pdo->prepare("SELECT type_intervention, id_mecanicien FROM intervention WHERE id_intervention = ? AND id_client = ? AND statut = 'terminé'");
$stmt->execute([$id_intervention, $id_client]);
$inv = $stmt->fetch();

if (!$inv) { header("Location: historique.php?msg=invalid_intervention"); exit(); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $note = (int)($_POST['note'] ?? 5);
    $commentaire = $_POST['commentaire'] ?? '';
    
    // Check constraints 1-5
    if ($note < 1) $note = 1;
    if ($note > 5) $note = 5;

    $insert = $pdo->prepare("INSERT INTO avis (note, commentaire, date, id_intervention, id_user) VALUES (?, ?, CURDATE(), ?, ?)");
    $insert->execute([$note, $commentaire, $id_intervention, $id_client]);

    header("Location: historique.php?msg=avis_succes");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Évaluer le service - autoSOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .star-rating input { display: none; }
        .star-rating label { font-size: 3rem; color: #cbd5e1; cursor: pointer; transition: color 0.2s, transform 0.1s; margin: 0 5px; }
        .star-rating label:active { transform: scale(0.9); }
        .star-rating input:checked ~ label { color: #f59e0b; }
        .star-rating label:hover, .star-rating label:hover ~ label { color: #fbbf24; }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: center; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in text-center">
        
        <div class="bg-yellow-500 p-8 text-white relative">
            <i class="fas fa-star text-5xl mb-4 drop-shadow-md"></i>
            <h2 class="text-3xl font-black">Donnez votre avis</h2>
            <p class="opacity-90 font-medium mt-1">Intervention : <?= htmlspecialchars($inv['type_intervention']) ?></p>
        </div>
        
        <form method="POST" class="p-8">
            <input type="hidden" name="id_intervention" value="<?= htmlspecialchars($id_intervention) ?>">
            
            <p class="text-gray-500 uppercase font-bold text-sm tracking-wide mb-6">Notez l'efficacité du mécanicien</p>
            
            <div class="star-rating mb-8 bg-gray-50 p-4 rounded-xl shadow-inner">
                <input type="radio" id="star5" name="note" value="5" checked><label for="star5" title="Excellent"><i class="fas fa-star drop-shadow-sm"></i></label>
                <input type="radio" id="star4" name="note" value="4"><label for="star4" title="Très bien"><i class="fas fa-star drop-shadow-sm"></i></label>
                <input type="radio" id="star3" name="note" value="3"><label for="star3" title="Moyen"><i class="fas fa-star drop-shadow-sm"></i></label>
                <input type="radio" id="star2" name="note" value="2"><label for="star2" title="Médiocre"><i class="fas fa-star drop-shadow-sm"></i></label>
                <input type="radio" id="star1" name="note" value="1"><label for="star1" title="Mauvais"><i class="fas fa-star drop-shadow-sm"></i></label>
            </div>

            <div class="mb-8 text-left">
                <label class="block text-gray-400 font-bold mb-3 text-xs uppercase tracking-wider">Coup de coeur ou remarques</label>
                <textarea name="commentaire" rows="3" class="w-full p-4 border rounded-xl focus:ring-4 focus:ring-yellow-200 border-yellow-100 focus:border-yellow-400 outline-none transition" placeholder="Ex: Très professionnel et réparation express !"></textarea>
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-yellow-500 font-black text-lg py-4 rounded-xl shadow-lg transition transform active:scale-95">PUBLIER MON AVIS</button>
            <a href="historique.php" class="block w-full text-center text-gray-400 font-bold mt-4 mb-2 hover:text-gray-600 transition">Passer</a>
        </form>
    </div>
</body>
</html>
