<?php
require "dbconnect.php";

header("Content-Type: application/json");
$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "id required"]);
    exit;
}

$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        http_response_code(200);
        echo json_encode($user);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Query failed: " . $e->getMessage()]);
}