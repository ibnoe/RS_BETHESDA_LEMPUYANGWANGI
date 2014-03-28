<?php
// sfdn, 24-12-2006
session_start();

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/setting.php");
require_once("../lib/terbilang.php");


$ROWS_PER_PAGE = 999999;
//$RS_NAME           = $set_header[0]."<br>".$set_header[1];
//$RS_ALAMAT         = $set_header[2]."<br>".$set_header[3].$set_header[4];

if ($_GET["kas"] == "rj") {
    $jenisKwitansi =  "KWITANSI RINCIAN APOTEK RAWAT JALAN";
} elseif ($_GET["kas"] == "ri") {
    $jenisKwitansi = "KWITANSI RINCIAN APOTEK RAWAT INAP";
} else {
    $jenisKwitansi = "KWITANSI RINCIAN APOTEK IGD";
}
?>

<HTML>

    <HEAD>
        <TITLE></TITLE>
        <!--<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>-->
        <LINK rel='styleSheet' type='text/css' href='../invoice.css'>
       	<LINK rel='styleSheet' type='text/css' href='../cetak.css'>
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
	
	<table align=center >
    <tr>
        <td align="center" colspan="4" style="font-family: Tahoma; font-size: 18px; letter-spacing: 4px;"><b><?php echo $jenisKwitansi ?></b></u></td>
    </tr>

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

// Agung Sunandar Baru 14:38 14/06/2012

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
                    "   case when a.rujukan = 'Y' then 'Rujukan' else 'Non-Rujukan' end as datang " .
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
    
    //========= Kwitansi
if ($_GET[kas]=="rj"){
$ksr1 = "BYR";
}elseif ($_GET[kas]=="ri"){
$ksr1 = "BYI";
}elseif ($_GET[kas]=="igd"){
$ksr1 = "BYD";
}
$kwitansi=getFromTable("select max(new_id) from rs00005 where reg='$reg' and layanan != 'BATAL' and kasir='$ksr1' ");

