<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../v1/config/Database.php';
include_once '../../v1/models/Session.php';

$db = new Database();
$conn = $db->connect();

$session = new Session($conn);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->user_id) && !empty($data->session_token) && !empty($data->expires_at)) {
            $session->user_id = $data->user_id;
            $session->session_token = $data->session_token;
            $session->expires_at = $data->expires_at;

            if ($session->create()) {
                http_response_code(201);
                echo json_encode(array("status" => 1, "message" => "Session Created"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Session Not Created"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $session->id = intval($_GET['id']);
            $result = $session->read_single();
        } else {
            $result = $session->read();
        }

        if ($result->num_rows > 0) {
            $sessions = array();
            while ($row = $result->fetch_assoc()) {
                $sessions[] = $row;
            }
            echo json_encode($sessions);
        } else {
            http_response_code(404);
            echo json_encode(array("status" => 0, "message" => "No Sessions Found"));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id) && (!empty($data->user_id) || !empty($data->session_token) || !empty($data->expires_at))) {
            $session->id = intval($data->id);
            if (!empty($data->user_id)) $session->user_id = $data->user_id;
            if (!empty($data->session_token)) $session->session_token = $data->session_token;
            if (!empty($data->expires_at)) $session->expires_at = $data->expires_at;

            if ($session->update()) {
                echo json_encode(array("status" => 1, "message" => "Session Updated"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Session Not Updated"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id)) {
            $session->id = intval($data->id);

            if ($session->delete()) {
                echo json_encode(array("status" => 1, "message" => "Session Deleted"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "Session Not Deleted"));
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
