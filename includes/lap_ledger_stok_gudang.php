<?
require_once("startup.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$tgl1D = date("d");
$tgl1M = date("m");
$tgl1Y = date("Y");
$tgl2D = date("d");
$tgl2M = date("m");
$tgl2Y = date("Y");
if ($_GET["tanggal1D"] != '') {
    $tgl1D = $_GET["tanggal1D"];
    $tgl1M = $_GET["tanggal1M"];
    $tgl1Y = $_GET["tanggal1Y"];
    $tgl2D = $_GET["tanggal2D"];
    $tgl2M = $_GET["tanggal2M"];
    $tgl2Y = $_GET["tanggal2Y"];
}

$f = new Form($_SERVER["SCRIPT_NAME"], "GET", "NAME=Form1");
$f->PgConn = $con;
$f->hidden("p", 'lap_ledger_stok_gudang');
$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0, 0, 0, $tgl1M, $tgl1D, $tgl1Y)), "");
$f->selectDate("tanggal2", "s/d", getdate(mktime(0, 0, 0, $tgl2M, $tgl2D, $tgl2Y)), "");
$f->textauto_all("mOBAT", "AUTOTEXT_OBAT", "Item", 50, 100, $_GET["mOBAT"]);
$f->submit("TAMPILKAN");
$f->execute();

$tgl1 = $tgl1Y . '-' . $tgl1M . '-' . $tgl1D;
$tgl2 = $tgl2Y . '-' . $tgl2M . '-' . $tgl2D;

list($id_obat,$nama)=  explode(' - ', $_GET['mOBAT']);
$obatnya=$id_obat!=''?'AND a.obat_id= \''.$id_obat.'\'':'';
$SQL = "
SELECT 
 a.obat_id ,f.obat,
p.tdesc AS satuan,
b.gudang as qty_awal,
sum(i.item_qty_terima) as qty_penerimaan, 
sum(o.jum_inter) as qty_transfer_in,
sum(e.jum_inter) as qty_transfer_out,
sum(l.qty_retur) as qty_retur_suplier,
sum(r.qty_adjust) as qty_adjustment,
a.gudang as qty_akhir
FROM rs00016a a
/*stok awal tanggal pencarian - qty_awal - */
LEFT OUTER JOIN daily_stock b on a.obat_id= b.obat_id AND date(b.date_stock) = '$tgl1'
/* qty_transfer_out */
LEFT OUTER JOIN (
	SELECT d.item_id , sum(d.jumlah*d.jml_isi) jum_inter
	from internal_transfer_m c 
	JOIN internal_transfer_d d ON c.kode_transaksi = d.kode_transaksi AND c.poli_asal='003' AND c.tanggal_trans BETWEEN '$tgl1' AND '$tgl2'
	GROUP BY d.item_id
) as e on a.obat_id = e.item_id::NUMERIC
/* nama obat */
LEFT JOIN rs00015 f ON a.obat_id = f.id
/* qty_penerimaan */
LEFT OUTER JOIN (
	SELECT rsd.kode_obat , sum(h.item_qty_terima*rsd.jumlah1) as item_qty_terima
	from c_gr g 
	JOIN c_gr_item h ON g.no_faktur_jalan = h.no_faktur_jalan and g.tgl_terima BETWEEN '$tgl1' AND '$tgl2' 
	JOIN c_po_item cpi on g.kode_po = cpi.po_id AND h.item_id = cpi.item_id
	JOIN rs00016d rsd on rsd.kode_obat = cpi.item_id::NUMERIC AND rsd.satuan2 = cpi.satuan2
GROUP BY rsd.kode_obat
ORDER BY rsd.kode_obat ASC
) as i on a.obat_id = i.kode_obat::NUMERIC 
/* qty_retur */
LEFT OUTER JOIN (
	SELECT k.item_id , k.qty_retur
	from c_retur j 
	JOIN c_retur_item k ON j.no_retur = k.no_retur and j.tgl_retur BETWEEN '$tgl1' AND '$tgl2'
) as l on a.obat_id = l.item_id::NUMERIC 
/* qty_transfer_in */
LEFT OUTER JOIN (
	SELECT n.item_id , sum(n.jumlah*n.jml_isi) jum_inter
	from internal_transfer_m m 
	JOIN internal_transfer_d n ON m.kode_transaksi = n.kode_transaksi AND m.poli_tujuan='003' AND m.tanggal_trans BETWEEN '$tgl1' AND '$tgl2'
	GROUP BY n.item_id
) as o on a.obat_id = o.item_id::NUMERIC
LEFT JOIN rs00001 p ON f.satuan_id = p.tc AND p.tt = 'SAT'
/* qty_adjustment */
LEFT OUTER JOIN (
	SELECT q.obat_id,SUM(q.qty_adjust) AS qty_adjust 
	FROM adjusment_stok q
	WHERE q.idrs_16 = 'gudang' AND q.id_lokasi = '003' 
	GROUP BY q.obat_id
) as r ON a.obat_id = r.obat_id::NUMERIC 
WHERE 
f.obat NOT LIKE '%AJII%' AND 
f.obat NOT LIKE '%CSSD%' AND 
f.status = '1'  ".$obatnya."
GROUP BY a.obat_id,f.obat,a.gudang,b.gudang,p.tdesc
ORDER BY a.obat_id asc

