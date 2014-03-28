<?php // tokit, 7/15/2004 8:49:32 PM
ini_set('display_errors',1);
/*************************
          POSTING
*************************/
$PID = "370";
$SC = $_SERVER["SCRIPT_NAME"];
require_once("lib/dbconn.php");
$reg = $_GET["rg"];
$SQL = "SELECT posting('".$reg."', '".$PID."', '".$_SESSION['uid']."', false)";
pg_query($SQL);
?>
<script language=javascript>
<!--
window.location = "index2.php?p=<?echo $PID;?>&rg=<?echo $reg;?>&sub=4";
-->
</script>
