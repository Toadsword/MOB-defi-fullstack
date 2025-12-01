<?php
require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../utils/json.php';

$params = json_decode(file_get_contents('php://input'), true) ?? [];

$from_date = !empty($params["fromDate"]) ? $params["fromDate"] : null;
$to_date = !empty($params["toDate"]) ? $params["toDate"] : null;

$where = [];
$sql_params = [];

//Adding where statement only if the parameter exists
if ($from_date) {
    $where[] = "created_at >= :from_date::timestamp";
    $sql_params[':from_date'] = $from_date;
}
if ($to_date) {
    $where[] = "created_at <= :to_date::timestamp";
    $sql_params[':to_date'] = $to_date;
}

$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

$sql = "
    SELECT 
        from_station_id,
        to_station_id,
        SUM(distance_km) AS total_distance,
        COUNT(*) AS route_taken_count 
    FROM anayltics_routes
    $where_sql
    GROUP BY from_station_id, to_station_id
    ORDER BY route_taken_count DESC, total_distance DESC 
";

$stmt = db()->prepare($sql);
$stmt->execute($sql_params);

$items = [];
while ($row = $stmt->fetch()) {
    $items[] = [
        "fromStation" => $row["from_station_id"],
        "toStation" => $row["to_station_id"],
        "totalDistanceKm" => (float)$row["total_distance"],
        "routeTakenCount" => (int)$row["route_taken_count"]
    ];
}

json_response([
    "from_date" => $from_date,
    "to_date" => $to_date,
    "items" => $items
]);