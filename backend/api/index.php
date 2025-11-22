<?php
$request = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

switch ($request) {

    // POST /routes
    case "/routes":
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            require __DIR__ . "/routes/createRoute.php";
            exit;
        }
        break;

    // GET /analytics
    case "/analytics":
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            require __DIR__ . "/routes/getAnalytics.php";
            exit;
        }
        break;
}

http_response_code(404);
echo "Not Found";
