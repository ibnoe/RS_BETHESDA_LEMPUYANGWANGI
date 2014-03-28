<?php
session_start();
require_once("lib/dbconn.php");
require_once("startup.php");
require_once("lib/form.php");

$tglDari    = date('Y-m-d');
$tglSampai  = date('Y-m-d');

if($_GET['tgl_dari'] != ''){
    $tglDari    = $_GET['tgl_dari'];
}

if($_GET['tgl_sampai'] != ''){
    $tglSampai    = $_GET['tgl_sampai'];
}

?>

<h2>Laporan Pendapatan Rawat Inap</h2>
<?php 
	if(!$GLOBALS['print']) {	

?>
<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.datepicker.css">
<form action="#" method="post" id="table_form" class="">
	<input type="hidden" name="tgl_dari" id="tgl_dari" value="<?php echo $tglDari?>"  /> &nbsp; 
	<input type="hidden" name="tgl_sampai" id="tgl_sampai" value="<?php echo $tglSampai?>"  />
        <table>
            <tr>
                <td>
                    Mulai Tgl</td>
                <td>
                    <input type="text" name="tgl_dari_show" id="tgl_dari_show" size="25" value="<?php echo tanggal($tglDari)?>"  /> &nbsp;&nbsp;
                    Sampai Tgl : <input type="text" name="tgl_sampai_show" id="tgl_sampai_show" size="25" value="<?php echo tanggal($tglSampai)?>"  />  
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button id="btn_filter" type="button">Tampilkan</button></td>
            </tr>
        </table>
</form>   
<?php

	title_print("");
	title_excel("lap_pendapatan_rwi&edit=excel&tgl_dari=".$tglDari."&tgl_sampai=".$tglSampai."");

 }
	elseif($_GET['edit']=='excel' ) {
	?>
	<form action="#"  id="table_form" class="">
	    <table>
            <tr>
                <td>
                    Mulai Tgl  : <?php echo tanggal($_GET['tgl_dari']); $tglDari=$_GET['tgl_dari'];?></td>
                </tr>
                <tr>
				<td>
                    Sampai Tgl : <?php echo tanggal($_GET['tgl_sampai']);$tglSampai=$_GET['tgl_sampai'];?> 
                </td>
            </tr>
        </table>
</form>   
	<?php
	}
	else{
		?>
	<form action="#"  id="table_form" class="">
	    <table>
            <tr>
                <td>
                    Mulai Tgl  : <?php echo tanggal($_GET['tgl_dari']); $tglDari=$_GET['tgl_dari'];?></td>
                 </tr>
                <tr>
				<td>
				
                    Sampai Tgl : <?php echo tanggal($_GET['tgl_sampai']);$tglSampai=$_GET['tgl_sampai'];?> 
                </td>
            </tr>
        </table>
</form>   
	<?php
	
	
	}

	
