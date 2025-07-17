<?php
include '../layout/header.html.php'; // Inclusion du layout HTML commun (en-tête, styles, etc.)
include '../include/bd.inc.php'; // Inclusion du fichier de connexion à la base de données
$pdo = pdo_connect_mysql(); // Connexion à la base via PDO
$msg = ''; // Message de confirmation à afficher après insertion

// Vérifie si le formulaire a été soumis
if (!empty($_POST)) {

    // Récupération des champs du formulaire, avec sécurité minimale :
    // Si le champ est vide, on affecte une valeur par défaut (NULL ou chaîne vide)
    $idfilm = isset($_POST['idfilm']) && !empty($_POST['idfilm']) && $_POST['idfilm'] != 'auto' ? $_POST['idfilm'] : NULL;
    $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
    $annee = isset($_POST['annee']) ? $_POST['annee'] : '';
    $idGenre = isset($_POST['idGenre']) ? $_POST['idGenre'] : '';
    $resume = isset($_POST['resume']) ? $_POST['resume'] : '';

    // Préparation de la requête SQL d'insertion :
    // On insère un nouveau film dans la table "film" (toutes les colonnes)
    // L'utilisation de paramètres (?) permet d'éviter les injections SQL
    $stmt = $pdo->prepare();
    $stmt->execute([$idfilm, $titre, $annee, $idGenre, $resume]);

    // Message à afficher après succès
    $msg = 'Film ajouté avec succès';
}

// cette requête permet de provisionner la liste déroulante Genre
// On récupère tous les genres existants triés par nom
$stmt_genre = $pdo->prepare();
$stmt_genre->execute();
$genres = $stmt_genre->fetchAll(PDO::FETCH_ASSOC); // Résultat sous forme de tableau associatif
?>

<!-- Affichage du header HTML avec le titre de la page -->
<?= template_header('Ajout film') ?>

<!-- Message de confirmation (si un film a été ajouté) -->
<?php if ($msg): ?>
    <div class="alert alert-dismissible alert-success mt-2">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><?= $msg ?></strong>
        <a href="read.php">Retour vers la liste des films</a>
    </div>
<?php endif; ?>

<div class="content update">
    <h2>Ajouter un film</h2>

    <!-- Formulaire HTML d'ajout de film -->
    <form action="create.php" method="post">

        <!-- Les champs sont groupés 2 par 2 pour respecter le layout d'origine -->
        <label for="idfilm">ID</label>
        <label for="titre">Titre</label>
        <!-- Le champ ID est facultatif : "auto" déclenche la valeur NULL en PHP, utile pour AUTO_INCREMENT -->
        <input type="text" name="idfilm" placeholder="1" value="auto" id="id">
        <input type="text" name="titre" placeholder="Titre du film" id="titre">

        <label for="annee">Année</label>
        <label for="idGenre">Genre</label>
        <input type="number" name="annee" placeholder="2020" id="annee">

        <!-- Liste déroulante des genres disponibles (provenant de la table "genre") -->
        <select class="form-control" name="idGenre" id="idGenre">
            <?php foreach ($genres as $genre): ?>
                <option value="<?=$genre['id']?>"><?=$genre['nomGenre']?></option>
            <?php endforeach; ?>
        </select>

        <label for="resume">Résumé</label>
        <input type="text" name="resume" placeholder="Un résumé du film en quelques lignes" id="resume">

        <!-- Boutons de soumission et de retour -->
        <input type="submit" value="Enregistrer">
        <input type="return" value="Retour" onclick="history.back()">
    </form>
</div>
