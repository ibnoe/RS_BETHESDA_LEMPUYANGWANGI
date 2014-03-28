<?php // Nugraha, Sat May  1 09:58:26 WIT 2004
      // sfdn, 01-06-2004
      // sfdn, 24-12-2006
      // sfdn, 25-12-2006


session_start();
$PID = "888";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

/* By YGR  */
$jns_kasir = array(
	"rj"=>"RAWAT JALAN", 
	"ri"=>"RAWAT INAP",
	"igd"=>"IGD",
) ;

	$what = $jns_kasir[$_GET["kas"]] ;
	$sqlayanan = "NOT LIKE '%IGD%'";		
	if ($_GET["kas"] == "igd"){
		$sqlayanan = "LIKE '%IGD%'";}
		
	echo "<hr noshade size=1>";
	$reg = $_GET["rg"];
	$r = pg_query($con,
        "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, ".
        "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, ".
        "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, ".
        "    e.alm_tetap, e.kota_tetap, e.pos_tetap, e.tlp_tetap, ".
        "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
        "    c.tdesc AS penjamin, a.no_jaminan, a.rujukan, a.rujukan_rs_id, ".
        "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, a.poli,a.status_akhir_pasien,".
        "    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, ".
        "        CASE ".
        "            WHEN a.rawat_inap = 'I' THEN 'Rawat Inap'  ".
        "            WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan' ".
        "            ELSE 'IGD' ".
        "        END AS rawatan, ".
        "        age(a.tanggal_reg , e.tgl_lahir ) AS umur, ".
	"	case when a.rujukan = 'Y' then 'Rujukan' else 'Non-Rujukan' end as datang ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc AND h.tt = 'JDP' ".
        "WHERE a.id = '$reg'");
	$n = pg_num_rows($r);
	if($n > 0) $d = pg_fetch_object($r);
	pg_free_result($r);
	
$sisa = getFromTable(
					 "select sum((qty*harga)-pembayaran) as sisa ".
					 "from rs00008  ".
					 "where no_reg = '$reg' and ".
					 "trans_type IN ('LTM','BYR','OB1','POS')");

$xtagih = getFromTable(
					 "select sum(qty*harga) as xtagih ".
					 "from rs00008  ".
					 "where no_reg = '$reg' and ".
					 "(trans_type = 'OB2' and referensi = '')");
$sisa =  $xtagih + $sisa;
// end of tokit update

