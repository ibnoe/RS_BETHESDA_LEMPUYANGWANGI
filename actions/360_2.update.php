<?php 
session_start();
$PID = "360_2";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");


if($_POST['batch'] != ''){
	$batch = $_POST['batch'];
}else{
$batch = '-';
}


$expire = date('d-m-Y');
if($_POST['expire'] > 0){
	$expire = $_POST['expire'];
}

$qty = 0;
if($_POST['qty_terima'] > 0){
	$qty = $_POST['qty_terima'];
}

$hargaBeli = 0;
if($_POST['harga_beli_pesan'] > 0){
	$hargaBeli = $_POST['harga_beli_pesan']/$_POST['jumlah_terkecil'];
}
$cekPPN = (int) $_POST['cek_ppn'];
if($cekPPN >0){
	$hargaBeli = $_POST['harga_beli_pesan']/$_POST['jumlah_terkecil'];
}

$hargaJual = 0;
if($_POST['harga_jual'] > 0){
	$hargaJual = $_POST['harga_jual'];
}

$diskon1 = 0;
if($_POST['diskon1'] > 0){
	$diskon1 = $_POST['diskon1'];
}

$diskon2 = 0;
if($_POST['diskon2'] > 0){
	$diskon2 = $_POST['diskon2'];
}

$materai = 0;
if($_POST['materai'] > 0){
	$materai = $_POST['materai'];
}

$ppn = 0;
if($_POST['ppn'] > 0){
	$ppn = $_POST['ppn'];
}
if($_POST['bonus']==1){		
	$hargaBeli=0;
}


	
	$totbel = ($_POST['qty_terima']*$_POST['harga_beli_pesan']);
	$jum_ppn = $totbel*($ppn/100);
	$totalobatsebelum = getFromTable("select sum(total_jumlah) from c_po_item_terima where item_id = '".$_POST["item_id"]."'");
	$hargaBeliAsal = getFromTable("select harga_beli from rs00016 where obat_id='".$_POST["item_id"]."'");
	$totalbelisebelum= $totalobatsebelum*$hargaBeliAsal;
	$jumlahObat=$_POST['qty_terima']*$_POST['qty_terkecil'];
	
	
	$totalbelisekarang=($totbel+$jum_ppn)/$jumlahObat;
	
	$totalobat=$totalobatsebelum+$qty;
	$prajual=($totalbelisebelum+$totalbelisekarang)/$totalobat;
	
	
	
	//var_dump($totalbelisekarang);
	//$hargaJual = $prajual+(($ppn*$prajual)/100)+((20*$prajual)/100);
	//var_dump($_POST);
	/*
	echo "<br> 1. ".$totbel2 = (($_POST['qty_terima']*$_POST['harga_beli_pesan'])+((($_POST['qty_terima']*$_POST['harga_beli_pesan']) * $ppn)/100));
		echo "<br>2. ".$qty_akhir= getFromTable("select sum(qty) from buku_besar where item_id = '".$_POST['item_id']."' and trans_form='c_po_item_terima'");
		echo "<br>3. ".$average_akhir= getFromTable("select max(harga) from buku_besar where item_id = '".$_POST['item_id']."' and trans_form='c_po_item_terima'");
		echo "<br>5. ".$average= ($totbel2+($qty_akhir*$average_akhir))/ ($qty_akhir+$qty);
		*/
		$cek= getFromTable("select count(item_id) from buku_besar where item_id = '".$_POST['item_id']."' and trans_form='c_po_item_terima'");
		
		if ($cek>0){
		$totbel2 = (($_POST['qty_terima']*$_POST['harga_beli_pesan'])+((($_POST['qty_terima']*$_POST['harga_beli_pesan']) * $ppn)/100));
		$qty_akhir= getFromTable("select sum(qty) from buku_besar where item_id = '".$_POST['item_id']."' and trans_form in ('c_po_item_terima','saldo_awal')");
		$average_akhir= getFromTable("select max(harga) from buku_besar where item_id = '".$_POST['item_id']."' and trans_form in ('c_po_item_terima','saldo_awal')");
		$average= ($totbel2+($qty_akhir*$average_akhir))/ ($qty_akhir+$qty);
		}else{
		$totbel2 = (($_POST['qty_terima']*$_POST['harga_beli_pesan'])+((($_POST['qty_terima']*$_POST['harga_beli_pesan']) * $ppn)/100));
		$qty_akhir= getFromTable("select qty_ri from rs00016a where obat_id = '".$_POST['item_id']."'");
		$average_akhir= getFromTable("select harga_beli from rs00016 where obat_id = '".$_POST['item_id']."'");
		$average= ($totbel2+($qty_akhir*$average_akhir))/ ($qty_akhir+$qty);
		
		pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,ppn_in,ket,harga) 
									values (CURRENT_DATE,CURRENT_TIME,'".$_POST['item_id']."','OBT','020','".$qty_akhir."','-','saldo_awal',0,'-',$average_akhir)");
										
		}
		//var_dump($to); 
		//die;
	/*
	//$hargaBeli = ($_POST['harga_beli_pesan']/$_POST['jumlah_terkecil']);
	$hargaBeliAsal = getFromTable("select harga_beli from rs00016 where obat_id='".$_POST["item_id"]."'");
	if($hargaBeliAsal > $hargaBeli) {
		$hargaBeliAsli = $hargaBeliAsal;
	}else{
		$hargaBeliAsli = $hargaBeli;
	}
	$hargaJual = $hargaBeliAsli+(($ppn*$hargaBeliAsli)/100)+((20*$hargaBeliAsli)/100);
	*/

