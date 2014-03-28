<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004
      // sfdn, 30-04-2004

$PID = "807";
$OBT = $_POST["mOBT"];
$kat= $_POST["f_kategori_id"];

if ($_POST['f_satuan_id'] == "") $_POST['f_satuan_id'] = "999";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb1 = New InsertQuery();
$qb1->TableName = "rs00015";
$qb1->HttpAction = "POST";
$qb1->VarPrefix = "f_";
$qb1->addFieldValue("id", "nextval('rs00015_seq')");
//$qb1->addFieldValue("id_obat", "nextval('rs00015_seq')"); 
$SQL1 = $qb1->build();

pg_query($con, $SQL1);

//Input harga obat sesuai settingan margin harga jual di apotik rs sarila husada 27 Februari 2013 by Me
$r4 = pg_query($con,
                "select * " .
                "from margin_apotik " .
                "where kategori_id = '$kat'");
        $d4 = pg_fetch_object($r4);
        pg_free_result($r4);


if($kat=='029'){ //KATEGORI VAKSIN
	$harga_vaksin=$_POST['harga']*1.20;
$SQL2 = "insert into rs00016 (id,obat_id,tanggal_entry,harga,harga_pamper,harga_beli)". 
"values (nextval('rs00016_seq'),currval('rs00015_seq'),CURRENT_DATE,'".$_POST["harga"]."',
$harga_pamper,'".$_POST["harga_beli"]."')";

