<?php

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../services/PodcastService.php';
require_once __DIR__ . '/../services/ThematiqueService.php';

$resource = $_GET['resource'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
$input = json_decode(file_get_contents('php://input'), true);
$input = is_array($input) ? $input : [];

if ($id === false) {
	http_response_code(422);
	$result = ['erreur' => 'Identifiant invalide.'];
} elseif ($resource === 'podcasts') {
	$service = new PodcastService();
	if ($method === 'GET') {
		$result = $service->listerPodcasts($id === false ? null : $id);
	} elseif ($method === 'POST') {
		$result = $service->ajouterPodcast($input);
	} elseif ($method === 'PUT' && $id !== null && $id !== false) {
		$result = $service->modifierPodcast($id, $input);
	} elseif ($method === 'DELETE' && $id !== null && $id !== false) {
		$result = $service->supprimerPodcast($id);
	} else {
		http_response_code($method === 'PUT' || $method === 'DELETE' ? 422 : 405);
		$result = ['erreur' => 'Action ou identifiant invalide pour les podcasts.'];
	}
} elseif ($resource === 'themes') {
	$service = new ThematiqueService();
	if ($method === 'GET') {
		$result = $service->listerThematiques();
	} elseif ($method === 'POST') {
		$result = $service->ajouterThematique($input);
	} else {
		http_response_code(405);
		$result = ['erreur' => 'Méthode non autorisée pour les thématiques.'];
	}
} else {
	http_response_code(404);
	$result = ['erreur' => 'Ressource introuvable.'];
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);