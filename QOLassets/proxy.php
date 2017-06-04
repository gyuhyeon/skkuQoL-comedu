<?php
$ch = curl_init();
//here's your fucking header... you wasted 4 hours of my life.
$header = array(
   'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0.2) Gecko/20100101 Firefox/6.0.2',
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language: en-gb,en;q=0.5',
    'Accept-Encoding: gzip, deflate',
    'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
    'Proxy-Connection: Close',
    'Cookie: PREF=ID=2bb051bfbf00e95b:U=c0bb6046a0ce0334:',
    'Cache-Control: max-age=0',
    'Connection: Close'
);
// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://coe.skku.edu/coe/menu_6/data_01.jsp");
curl_setopt($ch, CURLOPT_HEADER, $header);
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