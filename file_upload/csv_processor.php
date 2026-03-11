<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['survey_name'])) {

    // Step 1: CSV uploaded → display quiz
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $surveyName = htmlspecialchars(trim($_POST['survey_name']));
        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");

        $questions = [];
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($row) < 2) continue;
            $question = htmlspecialchars(trim($row[0]));
            $correct = htmlspecialchars(trim($row[1]));
            $wrongOptions = array_slice($row, 2);
            $wrongOptions = array_map('htmlspecialchars', array_map('trim', $wrongOptions));
            $allOptions = $wrongOptions;
            $allOptions[] = $correct;
            shuffle($allOptions); // shuffle options
            $questions[] = [
                'question' => $question,
                'correct_answer' => $correct,
                'options' => $allOptions
            ];
        }
        fclose($handle);

        // Save questions in session
        $_SESSION['quiz'] = $questions;
        $_SESSION['survey_name'] = $surveyName;
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answers'])) {
    // Step 2: form submitted → check answers
    $questions = $_SESSION['quiz'] ?? [];
    $surveyName = $_SESSION['survey_name'] ?? 'Survey';
    $userAnswers = $_POST['answer'] ?? [];

    echo "<h2>Survey: $surveyName</h2>";
    echo "<ol>";
    $score = 0;
    foreach ($questions as $index => $q) {
        $userAnswer = $userAnswers[$index] ?? '';
        $correct = $q['correct_answer'];
        $isCorrect = $userAnswer === $correct;
        if ($isCorrect) $score++;
        echo "<li><strong>{$q['question']}</strong><br>";
        echo "Your Answer: $userAnswer " . ($isCorrect ? "<span style='color:green'>(Correct)</span>" : "<span style='color:red'>(Wrong)</span>") . "<br>";
        echo "Correct Answer: $correct<br></li><br>";
    }
    echo "</ol>";
    echo "<p><strong>Total Score: $score / ".count($questions)."</strong></p>";
    session_destroy();
    exit;
}

// Display quiz if questions exist
if (!empty($_SESSION['quiz'])) {
    $questions = $_SESSION['quiz'];
    $surveyName = $_SESSION['survey_name'];
    echo "<h2>Quiz: $surveyName</h2>";
    echo "<form method='post'>";
    echo "<ol>";
    foreach ($questions as $index => $q) {
        echo "<li><strong>{$q['question']}</strong><br>";
        foreach ($q['options'] as $opt) {
            echo "<input type='radio' name='answer[$index]' value='$opt' required> $opt<br>";
        }
        echo "</li><br>";
    }
    echo "</ol>";
    echo "<button type='submit' name='submit_answers'>Submit Answers</button>";
    echo "</form>";
}
?>