<?php // Nugraha, 14/02/2004

$PID = "index_pegawai";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$id = getFromTable("select count(id_dok) from index_pegawai where id_dok='".$_POST[f_id_dok]."'");
//echo $id;
$gaji = $_POST[f_gaji] ;
$index = number_format($gaji / 100000,0,0,0);
if ($id > 0) {
	$SQL = "update index_pegawai set gol='".$_POST[f_gol]."',gaji=".$_POST[f_gaji].",index_gaji='$index',
					pendidikan_id='".$_POST[f_pendidikan_id]."',risk_id='".$_POST[f_risk_id]."',emergency_id='".$_POST[f_emergency_id]."'
					,posisi_id='".$_POST[f_posisi_id]."' where id_dok = '".$_POST[f_id_dok]."'";
	
	pg_query($con, $SQL);

	header("Location: ../index2.php?p=$PID&mPEG=".$_POST["mPEG"]."&mJAB=".$_POST["mJAB"]);
}else{
	$SQL = "insert into index_pegawai (id_dok,gol,gaji,index_gaji,pendidikan_id,risk_id,emergency_id,posisi_id)
			values ('".$_POST[f_id_dok]."','".$_POST[f_gol]."',".$_POST[f_gaji].",'$index','".$_POST[f_pendidikan_id]."','".$_POST[f_risk_id]."',
			'".$_POST[f_emergency_id]."','".$_POST[f_posisi_id]."') ";
	
	pg_query($con, $SQL);

	header("Location: ../index2.php?p=$PID&mPEG=".$_POST["mPEG"]."&mJAB=".$_POST["mJAB"]);
}


exit;

?>
