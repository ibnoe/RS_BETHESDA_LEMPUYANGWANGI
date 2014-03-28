<?php 
$_GET["rg"] = $_GET[rg];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];     

echo "<hr noshade size=1>";
echo"<div align=left class=form_subtitle>RINCIAN</div>";
    
$r = pg_query($con,
    "select distinct trans_group, trans_form ".
    "from rs00008 ".
    "where no_reg = '$reg2'".
    "and trans_type in ('OB1') ".
    "order by trans_group");

echo "<table border=0 cellspacing=0 width='100%'>";
echo "<tr>";
echo "<th class=TBL_HEAD2 align=left>TANGGAL</th>";
echo "<th class=TBL_HEAD2 colspan=6 align=left>URAIAN</th>";
echo "<th class=TBL_HEAD2 align=center>JUMLAH</th>";
echo "<th class=TBL_HEAD2 align=right>TAGIHAN</th>";
echo "<th class=TBL_HEAD2></th>";
echo "</tr>";

if ($_SESSION[gr] != "apotek-ri" && $_SESSION[gr] != "apotek-rj") {
while ($d = pg_fetch_object($r)) {

    //---------------- TINDAKAN MEDIS
    $r1 = pg_query($con,
        "select a.id, f.id, a.layanan, a.hierarchy, h.tdesc as jenis_jasa, ii.tdesc as kelas, ".
        "b.id as level1_id, b.layanan as level1, ".
        "c.id as level2_id, c.layanan as level2, ".
        "d.id as level3_id, d.layanan as level3, ".
        "f.qty, g.tdesc as satuan, f.tagihan, f.pembayaran, f.tanggal_trans, f.trans_group ".
        "from rs00034 a ".
        "join rs00008 f on to_number(f.item_id,'999999999999') = a.id ".
        "     and f.trans_type = 'OB1' ".
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
       
        "where f.trans_group = $d->trans_group ".
        "order by level1_id, level2_id, level3_id, a.id");
    $rows = pg_num_rows($r1);
    for ($n = 1; $n < 5; $n++) $prevLevel[$n] = "";
    while ($d1 = pg_fetch_object($r1)) {
        if (!$printSubTitle) {
            echo "<tr>";
            if ($oldDate == $d1->tanggal_trans) {
      //          echo "<td class=TBL_BODY2>&nbsp;</td>";
            } else {
     //           echo "<td class=TBL_BODY2>".date("d-m-Y", pgsql2mktime($d1->tanggal_trans))."</td>";
                $oldDate = $d1->tanggal_trans;
            }
            echo "<td class=TBL_BODY2 colspan=9>";
	  //  echo "<B>LAYANAN TINDAKAN MEDIS</B></td>";
         //   echo "</tr>";
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
           //     echo "<tr>";
                if ($oldDate == $d1->tanggal_trans) {
              //      echo "<td class=TBL_BODY2>&nbsp;</td>";
                } else {
        //            echo "<td class=TBL_BODY2>".date("d-m-Y", pgsql2mktime($d1->tanggal_trans))."</td>";
                    $oldDate = $d1->tanggal_trans;
                }
                for ($m = 1; $m <= $n; $m++)// echo "<td class=TBL_BODY2 width=1>&nbsp;&nbsp;</td>";
             //   echo "<td class=TBL_BODY2 colspan='".(9-$n)."'>".$currLevel[$n]."</td>";
             //   echo "</tr>";
                for ($m = $n; $m < 5; $m++) $prevLevel[$m] = "";
            }
        }
     //   echo "<tr>";
        if ($oldDate == $d1->tanggal_trans) {
     //       echo "<td class=TBL_BODY2>&nbsp;</td>";
        } else {
         //   echo "<td class=TBL_BODY2>".date("d-m-Y", pgsql2mktime($d1->tanggal_trans))."</td>";
            $oldDate = $d1->tanggal_trans;
        }
        for ($n = 1; $n < 5; $n++) $prevLevel[$n] = $currLevel[$n];
    }
    pg_free_result($r1);
}
}



    $printSubTitle = false;
    $printSubTitleObat = false;


//if ($_SESSION[gr] == "apotek-ri" || $_SESSION[gr] == "apotek-rj" || $_SESSION[gr] == "root"|| $_SESSION[gr] == "BEDAH" || $_SESSION[gr] == "JANTUNG" || $_SESSION[gr] == "INTERNE" || $_SESSION[gr] == "ANAK" || $_SESSION[gr] == "KULMIN" || $_SESSION[gr] == "GIGI" || $_SESSION[gr] == "MATA" || $_SESSION[gr] == "AKUPUNTUR" || $_SESSION[gr] == "UMUM" || $_SESSION[gr] == "THT" || $_SESSION[gr] == "PARU" || $_SESSION[gr] == "KEBIDANAN" || $_SESSION[gr] == "JIWA" || $_SESSION[gr] == "SARAF" || $_SESSION[gr] == "GIZI" || $_SESSION[uid] == "apotikrj" || $_SESSION[uid] == "apotik" || $_SESSION[uid] == "igd") {


