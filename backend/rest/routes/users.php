<?php
require_once __DIR__ . '/../services/UserService.php';

/**
 * @OA\Get(
 *     path="/users",
 *     tags={"Users"},
 *     summary="Get all users",
 *     @OA\Response(
 *         response=200,
 *         description="Get all users successful"
 *     )
 * )
 */
Flight::route('GET /users', function () {
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRole('admin');

    $user = Flight::get('user');
    file_put_contents('debug.log', "👤 User from token: " . json_encode($user) . "\n", FILE_APPEND);

    $service = new UserService();
    $users = $service->getAllUsers();

    file_put_contents('debug.log', "📋 Users from DB: " . json_encode($users) . "\n", FILE_APPEND);

    header('Content-Type: application/json');
    echo json_encode($users);
});


/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Get a user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Get a user by ID successful"
 *     )
 * )
 */
Flight::route('GET /users/@id', function ($id) {
    
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRole('admin');
    $service = new UserService();
    Flight::json($service->getUserById($id));
});

/**
 * @OA\Post(
 *     path="/users",
 *     tags={"Users"},
 *     summary="Create a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="Jane Doe"),
 *             @OA\Property(property="email", type="string", example="jane@example.com"),
 *             @OA\Property(property="password", type="string", example="secret123"),
 *             @OA\Property(property="role", type="string", example="user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Create a new user successful"
 *     )
 * )
 */
Flight::route('POST /users', function () {
    
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRole('admin');
    $data = Flight::request()->data->getData();
    $service = new UserService();
    Flight::json($service->createUser($data));
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Update a user",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "role"},
 *             @OA\Property(property="name", type="string", example="Jane Doe"),
 *             @OA\Property(property="email", type="string", example="jane@example.com"),
 *             @OA\Property(property="role", type="string", example="admin")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Update a user successful"
 *     )
 * )
 */
Flight::route('PUT /users/@id', function ($id) {
    
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRole('admin');
    $data = Flight::request()->data->getData();
    $service = new UserService();
    Flight::json($service->updateUser($id, $data));
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Delete a user",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Delete a user successful"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function ($id) {
    
    Flight::get('auth_middleware')->verifyToken();
    Flight::get('auth_middleware')->authorizeRole('admin');
    $service = new UserService();
    Flight::json($service->deleteUser($id));
});