pg_query("insert into rs00016a values (currval('rs00015_seq'), 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
@$err = pg_query($con, $SQL2);
} else if($kat=='040'){ //PAMPERS & PEMBALUT
	$harga_pamper=$_POST['harga']*1.15;
$SQL2 = "insert into rs00016 (id,obat_id,tanggal_entry,harga,harga_pamper,harga_beli)". 
"values (nextval('rs00016_seq'),currval('rs00015_seq'),CURRENT_DATE,'".$_POST["harga"]."',$harga_pamper,'".$_POST["harga_beli"]."')";
// tokit: buat stok awal = 0 utk apotek rj dan ri
pg_query("insert into rs00016a  ".
	"values (currval('rs00015_seq'), 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");

@$err = pg_query($con, $SQL2);
}/*else if($kat=='013' || $kat=='014' || $kat=='048'){ //Sirup & Salep/Tetes
	//Formula RAJAL OBAT LUAR DAN TABLET
	$harga_car_drs=$_POST['harga']*1.25;

	//Formula RAJAL INJEKSI DAN ALKES
	$harga_car_rsrj=$_POST['harga']*1.25;

	//Formula RAJAL TAGIHAN
	$harga_car_rsri=$_POST['harga']*1.25;

	//Formula HV
	$harga_inhealth_drs=$_POST['harga']*1.25;

	//Formula BON KARYAWAN
	$harga_inhealth_rs=$_POST['harga']*1.25;

	//Formula RAJAL KARYAWAN
	$harga_jam_ri=$_POST['harga']*1.25;

	//Formula ROS
	$harga_jam_rj=$_POST['harga']*1.25;

	//Formula RANAP UMUM KELAS III
	$harga_kry_kelinti=$_POST['harga']*1.25;

	//Formula RANAP UMUM KELAS II - VIP
	$harga_kry_kelbesar=$_POST['harga']*1.25;

	//Formula RANAP IBU KELAS III (KHUSUS)
	$harga_kry_kelgratisri=$_POST['harga']*1.25;

	//Formula RANAP IBU KELAS III - VIP
	$harga_kry_kelgratisrj=$_POST['harga']*1.25;

	//Formula RANAP BAYI KELAS III (KHUSUS)
	$harga_kry_kelrespoli=$_POST['harga']*1.25;

	//Formula RANAP BAYI KELAS III - VIP
	$harga_kry_kel=$_POST['harga']*1.25;

	//Formula RANAP KARYAWAN
	$harga_umum_rj=$_POST['harga']*1.25;

	//Formula KELUARGA INTI
	$harga_umum_ri=$_POST['harga']*1.25;

	//Formula RANAP IBU TAGIHAN KELAS III (KHUSUS)
	$harga_umum_ikutrekening=$_POST['harga']*1.25;

	//Formula RANAP IBU TAGIHAN KELAS III - VIP
	$harga_gratis_rj=$_POST['harga']*1.25;

	//Formula RANAP UMUM TAGIHAN KELAS II - I
	$harga_gratis_ri=$_POST['harga']*1.25;

	//Formula RANAP UMUM TAGIHAN KELAS III
	$harga_pen_bebas=$_POST['harga']*1.25;

	//Formula ASURANSI
	$harga_nempil=$_POST['harga']*1.25;

	//Formula RAJAL RESEP LUAR
	$harga_nempil_apt=$_POST['harga']*1.25;
	
} */ else {
	/*
	//Formula RAJAL OBAT LUAR DAN TABLET
	$harga_car_drs=$_POST['harga']*$d4->pm_car_drs;

	//Formula RAJAL INJEKSI DAN ALKES
	$harga_car_rsrj=$_POST['harga']*$d4->pm_car_rsrj;

	//Formula RAJAL TAGIHAN
	$harga_car_rsri=$_POST['harga']*$d4->pm_car_rsri;

	//Formula HV
	$harga_inhealth_drs=$_POST['harga']*$d4->pm_inhealth_drs;

	//Formula BON KARYAWAN
	$harga_inhealth_rs=$_POST['harga']*$d4->pm_inhealth_rs;

	//Formula RAJAL KARYAWAN
	$harga_jam_ri=$_POST['harga']*$d4->pm_jam_ri;

	//Formula ROS
	$harga_jam_rj=$_POST['harga']*$d4->pm_jam_rj;

	//Formula RANAP UMUM KELAS III
	$harga_kry_kelinti=$_POST['harga']*$d4->pm_kry_kelinti;

	//Formula RANAP UMUM KELAS II - VIP
	$harga_kry_kelbesar=$_POST['harga']*$d4->pm_kry_kelbesar;

	//Formula RANAP IBU KELAS III (KHUSUS)
	$harga_kry_kelgratisri=$_POST['harga']*$d4->pm_kry_kelgratisri;

	//Formula RANAP IBU KELAS III - VIP
	$harga_kry_kelgratisrj=$_POST['harga']*$d4->pm_kry_kelgratisrj;

	//Formula RANAP BAYI KELAS III (KHUSUS)
	$harga_kry_kelrespoli=$_POST['harga']*$d4->pm_kry_kelrespoli;

	//Formula RANAP BAYI KELAS III - VIP
	$harga_kry_kel=$_POST['harga']*$d4->pm_kry_kel;

	//Formula RANAP KARYAWAN
	$harga_umum_rj=$_POST['harga']*$d4->pm_umum_rj;

	//Formula KELUARGA INTI
	$harga_umum_ri=$_POST['harga']*$d4->pm_umum_ri;

	//Formula RANAP IBU TAGIHAN KELAS III (KHUSUS)
	$harga_umum_ikutrekening=$_POST['harga']*$d4->pm_umum_ikutrekening;

	//Formula RANAP IBU TAGIHAN KELAS III - VIP
	$harga_gratis_rj=$_POST['harga']*$d4->pm_gratis_rj;

	//Formula RANAP UMUM TAGIHAN KELAS II - I
	$harga_gratis_ri=$_POST['harga']*$d4->pm_gratis_ri;

	//Formula RANAP UMUM TAGIHAN KELAS III
	$harga_pen_bebas=$_POST['harga']*$d4->pm_pen_bebas;

	//Formula ASURANSI
	$harga_nempil=$_POST['harga']*$d4->pm_nempil;

	//Formula RAJAL RESEP LUAR
	$harga_nempil_apt=$_POST['harga']*$d4->pm_nempil_apt;
	*/
	//Formula RANAP
	$harga_ranap=$_POST['harga']*$d4->pm_ranap;

	//Formula OBAT KHUSUS I
	$harga_khusus_i=$_POST['harga']*$d4->pm_khusus_i;

	//Formula OBAT KHUSUS II
	$harga_khusus_ii=$_POST['harga']*$d4->pm_khusus_ii;

	//Formula KELUARGA KARYAWAN
	$harga_kel_kry=$_POST['harga']*$d4->pm_kel_kry;

	//Formula OBAT RAJAL
	$harga_obat_rajal=$_POST['harga']*$d4->pm_obat_rajal;

	//Formula OBAT TJP
	$harga_obat_tjp=$_POST['harga']*$d4->pm_obat_tjp;

	//Formula BPJS
	$harga_bpjs=$_POST['harga']*$d4->pm_bpjs;

	//Formula ASURANSI SWASTA
	$harga_aswas=$_POST['harga']*$d4->pm_aswas;

	//Formula ALKES BHP
	$harga_alkes_bhp=$_POST['harga']*$d4->pm_alkes_bhp;

	//Formula HV
	$harga_hv=$_POST['harga']*$d4->pm_hv;

	//Formula KLINIK
	$harga_klinik=$_POST['harga']*$d4->pm_klinik;

	//Formula OBAT NEMPIL PAJAL
	$harga_nempil_rajal=$_POST['harga']*$d4->pm_nempil_rajal;

	//Formula LANGGANAN
	$harga_langganan=$_POST['harga']*$d4->pm_langganan;
	
 $SQL2 = "insert into rs00016 (id,obat_id,tanggal_entry,harga,
harga_ranap,
harga_khusus_i,
harga_khusus_ii,
harga_kel_kry,
harga_obat_rajal,
harga_obat_tjp, 
harga_bpjs,
harga_aswas,
harga_alkes_bhp,
harga_nempil_rajal,
harga_hv,
harga_klinik,
harga_langganan,
harga_beli)". 
" values (nextval('rs00016_seq'),currval('rs00015_seq'),CURRENT_DATE,'".$_POST["harga"]."',
$harga_ranap,
$harga_khusus_i,
$harga_khusus_ii,
$harga_kel_kry,
$harga_obat_rajal,
$harga_obat_tjp, 
$harga_bpjs,
$harga_aswas,
$harga_alkes_bhp,
$harga_nempil_rajal,
$harga_hv,
$harga_harga_klinik,
$harga_langganan,'".$_POST["harga_beli"]."')";
// tokit: buat stok awal = 0 utk apotek rj dan ri
pg_query("insert into rs00016a  ".
	"values (currval('rs00015_seq'), 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
//die;
@$err = pg_query($con, $SQL2);

}

if($err == false) {
    header("Location: ../index2.php?p=$PID&err=".
        urlencode(pg_last_error($con))."&e=new");
    exit;
} else {
    header("Location: ../index2.php?p=$PID&mOBT=$OBT");
    exit;
}

?>
