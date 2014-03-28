<? 
$PID = "lap_pend_total2";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

    //------------------------------------------------------- mulai
    if (!$GLOBALS['print']){
    	title("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Total");
	title_excel("lap_pend_total2&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mPASIEN=".$_GET["mPASIEN"]."");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pendapatan Total");
		
    }
    
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
/*
	include("tanggalan");
   if (!empty($_GET[mPASIEN])) {
    	$add = " c.tipe = '".$_GET[mPASIEN]."'";
   	} else {
      	$add = " c.tipe != '".$_GET[mPASIEN]."'";
   	}
*/   
	if (!$GLOBALS['print']) {
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

		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
        						 "select tc , tdesc from rs00001 where tt='JEP' and tc='006' or tt='JEP' and tc='007' or tt='JEP' and tc='008' or tt='JEP' and tc='010' or tt='JEP' and tc='011' or tt='JEP' and tc='012'", ($_GET["mPASIEN"]) ? $_GET[mPASIEN] : "006","" );
    
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
		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
        						 "select tc , tdesc from rs00001 where tt='JEP' and tc='006' or tt='JEP' and tc='007' or tt='JEP' and tc='008' or tt='JEP' and tc='010' or tt='JEP' and tc='011' or tt='JEP' and tc='012'", ($_GET["mPASIEN"]) ? $_GET[mPASIEN] : "006","" );
	    $f->execute();
	}
	
    echo "<br>";
$kd_poli=getFromTable("select tdesc from rs00001 where tt='LYN' and tt='".$_GET[mPASIEN]."' ");
//============================================================================
//total layanan RJ
$total_lay_rj=getfromtable("select sum(jumlah) from rsv_lap_tot where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe='".$_GET[mPASIEN]."' ");
//total layanan IGD
$total_lay_igd=getfromtable("select sum(radiologi + laboratorium + tindakan) from rsv_lap_igd where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%$kd_poli%' ");
//total layanan RI
$total_lay_ri=getfromtable("select sum(radiologi + laborat + layanan + akomodasi ) from rsv_lap_ri1 where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tdesc like '%$kd_poli%' ");
$sub_tot1=$total_lay_rj+$total_lay_igd+$total_lay_ri;

//============================================================================

$total_askes_rj=getfromtable("select sum(bayar_askes) from rsv_lap_rj where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%$kd_poli%' ");
$total_askes_ri=getfromtable("select sum(jml_askes) from rsv_lap_ri1 where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%$kd_poli%' ");
$total_askes_igd=getfromtable("select sum(bayar_askes) from rsv_lap_igd where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%$kd_poli%' ");

$total_askes=$total_askes_ri+$total_askes_rj+$total_askes_igd;

$total_pot_rj=getfromtable("select sum(bayar_potongan) from rsv_lap_rj where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%$kd_poli%' ");
$total_pot_ri=getfromtable("select sum(jml_potongan) from rsv_lap_ri1 where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%$kd_poli%' ");
$total_pot_igd=getfromtable("select sum(bayar_potongan) from rsv_lap_igd where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%$kd_poli%' ");

$total_pot=$total_pot_ri+$total_pot_rj+$total_pot_igd;


