<?php

include_once '../Controllers/database.php';
include_once '../Models/User.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

$database = new Database();
$connection = $database->connect();

if ($connection === null) {
    echo json_encode(["message" => "Database connection failed."]);
    exit();
}

$user = new User($connection);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {
        $user->name = htmlspecialchars(strip_tags($data->name));
        $user->email = htmlspecialchars(strip_tags($data->email));
        $user->password = htmlspecialchars(strip_tags($data->password));

        if ($user->create_data()) {
            http_response_code(201); // Created
            echo json_encode(["message" => "User created successfully."]);
        } else {
            http_response_code(503); // Service unavailable
            echo json_encode(["message" => "Unable to create user."]);
        }
    } else {
        http_response_code(400); // Bad request
        echo json_encode(["message" => "Incomplete data."]);
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(["message" => "Method not allowed."]);
}

?>
