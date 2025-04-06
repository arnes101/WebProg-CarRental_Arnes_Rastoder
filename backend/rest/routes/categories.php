<?php
require_once __DIR__ . '/../services/CategoryService.php';

Flight::route('GET /categories', function () {
    $service = new CategoryService();
    Flight::json($service->getAllCategories());
});

Flight::route('GET /categories/@id', function ($id) {
    $service = new CategoryService();
    Flight::json($service->getCategoryById($id));
});

Flight::route('POST /categories', function () {
    $data = Flight::request()->data->getData();
    $service = new CategoryService();
    Flight::json($service->createCategory($data));
});

Flight::route('PUT /categories/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $service = new CategoryService();
    Flight::json($service->updateCategory($id, $data));
});

Flight::route('DELETE /categories/@id', function ($id) {
    $service = new CategoryService();
    Flight::json($service->deleteCategory($id));
});
