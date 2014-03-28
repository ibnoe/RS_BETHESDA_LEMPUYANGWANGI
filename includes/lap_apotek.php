<?//Agung S.

$PID = "lap_apotek";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


if ($_GET[tc]=="view"){

if (!$GLOBALS['print']){
		title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Rincian Pendapatan Apotek");
		title_excel("lap_apotek&tc=view&jns=".$_GET[jns]."&t1=".$_GET[t1]."&tipe=".$_GET[tipe]."&ksr=".$_GET[ksr]."&urs=".$_GET[urs]."&tot=".$_GET[tot]."");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Rincian Pendapatan Apotek");
    }

if($_GET[ksr]=="BYD" ){
$kasir="APOTEK IGD";
}elseif($_GET[ksr]=="BYR"){
$kasir="APOTEK RJ";
}

if($_GET[tipe]=="320RJ_IGDU" ){
$JNS="PENJUALAN UMUM APOTEK IGD";
}elseif($_GET[tipe]=="320RJ_BYSU"){
$JNS="PENJUALAN UMUM APOTEK SWADANA";
}elseif($_GET[tipe]=="320RJ_IGD" ){
$JNS="PENJUALAN NON UMUM APOTEK IGD";
}elseif($_GET[tipe]=="320RJ_SWD"){
$JNS="PENJUALAN NON UMUM APOTEK SWADANA";
}

$r = pg_query($con,
	"select tanggal(to_date('".$_GET["t1"]."','YYYY-MM-DD'),0) as tgl");
$d = pg_fetch_object($r);
pg_free_result($r);
$bulan = $d->tgl;

$nm=getFromTable("select upper(nama) from rs99995 where uid='".$_GET["urs"]."'");

$f = new Form("");
	echo "<br>";
echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NAMA APOTEK </td>";
		echo "<td bgcolor='B0C4DE'><b>: $kasir </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> JENIS PENDAPATAN </td>";
		echo "<td bgcolor='B0C4DE'><b>: $JNS </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> TANGGAL </td>";
		echo "<td bgcolor='B0C4DE'><b>: $bulan </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NAMA KASIR PENERIMA </td>";
		echo "<td bgcolor='B0C4DE'><b>: $nm </td>";
	echo "</tr>";
echo "</table>";

    $f->execute();
    echo "<br>";
if($_GET[tipe]=="320RJ_IGDU" or $_GET[tipe]=="320RJ_BYCU" or $_GET[tipe]=="320RJ_BYSU" or $_GET[tipe]=="320RJ_BYAU"){	
$SQL = "select a.reg, b.nama, a.tgl_entry, a.kasir, a.layanan, a.user_id from rs00005 a, apotik_umum b where a.reg=b.code and a.tgl_entry='".$_GET["t1"]."' and a.kasir='".$_GET["ksr"]."' 
        and a.layanan ='".$_GET["tipe"]."' and a.user_id='".$_GET["urs"]."' and a.is_bayar='Y' ";
}else{
$SQL = "select a.reg, b.nama, a.tgl_entry, a.kasir, a.layanan, a.user_id from rs00005 a, rsv_pasien b where a.reg=b.id and a.tgl_entry='".$_GET["t1"]."' 
        and a.layanan ='".$_GET["tipe"]."' and a.user_id='".$_GET["urs"]."' and a.is_bayar='Y' 
		group by a.reg, b.nama, a.tgl_entry, a.kasir, a.layanan, a.user_id ";
}

	@$r1 = pg_query($con,$SQL);
	@$n1 = pg_num_rows($r1);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  

?>
<table width="100%">
	<tr>
		<td rowspan="2" class="TBL_HEAD" align="center" width="5%">NO.</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="10%">NO. REG</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="20%">NAMA</td>
		<td colspan="6" class="TBL_HEAD" align="center">RINCIAN</td>
	</tr>
	<tr>
		<td class="TBL_HEAD" align="center" >NAMA OBAT</td>
		<td class="TBL_HEAD" align="center" width="8%">JENIS OBAT</td>
		<td class="TBL_HEAD" align="center" width="5%">QTY</td>
		<td class="TBL_HEAD" align="center" width="10%">HARGA</td>
		<td class="TBL_HEAD" align="center" width="10%">R (JASA RESEP/RACIKAN)</td>
		<td class="TBL_HEAD" align="center" width="10%">JUMLAH HARGA</td>
	</tr>
	<?	
			
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
			        	<td class="TBL_BODY" align="left"><?=$row1["reg"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["nama"] ?> </td>
						<td class="TBL_BODY" colspan="6">
						<?
						$SQL2 = "select a.no_reg, a.tanggal_entry, case when a.trans_type='OB1' then 'Obat Jadi' else 'Obat Racikan' end as jns_obt,a.item_id, b.obat, a.referensi, a.qty, a.harga, a.tagihan 
								from rs00008 a, rsv0004 b
								where a.item_id=b.id::text and a.no_reg='".$row1["reg"]."' and a.tanggal_entry='".$row1["tgl_entry"]."' and a.trans_form like '%320RJ%' ";
								
								@$r2 = pg_query($con,$SQL2);
								@$n2 = pg_num_rows($r2);

								$max_row2= 30 ;
								$mulai2 = $HTTP_GET_VARS["rec"] ;	
								if (!$mula2i){$mulai2=1;}  
								
								$totbaru2= 0;
								$row2=0;
								$i2= 1 ;
								$j2= 1 ;
								$last_id2=1;			
								while (@$row2 = pg_fetch_array($r2)){
									if (($j2<=$max_row2) AND ($i2 >= $mulai2)){
										if($row2["no_reg"]==$row1["reg"]){
										?>
										
											<tr valign="top">
												<td class="TBL_BODY" colspan="3">
												<td class="TBL_BODY" align="left"><?=$row2["obat"]  ?> </td>
												<td class="TBL_BODY" align="left"><?=$row2["jns_obt"] ?> </td>
												<td class="TBL_BODY" align="left"><?=$row2["qty"] ?> </td>
												<td class="TBL_BODY" align="right"><?=number_format($row2["harga"],2,",",".") ?> </td>
												<td class="TBL_BODY" align="right"><?=number_format($row2["referensi"],2,",",".") ?> </td>
												<td class="TBL_BODY" align="right"><?=number_format($row2["tagihan"],2,",",".") ?> </td>
											</tr>
										
										<?
										$totbaru2=$totbaru2+$row2["tagihan"] ;
										}
										
								;$j2++;	}
								
							$i2++;	} 
							
						?>
						</td>												
					</tr>	

					<?;$j++;					
				}
				$i++;
				//if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="center" colspan="8" height="25" valign="middle"><b> TOTAL </b></td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($_GET["tot"],2,",",".") ?>&nbsp;&nbsp;</b></td>
					</tr>	
</table>
<?


}else{
   if (!$GLOBALS['print']){
    	//title("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Per Kasir");
		title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Apotek");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pendapatan Apotek");
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

		
		$f->selectArray("mAPOTEK_J", "Jenis Penjualan",Array("" => "","U" => "PENJUALAN OBAT UMUM", "P" => "PENJUALAN OBAT NON UMUM"),
                     $_GET[mAPOTEK_J], "onChange='document.Form1.submit();'; ");
		if ($_GET[mAPOTEK_J]=="P"){
		$f->selectArray("mAPOTEK", "Nama Apotek",Array(""=>"", "320RJ_IGD" => "APOTEK IGD",  "320RJ_SWD" => "APOTEK SWADANA"),
                     $_GET[mAPOTEK], " ");			 
		}elseif ($_GET[mAPOTEK_J]=="U"){
		$f->selectArray("mAPOTEK", "Nama Apotek",Array(""=>"", "320RJ_IGDU" => "APOTEK IGD",  "320RJ_SWDU" => "APOTEK SWADANA"),
                     $_GET[mAPOTEK], " ");
		}
		
		$f->selectSQL("mKASIR", "User","select '' as nama,'' as nama union ".
        						 "select a.user_id, b.nama from rs00005 a, rs99995 b where a.user_id=b.uid group by a.user_id, b.nama ", $_GET["mKASIR"], "");
		
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
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
	    }
		
		$f->selectSQL("mKASIR", "User","select '' as nama,'' as nama union ".
        						 "select a.user_id, b.nama from rs00005 a, rs99995 b where a.user_id=b.uid group by a.user_id, b.nama ", $_GET["mKASIR"], "disabled");
		
		$f->selectArray("mAPOTEK_J", "Jenis Penjualan",Array("" => "","U" => "PENJUALAN OBAT UMUM", "P" => "PENJUALAN OBAT NON UMUM"),
                     $_GET[mAPOTEK_J], "disabled ");
		if ($_GET[mAPOTEK_J]=="P"){
		$f->selectArray("mAPOTEK", "Nama Apotek",Array(""=>"", "320RJ_IGD" => "APOTEK IGD",  "320RJ_SWD" => "APOTEK RJ"),
                     $_GET[mAPOTEK], "disabled");			 
		}elseif ($_GET[mAPOTEK_J]=="U"){
		$f->selectArray("mAPOTEK", "Nama Apotek",Array("320RJ_IGDU"=>"", "APOTEK UMUM IGD" => "APOTEK UMUM IGD", "320RJ_SWDU" => "APOTEK UMUM RJ"),
                     $_GET[mAPOTEK], "disabled");
		}
	    $f->execute();
	}


    echo "<br>";


