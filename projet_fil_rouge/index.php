<?php

require_once "connexion.php";

$sql = "SELECT episodes.*,
               auditeurs.nom AS auditeur,
               thematiques.nom AS thematique,
               playlists.nom AS playlist
        FROM episodes
        JOIN auditeurs ON episodes.auditeur_id = auditeurs.id
        JOIN thematiques ON episodes.thematique_id = thematiques.id
        JOIN playlists ON episodes.playlist_id = playlists.id
        ORDER BY episodes.id DESC";

$stmt = $pdo->query($sql);
$episodes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Podcasts</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>🎙️ Podcastia</h1>

    <nav>
        <a href="index.php">Accueil</a>
        <a href="ajouter.php">Ajouter un épisode</a>
    </nav>
</header>

<main>

    <h2>Nos épisodes</h2>

    <div class="episodes">

        <?php foreach ($episodes as $episode): ?>

            <div class="card">

                <?php if (!empty($episode['image'])): ?>
                    <img src="<?= htmlspecialchars($episode['image']) ?>" alt="Podcast">
                <?php else: ?>
                    <div class="image-vide">🎙️</div>
                <?php endif; ?>

                <div class="card-content">

                    <h3>
                        <?= htmlspecialchars($episode['titre']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($episode['description']) ?>
                    </p>

                    <span>
                        🎧 <?= htmlspecialchars($episode['thematique']) ?>
                    </span>

                    <span>
                        👤 <?= htmlspecialchars($episode['auditeur']) ?>
                    </span>

                    <span>
                        📅 <?= htmlspecialchars($episode['date_creation']) ?>
                    </span>

                    <?php if (!empty($episode['audio'])): ?>
                        <audio controls>
                            <source src="<?= htmlspecialchars($episode['audio']) ?>">
                        </audio>
                    <?php endif; ?>

                    <div class="actions">

                        <a href="modifier.php?id=<?= $episode['id'] ?>">
                            Modifier
                        </a>

                        <a href="supprimer.php?id=<?= $episode['id'] ?>">
                            Supprimer
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</main>

<footer>
    <p>© 2026 - Gestion des Podcasts</p>
</footer>

</body>
</html>