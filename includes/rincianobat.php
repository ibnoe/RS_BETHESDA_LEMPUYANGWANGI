<?php // Nugraha, Sat May  1 10:22:31 WIT 2004
      // sfdn, 01-06-2004

$_GET["rg"] = $_GET[rg];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];   
//echo "mr=".$_GET["mr"];exit;   

//---------------Agung---------------
if ($_GET["p"]!="320RJ"){
echo "<hr noshade size=1>";
 $r2 = pg_query($con,"select * from rsv0012 where id = '$reg2'");
    if ($d2 = pg_fetch_object($r2)){
    	
		echo "<table><tr>";
		echo "<td  align=right><b>TOTAL TAGIHAN :</b></td>";
		echo "<td  align=right><b>".number_format($d2->tagih)."<b></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align=right><b>PEMBAYARAN :</b></td>";
		echo "<td align=right><b>".number_format($d2->bayar)."<b></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align=right><b>SISA :</b></td>";
		echo "<td align=right><b>".number_format($d2->sisa)."</b></td>";
		echo "</tr></table>";
		
    }
//-----------------------------------
echo "<hr noshade size=1>";
}
echo"<div align=left class=form_subtitle>RESEP DOKTER POLI</div>";    
    
$r = pg_query($con,
    "select distinct trans_group, trans_form ".
    "from rs00008 ".
    "where no_reg = '$reg2'".
	"and trans_type in ('LTM','OBA','POS') ".
    "order by trans_group");

echo "<table border=0 cellspacing=0 width='100%'>";
echo "<tr>";
echo "<th class=TBL_HEAD2 align=left>TANGGAL</th>";
//echo "<th class=TBL_HEAD2>REF#</th>";
echo "<th class=TBL_HEAD2 colspan=6 align=left>URAIAN</th>";
echo "<th class=TBL_HEAD2 align=center>JUMLAH</th>";
echo "<th class=TBL_HEAD2 align=right>RACIKAN ?</th>";
//echo "<th class=TBL_HEAD2>PEMBAYARAN</th>";
echo "<th class=TBL_HEAD2></th>";
echo "</tr>";


//if ($_SESSION[gr] == "apotek-ri" || $_SESSION[gr] == "apotek-rj" || $_SESSION[gr] == "root"|| $_SESSION[gr] == "BEDAH" || $_SESSION[gr] == "JANTUNG" || $_SESSION[gr] == "INTERNE" || $_SESSION[gr] == "ANAK" || $_SESSION[gr] == "KULMIN" || $_SESSION[gr] == "GIGI" || $_SESSION[gr] == "MATA" || $_SESSION[gr] == "AKUPUNTUR" || $_SESSION[gr] == "UMUM" || $_SESSION[gr] == "THT" || $_SESSION[gr] == "PARU" || $_SESSION[gr] == "KEBIDANAN" || $_SESSION[gr] == "JIWA" || $_SESSION[gr] == "SARAF" || $_SESSION[gr] == "GIZI" || $_SESSION[uid] == "apotikrj" || $_SESSION[uid] == "apotik" || $_SESSION[uid] == "igd") {


//---<<<<<<<<<<<<<<<<<< PEMBELIAN OBAT >>>>>>>>>>>>>>>>>>>>>


