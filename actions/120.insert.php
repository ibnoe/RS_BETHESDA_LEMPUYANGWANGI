<? // Nugraha Tue Mar 30 04:27:21 WIT 2004

$PID = "120";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");



//pg_query("select nextval('rs00006_seq')");
pg_query("select nextval('rs00008_seq')");
pg_query("select nextval('rs00008_seq_group')");/*

  */
if ($_POST[f_poli] == "" and $_POST[f_rawat_inap] == "Y") {
	$pesan2 = "* Pilih Poli";
   header("Location: ../index2.php?p=120&q=reg&mr_no=".$_POST[f_mr_no]."&psn2=$pesan2");
   exit();
}


//cecking pasien lama atau baru
// pasien lama = pasien yg sudah terdaftar di POLI tertentu

if ($_POST['f_poli'] == '' and $_POST[f_rawat_inap] != "I"){
	//UGD
	$poli = '100';
}else{
	$poli = $_POST['f_poli'];
}

$SQL = "select mr_no, poli from rs00006 where mr_no = '{$_POST['f_mr_no']}' and poli::text = '$poli' ";
	$r2 = pg_query($con,$SQL);
    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
    
//cek pasien udah terdaftar di poli?
if ($d2->mr_no == '' ){
	//jika belum ada -> pasien baru
	$_POST['f_jenis_kedatangan_id'] = '001';
}else {
	// pasien lama
	$_POST['f_jenis_kedatangan_id'] = '003';
}

//=========================
/*

$qb = New InsertQuery();
$qb->TableName = "rs00006";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";

//$qb->addFieldValue("id", $no_reg_baru);
$qb->addFieldValue("id", "currval('rs00006_seq')");
$SQL = $qb->build();
pg_query($con, $SQL);
*/
	 //
	
	//Ambil semua data
	//date_default_timezone_set("Asia/Jakarta");
    $tanggal_regis= date('Y-m-d');
	$mr_no = $_POST['f_mr_no'];
	$id_penanggung = $_POST['f_id_penanggung'];
	$id_penjamin= $_POST['f_id_penjamin'];
	$no_jaminan= $_POST['f_no_jaminan'];
	$rujukan = $_POST['f_rujukan'];
	$rujukan_rs_id =$_POST['f_rujukan_rs_id'];
	$rujukan_dokter = $_POST['f_rujukan_dokter'];
	$rawat_inap = $_POST['f_rawat_inap'];
	$status = $_POST['f_status'];
	$tipe = $_POST['f_tipe'];
	$diagnosa_sementara = $_POST['f_diagnosa_sementara'];
	$is_bayar = "N";
	$status_bayar = "-";
	$status_akhir= $_POST['f_status_akhir_pasien'];
	$jml_bayar_akhir = 0;
	$tgl_keluar = $tanggal_regis;
	$jenis_kedatangan =$_POST['f_jenis_kedatangan_id'];
	$is_karcis = "Y";
	$nm_penanggung=$_POST['f_nm_penanggung'];
	$hub_penanggung=$_POST['f_hub_penanggung'];
	$is_baru = $_POST['f_is_baru'];
	$periksa = "N";
	$user_id= $_POST['f_user_id'];
	$is_out = "N";
	$no_asuransi =$_POST['f_no_asuransi'];
	$jenis_penunjang = "";
	$status_apotek = "0";

	
	$tr = new PgTrans;
	$tr->PgConn = $con;
	//ambil bulan dari tanggal terakhir
	$tanggal_akhir = getFromTable("select tanggal_reg from rs00006 ORDER BY tanggal_reg desc limit 1");
	$bulan_akhir =  substr($tanggal_akhir,-5,2);
	//pengambilan hari ini
	//$tanggal_sekarang = date("d/m/Y",pgsql2mktime($d->tanggal_reg));
	$hari =  date('d');
	$bulan =  date('m');
	$tahun = substr(date('Y'),-2);
	//cek tanggal = 01 atau cek pergantian bulan
	if($bulan != $bulan_akhir){
		$no_reg_baru = (string) "0001".$bulan.$tahun;
	}else{//jika menambah no_reg
		$no_reg_akhir2 = getFromTable("select substring(id,1,4) from rs00006 ORDER BY oid desc, tanggal_reg desc limit 1");
		//$no_reg_akhir2 = (string) substr($no_reg_akhir,4);
		$no_reg_akhir3 = (int) $no_reg_akhir2 + 1;
		$panjang_no_reg= strlen($no_reg_akhir3);
		switch($panjang_no_reg){
			case 1 : $no_reg_baru = (string) "000".$no_reg_akhir3.$bulan.$tahun;
					break;
			case 2 : $no_reg_baru = (string) "00".$no_reg_akhir3.$bulan.$tahun;
					break;
			case 3 : $no_reg_baru = (string) "0".$no_reg_akhir3.$bulan.$tahun;
					break;
			default: $no_reg_baru = (string) $no_reg_akhir3.$bulan.$tahun;
					break;
		}
	}	

	
	
	$tr2=pg_query($con,"insert into rs00006 (id,mr_no,id_penanggung,id_penjamin,no_jaminan".
			",rujukan,rujukan_rs_id,rujukan_dokter,rawat_inap,status,tipe,diagnosa_sementara,is_bayar,status_bayar,".
			"status_akhir_pasien,jml_bayar_akhir,tgl_keluar,jenis_kedatangan_id,poli,is_karcis,nm_penanggung, hub_penanggung,".
			"is_baru,periksa,user_id,is_out,no_asuransi,jenis_penunjang,status_apotek) values ('$no_reg_baru',".
			"'$mr_no','$id_penanggung','$id_penjamin','$no_jaminan'".
			",'$rujukan','$rujukan_rs_id','$rujukan_dokter','$rawat_inap','$status','$tipe','$diagnosa_sementara','$is_bayar','$status_bayar',".
			"'$status_akhir','$jml_bayar_akhir','$tgl_keluar','$jenis_kedatangan','$poli','$is_karcis','$nm_penanggung', '$hub_penanggung',".
			"'$is_baru','$periksa','$user_id','$is_out','$no_asuransi','$jenis_penunjang','$status_apotek' )");
