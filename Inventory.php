<?php
include("config.php");
require "util.php"; 

// 強制 PHP 顯示錯誤訊息
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 權限
if ( !isset($_SESSION['username']) ){
	header("Location: ../index.php");
}
// $user_id = $_SESSION["userid"];
//echo "<hr>$user_id<hr>";
/*
select * from gds_001_bas as bas LEFT JOIN gds_003_price as price on bas.gds_id=price.gds_id LEFT JOIN gds_005_stocks as stocks on bas.gds_id=stocks.gds_id


select bas.gds_id, bas.name, bas.state, bas.sort, price.price, stocks.stocks, bas.user_id from gds_001_bas as bas LEFT JOIN gds_003_price as price on bas.gds_id=price.gds_id LEFT JOIN gds_005_stocks as stocks on bas.gds_id=stocks.gds_id order by bas.sort, bas.gds_id
*/
/*
trade_type
進 01 買進 02 股息 03 資買 04 券買
出 11 賣出 12 減資 13 券賣 14 資賣
*/
$sql= "select s060.stk_code,stock_list.name as stk_name, sum(s060.vol) as sum, stock_list.status from stk_060_trade_rec s060, stock_list ";
$sql.="where trade_type!='20' and s060.stk_code= stock_list.loc_code and s060.user_id='".$_SESSION["userid"]."' ";
$sql.="group by s060.stk_code order by status desc, sum desc";
// $sql = "select name FROM stock_list where  loc_code='$stk_code' ";
// echo "<hr>$sql<hr>";
$result=mysql_query($sql);
//echo "<hr>result:$result<hr>";
$content = "";
$products = array();
$stocks = "";
while($row=mysql_fetch_array($result)) {
    list($stk_code, $stk_name, $sum) = $row;
    $stocks .= $stk_code.",";
    // $detail = array(
    //   "id" => $stk_code,
    //   "name" => $sum,
    //   "price" => "999",
    //   "stocks" => "888"
    // );
    // $product = array(
    //   "id" => $stk_code,
    //   "detail" => $detail
    // );
    // array_push($products,$product);
    $sum = number_format($sum, 0, '.', ',');
    $content .= <<<EOF

    <div class="col mb-4">
        <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <h2 class="my-0 font-weight-normal">$stk_code $stk_name</h2>
        </div>
        <div class="card-body">
            <h2 class="card-title pricing-card-title"><span id='price$stk_code'>-</span></h2>
            <ul class="list-unstyled mt-3 mb-4">
            <li><h4><span id='spread$stk_code'>-</span>&nbsp;<span id='pfp$stk_code'>-</span></h4></li> 
            <li><h4>$sum 股</h4></li> 
            <li><h4><a href='https://tw.stock.yahoo.com/q/bc?s=$stk_code' target='_blank'>Yahoo!</a></h4></li> 
            </ul>
            <button type="button" class="btn btn-lg btn-block btn-outline-primary">--</button>
        </div>
        </div>
    </div>

EOF;
}
$stocks=substr($stocks,0,-1);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>庫存</title>

    <!-- Bootstrap core CSS -->
<link href="bootstrap/4.5.0/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">

<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="js/jquery.mycart.min.js"></script>

<script>
// var products = JSON.parse('<?php echo json_encode($products); ?>');
// for( let i = 0 ; i < products.length ; i++ ){
//   console.log(products[i]);
// }

// Object.keys(products).forEach(function(key) {
//   console.table('Key : ' + key + ', Value : ' + products[key])
// })

// console.log(products.);
// var pid = products.keys();
// for( let i = 0 ; i < pid.length ; i++ ){
//   console.log(pid[i]);
// }
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
            var pfp = item["pfp"];
            var y = item["y"];
            var p = item["price"];
            var spread = p-y; //價差
            if(pfp>0){
                pfp = "(+" + pfp + "%)";
                $("#price" + item["code"]).attr('class', 'text-danger');
                $("#pfp" + item["code"]).attr('class', 'text-danger');
                $("#spread" + item["code"]).attr('class', 'text-danger');
                $("#price" + item["code"]).html(toCurrency(p, 2));
                $("#spread" + item["code"]).html(toCurrency(spread, 2));
                $("#pfp" + item["code"]).html(pfp);
            }else{
                pfp = "(" + pfp + "%)";
                $("#price" + item["code"]).attr('class', 'text-success');
                $("#pfp" + item["code"]).attr('class', 'text-success');
                $("#spread" + item["code"]).attr('class', 'text-success');
                $("#price" + item["code"]).html(toCurrency(p, 2));
                $("#spread" + item["code"]).html(toCurrency(spread, 2));
                $("#pfp" + item["code"]).html(pfp);
            }
		});
		// $("#resultsaveimg").html("儲存錯誤!!<br>"+result.Msg);
		//   pfp

	}else{
		console.log("Stock Price Error" + StockPriceJson["Data"]);
		StockPriceJson["Data"].forEach(function(item, i) {
      $("#price" + item["code"]).attr('class', 'text-danger');
      $("#pfp" + item["code"]).attr('class', 'text-danger');
      $("#spread" + item["code"]).attr('class', 'text-danger');
			$("#price" + item["code"]).html("-");
			$("#pfp" + item["code"]).html("-");
			$("#spread" + item["code"]).html("-");
		});
    
	}
}

