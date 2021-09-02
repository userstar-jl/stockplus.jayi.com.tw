<?php
include("config.php");
// if ( isset($_SESSION['username']) )
// {
// 	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS"))
// 				header("Location:/mobile/index.php");
// 			else
// 				header("Location: iframe.php");
// }
$url = $_GET['url'];
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
			if(isset($_GET['url'])){
				header("Location: " . $_GET['url']);
			}else{
				if(strpos($agent,"Kindle"))
					header("Location: FeatureList.php");
				else if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS"))
					header("Location: FeatureList.php");
				else
					header("Location: FeatureList.php");
			}
		}
		else
			$err_msg="帳號或密碼錯誤!!";
	}
	else
		$err_msg="帳號或密碼錯誤!!";

	$err_msg="<div class=\"alert alert-warning alert-dismissible fade show\" role=\"alert\">\n\n
	<strong>Warning</strong>$err_msg \n\n
	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n\n
	<span aria-hidden=\"true\">&times;</span>\n\n
	</button>\n\n
	</div>\n\n";
}
?>
<!doctype html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Stock Plus Signin</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/sign-in/">

	<!-- Bootstrap core CSS -->
	
	<link href="bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">

	<meta name="theme-color" content="#563d7c">


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
	</style>
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

	<script type="text/javascript" src="js/jquery-3.4.1.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script language="javascript">
	$(document).ready(function(){
		console.log("cookie('id'):" + $.cookie("id"));
		if($.cookie("id")&&$.cookie("pw")){
			$('#inputUserId').val($.cookie("id"));
			$('#inputPassword').val($.cookie("pw"));
		}else{
		}
		$('#remember').change(function () {
			console.log($('#remember').prop("checked"));
		});
			
	})


	function chk_data(){
		if ($('#remember').is(":checked")){
			$.cookie("id", $('#inputUserId').val(),{ expires: 30, path: '/'});
			$.cookie("pw", $('#inputPassword').val(),{ expires: 30, path: '/'});
		}
		return true;
	}
	</script>
  </head>
  <body class="text-center">
    <form class="form-signin" method="post" action="" onSubmit="return chk_data();">
  <img class="mb-4" src="https://getbootstrap.com/docs/4.5/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
  <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
  <?=$err_msg?>
  <label for="inputUserId" class="sr-only">帳號</label>
  <input type="text" name="id" id="inputUserId" class="form-control" placeholder="帳號" required autofocus>
  <label for="inputPassword" class="sr-only">密碼</label>
  <input type="password" name="pw" id="inputPassword" class="form-control" placeholder="密碼" required>
  <div class="checkbox mb-3">
    <label>
      <input type="checkbox" id="remember" value="remember-me"> Remember me
    </label>
  </div>
  <input type="hidden" name="url" id="url" value="<?=$url?>">
  <button class="btn btn-lg btn-primary btn-block" type="submit">登入</button>
  <p class="mt-5 mb-3 text-muted">&copy; 2017-2020</p>
</form>
</body>
</html>
