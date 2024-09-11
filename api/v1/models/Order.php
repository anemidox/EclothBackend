<?php
class Order {
    private $conn;
    private $table = 'orders';

    public $id;
    public $user_id;
    public $total_price;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new order
    public function create() {
        $sql = "INSERT INTO " . $this->table . " SET user_id = ?, total_price = ?, status = ?, created_at = ?, updated_at = ?";
        $stmt = $this->conn->prepare($sql);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->total_price = htmlspecialchars(strip_tags($this->total_price));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        $stmt->bind_param("idsss", $this->user_id, $this->total_price, $this->status, $this->created_at, $this->updated_at);

        return $stmt->execute();
    }

    // Read all orders
    public function read() {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Read a single order by ID
    public function read_single() {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Update an order by ID
    public function update() {
        $sql = "UPDATE " . $this->table . " SET user_id = ?, total_price = ?, status = ?, updated_at = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->total_price = htmlspecialchars(strip_tags($this->total_price));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        $stmt->bind_param("idssi", $this->user_id, $this->total_price, $this->status, $this->updated_at, $this->id);

        return $stmt->execute();
    }

    // Delete an order by ID
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }
}
?>
