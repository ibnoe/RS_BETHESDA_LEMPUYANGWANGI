<?php // Nugraha, Sat May  1 09:58:26 WIT 2004
      // sfdn, 01-06-2004

$PID = "335";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

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
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
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
        "WHERE a.id = lpad($reg,10,'0')");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

// begin tokit update
/*
$sisa = getFromTable(
					 "select sum((qty*harga)-pembayaran) as sisa ".
					 "from rs00008  ".
					 "where to_number(no_reg,'999999999999') = $reg and ".
					 "trans_type IN ('LTM','BYR','OB1')");
*/
$sisa = getFromTable(
					 "select sum((qty*harga)-pembayaran) as sisa ".
					 "from rs00008  ".
					 "where to_number(no_reg,'999999999999') = $reg and ".
					 "trans_type IN ('LTM','BYR','OB1','POS')");

$xtagih = getFromTable(
					 "select sum(qty*harga) as xtagih ".
					 "from rs00008  ".
					 "where to_number(no_reg,'999999999999') = $reg and ".
					 "(trans_type = 'OB2' and referensi = '')");
$sisa =  $xtagih + $sisa;
// end of tokit update

if($_GET["e"] == "edit") {

	if ( ($_GET['mCAB'] == "") || ($_GET['mKELUAR'] == "") ) {
	
	
	
	echo "<script language=javascript>\n";
	echo "<!--\n";
	echo "window.location = \"index2.php?p=$PID&rg=".$_GET['rg']."&sub=".$_GET['sub']."\";\n";
	echo "-->\n";
	echo "</script>";
	
	exit();
	
	}


    // ambil data pasien di master data registrasi rs00006
    $r1 = pg_query($con,
         "select tipe, rujukan, id as no_reg, tanggal_reg, rawat_inap ".
        "from rs00006 ".
        "where to_number(id,'9999999999') = $reg ");
    $n1 = pg_num_rows($r1);
    if($n1 > 0) $d1 = pg_fetch_object($r1);
    pg_free_result($r1);

    // menghitung kunjungan pasien, sehingga dpt.digolongkan sbg.pasien L-ama/B-aru
    $reg_count = getFromTable("select count(mr_no) from rs00006 ".
                "where mr_no = (select mr_no from rs00006 ".
                "               where to_number(id,'9999999999') = $reg)");
    $baru  = "Y";
    $loket = "RJN";
    if ($reg_count > 1 ) $baru = "T";
    if ($d1->rawat_inap == "I" ) {
        // ambil data pasien rawat inap: bangsal_id, tgl_masuk dan jumlah hari dirawat
        $r2 = pg_query($con,
            "select bangsal_id, extract(day from current_timestamp - ts_check_in) as hari ".
            "from rs00010 ".
            "where to_number(no_reg,'9999999999') = $reg ");
        $n2 = pg_num_rows($r2);
        if($n2 > 0) $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $loket = "RIN";
    } elseif ($d1->rawat_inap == "N" ) {
        $loket = "IGD";
    }
	// pengecekan apakah pembayaran sama dengan tagihan yg. belum terbayar
	$flglunas = "N";
	$amount = getFromTable("select sum(qty*harga) from rs00008 ".
							"where to_number(no_reg,'999999999999')= $reg and ".
							"	is_bayar='N'");
	if ($amount == $_GET["bayar"]) {
		$flglunas = "Y";
	}						
    // data terakhir (recor terakhir) seorang pasian tercatat sbg. penghuni bangsal
    $id_max = getFromTable("select max(id) from rs00010 ".
                            "where to_number(no_reg,'9999999999') = $reg");

    $SQL1 = "update rs00006 set is_bayar='$flglunas', status_bayar=lpad(".$_GET["mCAB"].",3,'0'), ".
            "   status_akhir_pasien=lpad(".$_GET["mKELUAR"].",3,'0') ".
            "where to_number(id,'9999999999') = $reg ";
    
    // update rs00008 untuk pasien RAWAT JALAN DAN IGD
    $SQL2 = "insert into rs00008 (id,trans_type, is_inout, qty,  ".
            "   tanggal_entry, is_baru, no_reg, tanggal_trans, status_out, ".
            "   cara_bayar_id, trans_group, is_bayar ) ".
            "values (nextval('rs00008_seq'),'$loket','O',1, '$d1->tanggal_reg', ".
            "'$baru','$d1->no_reg', ".
            "CURRENT_DATE,lpad(".$_GET["mKELUAR"].",3,'0'),lpad(".$_GET["mCAB"].",3,'0'), nextval('rs00008_seq_group'),'$flglunas')";

    // nilai pembayaran dicatat juga di table rs00006.jml_akhir_pembayaran
    $SQL4 =
            "update rs00006 set tgl_keluar=CURRENT_DATE, jml_bayar_akhir=".$_GET["bayar"].
			" where to_number(id,'9999999999') = $reg";

    // pencatatan pembayaran di table rs00008
    $SQL5 = "insert into rs00008 (id, qty,trans_type, tanggal_trans, no_reg, referensi, ".
            "   trans_form, pembayaran,trans_group,is_bayar,no_kwitansi ) ".
            "values (nextval('rs00008_seq'),0,'BYR',CURRENT_DATE,'$d1->no_reg', 'KASIR', ".
            "'$PID',".$_GET["bayar"].",nextval('rs00008_seq_group'),'$flglunas',nextval('rs88888_seq'))";

    $SQL6 = "update rs00008 set is_bayar = '$flglunas' where to_number(no_reg,'9999999999') = $reg";
	
	if ($loket == "RIN") {
		$jum_hari = $d2->hari;
		if ($jum_hari < 1) $jum_hari = 1;
        $SQL2 =
            "insert into rs00008 (id,trans_type, is_inout, qty,  ".
            "   tanggal_entry, is_baru,no_reg, tanggal_trans, status_out, ".
            "   cara_bayar_id,lama_dirawat, bangsal_id, trans_group,is_bayar ) ".
            "values (nextval('rs00008_seq'),'$loket','O',1, '$d1->tanggal_reg', ".
            "   '$baru','$d1->no_reg', ".
            "   CURRENT_DATE,lpad(".$_GET["mKELUAR"].",3,'0'),lpad(".$_GET["mCAB"].",3,'0'), ".
            "   '$jum_hari','$d2->bangsal_id', nextval('rs00008_seq_group'),'$flglunas')";

        $SQL3 =
            "update rs00010 set ts_calc_stop=CURRENT_DATE ".
            "where id = $id_max";
    }

    pg_query($con, $SQL1);
    pg_query($con, $SQL2);
    pg_query($con, $SQL4);
    pg_query($con, $SQL5);
	pg_query($con, $SQL6);
	
    if ($loket == "RIN") {
        pg_query($con, $SQL3);
    }
	
	if (file_exists("includes/$PID.".$_POST["sub"].".php")) include_once("includes/$PID.".$_POST["sub"].".php");
    /*
	$t = new Form($SC, "GET", "NAME=Form1");
    $t->hidden("p", $PID);
    $t->hidden("mPERIODE",$_GET["mPERIODE"]);
    $t->hidden("rg",$_GET["rg"]);
    $t->hidden("sub",$_GET["sub"]);
    $t->hidden("e","edit");
    $t->execute();
    exit;
	*/
	
	echo "<center><br><br><br>";
	echo "<b>Transaksi pembayaran telah diproses...</b>";
	echo "</center>";
	
} else {
    title("Pembayaran");
    echo "<br>";

    echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='50%'>";
    $f = new ReadOnlyForm();
    $f->text("Nama", $d->nama);
    $f->text("Jenis Kelamin", $d->jenis_kelamin == "L" ? "Laki-Laki" : "Perempuan");
    $f->text("Umur", umur($d->umur));
    $f->text("Agama", $d->agama);
    $f->text("Alamat", $d->alm_tetap);
    $f->text("Kota", $d->kota_tetap);
    $f->text("Kode Pos", $d->pos_tetap);
    $f->text("Telepon", $d->tlp_tetap);
    $f->execute();
    echo "</td><td align=left valign=top width='50%'>";

    $t = new Form($SC, "GET", "NAME=Form1");
    $t->PgConn = $con;
    $t->hidden("p", $PID);
    $t->hidden("mPERIODE",$_GET["mPERIODE"]);
    $t->hidden("rg",$_GET["rg"]);
    $t->hidden("sub",$_GET["sub"]);
    $t->hidden("e","edit");
    $t->hidden("bayar",$_GET["bayar"]);
    $t->selectSQL("mCAB", "Cara Pembayaran",
        "select '' as tc, '' as tdesc union ".
        "select tc , tdesc ".
        "from rs00001 ".
        "where tt='CAB' and tc!='000'", $_GET["mCAB"],"");

    $t->selectSQL("mKELUAR", "Status Akhir Pasien",
        "select '' as tc, '' as tdesc union ".
        "select tc , tdesc ".
        "from rs00001 ".
        "where tt='SAP' and tc!='000'", $_GET["mKELUAR"],"");

    $t->selectDate("tanggal", "Tanggal", getdate(), "");
    $t->text("bayar","Tagihan",12,12,$sisa,"style='text-align:right'");
    $t->submit(" OK ", "HREF='index2.php".
                "?p=$PID&e=edit&mPERIODE=".$_GET["mPERIODE"].
                "&rg=".$_GET["rg"].
                "&sub=".$_GET["sub"]."'");
    $t->execute();

    echo "</td></tr></table>";
}






?>
