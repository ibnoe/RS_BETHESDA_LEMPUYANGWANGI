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
//Formula car dr. Sudjiyati
$harga_car_drs=($_POST['harga']+$d4->tuslah_car_drs)+($_POST['harga']*$d4->pm_car_drs);

//Formula car rs rawat jalan
$harga_car_rsrj=($_POST['harga']+$d4->tuslah_car_rsrj)+($_POST['harga']*$d4->pm_car_rsrj);

//Formula car rs rawat inap
$harga_car_rsri=($_POST['harga']+$d4->tuslah_car_rsri)+($_POST['harga']*$d4->pm_car_rsri);

//Formula inhealth dr. Sudjiyati
$harga_inhealth_drs=($_POST['harga']+$d4->tuslah_inhealth_drs)+($_POST['harga']*$d4->pm_inhealth_drs);

//Formula inhealth rs
$harga_inhealth_rs=($_POST['harga']+$d4->tuslah_inhealth_rs)+($_POST['harga']*$d4->pm_inhealth_rs);

//Formula jamkesmas rawat inap
$harga_jam_ri=($_POST['harga']+$d4->tuslah_jam_ri)+($_POST['harga']*$d4->pm_jam_ri);

//Formula jamkesmas rawat jalan
$harga_jam_rj=($_POST['harga']+$d4->tuslah_jam_rj)+($_POST['harga']*$d4->pm_jam_rj);

//Formula karyawan&keluarga inti rawat inap
$harga_kry_kelinti=($_POST['harga']+$d4->tuslah_kry_kelinti)+($_POST['harga']*$d4->pm_kry_kelinti);

//Formula karyawan&keluarga besar
$harga_kry_kelbesar=($_POST['harga']+$d4->tuslah_kry_kelbesar)+($_POST['harga']*$d4->pm_kry_kelbesar);

//Formula karyawan&keluarga gratis rawat inap
$harga_kry_kelgratisri=($_POST['harga']+$d4->tuslah_kry_kelgratisri)+($_POST['harga']*$d4->pm_kry_kelgratisri);

//Formula karyawan&keluarga gratis rawat jalan
$harga_kry_kelgratisrj=($_POST['harga']+$d4->tuslah_kry_kelgratisrj)+($_POST['harga']*$d4->pm_kry_kelgratisrj);

//Formula karyawan&keluarga resep poli
$harga_kry_kelrespoli=($_POST['harga']+$d4->tuslah_kry_kelrespoli)+($_POST['harga']*$d4->pm_kry_kelrespoli);

//Formula karyawan&keluarga
$harga_kry_kel=($_POST['harga']+$d4->tuslah_kry_kel)+($_POST['harga']*$d4->pm_kry_kel);

//Formula umum rawat jalan
$harga_umum_rj=($_POST['harga']+$d4->tuslah_umum_rj)+($_POST['harga']*$d4->pm_umum_rj);

//Formula umum rawat inap
$harga_umum_ri=($_POST['harga']+$d4->tuslah_umum_ri)+($_POST['harga']*$d4->pm_umum_ri);

//Formula umum ikut rekening
$harga_umum_ikutrekening=($_POST['harga']+$d4->tuslah_umum_rikutrekening)+($_POST['harga']*$d4->pm_umum_ikutrekening);

//Formula gratis rawat jalan
$harga_gratis_rj=($_POST['harga']+$d4->tuslah_gratis_rj)+($_POST['harga']*$d4->pm_gratis_rj);

//Formula gratis rawat inap
$harga_gratis_ri=($_POST['harga']+$d4->tuslah_gratis_ri)+($_POST['harga']*$d4->pm_gratis_ri);

//Formula penjualan bebas
$harga_pen_bebas=($_POST['harga']+$d4->tuslah_pen_bebas)+($_POST['harga']*$d4->pm_pen_bebas);

//Formula nempil
$harga_nempil=($_POST['harga']+$d4->tuslah_nempil)+($_POST['harga']*$d4->pm_nempil);

//Formula nempil apotik kurnia
$harga_nempil_apt=($_POST['harga']+$d4->tuslah_nempil_apt)+($_POST['harga']*$d4->pm_nempil_apt);

/*$SQL2 = "insert into rs00016 (id,obat_id,tanggal_entry,harga,harga_car_drs,harga_car_rsrj, harga_car_rsri,harga_inhealth_drs,harga_inhealth_rs, harga_jam_ri,harga_jam_rj, harga_kry_kelinti, harga_kry_kelbesar,harga_kry_kelgratisri, harga_kry_kelrespoli, harga_kry_kel,harga_kry_gratisrj, harga_umum_rj,harga_umum_ri,harga_umum_ikutrekening,harga_gratis_rj,harga_gratis_ri, harga_pen_bebas, harga_nempil, harga_nempil_apt)". 
"values (nextval('rs00016_seq'),currval('rs00015_seq'),CURRENT_DATE,'".$_POST["harga"]."',$harga_car_drs, $harga_car_rsrj,$harga_car_rsri,$harga_inhealth_drs,$harga_inhealth_rs,$harga_jam_ri,$harga_jam_rj, $harga_kry_kelinti,$harga_kry_kelbesar,$harga_kry_kelgratisri, $harga_kry_kelrespoli,$harga_kry_kel,$harga_kry_gratisrj,$harga_umum_rj,$harga_umum_ikutrekening,$harga_gratis_rj,$harga_gratis_ri, $harga_pen_bebas,$harga_nempil,$harga_nempil_apt)";*/

$SQL2 = "insert into rs00016 (id,obat_id,tanggal_entry,harga,harga_car_drs,harga_car_rsrj,harga_car_rsri,harga_inhealth_drs,harga_inhealth_rs, harga_jam_ri,harga_jam_rj, harga_kry_kelinti,harga_kry_kelbesar,harga_kry_kelgratisri, harga_kry_kelrespoli, harga_kry_kel,harga_kry_kelgratisrj,harga_umum_ri,harga_umum_rj,harga_umum_ikutrekening,harga_gratis_rj,harga_gratis_ri, harga_pen_bebas,harga_nempil, harga_nempil_apt)". 
"values (nextval('rs00016_seq'),currval('rs00015_seq'),CURRENT_DATE,'".$_POST["harga"]."',$harga_car_drs,$harga_car_rsrj,$harga_car_rsri,$harga_inhealth_drs,$harga_inhealth_rs,$harga_jam_ri,$harga_jam_rj, $harga_kry_kelinti,$harga_kry_kelbesar,$harga_kry_kelgratisri, $harga_kry_kelrespoli,$harga_kry_kel,$harga_kry_kelgratisrj,$harga_umum_ri,$harga_umum_rj,$harga_umum_ikutrekening,$harga_gratis_rj,$harga_gratis_ri,$harga_pen_bebas,$harga_nempil,$harga_nempil_apt)";

// tokit: buat stok awal = 0 utk apotek rj dan ri
pg_query("insert into rs00016a  ".
	"values (currval('rs00015_seq'), 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");

@$err = pg_query($con, $SQL2);

if($err == false) {
    header("Location: ../index2.php?p=$PID&err=".
        urlencode(pg_last_error($con))."&e=new");
    exit;
} else {
    header("Location: ../index2.php?p=$PID&mOBT=$OBT");
    exit;
}

?>
