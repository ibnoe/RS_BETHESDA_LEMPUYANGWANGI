<?php
	// sfdn, 24-12-2006
session_start();

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/setting.php");


$ROWS_PER_PAGE     = 14;
$RS_NAME           = $set_header[0]."<br>".$set_header[1];
$RS_ALAMAT         = $set_header[2]."<br>".$set_header[3].$set_header[4];

?>

<HTML>

<HEAD>
<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
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

<BODY TOPMARGIN=5 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0 />

<?
title($RS_NAME);
subtitle($RS_ALAMAT);
echo "<hr>";
echo "<br>";


$reg = $_GET["rg"];
if ($reg > 0) {
    if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where id = $reg ".
                     " ") ==0) {
                     //"and status = 'A'") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}

/*
if ($_SESSION[uid] == "kasir1") {
  title("KASIR RAWAT JALAN");
} elseif ($_SESSION[uid] == "kasir2") {
  title("KASIR RAWAT INAP");
} else {
  title("KASIR IGD");
}
*/
title("K A S I R");

title("Data Pasien");
echo "<br>";

include("335.inc.php");

$tot_bahan = 0;
$tot_sarana = 0;
$tot_periksa = 0;


echo "<hr noshade size=1>";
title("Rincian Tagihan");
echo "<br>";

$r = pg_query($con,
    "select distinct trans_group ".
    "from rs00008 ".
    "where no_reg = $reg ".
    "and trans_type in ('LTM','BYR','OB2','OB1','POS') ".
    "order by trans_group");
echo "<table border=0 cellspacing=0 width='100%'>";
echo "<tr>";
echo "<th class=TBL_HEAD2>TANGGAL</th>";
//echo "<th class=TBL_HEAD2>REF#</th>";
echo "<th class=TBL_HEAD2 colspan=6>URAIAN</th>";
echo "<th class=TBL_HEAD2>JUMLAH</th>";
echo "<th class=TBL_HEAD2>TAGIHAN</th>";
echo "<th class=TBL_HEAD2>PEMBAYARAN</th>";
echo "</tr>";
while ($d = pg_fetch_object($r)) {
    // TINDAKAN MEDIS
    $r1 = pg_query($con,
        "select a.id, a.layanan, a.hierarchy, h.tdesc as jenis_jasa, ii.tdesc as kelas, ".
        "b.id as level1_id, b.layanan as level1, ".
        "c.id as level2_id, c.layanan as level2, ".
        "d.id as level3_id, d.layanan as level3, ".
        "e.id as level4_id, e.layanan as level4, ".
        "f.qty, g.tdesc as satuan, f.tagihan, f.pembayaran, f.tanggal_trans, f.trans_group, f.no_kwitansi as nip ".
        "from rs00034 a ".
        "join rs00008 f on to_number(f.item_id,'999999999999') = a.id ".
        "     and f.trans_type = 'LTM' ".
        "left join rs00001 g on a.satuan_id = g.tc ".
        "     and g.tt = 'SAT' ".
	"left join rs00001 h on a.sumber_pendapatan_id = h.tc and h.tt = 'SBP' ".
	"left join rs00001 ii on a.klasifikasi_tarif_id = ii.tc and ii.tt = 'KTR' ".

        "left join rs00034 b on substr(b.hierarchy,4,12) = '000000000000' ".
        "     and substr(a.hierarchy,1,3)  = substr(b.hierarchy,1,3) ".
        "     and b.id <> a.id ".
        "left join rs00034 c on substr(c.hierarchy,7,9)  = '000000000' ".
        "     and substr(a.hierarchy,1,6)  = substr(c.hierarchy,1,6) ".
        "     and c.id <> a.id ".
        "left join rs00034 d on substr(d.hierarchy,10,6) = '000000' ".
        "     and substr(a.hierarchy,1,9)  = substr(d.hierarchy,1,9) ".
        "     and d.id <> a.id ".
        "left join rs00034 e on substr(e.hierarchy,13,3) = '000' ".
        "     and substr(a.hierarchy,1,12) = substr(e.hierarchy,1,12) ".
        "     and e.id <> a.id ".
        "where f.trans_group = $d->trans_group ".
        "order by level1_id, level2_id, level3_id, level4_id, a.id");
    $rows = pg_num_rows($r1);
    for ($n = 1; $n < 5; $n++) $prevLevel[$n] = "";
    while ($d1 = pg_fetch_object($r1)) {
        if (!$printSubTitle) {
            echo "<tr>";
            if ($oldDate == $d1->tanggal_trans) {
                echo "<td class=TBL_BODY2>&nbsp;</td>";
            } else {
                echo "<td class=TBL_BODY2>".date("d-m-Y", pgsql2mktime($d1->tanggal_trans))."</td>";
                $oldDate = $d1->tanggal_trans;
            }
            /*
            if ($oldRef == $d1->trans_group) {
                echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
            } else {
                echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
                $oldRef = $d1->trans_group;
            }
            */
            echo "<td class=TBL_BODY2 colspan=9><B>LAYANAN TINDAKAN MEDIS</B></td>";
            echo "</tr>";
            $printSubTitle = true;
        }
        $level = 1;
        if ($d1->level1_id > 0) $level = 2;
        if ($d1->level2_id > 0) $level = 3;
        if ($d1->level3_id > 0) $level = 4;
        if ($d1->level4_id > 0) $level = 5;
        for ($n = 1; $n < 5; $n++) eval("\$currLevel[$n] = \"\$d1->level$n\";");
        for ($n = 1; $n < 5; $n++) {
            if ($currLevel[$n] != $prevLevel[$n]) {
                echo "<tr>";
                if ($oldDate == $d1->tanggal_trans) {
                    echo "<td class=TBL_BODY2>&nbsp;</td>";
                } else {
                    echo "<td class=TBL_BODY2>".date("d-m-Y", pgsql2mktime($d1->tanggal_trans))."</td>";
                    $oldDate = $d1->tanggal_trans;
                }
                /*
                if ($oldRef == $d1->trans_group) {
                    echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
                } else {
                    echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
                    $oldRef = $d1->trans_group;
                }
                */
                for ($m = 1; $m <= $n; $m++) echo "<td class=TBL_BODY2 width=1>&nbsp;&nbsp;</td>";
                echo "<td class=TBL_BODY2 colspan='".(9-$n)."'>".$currLevel[$n]."</td>";
                echo "</tr>";
                for ($m = $n; $m < 5; $m++) $prevLevel[$m] = "";
            }
        }
        echo "<tr>";
        if ($oldDate == $d1->tanggal_trans) {
            echo "<td class=TBL_BODY2>&nbsp;</td>";
        } else {
            echo "<td class=TBL_BODY2>".date("d-m-Y", pgsql2mktime($d1->tanggal_trans))."</td>";
            $oldDate = $d1->tanggal_trans;
        }
        /*
        if ($oldRef == $d1->trans_group) {
            echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
        } else {
            echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
            $oldRef = $d1->trans_group;
        }
	*/
	if (substr($d1->hierarchy,0,6) == "003113") $tokit = " - ".$d1->jenis_jasa;
	if (substr($d1->hierarchy,0,6) == "003002" and $d1->jenis_jasa == 'JASA PEMERIKSAAN') { $tokit = " - ".$d1->kelas; } else {  $tokit = ""; }

        if ($d1->jenis_jasa == "BAHAN") {
           $tot_bahan = $tot_bahan + $d1->tagihan;
        } elseif ($d1->jenis_jasa == "JASA SARANA") {
           $tot_sarana = $tot_sarana + $d1->tagihan;
        } elseif ($d1->jenis_jasa == "JASA PEMERIKSAAN") {
           $tot_periksa = $tot_periksa + $d1->tagihan;
        }

        $dokter = getFromTable("select nama from rs00017 where id = '".$d1->nip."'");
        if ($d1->nip > 0) {
           $dokter = "(".$dokter.")";
        } else {
           $dokter = "";
        }
        for ($m = 1; $m <= $level; $m++) echo "<td class=TBL_BODY2 width=1>&nbsp;&nbsp;</td>";
        echo "<td class=TBL_BODY2 colspan='".(6-$level)."'>".$d1->layanan.$tokit." $dokter</td>";
        echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." $d1->satuan</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->tagihan,2)."</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->pembayaran,2)."</td>";
        echo "</tr>";
        for ($n = 1; $n < 5; $n++) $prevLevel[$n] = $currLevel[$n];
    }
    pg_free_result($r1);


    $printSubTitle = false;
    $printSubTitleObat = false;
}
//---<<<<<<<<<<<<<<<<<< PEMBELIAN OBAT >>>>>>>>>>>>>>>>>>>>>

