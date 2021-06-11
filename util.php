<?php
//include_once("../class/big5_func/big5_func.inc");
// include_once("../class/simple_html_dom.php");
// include_once("../class/dBug.php");
function get_tid($table_name)
{
	$id=date("YmdHis");
	//$id="20051226165233";
	$sql = "SELECT right(id,3) FROM $table_name where id like '$id%' order by id desc limit 1 ";
	$result=mysql_query($sql);
	$row=mysql_fetch_row($result);
	list($id)=$row;
	if($id=="")
		$id=date("YmdHis")."001";
	else
		//$id;
		$id=date("YmdHis").sprintf("%03d", ++$id);
	return $id;
}

function get_tid_type2($table_name, $timestamp)
{
	//$id=date("YmdHis");
	//$id="20051226165233";
	$sql = "SELECT right(id,3) FROM $table_name where id like '$timestamp%' order by id desc limit 1 ";
	$result=mysql_query($sql);
	$row=mysql_fetch_row($result);
	list($id)=$row;
	if($id=="")
		$id=$timestamp."001";
	else
		//$id;
		$id=$timestamp.sprintf("%03d", ++$id);
	return $id;
}

function get_id($table_name)
{
	$sql = "SELECT id FROM ".$table_name." order by id desc";
	$result=mysql_query($sql);
	$row=mysql_fetch_row($result);
	list($id)=$row;
	return ++$id;
}

function timestamp()
{
	return date("YmdHis");
}
function get_stk_name($stk_code)
{
	$sql = "select name FROM stock_list where  loc_code='$stk_code' ";
	$result=mysql_query($sql);
	$row_sum=mysql_num_rows($result);
	$row=mysql_fetch_row($result);
	list($stk_name)=$row;	
	return $stk_name;
}

function data_count($sql)
{
	$result=mysql_query($sql);
	$row_sum=mysql_num_rows($result);
	return $row_sum;
}
function is_stk_code($stk_code)
{
	$sql = "select name FROM stock_list where  loc_code='$stk_code' ";
	$result=mysql_query($sql);
	$row_sum=mysql_num_rows($result);
	$row=mysql_fetch_row($result);
	list($stk_name)=$row;
	if($stk_name!="")
		return true;
	else
		return false;
}

function get_group_name($id)
{
	$sql = "select name FROM stock_group where  id='$id' ";
	$result=mysql_query($sql);
	$row_sum=mysql_num_rows($result);
	$row=mysql_fetch_row($result);
	list($name)=$row;	
	return $name;
}
function get_res_rpt_title($kind,$id)
{
	if($kind==0)
		$name=get_group_name($id);
	else
		$name=get_stk_name($id);
	return $name;
}


function get_tradetype_select($tmp)
{
	$r="<select name='trade_type'><option value='00' selectd>- 請選擇 -</option>";
	$sql = "select id,name FROM stk_062_trade_type where  id like '$tmp%' ";
	//$r=$sql;
	$result=mysql_query($sql);
	
	while($row=mysql_fetch_array($result)) {
		list($id,$name)=$row;
		
		$r.="<option value='$id'>$name</option>";	
	}
	$r.="</select>";
	return $r;
}

function get_stk_acc_select($user_id)
{
	$r="<select name='stk_acc'><option value='00' selectd>- 請選擇 -</option>";
	$sql = "select company_code,acc_no,securities_company FROM stk_064_stock_account where  user_id='$user_id' ";
	//$r=$sql;
	$result=mysql_query($sql);
	
	while($row=mysql_fetch_array($result)) {
		list($company_code,$acc_no,$securities_company)=$row;
		
		$r.="<option value='$company_code$acc_no'>$company_code $securities_company</option>";	
	}
	$r.="</select>";
	return $r;
}

function get_mystocks_select($ob_id,$user_id)
{
	$r="<select name='$ob_id' id='$ob_id'><option value='00' selectd>- 請選擇 -</option>";
	$sql = "select distinct sl.loc_code,sl.name FROM stk_060_trade_rec stk_060 ";
	$sql .= "inner join stock_list sl on sl.loc_code=stk_060.stk_code ";
	$sql .= "where user_id='$user_id' ";
	//$r=$sql;
	$result=mysql_query($sql);
	
	while($row=mysql_fetch_array($result)) {
		list($id,$name)=$row;		
		$r.="<option value='$id'>$id  $name</option>";	
	}
	$r.="</select>";
	return $r;
}

function bspoint_arr($user_id)
{
	$sql = "select stk_code,type,price,note from stk_066_bs_point ";
	$sql .= "where user_id='$user_id' ";
	$result=mysql_query($sql);
	$record = array();
	while($row=mysql_fetch_array($result))
	{
		//$row=mysql_fetch_row($result);
		list($stk_code,$type,$price,$note)=$row;
		//echo "stk_code:$stk_code--type:$type:--price:$price<hr>";
		$record[$stk_code.$type]["p"]=$price;
		$record[$stk_code.$type]["n"]=$note;
	}
	//mysql_close($link);
	return $record;
}


function bspoint_arr1($user_id,$stk_code)
{
	$sql = "select stk_code,type,price,note from stk_066_bs_point ";
	$sql .= "where user_id='$user_id' and stk_code='$stk_code' ";
	$result=mysql_query($sql);
	$record = array();
	$row=mysql_fetch_row($result);
	if($row)
	{
		while($row!=null)
		{
			//$row=mysql_fetch_row($result);
			list($stk_code,$type,$price,$note)=$row;
			//echo "stk_code:$stk_code--type:$type:--price:$price<hr>";
			$record[$type]["p"]=$price;
			$record[$type]["n"]=$note;
			
			$row=mysql_fetch_row($result);
		}
	}
	else
		$record="nodata";
	//mysql_close($link);
	return $record;
}

