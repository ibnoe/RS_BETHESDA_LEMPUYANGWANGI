<?php
// sfdn, 24-12-2006
session_start();

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/setting.php");


$ROWS_PER_PAGE = 999999;
//$RS_NAME           = $set_header[0]."<br>".$set_header[1];
//$RS_ALAMAT         = $set_header[2]."<br>".$set_header[3].$set_header[4];
?>

<HTML>

    <HEAD>
        <TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
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

    <BODY TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 />
<!--
<table align=center >
    <tr>
        <td height="225">&nbsp;</td>
    </tr>
</table>
-->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 14px; letter-spacing: 2px;">
		<tr valign="middle" >
			<td rowspan="2" align="center">
			<font color=white>
				<div style="font-family: Tahoma; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px;">&nbsp</div>
			    <div style="font-family: Tahoma; font-size: 16px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[0]?></div>
			    <?php 
			    $set_header[2] = explode('-',$set_header[2]);
			    ?>
				<div style="font-family: Tahoma; font-size: 16px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[2][0]?></div>
				<div style="font-family: Tahoma; font-size: 16px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[2][1]?>-<?=$set_header[2][2]?></div>
				<div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[3]?></div>
			</font>
		</tr>			
</table>
<hr></hr>
    <?
    $reg = $_GET["rg"];
    $tglini = date("d");
    $blnini = date("m");
    $thnini = date("Y");
    $cek_deposit = getFromTable("select sum(jumlah) from rs00044 where no_reg = '" . $_GET[rg] . "'");
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

$rt = pg_query($con,"select a.*,(b.nama)as jawab,(j.nama)as periksa,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,
				(h.nama)as pengirim,(i.nama)as operator,f.nama as nm_pasien,f.mr_no,f.alm_tetap, f.kota_tetap,g.tdesc as poli_asal, (f.tgl_lahir::timestamp with time zone) AS umur, d.rawat_inap ,case when f.jenis_kelamin='L' then 'Laki-laki' else 'Perempuan' end as jk, z.tdesc as tipe, tanggal(CURRENT_DATE,0) as tgl_cetak,
				(CURRENT_TIME) as wkt
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
						left join rs00001 z on z.tc = d.tipe and z.tt ='JEP'".
						"where a.no_reg='$reg' and a.id_poli ='203'");


    $nt = pg_num_rows($rt);
    if ($nt > 0){
        $dt = pg_fetch_object($rt);
    }
    else{
    $rt = pg_query($con,"select a.id as no_reg,a.mr_no,a.nama as nm_pasien,age(a.tanggal_reg::timestamp with time zone, b.tgl_lahir::timestamp with time zone) AS umur,
	a.tanggal_reg,c.diagnosa_sementara,a.rawat_inap,b.alm_tetap,b.kota_tetap, g.tdesc as poli_asal, tanggal(CURRENT_DATE,0) as tgl_cetak,
	b.tgl_lahir::timestamp AS umur,
	CASE
            WHEN b.jenis_kelamin::text = 'L'::text THEN 'Laki-laki'::text
            ELSE 'Perempuan'::text
        END AS jk,b.pangkat_gol,b.nrp_nip,b.kesatuan,g.tdesc as tipe_desc 
        from rsv_pasien4 a  
        left join rs00002 b on b.mr_no=a.mr_no
        left join rs00006 c on c.id=a.id
        LEFT JOIN rs00001 g ON c.tipe::text = g.tc::text AND g.tt::text = 'JEP'::text
        WHERE a.id = '$reg'");
    $dt = pg_fetch_object($rt);
    }
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

    /** if($yearDiff>1000){
    $yearDiff = str_ireplace('years','tahun',$BirthDate);
    $yearDiff = str_ireplace('years','tahun',$yearDiff);
    }*/
    


    return $YearDiff;
    }

    ?>
    <table cellpadding="0" cellspacing="0" class="items"><tbody><tr>
                

                <td width="50%">
                    
                        <table class="none" cellpadding="0" cellspacing="0">
                            <tr>
                                <td ><font size="1"  face="Arial">Nomor MR</td>
                                <td ><font size="1"  face="Arial">:</td>
                                <td ><font size="1"  face="Arial"><?php echo $dt->mr_no;?></td>
                            </tr>
			    <tr>
                                <td><font size="1"  face="Arial">No. Reg.</td>
                                <td><font size="1"  face="Arial">:</td>
                                <td><font size="1"  face="Arial"><B><?php echo $dt->no_reg; ?></B></td>
                            </tr>
                            <tr>
                                <td><font size="1"  face="Arial">Nama Pasien</td>
                                <td><font size="1"  face="Arial">:</td>
                                <td><font size="1"  face="Arial"><B><?php echo $dt->nm_pasien; ?></B></td>
                            </tr>
                            <tr>
                                <td><font size="1"  face="Arial">Jenis Kelamin</td>
                                <td><font size="1"  face="Arial">:</td>
                                <td><font size="1"  face="Arial"><?php echo $dt->jk; ?></td>
                            </tr>
			    <tr>
                                <td><font size="1"  face="Arial">Alamat</td>
                                <td><font size="1"  face="Arial">:</td>
                                <td><font size="1"  face="Arial"><?php echo $dt->alm_tetap . "," . $dt->kota_tetap; ?></td>
                            </tr>  
                        </table>
			<td width="50%">
			 <table class="none" cellpadding="0" cellspacing="0"> 
                            <tr>
                                <td><font size="1"  face="Arial">Umur</td>
                                <td><font size="1"  face="Arial">:</td>
                                <td><font size="1"  face="Arial"><B><?php echo CalculateAge($BirthDate) ?>Tahun</B></td>
                            </tr>
			    <tr>
                                <td><font size="1"  face="Arial"><?php echo $rawatan; ?></td>
                                <td><font size="1"  face="Arial">:</td>
                                <td><font size="1"  face="Arial"><B><?php echo $poli; ?></B></td>
                            </tr>

			    <tr>
                                <td><font size="1"  face="Arial">Tanggal Cetak</td>
                                <td><font size="1"  face="Arial">:</td>
                                <td><font size="1"  face="Arial"><?php echo $dt->tgl_cetak; ?></td>
                            </tr>
                            <tr>
                                <td><font size="1"  face="Arial"><B>Dokter Pengirim</B></td>
                                <td><font size="1"  face="Arial">:</td>
                                <td><font size="1"  face="Arial"><B><?php echo $dt->pengirim; ?></B></td>
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
        <?
        
