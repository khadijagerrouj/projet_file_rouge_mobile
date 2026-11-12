<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../services/{{Entity}}Service.php';

$service = new {{Entity}}Service();
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
$input = json_decode(file_get_contents('php://input'), true) ?? [];

if ($id === false) {
    http_response_code(422);
    $result = ['erreur' => 'Identifiant invalide.'];
} elseif ($method === 'GET') {
    $result = $service->lister{{Entities}}($id);
} elseif ($method === 'POST') {
    $result = $service->ajouter{{Entity}}($input);
} elseif ($method === 'PUT' && $id !== null) {
    $result = $service->modifier{{Entity}}($id, $input);
} elseif ($method === 'DELETE' && $id !== null) {
    $result = $service->supprimer{{Entity}}($id);
} else {
    http_response_code($method === 'PUT' || $method === 'DELETE' ? 422 : 405);
    $result = ['erreur' => 'Méthode ou identifiant non valide.'];
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);