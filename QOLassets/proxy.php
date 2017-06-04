<?php
$ch = curl_init();
//here's your fucking header... you wasted 4 hours of my life.
$header = array(
    'Host : http://coe.skku.edu',
   'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'Accept-Language: en-US,en;q=0.8,ko;q=0.6',
    'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
    'Proxy-Connection: Close',
    'Cookie: 	__utmt=1; __utma=12798129.1253298825.1496590349.1496590349.1496590349.1; __utmb=12798129.1.10.1496590349; __utmc=12798129; __utmz=12798129.1496590349.1.1.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided)',
    'Cache-Control: max-age=0',
    'Connection: Close'
);
$agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36";
// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://coe.skku.edu/coe/menu_6/data_01.jsp");
curl_setopt($ch, CURLOPT_HEADER, $header);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL and pass it to the browser
$urlContent = curl_exec($ch);
if(!curl_errno($ch))
{
   $info = curl_getinfo($ch);
   echo $urlContent;
}
else{
    echo "Something went wrong";
}
?>