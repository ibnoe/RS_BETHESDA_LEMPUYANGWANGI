<?php
	// sfdn, 24-12-2006
session_start();

require_once("../lib/setting.php");
require_once("../lib/terbilang.php");

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

$ROWS_PER_PAGE     = 999999;
//$RS_NAME           = $set_header[0]."<br>".$set_header[1];
//$RS_ALAMAT         = $set_header[2]."<br>".$set_header[3].$set_header[4];

?>

<HTML>


<HEAD>
<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
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
echo "<hr>";
titlecashier2('PEMERINTAH PROPINSI SUMATERA BARAT');
titlecashier4('RSUD Dr. ACHMAD MOCHTAR BUKITTINGGI');
titlecashier1('Jl. Dr. A. Rivai - Bukittinggi');
titlecashier1('Telp. Hunting (0752) 21720 - 21492 - 21831 - 21322');
echo "<hr>";
//echo "<br>";

$reg = $_GET["rg"];

$rt = pg_query($con,
        "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, ".
        "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, ".
        "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, ".
        "    e.alm_tetap, e.kota_tetap, e.umur, e.pos_tetap, e.tlp_tetap, ".
        "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
        "    c.tdesc AS penjamin, a.no_jaminan,a.no_asuransi ,a.rujukan, a.rujukan_rs_id, ".
        "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
        "    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, ".
        "        CASE ".
        "            WHEN a.rawat_inap = 'I' THEN 'Rawat Inap' ".
        "            WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan' ".
        "            ELSE 'IGD' ".
        "        END AS rawat, ".
        "        age(a.tanggal_reg , e.tgl_lahir ) AS umur, ".
	"	case when a.rujukan = 'Y' then 'Rujukan' else 'Non-Rujukan' end as datang ".
	"    , i.tdesc as poli,e.pangkat_gol,e.nrp_nip,e.kesatuan ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc AND h.tt = 'JDP' ".
        "   left join rs00001 i on i.tc_poli = a.poli ".
        "WHERE a.id = '$reg'  ");
		//"WHERE a.id = '$reg'");
     

    $nt = pg_num_rows($rt);
    if($nt > 0) $dt = pg_fetch_object($rt);
    pg_free_result($rt);

