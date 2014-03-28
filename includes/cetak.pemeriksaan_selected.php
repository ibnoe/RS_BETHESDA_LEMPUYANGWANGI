<?php
session_start();
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/setting.php");

   $RS_NAME           = "LABORATORIUM ".$set_header[0]."<br>".$set_header[1];
$RS_ALAMAT         = $set_header[2]."<br>".$set_header[3].$set_header[4];
?>

<HTML>

    <HEAD>
        <TITLE>::: Sistem Informasi :::</TITLE>
        <LINK rel='styleSheet' type='text/css' href='../invoice.css'>
        <SCRIPT LANGUAGE="JavaScript">
            <!-- Begin
            function printWindow() {
                bV = parseInt(navigator.appVersion);
                if (bV >= 4) window.print();
            }
            //  End -->
        </script>


    </HEAD>

    </HEAD>

    <BODY TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 />
	<!--START KOP KWITANSI -->
	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 14px; letter-spacing: 0px;">
		<tr valign="middle" >
			<td rowspan="2" align="center">
			<img width="100px" height="100px" src="../<?=$set_client_logo?>" style="margin-left:15px;margin-top:5px;" align="left"/>
			<font color=white>
				<div style="font-family: arial; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px;">&nbsp</div>
			    <div style="font-family: arial; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold">Laboratorium <?=$set_header[0]?></div>
				<div style="font-family: arial; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[2]?></div>
				<div style="font-family: arial; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[3]?></div>
			</font>
		</tr>		
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 2px; letter-spacing: 0px;">
	    <tr>
	        <td align="left" style='border-top:solid 0px #000;border-bottom:solid 2px #000;'>&nbsp;</td>
	    </tr>
	    <tr>
	        <td align="left" style='border-top:solid 2px #000;border-bottom:solid 0px #000;'>&nbsp;</td>
	    </tr>
	</table>
	<!--END KOP KWITANSI -->
    <?
    $reg            = $_GET["rg"];
    $tgl_sekarang   = date("d-m-Y H:i:s", time());
    $tgl_now        = date("d-m-Y", time());
    $noUrut = 0;

    if ($reg > 0) {
        if (getFromTable("select to_number(id,'9999999999') as id " .
                        "from rs00006 " .
                        "where id = '$reg' " .
                        " ") == 0) {
            //"and status = 'A'") == 0) {
            $reg = 0;
            $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
        }
    }
	pg_query("update c_catatan set tgl_terima=CURRENT_DATE where no_reg='$_GET[rg]' and tgl_terima is null");
