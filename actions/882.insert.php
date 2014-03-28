<?php // Nugraha, 22/02/2004

$PID = "882";

require_once("../lib/dbconn.php");

$level = $_POST["f_level"] ;
$back_to = "&L1=".$_POST["f_gr_id"] ;

Switch ($level) {
	case 0 : 
	    $SQL = "insert into rs_grup_user " .
	           "(gr_id,gr_nama,gr_ket) ".
	           "values".
	           "('".$_POST["f_gr_id"]."','".$_POST["f_gr_nama"]."','".$_POST["f_gr_ket"]."')";
	   pg_query($con, $SQL);	           
	case 1 : 
		$SQL1 = "SELECT id,menu FROM rs99999 WHERE SUBSTRING(ID FROM 3 FOR 2) <> '00'  ";
		$r1 = pg_query($con, $SQL1);
		$appl = $_POST["ap"];
 
		 
		while ($d1 = pg_fetch_object($r1)) {
			 
 			$temp = $appl[$d1->id] ;
 
			if ($temp){
				$SQL = "insert into rs_grup_menu (appl_id,gr_id) values".
			           	"('".$d1->id."','".$_POST["f_gr_id"]."')";
 
			 	pg_query($con, $SQL);				
			}
		}	
   
	Break ;
	
}



 header("Location: ../index2.php?p=$PID".$back_to);
 exit;

?>
