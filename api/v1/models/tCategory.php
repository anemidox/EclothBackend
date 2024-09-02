<?php
// namespace App\Models;

// use PDO;

// class Category {
//     private $conn;
//     private $table = 'categories';

//     public $id;
//     public $name;
//     public $category_url;
//     public $description;

//     public function __construct($db) {
//         $this->conn = $db;
//     }

//     public function create() {
//         $query = "INSERT INTO " . $this->table . " SET name = :name, category_url = :category_url, description = :description";
//         $stmt = $this->conn->prepare($query);

//         $stmt->bindParam(':name', $this->name);
//         $stmt->bindParam(':category_url', $this->category_url);
//         $stmt->bindParam(':description', $this->description);

//         return $stmt->execute();
//     }

//     public function read() {
//         $query = "SELECT * FROM " . $this->table;
//         $stmt = $this->conn->prepare($query);
//         $stmt->execute();
//         return $stmt;
//     }

//     public function read_single() {
//         $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
//         $stmt = $this->conn->prepare($query);
//         $stmt->bindParam(':id', $this->id);
//         $stmt->execute();
//         return $stmt;
//     }

//     public function update() {
//         $query = "UPDATE " . $this->table . " SET name = :name, description = :description WHERE id = :id";
//         $stmt = $this->conn->prepare($query);

//         $stmt->bindParam(':name', $this->name);
//         $stmt->bindParam(':description', $this->description);
//         $stmt->bindParam(':id', $this->id);

//         return $stmt->execute();
//     }

//     public function delete() {
//         $query = "DELETE FROM " . $this->table . " WHERE id = :id";
//         $stmt = $this->conn->prepare($query);
//         $stmt->bindParam(':id', $this->id);
//         return $stmt->execute();
//     }
// }

?>