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
    if (!$GLOBALS['print']){
    	title("<img src='icon/keuangan-2.gif' align='absmiddle' > INFO TRANSAKSI");	
		title("Data Pemeriksaan Kesehatan Pasien");
	    title_excel("transaksi_pasien&v=".$_GET["v"]."&t1=".$_GET["t1"]."&t2=".$_GET["t2"]."");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > INFO TRANSAKSI");
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
    // agung
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
    $rowsPemakaianObat      = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty, tagihan::integer, referensi::integer, dibayar_penjamin::integer, trans_type  
                             FROM rs00008 
                             WHERE trans_type = 'OB1' AND rs00008.no_reg = '".$_GET["v"]."' ");
    $rowsPemakaianRacikan   = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty, tagihan::integer, referensi::integer, dibayar_penjamin::integer, trans_type  
                             FROM rs00008 
                             WHERE trans_type = 'RCK' AND rs00008.no_reg = '".$_GET["v"]."' ");
    $rowsReturn   = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty_return as  qty, tagihan::integer, referensi::integer, dibayar_penjamin::integer, trans_type  
                             FROM rs00008_return 
                             WHERE (trans_type = 'OB1' OR trans_type = 'RCK') AND rs00008_return.no_reg = '".$_GET["v"]."' ");
?>
<table width="100%">
    <tr>
        <td width="157" align="LEFT" colspan="7" class="FORM_SUBTITLE1">Data Farmasi</td>
    </tr>
     <tr>
        <td class="TBL_HEAD" align="center" width="5%">NO.</td>
        <td class="TBL_HEAD" align="center" width="15%">WAKTU ENTRY</td>
        <td class="TBL_HEAD" align="center">NAMA OBAT</td>
        <td class="TBL_HEAD" align="center" width="10%">HARGA SATUAN</td>
        <td class="TBL_HEAD" align="center" width="5%">QTY</td>
        <td class="TBL_HEAD" align="center" width="10%">PENJAMIN</td>
        <td class="TBL_HEAD" align="center" width="10%">JUMLAH</td>
    </tr>
    <tr>
        <td class="TBL_BODY" align="left" colspan="7" style="font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Obat Resep</td>
    </tr>
<?php
    $iData        = 0;
    $total          = 0;
    $totalPenjamin  = 0;
    $totalSelisih   = 0;
    $totalFarmasi   = 0;
    while($row=pg_fetch_array($rowsPemakaianObat)){
        $iData++;
        $total          = $total + $row["tagihan"];
        $totalPenjamin  = $totalPenjamin + $row["dibayar_penjamin"];
        $totalSelisih   = $totalSelisih + ($row["tagihan"]-$row["dibayar_penjamin"]);
        

        $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga::integer 
                                    FROM rs00015 
                                    INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                    INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                    WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] );
        $obat = pg_fetch_array($sqlObat);
        $arrWaktuEntry = explode('.', $row["waktu_entry"]);
        $arrJamEntry = explode(':', $arrWaktuEntry[0]);
?>
    <tr>
        <td class="TBL_BODY" align="right"><?php echo $iData?></td>
        <td class="TBL_BODY" align="left"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
        <td class="TBL_BODY" align="left"><?=$obat["obat"]?></td>
        <td class="TBL_BODY" align="right"><?=number_format($obat["harga"],'0','','.')?></td>
        <td class="TBL_BODY" align="right"><?=$row["qty"]?></td>
        <td class="TBL_BODY" align="right"><?=number_format($row["dibayar_penjamin"],'0','','.')?></td>
        <td class="TBL_BODY" align="right"><?=number_format($row["tagihan"]-$row["dibayar_penjamin"],'0','','.')?></td>
    </tr>
<?php
        }
        $totalFarmasi   = $totalSelisih;
?>    
     <tr>
        <td class="TBL_BODY" align="left" colspan="7" style="font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Obat Racikan</td>
    </tr>
