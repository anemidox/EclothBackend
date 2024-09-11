<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../v1/config/Database.php';
include_once '../../v1/models/User.php';

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->username) && !empty($data->email) && !empty($data->password)) {
            $user->username = $data->username;
            $user->email = $data->email;
            $user->password = password_hash($data->password, PASSWORD_BCRYPT);
            $user->first_name = $data->first_name ?? null;
            $user->last_name = $data->last_name ?? null;

            if ($user->create()) {
                http_response_code(201);
                echo json_encode(array("status" => 1, "message" => "User Created"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "User Not Created"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $user->id = intval($_GET['id']);
            $result = $user->read_single();
        } else {
            $result = $user->read();
        }

        if ($result->num_rows > 0) {
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            echo json_encode($users);
        } else {
            http_response_code(404);
            echo json_encode(array("status" => 0, "message" => "No Users Found"));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id) && (!empty($data->username) || !empty($data->email) || !empty($data->password) || !empty($data->first_name) || !empty($data->last_name))) {
            $user->id = intval($data->id);
            if (!empty($data->username)) $user->username = $data->username;
            if (!empty($data->email)) $user->email = $data->email;
            if (!empty($data->password)) $user->password = password_hash($data->password, PASSWORD_BCRYPT);
            if (!empty($data->first_name)) $user->first_name = $data->first_name;
            if (!empty($data->last_name)) $user->last_name = $data->last_name;

            if ($user->update()) {
                echo json_encode(array("status" => 1, "message" => "User Updated"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "User Not Updated"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => 0, "message" => "Invalid Input"));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!empty($data->id)) {
            $user->id = intval($data->id);

            if ($user->delete()) {
                echo json_encode(array("status" => 1, "message" => "User Deleted"));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => 0, "message" => "User Not Deleted"));
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
