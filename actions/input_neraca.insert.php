<?php // efrizal

session_start();
$PID = "input_neraca";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

if (($_SESSION[totaldebit]) !== ($_SESSION[totalkredit])) {
	echo "<script>alert(\"Jumlah Debit dan Kredit harus sama. Silahkan klin tombol Back\");</script>";
    exit;
}
if (is_array($_SESSION["akun"]["akun"])) {
    foreach ($_SESSION["akun"]["akun"] as $v) {
	$id		= getFromTable("select max(id) from neraca ");
	$ids	= $id + 1;
	$hyx	= getFromTable("select hierarchy from akun_master where id = '".$v["id"]."' ");
	if (substr($hyx,0,3) == "001"){
		if($v["debit"]>0 ){
			$jumlah = $v["debit"];
		}else{
			$jumlah = -$v["kredit"];
		}
	}elseif(substr($hyx,0,3) == "002" || substr($hyx,0,3) == "003"){
		if($v["debit"]>0 ){
			$jumlah = -$v["debit"];
		}else{
			$jumlah = $v["kredit"];
		}
	}
	$periode = $v["periode"];
		pg_query("insert into neraca (id, type, debit, kredit, jml, user_id, periode, tgl_posting)
		values (".$ids.", '".$v["id"]."', ".$v["debit"].", ".$v["kredit"].",".$jumlah.",'".$v["user_id"]."','$periode'::date, CURRENT_TIMESTAMP) ");

    }
}

    unset($_SESSION["akun"]);
	unset($_SESSION["periode"]);
    $_SESSION["dialog"]["title"] = "Transaksi telah diproses...";
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    header("Location: ../index2.php?p=dialog");
    exit;


?>
