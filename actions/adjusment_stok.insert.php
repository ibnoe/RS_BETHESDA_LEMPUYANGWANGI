<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
	 //  Ian, 30 Nov 2007 0:56 WIB
	 // ary, 24 Feb 2010 -> Developing database

session_start();
$PID = "adjusment_stok";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

//$no_po  = $_SESSION["ob4"]["nomor-po"];
//$pen_jwb  = $_SESSION["ob4"]["penanggung-jawab"];
//$tgl_po = $_SESSION["ob4"]["tanggal-pengadaan"];

$poli_tujuan=$_POST["poli_tujuan"];
$poli_asal=$_POST["poli_asal"];
$stok_poli=$_POST["stok_poli"];
//echo $poli_tujuan;
//echo $poli_asal;
if($_POST["poli_tujuan"]=='003'){
	$adjus = 'gudang';
}else if($_POST["poli_tujuan"]=='020'){
	$adjus = 'qty_ri';
}else{
	$adjus = 'qty_'.$_POST["poli_tujuan"];
}
$tr = new PgTrans;
$tr->PgConn = $con;
if (is_array($_SESSION["ob4"]["obat"])) {
	
		$tr->addSQL("insert into stok_adjusment (kode_transaksi,tanggal_trans,waktu_trans,stok_poli,status,nm_user)".
				"values (nextval('stok_adjusment_seq'),CURRENT_DATE,CURRENT_TIME,'$poli_tujuan','1','".$_SESSION["nama_usr"]."')");
	foreach ($_SESSION["ob4"]["obat"] as $v){
	$selisih=$v["jml_minta"]-$v["jml_depo"];
	$total=$v["harga_beli"]*$selisih;
	//	$kode_trans = pg_query("select currval('stok_adjusment_seq')");
	

		$tr->addSQL("insert into stok_adjusment_item (kode_transaksi,item_id,batch_id,stok_asal,stok_real,keterangan,selisih_stok,hna,total,status,verifikator,waktu_ver,jam_ver)".
				"values (currval('stok_adjusment_seq'),'".$v["id"]."','".$v["batch"]."','".$v["jml_depo"]."','".$v["jml_minta"]."','".$v["keterangan"]."',$selisih,$v[harga_beli],$total,'1','".$_SESSION[nama_usr]."',CURRENT_DATE,CURRENT_TIME)");
					
	// update stok asal
	 $tr->addSQL("update rs00016a set $stok_poli = ".$v["jml_minta"]." ,status_adjusment_".$adjus." = 1 ".
	 	 "where obat_id=".$v["id"]."");
	
	/*$stok_pl = explode("_",$stok_poli);
	if($stok_poli =='gudang'){
		$stok_depo = '003';
	}else if($stok_poli =='qty_ri'){
		$stok_depo = '020';
	}else{
		$stok_depo = $stok_pl[1];
	} */
	
	
			// mengurangi stok asal
	/* $tr->addSQL("update rs00016a set gudang = gudang - ".$v["jml_minta"]."  ".
	 	 "where obat_id=".$v["id"]."");
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
	//die;
}


if ($tr->execute()) {
	if (is_array($_SESSION["ob4"]["obat"])) {
	
	$kode_trans = getFromTable("select max(kode_transaksi) from stok_adjusment");

	// ---- insert ke buku besar
	pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form) 
									values (CURRENT_DATE,CURRENT_TIME,'".$v['id']."','OBA','$poli_tujuan','$selisih','$kode_trans','stok_adjusment_item')");
	}
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
    echo "<script language='JavaScript'> document.location='../index2.php?p=$PID&act=$_GET[act]'</script>";
}

?>
