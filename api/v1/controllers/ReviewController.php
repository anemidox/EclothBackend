<?php

// Headers for allowing cross-origin requests and setting content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

// Include the required files
include_once '../../v1/config/Database.php';
include_once '../../v1/models/Review.php';

// Instantiate the database and the review object
$db = new Database();
$conn = $db->connect();

$review = new Review($conn);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw input data from the request body
    $data = json_decode(file_get_contents('php://input'));

    // Validate that the required fields are present
    if (!empty($data->product_id) && !empty($data->user_id) && !empty($data->rating) && !empty($data->comment)) {
        // Set the review properties
        $review->product_id = $data->product_id;
        $review->user_id = $data->user_id;
        $review->rating = $data->rating;
        $review->comment = $data->comment;

        // Attempt to create the review
        if ($review->create()) {
            // If successful, respond with a 201 status and a success message
            http_response_code(201);
            echo json_encode(array(
                "status" => 1,
                "message" => "Review Created"
            ));
        } else {
            // If there was an error, respond with a 500 status and an error message
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => "Review Not Created"
            ));
        }
    } else {
        // If any required fields are missing, respond with a 400 status and an error message
        http_response_code(400);
        echo json_encode(array(
            "status" => 0,
            "message" => "Invalid Data"
        ));
    }
} else {
    // If the request method is not POST, respond with a 405 status and an error message
    http_response_code(405);
    echo json_encode(array(
        "status" => 0,
        "message" => "Method Not Allowed"
    ));
}

?>
