<?php

header("Content-Type: application/json; charset=UTF-8");

$dsn = "pgsql:host=db;port=5432;dbname=mydb;";
$user = "user";
$pass = "pass";

try {
    $db = new PDO($dsn, $user, $pass);

    $stmt = $db->query("SELECT 'Hello from Postgres through PHP!' AS message");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