if ($reg > 0) {
    if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where id = '$reg' ".
                     " ") ==0) {
                     //"and status = 'A'") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}




echo "<table align=center width=100% border=0>";
echo "<tr><td align=center> ";
if ($_GET["kas"] == "rj") {
  titlecashier2("KWITANSI PEMBAYARAN RAWAT JALAN");
} elseif ($_GET["kas"] == "ri") {
  titlecashier2("KWITANSI PEMBAYARAN RAWAT INAP");
} else {
  titlecashier2("KWITANSI PEMBAYARAN IGD");
}
//titlecashier("KWITANSI PEMBAYARAN");
$tglini = date("d");
$blnini = date("m");
$thnini = date("Y");
echo "</td></tr>";
echo "<tr><td align=center>";
titlecashier3("No : ".$reg);
echo "<hr>";
echo "</td></tr>";
echo "</table>";

//include("335.inc_.php");
?>
<table border="0" width=100%>
	<tr>
        <td valign=top width=30% class="TITLE_SIM3"><b>SUDAH TERIMA DARI </b></td>
		<td valign=top class="TITLE_SIM3"><b>:</b></td>
		<td valign=top class="TITLE_SIM3"><b> Tn/Ny/Sdr. <?= $dt->nama ?></b></td>
        
	</tr>
<?
$rrs = pg_query($con,
        "select * from rs00005 ".
		"where kasir in ('BYG','BYC','BYS','BYA') and ".
		"	to_number(reg,'999999999999') = '$reg' "); //and ".
		//"	referensi IN ('KASIR')");

while ($dds = pg_fetch_object($rrs)) {
?>

	<tr>
        <td valign=top width=30% class="TITLE_SIM3"><b>UANG SEJUMLAH</b></td>
		<td valign=top class="TITLE_SIM3"><b>:</b></td>
		<td valign=top  class="TITLE_SIM3"><b>Rp. <?= number_format($dds->jumlah,2) ?></b></td>
        
	</tr><tr>
		<td valign=top class="TITLE_SIM3"><b>&nbsp;</b></td>
		<td valign=top class="TITLE_SIM3"><b>&nbsp;</b></td>
        <td valign=top class="TITLE_SIM3"><b><i><?php $y=terbilang($dds->jumlah);
		echo strtoupper($y);?> RUPIAH</i></b></td>
	</tr>

<?
}
pg_free_result($rrs);


?>

<tr>
		<td valign=top class="TITLE_SIM3"><b>SEBAB DARI</b></td>
		<td valign=top class="TITLE_SIM3"><b>:</b></td>
        <td valign=top class="TITLE_SIM3"><b>Pembayaran Pelayanan Kesehatan di <?php echo $set_header[0]; ?></b></td>
   </tr>
</table>
<?
include("335.inc_2.php");

   title("Pembayaran");
    echo "<br>";


    if ($_GET["kas"] == "igd") {
       $loket = "IGD";
       $kasir = "IGD";
       $lyn = "layanan = '100'";
   
           
       
    } elseif ($_GET["kas"] == "rj") {
       $loket = "RJL";
       $kasir = "RJL";
       $lyn = "layanan not in ('100','99996','99997','12651','13111')";

    } else {
       $loket = "RIN";
       $kasir = "RIN";
       $lyn = "(layanan not in ('99996','99997','12651','13111'))";
       $d->poli = 0;
    }


    $poli = getFromTable("SELECT tdesc FROM rs00001 WHERE tt = 'LYN' and tc=$d->poli");

    $karcis = getFromTable("SELECT sum(jumlah) as jumlah FROM rs00005 WHERE reg='".$_GET[rg]."' AND is_karcis='Y'  ");
	if($_GET[kas]=="ri"){
    	$cekBayar = getFromTable("select SUM(jumlah) from rs00005 where reg='".$_GET[rg]."' and (kasir='BYR' or kasir = 'BYD' or kasir ='BYI')");
		$cekBayar=$cekBayar-$karcis;
	}
	else{
		$cekBayar = getFromTable("select SUM(jumlah) from rs00005 where reg='".$_GET[rg]."' and (kasir='BYR' or kasir = 'BYD' or kasir ='BYI') and is_obat='N'");
	}

$loket = getFromTable("select ".
         "case when rawat_inap = 'I' then 'RIN' ".
         "     when rawat_inap = 'Y' then 'RJL' ".
         "     else 'IGD' ".
         "end as rawatan ".
         "from rs00006 where id = '".$_GET[rg]."'");

$kodepoli = getFromTable("select poli from rs00006 where id = '".$_GET[rg]."'");

$namadokter = getFromTable("SELECT B.NAMA FROM RS00017 B 
    				LEFT JOIN  C_VISIT A ON A.ID_DOKTER = B.ID
    				WHERE A.ID_POLI=$kodepoli AND A.NO_REG='".$_GET[rg]."'"); 
if ($namadokter !="") {
$namadokter = "(".$namadokter.")";};

    $cekAskes = getFromTable("select  sum(a.tagihan) from   rs00008  a,  rs00034 b 	         ".
				"where a.no_reg = '".$_GET[rg]."'  AND b.tipe_pasien_id = '007'  ".
				"AND  b.id = to_number(a.item_id,'999999999999') AND a.trans_form <> '-' and a.item_id <>'-'  ");

$karcis = getFromTable("SELECT sum(jumlah) as jumlah FROM rs00005 WHERE reg='".$_GET[rg]."' AND is_karcis='Y'  ");

   $tipepasien = getFromTable("select  b.tipe from   rs00008  a,  rs00006 b 	         ".
			"where a.no_reg = '".$_GET[rg]."'  AND b.id = a.no_reg ");
if ($loket == "IGD") {
  $lyn123 = 100;
  
} elseif ($loket == "RJL") {
  $lyn123 = $kodepoli;
  
if ($lyn123 == 101 or $lyn123 == 105) { 

} else { 

} 
} else {
  $lyn123 = 0;
}


 if ($tipepasien == '007') { $paket1 = 'PAKET I ASKES'; $cekAskes = $cekAskes ;} else { $paket1 = 'KARCIS + PEMERIKSAAN DOKTER';};

    $cekPotong = getFromTable("select jumlah from rs00005 where reg='".$_GET[rg]."' and kasir='POT'");

$karcis = $hargatiket;

$bangsal_sudah_posting = 0.00;
$rec = pg_query("select * from rs00008 ".
                     "where trans_type = 'POS' and to_number(no_reg,'999999999999') = $reg order by id");
$rec_num = pg_num_rows($rec);

if ($rec_num > 0 ) {

	$r1 = pg_query($con,
	        "select a.id, a.ts_check_in::date, e.bangsal, d.bangsal as ruangan, b.bangsal as bed, ".
	        "    c.tdesc as klasifikasi_tarif, ".
	        "    extract(day from a.ts_calc_stop - a.ts_calc_start) as qty, ".
	        "    d.harga as harga_satuan, ".
	        "    extract(day from a.ts_calc_stop - a.ts_calc_start) * d.harga as harga, ".
	        "    a.ts_calc_stop ".
	        "from rs00010 as a ".
	        "    join rs00012 as b on a.bangsal_id = b.id ".
	        "    join rs00012 as d on substr(b.hierarchy,1,6) || '000000000' = d.hierarchy ".
	        "    join rs00012 as e on substr(b.hierarchy,1,3) || '000000000000' = e.hierarchy ".
	        "    join rs00001 as c on d.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
	        "where to_number(a.no_reg,'9999999999') = $reg and ts_calc_stop is not null");

	
	while ($ddd = pg_fetch_object($rec)) {
		while ($d1 = pg_fetch_object($r1)) {
		
		    $qty = $d1->qty;
		    $harga = $qty * $d1->harga_satuan;
		    $bangsal_sudah_posting = $bangsal_sudah_posting + $harga;
		}
	}
}

// >>>>>>>>>>>>>>>>  <<<<<<<<<<<<<<<<<<<<<<<
if (getFromTable ("select rawat_inap from rs00006 ".
				     "where to_number(id,'999999999999') = $reg") == "I") {
// TAGIHAN SEMENTARA AKOMODASI

$bangsal_belum_posting = 0.00;

$r1 = pg_query($con,
        "select a.id, a.ts_check_in::date, e.bangsal, d.bangsal as ruangan, b.bangsal as bed, ".
        "    c.tdesc as klasifikasi_tarif, ".
        "    extract(day from current_timestamp - a.ts_calc_start) as qty, ".
        "    d.harga as harga_satuan, ".
	// sfdn, 17-12-2006 --> harga = harga * jumlah hari
        "    extract(day from current_timestamp - a.ts_calc_start) * d.harga as harga ".
	// --- eof sfdn, 17-12-2006
        "from rs00010 as a ".
        "    join rs00012 as b on a.bangsal_id = b.id ".
        "    join rs00012 as d on substr(b.hierarchy,1,6) || '000000000' = d.hierarchy ".
        "    join rs00012 as e on substr(b.hierarchy,1,3) || '000000000000' = e.hierarchy ".
        "    join rs00001 as c on d.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
        "where to_number(a.no_reg,'9999999999') = $reg and ts_calc_stop is null");
if ($d1 = pg_fetch_object($r1)) {
    $bangsal_belum_posting = $bangsal_belum_posting + $d1->harga;
	// --- eof 17-12-2006 ---
	pg_free_result($r1);
}
}
if ($bangsal_sudah_posting > 0 ) {

	$bangsal_belum_posting = 0;
}



$r1 = pg_query($con,
    "select sum(tagihan) as tagihan, sum(pembayaran) as pembayaran ".
    "from rs00008 ".
    "where trans_type in ('LTM', 'BYR') ".
    "and to_number(no_reg, '999999999999') = $reg");
$d1 = pg_fetch_object($r1);
pg_free_result($r1);


$jml_total_Tagihan = $bangsal_sudah_posting+$bangsal_belum_posting ;
 


    $akomodasi = getFromTable("select sum(jumlah) as jumlah ".
         "from rs00005 where reg='".$_GET[rg]."' AND is_karcis='N' AND is_obat='N' AND kasir='$kasir' ".
         "AND layanan = 99996 ");

 

   /*  $tindakan = getFromTable("select sum(jumlah) as jumlah ".            
         "from rs00005 where reg='".$_GET[rg]."' AND is_karcis='N' AND is_obat='N' AND (kasir='RIN' OR kasir='RJL' OR kasir='IGD' ) "); */
$tindakan = getFromTable("select sum(tagihan) as jumlah ".            
         "from rs00008 where no_reg='".$_GET[rg]."' AND (trans_type='LTM')  and trans_form not in ('p_laboratorium','p_radiologi')");
//if ($lyn123 == 114 or $lyn123 == 115) { $tindakan = $tindakan/2 ;} else {$tindakan = $tindakan;};

    /* $laborat = getFromTable("select sum(jumlah) as jumlah ".
         "from rs00005 where reg='".$_GET[rg]."' AND is_karcis='N' AND is_obat='N' AND kasir='$kasir' ".
         "AND layanan = 12651 ");

    $radiologi = getFromTable("select sum(jumlah) as jumlah ".
         "from rs00005 where reg='".$_GET[rg]."' AND is_karcis='N' AND is_obat='N' AND kasir='$kasir' ".
         "AND layanan = 13111 "); */
	
	$laborat = getFromTable("select sum(tagihan) as jumlah ".
         "from rs00008 where no_reg='".$_GET[rg]."' AND (trans_type='LTM') AND trans_form='p_laboratorium' ");

    $radiologi = getFromTable("select sum(tagihan) as jumlah ".
         "from rs00008 where no_reg='".$_GET[rg]."' AND (trans_type='LTM') AND trans_form='p_radiologi' ");
		 
    $obat = getFromTable("select sum(jumlah) as jumlah ".
         "from rs00005 where reg='".$_GET[rg]."' AND is_karcis='N' AND is_obat='Y' AND kasir='$kasir' ".
         "AND layanan in ('99997', '99995') ");
   

    $retur = getFromTable("select sum(jumlah) as jumlah ".
         "from rs00005 where reg='".$_GET[rg]."' and layanan = 90000 ");


    $total = $karcis + $tindakan + $laborat + $jml_total_Tagihan + $radiologi + $obat - $retur;
    //$bayarobat = $obat - $retur;


// obat nggambus
$reg = $_GET["rg"];
 
$rec = getFromTable ("select count(id) from rs00008 ".
                     "where trans_type = 'OB1' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");

if ($rec > 0 ) {

	$SQL =
		"select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans, ".
		"obat, qty, c.tdesc as satuan, sum(harga*qty) as tagihan, pembayaran, trans_group, d.tdesc as kategori ".
		"from rs00008 a, rs00015 b, rs00001 c, rs00001 d ".
		"where to_number(a.item_id,'999999999999') = b.id  ".
		"and b.satuan_id = c.tc and a.trans_type = 'OB1' ".
		"and c.tt = 'SAT' ".
		"and b.kategori_id = d.tc and d.tt = 'GOB' ".
		"and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'".
		"group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group,   c.tdesc ";
	$r1 = pg_query($con, "$SQL ");

        $kateg = "000";
        $ob_urut = 0;

    	while ($d1 = pg_fetch_object($r1)) {
                if ($d1->kategori != $kateg) {
                   $ob_urut++;
                   $obatx[$ob_urut] = 0;
                   $kateg = $d1->kategori;
	           $cek_kateg = substr($kateg,0,1);

                }



                if ($cek_kateg == "A") {   // apbd
                   $obatx[1] = $obatx[1] + $d1->tagihan;
                } elseif ($cek_kateg == "D") {    // dpho
                   $obatx[2] = $obatx[2] + $d1->tagihan;
                } elseif ($cek_kateg == "K") {    // koperasi
                   $obatx[3] = $obatx[3] + $d1->tagihan;
                }
              //  $tot_obat = $tot_obat + $d1->tagihan;
  $tot_obat = 0;
	}
	pg_free_result($r1);

}

$sql="SELECT a.obat as nama,b.harga,b.qty, (b.tagihan-(b.harga*b.qty)) as jasa, b.tagihan   
	from rs00015 a, rs00008 b 
	where a.id::text=b.item_id and b.no_reg='".$_GET["rg"]."' and trans_type='OB1' and trans_form in ('320RJ_IGD','320RJ_SWD','320RJ_CDM','320RJ_ASK')";

$sql2 = "SELECT 'Racikan Obat' as nama,sum(b.harga) as harga, '' as qty , sum((b.tagihan-(b.harga*b.qty))) as jasa, sum(b.tagihan) as tagihan
		from rs00015 a, rs00008 b 
		where a.id::text=b.item_id and b.no_reg='".$_GET["rg"]."' and trans_type='RCK' and trans_form in ('320RJ_IGD','320RJ_SWD','320RJ_CDM','320RJ_ASK') ";
		
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
	$blm_byr=getFromTable("select sum(jumlah) from rs00005 where is_obat='Y' and kasir in ('BYG','BYC','BYS','BYA') and is_bayar='N' and reg='".$_GET["rg"]."'");
	$sdh_byr=getFromTable("select sum(jumlah) from rs00005 where is_obat='Y' and kasir in ('BYG','BYC','BYS','BYA') and is_bayar='Y' and reg='".$_GET["rg"]."'");
	$pot_byr=getFromTable("select sum(jumlah) from rs00005 where is_obat='Y' and kasir in ('POT') and is_bayar='Y' and reg='".$_GET["rg"]."'");
	$sisa_tgh=$blm_byr - ($sdh_byr + $pot_byr);
	//=========================
		
		
?>

<br>
<table  class="TBL_BORDER" align="center" border="0" WIDTH='100%'>
	<tr valign="top">
		<td class="TBL_HEAD2" align="center" valign="middle"><font size=1 ><b>Nama Obat</b></font></td>
		<td class="TBL_HEAD2" align="center" width="15%" valign="middle"><font size=1 ><b>Harga</b></font></td>
		<td class="TBL_HEAD2" align="center" width="15%" valign="middle"><font size=1 ><b>Qty</b></font></td>
		<td class="TBL_HEAD2" align="center" width="15%" valign="middle"><font size=1 ><b>Jasa<br>Resep/Racikan</b></font></td>
		<td class="TBL_HEAD2" align="center" width="15%" valign="middle"><font size=1 ><b>Jumlah Harga</b></font></td>
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
			        	<td class="TBL_BODY" align="left"><font size=2 ><?=$row1["nama"] ?> </font></td>
						<td class="TBL_BODY" align="right"><font size=2 ><?=number_format($row1["harga"] ,2,",",".") ?> </font></td>
						<td class="TBL_BODY" align="center"><font size=2 ><?=$row1["qty"] ?> </font></td>
						<td class="TBL_BODY" align="right"><font size=2 ><?=number_format($row1["jasa"] ,2,",",".")?></font></td>
						<td class="TBL_BODY" align="right"><font size=2 ><?=number_format($row1["tagihan"] ,2,",",".")?></font></td>
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
			        <td class="TBL_BODY" align="left" colspan=3><font size=2 ><?=$row2["nama"] ?> </font></td>
					<td class="TBL_BODY" align="right"><font size=2 ><?=number_format($row2["jasa"] ,2,",",".")?></font></td>
					<td class="TBL_BODY" align="right"><font size=2 ><?=number_format($row2["tagihan"] ,2,",",".")?></font></td>
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
		
					<tr valign="top" class="TBL_HEAD2" >  
			        	<td align="right" colspan="4" height="25" valign="middle"><font size=1 > TOTAL TAGIHAN</font></td>
						<td align="right" valign="middle"><font size=1 >Rp. <?=number_format($total,2,",",".")?></font></td>
					</tr>
					<tr valign="top" class="TBL_HEAD2" >  
			        	<td align="right" colspan="4" height="25" valign="middle"><font size=1 > POTONGAN </font></td>
						<td align="right" valign="middle"><font size=1 >Rp. <?=number_format($pot_byr,2,",",".")?></font></td>
					</tr>
					<tr valign="top" class="TBL_HEAD2" >  
			        	<td align="right" colspan="4" height="25" valign="middle"><font size=1 > BAYAR </font></td>
						<td align="right" valign="middle"><font size=1 >Rp. <?=number_format($sdh_byr,2,",",".")?></font></td>
					</tr>
					<tr valign="top" class="TBL_HEAD2" >  
						<td align="left" colspan="5" valign="middle"><font size=1 >TERBILANG :  <i><?php $y=terbilang($sdh_byr);
		echo strtoupper($y);?> RUPIAH</i></font></td>
					</tr>
					
</table>
<br>


<?

echo "\n<script language='JavaScript'>\n";
echo "function cetakrincian(tag) {\n";
echo "    sWin = window.open('index2.php?tag=' + tag, 'xWin',".
     " 'width=500,height=400,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";
$tgl_sekarang = date("d M Y", time());
?>
<table border="0" align="right" width="50%">
  <tr>
        <td align="center" class="TITLE_SIM3"><b>Bukittinggi, <?echo $tgl_sekarang;?></b></td>
      
  </tr>
  <tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><u><b><? echo $_SESSION["nama_usr"];?></b></u></td>
</tr>
</table>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</html>
