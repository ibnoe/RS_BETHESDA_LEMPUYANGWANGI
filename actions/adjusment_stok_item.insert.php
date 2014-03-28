<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
	 //  Ian, 30 Nov 2007 0:56 WIB
	 // ary, 24 Feb 2010 -> Developing database

session_start();
$PID = "adjusment_stok";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$poli_tujuan=$_POST["qty_tujuan"];
//$poli_asal=$_POST["qty_asal"];


$tr = new PgTrans;
$tr->PgConn = $con;

//Agung Sunandar 23:56 07/08/2012 
/*if ($_POST[action]=="edit"){
	$cek_qty=getFromTable("select $poli_asal from rs00016a where obat_id=".$_POST["id"]."  ");
	//echo $cek_qty,"-",$_POST["qty"];
	if ($_POST["qty"] > $cek_qty){
	?>
     <script>
         alert ('Jumlah pemberian tidak boleh lebih besar dari stok yang ada!');
     </script>    
     <?
    echo "<script language='JavaScript'>document.location='../index2.php?p=$PID&action=".$_POST["action"]."&f=".$_POST[f]."&e=".$_POST["e"]."&g=".$_POST["g"]."&id_obt=".$_POST[id_obt]."'</script>";
	}else{
	// mengupdate stok permintaan
	$tr->addSQL("update transfer_keluar_item set jumlah = ".$_POST["qty"]."  ".
	 	 "where oid=".$_POST["id_obt"]."");
	
	}	
} else*/if($_POST[action]=="verifikasi"){
	// mengupdate stok permintaan
	$tr->addSQL("update stok_adjusment_item set status='1', verifikator = '".$_SESSION[nama_usr]."',waktu_ver=CURRENT_DATE,jam_ver=CURRENT_TIME  ".
	 	 "where oid=".$_POST["id_obt"]."");
	
	// mengurangi stok asal
	 $tr->addSQL("update rs00016a set $poli_tujuan = ".$_POST["qty"]."  ".
	 	 "where obat_id=".$_POST["id"]."");
	//mengurangi stok tujuan
	/* $tr->addSQL("update rs00016a set $poli_tujuan = $poli_tujuan + ".$_POST["qty"]."  ".
	 	 "where obat_id=".$_POST["id"].""); */
}

/* Agung Sunandar 1:42 08/08/2012
	if (is_array($_SESSION["ob4"]["obat"])) {
	
	
		$tr->addSQL("insert into internal_transfer_m (kode_transaksi,tanggal_trans,poli_tujuan,poli_asal,status)".
				"values (nextval('internal_transfer_seq'),CURRENT_DATE,'$poli_tujuan','$poli_asal','0')");
				
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
		}
	}
} */



if ($tr->execute()) {
    $cek_status=getFromTable("select count(status) from stok_adjusment_item where status='0' and kode_transaksi=".$_POST["f"]."");
	if($cek_status > 0){
	header("Location: ../index2.php?p=$PID&act=$_POST[act]&action=view&f=".$_POST[f]."&e=".$_POST["e"]."&g=".$_POST["g"]."");
    exit;
	}else{
	pg_query("update stok_adjusment set status='1' where kode_transaksi=".$_POST["f"]."");
	
	header("Location: ../index2.php?p=$PID&act=$_POST[act]");
    exit;
	}
} else {
    ?>
     <script>
         alert ('Terjadi kesalahan input!');
     </script>    
     <?
    echo "<script language='JavaScript'>document.location='../index2.php?p=$PID&act=$_POST[act]&action=".$_POST["action"]."&f=".$_POST[f]."&e=".$_POST["e"]."&g=".$_POST["g"]."&id_obt=".$_POST[id_obt]."'</script>";
}

?>
