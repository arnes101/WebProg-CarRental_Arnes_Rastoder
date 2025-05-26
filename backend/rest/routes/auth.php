<?php
require_once __DIR__ . '/../services/AuthService.php';



Flight::route('POST /auth/register', function() {
    file_put_contents('debug.log', "🔍 Reached /auth/register route\n", FILE_APPEND);
    try {
        $data = Flight::request()->data->getData();
        file_put_contents('debug.log', "📥 Received data: " . json_encode($data) . "\n", FILE_APPEND);

        $authService = new AuthService();
        $user = $authService->register($data);

        file_put_contents('debug.log', "✅ Registered user: " . json_encode($user) . "\n", FILE_APPEND);

        Flight::json($user);
    } catch (Exception $e) {
        file_put_contents('debug.log', "❌ Register failed: " . $e->getMessage() . "\n", FILE_APPEND);
        Flight::json(["error" => $e->getMessage()], 400);
    }
});


Flight::route('POST /auth/login', function() {
    try {
        $data = Flight::request()->data->getData();
        $authService = new AuthService();
        $tokenData = $authService->login($data);
        Flight::json($tokenData);
    } catch (Exception $e) {
        Flight::halt(401, json_encode(["error" => $e->getMessage()]));
    }
});

Flight::route('GET /auth/me', function () {
    $user = Flight::get('user'); // This is set by your AuthMiddleware
    Flight::json($user);
});