<?php
include("config.php");
require "util.php"; 

// 強制 PHP 顯示錯誤訊息
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 權限
// if ( !isset($_SESSION['username']) )
// {
// 	header("Location: ../index.php");
// }
// $user_id = $_SESSION["userid"];
//echo "<hr>$user_id<hr>";
/*
select * from gds_001_bas as bas LEFT JOIN gds_003_price as price on bas.gds_id=price.gds_id LEFT JOIN gds_005_stocks as stocks on bas.gds_id=stocks.gds_id


select bas.gds_id, bas.name, bas.state, bas.sort, price.price, stocks.stocks, bas.user_id from gds_001_bas as bas LEFT JOIN gds_003_price as price on bas.gds_id=price.gds_id LEFT JOIN gds_005_stocks as stocks on bas.gds_id=stocks.gds_id order by bas.sort, bas.gds_id
*/
$sql="select bas.gds_id, bas.name, bas.state, bas.sort, price.price, stocks.stocks, bas.user_id from gds_001_bas as bas ";
$sql .= "LEFT JOIN gds_003_price as price on bas.gds_id=price.gds_id ";
$sql .= "LEFT JOIN gds_005_stocks as stocks on bas.gds_id=stocks.gds_id ";
$sql .= "order by bas.sort, bas.gds_id";

//echo "<hr>$sql<hr>";
$result=mysql_query($sql);
//echo "<hr>result:$result<hr>";
$content = "";
$products = array();

while($row=mysql_fetch_array($result)) {
    list($gds_id, $name, $state, $sort, $price, $stocks, $user_id) = $row;

    $detail = array(
      "id" => $gds_id,
      "name" => $name,
      "price" => $price,
      "stocks" => $stocks
    );
    $product = array(
      "id" => $gds_id,
      "detail" => $detail
    );
    array_push($products,$product);

    $content .= <<<EOF

    <div class="col mb-4">
        <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <h4 class="my-0 font-weight-normal">$name</h4>
        </div>
        <div class="card-body">
            <h1 class="card-title pricing-card-title">$$price <small class="text-muted">/ 台斤</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
            <li><h4>現量:$stocks</h4></li>
            <li>
            <div class="qty mt-5">
            <span class="minus bg-primary">-</span>
            <input type="number" class="count" name="qty" value="0">
            <span class="plus bg-primary">+</span>
            </div>
            </li>
            </ul>
            <button type="button" class="btn btn-lg btn-block btn-outline-primary">加入購物車</button>
        </div>
        </div>
    </div>

EOF;

}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>四湖羊肉小舖</title>

    <!-- Bootstrap core CSS -->
<link href="bootstrap/4.5.0/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">

<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="js/jquery.mycart.min.js"></script>

<script>
var products = JSON.parse('<?php echo json_encode($products); ?>');
for( let i = 0 ; i < products.length ; i++ ){
  console.log(products[i]);
}

Object.keys(products).forEach(function(key) {
  console.table('Key : ' + key + ', Value : ' + products[key])
})

// console.log(products.);
// var pid = products.keys();
// for( let i = 0 ; i < pid.length ; i++ ){
//   console.log(pid[i]);
// }

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
    
      /* 購物車 */
    .badge-notify{
      background:red;
      position:relative;
      top: -20px;
      right: 15px;
      padding:3px;
      font-size: 1.125rem;
    }
    .my-cart-icon-affix {
      position: fixed;
      z-index: 999;
    }

    /* 數量 + - */
    .qty .count {
        color: #000;
        display: inline-block;
        vertical-align: top;
        font-size: 25px;
        font-weight: 700;
        line-height: 30px;
        padding: 0 2px
        ;min-width: 35px;
        text-align: center;
    }
    .qty .plus {
        cursor: pointer;
        display: inline-block;
        vertical-align: top;
        color: white;
        width: 30px;
        height: 30px;
        font: 30px/1 Arial,sans-serif;
        text-align: center;
        border-radius: 50%;
        }
    .qty .minus {
        cursor: pointer;
        display: inline-block;
        vertical-align: top;
        color: white;
        width: 30px;
        height: 30px;
        font: 30px/1 Arial,sans-serif;
        text-align: center;
        border-radius: 50%;
        background-clip: padding-box;
    }
    div {
        text-align: center;
    }
    .minus:hover{
        background-color: #717fe0 !important;
    }
    .plus:hover{
        background-color: #717fe0 !important;
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
  <h1>四湖羊肉小舖</h1>
  <h4>本土。新鮮。直送</h4>
  <p class="lead">一些介紹</p>
  </div>

  <div class="container">
    <div class="card-deck row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 text-center">
    <!--card begin-->
    <?=$content?> 
    <!--card end-->
    </div>
  </div>
      <span class="fixed-bottom text-right fa fa-shopping-cart fa-2x">
        <span class="badge badge-success badge-notify  my-cart-badge">0</span>
      </span>
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
</body>
</html>
