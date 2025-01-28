<?php
class Studio {
    private $conn;
    private $table = "studios";

    public function __construct($db) {
        $this->conn = $db;
    }

public function getAllStudios() {
    $query = "SELECT * FROM " . $this->table;
    $stmt = $this->conn->prepare($query);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>
