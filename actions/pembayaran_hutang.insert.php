<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
      // sfdn, 23-04-2004
      // sfdn, 09-05-2004

session_start();
$PID = "pembayaran_hutang";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");



$no_faktur=getFromTable("select no_faktur from c_po where po_id='".$_POST["po_id"]."'");
$jumlah_bayar=$_POST["jumlah_bayar"];
$jumlah_bayar1=getFromTable("select jumlah_bayar from piutang_po where po_id='".$_POST["po_id"]."'");
$jumlah_hutang=getFromTable("select jumlah_hutang from piutang_po where po_id='".$_POST["po_id"]."'");
$sisa=$jumlah_hutang-($jumlah_bayar1+$jumlah_bayar);
$jumlah_sisa=getFromTable("select sisa_hutang from piutang_po where po_id='".$_POST["po_id"]."'");

if ($jumlah_bayar==$jumlah_hutang) {

	$SQL="update piutang_po set jumlah_bayar=$jumlah_bayar1+$jumlah_bayar, sisa_hutang=$sisa,tgl_bayar=CURRENT_DATE where po_id='".$_POST["po_id"]."'";
	$SQL1="update c_po set status_bayar=2 where po_id='".$_POST["po_id"]."'";
	pg_query($con, $SQL);
	pg_query($con, $SQL1);
}else if ($jumlah_bayar==$jumlah_sisa)  {
	$SQL="update piutang_po set jumlah_bayar=$jumlah_bayar1+$jumlah_bayar, sisa_hutang=$sisa,tgl_bayar=CURRENT_DATE where po_id='".$_POST["po_id"]."'";
	$SQL1="update c_po set status_bayar=2 where po_id='".$_POST["po_id"]."'";
	
	
	pg_query($con, $SQL);
	pg_query($con, $SQL1);

}else {

$SQL="update piutang_po set jumlah_bayar=$jumlah_bayar1+$jumlah_bayar, sisa_hutang=$sisa,tgl_bayar=CURRENT_DATE where po_id='".$_POST["po_id"]."'";
	$SQL1="update c_po set status_bayar=1 where po_id='".$_POST["po_id"]."'";
	
	
	pg_query($con, $SQL);
	pg_query($con, $SQL1);
} 
$SQL3 = "insert into rs00005 (id,reg,tgl_entry,kasir,is_obat,is_karcis,layanan,jumlah,is_bayar,bayar,nama_kasir,no_kartu,waktu_bayar) values
(nextval('rs00005_seq'),'".$_POST["po_id"]."',CURRENT_DATE,'HUT','N','N','0',$jumlah_bayar,'Y','-','".$_SESSION["uid"]."','-',CURRENT_TIME)";
pg_query($con, $SQL3);

$PID2 = "Pembayaran Hutang";
$SQL2 = "insert into history_user " .
        "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
        "values".
        "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'$PID2','Inventory -> $PID2','Pembayaran Hutang No. PO ".$_POST["po_id"]." No. Faktur $no_faktur','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
pg_query($con, $SQL2);
		
header("Location: ../index2.php?p=print_hutang&po_id=$no_faktur&jumlah=$jumlah_bayar");
exit;
?>
