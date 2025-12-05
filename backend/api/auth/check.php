<?php
require_once __DIR__ . '/../utils/json.php';

session_start();

$is_authenticated = !empty($_SESSION["user_id"]);
$is_admin = $_SESSION["is_admin"] ?? false;

json_response([
    "authenticated" => $is_authenticated,
    "email" => $_SESSION["email"] ?? null,
    "is_admin" => $is_admin
]);