//==========
    ?>
    <!--    <div class="wrapper">-->
    <!--        <table class="header">
                <tbody>
                    <tr>
                        <td nowrap="nowrap" width="50%">

                        </td>
                        <td align="center" width="50%">

                        </td>
                    </tr>
                </tbody>
            </table>-->

    <table cellpadding="0" cellspacing="0" class="items"><tbody><tr>
                

                <td width="100%">
                    
                        <table class="none" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="add-bold"><font size="1"  face="Tahoma">No. Kwitansi</td>
                                <td class="add-bold"><font size="1"  face="Tahoma">:</td>
                                <td class="add-bold"><font size="1"  face="Tahoma"><?php echo $kwitansi;?></td>
                            </tr>
                            <tr>
                                <td class="add-bold"><font size="1"  face="Tahoma">No. Reg</td>
                                <td class="add-bold"><font size="1"  face="Tahoma">:</td>
                                <td class="add-bold"><font size="1"  face="Tahoma"><?php echo $dt->id;?></td>
                            </tr>
                            <tr>
                                <td><font size="1"  face="Tahoma">Nama Pasien</td>
                                <td><font size="1"  face="Tahoma">:</td>
                                <td><font size="1"  face="Tahoma"><?php echo $dt->nama; ?></td>
                            </tr>
                            
                            <!--<tr>
                                <td><font size="1"  face="Tahoma">No. Reg.</td>
                                <td><font size="1"  face="Tahoma">:</td>
                                <td><font size="1"  face="Tahoma"><? //echo $dt->id; ?></td>
                            </tr>-->
                            <tr>
                                <td><font size="1"  face="Tahoma"><?php echo $rawatan; ?></td>
                                <td><font size="1"  face="Tahoma">:</td>
                                <td><font size="1"  face="Tahoma"><?php echo $poli; ?></td>
                            </tr>
                        </table>
                   
                </td></tr></tbody></table>
                
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
		                <td valign=top class="TITLE_SIM3"><b><font size="1"  face="Tahoma"><?php echo $pembayar1 ?></font></b></td>
		
		            </tr>
		            <?
		            $rrs = pg_query($con,
		                    "select sum(jumlah) as jumlah from rs00005 " .
		                    "where kasir in ('BYR','BYI','BYD') and " .
		                    "	to_number(reg,'999999999999') = '$reg' "); //and ".
		            //"	referensi IN ('KASIR')");
		
		            while ($dds = pg_fetch_object($rrs)) {
		                ?>
		
		                <tr>
		                    <td valign=top width=30% class="TITLE_SIM3"><b><font size="1"  face="Tahoma">UANG SEJUMLAH</font></b></td>
		                    <td valign=top class="TITLE_SIM3"><b><font size="1"  face="Tahoma">:</font></b></td>
		                    <td valign=top  class="TITLE_SIM3"><b><font size="1"  face="Tahoma">Rp. <?php echo number_format($dds->jumlah,
		                        2) ?></font></b></td>
		
		                </tr><tr>
		                    <td valign=top class="TITLE_SIM3"><b></b></td>
		                    <td valign=top class="TITLE_SIM3"><b></b></td>
		                </tr>
					    <?php
					    $y = terbilang($dds->jumlah);
					    ?>
					                <!--<table>
					                <tr>
					    <td valign=top class="TITLE_SIM3"><b><i><font size="1"  face="Tahoma"><?php // $y = terbilang($dds->jumlah);
					    //echo strtoupper($y); 
					    ?> RUPIAH</font></i></b></td>
					    </tr>
					        </table>-->
					    <?
					}
					pg_free_result($rrs);
					$y = terbilang($dds->jumlah);
					?>
		        </table>
    
    <table style="width: 100% !important;" cellpadding="0" cellspacing="0" border=1>
        <tr>
            <td align="center"><b><font size="1"  face="Tahoma">Rincian Pembayaran <?php echo $kasir; ?></b></font></td>
        </tr>
    </table>
    <?
//        title("Rincian Layanan/Tindakan Medis");
    echo "<br>";

// RIncian Layanan Tindakan
    $r = pg_query($con,
                    "select distinct trans_group, trans_form, tanggal_trans " .
                    "from rs00008 " .
                    "where no_reg = '$reg' " .
                    "and trans_type in ('LTM') " .
                    "order by trans_group");
    ?>
    <table width="100%"  class="items" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                
                <td colspan="2"><font size="1"  face="Tahoma">DESCRIPTION</td>
                <td width="20%"><font size="1"  face="Tahoma">JUMLAH</td>
                <td width="10%"><font size="1"  face="Tahoma">TAGIHAN</td>
            </tr>      
        <?
        
