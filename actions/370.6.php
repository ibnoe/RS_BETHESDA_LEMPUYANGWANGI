<?php // tokit, 7/15/2004 8:49:32 PM
ini_set('display_errors',1);
/*************************
          POSTING
*************************/
$PID = "370";
$SC = $_SERVER["SCRIPT_NAME"];
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/functions.php");
$T->show(0);
echo "<hr noshade size=1>";
$reg = $_GET["rg"];
$sub = 4;
// data terakhir (record terakhir) seorang pasian tercatat sbg. penghuni bangsal
$id_max = getFromTable("select max(id) from rs00010 ".
                        "where no_reg = '$reg'");
$id_bangsal = getFromTable("select bangsal_id from rs00010 ".
                        "where no_reg = '$reg' order by id desc");
$skrg = time();
$ts_check_in = date("Y-m-d H:i:s", $skrg);
$tgl = date("d", $skrg);
$bln = date("m", $skrg);
$thn = date("Y", $skrg);
$jam = date("H",$skrg);
/**
if ($jam >= 12) {
    $ts_calc_start = date("Y-m-d", $skrg).
        " 12:00:00";
} else {
    $ts_calc_start = date("Y-m-d", mktime(0,0,0,$bln,$tgl,$thn)).
        " 12:00:00";
}*/
//$ts_calc_start = date("Y-m-d H:i:s", $skrg);;
$x = pg_query($con,
        "select a.id, a.ts_check_in::date, e.bangsal, d.bangsal as ruangan, b.bangsal as bed, ".
        "    c.tdesc as klasifikasi_tarif, ".
        "    extract(day from current_timestamp - a.ts_calc_start) as qty, ".
        "    d.harga as harga_satuan, a.ts_calc_start,".
        "    extract(day from current_timestamp - a.ts_calc_start) * d.harga as harga, ".
        "	 extract(hour from current_timestamp - a.ts_calc_start) as jml_jam ".
        "from rs00010 as a ".
        "    join rs00012 as b on a.bangsal_id = b.id ".
        "    join rs00012 as d on substr(b.hierarchy,1,6) || '000000000' = d.hierarchy ".
        "    join rs00012 as e on substr(b.hierarchy,1,3) || '000000000000' = e.hierarchy ".
        "    join rs00001 as c on d.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
        "where to_number(a.no_reg,'9999999999') = $reg and ts_calc_stop is null");
$xxx = pg_fetch_object($x);
/* Jika jumlah jam lebih dari 3 atau jumlah hari lebih dari 0 */
if($xxx->jml_jam>3 || $xxx->qty > 0){
	/* Jika jumlah jam > 3 dan jumlah jam <= 7 harganya 50% dari tarif */
	if(($xxx->jml_jam>3)&&($xxx->jml_jam<=7))
	   $xxx->qty += 0.5;
	 /* Jika jumlah jam > 3 dan jumlah jam <= 7 harganya 100% dari tarif */
	else if ($xxx->jml_jam>7)
	   $xxx->qty += 1;
	   $xxx->harga = $xxx->harga_satuan*$xxx->qty;
/**
if(getFromTable("SELECT count(no_reg) FROM rs00010 WHERE no_reg ='".$reg."'")<=1){	
$xxx->harga += (($xxx->jml_jam<=12)&&($xxx->qty<1)) ? 0.5*$xxx->harga_satuan : $xxx->harga_satuan;
$xxx->qty += (($xxx->jml_jam<12)&&($xxx->qty<1)) ? 0.5 : 1;
}
*/
$SQL = "update rs00010 set ts_calc_stop=CURRENT_TIMESTAMP where id = '$id_max'";
$SQL1 = "insert into rs00010 (id, no_reg, bangsal_id, ts_check_in, ts_calc_start) ".
       "values (nextval('rs00010_seq'),'$reg','$id_bangsal',".
       "'$ts_check_in'::timestamp,'$ts_check_in'::timestamp)";
//echo $xxx->qty.'~'.$xxx->jml_jam.'~'.$xxx->harga;exit;
/**       
if($xxx->qty>0.5){
	pg_query("DELETE FROM rs00008 WHERE trans_type = 'POS' AND qty = 0.5 AND no_reg = '".$reg."'");
	}      
	*/
/* Jika pada tanggal tersebut sudah ada postingan, maka perbaharui tagihan dan qty, jika tidak ada buat baru*/	
if(getFromTable("SELECT count(no_reg) FROM rs00008 WHERE no_reg='".$reg."' AND trans_type='POS' AND tanggal_entry='".date("Y-m-d")."'")<1){ 
pg_query("select nextval('kasir_seq')");
$SQL2 = "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg, ".
                "qty,           harga,       tagihan".
            ") values (".
                "nextval('rs00008_seq'), 'POS', '$PID', nextval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '$reg',  " .
                $xxx->qty.",".$xxx->harga_satuan.",".$xxx->harga.")";

$SQL3 = "insert into rs00005 ".
        "VALUES(currval('kasir_seq'), '$reg', CURRENT_DATE, 'RIN', 'N', 'N', 99996, $xxx->harga, 'N') ";
}
else{
$SQL2 = "UPDATE rs00008 SET qty = ".$xxx->qty.", harga = ".$xxx->harga_satuan.", tagihan =".$xxx->harga." WHERE id = 
(SELECT id FROM rs00008 WHERE trans_type = 'POS' AND no_reg='".$reg."' AND tanggal_entry='".date("Y-m-d")."' ORDER BY id DESC LIMIT 1 OFFSET 0)";
$SQL3 = "UPDATE rs00005 SET jumlah = ".$xxx->harga." WHERE id = (SELECT id FROM rs00005 WHERE kasir='RIN' 
AND tgl_entry='".date("Y-m-d")."' AND reg = '".$reg."'  ORDER BY id DESC LIMIT 1 OFFSET 0)";	
	}
pg_query($con, $SQL2);
pg_query($con, $SQL);
pg_query($con, $SQL1);
pg_query($con, $SQL3);
}
//if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");
//exit;
?>
<script language=javascript>
<!--
window.location = "index2.php?p=<?echo $PID;?>&rg=<?echo $reg;?>&sub=4";
-->
</script>
