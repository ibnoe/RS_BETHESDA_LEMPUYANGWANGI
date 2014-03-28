<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 09-05-2004
   // sfdn, 11-05-2004


$PID = "arus_kas";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

	
if($_GET["tc"] == "view") {
/*
*/
} else {
    
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > ALIRAN KAS");
		//title_excel("aruskas");
		title_excel("aruskas&mPeriode=".$_GET["mPeriode"]."");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > ALIRAN KAS");
		//title_excel("aruskas");
		title_excel("aruskas&mPeriode=".$_GET["mPeriode"]."");
    }
    echo "<br>";
    //$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

    if (!$GLOBALS['print']){
	    if (!isset($_GET['tanggal1D'])) {
		$tanggal1D = date("d", time());
		$tanggal1M = date("m", time());
		$tanggal1Y = date("Y", time());
		$tanggal2D = date("d", time());
		$tanggal2M = date("m", time());
		$tanggal2Y = date("Y", time());

	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

	    } else {
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
	    }

	$f->submit ("TAMPILKAN");
	    $f->execute();

	} else {
		if (!isset($_GET['tanggal1D'])) {
		$tanggal1D = date("d", time());
		$tanggal1M = date("m", time());
		$tanggal1Y = date("Y", time());
		$tanggal2D = date("d", time());
		$tanggal2M = date("m", time());
		$tanggal2Y = date("Y", time());

	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "disabled");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "disabled");
	    } else {
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
		
	    }

	    $f->execute();
	}
	
    echo "<br>";
