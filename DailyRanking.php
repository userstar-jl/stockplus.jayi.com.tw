<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  # 設定輸出錯誤類型
ini_set('display_errors','on');     # 開啟錯誤輸出
ini_set('display_startup_errors', 1);

include("config.php");
require "util.php"; 

$dir = "/mnt/yau/stock_sys/data/ExchangeReport";

$user_id = $_SESSION["userid"];

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>每日排行</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/dashboard/">

    <!-- Bootstrap core CSS -->
<link href="bootstrap/4.5.0/css/bootstrap.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
  $( "#addProductBtn" ).click(function() {
    //alert( "Handler for .click() called." );
    location.href = 'ProductAdd.php';
  });

});
</script>
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      table thead tr th {
        font-size: 20px;
      }

      table tbody tr td {
        font-size: 20px;
      }

    </style>
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">Company name</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="#">Sign out</a>
    </li>
  </ul>
</nav>

<div class="container-fluid">
  <div class="row">
  <?php include_once ("sidebarMenu.php"); ?>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">每日排行</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <button type="button" id="addProductBtn" class="btn btn-sm btn-outline-secondary">新增</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.open('pricing.php', '_blank');">價目表</button>
          </div>
        </div>
      </div>
      <div id="wrapper" class="container-fluid">
<?php
$content="";
$file_name="";
// if(is_dir($dir)){//檢查是否是目錄
//   if($dh=opendir($dir)){//打開目錄
//     foreach (scandir($dir,1) as $file) {
//       //第一個跟第二個檔名是 .. 及 . 
//       if($file!='..' && $file!='.'){
//         $file_name = basename($file);
//         if(strpos($file_name, "DailyExchangeReportTop")!== false){
//           $content.=basename($file);
//           $file_name = basename($file);
//           $title = substr($file_name, strpos($file_name,"_")+1, 8);
//           // $title = strpos($file_name,"_");
   
//         	// echo "<h1><a href='ExrightAnnouncement.php?ex_right_date=".date("Ymd",strtotime(($i-5).'day',strtotime($ex_right_date)))."' target='_blank'><b>".date("Y/m/d",strtotime(($i-5).'day',strtotime($ex_right_date)))."</b></a> $OpenOrNotTitle</h1> &nbsp;&nbsp;";
//         	echo "<a href='DailyRanking.php?ex_right_date=". $file_name ."' target='_blank'><b>".$title."</b></a>&nbsp;&nbsp;";
//         }
//         // 
//       }
//     }
//   }
// }
// clearstatcache();//清除檔案狀態快取
?>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>日期</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>

          <?php

$content="";
$file_name="";
if(is_dir($dir)){//檢查是否是目錄
  if($dh=opendir($dir)){//打開目錄
    foreach (scandir($dir,1) as $file) {
      //第一個跟第二個檔名是 .. 及 . 
      if($file!='..' && $file!='.'){
        $file_name = basename($file);
        if(strpos($file_name, "DailyExchangeReportTop")!== false){
          $content.=basename($file);
          $file_name = basename($file);
          $title = substr($file_name, strpos($file_name,"_")+1, 8);
          // $title = strpos($file_name,"_");
          echo <<< EOD
          <tr>
          <td><a href='/DailyRankingDetail.php?file=$file_name&tag=all' target='_blank'>$title</></td>
          <td>
          <a href='/DailyRankingDetail.php?file=$file_name&tag=上市漲幅25' target='_blank'>上市漲幅25</><br>
          <a href='/DailyRankingDetail.php?file=$file_name&tag=上櫃漲幅25' target='_blank'>上櫃漲幅25</><br>
          <a href='/DailyRankingDetail.php?file=$file_name&tag=上市跌幅25' target='_blank'>上市跌幅25</><br>
          <a href='/DailyRankingDetail.php?file=$file_name&tag=上櫃跌幅25' target='_blank'>上櫃跌幅25</><br>
          <a href='/DailyRankingDetail.php?file=$file_name&tag=上市成交量25' target='_blank'>上市成交量25</><br>
          <a href='/DailyRankingDetail.php?file=$file_name&tag=上櫃成交量25' target='_blank'>上櫃成交量25</>
          </td>
          </tr>
EOD;
        }
        // 
      }
    }
  }
}
clearstatcache();//清除檔案狀態快取
              
           ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
      <script src="bootstrap/4.5.0/js/bootstrap.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
      <script src="js/dashboard.js"></script>
</body>
</html>
