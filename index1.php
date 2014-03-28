<?
$ROWS_PER_PAGE     = 14;
$RS_NAME           = "";
$ROOM_LEAP_TIME    = "12:00:00";



session_start();

if (isset($_GET["httpHeader"]) && file_exists("includes/".$_GET["p"].".php")) {
    include("includes/".$_GET["p"].".php");
    exit;
}

?>
<HTML>

<HEAD>
<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='default.css'>
    <LINK rel="stylesheet" type="text/css" href="menu.css">
    <LINK rel="icon" href="images/icon.png" type="image/png">
    <LINK rel="shortcut icon" href="images/icon.png" type="image/png">
    <SCRIPT language="JavaScript" src="lib/sjsm.js"></SCRIPT>
</HEAD>

<BODY TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>

<script language="JavaScript" src="menu.php"></script>

<CENTER>
<BR>
<TABLE bgcolor="#ffffff" WIDTH=780 BORDER=0 CELLSPACING=0 CELLPADDING=0>
    <TR>
        <TD colspan="3">
	<!-- <IMG src="images/dinar.jpg" border="0"> -->
	
	<IMG src="image/bg/bg.home.jpg" border="0">
	</TD>
    </TR>
    <TR>
        <TD colspan="3" bgcolor="#299100"></TD>
    </TR>
    <TR>
        <TD colspan="3" bgcolor="#b6f29b">
        <script language="Javascript">d.write(menu.m)/*Menu inserted*/</script>
        </TD>
    </TR>
    <TR>
        <TD colspan="3" bgcolor="#299100"></TD>
    </TR>
    <TR>
        <TD><img src="images/bg.jpg" border="0"></TD>
        <TD valign="TOP">
            <TABLE border="0" width="100%" cellspacing="0" cellpadding="8">
            <tr>
            <td class=form_title align=right><? echo "<font color=red>".strtoupper($_SESSION[uid])."</font>";?></td>
            </tr>

            <TR><TD>
            <?
            if (isset($_GET[p]) && file_exists("includes/".$_GET["p"].".php")) {
                include("includes/".$_GET["p"].".php");
            } elseif (empty($_SESSION[uid])) {
                include("login/index.php");
            } else {
                echo "<img src=\"images/spacer.gif\" border=0 width=1 height=150><br>";
                echo "<div align=center><font class=form_title>".strtoupper($_SESSION[uid])." siap beroperasi.";
                echo "<br>Pilih menu di atas.</font></div>";
            }

            ?>
			
            </TD></TR></TABLE>
            <BR>
            <BR>
        </TD>
        <TD><img src="images/bg.jpg" border="0"></TD>
    </TR>
    <TR>
        <TD colspan="3" bgcolor="#299100">&nbsp;</TD>
    </TR>
</TABLE>

</CENTER> 
<script language="JavaScript">d.write(menu.sm)/*Menu inserted*/</script>

<DIV align="center">----- oOo -----</DIV>

</BODY>

</HTML>
