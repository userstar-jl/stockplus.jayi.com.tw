<?php 
$url = "https://scontent.ftpe7-2.fna.fbcdn.net/v/t1.0-9/p960x960/92472282_2917318844990521_4661210096611622912_o.jpg?_nc_cat=109&_nc_sid=8024bb&_nc_ohc=T-hgP0lafNkAX8qahRT&_nc_ht=scontent.ftpe7-2.fna&_nc_tp=6&oh=766bfd4406c5bb00f7c3fd2929d44703&oe=5EBBB845";
//saveImage($url, "fb.jpg");
function saveImage($url, $savePath) 
{

    $ch = curl_init($url);
    $fp = fopen($savePath, 'wb');

    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    $result = curl_exec($ch);

    fclose($fp);
    curl_close($ch);

    return $result;

}
?>
<img src='fb.jpg'>