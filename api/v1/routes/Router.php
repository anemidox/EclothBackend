<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and model files
include_once '../../v1/config/Database.php';
include_once '../../v1/models/Category.php';
include_once '../../v1/models/User.php';
include_once '../../v1/models/Product.php';
include_once '../../v1/models/Order.php';
include_once '../../v1/models/OrderItem.php';
include_once '../../v1/models/Cart.php';
include_once '../../v1/models/Payment.php';
include_once '../../v1/models/Review.php';
include_once '../../v1/models/Session.php';
include_once '../../v1/models/Wishlist.php';

// Initialize database connection
$db = new Database();
$conn = $db->connect();

// Get the requested URL
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Routing
switch (true) {
    // Category routes
    case strpos($request_uri, '/categories') === 0:
        include_once '../../v1/controllers/CategoryController.php';
        break;
    
    // User routes
    case strpos($request_uri, '/users') === 0:
        include_once '../../v1/controllers/UserController.php';
        break;

    // Product routes
    case strpos($request_uri, '/products') === 0:
        include_once '../../v1/controllers/ProductController.php';
        break;
    
    // Order routes
    case strpos($request_uri, '/orders') === 0:
        include_once '../../v1/controllers/OrderController.php';
        break;

    // OrderItem routes
    case strpos($request_uri, '/order-items') === 0:
        include_once '../../v1/controllers/OrderItemController.php';
        break;

    // Cart routes
    case strpos($request_uri, '/cart') === 0:
        include_once '../../v1/controllers/CartController.php';
        break;

    // Payment routes
    case strpos($request_uri, '/payments') === 0:
        include_once '../../v1/controllers/PaymentController.php';
        break;

    // Review routes
    case strpos($request_uri, '/reviews') === 0:
        include_once '../../v1/controllers/ReviewController.php';
        break;

    // Session routes
    case strpos($request_uri, '/sessions') === 0:
        include_once '../../v1/controllers/SessionController.php';
        break;

    // Wishlist routes
    case strpos($request_uri, '/wishlists') === 0:
        include_once '../../v1/controllers/WishlistController.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(array("status" => 0, "message" => "Not Found"));
        break;
}

// Close the database connection
$conn->close();

?>
