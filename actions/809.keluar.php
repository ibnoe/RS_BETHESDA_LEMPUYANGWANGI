<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004
$PID = "809";
require_once("../lib/dbconn.php");
if (empty($_GET[sure])) {
        header("Location: ../index2.php?p=$PID&mPEG=". $_GET["mPEG"].
            "&mJAB=" . $_GET["mJAB"] .
            "&e=" . $_GET["e"] ."&sure=false&z=tiga");
	exit();

} elseif ($_GET[sure] == "::YA::") {
         $SQL = "update rs00017 set tgl_keluar = '".date("Y-m-d", time())."' where id = '".$_GET["e"]."' ";
         $SQL1 = "update rs00017 set status = 'klr' where id = '".$_GET["e"]."' ";
} else {
    header("Location: ../index2.php?p=$PID&mPEG=".$_GET[mPEG]."&mJAB=".$_GET[mJAB]);
    exit();
}
pg_query($con, $SQL);
pg_query($con, $SQL1);
header("Location: ../index2.php?p=$PID&mPEG=".$_GET[mPEG]."&mJAB=".$_GET[mJAB]."&sort=".$_GET[sort].
	"&order=".$_GET[order]."&tblstart=".$_GET[tblstart]);
exit;

?>
