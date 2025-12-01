<?php
require_once __DIR__ . '/db_connection.php';
// Return list of stations as JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // ajuster en prod

try {
    $stmt = db()->query("SELECT shortName, longName FROM stations ORDER BY longName");
    $stations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($stations);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed', 'message' => $e->getMessage()]);
}
?>