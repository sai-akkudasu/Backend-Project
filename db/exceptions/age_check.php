<?php

class AgeException extends Exception {
    public function errorMessage() {
    
        return "Error: You must be 18 years or older. Your age is {$this->getMessage()}";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $age = $_POST['age'];

    try {
     
        if ($age < 18) {
            throw new AgeException($age); 
        } else { 
            echo "<h3>Welcome  You are $age years old.</h3>";
        }
    } catch (AgeException $e) {
        echo ". $e->errorMessage() . ";
    }
}
?>
