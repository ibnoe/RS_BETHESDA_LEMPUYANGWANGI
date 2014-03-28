<?php
session_start();
$ROWS_PER_PAGE     = 14;

$PID = "110";
$SC = "../index2.php";

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");
require_once("../lib/setting.php");
?>

<HTML>
<HEAD>
</HEAD>
<body>

<?
$reg = $_GET["rg"];?>
<script language="javascript">
	function cetakrm(){
		sWin=window.open('cetak.120_rm.php?rg=<? echo $reg?>', '_blank','top=0,left=0,width=500,height=300,menubar=no,scrollbars=yes');
		sWin.focus();
	}
	function cetakkartu(){
		sWin =window.open('cetak.120_kartu.php?rg=<? echo $reg?>', '_blank','top=0,left=0,width=500,height=300,menubar=no,scrollbars=yes');
		sWin.focus();
	}
</script>

<br>
<br>

<input type='button' value='cetak kartu' onclick="cetakrm()" style="border-style:none;"/>
<input type='button' value='cetak status' onclick="cetakkartu()" style="border-style:none;"/>

</body>
</html>

<?php
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<DIV><A HREF='$SC?p=$PID&no_mr=$reg'><img src='../images/icon-back.png'/></a></DIV>";
echo "<br>";
echo "<br>";
?>
