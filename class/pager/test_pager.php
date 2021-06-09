<?php
// FileName: test_pager.php
// 這是一段簡單的示例代碼，前邊省略了使用pear db類建立資料庫連接的代碼 
require "Pager.class.php"; 
//目前頁碼
if ( isset($_POST['page']) )
{
   $page = (int)$_POST['page'];
}
else
{
   $page = 1;
} 
//每頁資料數

if ( isset($_POST['PageSize']) )
{
   $PageSize = (int)$_POST['PageSize'];
}
else
{
   $PageSize = 10;
} 

//echo $page."<hr>";
$sql = "select * from stk_050_res_rep_m order by id"; 
$pager_option = array(
       "sql" => $sql,
       "PageSize" => $PageSize,
       "CurrentPageID" => $page
); 
/*
if ( isset($_GET['numItems']) )
{
   $pager_option['numItems'] = (int)$_GET['numItems'];
} 
*/
$pager = @new Pager($pager_option);
$pager->pp();

//$data = $pager->getPageData(); 
if ( $pager->isFirstPage )
{
   $turnover = "|<<第一頁&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<上一頁&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}
else
{
   $turnover = "<a href='#' onClick=\"javascript:document.pager.page.value=1;submit()\">|<<第一頁</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"javascript:document.pager.page.value=$pager->PreviousPageID;submit()\"><上一頁</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
} 
if ( $pager->isLastPage )
{
   $turnover .= "下一頁>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;最後一頁>>|";
}
else
{
   $turnover .= "<a href='#' onClick=\"javascript:document.pager.page.value=$pager->NextPageID;submit()\">下一頁></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"javascript:document.pager.page.value=$pager->numPages;submit()\">最後一頁>>|</a>";
}
//頁次: 8      共 8 頁          共 16 筆
$turnover.="\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n頁次: <font COLOR=\"Red\">$pager->CurrentPageID </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;共<font COLOR=\"Red\"> $pager->numPages </font>頁&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;共<font COLOR=\"Red\"> $pager->numItems </font>筆&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

//每頁X筆
$turnover.="每頁<select size=\"1\" onchange=\"javascript:document.pager.PageSize.value=this.value;submit()\" name=\"MPageSize\">\r\n";
$turnover.="<option value=\"$pager->PageSize\" selected>$pager->PageSize</option>\r\n";
for($i=1;$i<16;$i++)
{
	$turnover.="<option value=\"$i\">$i</option>\r\n";
	
	}
$turnover.="</select>筆&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n";
//到X頁
$turnover.="到第<INPUT class=texts maxLength=5 size=3 dir=\"rtl\" name=gopage value=\"$pager->CurrentPageID\" onFocus=\"javascript:document.pager.gopage.value='';\">頁&nbsp;&nbsp;\r\n<INPUT class=buttons type=button value=確定 onClick=\"javascript:document.pager.page.value=document.pager.gopage.value;document.pager.submit();\" onKeyPress=\"javascript:document.pager.page.value=document.pager.gopage.value;document.pager.submit();\">\r\n";
//導覽ToolBar
$turnover="\r\n<form name=\"pager\" method=\"post\" action=\"\">\r\n<input type=\"hidden\" name=\"PageSize\" value=\"$PageSize\">\r\n<input type=\"hidden\" name=\"page\" value=\"$page\">\r\n".$turnover;
$turnover.="</form>\r\n";
//echo "<hr>".$turnover."<hr>";
echo "<hr>".$pager->paging_bar1()."<hr>";

//表頭
echo <<< EOD
<table border="1">
<tr>
<td>a</td>
<td>b</td>
<td>c</td>
<td>d</td>
<td>e</td>
</tr>
EOD;
echo "<tr>";

//內容
$result=$pager->getDataLink();
$row=mysql_fetch_row($result);
while($row!=NULL){
	list($loc_code,$name,$isin,$market,$industrial)=$row;
	//echo $loc_code . "<br>";
	echo <<< EOD
<tr>
<td>$loc_code&nbsp;</td>
<td>$name&nbsp</td>
<td>$isin&nbsp</td>
<td>$market&nbsp</td>
<td>$industrial&nbsp</td>
</tr>
	
EOD;
	
	$row=mysql_fetch_row($result);
}

/*
echo $pager->$NextPageID."<hr>";
echo "temp=".$pager->$temp."<hr>";
*/
?>
</table>
 