$obat_belum_dibayar = 0.00;
$rec = getFromTable ("select count(id) from rs00008 ".
				     "where trans_type = 'OB1' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
// tokit, "and referensi != 'F'" added

if ($rec > 0 ) {
	$obat_belum_dibayar = getFromTable ("select sum(qty*harga) from rs00008 ".
										"where trans_type = 'OB1' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
	$SQL =
		"select to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans, ".
		"obat, qty, c.tdesc as satuan, sum(harga*qty) as tagihan, pembayaran, trans_group, d.tdesc as kategori ".
		"from rs00008 a, rs00015 b, rs00001 c, rs00001 d ".
		"where to_number(a.item_id,'999999999999') = b.id  ".
		"and b.satuan_id = c.tc and a.trans_type = 'OB1' ".
		"and c.tt = 'SAT' ".
		"and b.kategori_id = d.tc and d.tt = 'GOB' ".
		"and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'".
		"group by d.tdesc, a.tanggal_trans, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc";
	$r1 = pg_query($con, "$SQL ");
	
        $kateg = "000";
        $ob_urut = 0;

    	while ($d1 = pg_fetch_object($r1)) {
		if (!$printSubTitleObat) {
			$printSubTitleObat = true;
			echo "<tr>";
			if ($oldDate == $d1->tanggal_trans) {
				echo "<td class=TBL_BODY2>&nbsp;</td>";
			} else {
				echo "<td class=TBL_BODY2>$d1->tanggal_trans</td>";
				$oldDate = $d1->tanggal_trans;
			}
			/*
			if ($oldRef == $d1->trans_group) {
				echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
			} else {
				echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
				$oldRef = $d1->trans_group;
			}
			*/
			echo "<td class=TBL_BODY2 colspan=9><B>PEMBELIAN OBAT</B></td>";
			echo "</tr>";
		}
		echo "<tr>";
		if ($oldDate == $d1->tanggal_trans) {
			echo "<td class=TBL_BODY2>&nbsp;</td>";
		} else {
			echo "<td class=TBL_BODY2>$d1->tanggal_trans</td>";
			$oldDate = $d1->tanggal_trans;
		}
		/*
		if ($oldRef == $d1->trans_group) {
			echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
		} else {
			echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
			$oldRef = $d1->trans_group;
		}
		*/
		echo "<td class=TBL_BODY2>&nbsp;</td>";
		echo "<td class=TBL_BODY2 colspan=5>";
                if ($d1->kategori != $kateg) {
                   $ob_urut++;
                   $obat[$ob_urut] = 0;
                   echo "<u><b>$d1->kategori</b></u><br>";
                   $kateg = $d1->kategori;
			 $cek_kateg = substr($kateg,0,1);
                }


                if ($cek_kateg == "A") {   // DINAS
                   $obatx[1] = $obatx[1] + $d1->tagihan;
                } elseif ($cek_kateg == "D") {    // dpho
                   $obatx[2] = $obatx[2] + $d1->tagihan;
                } elseif ($cek_kateg == "K") {    // koperasi
                   $obatx[3] = $obatx[3] + $d1->tagihan;
                }


                $tot_obat = $tot_obat + $d1->tagihan;
                echo "$d1->obat</td>";
		echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." $d1->satuan</td>";
		echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->tagihan,2)."</td>";
		echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->pembayaran,2)."</td>";
		echo "</tr>";
	}
	pg_free_result($r1);
}



    $printSubTitle = false;
    $printSubTitleObat = false;

