<?php
ini_set('error_reporting', E_ALL);
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

~~sudo crontab -e
30 7 * * * sh /var/www/cronjob.sh

~~ cronjob.sh ~~
currentdate=$(TZ=":Asia/Seoul" date +%Y-%m-%d)
curl -X GET "http://comedu.co.kr/qol/seminarproject/reserve_notification.php?currentdate=${currentdate}"

doesn't work(probably encoding issues) : curl -X POST -H "Content-Type: application/x-www-form-urlencoded; charset=utf-8"  -d '{"currentdate":"'"$currentdate"'"}'  http://comedu.co.kr/qol/seminarproject/reserve_notification.php

*/

$currentdate = htmlspecialchars($_GET['currentdate']);
//$currentdate = $_POST['currentdate'];
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

$message="금일 세미나실 철야 신청자: ";

$interval=0;
$dt=new DateTime("now", new DateTimeZone('Asia/Seoul'));
$dt->setTimestamp(time());
if($dt->format('w')==5){
    //friday..
    $interval=2;
    $message="금/토/일 세미나실 철야 신청자: ";
}
else if($dt->format('w')==6||$dt->format('w')==0){
    echo json_encode(array("response" => "no sms on weekends"), JSON_UNESCAPED_UNICODE);
    die();
}


$sql = "SELECT * FROM admin.qol_seminarreservelist WHERE reservedate >= '$currentdate' and reservedate<=DATE(DATE_ADD('$currentdate', INTERVAL '$interval' DAY)) and endtime>=18";
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
    $conn->close();
    echo json_encode(array("response" => "철야신청자없음"), JSON_UNESCAPED_UNICODE);
    die();
}
$conn->close();

use Twilio\Rest\Client;
$twclient = new Client($sid, $token);

$people=array("+82-10-7248-1535" => "이규현", "+82-10-2614-5698" => "정윤석");

for($i=0; $i<count($jsonresponse); ++$i){
    $message.=($jsonresponse[$i]['신청자']." ");
}
foreach ($people as $number => $name){
    $sms = $twclient->account->messages->create(
        $number,

        array(
            'from' => "+1 424-361-0119",
            'body' => $message
        )
    );
}

echo json_encode(array("response" => "success"), JSON_UNESCAPED_UNICODE);
die();

?>
