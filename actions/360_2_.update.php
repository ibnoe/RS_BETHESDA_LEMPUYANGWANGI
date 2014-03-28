<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
      // sfdn, 23-04-2004
      // sfdn, 09-05-2004

session_start();
$PID = "360_2";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

if($_POST["no_faktur"]=='' or $_POST["jatuh_tempo"]==''){
	?>
     <script>
         alert ('Data belum lengkap!');
     </script>    
     <?
    echo "<script language='JavaScript'>document.location='../index2.php?p=$PID&edit=edit1&poid=".$_POST["po_id"]."'</script>";
	
}else{
$SQL = "update c_po set jatuh_tempo = '".$_POST["jatuh_tempo"]."',no_faktur='".$_POST["no_faktur"]."' ".
       "where po_id='".$_POST["po_id"]."'";

//$tot_hutang=getFromTable("select sum(jumlah_harga) from c_po_item where po_id='".$_POST["po_id"]."'");

$SQL3 = "update piutang_po set no_faktur='".$_POST["no_faktur"]."',tgl_bayar='".$_POST["jatuh_tempo"]."' where po_id='".$_POST["po_id"]."'";
pg_query($con, $SQL3);

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID&edit=view2&poid=".$_POST["po_id"]."");
exit;   
}
?>
