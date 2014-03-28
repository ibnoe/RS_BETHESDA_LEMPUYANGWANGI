
<script language='JavaScript'>
	function selectLaporan() {
	   sWin = window.open('popup/laporan_ri.php', 'xWin', 'top=0,left=0,width=500,height=650,menubar=no,scrollbars=yes')
	   sWin.focus()
	}
</script>
<?
//heri 23 juli 2007

$PID = "rm_inap";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();

if (!empty($_SESSION[uid])) {

require_once("startup.php");
require_once("lib/visit_setting.php");


//title_print("Laporan Rawat Inap");
title_excel("rm_inap");
 // pake session popup==========================
/*	
	
	$SQL = "select tc,tdesc from rs00001 where tt='LRI' and tc='" . $_SESSION["SELECT_LAP"] . "'";
	$r=pg_query($con,$SQL);
	$d2 = pg_fetch_array($r);
	pg_free_result($r);
	//unset($_SESSION["SELECT_LAP"]);
		echo $sub = $d2["tc"];
		$F = new Form($SC,"POST","name='frmTst'");
		$F->hidden("p",$PID);
		$F->rotext("<font size='3'>Laporan Rawat Inap</font>","<font size='3'><b>{$d2["tdesc"]}</b></font> <nobr><a href='javascript:selectLaporan()'><input type='button' name='pilih' value='Pilih'>");
		//$F->selectSQL2("sub","Pilih Laporan","select tc,tdesc from rs00001 where tt='LRI'",$_GET["sub"],"onChange='document.frmTst.submit();';");
		$F->execute();


$_GET["sub"] = isset($_SESSION["SELECT_LAP"]) == $d2["tc"] ? $d2["tc"] : "";

if (isset($_GET["sub"])) {
    $_SESSION["SELECT"] = $d2["tc"];
    //unset($_SESSION["SELECT_LAP"]);
}
	$_GET["sub"] = isset($_SESSION["SELECT"]) == $d2["tc"] ? $d2["tc"] : "";
	if (file_exists("includes/$PID." . $_GET["sub"] . ".php")) {
		// echo "<DIV CLASS=BOX>";
		unset($_SESSION["SELECT_LAP"]);
		include_once("includes/$PID." . $_GET["sub"] . ".php");
		// echo "</DIV>";
		//unset($_SESSION["SELECT_LAP"]);
	}
	//==============================================
*/

if (!$GLOBALS['print']){	
	echo "<table border='0' width='100%' cellspacing=2 cellpadding=2><tr><td width='90%'>";
	$f = new Form($SC, "GET", "name=frmConfig");
	$f->PgConn = $con;
	$f->hidden("p", $PID);

	//$f->selectArray2("sub", "Laporan Rawat Inap", $sub, $_GET["sub"],
	//  						"onChange='document.frmConfig.submit();'; ");
	$f->selectSQL2("sub","<font size='3'><b>LAPORAN RAWAT INAP</b></font>","Select ''as tc,'---  Pilih Laporan  ---'as tdesc union select tc,tdesc from rs00001 where tt='LRI' and tc in('E05','H02') order by tc asc",
								$_GET["sub"],"onChange='document.frmConfig.submit();'; ");    

	$f->execute();
	echo "</td><td>";
	echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
	echo "</td></tr></table>";	
}
		$r=pg_query($con,"select tc,tdesc from rs00001 where tt='LRI' and tc not in('000') and tc='{$_GET["sub"]}' ");
		$d2 = pg_fetch_array($r);
		
		$_GET["sub"] = strlen($_GET["sub"]) == 0 ? 0 : $_GET["sub"];
		if (file_exists("includes/$PID." . $_GET["sub"] . ".php")) {
		  echo "<DIV CLASS=BOX>";
		  //	if (!$GLOBALS['print']){
		//		title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > {$d2['tdesc']}");
				//echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
		//	}else{
				title_print("<img src='icon/medical-record.gif' align='absmiddle' > {$d2['tdesc']}");
		//	}
		    include_once("includes/$PID." . $_GET["sub"] . ".php");
		    
		  echo "</DIV>";
		}
//============================================================
/*
//pake combo group layanan
		    $ext = "OnChange = 'Form1.submit();'";
			title_print("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  LAYANAN RAWAT INAP");
			$level = 0;
			$f = new Form($SC, "GET", "NAME=Form1");
			$f->PgConn = $con;
			$f->hidden("p", $PID);
			//$f->hidden("ri",$_GET["ri"]);
				
			$f->selectSQL("ri", "Rawat Inap",
							"select '' as tc,'' as tdesc union select tc, tdesc from rs00001 where tt='GRI' and tc not in ('000') ".
							"order by tc", $_GET["ri"],	$ext);
							
			if (strlen($_GET["ri"]) > 0) $level = 1;
	
			$SQL2 =  "select '' as tt,'' as tdesc union select a.tc, a.tdesc ".
					"from rs00001 a ".
					"left join rs00001 b on substr(a.tc,1,1) = substr(b.tc,1,1) and b.tt='GRI' and b.tc not in ('000') ".
					"where a.tt='LRI' and a.tc not in ('000') and substr(a.tc,1,1) = substr('{$_GET["ri"]}',1,1) ";
			$r = pg_query($con,$SQL2);
			$n = pg_num_rows($r);
			if($n > 2){
				//$f->hidden("s", $_GET["s"]);
				$f->selectSQL("sub", "",$SQL2 , $_GET["sub"], $ext);
				if (strlen($_GET["sub"]) > 0) $level = 2;
			}
			$f->execute();
			echo "level=".$level;
	//$_GET["ri"] = strlen($_GET["ri"]) == 0 ? 0 : $_GET["ri"];
	//$_GET["sub"] = isset($_GET["ri"])? $_GET["ri"] : $_GET["sub"];
	if (file_exists("includes/$PID." . $level . ".php")) {
	   // echo "<DIV CLASS=BOX>";
	    include_once("includes/$PID." . $level . ".php");
	   // echo "</DIV>";
	}
*/
}

?>