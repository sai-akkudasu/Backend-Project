<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload CSV Quiz</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        input, button { margin: 5px 0; padding: 8px; }
    </style>
</head>
<body>
    <h2>Upload CSV to Generate Quiz</h2>
    <form method="post" enctype="multipart/form-data" action="csv_processor.php">
        <label>Survey Name:</label><br>
        <input type="text" name="survey_name" required placeholder="Enter Survey Name"><br>
        <label>CSV File:</label><br>
        <input type="file" name="file" accept=".csv" required><br>
        <button type="submit">Generate Quiz</button>
    </form>
</body>
</html>