<? // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006
//session_start();
//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "daftarri"  || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "ruang_linen";
$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);
		
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  MANAJEMEN LINEN");


//tambah jenis linen baru
 if (isset($_GET["g_tl"])){
 	
	echo "<form name='paket' method='POST' action='actions/ruang_linen.insert.php'>
	<table>
		<tr>
			<td>ID Ruangan</td>
			<td>: </td>
			<td><input type='text' name='id_linen' readonly='true' value='<OTOMATIS>'> </td>
		</tr>
		
		<tr>
			<td>Nama Ruangan</td>
			<td>: </td>
			<td><input type='text' name='nama'> </td>
		</tr>
		
		<tr>
			<td><input type='submit' value='Simpan'> </td>
			<td><input type='reset' value='Batal'> </td>
		</tr>
	</table>
</form>  ";
 }
 
 
 //edit jenis linen
 elseif (isset($_GET["e_l"])){
 	if ($_GET["e_l"] != "new") {
        $r = pg_query($con, "select * from ruang_linen
            where id='".$_GET['e_l']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	echo "<form name='paket' method='POST' action='actions/ruang_linen.update.php'>
	<table>
		<tr>
			<td>ID Jenis</td>
			<td>: </td>
			<td><input type='text' name='idd' readonly='true' value=".$d->id." disabled=true> 
			<input type=hidden name=id value=$d->id></td>
		</tr>
		
		<tr>
			<td>Jenis</td>
			<td>: </td>
			<td><input type='text' name='nama' value=".$d->nama_ruang."> </td>
		</tr>
		
		<tr>
			<td><input type='submit' value='Simpan'> </td>
			<td><input type='reset' value='Batal'> </td>
		</tr>
	</table>
</form>  ";
 }
 
 //hapus jenis linen
 else if(isset($_GET["n_l"])) {
	if ($_GET["n_l"] != "new") {
        $r = pg_query($con, "SELECT * FROM ruang_linen where id='".$_GET['n_l']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	
echo"<form method='POST' action='actions/ruang_linen.delete.php'>
			<table>
			<h1>Anda yakin menghapus data ini?</h1>
				<tr>	
					<td>No seri</td><td>:</td>
					<td><input type='text' name='idfd value='".$d->id."' disabled=true> 
					<input type=hidden name=id value=$d->id></td>
					
				</tr>
				<tr>
					<td>jenis linen</td><td>:</td>
					<td><input type='text' name='jenis_linen' value='".$d->nama_ruang."' disabled=true></td>
				</tr>
				<tr>
					<input type='submit' value=YA></td>
					
				</tr>
			</table>
		</form>";
}
 
  
 
 
 //edit linen
 
 else{
 	
	//jenis linennya
 	subtitle("R U A N G A N");
 	$t2 = new PgTable($con, "100%");
	$t2->SQL = "SELECT nama_ruang,id FROM ruang_linen";
        if (!isset($_GET[sort])) {
           $_GET[sort] = "id";
           $_GET[order] = "asc";
	}
  	$t2->ColHeader = array("Nama Ruangan","Edit/Hapus");
    $t2->ShowRowNumber = true;
   	$t2->ColAlign[0] = "CENTER";
	$t2->ColAlign[1] = "CENTER";
    $t2->ColAlign[1] = "CENTER";
	$t2->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e_l=<#1#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='$SC?p=$PID&n_l=<#1#>'>".icon("delete","Hapus")."</A>".
            "</nobr>"; 
    $t2->ColAlign[10] = "CENTER";
	$t2->ColAlign[11] = "CENTER";
 	$t2->execute();
	echo "<h1><A HREF='$SC?p=$PID&g_tl=0'>Tambah Ruangan</h1>";
	
	echo"</br> </br>";
 	
	//linenenya
	
 }
?> 