//Pendapatan Usaha
$layanan1=getfromtable("select sum(a.jumlah) from rs00005 a, rs00001 b, rs00006 c
where c.poli=b.tc_poli and c.id=a.reg and a.reg=c.id and a.layanan not in ('99997') and a.kasir not in ('BYR','POT','ASK','BYI','BYD') and a.is_karcis='N' and b.tt = 'LYN' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2')");
$obat1=getfromtable("select sum(a.jumlah) from rs00005 a, rs00001 b, rs00006 c
where c.poli=b.tc_poli and c.id=a.reg and a.reg=c.id and a.layanan='99997' and a.kasir not in ('BYR','POT','ASK','BYI','BYD') and a.is_karcis='N' and b.tt = 'LYN' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2')");
$pendapatan_usaha=$layanan1+$obat1;
//Pendapatan APBD
$apbd=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,2)='22' and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//Pendapatan Hibah
$hibah=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,2)='23' and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//piutang
$piutang=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,4)='1118' and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//persediaan
$persediaan=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,7) in ('1105.04','1105.03') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//uang muka
$uang_muka=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,7) in ('1105.05','1104.01') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//Pendapatan usaha lain-lain
$usaha_lain=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,7) in ('1104.04','1104.03''1104.02','1105.02','1105.01') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//biaya administrasi umum
$administrasi_umum=getfromtable("select sum(jumlah) from kas_keluar where substring(kode_trans,1,4)='3300' and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//biaya layanan

//biaya pembayaran hutang lancar
$pembayaran_hutang=getfromtable("select sum(jumlah) from kas_keluar where substring(kode_trans,1,4)='4100' and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//biaya lainnya
$biaya_lainnya=getfromtable("select sum(jumlah) from kas_keluar where substring(kode_trans,1,4) in ('3100','3200','6000') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//============================================================================================
$jumlah_kegiatan_operasi=($pendapatan_usaha+$apbd+$hibah+$piutang+$persediaan+$persediaan+$uang_muka+$usaha_lain)-($pembayaran_hutang+$biaya_lainnya);
//============================================================================================

//penjualan Aset
$penjualan_aset=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,7) in ('2401.00') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//penjualan aset investasi jangka panjang
$penjualan_aset_panjang=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,7) in ('2402.00','2403.00','2404.00') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//penjualan Aset lainnya
$penjualan_aset_lainnya=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,7) in ('2405.00') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//pembelian Aset
$pembelian_aset=getfromtable("select sum(jumlah) from kas_keluar where substring(kode_trans,1,7) in ('6100.00','6200.00') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//Pemeliharaan Aset
$pemeliharaan_aset=getfromtable("select sum(jumlah) from kas_keluar where substring(kode_trans,1,7) in ('6300.00') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//Pemeliharaan Aset Lainnya
$pemeliharaan_aset_lainnya=getfromtable("select sum(jumlah) from kas_keluar where substring(kode_trans,1,7) in ('6400.00') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//============================================================================================
$jumlah_kegiatan_investasi=($penjualan_aset+$penjualan_aset_panjang+$penjualan_aset_lainnya)-($pembelian_aset+$pemeliharaan_aset+$pemeliharaan_aset_lainnya);
//============================================================================================

//Pinjaman Bank
$pinjaman_bank=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,7) in ('2500.00') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//hutang investasi
$hutang_investasi=getfromtable("select sum(jumlah) from kas_masuk where substring(kode_trans,1,7) in ('4201.00') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//pembayaran pinjaman bank
$pembayaran_pinjaman_bank=getfromtable("select sum(jumlah) from kas_keluar where substring(kode_trans,1,7) in ('2500.00') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");
//pembayaran hutang investasi
$pembayaran_hutang_investasi=getfromtable("select sum(jumlah) from kas_keluar where substring(kode_trans,1,7) in ('4202.00','4202.00') and (tanggal between '$ts_check_in1' and '$ts_check_in2')");

//============================================================================================
$jumlah_kegiatan_pembelanjaan=($pinjaman_bank+$hutang_investasi)-($pembayaran_pinjaman_bank+$pembayaran_hutang_investasi);
//============================================================================================

$grand_tot=$jumlah_kegiatan_operasi+$jumlah_kegiatan_investasi+$jumlah_kegiatan_pembelanjaan;




}

?>

<table align="center" WIDTH='65%' BORDER=0 CELLSPACING=1 CELLPADDING=2>
	<tr>
	    <td align="center" colspan=8><b><font size=3>RS. SEMUA BAIK</b></td>
	</tr>
	<tr>
	    <td align="center" colspan=8><b>LAPORAN ARUS KAS - METODE TIDAK LANGSUNG </b></td>
	</tr>
	<tr>
		<td align="right" colspan=8><b>&nbsp;</b></td>
	</tr>
	<tr>
		<td align="left" colspan=8><b>ARUS KAS DARI KEGIATAN OPERASI :</b></td>
	</tr>

	<tr>
	    <td align="left"colspan=8><b>Penyesuaian :</b></td>
	</tr>	
	<tr>
	    <td align="right"><b>+/+</b></td>
		<td align="left">Pendapatan dari Jasa Layanan & Penjualan</td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($pendapatan_usaha,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>	
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Pendapatan dari APBD</td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($apbd,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Pendapatan Hibah </td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($hibah,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Pendapatan Piutang </td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($piutang,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Pendapatan Persediaan & Perlengkapan</td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($persediaan,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Pendapatan Uang Muka Pasien </td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($uang_muka,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>	
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Pendapatan Usaha Lainnya </td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($usaha_lain,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>-/-</b></td>
		<td align="left">Biaya Layanan </td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($obat,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Biaya Umum dan Administrasi </td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($administrasi_umum,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Biaya Pembayaran Hutang </td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($pembayaran_hutang,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="right"><b>&nbsp;</b></td>
		<td align="left">Biaya Lainnya</td>
		<td align="right">Rp.</td>
		<td align="right"><u><?=number_format($biaya_lainnya,2,",",".")?></u></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
		<td align="left" colspan=4><b>Jumlah Arus Kas Netto dari Kegiatan Operasi </b></td>
		<td align="right"><b>Rp.</b></td>
		<td align="right"><b><?=number_format($jumlah_kegiatan_operasi,2,",",".")?></b></td>
		<td align="center" colspan=2><b>&nbsp;</b></td>
	</tr>
	<tr>
		<td align="right" colspan=8><b>&nbsp;</b></td>
	</tr>
	<tr>
		<td align="left" colspan=8><b>ARUS KAS DARI KEGIATAN INVESTASI :</b></td>
	</tr>
	<tr>
	    <td align="right"><b>+/+</b></td>
		<td align="left">Hasil Penjualan Aset Tetap</td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($penjualan_aset,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Hasil Penjualan Investasi Jangka Panjang </td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($penjualan_aset_panjang,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Hasil Penjualan Aset Lainnya </td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($penjualan_aset_lainnya,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="right"><b>-/-</b></td>
		<td align="left">Pembelian Aset Tetap</td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($pembelian_aset,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Pemeliharaan Investasi Jangka Panjang</td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($pemeliharaan_aset,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Pemeliharaan Aset Lainnya</td>
		<td align="right">Rp.</td>
		<td align="right"><u><?=number_format($pemeliharaan_aset_lainnya,2,",",".")?></u></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
		<td align="left" colspan=4><b>Jumlah Arus Kas Netto dari Kegiatan Investasi </b></td>
		<td align="right"><b>Rp.</b></td>
		<td align="right"><b><?=number_format($jumlah_kegiatan_investasi,2,",",".")?></b></td>
		<td align="center" colspan=2><b>&nbsp;</b></td>
	</tr>
	<tr>
		<td align="right" colspan=8><b>&nbsp;</b></td>
	</tr>
	<tr>
		<td align="left" colspan=8><b>ARUS KAS DARI KEGIATAN PEMBELANJAAN/PENDANAAN :</b></td>
	</tr>
	<tr>
	    <td align="right"><b>+/+</b></td>
		<td align="left">Penambahan Hutang Investasi</td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($hutang_investasi,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Perolehan Pinjaman</td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($pinjaman_bank,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="right"><b>-/-</b></td>
		<td align="left">Pembayaran Hutang Investasi</td>
		<td align="right">Rp.</td>
		<td align="right"><?=number_format($obat,2,",",".")?></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
	    <td align="center"><b>&nbsp;</b></td>
		<td align="left">Pembayaran Pinjaman</td>
		<td align="right">Rp.</td>
		<td align="right"><u><?=number_format($obat,2,",",".")?></u></td>
		<td align="center" colspan=4><b>&nbsp;</b></td>
	</tr>
	<tr>
		<td align="left" colspan=4><b>Jumlah Arus Kas Netto dari Kegiatan Pembelanjaan</b></td>
		<td align="right"><b>Rp.</b></td>
		<td align="right"><b><u><?=number_format($jumlah_kegiatan_pembelanjaan,2,",",".")?></u></b></td>
		<td align="center" colspan=2><b>&nbsp;</b></td>
	</tr>
	<tr>
		<td align="left" colspan=6><b>TOTAL KENAIKAN (PENURUNAN) NETTO DALAM KAS</b></td>
		<td align="right"><b>Rp.</b></td>
		<td align="right"><b><?=number_format($grand_tot,2,",",".")?></b></td>
	</tr>
</table>