// ******************* RETUR OBAT

    $r1 = pg_query($con,
        "select a.id, a.tanggal_trans, b.obat, a.qty, a.harga, c.tdesc as satuan, ".
        "   a.trans_group, d.tdesc as kategori ".
        "from rs00008 a, rs00015 b ".
        "   left join rs00001 c on c.tc = b.satuan_id and c.tt = 'SAT' ".
        "   left join rs00001 d on d.tc = b.kategori_id and d.tt = 'GOB' ".
        "where to_number(a.item_id,'999999999999') = b.id ".
        "   and a.trans_type='RET' ".
        "   and a.no_reg = $reg".
        "group by d.tdesc, a.id, a.tanggal_trans, b.obat, a.qty, a.harga, c.tdesc, ".
        "   a.trans_group  ");

        $kateg = "000";
        $ob_urut = 0;

    while ($d1 = pg_fetch_object($r1)) {
        if (!$printSubTitleObat) {
            $printSubTitleObat = true;
            echo "<tr>";
            if ($oldDate == $d1->tanggal_trans) {
                echo "<td class=TBL_BODY2>&nbsp;</td>";
            } else {
                echo "<td class=TBL_BODY2>".date("d-m-Y", pgsql2mktime($d1->tanggal_trans))."</td>";
                $oldDate = $d1->tanggal_trans;
            }
            /*
            if ($oldRef == $d1->trans_group) {
                echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
            } else {
                echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
                $oldRef = $d1->trans_group;
            }
            */
            echo "<td class=TBL_BODY2 colspan=9><B>RETUR OBAT</B></td>";
            echo "</tr>";
        }
        echo "<tr>";
        if ($oldDate == $d1->tanggal_trans) {
            echo "<td class=TBL_BODY2>&nbsp;</td>";
        } else {
            echo "<td class=TBL_BODY2>".date("d-m-Y", pgsql2mktime($d1->tanggal_trans))."</td>";
            $oldDate = $d1->tanggal_trans;
        }
        /*
        if ($oldRef == $d1->trans_group) {
            echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
        } else {
            echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
            $oldRef = $d1->trans_group;
        }
        */
        echo "<td class=TBL_BODY2>&nbsp;</td>";
        echo "<td class=TBL_BODY2 colspan=5>";

	//echo "<a href='actions/335.3.delete.php?del=$d1->id&tbl=obat2&rg=".$_GET[rg]."'>xxx".icon("del-left")."</a>";

                if ($d1->kategori != $kateg) {
                   $ob_urut++;
                   $obatr[$ob_urut] = 0;
                   echo "<u><b>$d1->kategori</b></u><br>";
                   $kateg = $d1->kategori;
                   $cek_kateg = substr($kateg,0,1);

                }

	$tagihan = $d1->qty*$d1->harga;
	$jml_retur = $jml_retur + $tagihan;
	$pembayaran = 0;


                if ($cek_kateg == "A") {   // DINAS
                   $obatr[1] = $obatr[1] + $tagihan;
                } elseif ($cek_kateg == "D") {    // dpho
                   $obatr[2] = $obatr[2] + $tagihan;
                } elseif ($cek_kateg == "K") {    // koperasi
                   $obatr[3] = $obatr[3] + $tagihan;
                }

	echo "$d1->obat</td>";
       echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." $d1->satuan</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>-".number_format($tagihan,2)."</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($pembayaran,2)."</td>";
        echo "</tr>";
     }
    pg_free_result($r1);

