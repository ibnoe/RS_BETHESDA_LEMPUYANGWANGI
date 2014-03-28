<?php // Nugraha, Thu Apr 22 11:58:22 WIT 2004
      // sfdn, 23-04-2004: tambah harga obat
      // sfdn, 09-05-2004
	  // sfdn, 31-05-2004

session_start();
$PID = "325";

//echo "xxxx: ".$_POST[byr]; exit();

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

foreach ($_SESSION["layanan"] as $v) {
        $total += $v["total"];

}

$tr = new PgTrans;
$tr->PgConn = $con;
pg_query("select nextval('rs00008_seq_group')");
pg_query("select nextval('kasir_seq')");

$tr->addSQL("select nextval('rs88888_seq')");

// LAYANAN TINDAKAN MEDIS DAN PEMBAGIAN JASA MEDIS
if (is_array($_SESSION["layanan"])) {

   if (isset($_POST["byr"])) {
		$v1 = ",is_bayar,no_kwitansi";
		$v2 = ",'Y',currval('rs88888_seq')";

                $kodepoli = getFromTable("select poli from rs00006 where id = lpad('".$_POST["rg"]."',10,'0')");
		if ($_POST[rawatan] == "IGD") {
                   $loket = "IGD";
		} else {
                   $loket = "RJL";
		}

    	        pg_query("INSERT INTO rs00005 VALUES( currval('kasir_seq'), lpad('".$_POST["rg"]."',10,'0'), ".
                      "CURRENT_DATE, '$loket', 'N', 'Y', $kodepoli, $total, 'Y')") or die("eror atuh");

   } else {
		$v1 = "";
		$v2 = "";

		pg_query("update rs00006 set is_karcis='Y' where id = lpad('".$_POST["rg"]."',10,'0')");
		if ($_POST[rawatan] = "IGD") {
                   pg_query("INSERT INTO rs00005 VALUES( currval('kasir_seq'), lpad('".$_POST["rg"]."',10,'0'), ".
                      "CURRENT_DATE, 'RIN', 'N', 'Y', 0, '$total', 'N')") or die("eror atuh");
		}


   }

    foreach ($_SESSION["layanan"] as $v) {
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran $v1 ".
            ") values (".
                "nextval('rs00008_seq'), 'LTM', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, lpad('".$_POST["rg"]."',10,'0'), '".$v["id"]."', '', " .
                $v["jumlah"].",".$v["harga"].",".$v["total"].",0 $v2)"
        );
    }

    $r1 = pg_query($con,
        "select * from rs00020 where pembagian_jasa_medis_id = '".$_SESSION["pjmtype"]."'");
    while ($d1 = pg_fetch_object($r1)) {
        if (is_array($_SESSION["pjm"]["$d1->id"])) {
            foreach ($_SESSION["pjm"]["$d1->id"] as $v) {
                if ($d1->is_person == "Y" && $v["id"] != "---") {
                    $tr->addSQL(
                        "insert into rs00033 (" .
                            "id, trans_group, pembagian_jasa_medis_id, nip " .
                        ") values (" .
                            "nextval('rs00033_seq'), currval('rs00008_seq_group'),'$d1->id', '".$v["id"]."')"
                    );
                }
            }
        }
    }
    pg_free_result($r1);
}
// URAIAN DIAGNOSA
if (isset($_SESSION["s2note"])) {
    $tr->addSQL(
        "insert into rs00008 (" .
            "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
            "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
            "qty,           harga,       tagihan,    pembayaran ".
        ") values (".
            "nextval('rs00008_seq'), 'DIA', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
            "CURRENT_DATE, CURRENT_TIME, lpad('".$_POST["rg"]."',10,'0'), '', '', " .
            "0,0,0,0)"
    );
    $tr->addSQL(
        "insert into rs00009 (trans_id, description) " .
        "values (currval('rs00008_seq'), '".$_SESSION["s2note"]."')");
}

// ICD
if (is_array($_SESSION["icd"])) {
    foreach ($_SESSION["icd"] as $v) {
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran ".
            ") values (".
                "nextval('rs00008_seq'), 'ICD', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, lpad('".$_POST["rg"]."',10,'0'), '".$v["id"]."', '', " .
                "0,0,0,0)"
        );
    }
}

// RESEP
if (is_array($_SESSION["obat"])) {
    foreach ($_SESSION["obat"] as $v) {
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran ".
            ") values (".
                "nextval('rs00008_seq'), 'OB1', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, lpad('".$_POST["rg"]."',10,'0'), '".$v["id"]."', '', " .
                "'".$v["jumlah"]."',".$v["harga"].",0,0)"
        );

        if (strlen($v["dosis"]) > 0) {
            $tr->addSQL(
                "insert into rs00009 (trans_id, description) " .
                "values (currval('rs00008_seq'), '".$v["dosis"]."')");
        }

        // tambahan sfdn 09-05-2004
        $tr->addSQL("update rs00016 set qty_keluar = qty_keluar + ".$v["jumlah"].
            " where obat_id = '".$v["id"]."'");
        // akhir tambahan
        //$tr->showSQL();
    }
}

