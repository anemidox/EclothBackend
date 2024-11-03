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

        if (isset($data->category_id) && isset($data->category_name) && isset($data->name) && isset($data->price) && isset($data->stock_quantity)) {
            $product->category_id = htmlspecialchars(strip_tags($data->category_id));
            $product->category_name = htmlspecialchars(strip_tags($data->category_name));
            $product->name = htmlspecialchars(strip_tags($data->name));
            $product->description = isset($data->description) ? htmlspecialchars(strip_tags($data->description)) : null;
            $product->price = htmlspecialchars(strip_tags($data->price));
            $product->stock_quantity = htmlspecialchars(strip_tags($data->stock_quantity));
            $product->image_url = isset($data->image_url) ? htmlspecialchars(strip_tags($data->image_url)) : null;

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
        if (isset($_GET['category_id'])) {
            // Get all products by category ID
            $product->category_id = htmlspecialchars(strip_tags($_GET['category_id']));
            $result = $product->read_by_category_id();

            $products = array();
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }

            if (!empty($products)) {
                http_response_code(200);
                echo json_encode($products);
            } else {
                http_response_code(404);
                echo json_encode(array("status" => 0, "message" => "No Products Found in this Category"));
            }

        } elseif (isset($_GET['category_name'])) {
            // Get all products by category name
            $product->category_name = htmlspecialchars(strip_tags($_GET['category_name']));
            $result = $product->read_by_category_name();

            $products = array();
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }

            if (!empty($products)) {
                http_response_code(200);
                echo json_encode($products);
            } else {
                http_response_code(404);
                echo json_encode(array("status" => 0, "message" => "No Products Found in this Category Name"));
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

        if (isset($data->id) && isset($data->category_id) && isset($data->category_name) && isset($data->name) && isset($data->price) && isset($data->stock_quantity)) {
            $product->id = htmlspecialchars(strip_tags($data->id));
            $product->category_id = htmlspecialchars(strip_tags($data->category_id));
            $product->category_name = htmlspecialchars(strip_tags($data->category_name));
            $product->name = htmlspecialchars(strip_tags($data->name));
            $product->description = isset($data->description) ? htmlspecialchars(strip_tags($data->description)) : null;
            $product->price = htmlspecialchars(strip_tags($data->price));
            $product->stock_quantity = htmlspecialchars(strip_tags($data->stock_quantity));
            $product->image_url = isset($data->image_url) ? htmlspecialchars(strip_tags($data->image_url)) : null;

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

        if (isset($data->id)) {
            $product->id = htmlspecialchars(strip_tags($data->id));

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
