<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 21-04-2004
   // sfdn, 23-04-2004
   // sfdn, 09-05-2004

$PID = "lap_buku_besar_apo";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

?>
 <script type="text/javascript" src="plugin/jquery.js"></script>
<script type='text/javascript' src='plugin/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='plugin/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='plugin/thickbox-compressed.js'></script>
<script type='text/javascript' src='plugin/jquery.autocomplete.js'></script>
<script type='text/javascript' src='plugin/localdata.js'></script>

<link rel="stylesheet" type="text/css" href="plugin/jquery.autocomplete.css" />

<script type='text/javascript' src='plugin/jquery.js'></script>
<!--<script type='text/javascript' src='plugin/jquery.bgiframe.min.js'></script>-->
<!--<script type='text/javascript' src='plugin/jquery.ajaxQueue.js'></script>-->
<!--<script type='text/javascript' src='plugin/thickbox-compressed.js'></script>-->
<script type='text/javascript' src='plugin/jquery.autocomplete.js'></script>
<!--<script type='text/javascript' src='plugin/localdata.js'></script>-->
<script type='text/javascript' src='plugin/jquery.ui.core.js'></script>
<script type='text/javascript' src='plugin/jquery.ui.datepicker.js'></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"/>
<link rel="stylesheet" type="text/css" href="plugin/jquery.autocomplete.css"/>
<script type="text/javascript">
    var $J = jQuery.noConflict();
    $J(document).ready(function(){
        
       $J("#AUTOTEXT_OBAT").autocomplete("123Includes/ajax/list_obat.php", {
            width: 260,
            selectFirst: false,
            dataType: "json",
            //            minChars: 4,
            parse: function(data) {
                return $J.map(data, function(row) {
                    return {
                        data: row,
                        value: row.id,
                        result: row.obat
                    }
                });
            },
            formatItem: function(item) {
                return item.obat;
            }
        }).result(function(e, item) {
            $J("#id_obat").val(item.id);
        });
		
    });
</script>
<?php
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
title("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' >  LAPORAN BUKU BESAR APOTEK");
}else{
title("LAPORAN BUKU BESAR APOTEK");
}
title_print("");
echo "<br>";
$f = new Form($SC, "GET", "NAME=Form1");
$f->PgConn = $con;
$f->hidden("p", $PID);
if($GLOBALS['print']){
	$ext ="DISABLED";
}
/*$f->selectSQL("mOBT", "Kategori Obat",
    "select '' as tc, '' as tdesc union ".
    "select tc, tdesc ".
    "from rs00001 ".
    "where tt = 'GOB' and (tc!='000') ".
    "order by tdesc", $_GET["mOBT"],
    $ext);*/
