<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
	 //  Ian, 30 Nov 2007 0:56 WIB
	 // ary, 24 Feb 2010 -> Developing database

session_start();
$PID = "internal_transfer_minta";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

//$no_po  = $_SESSION["ob4"]["nomor-po"];
//$pen_jwb  = $_SESSION["ob4"]["penanggung-jawab"];
//$tgl_po = $_SESSION["ob4"]["tanggal-pengadaan"];
$poli_tujuan=$_POST["poli_tujuan"];
$poli_asal=$_POST["poli_asal"];
//echo $poli_tujuan;
//echo $poli_asal;

$tr = new PgTrans;
$tr->PgConn = $con;
if (is_array($_SESSION["ob4"]["obat"])) {
	
	
		$tr->addSQL("insert into internal_transfer_m (kode_transaksi,tanggal_trans,poli_tujuan,poli_asal,status)".
				"values (nextval('internal_transfer_seq'),CURRENT_DATE,'$poli_tujuan','$poli_asal','0')");
				
	foreach ($_SESSION["ob4"]["obat"] as $v){
		$tr->addSQL("insert into internal_transfer_d (kode_transaksi,item_id,batch_id,jumlah,keterangan,user_id,nm_user)".
				"values (currval('internal_transfer_seq'),'".$v["id"]."','".$v["batch"]."','".$v["jumlah_obat"]."','".$v["keterangan"]."','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')");
        
		/* $ra = pg_query($con, "select * from rs00016a where obat_id = ".$v["id"]."");
        $da = pg_fetch_object($ra);
        pg_free_result($ra);
        $toting = ((int) $da->qty_ri) - ((int) $v["jumlah_pakai"]);
		
 		if ($poli_asal == "003"){
		 $tr->addSQL("update rs00016a set gudang=gudang - ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]."");
		}elseif ($poli_asal == "020"){
		$tr->addSQL("update rs00016a set qty_ri=qty_ri - ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]." ");
		}else{
		$tr->addSQL("update rs00016a set qty_$poli_asal=qty_$poli_asal - ".$v["jumlah_pakai"]."  ".
	 	 "where obat_id=".$v["id"]." ");
		} 
		
		
		if ($poli_tujuan == "003"){
		$tr->addSQL("update rs00016a set gudang=gudang + ".$v["jumlah_pakai"]." where obat_id=".$v["id"]."");
		}elseif ($poli_tujuan == "020"){
		$tr->addSQL("update rs00016a set qty_ri=qty_ri + ".$v["jumlah_pakai"]."  where obat_id=".$v["id"]."");
		}else{
		$tr->addSQL("update rs00016a set qty_$poli_tujuan=qty_$poli_tujuan + ".$v["jumlah_pakai"]."  where obat_id=".$v["id"]."");
		} */
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
?>
     <script>
         alert ('Terjadi kesalahan input!');
     </script>    
     <?
	// echo $tr->ErrMsg;
    echo "<script language='JavaScript'> document.location='../index2.php?p=$PID'</script>";
}

?>
