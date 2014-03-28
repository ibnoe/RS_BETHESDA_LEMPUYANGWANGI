<?php // najla 23 03 2011 di pengungsian

$PID = "akun_pengeluaran";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

 if(isset($_GET["action"])=="new") {
$tgl_sekarang = date("Y-m-d", time());
$thn_sekarang=date("Y",time());
echo $thn_sekarang-56;
echo "\n<script language='JavaScript'>\n";
        echo "function selectKode(tag) {\n";
        echo "    sWin = window.open('popup/kode_kredit.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n</script>";

					if (isset($_SESSION["SELECT_EMP"])) {
    					$_SESSION["KREDIT"]["kode"] = $_SESSION["SELECT_EMP"];
    					$_SESSION["KREDIT"]["keterangan"] =
        				getFromTable(
			            "select keterangan from kode_trans where kode = '".$_SESSION["KREDIT"]["kode"]."'");
    					unset($_SESSION["SELECT_EMP"]);
					}
title("CASH OUT");
$f = new Form("actions/kas_keluar.insert.php", "POST", "NAME=Form1");
    $f->PgConn = $con;
	$f->calendar("f_tanggal","Tanggal Kejadian",15,15,$tgl_sekarang,"Form1","icon/calendar.gif","Pilih Tanggal",$ext);
	$f->textAndButton3("f_kode_trans","Kode Transaksi",2,10,$_SESSION["KREDIT"]["kode"],$ext,"nm2",30,70,$_SESSION["KREDIT"]["keterangan"],$ext,"...",$ext,"OnClick='selectKode();';");	
	$f->text("f_keterangan","Keterangan",44,50,"","");
	$f->text("f_jumlah","Jumlah Transaksi",44,50,"","");
	$f->hidden("f_cara_bayar","001",44,50,"001","");
	$f->submit("Simpan");
    $f->execute();
}
	
else{
 	
	//jenis linennya
 	title("KAS KELUAR");
 	$t2 = new PgTable($con, "100%");
	$t2->SQL = "SELECT to_char(tanggal,'dd Mon yyyy')as tanggal	,a.kode_trans||' - '||b.keterangan,a.keterangan ,a.jumlah FROM kas_keluar a,kode_trans b where a.kode_trans=b.kode";
        if (!isset($_GET[sort])) {
           $_GET[sort] = "tanggal";
           $_GET[order] = "asc";
	}
	 $t->RowsPerPage = 20;
  	$t2->ColHeader = array("Tanggal Transaksi","Kode Transaksi","Keterangan","Jumlah");
    $t2->ShowRowNumber = true;
   	$t2->ColAlign[0] = "CENTER";
	$t2->ColAlign[1] = "LEFT";
    $t2->ColAlign[2] = "LEFT";
    $t2->ColAlign[3] = "RIGHT";
	//$t2->ColAlign[4] = "CENTER";
 	$t2->execute();
	echo "<h1><A HREF='$SC?p=$PID&action=new'>Tambah Transaksi</h1>";
}

?>