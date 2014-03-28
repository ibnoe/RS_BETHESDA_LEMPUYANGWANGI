<?	

$PID = "lap_kso";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/setting.php"); 	
	


    $f = new Form($SC, "GET", "NAME=Form1");
     
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	if(!$GLOBALS['print']){
		if (!isset($_GET['mTAHUN'])) {
                        $mBULAN = date("m", time());
			$mTAHUN = date("Y", time());
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(tanggal_reg,'mm'), TO_CHAR(tanggal_reg,'mm') from rs00006 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $mTAHUN,$ext);
            
    	} else {
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(tanggal_reg,'mm'), TO_CHAR(tanggal_reg,'mm') from rs00006 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $_GET["mTAHUN"],$ext);
            				 
    	}
		$f->submit ("TAMPILKAN");
		$f->execute();
	} else {
		if (!isset($_GET['mTAHUN'])) {
                        $mBULAN = date("m", time());
			$mTAHUN = date("Y", time());
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(tanggal_reg,'mm'), TO_CHAR(tanggal_reg,'mm') from rs00006 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $mTAHUN,$ext);
        
    	} else {
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(tanggal_reg,'mm'), TO_CHAR(tanggal_reg,'mm') from rs00006 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $_GET["mTAHUN"],$ext);
            
    	}
		$f->execute();
	}

				if ($_GET["mTAHUN"] % 4 == 0){
                    if ($mBULAN == '04' or $mBULAN == '06' or $mBULAN == '09' or $mBULAN == '11'){
                        $bulanini = 30;
                    }elseif ($mBULAN == '02'){
                        $bulanini = 29;
                    } else {
                        $bulanini = 31;
                    }
                } else {
                    if ($mBULAN == '04' or $mBULAN == '06' or $mBULAN == '09' or $mBULAN == '11'){
                        $bulanini = 30;
                    }elseif ($mBULAN == '02'){
                        $bulanini = 28;
                    } else {
                        $bulanini = 31;
                    }
                }
                //$tgl = 1;
				for ($tgl=1;$tgl<=$bulanini;$tgl++) 
				$tgl1 = $tgl - 1;
				
if ($_GET['mTAHUN']){
$r = pg_query($con, "select tanggal('".$_GET['mTAHUN']."-".$_GET['mBULAN']."-01'::date,0) as tanggal1, tanggal('".$_GET['mTAHUN']."-".$_GET['mBULAN']."-$tgl1'::date,0) as tanggal2 ");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);


$rj=getFromTable("select count(id) from rsv_daftar_kso where (tanggal_reg between '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-01' and '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-$tgl1') and rawat_inap='Y'");
$ri=getFromTable("select count(id) from rsv_daftar_kso where (tanggal_reg between '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-01' and '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-$tgl1') and rawat_inap='I'");
$igd=getFromTable("select count(id) from rsv_daftar_kso where (tanggal_reg between '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-01' and '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-$tgl1') and rawat_inap='N'");
}

$jml_tagihan=($rj * 5000) + ($igd * 5000) + ($ri * 15000);
$ppn=((($rj * 5000) + ($igd * 5000) + ($ri * 15000))*10/100);
$total=$jml_tagihan + $ppn;

?> 							 
<table width="50%" class="TBL_BORDER" border="1">
<tr><td align="center"><h1>LAPORAN TAGIHAN KSO</h1></td>
</tr>
<tr>
	<td>
<table width="100%" border="0">
	<tr>
		<td class="TBL_BODY2">TANGGAL</td>
		<td class="TBL_BODY2" colspan="3">: <? echo $d->tanggal1; ?> s/d <? echo $d->tanggal2; ?></td>
	</tr>
	<tr>
		<td class="TBL_BODY2">JUMLAH PASIEN RJ</td>
		<td class="TBL_BODY2">: <? echo $rj; ?> Orang</td>
		<td class="TBL_BODY2">X Rp. <?=number_format(5000,2,",",".")?> / Orang</td>
		<td class="TBL_BODY2">= Rp. <?=number_format($rj * 5000,2,",",".")?> </td>
	</tr>
	<tr>
		<td class="TBL_BODY2">JUMLAH PASIEN IGD</td>
		<td class="TBL_BODY2">: <? echo $igd; ?> Orang</td>
		<td class="TBL_BODY2">X Rp. <?=number_format(5000,2,",",".")?> / Orang</td>
		<td class="TBL_BODY2">= Rp. <?=number_format($igd * 5000,2,",",".")?></td>
	</tr>
	<tr>
		<td class="TBL_BODY2">JUMLAH PASIEN RI</td>
		<td class="TBL_BODY2">: <? echo $ri; ?> Orang</td>
		<td class="TBL_BODY2">X Rp. <?=number_format(15000,2,",",".")?> / Orang</td>
		<td class="TBL_BODY2">= Rp. <?=number_format($ri * 15000,2,",",".")?></td>
	</tr>
	<tr>
		<td class="TBL_BODY2" colspan="3" align="right"><b>JUMLAH TAGIHAN</b></td>
		<td class="TBL_BODY2"><b>= Rp. <?=number_format($jml_tagihan,2,",",".")?> </b></td>
	</tr>
	<tr>
		<td class="TBL_BODY2" colspan="3" align="right"><b>PPN 10.00 %</b></td>
		<td class="TBL_BODY2"><b>= Rp. <?=number_format($ppn,2,",",".")?> </b></td>
	</tr>
	<tr>
		<td class="TBL_BODY2" colspan="3" align="right"><b>TOTAL TAGIHAN</b></td>
		<td class="TBL_BODY2"><b>= Rp. <?=number_format($total,2,",",".")?> </b></td>
	</tr>
</table>
	</td>
</tr>
</table>
<br>
<br>
<?  if ($_GET["mTAHUN"])
{
?>
<table>
<tr>
<td align="center">Cetak Invoice</td>
</tr>
<tr>
<td align="center"> <a href="javascript: cetakinvoice(<? echo $_GET['mTAHUN']."-".$_GET['mBULAN'];?>)" ><img src="images/cetak.gif" border="0"></a></td>
</tr></table>
<?
}

echo "\n<script language='JavaScript'>\n";
echo "function cetakinvoice(tag) {\n";
echo "    sWin = window.open('includes/cetak.invoice.php?rg='+tag+'&mBULAN=".$_GET['mBULAN']."&mTAHUN=".$_GET['mTAHUN']."', 'xWin',".
     " 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";
?>