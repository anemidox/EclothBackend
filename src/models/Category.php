<?php

class Category {

    public $name;
    public $description;
    public $image;

    private $connection;
    private $tableName;

    public function __construct($dbConnection)
    {
        $this->connection = $dbConnection;
        $this->tableName = 'categories';
    }

    public function createCategory() {
        $query = "INSERT INTO " . $this->tableName . " (name, description, image) VALUES (?, ?, ?)";

        $statement = $this->connection->prepare($query);

        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image));

        // Bind parameters
        $statement->bind_param('sss', $this->name, $this->description, $this->image);

        // Execute the query and return the result
        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // read all data

    public function getCategory() {
        $query = "SELECT * FROM " . $this->tableName;

        $obj = $this->connection->prepare($query);

        $obj->execute();

        return $obj->get_result();
    }

}

?>
