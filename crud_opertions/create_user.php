<?php
require "dbconnect.php";

header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['name'], $input['email'], $input['password'])) {
    http_response_code(400);
    echo json_encode(["error" => "All fields are required"]);
    exit;
}

$name = $input['name'];
$email = $input['email'];
$password = password_hash($input['password'], PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $password]);

    http_response_code(201);
    echo json_encode(["message" => "User created successfully"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to create user: " . $e->getMessage()]);
}