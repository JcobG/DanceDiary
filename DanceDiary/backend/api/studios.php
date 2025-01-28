<?php

require_once '../config/Database.php';
require_once '../models/Studio.php';

$database = new Database();
$db = $database->connect();
$studio = new Studio($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $studios = $studio->getAllStudios();
    echo json_encode($studios);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed."]);
}

?>
