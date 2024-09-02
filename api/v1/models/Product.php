<?php

class Product {
    public $id;
    public $category_id;
    public $name;
    public $description;
    public $price;
    public $stock_quantity;
    public $image_url;

    private $conn;
    private $table = 'products';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new product
    public function create() {
        $sql = "INSERT INTO " . $this->table . " (category_id, name, description, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->stock_quantity = htmlspecialchars(strip_tags($this->stock_quantity));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));

        $stmt->bind_param("issdis", $this->category_id, $this->name, $this->description, $this->price, $this->stock_quantity, $this->image_url);

        return $stmt->execute();
    }

    // Read all products
    public function read() {
        $sql = "SELECT id, category_id, name, description, price, stock_quantity, image_url, created_at, updated_at FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Read a single product by ID
    public function read_single() {
        $sql = "SELECT id, category_id, name, description, price, stock_quantity, image_url, created_at, updated_at FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Update a product by ID
    public function update() {
        $sql = "UPDATE " . $this->table . " SET category_id = ?, name = ?, description = ?, price = ?, stock_quantity = ?, image_url = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->stock_quantity = htmlspecialchars(strip_tags($this->stock_quantity));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("issdisi", $this->category_id, $this->name, $this->description, $this->price, $this->stock_quantity, $this->image_url, $this->id);

        return $stmt->execute();
    }

    // Delete a product by ID
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }
}
?>
