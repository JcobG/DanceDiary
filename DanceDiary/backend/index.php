<?php

// Obsługa CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Obsługa preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

// Obsługa pustego PATH_INFO (np. dla strony głównej)
$path = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];

if (empty($path[0])) {
echo json_encode(["message" => "Welcome to DanceDiary API!"]);
exit();
}

// Przekierowanie do odpowiednich endpointów
switch ($path[0]) {
case 'users':
require_once __DIR__ . '/api/users.php';
break;
case 'reservations':
require_once __DIR__ . '/api/reservations.php';
break;
case 'trainers':
require_once __DIR__ . '/api/trainers.php';
break;
case 'studios':
require_once __DIR__ . '/api/studios.php';
break;
case 'notes':
require_once __DIR__ . '/api/notes.php';
break;
default:
http_response_code(404);
echo json_encode(["message" => "Endpoint not found"]);
}
?>