/**
　　* 從數組中刪除空白的元素（包括只有空白字符的元素）
　　*
　　* @param array $arr
　　* @param boolean $trim
　　*/
function array_remove_empty(& $arr, $trim = true)
{
	foreach ($arr as $key => $value) {
		if (is_array($value)) {
			array_remove_empty($arr[$key]);
		} else {
			$value = trim($value);
			if ($value == '') {
			unset($arr[$key]);
			} elseif ($trim) {
			$arr[$key] = $value;
			}
		}
	}
} 
function get_yahoo_news_content($url)
{
	$url = urlencode($url);
	//$url = big5_utf8_encode($url);
	//$filename = basename($url);
	//echo $filename; //filename.php
	
	$a = array("%3A", "%2F", "%40");
	$b = array(":", "/", "@");
	$url = str_replace($a, $b, $url);
	
	$dom = file_get_html($url);
	$contents = "";
	//= $dom->find('table[class=yui-text-left yui-table-wfix ynwsart]');
	foreach($dom->find('table[class=yui-text-left yui-table-wfix ynwsart]') as $element)
	{
		//取代
		$element->getElementById("ypopsub")->outertext="";
		foreach($element->find('table') as $tables)
		{
			$tables->outertext="";
		}
		//echo(big5_utf8_encode($element->innertext));
		$contents.=$element->outertext;
		//$contents.=$element->getElementById("ypopsub")->innertext="aa";
	}
	//$contents = $dom->plaintext;
	//$contents = big5_utf8_encode($contents);
	return big5_utf8_encode($contents);
}

function OpenOrNot($require_date){
	// 今天是否開市 規則：六日不開市 && 特殊節日不開市
	$open_or_not = false;
	//是否為特殊節日
	$sql = "select * from holiday_schedule where holiday_date='" . $require_date . "';";
	//echo($sql);
	$result=mysql_query($sql);
	$row=mysql_fetch_row($result);

	$weekday = date('w',strtotime($require_date));
	//非星期日 && 非星期六 && 非特殊節日
	//echo("$require_date weekday:$weekday; row:$row<hr>");
	if($weekday!=0 && $weekday!=6 && $row==null){
		$open_or_not = true;
	}
	//echo $require_date."--";
	return $open_or_not;
}

function toIds($stk_code){
	$sql = "SELECT distinct stock_list.loc_code as stock_code,stock_list.market as stock_type FROM stock_list where loc_code in ($stk_code);";
	//echo("<hr>".$sql."<hr>");
	$result=mysql_query($sql);
	$row=mysql_fetch_row($result);
	$ids="";
	while($row!=NULL){
		list($stock_code,$stock_type)=$row;
		if($stock_type=="0"){//上市 tse
			$ids=$ids."tse_".$stock_code.",";
		}else if($stock_type=="1"){//上櫃 otc
			$ids=$ids."otc_".$stock_code.",";
		}
		$row=mysql_fetch_row($result);
	}
	
	return substr($ids,0,-1);
}

function getBoss(){
	$dir = 'D:\\AppServ\\www\\stock_sys\\boss\\';
	//$content = "aa";
	$files  = array();
	if ($handle = opendir($dir)) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				//$content .= "$entry\n";
				array_push($files, $entry);
			}
		}
	
		closedir($handle);
	}
	$file = $files[rand(0, count($files)-1)];
	$fileData = file_get_contents($dir.$file);
	$result=array("FileName"=>$file,"FileContent"=>$fileData);
	return $result;
}

function get_img_src($content)
{
	$result = array();
	//步驟一：將「\"」改成「"」
	//因為tiny_mce會加所有的「"」改成「\"」，避免作為SQL Script時產生錯誤
	$pattern = "/([\\\][\"])/i";//用二個[]來代表「\」「"」
	$replacement = "\"";
	$HTMLcontent=preg_replace($pattern, $replacement, $content);

	preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $HTMLcontent, $matches);
	//new dBug($matches);
	//new dBug($matches[count($matches)-1]);
	$urlsArray = $matches[count($matches)-1];
	foreach ( $urlsArray as $key => $value ) {
		//print("<hr>$key:$value\n</hr>\n");
		array_push($result, $value);
	}
	/*
	foreach ( $matches as $key => $value ) {
		//$tmp = $matches[ $key ];
		$tmp = "";
		$count = 0;
		//print("<hr>$count:$key:$value\n</hr>\n");
		foreach ( $value as $k2 => $v2 )
		{
			//print("<hr>$count:$k2:$v2\n</hr>\n");
      		//$tmp .= $v2;
			//array_push($result, $v2);
			//print("<hr>$count:$v2\n</hr>\n");

			// $data = $v2;
			// if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
			// 	$file_name=md5(uniqid (""));
			// 	//save_Base64Image($SaveImagePath, $file_name,$data);
			// 	$tmp .= "Base64 Data";
			// }else{
			
			// 	//$span_value = $matches[ $key ][2];
			// 	$tmp .=$count.":".$data ."\r\n";
			// }
			$count++;
		}
	}
	print($tmp);
	print(json_encode($result));
	*/
	return (json_encode($result));
}
?>