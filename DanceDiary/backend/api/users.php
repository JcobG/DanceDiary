<?php

require_once '../config/Database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST' && isset($_GET['action'])) {
    if ($_GET['action'] === 'register') {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $user->register($data['first_name'], $data['last_name'], $data['email'], $data['password'], $data['role'], $data['studio_id']);
        echo json_encode(["success" => $result]);
    } elseif ($_GET['action'] === 'login') {
        $data = json_decode(file_get_contents("php://input"), true);
        $userInfo = $user->login($data['email'], $data['password']);
        if ($userInfo) {
            echo json_encode(["success" => true, "user" => $userInfo]);
        } else {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Invalid credentials"]);
        }
    }
} elseif ($method === 'GET') {
    if (isset($_GET['id'])) {
        $profile = $user->getProfile($_GET['id']);
        echo json_encode($profile);
    } elseif (isset($_GET['action']) && $_GET['action'] === 'search') {
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        $studio = isset($_GET['studio']) ? $_GET['studio'] : null;
        $results = $user->searchTrainers($name, $studio);
        echo json_encode($results);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed."]);
}

?>
