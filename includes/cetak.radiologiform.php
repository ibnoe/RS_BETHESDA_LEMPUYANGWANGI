<?php
	// sfdn, 24-12-2006
session_start();

require_once("../lib/setting.php");
require_once("../lib/terbilang.php");

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

$ROWS_PER_PAGE     = 999999;
//$RS_NAME           = $set_header[0]."<br>".$set_header[1];
//$RS_ALAMAT         = $set_header[2]."<br>".$set_header[3].$set_header[4];

?>

<HTML>


<HEAD>
<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
<LINK rel='styleSheet' type='text/css' href='../cetak.css'>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>

</HEAD>

<BODY TOPMARGIN=1 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0 />
<table align=center >
    <tr>
        <td height="225">&nbsp;</td>
    </tr>
    <tr>
        <!--<td align="center" colspan="4" style="font-family: Tahoma; font-size: 18px; letter-spacing: 4px;"><b><U>HASIL PEMERIKSAAN RADIOLOGI</b></u></td>-->
    </tr>
</table>
<?
//echo "<hr>";
//titlecashier2('INSTALASI PEMELIHARAAN SARAN RUMAH SAKIT');
//titlecashier4('RS SARILA HUSADA SRAGEN');
//titlecashier1('INSTALASI RADIOLOGI');
//titlecashier1('Telp. Hunting (0752) 21720 - 21492 - 21831 - 21322');
//echo "<hr>";
echo "<br>";
$reg=getFromTable("select no_reg from c_visit where oid='".$_GET[rg]."'");
$sql = "select a.*,(b.nama)as periksa,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,
				(h.nama)as pengirim,(i.nama)as operator,f.nama as nm_pasien,f.mr_no,g.tdesc as poli_asal,
				case when f.jenis_kelamin='L' then 'Laki-laki' else 'Perempuan' end as jk, z.tdesc as tipe, tanggal(CURRENT_DATE,0) as tgl_cetak, (CURRENT_TIME) as wkt
						from c_visit a 
						left join rs00017 b on a.id_dokter = B.ID 
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00002 f on f.mr_no=d.mr_no
						left join rs00017 h on h.id = a.id_perawat
                        left join rs00017 i on i.id = a.id_perawat1
						left join rs00001 g on g.tc_poli = d.poli and g.tt ='LYN'
						left join rs00001 z on z.tc = d.tipe and z.tt ='JEP'
						where a.no_reg='$reg' and a.id_poli ='204' and a.oid='".$_GET["rg"]."' ";

				$r = pg_query($con,$sql);
				$n = pg_num_rows($r);
			    if($n > 0) $d2 = pg_fetch_array($r);
			    pg_free_result($r);

echo "<br>";
echo "<b><u><font size='2'><i>Untuk dilampirkan pada Status Penderita</i></font></u></b>";
echo "<br>";

// membaca input dari form
$input = $d2["vis_8"];

// memecah string input berdasarkan karakter '\r\n\r\n'
$pecah = explode("\r\n\r\n", $input);

// string kosong inisialisasi
$text = "";

// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
// lalu menggabungnya menjadi satu string utuh $text
for ($i=0; $i<=count($pecah)-1; $i++)
{
   $part = str_replace($pecah[$i], "<p>".$pecah[$i]."</p>", $pecah[$i]);
   $text .= $part;
}

//==============================================
$input1 = $d2["vis_7"];

// memecah string input berdasarkan karakter '\r\n\r\n'
$pecah1 = explode("\r\n\r\n", $input1);

// string kosong inisialisasi
$text1 = "";

// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
// lalu menggabungnya menjadi satu string utuh $text
for ($i1=0; $i1<=count($pecah1)-1; $i1++)
{
   $part1 = str_replace($pecah1[$i1], "<p>".$pecah1[$i1]."</p>", $pecah1[$i1]);
   $text1 .= $part1;
}

//==============================================
$input2 = $d2["vis_2"];

// memecah string input berdasarkan karakter '\r\n\r\n'
$pecah2 = explode("\r\n\r\n", $input2);

// string kosong inisialisasi
$text2 = "";

// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
// lalu menggabungnya menjadi satu string utuh $text
for ($i2=0; $i2<=count($pecah2)-1; $i2++)
{
   $part2 = str_replace($pecah2[$i2], "<p>".$pecah2[$i2]."</p>", $pecah2[$i2]);
   $text2 .= $part2;
}
//==============================================
$input3 = $d2["vis_1"];

