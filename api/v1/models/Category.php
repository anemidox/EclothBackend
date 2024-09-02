<?php

class Category {
    public $name;
    public $description;
    public $categorie_url; // Keep this consistent with the property name used in the class

    private $conn;
    private $table;

    public function __construct($db) {
        $this->conn = $db;
        $this->table = 'categories';
    }

    public function create() {
        // Corrected SQL query string with a space after the table name
        $sql = "INSERT INTO " . $this->table . " SET name = ?, description = ?, categorie_url = ?";

        $stmt = $this->conn->prepare($sql);

        // Sanitize the input data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->categorie_url = htmlspecialchars(strip_tags($this->categorie_url));

        // Bind parameters
        $stmt->bind_param("sss", $this->name, $this->description, $this->categorie_url);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>