// Pembelian Obat
            $rec3 = getFromTable("select count(id) from c_catatan " .
                            "where to_number(no_reg,'999999999999') = $reg and is_inap!= 'I'");


            if ($rec3 > 0) {
                $sqlf = "select a.id,a.no_reg,a.tanggal_entry,a.waktu_entry,a.item_id, b.jenis,b.parameter,a.hasil,b.satuan,b.rentang_normal,b.jenis,a.keterangan 
		  from c_catatan a
		  left join c_pemeriksaan_lab b on a.item_id = b.id
		  where a.no_reg ='".$_GET['rg']."' and a.id_poli = '203' and is_inap!='I' order by id";
                @$r5 = pg_query($con, $sqlf);
                @$n5 = pg_num_rows($r5);

                $max_row5 = 200;
                $mulai5 = $HTTP_GET_VARS["rec"];
                if (!$mulai5) {
                    $mulai5 = 1;
                }
            
                $row5 = 0;
                $i5 = 1;
                $j5 = 1;
                $last_id5 = 1;
                while (@$row5 = pg_fetch_array($r5)) {
                    if (($j5 <= $max_row5) AND ($i5 >= $mulai5)) {
                        $no5 = $i5;
			$newjenis=$row5["jenis"];
			if ($oldjenis==$row5["jenis"]&&$oldjenis!='')
			{$newjenis='';}
            ?><? if ($oldjenis==$row5["jenis"]&&$oldjenis!='')
			{?><tr>
			    <td  align="left"><font size="1"  face="Arial"><?php echo $row5["parameter"] ?></td>
                            <td  align="left"><font size="1"  face="Arial"><B><?php echo  $row5["hasil"] ?></B></td>
			    <td  align="left"><font size="1"  face="Arial"><?php echo  $row5["rentang_normal"]?></td>
			    <td  align="left"><font size="1"  face="Arial"><?php echo  $row5["satuan"]?></td>
			    <td  align="left"><font size="1"  face="Arial"><?php echo  $row5["keterangan"]?></td>
                        </tr><?}else{ ?>
                        <tr>
                            <td  colspan="5" align="left" bgcolor="grey"><font size="1"  face="Arial"><?= $newjenis ?></td>
                        </tr>
			<tr>
			    <td  align="left"><font size="1"  face="Arial"><?php echo  $row5["parameter"] ?></td>
                            <td  align="left"><font size="1"  face="Arial"><B><?php echo  $row5["hasil"] ?></B></td>
			    <td  align="left"><font size="1"  face="Arial"><?php echo  $row5["rentang_normal"]?></td>
			    <td  align="left"><font size="1"  face="Arial"><?php echo  $row5["satuan"]?></td>
			    <td  align="left"><font size="1"  face="Arial"><?php echo  $row5["keterangan"]?></td>

                        </tr>
            <?}
                        ;
                        $j5++;
			$oldjenis=$row5["jenis"];
                    }

                    $i5++;
                }
            ?>
            <?
            }


            ///Batas Pembelian Obat
            ?>
        </tbody>
    </table>
<table>
	<tr>
		<td><font size="1"  face="Arial"><B>Dokter Penanggung Jawab</B></td>
        <td><font size="1"  face="Arial">:</td>
        <td><font size="1"  face="Arial"><B><?php echo  $dt->jawab;?></B></td>
    </tr>
    <tr>
        <td><font size="1"  face="Arial"><B>Pemeriksa</B></td>
        <td><font size="1"  face="Arial">:</td>
        <td><font size="1"  face="Arial"><B><?php echo  $dt->periksa;?></B></td>
    </tr>
	<tr>
		<td><font size="1"  face="Arial"><?php echo  gmdate("H:i:s", time()+60*60*7); ?></td>
    </tr>
</table>
    <?
//======================================================================
            echo "\n<script language='JavaScript'>\n";
            echo "function cetakaja(tag) {\n";
            echo "    sWin = window.open('index2.php?tag=' + tag, 'xWin'," .
            " 'width=500,height=400,menubar=no,scrollbars=yes');\n";
            echo "    sWin.focus();\n";
            echo "}\n";
            echo "</script>\n";
            $tgl_sekarang = date("d M Y", time());
    ?>


    <SCRIPT LANGUAGE="JavaScript">
        <!-- Begin
        printWindow();
        //  End -->
    </script>

</body>
</html>
