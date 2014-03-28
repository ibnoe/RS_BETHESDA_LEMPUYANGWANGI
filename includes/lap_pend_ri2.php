<?

$PID = "lap_pend_ri2";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if($_GET["tc"] == "view") {
    title("Rincian Pendapatan Rawat Inap");

    if ($_GET["e"] == "Y") {
        $unit = "Rawat Jalan";
    } elseif  ($_GET["e"] == "N"){
        $unit = "IGD";
    } elseif ($_GET["e"] == "I"){
        $unit = "Rawat Inap";
    } else {
        $unit = "Semua";
    }

    $pasien = getFromTable(
               "select tdesc from rs00001 ".
               "where tc = '".$_GET["u"]."' and tt='JEP'");

    $r = pg_query($con, "select tanggal(to_date(".$_GET["f"].",'YYYYMMDD'),3) as tgl");
    $d = pg_fetch_object($r);
    pg_free_result($r);

    $bulan = $d->tgl;
    $f = new Form("");
    $f->subtitle("Tanggal    : $bulan");
    $f->subtitle("U n i t    : $unit");
    $f->subtitle("Tipe Pasien : $pasien");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    $r2 = pg_query($con,
              "select sum(a.qty * a.harga) as jum ".
              "from rs00008 a ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "where b.rawat_inap='".$_GET["e"]."' and ".
              "     to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     a.trans_type='OB1' and b.tipe = '".$_GET["u"]."'");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t->SQL = "select c.mr_no,c.nama,a.no_reg, ".
              "     e.obat, a.qty, a.harga, sum(a.qty * a.harga) as tagih ".
              "from rs00008 a  ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "     left join rs00002 c ON b.mr_no = c.mr_no ".
              "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
              "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
              "where ".
              " to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     b.rawat_inap ='".$_GET["e"]."' and ".
              "     a.trans_type = 'OB1' ".
              "group by c.mr_no, c.nama, a.no_reg, e.obat, a.qty, a.harga";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColFormatMoney[6] = "%!+#2n";
    $t->ColHeader = array("MR.NO","NAMA","NO.REG","NAMA O B A T","QTY","HARGA","Rp.");
    $t->ColFooter[6] =  number_format($d2->jum,2);
    //$t->ShowSQLExecTime = true;
    //$t->ShowSQL = true;

    $t->execute();

} else {
    if (!$GLOBALS['print']){
    	title("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Rawat Inap");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pendapatan Rawat Inap");
    }
    //title("LAPORAN PENDAPATAN RAWAT INAP");
	title_excel("lap_pend_ri2&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mPASIEN=".$_GET["mPASIEN"]."");
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
        $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"]+1,$_GET["tanggal2Y"]));
    	$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
    	}

    	$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
        						 "select tc, tdesc from rs00001 ".
        						 "where tt='JEP' and tc != '000' Order By tdesc Asc;", $_GET["mPASIEN"],$ext);
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
        $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"]+1,$_GET["tanggal2Y"]));
    	$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
    	}

    	$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
        						 "select tc, tdesc from rs00001 ".
        						 "where tt='JEP' and tc != '000' ", $_GET["mPASIEN"],"disabled");
    	$f->execute();
	}	
    	
    	echo "<br>";

$SQL = "
select z.tgl_entry,x.mr_no, z.reg, x.nama, 
(select d.bangsal || ' / ' || c.bangsal || ' / ' || e.tdesc || ' / ' || b.bangsal 
from rs00010 as a
join rs00012 as b on a.bangsal_id = b.id
join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000'
join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000'
join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
where a.no_reg= z.reg and a.awal='1'
)
as bangsal,
(select sum(f.jumlah) as jumlah from rs00005 f where f.reg=z.reg AND f.is_karcis='N' AND f.is_obat='N' AND f.kasir='RIN' AND f.layanan='99996') as akomodasi,
(select sum(g.tagihan) as jumlah from rs00008 g where g.no_reg=z.reg AND g.trans_type='LTM'  and g.trans_form not in ('p_laboratorium','p_radiologi')) as layanan,
(select sum(h.tagihan) as jumlah from rs00008 h where h.no_reg=z.reg AND h.trans_type='LTM' AND h.trans_form='p_laboratorium') as laborat,
(select sum(i.tagihan) as jumlah from rs00008 i where i.no_reg=z.reg AND i.trans_type='LTM' AND i.trans_form='p_radiologi') as radiologi,
(select sum(j.jumlah) as jumlah from rs00005 j where j.reg=z.reg AND j.is_karcis='N' AND j.is_obat='Y' AND j.kasir in ('IGD','RJN','RJL','RIN') AND j.layanan in ('99997', '99995', '320RJ_SWD','320RJ_IGD') )   as obat,
(select sum(k.jumlah) as jumlah from rs00005 k where k.reg=z.reg AND k.is_karcis='N' AND k.is_obat='N' AND k.kasir='BYI') as jml_bayar,
(select sum(l.jumlah) as jumlah from rs00005 l where l.reg=z.reg AND l.is_karcis='N' AND l.is_obat='N' AND l.kasir='POT') as jml_potongan,
(select sum(m.jumlah) as jumlah from rs00005 m where m.reg=z.reg AND m.is_karcis='N' AND m.is_obat='N' AND m.kasir='ASK') as jml_askes
from rsv_kasir z
left join rs00006 y ON z.reg = y.id
left join rs00002 x ON y.mr_no = x.mr_no
where z.kasir='BYI' and (z.tgl_entry between '$ts_check_in1' and '$ts_check_in2') and z.tipe like '%".$_GET["mPASIEN"]."%'
group by  z.tgl_entry,x.mr_no, z.reg, x.nama, z.jumlah
 ";
