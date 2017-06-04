<?php
$url = 'http://coe.skku.edu/coe/menu_6/data_01.jsp';
$handle = fopen($url, "r");
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        echo $buffer;
    }
    fclose($handle);
}
?>