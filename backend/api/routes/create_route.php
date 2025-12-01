<?php
require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../utils/json.php';
require_once __DIR__ . '/../utils/error.php';
require_once __DIR__ . '/../utils/path.php';

$body = json_decode(file_get_contents("php://input"), true);

$from = $body["fromStationId"] ?? null;
$to = $body["toStationId"] ?? null;
$analytic = $body["analyticCode"] ?? null;

if (!$from || !$to || !$analytic) {
    error_response("INVALID_REQUEST", "Missing required fields");
}

// Build graph
$db = db();
$q = $db->query("SELECT * FROM distances");

$graph = [];
while ($row = $q->fetch()) {
    $graph[$row["fk_parent_stations"]][] = [
        "child" => $row["fk_child_stations"],
        "distance" => (float)$row["distance"]
    ];
}

// Compute shortest path
$result = compute_shortest_path($graph, $from, $to);
if (!$result) {
    error_response("NO_ROUTE", "No path found.");
}

$path = $result["path"];
$distance = $result["distance"];

// Convert PHP array to PostgreSQL array format
$pathArray = '{' . implode(',', array_map(fn($p) => '"' . addslashes($p) . '"', $path)) . '}';

// Insert into DB
$stmt = $db->prepare("
    INSERT INTO anayltics_routes (from_station_id, to_station_id, analytic_code, distance_km, path)
    VALUES (:f, :t, :a, :d, :p)
    RETURNING id, created_at
");

$stmt->execute([
    ":f" => $from,
    ":t" => $to,
    ":a" => $analytic,
    ":d" => $distance,
    ":p" => $pathArray
]);

$row = $stmt->fetch();

// API response
json_response([
    "id" => $row["id"],
    "fromStationId" => $from,
    "toStationId" => $to,
    "analyticCode" => $analytic,
    "distanceKm" => $distance,
    "path" => $path,
    "createdAt" => $row["created_at"]
]);
