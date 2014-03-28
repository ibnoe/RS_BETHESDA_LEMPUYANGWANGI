<?php 
//echo '<pre>';
//var_dump($_POST); die;
$PID = "post_pend";
//$SC = $_SERVER["SCRIPT_NAME"];
session_start();
//var_dump($_POST);
//die;

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");
require_once("../lib/functions.php");


//ECHO "Location: ..".$_POST["SC"]."?p=$PID&search=".$_POST["rg"];
//exit;			

if ($_POST["act"]=="new"){ 
	
	$x=$_POST["i"];		
	for ($x=1; $x<=25; $x++) {
		if ($_POST["kode_".$x] != '' && $_POST["jumlah_".$x] != '0'){
				
				if ($_POST["akun_type_".$x]=="Debet"){
						$SQL = "insert into jurnal_umum (id,tanggal_akun,  no_akun, keterangan, debet, kredit, user_id, nm_kasir, ket,waktu_entry,jns_akun) values (
								nextval('jurnal_umum_seq'),CURRENT_DATE, '".$_POST["kode_".$x]."', 'Pasien ".$_POST["rg"]."','".$_POST["jumlah_".$x]."',0,'".$_SESSION[uid]."','".$_SESSION[nama_usr]."','".$_POST["akun_type_".$x]."',CURRENT_TIME,'SUB')";
				//var_dump($SQL);
				//die;
				$tot_debet=$tot_debet+$_POST["jumlah_".$x];				
				}elseif ($_POST["akun_type_".$x]=="Kredit"){
						$SQL = "insert into jurnal_umum (id,tanggal_akun,  no_akun, keterangan, debet, kredit, user_id, nm_kasir, ket,waktu_entry,jns_akun) values (
								nextval('jurnal_umum_seq'),CURRENT_DATE, '".$_POST["kode_".$x]."', 'Pasien ".$_POST["rg"]."',0,'".$_POST["jumlah_".$x]."','".$_SESSION[uid]."','".$_SESSION[nama_usr]."','".$_POST["akun_type_".$x]."',CURRENT_TIME,'SUB')";
				}
				$tot_kredit=$tot_kredit+$_POST["jumlah_".$x];
				@$err = pg_query($con, $SQL);
		}//else{
			//echo "WE ARE BREAK TO TAKE A CUP OF COFFE!";
				
				//die;
			//break;
		//}
		}
}
$kredit=$tot_kredit-$tot_debet;
				$coa=coa_id();
				//$coa="COA/2014/00001";
				//echo "update rs00008 set is_coa='1' and tgl_coa=CURRENT_DATE where no_reg=".$_POST["rg"];
				$insertJurMuM = pg_query($con, "insert into jurnal_umum_m (id,tanggal,no_faktur,keterangan,tot_debet,tot_kredit,jns_akun) 
										 values (nextval('jurnal_umum_m_seq'),CURRENT_DATE,'".$coa."','Pembayaran pasien ".$_POST["rg"]."',$tot_debet,$kredit,'SUB')");
	
				$UpdateTransaksi = pg_query($con, "update rs00008 set is_coa='1', tgl_coa=CURRENT_DATE where no_reg='".$_POST["rg"]."'");
				header("Location: ../index2.php?p=$PID&search=".$_POST["rg"]);
				exit;
/*
if($err == false) {
    //header("Location: ../index2.php?p=$PID&tt=".$_POST["tt"].
    //    "&tc=".$_POST["tc"]."&tdesc=".$_POST["tdesc"]."&err=".
    //    urlencode(pg_last_error($con))."&e=new");
    //exit;
	echo "SALAH KOPLOK!!";
	die;
} else {
    header("Location: ../index2.php?p=$PID&search=".$_POST["rg"];
    exit;
}


?>
