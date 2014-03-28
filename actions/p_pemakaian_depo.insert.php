<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004

session_start();
$PID = "p_pemakaian_depo";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

//echo "sts=". $_POST['status']; echo "<br>Sese=".$_SESSION['ob4']['obat']["status"]."<br>";

//echo "tgl=".$_SESSION["ob4"]["tgl-pengadaan"];exit;
$tr = new PgTrans;
$tr->PgConn = $con;
if (is_array($_SESSION["ob4"]["obat"])) {
    $tr->addSQL("select nextval('f_pemakaian_depo_seq')");
    foreach ($_SESSION["ob4"]["obat"] as $v) {
    	
    $tgl_1 = explode("-",$v["tglPakai"]);
	$tgl_2 = $tgl_1[2]."-" .$tgl_1[1]."-".$tgl_1[0];
 //echo"tanggal=".$tgl_4;exit;
       
       $SQL =   "insert into f_pemakaian_depo (" .
                "id,depo_id,obat_id,pemakaian_tgl_entry, ".
                "pemakaian_jml,pemakaian_ket,user_id".
            ") values (".
                "nextval('f_pemakaian_depo_seq'),'".$_POST["depo_id"]."', '".$v["id"]."','".$tgl_2."','".$v["jumlah_pakai"]."','".$v["keterangan"]."','".$_SESSION[uid]."')";

      // echo $SQL;exit;
       $tr->addSQL($SQL );
	if($_POST["depo_id"] == '209'){
	
		pg_query("update rs00016 set qty_awal=qty_awal + ".$v[jumlah_beri]." where obat_id = ".$v[id]);
		pg_query("update rs00016 set qty_akhir=qty_awal where obat_id = ".$v[id]);
	}else {
		
		//edited 090310	->
		@pg_query("update rs00016 set qty_awal=qty_awal - ".$v[jumlah_beri]." where obat_id = ".$v[id]);
		@pg_query("update rs00016 set qty_keluar=qty_keluar + ".$v[jumlah_beri]." where obat_id = ".$v[id]);
		@pg_query("update rs00016 set qty_akhir=qty_awal where obat_id = ".$v[id]);
	}


    }
}



if ($tr->execute()) {
    unset($_SESSION["ob4"]);
    $_SESSION["dialog"]["title"] = "Transaksi telah diproses...";
    //$_SESSION["dialog"]["desc"] = "Klik tombol dibawah ini untuk melakukan transaksi lagi...";
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    header("Location: ../index2.php?p=dialog");
    exit;
} else {
    echo $tr->ErrMsg;
}

?>
