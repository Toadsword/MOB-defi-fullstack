<?php
require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../utils/json.php';

$params = $_GET;

$from_date = $params["fromDate"] ?? null;
$to_date = $params["toDate"] ?? null;
$group = $params["groupBy"] ?? "none";

$groupSql = match($group) {
    "day" => "TO_CHAR(created_at, 'YYYY-MM-DD')",
    "month" => "TO_CHAR(created_at, 'YYYY-MM')",
    "year" => "TO_CHAR(created_at, 'YYYY')",
    default => "NULL"
};

$sql = "
    SELECT analytic_code,
           SUM(distance_km) AS total,
           MIN(created_at)::date AS start_date,
           MAX(created_at)::date AS end_date,
           $groupSql AS grp
    FROM anayltics_routes
    WHERE (:from_date IS NULL OR created_at >= :from_date)
      AND (:to_date IS NULL OR created_at <= :to_date)
    GROUP BY analytic_code, grp
    ORDER BY start_date
";

$stmt = db()->prepare($sql);
$stmt->execute([
    ":from_date" => $from_date,
    ":to_date" => $to_date
]);

$items = [];
while ($row = $stmt->fetch()) {
    $items[] = [
        "analyticCode" => $row["analytic_code"],
        "totalDistanceKm" => (float)$row["total"],
        "periodStart" => $row["start_date"],
        "periodEnd" => $row["end_date"],
        "group" => $row["grp"]
    ];
}

json_response([
    "from_date" => $from_date,
    "to_date" => $to_date,
    "groupBy" => $group,
    "items" => $items
]);
