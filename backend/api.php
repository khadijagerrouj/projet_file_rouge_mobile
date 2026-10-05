<?php

declare(strict_types=1);

require_once __DIR__ . '/Podcast.php';
require_once __DIR__ . '/Thematique.php';

header('Content-Type: application/json; charset=utf-8');

const FICHIER_DONNEES = __DIR__ . '/data.json';

function repondre(int $code, array $donnees): void
{
    http_response_code($code);
    echo json_encode($donnees, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function lireDonnees(): array
{
    $contenu = file_get_contents(FICHIER_DONNEES);
    $donnees = json_decode($contenu ?: '', true);

    if (!is_array($donnees)) {
        return ['podcasts' => [], 'thematiques' => []];
    }

    $donnees['podcasts'] = $donnees['podcasts'] ?? [];
    $donnees['thematiques'] = $donnees['thematiques'] ?? [];

    return $donnees;
}

function enregistrerDonnees(array $donnees): bool
{
    $json = json_encode($donnees, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    return $json !== false && file_put_contents(FICHIER_DONNEES, $json, LOCK_EX) !== false;
}

function lireCorpsRequete(): array
{
    if (str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/x-www-form-urlencoded')) {
        return $_POST;
    }

    $corps = json_decode(file_get_contents('php://input') ?: '', true);

    return is_array($corps) ? $corps : [];
}

function nouvelIdentifiant(): string
{
    return bin2hex(random_bytes(8));
}

function normaliserNom(string $nom): string
{
    $majusculesAccentuees = [
        'É' => 'é', 'È' => 'è', 'Ê' => 'ê', 'Ë' => 'ë',
        'À' => 'à', 'Â' => 'â', 'Ä' => 'ä', 'Ù' => 'ù',
        'Û' => 'û', 'Ü' => 'ü', 'Î' => 'î', 'Ï' => 'ï',
        'Ô' => 'ô', 'Ö' => 'ö', 'Ç' => 'ç',
    ];

    if (function_exists('mb_strtolower')) {
        return mb_strtolower($nom, 'UTF-8');
    }

    return strtolower(strtr($nom, $majusculesAccentuees));
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_GET['action'] ?? '';
$donnees = lireDonnees();

if ($method === 'GET' && $action === 'liste') {
    repondre(200, $donnees);
}

$corps = lireCorpsRequete();

if ($method === 'POST' && $action === 'podcast') {
    $titre = trim((string) ($corps['titre'] ?? ''));

    if ($titre === '') {
        repondre(422, ['message' => 'Le titre du podcast est obligatoire.']);
    }

    $podcast = new Podcast(
        nouvelIdentifiant(),
        $titre,
        trim((string) ($corps['description'] ?? '')),
        trim((string) ($corps['url'] ?? '')),
        trim((string) ($corps['thematique'] ?? '')),
        date('Y-m-d')
    );

    $donnees['podcasts'][] = $podcast->toArray();

    if (!enregistrerDonnees($donnees)) {
        repondre(500, ['message' => 'Impossible d’enregistrer le podcast.']);
    }

    repondre(201, ['message' => 'Podcast ajouté.', 'podcast' => $podcast->toArray()]);
}

if ($method === 'POST' && $action === 'thematique') {
    $nom = trim((string) ($corps['nom'] ?? ''));

    if ($nom === '') {
        repondre(422, ['message' => 'Le nom de la thématique est obligatoire.']);
    }

    foreach ($donnees['thematiques'] as $thematiqueExistante) {
        if (normaliserNom($thematiqueExistante['nom']) === normaliserNom($nom)) {
            repondre(409, ['message' => 'Cette thématique existe déjà.']);
        }
    }

    $thematique = new Thematique(nouvelIdentifiant(), $nom);
    $donnees['thematiques'][] = $thematique->toArray();

    if (!enregistrerDonnees($donnees)) {
        repondre(500, ['message' => 'Impossible d’enregistrer la thématique.']);
    }

    repondre(201, ['message' => 'Thématique ajoutée.', 'thematique' => $thematique->toArray()]);
}

if (($method === 'PUT' && $action === 'podcast') || ($method === 'POST' && $action === 'modifier-podcast')) {
    $id = (string) ($corps['id'] ?? '');
    $titre = trim((string) ($corps['titre'] ?? ''));

    if ($id === '' || $titre === '') {
        repondre(422, ['message' => 'Un identifiant et un titre sont obligatoires.']);
    }

    foreach ($donnees['podcasts'] as $index => $podcastExistant) {
        if ($podcastExistant['id'] === $id) {
            $podcastMisAJour = new Podcast(
                $id,
                $titre,
                trim((string) ($corps['description'] ?? '')),
                trim((string) ($corps['url'] ?? '')),
                trim((string) ($corps['thematique'] ?? '')),
                $podcastExistant['date'] ?? date('Y-m-d')
            );

            $donnees['podcasts'][$index] = $podcastMisAJour->toArray();

            if (!enregistrerDonnees($donnees)) {
                repondre(500, ['message' => 'Impossible de modifier le podcast.']);
            }

            repondre(200, ['message' => 'Podcast modifié.', 'podcast' => $podcastMisAJour->toArray()]);
        }
    }

    repondre(404, ['message' => 'Podcast introuvable.']);
}

if ($method === 'DELETE' && $action === 'podcast') {
    $id = (string) ($_GET['id'] ?? '');
    $nombreAvant = count($donnees['podcasts']);
    $donnees['podcasts'] = array_values(array_filter(
        $donnees['podcasts'],
        static fn (array $podcast): bool => $podcast['id'] !== $id
    ));

    if (count($donnees['podcasts']) === $nombreAvant) {
        repondre(404, ['message' => 'Podcast introuvable.']);
    }

    if (!enregistrerDonnees($donnees)) {
        repondre(500, ['message' => 'Impossible de supprimer le podcast.']);
    }

    repondre(200, ['message' => 'Podcast supprimé.']);
}

repondre(404, ['message' => 'Action inconnue.']);
