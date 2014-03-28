<?php // Nugraha, Fri Apr 30 14:43:48 WIT 2004

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

function getNextCode($hcode)
{
    $level = getLevel($hcode);
    $prefix = substr($hcode, 0, $level * 3);
    for ($n = 1; $n < 1000; $n++) {
        $SQL = "select id from rs00034 where hierarchy = '".
                substr($hcode,0,$level*3).
                str_pad($n,3,"0",STR_PAD_LEFT).
                str_repeat("0",(4-$level)*3).
                "'";
        $code = getFromTable($SQL);
        if (strlen($code) == 0)
            return
                substr($hcode,0,$level*3).
                str_pad($n,3,"0",STR_PAD_LEFT).
                str_repeat("0",(4-$level)*3);
    }
}

$code = getNextCode($_POST["parent"]);

$qb = New InsertQuery();
$qb->TableName = "rs00034";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "nextval('rs00034_seq')");
$qb->addFieldValue("hierarchy", "'$code'");
$SQL = $qb->build();

$level = getLevel($_POST["parent"]);

for ($n = $level; $n > 0; $n--) {
    $href = "&L$n=" . substr($_POST["parent"],0,$n*3) . str_repeat("0", 15 - ($n * 3)) . $href;
}
$href = "../index2.php?p=$PID".$href;
pg_query($con, $SQL);
/**
$SQL_jasmed = null;
if(!empty($_POST['f_layanan'])){
foreach($_POST['jasa_rs'] AS $key => $val){
	$SQL_jasmed = "INSERT INTO jasmed(tipe_pasien, layanan, jasa_dokter, jasa_asisten, jasa_rs, jasa_alat, jasa_anestesi, jasa_operator,
		jasa_dokter_persen, jasa_asisten_persen, jasa_rs_persen, jasa_alat_persen, jasa_anestesi_persen, jasa_operator_persen) 
							   VALUES('$key', '$code', ".$_POST['jasa_dokter'][$key].", ".$_POST['jasa_asisten'][$key]."
							   , ".$_POST['jasa_rs'][$key].", ".$_POST['jasa_alat'][$key].", ".$_POST['jasa_anestesi'][$key].", 
							   ".$_POST['jasa_operator'][$key].", ".$_POST['jasa_dokter_persen'][$key].", ".$_POST['jasa_asisten_persen'][$key]."
							   , ".$_POST['jasa_rs_persen'][$key].", ".$_POST['jasa_alat_persen'][$key].", ".$_POST['jasa_anestesi_persen'][$key].", 
							   ".$_POST['jasa_operator_persen'][$key].")";
	pg_query($con, $SQL_jasmed);
	}
}*/
header("Location: $href");
exit;

?>