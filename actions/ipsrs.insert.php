<?php // Nugraha, 14/02/2004

$PID = "ipsrs";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
  
if ($_POST[action] == "edit") {
	$SQL = "update rs80808 set 
			tanggal='".$_POST[f_tanggal]."', waktu='".$_POST[f_waktu]."', 
			nomor='".$_POST[f_nomor]."', jns_kegiatan='".$_POST[f_jns_kegiatan]."', 
			catatan_jns='".$_POST[f_catatan_jns]."',catatan='".$_POST[f_catatan]."',
			pelapor='".$_POST[f_pelapor]."',pekerja='".$_POST[f_pekerja]."',id_ruang='".$_POST[f_id_ruang]."' where id_ipsrs = '".$_POST[id]."'";
	
	pg_query($con, $SQL);

	header("Location: ../index2.php?p=$PID&action=tambah&id=".$_POST[id]."");
}elseif ($_POST[action] == "new"){
	$SQL = "insert into rs80808 (id_ipsrs,tanggal, waktu, nomor, jns_kegiatan, catatan_jns,catatan,pelapor,pekerja,status,id_ruang)
						 values (nextval('rs80808_seq'),'".$_POST[f_tanggal]."','".$_POST[f_waktu]."','".$_POST[f_nomor]."','".$_POST[f_jns_kegiatan]."','".$_POST[f_catatan_jns]."','".$_POST[f_catatan]."',
			'".$_POST[f_pelapor]."','".$_POST[f_pekerja]."','0','".$_POST[f_id_ruang]."') ";
	
	pg_query($con, $SQL);

	header("Location: ../index2.php?p=$PID");
}elseif ($_POST[action] == "tambah"){
	$SQL = "insert into rs80888 (id_ipsrs,suku_cadang, jumlah,id)
						 values ('".$_POST[id]."','".$_POST[suku_cadang]."','".$_POST[jumlah]."',nextval('rs80808_seq')) ";

	pg_query($con, $SQL);

	header("Location: ../index2.php?p=$PID&action=tambah&id=".$_POST[id]."");
}elseif ($_GET[action] == "hapus"){
	$SQL = "delete from rs80888 where id = '".$_GET[id]."'";
	
	pg_query($con, $SQL);

	header("Location: ../index2.php?p=$PID&action=tambah&id=".$_GET[id_ipsrs]."");
}elseif ($_GET[action] == "hapus1"){
	$SQL = "delete from rs80888 where id_ipsrs = '".$_GET[id_ipsrs]."'";
	$SQL1 = "delete from rs80808 where id_ipsrs = '".$_GET[id_ipsrs]."'";
	pg_query($con, $SQL);
        pg_query($con, $SQL1);
	header("Location: ../index2.php?p=$PID");
}elseif ($_POST[action] == "status"){
	$SQL = "update rs80808 set 
                status='".$_POST[f_status]."', tgl_selesai='".$_POST[f_tgl_selesai]."', waktu_selesai='".$_POST[f_waktu_selesai]."', 
                catatan_hasil='".$_POST[f_catatan_hasil]."' where id_ipsrs = '".$_POST[id]."'";
	
	pg_query($con, $SQL);
	header("Location: ../index2.php?p=$PID");
}


exit;

?>
