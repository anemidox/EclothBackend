<?php

class Database {
    private $host = "localhost"; // Change as needed
    private $db_name = "ecloth"; // Your database name
    private $username = "root"; // Your database username
    private $password = "dhanujatoor"; // Your database password
    public $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
