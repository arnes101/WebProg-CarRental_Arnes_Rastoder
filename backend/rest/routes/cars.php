<?php
require_once __DIR__ . '/../services/CarService.php';

/**
 * @OA\Get(
 *     path="/cars",
 *     tags={"Cars"},
 *     summary="Get all cars",
 *     @OA\Response(
 *         response=200,
 *         description="Get all cars successful"
 *     )
 * )
 */
Flight::route('GET /cars', function () {
    $service = new CarService(Flight::get('pdo'));
    Flight::json($service->getAllCars());
});

/**
 * @OA\Get(
 *     path="/cars/{id}",
 *     tags={"Cars"},
 *     summary="Get a car by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Get a car by ID successful"
 *     )
 * )
 */
Flight::route('GET /cars/@id', function ($id) {
    $service = new CarService(Flight::get('pdo'));
    Flight::json($service->getCarById($id));
});

/**
 * @OA\Post(
 *     path="/cars",
 *     tags={"Cars"},
 *     summary="Create a new car",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "price_per_day", "kilometers", "fuel_consumption", "category_id"},
 *             @OA\Property(property="name", type="string", example="Toyota Yaris"),
 *             @OA\Property(property="price_per_day", type="number", format="float", example=39.99),
 *             @OA\Property(property="kilometers", type="integer", example=45000),
 *             @OA\Property(property="fuel_consumption", type="string", example="5.8L/100km"),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="image_url", type="string", example="https://example.com/yaris.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Create a new car successful"
 *     )
 * )
 */
Flight::route('POST /cars', function () {
    $data = Flight::request()->data->getData();
    $service = new CarService(Flight::get('pdo'));
    Flight::json($service->createCar($data));
});

/**
 * @OA\Put(
 *     path="/cars/{id}",
 *     tags={"Cars"},
 *     summary="Update a car",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="price_per_day", type="number", format="float"),
 *             @OA\Property(property="kilometers", type="integer"),
 *             @OA\Property(property="fuel_consumption", type="string"),
 *             @OA\Property(property="category_id", type="integer"),
 *             @OA\Property(property="image_url", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Update a car successful"
 *     )
 * )
 */
Flight::route('PUT /cars/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $service = new CarService(Flight::get('pdo'));
    Flight::json($service->updateCar($id, $data));
});

/**
 * @OA\Delete(
 *     path="/cars/{id}",
 *     tags={"Cars"},
 *     summary="Delete a car",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Delete a car successful"
 *     )
 * )
 */
Flight::route('DELETE /cars/@id', function ($id) {
    $service = new CarService(Flight::get('pdo'));
    Flight::json($service->deleteCar($id));
});
