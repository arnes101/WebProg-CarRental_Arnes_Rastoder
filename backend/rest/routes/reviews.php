<?php
require_once __DIR__ . '/../services/ReviewService.php';

/**
 * @OA\Get(
 *     path="/reviews",
 *     tags={"Reviews"},
 *     summary="Get all reviews",
 *     @OA\Response(
 *         response=200,
 *         description="Get all reviews successful"
 *     )
 * )
 */
Flight::route('GET /reviews', function () {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $service = new ReviewService();
    Flight::json($service->getAllReviews());
});

/**
 * @OA\Get(
 *     path="/reviews/car/{car_id}",
 *     tags={"Reviews"},
 *     summary="Get reviews by car ID",
 *     @OA\Parameter(
 *         name="car_id", in="path", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Get reviews for a car"
 *     )
 * )
 */
Flight::route('GET /reviews/car/@car_id', function ($car_id) {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $service = new ReviewService();
    $allReviews = $service->getAllReviews();
    $carReviews = array_filter($allReviews, function ($r) use ($car_id) {
        return $r['car_id'] == $car_id;
    });
    Flight::json(array_values($carReviews));
});
/**
 * @OA\Get(
 *     path="/reviews/{id}",
 *     tags={"Reviews"},
 *     summary="Get a review by ID",
 *     @OA\Parameter(
 *         name="id", in="path", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Get a review by ID successful"
 *     )
 * )
 */
Flight::route('GET /reviews/@id', function ($id) {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $service = new ReviewService();
    Flight::json($service->getReviewById($id));
});

/**
 * @OA\Post(
 *     path="/reviews",
 *     tags={"Reviews"},
 *     summary="Create a new review",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "car_id", "content"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="car_id", type="integer", example=2),
 *             @OA\Property(property="content", type="string", example="Great car, very clean and efficient!"),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-29T12:00:00")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Create a new review successful"
 *     )
 * )
 */
Flight::route('POST /reviews', function () {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $data = Flight::request()->data->getData();
    $data['created_at'] = date('Y-m-d H:i:s');
    $service = new ReviewService();
    Flight::json($service->createReview($data));
});

/**
 * @OA\Put(
 *     path="/reviews/{id}",
 *     tags={"Reviews"},
 *     summary="Update a review",
 *     @OA\Parameter(
 *         name="id", in="path", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="content", type="string", example="Updated review content"),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-29T13:30:00")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Update a review successful"
 *     )
 * )
 */
Flight::route('PUT /reviews/@id', function ($id) {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $data = Flight::request()->data->getData();
    $data['created_at'] = date('Y-m-d H:i:s'); 
    $service = new ReviewService();
    Flight::json($service->updateReview($id, $data));
});

/**
 * @OA\Delete(
 *     path="/reviews/{id}",
 *     tags={"Reviews"},
 *     summary="Delete a review",
 *     @OA\Parameter(
 *         name="id", in="path", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Delete a review successful"
 *     )
 * )
 */
Flight::route('DELETE /reviews/@id', function ($id) {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $service = new ReviewService();
    Flight::json($service->deleteReview($id));
});
