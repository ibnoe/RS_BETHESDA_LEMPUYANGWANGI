<?php // Nugraha, Fri May  7 15:12:13 WIT 2004
      // sfdn, 29-05-2004
	 // Apep, 06 November 2007 0:56 WIB
$PID = "835";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
//echo"id=".$_GET["e"]."<br>";
//echo"parent=".$_GET["parent"];exit;
function getLevel($hcode)
{
	//========ini aslinya===========
    if (strlen($hcode) != 9) return 0;
    if (substr($hcode,  4,  6) == str_repeat("0", 6)) return 1;
    if (substr($hcode,  7,  3) == str_repeat("0", 3)) return 2;
    return 3;
    
    //perubahan awal
    //if (strlen($hcode) != 15) return 0;
    //if (substr($hcode,  4, 12) == str_repeat("0", 12)) return 1;
    //if (substr($hcode,  7,  9) == str_repeat("0",  9)) return 2;
    //if (substr($hcode, 10,  6) == str_repeat("0",  6)) return 3;
    //if (substr($hcode, 13,  3) == str_repeat("0",  3)) return 4;
    //return 5;
    // akhir perubahan
}

$SQL = "delete from rs00012 where ".
              "id = '".$_GET["e"]."' ";

$level = getLevel($_POST["parent"]);

for ($n = $level; $n > 0; $n--) {
	//==========ini aslinya===============
    $href = "&L$n=" . substr($_POST["parent"],0,$n*3) . str_repeat("0", 9 - ($n * 3)) . $href;
    //perubahan awal
    //$href = "&L$n=" . substr($_POST["parent"],0,$n*3) . str_repeat("0", 15 - ($n * 3)) . $href;
    //perubahan akhir
}
$href = "../index2.php?p=$PID".$href;

pg_query($con, $SQL);

/*

$code    = getFromTable("select hierarchy from rs00012 where id = '".$_POST["id"]."'");
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
