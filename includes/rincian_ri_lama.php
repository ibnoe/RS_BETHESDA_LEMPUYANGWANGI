<?php // Nugraha, Sat May  1 10:22:31 WIT 2004
      // sfdn, 01-06-2004

$_GET["rg"] = $_GET[rg];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$reg2 = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];   
//echo "mr=".$_GET["mr"];exit;   

//---------------hery 13072007---------------
//echo $reg2;
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
echo"<div align=left class=form_subtitle>RINCIAN</div>";    
    
$r = pg_query($con,
    "select distinct trans_group, trans_form,is_bayar ".
    "from rs00008 ".
    "where no_reg = '$reg2'".
    "and trans_type in ('LTM','OB1','POS') ".
    "order by trans_group");

echo "<table border=0 cellspacing=0 width='100%'>";
echo "<tr>";
echo "<th class=TBL_HEAD2>TANGGAL</th>";
//echo "<th class=TBL_HEAD2>REF#</th>";
echo "<th class=TBL_HEAD2 colspan=6>URAIAN</th>";
echo "<th class=TBL_HEAD2>JUMLAH</th>";
echo "<th class=TBL_HEAD2>TAGIHAN</th>";
//echo "<th class=TBL_HEAD2>PEMBAYARAN</th>";
echo "<th class=TBL_HEAD2></th>";
echo "</tr>";