";
//echo $_GET['mOBAT'];
//echo $SQL;
@$r2 = pg_query($con, $SQL);
@$n2 = pg_num_rows($r2);
?>
<script type='text/javascript' src='plugin/jquery.js'></script>
<script type='text/javascript' src='plugin/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='plugin/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='plugin/thickbox-compressed.js'></script>
<script type='text/javascript' src='plugin/jquery.autocomplete.js'></script>
<script type='text/javascript' src='plugin/localdata.js'></script>
<script type='text/javascript' src='plugin/jquery.ui.core.js'></script>
<script type='text/javascript' src='plugin/jquery.ui.datepicker.js'></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"/>
<link rel="stylesheet" type="text/css" href="plugin/jquery.autocomplete.css"/>

<script>
    $(document).ready(function(){
        $("#AUTOTEXT_OBAT").autocomplete("lib/get_obat.php", {
            width: 260,
            selectFirst: false
        });
        
    });
</script>

<table width="100%" style="border: solid 1px #000;">
    <tr>
        <td class="TBL_HEAD" align="center">NO.</td>
        <td class="TBL_HEAD" align="center">NAMA OBAT</td>
        <td class="TBL_HEAD" align="center">SATUAN</td>
        <td class="TBL_HEAD" align="center">QTY AWAL</td>
        <td class="TBL_HEAD" align="center">QTY PENERIMAAN</td>
        <td class="TBL_HEAD" align="center">QTY TRANSFER IN</td>
        <td class="TBL_HEAD" align="center">QTY TRANSFER OUT</td>
        <td class="TBL_HEAD" align="center">QTY RETUR SUPLIER</td>
        <td class="TBL_HEAD" align="center">QTY ADJUST</td>
        <td class="TBL_HEAD" align="center">QTY AKHIR</td>
    </tr>

    <?php
    $i = 0;
    while (@$row2 = pg_fetch_array($r2)) {
        $i++;
        ?>
        <tr>
            <td class="TBL_BODY" ><?php echo $i; ?></td>
            <td class="TBL_BODY"><?php echo $row2['obat']; ?></td>
            <td class="TBL_BODY"><?php echo $row2['satuan']; ?></td>
            <td class="TBL_BODY"><?php echo $row2['qty_awal']; ?></td>
            <td class="TBL_BODY"><?php echo $row2['qty_penerimaan']; ?></td>
            <td class="TBL_BODY"><?php echo $row2['qty_transfer_in']; ?></td>
            <td class="TBL_BODY"><?php echo $row2['qty_transfer_out']; ?></td>
            <td class="TBL_BODY"><?php echo $row2['qty_retur_suplier']; ?></td>
            <td class="TBL_BODY"><?php echo $row2['qty_adjustment']; ?></td>
            <td class="TBL_BODY"><?php echo $row2['qty_akhir']; ?></td>
        </tr>
        <?php
    }
    echo '</table>';
    ?>