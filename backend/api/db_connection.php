<?php
function db() {
    $host = 'db';  // Docker service name instead of localhost
    $port = 5432;
    $db = getenv('DB_NAME') ?: 'mydb';
    $user = getenv('DB_USER') ?: 'admin_user';
    $pass = getenv('DB_PASS') ?: 'admin_pass';
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    return new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
?>