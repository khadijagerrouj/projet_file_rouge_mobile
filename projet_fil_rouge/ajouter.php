<?php

require_once "connexion.php";

$auditeurs = $pdo->query("SELECT * FROM auditeurs")->fetchAll(PDO::FETCH_ASSOC);
$thematiques = $pdo->query("SELECT * FROM thematiques")->fetchAll(PDO::FETCH_ASSOC);
$playlists = $pdo->query("SELECT * FROM playlists")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $image = $_POST["image"];
    $audio = $_POST["audio"];
    $thematique_id = $_POST["thematique_id"];
    $auditeur_id = $_POST["auditeur_id"];
    $playlist_id = $_POST["playlist_id"];
    $date = date("Y-m-d");

    $sql = "INSERT INTO episodes
            (titre, description, image, audio, thematique_id, auditeur_id, playlist_id, date_creation)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $titre,
        $description,
        $image,
        $audio,
        $thematique_id,
        $auditeur_id,
        $playlist_id,
        $date
    ]);

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un épisode</title>
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

        <h2>Ajouter un épisode</h2>

        <form method="POST">

            <label>Titre</label>
            <input type="text" name="titre" required>

            <label>Description</label>
            <textarea name="description" required></textarea>

            <label>Image</label>
            <input type="text" name="image"
                   placeholder="URL de l'image">

            <label>Audio</label>
            <input type="text" name="audio"
                   placeholder="URL de l'audio">

            <label>Thématique</label>
            <select name="thematique_id" required>

                <?php foreach ($thematiques as $thematique): ?>

                    <option value="<?= $thematique['id'] ?>">
                        <?= htmlspecialchars($thematique['nom']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <label>Auditeur</label>
            <select name="auditeur_id" required>

                <?php foreach ($auditeurs as $auditeur): ?>

                    <option value="<?= $auditeur['id'] ?>">
                        <?= htmlspecialchars($auditeur['nom']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <label>Playlist</label>
            <select name="playlist_id" required>

                <?php foreach ($playlists as $playlist): ?>

                    <option value="<?= $playlist['id'] ?>">
                        <?= htmlspecialchars($playlist['nom']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <button type="submit">
                Ajouter l'épisode
            </button>

        </form>

    </div>

</main>

</body>
</html>