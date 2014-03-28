<?php 
// Wildan ST. 18 Feb 2014

$PID = "akun_m_periode";

require_once("../lib/dbconn.php");

if (empty($_GET[sure])) {
	
        header("Location: ../index2.php?p=$PID&e=" . $_GET["e"] ."&sure=false");
	exit();

} elseif ($_GET[sure] == "YA") {

         $SQL = "delete from triwulan where kode = '".$_GET["e"]."'";

} else {

    header("Location: ../index2.php?p=$PID");
    exit();

}



pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID"); 
exit;

?>
