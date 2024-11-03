<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Overview</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            height: 80vh;
            padding: 10px;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            padding: 10px;
            width: 80%;
            max-width: 500px;
        }

        h1 {
            color: #343a40;
            margin-bottom: 15px;
            font-size: 2em;
            font-weight: 600;
            text-align: left;
        }

        a {
            display: block;
            font-size: 18px;
            color: #ffffff;
            text-decoration: none;
            margin: 5px 0;
            padding: 12px;
            border-radius: 6px;
            background-color: #007bff;
            transition: background-color 0.3s, box-shadow 0.3s;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        a:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        a:active {
            background-color: #004085;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>API Endpoints</h1>
        <a href="api/v1/controllers/CategoryController.php">Categories</a>
        <a href="api/v1//controllers/UserController.php">Users</a>
        <a href="api/v1/controllers/ProductController.php">Products</a>
        <a href="api/v1/controllers/OrderController.php">Orders</a>
        <a href="api/v1/controllers/OrderController.php">Order Items</a>
        <a href="api/v1/controllers/CartController.php">Cart</a>
        <a href="api/v1/controllers/PaymentController.php">Payments</a>
        <a href="api/v1/controllers/ReviewController.php">Reviews</a>
        <a href="api/v1/controllers/SessionController.php">Sessions</a>
        <a href="api/v1/controllers/WishlistController.php">Wishlists</a>
    </div>
</body>
</html>
