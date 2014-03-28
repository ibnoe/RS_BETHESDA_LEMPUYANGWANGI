<?php
session_start();
require_once("../startup.php");
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/setting.php");

$ROWS_PER_PAGE = 999999;

$_GET["mPOLI"]=$setting_poli["laboratorium"];
$reg = $_GET["rg"];
    ?>
   
<HTML>
    <HEAD>
        <TITLE>Hasil Pemeriksaan Laboratorium</TITLE>
        <LINK rel='styleSheet' type='text/css' href='../cetak.css'>
        <LINK rel='styleSheet' type='text/css' href='../invoice.css'>
        <SCRIPT LANGUAGE="JavaScript">
            <!-- Begin
            function printWindow() {
            bV = parseInt(navigator.appVersion);
            if (bV >= 4) window.print();
            }
        </script>
    </HEAD>

<BODY TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 />
<table align=center >
    <tr>
        <td height="225">&nbsp;</td>
    </tr>
    <tr>
        <!--<td align="center" colspan="4" style="font-family: Tahoma; font-size: 18px; letter-spacing: 4px;"><b><U>HASIL PEMERIKSAAN LABORATORIUM</b></u></td>-->
    </tr>
</table>
<?
 /*$rt = pg_query($con,
                    "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, " .
                    "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, " .
                    "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, " .
                    "    e.alm_tetap, e.kota_tetap, e.umur, e.pos_tetap, e.tlp_tetap, " .
                    "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, " .
                    "    c.tdesc AS penjamin, a.no_jaminan,a.no_asuransi ,a.rujukan, a.rujukan_rs_id, " .
                    "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, " .
                    "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara," .
                    "    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, " .
                    "        CASE " .
                    "            WHEN a.rawat_inap = 'I' THEN 'Rawat Inap' " .
                    "            WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan' " .
                    "            ELSE 'IGD' " .
                    "        END AS rawat, " .
                    "        age(a.tanggal_reg , e.tgl_lahir ) AS umur, " .
                    "	case when a.rujukan = 'Y' then 'Rujukan' else 'Non-Rujukan' end as datang " .
                    "    , i.tdesc as poli,e.pangkat_gol,e.nrp_nip,e.kesatuan " .
                    "FROM rs00006 a " .
                    "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'" .
                    "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' " .
                    "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no " .
                    "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' " .
                    "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' " .
                    "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' " .
                    "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc AND h.tt = 'JDP' " .
                    "   left join rs00001 i on i.tc_poli = a.poli " .
                    "WHERE a.id = '$reg'  ");
    $nt = pg_num_rows($rt);
    if ($nt > 0)
        $dt = pg_fetch_object($rt);
    pg_free_result($rt);*/


$rt = pg_query($con,"select a.*,(b.nama)as periksa,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,
				(h.nama)as pengirim,(i.nama)as operator,f.nama as nm_pasien,f.mr_no,f.alm_tetap, f.kota_tetap,g.tdesc as poli_asal,
				case when f.jenis_kelamin='L' then 'Laki-laki' else 'Perempuan' end as jk, z.tdesc as tipe, tanggal(CURRENT_DATE,0) as tgl_cetak, (CURRENT_TIME) as wkt
						from c_visit a 
						left join rs00017 b on a.id_dokter = B.ID 
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00002 f on f.mr_no=d.mr_no
						left join rs00017 h on h.id = a.id_dokter2
                        left join rs00017 i on i.id = a.id_perawat1
						left join rs00001 g on g.tc_poli = d.poli and g.tt ='LYN'
						left join rs00001 z on z.tc = d.tipe and z.tt ='JEP'
						where a.no_reg='$reg' and a.id_poli ='203'");

				$nt = pg_num_rows($rt);
    				if ($nt > 0)
				$dt = pg_fetch_object($rt);
				pg_free_result($rt);

    if ($reg > 0) {
        if (getFromTable("select to_number(id,'9999999999') as id " .
                        "from rs00006 " .
                        "where id = '$reg' " .
                        " ") == 0) {
            $reg = 0;
            $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
        }
    }

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
                    "where to_number(a.no_reg,'9999999999') = '$reg' and ts_calc_stop is not null");


    $nt1 = pg_num_rows($r12);
    if ($nt1 > 0)
        $dt1 = pg_fetch_object($r12);
    pg_free_result($r12);