// Pembelian Obat
            $rec3 = getFromTable("select count(id) from rs00008 " .
                            "where trans_type = 'OB1' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");


            if ($rec3 > 0) {
                $sqlf = "select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans,
        obat, qty ||' '|| c.tdesc as qty, sum(tagihan) as tagihan, pembayaran, trans_group, d.tdesc as kategori, a.trans_form 
        from rs00008 a, rs00015 b, rs00001 c, rs00001 d 
        where to_number(a.item_id,'999999999999') = b.id  
        and b.satuan_id = c.tc and a.trans_type = 'OB1' 
        and c.tt = 'SAT' 
        and b.kategori_id = d.tc and d.tt = 'GOB' 
        and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'
        group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form ";
                @$r5 = pg_query($con, $sqlf);
                @$n5 = pg_num_rows($r5);

                $max_row5 = 200;
                $mulai5 = $HTTP_GET_VARS["rec"];
                if (!$mulai5) {
                    $mulai5 = 1;
                }
            ?>
                <tr class="items">
                    <td  align="center">&nbsp;</td>
                    <td  align="left"><b><u><font size="1"  face="Tahoma">RINCIAN OBAT APOTEK</u></b></td>
                    <td  align="center">&nbsp;</td>
                    <td  align="center">&nbsp;</td>
                    <td  align="right">&nbsp;</td>

                </tr>
            <?
                $row5 = 0;
                $tagihan5 = 0;
                $i5 = 1;
                $j5 = 1;
                $last_id5 = 1;
                while (@$row5 = pg_fetch_array($r5)) {
                    if (($j5 <= $max_row5) AND ($i5 >= $mulai5)) {
                        $no5 = $i5;
            ?>
                        <tr>
                            
                            <td  align="left"><font size="1"  face="Tahoma"><?php echo $row5["obat"] ?></td>
                            <td  align="right">&nbsp;</td>
                            <td  align="left"><font size="1"  face="Tahoma"><?php echo $row5["qty"] ?></td>
                            <td  align="right"><font size="1"  face="Tahoma"><?php echo number_format($row5["tagihan"], 2, ",", ".") ?></td>

                        </tr>
            <?
                        $tagihan5 = $tagihan5 + $row5["tagihan"];

                        ;
                        $j5++;
                    }

                    $i5++;
                }
            ?>
            <?
            }


            ///Batas Pembelian Obat
// Pembelian Obat Racikan
            $rec4 = getFromTable("select count(id) from rs00008 " .
                            "where trans_type = 'RCK' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");


            if ($rec4 > 0) {
                $sqlf = "select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans,
        obat, qty ||' '|| c.tdesc as qty, sum(tagihan) as tagihan, pembayaran, trans_group, d.tdesc as kategori, a.trans_form 
        from rs00008 a, rs00015 b, rs00001 c, rs00001 d 
        where to_number(a.item_id,'999999999999') = b.id  
        and b.satuan_id = c.tc and a.trans_type = 'RCK' 
        and c.tt = 'SAT' 
        and b.kategori_id = d.tc and d.tt = 'GOB' 
        and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'
        group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form ";
                @$r6 = pg_query($con, $sqlf);
                @$n6 = pg_num_rows($r6);

                $max_row6 = 200;
                $mulai6 = $HTTP_GET_VARS["rec"];
                if (!$mulai6) {
                    $mulai6 = 1;
                }
            ?>
                <tr>
                    <td class="TBL_BODY2" align="center">&nbsp;</td>
                    <td class="TBL_BODY2" align="left"><font size="1"  face="Tahoma"><b><u>OBAT APOTEK</b></u></td>
                    <td class="TBL_BODY2" align="center">&nbsp;</td>
                    <td class="TBL_BODY2" align="center">&nbsp;</td>
                    <td class="TBL_BODY2" align="right">&nbsp;</td>

                </tr>
            <?
                $row6 = 0;
                $tagihan6 = 0;
                $i6 = 1;
                $j6 = 1;
                $last_id6 = 1;
                while (@$row6 = pg_fetch_array($r6)) {
                    if (($j6 <= $max_row6) AND ($i6 >= $mulai6)) {
                        $no6 = $i6;
            ?>
                        <tr>
                           
                            <td class="TBL_BODY2" align="left"><font size="1"  face="Tahoma"><?php echo $row6["obat"] ?></td>
                            <td  align="right">&nbsp;</td>
                            <td class="TBL_BODY2" align="left"><font size="1"  face="Tahoma"><?php echo $row6["qty"] ?></td>
                            <td class="TBL_BODY2" align="right"><font size="1"  face="Tahoma"><?php echo number_format($row6["tagihan"], 2, ",", ".") ?></td>

                        </tr>
            <?
                        $tagihan6 = $tagihan6 + $row6["tagihan"];

                        ;
                        $j6++;
                    }

                    $i6++;
                }
             }
