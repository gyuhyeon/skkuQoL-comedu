<?php
//set response header
header('Content-type:application/json;charset=utf-8');
$response='NULL';

if(!isset($_POST['loggedmembersrl'])){
    $response="로그인한 관리자만 열람 가능합니다.";
    echo json_encode(["response" => $response]);    
    die();
}
$loggedmembersrl = $_POST['loggedmembersrl'];
//insecure. this information should be stored elsewhere outside of root.
$allowed = array(147, 987);
$auth=FALSE;
for($i=0; $i<count($allowed); ++$i){
    if($loggedmembersrl==$allowed[$i]){
        $auth=TRUE;
    }
}
if($auth===FALSE){
    $response="관리자만 열람 가능합니다.";
    echo json_encode(["response" => $response]);    
    die();
}
else{
    $response="success";
    //also insecure
    $link="http://comedu.co.kr/qol/seminarproject/reserve_status.html";
    echo json_encode(["response" => $response, "link" => $link]);
    die();
}

?>