<?php // Nugraha Tue Mar 30 04:27:21 WIT 2004

$PID = "1202";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");



pg_query("select nextval('rs00006_seq')");
pg_query("select nextval('rs00008_seq')");
pg_query("select nextval('rs00008_seq_group')");

if ($_POST[f_poli] == "" and $_POST[f_rawat_inap] == "Y") {
	$pesan2 = "* Pilih Poli";
   header("Location: ../index2.php?p=1202&q=reg&mr_no=".$_POST[f_mr_no]."&psn2=$pesan2");
   exit();
}


//cecking pasien lama atau baru
// pasien lama = pasien yg sudah terdaftar di POLI tertentu

if ($_POST['f_poli'] == ''){
	//UGD
	$poli = '100';
}else{
	$poli = $_POST['f_poli'];
}

$SQL = "select mr_no, poli from rs00006 where mr_no = '{$_POST['f_mr_no']}' and poli = '$poli' ";
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


$qb = New InsertQuery();
$qb->TableName = "rs00006";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "lpad(currval('rs00006_seq'),10,'0')");
$SQL = $qb->build();



 pg_query($con, $SQL);

$r = pg_query($con, "select lpad(currval('rs00006_seq'),10,'0') as no_reg");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);



// karcisnya diinput ke table sekalian
 $no_reg = $d->no_reg;
 include("../includes/karcis.php");
// end of proses input karcis


// insert POT, ASK, BYR sekalian
//pg_query("insert into rs00005 values(nextval('kasir_seq'),'$d->no_reg',CURRENT_DATE,'BYR','N','N',0,0,'Y')");

 
pg_query("insert into rs00005 values(nextval('kasir_seq'), '$d->no_reg', CURRENT_DATE, 'ASK', 'N', 'N', 0, 0, 'Y')");
pg_query("insert into rs00005 values(nextval('kasir_seq'),'$d->no_reg',CURRENT_DATE,'POT','N','N',0,0,'Y')");
 

// end of POT, ASK, BYR


/* Insert untuk pendaftaran rawat inapnya // by yudha */

if (!$_SESSION["BANGSAL"]["id"]) {
	 $_SESSION["BANGSAL"]["id"] = $_POST["kode_bangsal"];
	}
	
// echo "hasilnya:".$_POST['kode_bangsal']."=".$_POST['f_mr_no'] ;

 
if (!$_SESSION["BANGSAL"]["id"]) {  $_SESSION["BANGSAL"]["id"] = $_POST["f_kode_bangsal"]; }
 
$ts_check_in = date("Y-m-d", mktime(0,0,0,$_POST["tanggalM"],$_POST["tanggalD"],$_POST["tanggalY"]))." ".$_POST["jam"].":00";
$jam = (int) substr($_POST["jam"],0,2);

if ($jam >= 12) {
    $ts_calc_start = date("Y-m-d", mktime(0,0,0,$_POST["tanggalM"],$_POST["tanggalD"],$_POST["tanggalY"])). " 12:00:00";
} else {
    $ts_calc_start = date("Y-m-d", mktime(0,0,0,$_POST["tanggalM"],$_POST["tanggalD"]-1,$_POST["tanggalY"])). " 12:00:00";
}

$SQL = "insert into rs00010 (id, no_reg, bangsal_id, ts_check_in, ts_calc_start) ".
       "values (nextval('rs00010_seq'),'".$no_reg."','".$_SESSION["BANGSAL"]["id"].
       "','$ts_check_in'::timestamp,'$ts_calc_start'::timestamp)";

 
$r1 = pg_query($con,
    "select tipe, jenis_kedatangan_id as rujukan, id as no_reg, tanggal_reg, rawat_inap ".
    "from rs00006 ".
    "where id = '".$no_reg."'");
$n1 = pg_num_rows($r1);
if($n1 > 0) $d1 = pg_fetch_object($r1);
pg_free_result($r1);
$reg_count = getFromTable("select count(mr_no) from rs00006 ".
            "where mr_no = (select mr_no from rs00006 where id = '".$_POST["rg"]."') ".
            "   and id <= '".$no_reg."'");
$baru    = "Y";
 
$bangsal = $_SESSION["BANGSAL"]["id"];


$asal	 = $_POST["asal"];
$tglmasuk= $ts_check_in;

if ($reg_count > 1 ) $baru = "T";

$SQL1 = "insert into rs00008 (id,trans_type, is_inout, qty,  ".
        "		is_baru,no_reg, tanggal_trans,datang_id,trans_group) ".
        "values (nextval('rs00008_seq'),'RIN','I',1, ".
        "'$baru','$no_reg','$tglmasuk','$asal', nextval('rs00008_seq_group'))";

$SQL2 = "update rs00006 set rawat_inap='I', rujukan = '$asal' where id ='".$no_reg."'";
$SQL3 = "update rs00005 set kasir='RIN'                       where reg='".$no_reg."' and (kasir = 'RJL' OR kasir = 'IGD') ";
$SQL4 = "update rs00006 set status='A'                        where id='".$no_reg."' ";

 
 
unset($_SESSION["BANGSAL"]);
pg_query($con, $SQL);
pg_query($con, $SQL1);
pg_query($con, $SQL2);
pg_query($con, $SQL3);
pg_query($con, $SQL4);





 //header("Location: ../index2.php?p=1202&id=$no_reg");
header("Location: ../index2.php?p=$PID");

exit;

?>
