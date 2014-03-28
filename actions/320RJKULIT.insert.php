<?php // Nugraha, Thu Apr 22 11:58:22 WIT 2004
      // sfdn, 23-04-2004: tambah harga obat
      // sfdn, 09-05-2004
	  // sfdn, 31-05-2004

session_start();
$PID = "320RJKULIT";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$tokit = pg_query("select nextval('rs00008_seq_group')");
//$tokit = pg_query("select nextval('rs00008_seq')");
pg_query("select nextval('kasir_seq')");
//pg_query("select nextval('rs00030_seq')");


$tr = new PgTrans;
$tr->PgConn = $con;
//$tr->addSQL("select nextval('rs00008_seq_group')");
//$tr->addSQL("select nextval('rs88888_seq')");


// LAYANAN TINDAKAN MEDIS DAN PEMBAGIAN JASA MEDIS
if (is_array($_SESSION["layanan"])) {
   if ($_POST["sub"] == "byr") {
		$v1 = ",is_bayar,no_kwitansi";
		$v2 = ",'Y',currval('rs88888_seq')";
   } else {
		$v1 = "";
		$v2 = "";
   }


   foreach ($_SESSION["layanan"] as $v) {
        $total += $v["total"];

   }

   $kodepoli = getFromTable("select poli from rs00006 where id = '".$_POST["rg"]."'");
   if ($_POST[rawatan] == "IGD") {
      $loket = "IGD";
   } elseif ($_POST[rawatan] == "Rawat Inap") {
      $loket = "RIN";
      $kodepoli = 0;
   } else {
      $loket = "RJL";
   }

   if ($_SESSION[gr] == "laborat") {
      $kodepoli = "12651";
      $PID = "LAB";      
   } elseif ($_SESSION[gr] == "radiologi") {
      $kodepoli = "13111";
      $PID = "RAD";
   }


   // insert to rs00005
   //pg_query("INSERT INTO rs00005 VALUES( currval('kasir_seq'), '".$_POST["rg"]."', ".
     //   "CURRENT_DATE, '$loket', 'N', 'N', $kodepoli, $total, 'N')") or die("eror atuh");

    // insert to rs00008
    foreach ($_SESSION["layanan"] as $v) {
        if ($v["nip"]) {
           $dokter = $v["nip"];
        } else {
           $dokter = 0;
        }

        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran, no_kwitansi ".
            ") values (".
                "nextval('rs00008_seq'), 'LTM', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '".$v["id"]."', '', " .
                $v["jumlah"].",".$v["harga"].",".$v["total"].", 0, $dokter)"
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

                    $rs00027_id = getFromTable(
            "select rs00027_id ".
	    "from rs00017  ".
	    "where nip='".$v["id"]."' ");

                    $rs00017_id = getFromTable(
            "select id ".
	    "from rs00017  ".
	    "where nip='".$v["id"]."' ");

                    $jmf_id = getFromTable(
            "select jabatan_medis_fungsional_id ".
	    "from rs00017  ".
	    "where nip='".$v["id"]."' ");

            //echo "xx: $rs00027_id , $rs00017_id , $jmf_id";

                    // angka kredit insert to table (Catatan Medik)

	            if ($_SESSION[gr] == "ri" || $_SESSION[gr] == "rj" || $_SESSION[gr] == "igd") {

                    $tr->addSQL(
                        "insert into rs00030 (" .
                            "id, rs00025_id, rs00027_id, rs00017_id " .
                        ") values (" .
                            "nextval('rs00030_seq'), 23, $rs00027_id, '$rs00017_id')"
                    );

                    if ($jmf_id == "001") {
                    //echo "xxx".$v[id];
                    $tr->addSQL(
                        "insert into rs00030 (" .
                            "id, rs00025_id, rs00027_id, rs00017_id " .
                        ") values (" .
                            "nextval('rs00030_seq'), 9, $rs00027_id, '$rs00017_id')"
                    );

                    } elseif ($jmf_id == "002") {

                    $tr->addSQL(
                        "insert into rs00030 (" .
                            "id, rs00025_id, rs00027_id, rs00017_id " .
                        ") values (" .
                            "nextval('rs00030_seq'), 1, $rs00027_id, $rs00017_id)"
                    );

                    }


                    } else {

                    $tr->addSQL(
                        "insert into rs00030 (" .
                            "id, rs00025_id, rs00027_id, rs00017_id " .
                        ") values (" .
                            "nextval('rs00030_seq'), 24, $rs00027_id, $rs00017_id)"
                    );

                    // visite
                    $tr->addSQL(
                        "insert into rs00030 (" .
                            "id, rs00025_id, rs00027_id, rs00017_id " .
                        ") values (" .
                            "nextval('rs00030_seq'), 61, $rs00027_id, $rs00017_id)"
                    );



                    }


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
            "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '', '', " .
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
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '".$v["id"]."', '', " .
                "0,0,0,0)"
        );
    }
}

