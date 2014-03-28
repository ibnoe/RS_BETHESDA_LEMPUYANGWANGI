<?
require_once("lib/setting.php");
require_once("lib/loginform.php");
require_once("lib/dbconn.php");
?>

<html>
<head> 
<title><?=$set_client_name ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT language="JavaScript" src="menu_style.js"></SCRIPT>

<link href="css/template.css" rel="stylesheet" type="text/css" />
    <LINK rel='StyleSheet' type='text/css' href='default.css'>
    <LINK rel="stylesheet" type="text/css" href="menu.css">
    <LINK rel="stylesheet" type="text/css" href="tabbar.css">
    <LINK rel="icon" href="images/icon.png" type="image/png">
    <LINK rel="shortcut icon" href="images/icon.png" type="image/png">
    <SCRIPT language="JavaScript" src="lib/sjsm.js"></SCRIPT>
    
    <SCRIPT language="JavaScript" src="lib/date/CalendarPopup.js"></SCRIPT>
    <SCRIPT language="JavaScript" src="lib/date/date.js"></SCRIPT>
    <SCRIPT language="JavaScript" src="lib/date/AnchorPosition.js"></SCRIPT>
    <SCRIPT language="JavaScript" src="lib/date/PopupWindow.js"></SCRIPT>
	<SCRIPT LANGUAGE="JavaScript">
		var cal = new CalendarPopup();
	</SCRIPT>
	
</head>
<body>
<?

/*echo "<center><br> Masukan Username dan Password yang sudah di daftarkan <br/> <br/> </center>";
echo "<table cellpadding=0 cellspacing=0 border=0 align=center>";
echo "<tr height=50>";
echo "</tr>";
echo "<tr>";
echo "<td colspan=3 align=center><img src=\"images/login_01.png\" width=636 height=168></td>";
echo "</tr>";


echo "<tr>";
//echo "<td align=center background=images/login_02.gif height=192 >";
echo "<td align=center height=196 style=\"background:transparent url(images/login_02.png) no-repeat right top;padding:40px\">";
$t = new Form("login/process.php");
$t->PgConn = $con;
//$t->selectSQL("login_id","Username","select uid, upper(uid) from rs99995 where uid != 'root' order by uid","");
$t->text("login_id","Username","","","");

$t->password("login_pass","Password","","","");
$t->submit("Login");
$t->execute();
echo "</td>";
echo "</tr>";

if (isset($_SESSION[error])) {
   echo "<tr><td colspan=3 align=center><br><br><font color=red>".$_SESSION[error]."</font></td></tr>";
   unset($_SESSION[error]);
   

}

echo "</table>    ";
*/

?>
<LINK rel="stylesheet" type="text/css" href="css/redis.css">
<div id="container-log">
    <div id="log-head">Sign In</div>
    <div id="log-box">
        <img src="images/logo.png"> </img>
        <div class="log-form">
            <div id="notif">Masukan Username dan Password</div>
            <form action="login/process.php" method="post">
                <div class="log-fix"><input name="login_id" type="text" placeholder="Username"></div>
                <div class="log-fix"><input name="login_pass" type="password" placeholder="Password"></div>
                <div class="log-submit"><input type="submit" value="Login"></div>
            </form>
            <?
            if (isset($_SESSION[error])) {
                echo "<div align=\"center\"><font color=red>" . $_SESSION[error] . "</font></div>";
                unset($_SESSION[error]);
            }
            ?>
        </div>
    </div>
</div>
<div id="foot-er">Copyright &copy; 2012 by<b>&nbsp;<i>One Medic</i></b> - All Right Reserved<br><b>www.one-medic.net</b></br></div>
</body>
