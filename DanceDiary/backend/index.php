<?php

header("Content-Type: application/json");

// Dynamiczny routing
$path = explode('/', trim($_SERVER['PATH_INFO'], '/'));

if (empty($path[0])) {<?php

header("Content-Type: application/json");

// Dynamiczny routing
//$path = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$path = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];

if (empty($path[0])) {
    echo json_encode(["message" => "Welcome to DanceDiary API!"]);
    exit();
}

// Przekierowanie do odpowiednich endpointów
switch ($path[0]) {
    case 'users':
        require_once 'api/users.php';
        break;
    case 'reservations':
        require_once 'api/reservations.php';
        break;
    case 'studios':
        require_once 'api/studios.php';
        break;
    case 'notes':
        require_once 'api/notes.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(["message" => "Endpoint not found"]);
}
?>

    echo json_encode(["message" => "Welcome to DanceDiary API!"]);
    exit();
}

// Przekierowanie do odpowiednich endpointów
switch ($path[0]) {
    case 'users':
        require_once 'api/users.php';
        break;
    case 'reservations':
        require_once 'api/reservations.php';
        break;
    case 'studios':
        require_once 'api/studios.php';
        break;
    case 'notes':
        require_once 'api/notes.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(["message" => "Endpoint not found"]);
}
?>