?>
<br/>
    <table cellpadding="0" cellspacing="0" class="items">
        <tbody>
            <tr>
                

                <td width="65%">
                        <table class="none">
                            <tr>
                                <td>Nomor MR</td>
                                <td>:</td>
                                <td><? echo $dt->mr_no; ?></td>
                            </tr>
			    <tr>
                                <td>No. Reg.</td>
                                <td>:</td>
                                <td><? echo $dt->no_reg; ?></td>
                            </tr>
                            <tr>
                                <td>Nama Pasien</td>
                                <td>:</td>
                                <td><? echo $dt->nm_pasien; ?></td>
                            </tr>
			    <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td><? echo $dt->jk; ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td><? echo $dt->alm_tetap . "," . $dt->kota_tetap; ?></td>
                            </tr>
			    <tr>
			    <td>
			    <br />
			    </td>
			    </tr>
			    <tr>
                                <td>Unit Asal</td>
                                <td>:</td>
                                <td><? echo $dt->poli_asal; ?></td>
                            </tr>
			    <tr>
                                <td>Tanggal Cetak</td>
                                <td>:</td>
                                <td><?echo $dt->tgl_cetak; ?> - <?echo $dt->wkt; ?></td>
                            </tr>
                            <tr>
                                <td><B>Dokter Pengirim</B></td>
                                <td>:</td>
                                <td><B><? echo $dt->pengirim; ?></B></td>
                            </tr>
			    <tr>
                                <td><B>Dokter Penanggung Jawab</B></td>
                                <td>:</td>
                                <td><B><? echo $dt->periksa;?></B></td>
                            </tr>
                        </table>
                    </div>
                </td></tr></tbody></table>
<br />
<?
$sql2="select b.jenis,b.parameter,a.vis_2,b.satuan,b.rentang_normal,a.vis_3 
		  from c_catatan a
		  left join c_pemeriksaan_lab b on CAST (a.vis_1 as numeric) = b.id
		  where a.no_reg ='{$_GET['rg']}' and a.id_ri = '203' order by tanggal";

@$r1 = pg_query($con,$sql2);
			@$n1 = pg_num_rows($r1);
			
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}

?>

<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">JENIS PEMERIKSAAN</td>
				<td class="TBL_HEAD"align="center">ITEM</td>
				<td class="TBL_HEAD"align="center">HASIL</td>
				<td class="TBL_HEAD"align="center">SATUAN</td>
				<td class="TBL_HEAD"align="center">RENTANG NORMAL</td>
				<td width="10%" align="center" class="TBL_HEAD">KETERANGAN</td>
			</tr>
			
	
		<?	
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BODY" align="center"><?=$no ?> </td>
			        	<td class="TBL_BODY" align="left"><?=$row1["jenis"] ?> </td>
						<td align="left" class="TBL_BODY"><?=$row1["parameter"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["vis_2"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["satuan"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["rentang_normal"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["vis_3"] ?></td>
						
					</tr>	
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			}

?>
</TABLE>

<?

$tgl_sekarang = date("d M Y", time());
/*echo "<br>";
echo "<br>";
echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='55%'>";

echo "<tr>";
echo "<td class=TBL_BODY3 colspan=16 align=left><B><B>&nbsp;</B></B></td>";
echo "<td class=TBL_BODY3 colspan=16 align=right><B><B>Sragen,".$tgl_sekarang."&nbsp;  &nbsp; &nbsp; &nbsp;</B></B></td>";
echo "</tr>";

echo "<tr>";
echo "<td class=TBL_BODY3 colspan=16 align=left><B><B>&nbsp;</B></B></td>";
echo "<td class=TBL_BODY3 colspan=16 align=right><B><B>Yang Memeriksa &nbsp;  &nbsp; &nbsp; &nbsp;</B></B></td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY1 colspan=16 align=right><B>&nbsp;</B></td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY1 colspan=16 align=right><B>&nbsp;</B></td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY1 colspan=16 align=right><B>&nbsp;</B></td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY1 colspan=16 align=right><B>&nbsp;</B></td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY3 colspan=16 align=right><B><B>&nbsp;&nbsp;&nbsp;&nbsp;</B></B></td>";
echo "<td class=TBL_BODY3 colspan=16 align=right><B><B>&nbsp;&nbsp;(_____________________)&nbsp;&nbsp;</B></B></td>";
echo "</tr>";
echo "</table>";
echo "<br />";
echo "<br />";*/
?>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>
</body>
</html>

