<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 21-04-2004
   // sfdn, 23-04-2004
   // sfdn, 09-05-2004

$PID = "lap_mutasi_saldo_apo";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

//if($_GET["tc"] == "view") {
/*    
    title("<img src='icon/daftar-2.gif' align='absmiddle' >  RINCIAN PENGELUARAN BARANG");
    echo "<br>";
    $r = pg_query($con, "select b.obat,a.harga,c.tdesc as satuan,d.tdesc as kategori ".
                    "from rs00008 a, rs00015 b, rs00001 c, rs00001 d ".
                    "where a.item_id = '".$_GET["v"]."' ".
                    "and to_number(a.item_id, '999999999999') = b.id ".
                    "and b.satuan_id = c.tc and c.tt='SAT' ".
                    "and b.kategori_id = d.tc and d.tt='GOB'");

    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);

    $f = new Form("");
    $f->subtitle("Nama Barang: $d->obat");
    $f->subtitle("Satuan: $d->satuan");
    $f->subtitle("Harga: $d->harga");
    $f->subtitle("Kategori : $d->kategori");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    /*
    $r2 = pg_query($con, "select sum(qty) as jum, harga, sum(qty*harga) as nil ".
              "from rs00008 ".
	      "where item_id='".$_GET["v"]."' ".
              "and trans_type='OB1' ".
              "group by harga");
    */
	/*
    $r2 = pg_query($con, "select sum(a.qty) as jum_keluar, sum(a.qty*a.harga) as nilai,sum(d.total_jumlah) as jum_masuk ".
              "from rs00008 a ".
              "left join rs00015 b on to_number(a.item_id::text, '999999999999'::text) = b.id ".
              "left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
			  "     left join c_po_item_terima d on a.item_id = d.item_id::text and d.po_status='2' ".
              "where (a.tanggal_trans between '".$_GET[ts_check_in1]."' and '".$_GET[ts_check_in2]."') and ".
	      "a.trans_type='OB1' and a.item_id ='".$_GET[v]."' ");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t->SQL = "select e.nama, no_reg, ".
              "to_char(a.tanggal_trans,'dd MON YYYY') as tgl_trans_str, ".
              "qty as jum, a.harga, (qty*harga) as nilai ".
              "from rs00008 a, rs00015 b ,rs00001 c, rs00006 d, rs00002 e ".
              "where (a.tanggal_trans between '".$_GET[ts_check_in1]."' and '".$_GET[ts_check_in2]."') and ".
	      "a.trans_type='OB1' and ".
              "to_number(a.item_id::text, '999999999999'::text) = b.id ".
              "and b.satuan_id = c.tc and c.tt='SAT' ".
              "and a.no_reg = d.id and d.mr_no = e.mr_no ".
              "and item_id ='".$_GET["v"]."'";


    if (!isset($_GET[sort])) {
           $_GET[sort] = "nama";
           $_GET[order] = "asc";
    }
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[1] = "CENTER";
    $t->RowsPerPage = 20;
    //$t->ColFormatMoney[4] = "%!+#2n";
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColFormatNumber[3] = 0;
    $t->ColFormatNumber[4] = 2;
    $t->ColFormatNumber[5] = 2;
    $t->ColHeader = array("NAMA PASIEN","NO REGISTRASI","TANGGAL","QTY","HARGA","TOTAL");
    $t->ColFooter[3] =  number_format($d2->jum,0,',','.');
    //$t->ColFooter[4] =  number_format($d2->harga,2);
    $t->ColFooter[5] =  number_format($d2->nilai,2,',','.');
    $t->execute();

} else {
*/

if(!$GLOBALS['print']){
title("<img src='icon/daftar-2.gif' align='absmiddle' >  LAPORAN MUTASI SALDO APOTEK");
}else{
title("LAPORAN MUTASI SALDO APOTEK");
}
title_print("");
echo "<br>";
$f = new Form($SC, "GET", "NAME=Form1");
$f->PgConn = $con;
$f->hidden("p", $PID);
if($GLOBALS['print']){
	$ext ="DISABLED";
}
$f->selectSQL("mOBT", "Kategori Obat",
    "select '' as tc, '' as tdesc union ".
    "select tc, tdesc ".
    "from rs00001 ".
    "where tt = 'GOB' and (tc!='000') ".
    "order by tdesc", $_GET["mOBT"],
    $ext);

include("xxx2");

