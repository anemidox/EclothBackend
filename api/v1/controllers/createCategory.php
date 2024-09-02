<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../v1/config/Database.php';
include_once '../../v1/models/Category.php';

$db = new Database();
$conn = $db->connect();

$category = new Category($conn);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->name) && !empty($data->category_url) && !empty($data->description)) {
            $category->name = $data->name;
            $category->category_url = $data->category_url;
            $category->description = $data->description;

            if ($category->create()) {
                http_response_code(201);
                echo json_encode(array("status" => 1, "message" => "Category Created"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Category Not Created"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $category->id = intval($_GET['id']);
            $result = $category->read_single();
        } else {
            $result = $category->read();
        }

        if ($result->num_rows > 0) {
            $categories = array();
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
            echo json_encode($categories);
        } else {
            http_response_code(404);
            echo json_encode(array("status" => 0, "message" => "No Categories Found"));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id) && (!empty($data->name) || !empty($data->description) || !empty($data->category_url))) {
            $category->id = intval($data->id);
            if (!empty($data->name)) $category->name = $data->name;
            if (!empty($data->description)) $category->description = $data->description;
            if (!empty($data->category_url)) $category->category_url = $data->category_url;

            if ($category->update()) {
                echo json_encode(array("status" => 1, "message" => "Category Updated"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Category Not Updated"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id)) {
            $category->id = intval($data->id);

            if ($category->delete()) {
                echo json_encode(array("status" => 1, "message" => "Category Deleted"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Category Not Deleted"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("status" => 0, "message" => "Method Not Allowed"));
        break;
}

$conn->close();

?>
