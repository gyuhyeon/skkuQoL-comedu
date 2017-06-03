<?php
$dbservername = "localhost";
$dbusername = "root";
$dbpassword = "insecurelocalpassword";
$dbname = "admin";

// Create connection
$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//note : $_POST indexes using "name" attributes from the form.
$purpose = $_POST['purpose'];
$day = $_POST['day'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$reservename = $_POST['reservename'];
$groupsize = $_POST['groupsize'];
$password = $_POST['password'];

//find if there's already a reservation by purpose!=0(not personal use) and reservedate matches and time conflicts
$sql = "SELECT * FROM admin.qol_seminarreservelist WHERE (reservedate = $day and purpose>0) and ((starttime<$end_time) endtimee= ";

//mysql query
$result = $conn->query($sql);

//if purpose is not personal yet the query finding non-personal(therefore official) use returned something, there's a conflict.
if ($result->num_rows > 0 && $purpose!=0) {
    echo "ERROR : CANNOT RESERVE AT SPECIFIED TIME";
}
else {

    $sql = "INSERT INTO admin.qol_seminarreservelist(purpose, studentname, reservedate, starttime, endtime, password) VALUES($purpose, $reservename, $day, $start_time, $end_time, $password)";

    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["user_id"]."<br>";
    }
}
$conn->close();
?>