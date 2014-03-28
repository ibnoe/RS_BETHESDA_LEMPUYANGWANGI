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
//    echo "<hr>";
//    titlecashier2('');
//    titlecashier4('RS SITI KHADIJAH PEKALONGAN');
//    titlecashier1('');
//    titlecashier1('');
//    echo "<hr>";
//echo "<br>";

    $reg = $_GET["rg"];

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


	//========= Kwitansi
if ($_GET[kas]=="rj"){
$ksr1 = "BYR";
}elseif ($_GET[kas]=="ri"){
$ksr1 = "BYI";
}elseif ($_GET[kas]=="igd"){
$ksr1 = "BYD";
}
$kwitansi=getFromTable("select max(no_kwitansi) from rs00005 where oid='".$_GET[oid]."' and reg='$reg' and layanan != 'BATAL' and kasir='$ksr1' ");

//==========

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
    <!--START KOP KWITANSI -->
	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 14px; letter-spacing: 2px;">
		<tr valign="middle" >
			<td rowspan="2" align="center"><!--<img width="70px" height="70px" src="../images/logo_kotakab_sragen.png" align="left"/>-->
			<font color=white>
				<div style="font-family: Tahoma; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px;">&nbsp</div>
			    <div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?php echo $set_header[0]?></div>
				<div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?php echo $set_header[2]?></div>
				<div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?php echo $set_header[3]?></div>
			</font>
		</tr>			
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 1px; letter-spacing: 2px;">
	    <tr>
	        <td align="left" style='border-top:solid 0px #000;border-bottom:solid 2px #000;'>&nbsp;</td>
	    </tr>
	    <tr>
	        <td align="left" style='border-top:solid 2px #000;border-bottom:solid 0px #000;'>&nbsp;</td>
	    </tr>
	</table>
	<!--END KOP KWITANSI -->
	
    <table width="100%" cellpadding="5" cellspacing="5">
        <tr>
            <td align="center"><b>  <?php
                                    if ($_GET["kas"] == "rj") {
                                        echo "BUKTI TITIPAN SEMENTARA PEMBAYARAN RAWAT JALAN";
                                    } elseif ($_GET["kas"] == "ri") {
                                        echo  "BUKTI TITIPAN SEMENTARA PEMBAYARAN RAWAT INAP";
                                    } else {
                                        echo  "BUKTI TITIPAN SEMENTARA PEMBAYARAN IGD";
                                    } ?> </b></td>
        </tr>
    </table>
	<?php 
	$pembayar  = getFromTable(  "select max(bayar) from rs00005 " .
                                "where oid='".$_GET[oid]."' ");
	if ($pembayar == ''){
	$pembayar1=$dt->nama;	
	}else{
	$pembayar1=$pembayar;
	}
	?>
	
    <table border="0" width=100%>
		<tr>
            <td valign=top width=30% class="TITLE_SIM3"><b>NO REG </b></td>
            <td valign=top class="TITLE_SIM3"><b>:</b></td>
            <td valign=top class="TITLE_SIM3"><b> <?= $dt->id ?></b></td>

        </tr>
        <tr>
            <td valign=top width=30% class="TITLE_SIM3"><b>SUDAH TERIMA DARI </b></td>
            <td valign=top class="TITLE_SIM3"><b>:</b></td>
            <td valign=top class="TITLE_SIM3"><b> Tn/Ny/Sdr. <?= $pembayar1 ?></b></td>

        </tr>
        <?
                                    $rrs = pg_query($con,
                                                    "select sum(jumlah) as jumlah from rs00005 " .
                                                    "where kasir in ('BYR','BYI','BYD') and " .
                                                    "	to_number(reg,'999999999999') = '$reg' and oid='".$_GET[oid]."' "); //and ".
                                    //"	referensi IN ('KASIR')");

                                    while ($dds = pg_fetch_object($rrs)) {
        ?>

                                        <tr>
                                            <td valign=top width=30% class="TITLE_SIM3"><b>UANG SEJUMLAH</b></td>
                                            <td valign=top class="TITLE_SIM3"><b>:</b></td>
                                            <td valign=top  class="TITLE_SIM3"><b>Rp. <?= number_format($dds->jumlah, 2) ?></b></td>

                                        </tr>
										<tr>
                                            <td valign=top class="TITLE_SIM3"><b>&nbsp;</b></td>
                                            <td valign=top class="TITLE_SIM3"><b>&nbsp;</b></td>
                                            <td valign=top class="TITLE_SIM3"><b><i><?php $y = terbilang($dds->jumlah);
                                        echo strtoupper($y); ?> RUPIAH</i></b></td>
										</tr>

        <?
                                    }
                                    pg_free_result($rrs);
        ?>
									 <tr>
                                        <td valign=top class="TITLE_SIM3"><b>&nbsp;</b></td>
                                        <td valign=top class="TITLE_SIM3"><b>&nbsp;</b></td>
                                        <td valign=top class="TITLE_SIM3"><b>Untuk Pembayaran atas Pasien Tn/Ny/Sdr. <?php echo $dt->nama; ?></b></td>
                                    </tr>
		<?php
										$ToTags = pg_query($con,
                                                    "select a.mr_no as mr_no, a.id as no_reg, upper(a.nama)as nama, 
													a.tgl_reg as tgl_reg, a.asal as poli, a.pasien as tipe_pasien, a.tagih,
													case when a.sisa < 0 then a.sisa * -1 else a.sisa end, 
													to_char((select sum(z.jumlah) from rs00044 z where z.no_reg = a.id),'999,999,999.99') as deposit, a.id as no_reg, a.id as no_reg
													from rsv0012 a 
													where a.id='".$_GET[rg]."'");
									 while ($ToTag = pg_fetch_object($ToTags)) {
									 
		?>			
										<tr>
											<td valign=top width=30% class="TITLE_SIM3"><b>&nbsp; </b></td>
											<td valign=top class="TITLE_SIM3"><b>&nbsp;</b></td>
											<td valign=top class="TITLE_SIM3"><b>&nbsp;</b></td>

										</tr>
										<tr>
											<td valign=top width=30% class="TITLE_SIM3"><b>TOTAL TAGIHAN </b></td>
											<td valign=top class="TITLE_SIM3"><b>:</b></td>
											<td valign=top class="TITLE_SIM3"><b>Rp. <?= number_format($ToTag->tagih, 2) ?></b></td>

										</tr>
										<tr>
											<td valign=top width=30% class="TITLE_SIM3"><b>SISA </b></td>
											<td valign=top class="TITLE_SIM3"><b>:</b></td>
											<td valign=top class="TITLE_SIM3"><b>Rp. <?= number_format($ToTag->sisa, 2) ?></b></td>

										</tr>
		<?php
									}
                                    pg_free_result($ToTags);
		?>
									
									
                                </table><br><br>
    <?

                                    echo "\n<script language='JavaScript'>\n";
                                    echo "function cetakrincian(tag) {\n";
                                    echo "    sWin = window.open('index2.php?tag=' + tag, 'xWin'," .
                                    " 'width=500,height=400,menubar=no,scrollbars=yes');\n";
                                    echo "    sWin.focus();\n";
                                    echo "}\n";
                                    echo "</script>\n";
                                    $tgl_sekarang = date("d M Y", time());
    ?>
                                    <table border="0" align="right" width="50%">
                                        <tr>
                                            <td align="center" class="TITLE_SIM3"><b><?php echo $client_city; ?>, <?php echo $tgl_sekarang; ?></b></td>

                                        </tr>
                                        <tr>
                                            <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="TITLE_SIM3"><u><b><? echo $_SESSION["nama_usr"]; ?></b></u></td>
        </tr>
    </table>

    <SCRIPT LANGUAGE="JavaScript">
        <!-- Begin
        printWindow();
        //  End -->
    </script>

</body>
</html>
