<?php // Yudha, Wed Jun  2 16:19:25 WIT 2004

session_start();

$PID = "882";

require_once("../lib/dbconn.php");
 



$level = $_POST["f_level"] ;
$back_to = "&L1=".$_POST["f_gr_id"] ;
Switch ($level) {
	case 0 : 
 
		if (strlen($_POST["f_gr_id"]) > 0 ) {
		    $SQL = "delete from rs_grup_user " .
		           "where gr_id = '".$_POST["f_gr_id"]."'";
		   
		     pg_query($con, $SQL);        
		} 	  
	case 1 : 
 
		if (strlen($_POST["f_gr_id"]) > 0 ) {
		    $SQL = "delete from rs_grup_menu " .
		           "where gr_id = '".$_POST["f_gr_id"]."' AND appl_id = '".$_POST["f_appl_id"]."'";
		   
		     pg_query($con, $SQL);        
		} 
	Break ;
	
}

header("Location: ../index2.php?p=$PID".$back_to);
exit();

?>