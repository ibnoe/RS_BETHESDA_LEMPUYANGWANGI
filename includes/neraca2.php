<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 09-05-2004
   // sfdn, 11-05-2004


$PID = "neraca2";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

	
if($_GET["tc"] == "view") {
/*
*/
} else {
    
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > NERACA");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > NERACA");
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

//kas
$kas=getfromtable ("select (sum(debet)-(sum(kredit))) as kas from jurnal_umum where no_akun in ('1101.02','1101.01') and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//bank
$bank=getfromtable ("select (sum(kredit)) as kas from jurnal_umum where substring(no_akun,1,4)='1102' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//surat berharga
$surat_berharga=getfromtable("select (sum(kredit)) as kas from jurnal_umum where no_akun='1105.01' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//perlengkapan
$perlengkapan=getfromtable("select (sum(kredit)) as kas from jurnal_umum where no_akun='1105.03' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//persediaan
$persediaan=getfromtable("select (sum(kredit)) as kas from jurnal_umum where no_akun='1105.04' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//biaya dibayar dimuka
$biaya_dibayar_dimuka=getfromtable("select (sum(kredit)) as kas from jurnal_umum where no_akun='1105.05' and no_akun!='1104.05' (tanggal_akun between '$ts_check_in1' and '$ts_check_in2') or substring(no_akun,1,4)='1104' and no_akun!='1104.05' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//piutang lain-lain
$piutang=getfromtable("select (sum(debet)-sum(kredit)) as kas from jurnal_umum where substring(no_akun,1,4)='1118' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//investasi
$investasi=getfromtable("select (sum(kredit)) as kas from jurnal_umum where no_akun='1105.06' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//tanah
$tanah=getfromtable("select (sum(debet)-sum(kredit)) as kas from jurnal_umum where no_akun='1201.00' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//bangunan dan peralatan
$bangunan_peralatan=getfromtable("select (sum(kredit)) as kas from jurnal_umum where no_akun in ('1202.00','1208.00') and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//akumulasi penyusutan
$akumulasi_penyusutan=getfromtable("select (sum(debet)-sum(kredit)) as kas from jurnal_umum where no_akun in ('1209.00','1207.00','1205.00','1203.00') and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//aktiva lain-lain
$aktiva_lain=getfromtable("select (sum(debet)-sum(kredit)) as kas from jurnal_umum where no_akun not in ('1101.02','1101.01','1105.01','1105.03','1105.04','1105.05','1105.06','1201.00','1202.00','1208.00','1209.00','1207.00','1205.00','1203.00') and substring(no_akun,1,4) not in ('1102','1104','1118') and substring(no_akun,1,1) not in ('3','2','4','5') and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");




//hutang usaha
$hutang_usaha=getfromtable("select (sum(debet)-sum(kredit)) as kas from jurnal_umum where no_akun='4101.00' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//uang muka pasien
$uang_muka_pasien=getfromtable("select (sum(kredit)) as kas from jurnal_umum where no_akun='1104.05' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//hutang lain-lain
$hutang_lain=getfromtable("select (sum(debet)-sum(kredit)) as kas from jurnal_umum where substring(no_akun,1,4)= '4102' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//hutang investasi
$hutang_investasi=getfromtable("select (sum(debet)-sum(kredit)) as kas from jurnal_umum where substring(no_akun,1,4)= '4201' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//kewajiban lain-lain
$kewajiban_lain_lain=getfromtable("select (sum(debet)-sum(kredit)) as kas from jurnal_umum where substring(no_akun,1,1)= '3' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//modal dasar
$modal_dasar=getfromtable("select (sum(kredit)) as kas from jurnal_umum where substring(no_akun,1,2) in ('51','52') and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//modal sumbangan
$modal_sumbangan=getfromtable("select (sum(kredit)) as kas from jurnal_umum where substring(no_akun,1,2)='53' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')or no_akun='1105.06' and (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
//sisa hasil usaha
$layanan=getfromtable("select sum(a.jumlah) from rs00005 a, rs00001 b, rs00006 c
where c.poli=b.tc_poli and c.id=a.reg and a.reg=c.id and a.layanan not in ('99997') and a.kasir not in ('BYR','POT','ASK','BYI','BYD') and a.is_karcis='N' and b.tt = 'LYN' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2')");

$obat=getfromtable("select sum(a.jumlah) from rs00005 a, rs00001 b, rs00006 c
where c.poli=b.tc_poli and c.id=a.reg and a.reg=c.id and a.layanan='99997' and a.kasir not in ('BYR','POT','ASK','BYI','BYD') and a.is_karcis='N' and b.tt = 'LYN' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2')");

$lainnya=getfromtable("select sum(jumlah) from kas_masuk where (tanggal between '$ts_check_in1' and '$ts_check_in2') and substring(kode_trans,1,1) not in ('5')");

$cash_out=getfromtable("select sum(jumlah) from kas_keluar where (tanggal between '$ts_check_in1' and '$ts_check_in2') and substring(kode_trans,1,1) != '4'");

$hutang=getfromtable("select sum(jumlah) from kas_keluar where (tanggal between '$ts_check_in1' and '$ts_check_in2') and substring(kode_trans,1,1)='4'");

$tot_pendapatan=$layanan+$obat+$lainnya;
$tot_pengeluaran=$cash_out+$hutang;
$grand_total=$tot_pendapatan-$tot_pengeluaran;
//===============================================================

//aktiva  lancar+tetap
$total_aktiva_lancar1=$kas12+$bank+$surat_berharga+$perlengkapan+$persediaan+$biaya_dibayar_dimuka+$piutang;
$total_aktiva_tetap1=($investasi+$tanah+$bangunan_peralatan+$aktiva_lain)-$akumulasi_penyusutan;

//kewajiban
$jumlah_kewajiban_lancar=$hutang_usaha+$hutang_lain+$uang_muka_pasien;
$jumlah_kewajiban_taklancar=$hutang_investasi+$kewajiban_lain_lain;
$total_modal=$modal_dasar+$modal_sumbangan+$grand_total;
$jumlah_kewajiban=$jumlah_kewajiban_lancar+$jumlah_kewajiban_taklancar;

//AKUMULASI SISA HASIL
$sql1=getfromtable("select sum(kredit) from jurnal_umum where (tanggal_akun between '$ts_check_in1' and '$ts_check_in2')");
$akumulasi_sisa_hasil=$sql1-($total_modal+$jumlah_kewajiban);

//pengurangan kas
$kas_1=$sql1-($total_aktiva_lancar1+$total_aktiva_tetap1);
//jumlah total aktiva tetap & lancar
$total_aktiva_lancar=$kas_1+$bank+$surat_berharga+$perlengkapan+$persediaan+$biaya_dibayar_dimuka+$piutang;
$total_aktiva_tetap=($investasi+$tanah+$bangunan_peralatan+$aktiva_lain)-$akumulasi_penyusutan;

$total_modal=$modal_dasar+$modal_sumbangan+$grand_total+$akumulasi_sisa_hasil;


$total_aktiva=$total_aktiva_lancar+$total_aktiva_tetap;
$total_kewajiban=$jumlah_kewajiban+$total_modal;
$tgl=date("d F Y",strtotime($ts_check_in1));
$tgl2=date("d F Y",strtotime($ts_check_in2));
}

?>
<table align="center" WIDTH='75%' BORDER=0 CELLSPACING=1 CELLPADDING=2>
	<tr>
	    <td align="center" colspan=4><b><? subtitle_print($set_header[0]." ".$set_header[1]);?></b></td>
	</tr>
	<tr>
	    <td align="center" colspan=4><b>NERACA dari Tanggal <?=$tgl ?> s/d <?=$tgl2?></b></td>
	</tr>
	<tr>
	    <td align="center" colspan=4><b>&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
        
  </tr>
  <tr>
	    <td align="center" colspan=2 bgcolor="#20B2AA"><b>AKTIVA</b></td>
        <td align="center" colspan=2 bgcolor="#20B2AA"><b>KEWAJIBAN</b></td>
  </tr>
  <tr>
	<td colspan=2><b>AKTIVA LANCAR</b></td>
	<td colspan=2><b>BIAYA/BEBAN</b></td>
  </tr>
  <?// sub anak aktiva lancar dan beban ?>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;KAS </td>
		<td align="right"><?=number_format($kas_1,2,",",".") ?></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;HUTANG USAHA </td>
		<td align="right"><?=number_format($hutang_usaha,2,",",".") ?></td>
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;SURAT BERHARGA JK. PENDEK </td>
		<td align="right"><?=number_format($surat_berharga,2,",",".") ?></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;UANG MUKA PASIEN </td>
		<td align="right"><?=number_format($uang_muka_pasien,2,",",".") ?></td>
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;BANK </td>
		<td align="right"><?=number_format($bank,2,",",".") ?></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;HUTANG LAIN-LAIN</td>
		<td align="right"><?=number_format($hutang_lain1,2,",",".") ?></td>
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;PIUTANG PASIEN </td>
		<td align="right"><?=number_format($lainnya1,2,",",".") ?></td>
		<td align="left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JUMLAH KEWAJIBAN LANCAR  </b></td>
		<td align="right"><b><?=number_format($jumlah_kewajiban_lancar,2,",",".") ?></b></td>
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;PIUTANG LAIN-LAIN </td>
		<td align="right"><?=number_format($piutang,2,",",".") ?></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<td align="right"><b>&nbsp;</b></td>
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;PERSEDIAAN </td>
		<td align="right"><?=number_format($persediaan,2,",",".") ?></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
		<td align="left">&nbsp;</td>
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;BIAYA DIBAYAR DIMUKA </td>
		<td align="right"><?=number_format($biaya_dibayar_dimuka,2,",",".") ?></td>
		<td align="left" colspan=2><b>KEWAJIBAN TAK LANCAR</b></td>
		
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;PERLENGKAPAN </td>
		<td align="right"><?=number_format($perlengkapan,2,",",".") ?></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;HUTANG INVESTASI</td>
		<td align="right"><?=number_format($hutang_investasi,2,",",".") ?></td>
	  </tr>
	  <tr>
		<td align="left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL AKTIVA LANCAR </b></td>
		<td align="right"><b><?=number_format($total_aktiva_lancar,2,",",".") ?></b></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;KEWAJIBAN LAIN-LAIN </td>
		<td align="right"><?=number_format($kewajiban_lain_lain,2,",",".") ?></td>
	  </tr><tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="left">&nbsp;</td>
		<td align="left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JUMLAH KEWAJIBAN TAK LANCAR </b></td>
		<td align="right"><b><?=number_format($jumlah_kewajiban_taklancar,2,",",".") ?></b></td>
	  </tr>

  <tr>
	<td colspan=4><b>AKTIVA TETAP</b></td>
	
  </tr>
      <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;INVESTASI</td>
		<td align="right"><?=number_format($investasi,2,",",".") ?></td>
		<td align="left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL KEWAJIBAN </b></td>
		<td align="right"><b><?=number_format($jumlah_kewajiban,2,",",".") ?></b></td>
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;TANAH</td>
		<td align="right"><?=number_format($tanah,2,",",".") ?></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="left">&nbsp;</td>
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;BANGUNAN & PERALATAN</td>
		<td align="right"><?=number_format($bangunan_peralatan,2,",",".") ?></td>
		<td align="left" colspan=2><b>MODAL </b></td>
		
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;AKUMULASI PENYUSUTAN</td>
		<td align="right"><?=number_format($akumulasi_penyusutan,2,",",".") ?></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;MODAL DASAR </td>
		<td align="right"><?=number_format($modal_dasar,2,",",".") ?></td>
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;AKTIVA TETAP NETTO</td>
		<td align="right"><?=number_format($aktiva_tetap_neto,2,",",".") ?></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;MODAL SUMBANGAN </td>
		<td align="right"><?=number_format($modal_sumbangan,2,",",".") ?></td>
	  </tr>
	  <tr>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;AKTIVA LAIN-LAIN</td>
		<td align="right"><?=number_format($aktiva_lain,2,",",".") ?></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;AKUMULASI SISA HASIL </td>
		<td align="right"><?=number_format($akumulasi_sisa_hasil,2,",",".") ?></td>
	  </tr>
	  <TR>
		<td align="left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL AKTIVA TETAP</b></td>
		<td align="right"><b><?=number_format($total_aktiva_tetap,2,",",".") ?></b></td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;SISA USAHA PERIODE INI </td>
		<td align="right"><?=number_format($grand_total,2,",",".") ?></td>
	  </tr>
	  <TR>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="left">&nbsp;</td>
		<td align="left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL MODAL </b></td>
		<td align="right"><b><?=number_format($total_modal,2,",",".") ?></b></td>
	  </tr>
	  <TR>
		<td align="left">&nbsp;</td>
		<td align="left">&nbsp;</td>
		<td align="left">&nbsp; </td>
		<td align="left">&nbsp;</td>
	  </tr>
	  <TR>
		<td align="left"bgcolor="#20B2AA"><b>TOTAL AKTIVA</b></td>
		<td align="right"bgcolor="#20B2AA"><b><?=number_format($total_aktiva,2,",",".") ?></b></td>
		<td align="left"bgcolor="#20B2AA"><b>TOTAL KEWAJIBAN </b></td>
		<td align="right"bgcolor="#20B2AA"><b><?=number_format($total_kewajiban,2,",",".") ?></b></td>
	  </tr>
</table>