// TAGIHAN AKOMODASI RAWAT INAP

$bangsal_sudah_posting = 0.00;
$rec = pg_query("select * from rs00008 ".
                     "where trans_type = 'POS' and to_number(no_reg,'999999999999') = $reg order by id");
$rec_num = pg_num_rows($rec);

if ($rec_num > 0 ) {

$r1 = pg_query($con,
        "select a.id, a.ts_check_in::date, e.bangsal, d.bangsal as ruangan, b.bangsal as bed, ".
        "    c.tdesc as klasifikasi_tarif, ".
        "    extract(day from a.ts_calc_stop - a.ts_calc_start) as qty, ".
        "    d.harga as harga_satuan, ".
        "    extract(day from a.ts_calc_stop - a.ts_calc_start) * d.harga as harga, ".
        "    a.ts_calc_stop ".
        "from rs00010 as a ".
        "    join rs00012 as b on a.bangsal_id = b.id ".
        "    join rs00012 as d on substr(b.hierarchy,1,6) || '000000000' = d.hierarchy ".
        "    join rs00012 as e on substr(b.hierarchy,1,3) || '000000000000' = e.hierarchy ".
        "    join rs00001 as c on d.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
        "where to_number(a.no_reg,'9999999999') = $reg and ts_calc_stop is not null");



while ($ddd = pg_fetch_object($rec)) {
if ($d1 = pg_fetch_object($r1)) {


    $qty = $ddd->qty;
    $harga = $qty * $d1->harga_satuan;
    echo "<tr>";
    if ($oldDate == date("Y-m-d")) {
        echo "<td class=TBL_BODY2>&nbsp;</td>";
    } else {
        echo "<td class=TBL_BODY2>".date("d-m-Y")."</td>";
        $oldDate = date("Y-m-d");
    }
    //echo "<td class=TBL_BODY2 align=CENTER>$ddd->trans_group</td>";
    echo "<td class=TBL_BODY2 colspan=9><B>AKOMODASI ".
         date("d-m-Y", pgsql2mktime($d1->ts_check_in))."   s/d   ".
         date("d-m-Y", pgsql2mktime($d1->ts_calc_stop)).
         "</B></td>";
    echo "</tr>";

    echo "<tr>";
    //echo "<td class=TBL_BODY2>&nbsp;</td>";
    echo "<td class=TBL_BODY2>&nbsp;</td>";
    echo "<td class=TBL_BODY2>&nbsp;</td>";
    echo "<td class=TBL_BODY2 colspan=5>$d1->bangsal / $d1->ruangan / ".
         "$d1->bed / $d1->klasifikasi_tarif</td>";
    echo "<td class=TBL_BODY2 width='12%'>".number_format($qty)." HARI</td>";
    echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($harga,2)."</td>";
    echo "<td class=TBL_BODY2 width='12%' align=right>".number_format(0,2)."</td>";
    echo "</tr>";
    $bangsal_sudah_posting = $bangsal_sudah_posting + $harga;
    //pg_free_result($r1);

    //pg_fetch_object($r1);

}
}
}

