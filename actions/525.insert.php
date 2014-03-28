<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
	 //  Ian, 30 Nov 2007 0:56 WIB
	 // ary, 24 Feb 2010 -> Developing database
	 // Agung Sunandar 1:26 30/06/2012 input retur

session_start();
$PID = "525";
ini_set('display_errors',1);
//var_dump($_SESSION["ob4"]);
require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$no_po  = $_SESSION["ob4"]["nomor-po"];
$pen_jwb  = $_SESSION["ob4"]["penanggung-jawab"];
$ppn  = $_SESSION["ob4"]["ppn"];
$disc1  = $_SESSION["ob4"]["disc1"];
$disc2  = $_SESSION["ob4"]["disc2"];
$tgl_po = $_SESSION["ob4"]["tanggal-pengadaan"];
$id_supp=$_SESSION["ob4"]["supplier1"]["id"];
$tr = new PgTrans;
$tr->PgConn = $con;


if (is_array($_SESSION["ob4"]["obat"])) {

	
		$tr->addSQL("insert into rs00016b (retur_id,tgl_retur,suplier_id,retur_personal,status_retur)".
				"values ('".$no_po."','".$tgl_po."','".$id_supp."','".$pen_jwb."',0)");
	
				
	foreach ($_SESSION["ob4"]["obat"] as $v){
		$tr->addSQL("insert into rs00016c (item_id,retur_id,item_qty,ket,po_status)".
				"values ('".$v["id"]."','".$no_po."','".$v["jumlah"]."','".$v["ket"]."',0)");
		//$test "update rs00016a set qty_ri=qty_ri - ".$v["jumlah"]." where obat_id='".$v["id"]."'");
		//var_dump($v);
		$isi=$v["jumlah"];
		$id_supp=$_SESSION["ob4"]["supplier1"]["id"];
		$tr->addSQL("update rs00016a set qty_ri=qty_ri - ".$isi." where obat_id='".$v["id"]."'");
		$nama=getFromTable("select nama from rs00028 where id='".$id_supp."'");
		$harga=getFromTable("select harga from buku_besar where item_id = '".$v['id']."' and trans_form='c_po_item_terima' order by id Desc");
		if ($harga!='') {
		pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,harga,ket) 
									values (CURRENT_DATE,CURRENT_TIME,'".$v['id']."','ORK','020','".$v['jumlah']."','".$no_po."','rs00016c',$harga,'RETUR KE $nama')");

		}else{
		$harga2=getFromTable("select harga_beli from rs000016 where obat_id = '".$v['id']."'");
		pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,harga,ket) 
									values (CURRENT_DATE,CURRENT_TIME,'".$v['id']."','ORK','020','".$v['jumlah']."','".$no_po."','rs00016c',$harga,'RETUR KE $nama')");
		}
		
	}			
}


if ($tr->execute()) {
    unset($_SESSION["ob4"]);
	header("Location: ../index2.php?p=$PID");
	exit;
    //exit;
} 
else{
	echo $tr->showSQL();
	}

?>
