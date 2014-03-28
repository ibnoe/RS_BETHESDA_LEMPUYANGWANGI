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
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  PENYEWAAN LINEN");
?> 
<form name="paket">
	<table>
		<tr>
			<td>No Nota</td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<td>Tanggal </td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<td>Nama Penyewa</td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<td>No Kamar</td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<td>Nama Pasien</td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<td>Lama Sewa </td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<tr>
			<td>Id Linen</td>
			<td>: </td>
			<td><input type="text" name="max_cuci"> </td>
		</tr>
		<tr>
			<td>Jenis Linen</td>
			<td>: </td>
			<td> <select name="jenis_linen">
				<option value ="Laken"> Laken </option>
				</select> 
			</td>
		</tr>
		<tr>
			<td>Kelas Linen </td>
			<td>: </td>
			<td> <select name="kelas_linen">
				<option value ="Vip"> Vip </option>
				</select> 
			</td>
		</tr>
		<td>Harga perhari </td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<td>Total </td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<td>Uang Muka </td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<td>Sisa</td>
			<td>: </td>
			<td><input type="text" name="id_linen"> </td>
		</tr>
		<tr>
			<td><input type="submit" value="Simpan"> </td>
			<td><input type="reset" value="Batal"> </td>
		</tr>
	</table>
</form>    