$obat_belum_dibayar = 0.00;
$rec = getFromTable ("select count(id) from rs00008 ".
				     "where trans_type = 'OBA' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
// tokit, "and referensi != 'F'" added

if ($rec > 0 ) {
	$obat_belum_dibayar = getFromTable ("select sum(qty*harga) from rs00008 ".
		"where trans_type = 'OBA' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
	$SQL =
		"select a.is_racikan,a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans, ".
		"obat, qty, c.tdesc as satuan, a.tagihan, pembayaran, trans_group, d.tdesc as kategori, a.trans_form,a.is_bayar ".
		"from rs00008 a, rs00015 b, rs00001 c, rs00001 d ".
		"where to_number(a.item_id,'999999999999') = b.id  ".
		"and b.satuan_id = c.tc and a.trans_type = 'OBA' ".
		"and c.tt = 'SAT' ".
		"and b.kategori_id = d.tc and d.tt = 'GOB' ".
		"and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'".
		"group by  a.tagihan,a.is_bayar,a.is_racikan,d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form ";
		
		
	$r1 = pg_query($con, "$SQL");

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

                if (("$d1->trans_form") == "$PID" and ("$d1->is_bayar") == "N") {
                echo "<a href='actions/retur_obat_poli.delete.php?pid=$PID&del=$d1->id&id=$d1->obat_id&qty=$d1->qty&tbl=retur&rg=".$_GET["rg"]."'>".icon("del-left","Hapus")."</a>";
                }
                //ditambah sama efrizal 2010-11-04
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
		//echo "$d1->is_racikan";
                echo "</td>";
		echo "<td class=TBL_BODY2 width='12%' align=center>".number_format($d1->qty)." $d1->satuan</td>";
		//echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->tagihan,2)."</td>";
                echo "<td class=TBL_BODY2 width='12%' align=center>&nbsp;$d1->is_racikan&nbsp;</td>";
		// pembayaran bisa nyicil jadi di taruh di bawah aje.
		//echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->pembayaran,2)."</td>";
		echo "<td class=TBL_BODY2 width='12%' align=right>&nbsp;</td>";
		echo "</tr>";
	}
	pg_free_result($r1);


}

    $printSubTitle = false;
    $printSubTitleObat = false;



// ******************* RETUR OBAT

    $r1 = pg_query($con,
        "select a.id, a.tanggal_trans, b.obat, b.id as obat_id, a.qty, a.harga, c.tdesc as satuan, ".
        "   a.trans_group, d.tdesc as kategori ".
        "from rs00008 a, rs00015 b ".
        "   left join rs00001 c on c.tc = b.satuan_id and c.tt = 'SAT' ".
        "   left join rs00001 d on d.tc = b.kategori_id and d.tt = 'GOB' ".
        "where to_number(a.item_id,'999999999999') = b.id ".
        "   and a.trans_type='RET' ".
        "   and a.no_reg = '$reg'".
        "group by d.tdesc, a.id, a.tanggal_trans, b.obat, b.id, a.qty, a.harga, c.tdesc, ".
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
        echo "<td class=TBL_BODY2>&nbsp;</td>";
        echo "<td class=TBL_BODY2 colspan=5>";



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

        echo "<a href='actions/retur.delete.php?del=$d1->id&id=$d1->obat_id&qty=$d1->qty&tbl=retur&rg=".$_GET["rg"]."'>".icon("del-left","Hapus")."</a>";
                if ($cek_kateg == "A") {   // apbd
                   $obatr[1] = $obatr[1] + $tagihan;
                } elseif ($cek_kateg == "D") {    // dpho
                   $obatr[2] = $obatr[2] + $tagihan;
                } elseif ($cek_kateg == "K") {    // koperasi
                   $obatr[3] = $obatr[3] + $tagihan;
                }

	echo "$d1->obat</td>";
        echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." $d1->satuan</td>";
        echo "<td class=TBL_BODY2 width='12%' align=center>-".number_format($tagihan,2)."</td>";
	    echo "<td class=TBL_BODY2 width='12%' align=right>&nbsp;</td>";
        echo "</tr>";
     }
    pg_free_result($r1);

   
//}



// ---------------------------------------------------------


// >>>>>>>>>>>>>  FOOTER <<<<<<<<<<<<<<

$r1 = pg_query($con,
    "select sum(tagihan) as tagihan, sum(pembayaran) as pembayaran ".
    "from rs00008 ".
    "where trans_type in ('OB2', 'BYR') ".
    "and to_number(no_reg, '999999999999') = $reg");
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
