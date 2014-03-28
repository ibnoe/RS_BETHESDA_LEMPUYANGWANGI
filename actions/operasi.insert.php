<?php
/**
 * Gema Perbangsa
 * 19 September 2013
 */ 

include '../lib/dbconn.php';
include '../lib/class.PgTrans.php';

$tr = new PgTrans();
$tr->PgConn = $con;

$citoOperasi = 0;
$citoAnestesi = 0;
$bangsal_id = (int)getFromTable("SELECT bangsal_id FROM rs00010 WHERE id = (SELECT MAX(id) FROM rs00010 WHERE no_reg='".$_POST['rg']."')");		
$SQLOP = "INSERT INTO rs00008(id, trans_type, trans_form, trans_group, tanggal_trans, tanggal_entry, waktu_entry, no_reg, item_id,referensi,
		qty, harga, diskon,dibayar_penjamin, tagihan, pembayaran, bangsal_id,no_kwitansi) VALUES(nextval('rs00008_seq'), 'LTM', 'p_operasi',
		nextval('rs00008_seq_group'), CURRENT_DATE, CURRENT_DATE, CURRENT_TIME,
		'".$_POST['rg']."', ".$_POST['idItemOp'].",'$citoOperasi',1, ".$_POST['hargaOperasi'].",".(double)$_POST['diskonNominalOp'].",
		".(double)$_POST['dibayarPenjaminOperasi'].",".($_POST['hargaOperasi']-(double)$_POST['diskonNominalOp']+$citoOperasi).", 0,
		".$bangsal_id.",".$_POST['idOp1'].")";

$tr->addSQL($SQLOP);

$SQLRELOP = "INSERT INTO rs00008_op(id_rs08, trans_group_rs00008, id_dokter1, diskon_dokter1, id_dokter2, diskon_dokter2, id_asisten1,
  diskon_asisten1, id_asisten2, diskon_asisten2, id_asisten3, diskon_asisten3) 
  VALUES(currval('rs00008_seq'),currval('rs00008_seq_group'), ".(int)$_POST['idOp1'].", ".(double)$_POST['diskonNominalOperasi1'].",
  ".(int)$_POST['idOp2'].", ".(double)$_POST['diskonNominalOperasi2'].",".(int)$_POST['idAsistenOp1'].", ".(double)$_POST['diskonNominalAsistenOperasi1'].",
  ".(int)$_POST['idAsistenOp2'].", ".(double)$_POST['diskonNominalAsistenOperasi2'].", ".(int)$_POST['idAsistenOp3'].", ".(double)$_POST['diskonNominalAsistenOperasi3'].")";
  
$tr->addSQL($SQLRELOP);

$SQLAN = "INSERT INTO rs00008(id, trans_type, trans_form, trans_group, tanggal_trans, tanggal_entry, waktu_entry, no_reg, item_id,referensi,
		qty, harga, diskon,dibayar_penjamin, tagihan, pembayaran, bangsal_id, no_kwitansi) VALUES(nextval('rs00008_seq'), 'LTM', 'p_operasi',
		currval('rs00008_seq_group'), CURRENT_DATE, CURRENT_DATE, CURRENT_TIME,'".$_POST['rg']."', ".(int)$_POST['idItemAn'].",
		'$citoAnestesi',1, ".$_POST['hargaAnestesi'].",".(double)$_POST['diskonNominalAnestesi'].", ".$_POST['dibayarPenjaminAnestesi'].",
		".($_POST['hargaAnestesi']-(double)$_POST['diskonNominalAnestesi']+$citoAnestesi).", 0, ".$bangsal_id.",".(int)$_POST['idAn1'].")";

$tr->addSQL($SQLAN);

$SQLRELAN = "INSERT INTO rs00008_op(id_rs08, trans_group_rs00008, id_dokter1, diskon_dokter1, id_dokter2, diskon_dokter2, id_asisten1,
  diskon_asisten1, id_asisten2, diskon_asisten2, id_asisten3, diskon_asisten3) VALUES(currval('rs00008_seq'),currval('rs00008_seq_group'), 
  ".(int)$_POST['idAn1'].", ".(double)$_POST['diskonNominalAnestesi1'].",".(int)$_POST['idAn2'].", ".(double)$_POST['diskonNominalAnestesi2'].",
  ".(int)$_POST['idAsistenAn1'].", ".(double)$_POST['diskonNominalAsistenAnestesi1'].",".(int)$_POST['idAsistenAn2'].", 
  ".(double)$_POST['diskonNominalAsistenAnestesi2'].", ".(int)$_POST['idAsistenAn3'].", ".(double)$_POST['diskonNominalAsistenAnestesi3'].")";
  
$tr->addSQL($SQLRELAN);

if(!$tr->execute()){
echo json_encode(array($tr->showSQL()));
}
