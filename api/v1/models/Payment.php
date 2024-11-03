<?php
class Payment {
    private $conn;
    private $table = 'payments';

    public $id;
    public $order_id;
    public $payment_method;
    public $payment_status;
    public $amount;
    public $payment_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new payment
    public function create() {
        $sql = "INSERT INTO " . $this->table . " SET order_id = ?, payment_method = ?, payment_status = ?, amount = ?, payment_date = ?";
        $stmt = $this->conn->prepare($sql);

        $this->order_id = htmlspecialchars(strip_tags($this->order_id));
        $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));
        $this->payment_status = htmlspecialchars(strip_tags($this->payment_status));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->payment_date = htmlspecialchars(strip_tags($this->payment_date));

        $stmt->bind_param("issds", $this->order_id, $this->payment_method, $this->payment_status, $this->amount, $this->payment_date);

        return $stmt->execute();
    }

    // Read all payments
    public function read() {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Read a single payment by ID
    public function read_single() {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Update a payment by ID
    public function update() {
        $sql = "UPDATE " . $this->table . " SET order_id = ?, payment_method = ?, payment_status = ?, amount = ?, payment_date = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->order_id = htmlspecialchars(strip_tags($this->order_id));
        $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));
        $this->payment_status = htmlspecialchars(strip_tags($this->payment_status));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->payment_date = htmlspecialchars(strip_tags($this->payment_date));

        $stmt->bind_param("issdsi", $this->order_id, $this->payment_method, $this->payment_status, $this->amount, $this->payment_date, $this->id);

        return $stmt->execute();
    }

    // Delete a payment by ID
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }
}
?>
