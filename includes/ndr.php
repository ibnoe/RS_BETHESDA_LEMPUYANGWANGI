<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004
   // sfdn, 24-12-2006	

$PID = "ndr";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

// 24-12-2006
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
// ---- end ----
if($_GET["tc"] == "view") {
    title("Rincian Retur Obat");

    if ($_GET["e"] == "Y") {
        $unit = "Rawat Jalan";
    } elseif  ($_GET["e"] == "N"){
        $unit = "IGD";
    } elseif ($_GET["e"] == "I"){
        $unit = "Rawat Inap";
    } else {
        $unit = "Semua";
    }

    $pasien = getFromTable(
               "select tdesc from rs00001 ".
               "where tc = '".$_GET["u"]."' and tt='JEP'");

    $r = pg_query($con, "select tanggal(to_date(".$_GET["f"].",'YYYYMMDD'),3) as tgl");
    $d = pg_fetch_object($r);
    pg_free_result($r);

    $bulan = $d->tgl;
    $tgl_year = substr($_GET[f],0,4);
    $tgl_mnth = substr($_GET[f],4,2);
    $tgl_day = substr($_GET[f],6,2);
    
    $f = new Form("");
    $f->subtitle("Tanggal     : $tgl_day-$tgl_mnth-$tgl_year");
    $f->subtitle("U n i t     : $unit");
    $f->subtitle("Tipe Pasien : $pasien");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    $r2 = pg_query($con,
              "select sum(a.qty * a.harga) as jum ".
              "from rs00008 a ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "		where b.rawat_inap='".$_GET["e"]."' and ".
              "     to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     a.trans_type='RET' and b.tipe = '".$_GET["u"]."'");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t->SQL = "select c.mr_no,c.nama,a.no_reg, ".
              "     e.obat, a.qty, a.harga, sum(a.qty * a.harga) as tagih ".
              "from rs00008 a  ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "     left join rs00002 c ON b.mr_no = c.mr_no ".
              "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
              "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
              "where ".
              " to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     b.rawat_inap ='".$_GET["e"]."' and ".
              "     a.trans_type = 'RET' ".
              "group by c.mr_no, c.nama, a.no_reg, e.obat, a.qty, a.harga";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = 30;
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColFormatMoney[6] = "%!+#2n";
    $t->ColHeader = array("MR.NO","NAMA","NO.REG","NAMA O B A T","QTY","HARGA","Rp.");
    $t->ColFooter[6] =  number_format($d2->jum,2);
    //$t->ShowSQLExecTime = true;
    //$t->ShowSQL = true;

    $t->execute();

} else {
    //------------------------------------------------------- mulai
    if (!$GLOBALS['print']){
    	title("<img src='icon/indikator.gif' align='absmiddle' > NDR");
    } else {
    	title("<img src='icon/indikator.gif' align='absmiddle' > NDR");
    }
    //title("LAPORAN PENDAPATAN TOTAL");
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	if(!$GLOBALS['print']){
		if (!isset($_GET['mTAHUN'])) {

			$mTAHUN = date("Y", time());

			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $mTAHUN,$ext);
    	} else {
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $_GET["mTAHUN"],$ext);
    	}
    	//$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' ",$_GET[mPASIEN],"");
		$f->submit ("TAMPILKAN");
		$f->execute();	
	} else {
		if (!isset($_GET['mTAHUN'])) {

			$mTAHUN = date("Y", time());

			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $mTAHUN,$ext);
    	} else {
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $_GET["mTAHUN"],$ext);
    	}
		$f->execute();	
	}
	
   if (!empty($_GET[mPASIEN])) {
    	$add = " c.tipe = '".$_GET[mPASIEN]."'";
   	} else {
      	$add = " c.tipe != '".$_GET[mPASIEN]."'";
   	}
   
    echo "<br>";

	
	$sql  = "select a.tdesc as layanan, sum(b.jumlah) as jml_duit, c.tipe
			 from rs00006 c
			 left join rs00001 a on c.poli = a.tc_poli and (c.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') 
			 left join rs00005 b on c.id = b.reg where b.kasir not in ('ASK','POT','IGD') and b.is_obat != 'Y' 
			 and $add and a.tt = 'LYN' 
			 group by a.tdesc,c.tipe order by a.tdesc asc ";
    
					
    $spasi1 = "<img src='images/spacer.gif' width='20' height='1'>";
    $spasi2 = "<img src='images/spacer.gif' width='50' height='1'>";
    $spasi3 = "";

    echo "<table cellpadding=0 cellspacing=0 border=0>";
    //echo "<td colspan=4><b><font size=1>INDIKATOR PERFORMANSI</size></b></td>";
    echo "</tr>";
	
    $sql_satu = getFromTable ("select (sum(extract(day from case when a.ts_calc_stop is null then current_timestamp else a.ts_calc_stop ".
                              "end - a.ts_calc_start))) ".
                              "from rs00010 a ".
                              //"join rs00012 as b on a.bangsal_id = b.id ".
                              "where extract(YEAR from a.ts_calc_start) = ".$_GET["mTAHUN"]." ");
    
	$sql_satus = getFromTable("select count(b.bangsal) ".
                              "from rs00012 b");
	if($_GET["mTAHUN"] % 4 == 0) {	
	$bor = ($sql_satu/($sql_satus*365))*100;
	} else {
	$bor = ($sql_satu/($sql_satus*365))*100;
	}
    $d5 = getFromTable("select count(b.id) ".
                       "from rs00010 b ".
					   "where extract(YEAR from b.ts_calc_start) = ".$_GET["mTAHUN"]."");
	
                    
    if($_GET["mTAHUN"] % 4 == 0) {
	@$toi = (($sql_satus*366)-$sql_satu)/$d5;
	} else {
	@$toi = (($sql_satus*365)-$sql_satu)/$d5;
	}

	@$bto = $d5/$sql_satus;

    echo "<tr>";
    echo "<td>".$spasi1."<b><font size=4>Net Death Rate</size></b></td>";
    echo "<td>&nbsp;</td>";
    echo "<td align=right>&nbsp;:&nbsp;</td>";
    echo "<td align=right colspan=2><font size=4><b>".number_format($bto,0,",",".")."</b></size></td>";
    echo "</tr>";

    echo "</table>";
    echo "<table cellpadding=0 cellspacing=0 border=0>";
    echo "&nbsp";
    echo "&nbsp";
    echo "</table>";
    echo "<b><font size=1>KETERANGAN:</size></b>";
    echo "<table cellpadding=0 cellspacing=0 border=0>";
    echo "&nbsp";
    echo "</table>";
    echo "<b><font size=1>NDR adalah angka kematian 48 jam setelah dirawat untuk tiap-tiap 1000 penderita keluar.</size></b>";
    echo "<table cellpadding=0 cellspacing=0 border=0>";
    echo "&nbsp";
    echo "</table>";
    echo "<b><font size=1>Indikator ini memberikan gambaran mutu pelayanan di rumah sakit.</size></b>";

}
title_print("");
?>