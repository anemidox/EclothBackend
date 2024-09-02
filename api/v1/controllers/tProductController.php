<?php
namespace App\Controllers;

use App\Models\Product;
use PDO;

class ProductController {
    private $db;
    private $product;

    public function __construct() {
        $this->db = (new \App\Config\Database())->connect();
        $this->product = new Product($this->db);
    }

    public function createProduct() {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data && isset($data['name'], $data['category_id'], $data['price'], $data['description'], $data['image_url'])) {
            $this->product->name = $data['name'];
            $this->product->category_id = $data['category_id'];
            $this->product->price = $data['price'];
            $this->product->description = $data['description'];
            $this->product->image_url = $data['image_url'];

            if ($this->product->create()) {
                echo json_encode(['message' => 'Product Created']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Product Not Created']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid Data']);
        }
    }

    public function getProducts() {
        $result = $this->product->read();
        $num = $result->rowCount();

        if ($num > 0) {
            $products_arr = [];
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $products_arr[] = $row;
            }
            echo json_encode($products_arr);
        } else {
            echo json_encode(['message' => 'No Products Found']);
        }
    }

    public function getProductById($id) {
        $this->product->id = $id;
        $result = $this->product->read_single();

        if ($result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            echo json_encode($row);
        } else {
            echo json_encode(['message' => 'Product Not Found']);
        }
    }

    public function updateProduct($id) {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data && isset($data['name'], $data['category_id'], $data['price'], $data['description'], $data['image_url'])) {
            $this->product->id = $id;
            $this->product->name = $data['name'];
            $this->product->category_id = $data['category_id'];
            $this->product->price = $data['price'];
            $this->product->description = $data['description'];
            $this->product->image_url = $data['image_url'];

            if ($this->product->update()) {
                echo json_encode(['message' => 'Product Updated']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Product Not Updated']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid Data']);
        }
    }

    public function deleteProduct($id) {
        $this->product->id = $id;

        if ($this->product->delete()) {
            echo json_encode(['message' => 'Product Deleted']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product Not Deleted']);
        }
    }
}

