<?php // Nugraha, Thu Apr 22 11:58:22 WIT 2004
      // sfdn, 23-04-2004: tambah harga obat
      // sfdn, 09-05-2004
	  // sfdn, 31-05-2004

session_start();
$PID = "apotik_umum_obat";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$tokit = pg_query("select nextval('rs00008_seq_group')");
pg_query("select nextval('kasir_seq')");

$tr = new PgTrans;
$tr->PgConn = $con;

// RESEP  /  OBAT
if (is_array($_SESSION["obat"])) {

    // tokit punya
   foreach ($_SESSION["obat"] as $v) {
        $total += $v["total"];
   }
   $cek_karcis = getFromTable("select jumlah from rs00005 where reg = '".$_POST["rg"]."' and is_karcis = 'Y'");

   if ($_POST[tt] == "igd") {
      $loket = "IGD";
	  $PID1 = "320RJ_IGDU";
   } elseif ($_POST[tt] == "swd") {
      $loket = "SWD";
	  $PID1 = "320RJ_SWDU";
   } elseif ($_POST[tt] == "cdm") {
      $loket = "CDM";
	  $PID1 = "320RJ_CDMU";
   } else {
      $loket = "ASK";
	  $PID1 = "320RJ_ASKU";
   }
   
   pg_query("INSERT INTO rs00005 VALUES( currval('kasir_seq'), '".$_POST["rg"]."', ".
        "CURRENT_DATE, '$loket', 'Y', 'N', '$PID1', $total, 'N','".$_SESSION["uid"]."')") or die("eror atuh");

/*    $cekPotObat = getFromTable("select jumlah from rs00005 ".
			"where reg = '".$_POST["rg"]."' and layanan = 99995 ");
   $totalObat = getFromTable("select sum(jumlah) from rs00005 ".
			"where reg = '".$_POST["rg"]."'".
			"	and is_obat = 'Y' and layanan != 99995");
   if ($cek_karcis == 4500) {
	$xcek_karcis = 0;
      if ($totalObat > 2000) {

   if ($cekPotObat < 1) {
      pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, ".
		"is_obat, is_karcis, layanan, jumlah, is_bayar) ".
      		"values (nextval('kasir_seq'), '".$_POST["rg"]."', ".
		"CURRENT_DATE, '$loket', 'Y', 'N', 99995, $xcek_karcis, 'N' )") or die("pot obat err");
   	}
      }
   } elseif ($cek_karcis == 9000) {
	$xcek_karcis = 0;
      if ($totalObat > 4000) {
   if ($cekPotObat < 1) {
      pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, ".
		"is_obat, is_karcis, layanan, jumlah, is_bayar) ".
      		"values (nextval('kasir_seq'), '".$_POST["rg"]."', ".
		"CURRENT_DATE, '$loket', 'Y', 'N', 99995, $xcek_karcis, 'N' )") or die("pot obat err2");
   	}
      }
   } */
    foreach ($_SESSION["obat"] as $v) {
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran,user_id ".
            ") values (".
                "nextval('rs00008_seq'), 'OB1', '$PID1', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."',  '".$v["id"]."','".$v["ppn"]."', " .
                "'".$v["jumlah"]."',".$v["harga"].",".$v["total"].",0, '".$_SESSION[uid]."')"
        );
       
        $tr->addSQL("update rs00016 set qty_keluar = qty_keluar + ".$v["jumlah"].
            " where obat_id = '".$v["id"]."'");

		if ($_POST["tt"]=="igd"){
		$tr->addSQL("update rs00016a set qty_interne = qty_interne - ".$v["jumlah"].
            " where obat_id = '".$v["id"]."'");
		}elseif ($_POST["tt"]=="swd"){
		$tr->addSQL("update rs00016a set qty_ri = qty_ri - ".$v["jumlah"].
            " where obat_id = '".$v["id"]."'");
		}elseif ($_POST["tt"]=="cdm"){
		$tr->addSQL("update rs00016a set qty_jiwa = qty_jiwa - ".$v["jumlah"].
            " where obat_id = '".$v["id"]."'");
		}elseif ($_POST["tt"]=="ask"){
		$tr->addSQL("update rs00016a set qty_kebid = qty_kebid - ".$v["jumlah"].
            " where obat_id = '".$v["id"]."'");
		}
    }
}

if ($tr->execute()) {

    unset($_SESSION["obat"]);

    if ($_SESSION[gr] == "laborat" || $_SESSION[gr] == "radiologi" ) {
	header("Location: ../index2.php?p=apotik_umum&list=resepobat&tt=".$_POST[tt]."&rg=".$_POST[rg]."&sub=".$_POST[sub]);
    } else {
        header("Location: ../index2.php?p=apotik_umum&list=resepobat&tt=".$_POST[tt]."&rg=".$_POST[rg]."&sub=".$_POST[sub]);
    }
    exit;
} else {
    echo $tr->ErrMsg;
}

?>
