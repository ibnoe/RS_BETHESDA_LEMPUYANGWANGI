<?php // Nugraha, 22/02/2004

$PID = "882";

require_once("../lib/dbconn.php");

$level = $_POST["f_level"] ;
$back_to = "&L1=" ;

Switch ($level) {
	case 0 : 
	    $SQL = "UPDATE rs_grup_user SET " .
	           "gr_nama = '".trim($_POST["f_gr_nama"])."',".
	           "gr_ket = '".trim($_POST["f_gr_ket"])."' ".
	           "WHERE gr_id = '".trim($_POST["f_gr_id"])."'" ; 
	   pg_query($con, $SQL);	           
 
	Break ;
	
}



 header("Location: ../index2.php?p=$PID".$back_to);
 exit;

?>
