<?php
session_start();
$ROWS_PER_PAGE     = 14;

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");
require_once("../lib/setting.php");
$RS_NAME           = $set_client_name ;
$Judul_kartu	   = "Persetujuan Rawat Inap";
$tgl_sekarang 	   = date("d M Y", time());
?>

<h3TML>
<h3EAD>
<TITLE>.: Sistem Informasi <?php echo $RS_NAME; ?> :.</TITLE>
<LINK rel='styleSheet' type='text/css' href='../cetak.css'>
<!-- <LINK rel='styleSheet' type='text/css' href='../invoice.css'> -->
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>
</h3EAD>

<BODY TOPMARGIN=5 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0>
<?

$reg = $_GET["rg"];

$r = pg_query($con,
	"SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, ".
	"    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, ".
	"    e.tmp_lahir, e.jenis_kelamin, e.gol_darah, e.sukubangsa, f.tdesc AS agama, ".
	"    e.alm_tetap, e.kota_tetap, e.umur, e.pos_tetap, e.pekerjaan, e.no_ktp, e.tlp_tetap, ".
	"    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
	"    c.tdesc AS penjamin, a.no_jaminan,a.no_asuransi ,a.rujukan, a.rujukan_rs_id, ".
	"    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
	"    a.status, a.tipe, e.status_nikah, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
	"    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, ".
	"        CASE ".
	"            WHEN a.rawat_inap = 'I' THEN 'Rawat Inap' ".
	"            WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan' ".
	"            ELSE 'IGD' ".
	"        END AS rawat, ".
	"        age(a.tanggal_reg , e.tgl_lahir ) AS umur, ".
"	case when a.rujukan = 'Y' then 'Rujukan' else 'Non-Rujukan' end as datang ".
"    , i.tdesc as poli,e.pangkat_gol,e.nrp_nip,e.kesatuan ".
	"FROM rs00006 a ".
	"   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
	"   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
	"   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
	"   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
	"   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
	"   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
	"   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc AND h.tt = 'JDP' ".
	"   left join rs00001 i on i.tc_poli = a.poli ".
	"WHERE a.id = '$reg'  ");

$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);
echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='50%'>";
/*$umure = umur($d->umur);
$umure = explode(" ",$umure);
$umur = $umure[0]." thn";*/
$alamat = $d->alm_tetap." ".$d->kota_tetap;

// ambil bangsal
$id_min = getFromTable("select min(id) from rs00010 where no_reg = '".$_GET["rg"]."'");
$id_max = getFromTable("select max(id) from rs00010 where no_reg = lpad(".$_GET["rg"].",10,'0')");
if (!empty($id_max)) {
$bangsal = getFromTable("select c.bangsal || ' / ' || e.tdesc ".
				   "from rs00010 as a ".
				   "    join rs00012 as b on a.bangsal_id = b.id ".
				   "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
				   //"    join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
				   "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
				   "where a.id = '$id_max'");
}
$bangsal = getFromTable("select d.bangsal || ' / ' || c.bangsal || ' / ' || e.tdesc || ' / ' || b.bangsal ".
				   "from rs00010 as a ".
				   "    join rs00012 as b on a.bangsal_id = b.id ".
				   "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
				   "    join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
				   "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
				   "where a.id = '$id_min'");
?>

<br>
<br>
<br>

<table border="1" width='100%' cellpadding="10" cellspacing="10">
	<tr>
		<td class='TBL_HEAD2' width='50%' rowspan="2" align="center"><font face="Times New Roman" size="6"><b>PERSETUJUAN PASIEN <br> RAWAT INAP</b></font></td>
		<td class='TBL_HEAD2' width='50%'><font face="Times New Roman" size="4"><b>No. Registrasi : <?php echo $d->id;?></b></font></td>
	</tr>
	<tr>
		<td class='TBL_HEAD2' width='50%'><font face="Times New Roman" size="4"><b>No. Rekam Medik : <?php echo $d->mr_no;?></b></font></td>
	</tr>
</table>

<br>

<table border="0" width='100%'>
	<tr>
		<td class='TBL_BODY' width='100%'>Yang bertandatangan di bawah ini,</td>
	</tr>
</table>

<br>

<table border="0" width='100%'>
	<tr>
		<td class='TBL_HEAD2' width='100%' colspan="2">PASIEN</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Nama</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->nama;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Jenis Kelamin</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->jenis_kelamin;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Tempat & Tanggal Lahir</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->tmp_lahir.', '.$d->tgl_lahir;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Umur</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->umur;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Golongan Darah</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->gol_darah;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Agama</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->agama;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Bangsa</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->sukubangsa;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Alamat</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->alm_tetap.', '.$d->kota_tetap.' '.$d->pos_tetap;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Telp.</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->tlp_tetap;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>No. KTP/SIM/Pasport</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->no_ktp;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Pendidikan :</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->kesatuan;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Pekerjaan</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->pekerjaan;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Status Perkawinan</td>
		<td class='TBL_BODY' width='75%'>: <?php echo $d->status_nikah;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Pernah dirawat di RS. Sarila Husada</td>
		<td class='TBL_BODY' width='75%'>: <input type="checkbox">Belum <input type="checkbox">Sudah pernah, 
											tahun ............................................. No. RM : .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Sumber Pasien Kiriman Dari</td>
		<td class='TBL_BODY' width='75%'>: <input type="checkbox">URJ <input type="checkbox">IGD 
											<input type="checkbox">Dokter <input type="checkbox">Rumah Sakit 
											<input type="checkbox">Puskesmas <br>&nbsp;&nbsp;<input type="checkbox">Dokter luar RS 
											<input type="checkbox">Perawat/Bidan praktek
											<input type="checkbox">Polisi <input type="checkbox">Lainnya<td>
	</tr>
