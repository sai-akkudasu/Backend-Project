<?php
header("Content-Type: application/json");

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => false, "message" => "Only POST method allowed"]);
    exit;
}

// Check file upload
if (!isset($_FILES['file'])) {
    echo json_encode(["status" => false, "message" => "No file uploaded"]);
    exit;
}

$file = $_FILES['file'];

// Validate file type
$fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if ($fileExtension !== 'csv') {
    echo json_encode(["status" => false, "message" => "Only CSV files are allowed"]);
    exit;
}

// Optional: Save uploaded file
$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
move_uploaded_file($file['tmp_name'], $uploadDir . basename($file['name']));

// Open CSV
$handle = fopen($uploadDir . basename($file['name']), "r");
if (!$handle) {
    echo json_encode(["status" => false, "message" => "Unable to read file"]);
    exit;
}

$data = [];
$rowNumber = 0;

while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $rowNumber++;

    // Skip empty rows
    if (count($row) < 3) continue;

    // Skip header row
    if ($rowNumber == 1 && strtolower($row[0]) == 'name') continue;

    $data[] = [
        "name" => trim($row[0]),
        "email" => trim($row[1]),
        "password" => password_hash(trim($row[2]), PASSWORD_DEFAULT) // hashed password
    ];
}

fclose($handle);

echo json_encode([
    "status" => true,
    "message" => "CSV processed successfully",
    "total_records" => count($data),
    "data" => $data
]);