<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
	 //  Ian, 30 Nov 2007 0:56 WIB
	 // ary, 24 Feb 2010 -> Developing database

session_start();
$PID = "internal_transfer1";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");


$poli_tujuan=$_POST["poli_tujuan"];
$poli_asal=$_POST["poli_asal"];

$tr = new PgTrans;
$tr->PgConn = $con;
if (is_array($_SESSION["ob4"]["obat"])) {
	
	
		$tr->addSQL("insert into internal_transfer_m (kode_transaksi,tanggal_trans,poli_tujuan,poli_asal,status)".
				"values (nextval('internal_transfer_seq'),CURRENT_DATE,'$poli_tujuan','$poli_asal','1')");
				
	foreach ($_SESSION["ob4"]["obat"] as $v){
		$tr->addSQL("insert into internal_transfer_d (kode_transaksi,item_id,batch_id,jumlah,keterangan,user_id,nm_user)".
				"values (currval('internal_transfer_seq'),'".$v["id"]."','".$v["batch"]."','".$v["jumlah_pakai"]."','".$v["keterangan"]."','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')");
        $ra = pg_query($con, "select * from rs00016a where obat_id = ".$v["id"]."");
        $da = pg_fetch_object($ra);
        pg_free_result($ra);
        $toting = ((int) $da->qty_ri) - ((int) $v["jumlah_pakai"]);
		
 		if ($poli_asal == "003"){
		 $tr->addSQL("update rs00016a set gudang=gudang - ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]."");
		}elseif ($poli_asal == "020"){
		$tr->addSQL("update rs00016a set qty_jantung = qty_jantung - ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]." ");
		}elseif ($poli_asal == "021"){
		$tr->addSQL("update rs00016a set qty_interne = qty_interne - ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]." ");
		}elseif ($poli_asal == "022"){
		$tr->addSQL("update rs00016a set qty_jiwa = qty_jiwa - ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]." ");
		}elseif ($poli_asal == "023"){
		$tr->addSQL("update rs00016a set qty_kebid = qty_kebid - ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]." ");
		}
		
		
		if ($poli_tujuan == "003"){
		 $tr->addSQL("update rs00016a set gudang=gudang + ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]."");
		}elseif ($poli_tujuan == "020"){
		$tr->addSQL("update rs00016a set qty_jantung = qty_jantung + ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]." ");
		}elseif ($poli_tujuan == "021"){
		$tr->addSQL("update rs00016a set qty_interne = qty_interne + ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]." ");
		}elseif ($poli_tujuan == "022"){
		$tr->addSQL("update rs00016a set qty_jiwa = qty_jiwa + ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]." ");
		}elseif ($poli_tujuan == "023"){
		$tr->addSQL("update rs00016a set qty_kebid = qty_kebid + ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]." ");
		}
	}
}


if ($tr->execute()) {
    unset($_SESSION["ob4"]);
    $_SESSION["dialog"]["title"] = "Transaksi telah diproses...";
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    header("Location: ../index2.php?p=dialog");
    exit;
} else {
    echo $tr->ErrMsg;
}

?>
