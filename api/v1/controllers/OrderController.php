<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../v1/config/Database.php';
include_once '../../v1/models/Order.php';

$db = new Database();
$conn = $db->connect();

$order = new Order($conn);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->user_id) && !empty($data->total_price)) {
            $order->user_id = $data->user_id;
            $order->total_price = $data->total_price;
            $order->status = isset($data->status) ? $data->status : 'pending';
            $order->created_at = isset($data->created_at) ? $data->created_at : null;
            $order->updated_at = isset($data->updated_at) ? $data->updated_at : null;

            if ($order->create()) {
                http_response_code(201);
                echo json_encode(array("status" => 1, "message" => "Order Created"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Order Not Created"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $order->id = intval($_GET['id']);
            $result = $order->read_single();
        } else {
            $result = $order->read();
        }

        if ($result->num_rows > 0) {
            $orders = array();
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
            echo json_encode($orders);
        } else {
            http_response_code(404);
            echo json_encode(array("status" => 0, "message" => "No Orders Found"));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id) && (!empty($data->user_id) || !empty($data->total_price) || !empty($data->status))) {
            $order->id = intval($data->id);
            if (!empty($data->user_id)) $order->user_id = $data->user_id;
            if (!empty($data->total_price)) $order->total_price = $data->total_price;
            if (!empty($data->status)) $order->status = $data->status;
            if (!empty($data->updated_at)) $order->updated_at = $data->updated_at;

            if ($order->update()) {
                echo json_encode(array("status" => 1, "message" => "Order Updated"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Order Not Updated"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id)) {
            $order->id = intval($data->id);

            if ($order->delete()) {
                echo json_encode(array("status" => 1, "message" => "Order Deleted"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Order Not Deleted"));
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
