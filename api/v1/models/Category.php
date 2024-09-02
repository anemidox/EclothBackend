<?php

class Category {
    public $id;
    public $name;
    public $description;
    public $category_url;

    private $conn;
    private $table = 'categories';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new category
    public function create() {
        $sql = "INSERT INTO " . $this->table . " SET name = ?, description = ?, categorie_url = ?";
        $stmt = $this->conn->prepare($sql);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_url = htmlspecialchars(strip_tags($this->category_url));

        $stmt->bind_param("sss", $this->name, $this->description, $this->category_url);

        return $stmt->execute();
    }

    // Get all categories
    public function read() {
        $sql = "SELECT id, name, description, categorie_url FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Get a single category by ID
    public function read_single() {
        $sql = "SELECT id, name, description, categorie_url FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Update a category by ID
    public function update() {
        $sql = "UPDATE " . $this->table . " SET name = ?, description = ?, categorie_url = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_url = htmlspecialchars(strip_tags($this->category_url));

        $stmt->bind_param("sssi", $this->name, $this->description, $this->category_url, $this->id);

        return $stmt->execute();
    }

    // Delete a category by ID
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}

?>
