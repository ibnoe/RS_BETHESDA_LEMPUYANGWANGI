<? // 30/12/2003
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 29-04-2004
   // sfdn, 30-04-2004
   // sfdn, 09-05-2004
   // sfdn, 18-05-2004
   // sfdn, 02-06-2004
   // tokit aja, 15-09-2004
   // sfdn, 17-12-2006
   // sfdn, 24-12-2006
   // sfdn, 25-12-2006
   // sfdn, 26-12-2006
   // Agung Sunandar 15:42 11/07/2012 menambahkan untuk pelayanan paket
$PID = "transaksi_pasien";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");

    if ($_SESSION[uid] == "kasir2") {
       $what = "RAWAT INAP";
       $sqlayanan = "NOT LIKE '%IGD%'";	
    } elseif ($_SESSION[uid] == "kasir1") {
       $what = "RAWAT JALAN";
       $sqlayanan = "NOT LIKE '%IGD%'";
    } else {
       $what = "IGD";
       $sqlayanan = "LIKE '%IGD%'";
    }

if (isset($_GET["v"])) {
    $r = pg_query($con, 
	    "select a.id,tanggal(tanggal_reg,3) as tgl_reg_str, tanggal_reg as tgl_reg, ".
            "		b.nama,b.alm_tetap,b.nrp_nip, ".
	    "	case when a.rawat_inap ='I' then 'Rawat Inap' ".
	    "		when a.rawat_inap='Y' then 'Rawat Jalan' else 'IGD' end as rawatan, ".
            "	c.tdesc as pasien, a.id, a.mr_no, a.rawat_inap ".
            "from rs00006 a ".
            "   left join rs00002 b ON a.mr_no = b.mr_no ".
            "   left join rs00001 c ON (a.tipe = c.tc and c.tt='JEP') ".
            "where a.id = '".$_GET["v"]."' ");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    // header info transaksi 
    echo '<br/>';
    if (!$GLOBALS['print']){
    	//title("<img src='icon/keuangan-2.gif' align='absmiddle' > INFO TRANSAKSI");	
		title("Data Pemeriksaan Kesehatan Pasien");
	    //title_excel("transaksi_pasien&v=".$_GET["v"]."&t1=".$_GET["t1"]."&t2=".$_GET["t2"]."");
    } else {
    	//title("<img src='icon/keuangan.gif' align='absmiddle' > INFO TRANSAKSI");
    }
    
	$akhir = getFromTable("select to_char(CURRENT_TIMESTAMP,'DD-MON-YYYY HH24:MI:SS') as tgl");
	//Bangsal untuk RI
	$r1 = pg_query($con,
            "select to_char(c.ts_check_in, 'DD-MON-YYYY HH24:MI:SS'), ".
            "   to_char(d.ts_calc_stop,'DD-MON-YYYY HH24:MI:SS'), g.bangsal||' / '||f.bangsal||' / '||e.bangsal as bangsal0, ".
            "   f.bangsal as bangsal1, g.bangsal as bangsal2, h.tdesc, ".
            "   extract(day from case when d.ts_calc_stop is null ".
            "       then current_timestamp else d.ts_calc_stop ".
            "           end - d.ts_calc_start) as hari, f.harga, ".
            "   to_char((extract(day from case when d.ts_calc_stop is null ".
            "       then current_timestamp else d.ts_calc_stop ".
            "           end - d.ts_calc_start)*f.harga),'999999999') as biaya ".
            "from rs00006 a ".
            "   left join rs00010 c ON a.id = c.no_reg and c.id = ".
            "       (select min(id) from rs00010 where no_reg = a.id) ".
            "   left join rs00010 d ON a.id = d.no_reg and d.id = ".
            "       (select max(id) from rs00010 where no_reg = a.id) ".
            "   left join rs00012 e ON d.bangsal_id = e.id ".
            "   left join rs00012 f ON substr(e.hierarchy,1,6)||'000000000' = f.hierarchy ".
            "   left join rs00012 g ON substr(e.hierarchy,1,3)||'000000000000' = g.hierarchy ".
            "   left join rs00001 h ON f.klasifikasi_tarif_id = h.tc and h.tt='KTR' ".
            "where a.id = '".$_GET["v"]."'");
	
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
	
	//Poli untuk RJ
	$r2 = pg_query($con,
            "select a.tdesc from rs00001 a ".
            "   left join rs00006 b ON a.tc::text = b.poli::text and a.tt='LYN' ".
            "where b.id = '".$_GET["v"]."'");
	
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
	
	echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='50%'>";
    $f = new ReadOnlyForm();
    $f->text("Nomor MR",$d->mr_no);
    $f->text("Nama Pasien",$d->nama);
    $f->text("Alamat", $d->alm_tetap);
    $f->text("Pekerjaan / Golongan", $d->nrp_nip);
    $f->text("Tipe Pasien", $d->pasien);
    $f->execute();
    
    echo "</td><td valign=top width='50%'>";
    $f = new ReadOnlyForm();
    $f->text("No. Registrasi", $d->id);
    //$f->text("Nomor Registrasi", formatRegNo($d->id). " - " .
   // 	getFromTable("select count(mr_no) from rs00006 where mr_no = '$d->mr_no'"));
    $f->text("Tanggal Registrasi", $d->tgl_reg_str);
	if($d->rawatan == "Rawat Inap"){
    $f->text("U n i t", $d->rawatan,$d1->bangsal0);
	$f->text("Bangsal", $d1->bangsal0);
    }else{
	$f->text("U n i t", $d->rawatan);
	$f->text("P o l i", $d2->tdesc);
	}
    $f->execute();
    echo "</td></tr></table><br>";
	// end header

// PAKET LAYANAN
$cek=getFromTable("select count(no_reg) from rs00008 where trans_type = 'LTM' and no_reg='".$_GET["v"]."' and referensi != 'P' ");

if ($cek > 0){
    //title_print("");
    $f = new Form("");
    $f->title1("Data Tindakan");
    $f->execute();
    // agung
    if ($_GET["t1"] > "2011-11-17" or $_GET["t2"] > "2011-11-17" ){
        $from = "from rs00008 a, rs00034 e,rs00034 f , rs99995 b";
            }elseif ($_GET["t1"] < "2011-11-17" or $_GET["t2"] < "2011-11-17" ){
        $from = "from rs00008 a, rs00034b e,rs00034b f ";
            }
            
    $r2 = pg_query($con, "select sum(tagihan) as jum $from ".
        "where a.trans_type='LTM' and to_number(a.item_id,'999999999999')= e.id ".
        "	and a.no_reg = '".$_GET["v"]."' and ".
        "	(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and ".
        "	case when length(rtrim(e.hierarchy,'0'))=12 ".
	"		  then substr(rtrim(e.hierarchy,'0'),1,9) = substr(rtrim(f.hierarchy,'0'),1,9) ".
        "	     when length(rtrim(e.hierarchy,'0'))=9 ".
	"		  then substr(rtrim(e.hierarchy,'0'),1,6) = substr(rtrim(f.hierarchy,'0'),1,6) ".
        "	     when length(rtrim(e.hierarchy,'0'))=6 ".
	"		  then substr(rtrim(e.hierarchy,'0'),1,3) = substr(rtrim(f.hierarchy,'0'),1,3) ".
        "	else substr(rtrim(e.hierarchy,'0'),1,12) = substr(rtrim(f.hierarchy,'0'),1,12) ".
	"	end ".
	"and f.is_group='Y' ");

	// --- eof 26-12-2006 ---

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);


    // LAYANAN
    $t = new PgTable($con, "100%");
        
    $t->SQL =
        "select f.layanan as desc2,e.layanan as desc1,a.tagihan as tagihan,b.nama,
		case 	when a.trans_form='p_peny_dalam' then 'POLIKLINIK INTERNE'
				when a.trans_form='p_saraf' then 'POLIKLINIK SARAF'
				when a.trans_form='p_mata' then 'POLIKLINIK MATA'
				when a.trans_form='p_jantung' then 'POLIKLINIK JANTUNG'
				when a.trans_form='p_laboratorium' then 'LAYANAN LABORATORIUM'
				when a.trans_form='p_radiologi' then 'LAYANAN RADIOLOGI'
				when a.trans_form='p_psikiatri' then 'POLIKLINIK JIWA'
				when a.trans_form='p_ginekologi' then 'KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (GINEKOLOGI)'
				when a.trans_form='p_bedah' then 'POLIKLINIK BEDAH'
				when a.trans_form='p_fisioterapi' then 'UNIT REHABILITASI MEDIK'
				when a.trans_form='p_igd' then 'INSTALASI GAWAT DARURAT'
				when a.trans_form='p_kulit_kelamin' then 'POLIKLINIK KULIT DAN KELAMIN'
				when a.trans_form='p_paru' then 'POLIKLINIK PARU'
				when a.trans_form='p_anak' then 'POLIKLINIK ANAK'
				when a.trans_form='p_gigi' then 'POLIKLINIK GIGI DAN MULUT'
				when a.trans_form='p_operasi' then 'LAYANAN OPERASI'
				else 'Bangsal Rawatan'
			end as poli_input $from 
			where a.user_id=b.uid and a.trans_type='LTM' and to_number(a.item_id,'999999999999')= e.id 
			and a.no_reg = '".$_GET["v"]."' and a.referensi !='P' and 
			(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and 
			f.is_group='Y' "; 

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    //$t->ColFormatNumber[2] = 2;
    $t->ColHeader = array("WAKTU ENTRY","NAMA LAYANAN/TINDAKAN","Rp.","USER INPUT");
    $t->ColFooter[2] =  number_format($d2->jum,2);
    $t->DisableScrollBar = true;
    $t->DisableStatusBar = true;
    //$t->execute();
    echo "<br>";

	$sql =
        "select a.layanan, f.qty ||' '|| g.tdesc as qty, f.tagihan,  to_char(f.tanggal_trans,'dd-mm-yyyy') as tanggal_trans,  to_char(f.waktu_entry, 'HH24:MM:SS') as waktu_entry, h.nama, f.user_id 
				from rs00034 a 
				left join rs00008 f on to_number(f.item_id,'999999999999') = a.id and f.trans_type = 'LTM' and f.referensi != 'P'
				left join rs00001 g on a.satuan_id = g.tc and g.tt = 'SAT' 
				left join rs00017 h on f.no_kwitansi::numeric = h.id::numeric
				where f.no_reg = '$_GET[v]' 
				order by  tanggal_trans desc"; 
	
	@$r1 = pg_query($con,$sql);
	@$n1 = pg_num_rows($r1);

	$max_row= 99999 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  

?>
<table width="100%">
	<tr>
		<td class="TBL_HEAD" align="center"width="5%">NO.</td>
		<td class="TBL_HEAD" align="center" width="15%">WAKTU ENTRY</td>
		<td class="TBL_HEAD" align="center">NAMA LAYANAN/TINDAKAN</td>
                <td class="TBL_HEAD" align="center" width="20%">PEMBERI TINDAKAN</td>
		<td class="TBL_HEAD" align="center" width="10%">Rp.</td>
		
		<td class="TBL_HEAD" align="center" width="15%">USER INPUT</td>
	</tr>
	<?	
			$totbaru2= 0;
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
			        	<td class="TBL_BODY" align="left"><?=$row1["tanggal_trans"] ?>&nbsp;&nbsp;<?=$row1["waktu_entry"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["layanan"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["nama"] ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["tagihan"] ,0,"",".")?></td>
						<td class="TBL_BODY" align="left">&nbsp;&nbsp;<?=$row1["user_id"] ?> </td>
					</tr>	

					<?
					$totbaru2=$totbaru2+$row1["tagihan"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="center" colspan="3" height="25" valign="middle"><b> TOTAL </b></td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($totbaru2,2,",",".") ?></b></td>
                        <td class="TBL_HEAD" align="right" valign="middle"><b>&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b>&nbsp;</b></td>
					</tr>	
</table>
<?
}
// PAKET LAYANAN
$cek=getFromTable("select count(no_reg) from rs00008 where trans_type = 'LTM' and no_reg='".$_GET["v"]."' and referensi='P' ");

if ($cek > 0){
$f = new Form("");
    $f->title1("Data Tindakan");
    $f->execute();
    $from = "from rs00008 a, rs99996 e,rs00034 f , rs99995 b";

	$sql =
        "select distinct to_char(a.tanggal_entry,'dd/mm/yyyy')||' '||to_char(waktu_entry,'HH24:MI:SS') as waktu, e.description||' ('||c.nama||') ' as desc1,a.tagihan as tagihan, to_char(a.waktu_entry, 'HH24:MM:SS') as waktu_entry,
		case 	when a.trans_form='p_peny_dalam' then 'POLIKLINIK INTERNE'
				when a.trans_form='p_saraf' then 'POLIKLINIK SARAF'
				when a.trans_form='p_mata' then 'POLIKLINIK MATA'
				when a.trans_form='p_jantung' then 'POLIKLINIK JANTUNG'
				when a.trans_form='p_laboratorium' then 'LAYANAN LABORATORIUM'
				when a.trans_form='p_radiologi' then 'LAYANAN RADIOLOGI'
				when a.trans_form='p_psikiatri' then 'POLIKLINIK JIWA'
				when a.trans_form='p_ginekologi' then 'KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (GINEKOLOGI)'
				when a.trans_form='p_bedah' then 'POLIKLINIK BEDAH'
				when a.trans_form='p_fisioterapi' then 'UNIT REHABILITASI MEDIK'
				when a.trans_form='p_igd' then 'INSTALASI GAWAT DARURAT'
				when a.trans_form='p_kulit_kelamin' then 'POLIKLINIK KULIT DAN KELAMIN'
				when a.trans_form='p_paru' then 'POLIKLINIK PARU'
				when a.trans_form='p_anak' then 'POLIKLINIK ANAK'
				when a.trans_form='p_gigi' then 'POLIKLINIK GIGI DAN MULUT'
				when a.trans_form='p_operasi' then 'LAYANAN OPERASI'
				else 'BANGSAL RAWATAN'
		end as poli_input,b.nama
		from rs00008 a
		left join rs99996 e ON to_number(a.item_id,'999999999999') = e.id 
		left join rs00017 c ON a.no_kwitansi::text = c.id::text
		left join rs99995 b ON (a.user_id like '%b.uid%' or a.user_id = b.uid)
		where a.referensi ='P' and  a.trans_type='LTM' and a.no_reg = '".$_GET["v"]."' and (a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') ";  
		
	@$r1 = pg_query($con,$sql);
	@$n1 = pg_num_rows($r1);

	$max_row= 9999 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
?>
<table width="100%">
	<tr>
		<td class="TBL_HEAD" align="center" width="5%">NO.</td>
		<td class="TBL_HEAD" align="center" width="15%">WAKTU ENTRY</td>
		<td class="TBL_HEAD" align="center">NAMA LAYANAN/TINDAKAN</td>
		<td class="TBL_HEAD" align="center" width="10%">Rp.</td>
		<td class="TBL_HEAD" align="center" width="20%">POLI INPUT</td>
		<td class="TBL_HEAD" align="center" width="15%">USER INPUT</td>
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
			        	<td class="TBL_BODY" align="left"><?=$row1["waktu"] ?> &nbsp;&nbsp; <?=$row1['waktu_entry']?></td>
						<td class="TBL_BODY" align="left"><?=$row1["desc1"] ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["tagihan"] ,2,",",".")?></td>
						<td class="TBL_BODY" align="left">&nbsp;&nbsp;<?=$row1["poli_input"] ?> </td>
						<td class="TBL_BODY" align="left">&nbsp;&nbsp;<?=$row1["nama"] ?> </td>
					</tr>	

					<?
					$totbaru=$totbaru+$row1["tagihan"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="center" colspan="3" height="25" valign="middle"><b> TOTAL </b></td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($totbaru,2,",",".") ?></b></td>
                        <td class="TBL_HEAD" align="right" valign="middle"><b>&nbsp;</b></td>
						<td class="TBL_HEAD" align="right" valign="middle"><b>&nbsp;</b></td>
					</tr>	
</table>
<?
}	
    // OBAT BHP
	$totalsdhbayar = 0.00;
	$totalblmbayar = 0.00;
	$rec1 = getFromTable (
    		"select count(id) from rs00008 ".
			"where trans_type = 'BHP' and no_reg ='".$_GET["v"]."'");
			
	if ($rec1 > 0) {
		$SQL2 =		"select e.obat, a.harga, a.qty, a.referensi, case when a.is_bayar='Y' then 'LUNAS' else 'BELUM LUNAS' end as status, to_char(a.tanggal_entry,'dd-mm-yyyy') as tanggal_entry, to_char(a.waktu_entry, 'HH24:MM:SS') as waktu_entry,".
					"a.tagihan as jum ".
					"from rs00008 a ".
					"	left join  rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
					"where a.trans_type='BHP' and ".
					"	a.no_reg = '".$_GET["v"]."' and ".
					"	(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') ";
		$f = new Form("");
		$f->title1("Data Obat BHP");
		$f->execute();
		
		@$r1 = pg_query($con,$SQL2);
		@$n1 = pg_num_rows($r1);

		$max_row= 99999 ;
		$mulai = $HTTP_GET_VARS["rec"] ;	
		if (!$mulai){$mulai=1;}  
?>
<table width="100%">
	<tr>
		<td class="TBL_HEAD" align="center"width="5%">NO.</td>
		<td class="TBL_HEAD" align="center" width="15%">WAKTU ENTRY</td>
		<td class="TBL_HEAD" align="center">NAMA OBAT</td>
		<td class="TBL_HEAD" align="center" width="10%">HARGA SATUAN</td>
		<td class="TBL_HEAD" align="center" width="5%">QTY</td>
		<td class="TBL_HEAD" align="center" width="10%">JASA OBAT</td>
		<td class="TBL_HEAD" align="center" width="10%">STATUS</td>
		<td class="TBL_HEAD" align="center" width="10%">TOTAL(Rp.)</td>
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
			        	<td class="TBL_BODY" align="left"><?=$row1["tanggal_entry"] ?> &nbsp;&nbsp; <?=$row1["waktu_entry"] ?></td>
			        	<td class="TBL_BODY" align="left"><?=$row1["obat"] ?> &nbsp;</td>
                                        <td class="TBL_BODY" align="right"><?=number_format($row1["harga"] ,2,",",".")?></td>
                                        <td class="TBL_BODY" align="center"><?=$row1["qty"] ?> </td>						
                                        <td class="TBL_BODY" align="right"><?=number_format($row1["referensi"] ,2,",",".")?></td>						
                                        <td class="TBL_BODY" align="left"><?=$row1["status"] ?> </td>						
                                        <td class="TBL_BODY" align="right"><?=number_format($row1["jum"] ,2,",",".")?></td>
					</tr>	

					<?
									
					$totbaru = $totbaru + $row1["jum"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="center" colspan="6" height="25" valign="middle"><b> TOTAL </b></td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($totbaru,2,",",".") ?></b></td>
					</tr>	
</table>
<?		
    } 
    // OBAT
	$totalsdhbayar = 0.00;
	$totalblmbayar = 0.00;
	$rec1 = getFromTable (
    		"select count(id) from rs00008 ".
			"where trans_type = 'OB1' and no_reg ='".$_GET["v"]."'");
			
	if ($rec1 > 0) {
		$SQL2 =		"select e.obat, a.harga, a.qty, a.referensi, case when a.is_bayar='Y' then 'LUNAS' else 'BELUM LUNAS' end as status, to_char(a.tanggal_entry,'dd-mm-yyyy') as tanggal_entry, to_char(a.waktu_entry, 'HH24:MM:SS') as waktu_entry,".
					"a.tagihan as jum ".
					"from rs00008 a ".
					"	left join  rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
					"where a.trans_type='OB1' and ".
					"	a.no_reg = '".$_GET["v"]."' and ".
					"	(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') ";
		$totalsdhbayar = getFromTable(
					"select sum(tagihan) as jumlah ".
					"from rs00008 ".
					"where trans_type='OB1' and ".
					"	no_reg = '".$_GET["v"]."' and ".
					"(tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') ");
			// ---- end of 24-12-2006 ----
		$f = new Form("");
		$f->title1("Data Obat");
		$f->execute();
		
		@$r1 = pg_query($con,$SQL2);
		@$n1 = pg_num_rows($r1);

		$max_row= 99999 ;
		$mulai = $HTTP_GET_VARS["rec"] ;	
		if (!$mulai){$mulai=1;}  
?>
<table width="100%">
	<tr>
		<td class="TBL_HEAD" align="center" width="5%">NO.</td>
		<td class="TBL_HEAD" align="center" width="15%">WAKTU ENTRY</td>
		<td class="TBL_HEAD" align="center">NAMA OBAT</td>
		<td class="TBL_HEAD" align="center" width="10%">HARGA SATUAN</td>
		<td class="TBL_HEAD" align="center" width="5%">QTY</td>
		<td class="TBL_HEAD" align="center" width="10%">JASA OBAT</td>
		<td class="TBL_HEAD" align="center" width="10%">TOTAL(Rp.)</td>
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
			        	<td class="TBL_BODY" align="left"><?=$row1["tanggal_entry"] ?> &nbsp;&nbsp; <?=$row1["waktu_entry"] ?> </td>
			        	<td class="TBL_BODY" align="left"><?=$row1["obat"] ?> </td>
                                        <td class="TBL_BODY" align="right"><?=number_format($row1["harga"] ,2,",",".")?></td>
                                        <td class="TBL_BODY" align="center"><?=$row1["qty"] ?> </td>						
                                        <td class="TBL_BODY" align="right"><?=number_format($row1["referensi"] ,2,",",".")?></td>						
                                        <td class="TBL_BODY" align="right"><?=number_format($row1["jum"] ,2,",",".")?></td>
					</tr>	

					<?
					$rec1 = getFromTable (
								"select count(id) from rs00008 ".
								"where trans_type = 'RCK' ".
								"		and no_reg ='".$_GET["v"]."'");
					if ($rec1 > 0) {
						$harga =	getFromTable("select sum(a.tagihan) as tagihan ".
								"from rs00008 a ".
								"	left join rs00015 b ".
								"ON to_number(a.item_id,'999999999999') = b.id ".
								"where a.trans_type = 'RCK' and ".
								"	a.no_reg = '".$_GET["v"]."' and ".
								"(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') ".
								"group by b.obat, a.qty, a.harga ");
					?>
					<tr valign="top" class="<??>" > 
						<td class="TBL_BODY" align="center"><?=$no + 1 ?> </td>
			        	<td class="TBL_BODY" align="left" colspan="4">Obat Racikan</td>
						<td class="TBL_BODY" align="right"><?=number_format($harga ,2,",",".")?></td>
					</tr>
					<?
					}
					
					$totbaru=$harga + $totbaru+$row1["jum"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
				
			} 
			$totall=$totbaru+$totbaru2
			?>
			
					<tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="left" colspan="6" height="25"  style="padding-left:50px;vertical-align:middle;"><b> TOTAL </b></td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($totbaru,2,",",".") ?></b></td>
					</tr>
					<tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="left" colspan="6" height="25"  style="padding-left:50px;vertical-align:middle;"><b> GRAND TOTAL </b></td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($totall,2,",",".") ?></b></td>
					</tr>	
					<!--tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="left" colspan="5" height="25"  style="padding-left:50px;vertical-align:middle;"><b> TOTAL PEMBAYARAN </b></td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($totall,2,",",".") ?></b></td>
					</tr-->	
</table>
<?		
    } 
    // DIAGNOSA

		$f = new Form("");
		echo "<br>";
		$f->title1("Data Diagnosa (ICD 10)");
		$f->execute();
		
		$t = new PgTable($con, "100%");
		$t->SQL = "select a.item_id,b.description,b.category from rs00008 a 
				   left join rsv0005 b on b.diagnosis_code = a.item_id
				   where trans_type='ICD' and a.no_reg ='".$_GET["v"]."' order by tanggal_entry";		   
		
		$t->setlocale("id_ID");
		$t->ShowRowNumber = true;
		$t->RowsPerPage = $ROWS_PER_PAGE;
		$t->ColHeader = array("KODE ICD","DESKRIPSI ICD","DIAGNOSA");
		$t->DisableScrollBar = true;
		$t->DisableStatusBar = true;		
		$t->execute();
        
     // TINDAKAN
		$f = new Form("");
		echo "<br>";
		$f->title1("Data Tindakan (ICD 9)");
		$f->execute();
		
		$t = new PgTable($con, "100%");
		$t->SQL = "select a.item_id,b.kode,b.nama from rs00008 a 
				   join icd_9 b on b.kode = a.item_id
				   where trans_type='CD9' and a.no_reg ='".$_GET["v"]."' order by tanggal_entry";		   
		
		$t->setlocale("id_ID");
		$t->ShowRowNumber = true;
		$t->RowsPerPage = $ROWS_PER_PAGE;
		$t->ColHeader = array("KODE ICD","DESKRIPSI ICD");
		$t->DisableScrollBar = true;
		$t->DisableStatusBar = true;		
		$t->execute();
 
    // informasi bangsal bagi Pasien Rawat Inap
    echo "<br>";

    if ($d->rawat_inap == "I") {
        $f = new Form("");
        $f->title1("Data Pemondokan");
        $f->execute();

        $t = new PgTable($con, "100%");
        $r1 = pg_query($con,
            "select sum(extract(day from case when d.ts_calc_stop is null ".
            "       then current_timestamp else d.ts_calc_stop ".
            "           end - d.ts_calc_start)*f.harga) as biaya ".
            "from rs00006 a ".
            "   left join rs00010 c ON a.id = c.no_reg and c.id = ".
            "       (select min(id) from rs00010 where no_reg = a.id) ".
            "   left join rs00010 d ON a.id = d.no_reg and d.id = ".
            "       (select max(id) from rs00010 where no_reg = a.id) ".
            "   left join rs00012 e ON d.bangsal_id = e.id ".
            "   left join rs00012 f ON substr(e.hierarchy,1,6)||'000000000' = f.hierarchy ".
            "   left join rs00012 g ON substr(e.hierarchy,1,3)||'000000000000' = g.hierarchy ".
            "   left join rs00001 h ON f.klasifikasi_tarif_id = h.tc and h.tt='KTR' ".
            "where a.id = '".$_GET["v"]."'");

        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);

	
        $t->SQL =
            "select to_char(c.ts_check_in, 'DD-MON-YYYY HH24:MI:SS'), ".
            "   to_char(d.ts_calc_stop,'DD-MON-YYYY HH24:MI:SS'), e.bangsal, ".
            "   f.bangsal, g.bangsal, h.tdesc, ".
            "   extract(day from case when d.ts_calc_stop is null ".
            "       then current_timestamp else d.ts_calc_stop ".
            "           end - d.ts_calc_start) as hari, f.harga, ".
            "   to_char((extract(day from case when d.ts_calc_stop is null ".
            "       then current_timestamp else d.ts_calc_stop ".
            "           end - d.ts_calc_start)*f.harga),'999999999') as biaya ".
            "from rs00006 a ".
            "   left join rs00010 c ON a.id = c.no_reg and c.id = ".
            "       (select min(id) from rs00010 where no_reg = a.id) ".
            "   left join rs00010 d ON a.id = d.no_reg and d.id = ".
            "       (select max(id) from rs00010 where no_reg = a.id) ".
            "   left join rs00012 e ON d.bangsal_id = e.id ".
            "   left join rs00012 f ON substr(e.hierarchy,1,6)||'000000000' = f.hierarchy ".
            "   left join rs00012 g ON substr(e.hierarchy,1,3)||'000000000000' = g.hierarchy ".
            "   left join rs00001 h ON f.klasifikasi_tarif_id = h.tc and h.tt='KTR' ".
            "where a.id = '".$_GET["v"]."'";

        $t->setlocale("id_ID");
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColAlign[6] = "RIGHT";
        $t->ColFormatNumber[7] = 2;
        $t->ColFormatNumber[8] = 2;
		$t->ColFormatNumber[9] = 2;
        $t->ColHeader = array("TGL. MASUK","TGL. KELUAR"," B E D","NAMA RUANG","BANGSAL KEPERAWATAN", "KLS.TARIF","JML. HARI","TARIF","Rp");
        $t->DisableScrollBar = true;
        $t->DisableStatusBar = true;
        $t->ColFooter[8] =  number_format($d1->biaya,2);
        $t->execute();
    }
} 
?>
