<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004
      // sfdn, 30-04-2004

$PID        = "807";
$OBT        = $_POST["mOBT"];
$id         = $_POST["id"];
$harga      = (int)$_POST['harga'];
$stokGudang = (int)$_POST['gudang'];
$stokRI     = (int)$_POST['qty_ri'];
$kat= $_POST["f_kategori_id"];

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs00015";
$qb->VarPrefix = "f_";
$qb->addPrimaryKey("id", "'".$id."'");
$SQL = $qb->build(); 

pg_query($con, "update rs00015 SET status = ".$_POST[f_status]." where id = ".$id."");

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

pg_query($con, "update rs00016 SET harga = ".$harga.", tanggal_entry= CURRENT_DATE,harga_car_drs=".$harga_car_drs.",harga_car_rsrj=".$harga_car_rsrj.",harga_car_rsri=".$harga_car_rsri.",harga_inhealth_drs=".$harga_inhealth_drs.",harga_inhealth_rs=".$harga_inhealth_rs.", harga_jam_ri=".$harga_jam_ri.",harga_jam_rj=".$harga_jam_rj.", harga_kry_kelinti=".$harga_kry_kelinti.",harga_kry_kelbesar=".$harga_kry_kelbesar.", harga_kry_kelgratisri=".$harga_kry_kelgratisri.", harga_kry_kelrespoli=".$harga_kry_kelrespoli.", harga_kry_kel=".$harga_kry_kel.",harga_kry_kelgratisrj=".$harga_kry_kelgratisrj.",harga_umum_ri=".$harga_umum_ri.",harga_umum_rj=".$harga_umum_rj.",harga_umum_ikutrekening=".$harga_umum_ikutrekening.",harga_gratis_rj=".$harga_gratis_rj.",harga_gratis_ri=".$harga_gratis_ri.", harga_pen_bebas=".$harga_pen_bebas.",harga_nempil=".$harga_nempil.", harga_nempil_apt=".$harga_nempil_apt."  where obat_id = ".$id."");

pg_query($con, "UPDATE rs00016a set gudang = ".$stokGudang." where obat_id = ".$id."");
pg_query($con, "UPDATE rs00016a set qty_ri = ".$stokRI." where obat_id = ".$id."");

$arrPost = $_POST;
unset($arrPost['id']);
unset($arrPost['f_kategori_stock_id']);
unset($arrPost['f_kategori_id']);
unset($arrPost['mOBT']);
unset($arrPost['f_obat']);
unset($arrPost['f_generik']);
unset($arrPost['f_satuan_id']);
unset($arrPost['harga']);
unset($arrPost['f_max_stok']);
unset($arrPost['f_min_stok']);
unset($arrPost['f_status']);
unset($arrPost['stok']);
unset($arrPost['f_min_stok']);
unset($arrPost['f_min_stok']);
unset($arrPost['gudang']);
unset($arrPost['qty_ri']);

foreach($arrPost as $key => $val){
    $valstr[] = substr($key,2)." = ".$val;
}
// update nama obat
pg_query($con, "UPDATE rs00015 SET obat = '".$_POST['f_obat']."' WHERE id = ". $id);
pg_query($con, "UPDATE rs00016a SET ".implode(', ', $valstr)." WHERE obat_id = ". $id);

header("Location: ../index2.php?p=$PID&mOBT=$OBT&search=".$_GET[search]."&sort=".$_GET[sort]."&order=".$_GET[order]."&tblstart=".$_GET[tblstart]);
exit;

?>
