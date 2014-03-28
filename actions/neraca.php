<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 09-05-2004
   // sfdn, 11-05-2004


$PID = "lap_labarugi1";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if($_GET["tc"] == "view") {
/*
*/
} else {
    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > LAPORAN LABA RUGI");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > LAPORAN LABA RUGI");
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

    echo "<br>";
    

//---------------agung 04/2011---------------

$layanan=getfromtable("select sum(a.jumlah) from rs00005 a, rs00001 b, rs00006 c
where c.poli=b.tc_poli and c.id=a.reg and a.reg=c.id and a.layanan not in ('99997') and a.kasir not in ('BYR','POT','ASK','BYI','BYD') and a.is_karcis='N' and b.tt = 'LYN' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2')");

$obat=getfromtable("select sum(a.jumlah) from rs00005 a, rs00001 b, rs00006 c
where c.poli=b.tc_poli and c.id=a.reg and a.reg=c.id and a.layanan='99997' and a.kasir not in ('BYR','POT','ASK','BYI','BYD') and a.is_karcis='N' and b.tt = 'LYN' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2')");

$lainnya=getfromtable("select sum(jumlah) from kas_masuk where (tanggal between '$ts_check_in1' and '$ts_check_in2')");

$cash_out=getfromtable("select sum(jumlah) from kas_keluar where (tanggal between '$ts_check_in1' and '$ts_check_in2') and substring(kode_trans,1,1) != '4'");

$hutang=getfromtable("select sum(jumlah) from kas_keluar where (tanggal between '$ts_check_in1' and '$ts_check_in2') and substring(kode_trans,1,1)='4'");

$tot_pendapatan=$layanan+$obat+$lainnya;
$tot_pengeluaran=$cash_out+$hutang;
$grand_total=$tot_pendapatan-$tot_pengeluaran;
}

?>
<table align="center" CLASS=TBL_BORDER WIDTH='50%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" >Akun Perkiraan</td>
        <td class="TBL_HEAD" align="center" >Jumlah Pendapatan</td>
		<td class="TBL_HEAD" align="center" >Jumlah Pengeluaran</td>
    </tr>
	<tr>
		<td class="TBL_BODY" align="left" colspan="3"><?='PENDAPATAN'?></td>
    </tr>
	<tr>
		<td class="TBL_BODY" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?='Penjualan Obat'?></td>
		<td class="TBL_BODY" align="right"><?=number_format($obat,2,",",".")?></td>
		<td class="TBL_BODY" align="center"><? ?></td>
    </tr>
	<tr>
		<td class="TBL_BODY" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?='Pelayan'?></td>
		<td class="TBL_BODY" align="right"><?=number_format($layanan,2,",",".") ?></td>
		<td class="TBL_BODY" align="center"><? ?></td>
    </tr>
	<tr>
		<td class="TBL_BODY" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?='Pendapatan Lain-lain'?></td>
		<td class="TBL_BODY" align="right"><?=number_format($lainnya,2,",",".") ?></td>
		<td class="TBL_BODY" align="center"><? ?></td>
    </tr>
	<tr>
		<td align="RIGHT" bgcolor="#C1CDCD">JUMLAH PENDAPATAN</td>
		<td align="right" bgcolor="#C1CDCD"><?=number_format($tot_pendapatan,2,",",".")?></td>
		<td align="left" bgcolor="#C1CDCD"><?=''?></td>
    </tr>
	
	<tr>
		<td class="TBL_BODY" align="left" colspan="3"><?='PENGELUARAN'?></td>
    </tr>
	<tr>
      
      <td class="TBL_BODY" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?='Biaya dan Beban'?></td>
      <td class="TBL_BODY" align="right"></td>
	  <td class="TBL_BODY" align="right"><?=number_format($cash_out,2,",",".")?></td>
    </tr>
	<tr>
      
      <td class="TBL_BODY" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?='Pembayaran Hutang'?></td>
      <td class="TBL_BODY" align="right"></td>
	  <td class="TBL_BODY" align="right"><?=number_format($hutang,2,",",".")?></td>
    </tr>
	<tr>
      <td align="RIGHT" bgcolor="#C1CDCD">JUMLAH PENGELUARAN</td>
	  <td align="left" bgcolor="#C1CDCD"><?=''?></td>
	  <td align="right" bgcolor="#C1CDCD"><?=number_format($tot_pengeluaran,2,",",".")?></td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="RIGHT" colspan="2">GRAND TOTAL</td>
		<td class="TBL_HEAD" align="RIGHT"><?=number_format($grand_total,2,",",".")?></td>
    </tr>
</table>