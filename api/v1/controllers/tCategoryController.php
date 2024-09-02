<?php
// namespace App\Controllers;

// use App\Models\Category;
// use PDO;

// class CategoryController {
//     private $db;
//     private $category;

//     public function __construct() {
//         $this->db = (new \App\Config\Database())->connect();
//         $this->category = new Category($this->db);
//     }

//     public function createCategory() {
//         $data = json_decode(file_get_contents('php://input'), true);

//         if ($data && isset($data['name'], $data['category_url'], $data['description'])) {
//             $this->category->name = $data['name'];
//             $this->category->category_url = $data['category_url'];
//             $this->category->description = $data['description'];

//             if ($this->category->create()) {
//                 echo json_encode(['message' => 'Category Created']);
//             } else {
//                 http_response_code(400);
//                 echo json_encode(['message' => 'Category Not Created']);
//             }
//         } else {
//             http_response_code(400);
//             echo json_encode(['message' => 'Invalid Data']);
//         }
//     }

//     public function getCategories() {
//         $result = $this->category->read();
//         $num = $result->rowCount();

//         if ($num > 0) {
//             $categories_arr = [];
//             while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
//                 $categories_arr[] = $row;
//             }
//             echo json_encode($categories_arr);
//         } else {
//             echo json_encode(['message' => 'No Categories Found']);
//         }
//     }

//     public function getCategoryById($id) {
//         $this->category->id = $id;
//         $result = $this->category->read_single();

//         if ($result->rowCount() > 0) {
//             $row = $result->fetch(PDO::FETCH_ASSOC);
//             echo json_encode($row);
//         } else {
//             echo json_encode(['message' => 'Category Not Found']);
//         }
//     }

//     public function updateCategory($id) {
//         $data = json_decode(file_get_contents('php://input'), true);

//         if ($data && isset($data['name'], $data['description'])) {
//             $this->category->id = $id;
//             $this->category->name = $data['name'];
//             $this->category->description = $data['description'];

//             if ($this->category->update()) {
//                 echo json_encode(['message' => 'Category Updated']);
//             } else {
//                 http_response_code(400);
//                 echo json_encode(['message' => 'Category Not Updated']);
//             }
//         } else {
//             http_response_code(400);
//             echo json_encode(['message' => 'Invalid Data']);
//         }
//     }

//     public function deleteCategory($id) {
//         $this->category->id = $id;

//         if ($this->category->delete()) {
//             echo json_encode(['message' => 'Category Deleted']);
//         } else {
//             http_response_code(400);
//             echo json_encode(['message' => 'Category Not Deleted']);
//         }
//     }
// }
