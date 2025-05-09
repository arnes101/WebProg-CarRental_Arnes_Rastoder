<?php
require_once __DIR__ . '/../services/CategoryService.php';

/**
 * @OA\Get(
 *     path="/categories",
 *     tags={"Categories"},
 *     summary="Get all categories",
 *     @OA\Response(
 *         response=200,
 *         description="Get all categories successful"
 *     )
 * )
 */
Flight::route('GET /categories', function () {
    $service = new CategoryService();
    Flight::json($service->getAllCategories());
});

/**
 * @OA\Get(
 *     path="/categories/{id}",
 *     tags={"Categories"},
 *     summary="Get a category by ID",
 *     @OA\Parameter(
 *         name="id", in="path", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Get a category by ID successful"
 *     )
 * )
 */
Flight::route('GET /categories/@id', function ($id) {
    $service = new CategoryService();
    Flight::json($service->getCategoryById($id));
});

/**
 * @OA\Post(
 *     path="/categories",
 *     tags={"Categories"},
 *     summary="Create a new category",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="SUV")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Create a new category successful"
 *     )
 * )
 */
Flight::route('POST /categories', function () {
    $data = Flight::request()->data->getData();
    $service = new CategoryService();
    Flight::json($service->createCategory($data));
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     tags={"Categories"},
 *     summary="Update a category",
 *     @OA\Parameter(
 *         name="id", in="path", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Luxury")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Update a category successful"
 *     )
 * )
 */
Flight::route('PUT /categories/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $service = new CategoryService();
    Flight::json($service->updateCategory($id, $data));
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     tags={"Categories"},
 *     summary="Delete a category",
 *     @OA\Parameter(
 *         name="id", in="path", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Delete a category successful"
 *     )
 * )
 */
Flight::route('DELETE /categories/@id', function ($id) {
    $service = new CategoryService();
    Flight::json($service->deleteCategory($id));
});