if($_GET["e"] == "edit") {

	// if ( ($_GET['mCAB'] == "") || ($_GET['mKELUAR'] == "") ) {
	if ( ($_GET['mCAB'] == "") ) {

echo "STATUS PEMBAYARAN HARUS DI ISI ";
	echo "<script language=javascript>\n";
	echo "<!--\n";
	echo "window.location = \"index2.php?p=$PID&rg=".$_GET['rg']."&sub=".$_GET['sub']."\";\n";
	echo "-->\n";
	echo "</script>";

	exit();

	}

    // cek tabel rs00005 udah ada BYR, ASK, POT belum ??
    
   // $cekTrans = getFromTable("select sum(qty*harga) from rs00008 where no_reg = lpad($reg,10,'0') and  is_bayar='N'");

	/* Hitung Transaksi ASKES */
    $cekAskes = getFromTable("select jumlah from rs00005 where reg='".$_GET[rg]."' and kasir='ASK'");
    
    if (!isset($cekAskes) && isset($_GET[askes])) {
       pg_query("insert into rs00005 ".
		"values(nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, 'ASK', ".
		"'N', 'N', 0, $_GET[askes], 'Y')");
    } else {
       $askes = $cekAskes + $_GET[askes];
       pg_query("update rs00005 set jumlah = $askes where reg = '".$_GET[rg]."'  and kasir = 'ASK'");
    }


       
    /* proses data POTONGAN / KERINGANAN */    
     $cekPotong = getFromTable("select jumlah from rs00005 where reg='".$_GET[rg]."' and kasir='POT'");
     
    if (!isset($cekPotong)) {
       pg_query("insert into rs00005 ".
		" values(nextval('kasir_seq'),'".$_GET[rg]."',CURRENT_DATE,'POT', ".
		"'N','N',0,$_GET[keringanan],'Y')");
    } else {
       $potong = $cekPotong + $_GET[keringanan];
       pg_query("update rs00005 set jumlah = $potong where reg = '".$_GET[rg]."' and kasir = 'POT'");
	   #################################################################################################efrizal
	   if ($_GET["mCAB"]=="012"){
	   $riz = getfromtable("select no_asuransi from rs00006 where id = '".$_GET[rg]."' ");
	   $pakai1 = getfromtable("select plafon_pakai from ciu where no_asuransi = '".$riz."' ");
	   $sisa1 = getfromtable("select plafon_sisa from ciu where no_asuransi = '".$riz."' ");
	   $pakai2 = $pakai1 + $potong;
	   $sisa2 = $sisa1 - $potong;
	   pg_query("update ciu set plafon_sisa = $sisa2, plafon_pakai = $pakai2 where no_asuransi = '".$riz."' ");
	   ####################################################################################################efrizal
	   }
    }

    /* Total POTONGAAN  */
    $total_potongan = $potong + $askes ;
    
    /*
    if ($_GET[keringanan] > 0) {
	$_GET[bayar] = $_GET[bayar] - $_GET[keringanan];

    }	
    */
    
    if ($_GET[bayar] > 0) {
		if ($_GET[kas]=="igd"){
 
	    pg_query("insert into rs00005 ".
			" values(nextval('kasir_seq'),'".$_GET[rg]."',CURRENT_DATE, ".
			"'BYD','N','N',0,$_GET[bayar],'Y')");
	    
		$sql1=("update rs00006 set is_bayar = 'Y' where id = '".$_GET[rg]."'");
		
		$sql3="insert into jurnal_umum (id,tanggal_akun,no_akun,keterangan,debet,kredit,user_id,nm_kasir,kasir_type) values (nextval('jurnal_umum_seq'),CURRENT_DATE,'1101.02','Kas Kecil',$_GET[bayar],0,'".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."','kasir_BYD')";

		$sql2="insert into jurnal_umum (id,tanggal_akun,no_akun,keterangan,debet,kredit,user_id,nm_kasir,kasir_type) values (nextval('jurnal_umum_seq'),CURRENT_DATE,'2100.00','Pembayaran Kasir',0,$_GET[bayar],'".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."','kasir_BYD')";
		
		$sql4="insert into kas_masuk (id,tanggal,kode_trans,keterangan,jumlah,cara_bayar) values (nextval('kas_masuk_seq'),CURRENT_DATE,'2100.00','Pembayaran Kasir',$_GET[bayar],'".$_GET[mCAB]."')";
		
		pg_query($con, $sql4);
		pg_query($con, $sql1);
		pg_query($con, $sql3);
		pg_query($con, $sql2);
		}

		else if ($_GET[kas]=="rj"){
 
	    pg_query("insert into rs00005 ".
			" values(nextval('kasir_seq'),'".$_GET[rg]."',CURRENT_DATE, ".
			"'BYR','N','N',0,$_GET[bayar],'Y')");
		
		$sql1=("update rs00006 set is_bayar = 'Y' where id = '".$_GET[rg]."'");
		
		$sql3="insert into jurnal_umum (id,tanggal_akun,no_akun,keterangan,debet,kredit,user_id,nm_kasir,kasir_type) values (nextval('jurnal_umum_seq'),CURRENT_DATE,'1101.02','Kas Kecil',$_GET[bayar],0,'".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."','kasir_BYR')";

		$sql2="insert into jurnal_umum (id,tanggal_akun,no_akun,keterangan,debet,kredit,user_id,nm_kasir,kasir_type) values (nextval('jurnal_umum_seq'),CURRENT_DATE,'2100.00','Pembayaran Kasir',0,$_GET[bayar],'".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."','kasir_BYR')";
		
		$sql4="insert into kas_masuk (id,tanggal,kode_trans,keterangan,jumlah,cara_bayar) values (nextval('kas_masuk_seq'),CURRENT_DATE,'2100.00','Pembayaran Kasir',$_GET[bayar],'".$_GET[mCAB]."')";
		
		pg_query($con, $sql4);
		pg_query($con, $sql1);
		pg_query($con, $sql3);
		pg_query($con, $sql2);		
			}
	    
		
		else if ($_GET[kas]=="ri"){
 
	    pg_query("insert into rs00005 ".
			" values(nextval('kasir_seq'),'".$_GET[rg]."',CURRENT_DATE, ".
			"'BYI','N','N',0,$_GET[bayar],'Y')"); 
			
		$sql1=("update rs00006 set is_bayar = 'Y' where id = '".$_GET[rg]."'");
		
		$sql3="insert into jurnal_umum (id,tanggal_akun,no_akun,keterangan,debet,kredit,user_id,nm_kasir,kasir_type) values (nextval('jurnal_umum_seq'),CURRENT_DATE,'1101.02','Kas Kecil',$_GET[bayar],0,'".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."','kasir_BYI')";

		$sql2="insert into jurnal_umum (id,tanggal_akun,no_akun,keterangan,debet,kredit,user_id,nm_kasir,kasir_type) values (nextval('jurnal_umum_seq'),CURRENT_DATE,'2100.00','Pembayaran Kasir',0,$_GET[bayar],'".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."','kasir_BYI')";
		
		$sql4="insert into kas_masuk (id,tanggal,kode_trans,keterangan,jumlah,cara_bayar) values (nextval('kas_masuk_seq'),CURRENT_DATE,'2100.00','Pembayaran Kasir',$_GET[bayar],'".$_GET[mCAB]."')";
		
		pg_query($con, $sql4);
		pg_query($con, $sql1);
		pg_query($con, $sql3);
		pg_query($con, $sql2);
		}
    }
    
    // ambil data pasien di master data registrasi rs00006
    $r1 = pg_query($con,
         "select tipe, rujukan, id as no_reg, tanggal_reg, rawat_inap ".
        "from rs00006 ".
        "where id ='$reg' ");
    $n1 = pg_num_rows($r1);
    if($n1 > 0) $d1 = pg_fetch_object($r1);
    pg_free_result($r1);

    // menghitung kunjungan pasien, sehingga dpt.digolongkan sbg.pasien L-ama/B-aru
    $reg_count = getFromTable("select count(mr_no) from rs00006 ".
                "where mr_no = (select mr_no from rs00006 ".
                "               where id = $reg)");
    $baru  = "Y";
    $loket = "RJN";
    if ($reg_count > 1 ) $baru = "T";
    
   /* ambil data pasien rawat inap: bangsal_id, tgl_masuk dan jumlah hari dirawat */

    if ($d1->rawat_inap == "I" ) {
	        $r2 = pg_query($con,
	            "select bangsal_id, extract(day from current_timestamp - ts_check_in) as hari ".
	            "from rs00010 ".
	            "where no_reg = '$reg' ");
	        $n2 = pg_num_rows($r2);
	        if($n2 > 0) $d2 = pg_fetch_object($r2);
	        pg_free_result($r2);
	        $loket = "RIN";
	    } elseif ($d1->rawat_inap == "N" ) {
	        $loket = "IGD";
    }
   
   /* pengecekan apakah pembayaran sama dengan tagihan yg. belum terbayar */
	$flglunas = "N";
	$amount = getFromTable("select sum(qty*harga) from rs00008 where no_reg = $reg and is_bayar='N'");
	$karcis = getFromTable("SELECT sum(jumlah) as jumlah FROM rs00005 WHERE reg='".$_GET[rg]."' AND is_karcis='Y'  ");
	
	if($_GET[kas]=="ri"){
    $cekBayar = getFromTable("select SUM(jumlah) from rs00005 where reg='".$_GET[rg]."' and (kasir='BYR' or kasir = 'BYD' or kasir ='BYI')")-$karcis;
	}
	else{
		$cekBayar = getFromTable("select SUM(jumlah) from rs00005 where reg='".$_GET[rg]."' and (kasir='BYR' or kasir = 'BYD' or kasir ='BYI')");
	}

   	
	
	$total_pembayaran = $cekBayar + $total_potongan ; 
	
    
    // if ($amount == $_GET["bayar"] + $_GET["keringanan"]) {
    
    if ($amount == $total_pembayaran ) {
    	
		$flglunas = "Y";
	
	// data terakhir (record terakhir) seorang pasian tercatat sbg. penghuni bangsal
	$id_max = getFromTable("select max(id) from rs00010 ".
				"where no_reg = $reg");

		// sfdn, 25-12-2006
		pg_query("update rs00006 set is_bayar='$flglunas', ". 
				"status_bayar=".$_GET["mCAB"].", ".
				"status_akhir_pasien=".$_GET["mKELUAR"]."".
				", user_id = '".$_SESSION[uid]."' ".
				"where id = $reg ");

		pg_query("update rs00005 set is_bayar ='$flglunas' ".
				"where reg = $reg ");

		pg_query("update rs00008 set is_bayar ='$flglunas',user_id = '".$_SESSION[uid]."' ".
			"where no_reg = $reg AND is_bayar ='N'");

		// --- end of 25-12-2006 ---
		// sfdn, 27-12-2006 --> merubah data tanggal keluar pasien (rgl_keluar)
		//  ke CURRENT DATE
		pg_query("update rs00006 set tgl_keluar=  current_timestamp ".
				"where id = $reg ");
		// --- eof 27-12-2006 ---
	}

   
 

  

	echo "<center><br><br><br>";
	echo "<b>Transaksi pembayaran sedang diproses...</b>";
	echo "</center>";

        //exit();
	echo "<script language=javascript>\n";
	echo "<!--\n";
	echo "window.location = \"$SC?p=$PID&rg=".$_GET[rg]."&sub=".$_GET[sub]."&kas=".$_GET[kas]."&cetak=N\";\n";
//	echo "window.location = \"$SC?p=$PID&sub=".$_GET[sub]."&kas=".$_GET[kas]."\";\n";
	echo "-->\n";
	echo "</script>\n";

} else {
    title("Pembayaran");
    echo "<br>";

    echo "<table border=0 width='100%' cellspacing=0 cellpadding=0>";
    echo "<tr>";
    echo "<td><img src=\"images/spacer.gif\" width=50 height=1></td>";
    echo "<td><img src=\"images/spacer.gif\" width=400 height=1></td>";
    echo "<td><img src=\"images/spacer.gif\" width=100 height=1></td>";
    echo "<td><img src=\"images/spacer.gif\" width=100 height=1></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<th class=TBL_HEAD2 width=50>NO</th>";
    echo "<th class=TBL_HEAD2 width=300>URAIAN</th>";
    echo "<th class=TBL_HEAD2 width=100>TAGIHAN</th>";
    echo "<th class=TBL_HEAD2 width=100>KETERANGAN</th>";
    echo "</tr>";

/* 
    if ($_SESSION[uid] == "igd") {
       $loket = "IGD";
       $kasir = "IGD";
       $lyn = "layanan = 10";
    } elseif ($_SESSION[uid] == "kasir1") {
       $loket = "RJL";
       $kasir = "RJL";
       $lyn = "layanan not in ('10','99996','99997','12651','13111')";
    } else {
       $loket = "IGD";
       $kasir = "RIN";
       $lyn = "(layanan = 0 OR layanan = 10)";
       $d->poli = 0;
    }
*/

       
       /*
          10 : IGD			:
       99996 : AKomodasi Rawat Inap     :
       99997 : OBAT /Apotik             :
       12651 : LABORATORIUM             :
       13111 : Radiologi                :
       90000 : retur Obat               :
       
       */


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
        // $lyn = "(layanan = 0 OR layanan = 100 or layanan = 99997 )   ";
       // $lyn = "(layanan = 0 OR layanan = 100) and (layanan not in ('99996','99997','12651','13111'))";
       $lyn = "(layanan not in ('99996','99997','12651','13111'))";
       $d->poli = 0;
    }


    // $poli = getFromTable("select layanan from rs00034 where id=$d->poli");
    $poli = getFromTable("SELECT tdesc FROM rs00001 WHERE tt = 'LYN' and tc=$d->poli");

     //ngilangin karcis di ri najla 07012011
    $karcis = getFromTable("SELECT sum(jumlah) as jumlah FROM rs00005 WHERE reg='".$_GET[rg]."' AND is_karcis='Y'  ");
	if($_GET[kas]=="ri"){
    	$cekBayar = getFromTable("select SUM(jumlah) from rs00005 where reg='".$_GET[rg]."' and 	(kasir='BYR' or kasir = 'BYD' or kasir ='BYI')");
		$cekBayar=$cekBayar-$karcis;
	}
	else{
		$cekBayar = getFromTable("select SUM(jumlah) from rs00005 where reg='".$_GET[rg]."' and (kasir='BYR' or kasir = 'BYD' or kasir ='BYI')");
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
 
 // Tipe Pasien ID 005 = Tipe ASKES
    $cekAskes = getFromTable("select  sum(a.tagihan) from   rs00008  a,  rs00034 b 	         ".
				"where a.no_reg = '".$_GET[rg]."'  AND b.tipe_pasien_id = '007'  ".
				"AND  b.id = to_number(a.item_id,'999999999999') AND a.trans_form <> '-' and a.item_id <>'-'  ");

$karcis = getFromTable("SELECT sum(jumlah) as jumlah FROM rs00005 WHERE reg='".$_GET[rg]."' AND is_karcis='Y'  ");

   $tipepasien = getFromTable("select  b.tipe from   rs00008  a,  rs00006 b 	         ".
			"where a.no_reg = '".$_GET[rg]."'  AND b.id = a.no_reg ");
if ($loket == "IGD") {
  $lyn123 = 100;
/* if ($tipepasien == '007') { $paket1 = 'PAKET I ASKES'; $cekAskes = $cekAskes+11000 ; $hargatiket = 11000;} else { $paket1 = 'KARCIS + PEMERIKSAAN DOKTER'; $hargatiket = $karcis;};*/
  
} elseif ($loket == "RJL") {
  $lyn123 = $kodepoli;
  
if ($lyn123 == 101 or $lyn123 == 105) { 
 /*if ($tipepasien == '007') { $paket1 = 'PAKET I ASKES'; $cekAskes = $cekAskes+11000 ; $hargatiket = 11000;} else { $paket1 = 'KARCIS + PEMERIKSAAN DOKTER'; $hargatiket = $karcis;};*/
} else { 
/*if ($tipepasien == '007') { $paket1 = 'PAKET I ASKES'; $cekAskes = $cekAskes+11000 ; $hargatiket = 11000;} else { $paket1 = 'KARCIS + PEMERIKSAAN DOKTER'; $hargatiket = $karcis;};*/
} 
} else {
  $lyn123 = 0;
}


 if ($tipepasien == '007') { $paket1 = 'PAKET I ASKES'; $cekAskes = $cekAskes ;} else { $paket1 = 'KARCIS + PEMERIKSAAN DOKTER';};
  

//    $cekAskes = getFromTable("select jumlah from rs00005 where reg='".$_GET[rg]."' and kasir='ASK'");
  
  
    $cekPotong = getFromTable("select jumlah from rs00005 where reg='".$_GET[rg]."' and kasir='POT'");

  //  $karcis = getFromTable("SELECT sum(jumlah) as jumlah FROM rs00005 WHERE reg='".$_GET[rg]."' AND is_karcis='Y'  ");
    //if (empty($karcis)) $karcis = 0;
$karcis = $hargatiket;

////
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
		// if ($d1 = pg_fetch_object($r1)) {
		while ($d1 = pg_fetch_object($r1)) {
		
		    $qty = $d1->qty;
		    $harga = $qty * $d1->harga_satuan;
	//	    echo "<tr>";
	//	    if ($oldDate == date("Y-m-d")) {
	//	        echo "<td class=TBL_BODY2>&nbsp;</td>";
	//	    } else {
		//        echo "<td class=TBL_BODY2>".date("d-m-Y")."</td>";
	//	        $oldDate = date("Y-m-d");
	//	    }
	//	    echo "<td class=TBL_BODY2 colspan=9><B>AKOMODASI ".
	//	         date("d-m-Y", pgsql2mktime($d1->ts_check_in))."   s/d   ".
	//	         date("d-m-Y", pgsql2mktime($d1->ts_calc_stop)).
	//	         "</B></td>";
	//	    echo "</tr>";
		
	//	    echo "<tr>";
	//	    echo "<td class=TBL_BODY2>&nbsp;</td>";
	//	    echo "<td class=TBL_BODY2>&nbsp;</td>";
	//	    echo "<td class=TBL_BODY2 colspan=5>$d1->bangsal / $d1->ruangan / ".
	//	         "$d1->bed / $d1->klasifikasi_tarif</td>";
	//	    echo "<td class=TBL_BODY2 width='12%'>".number_format($qty)." HARI</td>";
	//	    echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($harga,2)."</td>";
		  //  echo "<td class=TBL_BODY2 width='12%' align=right>".number_format(0,2)."</td>";
	//	    echo "</tr>";
		    $bangsal_sudah_posting = $bangsal_sudah_posting + $harga;
		   // pg_free_result($r1);
		
		    //pg_fetch_object($r1);
		
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
 //   echo "<tr>";
 //   if ($oldDate == date("Y-m-d")) {
  //      echo "<td class=TBL_BODY2>&nbsp;</td>";
 //   } else {
 //       echo "<td class=TBL_BODY2>".date("d-m-Y")."</td>";
  //      $oldDate = date("Y-m-d");
  //  }
    //echo "<td class=TBL_BODY2 align=CENTER>###</td>";
  //  echo "<td class=TBL_BODY2 colspan=9><B>AKOMODASI ".
  //       date("d-m-Y", pgsql2mktime($d1->ts_check_in))."   s/d   ".
    //     date("d-m-Y").
  //       "  (BELUM POSTING)</B></td>";
  //  echo "</tr>";

 //   echo "<tr>";
 //   echo "<td class=TBL_BODY2>&nbsp;</td>";
 //   echo "<td class=TBL_BODY2>&nbsp;</td>";
    //echo "<td class=TBL_BODY2>&nbsp;</td>";
//    echo "<td class=TBL_BODY2 colspan=5>$d1->bangsal / $d1->ruangan / ".
 //        "$d1->bed / $d1->klasifikasi_tarif</td>";
   // echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." HARI</td>";
	// sfdn, 17-12-2006
//    echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->harga,2)."</td>";
   // echo "<td class=TBL_BODY2 width='12%' align=right>".number_format(0,2)."</td>";
 //   echo "</tr>";
    //$bangsal_belum_posting = $d1->harga;
	// sfdn, 17-12-2006 --> penjumlahan nilai yang yang harus dibayar
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
 
//echo "<tr>";
//echo "<th class=TBL_HEAD3 colspan=8 align=RIGHT>JUMLAH TAGIHAN &nbsp; : &nbsp;</th>";
//echo "<th class=TBL_HEAD3 align=RIGHT>".number_format( $jml_total_Tagihan ,2)."</th>";
//echo "<th class=TBL_HEAD3 align=RIGHT>&nbsp; </th>";
//echo "</tr>";
////

    $akomodasi = getFromTable("select sum(jumlah) as jumlah ".
         "from rs00005 where reg='".$_GET[rg]."' AND is_karcis='N' AND is_obat='N' AND kasir='$kasir' ".
         "AND layanan = 99996 ");

 

    $tindakan = getFromTable("select sum(jumlah) as jumlah ".            
         "from rs00005 where reg='".$_GET[rg]."' AND is_karcis='N' AND is_obat='N' AND (kasir='RIN' OR kasir='RJL' OR kasir='IGD' ) ");

if ($lyn123 == 114 or $lyn123 == 115) { $tindakan = $tindakan/2 ;} else {$tindakan = $tindakan;};

 /*
 echo "select sum(jumlah) as jumlah ".            
         "from rs00005 where reg='".$_GET[rg]."' AND is_karcis='N' AND is_obat='N' AND (kasir='RIN' OR kasir='RJL' OR kasir='IGD' ) ".
         "AND $lyn ";
   
         echo $tindakan ; 
         echo "=============";
         */
         
    $laborat = getFromTable("select sum(jumlah) as jumlah ".
         "from rs00005 where reg='".$_GET[rg]."' AND is_karcis='N' AND is_obat='N' AND kasir='$kasir' ".
         "AND layanan = 12651 ");

    $radiologi = getFromTable("select sum(jumlah) as jumlah ".
         "from rs00005 where reg='".$_GET[rg]."' AND is_karcis='N' AND is_obat='N' AND kasir='$kasir' ".
         "AND layanan = 13111 ");

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

$cek_loket = getFromTable("select kasir from rs00005 where reg = '".$_GET[rg]."' and is_karcis = 'Y'");

/*
if ($cek_loket == "RJL") {
if ($karcis == 4500) {
   if ($obatx[1]>=2000) {
	// sfdn, 27-12-2006 --> dgn. pertimbangan kondisi (hanya di RS Karanganyar), maka
	// total obat tidak dipotong 2000
        //$tot_obat = $tot_obat - 2000;
      $tot_obat = $tot_obat; 
	// --- eof 27-12-2006
   }
} elseif ($karcis == 9000) {
   if ($obatx[1]>=4000) {
	// sfdn, 27-12-2006 --> dgn. pertimbangan kondisi (hanya di RS Karanganyar), maka
	// total obat tidak dipotong 4000
        //$tot_obat = $tot_obat - 4000;
      $tot_obat = $tot_obat; 
	// --- eof 27-12-2006
   }
}

}
*/


    // $total = $karcis + $tindakan + $laborat + $akomodasi + $radiologi + $tot_obat - $retur;
$nomor = 1;
//menghilangkan tampilan harga tiket dikasir by djeko 11111
//if ($loket == "RJL" or $loket == "IGD") 
    // {
    //echo "<tr>";
    //echo "<td class=TBL_BODY2 align=center>$nomor</td>";
	//$nomor = $nomor + 1;
    //echo "<td class=TBL_BODY2 align=left>$paket1 $namadokter</td>";
    //echo "<td class=TBL_BODY2 align=right>".number_format($karcis,2)."</td>";
    //echo "<td class=TBL_BODY2 align=left>&nbsp;</td>";
   //echo "</tr>";  }

    echo "<tr>";
    echo "<td class=TBL_BODY2 align=center>$nomor</td>";
	$nomor = $nomor + 1;
    echo "<td class=TBL_BODY2 align=left>LAYANAN / TINDAKAN MEDIS</td>";
    echo "<td class=TBL_BODY2 align=right>".number_format($tindakan,2)."</td>";
    echo "<td class=TBL_BODY2 align=left> &nbsp;</td>";
    echo "</tr>";


    echo "<tr>";
    echo "<td class=TBL_BODY2 align=center>$nomor</td>";
	$nomor = $nomor + 1;
    echo "<td class=TBL_BODY2 align=left>OBAT</td>";
    echo "<td class=TBL_BODY2 align=right>".number_format($obat,2)."</td>";
    echo "<td class=TBL_BODY2 align=left>&nbsp;</td>";
    echo "</tr>";
/*
    echo "<tr>";
    echo "<td class=TBL_BODY2 align=center>3</td>";
    echo "<td class=TBL_BODY2 align=left>RETUR Obat</td>";
    echo "<td class=TBL_BODY2 align=right>-".number_format($retur,2)."</td>";
    echo "<td class=TBL_BODY2 align=left>&nbsp;</td>";
    echo "</tr>";
*/
    echo "<tr>";
    echo "<td class=TBL_BODY2 align=center>$nomor</td>";
	$nomor = $nomor + 1;
    echo "<td class=TBL_BODY2 align=left>LABORATORIUM</td>";
    echo "<td class=TBL_BODY2 align=right>".number_format($laborat,2)."</td>";
    echo "<td class=TBL_BODY2 align=left>&nbsp;</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class=TBL_BODY2 align=center>$nomor</td>";
	$nomor = $nomor + 1;
    echo "<td class=TBL_BODY2 align=left>RADIOLOGI</td>";
    echo "<td class=TBL_BODY2 align=right>".number_format($radiologi,2)."</td>";
    echo "<td class=TBL_BODY2 align=left>&nbsp;</td>";
    echo "</tr>";

    // if ($_SESSION[uid] == "kasir2" || $_SESSION[uid] == "root") {
    if ($_GET["kas"] == "ri" || $_GET["kas"] == "root") {    	
    echo "<tr>";
    echo "<td class=TBL_BODY2 align=center>$nomor</td>";
	$nomor = $nomor + 1;
    echo "<td class=TBL_BODY2 align=left>AKOMODASI RAWAT INAP</td>";
    echo "<td class=TBL_BODY2 align=right>".number_format($jml_total_Tagihan,2)."</td>";
    echo "<td class=TBL_BODY2 align=left>&nbsp;</td>";
    echo "</tr>";
    }

    echo "<tr>";
    echo "<td class=TBL_HEAD2 align=center>&nbsp;</td>";
    echo "<td class=TBL_HEAD2 align=right>TOTAL :</td>";
    echo "<td class=TBL_HEAD2 align=right>".number_format($total,2)."</td>";
    echo "<td class=TBL_HEAD2 align=left>&nbsp;</td>";
    echo "</tr>";

    if ($d->tipe_desc == "ASKES") {
    echo "<tr>";
    echo "<td class=TBL_BODY2 align=center>&nbsp;</td>";
    echo "<td class=TBL_BODY2 align=right>ASKES :</td>";
    echo "<td class=TBL_BODY2 align=right>".number_format($cekAskes,2)."</td>";
    echo "<td class=TBL_BODY2 align=left>&nbsp;</td>";
    echo "</tr>";
    }

    echo "<tr>";
    echo "<td class=TBL_BODY2 align=center>&nbsp;</td>";
    echo "<td class=TBL_BODY2 align=right>POTONGAN :</td>";
    echo "<td class=TBL_BODY2 align=right>".number_format($cekPotong,2)."</td>";
    echo "<td class=TBL_BODY2 align=left>&nbsp;</td>";
    echo "</tr>";


   
	 $tagihan = $total  - ($cekAskes + $cekBayar + $cekPotong) ; ; 
	
    
  
    echo "<tr>";
    echo "<td class=TBL_BODY2 align=center>&nbsp;</td>";
    echo "<td class=TBL_BODY2 align=right>PEMBAYARAN :</td>";
    echo "<td class=TBL_BODY2 align=right>".number_format($cekBayar,2)."</td>";
    echo "<td class=TBL_BODY2 align=left>&nbsp;</td>";
    echo "</tr>";


    echo "<tr>";
    echo "<td class=TBL_HEAD2 align=center>&nbsp;</td>";
    echo "<td class=TBL_HEAD2 align=right>TAGIHAN :</td>";
    echo "<td class=TBL_HEAD2 align=right>".number_format($tagihan,2)."</td>";
    echo "<td class=TBL_HEAD2 align=left>&nbsp;</td>";
    echo "</tr>";




    //while ($qr = pg_fetch_object($q)) {


    //}

    echo "</table><br>";


echo "\n<script language='JavaScript'>\n";
echo "function hitung1() {\n";
echo "       var jml,potongan ;   \n";
echo "       potongan = Math.round(document.Form1.keringanan.value) + Math.round(document.Form1.askes.value)  ;  \n";
echo "       jml = Math.round(document.Form1.tmp_tagihan.value) - potongan ;    ; \n";
echo "       document.Form1.bayar.value =  Math.round(jml);     \n";
echo "       document.Form1.sisa.value = Math.round(document.Form1.tmp_tagihan.value) - (Math.round(document.Form1.bayar.value) + potongan) ;     \n";
echo "        \n";
echo "}\n";

echo "function hitung2() {\n";
echo "       var jml,potongan ;   \n";
echo "       potongan = Math.round(document.Form1.keringanan.value) + Math.round(document.Form1.askes.value)  ;  \n";
echo "       jml = Math.round(document.Form1.tmp_tagihan.value) - potongan ;    ; \n";
echo "       document.Form1.sisa.value = Math.round(document.Form1.tmp_tagihan.value) - (Math.round(document.Form1.bayar.value) + potongan) ;     \n";
echo "        \n";
echo "}\n";
echo "</script>\n";

    echo "<table border=0 width='100%' cellspacing=0 cellpadding=0>";
    echo "<tr><td valign=top width='50%'>";

    $t = new Form($SC, "GET", "NAME=Form1");
    $t->PgConn = $con;
    $t->hidden("p", $PID);
    $t->hidden("mPERIODE",$_GET["mPERIODE"]);
    $t->hidden("rg",$_GET["rg"]);
    $t->hidden("sub",$_GET["sub"]);
    $t->hidden("e","edit");
 //   $t->hidden("bayar",$_GET["bayar"]);
    $t->hidden("kas",$_GET["kas"]);
    
    $t->hidden("tmp_tagihan",$tagihan);
######################################################################################efrizal	
    $t->selectSQL("mCAB", "Cara Pembayaran",
        "select '' as tc, '' as tdesc union ".
        "select a.tc , a.tdesc ".
        "from rs00001 a, rs00006 b ".
        "where a.tt='CAB' and b.id ='".$_GET["rg"]."'  and case when b.tipe = '013' then (a.tc='001' or a.tc='012') else a.tc='001' end ", ($_GET["mCAB"]) ? $_GET[mCAB] : "001","OnChange=\"setciu(this.value);\"");
######################################################################################efrizal

		
    $t->selectSQL("mKELUAR", "Status Akhir Pasien",
        "select '' as tc, '' as tdesc union ".
        "select tc , tdesc ".
        "from rs00001 ".
        "where tt='SAP' and tc!='000'", ($_GET["mKELUAR"]) ? $_GET[mSAP] : $d->status_akhir_pasien ," disabled");

    $t->selectDate("tanggal", "Tanggal", getdate(), "");

    $t->hidden("askes","0");
      
    $t->text("keringanan","Asuransi",12,12,"0","style='text-align:right' onchange='hitung1()' ");
    $t->text("bayar","Pembayaran",12,12,$tagihan,"style='text-align:right'  onchange='hitung2()' ");
    $t->text("sisa","SISA",12,12,0,"style='text-align:right' disabled" );    

if ($tagihan > 0 ){
    $t->submit(" BAYAR ", "HREF='index2.php".
                "?p=$PID&e=edit&mPERIODE=".$_GET["mPERIODE"].

                "&rg=".$_GET["rg"].
		"&bayar=".$_GET["bayar"].
 "&cetak=Y".
                "&sub=".$_GET["sub"]."'");
}	
    $t->execute();
####################################################################################efrizal
$zal = getfromtable("select no_asuransi from rs00006 where id ='".$_GET["rg"]."' ");
$efr = getfromtable("select case when '".$tagihan."' <= plafon_sisa then '".$tagihan."' else plafon_sisa end 
										from ciu where no_asuransi ='".$zal."' ");
 ?>       <SCRIPT language="JavaScript">

            function setciu( v )
            {
			if (v == "012") {
			document.Form1.keringanan.value = <?=$efr?>;
			document.Form1.bayar.value = <?=$tagihan-$efr?>;
			}else{
			document.Form1.keringanan.value = "0";
			document.Form1.bayar.value = <?=$tagihan?>;
			}
            }
        </SCRIPT><?
########################################################################################efrizal
    echo "</td><td align=right valign=top>";

echo "\n<script language='JavaScript'>\n";
echo "function cetakaja(tag) {\n";
echo "    sWin = window.open('includes/cetak.335.4.php?rg=' + tag+'&kas=".$_GET["kas"]."', 'xWin',".
     " 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

echo "\n<script language='JavaScript'>\n";
echo "function cetakrincian(tag) {\n";
echo "    sWin = window.open('includes/cetak.rincian.php?rg=' + tag+'&kas=".$_GET["kas"]."', 'xWin',".
     " 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

?>
<table>
    <tr>
	<? if ($tagihan<=0) {?>
        <td align="center"> Cetak Kwitansi</td>
        <?}?>
        <td align="center"> &nbsp</td>
        <td align="center">Cetak Rincian</td>
    </tr>

    <tr>
	<? if ($tagihan<=0) {?>
        <td align="center"> <a href="javascript: cetakrincian(<? echo (int) $_GET[rg];?>)" ><img src="images/cetak.gif" border="0"></a></td>
		<?}?>
        <td align="center"> &nbsp</td>
        <td align="center"><a href="javascript: cetakaja(<? echo (int) $_GET[rg];?>)" ><img src="images/cetak.gif" border="0"></a></td>
    </tr>
</table>

<?
    echo "</td></tr></table>";
}

?>


