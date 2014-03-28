<?php
//ini_set('display_errors',1);

require_once("../lib/dbconn.php");
$term = $_REQUEST['term'];
if($_REQUEST['stat']==1){
$a = pg_query(
"select tdesc from rs00001
			where
			tt='SAT' 
			AND  tdesc= '$term' and tc <> '000'");
/*$a = pg_query("SELECT DISTINCT rs00015.id, rs00015.obat, rs00016.harga,rs00016.harga_car_drs,
rs00016.harga_car_rsrj  AS harga_karyawan_rj,rs00016.harga_car_rsri AS harga_karyawan_ri,rs00016.harga_inhealth_drs AS harga_jpk_rj,rs00016.harga_inhealth_rs AS harga_jpk_ri,
rs00016.harga_jam_ri,rs00016.harga_jam_rj,rs00016.harga_kry_kelinti,rs00016.harga_kry_kelbesar,
rs00016.harga_kry_kelgratisri,rs00016.harga_kry_kelrespoli,rs00016.harga_kry_kel,
rs00016.harga_kry_kelgratisrj,rs00016.harga_umum_ri,rs00016.harga_umum_rj,
rs00016.harga_umum_ikutrekening,rs00016.harga_gratis_rj,rs00016.harga_gratis_ri,
rs00016.harga_pen_bebas,rs00016.harga_nempil,rs00016.harga_nempil_apt,margin_apotik.tuslah_car_drs,
margin_apotik.tuslah_car_rsrj,margin_apotik.tuslah_car_rsri,margin_apotik.tuslah_inhealth_drs,
margin_apotik.tuslah_inhealth_rs,margin_apotik.tuslah_jam_ri,margin_apotik.tuslah_jam_rj,
margin_apotik.tuslah_kry_kelinti,margin_apotik.tuslah_kry_kelbesar,margin_apotik.tuslah_kry_kelgratisri,
margin_apotik.tuslah_kry_kelrespoli,margin_apotik.tuslah_kry_kel,margin_apotik.tuslah_kry_kelgratisrj,
margin_apotik.tuslah_umum_ri,margin_apotik.tuslah_umum_rj,margin_apotik.tuslah_umum_ikutrekening,
margin_apotik.tuslah_gratis_rj,margin_apotik.tuslah_gratis_ri,margin_apotik.tuslah_pen_bebas,
margin_apotik.tuslah_nempil,margin_apotik.tuslah_nempil_apt,
rs00001.comment AS jasa, rs00016a.qty_ri AS stok
    FROM rs00015 
    INNER JOIN rs00001 ON rs00015.kategori_id = rs00001.tc 
    INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id
    INNER JOIN rs00016a ON rs00015.id = rs00016a.obat_id
    INNER JOIN margin_apotik on rs00015.kategori_id = margin_apotik.kategori_id 
    WHERE rs00001.tt = 'GOB' and rs00015.kategori_id = margin_apotik.kategori_id and status='1' AND rs00015.obat = '$term'"); */
$r = pg_fetch_array($a);
/**
 *  var obatId = ui.item.id;
                var obatNama = ui.item.value;
                var obatSatuan = ui.item.satuan;
                var obatJasa = ui.item.jasa;
                var obatHarga = ui.item.harga;
                var obatStok = ui.item.stok;
 */
$return_arr[] = array('satuan'=>$r['tdesc']);
}
else if($_REQUEST['stat']==0){
$a = pg_query(
"select tdesc from rs00001
			where
			tt='SAT' 
			AND  tdesc ILIKE '%".$term."%' and tc <> '000'");
//$a = pg_query("SELECT obat,id FROM rs00015 WHERE obat ILIKE '%".$term."%' LIMIT 10 OFFSET 0");

while($r = pg_fetch_array($a)){
$return_arr[] = $r['tdesc'];
}
}
echo json_encode($return_arr);