$rowsPasien = pg_query($con, "select to_char(min(a.ts_check_in),'dd Mon YYYY') as tgl_masuk, f.mr_no, f.nama, f.id, (select to_char(max(ts_calc_stop),'dd Mon yyyy')as tgl_keluar from rs00010 where a.no_reg=no_reg and id=(select max(id) from rs00010 where no_reg =a.no_reg) ) as check_out, d.bangsal, f.poli_desc as pemeriksaan,
(SELECT sum(e.tagihan) AS sum  FROM rs00034 aa, rs00008 e  WHERE e.item_id::text!='-'::text and to_number(e.item_id::text,'999999999999')::text=aa.id::text and e.no_reg::text =f.id::text AND e.trans_type::text = 'LTM'::text AND E.referensi != 'P' ) AS total_layanan,
(SELECT sum(e.tagihan) AS sum FROM rs00008_return e WHERE e.no_reg::text =f.id::text AND (e.trans_type::text = 'OB1'::text OR e.trans_type::text = 'OB1'::text )) AS retur,
(SELECT sum(e.tagihan) AS sum FROM rs00015 b, rs00008 e  WHERE   e.item_id::text!='-'::text and to_number(e.item_id::text,'999999999999')::text=b.id::text and e.no_reg::text =f.id::text AND e.trans_type::text = 'BHP'::text) AS bhp,
(SELECT sum(ff.tagihan) AS sum FROM rs00008 ff WHERE ff.no_reg::text = f.id::text AND ff.trans_type::text = 'OB1'::text) AS obat, 
(SELECT sum(fff.tagihan) AS sum FROM rs00008 fff WHERE fff.no_reg::text = f.id::text AND fff.trans_type::text = 'RCK'::text) AS racikan, 
(SELECT sum((extract(day from case when aaa.ts_calc_stop is null then current_timestamp else aaa.ts_calc_stop end - aaa.ts_calc_start) + case WHEN
	abs(extract(hour from aaa.ts_calc_stop-aaa.ts_check_in)) >=12 then 1 else 0 end )*ff.harga)  FROM rs00012 aa, rs00010 aaa, rs00012 ff  WHERE substr(aa.hierarchy,1,6)||'000000000' = ff.hierarchy and aaa.bangsal_id::text=aa.id::text and aaa.no_reg::text = f.id::text ) AS tarif_inap,
(SELECT sum(eeee.dibayar_penjamin) as SUM FROM rs00008 eeee WHERE eeee.no_reg::text = f.id::text) as penjamin 
from rs00010 a 
join rsv_pasien2 f on a.no_reg=f.id 
join rs00012 as b on a.bangsal_id = b.id 
join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
join rs00001 as g on f.poli = g.tc_poli and g.tt = 'LYN' 
join c_visit_ri c on a.no_reg=c.no_reg 
where c.id_ri= 'E05' and (a.ts_check_in between '".$tglDari."' AND '".$tglSampai."') group by f.mr_no,f.id,f.nama,d.bangsal,c.no_reg,a.no_reg,g.tdesc, f.poli_desc, a.ts_check_in order by a.ts_check_in" );
?>
<table id="list-pasien" width="100%">
        <tr>
            <td rowspan="1" class="TBL_HEAD" align="center" width="3%">NO.</td>
            <td rowspan="1" class="TBL_HEAD" align="center" width="25%">TANGGAL MASUK</td>
			<td rowspan="1" class="TBL_HEAD" align="center" width="25%">TANGGAL KELUAR</td>
            <td rowspan="1" class="TBL_HEAD" align="center" width="10%">NO. REG</td>
            <td rowspan="1" class="TBL_HEAD" align="center" width="10%">NO. MR</td>
            <td rowspan="1" class="TBL_HEAD" align="center" width="25%">NAMA</td>
            <td rowspan="1" class="TBL_HEAD" align="center" width="15%">BANGSAL</td>
            <td rowspan="1" class="TBL_HEAD" align="center" width="45%">POLI MASUK</td>
			<td rowspan="1" class="TBL_HEAD" align="center" width="25%">TOTAL LAYANAN</td>
			<td rowspan="1" class="TBL_HEAD" align="center" width="25%">RETUR OBAT</td>
		    <td rowspan="1" class="TBL_HEAD" align="center" width="25%">BHP</td>
            <td rowspan="1" class="TBL_HEAD" align="center" width="25%">OBAT</td>
			<td rowspan="1" class="TBL_HEAD" align="center" width="25%">RACIKAN</td>
			<td rowspan="1" class="TBL_HEAD" align="center" width="25%">TARIF INAP</td>
			<td rowspan="1" class="TBL_HEAD" align="center" width="25%">SUB TOTAL</td>
			<td rowspan="1" class="TBL_HEAD" align="center" width="25%">PENJAMIN</td>
            <td rowspan="1" class="TBL_HEAD" align="center" width="25%">TOTAL TAGIHAN</td>
        </tr>
        <?php
            if(!empty($rowsPasien)){
                 $i=0;
				 $tot1=0;
				 $tot2=0;
				 $tot3=0;
				 $tot4=0;
				 $tot5=0;
				 $tot6=0;
				 $tot7=0;
				 $tot8=0;
				 $tot9=0;
				 
                 while($row=pg_fetch_array($rowsPasien)){
                    $i++;?>
					<tr>
					<td class="TBL_BODY"><?php echo $i?></td>
					<td class="TBL_BODY"><?php echo $row['tgl_masuk']?></td>
					<td class="TBL_BODY"><?php echo $row['check_out']?></td>
					<td class="TBL_BODY"><?php echo $row['id']?></td>
					<td class="TBL_BODY"><?php echo $row['mr_no']?></td>
					<td class="TBL_BODY"><?php echo $row['nama']?></td>
					<td class="TBL_BODY"><?php echo $row['bangsal']?></td>
					<td class="TBL_BODY"><?php echo $row['pemeriksaan']?></td>
					<td class="TBL_BODY" align="right" id="val_bhp_<?php echo $i ?>"><?php echo number_format($row["total_layanan"],0," ",  ".");$tot8=$tot8+ $row["total_layanan"];?> </td>
					<td class="TBL_BODY" align="right" id="val_bhp_<?php echo $i ?>"><?php echo number_format($row["retur"],0," ",  ".");$tot9=$tot9+ $row["retur"];?> </td>
					<td class="TBL_BODY" align="right" id="val_bhp_<?php echo $i ?>"><?php echo number_format($row["bhp"],0," ",  ".");$tot1=$tot1+ $row["bhp"];?> </td>
					<td class="TBL_BODY" align="right" id="val_obat_<?php echo $i ?>"><?php echo number_format($row["obat"],0," ",  ".");$tot2=$tot2+ $row["obat"]; ?> </td>
					<td class="TBL_BODY" align="right" id="val_racikan_<?php echo $i ?>"><?php echo number_format($row["racikan"],0," ",  ".");$tot3=$tot3+ $row["racikan"]; ?> </td>
					<td class="TBL_BODY" align="right" id="val_tarif_inap_<?php echo $i ?>"><?php echo number_format($row["tarif_inap"],0," ",  ".");$tot4=$tot4+ $row["tarif_inap"]; ?> </td>
					<td class="TBL_BODY" align="right" id="val_subtotal_<?php echo $i ?>"><?php echo number_format((($row["bhp"]+$row["obat"]+ $row["racikan"]+$row["tarif_inap"]+ $row["total_layanan"])-$row["retur"]),0," ",  ".") ;
					$subtotal=($row["bhp"]+$row["obat"]+ $row["racikan"]+$row["tarif_inap"]+ $row["total_layanan"])-$row["retur"];
					$tot5=$tot5+ (($row["bhp"]+$row["obat"]+ $row["racikan"]+$row["tarif_inap"]+ $row["subtotal"])-$row["penjamin"]);?> </td>
					<td class="TBL_BODY" align="right" id="val_penjamin_<?php echo $i ?>"><?php echo number_format($row["penjamin"],0," ",  ".");$tot6=$tot6+ $row["penjamin"]; ?> </td>
					<td class="TBL_BODY" align="right" id="val_sisa_<?php echo $i ?>"><?php echo number_format(($subtotal-$row["penjamin"]),0," ",  ".");
					$tot7=$tot7+ ($tot5-$row["penjamin"]); ?> </td>
					<?php
                 }
            }
        ?>
		
    </tr>
	<tr>
        <td colspan="8" class="TBL_HEAD" align="right">J U M L A H</td>
		<td class="TBL_HEAD" align="right"><?php echo number_format($tot8,0," ",  ".");?></td>
		<td class="TBL_HEAD" align="right"><?php echo number_format($tot9,0," ",  ".");?></td>
        <td class="TBL_HEAD" align="right"><?php echo number_format($tot1,0," ",  ".");?></td>
        <td class="TBL_HEAD" align="right"><?php echo number_format($tot2,0," ",  ".");?></td>
        <td class="TBL_HEAD" align="right"><?php echo number_format($tot3,0," ",  ".");?></td>
        <td class="TBL_HEAD" align="right"><?php echo number_format($tot4,0," ",  ".");?></td>
        <td class="TBL_HEAD" align="right"><?php echo number_format($tot5,0," ",  ".");?></td>
        <td class="TBL_HEAD" align="right"><?php echo number_format($tot6,0," ",  ".");?></td>
        <td class="TBL_HEAD" align="right"><?php echo number_format($tot7,0," ",  ".");?></td>
    </tr>
</table>
<script language="JavaScript" type="text/JavaScript">

$(document).ready(function() { 

	$("#tgl_dari_show").datepicker({
		showOn: 'button', 
		buttonImage: 'images/calendar.gif', 
		dateFormat: 'd MM yy',
		buttonImageOnly: true,
		altField: '#tgl_dari', altFormat: 'yy-mm-dd'
	});

	$("#tgl_sampai_show").datepicker({
		showOn: 'button', 
		buttonImage: 'images/calendar.gif', 
		dateFormat: 'd MM yy',
		buttonImageOnly: true,
		altField: '#tgl_sampai', altFormat: 'yy-mm-dd'
	});

	$("#btn_filter").click(function(){
		var tglDari         = $("#tgl_dari").val();
		var tglSampai       = $("#tgl_sampai").val();
		var tipePasienId    = $("#tipe_pasien_id").val();
		var unitId          = $("#unit_id").val();
		var dokter          = $("#dokter").val();
		window.location = 'index2.php?p=lap_pendapatan_rwi&tgl_dari='+tglDari+'&tgl_sampai='+tglSampai;
	
	});

	$("#btn_print").click(function(){
		$("#laporan-transaksi").printElement();

	});
	$("#btn_download").click(function(){
		var tglDari		= $("#tgl_dari").val();
		var tglSampai	= $("#tgl_sampai").val();
		window.location = '<? //=base_url()?>index.php/laporan/transaksi/index/'+tglDari+'/'+tglSampai+'/false/true';
	});
});

//    totalVisite		= 0;
    totalBhp		= 0;
    totalObat	= 0;
    totalRacikan	= 0;
//    totalKonsultasi	= 0;
    totalTarifInap		= 0;
    totalSubTotal	= 0;
    totalPenjamin= 0;
    totalSisa	= 0;
    for(i=1;i<=<?php echo $i ?>;i++){
		bhpTmp = $('#val_bhp_'+i).text();
        bhp = parseInt(bhpTmp.replace('.',''));
        totalBhp = totalBhp+bhp;
		
        obatTmp = $('#val_obat_'+i).text();
        obat = parseInt(obatTmp.replace('.',''));
        totalObat = totalObat+obat;

        racikanTmp = $('#val_racikan_'+i).text();
        racikan = parseInt(racikanTmp.replace('.',''));
        totalRacikan = totalRacikan+racikan;

        tarifInapTmp = $('#val_tarif_inap_'+i).text();
        tarifInap = parseInt(tarifInapTmp.replace('.',''));
        totalTarifInap = totalTarifInap+tarifInap;

        subtotalTmp = $('#val_subtotal_'+i).text();
        subtotal = parseInt(subtotalTmp.replace('.',''));
        totalSubTotal = totalSubTotal+subtotal;

        
        sisaTmp = $('#val_sisa_'+i).text();
        sisa = parseInt(sisaTmp.replace('.',''));
        totalSisa = totalSisa+sisa;
		penjaminTmp = $('#val_penjamin_'+i).text();
        penjamin = parseInt(penjaminTmp.replace('.',''));
        totalPenjamin= totalPenjamin+penjamin;

    }

//    $('#jumlah_visite').text(totalVisite);
    $('#jumlah_bhp').text(totalBhp);
    $('#jumlah_obat').text(totalObat);
    $('#jumlah_racikan').text(totalRacikan);
//    $('#jumlah_konsultasi').text(totalKonsultasi);
    $('#jumlah_tarifinap').text(totalTarifInap);
    $('#jumlah_subtotal').text(totalSubTotal);
    $('#jumlah_penjamin').text(totalPenjamin);
    $('#jumlah_sisa').text(totalSisa);

</script>
<?php
function tanggal($tanggal) {
    $arrTanggal = explode('-', $tanggal);

    $hari = $arrTanggal[2];
    $bulan = $arrTanggal[1];
    $tahun = $arrTanggal[0];

    $result = $hari . ' ' . bulan($bulan) . ' ' . $tahun;

    return $result;
}

function tanggalShort($tanggal) {
    $arrTanggal = explode('-', $tanggal);

    $hari = $arrTanggal[2];
    $bulan = $arrTanggal[1];
    $tahun = $arrTanggal[0];

    $result = $hari . ' ' . substr(bulan($bulan),0,3) . ' ' . $tahun;

    return $result;
}

function bulan($params) {
    switch ($params) {
        case 1:
            $bln = "Januari";
            break;
        case 2:
            $bln = "Pebruari";
            break;
        case 3:
            $bln = "Maret";
            break;
        case 4:
            $bln = "April";
            break;
        case 5:
            $bln = "Mei";
            break;
        case 6:
            $bln = "Juni";
            break;
        case 7:
            $bln = "Juli";
            break;
        case 8:
            $bln = "Agustus";
            break;
        case 9:
            $bln = "September";
            break;
        case 10:
            $bln = "Oktober";
            break;
        case 11:
            $bln = "Nopember";
            break;
        case 12:
            $bln = "Desember";
            break;
            break;
    }
    return $bln;
}
?>