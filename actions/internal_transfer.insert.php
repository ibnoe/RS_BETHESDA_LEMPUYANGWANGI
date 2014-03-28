<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
	 //  Ian, 30 Nov 2007 0:56 WIB
	 // ary, 24 Feb 2010 -> Developing database

session_start();
$PID = "internal_transfer";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$poli_tujuan=$_POST["qty_tujuan"];
$poli_asal=$_POST["qty_asal"];
$kode_trans=$_POST["f"];

$tr = new PgTrans;
$tr->PgConn = $con;

//Agung Sunandar 23:56 07/08/2012 
if ($_POST[action]=="edit"){
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
	$tr->addSQL("update internal_transfer_d set jumlah = ".$_POST["qty"]."  ".
	 	 "where oid=".$_POST["id_obt"]."");
	
	}	
}elseif($_POST[action]=="verifikasi"){
	// mengupdate stok permintaan
	
	$tr->addSQL("update internal_transfer_d set status='1', verifikator = '".$_SESSION[nama_usr]."'  ".
	 	 "where oid=".$_POST["id_obt"]."");
	
	//echo "INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,ket) 
	//								values (CURRENT_DATE,CURRENT_TIME,'".$_POST['id']."','OBT','020','".$_POST['qty']."','".$kode_trans."','internal_transfer_d', 'Barang Ruangan Asal $_POST['e']')";
	//var_dump($_SESSION);
	// ---- insert ke buku besar
	$harga=getFromTable("select harga from buku_besar where item_id = '".$_POST['id']."' and trans_form='c_po_item_terima' order by id Desc");
		
	pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,ket,harga) 
									values (CURRENT_DATE,CURRENT_TIME,'".$_POST['id']."','OBT','020','".$_POST['qty']."','".$kode_trans."','internal_transfer_d', 'Barang Ruangan Asal ".$_POST["e"]."','".$harga."')");
	
	
	// mengurangi stok asal
	 $tr->addSQL("update rs00016a set $poli_asal = $poli_asal - ".$_POST["qty"]."  ".
	 	 "where obat_id=".$_POST["id"]."");
	//menambah stok tujuan
	 $tr->addSQL("update rs00016a set $poli_tujuan = $poli_tujuan + ".$_POST["qty"]."  ".
	 	 "where obat_id=".$_POST["id"]."");
	
	
	
	$depo=getFromtable("Select tc from rs00001 where tdesc='".$_POST["g"]."'");
		 //insert buku besar Trendy// ---- insert ke buku besar
		 // ---- insert ke buku besar
	//$PoliTujuan=getFromTable("select tdesc from rs00001 where tt = '".$poli_tujuan."' and tt='GDP'");
	$harga=getFromTable("select harga from buku_besar where item_id = '".$_POST['id']."' and trans_form='c_po_item_terima' order by id Desc");
	
	pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,ket) 
									values (CURRENT_DATE,CURRENT_TIME,'".$_POST['id']."','OBK','$depo','".$_POST['qty']."','".$kode_trans."','internal_transfer_d','Barang Ruangan Tujuan ".$_POST["g"]."')");
	
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
    $cek_status=getFromTable("select count(status) from internal_transfer_d where status='0' and kode_transaksi=".$_POST["f"]."");
	if($cek_status > 0){
	header("Location: ../index2.php?p=$PID&action=view&f=".$_POST[f]."&e=".$_POST["e"]."&g=".$_POST["g"]."");
    exit;
	}else{
	pg_query("update internal_transfer_m set status='1' where kode_transaksi=".$_POST["f"]."");
	
	header("Location: ../index2.php?p=$PID");
    exit;
	}
} else {
    ?>
     <script>
         alert ('Terjadi kesalahan input!');
     </script>    
     <?
    echo "<script language='JavaScript'>document.location='../index2.php?p=$PID&action=".$_POST["action"]."&f=".$_POST[f]."&e=".$_POST["e"]."&g=".$_POST["g"]."&id_obt=".$_POST[id_obt]."'</script>";
}

?>
