<?php
$url = 'http://coe.skku.edu/coe/menu_6/data_01.jsp';
if (preg_match('/\b(https?|ftp):\/\/*/', $url) !== 1) die;
echo (file_get_contents($url));
?>