<?
$PID = "hrd_tambah_jadwal";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

if ($_POST[f_tempat_poli] == "" and $_POST[f_tempat] == "P") {
	$pesan2 = "* Pilih Poli";
   header("Location: ../index2.php?p=hrd_tambah_jadwal&q=reg&id=".$_POST[f_id_pegawai]."&psn=$pesan2");
   exit();
}
if ($_POST[f_tempat_bangsal] == "" and $_POST[f_tempat] == "B") {
	$pesan3 = "* Pilih Bangsal";
   header("Location: ../index2.php?p=hrd_tambah_jadwal&q=reg&id=".$_POST[f_id_pegawai]."&psn2=$pesan3");
   exit();
}
$sql1 = "select * from hrd_absen where id_pegawai = ".$_POST['f_id_pegawai']." and tanggal = '".$_POST['f_tanggal']."' and shift = '".$_POST['f_shift']."' ";
$result2 = pg_query($con, "$sql1");
$row = pg_num_rows($result2) ;
if ($row > 0  or $_POST[f_tanggal] < date("Y-m-d", time())){
    //echo "<script>alert(\"The two password did ".$sql1." not match.\");</script>";
	$pesan4 = "* Anda gagal menjadwalkan, jadwalkan yang lain";
   header("Location: ../index2.php?p=hrd_tambah_jadwal&q=reg&id=".$_POST[f_id_pegawai]."&psn2=$pesan4");
   exit();
}
pg_query("select nextval('hrd_absen_seq')");

if ($_POST['f_tempat'] == 'B'){
	$poli = '-';
        $bangsal = $_POST['f_tempat_bangsal'];
}elseif ($_POST['f_tempat'] == 'P'){
	$poli = $_POST['f_tempat_poli'];
        $bangsal = '-';
}else{
        $poli = '-';
        $bangsal = '-';
}

$qb = New InsertQuery();
$qb->TableName = "hrd_absen";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "currval('hrd_absen_seq')");
$SQL = $qb->build();

pg_query($con, $SQL);

$r = pg_query($con, "select currval('hrd_absen_seq') as id");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

header("Location: ../index2.php?p=$PID");

exit;

?>
