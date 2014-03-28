<?php // tokit, 2004 09 07

$PID = "140";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

//pg_query("select nextval('rs00008_seq')");
//pg_query("select nextval('rs00008_seq_group')");

if (empty($_POST[f_poli])) {

   $poli = $_POST[xpoli];
   //header("Location: ../index2.php?p=140&e=".$_POST[no_reg]."&err=1");
   //echo "xx: ".$poli." xpoli: ".$_POST[xpoli];
   //exit();
} else {
   $poli = $_POST[f_poli];
}

$tgl_sekarang = date("Y-m-d", time());
$tgl_reg = $_POST[tanggal_reg];
//if ($tgl_sekarang == $tgl_reg) {

	// close $tgl_reg dan tidak rubah -> tanggal_reg='$tgl_reg', 
	// $tgl_reg = $_POST[tgl_regY]."-".$_POST[tgl_regM]."-".$_POST[tgl_regD];
	if ($_POST['rawat_inap']!= 'I') {
	pg_query("update rs00006 set poli=".$poli.", tipe='".$_POST['f_tipe']."', rujukan='".$_POST['f_rujukan']."', ".
		 	 "rujukan_rs_id='".$_POST['f_rujukan_rs_id']."', id_penanggung='".$_POST['f_id_penanggung']."', ".
		 	 "id_penjamin='".$_POST['f_id_penjamin']."', rujukan_dokter='".$_POST['f_rujukan_dokter']."', ".
		 	 "diagnosa_sementara='".$_POST['f_diagnosa_sementara']."',rawat_inap='".$_POST['f_rawat_inap']."' ".
		 	 "where id='".$_POST['no_reg']."'") or die("error atuh");
	}
	//pg_query("delete from rs00008 where no_reg='".$_POST[no_reg]."' and trans_form = '120' ");
	//pg_query("delete from rs00005 where is_karcis='Y' and reg = '".$_POST[no_reg]."'");
	if($_POST['f_tipe']=='001'){
		pg_query("UPDATE rs00008 SET tagihan = (harga*qty-diskon)+referensi::numeric, dibayar_penjamin = 0 WHERE no_reg = '".$_POST['no_reg']."' AND trans_type IN('OB1','RCK','BHP') and referensi !='-' ");
	}
	else{
		pg_query("UPDATE rs00008 SET tagihan = (harga*qty-diskon)+referensi::numeric, dibayar_penjamin = harga*qty-diskon WHERE no_reg = '".$_POST['no_reg']."' AND trans_type IN('OB1','RCK','BHP') and referensi !='-' ");
	}
	// input karcis
	$kodepoli = $poli;
	$no_reg = $_POST[no_reg];
	include("../includes/karcis.php");
	// end of input karcis
	
	//header("Location: ../index2.php?p=$PID&q=search&search=".$_POST[mr_no]);
	header("Location: ../index2.php?p=$PID&q=search&search=".$_POST[no_reg]);
	exit;
	
//} else {
//	echo "\n<script language='JavaScript'>\n";
//	echo "alert ('Maaf, Data regsitrasi pasien kemarin tidak bisa dirubah.')\n";
//	echo "</script>\n";
//	die("Maaf, Data regsitrasi pasien kemarin tidak bisa dirubah !!!");
	
//}

?>
