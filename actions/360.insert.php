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

/*if () {
    unset($_SESSION["ob5"]);
    $_SESSION["dialog"]["title"] = "Transaksi telah diproses...";
    //$_SESSION["dialog"]["desc"] = "Klik tombol dibawah ini untuk melakukan transaksi lagi.";
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    header("Location: ../index2.php?p=dialog");
    exit;
} else {
    echo $tr->ErrMsg;
}*/
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
                "CURRENT_DATE, CURRENT_TIME, '".$_POST[apotek]."', '".$v["id"]."', '', " .
                "'".$v["jumlah"]."','".$v["harga"]."',0,'".$v["total"]."')"
        );
        
	
	if ($_POST[apotek] == "RJ") {
	$tr->addSQL("update rs00016a set qty_rj = qty_rj + ".$v["jumlah"].
            " where obat_id = '".$v["id"]."'");
	    
        
	} else {
	$tr->addSQL("update rs00016a set qty_ri = qty_ri + ".$v["jumlah"].", gudang = gudang - ".$v["jumlah"]." ".
            " where obat_id = '".$v["id"]."'");
        
	}
	
	$tr->addSQL("update rs00016 set qty_awal = qty_awal - ".$v["jumlah"].
            " where obat_id = '".$v["id"]."'");


    }
}

if ($tr->execute()) {
    unset($_SESSION["ob5"]);
    $_SESSION["dialog"]["title"] = "Transaksi telah diproses...";
    //$_SESSION["dialog"]["desc"] = "Klik tombol dibawah ini untuk melakukan transaksi lagi.";
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    header("Location: ../index2.php?p=dialog");
    exit;
} else {
    echo $tr->ErrMsg;
}

?>
