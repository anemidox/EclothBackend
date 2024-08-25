<?php

class User {
    public $name;
    public $email;
    public $password;

    private $conn;
    private $table_name;

    public function __construct($db) {
        $this->conn = $db;
        $this->table_name = 'users'; // Your table name
    }

    public function create_data() {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (:name, :email, :password)";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // Bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        // Execute query
        return $stmt->execute();
    }
}
?>
