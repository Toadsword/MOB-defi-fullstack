<?php
require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../utils/json.php';

const DEFAULT_IS_ADMIN = false;
const DEFAULT_VERIFIED = true; // For local testing. In production, set to false.

$params = json_decode(file_get_contents('php://input'), true) ?? [];

$email = trim($params["email"] ?? "");
$password = $params["password"] ?? "";
$password_confirm = $params["passwordConfirm"] ?? "";

// Validation
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(["error" => "Invalid email"], 400);
    exit;
}

if (empty($password) || strlen($password) < 6) {
    json_response(["error" => "Password must be at least 6 characters"], 400);
    exit;
}

if ($password !== $password_confirm) {
    json_response(["error" => "Passwords do not match"], 400);
    exit;
}

// Check if email already exists
$stmt = db()->prepare("SELECT id FROM users WHERE email = :email");
$stmt->execute([":email" => $email]);
if ($stmt->fetch()) {
    json_response(["error" => "Email already registered"], 409);
    exit;
}

// Hash password and create user
$password_hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = db()->prepare("
    INSERT INTO users (email, password_hash, verified, is_admin)
    VALUES (:email, :password_hash, :verified, :is_admin)
    RETURNING id
");

$stmt->execute([
    ":email" => $email,
    ":password_hash" => $password_hash,
    ":verified" => DEFAULT_VERIFIED === true ? 'true' : 'false',  // Auto-verify for local testing
    ":is_admin" => DEFAULT_IS_ADMIN === true ? 'true' : 'false'
]);

$user = $stmt->fetch();

// Start session
session_start();
$_SESSION["user_id"] = $user["id"];
$_SESSION["email"] = $email;
$_SESSION["is_admin"] = false;

json_response(["success" => true, "message" => "Account created successfully"]);