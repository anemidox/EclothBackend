<?php
class OrderItem {
    private $conn;
    private $table = 'order_items';

    public $id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $price;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new order item
    public function create() {
        $sql = "INSERT INTO " . $this->table . " SET order_id = ?, product_id = ?, quantity = ?, price = ?, created_at = ?";
        $stmt = $this->conn->prepare($sql);

        $this->order_id = htmlspecialchars(strip_tags($this->order_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));

        $stmt->bind_param("iiids", $this->order_id, $this->product_id, $this->quantity, $this->price, $this->created_at);

        return $stmt->execute();
    }

    // Read all order items
    public function read() {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Read a single order item by ID
    public function read_single() {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Update an order item by ID
    public function update() {
        $sql = "UPDATE " . $this->table . " SET order_id = ?, product_id = ?, quantity = ?, price = ?, created_at = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->order_id = htmlspecialchars(strip_tags($this->order_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));

        $stmt->bind_param("iiidsi", $this->order_id, $this->product_id, $this->quantity, $this->price, $this->created_at, $this->id);

        return $stmt->execute();
    }

    // Delete an order item by ID
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }
}
?>