// >>>>>>>>>>>>>>>>  <<<<<<<<<<<<<<<<<<<<<<<
if (getFromTable ("select rawat_inap from rs00006 ".
				     "where to_number(id,'999999999999') = $reg") == "I") {
// TAGIHAN SEMENTARA AKOMODASI
$bangsal_belum_posting = 0.00;
$r1 = pg_query($con,
        "select a.id, a.ts_check_in::date, e.bangsal, d.bangsal as ruangan, b.bangsal as bed, ".
        "    c.tdesc as klasifikasi_tarif, ".
        "    extract(day from current_timestamp - a.ts_calc_start) as qty, ".
        "    d.harga as harga_satuan, ".
        "    extract(day from current_timestamp - a.ts_calc_start) * d.harga as harga ".
        "from rs00010 as a ".
        "    join rs00012 as b on a.bangsal_id = b.id ".
        "    join rs00012 as d on substr(b.hierarchy,1,6) || '000000000' = d.hierarchy ".
        "    join rs00012 as e on substr(b.hierarchy,1,3) || '000000000000' = e.hierarchy ".
        "    join rs00001 as c on d.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
        "where to_number(a.no_reg,'9999999999') = $reg and ts_calc_stop is null");
if ($d1 = pg_fetch_object($r1)) {
    echo "<tr>";
    if ($oldDate == date("Y-m-d")) {
        echo "<td class=TBL_BODY2>&nbsp;</td>";
    } else {
        echo "<td class=TBL_BODY2>".date("d-m-Y")."</td>";
        $oldDate = date("Y-m-d");
    }
    //echo "<td class=TBL_BODY2 align=CENTER>###</td>";
    echo "<td class=TBL_BODY2 colspan=9><B>AKOMODASI ".
         date("d-m-Y", pgsql2mktime($d1->ts_check_in))."   s/d   ".
         date("d-m-Y").
         "  (BELUM POSTING)</B></td>";
    echo "</tr>";
    
    echo "<tr>";
    echo "<td class=TBL_BODY2>&nbsp;</td>";
    echo "<td class=TBL_BODY2>&nbsp;</td>";
    //echo "<td class=TBL_BODY2>&nbsp;</td>";
    echo "<td class=TBL_BODY2 colspan=5>$d1->bangsal / $d1->ruangan / ".
         "$d1->bed / $d1->klasifikasi_tarif</td>";
    echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." HARI</td>";
    echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->harga,2)."</td>";
    echo "<td class=TBL_BODY2 width='12%' align=right>".number_format(0,2)."</td>";
    echo "</tr>";
    $bangsal_belum_posting = $bangsal_belum_posting+$d1->harga;
	pg_free_result($r1);
}
}

