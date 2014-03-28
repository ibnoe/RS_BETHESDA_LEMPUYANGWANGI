<?
$PID = "lap_non_proses";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 1000;

require_once("startup.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/class.BaseTable.php");
require_once("lib/functions.php");


	title_print("<img src='icon/rawat-jalan-2_asli.gif' align='absmiddle' > LAPORAN BARANG BELUM DI PROSES");
	title_excel("lap_non_proses&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&supplier=".$_GET["supplier"]."&faktur_po=".$_GET["faktur_po"]."");

	
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	if (!$GLOBALS['print']) {
		include(xxx2);
		$f->selectSQL("supplier","SUPPLIER","SELECT '' AS id, '' AS nama UNION SELECT id, nama FROM rs00028 ORDER BY nama ASC", $_GET['supplier'],null);
		$f->text("faktur_po", "No. PO / No. Faktur", null, 50, $_GET['faktur_po']);
		$f->submit ("TAMPILKAN");
		$f->execute();
	}
	else{
		include(xxx2);
		$f->selectSQL("supplier","SUPPLIER","SELECT '' AS id, '' AS nama UNION SELECT id, nama FROM rs00028 ORDER BY nama ASC", $_GET['supplier'],null);
		$f->text("faktur_po", "No. PO / No. Faktur", null, 50, $_GET['faktur_po']);
		$f->execute();
	}
    
    echo "<BR>";
	$tgl1=$ts_check_in1;
	$tgl2=$ts_check_in2;
    //$t = new PgTable($con, "100%");
      if($_GET['supplier']){
		$cond = " AND supp_id=".$_GET['supplier'];
	}else if($_GET['faktur_po']){
		$cond = " AND (a.po_id LIKE '%".$_GET['faktur_po']."%' OR b.no_faktur LIKE '%".$_GET['faktur_po']."%')";
	}else if($_GET['faktur_po'] && $_GET['supplier']){
	$cond = " AND supp_id=".$_GET['supplier']." AND (a.po_id LIKE '%".$_GET['faktur_po']."%' OR b.no_faktur LIKE '%".$_GET['faktur_po']."%')";
	}else{}
	
	$rowsObat = pg_query($con,"	select a.po_id, to_char(a.po_tanggal,'DD Mon YYYY') as po_tanggal, a.nama, d.obat, b.jumlah2, b.harga_beli as harga,b.diskon1
from rsv_pengadaan a 
LEFT JOIN c_po_item b ON b.po_id=a.po_id  
LEFT JOIN rs00015 d ON d.id::text=b.item_id::text 
where 
b.po_status='0' and ".
//((select count(z.item_id) as jumlah from c_po_item z where z.po_id=a.po_id) - (select count(x.item_id) as jumlah from c_po_item_terima x where x.po_id=a.po_id)) !='0' and
"a.po_tanggal between '$tgl1' and '$tgl2'".$cond." order by a.po_tanggal,a.po_id,d.obat");

//--

?>

<table id="list-pasien" width="100%">
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="20">No.</td>
            <td align="CENTER" class="TBL_HEAD" width="40">TANGGAL</td>
            <td align="CENTER" class="TBL_HEAD" width="60">NO. PO</td>
            <td align="CENTER" class="TBL_HEAD" width="40">NAMA SUPPLIER</td>
            <td align="CENTER" class="TBL_HEAD" width="120">NAMA BARANG</td>
            <td align="CENTER" class="TBL_HEAD" width="120">JUMLAH BELI</td>
            <td align="CENTER" class="TBL_HEAD" width="120">HARGA</td>
            <td align="CENTER" class="TBL_HEAD" width="120">DISKON (%)</td>
            <td align="CENTER" class="TBL_HEAD" width="150">TOTAL</td>
            <td align="CENTER" class="TBL_HEAD" width="120">PPN (%)</td>
            <td align="CENTER" class="TBL_HEAD" width="120">MATERAI</td>
            <td align="CENTER" class="TBL_HEAD" width="120">GRAND TOTAL</td>
        </tr>
    </thead>
    <tbody>
        <?php
            if(!empty($rowsObat)){
                 $i=1;
				 $j=0;
                 while($row=pg_fetch_array($rowsObat)){
                 	
					 $newTgl		= $row['po_tanggal'];
					 $newNoRes		= $row['po_id'];
					 $newNama		= $row['nama'];
					
					 
					 if ($oldNoRes == $row['po_id'] && $oldNoRes != '') {
						$ii='';
						$jj='';
					 } else {
					 	$ii=$i++;
						$jj=$j++;
					 }
					 
					 if ($oldTgl == $row['po_tanggal'] && $oldTgl != '') {
						$newTgl = '';
					 }
					 
					 if ($oldNoRes == $row['po_id'] && $oldNoRes != '') {
						$newNoRes = '';
						$newTgl = '';
					 }
					 
					 if ($oldNama == $row['nama'] && $oldNama != '') {
						$newNama = '';
					 }
					 
					 
			$totalpokok=$totalpokok+$row['total'];
					 
        ?>
        <tr>
            <td align="right"><?php echo $ii?></td>
            <td align="right"><?php echo $newTgl;?>&nbsp;&nbsp;&nbsp;</td>
            <td align="left">&nbsp;&nbsp;&nbsp;<?php echo $newNoRes;?></td>
            <td align="left">&nbsp;&nbsp;&nbsp;<?php echo $newNama;?></td>
            <td align="left">&nbsp;&nbsp;&nbsp;<?php echo $row['obat'];?></td>
            <td align="right"><?php echo $row['jumlah2'];?>&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($row['harga'],2);?>&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($row['diskon1'],2);?>&nbsp;&nbsp;&nbsp;%</td>
            <td align="right"><?php echo number_format($row['total'],2);?>&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($row['ppn'],2);?>%&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($row['materai'],2);?>&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($row['totalppn'],2);?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <?php
        	$oldTgl		= $row['po_tanggal'];
			$oldNoRes	= $row['po_id'];
			$oldNama	= $row['nama'];
                 //$totalobat=$totalharga+$row['harga'];
                 $totalharga=$totalharga+$row['harga'];
                 $totaldiskon=$totaldiskon+$row['diskon'];
                 $totalpesan=$totalpesan+$row['total'];
                 $totalpesanppn=$totalpesanppn+$row['totalppn'];
                 $totalmaterai=$totalmaterai+$row['materai'];
            }
			}
			
			?>
         <tr>
	        <td colspan="6" class="TBL_HEAD" align="right">T O T A L</td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah"><?php echo number_format($totalharga,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah"><?php echo number_format($totaldiskon,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah"><?php echo number_format($totalpesan,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah">&nbsp;</td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah"><?php echo number_format($totalmaterai,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah"><?php echo number_format($totalpesanppn,2);?></td>
	    </tr>
    </tbody>    
</table>
