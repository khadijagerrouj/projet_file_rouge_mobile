<?php

require_once "connexion.php";

$id = $_GET["id"];

if (isset($_GET["confirmer"])) {

    $stmt = $pdo->prepare("DELETE FROM episodes WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Supprimer</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="confirmation">

    <h2>Supprimer cet épisode ?</h2>

    <p>Cette action est définitive.</p>

    <a class="btn-danger"
       href="supprimer.php?id=<?= $id ?>&confirmer=1">
        Oui, supprimer
    </a>

    <a class="btn-retour" href="index.php">
        Annuler
    </a>

</div>

</body>
</html>