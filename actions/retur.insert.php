<?php
// tokit 2004-11-2


session_start();
$PID = "320";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$tokit = pg_query("select nextval('rs00008_seq_group')");
$tokit = pg_query("select nextval('rs00008_seq')");
pg_query("select nextval('kasir_seq')");
//pg_query("select nextval('rs00030_seq')");


$tr = new PgTrans;
$tr->PgConn = $con;


// RESEP  /  OBAT

if (($_POST[retur] > 0) && ($_POST[sisa] >= $_POST[retur])) {

if ($_POST[sub] == "retur"){


 //  $kodepoli = getFromTable("select poli from rs00006 where id = lpad('".$_POST["rg"]."',10,'0')");
  $kodepoli = getFromTable("select poli from rs00006 where id = lpad('".$_POST["rg"]."',10,'0')");

   if ($_POST[rawatan] == "IGD") {
      $loket = "IGD";
   } elseif ($_POST[rawatan] == "Rawat Inap") {
      $loket = "RIN";
      $kodepoli = 0;
   } else {
      $loket = "RJL";
   }

   $totalret = $_POST[retur] * $_POST[harga];
   pg_query("INSERT INTO rs00005 VALUES( currval('kasir_seq'), lpad('".$_POST["rg"]."',10,'0'), ".
        "CURRENT_DATE, '$loket', 'Y', 'N', 90000, $totalret, 'N')") or die("eror atuh");

   $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran ".
            ") values (".
                "currval('rs00008_seq'), 'RET', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, lpad('".$_POST["rg"]."',10,'0'), '".$_POST[id]."', '".$_POST[retur_id]."', " .
                "'".$_POST[retur]."',".$_POST[harga].",0,0)"
        );

   if ($_SESSION[uid] == "apotek rj") {
   $tr->addSQL("update rs00016a set qty_rj = qty_rj + ".$_POST[retur].
            " where obat_id = ".$_POST["id"]);
   } elseif ($_SESSION[uid] == "apotek ri") {
   $tr->addSQL("update rs00016a set qty_ri = qty_ri + ".$_POST[retur].
            " where obat_id = ".$_POST["id"]);
   }


        //$tr->showSQL();

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


    if ($_SESSION[uid] == "laborat" || $_SESSION[uid] == "radiologi" ) {
	header("Location: ../index2.php?p=320&rg=".$_POST[rg]."&sub=".$_POST[sub]);

    } else {
        header("Location: ../index2.php?p=320&rg=".$_POST[rg]."&sub=retur");
	//header("Location: ../index2.php?p=dialog");
    }

    exit;
} else {
    echo $tr->ErrMsg;
    exit;
}


} // end of sisa >= retur

        header("Location: ../index2.php?p=320&rg=".$_POST[rg]."&sub=retur");


?>
