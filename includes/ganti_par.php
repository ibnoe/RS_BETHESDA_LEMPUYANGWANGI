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
			<td>ID PAR</td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<td>Tanggal </td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<tr>
		<td>Status</td>
			<td>: </td>
			<td><select name="jenis_linen">
				<option value ="Laken"> Dipakai</option>
				<option value ="Laken"> Dicuci</option>
				<option value ="Laken"> Diruangan</option>
				<option value ="Laken"> Digudang</option>
				</select> </td>
		</tr>
		
		<tr>
		<tr>
			<td>No Kamar</td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		
		
		
		<tr>
			<td><input type="submit" value="Simpan"> </td>
			<td><input type="reset" value="Batal"> </td>
		</tr>
	</table>
</form>    


