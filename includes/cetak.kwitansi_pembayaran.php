<?php
session_start();

require_once("../lib/setting.php");
require_once("../lib/terbilang.php");

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
?>
<HTML>
    <HEAD>
        <TITLE></TITLE>
        <!--<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>-->
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
    
    <!--START KOP KWITANSI -->
	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 14px; letter-spacing: 2px;">
		<tr valign="middle" >
			<td rowspan="2" align="center"><!--<img width="70px" height="70px" src="../images/logo_kotakab_sragen.png" align="left"/>-->
			<font color=white>
				<div style="font-family: Tahoma; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px;">&nbsp</div>
			    <div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[0]?></div>
				<div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[2]?></div>
				<div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[3]?></div>
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

    <?
    $reg = $_GET["rg"];
    $rt = pg_query($con,
            "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, " .
            "    a.mr_no, upper(e.nama)as nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, " .
            "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, " .
            "    upper(e.alm_tetap)as alm_tetap, upper(e.kota_tetap)as kota_tetap, e.umur, e.pos_tetap, e.tlp_tetap, " .
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


    $nt = pg_num_rows($rt);
    if ($nt > 0)
        $dt = pg_fetch_object($rt);
    pg_free_result($rt);

    if ($reg) {
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
            "where to_number(a.no_reg,'9999999999') = $reg and ts_calc_stop is not null");



    $nt1 = pg_num_rows($r12);
    if ($nt1 > 0)
        $dt1 = pg_fetch_object($r12);
    pg_free_result($r12);

    if ($_GET["kas"] == "rj") {
        $kwitansi = 'RJ - '.$reg;
        $poli = $dt->poli;
    } elseif ($_GET["kas"] == "ri") {
        $kwitansi = 'RI - '.$reg;
        $poli = $dt1->bangsal . " / " . $dt1->ruangan . " / " . $dt1->bed . " / " . $dt1->klasifikasi_tarif;
    } else {
        $kwitansi = 'RJ - '.$reg;
        $poli = "IGD";
    }
    ?>
    <table cellpadding="0" cellspacing="0" class="items">
        <tbody>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><b>  <font size="1"><?php
    if ($_GET["kas"] == "rj") {
        echo "KWITANSI PEMBAYARAN PENJAMIN RAWAT JALAN";
    } elseif ($_GET["kas"] == "ri") {
        echo "KWITANSI PEMBAYARAN PENJAMIN RAWAT INAP";
    } else {
        echo "KWITANSI PEMBAYARAN PENJAMIN IGD";
    }
    ?> </b></td>
            </tr>
        </table>
        <?
        //$tgl_sekarang = date("d M Y",time());
	$tgl_sekarang = getFromTable("SELECT tanggal(tgl_entry,3) FROM rs00005 WHERE reg='".$_GET['rg']."' AND kasir IN ('BYR', 'BYI') AND is_bayar = 'Y'");
        ?>
        <table border="0" class="none" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <table border="0" class="none" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="add-bold"><font size="1"  face="Tahoma">NO. KWITANSI</font></td>
                            <td class="add-bold"><font size="1"  face="Tahoma">:</font></td>
                            <td class="add-bold"><font size="1"  face="Tahoma"><? echo $kwitansi; ?></font></td>
                        </tr>
                      <!--<tr>
                        <td><font size="1"  face="Tahoma">NO. REG.</font></td>
                        <td><font size="1"  face="Tahoma">:</font></td>
                        <td><font size="1"  face="Tahoma"><? // echo $dt->id;  ?></font></td>
                        </tr>-->
                        <tr>
                            <td><font size="1"  face="Tahoma">NAMA PASIEN</font></td>
                            <td><font size="1"  face="Tahoma">:</font></td>
                            <td><font size="1"  face="Tahoma"><? echo $dt->nama; ?></font></td>
                        </tr>
                        <tr>
                            <td><font size="1"  face="Tahoma">ALAMAT</font></td>
                            <td><font size="1"  face="Tahoma">:</font></td>
                            <td><font size="1"  face="Tahoma" ><? echo $dt->alm_tetap . " " . $dt->kota_tetap; ?></font></td>
                        </tr>
                    </table>
                </td>
                <td width="200">&nbsp;</td>
                <td>
                    <table border="0" class="none" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="add-bold"><font size="1"  face="Tahoma"><? echo $tgl_sekarang; ?></font>
                        </tr>
                        <tr>
                            <td><font size="1"  face="Tahoma"><? echo $poli; ?></font>
                        </tr>
                        <tr>
                            <td><font size="1"  face="Tahoma">PENJAMIN:<? echo $dt->tipe_desc; ?></font>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table></td>
            </tr>
        </table>


        <?php
        $pembayar = getFromTable("select max(bayar) as jumlah from rs00005 " .
                "where kasir in ('BYR','BYI','BYD') and " .
                "to_number(reg,'999999999999') = '$reg' ");
        if ($pembayar == '') {
            $pembayar1 = $dt->nama;
        } else {
            $pembayar1 = $pembayar;
        }
        ?>
        <br>
        <table border="0" width=100% cellpadding="0" cellspacing="0">
            <tr>
                <td valign=top width=35% class="TITLE_SIM3"><b><font size="1"  face="Tahoma">SUDAH TERIMA DARI </font></b></td>
                <td valign=top class="TITLE_SIM3"><b><font size="1"  face="Tahoma">:</font></b></td>
                <td valign=top class="TITLE_SIM3"><b><font size="1"  face="Tahoma"><?= $pembayar1 ?></font></b></td>

            </tr>
            <?
            $rrs = pg_query($con,
                    "select sum(jumlah) as jumlah from rs00005 " .
                    "where kasir in ('BYR','BYI','BYD') and reg  = '".$reg."'"); //and ".
					
			$cashPembayaran = getFromTable( "select sum(cash_pembayaran) as cash_pembayaran from rs00005 " .
                    "where kasir in ('BYR','BYI','BYD') and reg  = '".$reg."'");
			$cashPengembalian = getFromTable( "select sum(cash_pengembalian) as cash_pengembalian from rs00005 " .
                    "where kasir in ('BYR','BYI','BYD') and reg  = '".$reg."'");
			

            while ($dds = pg_fetch_object($rrs)) {
                $jumlah = $dds->jumlah;
				
				//--pembulatan
				$totalPembulatan = pembulatan($jumlah);
				$pembulatan = $totalPembulatan - $jumlah;
				//--
                ?>

                <tr>
                    <td valign=top width=30% class="TITLE_SIM3"><b><font size="1"  face="Tahoma">UANG SEJUMLAH</font></b></td>
                    <td valign=top class="TITLE_SIM3"><b><font size="1"  face="Tahoma">:</font></b></td>
                    <td valign=top  class="TITLE_SIM3"><b><font size="1"  face="Tahoma">Rp. <?= number_format($jumlah,
                        2) ?></font></b></td>

                </tr>
                <tr>
                    <td valign=top width=30% class="TITLE_SIM3"><b><font size="1"  face="Tahoma">PEMBULATAN</font></b></td>
                    <td valign=top class="TITLE_SIM3"><b><font size="1"  face="Tahoma">:</font></b></td>
                    <td valign=top  class="TITLE_SIM3"><b><font size="1"  face="Tahoma">Rp. <?= number_format($pembulatan,
                        2) ?></font></b></td>

                </tr>
                <tr>
                    <td valign=top width=30% class="TITLE_SIM3"><b><font size="1"  face="Tahoma">&nbsp;</font></b></td>
                    <td valign=top class="TITLE_SIM3"><b><font size="1"  face="Tahoma">&nbsp;</font></b></td>
                    <td valign=top  class="TITLE_SIM3"><b><font size="1"  face="Tahoma">&nbsp;</font></b></td>

                </tr>
				<tr>
                    <td valign=top width=30% class="TITLE_SIM3"><b><font size="2"  face="Tahoma">TOTAL</font></b></td>
                    <td valign=top class="TITLE_SIM3"><b><font size="2"  face="Tahoma">:</font></b></td>
                    <td valign=top  class="TITLE_SIM3"><b><font size="2"  face="Tahoma">Rp. <?= number_format($totalPembulatan,
                        2) ?></font></b></td>

                </tr>
				<tr>
                    <td valign=top width=30% class="TITLE_SIM3"><b><font size="2"  face="Tahoma">TUNAI</font></b></td>
                    <td valign=top class="TITLE_SIM3"><b><font size="2"  face="Tahoma">:</font></b></td>
                    <td valign=top  class="TITLE_SIM3"><b><font size="2"  face="Tahoma">Rp. <?= number_format($cashPembayaran,
                        2) ?></font></b></td>

                </tr>
				<tr>
                    <td valign=top width=30% class="TITLE_SIM3"><b><font size="2"  face="Tahoma">KEMBALI</font></b></td>
                    <td valign=top class="TITLE_SIM3"><b><font size="2"  face="Tahoma">:</font></b></td>
                    <td valign=top  class="TITLE_SIM3"><b><font size="2"  face="Tahoma">Rp. <?= number_format($cashPengembalian,
                        2) ?></font></b></td>

                </tr>
                <tr>
                    <td valign=top class="TITLE_SIM3"><b></b></td>
                    <td valign=top class="TITLE_SIM3"><b></b></td>
                </tr>
    <?php
    $y = terbilang($totalPembulatan);
}
pg_free_result($rrs);
$y = terbilang($dds->jumlah);
?>


        </table>
        <table border="0" align="right" width="50%" cellpadding="0" cellspacing="0">
<?php
        echo "\n<script language='JavaScript'>\n";
        echo "function cetakrincian(tag) {\n";
        echo "    sWin = window.open('index2.php?tag=' + tag, 'xWin'," .
        " 'width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
        $tgl_sekarang = date("d M Y",
                time());

        echo "<br/>";
        echo "<table>";
        echo "<td valign=top class='TITLE_SIM3'><b><i><font size='2'  face='Tahoma'>";
        $y = terbilang($totalPembulatan);
        echo strtoupper($y);
        echo "RUPIAH</font></i></b></td>";
        echo "</tr>";
        echo "</table>";
        ?>
            <br/>
            <br/>
        <table border="0" align="right" width="50%" cellpadding="0" cellspacing="0">

            <tr>
                <td align="right" class="TITLE_SIM3"><u><b><font size="2" face="Tahoma"><? echo $_SESSION["nama_usr"]; ?></b></u></td>
            </tr>
        </table>

        <SCRIPT LANGUAGE="JavaScript">
            <!-- Begin
            printWindow();
            //  End -->
        </script>

    </body>
</html>