</table>

<br>

<table border="0" width='100%'>
	<tr>
		<td class='TBL_HEAD2' width='100%' colspan="2">PENANGGUNG JAWAB</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Nama</td>
		<td class='TBL_BODY' width='75%'>: .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Alamat</td>
		<td class='TBL_BODY' width='75%'>: .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Telp.</td>
		<td class='TBL_BODY' width='75%'>: .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Kode Pos</td>
		<td class='TBL_BODY' width='75%'>: .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>No. KTP/SIM/Pasport</td>
		<td class='TBL_BODY' width='75%'>: .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Nama dan Alamat Keluarga Terdekat</td>
		<td class='TBL_BODY' width='75%'>: .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='100%' colspan="2">Dengan ini menyatakan bahwa saya setuju untuk dilakukan rawat inap di RS. Sarila Husada/sementara di *)</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Ruang</td>
		<td class='TBL_BODY' width='75%'>: .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Kamar No.</td>
		<td class='TBL_BODY' width='75%'>: .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Kelas</td>
		<td class='TBL_BODY' width='75%'>: .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='100%' colspan="2">Terhadap : diri saya sendiri/anak/istri/suami/............................................. saya tersebut di atas.</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Ruang/kelas yang dikehendaki/menjadi hak pasien </td>
		<td class='TBL_BODY' width='75%'>: <input type="checkbox">Sudah sesuai di atas <input type="checkbox">Ruang/Kelas : .............................................</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='25%'>Penaggung biaya</td>
		<td class='TBL_BODY' width='75%'>: <input type="checkbox">Pribadi <input type="checkbox">Kantor/Perusahaan <input type="checkbox">Asuransi : .............................................</td>
	</tr>
</table>

<br>

<table border="0" width='100%'>
	<tr>
		<td class='TBL_BODY' width='100%' colspan="3">Demi kelancaran perawatan, pengobatan, dan administrasi dengan ini juga menyatakan,</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='1%'>&nbsp;</td>
		<td class='TBL_BODY' width='1%'>a.</td>
		<td class='TBL_BODY' width='98%'>Setuju dan memberi ijin kepada dokter yang bersangkutan untuk merawat saya/pasien tersebut di atas,</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='1%'>&nbsp;</td>
		<td class='TBL_BODY' width='1%'>b.</td>
		<td class='TBL_BODY' width='98%'>Sanggup/bersedia membayar seluruh biaya perawatan sesuai dengan kelas yang saya kehendaki,</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='1%'>&nbsp;</td>
		<td class='TBL_BODY' width='1%'>c.</td>
		<td class='TBL_BODY' width='98%'>Telah menyetujui dan bersedia mentaati segala peraturan yang berlaku di RS. Sarila Husada,</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='1%'>&nbsp;</td>
		<td class='TBL_BODY' width='1%'>d.</td>
		<td class='TBL_BODY' width='98%'>Memberi kuasa kepada Dokter/Rumah Sakit untuk memberikan keterangan yang diperlakukan oleh instansi pihak penanggung biaya perawatan saya/pasien tersebut di atas,</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='1%'>&nbsp;</td>
		<td class='TBL_BODY' width='1%'>e.</td>
		<td class='TBL_BODY' width='98%'>bersedia menyelesaikan segala persyaratan administrasi yang diperlukan pihak penanggung biaya ke sub bagian kasir dalam waktu yang ditentukan,</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='1%'>&nbsp;</td>
		<td class='TBL_BODY' width='1%'>f.</td>
		<td class='TBL_BODY' width='98%'>Sanggup mentaati semua peraturan yang ada di RS. Sarila Husada.</td>
	</tr>
</table>

<br>

<table border="0" width='100%'>
	<tr>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
		<td class='TBL_BODY' width='50%' align="center">Sragen, <?php echo $tgl_sekarang;?></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='50%' align="center">Petugas Admisi TPPTI,</td>
		<td class='TBL_BODY' width='50%' align="center">Yang menyatakan,</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
		<td class='TBL_BODY' width='50%' align="center">&nbsp;</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='50%' align="center"><u>.............................................</u></td>
		<td class='TBL_BODY' width='50%' align="center"><u>.............................................</u></td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='50%' align="center">Tanda tangan dan nama terang</td>
		<td class='TBL_BODY' width='50%' align="center">Tanda tangan dan nama terang</td>
	</tr>
</table>

<table border="0" width='100%'>
	<tr>
		<td class='TBL_BODY' width='100%' colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class='TBL_BODY' width='100%' colspan="2"><b>CATATAN :</b></td>
	</tr>
</table>	

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</h3tml>
