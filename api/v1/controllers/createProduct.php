<?php

// Include necessary files
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');

include_once '../../v1/config/Database.php';
include_once '../../v1/models/Product.php';

// Instantiate Database and connect
$db = new Database();
$conn = $db->connect();

// Instantiate Product object
$product = new Product($conn);

// Check request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch($request_method) {
    case 'POST':
        // Create a new product
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->category_id) && !empty($data->name) && !empty($data->price) && !empty($data->stock_quantity)) {
            $product->category_id = $data->category_id;
            $product->name = $data->name;
            $product->description = $data->description ?? null;
            $product->price = $data->price;
            $product->stock_quantity = $data->stock_quantity;
            $product->image_url = $data->image_url ?? null;

            if ($product->create()) {
                http_response_code(201);
                echo json_encode(array("status" => 1, "message" => "Product Created"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Product Not Created"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Data"));
        }
        break;
    
    case 'GET':
        if (!empty($_GET['id'])) {
            // Get a single product by ID
            $product->id = $_GET['id'];
            $result = $product->read_single();

            if ($result->num_rows > 0) {
                http_response_code(200);
                echo json_encode($result->fetch_assoc());
            } else {
                http_response_code(404);
                echo json_encode(array("status" => 0, "message" => "Product Not Found"));
            }
        } else {
            // Get all products
            $result = $product->read();

            $products = array();
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }

            http_response_code(200);
            echo json_encode($products);
        }
        break;

    case 'PUT':
        // Update a product by ID
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id) && !empty($data->category_id) && !empty($data->name) && !empty($data->price) && !empty($data->stock_quantity)) {
            $product->id = $data->id;
            $product->category_id = $data->category_id;
            $product->name = $data->name;
            $product->description = $data->description ?? null;
            $product->price = $data->price;
            $product->stock_quantity = $data->stock_quantity;
            $product->image_url = $data->image_url ?? null;

            if ($product->update()) {
                http_response_code(200);
                echo json_encode(array("status" => 1, "message" => "Product Updated"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Product Not Updated"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Data"));
        }
        break;

    case 'DELETE':
        // Delete a product by ID
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id)) {
            $product->id = $data->id;

            if ($product->delete()) {
                http_response_code(200);
                echo json_encode(array("status" => 1, "message" => "Product Deleted"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Product Not Deleted"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Data"));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("status" => 0, "message" => "Method Not Allowed"));
        break;
}
?>