$f->textauto_all("obat", "AUTOTEXT_OBAT", "Nama Obat", 50, 100, $_GET["obat"],$ext);
$f->hidden("e",$_GET['e'],"id=id_obat");

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
        
	
	if(!$GLOBALS['print']){

    // search box
    echo "<BR>";
  /*  echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
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
  */	}

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
           $_GET[sort] = "b.tanggal_entry";
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
   /* $t->SQL = "SELECT a.item_id,a.nama_obat,c.tdesc as satuan,case when d.gudang>=0 then null else null end as saldo_awal,sum(a.qty_terima),sum(a.qty_keluar),sum(a.qty_ret_msk),sum(a.qty_ret_kel),sum(a.qty_adj),case when d.gudang>=0 then null else null end as saldo_akhir,a.hna,0*a.hna as jumlah_saldo from v_buku_besar_gd a ".
			"     left join rs00015 b on to_number(a.item_id, '999999999999') = b.id ".
			"     left join rs00001 c on b.kategori_id = c.tc and c.tt='SAT' ".
			"     left join rs00016a d on to_number(a.item_id, '999999999999') = d.obat_id ".
            "where (a.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and ".
	  //  "a.trans_type='OB1' and ".
            "b.kategori_id like '%".$_GET["mOBT"]."%' ".
            "and upper(a.nama_obat) LIKE '%".strtoupper($_GET["search"])."%'  ".
            "group by a.item_id,a.nama_obat,c.tdesc,a.hna,b.obat,d.gudang"; */
	if($_GET["e"]!=''){
		$sql_add = " and a.id='".strtoupper($_GET["e"])."' ";
	?>
	<table id="list-pasien" width="100%" >
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="10" <?=$font ?>>NO	</td>
            <td align="CENTER" class="TBL_HEAD" width="50" <?=$font ?>>TANGGAL</td>
            <td align="CENTER" class="TBL_HEAD" width="90" <?=$font ?>>NO BUKTI</td>
            <td align="CENTER" class="TBL_HEAD" width="40" <?=$font ?>>STOK AWAL</td>
            <td align="CENTER" class="TBL_HEAD" width="100" <?=$font ?>>QTY MASUK </td>
            <td align="CENTER" class="TBL_HEAD" width="100" <?=$font ?>>QTY KELUAR </td>
            <td align="CENTER" class="TBL_HEAD" width="100" <?=$font ?>>QTY RETUR MASUK </td>
            <td align="CENTER" class="TBL_HEAD" width="100" <?=$font ?>>QTY RETUR KELUAR </td>
            <td align="CENTER" class="TBL_HEAD" width="100" <?=$font ?>>ADJUSMENT </td>
            <td align="CENTER" class="TBL_HEAD" width="100" <?=$font ?>>STOK AKHIR </td>
        </tr>
    </thead>
    <tbody>
<?php
	if($ts_check_in2==date('Y-m-d')){
		$field="(SELECT i.qty_ri FROM rs00016a i where i.obat_id='$_GET[e]')";
	}else{
		$field="(SELECT i.qty_ri FROM daily_stock i where i.obat_id='$_GET[e]' and date(i.timestamp)=b.tanggal_entry )";
	}
    $rowsData = pg_query($con,"SELECT to_char(e.date_stock,'dd Mon YYYY')  as tanggal,b.trans_type || '/' || to_char(e.date_stock,'mmyy') || '-' || b.id  as no_bukti,
							   (SELECT h.qty_ri FROM daily_stock h where h.obat_id='$_GET[e]' and h.date_stock=b.tanggal_entry ) as stok_awal,case when b.qty_msk_apo is null then 0 else b.qty_msk_apo end as qty_terima,case when b.qty_keluar_apo is null then 0 else b.qty_keluar_apo end as qty_keluar,case when b.qty_ret_msk_apo is null then 0 else b.qty_ret_msk_apo end as qty_ret_msk,case when b.qty_ret_kel_apo is null then 0 else b.qty_ret_kel_apo end as qty_ret_kel,case when b.qty_adj_apo is null then 0 else b.qty_adj_apo end as qty_adj,$field as stok_akhir from rs00015 a ".
	//		  "     left join rs00001 c on a.kategori_id = c.tc and c.tt='SAT' ".
			  "     left join rs00016a d on a.id = d.obat_id ". 
			  "     left join rs00016 g on a.id = g.obat_id ". 
			  "     join daily_stock e on a.id = e.obat_id ". 
			  "     join v_buku_besar b on e.obat_id =to_number(b.item_id, '999999999999') and b.id_depo='020' and e.date_stock=b.tanggal_entry ".
			  " where a.kategori_id like '%".$_GET["mOBT"]."%'  and a.kategori_id not in('019') and e.date_stock between '$ts_check_in1' and '$ts_check_in2' ".
              " and upper(a.obat) LIKE '%".strtoupper($_GET["search"])."%' $sql_add order by e.date_stock,b.id asc "); 
if(!empty($rowsData)){
	 $n2 = pg_numrows($rowsData);
	 $i=0;
	// $qty=0;
	 while($row=pg_fetch_array($rowsData)){
		 $i++;
		 if($i==1){
			$stok_awal = $row["stok_awal"];
		 }else{
			$stok_awal = "";
		 }
		 if($tgl_skrg == $row["tanggal"]){
				$tgl="";
		}else{
				$tgl = $row["tanggal"];
		}
		if($i==$n2){
			$stok_akhir = $row["stok_akhir"];
		}else{
			$stok_akhir = "";
		}
		 ?>
	<tr>
		<td class='TBL_BODY' <?=$font ?>><?php echo $i?></td>		
		<td class='TBL_BODY' style="text-align: center;" <?=$font ?>><?=$tgl ?></td>
		<td class='TBL_BODY' style="text-align: center;" <?=$font ?>><?=$row["no_bukti"]; ?></td>
		<td class='TBL_BODY' style="text-align: right;" <?=$font ?>><?php echo $stok_awal ?></td>
		<td class='TBL_BODY' style="text-align: right;" <?=$font ?>><?=$row["qty_terima"];?></td>
		<td class='TBL_BODY' style="text-align: right;" <?=$font ?>><?=$row["qty_keluar"];?></td>
		<td class='TBL_BODY' style="text-align: right;" <?=$font ?>><?=$row["qty_ret_msk"];?></td>
		<td class='TBL_BODY' style="text-align: right;" <?=$font ?>><?=$row["qty_ret_kel"];?></td>
		<td class='TBL_BODY' style="text-align: right;" <?=$font ?>><?=$row["qty_adj"];?></td>
		<td class='TBL_BODY' style="text-align: right;" <?=$font ?>><?=$stok_akhir;?></td>
	</tr>
<?php
		$tgl_skrg = $row["tanggal"];
		$jum_stok_awal = $jum_stok_awal + $stok_awal;
		$jum_qty_terima = $jum_qty_terima + $row["qty_terima"];
		$jum_qty_keluar = $jum_qty_keluar + $row["qty_keluar"];
		$jum_qty_ret_msk = $jum_qty_ret_msk + $row["qty_ret_msk"];
		$jum_qty_ret_kel = $jum_qty_ret_kel + $row["qty_ret_kel"];
		$jum_qty_adj = $jum_qty_adj + $row["qty_adj"];
		$jum_stok_akhir = $jum_stok_akhir + $stok_akhir;
		 }
	}
	?>
		<tr>
		<td align="CENTER" class="TBL_HEAD" colspan='3' <?=$font ?>>TOTAL</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" <?=$font ?>><?=$jum_stok_awal ?>&nbsp;&nbsp;</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" <?=$font ?>><?=$jum_qty_terima ?>&nbsp;&nbsp;</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" <?=$font ?>><?=$jum_qty_keluar ?>&nbsp;&nbsp;</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" <?=$font ?>><?=$jum_qty_ret_msk ?>&nbsp;&nbsp;</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" <?=$font ?>><?=$jum_qty_ret_kel ?>&nbsp;&nbsp;</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" <?=$font ?>><?=$jum_qty_adj ?>&nbsp;&nbsp;</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" <?=$font ?>><?=$jum_stok_akhir ?>&nbsp;&nbsp;</td>
    </tbody> 
</table>    
	<?php
	/*$t->SQL = "SELECT to_char(b.tanggal_entry,'dd Mon YYYY')  as tanggal,b.trans_type || '-' || to_char(e.date_stock,'mmyy')  as no_bukti,e.gudang as stok_awal,case when b.qty_msk_gd is null then 0 else b.qty_msk_gd end as qty_terima,case when b.qty_keluar_gd is null then 0 else b.qty_keluar_gd end as qty_keluar,case when b.qty_ret_msk_gd is null then 0 else b.qty_ret_msk_gd end as qty_ret_msk,case when b.qty_ret_kel_gd is null then 0 else b.qty_ret_kel_gd end as qty_ret_kel,case when b.qty_adj_gd is null then 0 else b.qty_adj_gd end as qty_adj,f.gudang as stok_akhir from rs00015 a ".
	//		  "     left join rs00001 c on a.kategori_id = c.tc and c.tt='SAT' ".
			  "     left join rs00016a d on a.id = d.obat_id ". 
			  "     left join rs00016 g on a.id = g.obat_id ". 
			  "     join daily_stock e on a.id = e.obat_id ". 
			  "     join daily_stock f on a.id = f.obat_id and date(f.timestamp)=e.date_stock ". 
			  "     left join v_buku_besar b on e.obat_id =to_number(b.item_id, '999999999999') and b.id_depo='003' and e.date_stock=b.tanggal_entry ".
			  " where a.kategori_id like '%".$_GET["mOBT"]."%'  and a.kategori_id not in('019') and e.date_stock between '$ts_check_in1' and '$ts_check_in2' ".
              " and upper(a.obat) LIKE '%".strtoupper($_GET["search"])."%' $sql_add ";
			  //"group by a.id,a.obat,c.tdesc,g.harga_beli,d.gudang,e.gudang,f.gudang";
	
	$r2 = pg_query($con, "SELECT sum(b.qty_terima) as jum_terima,sum(b.qty_keluar) as jum_keluar,sum(b.qty_ret_msk) as jum_ret_msk,sum(b.qty_ret_kel) as jum_ret_kel,sum(b.qty_adj) as jum_adj from rs00015 a ".
			  "     left join v_buku_besar_gd b on a.id =to_number(b.item_id, '999999999999') and (b.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') ".
			  //"     left join rs00001 c on a.kategori_id = c.tc and c.tt='SAT' ".
			  "     left join rs00016a d on a.id = d.obat_id ". 
			  "     left join rs00016 g on a.id = g.obat_id ". 
		      " where a.kategori_id like '%".$_GET["mOBT"]."%'  and a.kategori_id not in('019') ".
              " and upper(a.obat) LIKE '%".strtoupper($_GET["search"])."%' $sql_add ");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
	$r3 = pg_query($con, "SELECT sum(e.gudang) as jum_stok_awal,sum(f.gudang) as jum_stok_akhir from rs00015 a ".
			  "     left join daily_stock e on a.id = e.obat_id and date_stock='$ts_check_in1' ". 
			  "     left join daily_stock f on a.id = f.obat_id and date(f.timestamp)='$ts_check_in2' ". 
			  "     left join rs00016 g on a.id = g.obat_id ". 
		      " where a.kategori_id like '%".$_GET["mOBT"]."%'  and a.kategori_id not in('019') ".
              " and upper(a.obat) LIKE '%".strtoupper($_GET["search"])."%' $sql_add ");

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
	//$t->ColFooter[10] =  number_format($d3->jumlah_saldo,2,',','.');
	
    $t->ShowRowNumber = true;
	$t->setlocale("id_ID");
	if(!$GLOBALS['print']){
    //$t->ShowRowNumber = true;
    $t->RowsPerPage = 100;
	}else{
		$t->RowsPerPage = 99999;
			$t->DisableNavButton = true;
			$t->DisableStatusBar = true;
			$t->DisableScrollBar = true;
	}
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[2] = "RIGHT";
    $t->ColAlign[5] = "RIGHT";
    //$t->ColFormatMoney[2] = "%!+#2n";
   // $t->ColFormatMoney[3] = "%!+#2n";
    //$t->ColFormatMoney[4] = "%!+.2n";

    $t->ColFormatNumber[2] = 0;
    $t->ColFormatNumber[3] = 2;
    $t->ColFormatNumber[4] = 2;

    $t->ColHeader = array("TANGGAL","NO BUKTI","STOK AWAL", "QTY MASUK","QTY KELUAR","QTY RETUR <br/>MASUK", "QTY RETUR <br/>KELUAR","ADJUSMENT","STOK AKHIR");
   // $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&v=<#4#>&ts_check_in1=$ts_check_in1&ts_check_in2=$ts_check_in2'>".icon("view","View")."</A>";
    //$t->ColFooter[2] =  number_format($d2->jum,0);
    //$t->ColFooter[3] =  number_format($d2->nilai,2,',','.');

    $t->execute();
	}
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
*/
	}
}

//} // end of tc = 'view'

?>