if ($_SESSION[gr] != "apotek-ri" && $_SESSION[gr] != "apotek-rj") {
while ($d = pg_fetch_object($r)) {

    //---------------- TINDAKAN MEDIS
    //if ($d->tanggal_trans > "2011-11-23" ){
    $r1 = pg_query($con,
        "select distinct a.id, f.id, a.layanan, a.hierarchy, h.tdesc as jenis_jasa, ii.tdesc as kelas,  ".
        "b.id as level1_id, b.layanan as level1, ".
        "c.id as level2_id, c.layanan as level2, ".
        "d.id as level3_id, d.layanan as level3, ".
        "e.id as level4_id, e.layanan as level4, ".
        "f.qty, g.tdesc as satuan, f.tagihan, f.pembayaran, f.tanggal_trans, f.trans_group, f.no_kwitansi as nip ,f.is_bayar, to_char(f.waktu_entry,'HH:MM:SS') as waktu ".
        ",x.tipe_pasien_id,xx.tdesc as tipe_pasien ".
        "from rs00034 a ".
        "join rs00008 f on to_number(f.item_id,'999999999999') = a.id ".
        "     and f.trans_type = 'LTM' ".
        "left join rs00001 g on a.satuan_id = g.tc ".
        "     and g.tt = 'SAT' ".
	"left join rs00001 h on a.sumber_pendapatan_id = h.tc and h.tt = 'SBP' ".
	"left join rs00001 ii on a.klasifikasi_tarif_id = ii.tc and ii.tt = 'KTR' ".
        //=========================================================================
        "left join rs00034 b on substr(b.hierarchy,4,12) = '000000000000' ".
        "     and substr(a.hierarchy,1,3)  = substr(b.hierarchy,1,3) ".
        "     and b.id <> a.id AND b.is_group  ='Y' ".
        "left join rs00034 c on substr(c.hierarchy,7,9)  = '000000000' ".
        "     and substr(a.hierarchy,1,6)  = substr(c.hierarchy,1,6) ".
        "     and c.id <> a.id AND c.is_group  ='Y' ".
        "left join rs00034 d on substr(d.hierarchy,10,6) = '000000' ".
        "     and substr(a.hierarchy,1,9)  = substr(d.hierarchy,1,9) ".
        "     and d.id <> a.id AND d.is_group  ='Y' ".
        "left join rs00034 e on substr(e.hierarchy,13,3) = '000' ".
        "     and substr(a.hierarchy,1,12) = substr(e.hierarchy,1,12) ".
        "     and e.id <> a.id AND e.is_group  ='Y' ".
        "left join rs00034 x on x.id = to_number(f.item_id,'999999999999')      ".
        "left join rs00001 xx on x.tipe_pasien_id  = xx.tc and xx.tt = 'JEP'    ".    
        //=========================================================================
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

            echo "<td class=TBL_BODY2 colspan=9>";
	    echo "<B>LAYANAN TINDAKAN MEDIS</B></td>";
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

        for ($m = 1; $m <= $level; $m++) echo "<td class=TBL_BODY2 width=1>&nbsp;&nbsp;</td>";
        echo "<td class=TBL_BODY2 colspan='".(6-$level)."'>";
	// -------------- delete transaksi
	if ($d->trans_form == 'p_akupunktur' and $d->is_bayar=='N') {
		echo "<a href='actions/p_akupunktur.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_anak' and $d->is_bayar=='N'){
		echo "<a href='actions/p_anak.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_bedah' and $d->is_bayar=='N'){
		echo "<a href='actions/p_bedah.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_gigi' and $d->is_bayar=='N'){
		echo "<a href='actions/p_gigi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_jantung' and $d->is_bayar=='N'){
		echo "<a href='actions/p_jantung.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_kulit_kelamin' and $d->is_bayar=='N'){
		echo "<a href='actions/p_kulit_kelamin.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_peny_dalam' and $d->is_bayar=='N'){
		echo "<a href='actions/p_peny_dalam.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_tht' and $d->is_bayar=='N'){
		echo "<a href='actions/p_tht.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_umum' and $d->is_bayar=='N') {
		echo "<a href='actions/p_umum.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_fisioterapi' and $d->is_bayar=='N') {
		echo "<a href='actions/p_fisioterapi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_mata' and $d->is_bayar=='N') {
		echo "<a href='actions/p_mata.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_paru' and $d->is_bayar=='N') {
		echo "<a href='actions/p_paru.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_ginekologi' and $d->is_bayar=='N') {
		echo "<a href='actions/p_ginekologi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_obsteteri' and $d->is_bayar=='N') {
		echo "<a href='actions/p_obsteteri.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_psikiatri' and $d->is_bayar=='N') {
		echo "<a href='actions/p_psikiatri.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_saraf' and $d->is_bayar=='N') {
		echo "<a href='actions/p_saraf.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_radiologi' and $d->is_bayar=='N') {
		echo "<a href='actions/p_radiologi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_igd' and $d->is_bayar=='N') {
		echo "<a href='actions/p_igd.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_laboratorium' and $d->is_bayar=='N') {
		echo "<a href='actions/p_laboratorium.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_riwayat_penyakit' and $d->is_bayar=='N') {
		echo "<a href='actions/p_riwayat_penyakit.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_catatan_kebidanan' and $d->is_bayar=='N') {
		echo "<a href='actions/p_catatan_kebidanan.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_catatan_bayi' and $d->is_bayar=='N') {
		echo "<a href='actions/p_catatan_bayi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_ginekologi' and $d->is_bayar=='N') {
		echo "<a href='actions/p_ginekologi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_obstetri' and $d->is_bayar=='N') {
		echo "<a href='actions/p_obstetri.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}
	echo $d1->layanan.$tokit."</td>";
        echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." $d1->satuan</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->tagihan,2)."</td>";
        //echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->pembayaran,2)."</td>";
        echo "<td class=TBL_BODY2 width='12%' align=right>&nbsp;</td>";
        echo "</tr>";
        for ($n = 1; $n < 5; $n++) $prevLevel[$n] = $currLevel[$n];
    }
    pg_free_result($r1);
}
}



    $printSubTitle = false;
    $printSubTitleObat = false;


if ($_SESSION[gr] == "apotek-ri" || $_SESSION[gr] == "apotek-rj" || $_SESSION[gr] == "root") {


//---<<<<<<<<<<<<<<<<<< PEMBELIAN OBAT >>>>>>>>>>>>>>>>>>>>>


$obat_belum_dibayar = 0.00;
$rec = getFromTable ("select count(id) from rs00008 ".
				     "where trans_type = 'OB1' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
// tokit, "and referensi != 'F'" added

if ($rec > 0 ) {
	$obat_belum_dibayar = getFromTable ("select sum(qty*harga) from rs00008 ".
		"where trans_type = 'OB1' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
	$SQL =
		"select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans, ".
		"obat, qty, c.tdesc as satuan, sum(harga*qty) as tagihan, pembayaran, trans_group, d.tdesc as kategori ".
		"from rs00008 a, rs00015 b, rs00001 c, rs00001 d ".
		"where to_number(a.item_id,'999999999999') = b.id  ".
		"and b.satuan_id = c.tc and a.trans_type = 'OB1' ".
		"and c.tt = 'SAT' ".
		"and b.kategori_id = d.tc and d.tt = 'GOB' ".
		"and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'".
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
		echo "<td class=TBL_BODY2 width='12%'>".number_format($d1->qty)." $d1->satuan</td>";
		echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($d1->tagihan,2)."</td>";
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
        echo "<td class=TBL_BODY2 width='12%' align=right>-".number_format($tagihan,2)."</td>";
	    echo "<td class=TBL_BODY2 width='12%' align=right>&nbsp;</td>";
        //echo "<td class=TBL_BODY2 width='12%' align=right>".number_format($pembayaran,2)."</td>";
        echo "</tr>";
     }
    pg_free_result($r1);

   
}



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
