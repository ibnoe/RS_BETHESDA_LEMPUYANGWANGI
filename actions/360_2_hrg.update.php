<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
      // sfdn, 23-04-2004
      // sfdn, 09-05-2004

session_start();
$PID = "360_2";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

if($_POST["harga_beli"]=='' or $_POST["harga_jual"]==''){
	?>
     <script>
         alert ('Data belum lengkap!');
     </script>    
     <?
	 
    echo "<script language='JavaScript'>document.location='../index2.php?p=$PID&edit=edit_harga&poid=".$_POST["po_id"]."&e=".$_POST["item_id"]."&o=".$_POST["o"]."'</script>";
	
}else{
$SQL = "update rs00016 set harga_beli = '".$_POST["harga_beli"]."',harga='".$_POST["harga_jual"]."' ".
       "where obat_id='".$_POST["item_id"]."'";

$SQL3 = "update c_po_item set po_status=2,harga_beli = ".$_POST["harga_beli_pesan"].", jumlah_harga= ".$_POST["tot_harga"]." - (".$_POST["diskon1"]." + ".$_POST["diskon2"].") , diskon1=".$_POST["diskon1"].", diskon2=".$_POST["diskon2"]."  
where po_id='".$_POST["po_id"]."' and item_id='".$_POST["item_id"]."'";
pg_query($con, $SQL3);
	   
$SQL3 = "update piutang_po set jumlah_hutang =jumlah_hutang + ".$_POST["tot_harga"]." where po_id='".$_POST["po_id"]."'";
pg_query($con, $SQL3);

pg_query($con, $SQL);

//========== Agung SUnandar 22:28 26/06/2012 hystory user
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Penerimaan Farmasi','Inventori -> Penerimaan Faktur','Menambah harga obat ".$_POST["o"]." dengan harga ".$_POST["harga_jual"]." pada ".$_POST["po_id"]." dan Faktur ".$_POST["no_faktur"]."','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
pg_query($con, $SQL2);
//======================

header("Location: ../index2.php?p=$PID&edit=view2&poid=".$_POST["po_id"]."");
exit;   
}
?>