// memecah string input berdasarkan karakter '\r\n\r\n'
$pecah3 = explode("\r\n\r\n", $input3);

// string kosong inisialisasi
$text3 = "";

// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
// lalu menggabungnya menjadi satu string utuh $text
for ($i3=0; $i3<=count($pecah3)-1; $i3++)
{
   $part3 = str_replace($pecah3[$i3], "<p>".$pecah3[$i3]."</p>", $pecah3[$i3]);
   $text3 .= $part3;
}
	?>
	<br>
	<table border="0" width="100%">
	<tr>
		<td class="TITLE_SIM3" width="14%" valign="top"><b>No. RM</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="30%" valign="top"><b> <? echo $d2["mr_no"]; ?></b></td>
		
		<td class="TITLE_SIM3" width="14%" valign="top"><b>No. Reg</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="30%" valign="top"><b> <?  echo $_GET["rg"]; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3" width="14%" valign="top"><b>Nama</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="30%" valign="top"><b> <?echo $d2["nm_pasien"];  ?></b></td>
		
		<td class="TITLE_SIM3" width="14%" valign="top"><b>No. Foto</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="30%" valign="top"><b> <? echo $d2["vis_3"]; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3" width="14%" valign="top"><b>Umur</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="30%" valign="top"><b> <?= $d2->pekerja; ?></b></td>
		
		<td class="TITLE_SIM3" width="14%" valign="top"><b>Poli/Ruangan</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="30%" valign="top"><b> <?echo $d2["poli_asal"];  ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3" width="17%" valign="top"><b>Jenis Kelamin</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="30%" valign="top"><b> <?echo $d2["jk"]; ?></b></td>
		
		<td class="TITLE_SIM3" width="25%" valign="top"><b>Dokter yang meminta</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="30%" valign="top"><b> <?echo $d2["pengirim"]; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3" width="17%" valign="top"><b>Tipe Pasien</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="30%" valign="top"><b> <?echo $d2["tipe"]; ?></b></td>
		
		<td class="TITLE_SIM3" width="25%" valign="top"><b>Tanggal Cetak</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="30%" valign="top"><b><?echo $d2["tgl_cetak"]; ?> - <?echo date('H:i:s', strtotime($d2["wkt"])); ?></b></td>
	</tr>
</table>
<br>
<hr>
<br>
<table>
	<tr>
		<td class="TITLE_SIM3" width="17%" valign="top"><b>Pemeriksaan</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="50%" valign="top"><b> <? echo $text3;  ?></b></td>
	</tr>
	<!--<tr>
		<td class="TITLE_SIM3" width="14%" valign="top"><b>Diagnosa Klinis</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="50%" valign="top"><b> <?echo $text2; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3" width="14%" valign="top"><b>Hasil</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="50%" valign="top"><b> <?echo $text1; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3" width="14%" valign="top"><b>&nbsp;</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>&nbsp;</b></td>
		<td class="TITLE_SIM3" width="50%" valign="top"><b>&nbsp;</b></td>
	</tr>
	<!--
	<tr>
		<td class="TITLE_SIM3" width="14%" valign="top"><b>Hasil Radiolog</b></td>
		<td class="TITLE_SIM3" width="1%" valign="top"><b>:</b></td>
		<td class="TITLE_SIM3" width="50%" valign="top"><b><? /* echo $text;*/ ?></b></td>
	</tr>
    -->
</table>
<br>
	<?


//$tgl_sekarang = "select tanggal(current_date,0)";
?>
<table border="0" align="right" width="100%">
  <!--<tr>
        <td align="center" class="TITLE_SIM3" width="50%"><b>&nbsp;</b></td>
		<td align="center" class="TITLE_SIM3" width="50%"><b>Sragen, <?echo $d2["tanggal_reg"];?></b></td>
      
  </tr>
  <tr>
	<td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
    <td align="center" class="TITLE_SIM3"><b>Dokter Spesialis Radiologi</b></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
	<td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
	<td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
	<td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
	<td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
	<td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
    <td align="center" class="TITLE_SIM3"><b><?php echo $d2["periksa"];?></b></td>
</tr>-->
</table>

<br>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</html>
