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
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  MANAJEMEN LINEN");
?> 
<form name="paket">
	<table>
		<tr>
			<td>ID Kelompok Bahan</td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		
		<tr>
			<td>Nama Kelompok Bahan</td>
			<td>: </td>
			<td><input type="text" name="max_cuci"> </td>
		</tr>
		
		<tr>
			<td><input type="submit" value="Simpan"> </td>
			<td><input type="reset" value="Batal"> </td>
		</tr>
	</table>
</form>    


