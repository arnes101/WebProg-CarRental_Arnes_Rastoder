<?php
// Disable deprecated warnings (for PHP 8.2+ compatibility)
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

require __DIR__ . '/../../../vendor/autoload.php';

use OpenApi\Generator;

$openapi = Generator::scan([
    __DIR__ . '/doc_setup.php',
    __DIR__ . '/../../../rest/routes'
]);

header('Content-Type: application/json');
echo $openapi->toJson();
