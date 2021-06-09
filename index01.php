<?php
include("config.php");
// if ( isset($_SESSION['username']) )
// {
// 	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS"))
// 				header("Location:/mobile/index.php");
// 			else
// 				header("Location: iframe.php");
// }
if ( isset($_POST['id']) )
{
	$login_id = $_POST['id'];
	$login_pw = $_POST['pw'];
	$sql="select id,name,pwd from sys_000_user_data ";
	$sql.="where id='$login_id'";
	//echo $sql;
	$result=mysql_query($sql);
	$row=mysql_fetch_row($result);
	list($id,$name,$pw)=$row;
	//print("$id,$name,$pw");
	if($id!="")
	{
		if($pw==$login_pw)
		{
			session_start();  //啟動 session
			$_SESSION['username']=$name;
			$_SESSION['userid']=$id;
			
			//$_SESSION['Today'] 判斷今天是否有登入，如果沒有，需要重新登入，為了判斷當日股市是否為交易日
			$_SESSION['Today']=date(Ymd);
			//header("Location: tree_menu/index.php");
			// 今天是否開市 規則：六日不開市 && 特殊節日不開市
			$open_or_not = false;
			//是否為特殊節日
			$sql = "select * from holiday_schedule where holiday_date='" . date("Ymd") . "';";
			//echo($sql);
			$result=mysql_query($sql);
			$row=mysql_fetch_row($result);
			
			$weekday = date('w',strtotime(date(Ymd)));
			//非星期日 && 非星期六 && 非特殊節日
			if($weekday!=0 && $weekday!=6 && $row==null){
				$open_or_not = true;
			}
			//echo ('w') . "--" .$weekday . "$open_or_not:".$open_or_not;
			$_SESSION['OpenOrNot'] = $open_or_not;
			//return;
			$agent = $_SERVER['HTTP_USER_AGENT'];
			// echo $agent;
			// return;
			if(strpos($agent,"Kindle"))
				header("Location:/mobile/list.php");
			else if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS"))
				header("Location:/mobile/list.php");
			else
				header("Location: FeatureList.php");
			
		}
		else
			$err_msg="帳號或密碼錯誤!!";
	}
	else
		$err_msg="帳號或密碼錯誤!!";

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>四湖羊肉小舖</title>
<link href="css/sty.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
input {
	BORDER-TOP: #fff0c1 1px solid;
	BORDER-BOTTOM: #fff0c1 1px solid;
	BORDER-RIGHT: #fff0c1 1px solid;
	BORDER-LEFT: #fff0c1 1px solid;
	FONT-SIZE: 16px;
	BACKGROUND: #ffffff;
	WIDTH:auto;
	COLOR: #333333;
	FONT-FAMILY: arial;
	font-weight: normal;
}
-->
</style>
<script language="javascript">

function chk_data()
{
	if(document.frm.id.value=="")
	{
		alert("請填寫帳號!!");
		return false;
	}
	
	if(document.frm.pw.value=="")
	{
		alert("請填寫密碼!!");
		return false;
	}
	return true;
}
</script>
</head>

<body onLoad="document.frm.id.focus();">
<table width="95%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th scope="row"><font color="#FF0000" size="-1"><?=$err_msg?></font>&nbsp;</th>
  </tr>
  <tr>
    <th scope="row" align="center"><table border="0" cellspacing="0" cellpadding="0" class="list_table" style="width:300px "><form name="frm" method="post" action="" onSubmit="return chk_data();">
      <tr>
        <th colspan="2" scope="row">四湖羊肉小舖&nbsp;</th>
        </tr>
      <tr>
        <th width="30%" class="list_td1" scope="row">帳號</th>
        <td width="70%" class="list_td2" align="center"><input name="id" type="text" id="id"></td>
      </tr>
      <tr>
        <th class="list_td1" scope="row">密碼</th>
        <td class="list_td2" align="center"><input type="password" name="pw"></td>
      </tr>
	  <tr>
        <th colspan="2" scope="row">
          <input type="submit" name="Submit" value="登入">
        </th>
        </tr></form>
    </table></th>
  </tr>
  <tr>
    <th scope="row">&nbsp;</th>
  </tr>
</table>
</body>
</html>