function toCurrency(num, n){
    var parts = num.toFixed(n).toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return parts.join('.');
}

function getChart(){
  console.log("getChart");	
  var url = 'webapi/CostVolumeProfit.php';
	$.ajax({
   		type: "post",
		data:{cmd:"getAll"}, 
   		url: url,
		dataType: "json",
		beforeSend:function(data){
			console.log("beforeSend");
		},
		success: function(data){
			console.log("data:"+data);	
      
			if(data.Success){
        
        var data_date=[], pv=[], pre_pl=[], pre_pl_ratio=[];
        for(var d in data.Data) {
          console.log(data.Data[d].data_date);
          data_date.unshift(data.Data[d].data_date);
          pv.unshift(data.Data[d].pv);
          pre_pl.unshift(data.Data[d].pre_pl);
          pre_pl_ratio.unshift(data.Data[d].pre_pl_ratio);
        }

        // 損益彙整表
        for(i=data_date.length-1;i>-1;i--){
          var newrow = document.createElement("tr");

          var data_date_Column = document.createElement("td");
          data_date_Column.innerText = data_date[i];
          newrow.appendChild(data_date_Column);

          // var tc_Column = document.createElement("td");
          // tc_Column.innerText = toCurrency(+data.Data[d].tc, 0);
          // newrow.appendChild(tc_Column);
          
          // var ts_Column = document.createElement("td");
          // ts_Column.innerText = toCurrency(+data.Data[d].ts, 0);
          // newrow.appendChild(ts_Column);
          
          var pv_Column = document.createElement("td");
          pv_Column.innerText = toCurrency(+pv[i], 0);
          newrow.appendChild(pv_Column);
          
          var pre_pl_Column = document.createElement("td");
          pre_pl_Column.innerText = toCurrency(+pre_pl[i], 0);
          newrow.appendChild(pre_pl_Column);

          var pv_y_Column = document.createElement("td");
          if(i>0){
            pv_y_Column.innerText = toCurrency(pv[i]-pv[i-1], 0);
          }else{
            pv_y_Column.innerText = "--";
          }
          newrow.appendChild(pv_y_Column);

          var pre_pl_ratio_Column = document.createElement("td");
          pre_pl_ratio_Column.innerText = pre_pl_ratio[i];
          newrow.appendChild(pre_pl_ratio_Column);

          $('#cost_volume_profit_talbe tbody').append(newrow);
        }

        // Graphs
        var ctx = document.getElementById('myChart')
        // eslint-disable-next-line no-unused-vars
        var myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: data_date,
            datasets: [{
              data: pre_pl,
              lineTension: 0,
              backgroundColor: 'transparent',
              borderColor: '#007bff',
              borderWidth: 4,
              pointBackgroundColor: '#007bff'
            }]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: false
                }
              }]
            },
            legend: {
              display: false
            }
          }
        })
			}else{
				
			}
		}
	});
}

$(document).ready(function(){
    $('.count').prop('disabled', true);
    $(document).on('click','.plus',function(){
        $('.count').val(parseInt($('.count').val()) + 1 );
    });
    $(document).on('click','.minus',function(){
        $('.count').val(parseInt($('.count').val()) - 1 );
            if ($('.count').val() == 0) {
                $('.count').val(1);
            }
        });
    getStockPrice("<?=toIds($stocks)?>");

    getChart();
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

      @media (max-width: 576px) {  
      .xs {color:red;font-weight:bold;}
      }

      /* Small devices (landscape phones, 576px and up) */
      @media (min-width: 576px) and (max-width:768px) {  
      .sm {color:red;font-weight:bold;}
      }
      
      /* Medium devices (tablets, 768px and up) The navbar toggle appears at this breakpoint */
      @media (min-width: 768px) and (max-width:992px) {  
      .md {color:red;font-weight:bold;}
      }
      
      /* Large devices (desktops, 992px and up) */
      @media (min-width: 992px) and (max-width:1200px) { 
      .lg {color:red;font-weight:bold;}
      }
      
      /* Extra large devices (large desktops, 1200px and up) */
      @media (min-width: 1200px) {  
          .xl {color:red;font-weight:bold;}
      }
    
        
    /*Prevent text selection*/
    span{
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }
    input{  
        border: 0;
        width: 2%;
    }
    nput::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input:disabled{
        background-color:white;
    }
    </style>
    <!-- Custom styles for this template -->
    
  </head>
  <body>
  
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
  <h1>庫存</h1>
  <h4>發。發發。發發發</h4>
  <p class="lead">每日一句</p>
  </div>

  <div class="container">
    <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>
    <div class="table-responsive">
        <table id="cost_volume_profit_talbe" class="table table-striped table-sm">
          <thead>
            <tr>
              <th>日期</th>
              <!-- <th>總投入成本</th>
              <th>已實珼收益</th> -->
              <th>庫存股現值</th>
              <th>預估損益</th>
              <th>+/-</th>
              <th>報酬率(%)</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    <div class="card-deck row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 text-center">
    <!--card begin-->
    <?=$content?> 
    <!--card end-->
    </div>
  </div>

  <div class="container text-right">
  </div>
  <footer class="footer">
    <div class="container">
      <span class="text-muted">Place sticky footer content here.</span>
    </div>
  </footer>
<!--
  <div class="fixed-bottom">購物車</div>
    -->
  <script src="bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
</body>
</html>
