<?php 

//Wildan, ST. 17 Feb 2014

$PID = "akun_master";

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
$qb->TableName = "akun_master";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addPrimaryKey("id", "'" . $_POST["id"] . "'");
$SQL = $qb->build();

$level = getLevel($_POST["parent"]);

for ($n = $level; $n > 0; $n--) {
    $href = "&L$n=" . substr($_POST["parent"],0,$n*3) . str_repeat("0", 15 - ($n * 3)) . $href;
}
$href = "../index2.php?p=$PID".$href."&sort=".$_POST[sort]."&order=".$_POST[order]."&tblstart=".$_POST[tblstart];

//echo $SQL;
pg_query($con, $SQL);
//$sql1="update akun_master set kode=(substring('".$_POST["hierarchy"]."',3,1)||''||substring('".$_POST["hierarchy"]."',6,1)||''||substring('".$_POST["hierarchy"]."',8,2)||'.'||substring('".$_POST["hierarchy"]."',11,2)) where hierarchy='".$_POST["hierarchy"]."'";
//pg_query($con, $sql1);
header("Location: $href");
exit;

?>
