<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004
session_start();

$PID = "811_2";
$PID2 = "%Pembagian sum. pendapatan";

require_once("../lib/dbconn.php");

if (empty($_GET[sure])) {
	
        header("Location: ../index2.php?p=$PID&e=" . $_GET["e"] ."&unit_medis_id=$_GET[unit_medis_id]&sure=false");
	exit();

} elseif ($_GET[sure] == "YA") {

         $SQL = "delete from rs00020 where ".
              "id = '".$_GET["e"]."' ";
//========== hystory user
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'$PID2','SysAdmin -> %Pembagian Sumber Pendapatan','Menghapus %Pemb. Sum. Pendapatan','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
    
//======================
} else {

    header("Location: ../index2.php?p=$PID&unit_medis_id=$_GET[unit_medis_id]");
    exit();

}


pg_query($con, $SQL2);
pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID&unit_medis_id=$_GET[unit_medis_id]&sort=".$_GET[sort].
	"&order=".$_GET[order]."&tblstart=".$_GET[tblstart]);
exit;

?>
