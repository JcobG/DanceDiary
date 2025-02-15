<?php

class Database {
    private $host = "localhost";
    private $db_name = "dance_diary";
    //private $username = "root";
    private $username = "jacobs";

    private $password = "";
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
            exit();
        }
        return $this->conn;
    }
}
?>
