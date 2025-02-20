<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Obsługa preflight OPTIONS request dla PUT
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Włącz debugowanie błędów
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$database = new Database();
$db = $database->connect();
$user = new User($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST' && isset($_GET['action'])) {
    if ($_GET['action'] === 'register') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['first_name'], $data['last_name'], $data['email'], $data['password'], $data['role'])) {
            http_response_code(400);
            echo json_encode(["message" => "Missing required fields"]);
            exit();
        }

        $studioId = isset($data['studio_id']) ? $data['studio_id'] : null;
        $result = $user->register($data['first_name'], $data['last_name'], $data['email'], $data['password'], $data['role'], $studioId);

        if ($result) {
            // Pobranie ID nowo utworzonego użytkownika
            $newUserId = $db->lastInsertId();

            // Tworzenie pustego profilu w user_profiles
            $stmt = $db->prepare("INSERT INTO user_profiles (user_id, phone_number, birthdate, bio) 
                          VALUES (:user_id, NULL, NULL, NULL)");
            $stmt->execute(['user_id' => $newUserId]);

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
        // Pobranie profilu użytkownika
        $user_id = intval($_GET['id']);
        error_log("Pobieranie profilu dla user_id: " . $user_id);

        $stmt = $db->prepare("SELECT * FROM user_profiles WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($profile) {
            echo json_encode($profile);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Profile not found"]);
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
} elseif ($method === 'PUT' && isset($_GET['id'])) {
    // Aktualizacja profilu użytkownika
    $user_id = intval($_GET['id']);
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid input"]);
        exit();
    }

    error_log("Aktualizacja profilu dla ID: " . $user_id);
    error_log("Dane JSON: " . file_get_contents("php://input"));

    $stmt = $db->prepare("UPDATE user_profiles 
                          SET phone_number = :phone, birthdate = :birthdate, bio = :bio 
                          WHERE user_id = :user_id");

    $stmt->execute([
        'phone' => isset($data['phone_number']) ? $data['phone_number'] : null,
        'birthdate' => isset($data['birthdate']) ? $data['birthdate'] : null,
        'bio' => isset($data['bio']) ? $data['bio'] : null,
        'user_id' => $user_id
    ]);

    error_log("rowCount: " . $stmt->rowCount());

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Profile updated"]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made or user not found"]);
    }
    exit();
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed."]);
    exit();
}
?>
