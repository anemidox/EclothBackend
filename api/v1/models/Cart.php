<?php

class Cart {
    private $conn;
    private $table = 'cart';

    public $id;
    public $user_id;
    public $product_id;
    public $quantity;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new cart item
    public function create() {
        $sql = "INSERT INTO " . $this->table . " SET user_id = ?, product_id = ?, quantity = ?, created_at = ?";
        $stmt = $this->conn->prepare($sql);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));

        $stmt->bind_param("iiis", $this->user_id, $this->product_id, $this->quantity, $this->created_at);

        return $stmt->execute();
    }

    // Read all cart items
    public function read() {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Read a single cart item by ID
    public function read_single() {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Update a cart item by ID
    public function update() {
        $sql = "UPDATE " . $this->table . " SET user_id = ?, product_id = ?, quantity = ?, updated_at = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        $stmt->bind_param("iiisi", $this->user_id, $this->product_id, $this->quantity, $this->updated_at, $this->id);

        return $stmt->execute();
    }

    // Delete a cart item by ID
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }
}
?>
