<?php
// FileName: test_pager.php
// �o�O�@�q²�檺�ܨҥN�X�A�e��ٲ��F�ϥ�pear db���إ߸�Ʈw�s�����N�X 
require "Pager.class.php"; 
//�ثe���X
if ( isset($_POST['page']) )
{
   $page = (int)$_POST['page'];
}
else
{
   $page = 1;
} 
//�C����Ƽ�

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
   $turnover = "|<<�Ĥ@��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<�W�@��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}
else
{
   $turnover = "<a href='#' onClick=\"javascript:document.pager.page.value=1;submit()\">|<<�Ĥ@��</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"javascript:document.pager.page.value=$pager->PreviousPageID;submit()\"><�W�@��</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
} 
if ( $pager->isLastPage )
{
   $turnover .= "�U�@��>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�̫�@��>>|";
}
else
{
   $turnover .= "<a href='#' onClick=\"javascript:document.pager.page.value=$pager->NextPageID;submit()\">�U�@��></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"javascript:document.pager.page.value=$pager->numPages;submit()\">�̫�@��>>|</a>";
}
//����: 8      �@ 8 ��          �@ 16 ��
$turnover.="\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n����: <font COLOR=\"Red\">$pager->CurrentPageID </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�@<font COLOR=\"Red\"> $pager->numPages </font>��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�@<font COLOR=\"Red\"> $pager->numItems </font>��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

//�C��X��
$turnover.="�C��<select size=\"1\" onchange=\"javascript:document.pager.PageSize.value=this.value;submit()\" name=\"MPageSize\">\r\n";
$turnover.="<option value=\"$pager->PageSize\" selected>$pager->PageSize</option>\r\n";
for($i=1;$i<16;$i++)
{
	$turnover.="<option value=\"$i\">$i</option>\r\n";
	
	}
$turnover.="</select>��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n";
//��X��
$turnover.="���<INPUT class=texts maxLength=5 size=3 dir=\"rtl\" name=gopage value=\"$pager->CurrentPageID\" onFocus=\"javascript:document.pager.gopage.value='';\">��&nbsp;&nbsp;\r\n<INPUT class=buttons type=button value=�T�w onClick=\"javascript:document.pager.page.value=document.pager.gopage.value;document.pager.submit();\" onKeyPress=\"javascript:document.pager.page.value=document.pager.gopage.value;document.pager.submit();\">\r\n";
//����ToolBar
$turnover="\r\n<form name=\"pager\" method=\"post\" action=\"\">\r\n<input type=\"hidden\" name=\"PageSize\" value=\"$PageSize\">\r\n<input type=\"hidden\" name=\"page\" value=\"$page\">\r\n".$turnover;
$turnover.="</form>\r\n";
//echo "<hr>".$turnover."<hr>";
echo "<hr>".$pager->paging_bar1()."<hr>";

//���Y
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

//���e
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
 

