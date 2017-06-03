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

$sanitization = TRUE;
if(!(($purpose>=0)&&($purpose<=4))){
    $sanitization = FALSE;
}
$date_regex ="/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/g";
if(!preg_match($date_regex, $day)){
    $sanitization = FALSE;
}
if(!(($start_time>=10)&&($start_time<=22))){
    $sanitization = FALSE;
}
if(!(($end_time>=10)&&($end_time<=22))){
    $sanitization = FALSE;
}
if(strlen($groupsize)>2){
    $sanitization = FALSE;
}
if(strlen($reservename)>13){
    $sanitization = FALSE;
}
if(strlen($password)>20){
    $sanitization = FALSE;
}

if($sanitization === FALSE){
    $response = "ERROR : 미입력/오기한 항목이 있거나, 비밀번호/이름이 너무 깁니다.";
}
//if purpose is not personal yet the query finding non-personal(therefore official) use returned something, there's a conflict.
else if ($result->num_rows > 0 && $purpose!=0) {
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