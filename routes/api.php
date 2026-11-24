<?php

use App\Controllers\ClassificationController;

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/ai-text-classifier/public';

$uri = str_replace($basePath, '', $uri);

if ($method === 'POST' && $uri === '/api/classify') {

    $controller = new ClassificationController();

    $controller->classify();

    exit;
}

http_response_code(404);

echo json_encode([
    'success' => false,
    'message' => 'Route not found!'
]);
