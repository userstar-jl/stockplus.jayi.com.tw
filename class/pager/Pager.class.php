<?php
include("../config.php");

// FileName: Pager.class.php
// 分頁類，這個類僅僅用於處理資料結構，不負責處理顯示的工作
Class Pager
{
   var $PageSize;             //每頁的數量
   var $CurrentPageID;        //當前的頁數
   var $NextPageID;           //下一頁
   var $PreviousPageID;       //上一頁
   var $numPages;             //總頁數
   var $numItems;             //總記錄數
   var $isFirstPage;          //是否第一頁
   var $isLastPage;           //是否最後一頁
   var $sql;                  //sql查詢語句
   
  function Pager($option)
   {
       //global $db;
       $this->_setOptions($option);
       // 總條數
       if ( !isset($this->numItems) )
       {

           $result=mysql_query($this->sql);
           $this->numItems =mysql_num_rows($result);
       }
       // 總頁數 numPages
       if ( $this->numItems > 0 )
       {
           if ( $this->numItems < $this->PageSize ){ $this->numPages = 1; }
           if ( $this->numItems % $this->PageSize )
           {
               $this->numPages= (int)($this->numItems / $this->PageSize) + 1;
           }
           else
           {
               $this->numPages = $this->numItems / $this->PageSize;
           }
       }
       else
       {
           $this->numPages = 0;
       }
       
       //判斷目前所在頁
       switch ( $this->CurrentPageID )
       {
           case $this->numPages == 1:
               $this->isFirstPage = true;
               $this->isLastPage = true;
               break;
           case 1://第一頁
               $this->isFirstPage = true;
               $this->isLastPage = false;
               break;
           case $this->numPages://最後一頁
               $this->isFirstPage = false;
               $this->isLastPage = true;
               break;
           default:
               $this->isFirstPage = false;
               $this->isLastPage = false;
       }
       //上一頁、下一頁指標
       if ( $this->numPages > 1 )
       {
           if ( !$this->isLastPage ) { $this->NextPageID = $this->CurrentPageID + 1; }
           if ( !$this->isFirstPage ) { $this->PreviousPageID = $this->CurrentPageID - 1; }
       }
       return true;
   }
   
   function getDataLink()
   {
       if ( $this->numItems )
       {
           global $db;
           
           $PageID = $this->CurrentPageID;
           
           $from = ($PageID - 1)*$this->PageSize;
           $count = $this->PageSize;
           
            
           $result=mysql_query($this->sql." limit ".$from.",".$count);
           return $result;
       }
       else
       {
           return false;
       }
   }
   
  function pp()
  {
  	echo "PageSize=".$this->PageSize."<hr>";
  	echo "CurrentPageID=".$this->CurrentPageID."<hr>";
  	echo "NextPageID=".$this->NextPageID."<hr>";
  	echo "PreviousPageID=".$this->PreviousPageID."<hr>";
  	echo "numPages=".$this->numPages."<hr>";
  	echo "numItems=".$this->numItems."<hr>";
  	echo "isFirstPage=".$this->isFirstPage."<hr>";
  	echo "isLastPage=".$this->isLastPage."<hr>";
  	echo "sql=".$this->sql."<hr>";
  }
  //paging_bar1
  function paging_bar1()
  {
  
  	//檢查頁碼是否為數字的javascript
  	$turnover="<script language=javascript>\r\nfunction isFloat(Str)\r\n{\r\nvar reFloat = /^((\d+(\d+)?)|((\d+)?\d+))$/;\r\nreturn reFloat.test(Str);\r\n}\r\n";
  	$turnover .="";
  	$turnover .="function check(obj)\r\n{\r\nif (!isFloat(obj.value))\r\n{\r\nalert('請使用整數的格式！');\r\nobj.focus();\r\nobj.select();\r\n}\r\n}\r\n";
  	$turnover .="</script>";
  	
  	if ( $this->isFirstPage )
	{
	   $turnover .= "|<<第一頁&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<上一頁&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	else
	{
	   $turnover = "<a href='#' onClick=\"javascript:document.pager.page.value=1;document.pager.submit()\">|<<第一頁</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"javascript:document.pager.page.value='$this->PreviousPageID';document.pager.submit()\"><上一頁</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	} 
	if ( $this->isLastPage )
	{
	   $turnover .= "下一頁>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;最後一頁>>|";
	}
	else
	{
	   $turnover .= "<a href='#' onClick=\"javascript:document.pager.page.value='$this->NextPageID';document.pager.submit()\">下一頁></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"javascript:document.pager.page.value='$this->numPages';document.pager.submit()\">最後一頁>>|</a>";
	}
	
	//頁次: 8      共 8 頁          共 16 筆
	$turnover.="\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n頁次: <font COLOR=\"Red\">$this->CurrentPageID </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;共<font COLOR=\"Red\"> $this->numPages </font>頁&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;共<font COLOR=\"Red\"> $this->numItems </font>筆&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	
	//每頁X筆
	$turnover.="每頁<select size=\"1\" onchange=\"javascript:document.pager.page.value='1';document.pager.PageSize.value=this.value;document.pager.submit()\" name=\"MPageSize\">\r\n";
	$turnover.="<option value=\"$this->PageSize\" selected>$this->PageSize</option>\r\n";
	for($i=1;$i<16;$i++)
	{
		$turnover.="<option value=\"$i\">$i</option>\r\n";
		
		}
	$turnover.="</select>筆&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n";
	//到X頁
	$turnover.="到第<INPUT class=texts maxLength=5 size=3 dir=\"rtl\" name=gopage value=\"$this->CurrentPageID\" onFocus=\"javascript:document.pager.gopage.value='';\" onBlur=\"check(this);\" >頁&nbsp;&nbsp;\r\n<INPUT class=buttons type=button value=確定 onClick=\"javascript:document.pager.page.value=document.pager.gopage.value;document.pager.submit();\" onKeyPress=\"javascript:document.pager.page.value=document.pager.gopage.value;document.pager.submit();\">\r\n";
	//導覽ToolBar 用<form>將整個toolbar包起來
	$turnover="\r\n<form name=\"pager\" method=\"post\" action=\"\">\r\n<input type=\"hidden\" name=\"PageSize\" value=\"$this->PageSize\">\r\n<input type=\"hidden\" name=\"page\" value=\"$this->CurrentPageID\">\r\n".$turnover;
	$turnover.="</form>\r\n";
	//設定字型大小:用<span>將整個toolbar包起來
	$turnover="<span style=\"font-size:13px \">".$turnover."</span>";
	return $turnover;
  }
  
  //paging_bar2
  function paging_bar2()
  {
  
  	//檢查頁碼是否為數字的javascript
  	$turnover="<script language=javascript>\r\nfunction isFloat(Str)\r\n{\r\nvar reFloat = /^((\d+(\d+)?)|((\d+)?\d+))$/;\r\nreturn reFloat.test(Str);\r\n}\r\n";
  	$turnover .="";
  	$turnover .="function check(obj)\r\n{\r\nif (!isFloat(obj.value))\r\n{\r\nalert('請使用整數的格式！');\r\nobj.focus();\r\nobj.select();\r\n}\r\n}\r\n";
  	$turnover .="</script>";
  	
  	if ( $this->isFirstPage )
	{
	   $turnover .= "|<<第一頁&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<上一頁&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	else
	{
	   $turnover = "<a href='#' onClick=\"javascript:document.pager.page.value=1;document.pager.submit()\">|<<第一頁</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"javascript:document.pager.page.value='$this->PreviousPageID';document.pager.submit()\"><上一頁</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	} 
	if ( $this->isLastPage )
	{
	   $turnover .= "下一頁>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;最後一頁>>|";
	}
	else
	{
	   $turnover .= "<a href='#' onClick=\"javascript:document.pager.page.value='$this->NextPageID';document.pager.submit()\">下一頁></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"javascript:document.pager.page.value='$this->numPages';document.pager.submit()\">最後一頁>>|</a>";
	}
	
	//頁次: 8      共 8 頁          共 16 筆
	$turnover.="\r\n<br>\r\n頁次: <font COLOR=\"Red\">$this->CurrentPageID </font>&nbsp;&nbsp;共<font COLOR=\"Red\"> $this->numPages </font>頁&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;共<font COLOR=\"Red\"> $this->numItems </font>筆&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	
	//每頁X筆
	$turnover.="每頁<select size=\"1\" onchange=\"javascript:document.pager.page.value='1';document.pager.PageSize.value=this.value;document.pager.submit()\" name=\"MPageSize\">\r\n";
	$turnover.="<option value=\"$this->PageSize\" selected>$this->PageSize</option>\r\n";
	for($i=1;$i<16;$i++)
	{
		$turnover.="<option value=\"$i\">$i</option>\r\n";
		
		}
	$turnover.="</select>筆&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n";
	//到X頁
	$turnover.="到第<INPUT class=texts maxLength=5 size=3 dir=\"rtl\" name=gopage value=\"$this->CurrentPageID\" onFocus=\"javascript:document.pager.gopage.value='';\" onBlur=\"check(this);\" >頁&nbsp;&nbsp;\r\n<INPUT class=buttons type=button value=確定 onClick=\"javascript:document.pager.page.value=document.pager.gopage.value;document.pager.submit();\" onKeyPress=\"javascript:document.pager.page.value=document.pager.gopage.value;document.pager.submit();\">\r\n";
	//導覽ToolBar 用<form>將整個toolbar包起來
	$turnover="\r\n<form name=\"pager\" method=\"post\" action=\"\">\r\n<input type=\"hidden\" name=\"PageSize\" value=\"$this->PageSize\">\r\n<input type=\"hidden\" name=\"page\" value=\"$this->CurrentPageID\">\r\n".$turnover;
	$turnover.="</form>\r\n";
	//設定字型大小:用<span>將整個toolbar包起來
	$turnover="<span style=\"font-size:13px \">".$turnover."</span>";
	return $turnover;
  }
   
   function _setOptions($option)
   {
       $allow_options = array(
                   'PageSize',
                   'CurrentPageID',
                   'sql',
                   'numItems'
       );
       
       foreach ( $option as $key => $value )
       {
           if ( in_array($key, $allow_options) && ($value != null) )
           {
               $this->$key = $value;
               //echo "key:".$key."--value:".$value."<hr>";
           }
       }
       
       return true;
   }
}
?>
