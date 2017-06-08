<?php
require($_SERVER["DOCUMENT_ROOT"].'/../dbconfig.php');
/*
dbconfig.php file outside of webroot has the following variables setup globally

$dbservername;
$dbusername;
$dbpassword;
$dbname;

*/

//header(even for json, text/html seems to give better results with encoding)
header('Content-Type: text/html; charset=utf-8');

$currentdate = $_POST['currentdate'];
//basic sql injection prevention
$date_regex ="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
if(!preg_match($date_regex, $currentdate)){
    echo json_encode(array("response" => "날짜형식오류"), JSON_UNESCAPED_UNICODE);
    die();
}

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



$sql = "SELECT * FROM admin.qol_seminarreservelist WHERE reservedate >= '$currentdate' and reservedate <= DATE(DATE_ADD('$currentdate', INTERVAL 6 DAY));";
$result = $conn->query($sql);

$jsonresponse=array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //$jsonresponse[]=array("reserve_srl" => $row["reserve_srl"], "purpose" => $row["purpose"]);
        //same as above. varname[]=~~ adds element to array, not overwrite it.
        $jsonresponse[]=$row;
    }
}
else {
    //used to give a "response" data, but perhaps it will be better to just give 0, since it might mess with reserve.js
    $jsonresponse=array();
}
//encode as json
echo json_encode($jsonresponse, JSON_UNESCAPED_UNICODE);

$conn->close();
?>