<?php // Nugraha, Fri May  7 15:17:58 WIT 2004
      // sfdn, 22-05-2004

$PID = "835";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

function getLevel($hcode)
{
    // --> perubahan sfdn
    //if (strlen($hcode) != 9) return 0;
    //if (substr($hcode,  4,  6) == str_repeat("0", 6)) return 1;
    //if (substr($hcode,  7,  3) == str_repeat("0", 3)) return 2;
    //return 3;
    // --> akhir perubahan

    //perubahan sfdn
    if (strlen($hcode) != 15) return 0;
    if (substr($hcode,  4, 12) == str_repeat("0", 12)) return 1;
    if (substr($hcode,  7,  9) == str_repeat("0",  9)) return 2;
    if (substr($hcode, 10,  6) == str_repeat("0",  6)) return 3;
    if (substr($hcode, 13,  3) == str_repeat("0",  3)) return 4;
    return 5;
    // akhir perubahan

}

function getNextCode($hcode)
{

/*  ini aslinya --> sfdn
    $level = getLevel($hcode);
    $prefix = substr($hcode, 0, $level * 3);
    for ($n = 1; $n < 1000; $n++) {
        $SQL = "select id from rs00012 where hierarchy = '".
                substr($hcode,0,$level*3).
                str_pad($n,3,"0",STR_PAD_LEFT).
                str_repeat("0",(2-$level)*3).
                "'";
        $code = getFromTable($SQL);
        if (strlen($code) == 0)
            return
                substr($hcode,0,$level*3).
                str_pad($n,3,"0",STR_PAD_LEFT).
                str_repeat("0",(2-$level)*3);
    }
*/
    $level = getLevel($hcode);
    $prefix = substr($hcode, 0, $level * 3);
    for ($n = 1; $n < 1000; $n++) {
        $SQL = "select id from rs00012 where hierarchy = '".
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
$qb->TableName = "rs00012";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "nextval('rs00012_seq')");
$qb->addFieldValue("hierarchy", "'$code'");
$SQL = $qb->build();

$level = getLevel($_POST["parent"]);

for ($n = $level; $n > 0; $n--) {
    // perubahan sfdn: str_repeat("0", 15 - ($n * 3))
    // aslinya angka 15 adalah 9
    $href = "&L$n=" . substr($_POST["parent"],0,$n*3) . str_repeat("0", 15 - ($n * 3)) . $href;
    // akhir perubahan
}
$href = "../index2.php?p=$PID".$href;

pg_query($con, $SQL);

/* ini aslinya --> sfdn

$bangsal = substr($code, 0, 3) . "000000";
$anaknya = substr($code, 0, 3);
$klas    = getFromTable("select klasifikasi_tarif_id from rs00012 where hierarchy = '$bangsal'");
$harga   = getFromTable("select harga from rs00012 where hierarchy = '$bangsal'");

pg_query($con,
    "update rs00012 set harga = '$harga', klasifikasi_tarif_id = '$klas' ".
    "where substr(hierarchy, 1, 3) = '$anaknya' ".
    "and hierarchy != '$bangsal'");
*/

header("Location: $href");
exit;

?>
