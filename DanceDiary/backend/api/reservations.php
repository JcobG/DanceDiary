<?php
/*
require_once '../config/Database.php';
require_once '../models/Reservation.php';
*/
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Reservation.php';

$database = new Database();
$db = $database->connect();
$reservation = new Reservation($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $result = $reservation->addReservation($data['user_id'], $data['trainer_id'], $data['studio_id'], $data['reservation_date']);
        echo json_encode(["success" => $result]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(["message" => $e->getMessage()]);
    }
} elseif ($method === 'GET' && isset($_GET['user_id'])) {
    $reservations = $reservation->getUserReservations($_GET['user_id']);
    echo json_encode($reservations);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed."]);
}

?>
