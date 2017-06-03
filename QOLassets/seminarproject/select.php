<?php
$dbservername = "localhost";
$dbusername = "root";
$dbpassword = "insecurelocalpassword";
$dbname = "admin";

//if text html utf-8
header('Content-Type: text/html; charset=utf-8');

// Create connection
$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//set characterset that php thinks our database is using
if (!$conn->set_charset("utf8")) {
	die("utf8 문자 세트를 가져오다가 에러가 났습니다 :".$conn->error." 현재 문자 세트 : ".$conn->character_set_name());
}

$currentdate = htmlspecialchars($_GET['currentdate']);
$date_regex ="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
if(!preg_match($date_regex, $currentdate)){
    die("날짜 양식 오류");
}
//set response header
//header('Content-type:application/json;charset=utf-8');

//find all reservations til 6 days from now
$sql = "SELECT * FROM admin.qol_seminarreservelist WHERE reservedate >= '$currentdate' and reservedate <= DATE(DATE_ADD('$currentdate', INTERVAL 6 DAY))";

//run mysql query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "<table>";
    echo "<tr>";
    echo "<td>reserve_srl</td>";
    echo "<td>purpose</td>";
    echo "<td>reservedate</td>";
    echo "<td>starttime</td>";
    echo "<td>endtime</td>";
    echo "<td>groupsize</td>";
    echo "<td>studentname</td>";
    echo "<td>password</td>";
    echo "</tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row["reserve_srl"]."</td>";
        echo "<td>".$row["purpose"]."</td>";
        echo "<td>".$row["reservedate"]."</td>";
        echo "<td>".$row["starttime"]."</td>";
        echo "<td>".$row["endtime"]."</td>";
        echo "<td>".$row["groupsize"]."</td>";
        echo "<td>".$row["studentname"]."</td>";
        echo "<td>".$row["password"]."</td>";
        echo "</tr>";
        
    }
    echo "</table>";
} else {
    echo "0 results";
}


//close connection
$conn->close();
?>