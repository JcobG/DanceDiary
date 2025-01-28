<?php
class Reservation {
    private $conn;
    private $table = "reservations";

    public function __construct($db) {
        $this->conn = $db;
    }
public function addReservation($userId, $trainerId, $studioId, $reservationDate) {
    $query = "INSERT INTO " . $this->table . " (user_id, trainer_id, studio_id, reservation_date) 
              VALUES (:user_id, :trainer_id, :studio_id, :reservation_date)";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':trainer_id', $trainerId);
    $stmt->bindParam(':studio_id', $studioId);
    $stmt->bindParam(':reservation_date', $reservationDate);

    return $stmt->execute();
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
