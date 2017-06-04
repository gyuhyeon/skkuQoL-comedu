<?php
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://coe.skku.edu/coe/menu_6/data_01.jsp");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL and pass it to the browser
$urlContent = curl_exec($ch);
if(!curl_errno($ch))
{
   $info = curl_getinfo($ch);
   header('Content-type: '.$info['content_type']);
   echo $urlContent;
}
else{
    echo "Something went wrong";
}
?>