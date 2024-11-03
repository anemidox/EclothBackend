<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../v1/config/Database.php';
include_once '../../v1/models/OrderItem.php';

$db = new Database();
$conn = $db->connect();

$orderItem = new OrderItem($conn);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->order_id) && !empty($data->product_id) && !empty($data->quantity) && !empty($data->price)) {
            $orderItem->order_id = $data->order_id;
            $orderItem->product_id = $data->product_id;
            $orderItem->quantity = $data->quantity;
            $orderItem->price = $data->price;
            $orderItem->created_at = isset($data->created_at) ? $data->created_at : null;

            if ($orderItem->create()) {
                http_response_code(201);
                echo json_encode(array("status" => 1, "message" => "Order Item Created"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Order Item Not Created"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $orderItem->id = intval($_GET['id']);
            $result = $orderItem->read_single();
        } else {
            $result = $orderItem->read();
        }

        if ($result->num_rows > 0) {
            $orderItems = array();
            while ($row = $result->fetch_assoc()) {
                $orderItems[] = $row;
            }
            echo json_encode($orderItems);
        } else {
            http_response_code(404);
            echo json_encode(array("status" => 0, "message" => "No Order Items Found"));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id) && (!empty($data->order_id) || !empty($data->product_id) || !empty($data->quantity) || !empty($data->price))) {
            $orderItem->id = intval($data->id);
            if (!empty($data->order_id)) $orderItem->order_id = $data->order_id;
            if (!empty($data->product_id)) $orderItem->product_id = $data->product_id;
            if (!empty($data->quantity)) $orderItem->quantity = $data->quantity;
            if (!empty($data->price)) $orderItem->price = $data->price;
            if (!empty($data->created_at)) $orderItem->created_at = $data->created_at;

            if ($orderItem->update()) {
                echo json_encode(array("status" => 1, "message" => "Order Item Updated"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Order Item Not Updated"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id)) {
            $orderItem->id = intval($data->id);

            if ($orderItem->delete()) {
                echo json_encode(array("status" => 1, "message" => "Order Item Deleted"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Order Item Not Deleted"));
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
