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
    $jenisKwitansi =  "KWITANSI RINCIAN LAYANAN RAWAT JALAN";
} elseif ($_GET["kas"] == "ri") {
    $jenisKwitansi = "KWITANSI RINCIAN LAYANAN RAWAT INAP";
} else {
    $jenisKwitansi = "KWITANSI RINCIAN LAYANAN IGD";
}
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

    <BODY TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 />
    
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
                                <td><font size="1"  face="Tahoma"><?php //echo $dt->id; ?></td>
                            </tr>-->
                            <tr>
                                <td><font size="1"  face="Tahoma"><?php echo $rawatan; ?></td>
                                <td><font size="1"  face="Tahoma">:</td>
                                <td><font size="1"  face="Tahoma"><?php echo $poli; ?></td>
                            </tr>
                        </table>
                   
                </td></tr></tbody>
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
		                    "where kasir in ('BYR','BYI','BYD') and " .
		                    "	to_number(reg,'999999999999') = '$reg' "); //and ".
		            //"	referensi IN ('KASIR')");
		
		            while ($dds = pg_fetch_object($rrs)) {
		                ?>
		
		                <tr>
		                    <td valign=top width=30% class="TITLE_SIM3"><b><font size="1"  face="Tahoma">UANG SEJUMLAH</font></b></td>
		                    <td valign=top class="TITLE_SIM3"><b><font size="1"  face="Tahoma">:</font></b></td>
		                    <td valign=top  class="TITLE_SIM3"><b><font size="1"  face="Tahoma">Rp. <?= number_format($dds->jumlah,
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
            $rec = getFromTable("select count(id) from rs00008 " .
                            "where trans_type = 'LTM' and to_number(no_reg,'999999999999') = $reg and referensi = 'P'");

            if ($rec > 0) {
                $sqla = "select distinct a.is_bayar,a.id,to_char(a.tanggal_trans,'dd-mm-yyyy') as tanggal_trans,a.no_reg,b.id, upper(b.description) as description,c.nama, a.qty, a.tagihan
                        from rs00008 a
                        left join rs99996 b on to_number(a.item_id,'9999999')=b.id
                        left join rs00017 c on c.id::numeric=a.no_kwitansi
                        where a.referensi ='P' and no_reg='$reg' order by a.id ";


                @$r1 = pg_query($con, $sqla);
                @$n1 = pg_num_rows($r1);

                $max_row1 = 200;
                $mulai1 = $HTTP_GET_VARS["rec"];
                if (!$mulai1) {
                    $mulai1 = 1;
                }
            ?>


                <tr>
                    <td  align="center">&nbsp;</td>
                    <td  align="left"><b><u><font size="1"  face="Tahoma">RINCIAN LAYANAN PAKET</u></b></td>
                    <td  align="center">&nbsp;</td>
                    <td  align="center">&nbsp;</td>
                    <td  align="right">&nbsp;</td>

                </tr>
            <?
                // Line 1 Grup layanan paket
                $row1 = 0;
                $tagihan = 0;
                $i = 1;
                $j = 1;
                $last_id = 1;
                while (@$row1 = pg_fetch_array($r1)) {
                    if (($j <= $max_row1) AND ($i >= $mulai1)) {
                        $no = $i;
            ?>

                        <tr>
                            
                            <td  align="left"><b><font size="1"><font size="1"  face="Tahoma">LAYANAN PAKET <?= $row1["description"] ?></b></td>
                            <td  align="left"><b><font size="1"><font size="1"  face="Tahoma"><?= $row1["nama"] ?></b></td>
                            <td  align="left"><b><font size="1"><font size="1"  face="Tahoma"><?= $row1["qty"] ?></b></td>
                            <td  align="right"><b><font size="1"><font size="1"  face="Tahoma"><?= number_format($row1["tagihan"], 2, ",", ".") ?></b></td>

                        </tr>
            <?
                        // line 2 Rincian oaket Layanan
                        $sqlb = "select a.id as id_lay, f.id,z.preset_id, a.layanan,
                z.qty ||' '|| g.tdesc as qty, f.tagihan,  f.tanggal_trans, f.trans_group 
                from rs00034 a 
                left join rs99997 z on z.item_id=a.id and z.trans_type='LYN'
                left join rs00008 f on to_number(f.item_id,'999999999999') = z.preset_id and f.trans_type = 'LTM' and f.referensi='P'
                left join rs00001 g on a.satuan_id = g.tc and g.tt = 'SAT' 
                where z.preset_id = $row1[id] and f.no_reg='".$_GET[rg]."'
                order by  a.id ";
            ?>
                        <tr>
                            <td  align="center">&nbsp;</td>
                            <td  align="left">&nbsp;&nbsp;&nbsp;&nbsp; <font size="1"  face="Tahoma">RINCIAN LAYANAN <?= $row1["description"] ?></td>
                            <td  align="center">&nbsp;</td>
                            <td  align="center">&nbsp;</td>
                            <td  align="right">&nbsp;</td>

                        </tr>


            <?
                        @$r2 = pg_query($con, $sqlb);
                        @$n2 = pg_num_rows($r2);

                        $max_row2 = 200;
                        $mulai2 = $HTTP_GET_VARS["rec"];
                        if (!$mulai2) {
                            $mulai2 = 1;
                        }

                        $row2 = 0;
                        $i2 = 1;
                        $j2 = 1;
                        $last_id2 = 1;
                        while (@$row2 = pg_fetch_array($r2)) {
                            if (($j2 <= $max_row2) AND ($i2 >= $mulai2)) {
                                $no2 = $i2;
            ?>

                                <tr>
                                    <td  align="center">&nbsp;</td>
                                    <td  align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <font size="1"  face="Tahoma"><?= $row2["layanan"] ?></td>
                                    <td  align="center">&nbsp;</td>
                                    <td  align="left"><?= $row2["qty"] ?></td>
                                    <td  align="right">&nbsp;</td>

                                </tr>
            <?
                                ;
                                $j2++;
                            }

                            $i2++;
                        }
                        // Batas Untuk Line 2
                        // line 2 Rincian paket obat
                        $sqlc = "select z.item_id,z.preset_id,to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans,
                b.obat, z.qty ||' '|| c.tdesc as qty, sum(tagihan) as tagihan, a.pembayaran
                from rs00008 a
                left join rs99997 z on z.preset_id=to_number(a.item_id,'999999999999') and z.trans_type='OBI'
                left join rs00015 b on z.item_id = b.id  
                left join rs00001 c on b.satuan_id = c.tc and c.tt = 'SAT' 
                left join rs00001 d on b.kategori_id = d.tc and d.tt = 'GOB' 
                where to_number(a.no_reg,'999999999999')= $reg  and a.referensi = 'P' and z.preset_id = $row1[id]
                group by  z.preset_id,z.item_id,d.tdesc, a.tanggal_trans, a.id, b.obat, z.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form ";

                        @$r3 = pg_query($con, $sqlc);
                        @$n3 = pg_num_rows($r3);
            ?>
                        <tr>
                            <td  align="center">&nbsp;</td>
                            <td  align="left">&nbsp;&nbsp;&nbsp;&nbsp;<font size="1"> <font size="1"  face="Tahoma"> RINCIAN OBAT <?= $row1["description"] ?></td>
                            <td  align="left">&nbsp;</td>
                            <td  align="left">&nbsp;</td>
                            <td  align="right">&nbsp;</td>

                        </tr>
            <?
                        $max_row3 = 200;
                        $mulai3 = $HTTP_GET_VARS["rec"];
                        if (!$mulai3) {
                            $mulai3 = 1;
                        }

                        $row3 = 0;
                        $i3 = 1;
                        $j3 = 1;
                        $last_id3 = 1;
                        while (@$row3 = pg_fetch_array($r3)) {
                            if (($j3 <= $max_row3) AND ($i3 >= $mulai3)) {
                                $no3 = $i3;
            ?>

                                <tr>
                                    <td  align="center">&nbsp;</td>
                                    <td  align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-<font size="1"  face="Tahoma"><?= $row3["obat"] ?></td>
                                    <td  align="center">&nbsp;</td>
                                    <td  align="left"><font size="1"><?= $row3["qty"] ?></td>
                                    <td  align="right">&nbsp;</td>

                                </tr>
            <?
                                ;
                                $j3++;
                            }

                            $i3++;
                        }
                        // Batas Untuk Line 3

                        $tagihan = $tagihan + $row1["tagihan"];

                        ;
                        $j++;
                    }

                    $i++;
                }

                // Batas Untuk Line 1
            }

// Rincian Layanan Non Paket

            $rec1 = getFromTable("select count(id) from rs00008 " .
                            "where trans_type = 'LTM' and to_number(no_reg,'999999999999') = $reg and referensi != 'P'");

            if ($rec1 > 0) {
                $sqle = "select f.id,f.item_id, a.layanan,c.nama,
                f.qty ||' '|| g.tdesc as qty, f.tagihan,  to_char(f.tanggal_trans,'dd-mm-yyyy') as tanggal_trans, f.trans_group,f.is_bayar 
                from rs00034 a 
                left join rs00008 f on to_number(f.item_id,'999999999999') = a.id and f.trans_type = 'LTM' and f.referensi != 'P'
                left join rs00001 g on a.satuan_id = g.tc and g.tt = 'SAT' 
                left join rs00017 c on f.no_kwitansi::numeric=c.id::numeric
                where f.no_reg = '$reg' 
                order by  a.id ";
                //echo $sqle;
                @$r4 = pg_query($con, $sqle);
                @$n4 = pg_num_rows($r4);

                $max_row4 = 200;
                $mulai4 = $HTTP_GET_VARS["rec"];
                if (!$mulai4) {
                    $mulai4 = 1;
                }
            ?>
                <tr>
                    <td  align="center">&nbsp;</td>
                    <td  align="left"><b><u><font size="1"  face="Tahoma">RINCIAN LAYANAN NON PAKET</u></b></td>
                    <td  align="center">&nbsp;</td>
                    <td  align="center">&nbsp;</td>
                    <td  align="right">&nbsp;</td>

                </tr>
            <?
                $row4 = 0;
                $tagihan2 = 0;
                $i4 = 1;
                $j4 = 1;
                $last_id4 = 1;
                while (@$row4 = pg_fetch_array($r4)) {
                    if (($j4 <= $max_row4) AND ($i4 >= $mulai4)) {
                        $no4 = $i4;
            ?>
                        <tr>
                            
                            <td  align="left"><font size="1"  face="Tahoma"><?= $row4["layanan"] ?></td>
                            <td  align="left"><b><font size="1"  face="Tahoma"><?= $row4["nama"] ?></b></td>
                            <td  align="left"><font size="1"  face="Tahoma"><?= $row4["qty"] ?></td>
                            <td  align="right"><font size="1"  face="Tahoma"><?= number_format($row4["tagihan"], 2, ",", ".") ?></td>
                        </tr>
            <?
                        $tagihan2 = $tagihan2 + $row4["tagihan"];

                        ;
                        $j4++;
                    }

                    $i4++;
                }
            ?>
            <?
            }
            //Batas Layanan Non Paket
            
// Pembelian BHP
        $rec3a = getFromTable ("select count(id) from rs00008 ".
                     "where trans_type = 'BHP' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
        
        
        if ($rec3a > 0){
        $sqlfa= "select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans,  
        obat, qty ||' '|| c.tdesc as qty, sum(tagihan) as tagihan, pembayaran, trans_group, d.tdesc as kategori, a.trans_form 
        from rs00008 a, rs00015 b, rs00001 c, rs00001 d 
        where to_number(a.item_id,'999999999999') = b.id  
        and b.satuan_id = c.tc and a.trans_type = 'BHP' 
        and c.tt = 'SAT' 
        and b.kategori_id = d.tc and d.tt = 'GOB' 
        and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'
        group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form ";
        @$r5a = pg_query($con,$sqlfa);
        @$n5a = pg_num_rows($r5a);
        
        $max_row5a= 200 ;
        $mulai5a = $HTTP_GET_VARS["rec"] ;
        if (!$mulai5a){$mulai5a=1;}
        
        ?>
        <tr>
        <td class="TBL_BODY" align="center">&nbsp;</td>
        <td class="TBL_BODY" align="left"><b><u><font size="1"  face="Tahoma">RINCIAN BHP RUANGAN</u></b></td>
        <td class="TBL_BODY" align="center">&nbsp;</td>
        <td class="TBL_BODY" align="center">&nbsp;</td>
        <td class="TBL_BODY" align="right">&nbsp;</td>
        
    </tr>
        <?
        
        $row5a=0;
        $tagihan5a=0;
        $i5a= 1 ;
        $j5a= 1 ;
        $last_id5a=1;
        while (@$row5a = pg_fetch_array($r5a)){
              if (($j5a<=$max_row5a) AND ($i5a >= $mulai5a)){
              $no5=$i5a;
        ?>
        <tr>
        
            <td class="TBL_BODY" align="left"><font size="1"  face="Tahoma"><?=$row5a["obat"] ?></td>
            <td class="TBL_BODY" align="center">&nbsp;</td>
            <td class="TBL_BODY" align="left"><font size="1"  face="Tahoma"><?=$row5a["qty"] ?></td>
            <td class="TBL_BODY" align="right"><font size="1"  face="Tahoma"><?=number_format($row5a["tagihan"],2,",",".") ?></td>
            
        </tr>
        <?
        $tagihan5a=$tagihan5a+$row5a["tagihan"];         
                     
             ;$j5a++;}
             
          $i5a++;}
          ?>
        <?
        
        
        }
        
        
        ///Batas BHP

// Layanan Akomodasi (Hari Rawatan Inap)
// TAGIHAN AKOMODASI RAWAT INAP

    // Tagihan Akomodasi
$rec5 = getFromTable ("select * from rs00008 " .
                            "where trans_type = 'POS' and to_number(no_reg,'999999999999') = $reg order by id");
        
$tgl_masuk = getFromTable("select min(b.ts_check_in) from rsv_akomodasi_inap b where b.no_reg = '$reg'");   
$tgl_pos = getFromTable("select max(d.ts_calc_stop::date) from rsv_akomodasi_inap d where d.no_reg='$reg'");    

        if ($rec5 > 0){
        $sqlg= "select a.ts_calc_stop,a.no_reg, (select min(b.ts_check_in) from rsv_akomodasi_inap b where b.no_reg=a.no_reg) as tgl_masuk,a.bangsal||' - '||a.ruangan||' - '||a.bed as ruang,a.klasifikasi_tarif,
                (select sum(c.qty) from rsv_akomodasi_inap c where c.no_reg=a.no_reg) as qty, a.harga_satuan,
                (select max(d.ts_calc_stop::date) from rsv_akomodasi_inap d where d.no_reg=a.no_reg) as tgl_posting,
                (select (substring((z.ts_calc_stop::timestamp)::text,12,8))::time - (substring((z.ts_check_in::timestamp)::text,12,8))::time from rs00010 z where z.no_reg=a.no_reg and z.ts_calc_stop=a.ts_calc_stop) as jumlah_jam
                from rsv_akomodasi_inap a
                where to_number(a.no_reg,'9999999999') = $reg and a.qty > 0
                group by a.no_reg,tgl_masuk,bangsal,a.ruangan,a.bed,a.klasifikasi_tarif, a.harga_satuan,a.ts_calc_stop ";
        @$r7 = pg_query($con,$sqlg);
        @$n7 = pg_num_rows($r7);
        
        $max_row7= 200 ;
        $mulai7 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai7){$mulai7=1;}
        
        ?>
        <tr>
        <td class="TBL_BODY" align="center">&nbsp;</td>
        <td class="TBL_BODY" align="left"><b><u><font size="1"  face="Tahoma">TAGIHAN AKOMODASI <?php echo $tgl_masuk?> s/d <?php echo  $tgl_pos;?></u></b></td>
        <td class="TBL_BODY" align="center">&nbsp;</td>
        <td class="TBL_BODY" align="center">&nbsp;</td>
        <td class="TBL_BODY" align="right">&nbsp;</td>
        
    </tr>
        <?
        
        $row7=0;
        $tagihan7=0;
        $i7= 1 ;
        $j7= 1 ;
        $last_id7=1;
        @$row7 = pg_fetch_array($r7)
              
        ?>
        <tr>
            
            <td class="TBL_BODY" align="left"><font size="1"  face="Tahoma"><?=$row7["ruang"] ?></td>
            <td class="TBL_BODY" align="center">&nbsp;</td>
            <td class="TBL_BODY" align="left"><font size="1"  face="Tahoma"><?=$row7["qty"] ?> HARI</td>
            <td class="TBL_BODY" align="right"><b><font size="1"  face="Tahoma"><?=number_format($row7["qty"] * $row7["harga_satuan"],2,",",".") ?></b></td>
            
        </tr>
        <?
        $tagihan7=$row7["qty"] * $row7["harga_satuan"];      
                     
          ?>
        <?
        
        }   

            $r2 = pg_query($con, "select * from rsv0012 where id = '$reg'");
            $d2 = pg_fetch_object($r2);
            $bayar = getFromTable("select sum(jumlah) from rs00005 where reg='$reg' and kasir in ('BYR','BYD','BYI') and layanan != 'DEPOSIT' ");
            $askes = getFromTable("select sum(jumlah) from rs00005 where reg='$reg' and kasir in ('ASK') ");
            $potongan = getFromTable("select sum(jumlah) from rs00005 where reg='$reg' and kasir in ('POT') ");
            $cek_deposit = getFromTable("select sum(jumlah) from rs00005 where reg='$reg' and layanan='DEPOSIT' and kasir in ('BYR','BYD','BYI') ");
            $total=$tagihan + $tagihan2 + $tagihan5a + $tagihan7;
            
			//--pembulatan
			$totalPembulatan = pembulatan($total);
			$pembulatan = $totalPembulatan - $total;
			//--
            ?>

            <tr >
                <td class="TBL_BODY2" align="right" colspan="3"><b><font size="1"  face="Tahoma">TOTAL TAGIHAN :</b></td>
                <td class="TBL_BODY2" align="right"><b><font size="1"  face="Tahoma"><?= number_format($total, 2, ",", ".") ?></b></td>
            </tr class="title">
            
            <tr >
                <td class="TBL_BODY2" align="right" colspan="3"><b><font size="1"  face="Tahoma">PEMBULATAN :</b></td>
                <td class="TBL_BODY2" align="right"><b><font size="1"  face="Tahoma"><?= number_format($pembulatan, 2, ",", ".") ?></b></td>
            </tr class="title">
            
            <tr >
                <td class="TBL_BODY2" align="right" colspan="3"><b><font size="1"  face="Tahoma">TOTAL :</b></td>
                <td class="TBL_BODY2" align="right"><b><font size="1"  face="Tahoma"><?= number_format($totalPembulatan, 2, ",", ".") ?></b></td>
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
            //$tgl_sekarang = date("d M Y", time());
            $tgl_sekarang = getFromTable("SELECT tanggal(tgl_entry,3) FROM rs00005 WHERE reg='".$_GET['rg']."' AND kasir IN ('BYR', 'BYI') AND is_bayar = 'Y'");
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
                    <td align="center" class="TITLE_SIM3"><font size="1"  face="Tahoma"><?php echo $clien_city; ?>, <?php echo $tgl_sekarang; ?></td>

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
