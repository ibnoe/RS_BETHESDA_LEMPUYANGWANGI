<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004


$PID = "805";

require_once("../lib/dbconn.php");

if (empty($_GET[sure])) {
	
        header("Location: ../index2.php?p=$PID&mJENJANG=".$_GET["j"].
            "&e=" . $_GET["e"] ."&sure=false");
	exit();

} elseif ($_GET[sure] == "YA") {

         $SQL = "delete from rs00027 where ".
              "id = '".$_GET["e"]."' ";

} else {

    header("Location: ../index2.php?p=$PID&mJENJANG=".$_GET["j"]);
    exit();

}



pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID&mJENJANG=".$_GET["j"]."&sort=".$_GET[sort].
	"&order=".$_GET[order]."&tblstart=".$_GET[tblstart]);
exit;

?>
