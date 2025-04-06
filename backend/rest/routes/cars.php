<?php
require_once __DIR__ . '/../services/CarService.php';

Flight::route('GET /cars', function () {
    $service = new CarService(Flight::get('pdo'));
    Flight::json($service->getAllCars());
});

Flight::route('GET /cars/@id', function ($id) {
    $service = new CarService(Flight::get('pdo'));
    Flight::json($service->getCarById($id));
});

Flight::route('POST /cars', function () {
    $data = Flight::request()->data->getData();
    $service = new CarService(Flight::get('pdo'));
    Flight::json($service->createCar($data));
});

Flight::route('PUT /cars/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $service = new CarService(Flight::get('pdo'));
    Flight::json($service->updateCar($id, $data));
});

Flight::route('DELETE /cars/@id', function ($id) {
    $service = new CarService(Flight::get('pdo'));
    Flight::json($service->deleteCar($id));
});
