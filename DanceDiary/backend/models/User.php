<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($firstName, $lastName, $email, $password, $role, $studioId = null) {
        // Sprawdzenie, czy email już istnieje
        $checkEmailQuery = "SELECT email FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($checkEmailQuery);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(["message" => "Email already in use"]);
            exit();
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (first_name, last_name, email, password_hash, role, studio_id) 
                  VALUES (:first_name, :last_name, :email, :password_hash, :role, :studio_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':studio_id', $studioId, PDO::PARAM_INT); //Obsluga NULL

        if ($stmt->execute()) {
            return true;
        }else {
            error_log("Rejestracja nie powiodła się: " . implode(" | ", $stmt->errorInfo()));
            return false;
        }
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
    /*public function getProfile($userId) {
    $query = "SELECT user_id, first_name, last_name, email, role FROM " . $this->table . " WHERE user_id = :user_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $userId);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}*/
    public function getProfile($userId) {
        $query = "SELECT user_id, first_name, last_name, email, role FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            http_response_code(404);
            echo json_encode(["message" => "User not found"]);
            exit();
        }

        echo json_encode($result);
        exit();
    }
    public function searchTrainers($name = null, $studio = null) {
        $query = "SELECT u.first_name || ' ' || u.last_name AS full_name, s.name AS studio_name
              FROM users u
              LEFT JOIN studios s ON u.studio_id = s.studio_id
              WHERE u.role = 'trener'";

        if (!empty($name)) {
            $query .= " AND (u.first_name ILIKE :name OR u.last_name ILIKE :name)";
        }
        if (!empty($studio)) {
            $query .= " AND s.name ILIKE :studio";
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


    public function getAllUsers() {
        $query = "SELECT user_id, first_name, last_name, email, role FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>
