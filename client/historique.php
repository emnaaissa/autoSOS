<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include("../config/db.php");
include("../includes/sidebar_client.php");

$id_user = $_SESSION['user_id'];

// PDO SAFE QUERY with Mechanic Info, Payment, and Review tracking
$stmt = $pdo->prepare("
    SELECT i.*, u.nom as meca_nom, u.prenom as meca_prenom, u.telephone as meca_telephone, 
           p.statut as p_statut, a.note as a_note
    FROM intervention i 
    LEFT JOIN user u ON i.id_mecanicien = u.id_user 
    LEFT JOIN paiement p ON i.id_intervention = p.id_intervention
    LEFT JOIN avis a ON i.id_intervention = a.id_intervention
    WHERE i.id_client = ?
    ORDER BY i.id_intervention DESC
");
$stmt->execute([$id_user]);
$interventions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique SOS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">

<!-- SIDEBAR -->
<?php include("../includes/sidebar_client.php"); ?>

<!-- CONTENT -->
<main class="md:ml-64 p-8">

    <h2 class="text-2xl font-bold mb-6 text-slate-800">
        Historique SOS
    </h2>

    <?php if (count($interventions) == 0): ?>
        <div class="bg-white p-6 rounded shadow text-gray-500">
            Aucun historique disponible
        </div>
    <?php endif; ?>

    <?php foreach ($interventions as $row): ?>

        <div class="bg-white p-5 rounded-xl shadow mb-4 border-l-4
            <?= $row['statut'] == 'terminé' ? 'border-green-500' : 'border-yellow-500' ?>">

            <div class="flex justify-between items-center">

                <div>
                    <p class="font-bold text-slate-800">
                        <?= htmlspecialchars($row['type_intervention']) ?>
                    </p>

                    <p class="text-sm text-gray-500 mb-2">
                        Statut: <span class="font-semibold uppercase"><?= htmlspecialchars($row['statut']) ?></span>
                    </p>
                    
                    <?php if (strtolower($row['statut']) === 'en cours' && !empty($row['meca_nom'])): ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800 mt-3 animate-fade-in">
                        <p class="font-bold mb-1"><i class="fas fa-wrench mr-2"></i>Pris en charge par :</p>
                        <p><?= htmlspecialchars($row['meca_prenom'] . ' ' . $row['meca_nom']) ?></p>
                        <p class="mt-1 font-bold"><i class="fas fa-phone mr-2"></i><?= htmlspecialchars($row['meca_telephone'] ?? 'Non fourni') ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (strtolower($row['statut']) === 'en attente'): ?>
                        <div class="mt-4">
                            <form action="annuler_intervention.php" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Voulez-vous vraiment annuler cette demande ?');">
                                <input type="hidden" name="id_intervention" value="<?= $row['id_intervention'] ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-md">
                                    <i class="fas fa-times-circle mr-2"></i> Annuler la demande
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (strtolower($row['statut']) === 'terminé'): ?>
                        <?php if (strtolower($row['p_statut'] ?? '') === 'payé'): ?>
                            <div class="mt-3 text-sm font-bold text-green-600 bg-green-50 p-2 py-1 rounded inline-flex items-center">
                                <i class="fas fa-check-circle mr-2"></i> Facture réglée
                            </div>
                            
                            <?php if (empty($row['a_note'])): ?>
                                <div class="mt-3">
                                    <a href="avis.php?id=<?= $row['id_intervention'] ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-bold inline-block transition shadow-md">
                                        <i class="fas fa-star text-white mr-1"></i> Laisser un avis
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="mt-3 text-yellow-500">
                                    <?php for($i=1; $i<=5; $i++) echo ($i <= $row['a_note']) ? '<i class="fas fa-star text-xl mr-1 drop-shadow-sm"></i>' : '<i class="far fa-star text-xl mr-1"></i>'; ?>
                                </div>
                            <?php endif; ?>
                            
                        <?php else: ?>
                            <div class="mt-4">
                                <a href="paiement.php?id=<?= $row['id_intervention'] ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold inline-block transition shadow-md">
                                    <i class="fas fa-credit-card mr-2"></i> Procéder au Paiement
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="text-right">
                    <span class="text-xs px-3 py-1 rounded-full
                        <?= $row['statut'] == 'terminé' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                        <?= htmlspecialchars($row['statut']) ?>
                    </span>
                </div>

            </div>

        </div>

    <?php endforeach; ?>

</main>

</body>
</html>