<?

require_once("lib/loginform.php");
require_once("lib/dbconn.php");


//echo "<center><br> Masukan Username dan Password yang sudah di daftarkan <br/> <br/> </center>";
echo "<table cellpadding=0 cellspacing=0 border=0 align=center>";
echo "<tr height=50>";
echo "</tr>";
echo "<tr>";
echo "<td colspan=3 align=center><img src=\"images/login_01.png\" width=636 height=168></td>";
echo "</tr>";


echo "<tr>";
//echo "<td align=center background=images/login_02.gif height=192 >";
echo "<td align=center height=196 style=\"background:white url(images/login_02.gif) no-repeat right top;padding:40px\">";
$t = new Form("login/process.php");
$t->PgConn = $con;
//$t->selectSQL("login_id","Username","select uid, upper(uid) from rs99995 where uid != 'root' order by uid","");
$t->text("login_id","Username","","","");

$t->password("login_pass","Password","","","");
$t->submit("Login");
$t->execute();
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan=3 align=center><img src=\"images/login_03.gif\" width=636 height=15></td>";
echo "</tr>";
echo "<tr>";
echo "<td style=\"font-size:11px;font-family:arial\">&copy; Copyright 2006-2009 <b><i>info</i><span style=\"color:#009900\">Kes</span>SB</b> - All Right Reserved</td>";
echo "</tr>";

if (isset($_SESSION[error])) {
   echo "<tr><td colspan=3 align=center><br><br><font color=red>".$_SESSION[error]."</font></td></tr>";
   unset($_SESSION[error]);
   

}

echo "</table>    ";

?>
