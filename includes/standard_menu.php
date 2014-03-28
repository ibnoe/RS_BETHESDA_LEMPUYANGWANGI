<? // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006
//session_start();
//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "daftarri"  || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "120";
$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);
		
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  INSTALASI GIZI");
?> 
<form name="paket" action="http://10.1.9.4/rumahsakit/includes/standard_menu_insert.php">
	<table>
		<tr>
			<td>ID Menu</td>
			<td>: </td>
			<td><input type="text" name="id_menu"> </td>
		</tr>
		
		<tr>
			<td>Nama Menu</td>
			<td>: </td>
			<td><input type="text" name="nama_menu"> </td>
		</tr>
		<tr>
			<td>Bahan</td>
			<td>: </td>
			<td><textarea name="bahan" rows="6"></textarea> </td>
		</tr>
		<tr>
			<td><input type="submit" value="Simpan"> </td>
			<td><input type="reset" value="Batal"> </td>
		</tr>
	</table>
</form>    


