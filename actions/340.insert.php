<?php // Nugraha, Fri Apr 23 13:40:34 WIT 2004
	  // sfdn, 31-05-2004

session_start();
$PID = "340";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

if (isset($_SESSION["resep"]))
    $rg = (int) getFromTable("select no_reg from rs00008 where trans_group = '".$_SESSION["resep"]."'");
if ($rg == 0) $rg = 0;

$tr = new PgTrans;
$tr->PgConn = $con;
if (is_array($_SESSION["ob2"])) {
    $tr->addSQL("select nextval('rs00008_seq_group')");
    $total = 0.00;
    foreach ($_SESSION["ob2"] as $v) {
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran ".
            ") values (".
                "nextval('rs00008_seq'), 'OB2', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, lpad('$rg',10,'0'), '".$v["id"]."', '".$v["ref"]."', " .
                "'".$v["jumlah"]."','".$v["harga"]."','".$v["total"]."',0)"
        );
        $total += $v["total"];
        if (strlen($v["ref"]) > 0) {
            $tr->addSQL(
                "update rs00008 set referensi = 'F' where id = '".$v["ref"]."'"
            );
        }
    }
    if ($_POST["tanpa_bayar"] == 0) {
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran ".
            ") values (".
                "nextval('rs00008_seq'), 'BYR', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, lpad('$rg',10,'0'), '', '', " .
                "0,0,0,$total)"
        );

        // tambahan sfdn, 31-05-2004
        $tr->addSQL(
            "insert into rs00008 (" .
                "id,trans_type, qty,  no_reg, tanggal_trans ".
            ") values (" .
                "nextval('rs00008_seq'),'RES', 1, lpad('$rg',10,'0'),  CURRENT_DATE)");
		// akhir tambahan sfdn, 31-05-2004

    }
}

if ($tr->execute()) {
    unset($_SESSION["resep"]);
    unset($_SESSION["ob2"]);
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
