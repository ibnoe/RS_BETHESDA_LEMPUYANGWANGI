<? // 30/12/2003
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 29-04-2004
   // sfdn, 30-04-2004
   // sfdn, 09-05-2004
   // sfdn, 18-05-2004
   // sfdn, 02-06-2004
   // tokit aja, 15-09-2004
   // sfdn, 17-12-2006
   // sfdn, 24-12-2006
   // sfdn, 25-12-2006
   // sfdn, 26-12-2006
 

$PID = "480";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");

    if ($_SESSION[uid] == "kasir2") {
       $what = "RAWAT INAP";
       $sqlayanan = "NOT LIKE '%IGD%'";	
    } elseif ($_SESSION[uid] == "kasir1") {
       $what = "RAWAT JALAN";
       $sqlayanan = "NOT LIKE '%IGD%'";
    } else {
       $what = "IGD";
       $sqlayanan = "LIKE '%IGD%'";
    }



if (isset($_GET["mJMK"])) $_SESSION["mJMK"] = $_GET["mJMK"];
    // search box
    title("<img src='icon/keuangan-2.gif' align='absmiddle' > TRANSAKSI PASIEN");
    $ext = "OnChange = 'Form1.submit();'";
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	include(xxx2);

    $f->selectSQL("mJMK", "Jenis Karcis","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 ".
    "where tt = 'JMK' and tc != '000' order by tdesc", $_SESSION["mJMK"],"");
    $f->submit ("TAMPILKAN");
    $f->execute();
    echo "<BR>";
	
	
	
	$ts_in1=$ts_check_in1." 00:00:00";
	$ts_in2=$ts_check_in2." 23:59:59";
	
	$jum="select a.harga
				from kasir_karcis a,master_karcis b
				where a.poli=b.id and b.jmk='".$_SESSION["mJMK"]."' and (a.tanggal_reg between '$ts_in1' and '$ts_in2') ";
	//buat jumlah
	$r=pg_query($con,$jum);
	$n = pg_num_rows($r);
	$total=0;
	while ($d1 = pg_fetch_array($r)){
	
	$total=$total+$d1["harga"];
	}
	
    $t = new PgTable($con, "100%");
    $t->SQL  ="select count(a.nama) as jumlah, b.code as poli, (a.harga*count(a.nama)) as jumlah_harga,
				(a.harga*count(a.nama)*0.6) as sarana ,(a.harga*count(a.nama)*0.4) as layanan
				from kasir_karcis a,master_karcis b
				where a.poli=b.id and b.jmk='".$_SESSION["mJMK"]."' and (a.tanggal_reg between '$ts_in1' and '$ts_in2') group by b.code,a.harga";  
	
    
    $t->setlocale("id_ID");
    $t->ColHeader = array("Jumlah Pasien","NAMA POLI","HARGA","SARANA", "LAYANAN" );
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
	$t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
	$t->ColAlign[4] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&v=<#2#>&t1=$ts_check_in1&t2=$ts_check_in2'><#2#></A>";
	$t->ColFooter[1]="TOTAL";
	$t->ColFooter[2]=$total;
	$t->ColFooter[3]=$total*0.6;
	$t->ColFooter[4]=$total*0.4;
    $t->execute();


//} // --- end of ($_SESSION[uid] ----
?>