//---<<<<<<<<<<<<<<<<<< PEMBELIAN OBAT >>>>>>>>>>>>>>>>>>>>>

/* 
$obat_belum_dibayar = 0.00;
$rec = getFromTable ("select count(id) from rs00008 where trans_type = 'OB1' and no_reg = '$reg' and referensi != 'F'");
$rec1 =  ("select count(id) from rs00008 where trans_type = 'OB1' and no_reg = '$reg' and referensi != 'F'");
//echo $rec1;
// tokit, "and referensi != 'F'" added

if ($rec > 0 ) {
	$obat_belum_dibayar = getFromTable ("select sum(tagihan) from rs00008 where trans_type = 'OB1' and no_reg = '$reg' and referensi != 'F'");
	$SQL =
		"select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans, ".
		"obat, qty, c.tdesc as satuan, sum(tagihan) as tagihan, pembayaran, trans_group, d.tdesc as kategori ".
		"from rs00008 a, rs00015 b, rs00001 c, rs00001 d ".
		"where to_number(a.item_id,'999999999999') = b.id  ".
		"and b.satuan_id = c.tc and a.trans_type = 'OB1' ".
		"and c.tt = 'SAT' ".
		"and b.kategori_id = d.tc and d.tt = 'GOB' ".
		"and a.no_reg= '$reg'  and referensi != 'F'".
		"group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group,   c.tdesc ";
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
		echo "<td class=TBL_BODY2>&nbsp;</td>";
		echo "<td class=TBL_BODY2 colspan=5>";
                if ($d1->kategori != $kateg) {
                   $ob_urut++;
                   $obatx[$ob_urut] = 0;
                   echo "<u><b>$d1->kategori</b></u><br>";
                   $kateg = $d1->kategori;
                   $cek_kateg = substr($kateg,0,1);
                }

                

                if ($cek_kateg == "A") {   // apbd
                   $obatx[1] = $obatx[1] + $d1->tagihan;
                } elseif ($cek_kateg == "D") {    // dpho
                   $obatx[2] = $obatx[2] + $d1->tagihan;
                } elseif ($cek_kateg == "K") {    // koperasi
                   $obatx[3] = $obatx[3] + $d1->tagihan;
                }

                $tot_obat = $tot_obat + $d1->tagihan;

                $jml_obat = $jml_obat + $d1->tagihan;
		echo "$d1->obat";
                echo "</td>";
		echo "<td class=TBL_BODY2 width='12%' align=center>".number_format($d1->qty)." $d1->satuan</td>";
		echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->tagihan,2)."</td>";
		echo "<td class=TBL_BODY2 width='12%' align=right>&nbsp;</td>";
		echo "</tr>";
	}
	pg_free_result($r1);


}

    $printSubTitle = false;
    $printSubTitleObat = false;

 */

// ******************* RETUR OBAT

    $r1 = pg_query($con,
        "select a.id, a.tanggal_trans, b.obat, b.id as obat_id, a.qty, a.harga,a.tagihan, c.tdesc as satuan, ".
        "   a.trans_group, d.tdesc as kategori ".
        "from rs00008 a, rs00015 b ".
        "   left join rs00001 c on c.tc = b.satuan_id and c.tt = 'SAT' ".
        "   left join rs00001 d on d.tc = b.kategori_id and d.tt = 'GOB' ".
        "where to_number(a.item_id,'999999999999') = b.id ".
        "   and a.trans_type='OB1' ".
        "   and a.no_reg = '$reg'".
        "group by d.tdesc, a.id, a.tanggal_trans, b.obat, b.id, a.qty, a.harga, c.tdesc, ".
        "   a.trans_group,a.tagihan  ");

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



                if ($d1->kategori != $kateg) {
                   $ob_urut++;
                   $obatr[$ob_urut] = 0;
                   echo "<u><b>$d1->kategori</b></u><br>";
                   $kateg = $d1->kategori;
                   $cek_kateg = substr($kateg,0,1);

                }

	//$tagihan = $d1->qty*$d1->harga;
        $tagihan = $d1->tagihan;
	$jml_retur = $jml_retur + $tagihan;
	$pembayaran = 0;

        echo "<a href='actions/retur_umum.delete.php?del=$d1->id&id=$d1->obat_id&qty=$d1->qty&tbl=retur&rg=".$_GET["rg"]."&tt=".$_GET["tt"]."&sub=".$_GET["sub"]."'>".icon("del-left","Hapus")."</a>";
                if ($cek_kateg == "A") {   // apbd
                   $obatr[1] = $obatr[1] + $tagihan;
                } elseif ($cek_kateg == "D") {    // dpho
                   $obatr[2] = $obatr[2] + $tagihan;
                } elseif ($cek_kateg == "K") {    // koperasi
                   $obatr[3] = $obatr[3] + $tagihan;
                }

	echo "$d1->obat</td>";
        echo "<td class=TBL_BODY2 width='12%' align=center>".number_format($d1->qty)." $d1->satuan</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($tagihan,2)."</td>";
	    echo "<td class=TBL_BODY2 width='12%' align=right>&nbsp;</td>";
        //echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($pembayaran,2)."</td>";
        echo "</tr>";
     }
    pg_free_result($r1);

   
