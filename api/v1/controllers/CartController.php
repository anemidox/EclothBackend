<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../v1/config/Database.php';
include_once '../../v1/models/Cart.php';

$db = new Database();
$conn = $db->connect();

$cart = new Cart($conn);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->user_id) && !empty($data->product_id) && !empty($data->quantity)) {
            $cart->user_id = $data->user_id;
            $cart->product_id = $data->product_id;
            $cart->quantity = $data->quantity;
            $cart->created_at = isset($data->created_at) ? $data->created_at : null;

            if ($cart->create()) {
                http_response_code(201);
                echo json_encode(array("status" => 1, "message" => "Cart Item Created"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Cart Item Not Created"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $cart->id = intval($_GET['id']);
            $result = $cart->read_single();
        } else {
            $result = $cart->read();
        }

        if ($result->num_rows > 0) {
            $cartItems = array();
            while ($row = $result->fetch_assoc()) {
                $cartItems[] = $row;
            }
            echo json_encode($cartItems);
        } else {
            http_response_code(404);
            echo json_encode(array("status" => 0, "message" => "No Cart Items Found"));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id) && (!empty($data->user_id) || !empty($data->product_id) || !empty($data->quantity))) {
            $cart->id = intval($data->id);
            if (!empty($data->user_id)) $cart->user_id = $data->user_id;
            if (!empty($data->product_id)) $cart->product_id = $data->product_id;
            if (!empty($data->quantity)) $cart->quantity = $data->quantity;
            if (!empty($data->updated_at)) $cart->updated_at = $data->updated_at;

            if ($cart->update()) {
                echo json_encode(array("status" => 1, "message" => "Cart Item Updated"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Cart Item Not Updated"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id)) {
            $cart->id = intval($data->id);

            if ($cart->delete()) {
                echo json_encode(array("status" => 1, "message" => "Cart Item Deleted"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Cart Item Not Deleted"));
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
