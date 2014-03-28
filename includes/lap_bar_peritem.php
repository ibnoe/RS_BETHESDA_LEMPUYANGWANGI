<?
$PID = "lap_bar_peritem";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 1000;

require_once("startup.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/class.BaseTable.php");
require_once("lib/functions.php");


	title_print("<img src='icon/rawat-jalan-2_asli.gif' align='absmiddle' > LAPORAN PENERIMAAN BARANG RUANGAN");
	title_excel("lap_bar_peritem&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&supplier=".$_GET["supplier"]."&faktur_po=".$_GET["faktur_po"]."");

    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	if (!$GLOBALS['print']) {
		include(xxx2);
		$f->selectSQL("supplier","Ruangan Tujuan","SELECT '' AS tc, '' AS tdesc UNION SELECT tc, tdesc FROM rs00001 where tt='GDP' and tc_tipe='1' and tc NOT IN ('020') ORDER BY tdesc ASC", $_GET['supplier'],null);
		$f->text("faktur_po", "No. Transaksi", null, 50, $_GET['faktur_po']);
		$f->submit ("TAMPILKAN");
		$f->execute();
	}
	else{
		include(xxx2);
		$f->selectSQL("supplier","Ruangan Tujuan","SELECT '' AS tc, '' AS tdesc UNION SELECT tc, tdesc FROM rs00001 where tt='GDP' ORDER BY tdesc ASC", $_GET['supplier'],null);
		$f->text("faktur_po", "No. Transaksi", null, 50, $_GET['faktur_po']);
		$f->execute();
	}
    
    echo "<BR>";
	$tgl1=$ts_check_in1;
	$tgl2=$ts_check_in2;
    //$t = new PgTable($con, "100%");
    if($_GET['supplier']){
		$cond = " AND a.poli_tujuan='".$_GET['supplier']."'";
	}
	
	if($_GET['faktur_po']){
		$cond = " AND (kode_transaksi ILIKE '%".$_GET['faktur_po']."%' OR kode_transaksi ILIKE '%".$_GET['faktur_po']."%')";
	}
	
 $rowsObat = pg_query($con,"select a.kode_transaksi, to_char(a.tanggal_trans,'DD Mon YYYY') as tanggal_trans, 
h.tdesc as ruangan, d.obat, b.jumlah, CASE WHEN z.harga::text IS NULL THEN e.harga_beli
ELSE z.harga END as harga,
CASE WHEN max(z.harga)::text IS NULL THEN e.harga_beli*b.jumlah
ELSE max(z.harga)*b.jumlah END as total 
from internal_transfer_m a 
LEFT JOIN internal_transfer_d b ON b.kode_transaksi=a.kode_transaksi 
LEFT JOIN buku_besar z ON b.item_id=z.item_id and trans_type='c_po_item_terima'
LEFT JOIN rs00001 h ON a.poli_tujuan::text=h.tc::text AND tt='GDP' 
LEFT JOIN rs00015 d ON d.id::text=b.item_id::text 
LEFT JOIN rs00016 e ON e.obat_id::text=b.item_id::text 
where a.status ='1' and a.tanggal_trans 
between '$tgl1' and '$tgl2' ".$cond."
group by a.kode_transaksi,a.tanggal_trans,h.tdesc,d.obat,b.jumlah,z.harga,e.harga_beli
order by a.tanggal_trans");

//--

?>

<table id="list-pasien" width="100%">
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="20">No.</td>
            <td align="CENTER" class="TBL_HEAD" width="40">TANGGAL</td>
            <td align="CENTER" class="TBL_HEAD" width="60">NO. TRANSAKSI</td>
            <td align="CENTER" class="TBL_HEAD" width="40">RUANGAN TUJUAN</td>
            <td align="CENTER" class="TBL_HEAD" width="120">NAMA BARANG</td>
            <td align="CENTER" class="TBL_HEAD" width="120">JUMLAH KIRIM</td>
            <td align="CENTER" class="TBL_HEAD" width="120">HARGA</td>
            <td align="CENTER" class="TBL_HEAD" width="120">TOTAL</td>
        </tr>
    </thead>
    <tbody>
        <?php
            if(!empty($rowsObat)){
                 $i=1;
				 $j=0;
                 while($row=pg_fetch_array($rowsObat)){
                 	
					 $newTgl		= $row['tanggal_trans'];
					 $newNoRes		= $row['kode_transaksi'];
					 $newNama		= $row['ruangan'];
					
					 if ($oldNoRes == $row['kode_transaksi'] && $oldNoRes != '') {
						$ii='';
						$jj='';
					 } else {
					 	$ii=$i++;
						$jj=$j++;
					 }
					 
					 if ($oldTgl == $row['tanggal_trans'] && $oldTgl != '') {
						$newTgl = '';
					 }
					 
					 if ($oldNoRes == $row['kode_transaksi'] && $oldNoRes != '') {
						$newNoRes = '';
						$newTgl = '';
					 }
					 
					 if ($oldNama == $row['ruangan'] && $oldNama != '') {
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
            <td align="right"><?php echo $row['jumlah'];?>&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($row['harga'],2);?>&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($row['total'],2);?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <?php
        	$oldTgl		= $row['tanggal_trans'];
			$oldNoRes	= $row['kode_transaksi'];
			$oldNama	= $row['ruangan'];
                 //$totalobat=$totalharga+$row['harga'];
                 $totalharga=$totalharga+$row['harga'];
                 $totalpesan=$totalpesan+$row['total'];
            }
			}
			
			?>
         <tr>
	        <td colspan="6" class="TBL_HEAD" align="right">T O T A L</td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah"><?php echo number_format($totalharga,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah"><?php echo number_format($totalpesan,2);?></td>
	    </tr>
    </tbody>    
</table>
