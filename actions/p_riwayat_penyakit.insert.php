<?php // Nugraha, Thu Apr 22 11:58:22 WIT 2004
      // sfdn, 23-04-2004: tambah harga obat
      // sfdn, 09-05-2004
	  // sfdn, 31-05-2004
//echo $_POST["list"];exit;
session_start();
$PID = "p_riwayat_penyakit";
require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$noreg= $_POST["f_no_reg"];
$_GET["mr"] = $_POST["mr"];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];
$ri = isset($_GET["ri"])? $_GET["ri"] : $_POST["ri"];
// Agung Sunandar 2:15 02/08/2012 CAri Nama Bangsal

$bangsal=getFromTable("select bangsal from rsv_akomodasi_inap where no_reg='$rg' group by bangsal");

//echo "rm={$_GET["mr"]}";exit;
$tokit = pg_query("select nextval('rs00008_seq_group')");
pg_query("select nextval('kasir_seq')");
$tr = new PgTrans;
$tr->PgConn = $con;
// LAYANAN TINDAKAN MEDIS DAN PEMBAGIAN JASA MEDIS
if (is_array($_SESSION["layanan"])) {
	   if ($_POST["sub"] == "byr") {
		$v1 = ",is_bayar,no_kwitansi";
		$v2 = ",'Y',currval('rs88888_seq')";
   } else {
		$v1 = "";
		$v2 = "";
   }

   foreach ($_SESSION["layanan"] as $v) {
        $total += $v["total"];
   }
   //$kodepoli = getFromTable("SELECT POLI FROM RS00006 WHERE ID = '".$_POST["rg"]."' ");
	//$kodepoli = $_POST["ri"];
   if ($_POST[rawatan] == "IGD") {
      $loket = "IGD";
   } elseif ($_POST[rawatan] == "Rawat Inap") {
      $loket = "RIN";
      
   } else {
      $loket = "RJL";
   }
   
   // margin pembayaran berdasarkan tipe pasien
    $tipe_pasien2 = getFromTable("SELECT tipe FROM rs00006 WHERE id = '".$_POST["rg"]."'");
    
    // group layanan "ADMINISTRASI" tidak dikenakan margin
    /* if ($v["id"] == '01636' || $v["id"] == '01637') {
        $margin_pembayaran2 = 0;
        $total_tagihan2 = $total + $margin_pembayaran;
    } else {
        if ($tipe_pasien2 == '054') {
            $margin_pembayaran2 = ((15*$v["total"])/100);
            $total_tagihan2 = $total + $margin_pembayaran2;
        } else if ($tipe_pasien2 == '016') {
            $margin_pembayaran2 = ((5*$v["total"])/100);
            $total_tagihan2 = $total + $margin_pembayaran2;
        } else {
            $margin_pembayaran2 = 0;
            $total_tagihan2 = $total + $margin_pembayaran2;
        }
    }
    */ 
    
            $margin_pembayaran2 = 0;
            $total_tagihan2 = $total + $margin_pembayaran2;
       
   // insert to rs00005
   pg_query("INSERT INTO rs00005 (id,reg,tgl_entry,kasir,is_obat,is_karcis,layanan,jumlah,is_bayar,user_id) 
   		VALUES( currval('kasir_seq'), '".$_POST["rg"]."', ".
        "CURRENT_DATE, '$loket', 'N', 'N','".$_POST["ri"]."', $total_tagihan2, 'N','".$_SESSION["uid"]."')") or die("Error");

    // insert to rs00008
    foreach ($_SESSION["layanan"] as $v) {
        if ($v["nip"]) {
           $dokter = $v["nip"];
        } else {
           $dokter = 0;
        }
		
		// margin pembayaran berdasarkan tipe pasien
        //karyawan rs           = 015
        //umum / pribadi        = 001
        //car dr. sudjiyati     = 038
        //jamkesmas             = 064
        //inhealt rs            = 016
        //car rs                = 054
        //inhealt dr. sudjiyati = 065
        //tanggungan rs         = 066
        $tipe_pasien = getFromTable("SELECT tipe FROM rs00006 WHERE id = '".$_POST["rg"]."'");        
        // group layanan "ADMINISTRASI" tidak dikenakan margin
        // administrasi pasien baru = 01636
        // administrasi pasien lama = 01637
        /** if ($v["id"] == '01636' || $v["id"] == '01637') {
            $margin_pembayaran = 0;
            $total_tagihan = $v["total"] + $margin_pembayaran;
        } else {
            if ($tipe_pasien == '054') {
                $margin_pembayaran = ((15*$v["total"])/100);
                $total_tagihan = $v["total"] + $margin_pembayaran;
            } else if ($tipe_pasien == '016') {
                $margin_pembayaran = ((5*$v["total"])/100);
                $total_tagihan = $v["total"] + $margin_pembayaran;
            } else {
                $margin_pembayaran = 0;
                $total_tagihan = $v["total"] + $margin_pembayaran;
            }
        }
        */ 

				$margin_pembayaran = 0;
                $total_tagihan = $v["total"] + $margin_pembayaran;
        $tr->addSQL(
            	"insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga, persen, diskon,       tagihan,    pembayaran, no_kwitansi,user_id,margin_pembayaran ".
            	") values (".
                "nextval('rs00008_seq'), 'LTM', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '".$v["id"]."', '".$v["harga_cito"]."', " .
                $v["jumlah"].",".$v["harga"].",".$v["diskon_per"].",".$v["diskon"].",".$total_tagihan.", 0, $dokter,'".$_SESSION["uid"]."', $margin_pembayaran)"
        );      	
        
        $r3 = pg_query($con,"select bangsal_id from rs00010 where no_reg = '".$_POST["rg"]."'");
        $n3 = pg_num_rows($r3);
    	if($n3 > 0) $d3 = pg_fetch_object($r3);
    	pg_free_result($r3);
    	
         $tr->addSQL("update rs00008 set bangsal_id=$d3->bangsal_id where no_reg = '".$_POST["rg"]."' and trans_form = '$PID'");
    }
	
    }
  
//layanan paket
// Agung Sunandar 18:31 26/06/2012 menambahkan simpan untuk paket layanan

if (is_array($_SESSION["layanan2"])) {
	$add = "&sub2=paket";
	$stok = isset($_GET["stok"])? $_GET["stok"] : $_POST["stok"];
	if ($_POST["sub"] == "byr") {
		$v1 = ",is_bayar,no_kwitansi";
		$v2 = ",'Y',currval('rs88888_seq')";
   } else {
		$v1 = "";
		$v2 = "";
   }

   foreach ($_SESSION["layanan2"] as $v) {
        $total += $v["total"];
   }
   /*
   -- edited 150210
   -- menghilangkan fungsi lpad()
   */

      $loket = "RJL";   
	foreach ($_SESSION["layanan2"] as $v) {
	$r1 = pg_query($con,"select item_id,qty from rs99997 where preset_id=".$v["id"]." and trans_type='OBI' ");
	}	
	$rows = pg_num_rows($r1);	
	$stok = $v["stok"];	
    for ($n = 1; $n < 5; $n++) $prevLevel[$n] = "";
    while ($d1 = pg_fetch_object($r1)) {
	pg_query("update rs00016a set qty_ri = qty_ri - $d1->qty where obat_id=$d1->item_id ");
	}
	
   // insert to rs00005
   pg_query("INSERT INTO rs00005 (id ,  reg,  tgl_entry ,  kasir ,  is_obat ,  is_karcis,  layanan,  jumlah ,  is_bayar ,  
  user_id ) VALUES( currval('kasir_seq'), '".$_POST["rg"]."', ".
        "CURRENT_DATE, '$loket', 'N', 'N', '888', $total, 'N','".$_SESSION["uid"]."')") or die("Error");

    // insert to rs00008
    foreach ($_SESSION["layanan2"] as $v) {
        if ($v["nip"]) {
           $dokter = $v["nip"];
        } else {
           $dokter = 0;
        }	
		
        $tr->addSQL(
            	"insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran, no_kwitansi,user_id,id_transaksi ".
            	") values (".
                "nextval('rs00008_seq'), 'LTM', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '".$v["id"]."', 'P', " .
                $v["jumlah"].",".$v["harga"].",".$v["total"].", 0, $dokter,'".$_SESSION["uid"]."', '".$v["stok"]."')"
        );        
        //$tr->addSQL("update c_visit set id_dokter=$dokter where no_reg = lpad('".$_POST["rg"]."',10,'0')");
    }
//========== Agung Sunandar 22:28 26/06/2012 hystory user
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Bangsal $bangsal','Pelayanan -> Bangsal $bangsal','Menambah Pelayanan Paket No.MR $mr No.REG $rg Total ".$v["total"]."','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
pg_query($con, $SQL2);
//======================
    } 
// Insert BHP
// Agung Sunandar 18:31 26/06/2012 menambahkan simpan untuk bhp

if (is_array($_SESSION["obat2"])) {
	$add = "&sub2=bhp";
	$stok = isset($_GET["stok"])? $_GET["stok"] : $_POST["stok"];
	if ($_POST["sub"] == "byr") {
		$v1 = ",is_bayar,no_kwitansi";
		$v2 = ",'Y',currval('rs88888_seq')";
   } else {
		$v1 = "";
		$v2 = "";
   }

   foreach ($_SESSION["obat2"] as $v) {
        $total += $v["total"];
   }
   /*
   -- edited 150210
   -- menghilangkan fungsi lpad()
   */
      $loket = "RJL";   
	/*foreach ($_SESSION["obat2"] as $v) {
	$r1 = pg_query($con,"select item_id,qty from rs99997 where preset_id=".$v["id"]." and trans_type='BHP' ");
	}	
	$rows = pg_num_rows($r1);*/	
	$stok = $v["stok"];
	$qty = $v["jumlah"];	
   // insert to rs00005 kode untuk BHP (333), kode paket (888)
   pg_query("INSERT INTO rs00005 (id ,  reg,  tgl_entry ,  kasir ,  is_obat ,  is_karcis,  layanan,  jumlah ,  is_bayar ,  
  user_id ) VALUES( currval('kasir_seq'), '".$_POST["rg"]."', ".
        "CURRENT_DATE, '$loket', 'N', 'N', '333', $total, 'N','".$_SESSION["uid"]."')") or die("Error");
    // insert to rs00008
    foreach ($_SESSION["obat2"] as $v) {
        if ($v["nip"]) {
           $dokter = $v["nip"];
        } else {
           $dokter = 0;
        }
		
        $penjamin = 0;
	if($v['penjamin'] == '1'){
            $penjamin = $v["total"];
        }	
		
        $tr->addSQL(
            	"insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan, dibayar_penjamin,    pembayaran, no_kwitansi,user_id,id_transaksi ".
            	") values (".
                "nextval('rs00008_seq'), 'BHP', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '".$v["id"]."', ' ', " .
                $v["jumlah"].",".$v["harga"].",".$v["total"].", ".$penjamin.", 0, $dokter,'".$_SESSION["uid"]."', '".$v["stok"]."')"
        );
        
        $tr->addSQL("update rs00016a set qty_ri = qty_ri - $v[jumlah] where obat_id= $v[id] ");
    }

//========== Agung Sunandar 22:28 26/06/2012 hystory user
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Bangsal $bangsal','Pelayanan -> Bangsal $bangsal','Menambah BHP No.MR $mr No.REG $rg Total ".$v["total"]."','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
pg_query($con, $SQL2);
//======================
    }  
      
  
// ICD
if (is_array($_SESSION["icd"])) {
    foreach ($_SESSION["icd"] as $v) {
        $tr->addSQL(
            	"insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran ".
            	") values (".
                "nextval('rs00008_seq'), 'ICD', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '".$v["id"]."', '', " .
                "0,0,0,0)"
        );
        $r4 = pg_query($con,"select bangsal_id from rs00010 where no_reg = '".$_POST["rg"]."'  ");
        $n4 = pg_num_rows($r4);
    	if($n4 > 0) $d4 = pg_fetch_object($r4);
    	pg_free_result($r4);
        $tr->addSQL("update rs00008 set bangsal_id=$d4->bangsal_id where no_reg = '".$_POST["rg"]."' and trans_form = '$PID'");
    }
}

// ICD 9
if (is_array($_SESSION["icd9"])) {
    foreach ($_SESSION["icd9"] as $x) {
        $tr->addSQL(
            	"insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran ".
            	") values (".
                "nextval('rs00008_seq'), 'CD9', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."', '".$x["id"]."', '', " .
                "0,0,0,0)"
        );
        $r4 = pg_query($con,"select bangsal_id from rs00010 where no_reg = '".$_POST["rg"]."'  ");
        $n4 = pg_num_rows($r4);
    	if($n4 > 0) $d4 = pg_fetch_object($r4);
    	pg_free_result($r4);
        $tr->addSQL("update rs00008 set bangsal_id=$d4->bangsal_id where no_reg = '".$_POST["rg"]."' and trans_form = '$PID'");
    }
}

//----tambahan for update.... hery------------------------
	if ($_POST['act'] == "edit") { 
//		echo '<pre>';
//		var_dump($_POST);
//		echo '</pre>';
		//if ($_POST['row'] > 0)	{
			
			$SQL2 = "select * from c_visit_ri where no_reg = '".$_POST["rg1"]."' and id_ri='".$_POST["f_id_ri"]."' and tanggal_reg = '".$_POST["f_tanggal_reg"]."' ";
			$r2 = pg_query($con,$SQL2);
			//echo $SQL2;		
			if ($d2 = pg_fetch_object($r2)){		
				$qb = New UpdateQuery();
				$qb->HttpAction = "POST";
				$qb->TableName = "c_visit_ri";
				$qb->VarPrefix = "f_";
				$qb->addFieldValue("id_dokter", "'" . $_POST["f_vis_1"] . "'");
				$qb->addPrimaryKey("no_reg", "'".$_POST["rg1"]."'");
				$qb->addPrimaryKey("id_ri", "'" . $_POST["f_id_ri"] . "'");
				$qb->addPrimaryKey("tanggal_reg", "'" . $_POST["f_tanggal_reg"] . "'");
				$SQL = $qb->build();
				
				pg_query($con, $SQL);
				unset($_SESSION["SELECT_EMP"]);
                unset($_SESSION["SELECT_EMP2"]);
				unset($_SESSION["SELECT_EMP3"]);
				header("Location: ../index2.php?p=$PID&list={$_POST["list"]}&rg1=".$_POST[rg1]."&rg=$noreg&ri=".$_POST["f_id_ri"]."&mr=".$_POST["mr"]);
				exit;
			}
	}
//--------------------------------------------------------	
	if ($_POST['act'] == "new") { 	
		
			$qb = New InsertQuery();
			$qb->TableName = "c_visit_ri";
			$qb->HttpAction = "POST";
			$qb->VarPrefix = "f_";
			$qb->addFieldValue("id_dokter", "'" . $_POST["f_vis_1"] . "'");
			$SQL = $qb->build();
			//echo $SQL;die;
			pg_query($con, $SQL);
			//pg_query("update rs00006 set periksa='Y' where id ='$noreg'");
			unset($_SESSION["SELECT_EMP"]);
			unset($_SESSION["SELECT_EMP2"]);
			unset($_SESSION["SELECT_EMP3"]);
			header("Location: ../index2.php?p=$PID&list={$_POST["list"]}&rg1=".$_POST[rg1]."&rg=$noreg&ri=".$_POST["f_id_ri"]."&mr=".$_POST["mr"]);
		   	exit;
	}
	/* Add by Yudha : 10/01/2008 */
	if ($_POST['act'] == "new2") { 	
			//pg_query("update c_visit set id_konsul='".$_POST["konsultasi"]."' where no_reg ='$noreg' and tanggal_reg = (select max(a.tanggal_reg) from c_visit a where  a.no_reg ='$noreg' )");
			//pg_query("update c_visit_ri set vis_80='".$_POST["konsultasi"]."' where no_reg ='$noreg'  ");
//			header("Location: ../index2.php?p=$PID&list={$_POST["list"]}&rg=$noreg&poli=".$_POST["f_id_poli"]."&mr=".$_POST["mr"]);
 			$r = pg_query($con,"SELECT * FROM c_visit WHERE no_reg ='$noreg' and id_ri ='".$_POST["f_id_ri"]."'");
		    	$d = pg_fetch_object($r);
			$knsl=getFromTable("select id_konsul from c_visit where no_reg ='$noreg' and id_ri ='".$_POST["f_id_ri"]."'");
			$knsl2=getFromTable("select id_konsul from c_visit where no_reg ='$noreg' and id_ri ='".$_POST["f_id_ri"]."' and id_konsul='".$_POST["konsultasi"]."'");
			if ($knsl==""){
			pg_query("update c_visit set id_konsul='".$_POST["konsultasi"]."' where no_reg ='$noreg' and id_ri ='".$_POST["f_id_ri"]."'");
			}
			if ($knsl2=="" and $knsl!=""){
			$SQL = "select * from c_visit where no_reg ='$noreg' and id_ri ='".$_POST["f_id_ri"]."' ";
			$r2 = pg_query($con,$SQL);
			$d2 = pg_fetch_object($r2);
			pg_free_result($r2);
			$tm1=date("Y-m-d H:i:s");
			$SQLa="insert into c_visit (no_reg,tanggal_reg,id_ri,id_konsul,id_dokter,id_perawat) 
			values ('$noreg', '$d2->tanggal_reg', '".$_POST['f_id_ri']."', '".$_POST["konsultasi"]."','$d2->id_dokter', '$d2->id_perawat')";
			pg_query($con, $SQLa);
			} 
			 $SQL = "select * from c_visit_ri where no_reg ='$noreg' and id_ri::text ='".$_POST["f_id_ri"]."' ";
			$r2 = pg_query($con,$SQL);
			$d2 = pg_fetch_object($r2);
			pg_free_result($r2);
			$tm1=date("Y-m-d H:i:s");
			$SQLa="insert into c_visit (no_reg,tanggal_reg,id_poli,id_ri,id_konsul,id_dokter,id_perawat,id_perawat2,tanggal_konsul,waktu_konsul) 
			values ('$noreg', '$d2->tanggal_reg','$d2->id_rujukan', '".$_POST['f_id_ri']."', '".$_POST["konsultasi"]."','$d2->id_dokter', '$d2->vis_1','$d2->vis_2',CURRENT_DATE,CURRENT_TIME)";
			pg_query($con, $SQLa);
			
			//var_dump($_POST['f_id_ri']);die;
			unset($_SESSION["SELECT_EMP"]);
			unset($_SESSION["SELECT_EMP2"]);
			unset($_SESSION["SELECT_EMP3"]);
            header("Location: ../index2.php?p=$PID&list={$_POST["list"]}&rg1=$noreg&rg=$noreg&ri=".$_POST["f_id_ri"]."&mr=".$_POST["mr"]);                        
		   	exit;
	}

if ($tr->execute()) {
    unset($_SESSION["layanan"]);
    unset($_SESSION["layanan2"]);
    unset($_SESSION["obat2"]);
    unset($_SESSION["icd"]);
    unset($_SESSION["icd9"]);
    if($_POST['sub'] != "retur"){  
    header("Location: ../index2.php?p=$PID&list={$_POST["list"]}&rg1=".$_POST[rg]."&rg=".$_POST[rg]."&ri=$ri&mr=".$_GET["mr"]."&sub=".$_POST[sub]."&sub2=".$_POST[sub2]);
    exit;
    }else{
    	header("Location: ../index2.php?p=$PID&list={$_POST["list"]}&rg=".$_POST[rg]."&rg1=".$_POST[rg]."&ri=$ri&mr=".$_GET["mr"]."&sub=".$_POST[sub]."&sub2=".$_POST[sub2]);
    exit;
    }
} else {
     ?>
    <script>
        alert ('Terjadi kesalahan input!');
    </script>    
    <?
    echo "<script language='JavaScript'>document.location='../index2.php?p=$PID&list={$_POST["list"]}&rg=".$_POST[rg]."&rg1=".$_POST[rg]."&ri=".$_POST["ri"]."&mr=".$_GET["mr"]."&sub2=".$_POST[sub2]."&sub=".$_POST[sub] . "'</script>";
}

?>
