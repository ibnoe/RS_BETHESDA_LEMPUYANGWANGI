<?php 	// Nugraha, Sun Apr 18 18:58:42 WIT 2004
      	// sfdn, 22-04-2004: hanya merubah beberapa title
      	// sfdn, 23-04-2004: tambah harga obat
      	// sfdn, 30-04-2004
      	// sfdn, 09-05-2004
      	// sfdn, 18-05-2004: age
      	// sfdn, 02-06-2004
      	// Nugraha, Sun Jun  6 18:14:41 WIT 2004 : Paket Transaksi
      	// sfdn, 24-12-2006 --> layanan hanya diberikan kpd. pasien yang blm. lunas
		// rs00006.is_bayar = 'N'
		// sfdn, 27-12-2006
		// App,02-06-2007 --> Developer

session_start();
$PID = "p_jenazah2";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/visit_setting.php");
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  INSTALASI JENAZAH");
title_excel("p_jenazah2");
//--fungsi column color-------------- 
$f = new Form("includes/p_jenazah_insert.php", "POST", "NAME=Form2");
  
?>
<?
if(isset($_GET["g"])) {
	
				  echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='100%'>";
				  echo"<div align=left class=FORM_SUBTITLE1><U>KETERANGAN JENAZAH</U></div>";
					
    echo"
<form name='jenazah' action='actions/p_jenazah2_insert.php'>
<table>
	<tr>
		<td>No Reg Jenazah</td>
		<td>:</td>
		<td><input type='text' name='no_reg'></td>
	</tr>
	<tr>
		<td>Petugas</td>
		<td>:</td>
		<td><input type='text' name='petugas'></td>
	</tr>
	<tr>
		<td>Nama Jenazah</td>
		<td>:</td>
		<td><input type='text' name='nama_jen'></td>
	</tr>
	<tr>
		<td>Jenis kelamin</td>
		<td>:</td>
		<td><input type='text' name='jenis_kel'></td>
	</tr>
	<tr>
		<td>Umur</td>
		<td>:</td>
		<td><input type='text' name='umur'></td>
	</tr>
	<tr>
		<td>Agama</td>
		<td>:</td>
		<td><input type='text' name='agama'></td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td>:</td>
		<td><textarea name='alamat'></textarea></td>
	</tr>
	<tr>
		<td>Tanggal masuk</td>
		<td>:</td>
		<td><input type='text' name='tgl_msk' </td>
		<td>jam masuk</td>
		<td>:</td>
		<td><input type='text' name='jam_msk'></td>
	</tr>
	<tr>
		<td>Tanggal meninggal</td>
		<td>:</td>
		<td><input type='text' name='tgl_keluar' </td>
		<td>jam masuk</td>
		<td>:</td>
		<td><input type='text' name='jam_keluar'></td>
	</tr>
	<tr>
		<td>Diagnosa</td>
		<td>:</td>
		<td><textarea name='diagnosa'></textarea></td>
	</tr>
	<tr>
		<td><input type='submit' value='Cetak'></td>
		<td></td>
		<td><input type='reset' value='Batal'/></td>
	</tr>
</table>
<form>";}

//UPDATE
else if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from jenazah
            where no_reg='".$_GET['e']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	echo"<form action='actions/p_jenazah2.update.php'>
			<table>
				<tr>
					<td>No Reg Jenazah</td>
					<td>:</td>
					<td><input type='text' name='no_reg'  value='".$d->no_reg."' readonly=true></td>
					<td><input type='hidden' name='no_reg_hidden' value='".$d->no_reg."'></td>
				</tr>
				<tr>
					<td>Petugas</td>
					<td>:</td>
					<td><input type='text' name='petugas'  value='".$d->petugas."'></td>
				</tr>
				<tr>
					<td>Nama Jenazah</td>
					<td>:</td>
					<td><input type='text' name='nama_jen'  value='".$d->nama."'></td>
				</tr>
				<tr>
					<td>Jenis kelamin</td>
					<td>:</td>
					<td><input type='text' name='jenis_kel' value='".$d->jenis_kel."'></td>
				</tr>
				<tr>
					<td>Umur</td>
					<td>:</td>
					<td><input type='text' name='umur' value='".$d->umur."'></td>
				</tr>
				<tr>
					<td>Agama</td>
					<td>:</td>
					<td><input type='text' name='agama' value='".$d->agama."'></td>
				</tr>
	<tr>
		<td>Alamat</td>
		<td>:</td>
		<td><textarea name='alamat'>".$d->alamat."</textarea></td>
	</tr>
	<tr>
		<td>Tanggal masuk</td>
		<td>:</td>
		<td><input type='text' name='tgl_msk' value='".$d->tgl_msk."' </td>
		<td>jam masuk</td>
		<td>:</td>
		<td><input type='text' name='jam_msk' value='".$d->jam_msk."'></td>
	</tr>
	<tr>
		<td>Tanggal meninggal</td>
		<td>:</td>
		<td><input type='text' name='tgl_keluar' value='".$d->tgl_keluar."'</td>
		<td>jam masuk</td>
		<td>:</td>
		<td><input type='text' name='jam_keluar' value='".$d->jam_keluar."'></td>
	</tr>
	<tr>
		<td>Diagnosa</td>
		<td>:</td>
		<td><textarea name='diagnosa'>".$d->diagnosa."</textarea></td>
	</tr>
					<tr>
					<td><input type='submit' value=Simpan> &nbsp; &nbsp;
					<input type='reset' value=Batal></td>
			</table>
		</form>";
}


//DELETE
else if(isset($_GET["n"])) {
if ($_GET["n"] != "new") {
        $r = pg_query($con, "SELECT * FROM jenazah where no_reg='".$_GET['n']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	echo"<form action='actions/p_jenazah2.delete.php'>
			<table>
			<h1>Anda yakin menghapus data ini?</h1>
				<tr>	
					<td>No Jenazah</td><td>:</td>
					<td><input type='text' name='no_reg' value='".$d->no_reg."' readonly></td>
				</tr>
				<tr>
					<td>Nama Jenazah</td><td>:</td>
					<td><input type='text' name='nama_jen' value='".$d->nama."' readonly></td>
				</tr>
				<tr>
					<input type='submit' value=YA></td>
					
				</tr>
			</table>
		</form>";
	
}


//VIEWER
else{

	   $t = new PgTable($con, "100%");

$t->SQL = "SELECT no_reg, nama, jenis_kel, tgl_keluar, jam_keluar, diagnosa, dummy FROM jenazah";

        if (!isset($_GET[sort])) {
           $_GET[sort] = "no_reg";
           $_GET[order] = "asc";
	}
  $t->ColHeader = array("No reg","Nama","Jenis Kelamin","Tanggal Meninggal", "Jam Meninggal", "Hasil Visum","Edit/Hapus");
    $t->ShowRowNumber = true;
   	$t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
	$t->ColAlign[4] = "CENTER";
	$t->ColAlign[5] = "CENTER";
	$t->ColAlign[6] = "CENTER";
	$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#0#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='$SC?p=$PID&n=<#0#>'>".icon("delete","Hapus")."</A>".
            "</nobr>"; 
    $t->ColAlign[10] = "CENTER";
	
$t->ColAlign[11] = "CENTER";
 $t->execute();
 echo "<h1><A HREF='$SC?p=$PID&g=0'>Tambah Data</h1>";}
?>