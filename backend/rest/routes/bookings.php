<?php
require_once __DIR__ . '/../services/BookingService.php';

/**
 * @OA\Get(
 *     path="/bookings",
 *     tags={"Bookings"},
 *     summary="Get all bookings",
 *     @OA\Response(
 *         response=200,
 *         description="Get all bookings successful"
 *     )
 * )
 */
Flight::route('GET /bookings', function () {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $service = new BookingService();
    Flight::json($service->getAllBookings());
});

/**
 * @OA\Get(
 *     path="/bookings/{id}",
 *     tags={"Bookings"},
 *     summary="Get a booking by ID",
 *     @OA\Parameter(
 *         name="id", in="path", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Get a booking by ID successful"
 *     )
 * )
 */
Flight::route('GET /bookings/@id', function ($id) {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $service = new BookingService();
    Flight::json($service->getBookingById($id));
});

/**
 * @OA\Post(
 *     path="/bookings",
 *     tags={"Bookings"},
 *     summary="Create a new booking",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "car_id", "rental_date", "pickup_location", "phone_number"},
 *             @OA\Property(property="user_id", type="integer", example=4),
 *             @OA\Property(property="car_id", type="integer", example=4),
 *             @OA\Property(property="rental_date", type="string", format="date", example="2025-05-01"),
 *             @OA\Property(property="pickup_location", type="string", example="Airport Terminal A"),
 *             @OA\Property(property="phone_number", type="string", example="+38760123456")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Create a new booking successful"
 *     )
 * )
 */
Flight::route('POST /bookings', function () {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $data = Flight::request()->data->getData();
    $service = new BookingService();
    Flight::json($service->createBooking($data));
});

/**
 * @OA\Put(
 *     path="/bookings/{id}",
 *     tags={"Bookings"},
 *     summary="Update a booking",
 *     @OA\Parameter(
 *         name="id", in="path", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="car_id", type="integer"),
 *             @OA\Property(property="rental_date", type="string", format="date"),
 *             @OA\Property(property="pickup_location", type="string"),
 *             @OA\Property(property="phone_number", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Update a booking successful"
 *     )
 * )
 */
Flight::route('PUT /bookings/@id', function ($id) {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $data = Flight::request()->data->getData();
    $service = new BookingService();
    Flight::json($service->updateBooking($id, $data));
});

/**
 * @OA\Delete(
 *     path="/bookings/{id}",
 *     tags={"Bookings"},
 *     summary="Delete a booking",
 *     @OA\Parameter(
 *         name="id", in="path", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Delete a booking successful"
 *     )
 * )
 */
Flight::route('DELETE /bookings/@id', function ($id) {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRoles(['admin', 'client']);
    $service = new BookingService();
    Flight::json($service->deleteBooking($id));
});
