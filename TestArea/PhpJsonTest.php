<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  # 設定輸出錯誤類型
ini_set('display_errors','on');     # 開啟錯誤輸出
ini_set('display_startup_errors', 1);

include("../config.php");
require "../util.php"; 

$jsonObj = getAPI('http://py.jayi.com.tw:888/market_status.py');
//   foreach ($jsonObj->{"MarketStatus"} as $key => $value) {
//     echo "$key->$value<br>";
    
// }
echo 'market_status:'.var_dump($jsonObj);
?>