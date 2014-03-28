<? // tokit, 2004 09 08

//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root" || $_SESSION[uid] == "laborat" || $_SESSION[uid] == "radiologi") {

$PID = "140";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (strlen($_GET["registered"]) == 0) $_GET["registered"] = "Y";
title("<img src='icon/daftar-2.gif' align='absmiddle' > EDIT REGISTRASI PASIEN");

echo "<br>";

$f = new Form($SC, "GET", "NAME=Form2");
$f->hidden("p",$PID);
$f->hidden("registered", "Y");
$f->hidden("q","search");
if (empty($_GET[e])) { 
$f->text("search","Pencarian <font color='red'>(NO.REG)",40,40,$_GET["search"]);
$f->submit(" Cari ");
}
$f->execute();

if ($_GET["e"]) {
        // data reg
        $r = pg_query($con, "select * from rs00006 where id = '".$_GET["e"]."'");
        $d = pg_fetch_object($r);
        pg_free_result($r);

        // data mr
        $rr = pg_query($con, "select * from rs00002 where mr_no = '$d->mr_no'");
        $dd = pg_fetch_object($rr);
        pg_free_result($rr);
        
        echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&registered=Y&q=search&search=a'>".icon("back","Kembali")."</a></DIV>";

        $f = new Form("actions/140.update.php", "POST", "NAME=Form1");
        $f->hidden("p",$PID);
        $f->hidden("mr_no",$d->mr_no);
        $f->hidden("xpoli",100); //Poliklikik IGD
		//$f->hidden("xpoli",$d->poli);
		$f->hidden("tanggal_reg",$d->tanggal_reg);

        $f->subtitle("Registrasi");
        $f->text("no_reg","No. Registrasi",12,12, $d->id,"readonly");
        $f->selectDate("tgl_reg", "Tgl Register", pgsql2phpDate($d->tanggal_reg),"DISABLED");
        $f->text("nama","Nama",40,50, $dd->nama,"readonly");
        $f->text("alamat","Alamat",40,50, "$dd->alm_tetap $dd->kota_tetap","readonly");

        if ($_SESSION[uid] == "igd") {

        $f->selectArray("f_rawat_inap", "Rawatan",Array("N" => "IGD"),"N", "OnChange=\"setPoli(this.value);\"");
        $f->PgConn = $con;
		$f->selectSQL("f_poli", "Poli","select '' as tc, '' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc NOT IN 
						 ('000','100','201','202','206','207','208') order by tdesc ","", "DISABLED");

        } elseif ($_SESSION[gr] == "daftar" || $_SESSION[gr] == "DAFTARRI") {

        $f->selectArray("f_rawat_inap", "Rawatan",Array("Y" => "Rawat Jalan"),"Y", "OnChange=\"setPoli(this.value);\"");
        $f->PgConn = $con;
		$f->selectSQL("f_poli", "Poli","select '' as tc, '' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc NOT IN 
						 ('000','100','201','202','206','207','208') order by tdesc ","", "");
        } else {

        $f->selectArray("f_rawat_inap", "<font color='red'>Rawatan",Array("Y" => "Rawat Jalan", "N" => "IGD"),
                        "N", "OnChange=\"setPoli(this.value);\"");
        $f->PgConn = $con;
		$f->selectSQL("f_poli", "<font color='red'>Poli","select '' as tc, '' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc NOT IN 
						 ('000','100','201','202','206','207','208') order by tdesc ","", "DISABLED");
        }

		$f->selectArray("f_rujukan", "Jenis Kedatangan",Array("N" => "Non Rujukan", "Y" => "Rujukan"),
                        $d->rujukan, "OnChange=\"setRujukan(this.value);\"");
        $f->selectSQL("f_rujukan_rs_id", "Rumah Sakit Perujuk","select '' as tc, '' as tdesc union ".
                      "select tc, tdesc from rs00001 where tt = 'RUJ' and tc != '000'","", "DISABLED");
        $f->text("f_rujukan_dokter","Dokter Perujuk",50,50,"","DISABLED");
        $f->selectSQL("f_id_penanggung", "Penanggung","select tc, tdesc from rs00001 where tt = 'PEN' and tc != '000'",
                      $d->id_penanggung);
        $f->selectSQL("f_id_penjamin", "Penjamin","select tc, tdesc from rs00001 where tt = 'PJN' and tc != '000'",
                      $d->id_penjamin);
        $f->selectSQL("f_tipe", "<font color='red'>Tipe Pasien","select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000'Order By tdesc Asc;",
                      $d->tipe);
        $f->selectSQL("f_diagnosa_sementara", "<font color='red'> Dokter Pemeriksa","select nama, nama from rs00017 WHERE pangkat LIKE '%DOKTER%' Order By nama Asc ;",
		              $d->diagnosa_sementara);

        $f->submit(" SIMPAN ");
       
	 $f->execute();
        
        if ($_GET[err] == 1) {
           echo "<br><font color=red>ERROR: Poli belum dipilih.</font>";
        }
        ?>

		
		
<BR>
<?
//tambah tombol Print indra
echo "\n<script language='JavaScript'>\n";
echo "function cetakaja(tag) {\n";
echo "    sWin = window.open('includes/cetak.121.php?rg=' + tag, 'xWin',".
     " 'top=0,left=0,width=500,height=300,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";
//------------------------------------------
?>
<?
//tambah tombol Print indra
echo "\n<script language='JavaScript'>\n";
echo "function cetakaja2(tag) {\n";
echo "    sWin = window.open('includes/cetak_form.121.php?rg=' + tag, 'xWin',".
     " 'top=0,left=0,width=500,height=300,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";
//------------------------------------------
?>
<div align="left">
PRINT REGISTER <a href="javascript: cetakaja('<? echo $_GET[e];?>')" ><img src="images/cetak.gif" border="0"></a>
PRINT FORM <a href="javascript: cetakaja2('<? echo $_GET[e];?>')" ><img src="images/cetak.gif" border="0"></a>
</div>






        <SCRIPT language="JavaScript">
            document.Form1.f_rujukan_rs_id.selectedIndex = -1;

            function setRujukan( v )
            {
                document.Form1.f_rujukan_rs_id.disabled = v == "N";
                document.Form1.f_rujukan_dokter.disabled = v == "N";
                document.Form1.f_rujukan_dokter.value = v == "N" ? "" : document.Form1.f_rujukan_dokter.value;
                document.Form1.f_rujukan_rs_id.selectedIndex = document.Form1.f_rujukan_rs_id.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.f_rujukan_rs_id.selectedIndex : -1;
            }
        </SCRIPT>
        <SCRIPT language="JavaScript">
            document.Form1.f_poli.selectedIndex = -1;
            function setPoli( v )
            {
                document.Form1.f_poli.disabled = v == "N";
                document.Form1.f_poli.selectedIndex = document.Form1.f_poli.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.f_poli.selectedIndex : -1;
            }
        </SCRIPT>
		
        <?php
} else {
    
    if ($_GET["registered"] == "Y" && $_GET["q"] == "search" && strlen($_GET["search"]) > 0) {
    
	$SQLSTR = "select a.mr_no, b.id, upper(a.nama)as nama,a.jenis_kelamin,a.umur,a.kesatuan, c.tdesc as poli, ".
			 "d.tdesc, b.id as href FROM  rs00006 b ".
              "left join rs00002 a on a.mr_no = b.mr_no  ".
			  "left join rs00001 c on b.poli = c.tc_poli and c.tt='LYN' ".
			  "left join rs00001 d on b.tipe = d.tc AND d.tt = 'JEP' ".
              "where upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ".
			  "OR b.id LIKE '%".$_GET["search"]."%' ".
              "OR a.mr_no LIKE '%".$_GET["search"]."%' ".
              "OR upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%'" ;
	// tambah urutan
    if (!isset($_GET[sort])) {
           $_GET[sort] = "mr_no";
           $_GET[order] = "asc";
	}   
	
	if ($_SESSION[uid] == "igd") {
	    $SQLWHERE = "AND (c.tc_poli = 100 OR c.tc_poli=0)";		//$SQLWHERE = "AND (b.poli = 10 OR b.poli=0)";
	//} elseif ($_SESSION[uid] == "daftar") {
	//    $SQLWHERE = "AND c.tc<>100 AND c.tc<>0";		//$SQLWHERE = "AND b.poli<>10 AND b.poli<>0";
	} elseif ($_SESSION[uid] == "laborat") {
		$SQLWHERE = "AND c.tc=203";
	} elseif ($_SESSION[uid] == "radiologi") {
		$SQLWHERE = "AND c.tc=204";
	}	
    
    $t = new PgTable($con, "100%");
    $t->SQL = "$SQLSTR $SQLWHERE";
    $t->ColHeader = array("NO.MR","NO.REG","NAMA","SEKS","UMUR (Tahun)","PEKERJAAN","RAWATAN","TIPE PASIEN","EDIT");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
	$t->ColAlign[1] = "CENTER";
        $t->ColAlign[3] = "CENTER";
        $t->ColAlign[4] = "CENTER";
	$t->ColAlign[7] = "CENTER";
	$t->ColAlign[8] = "CENTER";
	$t->RowsPerPage = 25;
    //$t->DisableStatusBar = true;
    $t->ColFormatHtml[8] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#8#>'>".icon("edit","Edit")."</A>";
    $t->execute();
    }
}


//} // end of $_SESSION[uid] == daftar || igd
?>