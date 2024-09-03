<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password;
    public $first_name;
    public $last_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new user
    public function create() {
        $sql = "INSERT INTO " . $this->table . " SET username = ?, email = ?, password = ?, first_name = ?, last_name = ?";
        $stmt = $this->conn->prepare($sql);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));

        $stmt->bind_param("sssss", $this->username, $this->email, $this->password, $this->first_name, $this->last_name);

        return $stmt->execute();
    }

    // Read all users
    public function read() {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Read a single user by ID
    public function read_single() {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Update a user by ID
    public function update() {
        $sql = "UPDATE " . $this->table . " SET username = ?, email = ?, password = ?, first_name = ?, last_name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));

        $stmt->bind_param("sssssi", $this->username, $this->email, $this->password, $this->first_name, $this->last_name, $this->id);

        return $stmt->execute();
    }

    // Delete a user by ID
    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }
}
?>