if($_POST['bonus']==0){
$SQL6="UPDATE rs00015 set batch ='".$batch."', locator='".$_POST["locator"]."' WHERE id::text= '".$_POST["item_id"]."'";
var_dump($SQL6);
die;
pg_query($con, $SQL6);		
$SQL7="UPDATE rs00016 set harga = ".(int) $totalbelisekarang.", harga_beli = ". (int) $_POST['harga_beli_pesan']." WHERE obat_id::text= '".$_POST["item_id"]."'";
pg_query($con, $SQL7); 

$kat= getFromTable("select kategori_id from rs00015 where id='".$_POST["item_id"]."'");

$r4 = pg_query($con,
                "select * " .
                "from margin_apotik " .
                "where kategori_id = '$kat'");
        $d4 = pg_fetch_object($r4);
        pg_free_result($r4);
		

if($kat=='029'){ //KATEGORI VAKSIN
	$harga_vaksin=$totalbelisekarang*1.20;
pg_query($con, "update rs00016 SET  tanggal_entry= CURRENT_DATE,harga_vaksin=".$harga_vaksin."  where obat_id = ".$_POST["item_id"]."");

} else if($kat=='040'){ //PAMPERS & PEMBALUT
	$harga_pamper=$totalbelisekarang*1.15;
pg_query($con, "update rs00016 SET  tanggal_entry= CURRENT_DATE,harga_pamper=".$harga_pamper."  where obat_id = ".$_POST["item_id"]."");

}/*else if($kat=='013' || $kat=='014' || $kat=='048'){ //Sirup & Salep/Tetes
	//Formula RAJAL OBAT LUAR DAN TABLET
	$harga_car_drs=$totalbelisekarang*1.25;

	//Formula RAJAL INJEKSI DAN ALKES
	$harga_car_rsrj=$totalbelisekarang*1.25;

	//Formula RAJAL TAGIHAN
	$harga_car_rsri=$totalbelisekarang*1.25;

	//Formula HV
	$harga_inhealth_drs=$totalbelisekarang*1.25;

	//Formula BON KARYAWAN
	$harga_inhealth_rs=$totalbelisekarang*1.25;

	//Formula RAJAL KARYAWAN
	$harga_jam_ri=$totalbelisekarang*1.25;

	//Formula ROS
	$harga_jam_rj=$totalbelisekarang*1.25;

	//Formula RANAP UMUM KELAS III
	$harga_kry_kelinti=$totalbelisekarang*1.25;

	//Formula RANAP UMUM KELAS II - VIP
	$harga_kry_kelbesar=$totalbelisekarang*1.25;

	//Formula RANAP IBU KELAS III (KHUSUS)
	$harga_kry_kelgratisri=$totalbelisekarang*1.25;

	//Formula RANAP IBU KELAS III - VIP
	$harga_kry_kelgratisrj=$totalbelisekarang*1.25;

	//Formula RANAP BAYI KELAS III (KHUSUS)
	$harga_kry_kelrespoli=$totalbelisekarang*1.25;

	//Formula RANAP BAYI KELAS III - VIP
	$harga_kry_kel=$totalbelisekarang*1.25;

	//Formula RANAP KARYAWAN
	$harga_umum_rj=$totalbelisekarang*1.25;

	//Formula KELUARGA INTI
	$harga_umum_ri=$totalbelisekarang*1.25;

	//Formula RANAP IBU TAGIHAN KELAS III (KHUSUS)
	$harga_umum_ikutrekening=$totalbelisekarang*1.25;

	//Formula RANAP IBU TAGIHAN KELAS III - VIP
	$harga_gratis_rj=$totalbelisekarang*1.25;

	//Formula RANAP UMUM TAGIHAN KELAS II - I
	$harga_gratis_ri=$totalbelisekarang*1.25;

	//Formula RANAP UMUM TAGIHAN KELAS III
	$harga_pen_bebas=$totalbelisekarang*1.25;

	//Formula ASURANSI
	$harga_nempil=$totalbelisekarang*1.25;

	//Formula RAJAL RESEP LUAR
	$harga_nempil_apt=$totalbelisekarang*1.25;
	
} */ else {
	/*
	//Formula RAJAL OBAT LUAR DAN TABLET
	$harga_car_drs=$totalbelisekarang*$d4->pm_car_drs;

	//Formula RAJAL INJEKSI DAN ALKES
	$harga_car_rsrj=$totalbelisekarang*$d4->pm_car_rsrj;

	//Formula RAJAL TAGIHAN
	$harga_car_rsri=$totalbelisekarang*$d4->pm_car_rsri;

	//Formula HV
	$harga_inhealth_drs=$totalbelisekarang*$d4->pm_inhealth_drs;

	//Formula BON KARYAWAN
	$harga_inhealth_rs=$totalbelisekarang*$d4->pm_inhealth_rs;

	//Formula RAJAL KARYAWAN
	$harga_jam_ri=$totalbelisekarang*$d4->pm_jam_ri;

	//Formula ROS
	$harga_jam_rj=$totalbelisekarang*$d4->pm_jam_rj;

	//Formula RANAP UMUM KELAS III
	$harga_kry_kelinti=$totalbelisekarang*$d4->pm_kry_kelinti;

	//Formula RANAP UMUM KELAS II - VIP
	$harga_kry_kelbesar=$totalbelisekarang*$d4->pm_kry_kelbesar;

	//Formula RANAP IBU KELAS III (KHUSUS)
	$harga_kry_kelgratisri=$totalbelisekarang*$d4->pm_kry_kelgratisri;

	//Formula RANAP IBU KELAS III - VIP
	$harga_kry_kelgratisrj=$totalbelisekarang*$d4->pm_kry_kelgratisrj;

	//Formula RANAP BAYI KELAS III (KHUSUS)
	$harga_kry_kelrespoli=$totalbelisekarang*$d4->pm_kry_kelrespoli;

	//Formula RANAP BAYI KELAS III - VIP
	$harga_kry_kel=$totalbelisekarang*$d4->pm_kry_kel;

	//Formula RANAP KARYAWAN
	$harga_umum_rj=$totalbelisekarang*$d4->pm_umum_rj;

	//Formula KELUARGA INTI
	$harga_umum_ri=$totalbelisekarang*$d4->pm_umum_ri;

	//Formula RANAP IBU TAGIHAN KELAS III (KHUSUS)
	$harga_umum_ikutrekening=$totalbelisekarang*$d4->pm_umum_ikutrekening;

	//Formula RANAP IBU TAGIHAN KELAS III - VIP
	$harga_gratis_rj=$totalbelisekarang*$d4->pm_gratis_rj;

	//Formula RANAP UMUM TAGIHAN KELAS II - I
	$harga_gratis_ri=$totalbelisekarang*$d4->pm_gratis_ri;

	//Formula RANAP UMUM TAGIHAN KELAS III
	$harga_pen_bebas=$totalbelisekarang*$d4->pm_pen_bebas;

	//Formula ASURANSI
	$harga_nempil=$totalbelisekarang*$d4->pm_nempil;

	//Formula RAJAL RESEP LUAR
	$harga_nempil_apt=$totalbelisekarang*$d4->pm_nempil_apt;
	*/
	//Formula RANAP
	$harga_ranap=$totalbelisekarang*$d4->pm_ranap;

	//Formula OBAT KHUSUS I
	$harga_khusus_i=$totalbelisekarang*$d4->pm_khusus_i;

	//Formula OBAT KHUSUS II
	$harga_khusus_ii=$totalbelisekarang*$d4->pm_khusus_ii;

	//Formula KELUARGA KARYAWAN
	$harga_kel_kry=$totalbelisekarang*$d4->pm_kel_kry;

	//Formula OBAT RAJAL
	$harga_obat_rajal=$totalbelisekarang*$d4->pm_obat_rajal;

	//Formula OBAT TJP
	$harga_obat_tjp=$totalbelisekarang*$d4->pm_obat_tjp;

	//Formula BPJS
	$harga_bpjs=$totalbelisekarang*$d4->pm_bpjs;

	//Formula ASURANSI SWASTA
	$harga_aswas=$totalbelisekarang*$d4->pm_aswas;

	//Formula ALKES BHP
	$harga_alkes_bhp=$totalbelisekarang*$d4->pm_alkes_bhp;

	//Formula HV
	$harga_hv=$totalbelisekarang*$d4->pm_hv;

	//Formula KLINIK
	$harga_klinik=$totalbelisekarang*$d4->pm_klinik;

	//Formula OBAT NEMPIL PAJAL
	$harga_nempil_rajal=$totalbelisekarang*$d4->pm_nempil_rajal;

	//Formula LANGGANAN
	$harga_langganan=$totalbelisekarang*$d4->pm_langganan;
	
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
				harga_klinik=".$harga_klinik.",
				harga_langganan=".$harga_langganan." where obat_id = ".$_POST["item_id"]."");
}

}
		
