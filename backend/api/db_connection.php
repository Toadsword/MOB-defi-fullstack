<?php
function db() {
    static $pdo = null;

    if ($pdo === null) {
        $pdo = new PDO(
            "pgsql:host=db;dbname=mydb",
            "postgres",
            "postgres",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
    return $pdo;
}