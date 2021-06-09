<?php
session_start();  //啟動 session
//$webhost="http://127.0.0.1:8000/stock_sys/";
//$webhost="http://220.132.244.53/stock_sys/";
//$webhost="http://stock.jayi.com.tw/";

//$singapore_stock_dir = 'D:\AppServ\www\stock_sys\sg\galleries\stock\\';

//$images_repos = "/var/www/i.jayi.com.tw/images_repos/";
//$images_url = "http://i.jayi.com.tw:888/images_repos/";

$dbhost = 'localhost';
$dbname = 'lambshop';
$dbuser = 'root';
$dbpasswd = 'j10551055';

$link=mysql_connect($dbhost,$dbuser,$dbpasswd) or die ("無法連接");

mysql_select_db($dbname,$link) or die("無法連接資料庫");
//echo "連接成功";

//mysql_close($link);

//080515 $StockQuoteApplet_url 已改由 StockQuoteApplet_url.php 取代
//$StockQuoteApplet_url="http://60.199.244.181/asp/java_asp/java_sq.asp";
//$StockQuoteApplet_url="http://60.199.244.179/asp/java_asp/java_sq.asp";


//因應php5而改的
// mysql_query('SET NAMES big5');
// mysql_query('SET CHARACTER_SET_CLIENT=big5');
// mysql_query('SET CHARACTER_SET_RESULTS=big5');
//因應php5而改的
mysql_query('SET NAMES utf8');
mysql_query('SET CHARACTER_SET_CLIENT=utf8');
mysql_query('SET CHARACTER_SET_RESULTS=utf8');

//For auto load more data
$items_per_group = 5;
?>