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

<?
echo "<hr>";
titlecashier2('');
titlecashier4('RS SITI KHADIJAH PEKALONGAN');
titlecashier1('');
titlecashier1('');
echo "<hr>";
//echo "<br>";

$reg = $_GET["rg"];

$rt = pg_query($con,
        "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, ".
        "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, ".
        "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, ".
        "    e.alm_tetap, e.kota_tetap, e.umur, e.pos_tetap, e.tlp_tetap, ".
        "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
        "    c.tdesc AS penjamin, a.no_jaminan,a.no_asuransi ,a.rujukan, a.rujukan_rs_id, ".
        "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
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
		//"WHERE a.id = '$reg'");
     

    $nt = pg_num_rows($rt);
    if($nt > 0) $dt = pg_fetch_object($rt);
    pg_free_result($rt);

if ($reg > 0) {
    if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where id = '$reg' ".
                     " ") ==0) {
                     //"and status = 'A'") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}




echo "<table align=center width=100% border=0>";
echo "<tr><td align=center> ";
if ($_GET["kas"] == "rj") {
  titlecashier2("KWITANSI PEMBAYARAN RAWAT JALAN");
} elseif ($_GET["kas"] == "ri") {
  titlecashier2("KWITANSI PEMBAYARAN RAWAT INAP");
} else {
  titlecashier2("KWITANSI PEMBAYARAN IGD");
}
//titlecashier("KWITANSI PEMBAYARAN");
$tglini = date("d");
$blnini = date("m");
$thnini = date("Y");
echo "</td></tr>";
echo "<tr><td align=center>";
titlecashier3("No : ".$reg);
echo "<hr>";
echo "</td></tr>";
echo "</table>";

//include("335.inc_.php");
?>
<table border="0" width=100%>
	<tr>
        <td valign=top width=30% class="TITLE_SIM3"><b>SUDAH TERIMA DARI </b></td>
		<td valign=top class="TITLE_SIM3"><b>:</b></td>
		<td valign=top class="TITLE_SIM3"><b> Tn/Ny/Sdr. <?= $dt->nama ?></b></td>
        
	</tr>
<?
$rrs = pg_query($con,
        "select * from rs00005 ".
		"where kasir in ('BYR','BYI','BYD') and ".
		"	to_number(reg,'999999999999') = '$reg' "); //and ".
		//"	referensi IN ('KASIR')");

while ($dds = pg_fetch_object($rrs)) {
?>

	<tr>
        <td valign=top width=30% class="TITLE_SIM3"><b>UANG SEJUMLAH</b></td>
		<td valign=top class="TITLE_SIM3"><b>:</b></td>
		<td valign=top  class="TITLE_SIM3"><b>Rp. <?= number_format($dds->jumlah,2) ?></b></td>
        
	</tr><tr>
		<td valign=top class="TITLE_SIM3"><b>&nbsp;</b></td>
		<td valign=top class="TITLE_SIM3"><b>&nbsp;</b></td>
        <td valign=top class="TITLE_SIM3"><b><i><?php $y=terbilang($dds->jumlah);
		echo strtoupper($y);?> RUPIAH</i></b></td>
	</tr>

<?
}
pg_free_result($rrs);


?>

<tr>
		<td valign=top class="TITLE_SIM3"><b>SEBAB DARI</b></td>
		<td valign=top class="TITLE_SIM3"><b>:</b></td>
        <td valign=top class="TITLE_SIM3"><b>Pembayaran Pelayanan Kesehatan di <?php echo $set_header[0]; ?></b></td>
   </tr>
</table>
<?
include("335.inc_2.php");
?>

</body>
</html>