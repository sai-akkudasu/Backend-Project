<?php
header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => false, "message" => "Only POST method allowed"]);
    exit;
}
if (!isset($_FILES['file'])) {
    echo json_encode(["status" => false, "message" => "No file uploaded"]);
    exit;
}

$file = $_FILES['file']['tmp_name'];
$handle = fopen($file, "r");
if (!$handle) {
    echo json_encode(["status" => false, "message" => "Unable to open CSV file"]);
    exit;
}

$questions = [];
$rowNumber = 0;

while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $rowNumber++;
    if (count($row) < 2) continue;

    $question = trim($row[0]);
    $correct = trim($row[1]);
    $wrongOptions = array_slice($row, 2);
    $wrongOptions = array_map('trim', $wrongOptions);

    $questions[] = [
        "question" => $question,
        "correct_answer" => $correct,
        "wrong_options" => $wrongOptions
    ];
}

fclose($handle);
echo json_encode([
    "status" => true,
    "total_questions" => count($questions),
    "data" => $questions
], JSON_PRETTY_PRINT);