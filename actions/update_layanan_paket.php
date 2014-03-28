<?php
require_once("../lib/dbconn.php");
pg_query("UPDATE rs00008 SET qty = ".$_POST['qty'].", tagihan = ".$_POST['tagihan'].", dibayar_penjamin = ".$_POST['dibayar_penjamin'].",
diskon = ".$_POST['diskon'].", persen = ".$_POST['persen']." WHERE id = ".$_POST['id']);
header('Location:../popup/edit_layanan_paket.php?id='.$_POST['id'].'&e=0');
