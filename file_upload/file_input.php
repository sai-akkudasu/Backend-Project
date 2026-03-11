<?php
require_once __DIR__ . "/../crud_opertions/dbconnect.php";
header("Content-Type: application/json");
// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => false,
        "message" => "Only POST method allowed"
    ]);
    exit;
}

// Check if file uploaded
if (!isset($_FILES['file'])) {
    echo json_encode([
        "status" => false,
        "message" => "No file uploaded"
    ]);
    exit;
}

$file = $_FILES['file'];

// Validate CSV file
$fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if ($fileExtension !== 'csv') {
    echo json_encode([
        "status" => false,
        "message" => "Only CSV files are allowed"
    ]);
    exit;
}

// Upload directory
$uploadDir = __DIR__ . '/uploads/';

// Create folder if not exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Save uploaded file
$filePath = $uploadDir . basename($file['name']);
move_uploaded_file($file['tmp_name'], $filePath);

// Open CSV
$handle = fopen($filePath, "r");

if (!$handle) {
    echo json_encode([
        "status" => false,
        "message" => "Unable to read CSV file"
    ]);
    exit;
}

$rowNumber = 0;
$inserted = 0;
$errors = [];

// Read CSV rows
while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {

    $rowNumber++;

    // Skip header row
    if ($rowNumber == 1 && strtolower($row[0]) == "name") {
        continue;
    }

    // Skip incomplete rows
    if (count($row) < 3) {
        continue;
    }

    $name = trim($row[0]);
    $email = trim($row[1]);

    // HASH PASSWORD
    $password = password_hash(trim($row[2]), PASSWORD_DEFAULT);

    try {

        $stmt = $pdo->prepare(
            "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
        );

        $stmt->execute([$name, $email, $password]);

        $inserted++;

    } catch (PDOException $e) {

        $errors[] = "Row $rowNumber failed: " . $e->getMessage();
    }
}

fclose($handle);

// Final response
echo json_encode([
    "status" => true,
    "message" => "CSV processed successfully",
    "records_inserted" => $inserted,
    "errors" => $errors
]);