<?php // Agung Sunandar 0:31 02/07/2012 untuk menyimpan deposit
session_start();
$PID = "888";

require_once("../lib/dbconn.php");

// buat kwitansi
		$tgl = date("d", time());
		$bln = date("m", time());
		$thn = date("y", time());
		$thn1 = date("Y", time());
		
		$cekpendapatan=getFromTable("select sum(jumlah) from rs00005 where tgl_entry='$thn1-$bln-01' and kasir in ('BYR','BYD','BYI') ");
		$cekpendapatan2=getFromTable("select sum(jumlah) from rs00005 where tgl_entry='$thn1-$bln-02' and kasir='$ksr' ");
		
		
		
		if($tgl==1 and $cekpendapatan==''){
		
			$cek=getFromTable("select count (status) from reset_kwitansi where bulan='$bln' and tahun='$thn1' ");
			
			if ($cek>0){
			}
			else{
			pg_query("insert into reset_kwitansi values('$bln','$thn1',1,'00000','00000','00000','00000')");
			}
		}

		$cekno=getFromTable("select (deposit::numeric + 1) from reset_kwitansi where bulan='$bln' and tahun='$thn1' ");
		$cekno1 = str_pad(((int) $cekno), 5, "0", STR_PAD_LEFT);
		$no_kwitansi="DEP - ".$cekno1."/".$bln."/".$thn1;
	
    $SQL = "insert into rs00044 " .
           "(no_reg, mr_no,tgl_deposit,wkt_deposit,nm_pasien,bangsal,cara_bayar,no_kartu,jumlah,pembayar,kasir) ".
           "values".
           "('".$_POST["no_reg"]."','".$_POST["mr_no"]."',CURRENT_DATE,CURRENT_TIME,'".$_POST["nama"]."','".$_POST["bangsal"]."','".$_POST["mCAB"]."',
		   '".$_POST["no_kartu"]."',".$_POST["jumlah"].",'".$_POST["pembayar"]."','".$_POST["kasir"]."')";
		   
    // Agung Sunandar 0:31 02/07/2012
	$SQL3=("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, is_karcis, layanan, jumlah,
  is_bayar, user_id, cab, bayar, no_kartu, waktu_bayar, no_kwitansi) ".
			" values(nextval('kasir_seq'),'".$_POST["no_reg"]."',CURRENT_DATE, ".
			"'BYI','N','N','DEPOSIT',".$_POST["jumlah"].",'Y','".$_SESSION["uid"]."','".$_POST[mCAB]."','".$_POST[pembayar]."','".$_POST[no_kartu]."',CURRENT_TIME,'$no_kwitansi')"); 
			
//========== hystory user
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'$PID2','Kasir -> Input Deposit Pasien','Menambah Deposit Pasien ".$_POST["nama"]." No.MR ".$_POST["mr_no"]." sejumlah ".$_POST["jumlah"]."','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
//echo $SQL2;
    pg_query($con, $SQL2);
    
//======================

$sql7=("update reset_kwitansi set deposit = $cekno where bulan='$bln' and tahun='$thn1' ");
pg_query($con, $sql7);

pg_query($con, $SQL3);
pg_query($con, $SQL);

if ($_POST["kasir"] == 'RAWAT INAP') {
    header("Location: ../index2.php?p=$PID&kas=ri&search=".$_POST["no_reg"]."");
    exit;
} else if ($_POST["kasir"] == 'RAWAT JALAN' || $_POST["kasir"] == 'IGD') {
	header("Location: ../index2.php?p=$PID&kas=rj&search=".$_POST["no_reg"]."");
    exit;
}

?>