/*
$f->selectSQL("mDETIL", "Detil/Summary",
    "select '' as tc, '' as tdesc union ".
    "select 'D' as tc, 'DETIL' as tdesc union ".
    "select 'S' as tc, 'SUMMARY' as tdesc ",$_GET["mDETIL"],
    $ext);
*/
$f->hidden("mDETIL","S");
if(!$GLOBALS['print']){
$f->submit ("OK");
}
$f->execute();

//echo "<BR>";

if($GLOBALS['print']){
	$border="BORDER=1";
}
$t = new PgTable($con, "100%",$border);

if ($_GET["mDETIL"] == 'S') {
    
    // search box
    echo "<BR>";
    if(!$GLOBALS['print']){
	echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<INPUT TYPE=HIDDEN NAME=mDETIL VALUE='".$_GET["mDETIL"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=mOBT VALUE='".$_GET["mOBT"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal1D VALUE='".$_GET["tanggal1D"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal1M VALUE='".$_GET["tanggal1M"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal1Y VALUE='".$_GET["tanggal1Y"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal2D VALUE='".$_GET["tanggal2D"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal2M VALUE='".$_GET["tanggal2M"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal2Y VALUE='".$_GET["tanggal2Y"]."'>";
    echo "<TD><font class=SUB_MENU>NAMA BARANG:</font> <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
    echo "</TR></FORM></TABLE></DIV>";
	}

    // summary
    $r2 = pg_query($con, 
            "select sum(a.qty) as jum_keluar,  ".
            "sum(a.qty*a.harga) as nilai,sum(d.total_jumlah) as jum_terima ".
            "from rs00008 a ".
            "     left join rs00015 b on to_number(a.item_id, '999999999999') = b.id and b.kategori_id = '".$_GET["mOBT"]."' ".
            "     left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
            "     left join c_po_item_terima d on a.item_id = d.item_id::text and d.po_status='2' ".
            "where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
	    "a.trans_type='OB1' ".
            "and upper(obat) LIKE '%".strtoupper($_GET["search"])."%' ");
            //"group by a.item_id,b.obat,c.tdesc,b.kategori_id,a.harga");


    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    if (!isset($_GET[sort])) {
           $_GET[sort] = "obat";
           $_GET[order] = "asc";
    }

    /*
    $t->SQL = "select b.obat,c.tdesc,sum(qty) as jum,a.harga, ".
            "sum(qty*harga) as nilai ".
            "from rs00008 a, rs00015 b, rs00001 c ".
            "where a.trans_type='OB2' and ".
            "to_number(a.item_id, '999999999999') = b.id and ".
            "kategori_id = '".$_GET["mOBT"]."' and ".
            "b.satuan_id = c.tc and c.tt='SAT' and ".
            "(a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') ".
            "group by b.obat,c.tdesc,a.harga,a.item_id";


    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[3] = "RIGHT";
    $t->ColAlign[4] = "RIGHT";
    $t->ColAlign[5] = "RIGHT";
    $t->ColAlign[6] = "RIGHT";
    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColFormatMoney[3] = "%!+#2n";
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColHeader = array("NAMA OBAT", "SATUAN", "QTY","HARGA", "Rp.");
    $t->ColFooter[4] =  number_format($d2->jum,2);
    $t->execute();
    */

    //$t = new PgTable($con, "100%");
    /*$t->SQL = "SELECT a.nama_obat,c.tdesc as satuan,sum(a.qty_terima),sum(a.qty_keluar),sum(a.qty_ret_msk),sum(a.qty_ret_kel),sum(a.qty_adj),d.qty_ri as saldo_akhir,a.hna,d.qty_ri*a.hna as jumlah_saldo from v_buku_besar_apo a ".
			"     left join rs00015 b on to_number(a.item_id, '999999999999') = b.id ".
			"     left join rs00001 c on b.kategori_id = c.tc and c.tt='SAT' ".
			"     left join rs00016a d on to_number(a.item_id, '999999999999') = d.obat_id ".
            "where (a.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and ".
	  //  "a.trans_type='OB1' and ".
            "b.kategori_id like '%".$_GET["mOBT"]."%' ".
            "and upper(a.nama_obat) LIKE '%".strtoupper($_GET["search"])."%'  ".
            "group by a.item_id,a.nama_obat,c.tdesc,a.hna,b.obat,d.qty_ri"; */
	
	if($ts_check_in2==date('Y-m-d')){
		$field= "d.qty_ri";
	}else{
		$field= "f.qty_ri";
	}
	$join =" join internal_transfer_d k on a.id::text = k.item_id
				 join internal_transfer_m l on k.kode_transaksi = l.kode_transaksi and l.status='1' and l.poli_asal='".$_GET['mGDP']."'  ";
		$join2 = " 	JOIN ( SELECT k.item_id from internal_transfer_d k join internal_transfer_m l on k.kode_transaksi = l.kode_transaksi where l.status='1' and l.poli_asal='".$_GET['mGDP']."'
					GROUP BY k.item_id 			  
					) as k on a.id =to_number(k.item_id, '999999999999') ";
	$t->SQL = "SELECT a.obat,c.tdesc,e.qty_ri as stok_awal,case when b.qty_msk is null then 0 else b.qty_msk end as qty_terima,case when b.qty_keluar is null then 0 else b.qty_keluar end as qty_keluar,case when b.qty_ret_msk is null then 0 else b.qty_ret_msk end as qty_ret_msk,case when b.qty_ret_kel is null then 0 else b.qty_ret_kel end as qty_ret_kel,case when b.qty_adj is null then 0 else b.qty_adj end as qty_adj,$field as stok_akhir,g.harga_beli,($field*g.harga_beli) as jumlah_saldo from rs00015 a ".
			  "     LEFT OUTER JOIN (
						SELECT h.item_id , sum(h.qty_msk) as qty_msk,sum(h.qty_keluar) as qty_keluar,sum(h.qty_ret_msk) as qty_ret_msk,sum(h.qty_ret_kel) as qty_ret_kel,sum(h.qty_adj) as qty_adj
							from v_buku_besar3 h where h.tanggal_entry between '$ts_check_in1' and '$ts_check_in2' and h.id_depo like '%020%'
							GROUP BY h.item_id
					) as b on a.id =to_number(b.item_id, '999999999999') ".
			  "     left join rs00001 c on a.kategori_id = c.tc and c.tt='SAT' ".
			  "     left join rs00016a d on a.id = d.obat_id ". 
			  "     left join rs00016 g on a.id = g.obat_id ". 
			  "     left join daily_stock e on a.id = e.obat_id and e.date_stock='$ts_check_in1' ". 
			  "     left join daily_stock f on a.id = f.obat_id and date(f.timestamp)='$ts_check_in2' $join ". 
			  " where a.kategori_id like '%".$_GET["mOBT"]."%' and a.status='1' ".
              " and upper(a.obat) LIKE '%".strtoupper($_GET["search"])."%'  ".
			  "group by a.id,a.obat,c.tdesc,g.harga_beli,d.gudang,e.qty_ri,$field,b.qty_msk,b.qty_keluar,b.qty_ret_msk,b.qty_ret_kel,b.qty_adj";
	
	$r2 = pg_query($con, "SELECT sum(b.qty_msk) as jum_terima,sum(b.qty_keluar) as jum_keluar,sum(b.qty_ret_msk) as jum_ret_msk,sum(b.qty_ret_kel) as jum_ret_kel,sum(b.qty_adj) as jum_adj from rs00015 a ".
			    "     LEFT OUTER JOIN (
						SELECT h.item_id , sum(h.qty_msk) as qty_msk,sum(h.qty_keluar) as qty_keluar,sum(h.qty_ret_msk) as qty_ret_msk,sum(h.qty_ret_kel) as qty_ret_kel,sum(h.qty_adj) as qty_adj
							from v_buku_besar3 h where h.tanggal_entry between '$ts_check_in1' and '$ts_check_in2' and h.id_depo like '%020%'
							GROUP BY h.item_id
					) as b on a.id =to_number(b.item_id, '999999999999') ".
			  "     left join rs00001 c on a.kategori_id = c.tc and c.tt='SAT' ".
			  "     left join rs00016a d on a.id = d.obat_id ". 
			  "     left join rs00016 g on a.id = g.obat_id $join2 ". 
		      " where a.kategori_id like '%".$_GET["mOBT"]."%' and a.status='1' ".
              " and upper(a.obat) LIKE '%".strtoupper($_GET["search"])."%'  ");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
	$r3 = pg_query($con, "SELECT sum(e.qty_ri) as jum_stok_awal,sum(f.qty_ri) as jum_stok_akhir,sum(f.qty_ri*g.harga_beli) as jumlah_saldo from rs00015 a ".
			  "     left join daily_stock e on a.id = e.obat_id and date_stock='$ts_check_in1' ". 
			  "     left join daily_stock f on a.id = f.obat_id and date(f.timestamp)='$ts_check_in2' ". 
			  "     left join rs00016 g on a.id = g.obat_id $join2". 
		      " where a.kategori_id like '%".$_GET["mOBT"]."%' ".
              " and upper(a.obat) LIKE '%".strtoupper($_GET["search"])."%' and a.status='1' ");

    $d3 = pg_fetch_object($r3);
    pg_free_result($r3);
	$t->ColFooter[1] =  "TOTAL ";
	$t->ColFooter[2] =  number_format($d3->jum_stok_awal,0,',','.');
	$t->ColFooter[3] =  number_format($d2->jum_terima,0,',','.');
	$t->ColFooter[4] =  number_format($d2->jum_keluar,0,',','.');
	$t->ColFooter[5] =  number_format($d2->jum_ret_msk,0,',','.');
	$t->ColFooter[6] =  number_format($d2->jum_ret_kel,0,',','.');
	$t->ColFooter[7] =  number_format($d2->jum_adj,0,',','.');
	$t->ColFooter[8] =  number_format($d3->jum_stok_akhir,0,',','.');
	$t->ColFooter[10] =  number_format($d3->jumlah_saldo,2,',','.');
	
	$t->ShowRowNumber = true;
	$t->setlocale("id_ID");
    if(!$GLOBALS['print']){
   // $t->ShowRowNumber = true;
    $t->RowsPerPage = 100;
	}else{
		$t->RowsPerPage = 99999;
			$t->DisableNavButton = true;
			$t->DisableStatusBar = true;
			$t->DisableScrollBar = true;
	}
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[2] = "RIGHT";
    $t->ColAlign[5] = "CENTER";
    //$t->ColFormatMoney[2] = "%!+#2n";
   // $t->ColFormatMoney[3] = "%!+#2n";
    //$t->ColFormatMoney[4] = "%!+.2n";

    $t->ColFormatNumber[2] = 0;
    $t->ColFormatNumber[3] = 2;
    $t->ColFormatNumber[4] = 2;

    $t->ColHeader = array("NAMA BARANG", "SATUAN","STOK AWAL", "QTY MASUK","QTY KELUAR","QTY RETUR <br/>MASUK", "QTY RETUR <br/>KELUAR","ADJUSMENT","STOK AKHIR","HNA","JUMLAH SALDO");
   // $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&v=<#4#>&ts_check_in1=$ts_check_in1&ts_check_in2=$ts_check_in2'>".icon("view","View")."</A>";
    //$t->ColFooter[2] =  number_format($d2->jum,0);
    //$t->ColFooter[3] =  number_format($d2->nilai,2,',','.');

    $t->execute();
}

