<? // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006
//session_start();
//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "daftarri"  || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "jenis_linen";
$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);
		
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  MANAJEMEN LINEN");
title_excel("jenis_linen");

//tambah jenis linen baru
 if (isset($_GET["g_tl"])){
 	if ($_GET["g_tl"] != "new") {
        $r = pg_query($con, "select * from linen
            where no_seri='".$_GET['e']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	echo "<form name='paket' action='actions/jenis_linen_insert.php'>
	<table>
		<tr>
			<td>ID Jenis</td>
			<td>: </td>
			<td><input type='text' name='id_linen' readonly='true' value='<OTOMATIS>'> </td>
		</tr>
		
		<tr>
			<td>Jenis</td>
			<td>: </td>
			<td><input type='text' name='enis_l'> </td>
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
        $r = pg_query($con, "select * from jenislinen
            where id='".$_GET['e_l']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	echo "<form name='paket' action='actions/jenis_linen.update.php'>
	<table>
		<tr>
			<td>ID Jenis</td>
			<td>: </td>
			<td><input type='text' name='id_linen' readonly='true' value=".$d->id."> </td>
		</tr>
		
		<tr>
			<td>Jenis</td>
			<td>: </td>
			<td><input type='text' name='jenis_l' value=".$d->nama_jenis."> </td>
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
        $r = pg_query($con, "SELECT * FROM jenislinen where id='".$_GET['n_l']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	
echo"<form action='actions/jenis_linen.delete.php'>
			<table>
			<h1>Anda yakin menghapus data ini?</h1>
				<tr>	
					<td>No seri</td><td>:</td>
					<td><input type='text' name='id' value='".$d->id."' readonly></td>
					
				</tr>
				<tr>
					<td>jenis linen</td><td>:</td>
					<td><input type='text' name='jenis_linen' value='".$d->nama_jenis."' readonly></td>
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
 	subtitle("Jenis Linen");
 	$t2 = new PgTable($con, "100%");
	$t2->SQL = "SELECT nama_jenis,id FROM jenislinen group by nama_jenis,id";
        if (!isset($_GET[sort])) {
           $_GET[sort] = "id";
           $_GET[order] = "asc";
	}
  	$t2->ColHeader = array("Jenis Linen","Edit/Hapus");
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
	echo "<h1><A HREF='$SC?p=$PID&g_tl=0'>Tambah Jenis Linen</h1>";
	
	echo"</br> </br>";
 	
	//linenenya
	
 }
?> 