//}



// ---------------------------------------------------------


// >>>>>>>>>>>>>  FOOTER <<<<<<<<<<<<<<

$r1 = pg_query($con,
    "select sum(tagihan) as tagihan, sum(pembayaran) as pembayaran ".
    "from rs00008 ".
    "where trans_type in ('OB1', 'BYR') ".
    "and (no_reg) = '$reg'");
$d1 = pg_fetch_object($r1);
pg_free_result($r1);

if ($_SESSION[uid] == "apotek ri" || $_SESSION[uid] == "apotek rj") {

echo "<tr>";
echo "<th class=TBL_HEAD2 colspan=8 align=RIGHT>JUMLAH HARGA OBAT&nbsp; : &nbsp;</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>".number_format($jml_obat,2)."</th>";
//echo "<th class=TBL_HEAD2 align=RIGHT>".number_format($pembayaran,2)."</th>";
echo "</tr>";
echo "<tr>";
echo "<td colspan=10></td>";
echo "</tr>";

echo "<tr>";
echo "<th class=TBL_HEAD2 colspan=8 align=RIGHT>JUMLAH RETUR&nbsp; : &nbsp;</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>-".number_format($jml_retur,2)."</th>";
//echo "<th class=TBL_HEAD2 align=RIGHT>".number_format($pembayaran,2)."</th>";
echo "</tr>";

echo "<tr>";
echo "<td colspan=10></td>";
echo "</tr>";
echo "<tr>";
echo "<th class=TBL_HEAD2 colspan=8 align=RIGHT>JUMLAH TAGIHAN &nbsp; : &nbsp;</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>".
     number_format(($d1->tagihan+$bangsal_sudah_posting+$obat_belum_dibayar)-$pembayaran-$jml_retur,2)."</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>&nbsp;</th>";
echo "</tr>";

echo "<tr><td colspan=10>";

echo "<table cellpadding=0 cellspacing=0 border=0 width=100%>";
echo "<tr>";
echo "<td class=TBL_BODY2 colspan=10><nobr><b>TOTAL BIAYA OBAT</b></nobr></td>";
echo "</tr>";

echo "<tr>";
echo "<td class=TBL_BODY2><img src=\"images/spacer.gif\" width=10 height=1><nobr><b>- APBD</b></nobr></td>";
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
echo "<td class=TBL_BODY2><img src=\"images/spacer.gif\" width=10 height=1><nobr><b>- KOPERASI</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($obatx[3],2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";
echo "</table>";

echo "</td></tr>";

echo "<tr>";
echo "<td class=TBL_BODY2><nobr><b>TOTAL RETUR OBAT</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>&nbsp;</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";

echo "<tr><td colspan=10>";

echo "<table cellpadding=0 cellspacing=0 border=0 width=100%>";
echo "<tr>";
echo "<td class=TBL_BODY2><img src=\"images/spacer.gif\" width=10 height=1><nobr><b>- APBD</b></nobr></td>";
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

$tagihobat[1] = $obatx[1] - $obatr[1];
$tagihobat[2] = $obatx[2] - $obatr[2];
$tagihobat[3] = $obatx[3] - $obatr[3];

echo "<tr>";
echo "<td class=TBL_BODY2><nobr><b>TOTAL TAGIHAN OBAT</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>&nbsp;</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";


echo "<tr><td colspan=10>";

echo "<table cellpadding=0 cellspacing=0 border=0 width=100%>";
echo "<tr>";
echo "<td class=TBL_BODY2><img src=\"images/spacer.gif\" width=10 height=1><nobr><b>- APBD</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($tagihobat[1],2)."</td>";
echo "<td class=TBL_BODY2 colspan=7 width=100%>&nbsp;</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY2><nobr><img src=\"images/spacer.gif\" width=10 height=1><b>- DPHO / ASKES</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($tagihobat[2],2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=TBL_BODY2><img src=\"images/spacer.gif\" width=10 height=1><nobr><b>- KOPERASI</b></nobr></td>";
echo "<td class=TBL_BODY2>&nbsp;:&nbsp;</td>";
echo "<td class=TBL_BODY2 align=RIGHT>".number_format($tagihobat[3],2)."</td>";
echo "<td class=TBL_BODY2 colspan=7>&nbsp;</td>";
echo "</tr>";
echo "</table>";

echo "</td></tr>";





}

echo "</table>";



//        }
?>
