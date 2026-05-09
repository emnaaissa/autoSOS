<?php
session_start();
include("../config/db.php");

$id_interv = $_GET['id'] ?? null;
$stmt = $pdo->prepare("SELECT type_intervention FROM intervention WHERE id_intervention = ?");
$stmt->execute([$id_interv]);
$inv = $stmt->fetch();
?>

<style>
    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 2.5rem;
        color: #cbd5e1;
        cursor: pointer;
        transition: color 0.2s;
        margin: 0 2px;
    }

    .star-rating input:checked~label {
        color: #f59e0b;
    }

    .star-rating label:hover,
    .star-rating label:hover~label {
        color: #fbbf24;
    }

    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }
</style>

<div class="bg-yellow-500 p-6 text-white text-center">
    <i class="fas fa-star text-4xl mb-2"></i>
    <h2 class="text-2xl font-black">Votre avis compte</h2>
    <p class="text-sm opacity-90"><?= htmlspecialchars($inv['type_intervention']) ?></p>
</div>

<form action="traiter_avis.php" method="POST" class="p-6">
    <input type="hidden" name="id_intervention" value="<?= $id_interv ?>">

    <p class="text-center text-gray-500 font-bold text-xs uppercase mb-4">Notez le service</p>

    <div class="star-rating mb-6 bg-gray-50 p-3 rounded-xl border border-gray-100">
        <input type="radio" id="star5" name="note" value="5" checked><label for="star5"><i class="fas fa-star"></i></label>
        <input type="radio" id="star4" name="note" value="4"><label for="star4"><i class="fas fa-star"></i></label>
        <input type="radio" id="star3" name="note" value="3"><label for="star3"><i class="fas fa-star"></i></label>
        <input type="radio" id="star2" name="note" value="2"><label for="star2"><i class="fas fa-star"></i></label>
        <input type="radio" id="star1" name="note" value="1"><label for="star1"><i class="fas fa-star"></i></label>
    </div>

    <div class="mb-6">
        <textarea name="commentaire" rows="3" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-yellow-300 outline-none text-sm" placeholder="Un petit mot sur le mécanicien..."></textarea>
    </div>

    <button type="submit" class="w-full bg-slate-900 text-yellow-500 font-black py-3 rounded-xl shadow-lg transition active:scale-95">
        PUBLIER MON AVIS
    </button>
    <button type="button" onclick="fermerAvis()" class="w-full text-center text-gray-400 text-xs mt-4 font-bold">Plus tard</button>
</form>