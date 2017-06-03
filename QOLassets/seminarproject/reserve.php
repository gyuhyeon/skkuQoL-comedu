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

if(!(($purpose>=0)&&($purpose<=4))){
     $response = "ERROR : 사용목적을 선택해주십시오.";
}
$date_regex ="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
if(!preg_match($date_regex, $day)){
    $response = "ERROR : 날짜를 선택해주십시오.";
}
else if(!(($start_time>=10)&&($start_time<=22))){
   $response = "ERROR : 시작시간을 선택해주세요.";
}
else if(!(($end_time>=10)&&($end_time<=22))){
    $response = "ERROR : 종료시간을 선택해주세요.";
}
else if(strlen($groupsize)>2){
    $response = "ERROR : 사용인원 수를 선택해주세요.";
}
else if(strlen($reservename)>13){
    $response = "ERROR : 이름은 10자 이내로 입력해주세요.";
}
else if(strlen($password)>15){
    $response = "ERROR : 비밀번호는 10자 이내로 입력해주세요.";
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
        $response = "예약 성공!";
    }
    else{
        $response = "ERROR : Something went wrong when inserting into database! $purpose,$reservename,$day,$start_time,$end_time,$groupsize,$password $conn->error";
    }
}

echo json_encode(["response" => $response]);

//close connection
$conn->close();
?>