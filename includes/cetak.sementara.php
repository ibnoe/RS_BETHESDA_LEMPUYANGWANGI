<?php
// sfdn, 24-12-2006
session_start();

require_once("../lib/setting.php");
require_once("../lib/terbilang.php");

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

$ROWS_PER_PAGE = 999999;
//$RS_NAME           = $set_header[0]."<br>".$set_header[1];
//$RS_ALAMAT         = $set_header[2]."<br>".$set_header[3].$set_header[4];
?>

<HTML>


    <HEAD>
        <TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
        <LINK rel='styleSheet' type='text/css' href='../cetak.css'>
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

    <BODY TOPMARGIN=1 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0 />

    <?

    $reg = $_GET["rg"];
	//echo $reg;
    $rt = pg_query($con,
                    "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, " .
                    "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, " .
                    "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, " .
                    "    e.alm_tetap, e.kota_tetap, e.umur, e.pos_tetap, e.tlp_tetap, " .
                    "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, " .
                    "    c.tdesc AS penjamin, a.no_jaminan,a.no_asuransi ,a.rujukan, a.rujukan_rs_id, " .
                    "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, " .
                    "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, " .
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
//"WHERE a.id = '$reg'");


    $nt = pg_num_rows($rt);
    if ($nt > 0)
        $dt = pg_fetch_object($rt);
    pg_free_result($rt);

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
	
if ($_GET["kas"] == "rj") {
        $kasir = ("Kasir Rawat Jalan");
        $rawatan = "Poli Pendaftaran";
        $poli = $dt->poli;
    } elseif ($_GET["kas"] == "ri") {
        $kasir = ("Kasir Rawat Inap");
        $rawatan = "Bangsal";
        $poli = $dt1->bangsal . " / " . $dt1->ruangan . " / " . $dt1->bed . " / " . $dt1->klasifikasi_tarif;
    } else {
        $kasir = ("Kasir IGD");
        $rawatan = "Ruang";
        $poli = "IGD";
    }
    ?>
    <table cellpadding="0" cellspacing="0" class="items">
        <tbody>
            <tr>
                

                <td width="60%">
                    <div class="addressbox">
                        <table class="none">
                            
                            <tr>
                                <td>Nama Pasien</td>
                                <td>:</td>
                                <td><? echo $dt->nama; ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td><? echo $dt->alm_tetap . " " . $dt->kota_tetap; ?></td>
                            </tr>
                            <tr>
                                <td>No. Reg.</td>
                                <td>:</td>
                                <td><? echo $dt->id; ?></td>
                            </tr>
                            <tr>
                                <td><? echo $rawatan;?> </td>
                                <td>:</td>
                                <td><? echo $poli; ?></td>
                            </tr>
                        </table>
                    </div>
                </td></tr></tbody></table>
    
<?php
if ($_GET[kas]=="igd")
		{
			$ksr="BYD";
			$kasir="IGD";
			$cekno=getFromTable("select (igd + 1) from reset_kwitansi where bulan='$bln' and tahun='$thn1' ");
		}elseif ($_GET[kas]=="rj")
			{
				$ksr="BYR";
				$kasir="RJ";
				$cekno=getFromTable("select (rj + 1) from reset_kwitansi where bulan='$bln' and tahun='$thn1' ");
			}elseif ($_GET[kas]=="ri")
				{
					$ksr="BYI";
					$kasir="RI";
					$cekno=getFromTable("select (ri + 1) from reset_kwitansi where bulan='$bln' and tahun='$thn1' ");
				}
				
$sql="select tgl_entry,jumlah,to_char(waktu_bayar,'hh:mi:ss') as waktu_bayar,no_kwitansi,oid from rs00005 where reg ='$reg' and kasir='$ksr' and layanan != 'BATAL' and jumlah > 0 ";//Perbaikan format time by Me
@$r1 = pg_query($con,$sql);
	@$n1 = pg_num_rows($r1);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  

	
	echo "\n<script language='JavaScript'>\n";
echo "function cetakrinciansementara(tag) {\n";
echo "    sWin = window.open('cetak.rincian.sementara.php?oid=' + tag+'&kas=".$_GET["kas"]."&rg=$reg', 'xWin',".
     " 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

?>
<br><br>
<table width="100%" border="1">
	<tr>
		<td class="TBL_HEAD" align="center" width="2%">NO</td>
		<td class="TBL_HEAD" align="center" width="15%">TANGGAL BAYAR</td>
		<td class="TBL_HEAD" align="center" width="15%">WAKTU BAYAR</td>
		<td class="TBL_HEAD" align="center" width="15%">NO.KWITANSI</td>
		<td class="TBL_HEAD" align="center" >JUMLAH</td>
		<td class="TBL_HEAD" align="center" width="10%">CETAK</td>
	</tr>
	<?	
			
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					
					$no=$i; 	
					?>		
				 	<tr valign="top" class="<??>" > 
						<td class="TBL_BODY" align="center"><?=$no ?> </td>
			        	<td class="TBL_BODY" align="left"><?=$row1["tgl_entry"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["waktu_bayar"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["no_kwitansi"] ?> </td>
						<td class="TBL_BODY" align="right">Rp. <?=number_format($row1["jumlah"],2,",",".")  ?> </td>
						<td class="TBL_BODY" align="center"><a href="javascript: cetakrinciansementara(<? echo $row1["oid"];?>)" ><img src="../images/cetak.gif" border="0"></a> </td>		
					</tr>	

					<?
					;$j++;					
				}
				$i++;		
			} ?>
</table>

   

</body>
</html>
