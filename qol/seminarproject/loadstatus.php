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

$currentdate = $_POST['currentdate'];
//basic sql injection prevention
$date_regex ="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
if(!preg_match($date_regex, $currentdate)){
    die("날짜 양식 오류(비정상적 사용입니다!)");
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
    $jsonresponse[]=array("result"=>"0");
}
//encode as json
echo json_encode($jsonresponse, JSON_UNESCAPED_UNICODE);

$conn->close();
?>