if ($bangsal_sudah_posting > 0) {
	$bangsal_belum_posting = 0;
}
// PEMBAYARAN DI KASIR
/*
$rr = pg_query($con,
                "select * from rs00008 ".
		"where trans_type = 'BYR' and ".
		"	to_number(no_reg,'999999999999') = $reg and ".
		"	referensi IN ('KASIR')");

while ($dd = pg_fetch_object($rr)) {
	echo "<tr>";
	if ($oldDate == $dd->tanggal_trans) {
		echo "<td class=TBL_BODY2>&nbsp;</td>";
	} else {
		echo "<td class=TBL_BODY2>".date("d-m-Y",pgsql2mktime($dd->tanggal_trans))."</td>";
		$oldDate = $dd->tanggal_trans;
	}
	echo "<td class=TBL_BODY2 align=CENTER>$dd->no_kwitansi</td>";
	echo "<td class=TBL_BODY2 colspan=6><B>PEMBAYARAN $dd->referensi</B></td>";
	echo "<td class=TBL_BODY2 width='12%'>&nbsp;</td>";
	echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($dd->tagihan,2)."</td>";
	echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($dd->pembayaran,2)."</td>";
	echo "</tr>";
}
pg_free_result($rr);
*/
$rr = pg_query($con,
        "select * from rs00005 ".
		"where kasir = 'BYR' and ".
		"	to_number(reg,'999999999999') = $reg "); //and ".
		//"	referensi IN ('KASIR')");

while ($dd = pg_fetch_object($rr)) {
	echo "<tr>";
	//if ($oldDate == $dd->tgl_entry) {
		//echo "<td class=TBL_BODY2>&nbsp;</td>";
	//} else {
		echo "<td class=TBL_BODY2>".date("d-m-Y",pgsql2mktime($dd->tgl_entry))."</td>";
		//$oldDate = $dd->tgl_entry;
	//}
	//echo "<td class=TBL_BODY2 align=CENTER>$dd->id</td>";
	echo "<td class=TBL_BODY2 colspan=6><B>PEMBAYARAN</B></td>";
	echo "<td class=TBL_BODY2 width='12%'>&nbsp;</td>";
	echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($dd->tagihan,2)."</td>";
	echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($dd->jumlah,2)."</td>";
	echo "</tr>";
	$pembayaran += $dd->jumlah;
}
pg_free_result($rr);


// ASKES - POTONGAN
$rr = pg_query($con,
                "select * from rs00005 ".
		"where kasir in ('POT','ASK') and ".
		"	to_number(reg,'999999999999') = $reg "); //and ".
		//"	referensi IN ('KASIR')");

while ($dd = pg_fetch_object($rr)) {
	echo "<tr>";
	//if ($oldDate == $dd->tgl_entry) {
		echo "<td class=TBL_BODY2>&nbsp;</td>";
	//} else {
	//	echo "<td class=TBL_BODY2>".date("d-m-Y",pgsql2mktime($dd->tgl_entry))."</td>";
	//	$oldDate = $dd->tgl_entry;
	//}

	if ($dd->kasir == "ASK") {
	   $what = "ASKES";
	} else {
	   $what = "POTONGAN";
        }

	//echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
	echo "<td class=TBL_BODY2 colspan=6><B>$what</B></td>";
	echo "<td class=TBL_BODY2 width='12%'>&nbsp;</td>";
	echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($dd->tagihan,2)."</td>";
	echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($dd->jumlah,2)."</td>";
	echo "</tr>";
	$pembayaran += $dd->jumlah;
}
pg_free_result($rr);

// >>>>>>>>>>>>>  FOOTER <<<<<<<<<<<<<<
$cek_karcis = getFromTable("select jumlah from rs00005 where reg = $reg and is_karcis = 'Y'");
$cek_loket = getFromTable("select kasir from rs00005 where reg = $reg and is_karcis = 'Y'");
if ($cek_loket == "RJL") {

if ($cek_karcis == 4500) {
if ($obatx[1]>=2000) {
   $potObat = getFromTable("select jumlah from rs00005 where reg = $reg and layanan = 99995 ");
}
} elseif ($cek_karcis == 9000) {
if ($obatx[1]>=4000) {
   $potObat = getFromTable("select jumlah from rs00005 where reg = $reg and layanan = 99995 ");
}
}

}


