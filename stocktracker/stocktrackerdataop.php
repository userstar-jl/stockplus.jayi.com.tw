<?php
include("../config.php");
include("../util.php");

session_start();  //啟動 session
if ( !isset($_SESSION['username']) )
{
//	header("Location: ../index.php");
echo("err:$stk_code:您尚未登入系統!!");
	exit;
}

//sleep(2);
//$data_date="20130409";
if(isset($_POST['data_date'])) {
	$data_date = $_POST["data_date"];
}else{
	$data_date=date(Ymd);
}
//data:{cmd:"modify",stk_code:stk_code,stk_name:stk_name,kind:kind,data_date:$("#data_date").val(),note:$("#n"+stk_code).val(),price:$("#p"+stk_code).val()}, 
$cmd=$_POST['cmd'];
$stk_code=$_POST['stk_code'];
$stk_name=$_POST['stk_name'];
$kind=$_POST['kind'];
$price=$_POST['price'];
$note=$_POST['note'];
if($cmd=="modify")
{
	$sql = "select id FROM stk_090_daily_stocks where stk_code='$stk_code' and add_date='$data_date' and user_id='".$_SESSION["userid"]."'";
	$result=mysql_query($sql);
	//echo($sql);
	
	if(mysql_num_rows($result)<=0)//新增
	{
		$id=get_tid("stk_090_daily_stocks");
		$sql = "insert into  stk_090_daily_stocks(id,stk_code,stk_name,price,kind,add_date,note,user_id) values ('$id','$stk_code','$stk_name','$price','$kind','$data_date','$note','".$_SESSION["userid"]."')";
		//echo $sql;
		$result=mysql_query($sql);
	}
	else //修改
	{
		$sql = "update stk_090_daily_stocks set stk_name='$stk_name',price='$price',note='$note',kind='$kind' where stk_code='$stk_code' and add_date='$data_date' and user_id='".$_SESSION["userid"]."'";
		$result=mysql_query($sql);
	}
	
	//echo $content;
	echo "ok:".$stk_code;
}//end if
else
{
	echo "err:$stk_code:錯誤原因不明";
}
?>