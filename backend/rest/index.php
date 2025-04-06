<?php
require 'vendor/autoload.php';
require 'config/Database.php';


$db = new Database();
$pdo = $db->connect();
Flight::set('pdo', $pdo);

require 'routes/users.php';
require 'routes/categories.php';
require 'routes/cars.php';
require 'routes/bookings.php';
require 'routes/reviews.php';



Flight::route('GET /test', function() {
    echo "🚀 FlightPHP is working!";
});


Flight::map('notFound', function(){
    echo '🚫 Custom 404: Route not found by FlightPHP.';
});

Flight::start();
