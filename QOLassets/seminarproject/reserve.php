<?php
$servername = "localhost";
$username = "root";
$password = "insecurelocalpassword";
$dbname = "admin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT user_id FROM admin.xe_member";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["user_id"]."<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>