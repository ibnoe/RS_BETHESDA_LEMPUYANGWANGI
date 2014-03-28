<?php // Nugraha, Thu Apr 22 11:58:22 WIT 2004
      // sfdn, 23-04-2004: tambah harga obat
      // sfdn, 09-05-2004
      // sfdn, 18-05-2004


session_start();
$PID = "380";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$tr = new PgTrans;
$tr->PgConn = $con;
$tr->addSQL("select nextval('rs00008_seq_group')");
// TRANSAKSI INSTALASI GIZI
if (is_array($_SESSION["layanan"])) {
    foreach ($_SESSION["layanan"] as $v) {
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran ".
            ") values (".
                "nextval('rs00008_seq'), 'OB9', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, lpad('".$_POST["rg"]."',10,'0'), '".$v["id"]."', '', " .
                $v["jumlah"].",".$v["harga"].",".$v["total"].",0)"
        );
    }

}


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
