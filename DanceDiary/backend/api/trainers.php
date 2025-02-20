<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

header("Content-Type: application/json");

$database = new Database();
$db = $database->connect();
$user = new User($db);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $name = isset($_GET['name']) ? $_GET['name'] : null;
    $studio = isset($_GET['studio']) ? $_GET['studio'] : null;

    $results = $user->searchTrainers($name, $studio);
    echo json_encode($results);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
}
if ($method === 'GET' && isset($path[1]) && $path[0] === 'specializations') {
    // Pobranie specjalizacji trenera
    $trainer_id = intval($path[1]);
    $stmt = $db->prepare("SELECT specialization FROM trainer_specializations WHERE trainer_id = :trainer_id");
    $stmt->execute(['trainer_id' => $trainer_id]);
    $specializations = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($specializations);
} elseif ($method === 'POST' && isset($path[1]) && $path[0] === 'specializations') {
    // Dodanie specjalizacji trenera
    $trainer_id = intval($path[1]);
    $data = json_decode(file_get_contents("php://input"), true);
    $specialization = isset($data['specialization']) ? $data['specialization'] : null;

    if (!$specialization) {
        http_response_code(400);
        echo json_encode(["message" => "Specialization is required"]);
        exit();
    }

    $stmt = $db->prepare("INSERT INTO trainer_specializations (trainer_id, specialization) VALUES (:trainer_id, :specialization)");
    $stmt->execute(['trainer_id' => $trainer_id, 'specialization' => $specialization]);

    echo json_encode(["message" => "Specialization added"]);
} elseif ($method === 'DELETE' && isset($path[1], $path[2]) && $path[0] === 'specializations') {
    // Usunięcie specjalizacji trenera
    $trainer_id = intval($path[1]);
    $specialization = urldecode($path[2]);

    $stmt = $db->prepare("DELETE FROM trainer_specializations WHERE trainer_id = :trainer_id AND specialization = :specialization");
    $stmt->execute(['trainer_id' => $trainer_id, 'specialization' => $specialization]);

    echo json_encode(["message" => "Specialization removed"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid request"]);
}
?>