//============================================================================
//total obat klinik RJ
$total_obat_swd=getfromtable("select sum(a.jumlah) from rs00005 a
where a.kasir='RJL' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2') and is_obat='Y' and is_bayar='Y' and layanan='320RJ_SWD'");
//total obat klinik igd
$total_obat_igd=getfromtable("select sum(a.jumlah) from rs00005 a
where a.kasir='IGD' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2') and is_obat='Y' and is_bayar='Y' and layanan='320RJ_IGD'");
//total obat umum RJ
$total_obat_swdu=getfromtable("select sum(a.jumlah) from rs00005 a
where a.kasir='RJL' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2') and is_obat='Y' and is_bayar='Y' and layanan='320RJ_SWDU'");
//total obat umum igd
$total_obat_igdu=getfromtable("select sum(a.jumlah) from rs00005 a
where a.kasir='IGD' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2') and is_obat='Y' and is_bayar='Y' and layanan='320RJ_IGDU'");
//total potongan obat umum RJ
$total_pot_swdu=getfromtable("select sum(a.jumlah) from rs00005 a
where a.kasir='POT' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2') and is_obat='Y' and is_bayar='Y' and layanan='320RJ_SWDU'");
//total potongan obat umum igd
$total_pot_igdu=getfromtable("select sum(a.jumlah) from rs00005 a
where a.kasir='POT' and (a.tgl_entry between '$ts_check_in1' and '$ts_check_in2') and is_obat='Y' and is_bayar='Y' and layanan='320RJ_IGDU'");


//total layanan per poli
$sql="select nm_poli as tdesc,sum(tagihan) as jumlah from rsv_lap_tot where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe = '".$_GET[mPASIEN]."' group by tdesc";
@$r1 = pg_query($con,$sql);
@$n1 = pg_num_rows($r1);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
			
title_print("");
?>
<TABLE>
	<tr >     	
		<td colspan="2"><b>A. PENDAPATAN NON-OBAT</b></td>
	</tr>
		<tr >   
			<td width="6%"> </td>
			<td><b>1. RAWAT JALAN</b></td>
		</tr>
	
		<?
			$tot1= 0;
			$totulang= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$no=$i 	
					?>		
				 	<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?=$row1["tdesc"] ?> </td>
						<td align="center">:</td>
						<td align="right"><?=number_format($row1["jumlah"] ,2,",",".")?></td>
					</tr>	

				<?
					$tot1=$tot1+$row1["jumlah"] ;
					;$j++;					
				}
				$i++;
				//if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
					<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL POTONGAN RJ</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($total_pot_rj,2,",",".")?></i></u></b></td>
					</tr>
					<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL DIBAYAR PENJAMIN RJ</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($total_askes_rj,2,",",".")?></i></u></b></td>
					</tr>
				 	<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL PEMBAYARAN TUNAI LAYANAN RJ</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($tot1 - ($total_pot_rj + $total_askes_rj),2,",",".")?></i></u></b></td>
					</tr>	
			<tr></tr>
		<tr >   
			<td width="6%"> </td>
			<td><b>2. IGD</b></td>
			<td align="center">:</td>
			<td align="right" ><?=number_format($total_lay_igd,2,",",".")?></td>
		</tr>
					<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL POTONGAN IGD</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($total_pot_igd,2,",",".")?></i></u></b></td>
					</tr>
					<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL DIBAYAR PENJAMIN IGD</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($total_askes_igd,2,",",".")?></i></u></b></td>
					</tr>
				 	<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL PEMBAYARAN TUNAI LAYANAN IGD</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($total_lay_igd - ($total_pot_igd + $total_askes_igd),2,",",".")?></i></u></b></td>
					</tr>	
		<tr>
		<td width="6%"> </td>
			<td><b>3. RAWAT INAP</b></td>
			<td align="center">:</td>
			<td align="right"><?=number_format($total_lay_ri,2,",",".")?></td>
		</tr>
					<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL POTONGAN RI</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($total_pot_ri,2,",",".")?></i></u></b></td>
					</tr>
					<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL DIBAYAR PENJAMIN RI</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($total_askes_ri,2,",",".")?></i></u></b></td>
					</tr>
				 	<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL PEMBAYARAN TUNAI LAYANAN RI</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($total_lay_ri - ($total_pot_ri + $total_askes_ri),2,",",".")?></i></u></b></td>
					</tr>	
					<? $sub_tot1=($total_lay_igd - ($total_pot_igd + $total_askes_igd) + ($tot1 - ($total_pot_rj + $total_askes_rj)) +($total_lay_ri - ($total_pot_ri + $total_askes_ri)));
					?>
		<tr valign="top" class="<? ?>" > 
			<td align="center"><? ?> </td>
			<td align="Right" bgcolor="	#717D7D"><b><font color="#ffffff">SUB TOTAL LAYANAN</b></td>
			<td align="right" bgcolor="	#717D7D" colspan="2"><b><font color="#ffffff"><?=number_format($sub_tot1,2,",",".")?></b></td>
		</tr>	
	<tr >     	
		<td colspan="2"><b>B. PENDAPATAN OBAT APOTEK KLINIK</b></td>
	</tr>
		<tr>
			<td width="6%"> </td>
			<td><b>1. APOTEK IGD</b></td>
			<td align="center">:</td>
			<td align="right"><?=number_format($total_obat_igd,2,",",".")?></td>
		</tr>
		<tr>
			<td width="6%"> </td>
			<td><b>2. APOTEK RJ</b></td>
			<td align="center">:</td>
			<td align="right"><?=number_format($total_obat_swd,2,",",".")?></td>
		</tr>
	
	<tr >     	
		<td colspan="2"><b>C. PENDAPATAN OBAT APOTEK UMUM</b></td>
	</tr>
		<tr>
			<td width="6%"> </td>
			<td><b>1. APOTEK IGD</b></td>
			<td align="center">:</td>
			<td align="right"><?=number_format($total_obat_igdu,2,",",".")?></td>
		</tr>
		<tr>
			<td width="6%"> </td>
			<td><b>2. APOTEK RJ</b></td>
			<td align="center">:</td>
			<td align="right"><?=number_format($total_obat_swdu,2,",",".")?></td>
		</tr>
		
					<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL POTONGAN APOTEK RI</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($total_pot_wsdu,2,",",".")?></i></u></b></td>
					</tr>
					<tr valign="top" class="<? ?>" > 
						<td align="center"><? ?> </td>
			        	<td align="Right"><b><u><i>TOTAL POTONGAN APOTEK IGD</i></u></b></td>
						<td align="center"><b><u><i>:</i></u></b></td>
						<td align="right"><b><u><i><?=number_format($total_pot_igdu,2,",",".")?></i></u></b></td>
					</tr>
		<? $total_pendapatan=$sub_tot1 + (( $total_obat_igd + $total_obat_swd + $total_obat_igdu + $total_obat_swdu) - ( $total_pot_igdu + $total_pot_swdu)) ; ?>
	<tr > 
		<td align="center"><? ?> </td>
		<td align="right" bgcolor="	#717D7D"><b><font color="#ffffff">TOTAL PENDAPATAN</b></td>
		<td align="right" bgcolor="	#717D7D"colspan="2"><b><font color="#ffffff"><?=number_format($total_pendapatan,2,",",".")?></b></td>
	</tr>
</table>
<br>
<hr>
<b><i>Note: Pendapatan Apotek tidak berdasarkan Tipe Pasien</i></b>
<br>
<b><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pencarian berdasarkan tanggal Pembayaran di Kasir</i></b>
<hr>