<?php
/*
Issue: 
未解
When https e.g. https://stock.wearn.com/foreign_buy.asp?stockid=2327&rand=1528962051709
cURL error: 
Unknown SSL protocol error in connection to stock.wearn.com:443

*/
include("../config.php");
include("../util.php");

session_start();  //啟動 session
if ( !isset($_SESSION['username']) )
{
//	header("Location: ../index.php");
echo("err:$stk_code:您尚未登入系統!!");
	exit;
}

$cmd = $_POST['cmd'];
$FileExtension = $_POST['FileExtension'];
$ImgUrl = $_POST['ImgUrl'];
set_time_limit(0); //0為無限制
//有錯誤時，均交由 Function errorHandler來處理
set_error_handler('errorHandler');
$SaveErrMSG="";
// $SaveImagePath = $singapore_stock_dir;
// $NewImgUrl = "../sg/galleries/stock/";
$SaveImagePath = $images_repos;
$NewImgUrl = $images_url;
$data_date=date('Ymd');

if($cmd=="OneImg"){
	//echo "ok:".$stk_code.":".$ImgUrl;
	if($stk_code==""){
		$file_name = $data_date."_".md5 (uniqid ("")).".".$FileExtension;
	}else{
		$file_name = $stk_code."_".$data_date."_".md5 (uniqid ("")).".".$FileExtension;
	}
	if(save_image($ImgUrl, $SaveImagePath.$file_name)){
		$resultArray = array(
			"Result" => "ok",
			"NewImgUrl" => $NewImgUrl.$file_name
		);
	}else{
		$resultArray = array(
			"Result" => "err",
			"Msg" => $SaveErrMSG
		);
	}	
	echo json_encode($resultArray);
}else if($cmd=="DelImg"){
	if(del_image($SaveImagePath.$ImgUrl))
	{
		$resultArray = array(
		"Result" => "ok"
		);
	}else{
		$resultArray = array(
			"Result" => "err",
			"Msg" => $SaveErrMSG
		);
	}
	echo json_encode($resultArray);
}else if($cmd=="PasteUpdate"){
	if($stk_code==""){
		$file_name = $data_date."_".md5 (uniqid ("")).".".$FileExtension;
	}else{
		$file_name = $stk_code."_".$data_date."_".md5 (uniqid ("")).".".$FileExtension;
	}

	$destination = $SaveImagePath.$file_name;
	$sourceString = $_POST["imgstring"];
	//$sourceString = $imgstring;
	$image = imagecreatefromstring(base64_decode($sourceString));
	if ($im !== false) {	
		imagejpeg($image, $destination, 100);
		$resultArray = array(
		"Result" => "ok",
		"NewImgUrl" => $NewImgUrl.$file_name
		);
	}else{
		$resultArray = array(
			"Result" => "err",
			"Msg" => "An error occurred."
			);
	}

	echo json_encode($resultArray);
}else{
	$resultArray = array(
		"Result" => "err",
		"Msg" => "錯誤原因不明"
		);
	echo json_encode($resultArray);
}

function del_image($file)
{
	global $SaveErrMSG;
	$SaveErrMSG = $file;
	//delete($file);
	return unlink($file);
}

function save_image($url,$fileTo)
{
	global $SaveErrMSG;
	//use curl
	$ch = curl_init($url);
	//$fp = fopen($fileTo, "wb");
	//curl_setopt($ch, CURLOPT_SSLVERSION, 1); // Apparently 2 or 3
	curl_setopt($ch, CURLOPT_SSLVERSION, 1); //This sets the version to TLSv1 (not SSLv1)
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	$userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';
	curl_setopt($ch, CURLOPT_USERAGENT,$userAgent);

	
	$buffer = curl_exec($ch);
	if (!$buffer){
   		$SaveErrMSG.= "cURL error number:" .curl_errno($ch)."<hr>";
   		$SaveErrMSG.= "URL: <br>$url<hr>cURL error: <br>" . curl_error($ch);
		curl_close($ch);
		//fclose($fp);
		return false;
	}else{
		$fp = fopen($fileTo, "wb");
		fwrite($fp, $buffer);
		fclose($fp);
		curl_close($ch);
		return true;
	}
	/*
	if(curl_exec($ch)){	
		curl_close($ch);
		fclose($fp);
		return true;
	}else{
		$SaveErrMSG.= curl_error($ch);
		fclose($fp);
		return false;
	}
	*/
	/*
	if (!copy($url, $fileTo)) {
    	//print("複製檔案 $file 失敗...<br>\n");
		$SaveErrMSG.="儲存 $url 失敗!!<br>";
		//echo $GLOBALS("SaveErrMSG");
		return false;
	}
	return true;
	*/
}

function errorHandler( $errno, $errstr, $errfile, $errline, $errcontext)
{
	global $SaveErrMSG;
	$SaveErrMSG.= 'Into '.__FUNCTION__.'() at line '.__LINE__.
  "<br><br>---ERRNO---<br>". print_r( $errno, true).
  "<br><br>---ERRSTR---<br>". print_r( $errstr, true).
  "<br><br>---ERRFILE---<br>". print_r( $errfile, true).
  "<br><br>---ERRLINE---<br>". print_r( $errline, true).
  "<br><br>---ERRCONTEXT---<br>".print_r( $errcontext, true).
  "<br><br>Backtrace of errorHandler()<br>";
  /*
  echo 'Into '.__FUNCTION__.'() at line '.__LINE__.
  "\n\n---ERRNO---\n". print_r( $errno, true).
  "\n\n---ERRSTR---\n". print_r( $errstr, true).
  "\n\n---ERRFILE---\n". print_r( $errfile, true).
  "\n\n---ERRLINE---\n". print_r( $errline, true).
  "\n\n---ERRCONTEXT---\n".print_r( $errcontext, true).
  "\n\nBacktrace of errorHandler()\n".
  print_r( debug_backtrace(), true);
  */
 }
?>