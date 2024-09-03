<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../v1/config/Database.php';
include_once '../../v1/models/WhichList.php';

$db = new Database();
$conn = $db->connect();

$wishlist = new WhichList($conn);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->user_id) && !empty($data->product_id)) {
            $wishlist->user_id = $data->user_id;
            $wishlist->product_id = $data->product_id;

            if ($wishlist->create()) {
                http_response_code(201);
                echo json_encode(array("status" => 1, "message" => "Wishlist Item Created"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Wishlist Item Not Created"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $wishlist->id = intval($_GET['id']);
            $result = $wishlist->read_single();
        } else {
            $result = $wishlist->read();
        }

        if ($result->num_rows > 0) {
            $wishlist_items = array();
            while ($row = $result->fetch_assoc()) {
                $wishlist_items[] = $row;
            }
            echo json_encode($wishlist_items);
        } else {
            http_response_code(404);
            echo json_encode(array("status" => 0, "message" => "No Wishlist Items Found"));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id) && (!empty($data->user_id) || !empty($data->product_id))) {
            $wishlist->id = intval($data->id);
            if (!empty($data->user_id)) $wishlist->user_id = $data->user_id;
            if (!empty($data->product_id)) $wishlist->product_id = $data->product_id;

            if ($wishlist->update()) {
                echo json_encode(array("status" => 1, "message" => "Wishlist Item Updated"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Wishlist Item Not Updated"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id)) {
            $wishlist->id = intval($data->id);

            if ($wishlist->delete()) {
                echo json_encode(array("status" => 1, "message" => "Wishlist Item Deleted"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Wishlist Item Not Deleted"));
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
