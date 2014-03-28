<?php // Nugraha, 14/02/2004

$PID = "110";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
 
$thnini = date("Y", time());
if ($_POST[f_umur] == "") {

   $_POST[f_umur] = $thnini - $_POST[f_tgl_lahirY];
} else {
  // $_POST[f_tgl_lahirD] = 1;
  // $_POST[f_tgl_lahirM] = 1;
   $_POST[f_tgl_lahirY] = $thnini - $_POST[f_umur];

}


$reg=$_POST["mr_no"] ;
$uid=$_POST["uid"] ;
$nama_usr=$_POST["nama_usr"] ;
 
$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs00002";
$qb->VarPrefix = "f_";
$qb->VarTypeIsDate = Array("tgl_lahir");
$qb->addPrimaryKey("mr_no", "'" . $_POST["mr_no"] . "'");
$SQL = $qb->build();


 pg_query($con, $SQL);

 //========= Trendy Dwi A G 08/01/2014 hystory user
	    pg_query("insert into history_user " .
		            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
		            "values".
		            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Edit Data Pasien', ".
		            "'Front Office -> Data No MR -> Edit Data Pasien','Data Pasien dengan No.MR $reg telah Diubah', ".
		            "'$uid','$nama_usr')");
		//=========
 
 header("Location: ../index2.php?p=$PID");
 exit;

?>
