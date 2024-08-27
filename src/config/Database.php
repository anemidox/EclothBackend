<?php

class Database {

    private string $host;
    private string $dbName;
    private string $username;
    private string $password;
    private ?mysqli $connection = null;

    public function __construct()
    {
        $this->host = 'localhost';
        $this->dbName = 'ecloth';
        $this->username = 'root';
        $this->password = 'dhanujatoor';
    }

    public function getConnection(): ?mysqli
    {
        if ($this->connection === null) {
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbName);

            if ($this->connection->connect_error) {
                die("Connection failed: " . $this->connection->connect_error);
            }
        }

        return $this->connection;
    }
}
?>
