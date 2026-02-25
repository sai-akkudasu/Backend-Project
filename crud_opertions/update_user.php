<?php
require "dbconnect.php";
header("Content-Type: application/json");
parse_str(file_get_contents("php://input"), $data);
if (!isset($data['id'], $data['name'], $data['email'])) {
    http_response_code(400);
    echo json_encode(["error" => "All fields required"]);
    exit;
}

$id = filter_var($data['id'], FILTER_VALIDATE_INT);
$name = $data['name'];
$email = $data['email'];

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $id]);

    if ($stmt->rowCount()) {
        http_response_code(200);
        echo json_encode(["message" => "User updated"]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Update failed: " . $e->getMessage()]);
}