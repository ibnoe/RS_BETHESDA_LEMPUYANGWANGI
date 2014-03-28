<?php
require_once("../lib/dbconn.php");
ini_set('display_errors',1);
pg_query("UPDATE rs00008 SET harga = ".floatval($_POST['harga']).", qty = ".$_POST['qty'].", tagihan = ".floatval($_POST['tagihan']).", dibayar_penjamin = ".floatval($_POST['dibayar_penjamin']).",
  diskon = ".floatval($_POST['diskon']).", persen = ".floatval($_POST['persen']).", no_kwitansi = ".$_POST['idDokter']." WHERE id = ".$_POST['id']);
if($_SESSION['uid']=='root'){
  pg_query("UPDATE rs00008 SET harga = ".floatval($_POST['harga']).", qty = ".$_POST['qty'].", tagihan = ".floatval($_POST['tagihan']).", dibayar_penjamin = ".floatval($_POST['dibayar_penjamin']).",
  diskon = ".floatval($_POST['diskon']).", persen = ".floatval($_POST['persen']).", no_kwitansi = ".$_POST['idDokter']." WHERE id = ".$_POST['id']);
}
echo "UPDATE rs00008 SET harga = ".floatval($_POST['harga']).", qty = ".$_POST['qty'].", tagihan = ".floatval($_POST['tagihan']).", dibayar_penjamin = ".floatval($_POST['dibayar_penjamin']).",
  diskon = ".floatval($_POST['diskon']).", persen = ".floatval($_POST['persen']).", no_kwitansi = ".$_POST['idDokter']." WHERE id = ".$_POST['id'];
header('Location:../popup/edit_layanan_non_paket.php?id='.$_POST['id'].'&e=0');
