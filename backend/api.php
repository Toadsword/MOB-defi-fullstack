<?php
require_once __DIR__ . '/api/db_connection.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Ensure this uses the correct user
$db = db(); 

// Update the switch statement to include the /api prefix
switch ($requestUri) {
    case '/api/stations': // Include /api prefix
        if ($requestMethod === 'GET') {
            require __DIR__ . '/api/stations.php';
        }
        break;

    case '/api/create_route': // Include /api prefix
        if ($requestMethod === 'POST') {
            require __DIR__ . '/api/routes/create_route.php';
        }
        break;

    case '/api/analytics': // Include /api prefix
        if ($requestMethod === 'GET') {
            require __DIR__ . '/api/routes/get_analytics.php';
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
}