<?php
    $iDataRacikan          = 0;
    $totalRacikan          = 0;
    $totalPenjaminRacikan  = 0;
    $totalSelisihRacikan   = 0;
    while($rowRacikan=pg_fetch_array($rowsPemakaianRacikan)){
        $iDataRacikan++;
        $totalRacikan          = $totalRacikan + $rowRacikan["tagihan"];
        $totalPenjaminRacikan  = $totalPenjaminRacikan + $rowRacikan["dibayar_penjamin"];
        $totalSelisihRacikan   = $totalSelisihRacikan + ($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]);

        $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga::integer 
                                    FROM rs00015 
                                    INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                    INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                    WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $rowRacikan["item_id"] );
        $obat = pg_fetch_array($sqlObat);
        $arrWaktuEntry = explode('.', $rowRacikan["waktu_entry"]);
        $arrJamEntry = explode(':', $arrWaktuEntry[0]);
?>
    <tr>
        <td class="TBL_BODY" align="right"><?php echo $iDataRacikan?></td>
        <td class="TBL_BODY" align="left"><?=tanggal($rowRacikan["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
        <td class="TBL_BODY" align="left"><?=$obat["obat"]?></td>
        <td class="TBL_BODY" align="right"><?=number_format($obat["harga"],'0','','.')?></td>
        <td class="TBL_BODY" align="right"><?=$rowRacikan["qty"]?></td>
        <td class="TBL_BODY" align="right"><?=number_format($rowRacikan["dibayar_penjamin"],'0','','.')?></td>
        <td class="TBL_BODY" align="right"><?=number_format($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"],'0','','.')?></td>
    </tr>
<?php
        }
        $totalFarmasi = $totalFarmasi + $totalSelisihRacikan;
?>     
     <tr>
        <td class="TBL_BODY" align="left" colspan="7" style="font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Return</td>
    </tr>
<?php
    $iDataReturn          = 0;
    $totalReturn          = 0;
    $totalPenjaminReturn  = 0;
    $totalSelisihReturn   = 0;
    while($rowReturn=pg_fetch_array($rowsReturn)){
        $iDataReturn++;
        $totalReturn          = $totalReturn + $rowReturn["tagihan"];
        $totalPenjaminReturn  = $totalPenjaminReturn + $rowReturn["dibayar_penjamin"];
        $totalSelisihReturn   = $totalSelisihReturn + ($rowReturn["tagihan"]-$rowRacikan["dibayar_penjamin"]);

        $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga::integer 
                                    FROM rs00015 
                                    INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                    INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                    WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $rowReturn["item_id"] );
        $obat = pg_fetch_array($sqlObat);
        $arrWaktuEntry = explode('.', $rowReturn["waktu_entry"]);
        $arrJamEntry = explode(':', $arrWaktuEntry[0]);
?>
    <tr>
        <td class="TBL_BODY" align="right"><?php echo $iDataReturn?></td>
        <td class="TBL_BODY" align="left"><?=tanggal($rowReturn["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
        <td class="TBL_BODY" align="left"><?=$obat["obat"]?></td>
        <td class="TBL_BODY" align="right"><?=number_format($obat["harga"],'0','','.')?></td>
        <td class="TBL_BODY" align="right"><?=$rowReturn["qty"]?></td>
        <td class="TBL_BODY" align="right"><?=number_format($rowReturn["dibayar_penjamin"],'0','','.')?></td>
        <td class="TBL_BODY" align="right"><?=number_format($rowReturn["tagihan"]-$rowReturn["dibayar_penjamin"],'0','','.')?></td>
    </tr>
<?php
        }
?>     
    <tr valign="top" class="TBL_HEAD">  
        <td class="TBL_HEAD" align="left" colspan="6" height="25"  style="padding-left:50px;vertical-align:middle;"><b> TOTAL </b></td>
        <td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($totalFarmasi-$totalSelisihReturn,'0','','.')?></b>&nbsp;&nbsp;</td>
    </tr>
    <tr valign="top" class="TBL_HEAD">  
        <td class="TBL_HEAD" align="left" colspan="6" height="25"  style="padding-left:50px;vertical-align:middle;"><b> GRAND TOTAL </b></td>
        <td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format((($totalFarmasi-$totalSelisihReturn)+$totbaru+$totbaru2),'0','','.')?></b>&nbsp;&nbsp;</td>
    </tr>	
</table>
<?		
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
        $t->ColHeader = array("TGL. MASUK","TGL. KELUAR"," B E D","NAMA RUANG","BANGSAL   KEPERAWATAN",
                            "KLS.TARIF","JML. HARI","TARIF","Rp");
        $t->DisableScrollBar = true;
        $t->DisableStatusBar = true;
		//$t->ColFooter[7] =  number_format($d1->hari,2);
        $t->ColFooter[8] =  number_format($d1->biaya,2);
		//$t->ColFooter[9] =  number_format($d1->biaya*$d1->hari,2);
        //$t->ShowSQLExecTime = true;
        //$t->ShowSQL = true;
        $t->execute();
    }
} else {
    // search box
    title("<img src='icon/keuangan-2.gif' align='absmiddle' > TRANSAKSI PASIEN");
	title_excel("transaksi_pasien&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&rawat_inap=".$_GET["rawat_inap"]."&mRAWAT=".$_GET["mRAWAT"]."&mPASIEN=".$_GET["mPASIEN"]."");
    $ext = "OnChange = 'Form1.submit();'";
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	include(xxx2);

    $f->selectArray("rawat_inap", "U n i t",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "onChange='document.Form1.submit();'; ");
	$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
	if ($_GET["rawat_inap"]=="Y"){
	$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
                                             SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
                                             order by tdesc ",$_GET["mRAWAT"], "");
	}elseif ($_GET["rawat_inap"]=="I"){
	$f->selectSQL("mINAP", "Bangsal ","select d.bangsal, d.bangsal as bangsal
                       from rs00010 as a 
                           join rs00012 as b on a.bangsal_id = b.id 
                           join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
                           join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
                           join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
group by d.bangsal
order by d.bangsal " ,$_GET["mINAP"], "");
	}else{}
    $f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' ORDER BY tdesc ASC",$_GET[mPASIEN],"");
    $f->submit ("TAMPILKAN");
    $f->execute();

    echo "<BR>";

    $t = new PgTable($con, "100%");
    
	if ($_GET["rawat_inap"]=="Y" or $_GET["rawat_inap"]!="I"){			
				$t->SQL  =  "select a.mr_no,c.nama,a.id, to_char(a.tanggal_reg,'DD-MM-YYYY') as tgl_reg_str, ".
                "   d.tdesc as pasien, ".
                "   case when a.rawat_inap='I' then 'RAWAT INAP' ".
                "        when a.rawat_inap='Y' then 'RAWAT JALAN' ".
                "        else 'IGD' end as rawat, ".
				"x.tdesc ".
				"from rs00006 a ".
                "   left join rs00008 b ON a.id = b.no_reg ".
                "   left join rs00002 c ON a.mr_no = c.mr_no ".
                "   left join rs00001 d ON a.tipe = d.tc and d.tt='JEP' ".
                "   left join rs00001 x ON a.poli = x.tc_poli and x.tt='LYN' ".
                "where (upper(c.nama) LIKE '%".strtoupper($_GET["search"])."%' OR ".
                "   a.id LIKE '%".$_GET["search"]."%' ) and ".
                "   (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ".
                "   and (a.rawat_inap like '%".$_GET[rawat_inap]."%') and (x.tc like '%".$_GET["mRAWAT"]."%') and a.tipe like '%".$_GET[mPASIEN]."%'  ".
                "group by a.mr_no,c.nama,a.id,a.tanggal_reg,d.tdesc, a.rawat_inap, x.tdesc ";
	}elseif ($_GET["rawat_inap"]=="I"){
				$t->SQL  =  "select a.mr_no,c.nama,a.id, to_char(a.tanggal_reg,'DD-MM-YYYY') as tgl_reg_str, ".
                "   d.tdesc as pasien, ".
                "   case when a.rawat_inap='I' then 'RAWAT INAP' ".
                "        when a.rawat_inap='Y' then 'RAWAT JALAN' ".
                "        else 'IGD' end as rawat, ".
				" h.bangsal || ' / ' || g.bangsal || ' / ' || i.tdesc || ' / ' || f.bangsal as bangsal
					from rs00006 a 
				left join rs00010 e ON a.id = e.no_reg 
				join rs00012 as f on e.bangsal_id = f.id 
				join rs00012 as g on g.hierarchy = substr(f.hierarchy,1,6) || '000000000' 
				join rs00012 as h on h.hierarchy = substr(f.hierarchy,1,3) || '000000000000' 
				join rs00001 as i on g.klasifikasi_tarif_id = i.tc and i.tt = 'KTR'
                left join rs00008 b ON a.id = b.no_reg 
                left join rs00002 c ON a.mr_no = c.mr_no 
                left join rs00001 d ON a.tipe = d.tc and d.tt='JEP' 
                left join rs00001 x ON a.poli = x.tc_poli and x.tt='LYN' ".
				"where (upper(c.nama) LIKE '%".strtoupper($_GET["search"])."%' OR ".
                "   a.id LIKE '%".$_GET["search"]."%' ) and ".
                "   (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ".
                "   and (a.rawat_inap like '%".$_GET[rawat_inap]."%') and (h.bangsal like '%".$_GET["mINAP"]."%') and a.tipe like '%".$_GET[mPASIEN]."%'  ".
                "group by a.mr_no,c.nama,a.id,a.tanggal_reg,d.tdesc, a.rawat_inap, x.tdesc,h.bangsal,g.bangsal,i.tdesc,f.bangsal ";
	}
    if (!isset($_GET[sort])) {
	$_GET[sort] = "a.tanggal_reg";
	$_GET[order] = "desc";
    }
    
    $t->setlocale("id_ID");
    $t->ColHeader = array("NO.MR","NAMA PASIEN","NO.REG","TGL.REG", "TIPE PASIEN","UNIT","RAWATAN" );
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&v=<#2#>&t1=$ts_check_in1&t2=$ts_check_in2'><#2#></A>";
    $t->execute();
echo "<br>";
echo "<b><i>Pencarian berdasarkan tanggal pendaftaran</i></b>";
}
//} // --- end of ($_SESSION[uid] ----


function tanggal($tanggal) {
        $arrTanggal = explode('-', $tanggal);

        $hari = $arrTanggal[2];
        $bulan = $arrTanggal[1];
        $tahun = $arrTanggal[0];

        $result = $hari . ' ' . bulan($bulan) . ' ' . $tahun;

        return $result;
    }

function bulan($params) {
    switch ($params) {
        case 1:
            $bln = "Jan";
            break;
        case 2:
            $bln = "Peb";
            break;
        case 3:
            $bln = "Mar";
            break;
        case 4:
            $bln = "Apr";
            break;
        case 5:
            $bln = "Mei";
            break;
        case 6:
            $bln = "Jun";
            break;
        case 7:
            $bln = "Jul";
            break;
        case 8:
            $bln = "Agu";
            break;
        case 9:
            $bln = "Sep";
            break;
        case 10:
            $bln = "Okt";
            break;
        case 11:
            $bln = "Nop";
            break;
        case 12:
            $bln = "Des";
            break;
            break;
    }
    return $bln;
}

function updateTagihanUntukKasir($con, $rg){
            // ---- insert juga rs00005 untuk kasir
        $sqlTotalBiayaObat      = pg_query($con, "SELECT SUM(tagihan) as jumlah_tagihan, SUM(dibayar_penjamin) as jumlah_penjamin  
                            FROM rs00008 
                            WHERE no_reg = '".$rg."' ");
        $totalBiayaObat = pg_fetch_array($sqlTotalBiayaObat);

        if($totalBiayaObat['jumlah_tagihan'] > 0){
            $totalBiayaObatVal = $totalBiayaObat['jumlah_tagihan'];
        }else{
            $totalBiayaObatVal = 0;
        }
        
        if($totalBiayaObat['jumlah_penjamin'] > 0){
            $totalBiayaObatValPenjamin = $totalBiayaObat['jumlah_penjamin'];
        }else{
            $totalBiayaObatValPenjamin = 0;
        }
        
        // cek dulu di tabel rs00005 klo datanya udah ada di update, klo g ada di insert aja cuy
        $sqlCek = pg_query($con, "SELECT jumlah FROM  rs00005 WHERE reg = '".$rg."' AND kasir = 'RJL' AND layanan = '320RJ_SWD' ");
        
        if(pg_num_rows($sqlCek) > 0){
            pg_query($con, "UPDATE  rs00005  SET  jumlah = ".((int)$totalBiayaObatVal-(int)$totalBiayaObatValPenjamin)." 
                WHERE reg = '".$rg."' AND kasir = 'RJL' AND layanan = '320RJ_SWD' ");
        }else{
            pg_query($con, "INSERT INTO rs00005 VALUES( nextval('kasir_seq'), '".$rg."', ".
        "CURRENT_DATE, 'RJL', 'Y', 'N', '320RJ_SWD', ".((int)$totalBiayaObatVal-(int)$totalBiayaObatValPenjamin).", 'N')");
        }
}
?>