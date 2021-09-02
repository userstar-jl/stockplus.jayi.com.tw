<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  # 設定輸出錯誤類型
ini_set('display_errors','on');     # 開啟錯誤輸出
ini_set('display_startup_errors', 1);

include("config.php");
require "util.php"; 
// $_SESSION['userid'] = null;
if ( !isset($_SESSION['userid']) )
{
	header("Location: ../index.php?url=".urlencode($_SERVER["REQUEST_URI"]));
}

$dir = "/mnt/yau/stock_sys/data/ExchangeReport/";
$user_id = $_SESSION["userid"];

if(isset($_REQUEST['file'])) {
  $file = $_REQUEST["file"];
  $tag = $_REQUEST["tag"];
}else{
  echo "<hr><div align='center'>No File Name</div><hr>";
  return;
}
// DailyExchangeReportTop_20210813
$data_date = substr($file, 23, 8);
$sql = "SELECT stk_code,note FROM stk_090_daily_stocks where add_date='". $data_date ."' and user_id='".$_SESSION["userid"]."' ";	
$sql .= "order by stk_code";
$result2=mysql_query($sql);
$notes="";
while($row=mysql_fetch_array($result2)) {
	list($stk_code,$note)=$row;
	$notes.="\"".$stk_code."\":\"".$note."\",";
}

$data = get_data($dir.$file, $tag);

function get_data($file, $tag){
	$contents="";
	$count = 1;
  $isTaget = false;
  $index = 0;
	
	if (($handle = fopen($file, "r")) !== FALSE) {
	    //while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		while (($data = __fgetcsv($handle, 1000, ",")) !== FALSE) {
	        $num = count($data);
	        //echo "<p> $num fields in line $row: <br /></p>\n";
	        /*
	        for ($c=0; $c < $num; $c++) {
	            echo $data[$c] . "<br />\n";
	        }
	        */

	        // $stock_code=substr($data[1]);
          // $stock_name=substr($data[1],strrpos($data[1]," ")+1 );
          if($num==1){
            if($tag=="all"){
              $isTaget = true; 
            }elseif(trim($tag)==trim($data[0])){
              $isTaget = true; 
            }else{
              $isTaget = false; 
            }
            if($isTaget){
              $index = 0;
              $contents.= ("<tr>\n<th colspan='10' nowrap='nowrap'><h2>". $data[0] ."</h2></th></tr>");
            }
            // echo $contents;
            
          }else if($num==17){
            if($index<2){
              
            }else{
              if($isTaget){
                $stock_code=$data[0];
                $stock_name=$data[1];
                $stock_price=$data[10];
                $order = $index-1;
                $contents.= ("<tr>\n<th class='text-center'>名次</th><th class='text-center' nowrap='nowrap'>股票代號/名稱</th><th class='text-right'>成交價</th><th class='text-right'>&nbsp;&nbsp;漲跌&nbsp;&nbsp;</th><th class='text-right'>&nbsp;漲跌幅&nbsp;</th><th class='text-right'>最高</th><th class='text-right'>最低</th><th class='text-right'>價差</th><th class='text-right'>成交張數</th><th class='text-right'>成交值(億)</th></tr>");
                $contents.= "<tr><td class='text-center'>$order</td><td class='text-center'><a href='http://stock.jayi.com.tw/stock_group/stock_detail.php?stk_code=$stock_code' target='_blank'>$stock_code $stock_name</a></td><td class='text-right'>$stock_price</td><td class='text-right'>$data[11]</td><td class='text-right'>". $data[12]*100 ."%</td><td class='text-right'>$data[7]</td><td class='text-right'>$data[8]</td><td class='text-right'>". round($data[7]-$data[8],2) ."</td><td class='text-right'>". round($data[3]/1000,2) ."</td><td class='text-right'>". round($data[5]/100000000,2) ."</td></tr>";
                $contents.= "<tr>\n<td colspan='10' aling='right' valign='bottom'>\n<input type='text' name='note' id='n".$stock_code."' style='width:60%;'>\n<input type='text' name='price' id='p".$stock_code."' value='".$stock_price."' style='width:10%;text-align:right;color:blue;'>\n
              &nbsp;&nbsp;\n";
                $contents.= "<img src='../images/like.png' onclick='track(\"".$stock_code."\",\"".$stock_name."\",\"1\")' style='cursor: pointer;'/>&nbsp;&nbsp;\n";
                $contents.= "<img src='../images/unlike.png' onclick='track(\"".$stock_code."\",\"".$stock_name."\",\"2\")' style='cursor: pointer;'/>&nbsp;&nbsp;<span id='r".$stock_code."' ></span>\n</td>\n</tr>\n";
                $contents.= "";
                $contents.= "<tr>\n<td colspan='10' class='text-center'>\n<img class='kchart' original='http://stock.wearn.com/finance_chart.asp?stockid=".$stock_code."&timekind=0&timeblock=90&sma1=&sma2=&sma3=&volume=0&indicator1=Vol&=http%3A//stock.wearn.com/CallAjaxStock.asp'/></td></tr>";
                
              }
            }
          }
          $index++;
	        //echo($contents);
	    }
	    fclose($handle);
	}
	//echo(big5_utf8_encode($count));
    return $contents;
}


