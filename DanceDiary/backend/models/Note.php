<?php
class Note {
    private $conn;
    private $table = "notes";

    public function __construct($db) {
        $this->conn = $db;
    }

  public function addNote($userId, $content) {
      $query = "INSERT INTO " . $this->table . " (user_id, content) 
                VALUES (:user_id, :content)";
      $stmt = $this->conn->prepare($query);
  
      $stmt->bindParam(':user_id', $userId);
      $stmt->bindParam(':content', $content);
  
      return $stmt->execute();
  }


  public function getUserNotes($userId) {
      $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':user_id', $userId);
  
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

}
?>
