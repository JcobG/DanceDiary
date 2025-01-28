<?php

require_once '../config/Database.php';
require_once '../models/Note.php';

$database = new Database();
$db = $database->connect();
$note = new Note($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $result = $note->addNote($data['user_id'], $data['content']);
    echo json_encode(["success" => $result]);
} elseif ($method === 'GET' && isset($_GET['user_id'])) {
    $notes = $note->getUserNotes($_GET['user_id']);
    echo json_encode($notes);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed."]);
}

?>
