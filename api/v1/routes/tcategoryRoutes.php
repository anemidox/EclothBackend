<?php

use App\Controllers\CategoryController;

$router->get('/categories', [CategoryController::class, 'getCategories']);
$router->get('/categories/{id}', [CategoryController::class, 'getCategoryById']);
$router->post('/categories/create', [CategoryController::class, 'createCategory']);
$router->post('/categories/edit/{id}', [CategoryController::class, 'updateCategory']);
$router->post('/categories/delete/{id}', [CategoryController::class, 'deleteCategory']);


?>