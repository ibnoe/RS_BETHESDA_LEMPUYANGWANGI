<?php
session_start();
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
  

$SC		= $_GET["SC"];
$noReg		= $_GET["rg"];
$mr		= $_GET["mr"];
$poli		= 203;
if($_GET["edit"]){
	pg_query($con, "UPDATE c_catatan set hasil ='".$_GET["hasil1"]."',keterangan='".$_GET["ket1"]."' where id='".$_GET["id_lab"]."' and item_id='".$_GET["id1"]."'");
} else{
	for($i=1;$i<=$_GET["no"];$i++){
		$pemId		= $_GET["id".$i];
		$Hasil		= $_GET["hasil".$i];
		$ket	= $_GET["ket".$i];
		$Is_inap	= 'Y';
		$sqlIs = pg_query($con, "SELECT rawat_inap FROM rs00006 WHERE id ='$noReg'");
		$rowIs = pg_fetch_array($sqlIs);
		$Is    = $rowIs['rawat_inap'];
		pg_query($con, "INSERT INTO c_catatan (id,no_reg,id_poli,tanggal_entry,waktu_entry,item_id,is_inap,hasil,keterangan)
		values(nextval('c_catatan_seq'),'".$noReg."','".$poli."',CURRENT_DATE,CURRENT_TIME, '".$pemId."','".$Is."','".$Hasil."','".$ket."')" );
		/*echo "INSERT INTO c_catatan (id,no_reg,id_poli,tanggal_entry,waktu_entry,item_id,is_inap,hasil,keterangan)                     values(nextval('c_catatan_seq'),'".$noReg."','".$poli."',CURRENT_DATE,CURRENT_TIME, '".$pemId."','".$Is."','".$Hasil."','".$Keterangan."')";
		echo "\n";*/
	}
}
$_SESSION["SELECT_LAB"]='';
header("Location: $SC?p=p_laboratorium&list=pemeriksaan&rg=" . $_GET["rg"]."&poli=203&mr=" . $_GET["mr"]);
exit;

?>
