<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
	 //  Ian, 30 Nov 2007 0:56 WIB
	 // ary, 24 Feb 2010 -> Developing database

session_start();
$PID = "350";

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
	
	/*$tgl_1 = explode("-",$_SESSION["ob4"]["tanggal-pengadaan"]);
	$tgl_2 = $tgl_1[1]."-" .$tgl_1[0]."-".$tgl_1[2];*/
	
	if($_POST['bonus']=='Y'){
	foreach ($_SESSION["ob4"]["obat"] as $v){
			$harga_beli = 0;
			$stok_gudang = getFromTable("select gudang from rs00016a where obat_id='".$v["id"]."'");
			$stok_apotek = getFromTable("select qty_ri from rs00016a where obat_id='".$v["id"]."'");
		$tr->addSQL("insert into c_po_item (item_id,po_id,item_qty,satuan1,jumlah2,total_jumlah,kode_trans,diskon1,harga_beli,bonus)".
				"values ('".$v["id"]."','".$_POST['poid']."','".$v["jumlah"]."','".$v["satuan2"]."','".$v["jumlah1"]."','".$v["total"]."','".$v["kode_trans"]."','0',$harga_beli,". (int) $v["bonus"].")");
		}
	}else{
		$tr->addSQL("insert into c_po (po_id,supp_id,po_tanggal,po_status,po_personal,ppn,tanggal_entry,user_entry)".
				"values ('".$no_po."','".$id_supp."','".$tgl_po."',0,'".$pen_jwb."','".$ppn."',CURRENT_DATE,'$_SESSION[uid]')");
 
	foreach ($_SESSION["ob4"]["obat"] as $v){
			$harga_beli = getFromTable("select harga_beli from rs00016 where obat_id='".$v["id"]."'");
			$stok_gudang = getFromTable("select gudang from rs00016a where obat_id='".$v["id"]."'");
			$stok_apotek = getFromTable("select qty_ri from rs00016a where obat_id='".$v["id"]."'");
	$tr->addSQL("insert into c_po_item (item_id,po_id,item_qty,satuan1,jumlah2,total_jumlah,kode_trans,diskon1,harga_beli,bonus)".
				"values ('".$v["id"]."','".$no_po."','".$v["jumlah"]."','".$v["satuan2"]."','".$v["jumlah1"]."','".$v["total"]."','".$v["kode_trans"]."','".$v["diskon"]."',$harga_beli,". (int) $v["bonus"].")");
      //$tr->addSQL("update rs00016 set  harga_beli=".$v["harga_beli"]." where obat_id='".$v["id"]."'");
		}
	}
}
if(!$_POST['bonus']){

$SQL1="insert into piutang_po (po_id,tanggal_po,jumlah_hutang,jumlah_bayar,sisa_hutang) values ('".$no_po."','".$tgl_po."',0,0,0)";
pg_query($con, $SQL1); 

}

if ($tr->execute()) {
    unset($_SESSION["ob4"]);

	if($_POST['bonus']!='Y'){
	header("Location: ../index2.php?p=print_po&po_id=$no_po&act=pengadaan");
	exit;
    }else{
	//header("Location: ../index2.php?p=360_2&edit=bonus&poid=$_POST[poid]");
	header("Location: ../index2.php?p=360_2");
	
	}//exit;
} else {
    echo $tr->ErrMsg;
}

?>
