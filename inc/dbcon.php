<?php

$host = "localhost";
$username = "root";
$password = "dhanujatoor";
$dbname = "ecloth";

$conn = mysqli_connect($host, $username, $password, $dbname);
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>