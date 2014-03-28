<?php
	// sfdn, 24-12-2006
session_start();

require_once("../lib/dbconn.php");
require_once("../lib/terbilang.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/setting.php");


?>

<HTML>

<HEAD>
<TITLE>::: Bukti Pembayaran Apotek :::</TITLE>
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

$ROWS_PER_PAGE     = 30;


$tgl_skrg=date('d-m-Y',time());

$sql="SELECT a.obat as nama,b.harga,b.qty, (b.tagihan-(b.harga*b.qty)) as jasa, b.tagihan   
	from rs00015 a, rs00008 b 
	where a.id::text=b.item_id and b.no_reg='".$_GET["rg"]."' and trans_type='OB1'";

$sql2 = "SELECT 'Racikan Obat' as nama,sum(b.harga) as harga, '' as qty , sum((b.tagihan-(b.harga*b.qty))) as jasa, sum(b.tagihan) as tagihan
		from rs00015 a, rs00008 b 
		where a.id::text=b.item_id and b.no_reg='".$_GET["rg"]."' and trans_type='RCK' ";
		
@$r1 = pg_query($con,$sql);
@$n1 = pg_num_rows($r1);

@$r2 = pg_query($con,$sql2);
@$n2 = pg_num_rows($r2);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
	
	if ($_GET[tt] == "igd") {
      $loket = "IGD";
	  $PID1 = "320RJ_IGD";
   } elseif ($_GET[tt] == "swd") {
      $loket = "SWADAYA";
	  $PID1 = "320RJ_SWD";
   } elseif ($_GET[tt] == "cdm") {
      $loket = "CINDUO MATO";
	  $PID1 = "320RJ_CDM";
   } else {
      $loket = "AKSES";
	  $PID1 = "320RJ_ASK";
   }
   
   
   //========== cek bayar/blm
	$blm_byr=getFromTable("select sum(jumlah) from rs00005 where is_obat='Y' and kasir in ('SWD','ASK','IGD','CDM') and is_bayar='N' and reg='".$_GET["rg"]."'");
	$sdh_byr=getFromTable("select sum(jumlah) from rs00005 where is_obat='Y' and kasir in ('BYR') and is_bayar='Y' and reg='".$_GET["rg"]."'");
	$pot_byr=getFromTable("select sum(jumlah) from rs00005 where is_obat='Y' and kasir in ('POT') and is_bayar='Y' and reg='".$_GET["rg"]."'");
	$sisa_tgh=$blm_byr - ($sdh_byr + $pot_byr);
	//=========================
		
		
?>
<br>
<table frame="border" align="center" WIDTH='100%' border='0'>
	<tr>
		<td align="center" colspan=4><font size=3><b> APOTEK <?= $loket;?> <br> RSUD Dr. ACHMAD MOCHTAR <br> BUKITTINGGI</b></font></td>
	</tr>
		<tr>
		<td colspan=4><font size=1 align="center"><b>&nbsp;</b></font></td>
	</tr>
	<tr>
		<td align="center" colspan=4><font size=2 ><b><u>BUKTI PEMBAYARAN</u></b></font></td>
	</tr>
	</tr>
		<tr>
		<td colspan=4><font size=1 align="center"><b>&nbsp;</b></font></td>
	</tr>
	</tr>
		<tr>
		<td align="left" width="10%"><font size=1 ><b>Tanggal :</b></font></td>
		<td align="left"><font size=1 ><b><?=$tgl_skrg?></b></font></td>
		<td align="right"><font size=1 ><b>No. Reg :</b></font></td>
		<td align="left"width="10%"><font size=1 ><b><?=$_GET["rg"]?></b></font></td>
	</tr>

