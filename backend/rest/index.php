<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/utils/Roles.php';

// Set DB connection globally
$db = new Database();
$pdo = $db->connect();
Flight::set('pdo', $pdo);

// Load auth routes BEFORE middleware so they're not intercepted
require 'routes/auth.php';

// Set middleware for all non-auth routes
Flight::set('auth_middleware', new AuthMiddleware());
Flight::route('/*', function () {
    $route = Flight::request()->url;
    file_put_contents("debug.log", "Intercepted relative path: $route\n", FILE_APPEND);

    if (!preg_match('#^/auth#', $route)) {
        Flight::get('auth_middleware')->verifyToken();
    }

    return true; // ✅ This allows the matched route to continue
});



// Load other route files AFTER middleware
require 'routes/users.php';
require 'routes/categories.php';
require 'routes/cars.php';
require 'routes/bookings.php';
require 'routes/reviews.php';

Flight::route('GET /test', function () {
    echo "🚀 FlightPHP is working!";
});

Flight::map('notFound', function () {
    echo '🚫 Custom 404: Route not found by FlightPHP.';
});

Flight::start();
