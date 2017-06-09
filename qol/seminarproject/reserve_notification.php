<?php
require($_SERVER["DOCUMENT_ROOT"].'/../dbconfig.php');
require($_SERVER["DOCUMENT_ROOT"].'/../twilio-php/Twilio/autoload.php');
require($_SERVER["DOCUMENT_ROOT"].'/../twilioconfig.php');
/*
dbconfig.php file outside of webroot has the following variables setup globally

$dbservername;
$dbusername;
$dbpassword;
$dbname;

*/

//header(even for json, text/html seems to give better results with encoding)
header('Content-Type: text/html; charset=utf-8');

//this file should be invoked via crontab at 5pm. post request should contain TZ=":Asia/Seoul" date
/*
currentdate=$(TZ=":Asia/Seoul" date +%Y-%m-%d)
curl -X POST http://comedu.co.kr/qol/reserve_notification.php --data '{"currentdate":"'"$currentdate"'"}'
*/

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

$sql = "SELECT * FROM admin.qol_seminarreservelist WHERE reservedate = '$currentdate' and endtime>=18";
$result = $conn->query($sql);

//create associative array from query result
$jsonresponse=array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //$jsonresponse[]=array("reserve_srl" => $row["reserve_srl"], "purpose" => $row["purpose"]);
        //same as above. varname[]=~~ adds element to array, not overwrite it.
        $jsonresponse[]=array('신청자'=>$row['studentname'], '시작시간'=>$row['starttime'], '종료시간'=>($row['endtime']+1));
    }
}
else {
    die();
}
$conn->close();

use Twilio\Rest\Client;
$twclient = new Client($sid, $token);

$people=array("+82-10-7248-1535" => "이규현");
$message="금일 철야 신청자: ";
for($i=0; $i<count($jsonresponse); ++$i){
    $message.=($jsonresponse[$i]['신청자']." ");
}
foreach ($people as $number => $name){
    $sms = $twclient->account->messages->create(
        $people,

        array(
            'from' => "+1 424-361-0119",
            'body' => $message
        )
    );
}


?>