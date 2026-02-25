<?php
require "dbconnect.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; 
    // Check if email exists
    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $message = "Email already registered!";
    } else {
        $sql = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($sql, "sss", $name, $email, $password);
        if (mysqli_stmt_execute($sql)) {
            $message = "Registration successful!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($sql);
    }

    mysqli_stmt_close($check);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
</head>
<body>
    <h2>Register Form</h2>

    <?php
    if ($message != "") {
        echo "<p>$message</p>";
    }
    ?>

    <form method="POST" action="">
        <table>
            <tr>
                <td>Name:</td>
                <td><input type="text" name="name" required></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" required></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="Register"></td>
            </tr>
        </table>
    </form>
</body>
</html>
