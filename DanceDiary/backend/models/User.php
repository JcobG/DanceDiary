<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($firstName, $lastName, $email, $password, $role, $studioId = null) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO " . $this->table . " (first_name, last_name, email, password_hash, role, studio_id) 
                  VALUES (:first_name, :last_name, :email, :password_hash, :role, :studio_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':studio_id', $studioId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($email, $password) {
        $query = "SELECT user_id, first_name, last_name, email, role, password_hash FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);

        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            unset($user['password_hash']);
            return $user;
        }
        return null;
    }
    public function getProfile($userId) {
    $query = "SELECT user_id, first_name, last_name, email, role FROM " . $this->table . " WHERE user_id = :user_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $userId);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function searchTrainers($name = null, $studio = null) {
    $query = "SELECT full_name, studio_name FROM trainer_search_view WHERE 1=1";

    if (!empty($name)) {
        $query .= " AND full_name ILIKE :name";
    }
    if (!empty($studio)) {
        $query .= " AND studio_name ILIKE :studio";
    }

    $stmt = $this->conn->prepare($query);

    if (!empty($name)) {
        $name = "%" . $name . "%";
        $stmt->bindParam(':name', $name);
    }
    if (!empty($studio)) {
        $studio = "%" . $studio . "%";
        $stmt->bindParam(':studio', $studio);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