// PEMBAYARAN

if (isset($_POST["byr"])) {
    $tr->addSQL(
        "insert into rs00008 (" .
            "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
            "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
            "qty,           harga,       tagihan,    pembayaran, is_bayar, no_kwitansi ".
        ") values (".
            "nextval('rs00008_seq'), 'BYR', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
            "CURRENT_DATE, CURRENT_TIME, lpad('".$_POST["rg"]."',10,'0'), '', '', " .
            "0,0,0,'".$_POST["byr"]."', 'Y',currval('rs88888_seq'))");

    // tambahan sfdn, 31-05-2004
    $reg_count = getFromTable("select count(mr_no) from rs00006 ".
                "where mr_no = (select mr_no from rs00006 ".
                "where id = lpad('".$_POST["rg"]."',10,'0'))");
    $baru    = "Y";
    if ($reg_count > 1 ) $baru = "T";

    $r1 = pg_query($con,
        "select tipe, id as no_reg, tanggal_reg, rawat_inap, rujukan ".
        "from rs00006 ".
        "where id = lpad('".$_POST["rg"]."',10,'0')");

    $n1 = pg_num_rows($r1);
    if($n1 > 0) $d1 = pg_fetch_object($r1);
    pg_free_result($r1);
	

    $loket	 = "RJN";
    if ($d1->rawat_inap == "N") {
	        $loket	 = "IGD";
	} elseif ($d->rawat_inap == "I"){
		$loket	 = "RIN";
    }
    $tr->addSQL(
        "insert into rs00008 (" .
   	"id,trans_type, is_inout, qty, no_reg, is_baru, tanggal_trans, datang_id, trans_group,is_bayar )".
	    "values (" .
            "nextval('rs00008_seq'),'$loket', 'O', 1,lpad($d1->no_reg,10,'0'), ".
            "'$baru',CURRENT_DATE,'$d1->rujukan',currval('rs00008_seq_group'), 'Y')");
    $tr->addSQL(
        "update rs00006 set is_karcis='Y' where id = lpad('".$_POST["rg"]."',10,'0')");

	// pada saat pasien pulang/keluar, maka beberapa info yg. harus di-record
	// adalah: status_keluar: Sembuh, Mati, Dirujuk dll., cara bayar: Tunai, Gratis dll
	// jika pasiennya adalah RI, maka ditambah dgn. info: lama dirawat
	// dikarenakan saat ini belum ada fasilitas untuk mencatat info tsb,
	// maka cukup SQL seperti di atas
	// jika telah ada info-info tsb, maka SQL di bawah ini yg. dipergunakan

	/*

			"insert into rs00008 (" .
			"trans_type, is_inout, qty ,  no_reg, is_baru, ".
			"tanggal_trans, ".
			"status_out, cara_bayar_id, gol_tindakan_id ".
		") values (" .
			"'$loket', 'O', 1,   lpad($d1->no_reg,10,'0'), ".
			"'$baru',CURRENT_DATE,".
			"<<<<kode tindakan>>>>,<<<<kode status keluar>>>>,<<<<kode cara bayar>>>>,".
			"<<<<kode gol.tindakan,BESAR,KECIL dll.>>>>)";
   */

	// akhir tambahan
	//$xxx = pg_query("select currval('rs00008_seq_group')");

    $r1 = pg_query($con,
        "select * from rs00008 where trans_group = currval('rs00008_seq_group')");
    while ($d1 = pg_fetch_object($r1)) {
                 $tr->addSQL(
                      "update rs00008 set is_bayar = 'Y'");
   }

}
//	pg_free_result($r1);
// $tr->showSQL();
if ($tr->execute()) {
    $_SESSION["dialog"]["title"] = "Transaksi telah diproses...";
    $_SESSION["dialog"]["desc"] = "Klik tombol dibawah ini untuk melakukan transaksi lagi.";
    if (is_array($_SESSION["obat"])) {
        $_SESSION["dialog"]["desc"] = "Nomor resep adalah ".
            getFromTable("select currval('rs00008_seq_group')") . "<br>" .
            $_SESSION["dialog"]["desc"];
    }
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    unset($_SESSION["layanan"]);
    unset($_SESSION["s2note"]);
    unset($_SESSION["icd"]);
    unset($_SESSION["obat"]);
    header("Location: ../index2.php?p=dialog");
    exit;
} else {
    echo $tr->ErrMsg;
}

?>
