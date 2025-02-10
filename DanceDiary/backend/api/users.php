<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

header("Content-Type: application/json");

$database = new Database();
$db = $database->connect();
$user = new User($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST' && isset($_GET['action'])) {
    if ($_GET['action'] === 'register') {
        $data = json_decode(file_get_contents("php://input"), true);

        // Sprawdzenie, czy wymagane pola są obecne
        if (!isset($data['first_name'], $data['last_name'], $data['email'], $data['password'], $data['role'])) {
            http_response_code(400);
            echo json_encode(["message" => "Missing required fields"]);
            exit();
        }

        // Obsługa `studio_id`, jeśli nie przekazano, ustawiamy `NULL`
        $studioId = isset($data['studio_id']) ? $data['studio_id'] : null;

        $result = $user->register($data['first_name'], $data['last_name'], $data['email'], $data['password'], $data['role'], $studioId);

        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Registration failed"]);
        }
        exit();
    } elseif ($_GET['action'] === 'login') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email'], $data['password'])) {
            http_response_code(400);
            echo json_encode(["message" => "Missing email or password"]);
            exit();
        }

        $userInfo = $user->login($data['email'], $data['password']);
        if ($userInfo) {
            echo json_encode(["success" => true, "user" => $userInfo]);
        } else {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Invalid credentials"]);
        }
        exit();
    }
} elseif ($method === 'GET') {
    if (isset($_GET['id'])) {
        $profile = $user->getProfile($_GET['id']);
        if ($profile) {
            echo json_encode($profile);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "User not found"]);
        }
        exit();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'search') {
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        $studio = isset($_GET['studio']) ? $_GET['studio'] : null;
        $results = $user->searchTrainers($name, $studio);
        echo json_encode($results);
        exit();
    } else {
        $users = $user->getAllUsers();
        echo json_encode($users);
        exit();
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed."]);
    exit();
}
?>