$r1 = pg_query($con,
    "select sum(tagihan) as tagihan, sum(pembayaran) as pembayaran ".
    "from rs00008 ".
    "where trans_type in ('LTM', 'OB2', 'BYR') ".
    "and to_number(no_reg, '999999999999') = $reg");
$d1 = pg_fetch_object($r1);
pg_free_result($r1);
echo "<tr>";
echo "<th class=TBL_HEAD2 colspan=8 align=RIGHT>JUMLAH &nbsp; : &nbsp;</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>".number_format($d1->tagihan+$bangsal_sudah_posting+$bangsal_belum_posting+$obat_belum_dibayar+$potObat-$jml_retur,2)."</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>".number_format($pembayaran,2)."</th>";
echo "</tr>";
echo "<tr>";
echo "<td colspan=10></td>";
echo "</tr>";
echo "<tr>";
echo "<th class=TBL_HEAD2 colspan=8 align=RIGHT>JUMLAH YANG HARUS DIBAYAR &nbsp; : &nbsp;</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>".
     number_format(($d1->tagihan+$bangsal_sudah_posting+$bangsal_belum_posting+$obat_belum_dibayar+$potObat)-$pembayaran-$jml_retur,2)."</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>&nbsp;</th>";
echo "</tr>";

/*
echo "<tr><td colspan=10>";

echo "<table cellpadding=0 cellspacing=0 border=0 width=100%>";
echo "<tr>";
echo "<td class=TBL_BODY2><nobr><b>TOTAL BIAYA BAHAN</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($tot_bahan,2)."</td>";
echo "<td class=TBL_BODY2 colspan=7 width=100%>&nbsp;</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY2><nobr><b>TOTAL BIAYA JASA SARANA</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($tot_sarana,2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY2><nobr><b>TOTAL BIAYA JASA PEMERIKSAAN</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($tot_periksa,2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";
$tot_obat2 = $tot_obat - $jml_retur;
echo "<tr>";
echo "<td class=TBL_BODY2><nobr><b>TOTAL BIAYA OBAT</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($tot_obat2,2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";

echo "<tr><td colspan=10>";

echo "<table cellpadding=0 cellspacing=0 border=0 width=100%>";
echo "<tr>";
echo "<td class=TBL_BODY2><nobr><img src=\"images/spacer.gif\" width=10 height=1><b>- DINAS</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($obatx[1],2)."</td>";
echo "<td class=TBL_BODY2 colspan=7 width=100%>&nbsp;</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY2><nobr><img src=\"images/spacer.gif\" width=10 height=1><b>- DPHO / ASKES</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($obatx[2],2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY2><nobr><img src=\"images/spacer.gif\" width=10 height=1><b>- KOPERASI</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($obatx[3],2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";
echo "</table>";

echo "</td></tr>";

echo "<tr>";
echo "<td class=TBL_BODY2><nobr><b>TOTAL RETUR OBAT</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($jml_retur,2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";

echo "<tr><td colspan=10>";

echo "<table cellpadding=0 cellspacing=0 border=0 width=100%>";
echo "<tr>";
echo "<td class=TBL_BODY2><img src=\"images/spacer.gif\" width=10 height=1><nobr><b>- DINAS</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($obatr[1],2)."</td>";
echo "<td class=TBL_BODY2 colspan=7 width=100%>&nbsp;</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY2><nobr><img src=\"images/spacer.gif\" width=10 height=1><b>- DPHO / ASKES</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($obatr[2],2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY2><img src=\"images/spacer.gif\" width=10 height=1><nobr><b>- KOPERASI</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($obatr[3],2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";
echo "</table>";

echo "</td></tr>";
echo "</table>";

echo "</td></tr>";
*/

echo "</table>";

pg_free_result($r);




echo "\n<script language='JavaScript'>\n";
echo "function cetakaja(tag) {\n";
echo "    sWin = window.open('index2.php?tag=' + tag, 'xWin',".
     " 'width=500,height=400,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

?>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</html>
