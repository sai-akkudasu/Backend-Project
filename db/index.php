<?php
require "dbconnect.php"; 

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
</head>
<body>

<h2>Users</h2>

<?php
if (mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No users found .";
}
?>

</body>
</html>
