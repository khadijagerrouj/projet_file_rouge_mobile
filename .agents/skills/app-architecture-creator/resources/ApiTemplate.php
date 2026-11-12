<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../services/{{Entity}}Service.php';

$service = new {{Entity}}Service();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?? [];

if ($method === 'GET') {
    $result = $service->lister{{Entities}}();
} elseif ($method === 'POST') {
    $result = $service->ajouter{{Entity}}($input);
} else {
    http_response_code(405);
    $result = ['success' => false, 'message' => 'Méthode non autorisée.'];
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);