<?

require_once("lib/form.php");
require_once("lib/dbconn.php");
$PID = "set_password";
$SC = $_SERVER["SCRIPT_NAME"];

$_SESSION[error] ="";
if ($_POST["login_pass1"] or $_POST["login_pass2"]) {
 

	$old_pass =MD5($_POST["login_pass1"]);
	$db_pass = getFromTable("select password  from rs99995 ".
                     "where uid = '".$_SESSION[uid]."' " ) ;
	$new_pass = MD5($_POST["login_pass2"]);
	
	if ($old_pass == $db_pass ){
  		$SQL = "UPDATE rs99995 SET password = '".$new_pass."' ".
	               "WHERE uid = '".$_SESSION[uid]."'" ; 	  
		pg_query($con, $SQL);		
		$_SESSION[error] = "PASSWORD SUDAH DI GANTI";

	}else {
		$_SESSION[error] = "PASSWORD LAMA TIDAK SAMA DENGAN PASSWORD DI DATABASE ";
		}

}

echo "<table cellpadding=0 cellspacing=0 border=0 align=center>";
if (isset($_SESSION[error])) {
   echo "<tr><td colspan=3 align=center><br><br><font color=red>".$_SESSION[error]."</font><br><br></td></tr>";
   unset($_SESSION[error]);

}
echo "<tr bgcolor=#646464>";
echo "<td colspan=3 align=center><font color=white style='font-size: 18px; font-weight: bold'>SET PASSWORD</font>";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td>";
	$t = new Form("index2.php?p=set_password");
	$t->PgConn = $con;
	$t->password("login_pass1","PASSWORD LAMA","20","20",$_POST["login_pass1"]);
	$t->password("login_pass2","PASSWORD BARU","20","20",$_POST["login_pass2"]);
	$t->submit("GANTI");
	$t->execute();
echo "</td>";
echo "</tr>";



echo "</table>    ";

?>