//if($tr2){echo "asdfdasf";echo $no_reg_baru;}else{echo"salah";echo $poli;}

$r = pg_query($con, "select id as no_reg from rs00006 ORDER BY  oid desc, tanggal_reg desc  limit 1 ");
//$r = pg_query($con, "select currval('rs00006_seq') as no_reg");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

if ($_POST["f_poli"] == "" and $_POST["f_rawat_inap"] == "I"){
$SQLh="update rs00006 set poli='208',status_akhir_pasien='012', rawat_inap='N' where id='$d->no_reg' and poli='0' and rawat_inap='I'";
pg_query($con, $SQLh);
}

// karcisnya diinput ke table sekalian
$no_reg = $d->no_reg;
include("../includes/karcis.php");
// end of proses input karcis


// insert POT, ASK, BYR sekalian
//pg_query("insert into rs00005 values(nextval('kasir_seq'),'$d->no_reg',CURRENT_DATE,'BYR','N','N',0,0,'Y')");
pg_query("insert into rs00005 values(nextval('kasir_seq'), '$d->no_reg', CURRENT_DATE, 'ASK', 'N', 'N', 0, 0, 'Y')");
pg_query("insert into rs00005 values(nextval('kasir_seq'),'$d->no_reg',CURRENT_DATE,'POT','N','N',0,0,'Y')");

//Agung Sunandar, penyimpanan untuk pasien MPK
/* if ($_POST["f_poli"] == "101"){
    $tm1=date("Y-m-d H:i:s");
    $SQLa="insert into c_visit (no_reg,tanggal_reg,id_poli,id_konsul) values ('$d->no_reg', '$tm1', '".$_POST['f_poli']."', '203')";
    $tm2=date("Y-m-d H:i:s");
    $SQLb="insert into c_visit (no_reg,tanggal_reg,id_poli,id_konsul) values ('$d->no_reg', '$tm2', '".$_POST['f_poli']."', '204')";
    $tm3=date("Y-m-d H:i:s");
    $SQLc="insert into c_visit (no_reg,tanggal_reg,id_poli,id_konsul) values ('$d->no_reg', '$tm3', '".$_POST['f_poli']."', '103')";
    $tm4=date("Y-m-d H:i:s");
    $SQLd="insert into c_visit (no_reg,tanggal_reg,id_poli,id_konsul) values ('$d->no_reg', '$tm4', '".$_POST['f_poli']."', '102')";
    $tm5=date("Y-m-d H:i:s");
    $SQLe="insert into c_visit (no_reg,tanggal_reg,id_poli,id_konsul) values ('$d->no_reg', '$tm5', '".$_POST['f_poli']."', '106')";
    $tm6=date("Y-m-d H:i:s");
    $SQLf="insert into c_visit (no_reg,tanggal_reg,id_poli,id_konsul) values ('$d->no_reg', '$tm6', '".$_POST['f_poli']."', '105')";
    $tm7=date("Y-m-d H:i:s");
    $SQLg="insert into c_visit (no_reg,tanggal_reg,id_poli,id_konsul) values ('$d->no_reg', '$tm7', '".$_POST['f_poli']."', '116')";
    pg_query($con, $SQLa);
    pg_query($con, $SQLb);
    pg_query($con, $SQLc);
    pg_query($con, $SQLd);
    pg_query($con, $SQLe);
    pg_query($con, $SQLf);
    pg_query($con, $SQLg);
} */
if ($_POST["f_poli"] == "" and $_POST["f_rawat_inap"] == "I"){
$SQLh="update rs00006 set poli='208',status_akhir_pasien='012', rawat_inap='N' where id='$d->no_reg' and poli='0' and rawat_inap='I'";
pg_query($con, $SQLh);
}
//echo $d->no_reg;
header("Location: ../index2.php?p=121&id=$d->no_reg");
//header("Location: ../index2.php?p=$PID");

exit;

?>