//(select sum(f.tagihan) as jumlah from rs00008 f where f.no_reg=z.reg AND f.trans_type='POS' ) as akomodasi,
//(select sum(f.jumlah) as jumlah from rs00005 f where f.reg=z.reg AND f.is_karcis='N' AND f.is_obat='N' AND f.kasir='99996') as akomodasi,
	@$r1 = pg_query($con,$SQL);
	@$n1 = pg_num_rows($r1);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
?>
<table width="100%">
	<tr>
		<td rowspan="2" class="TBL_HEAD" align="center" width="3%">NO.</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="6%">NO. REG</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="6%">NO. MR</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="15%">NAMA</td>
		<td rowspan="2" class="TBL_HEAD" align="center" width="15%">BANGSAL</td>
		<td colspan="9" class="TBL_HEAD" align="center">RINCIAN</td>
	</tr>
	<tr>
		<td class="TBL_HEAD" align="center" >PELAYANAN</td>
		<td class="TBL_HEAD" align="center" >OBAT</td>
		<td class="TBL_HEAD" align="center" >LABORATORIUM</td>
		<td class="TBL_HEAD" align="center" >RADIOLOGI</td>
		<td class="TBL_HEAD" align="center" >AKOMODASI</td>
		<td class="TBL_HEAD" align="center" >JUMLAH TAGIHAN</td>
		<td class="TBL_HEAD" align="center" >JUMLAH BAYAR</td>
		<td class="TBL_HEAD" align="center" >JUMLAH POTONGAN</td>
		<td class="TBL_HEAD" align="center" >JUMLAH DIBAYAR ASKES</td>
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
						<td class="TBL_BODY" align="left"><?=$row1["mr_no"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["nama"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["bangsal"] ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["layanan"],2,",",".")  ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["obat"],2,",",".") ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["laborat"],2,",",".") ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["radiologi"],2,",",".") ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["akomodasi"],2,",",".") ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format(($row1["layanan"]+$row1["obat"]+$row1["laborat"]+$row1["radiologi"]+$row1["akomodasi"]),2,",",".") ?> </td>	
						<td class="TBL_BODY" align="right"><?=number_format($row1["jml_bayar"],2,",",".") ?> </td>	
						<td class="TBL_BODY" align="right"><?=number_format($row1["jml_potongan"],2,",",".") ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["jml_askes"],2,",",".") ?> </td>		
					</tr>	

					<?
					$tot1=$tot1+$row1["layanan"] ;
					$tot2=$tot2+$row1["obat"] ;
					$tot3=$tot3+$row1["laborat"] ;
					$tot4=$tot4+$row1["radiologi"] ;
					$tot5=$tot5+$row1["akomodasi"] ;
					$tot6=$tot6+$row1["jml_bayar"] ;
					$tot7=$tot7+$row1["jml_potongan"] ;
					$tot8=$tot8+$row1["jml_askes"] ;
					;$j++;					
				}
				$i++;
				//if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			$tot=$tot1+$tot2+$tot3+$tot4+$tot5;
			?>
			
					<tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="center" colspan="5" height="25" valign="middle"><b> TOTAL </b></td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($tot1,2,",",".") ?>&nbsp;&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($tot2,2,",",".") ?>&nbsp;&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($tot3,2,",",".") ?>&nbsp;&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($tot4,2,",",".") ?>&nbsp;&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($tot5,2,",",".") ?>&nbsp;&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($tot,2,",",".") ?>&nbsp;&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($tot6,2,",",".") ?>&nbsp;&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($tot7,2,",",".") ?>&nbsp;&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($tot8,2,",",".") ?>&nbsp;&nbsp;</b></td>
					</tr>	
</table>
<br>
<b><i>Filter laporan berdasarkan Tanggal Bayar di kasir</i></b>
<?
}

?>
