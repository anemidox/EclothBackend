<?php
class Session {
    private $conn;
    private $table = 'sessions';

    public $id;
    public $user_id;
    public $session_token;
    public $expires_at;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new session
    public function create() {
        $sql = "INSERT INTO " . $this->table . " SET user_id = ?, session_token = ?, expires_at = ?";
        $stmt = $this->conn->prepare($sql);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->session_token = htmlspecialchars(strip_tags($this->session_token));
        $this->expires_at = htmlspecialchars(strip_tags($this->expires_at));

        $stmt->bind_param("iss", $this->user_id, $this->session_token, $this->expires_at);

        return $stmt->execute();
    }

    // Read all sessions
    public function read() {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Read a single session by ID
    public function read_single() {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Update a session by ID
    public function update() {
        $sql = "UPDATE " . $this->table . " SET user_id = ?, session_token = ?, expires_at = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->session_token = htmlspecialchars(strip_tags($this->session_token));
        $this->expires_at = htmlspecialchars(strip_tags($this->expires_at));

        $stmt->bind_param("issi", $this->user_id, $this->session_token, $this->expires_at, $this->id);

        return $stmt->execute();
    }

    // Delete a session by ID
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }
}
?>
