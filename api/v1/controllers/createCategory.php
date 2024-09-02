<?php

// Allow any origin, content type, and method POST
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

include_once '../../v1/config/Database.php';
include_once '../../v1/models/Category.php';

// Instantiate Database and connect
$db = new Database();
$conn = $db->connect();

// Instantiate Category object
$category = new Category($conn);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the raw posted data
    $data = json_decode(file_get_contents('php://input'));

    // Ensure required fields are not empty
    if (!empty($data->name) && !empty($data->category_url) && !empty($data->description)) {
        $category->name = $data->name;
        $category->categorie_url = $data->category_url;
        $category->description = $data->description;

        // Attempt to create the category
        if ($category->create()) {
            // Send a 200 OK response
            http_response_code(200);
            echo json_encode(array(
                "status" => 1,
                "message" => "Category Created"
            ));
        } else {
            // Send a 500 Internal Server Error response
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => "Category Not Created"
            ));
        }
    } else {
        // Send a 400 Bad Request response if required fields are missing
        http_response_code(400);
        echo json_encode(array(
            "status" => 0,
            "message" => "Invalid Data"
        ));
    }
} else {
    // Send a 405 Method Not Allowed response if the method is not POST
    http_response_code(405);
    echo json_encode(array(
        "status" => 0,
        "message" => "Method Not Allowed"
    ));
}

?>
