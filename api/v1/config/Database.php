<?php

class Database {
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;

    private $conn;

    public function connect() {
        $this->db_host = 'localhost';
        $this->db_name = 'ecloth';
        $this->db_user = 'root';
        $this->db_pass = 'dhanujatoor';

        $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        } else {
            return $this->conn;
        }
    }
}

?>