// RESEP  /  OBAT
if (is_array($_SESSION["obat"])) {

    // tokit punya
   foreach ($_SESSION["obat"] as $v) {
        $total += $v["total"];

   }

   $kodepoli = getFromTable("select poli from rs00006 where id = '".$_POST["rg"]."'");
   $cek_karcis = getFromTable("select jumlah from rs00005 where reg = '".$_POST["rg"]."' and is_karcis = 'Y'");



   if ($_POST[rawatan] == "IGD") {
      $loket = "IGD";
   } elseif ($_POST[rawatan] == "Rawat Inap") {
      $loket = "RIN";
      $kodepoli = 0;
   } else {
      $loket = "RJL";
   }
   //pg_query("INSERT INTO rs00005 VALUES( currval('kasir_seq'), '".$_POST["rg"]."', ".
     //   "CURRENT_DATE, '$loket', 'Y', 'N', 99997, $total, 'N')") or die("eror atuh");
   // end tokit punya
   
   // potongan obat karena obat paket
   $cekPotObat = getFromTable("select jumlah from rs00005 ".
			"where reg = '".$_POST["rg"]."' and layanan = 99995 ");
   $totalObat = getFromTable("select sum(jumlah) from rs00005 ".
			"where reg = '".$_POST["rg"]."'".
			"	and is_obat = 'Y' and layanan != 99995");
   if ($cek_karcis == 4500) {
	// sfdn, 27-12-2006 --> dgn. pertimbangan kondisional (cocok untuk RS Karanganyar, maka nilai
	// $xcek_karcis dijadikan 0
	$xcek_karcis = 0;
      //$xcek_karcis = -2000;
	// --- eof 27-12-2006 ---
      if ($totalObat > 2000) {

   /*if ($cekPotObat < 1) {
      pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, ".
		"is_obat, is_karcis, layanan, jumlah, is_bayar) ".
      		"values (nextval('kasir_seq'), '".$_POST["rg"]."', ".
		"CURRENT_DATE, '$loket', 'Y', 'N', 99995, $xcek_karcis, 'N' )") or die("pot obat err");
   	}*/

      }

   } elseif ($cek_karcis == 9000) {
	// sfdn, 27-12-2006 --> dgn. pertimbangan kondisional (cocok untuk RS Karanganyar, 
	//maka nilai
	// $xcek_karcis dijadikan 0
	$xcek_karcis = 0;
        //$xcek_karcis = -4000;
	// --- eof 27-12-2006 ---
      if ($totalObat > 4000) {
   /*if ($cekPotObat < 1) {
      pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, ".
		"is_obat, is_karcis, layanan, jumlah, is_bayar) ".
      		"values (nextval('kasir_seq'), '".$_POST["rg"]."', ".
		"CURRENT_DATE, '$loket', 'Y', 'N', 99995, $xcek_karcis, 'N' )") or die("pot obat err2");
   	}*/

      }

   }

   //echo "$PID - $_POST[rg] - ".$v["id"]." - ".$v["jumlah"]." - ".$v["harga"]." - total: $total"; exit();
    foreach ($_SESSION["obat"] as $v) {
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran ".
            ") values (".
                "nextval('rs00008_seq'), 'OBA', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '".$v["id"]."', '', " .
                "'".$v["jumlah"]."',0,0,0)"
        );

        /*
        if (strlen($v["dosis"]) > 0) {
            $tr->addSQL(
                "insert into rs00009 (trans_id, description) " .
                "values (currval('rs00008_seq'), '".$v["dosis"]."')");
        }
        */


        // tambahan sfdn 09-05-2004
        //$tr->addSQL("update rs00016 set qty_keluar = qty_keluar + ".$v["jumlah"].
          //  " where obat_id = '".$v["id"]."'");

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
            "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '', '', " .
            "0,0,0,'".$_POST["byr"]."', 'Y',currval('rs88888_seq'))"
    );

    // tambahan sfdn, 31-05-2004
    $reg_count = getFromTable("select count(mr_no) from rs00006 ".
                "where mr_no = (select mr_no from rs00006 ".
                "where id = '".$_POST["rg"]."')");
    $baru    = "Y";
    if ($reg_count > 1 ) $baru = "T";

    $r1 = pg_query($con,
        "select tipe, id as no_reg, tanggal_reg, rawat_inap, rujukan ".
        "from rs00006 ".
        "where id = '".$_POST["rg"]."'");

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
   	    "id,trans_type, is_inout, qty, no_reg, is_baru, tanggal_trans, ".
	    "datang_id, trans_group,is_bayar )".
	    "values (" .
            "nextval('rs00008_seq'),'$loket', 'O', 1,$d1->no_reg, ".
            "'$baru',CURRENT_DATE,'$d1->rujukan',currval('rs00008_seq_group'), 'Y')");
    $tr->addSQL(
        "update rs00006 set is_karcis='Y' where id = '".$_POST["rg"]."'");

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


    /*
    $_SESSION["dialog"]["title"] = "Transaksi telah diproses...";
    $_SESSION["dialog"]["desc"] = "Klik tombol dibawah ini untuk melakukan transaksi lagi.";
    if (is_array($_SESSION["obat"])) {
        //$_SESSION["dialog"]["desc"] = "Nomor resep adalah ".
            //getFromTable("select currval('rs00008_seq_group')") . "<br>" .
            //$_SESSION["dialog"]["desc"];
    }
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    */

    unset($_SESSION["layanan"]);
    unset($_SESSION["s2note"]);
    unset($_SESSION["icd"]);
    unset($_SESSION["obat"]);



    if ($_SESSION[gr] == "laborat" || $_SESSION[gr] == "radiologi" ) {
	header("Location: ../index2.php?p=p_kulit_kelamin&list=resepobat&rg=".$_POST[rg]."&sub=".$_POST[sub]."&mr=".$_POST[mr]);
//p=" . $_GET["p"] . "&list=resepobat&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=obat

    } else {
    //    header("Location: ../index2.php?p=320RJ&rg=".$_POST[rg]."&sub=".$_POST[sub]);
header("Location: ../index2.php?p=p_kulit_kelamin&list=resepobat&rg=".$_POST[rg]."&sub=".$_POST[sub]."&mr=".$_POST[mr]);
	//header("Location: ../index2.php?p=dialog");
    }
    
    exit;
} else {
    echo $tr->ErrMsg;
}

?>