$jum_ppn = $hargaBeli*($ppn/100);
$stokGudangAwal = getFromTable("select qty_ri from rs00016a where obat_id = ".$_POST["item_id"]);
pg_query($con, "UPDATE c_po_item SET po_status = 2 WHERE item_id = '".$_POST["item_id"]."' and po_id = '".$_POST["po_id"]."' and bonus = '".$_POST["bonus"]."'");
$cek_po_status = getFromTable("select count(po_status) from c_po_item where po_status=0 and po_id='".$_POST["po_id"]."'");
if($cek_po_status==0){
pg_query($con, "UPDATE c_po SET po_status=2 WHERE po_id = '".$_POST["po_id"]."'");
}else{
pg_query($con, "UPDATE c_po SET po_status=1 WHERE po_id = '".$_POST["po_id"]."'");
}
$count = getFromTable("select count(po_id) from c_po_item_terima where item_id = '".$_POST["item_id"]."' and po_id = '".$_POST["po_id"]."'  and bonus = '".$_POST["bonus"]."' ");
if($count==0){
$q = pg_query($con," SELECT * FROM c_po_item WHERE item_id = '".$_POST["item_id"]."' and po_id = '".$_POST["po_id"]."' and bonus = '".$_POST["bonus"]."'");
$row = pg_fetch_array($q);

pg_query($con, "INSERT INTO c_po_item_terima(item_id,po_id,item_qty,po_status,satuan1,jumlah2,total_jumlah,kode_trans,bonus,ppn_in)
									values('".$row['item_id']."','".$row['po_id']."','".$row['item_qty']."','".$row['po_status']."','".$row['satuan1']."','".$row['jumlah2']."','".$row['total_jumlah']."','".$row['kode_trans']."','".$row['bonus']."',$jum_ppn)");

$SQL = "UPDATE c_po_item_terima SET batch = '".$batch."',expire = '".$expire."', qty_terima=".$qty."
		,harga_beli = ". $hargaBeli.", diskon1='".$diskon1."' ,diskon2 = '".$diskon2."',bonus = '".$_POST["bonus"]."', materai = '".$materai."', ppn = '".$ppn."', po_status = 2  ,total_jumlah=jumlah2*".$qty.", tanggal_terima=CURRENT_DATE
		, no_faktur='".$_POST["no_faktur"]."', jatuh_tempo='".$_POST["jatuh_tempo"]."', jam_terima='".$_POST["jam_terima"]."' WHERE item_id = '".$_POST["item_id"]."' and po_id = '".$_POST["po_id"]."' and bonus='".$_POST["bonus"]."'";
pg_query($con, $SQL);
		
		
		$IdSupp=getFromTable("select supp_id from c_po where po_id = '".$_POST["po_id"]."'");
		$Supp=getFromTable("select nama from rs00028 where id = '".$IdSupp."'");
		
		// ---- insert ke buku besar
		// 2013-12-05 "Menentukan Cost Average gudang"
		
		pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,ppn_in,ket,harga) 
									values (CURRENT_DATE,CURRENT_TIME,'".$row['item_id']."','OBT','020','".$row['total_jumlah']."','".$_POST["po_id"]."','c_po_item_terima',$jum_ppn,'$Supp / $batch / $expire',$average)");
										
$SQL5="UPDATE rs00016a set qty_ri = qty_ri+". ($qty*$_POST['qty_terkecil']) ." WHERE obat_id::text= '".$_POST["item_id"]."'";
pg_query($con, $SQL5);

}else{
$qty_lama = getFromTable("select total_jumlah from c_po_item_terima where item_id = '".$_POST["item_id"]."' and po_id = '".$_POST["po_id"]."' and bonus='".$_POST["bonus"]."'");


$SQL = "UPDATE c_po_item_terima SET batch = '".$batch."',expire = '".$expire."', qty_terima=".$qty.",total_jumlah=".($qty*$_POST['qty_terkecil'])."
		,harga_beli = ". $hargaBeli.", diskon1='".$diskon1."' ,diskon2 = '".$diskon2."', materai = '".$materai."', ppn = '".$ppn."', po_status = 2  , no_faktur = '".$_POST["no_faktur"]."', jatuh_tempo = '".$_POST["jatuh_tempo"]."', tanggal_terima=CURRENT_DATE,ppn_in=$jum_ppn 
		WHERE item_id = '".$_POST["item_id"]."' and po_id = '".$_POST["po_id"]."' and bonus = '".$_POST["bonus"]."'";
		pg_query($con, $SQL);	
$qty_new = ($qty*$_POST['qty_terkecil'])-$qty_lama;

$qty_jumlah = $qty*$_POST['qty_terkecil'];
$SQL5="UPDATE rs00016a set qty_ri = qty_ri+".$qty_new ." WHERE obat_id::text= '".$_POST["item_id"]."'";

pg_query($con, $SQL5);

		// ---- update ke buku besar
		pg_query($con,"UPDATE buku_besar SET qty=$qty_jumlah,ppn_in=$jum_ppn WHERE kode_transaksi='".$_POST["po_id"]."' and item_id = '".$_POST["item_id"]."' and status_bonus = '".$_POST["bonus"]."'");

}

//$hargaBeli = ($_POST['harga_beli_pesan']/$_POST['jumlah_terkecil']);
	

header("Location: ../index2.php?p=$PID&edit=view&f=".$_POST["po_id"]."&poid=".$_POST["po_id"]."");
exit;	   
?>