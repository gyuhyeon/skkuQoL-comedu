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
//set characterset that php thinks our database is using
if (!$conn->set_charset("utf8")) {
	die("utf8 문자 세트를 가져오다가 에러가 났습니다 :".$conn->error." 현재 문자 세트 : ".$conn->character_set_name());
}

//note : $_POST indexes using "name" attributes from the form.
$purpose = $_POST['purpose'];
$day = $_POST['day'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$groupsize = $_POST['groupsize'];
$reservename = $_POST['reservename'];
$password = $_POST['password'];

//set response header
header('Content-type:application/json;charset=utf-8');
$response='NULL';

//find if there's already a reservation by purpose!=0(not personal use) and reservedate matches and time conflicts
$sql = "SELECT * FROM admin.qol_seminarreservelist WHERE (reservedate = '$day' and purpose>0) and ((starttime<=$end_time) and (endtime>=$start_time))";

//run mysql query
$result = $conn->query($sql);

//if purpose is not personal yet the query finding non-personal(therefore official) use returned something, there's a conflict.
if ($result->num_rows > 0 && $purpose!=0) {
    $response = "ERROR : CANNOT RESERVE AT SPECIFIED TIME";
}
else {
    $sql = "INSERT INTO admin.qol_seminarreservelist(purpose, studentname, reservedate, starttime, endtime, groupsize, password) VALUES('$purpose', '$reservename', '$day', $start_time, $end_time, '$groupsize', '$password')";
    //insertion query
    $result = $conn->query($sql);
    if($result === TRUE){
        $response = "Reservation success!";
    }
    else{
        $response = "ERROR : Something went wrong when inserting into database! $purpose,$reservename,$day,$start_time,$end_time,$groupsize,$password $conn->error";
    }
}

echo json_encode(["response" => $response]);

//close connection
$conn->close();
?>