<?php // Nugraha, Fri Apr 30 15:47:37 WIT 2004

$PID = "834";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

function getLevel($hcode)
{
    if (strlen($hcode) != 15) return 0;
    if (substr($hcode,  4, 12) == str_repeat("0", 12)) return 1;
    if (substr($hcode,  7,  9) == str_repeat("0",  9)) return 2;
    if (substr($hcode, 10,  6) == str_repeat("0",  6)) return 3;
    if (substr($hcode, 13,  3) == str_repeat("0",  3)) return 4;
    return 5;
}

$qb = New UpdateQuery();
$qb->TableName = "rs00034";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addPrimaryKey("id", "'" . $_POST["id"] . "'");
$SQL = $qb->build();

$level = getLevel($_POST["parent"]);

for ($n = $level; $n > 0; $n--) {
    $href = "&L$n=" . substr($_POST["parent"],0,$n*3) . str_repeat("0", 15 - ($n * 3)) . $href;
}
$href = "../index2.php?p=$PID".$href."&sort=".$_POST[sort]."&order=".$_POST[order]."&tblstart=".$_POST[tblstart];

/**
$SQL_jasmed = null;
foreach($_POST['jasa_rs'] AS $key => $val){
	if(getFromTable("SELECT count(id) FROM jasmed WHERE tipe_pasien = '$key' AND layanan='".$_POST['hierarchy_update']."'")>0){
	$SQL_jasmed = "UPDATE jasmed SET jasa_dokter = ".$_POST['jasa_dokter'][$key].", jasa_asisten = ".$_POST['jasa_asisten'][$key]."
					, jasa_rs = ".$_POST['jasa_rs'][$key].", jasa_alat = ".$_POST['jasa_alat'][$key].", jasa_anestesi = ".$_POST['jasa_anestesi'][$key].",
					jasa_operator = ".$_POST['jasa_operator'][$key].", jasa_dokter_persen = ".$_POST['jasa_dokter_persen'][$key].", 
					jasa_asisten_persen = ".$_POST['jasa_asisten_persen'][$key].", jasa_rs_persen = ".$_POST['jasa_rs_persen'][$key].", 
					jasa_alat_persen = ".$_POST['jasa_alat_persen'][$key].", jasa_anestesi_persen = ".$_POST['jasa_anestesi_persen'][$key].",
					jasa_operator_persen = ".$_POST['jasa_operator_persen'][$key]."
					WHERE tipe_pasien = '$key' AND layanan='".$_POST['hierarchy_update']."'";						   
	}
	else{
	$SQL_jasmed = "INSERT INTO jasmed(tipe_pasien, layanan, jasa_dokter, jasa_asisten, jasa_rs, jasa_alat, jasa_anestesi, jasa_operator,
		jasa_dokter_persen, jasa_asisten_persen, jasa_rs_persen, jasa_alat_persen, jasa_anestesi_persen, jasa_operator_persen) 
							   VALUES('$key', '".$_POST['hierarchy_update']."', ".$_POST['jasa_dokter'][$key].", ".$_POST['jasa_asisten'][$key]."
							   , ".$_POST['jasa_rs'][$key].", ".$_POST['jasa_alat'][$key].", ".$_POST['jasa_anestesi'][$key].", 
							   ".$_POST['jasa_operator'][$key].", ".$_POST['jasa_dokter_persen'][$key].", ".$_POST['jasa_asisten_persen'][$key]."
							   , ".$_POST['jasa_rs_persen'][$key].", ".$_POST['jasa_alat_persen'][$key].", ".$_POST['jasa_anestesi_persen'][$key].", 
							   ".$_POST['jasa_operator_persen'][$key].")";
		}
	pg_query($con, $SQL_jasmed);
	}**/
pg_query($con, $SQL);
header("Location: $href");
exit;

?>
