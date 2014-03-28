<?php // Nugraha, Sun May  2 15:15:02 WIT 2004
      // sfdn, 02-06-2004
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

echo "<hr noshade size=1>";
title("Rincian Tagihan");
echo "<br>";
$r = pg_query($con,
    "select distinct trans_group ".
    "from rs00008 ".
    "where to_number(no_reg, '999999999999') = $reg ".
    "and trans_type in ('LTM','BYR','OB2') ".
    "order by trans_group");
echo "<table border=0 cellspacing=0 width='100%'>";
echo "<tr>";
echo "<th class=TBL_HEAD2>TANGGAL</th>";
echo "<th class=TBL_HEAD2>REF#</th>";
echo "<th class=TBL_HEAD2 colspan=6>URAIAN</th>";
echo "<th class=TBL_HEAD2>JUMLAH</th>";
echo "<th class=TBL_HEAD2>TAGIHAN</th>";
echo "<th class=TBL_HEAD2>PEMBAYARAN</th>";
echo "</tr>";

while ($d = pg_fetch_object($r)) {
    // TINDAKAN MEDIS
    $r1 = pg_query($con,
        "select a.id, a.layanan, ".
        "b.id as level1_id, b.layanan as level1, ".
        "c.id as level2_id, c.layanan as level2, ".
        "d.id as level3_id, d.layanan as level3, ".
        "e.id as level4_id, e.layanan as level4, ".
        "f.qty, g.tdesc as satuan, f.tagihan, f.pembayaran, to_char(f.tanggal_trans,'DD-MM-YYYY') as  tanggal_trans, f.trans_group ".
        "from rs00034 a ".
        "join rs00008 f on to_number(f.item_id,'999999999999') = a.id ".
        "     and f.trans_type = 'LTM' ".
        "left join rs00001 g on a.satuan_id = g.tc ".
        "     and g.tt = 'SAT' ".
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
                echo "<td class=TBL_BODY2>$d1->tanggal_trans</td>";
                $oldDate = $d1->tanggal_trans;
            }
            if ($oldRef == $d1->trans_group) {
                echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
            } else {
                echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
                $oldRef = $d1->trans_group;
            }
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
                    echo "<td class=TBL_BODY2>$d1->tanggal_trans</td>";
                    $oldDate = $d1->tanggal_trans;
                }
                if ($oldRef == $d1->trans_group) {
                    echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
                } else {
                    echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
                    $oldRef = $d1->trans_group;
                }
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
            echo "<td class=TBL_BODY2>$d1->tanggal_trans</td>";
            $oldDate = $d1->tanggal_trans;
        }
        if ($oldRef == $d1->trans_group) {
            echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
        } else {
            echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
            $oldRef = $d1->trans_group;
        }
        for ($m = 1; $m <= $level; $m++) echo "<td class=TBL_BODY2 width=1>&nbsp;&nbsp;</td>";
        echo "<td class=TBL_BODY2 colspan='".(6-$level)."'>".$d1->layanan."</td>";
        echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." $d1->satuan</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->tagihan,2)."</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->pembayaran,2)."</td>";
        echo "</tr>";
        for ($n = 1; $n < 5; $n++) $prevLevel[$n] = $currLevel[$n];
    }
    pg_free_result($r1);
    // OBAT
	$SQL 		= "select to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans, ".
					"obat, qty, tdesc as satuan, tagihan, pembayaran, trans_group ".
		    		"from rs00008, rs00015, rs00001 ".
        			"where to_number(rs00008.item_id,'999999999999') = rs00015.id  ".
					"and rs00015.satuan_id = rs00001.tc and rs00008.trans_type = 'OB2' ".				
           			"and rs00001.tt = 'SAT' ".
        			"and trans_group = $d->trans_group ";
	$r1 = pg_query($con, "$SQL ");
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
            if ($oldRef == $d1->trans_group) {
                echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
            } else {
                echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
                $oldRef = $d1->trans_group;
            }
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
        if ($oldRef == $d1->trans_group) {
            echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
        } else {
            echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
            $oldRef = $d1->trans_group;
        }
        echo "<td class=TBL_BODY2>&nbsp;</td>";
        echo "<td class=TBL_BODY2 colspan=5>$d1->obat</td>";
        echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." $d1->satuan</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->tagihan,2)."</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->pembayaran,2)."</td>";
        echo "</tr>";
    }
    pg_free_result($r1);

    // PEMBAYARAN
    $r1 = pg_query($con,
        "select * from rs00008 ".
		"where trans_type = 'BYR' and ".
		"	trans_group = $d->trans_group and ".
		"	referensi NOT IN ('KASIR')");
    while ($d1 = pg_fetch_object($r1)) {
        echo "<tr>";
        if ($oldDate == $d1->tanggal_trans) {
            echo "<td class=TBL_BODY2>&nbsp;</td>";
        } else {
            echo "<td class=TBL_BODY2>".date("d-m-Y", pgsql2mktime($d1->tanggal_trans))."</td>";
            $oldDate = $d1->tanggal_trans;
        }
        if ($oldRef == $d1->no_kwitansi) {
            echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
        } else {
            echo "<td class=TBL_BODY2 align=CENTER>$d1->no_kwitansi</td>";
            $oldRef = $d1->no_kwitansi;
        }
        echo "<td class=TBL_BODY2 colspan=6><B>PEMBAYARAN </B></td>";
        echo "<td class=TBL_BODY2 width='12%'>&nbsp;</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->tagihan,2)."</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->pembayaran,2)."</td>";
        echo "</tr>";
    }
    pg_free_result($r1);
    $printSubTitle = false;
    $printSubTitleObat = false;

}

