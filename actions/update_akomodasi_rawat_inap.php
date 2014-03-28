<?php
require_once("../lib/dbconn.php");

pg_query("UPDATE rs00008 SET harga = ".$_POST['harga'].",qty = ".$_POST['qty'].", tagihan = ".($_POST['qty']*$_POST['harga']-$_POST['diskon']).", 
dibayar_penjamin = ".($_POST['dibayar_penjamin']).", bangsal_id = ".$_POST['bangsal_id'].",
 diskon = ".$_POST['diskon'].", persen = ".$_POST['persen']." WHERE id = ".$_POST['id']);
/**
pg_query("UPDATE rs00005 SET jumlah = ".$_POST['tagihan'].", keterangan = '".$_POST['bangsal_id']."-".$_POST['qty']."' WHERE id = 
(SELECT id FROM rs00005 WHERE reg = '".$_POST['reg']."' AND is_obat = 'N' AND keterangan = '".$_POST['bangsal_id']."-".$_POST['qty_05']."' 
AND tgl_entry = '".$_POST['tanggal_entry']."' AND kasir = 'RIN' AND layanan = '99996' LIMIT 1)");
**/
header('Location:../popup/edit_akomodasi_ri.php?id='.$_POST['id'].'&e=0');
