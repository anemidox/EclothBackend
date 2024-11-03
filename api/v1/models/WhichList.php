<?php
class WhichList {
    private $conn;
    private $table = 'wishlist';

    public $id;
    public $user_id;
    public $product_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new wishlist item
    public function create() {
        $sql = "INSERT INTO " . $this->table . " SET user_id = ?, product_id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));

        $stmt->bind_param("ii", $this->user_id, $this->product_id);

        return $stmt->execute();
    }

    // Read all wishlist items
    public function read() {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Read a single wishlist item by ID
    public function read_single() {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Read wishlist items by user ID
    public function read_by_user() {
        $sql = "SELECT * FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Update a wishlist item by ID
    public function update() {
        $sql = "UPDATE " . $this->table . " SET user_id = ?, product_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));

        $stmt->bind_param("iii", $this->user_id, $this->product_id, $this->id);

        return $stmt->execute();
    }

    // Delete a wishlist item by ID
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }
}
?>
