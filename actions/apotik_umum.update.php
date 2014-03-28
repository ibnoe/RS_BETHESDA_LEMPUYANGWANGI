<?php 
$PID = "apotik_umum";

require_once("../lib/dbconn.php");
//require_once("../lib/form.php");
//require_once("../lib/functions.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");
//$tanggal = $_POST["f_tanggal"];
//$jam = $_POST["f_jam"];
//$SQL = "update into apotik_umum (code, mr, nama, tanggal, jam, umur, sex) ".
//       "values ('".$_POST["f_code"]."', '".$_POST["f_mr"]."', '".$_POST["f_nama"]."', ".
//       "'$tanggal'::date, '$jam'::time, '".$_POST["f_umur"]."', '".$_POST["f_sex"]."')";
pg_query("update apotik_umum set mr='".$_POST["f_mr"]."', nama='".$_POST["f_nama"]."', umur='".$_POST["f_umur"]."', sex='".$_POST["f_sex"]."' ".
	 	 "where code='".$_POST[f_code]."'") or die("error atuh");
//pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID&list=resepobat&tt=".$_POST[tt]."&rg={$_POST["f_code"]}");
exit;
?>


