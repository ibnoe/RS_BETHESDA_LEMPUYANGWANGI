<?

$PID = "lap_pend_igd";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


if (!$GLOBALS['print']){
    	title("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan IGD");
		title_excel("lap_pend_igd&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mPASIEN=".$_GET["mPASIEN"]."");

 } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pendapatan IGD");
    }
	
	$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);


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
        						 "select tipe_pasien as tc,tipe_pasien as tdesc from rsv_lap_igd group by tipe_pasien Order By tdesc Asc;", $_GET["mPASIEN"],"");
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
		$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

	    } else {

		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->title("Dari Tanggal : ".$ts_check_in1);
		$f->title("s/d          : ".$ts_check_in2);
	    }

	    $f->title("Tipe Pasien  : ".$_GET["mPASIEN"]);
		$f->execute();
	}

    echo "<br>";

    
	$SQL = "select *, tindakan+obat+laboratorium+radiologi as tagihan, (tindakan+obat+laboratorium+radiologi)-(bayar_tunai+bayar_potongan+bayar_askes) as sisa
			from rsv_lap_igd 
			where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%".$_GET["mPASIEN"]."%'";

	@$r1 = pg_query($con,$SQL);
	@$n1 = pg_num_rows($r1);

	$max_row= 99999 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
	
	$SQL1 = "select tc, tdesc from rs00001 where tt='SBP' and tc !='000' order by tc";

	@$r1a = pg_query($con,$SQL1);
	@$n1a = pg_num_rows($r1a);

	$max_row= 99999 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
	
	$col=getFromTable("select count(tc) from rs00001 where tt='SBP' and tc !='000'");
?>
<table width="100%" border="1">
	<tr>
		<td rowspan="2" class="TBL_HEAD" align="center" width="3%">NO.</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="6%">TANGGAL</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="6%">NO. REG</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="6%">NO. MR</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="15%">NAMA</td>
		<td colspan="<?echo $col+3;?>" class="TBL_HEAD" align="center">RINCIAN TAGIHAN</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="7%">TOTAL TAGIHAN</td>
		<td colspan="4" class="TBL_HEAD" align="center">PEMBAYARAN</td>
	</tr>
	<tr>
	<?		$row1a=0;
			$ia= 1 ;
			$ja= 1 ;
			$last_id=1;			
			while (@$row1a = pg_fetch_array($r1a)){
				if (($ja<=$max_row) AND ($ia >= $mulai)){
					
					$noa=$ia; 	
					?>
					
		<td class="TBL_HEAD" align="center" width="7%"><?=$row1a["tdesc"] ?></td>
		
		<? ;$ja++;					
				}
				$ia++;
				//if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
		<td class="TBL_HEAD" align="center" width="7%">BHP</td>
		<td class="TBL_HEAD" align="center" width="7%">OBAT</td>
		<td class="TBL_HEAD" align="center" width="7%">PAKET</td>
		<td class="TBL_HEAD" align="center" width="7%">TUNAI</td>
		<td class="TBL_HEAD" align="center" width="7%">POTONGAN</td>
		<td class="TBL_HEAD" align="center" width="7%">PENJAMIN</td>
		<td class="TBL_HEAD" align="center" width="7%">PIUTANG PASIEN</td>
	</tr>
	
	<?		
			$tot1=0;
			$tot2=0;
			$tot3=0;
			$tot4=0;
			$tot5=0;
			$tot6=0;
			$tot7=0;
			$tot8=0;
			$tot9=0;
			
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					
					$no=$i; 	
					?>		
				 	<tr valign="top" class="<??>" > 
						<td class="TBL_BODY" align="center"><?=$no ?> </td>
			        	<td class="TBL_BODY" align="left"><?=$row1["tgl_entry"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["reg"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["mr_no"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["nama"] ?> </td>
						
						td class="TBL_BODY" align="left"><?=$row1["nm_poli"] ?> </td>
						
						<?
						$SQL2 = "select tc, tdesc from rs00001 where tt='SBP' and tc !='000' order by tc";
						@$r2 = pg_query($con,$SQL2);
						@$n2 = pg_num_rows($r2);
						
						$row2=0;
						$i2= 1 ;
						$j2= 1 ;
						$last_id2=1;			
						while (@$row2 = pg_fetch_array($r2)){
							if (($j2<=$max_row) AND ($i2 >= $mulai)){
								
								$no2=$i2; 	
						//================================
							$SQL3 = "select sum(a.tagihan) as jumlah 
									from rs00008 a
									left join rs00034 b on b.id=a.item_id::numeric
									left join rs00001 c on c.tc = b.sumber_pendapatan_id and c.tt='SBP'  
									where a.no_reg='".$row1["reg"]."' AND (a.trans_type='LTM') and c.tc like '%".$row2["tc"]."%'";
							
							@$r3 = pg_query($con,$SQL3);
							@$n3 = pg_num_rows($r3);
							
							$row3=0;
							$i3= 1 ;
							$j3= 1 ;
							$last_id3=1;			
							while (@$row3 = pg_fetch_array($r3)){
								if (($j3<=$max_row) AND ($i3 >= $mulai)){
									
									$no3=$i3; 	
								//================================
								?>
									<td class="TBL_BODY" align="right"><?=number_format($row3["jumlah"],2,",",".")  ?> </td>
								<?
								//================================						
										;$j3++;					
								}
								$i3++;	
							} 
						//================================						
									;$j2++;					
							}
							$i2++;	
						} 
						
						include ("tagihan");
						?>
						<!--td class="TBL_BODY" align="right"><?=number_format($row1["tindakan"],2,",",".")  ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["obat"],2,",",".") ?> </td-->
						<td class="TBL_BODY" align="right"><?=number_format($obat,2,",",".") ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($bhp,2,",",".") ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($paket,2,",",".") ?> </td>
						
						<td class="TBL_BODY" align="right"><?=number_format($total,2,",",".") ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["bayar_tunai"],2,",",".") ?> </td>	
						<td class="TBL_BODY" align="right"><?=number_format($row1["bayar_potongan"],2,",",".") ?> </td>	
						<td class="TBL_BODY" align="right"><?=number_format($row1["bayar_askes"],2,",",".") ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["sisa"],2,",",".") ?> </td>		
					</tr>	

					<?
					$tot6=$tot6+$row1["bayar_tunai"] ;
					$tot7=$tot7+$row1["bayar_potongan"] ;
					$tot8=$tot8+$row1["bayar_askes"] ;
					$tot9=$total - ($row1["bayar_tunai"] + $row1["bayar_potongan"] + $row1["bayar_askes"]) ;
					;$j++;					
				}
				$i++;
				//if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			$tot=$tot1+$tot2+$tot3+$tot4+$tot5;
			?>
			
					<tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="center" colspan="20" height="25" valign="middle">TOTAL </td>
						<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($tot6,2,",",".") ?>&nbsp;&nbsp;</td>
						<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($tot7,2,",",".") ?>&nbsp;&nbsp;</td>
						<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($tot8,2,",",".") ?>&nbsp;&nbsp;</td>
						<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($tot9,2,",",".") ?>&nbsp;&nbsp;</td>
					</tr>	
</table>
<br>
<b><i>Filter laporan berdasarkan Tanggal Bayar di kasir</i></b>