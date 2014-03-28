<?php // Nugraha, Thu Apr 22 11:58:22 WIT 2004
      // sfdn, 23-04-2004: tambah harga obat
      // sfdn, 09-05-2004
	  // sfdn, 31-05-2004

session_start();
$PID = "320RJJANTUNG";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$tokit = pg_query("select nextval('rs00008_seq_group')");
pg_query("select nextval('kasir_seq')");


$tr = new PgTrans;
$tr->PgConn = $con;


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
     
   // potongan obat karena obat paket
   $cekPotObat = getFromTable("select jumlah from rs00005 ".
			"where reg = '".$_POST["rg"]."' and layanan = 99995 ");
   $totalObat = getFromTable("select sum(jumlah) from rs00005 ".
			"where reg = '".$_POST["rg"]."'".
			"	and is_obat = 'Y' and layanan != 99995");
   if ($cek_karcis == 4500) {
		$xcek_karcis = 0;
      if ($totalObat > 2000) {

      }

   } elseif ($cek_karcis == 9000) {
	$xcek_karcis = 0;
      if ($totalObat > 4000) {

      }

   }

    foreach ($_SESSION["obat"] as $v) {
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran,is_racikan, dosis ".
            ") values (".
                "nextval('rs00008_seq'), 'OBA', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '".$v["id"]."', '', " .
                "'".$v["jumlah"]."',0,0,0, '".$v["is_racikan"]."', '".$v["dosis"]."')"
        );

    }
}


if ($tr->execute()) {

    unset($_SESSION["layanan"]);
    unset($_SESSION["s2note"]);
    unset($_SESSION["icd"]);
    unset($_SESSION["obat"]);

    if ($_SESSION[gr] == "laborat" || $_SESSION[gr] == "radiologi" ) {
		header("Location: ../index2.php?p=p_jantung&list=resepobat&rg=".$_POST[rg]."&sub=".$_POST[sub]."&mr=".$_POST[mr]);
    } else {
		header("Location: ../index2.php?p=$PID&list=resepobat&rg=".$_POST[rg]."&sub=".$_POST[sub]."&mr=".$_POST[mr]);
    }
    
    exit;
} else {
     ?>
     <script>
         alert ('Terjadi kesalahan input!');
     </script>    
     <?
    echo "<script language='JavaScript'>document.location='../index2.php?p=$PID&list={$_POST["list"]}&rg=".$_POST[rg]."&poli=".$_POST["poli"]."&mr=".$_GET["mr"]."&sub2=".$_POST[sub2]."&sub=".$_POST[sub]."'</script>";
}

?>
