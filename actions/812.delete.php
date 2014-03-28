<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004
session_start();

$PID = "812";
$PID2="Kelompok Sumber Pendapatan";

require_once("../lib/dbconn.php");

if (empty($_GET[sure])) {
	
        header("Location: ../index2.php?p=$PID&e=" . $_GET["e"] ."&sure=false");
	exit();

} elseif ($_GET[sure] == "YA") {

         $SQL = "delete from rs00021 where ".
              "id = '".$_GET["e"]."' ";
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'$PID2','SysAdmin -> Kelompok Sumber Pendapatan','Menghapus Sum. Pendapatan ".$_GET["e"]."','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
    
	
} else {

    header("Location: ../index2.php?p=$PID");
    exit();

}


pg_query($con, $SQL2);
pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID&sort=".$_GET[sort].
	"&order=".$_GET[order]."&tblstart=".$_GET[tblstart]);
exit;

?>
