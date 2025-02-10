<?php
class Reservation {
    private $conn;
    private $table = "reservations";

    public function __construct($db) {
        $this->conn = $db;
    }
/*public function addReservation($userId, $trainerId, $studioId, $reservationDate) {
    $query = "INSERT INTO " . $this->table . " (user_id, trainer_id, studio_id, reservation_date) 
              VALUES (:user_id, :trainer_id, :studio_id, :reservation_date)";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':trainer_id', $trainerId);
    $stmt->bindParam(':studio_id', $studioId);
    $stmt->bindParam(':reservation_date', $reservationDate);

    return $stmt->execute();
}*/
    public function addReservation($userId, $trainerId, $studioId, $reservationDate) {
        // Sprawdzenie czy użytkownik istnieje
        $checkUserQuery = "SELECT user_id FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($checkUserQuery);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            http_response_code(400);
            echo json_encode(["message" => "User does not exist"]);
            exit();
        }

        // Jeśli użytkownik istnieje dodajemy rezerwację
        $query = "INSERT INTO " . $this->table . " (user_id, trainer_id, studio_id, reservation_date) 
              VALUES (:user_id, :trainer_id, :studio_id, :reservation_date)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':trainer_id', $trainerId);
        $stmt->bindParam(':studio_id', $studioId);
        $stmt->bindParam(':reservation_date', $reservationDate);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
            exit();
        }

        http_response_code(500);
        echo json_encode(["message" => "Failed to add reservation"]);
        exit();
    }

    public function getUserReservations($userId) {
    $query = "SELECT r.reservation_id, r.reservation_date, s.name AS studio_name, t.first_name || ' ' || t.last_name AS trainer_name
              FROM " . $this->table . " r
              JOIN studios s ON r.studio_id = s.studio_id
              JOIN users t ON r.trainer_id = t.user_id
              WHERE r.user_id = :user_id
              ORDER BY r.reservation_date ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $userId);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
