<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  # 設定輸出錯誤類型
ini_set('display_errors','on');     # 開啟錯誤輸出
ini_set('display_startup_errors', 1);

include("config.php");
require "util.php"; 

$user_id = $_SESSION["userid"];

if(isset($_GET['ex_right_date'])) {
  $ex_right_date = $_GET["ex_right_date"];
}else{
  $ex_right_date=date("Ymd");
}

// if($ex_right_date==NULL)
// 	$ex_right_date=date("Ymd");
$ex_right_date_require = $ex_right_date;
//echo "<hr>$user_id<hr>";
if( strlen($ex_right_date) == 8){ 
    // 查詢日前
	$ex_right_date_b=date("Ymd",strtotime($ex_right_date));
    $ex_right_date_e=date("Ymd",strtotime('+1 day',strtotime($ex_right_date)));

    $sql = "select * from stk_084_dr_data where LENGTH(stk_code)=4 and ex_right_date between '$ex_right_date_b' and '$ex_right_date_e' order by ex_right_date desc, stk_code;";

}else if( strlen($ex_right_date) == 4){
    // 查詢年度
    $sql = "select * from stk_084_dr_data where LENGTH(stk_code)=4 and ex_right_date like '$ex_right_date%' order by ex_right_date, stk_code;";
}
//echo $sql;
$result=mysql_query($sql);
$row=mysql_fetch_row($result);
$row_sum=mysql_num_rows($result);

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>除權息即時盤</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/dashboard/">

    <!-- Bootstrap core CSS -->
<link href="bootstrap/4.5.0/css/bootstrap.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
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
        <h1 class="h2">除權息預告表</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <button type="button" id="addProductBtn" class="btn btn-sm btn-outline-secondary">新增</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.open('pricing.php', '_blank');">價目表</button>
          </div>
        </div>
      </div>
      <div align="center">
      <?php
	$i=0;
	while($i<11){
		//echo(OpenOrNot( date("Ymd",strtotime(($i-5).'day',strtotime($ex_right_date))) ). "--");
		if(OpenOrNot( date("Ymd",strtotime(($i-5).'day',strtotime($ex_right_date))) )){
			//echo("YES");
		}else{
			//echo("NO");
		}
		if($i==5){
      $jsonObj = getAPI('http://py.jayi.com.tw:888/market_status.py?d=20210621');
      // echo 'market_status:'.var_dump($jsonObj);
      // $jsonObj["MarketStatus"]["DateOpenOrNot"]
      if($jsonObj->{"MarketStatus"}->{"DateOpenOrNot"}){
        $OpenOrNotTitle = " 開市";
      }else{
        $OpenOrNotTitle = " 休市";
      }
      echo "<h1><a href='ExrightAnnouncement.php?ex_right_date=".date("Ymd",strtotime(($i-5).'day',strtotime($ex_right_date)))."' target='_blank'><b>".date("Y/m/d",strtotime(($i-5).'day',strtotime($ex_right_date)))."</b></a> $OpenOrNotTitle</h1> &nbsp;&nbsp;";
    }else{
      echo "<a href='ExrightAnnouncement.php?ex_right_date=".date("Ymd",strtotime(($i-5).'day',strtotime($ex_right_date)))."' target='_blank'><b>".date("Y/m/d",strtotime(($i-5).'day',strtotime($ex_right_date)))."</b></a>&nbsp;&nbsp;";
    }
		$i++;
	}
	?>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>日期</th>
              <th>除息</th>
              <th>除權</th>
              <th>股號/股名</th>
              <th>現價</th>
              <th>漲幅</th>
            </tr>
          </thead>
          <tbody>

          <?php
              if($row_sum>0) {
                mysql_data_seek($result,0);
                $row=mysql_fetch_row($result);
                $stocks="";
                $stk_num=1;
                while($row!=NULL){
                    list($stk_code,$stk_name ,$ex_right_date ,$StockDividend ,$CashDividend)=$row;
                    $stocks .= $stk_code.",";
                    $ex_right_date=substr($ex_right_date,-4);
                    $StockDividend=number_format(round($StockDividend, 2), 2);
                    $CashDividend=number_format(round($CashDividend, 2), 2);
                    $row=mysql_fetch_row($result);
                    $stk_num++;
                    echo <<< EOD
                <tr>
                <td>$ex_right_date</td>
                <td>$CashDividend</td>
                <td>$StockDividend</td>
                <td><a href="http://stock.jayi.com.tw/stock_group/stock_detail.php?stk_code=$stk_code" target='_blank'><b>$stk_code&nbsp;$stk_name&nbsp;</b></a></td>
                <td><div id='price$stk_code'>Loading...</div></td>
                <td><div id='pfp$stk_code'>Loading...</div></td>
                </tr>
EOD;
                }//while
                $stk_num--;
                $stocks=substr($stocks,0,-1);
                // echo $stocks."<br>";
              }
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
        <script language="javascript">
//讀取個股除權息資料
var StockPriceJson;
function getStockPrice(ids){
	var now = new Date();
	// var ID = now.getTime();
	// var url = 'http://py.jayi.com.tw:888/rtp03.py?ids=otc_3083,otc_3687';
	var url = 'http://py.jayi.com.tw:888/rtp03.py';
	$.ajax({
   	type: "post",
	data:{cmd:"QQQ",ids:ids}, 
   	url: url,
   	//data: pars,
	dataType: "html",
	beforeSend:null,
  	success:getStockPriceResponse
	});
}

function getStockPriceResponse(msg){
		// console.log(msg);
	//alert(msg);
	StockPriceJson = JSON.parse(msg);
	if(StockPriceJson["Success"]){
		// PriceData = StockPriceJson["Data"];
		StockPriceJson["Data"].forEach(function(item, i) {
      if(item["pfp"]>0){
        $("#price" + item["code"]).attr('class', 'text-danger');
        $("#pfp" + item["code"]).attr('class', 'text-danger');
        $("#price" + item["code"]).html(item["price"]);
        $("#pfp" + item["code"]).html(item["pfp"]);
      }else{
        $("#price" + item["code"]).attr('class', 'text-success');
        $("#pfp" + item["code"]).attr('class', 'text-success');
        $("#price" + item["code"]).html(item["price"]);
        $("#pfp" + item["code"]).html(item["pfp"]);
      }
		});
		// $("#resultsaveimg").html("儲存錯誤!!<br>"+result.Msg);
		//   pfp

	}else{
		console.log("Stock Price Error" + StockPriceJson["Data"]);
		StockPriceJson["Data"].forEach(function(item, i) {
      $("#price" + item["code"]).attr('class', 'text-danger');
      $("#pfp" + item["code"]).attr('class', 'text-danger');
			$("#price" + item["code"]).html("資料異常");
			$("#pfp" + item["code"]).html("資料異常");
		});
    
	}
}
stocks = "$stocks";
$(document).ready(function(){
	getStockPrice("<?=toIds($stocks)?>");
})
</script>
</body>
</html>