function __fgetcsv(&$handle, $length = null, $d = ",", $e = '"') {
    $d = preg_quote($d);
    $e = preg_quote($e);
    $_line = "";
    $eof=false;
    while ($eof != true) {
        $_line .= (empty ($length) ? fgets($handle) : fgets($handle, $length));
        $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);
        if ($itemcnt % 2 == 0)
            $eof = true;
    }
   $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));

    $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
    preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
    $_csv_data = $_csv_matches[1];

    for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++) {
        $_csv_data[$_csv_i] = preg_replace("/^" . $e . "(.*)" . $e . "$/s", "$1", $_csv_data[$_csv_i]);
        $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);
    }
    return empty ($_line) ? false : $_csv_data;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="每日排行">
    <title>每日排行</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/dashboard/">

    <!-- Bootstrap core CSS -->
    <style>
    .kchart {  
        max-height: 100%;  
        max-width: 100%; 
        width: auto;
        height: auto;
        margin: auto;
    }
    </style>
<link href="bootstrap/4.5.0/css/bootstrap.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="../js/jquery.lazyload.mini.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/util.js" charset="utf-8"></script>
<script>
$(document).ready(function(){
	$("img").lazyload({
		 //placeholder : "../images/progress.gif",
		 placeholder : "../images/grey.gif",
		 effect      : "fadeIn"
	});

	notes={<?=$notes?>};
	//alert(notes[1].length);
	var content='';
	for(var key in notes){
		content +="屬性名稱："+ key+" ; 值： "+notes[key]+"\n";
		if(notes[key]!="")
			$("#n"+key).val(notes[key]);
		else
			$("#n"+key).val("No comment!!");
		$("#r"+key).html("Added");

	}
	console.log(content);
});

function track(stk_code,stk_name,kind)
{
	if($("#data_date").val()=="")
	{
		alert("資料日期不應為空白!!");
		return;
	}
	if($("#data_date").val().length!=8 )
	{
		alert("資料日期長度非 8 碼!!");
		return;		
	}
	if(!isNumber($("#data_date").val()))
	{
		alert("資料日期應只為數字!!");
		return;		
	}
	if(!isDate($("#data_date").val(),"yyyyMMdd"))
	{
		alert("資料日期值有誤(yyyyMMdd)!!");
		return;		
	}
	
	this.stk_code=stk_code;
	var url = '../stocktracker/stocktrackerdataop.php';
	$.ajax({
   	type: "post",
	data:{cmd:"modify",stk_code:stk_code,stk_name:stk_name,kind:kind,data_date:$("#data_date").val(),note:$("#n"+stk_code).val(),price:$("#p"+stk_code).val()}, 
   	url: url,
   	//data: pars,
	dataType: "html",
	beforeSend:showLoading,
  success: showResponse
	
	});
	
	//alert($("#n"+stk_code).val());
	//alert(stk_code);
}
function showResponse(msg)
{
	//$("#result").html("");
	
	data=msg.split(":");
	//alert(escape(data[0]));
	if(data[0]=="ok")
	{
		//alert("儲存完成!!");
		
		$("#r"+data[1]).html("儲存完成");
		//$("#result").html("SAVED");
		//$("#go_save").attr("disabled", "");
	}
	else if(data[0]=="err")
	{
		alert("儲存失敗!!"+data[2]);
		$("#r"+data[1]).html(data[2]);
	}
	//Element.hide('result');
	//$G("submit").disabled=true;

}


function showLoading()
{
	//alert(stk_code);
	//$("#i"+stk_code).attr('src', '../images/progress.gif');
	$("#r"+stk_code).html("<img src='../images/progress.gif'>儲存中...");
	//Element.show('result');
}
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
<div class="container">
  <div class="row">

      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?=$data_date?> 排行</h1>
        <input type="hidden" name='note' id='data_date' value='<?=$data_date?>' style='width:100px;'>
      </div>
      <div id="wrapper" class="container-fluid">
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
<?=$data?>
          <?php

// $content="";
// $file_name="";
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
//           echo <<< EOD
//           <tr>
//           <td><a href='$file_name'>$title __</></td>
//           <td>$file_name</td>
//           <td>StockDividend</td>
//           <td>&nbsp;</b></a></td>
//           <td><div id='price$'>Loading...</div></td>
//           <td><div id='pfp$'>Loading...</div></td>
//           </tr>
// EOD;
//         }
//         // 
//       }
//     }
//   }
// }
// clearstatcache();//清除檔案狀態快取
              
           ?>
        </table>
      </div>
  </div>
</div>
<script src="bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>

</body>
</html>