// TAGIHAN Obat yang blm. dibayar
$obat_belum_dibayar = 0.00;
$rec = getFromTable ("select count(id) from rs00008 ".
				     "where trans_type = 'OB1' and to_number(no_reg,'999999999999') = $reg");
if ($rec > 0 ) {
	$obat_belum_dibayar = getFromTable ("select sum(qty*harga) from rs00008 ".
										"where trans_type = 'OB1' and to_number(no_reg,'999999999999') = $reg");
	$SQL =	
		"select to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans, ".
		"obat, qty, tdesc as satuan, sum(harga*qty) as tagihan, pembayaran, trans_group ".
		"from rs00008 a, rs00015 b, rs00001 c ".
		"where to_number(a.item_id,'999999999999') = b.id  ".
		"and b.satuan_id = c.tc and a.trans_type = 'OB1' ".				
		"and c.tt = 'SAT' ".
		"and to_number(a.no_reg,'999999999999')= $reg ".
		"group by a.tanggal_trans, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc";
	$r1 = pg_query($con, "$SQL ");
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
			if ($oldRef == $d1->trans_group) {
				echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
			} else {
				echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
				$oldRef = $d1->trans_group;
			}
			echo "<td class=TBL_BODY2 colspan=9><B>PEMBELIAN OBAT BLM.DIBAYAR</B></td>";
			echo "</tr>";
		}
		echo "<tr>";
		if ($oldDate == $d1->tanggal_trans) {
			echo "<td class=TBL_BODY2>&nbsp;</td>";
		} else {
			echo "<td class=TBL_BODY2>$d1->tanggal_trans</td>";
			$oldDate = $d1->tanggal_trans;
		}
		if ($oldRef == $d1->trans_group) {
			echo "<td class=TBL_BODY2 align=CENTER>&nbsp;</td>";
		} else {
			echo "<td class=TBL_BODY2 align=CENTER>$d1->trans_group</td>";
			$oldRef = $d1->trans_group;
		}
		echo "<td class=TBL_BODY2>&nbsp;</td>";
		echo "<td class=TBL_BODY2 colspan=5>$d1->obat</td>";
		echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." $d1->satuan</td>";
		echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->tagihan,2)."</td>";
		echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->pembayaran,2)."</td>";
		echo "</tr>";
	}
	pg_free_result($r1);
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
        "where to_number(a.no_reg,'9999999999') = $reg");
if ($d1 = pg_fetch_object($r1)) {
    echo "<tr>";
    if ($oldDate == date("Y-m-d")) {
        echo "<td class=TBL_BODY2>&nbsp;</td>";
    } else {
        echo "<td class=TBL_BODY2>".date("d-m-Y")."</td>";
        $oldDate = date("Y-m-d");
    }
    echo "<td class=TBL_BODY2 align=CENTER>###</td>";

    echo "<td class=TBL_BODY2 colspan=9><B>AKOMODASI ".
         date("d-m-Y", pgsql2mktime($d1->ts_check_in))." s/d ".
         date("d-m-Y").
         " (BELUM POSTING)</B></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td class=TBL_BODY2>&nbsp;</td>";
    echo "<td class=TBL_BODY2>&nbsp;</td>";
    echo "<td class=TBL_BODY2>&nbsp;</td>";
    echo "<td class=TBL_BODY2 colspan=5>$d1->bangsal / $d1->ruangan / ".
         "Kelas $d1->klasifikasi_tarif / $d1->bed </td>";
    echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." HARI</td>";
    echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->harga,2)."</td>";
    echo "<td class=TBL_BODY2 width='12%' align=right>".number_format(0,2)."</td>";
    echo "</tr>";
    $bangsal_belum_posting = $d1->harga;
}
pg_free_result($r1);
}
// PEMBAYARAN DI KASIR
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
		echo "<td class=TBL_BODY2>".date("d-m-y",pgsql2mktime($dd->tanggal_trans))."</td>";
		$oldDate = $dd->tanggal_trans;
	}
	echo "<td class=TBL_BODY2 align=CENTER>###</td>";
	echo "<td class=TBL_BODY2 colspan=6><B>PEMBAYARAN $dd->referensi</B></td>";
	echo "<td class=TBL_BODY2 width='12%'>&nbsp;</td>";
	echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($dd->tagihan,2)."</td>";
	echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($dd->pembayaran,2)."</td>";
	echo "</tr>";
}
pg_free_result($rr);
// >>>>>>>>>>>>>  <<<<<<<<<<<<<<
$r1 = pg_query($con,
    "select sum(tagihan) as tagihan, sum(pembayaran) as pembayaran ".
    "from rs00008 ".
    "where trans_type in ('LTM', 'OB2', 'BYR') ".
    "and to_number(no_reg, '999999999999') = $reg");
$d1 = pg_fetch_object($r1);
pg_free_result($r1);
echo "<tr>";
echo "<th class=TBL_HEAD2 colspan=9 align=RIGHT>JUMLAH &nbsp; : &nbsp;</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>".number_format($d1->tagihan+$bangsal_belum_posting+$obat_belum_dibayar,2)."</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>".number_format($d1->pembayaran,2)."</th>";
echo "</tr>";
echo "<tr>";
echo "<td colspan=10></td>";
echo "</tr>";
echo "<tr>";
echo "<th class=TBL_HEAD2 colspan=9 align=RIGHT>JUMLAH YANG HARUS DIBAYAR &nbsp; : &nbsp;</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>".
     number_format(($d1->tagihan+$bangsal_belum_posting+$obat_belum_dibayar)-$d1->pembayaran,2)."</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>&nbsp;</th>";
echo "</tr>";
echo "</table>";
pg_free_result($r);

?>
