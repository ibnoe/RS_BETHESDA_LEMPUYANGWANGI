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
pg_query($con, "update rs00015 SET satuan_id = '".$_POST[f_satuan_id]."' where id = ".$id."");
pg_query($con, "update rs00015 SET jenis_id = '".$_POST[f_jenis_id]."' where id = ".$id."");
pg_query($con, "update rs00015 SET tipe_id = '".$_POST[f_tipe_id]."' where id = ".$id."");
pg_query($con, "update rs00015 SET kategori_id = '".$_POST[f_kategori_id]."' where id = ".$id."");
pg_query($con, "update rs00015 SET antibiotik_id = '".$_POST[f_antibiotik_id]."' where id = ".$id."");
pg_query($con, "update rs00015 SET kerjasama_id = '".$_POST[f_kerjasama_id]."' where id = ".$id."");
pg_query($con, "update rs00015 SET prinsiple_id = '".$_POST[f_prinsiple_id]."' where id = ".$id."");

//Input harga obat sesuai settingan margin harga jual di apotik rs sarila husada 27 Februari 2013 by Me
$r4 = pg_query($con,
                "select * " .
                "from margin_apotik " .
                "where kategori_id = '$kat'");
        $d4 = pg_fetch_object($r4);
        pg_free_result($r4);


if($kat=='029'){ //KATEGORI VAKSIN
	$harga_vaksin=$_POST['harga']*1.20;
pg_query($con, "update rs00016 SET  tanggal_entry= CURRENT_DATE,harga_vaksin=".$harga_vaksin."  where obat_id = ".$id."");

} else if($kat=='040'){ //PAMPERS & PEMBALUT
	$harga_pamper=$_POST['harga']*1.15;
pg_query($con, "update rs00016 SET  tanggal_entry= CURRENT_DATE,harga_pamper=".$harga_pamper."  where obat_id = ".$id."");

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
	
pg_query($con, "update rs00016 SET  tanggal_entry= CURRENT_DATE,
				harga_ranap=".$harga_ranap.",
				harga_khusus_i=".$harga_khusus_i.",
				harga_khusus_ii=".$harga_khusus_ii.",
				harga_kel_kry=".$harga_kel_kry.", 
				harga_obat_rajal=".$harga_obat_rajal.",
				harga_obat_tjp=".$harga_obat_tjp.", 
				harga_bpjs=".$harga_bpjs.",
				harga_aswas=".$harga_aswas.", 
				harga_alkes_bhp=".$harga_alkes_bhp.", 
				harga_nempil_rajal=".$harga_nempil_rajal.", 
				harga_hv=".$harga_hv.",
				harga_langganan=".$harga_langganan." where obat_id = ".$id."");
				}


pg_query($con, "update rs00016 SET harga = ".$harga.", tanggal_entry= CURRENT_DATE,harga_car_drs=".$harga_car_drs.",harga_car_rsrj=".$harga_car_rsrj.",harga_car_rsri=".$harga_car_rsri.",harga_inhealth_drs=".$harga_inhealth_drs.",harga_inhealth_rs=".$harga_inhealth_rs.", harga_jam_ri=".$harga_jam_ri.",harga_jam_rj=".$harga_jam_rj.", harga_kry_kelinti=".$harga_kry_kelinti.",harga_kry_kelbesar=".$harga_kry_kelbesar.", harga_kry_kelgratisri=".$harga_kry_kelgratisri.", harga_kry_kelrespoli=".$harga_kry_kelrespoli.", harga_kry_kel=".$harga_kry_kel.",harga_kry_kelgratisrj=".$harga_kry_kelgratisrj.",harga_umum_ri=".$harga_umum_ri.",harga_umum_rj=".$harga_umum_rj.",harga_umum_ikutrekening=".$harga_umum_ikutrekening.",harga_gratis_rj=".$harga_gratis_rj.",harga_gratis_ri=".$harga_gratis_ri.", harga_pen_bebas=".$harga_pen_bebas.",harga_nempil=".$harga_nempil.", harga_nempil_apt=".$harga_nempil_apt.", harga_beli='".$_POST["harga_beli"]."'  where obat_id = ".$id."");

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
$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs00016a";
$qb->VarPrefix = "x_";
$qb->addPrimaryKey("obat_id", $id);
$SQL = $qb->build(); 
pg_query($SQL);
pg_query($con, "UPDATE rs00016a SET ".implode(', ', $valstr)." WHERE obat_id = ". $id);



header("Location: ../index2.php?p=$PID&mOBT=$OBT&search=".$_GET[search]."&sort=".$_GET[sort]."&order=".$_GET[order]."&tblstart=".$_GET[tblstart]);

exit;



?>

