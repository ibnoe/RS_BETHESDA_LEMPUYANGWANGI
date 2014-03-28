<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
      // sfdn, 23-04-2004
      // sfdn, 09-05-2004

session_start();
$PID = "360";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$tr = new PgTrans;
$tr->PgConn = $con;
if (is_array($_SESSION["ob5"]["obat"])) {
    $tr->addSQL("select nextval('rs00008_seq_group')");
    foreach ($_SESSION["ob5"]["obat"] as $v) {
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran ".
            ") values (".
                "nextval('rs00008_seq'), 'OB5', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_SESSION["ob5"]["supplier"]["id"]."', '".$v["id"]."', '".$_SESSION["ob5"]["nomor-bukti"]."', " .
                "'".$v["jumlah"]."','".$v["harga"]."',0,'".$v["total"]."')"
        );
        // tambahan sfdn 09-05-2004

        $tr->addSQL("update rs00016 set qty_terima = qty_terima + ".$v["jumlah"].
            " where obat_id = '".$v["id"]."'");
        // akhir tambahan

    }
}

if ($tr->execute()) {
    unset($_SESSION["ob5"]);
    $_SESSION["dialog"]["title"] = "Transaksi telah diproses...";
    $_SESSION["dialog"]["desc"] = "Klik tombol dibawah ini untuk melakukan transaksi lagi.";
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    header("Location: ../index2.php?p=dialog");
    exit;
} else {
    echo $tr->ErrMsg;
}

?>
