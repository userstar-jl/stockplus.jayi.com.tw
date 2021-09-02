<?php 
// webapi/CostVolumeProfit.php
session_start();  //啟動 session
include("../config.php");
include("../util.php");

$login = false;
if($userid!="" && $userpw!=""){
    //登入驗證
    $sql="select id,name,pwd from sys_000_user_data ";
    $sql.="where id='$userid'";
    $result=mysql_query($sql);
	$row=mysql_fetch_row($result);
	list($id,$name,$pw)=$row;
	if($id!=""){
        if($pw==$userpw)
		{
            session_start();  //啟動 session
			$_SESSION['username']=$name;
            $_SESSION['userid']=$id;
            $login = true;
        }
    }
    if(!$login){
        $output = array(
            "message" => "帳號或密碼錯誤",
            "status" => "error");

        header('Content-type: application/json; charset=utf-8');
        print(json_encode($output));
        exit();
    }  
}
$cmd = $_REQUEST["cmd"];
if(isset($cmd)){
    if($cmd=="update"){
        $data_date = $_REQUEST["data_date"];
        $tc = $_REQUEST["tc"];
        $ts = $_REQUEST["ts"];
        $pv = $_REQUEST["pv"];
        $pre_pl = $_REQUEST["pre_pl"];
        $pre_pl_ratio = $_REQUEST["pre_pl_ratio"];
        $note = $_REQUEST["note"];
        addCostVolumeProfit($data_date, $tc, $ts, $pv, $pre_pl, $pre_pl_ratio, $note);
    }else if($cmd=="getAll"){
        getAllCostVolumeProfit();
    }
}else{
    getAllCostVolumeProfit();
}

function addCostVolumeProfit($data_date, $tc, $ts, $pv, $pre_pl, $pre_pl_ratio, $note){
    $output = "";
    if ( isset($_SESSION['userid']) ){
        $user_id=$_SESSION["userid"];
        $sql = "SELECT id, data_date, tc, ts, pv, pre_pl, pre_pl_ratio, note FROM stk_063_cost_volume_profit WHERE user_id='$user_id' and data_date='$data_date'";
        //echo($sql);
        $result=mysql_query($sql);
        $row_sum=mysql_num_rows($result);
        if($row_sum==0){
            $sql = "INSERT INTO stock_sys.stk_063_cost_volume_profit (id, data_date, tc, ts, pv, pre_pl, pre_pl_ratio, note, user_id) VALUES (NULL, '$data_date', '$tc', '$ts', '$pv', '$pre_pl', '$pre_pl_ratio', '$note', '$user_id');";
            $result=mysql_query($sql);
            $output=array(
                // "sql" => $sql,
                "Success" => TRUE,
                "Msg" => "ADD OK"
            );
        }else{
            $sql = "UPDATE stock_sys.stk_063_cost_volume_profit SET tc = '$tc',ts = '$ts',pv = '$pv',pre_pl = '$pre_pl', pre_pl_ratio='$pre_pl_ratio', note='$note' WHERE user_id='$user_id' and data_date='$data_date'";
            $result=mysql_query($sql);
            $output=array(
                // "sql" => $sql,
                "Success" => TRUE,
                "Msg" => "UPDATE OK"
            );
        }
    }else{
        $output = array(
            "Success" => FALSE,
            "Msg" => "You are not logged in. Please log in and try again.",
            "status" => "error");
    }
    header('Content-type: application/json; charset=utf-8');
    print(json_encode($output));
}

function getAllCostVolumeProfit(){

    $output = "";
    if ( isset($_SESSION['userid']) ){
        $user_id=$_SESSION["userid"];
        $sql = "SELECT id, data_date, tc, ts, pv, pre_pl, pre_pl_ratio, note FROM stk_063_cost_volume_profit WHERE user_id='$user_id' order by data_date desc";
        //echo($sql);
        $result=mysql_query($sql);
        $row_sum=mysql_num_rows($result);
        $rows = array();
        if($row_sum>0){
            $row=mysql_fetch_row($result);
            while($row!=NULL){
                list($id, $data_date, $tc, $ts, $pv, $pre_pl, $pre_pl_ratio, $note)=$row;
                // $rows[] = $row;
                $tmp = array(
                    "data_date" => $data_date,
                    "tc" => $tc, 
                    "ts" => $ts, 
                    "pv" => $pv, 
                    "pre_pl" => $pre_pl, 
                    "pre_pl_ratio" => $pre_pl_ratio, 
                    "note" => $note 
                );
                array_push($rows,$tmp);
                $row=mysql_fetch_row($result);
            }
        }
        // $rows = array();
        // while($r = mysqli_fetch_assoc($result)) {
        //     $rows[] = $r;
        // }
        $output=array(
            // "sql" => $sql,
            "Success" => TRUE,
            "Data" => $rows,
            "Msg" => "OK"
        );
        // $output=$rows;
    
    }else{
        $output = array(
            "Success" => FALSE,
            "Msg" => "You are not logged in. Please log in and try again.",
            "status" => "error");
    }
    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin:*');
    print(json_encode($output));
}

?>