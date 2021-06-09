<?php
include("config.php");
require "util.php"; 
session_start();  //啟動 session
if ( !isset($_SESSION['userid']) )
{
	header("Location: index.php");
}
$user_id = $_SESSION["userid"];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ProductAdd</title>

    <!-- Bootstrap core CSS -->
<link href="bootstrap/4.5.0/css/bootstrap.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script>
$(document).ready(function(){
  $( "#saveBtn" ).click(function() {
    //alert( "Handler for .click() called." );
  });
});
</script>
</head>
<body class="bg-light">

<div class="py-5 text-center">
    <h1>新增產品</h1>
</div>

<div class="container">

    <form>
    <div class="row">
        <div class="col">
        <label for="Name">產品名稱</label>
        <input type="text" class="form-control" placeholder="產品名稱">
        </div>
    </div>
    <div class="row">
        <div class="col">
        <label for="Name">產品名稱</label>
        <input type="text" class="form-control" placeholder="產品名稱">
        </div>
    </div>

    <div class="mb-3">
          <label for="username">Username</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">@</span>
            </div>
            <input type="text" class="form-control" id="username" placeholder="Username" required>
            <div class="invalid-feedback" style="width: 100%;">
              Your username is required.
            </div>
          </div>
        </div>
    </form>
    
</div><!-- /.container -->

</body>
</html>