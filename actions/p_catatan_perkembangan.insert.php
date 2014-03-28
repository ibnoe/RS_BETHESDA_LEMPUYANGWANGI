<?php // Nugraha, Thu Apr 22 11:58:22 WIT 2004
      // sfdn, 23-04-2004: tambah harga obat
      // sfdn, 09-05-2004
	  // sfdn, 31-05-2004
//echo $_POST["list"];exit;
session_start();
$PID = "p_catatan_perkembangan_bayi";
require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$noreg= $_POST["f_no_reg"];
$_GET["mr"] = $_POST["mr"];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];
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

   
   // insert to rs00005
   pg_query("INSERT INTO rs00005 (id,reg,tgl_entry,kasir,is_obat,is_karcis,layanan,jumlah,is_bayar) 
   		VALUES( currval('kasir_seq'), '".$_POST["rg"]."' , ".
        "CURRENT_DATE, '$loket', 'N', 'N','".$_POST["ri"]."', $total, 'N')") or die("Error");

    // insert to rs00008
    foreach ($_SESSION["layanan"] as $v) {
        if ($v["nip"]) {
           $dokter = $v["nip"];
        } else {
           $dokter = 0;
        }

        $tr->addSQL(
            	"insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran, no_kwitansi ,user_id".
            	") values (".
                "nextval('rs00008_seq'), 'LTM', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."' , '".$v["id"]."', '', " .
                $v["jumlah"].",".$v["harga"].",".$v["total"].", 0, $dokter, '".$_SESSION["uid"]."')"
        );
        $r3 = pg_query($con,"select bangsal_id from rs00010 where no_reg = '".$_POST["rg"]."'  ");
        $n3 = pg_num_rows($r3);
    	if($n3 > 0) $d3 = pg_fetch_object($r3);
    	pg_free_result($r3);
    	
        $tr->addSQL("update rs00008 set bangsal_id=$d3->bangsal_id where no_reg = '".$_POST["rg"]."' and trans_form = '$PID'");
    }
	
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
                "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."' , '".$v["id"]."', '', " .
                "0,0,0,0, '".$_SESSION["uid"]."')"
        );
        $r4 = pg_query($con,"select bangsal_id from rs00010 where no_reg = '".$_POST["rg"]."'  ");
        $n4 = pg_num_rows($r4);
    	if($n4 > 0) $d4 = pg_fetch_object($r4);
    	pg_free_result($r4);
        $tr->addSQL("update rs00008 set bangsal_id=$d4->bangsal_id where no_reg = '".$_POST["rg"]."' and trans_form = '$PID'");
    }
}
// riwayat perkembangan bayi
if (is_array($_SESSION["riwayat_perkembangan"])) {
    foreach ($_SESSION["riwayat_perkembangan"] as $v) {
    	$tgl_1 = explode("-",$v["tanggal"]);
		$tgl_2 = $tgl_1[2]."-" .$tgl_1[1]."-".$tgl_1[0];
		//echo "tanggal=".$tgl_2;exit;
        
          $sql3= 	"insert into c_catatan (" .
                "id,no_reg,id_ri,tanggal,vis_1,vis_2".
            	") values (".
                "nextval('c_catatan_seq'),'".$_POST["rg"]."' , '".$_POST["ri"]."','".$tgl_2."','".$v["jam"]."','".$v["catatan"]."')";
        
        $tr->addSQL($sql3);
        $tr->addSQL("insert into c_visit_ri (no_reg,id_ri,id_rujukan) 
        			values ('".$_POST["rg"]."' ,'".$_POST["ri"]."','".$_POST["id_rujukan"]."')");
    }
    
    unset($_SESSION["riwayat_perkembangan"]);
}
// PEMBAYARAN
if (isset($_POST["byr"])) {
    $tr->addSQL(
        	"insert into rs00008 (" .
            "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
            "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
            "qty,           harga,       tagihan,    pembayaran, is_bayar, no_kwitansi ".
        	") values (".
            "nextval('rs00008_seq'), 'BYR', '$PID', currval('rs00008_seq_group'), CURRENT_DATE, " .
            "CURRENT_DATE, CURRENT_TIME, '".$_POST["rg"]."' , '', '', " .
            "0,0,0,'".$_POST["byr"]."', 'Y',currval('rs88888_seq'))"
    );

    // tambahan sfdn, 31-05-2004
    $reg_count = getFromTable("select count(mr_no) from rs00006 ".
            "where mr_no = (select mr_no from rs00006 ".
            "where id = '".$_POST["rg"]."' )");
    $baru    = "Y";
    if ($reg_count > 1 ) $baru = "T";

    $r1 = pg_query($con,
        	"select tipe, id as no_reg, tanggal_reg, rawat_inap, rujukan ".
        	"from rs00006 ".
        	"where id = '".$_POST["rg"]."' ");

    $n1 = pg_num_rows($r1);
    if($n1 > 0) $d1 = pg_fetch_object($r1);
    pg_free_result($r1);

    $loket	 = "RJN";
    if ($d1->rawat_inap == "N") {
	        $loket	 = "IGD";
	} elseif ($d->rawat_inap == "I"){
			$loket	 = "RIN";
    }
    $tr->addSQL(
        	"insert into rs00008 (" .
   	    	"id,trans_type, is_inout, qty, no_reg, is_baru, tanggal_trans, ".
	    	"datang_id, trans_group,is_bayar )".
	    	"values (" .
            "nextval('rs00008_seq'),'$loket', 'O', 1,$d1->no_reg, ".
            "'$baru',CURRENT_DATE,'$d1->rujukan',currval('rs00008_seq_group'), 'Y')");
    $tr->addSQL(
        	"update rs00006 set is_karcis='Y' where id = '".$_POST["rg"]."' ");
        //insert c_vis_jantung
	
	// pada saat pasien pulang/keluar, maka beberapa info yg. harus di-record
	// adalah: status_keluar: Sembuh, Mati, Dirujuk dll., cara bayar: Tunai, Gratis dll
	// jika pasiennya adalah RI, maka ditambah dgn. info: lama dirawat
	// dikarenakan saat ini belum ada fasilitas untuk mencatat info tsb,
	// maka cukup SQL seperti di atas
	// jika telah ada info-info tsb, maka SQL di bawah ini yg. dipergunakan
	$tr->addSQL(
			"insert into rs00008 (" .
			"trans_type, is_inout, qty ,  no_reg, is_baru, ".
			"tanggal_trans, ".
			"status_out, cara_bayar_id, gol_tindakan_id ".
		") values (" .
			"'$loket', 'O', 1,   $d1->no_reg, ".
			"'$baru',CURRENT_DATE,".
			"<<<<kode tindakan>>>>,<<<<kode status keluar>>>>,<<<<kode cara bayar>>>>,".
			"<<<<kode gol.tindakan,BESAR,KECIL dll.>>>>)");

	// akhir tambahan
    $r1 = pg_query($con,
        	"select * from rs00008 where trans_group = currval('rs00008_seq_group')");
    while ($d1 = pg_fetch_object($r1)) {
                 $tr->addSQL(
                      "update rs00008 set is_bayar = 'Y'");
   }
}


	
if ($tr->execute()) {

    unset($_SESSION["layanan"]);
    unset($_SESSION["icd"]);
    if($_POST['sub'] != "retur"){  
    header("Location: ../index2.php?p=$PID&list={$_POST["list"]}&rg1=".$_POST[rg1]."&rg=".$_POST[rg]."&ri=".$_POST["ri"]."&mr=".$_GET["mr"]."&sub=".$_POST[sub]);
    exit;
    }else{
    	header("Location: ../index2.php?p=$PID&list={$_POST["list"]}&rg=".$_POST[rg]."&ri=".$_POST["ri"]."&mr=".$_GET["mr"]."&sub=".$_POST[sub]);
    exit;
    }
} else {
    echo $tr->ErrMsg;
    
}

?>
