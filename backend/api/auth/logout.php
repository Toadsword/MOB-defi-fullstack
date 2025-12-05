<?php
require_once __DIR__ . '/../utils/json.php';

session_start();
session_destroy();

json_response(["success" => true, "message" => "Logged out successfully"]);