</table>
<br>
<table frame="border" align="center" border=1 WIDTH='100%'>
	<tr valign="top" class="TBL_HEAD" >
		<td align="center" valign="middle"><font size=1 ><b>Nama Obat</b></font></td>
		<td align="center" width="15%" valign="middle"><font size=1 ><b>Harga</b></font></td>
		<td align="center" width="15%" valign="middle"><font size=1 ><b>Qty</b></font></td>
		<td align="center" width="15%" valign="middle"><font size=1 ><b>Jasa<br>Resep/Racikan</b></font></td>
		<td align="center" width="15%" valign="middle"><font size=1 ><b>Jumlah Harga</b></font></td>
	</tr>
<?	
			$totbaru= 0;
			$totulang= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$no=$i 	
					?>		
				 	<tr > 
			        	<td align="left"><font size=1 ><?=$row1["nama"] ?> </font></td>
						<td align="right"><font size=1 ><?=number_format($row1["harga"] ,2,",",".") ?> </font></td>
						<td align="center"><font size=1 ><?=$row1["qty"] ?> </font></td>
						<td align="right"><font size=1 ><?=number_format($row1["jasa"] ,2,",",".")?></font></td>
						<td align="right"><font size=1 ><?=number_format($row1["tagihan"] ,2,",",".")?></font></td>
					</tr>	
					<?
					$totulang=$totulang+$row1["tagihan"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>

<?	
			$totbaru2= 0;
			$totulang2= 0;
			$row2=0;
			$i2= 1 ;
			$j2= 1 ;
			$last_id2=1;			
			while (@$row2 = pg_fetch_array($r2)){
				if (($j2<=$max_row) AND ($i2 >= $mulai)){
					$no=$i 	
					?>		
				 	<tr > 
			        <td align="left" colspan=3><font size=1 ><?=$row2["nama"] ?> </font></td>
					<td align="right"><font size=1 ><?=number_format($row2["jasa"] ,2,",",".")?></font></td>
					<td align="right"><font size=1 ><?=number_format($row2["tagihan"] ,2,",",".")?></font></td>
					</tr>	
					<?
					$totulang2=$totulang2+$row2["tagihan"] ;
					?>
					<?;$j2++;					
				}
				$i2++;
				if ($last_id2 < $row2->no_reg){$last_id2=$row2->no_reg;}		
			} 
			
			$total=$totulang+$totulang2;
			?>
		
					<tr valign="top" class="TBL_HEAD" >  
			        	<td align="right" colspan="4" height="25" valign="middle"><font size=1 > TOTAL TAGIHAN</font></td>
						<td align="right" valign="middle"><font size=1 >Rp. <?=number_format($total,2,",",".")?></font></td>
					</tr>
					<tr valign="top" class="TBL_HEAD" >  
			        	<td align="right" colspan="4" height="25" valign="middle"><font size=1 > POTONGAN </font></td>
						<td align="right" valign="middle"><font size=1 >Rp. <?=number_format($pot_byr,2,",",".")?></font></td>
					</tr>
					<tr valign="top" class="TBL_HEAD" >  
			        	<td align="right" colspan="4" height="25" valign="middle"><font size=1 > BAYAR </font></td>
						<td align="right" valign="middle"><font size=1 >Rp. <?=number_format($sdh_byr,2,",",".")?></font></td>
					</tr>
					<tr valign="top" class="TBL_HEAD" >  
						<td align="left" colspan="5" valign="middle"><font size=1 >TERBILANG :  <i><?php $y=terbilang($sdh_byr);
		echo strtoupper($y);?> RUPIAH</i></font></td>
					</tr>
					
</table>
<br>
<table align="center" WIDTH='100%'>
	<tr>
		<td align="center"colspan=4><font size=1 ><b>Terimakasih atas kedatangannya !</b></font></td>
	</tr>		
</table>

<?
echo "\n<script language='JavaScript'>\n";
echo "function cetakaja(tag) {\n";
echo "    sWin = window.open('index2.php?tag=' + tag, 'xWin',".
     " 'width=200,height=200,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";
?>

<SCRIPT LANGUAGE="JavaScript">

printWindow();

</script>

</body>
</html>