///Batas Pembelian Obat Racikan
            ?>
                        
        <?
            $r2 = pg_query($con, "select * from rsv0012 where id = '$reg'");
            $d2 = pg_fetch_object($r2);
            $bayar = getFromTable("select sum(jumlah) from rs00005 where reg='$reg' and kasir in ('BYR','BYD','BYI') and layanan != 'DEPOSIT' ");
            $askes = getFromTable("select sum(jumlah) from rs00005 where reg='$reg' and kasir in ('ASK') ");
            $potongan = getFromTable("select sum(jumlah) from rs00005 where reg='$reg' and kasir in ('POT') ");
            $cek_deposit = getFromTable("select sum(jumlah) from rs00005 where reg='$reg' and layanan='DEPOSIT' and kasir in ('BYR','BYD','BYI') ");
            $total=$tagihan5 + $tagihan6;
			
			//--pembulatan
			$totalPembulatan = pembulatan($total);
			$pembulatan = $totalPembulatan - $total;
			//--
            ?>

            <tr>
                <td class="TBL_BODY2" align="right" colspan="3"><b><font size="1"  face="Tahoma">TOTAL TAGIHAN :</b></td>
                <td class="TBL_BODY2" align="right"><b><font size="1"  face="Tahoma"><?php echo number_format($total, 2, ",", ".") ?></b></td>
            </tr class="title">
            
            <tr>
                <td class="TBL_BODY2" align="right" colspan="3"><b><font size="1"  face="Tahoma">PEMBULATAN :</b></td>
                <td class="TBL_BODY2" align="right"><b><font size="1"  face="Tahoma"><?php echo number_format($pembulatan, 2, ",", ".") ?></b></td>
            </tr class="title">
            
            <tr>
                <td class="TBL_BODY2" align="right" colspan="3"><b><font size="1"  face="Tahoma">TOTAL :</b></td>
                <td class="TBL_BODY2" align="right"><b><font size="1"  face="Tahoma"><?php echo number_format($totalPembulatan, 2, ",", ".") ?></b></td>
            </tr class="title">

        </tbody>
    </table>
    <?
//======================================================================
//include("cetak.inc.php");


            echo "\n<script language='JavaScript'>\n";
            echo "function cetakaja(tag) {\n";
            echo "    sWin = window.open('index2.php?tag=' + tag, 'xWin'," .
            " 'width=500,height=400,menubar=no,scrollbars=yes');\n";
            echo "    sWin.focus();\n";
            echo "}\n";
            echo "</script>\n";
            $tgl_sekarang = date("d M Y", time());
			
			echo "<table>";
	        echo "<td valign=top class='TITLE_SIM3'><b><i><font size='2'  face='Tahoma'>";
	        $y = terbilang($totalPembulatan);
	        echo strtoupper($y);
	        echo "RUPIAH</font></i></b></td>";
	        echo "</tr>";
	        echo "</table>";
    ?>
            <table border="0" align="right" width="50%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" class="TITLE_SIM3"><font size="1"  face="Tahoma"><?php echo $client_city; ?>, <?php echo $tgl_sekarang; ?></td>

                </tr>
                <tr>
                    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
                </tr>
                <tr>
                    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
                </tr>
                <tr>

                    <td align="center" class="TITLE_SIM3"><u><font size="2"  face="Tahoma"><?php echo $_SESSION["nama_usr"]; ?></u></td>
        </tr>
    </table>
    <table border="0" align="left" width="50%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>

        </tr>
        <tr>
            <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
        </tr>
        <tr>
            <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
        </tr>
        <tr>

            <td align="center" class="TITLE_SIM3"><u><font size="2"  face="Tahoma"><?php echo $set_header[0]?></u></td>
        </tr>
        <tr>
            <td align="center" class="TITLE_SIM3"><u><font size="2"  face="Tahoma"><?php echo $set_header[1]?></u></td>
        </tr>
    </table>

    <SCRIPT LANGUAGE="JavaScript">
        <!-- Begin
        printWindow();
        //  End -->
    </script>

</body>
</html>
