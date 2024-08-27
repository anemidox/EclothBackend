<?php 
// Include database and model files
include_once '../config/Database.php'; 
include_once '../models/Category.php'; 

// Set headers for HTTP response
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Initialize the database connection
$database = new Database();
$dbConnection = $database->getConnection();

// Initialize the Category object
$category = new Category($dbConnection);

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Decode the JSON input data
    $inputData = json_decode(file_get_contents("php://input"));

    // Validate the input data
    if (!empty($inputData->name) && !empty($inputData->description) && !empty($inputData->image)) {

        // Assign the input data to the Category object
        $category->name = $inputData->name;
        $category->description = $inputData->description;
        $category->image = $inputData->image;

        // Attempt to create the category
        if ($category->createCategory()) {
            http_response_code(201); // Use 201 for resource creation
            echo json_encode([
                'message' => 'Category created successfully',
                'status' => true
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'message' => 'Failed to create category',
                'status' => false
            ]);
        }
    } else {
        // Respond with error if required fields are missing
        http_response_code(400);
        echo json_encode([
            'message' => 'All fields are required',
            'status' => false
        ]);
    }
} else {
    // Respond with error if the request method is not POST
    http_response_code(405);
    echo json_encode([
        'status' => false,
        'message' => 'Method Not Allowed'
    ]);
}
?>
