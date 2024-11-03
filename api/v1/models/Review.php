<?php

class Review {

    public $id;
    public $product_id;
    public $user_id;
    public $rating;
    public $comment;

    private $conn;
    private $table = 'reviews';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new review
    public function create() {
        $sql = "INSERT INTO " . $this->table . " SET product_id = ?, user_id = ?, rating = ?, comment = ?";
        $stmt = $this->conn->prepare($sql);

        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->rating = htmlspecialchars(strip_tags($this->rating));
        $this->comment = htmlspecialchars(strip_tags($this->comment));

        $stmt->bind_param("iiis", $this->product_id, $this->user_id, $this->rating, $this->comment);

        return $stmt->execute();
    }

    // Get all reviews
    public function read() {
        $sql = "SELECT id, product_id, user_id, rating, comment FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Get a single review by ID
    public function read_single() {
        $sql = "SELECT id, product_id, user_id, rating, comment FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Update a review by ID
    public function update() {
        $sql = "UPDATE " . $this->table . " SET product_id = ?, user_id = ?, rating = ?, comment = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->rating = htmlspecialchars(strip_tags($this->rating));
        $this->comment = htmlspecialchars(strip_tags($this->comment));

        $stmt->bind_param("iiisi", $this->product_id, $this->user_id, $this->rating, $this->comment, $this->id);

        return $stmt->execute();
    }

    // Delete a review by ID
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }

    
}

?>