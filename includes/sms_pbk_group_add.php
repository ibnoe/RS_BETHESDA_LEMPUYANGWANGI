<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004
   // sfdn, 24-12-2006

$PID = "sms_pbk_group_add";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

// 24-12-2006
    if ($_SESSION[uid] == "kasir2") {
       $what = "RAWAT INAP";
       $sqlayanan = "NOT LIKE '%IGD%'";
    } elseif ($_SESSION[uid] == "kasir1") {
       $what = "RAWAT JALAN";
       $sqlayanan = "NOT LIKE '%IGD%'";
    } else {
       $what = "IGD";
       $sqlayanan = "LIKE '%IGD%'";

  echo "<table>";
    echo "<tr>";
    echo "<td><b><font size=4>Tambah Group</size></b></td>";
    echo "</tr>";
    echo "</table>";


// ---- end ----
  }
?>

<form name ="fp" action="actions/sms_pbk_group_proc.php" method=POST>
    <table border="0"  align="center" width=75% cellspacing="0" cellpadding="0">
    <TR>
        <td width=0%>&nbsp;</td>
        <TD width="25%" CLASS=FORM>Nama Group</TD>
        <TD width="4%" CLASS=FORM><div align="center">:</div></TD>
        <TD width="71%" CLASS=FORM>
    <INPUT TYPE=TEXT NAME=group SIZE=10 MAXLENGTH=12>
    </TD>
    </TR>
    <TR><TD></TD></TR>
       	<tr>
	<td><input type=submit value=Kirim></td>
	<td><input type=reset value=Reset></td>
	</tr>
</table>

</form>