<?php // Nugraha, 18/02/2004
	 //	 Apep, 06/11/2007

$PID = "800";

require_once("../lib/dbconn.php");

if (empty($_GET[sure])) {
	
        header("Location: ../index2.php?p=$PID&tt=" . $_GET["tt"] ."&tc=" . $_GET["tc"]."&sure=false");
	exit();

} elseif ($_GET[sure] == "YA") {

         $SQL = "delete from rs00001 where ".
       "tt = '".$_GET["tt"]."' and tc = '".$_GET["tc"]."'";

} else {

    header("Location: ../index2.php?p=$PID&tt=".$_GET["tt"]);
    exit();

}



pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID&tt=".$_GET["tt"]); 
exit;

?>
