<?php
declare(strict_types=1);

// Inclusion du layout HTML général et du fichier de connexion à la BDD
include '../layout/header.html.php';
include '../include/bd.inc.php';

// Connexion à la base via PDO
$pdo = pdo_connect_mysql();

// Messages d'information et d'erreur à afficher
$msg = '';
$error = '';

// On vérifie que l'identifiant du film est bien passé dans l'URL (via GET)
if (isset($_GET['idfilm'])) {
    // Si le formulaire a été soumis (méthode POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération sécurisée des champs du formulaire
        $idfilm = $_POST['idfilm'] ?? null;
        $titre = $_POST['titre'] ?? '';
        $annee = $_POST['annee'] ?? '';
        $idGenre = $_POST['idGenre'] ?? '';
        $resume = $_POST['resume'] ?? '';

        // Requête préparée pour mettre à jour le film
        $stmt = $pdo->prepare();
        $stmt->execute([
            ':idfilm' => $idfilm,
            ':titre' => $titre,
            ':annee' => $annee,
            ':idGenre' => $idGenre,
            ':resume' => $resume,
            ':original_id' => $_GET['idfilm']
        ]);

        $msg = 'Mise à jour effectuée !';
    }

    // On récupère les données du film à mettre à jour
    $stmt = $pdo->prepare();
    $stmt->execute([':id' => $_GET['idfilm']]);
    $film = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si aucun film correspondant à l'ID n'a été trouvé
    if (!$film) {
        $error = 'Cet ID n\'existe pas !';
    }
} else {
    $error = 'Aucun ID spécifié !';
}

// Récupération des genres disponibles pour la liste déroulante
$stmt_genre = $pdo->query();
$genres = $stmt_genre->fetchAll(PDO::FETCH_ASSOC);

?>

<?= template_header('Mise à jour d\'un film') ?>

<?php if ($msg): ?>
    <div class="alert alert-success mt-2">
        <strong><?= $msg ?></strong> —
        <a href="read.php">Retour à la liste</a>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger mt-2">
        <strong><?= $error ?></strong><br>
        <button onclick="history.back()" class="btn btn-secondary mt-2">Retour</button>
    </div>
<?php elseif (!empty($film)): ?>
    <div class="content update">
        <h2>Mise à jour du film : <strong><?= htmlspecialchars($film['titre']) ?></strong></h2>
        <form action="update.php?idfilm=<?= $film['idfilm'] ?>" method="post">
            <label for="idfilm">ID</label>
            <input type="number" name="idfilm" id="idfilm" value="<?= $film['idfilm'] ?>" required>

            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($film['titre']) ?>" required>

            <label for="number">Année</label>
            <input type="number" name="annee" id="annee" value="<?= $film['annee']?>"
                   required>

            <label for="idGenre">Genre</label>
            <select name="idGenre" id="idGenre" class="form-control" required>
                <option value="<?= $film['idGenre'] ?>"><?= htmlspecialchars($film['nomGenre']) ?> (actuel)</option>
                <?php foreach ($genres as $genre): ?>
                    <?php if ($genre['id'] != $film['idGenre']): ?>
                        <option value="<?= $genre['id'] ?>"><?= htmlspecialchars($genre['nomGenre']) ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>

            <label for="resume">Résumé</label>
            <input type="text" name="resume" id="resume" value="<?= htmlspecialchars($film['resume']) ?>">

            <input type="submit" value="Enregistrer" class="btn btn-primary mt-2">
            <button type="button" onclick="history.back()" class="btn btn-secondary mt-2">Retour</button>
        </form>
    </div>
<?php endif; ?>
