<?php

include '../lib/dbconn.php';
include '../lib/class.PgTrans.php';

$tr = new PgTrans();
$tr->PgConn = $con;

$citoOperasi = $_POST['totalOp']-(double)$_POST['hargaOperasi']-(double)$_POST['diskonNominalOp'];
$citoAnestesi = $_POST['totalAn']-(double)$_POST['hargaAnestesi']-(double)$_POST['diskonNominalAnestesi'];

$SQLOP = "UPDATE rs00008 SET item_id = '".$_POST['idItemOp']."', referensi = '$citoOperasi', harga =  ".$_POST['hargaOperasi'].", 
		  diskon = ".(double)$_POST['diskonNominalOp'].",dibayar_penjamin = ".(double)$_POST['dibayarPenjaminOperasi'].", 
		 tagihan = ".($_POST['totalOp'])." , pembayaran = 0 , no_kwitansi = ".$_POST['idOp1']."
		  WHERE id =".$_POST['id08Op'];

$tr->addSQL($SQLOP);

$SQLRELOP = "UPDATE rs00008_op SET id_dokter1 = ".(int)$_POST['idOp1'].", diskon_dokter1=".(double)$_POST['diskonNominalOperasi1'].", 
id_dokter2= ".(int)$_POST['idOp2'].", diskon_dokter2=".(double)$_POST['diskonNominalOperasi2'].", id_asisten1=".(int)$_POST['idAsistenOp1'].",
  diskon_asisten1=".(double)$_POST['diskonNominalOperasi2'].", id_asisten2=".(int)$_POST['idAsistenOp2'].", 
  diskon_asisten2=".(double)$_POST['diskonNominalOperasi2'].", 
  id_asisten3=".(int)$_POST['idAsistenOp3'].", diskon_asisten3=".(double)$_POST['diskonNominalOperasi2']." WHERE id_rs08 =".$_POST['id08Op'];
$tr->addSQL($SQLRELOP);

$SQLAN = "UPDATE rs00008 SET item_id = '".$_POST['idItemAn']."', referensi = '$citoAnestesi', harga =  ".$_POST['hargaAnestesi'].", 
		  diskon = ".(double)$_POST['diskonNominalAnestesi'].",dibayar_penjamin = ".(double)$_POST['dibayarPenjaminAnestesi'].", 
		  tagihan = ".($_POST['totalAn'])." , 
		  pembayaran = 0 , no_kwitansi = ".$_POST['idAn1']."
		  WHERE id =".$_POST['id08An'];

$tr->addSQL($SQLAN);

$SQLRELAN = "UPDATE rs00008_op SET id_dokter1 = ".(int)$_POST['idAn1'].", diskon_dokter1=".(double)$_POST['diskonNominalAnestesi1'].", 
id_dokter2= ".(int)$_POST['idAn2'].", diskon_dokter2=".(double)$_POST['diskonNominalAnestesi2'].", 
  id_asisten1=".(int)$_POST['idAsistenAn1'].",
  diskon_asisten1=".(double)$_POST['diskonNominalAsistenAnestesi1'].", id_asisten2=".(int)$_POST['idAsistenAn2'].", 
  diskon_asisten2=".(double)$_POST['diskonNominalAsistenAnestesi2'].", id_asisten3=".(int)$_POST['idAsistenAn3'].", 
  diskon_asisten3=".(double)$_POST['diskonNominalAsistenAnestesi3']." WHERE id_rs08 =".$_POST['id08An'];
$tr->addSQL($SQLRELAN);

if(!$tr->execute()){
echo json_encode(array($tr->showSQL()));
}	
