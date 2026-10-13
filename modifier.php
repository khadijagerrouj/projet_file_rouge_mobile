<?php

require_once "connexion.php";

$id = $_GET["id"];

$stmt = $pdo->prepare("SELECT * FROM episodes WHERE id = ?");
$stmt->execute([$id]);
$episode = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$episode) {
    die("Épisode introuvable.");
}

$auditeurs = $pdo->query("SELECT * FROM auditeurs")->fetchAll(PDO::FETCH_ASSOC);
$thematiques = $pdo->query("SELECT * FROM thematiques")->fetchAll(PDO::FETCH_ASSOC);
$playlists = $pdo->query("SELECT * FROM playlists")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "UPDATE episodes SET
            titre = ?,
            description = ?,
            image = ?,
            audio = ?,
            thematique_id = ?,
            auditeur_id = ?,
            playlist_id = ?
            WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $_POST["titre"],
        $_POST["description"],
        $_POST["image"],
        $_POST["audio"],
        $_POST["thematique_id"],
        $_POST["auditeur_id"],
        $_POST["playlist_id"],
        $id
    ]);

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un épisode</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>🎙️ Podcastia</h1>

    <nav>
        <a href="index.php">Accueil</a>
        <a href="ajouter.php">Ajouter</a>
    </nav>
</header>

<main>

    <div class="form-container">

        <h2>Modifier l'épisode</h2>

        <form method="POST">

            <label>Titre</label>
            <input type="text" name="titre"
                   value="<?= htmlspecialchars($episode['titre']) ?>"
                   required>

            <label>Description</label>
            <textarea name="description" required><?= htmlspecialchars($episode['description']) ?></textarea>

            <label>Image</label>
            <input type="text" name="image"
                   value="<?= htmlspecialchars($episode['image']) ?>">

            <label>Audio</label>
            <input type="text" name="audio"
                   value="<?= htmlspecialchars($episode['audio']) ?>">

            <label>Thématique</label>
            <select name="thematique_id">

                <?php foreach ($thematiques as $thematique): ?>

                    <option value="<?= $thematique['id'] ?>"
                        <?= $thematique['id'] == $episode['thematique_id'] ? 'selected' : '' ?>>

                        <?= htmlspecialchars($thematique['nom']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <label>Auditeur</label>
            <select name="auditeur_id">

                <?php foreach ($auditeurs as $auditeur): ?>

                    <option value="<?= $auditeur['id'] ?>"
                        <?= $auditeur['id'] == $episode['auditeur_id'] ? 'selected' : '' ?>>

                        <?= htmlspecialchars($auditeur['nom']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <label>Playlist</label>
            <select name="playlist_id">

                <?php foreach ($playlists as $playlist): ?>

                    <option value="<?= $playlist['id'] ?>"
                        <?= $playlist['id'] == $episode['playlist_id'] ? 'selected' : '' ?>>

                        <?= htmlspecialchars($playlist['nom']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <button type="submit">
                Enregistrer les modifications
            </button>

        </form>

    </div>

</main>

</body>
</html>