$rt = pg_query($con,"select a.*,(b.nama)as jawab,(j.nama)as periksa,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,
				(h.nama)as pengirim,(i.nama)as operator,f.nama as nm_pasien,f.mr_no,f.alm_tetap, f.kota_tetap,g.tdesc as poli_asal, age(d.tanggal_reg::timestamp with time zone, f.tgl_lahir::timestamp with time zone) AS umur, d.rawat_inap ,case when f.jenis_kelamin='L' then 'Laki-laki' else 'Perempuan' end as jk, z.tdesc as tipe, tanggal(CURRENT_DATE,0) as tgl_cetak,(CURRENT_TIME) as wkt
						from c_visit a 
						left join rs00017 b on a.id_dokter = B.ID 
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00002 f on f.mr_no=d.mr_no
						left join rs00017 h on h.id = a.id_dokter2
                        left join rs00017 i on i.id = a.id_perawat1
						left join rs00001 g on g.tc_poli = d.poli and g.tt ='LYN'
						left join rs00017 j on j.id = a.id_perawat 
						left join rs00001 z on z.tc = d.tipe and z.tt ='JEP'
						where a.no_reg='$reg' and a.id_poli ='203'");


    $nt = pg_num_rows($rt);
    if ($nt > 0)
        $dt = pg_fetch_object($rt);
    pg_free_result($rt);



    $r12 = pg_query($con,
                    "select a.id, a.ts_check_in::date, e.bangsal, d.bangsal as ruangan, b.bangsal as bed, " .
                    "    c.tdesc as klasifikasi_tarif, " .
                    "    extract(day from a.ts_calc_stop - a.ts_calc_start) as qty, " .
                    "    d.harga as harga_satuan, " .
                    "    extract(day from a.ts_calc_stop - a.ts_calc_start) * d.harga as harga, " .
                    "    a.ts_calc_stop " .
                    "from rs00010 as a " .
                    "    join rs00012 as b on a.bangsal_id = b.id " .
                    "    join rs00012 as d on substr(b.hierarchy,1,6) || '000000000' = d.hierarchy " .
                    "    join rs00012 as e on substr(b.hierarchy,1,3) || '000000000000' = e.hierarchy " .
                    "    join rs00001 as c on d.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' " .
                    "where to_number(a.no_reg,'9999999999') = $reg and ts_calc_stop is not null");


    $nt1 = pg_num_rows($r12);
    if ($nt1 > 0)
        $dt1 = pg_fetch_object($r12);
    pg_free_result($r12);

$pelayanan=$dt->rawat_inap;

    if ($pelayanan=='Y') {
        $kasir = ("Kasir Rawat Jalan");
        $rawatan = "Poli Pendaftaran";
        $poli = $dt->poli_asal;
    } elseif ($pelayanan=='I') {
        $kasir = ("Kasir Rawat Inap");
        $rawatan = "Bangsal";
        $poli = $dt1->bangsal . " / " . $dt1->ruangan . " / " . $dt1->bed . " / " . $dt1->klasifikasi_tarif;
    } else {
        $kasir = ("Kasir IGD");
        $rawatan = "Ruang";
        $poli = "IGD";
    }

    $BirthDate=$dt->umur;
    function CalculateAge($BirthDate) {
    list($Year, $Month, $Day) = explode("-", $BirthDate);
     
    $YearDiff = date("Y") - $Year;
    $MonthDiff = date("m") - $Month;
    $DayDiff = date("d") - $Day;
     
    if (date("m") < $Month || (date("m") == $Month && date("d") < $DayDiff)) {
    $YearDiff--;
    }
    return $YearDiff;
    }

$rowsPemeriksaan      = pg_query($con, "select 					a.id,a.no_reg,a.tanggal_entry,a.waktu_entry,a.item_id, b.jenis,b.parameter,a.hasil,b.satuan,b.rentang_normal,a.keterangan 
		  from c_catatan a
		  left join c_pemeriksaan_lab b on a.item_id = b.id
		  where a.no_reg ='".$_GET['rg']."' and a.id_poli = '203' order by id asc");
	$tgl_terima = getFromTable("select tanggal(tgl_terima,0) from c_catatan where no_reg='$_GET[rg]'");

	
    ?>
<table cellpadding="0" cellspacing="0" class="items"><tbody><tr>
                

                <td width="50%">
                    
                        <table class="none" cellpadding="0" cellspacing="0">
                            <tr>
                                <td ><font size="1.5"  face="Arial">Nomor MR</td>
                                <td ><font size="1.5"  face="Arial">:</td>
                                <td ><font size="1.5"  face="Arial"><? echo $dt->mr_no;?></td>
                            </tr>
			    <tr>
                                <td><font size="1.5"  face="Arial">No. Reg.</td>
                                <td><font size="1.5"  face="Arial">:</td>
                                <td><font size="1.5"  face="Arial"><B><? echo $dt->no_reg; ?></B></td>
                            </tr>
                            <tr>
                                <td><font size="1.5"  face="Arial">Nama Pasien</td>
                                <td><font size="1.5"  face="Arial">:</td>
                                <td><font size="1.5"  face="Arial"><B><? echo $dt->nm_pasien; ?></B></td>
                            </tr>
                            <tr>
                                <td><font size="1.5"  face="Arial">Jenis Kelamin</td>
                                <td><font size="1.5"  face="Arial">:</td>
                                <td><font size="1.5"  face="Arial"><? echo $dt->jk; ?></td>
                            </tr>
			    <tr>
                                <td><font size="1.5"  face="Arial">Alamat</td>
                                <td><font size="1.5"  face="Arial">:</td>
                                <td><font size="1.5"  face="Arial"><? echo $dt->alm_tetap . "," . $dt->kota_tetap; ?></td>
                            </tr>  
                        </table>
			<td width="50%">
			 <table class="none" cellpadding="0" cellspacing="0"> 
                            <tr>
                                <td><font size="1.5"  face="Arial">Umur</td>
                                <td><font size="1.5"  face="Arial">:</td>
                                <td><font size="1.5"  face="Arial"><B><? echo $dt->umur; ?></B></td>
                            </tr>
			    <tr>
                                <td><font size="1.5"  face="Arial"><? echo $rawatan; ?></td>
                                <td><font size="1.5"  face="Arial">:</td>
                                <td><font size="1.5"  face="Arial"><B><? echo $poli; ?></B></td>
                            </tr>

			    <tr>
                                <td><font size="1.5"  face="Arial">Tanggal Terima</td>
                                <td><font size="1.5"  face="Arial">:</td>
                                <td><font size="1.5"  face="Arial"><?echo $tgl_terima; ?></td>
                            </tr><tr>
                                <td><font size="1.5"  face="Arial">Tanggal Cetak</td>
                                <td><font size="1.5"  face="Arial">:</td>
                                <td><font size="1.5"  face="Arial"><?echo $dt->tgl_cetak; ?></td>
                            </tr>
                            <tr>
                                <td><font size="1.5"  face="Arial"><B>Dokter Pengirim</B></td>
                                <td><font size="1.5"  face="Arial">:</td>
                                <td><font size="1.5"  face="Arial"><B><? echo $dt->pengirim; ?></B></td>
                            </tr>
			    
			   </td>
                        </table>
                   
                </td></tr></tbody></table>
    <table width="100%"  cellpadding="2" cellspacing="0" BORDER=1 CLASS=TBL_BORDER>
        <tbody>
            <tr>  
                <td class="TBL_HEAD"><font size="1"  face="Arial" ><B><center>NAMA PEMERIKSAAN</center></B></td>
		<td width="15%" class="TBL_HEAD"><font size="1"  face="Arial"><B><center>HASIL</center></B></td>
		<td class="TBL_HEAD"><font size="1"  face="Arial"><B><center>RENTANG NORMAL</center></B></td>
                <td width="10%" class="TBL_HEAD"><font size="1"  face="Arial"><B><center>SATUAN</center></B></td>
		<td width="15%" class="TBL_HEAD"><font size="1"  face="Arial"><B><center>KETERANGAN</center></B></td>
            </tr>
<?php
if(pg_num_rows($rowsPemeriksaan) > 0){

        $iData          = 0;
        $iObat          = 0;
            
            
            $sqlf = "select a.id,a.no_reg,a.tanggal_entry,a.waktu_entry,a.item_id,b.hierarchy, b.jenis,b.parameter,a.hasil,b.satuan,b.rentang_normal,b.jenis,a.keterangan 
		  from c_catatan a
		  left join c_pemeriksaan_lab b on a.item_id = b.id
		  where a.no_reg ='".$_GET['rg']."' and a.id_poli = '203' order by id asc";
		  
//echo $sqlf;
                @$r5 = pg_query($con, $sqlf);
                @$n5 = pg_num_rows($r5);

	    	$max_row5 = $n5;
                $mulai5 = $HTTP_GET_VARS["rec"];
                if (!$mulai5) {
                    $mulai5 = 1;
                }
		$row5 = 0;
                $i5 = 1;
                $j5 = 1;
                $last_id5 = 1;
			
while (@$row5 = pg_fetch_array($r5)) {//O3
			//echo substr($row5["hierarchy"],0,3);
			$hierarchy = substr($row5["hierarchy"],0,3);
			@$r6 = pg_query($con, "select parameter from c_pemeriksaan_lab where is_group='Y' and hierarchy like '$hierarchy%' ");
            @$n6 = pg_num_rows($r6);
			$row6 = pg_fetch_array($r6);
	    $noUrut++;
            $iData++;
		if($_GET['cetak_'.$iData] != ''){
                $iObat++;
                    if (($j5 <= $max_row5) AND ($i5 >= $mulai5)) {//O4
                        $no5 = $i5;
			$newjenis=$row6["parameter"];
			
			if ($oldjenis==$row6["parameter"]&&$oldjenis!='')
			{$newjenis='';}
            ?><? if ($oldjenis==$row6["parameter"]&&$oldjenis!='')
			{//O5?><tr>
			    <td  align="left"><font size="1"  face="Arial"><?= $row5["parameter"] ?></td>
                <td  align="center"><font size="1"  face="Arial"><B><?= $row5["hasil"] ?></B></td>
			    <td  align="center"><font size="1"  face="Arial"><?= $row5["rentang_normal"]?></td>
			    <td  align="center"><font size="1"  face="Arial"><?= $row5["satuan"]?></td>
			    <td  align="center"><font size="1"  face="Arial"><?= $row5["keterangan"]?></td>
                        </tr><?//C5
			}else{ //O6 ?>
                        <tr>
                            <td  colspan="5" align="left" bgcolor="grey"><font size="2"  face="Arial"><b><?= $newjenis ?><b></td>
                        </tr>
			<tr>
			    <td  align="left"><font size="1"  face="Arial"><?= $row5["parameter"] ?></td>
                <td  align="center"><font size="1"  face="Arial"><B><?= $row5["hasil"] ?></B></td>
			    <td  align="center"><font size="1"  face="Arial"><?= $row5["rentang_normal"]?></td>
			    <td  align="center"><font size="1"  face="Arial"><?= $row5["satuan"]?></td>
			    <td  align="center"><font size="1"  face="Arial"><?= $row5["keterangan"]?></td>
                        </tr>
            <?}//C7
                        ;
                        $j5++;
			$oldjenis=$row6["parameter"];
                    }//C4
}
                    $i5++;
                }//C3
}
?>
    <?php
//if(pg_num_rows($rowsPemakaianRacikan) > 0){   
//    echo '<tr id="list_racikan_to_print"><td class="" colspan="6"><span style="font-weight: bold;"><br/>Obat Racikan</span></td></tr>';
//        $iRacikan       = 0;
//        while($rowRacikan=pg_fetch_array($rowsPemakaianRacikan)){
//            
//            $noUrut++;
//            $iData++;
//            
//            $sqlObatR = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
//                                        FROM rs00015 
//                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
//                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
//                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $rowRacikan["item_id"] );
//            $obatR = pg_fetch_array($sqlObatR);
//            if($_GET['cetak_'.$iData] != ''){
//                $iRacikan++;
//                $total          = $total + $rowRacikan["tagihan"];
//                $totalPenjamin  = $totalPenjamin + $rowRacikan["dibayar_penjamin"];
//                $totalSelisih   = $totalSelisih + ($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]);
//                $arrWaktuEntry2 = explode('.', $rowRacikan["waktu_entry"]);
                $arrJamEntry2 = explode(':', $arrWaktuEntry2[0]);                    
?>
<!--    <tr>
         <td class="" align="left" height="15" ><?=tanggal($rowRacikan["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry2[0].':'.$arrJamEntry2[1]?></td> 
        <td class="" align="left" height="15"><?=$noUrut?></td>
        <td class="" align="left" height="15"><?=$obatR["obat"]?></td>
        <td class="" align="right" height="15" style="text-align: right;"><?=$rowRacikan["qty"]?> <? //=$obatR["satuan"]?></td>
        <td class="" align="right" height="15"><?=number_format($rowRacikan["tagihan"],'0','','.')?></td>
        <td class="" align="right" height="15"><?=number_format($rowRacikan["dibayar_penjamin"],'0','','.')?></td>
        <td class="" align="right" height="15"><?=number_format(($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]),'0','','.')?>&nbsp;</td>
    </tr>-->
 
<?php
//            }
//        }
//}
?>        
<table width="100%">
<tr>
                                <td><font size="1"  face="Arial"><B>Dokter Penanggung Jawab</B></td>
                                <td><font size="1"  face="Arial">&nbsp;&nbsp;&nbsp;</td>
                                <td><font size="1"  face="Arial">&nbsp;&nbsp;&nbsp;</td>
                                <td><font size="1"  face="Arial">&nbsp;&nbsp;&nbsp;</td>
                                <td><font size="1"  face="Arial">&nbsp;&nbsp;&nbsp;</td>
                                <td><font size="1"  face="Arial"><B>Pemeriksa</B></td>
                            </tr>
<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
<tr>
                                <td><font size="1"  face="Arial"><B>( <? echo $dt->jawab;?> )</B></td>
                                 <td><font size="1"  face="Arial">&nbsp;&nbsp;&nbsp;</td>
                                <td><font size="1"  face="Arial">&nbsp;&nbsp;&nbsp;</td>
                                <td><font size="1"  face="Arial">&nbsp;&nbsp;&nbsp;</td>
                                <td><font size="1"  face="Arial">&nbsp;&nbsp;&nbsp;</td>
                                <td><font size="1"  face="Arial"><B>( <? echo $dt->periksa;?> )</B></td>
                            </tr>
			    <tr>
                                <td><font size="1"  face="Arial"><?echo gmdate("H:i:s", time()+60*60*7); ?></td>
                            </tr>
			    </table>

<SCRIPT LANGUAGE="JavaScript">
    printWindow();
</script>

</body>
</html>
<?php
if($iObat == 0){
    echo '<script>';
    echo '$("#list_obat_to_print").remove();';
    echo '</script>';
}
if($iRacikan == 0){
    echo '<script>';
    echo '$("#list_racikan_to_print").remove();';
    echo '</script>';
}
    
function tanggal($tanggal) {
        $arrTanggal = explode('-', $tanggal);

        $hari = $arrTanggal[2];
        $bulan = $arrTanggal[1];
        $tahun = $arrTanggal[0];

        $result = $hari . ' ' . bulan($bulan) . ' ' . $tahun;

        return $result;
    }

function bulan($params) {
    switch ($params) {
        case 1:
            $bln = "Jan";
            break;
        case 2:
            $bln = "Peb";
            break;
        case 3:
            $bln = "Mar";
            break;
        case 4:
            $bln = "Apr";
            break;
        case 5:
            $bln = "Mei";
            break;
        case 6:
            $bln = "Jun";
            break;
        case 7:
            $bln = "Jul";
            break;
        case 8:
            $bln = "Agu";
            break;
        case 9:
            $bln = "Sep";
            break;
        case 10:
            $bln = "Okt";
            break;
        case 11:
            $bln = "Nop";
            break;
        case 12:
            $bln = "Des";
            break;
            break;
    }
    return $bln;
}
?>
