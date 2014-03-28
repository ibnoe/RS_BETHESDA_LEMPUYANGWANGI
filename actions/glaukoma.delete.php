<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004


$PID = "input_glaukoma";

require_once("../lib/dbconn.php");

if (empty($_GET[sure])) {
	
        header("Location: ../index2.php?p=$PID&e=" . $_GET["e"] ."&sure=false");
	exit();

} elseif ($_GET[sure] == "YA") {

         $SQL = "delete from rl100020b where ".
              "id = '".$_GET["e"]."' ";

} else {

    header("Location: ../index2.php?p=$PID");
    exit();

}



pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID&sort=".$_GET[sort].
	"&order=".$_GET[order]."&tblstart=".$_GET[tblstart]);
exit;

?>