if($_GET[mAPOTEK]=="320RJ_IGD" or $_GET[mAPOTEK]=="320RJ_IGDU" ){
$kasir="BYG";
}elseif($_GET[mAPOTEK]=="320RJ_SWD" or $_GET[mAPOTEK]=="320RJ_SWDU" ){
$kasir="BYR";
}


$SQL = "select  to_char(tgl_entry, 'DD MON YYYY') as tgl_reg,tgl_entry, sum(jumlah) as jumlah, user_id  
	from rs00005 
	where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and is_obat='Y' and is_bayar='Y' and layanan='".$_GET[mAPOTEK]."'  and
	user_id like '%".$_GET[mKASIR]."%'  
	group by tgl_entry, user_id 
	order by tgl_entry ";


	@$r1 = pg_query($con,$SQL);
	@$n1 = pg_num_rows($r1);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  

?>
<table width="100%">
	<tr>
		<td class="TBL_HEAD" align="center"width="5%">NO.</td>
		<td class="TBL_HEAD" align="center">TANGGAL PEMBAYARAN</td>
		<td class="TBL_HEAD" align="center">JUMLAH PENERIMAAN</td>
		<td class="TBL_HEAD" align="center" width="20%">NAMA KASIR</td>
		<td class="TBL_HEAD" align="center" width="15%">LIHAT<br>RINCIAN</td>
	</tr>
	<?	
			$totbaru= 0;
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
			        	<td class="TBL_BODY" align="left"><?=$row1["tgl_reg"] ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["jumlah"] ,2,",",".")?></td>
						<td class="TBL_BODY" align="left">&nbsp;&nbsp;<?=$row1["user_id"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&jns=".$_GET[mAPOTEK_J]."&t1=".$row1["tgl_entry"]."&tipe=".$_GET[mAPOTEK]."&ksr=$kasir&urs=".$row1["user_id"]."&tot=".$row1["jumlah"]."'>".icon("view","View")."</A>";?> </td>
					</tr>	

					<?
					$totbaru=$totbaru+$row1["jumlah"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}
			} 
			?>
			
					<tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="center" colspan="2" height="25" valign="middle"><b> TOTAL </b></td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($totbaru,2,",",".") ?>&nbsp;&nbsp;</b></td>
                        <td class="TBL_HEAD" align="right" valign="middle"><b>&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b>&nbsp;</b></td>
					</tr>	
</table>
<?
}
?>