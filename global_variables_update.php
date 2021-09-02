<?php
include_once("gv.php");
// global_variables_update.php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  # 設定輸出錯誤類型
ini_set('display_errors','on');     # 開啟錯誤輸出
ini_set('display_startup_errors', 1);

$gv = GV::getInstance();
$gv->id = 99;

print($gv->id);
$handle = fopen("http://py.jayi.com.tw:888/market_status.py","rb");
$content = "";
while (!feof($handle)) {
        $content .= fread($handle, 10000);
}
fclose($handle);

// echo "content:". $content;
$jsonObj = json_decode($content);

// echo "jsonObj:". var_dump($jsonObj);
// echo "json_last_error:".json_last_error();

if($jsonObj->{"Success"}){
    foreach ($jsonObj->{"MarketStatus"} as $key => $value) {
        // echo "$key->$value<br>";
        if($key!="UpdateTime")
            $GLOBALS[$key] = $value;
    }
}
header('Content-type: application/json; charset=utf-8');
print(json_encode($jsonObj));

// header('Content-type: application/json; charset=utf-8');
// print(json_encode($jsonObj));


// echo $GLOBALS["DateOpenOrNot"] . "<br>";
// echo $GLOBALS["Day"] . "<br>";
// echo $GLOBALS["RealTime"] . "<br>";
// echo $GLOBALS["StockPriceStatus"] . "<br>";
?>