/*
else {

    // detail
    $r2 = pg_query($con, "select sum(a.qty*a.harga) as jum ".
            "from rs00008 a, rs00015 b ".
            "where a.trans_type='OB2' and ".
            "to_number(a.item_id, '999999999999') = b.id and ".
            "b.kategori_id= '".$_GET["mOBT"]."' and ".
            "(a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2')");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t->SQL = "select e.nama,a.no_reg,tanggal(a.tanggal_trans,3) as tanggal_trans_str, ".
              "a.trans_group,b.obat,c.tdesc,qty as jum,a.harga, ".
              "qty*harga as nilai ".
              "from rs00008 a, rs00015 b, rs00001 c, rs00006 d, rs00002 e ".
              "where a.trans_type='OB2' and ".
              "to_number(a.item_id, '999999999999') = b.id and ".
              "kategori_id = '".$_GET["mOBT"]."' and ".
              "b.satuan_id = c.tc and c.tt='SAT' and ".
              "a.no_reg = d.id and d.mr_no = e.mr_no and ".
              "(a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2')";


    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColAlign[6] = "RIGHT";
    $t->ColAlign[7] = "RIGHT";
    $t->ColAlign[8] = "RIGHT";
    $t->ColFormatMoney[6] = "%!+#2n";
    $t->ColFormatMoney[7] = "%!+#2n";
    $t->ColFormatMoney[8] = "%!+#2n";
    $t->ColHeader = array("NAMA PASIEN","NO.REG","TANGGAL","NO.RESEP","NAMA OBAT", "SATUAN", "QTY","HARGA", "Rp.");
    $t->ColFooter[8] =  number_format($d2->jum,2);
    $t->execute();

}
*/
//} // end of tc = 'view'

?>
