<?php

// Set headers to allow access from any origin and define allowed methods
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

// Include database and model files
include_once '../config/Database.php';
include_once '../models/Category.php';

// Initialize the database connection
$database = new Database();
$connection = $database->getConnection();

// Initialize the Category object
$category = new Category($connection);

// Handle GET request to fetch all data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = $category->getCategory();
    $num = $data->num_rows;

    // Check if there are any categories
    if ($num > 0) {
        $categories_arr = array();
        $categories_arr['data'] = array();

        // Fetch categories and build the response array
        while ($row = $data->fetch_assoc()) {
            $category_item = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'image' => $row['image']
            );

            array_push($categories_arr['data'], $category_item);
        }

        // Send the JSON response with all categories
        echo json_encode($categories_arr);
    } else {
        // Send a message if no categories are found
        echo json_encode(array('message' => 'No categories found'));
    }
} else {
    // Send a method not allowed message if the request is not GET
    http_response_code(405);
    echo json_encode(array('message' => 'Method Not Allowed'));
}

?>
