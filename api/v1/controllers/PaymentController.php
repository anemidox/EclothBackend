<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../v1/config/Database.php';
include_once '../../v1/models/Payment.php';

$db = new Database();
$conn = $db->connect();

$payment = new Payment($conn);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->order_id) && !empty($data->payment_method) && !empty($data->amount)) {
            $payment->order_id = $data->order_id;
            $payment->payment_method = $data->payment_method;
            $payment->payment_status = isset($data->payment_status) ? $data->payment_status : 'pending';
            $payment->amount = $data->amount;
            $payment->payment_date = isset($data->payment_date) ? $data->payment_date : null;

            if ($payment->create()) {
                http_response_code(201);
                echo json_encode(array("status" => 1, "message" => "Payment Created"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Payment Not Created"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $payment->id = intval($_GET['id']);
            $result = $payment->read_single();
        } else {
            $result = $payment->read();
        }

        if ($result->num_rows > 0) {
            $payments = array();
            while ($row = $result->fetch_assoc()) {
                $payments[] = $row;
            }
            echo json_encode($payments);
        } else {
            http_response_code(404);
            echo json_encode(array("status" => 0, "message" => "No Payments Found"));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id) && (!empty($data->order_id) || !empty($data->payment_method) || !empty($data->amount))) {
            $payment->id = intval($data->id);
            if (!empty($data->order_id)) $payment->order_id = $data->order_id;
            if (!empty($data->payment_method)) $payment->payment_method = $data->payment_method;
            if (!empty($data->payment_status)) $payment->payment_status = $data->payment_status;
            if (!empty($data->amount)) $payment->amount = $data->amount;
            if (!empty($data->payment_date)) $payment->payment_date = $data->payment_date;

            if ($payment->update()) {
                echo json_encode(array("status" => 1, "message" => "Payment Updated"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Payment Not Updated"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id)) {
            $payment->id = intval($data->id);

            if ($payment->delete()) {
                echo json_encode(array("status" => 1, "message" => "Payment Deleted"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Payment Not Deleted"));
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
