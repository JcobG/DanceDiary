<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

header("Content-Type: application/json");

$database = new Database();
$db = $database->connect();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $name = isset($_GET['name']) ? $_GET['name'] : null;
    $studio = isset($_GET['studio']) ? $_GET['studio'] : null;

    $results = $user->searchTrainers($name, $studio);
    echo json_encode($results);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
}
?>