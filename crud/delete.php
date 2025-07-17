<?php
declare(strict_types=1);

include '../layout/header.html.php';
include '../include/bd.inc.php';

$pdo = pdo_connect_mysql();
$msg = '';
$error = '';

// Vérifier que l'ID du film est présent dans l'URL et valide (nombre entier)
if (isset($_GET['idfilm']) && ctype_digit($_GET['idfilm'])) {
    $idfilm = (int)$_GET['idfilm'];

    // Préparation de la requête de suppression
    $stmt = $pdo->prepare();
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $msg = 'Le film a été supprimé avec succès.';
    } else {
        $error = 'Aucun film trouvé avec cet ID.';
    }
} else {
    $error = 'Aucun ID valide spécifié.';
}
?>

<?= template_header('Suppression film') ?>

<?php if ($msg): ?>
    <div class="alert alert-success mt-3">
        <strong><?= $msg ?></strong> —
        <a href="read.php">Retour à la liste des films</a>
    </div>
<?php elseif ($error): ?>
    <div class="alert alert-danger mt-3">
        <strong><?= $error ?></strong><br>
        <button onclick="history.back()" class="btn btn-secondary mt-2">Retour</button>
    </div>
<?php endif; ?>
