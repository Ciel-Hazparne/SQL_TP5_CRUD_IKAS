<?php
// Inclusion du template d'en-tête HTML commun à toutes les pages
include '../layout/header.html.php';
// Inclusion du fichier de connexion PDO à la base de données
include '../include/bd.inc.php';

// Connexion à la base de données via une fonction centralisée
$pdo = pdo_connect_mysql();

// ----------- GESTION DE LA PAGINATION ----------- //
// Détermination de la page courante (paramètre GET : ?page=)
// Si aucun paramètre ou paramètre invalide, on utilise la page 1 par défaut
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

// Nombre de films à afficher par page (résumés parfois longs)
$recordsPerPage = 4;

// ----------- RÉCUPÉRATION DES FILMS AVEC LIMIT ----------- //
// Requête préparée avec jointure INNER JOIN entre les tables 'film' et 'genre'
// On limite le résultat à 4 films par page pour la pagination

//$stmt = $pdo->prepare();

// Calcul de l'offset (décalage) en fonction de la page en cours
$offset = ($page - 1) * $recordsPerPage;

// Liaison des paramètres avec typage explicite
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
$stmt->execute();

// Récupération des films sous forme de tableau associatif
$films = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ----------- CALCUL DU NOMBRE TOTAL DE FILMS ----------- //
// Utilisé pour calculer le nombre total de pages
$totalFilms = (int) $pdo->query('SELECT COUNT(*) FROM Film')->fetchColumn();
$maxPage = (int) ceil($totalFilms / $recordsPerPage);

?>

<!-- Affichage de l'en-tête de la page -->
<?= template_header('Liste Films') ?>

<div class="content read">
    <h2>Liste des films</h2>

    <!-- Tableau des films -->
    <table>
        <thead>
        <tr>
            <td>Id</td>
            <td>Titre</td>
            <td>Année</td>
            <td>Genre</td>
            <td>Résumé</td>
            <td>Actions</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($films as $film): ?>
            <tr>
                <td><?= $film['idfilm'] ?></td>
                <td><?= htmlspecialchars($film['titre']) ?></td>
                <td><?= $film['annee'] ?></td>
                <td><?= htmlspecialchars($film['nomGenre']) ?></td>
                <td><small><?= nl2br(htmlspecialchars($film['resume'])) ?></small></td>
                <td class="actions">
                    <a href="update.php?idfilm=<?= urlencode($film['idfilm']) ?>" class="edit">
                        <i class="fa fa-pen fa-xs"></i>
                    </a>
                    <a href="delete.php?idfilm=<?= urlencode($film['idfilm']) ?>"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer le film « <?= addslashes($film['titre']) ?> » ?');"
                       class="trash">
                        <i class="fa fa-trash fa-xs"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Lien vers la page de création d’un nouveau film -->
    <a href="create.php" class="create-film">Ajouter un film</a>
</div>

<!-- Bloc de pagination -->
<div class="pagination mt-3 ml-5">
    <?php if ($page > 1): ?>
        <a class="page-link" href="read.php?page=1" title="Première page">&laquo;</a>
        <a class="page-link" href="read.php?page=<?= $page - 1 ?>" title="Page précédente">&lsaquo;</a>
    <?php endif; ?>

    <span class="page-link"><?= $page ?> / <?= $maxPage ?></span>

    <?php if ($page < $maxPage): ?>
        <a class="page-link" href="read.php?page=<?= $page + 1 ?>" title="Page suivante">&rsaquo;</a>
        <a class="page-link" href="read.php?page=<?= $maxPage ?>" title="Dernière page">&raquo;</a>
    <?php endif; ?>
</div>
