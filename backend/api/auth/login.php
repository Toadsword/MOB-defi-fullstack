<?php
require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../utils/json.php';

$params = json_decode(file_get_contents('php://input'), true) ?? [];

$email = trim($params["email"] ?? "");
$password = $params["password"] ?? "";

if (empty($email) || empty($password)) {
    json_response(["error" => "Email and password required"], 400);
    exit;
}

$stmt = db()->prepare("SELECT id, password_hash, verified, is_admin FROM users WHERE email = :email");
$stmt->execute([":email" => $email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user["password_hash"])) {
    json_response(["error" => "Invalid email or password"], 401);
    exit;
}

if (!$user["verified"]) {
    json_response(["error" => "Email not verified"], 403);
    exit;
}

session_start();
$_SESSION["user_id"] = $user["id"];
$_SESSION["email"] = $email;
$_SESSION["is_admin"] = $user["is_admin"];

json_response(["success" => true, "message" => "Logged in successfully"]);