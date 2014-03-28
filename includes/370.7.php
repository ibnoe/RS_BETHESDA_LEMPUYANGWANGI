<?php // Nugraha, Sat May  8 23:31:59 WIT 2004
	  // sfdn, 31-05-2004
	  // sfdn, 06-06-2004
          // tokit, 7/16/2004 12:15:24 AM
ob_start() ;
$T->show(1);
if ($_GET["rg"] == '') $_GET[rg] = null;


if (isset($_GET["rg"])) {
    $r = pg_query($con, "select b.id as no_reg, a.nama,  ".
                        "case when b.rawat_inap ='Y' then 'Rawat Jalan' when b.rawat_inap = 'N' then 'IGD' else 'Rawat Inap' end as loket, ".
                        "   b.rujukan as asal,b.flag ".
                        "from rs00002 a ".
                        "   left join rs00006 b ON a.mr_no = b.mr_no ".
                        "where to_number(b.id,'9999999999') = '".$_GET["rg"]."'");
    $d = pg_fetch_object($r);
    pg_free_result($r);
}

//if (strlen($d->no_reg) > 0 && strlen($_SESSION["SELECT_BANGSAL"]) > 0) {
if (strlen($d->no_reg) > 0  ) {	
    $f = new Form("actions/370.7.insert.php", "POST","NAME=Form1");
    $f->hidden("rg",$d->no_reg);
    $f->hidden("asal",$d->asal);
    $f->hidden("loket",$d->rawat_inap);


} else {
    $f = new Form("$SC", "GET","NAME=Form1");
    $f->hidden("p",$PID);
    $f->hidden("sub",$sub);
    
}

if (isset($_SESSION["SELECT_BANGSAL"])) {
    $_SESSION["BANGSAL"]["id"] = $_SESSION["SELECT_BANGSAL"];
    $_SESSION["BANGSAL"]["desc"] =
        getFromTable(
            "select c.bangsal || ' / ' || b.bangsal || ' / ' || i.tdesc || ' / ' || a.bangsal ".
            "from rs00012 as a ".
            "    join rs00012 as b on b.hierarchy = substr(a.hierarchy,1,6) || '000000000' ".
            "    join rs00012 as c on c.hierarchy = substr(a.hierarchy,1,3) || '000000000000' ".
            "	 join rs00001 i on i.tc = b.klasifikasi_tarif_id and i.tt='KTR' ".
            "where a.id = '".$_SESSION["BANGSAL"]["id"]."'"
        );
    unset($_SESSION["SELECT_BANGSAL"]);
}

if (strlen($d->no_reg) > 0) {
	echo "<br>";
    $f->PgConn = $con;
    $f->text("rg","Nomor Registrasi",10,10,$d->no_reg,"DISABLED");
    $f->text("nm","Nama Pasien",70,70,$d->nama,"DISABLED");

    $f->selectSQL("asal", "Jenis Kedatangan Pasien",
            "select '' as tc, '' as tdesc union ".
            "select 'Y' as tc, 'Rujukan' as tdesc union ".
			"select 'N' as tc, 'Non-Rujukan' as tdesc union ".
			"select 'U' as tc, 'Unit Lain' as tdesc ", $d->asal);
	
     $f->selectSQL("flag", "Status Bangsal",
            "select tc,tdesc FROM rs00001 WHERE tt = 'BST' and tc!='000' order by tdesc", $d->flag);

     $f->text("loket2","Loket Pendaftaran",30,30,$d->loket,"DISABLED");

     $f->hidden("kode_bangsal","");

    $f->textAndButton("nm_bangsal","Bangsal",70,70,$_SESSION["BANGSAL"]["desc"],"DISABLED","...",
        "OnClick='selectBangsal();';");
    $jam 	= getFromTable("select to_char(CURRENT_TIMESTAMP,'HH24:MI') as jam");
 
        $f->selectDate("tanggal", "Terhitung Mulai", getdate(), "");
        $f->text("jam","Jam (format = 24)",15,15,$jam,"");
 
    
} 
if ((strlen($d->no_reg) > 0) and  $_SESSION["BANGSAL"] ) {
    $f->submit("Daftar");
}

		
$f->execute();

 
echo "\n<script language='JavaScript'>\n";
echo "function selectBangsal() {\n";
echo "    sWin = window.open('popup/bangsal.php', 'xWin', 'width=600,height=400,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";



ob_end_flush() ;
?>
