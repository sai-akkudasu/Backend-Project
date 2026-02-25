<?php
require "dbconnect.php";

header("Content-Type: application/json");
parse_str(file_get_contents("php://input"), $data);
if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "id required"]);
    exit;
}

$id = filter_var($data['id'], FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount()) {
        http_response_code(200);
        echo json_encode(["message" => "User deleted"]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Delete failed: " . $e->getMessage()]);
}