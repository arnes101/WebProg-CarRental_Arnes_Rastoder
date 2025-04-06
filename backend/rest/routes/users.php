<?php
require_once __DIR__ . '/../services/UserService.php';

Flight::route('GET /users', function () {
    $service = new UserService();
    Flight::json($service->getAllUsers());
});

Flight::route('GET /users/@id', function ($id) {
    $service = new UserService();
    Flight::json($service->getUserById($id));
});

Flight::route('POST /users', function () {
    $data = Flight::request()->data->getData();
    $service = new UserService();
    Flight::json($service->createUser($data));
});

Flight::route('PUT /users/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $service = new UserService();
    Flight::json($service->updateUser($id, $data));
});

Flight::route('DELETE /users/@id', function ($id) {
    $service = new UserService();
    Flight::json($service->deleteUser($id));
});
