<?php // Nugraha, Fri Apr 30 14:43:48 WIT 2004

$PID = "mst_lab";

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
        $SQL = "select id from c_pemeriksaan_lab where hierarchy = '".
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
$qb->TableName = "c_pemeriksaan_lab";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "nextval('c_pemeriksaan_lab_seq')");
$qb->addFieldValue("hierarchy", "'$code'");
$SQL = $qb->build();

$level = getLevel($_POST["parent"]);

for ($n = $level; $n > 0; $n--) {
    $href = "&L$n=" . substr($_POST["parent"],0,$n*3) . str_repeat("0", 15 - ($n * 3)) . $href;
}
$href = "../index2.php?p=$PID".$href;

pg_query($con, $SQL);
header("Location: $href");
exit;

?>