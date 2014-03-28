<?php // Nugraha, Sat May  1 10:22:31 WIT 2004
      // sfdn, 01-06-2004

$_GET["rg"] = $_GET[rg];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];   
//echo "mr=".$_GET["mr"];exit;   

//---------------hery 13072007---------------
echo "<hr noshade size=1>";

$SQLINAP="select  
	a.tagih, 
	a.bayar as bayar,a.sisa
	
	from rsv0012 a 
	left join rs00006 b on a.id = b.id 
	left join rs00001 c on b.status_akhir_pasien = c.tc and c.tt = 'SAP' 
	left join rs00010 e on e.no_reg=a.id
	join rs00012 as g on e.bangsal_id = g.id
	join rs00012 as f on substr(g.hierarchy,1,6) || '000000000' = f.hierarchy
	where e.awal=1 and a.id='$reg2'";
	
 $r2 = pg_query($con,$SQLINAP);
    /*if ($d2 = pg_fetch_object($r2)){
    	echo "<table><tr>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>TOTAL TAGIHAN :&nbsp;</b>"."<b>".number_format($d2->tagih)."<b></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>PEMBAYARAN :&nbsp;</b>"."<b>".number_format($d2->bayar)."<b></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>SISA :&nbsp;</b>"."<b>".number_format($d2->sisa)."</b></td>";
	echo "</tr></table>";
    }*/
//-----------------------------------
echo "<hr noshade size=1>";
echo"<div align=left class=form_subtitle>RINCIAN</div>";    
    
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
echo "<th class=TBL_HEAD2>JUMLAH</th>";
echo "<th class=TBL_HEAD2 align=right>TAGIHAN</th>";
//echo "<th class=TBL_HEAD2>PEMBAYARAN</th>";
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
        //"e.id as level4_id, e.layanan as level4, ".
        "f.qty, g.tdesc as satuan, f.tagihan, f.pembayaran, f.tanggal_trans, f.trans_group ".
       //	"f.qty, g.tdesc as satuan, f.tagihan, f.qty*f.tagihan as pembayaran, f.tanggal_trans, f.trans_group ".
        "from rs00034 a ".
        "join rs00008 f on to_number(f.item_id,'999999999999') = a.id ".
        "     and f.trans_type = 'LTM' AND referensi <> 'P'".
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
            echo "<td class=TBL_BODY2 colspan=9 bgcolor='#00CCCC'>";
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
	if (substr($d1->hierarchy,0,6) == "003113") $tokit = " - ".$d1->jenis_jasa;
	if (substr($d1->hierarchy,0,6) == "003002" and $d1->jenis_jasa == 'JASA PEMERIKSAAN') { $tokit = " - ".$d1->kelas; } else {  $tokit = ""; }

        for ($m = 1; $m <= $level; $m++) echo "<td class=TBL_BODY2 width=1>&nbsp;&nbsp;</td>";
        echo "<td class=TBL_BODY2 colspan='".(6-$level)."'>";
	
	$tglhariini = date("Y-m-d", time());
	if ($d1->tanggal_trans == $tglhariini){
	if ($d->trans_form == '320' && $PID == "320") {
		echo "<a href='actions/320.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}
        
	
	
	if ($d->trans_form == 'p_akupunktur' && $PID == "p_akupunktur") {
		echo "<a href='actions/p_akupunktur.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_anak' && $PID == "p_anak"){
		echo "<a href='actions/p_anak.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_bedah' && $PID == "p_bedah"){
		echo "<a href='actions/p_bedah.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_gigi' && $PID == "p_gigi"){
		echo "<a href='actions/p_gigi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_jantung' && $PID == "p_jantung"){
		echo "<a href='actions/p_jantung.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_kulit_kelamin' && $PID == "p_kulit_kelamin"){
		echo "<a href='actions/p_kulit_kelamin.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_peny_dalam' && $PID == "p_peny_dalam"){
		echo "<a href='actions/p_peny_dalam.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_tht' && $PID == "p_tht"){
		echo "<a href='actions/p_tht.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_umum' && $PID == "p_umum") {
		echo "<a href='actions/p_umum.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_fisioterapi' && $PID == "p_fisioterapi") {
		echo "<a href='actions/p_fisioterapi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_mata' && $PID == "p_mata") {
		echo "<a href='actions/p_mata.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_paru' && $PID == "p_paru") {
		echo "<a href='actions/p_paru.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_ginekologi' && $PID == "p_ginekologi") {
		echo "<a href='actions/p_ginekologi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_obsteteri' && $PID == "p_obsteteri") {
		echo "<a href='actions/p_obsteteri.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_psikiatri' && $PID == "p_psikiatri") {
		echo "<a href='actions/p_psikiatri.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_saraf' && $PID == "p_saraf") {
		echo "<a href='actions/p_saraf.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_radiologi' && $PID == "p_radiologi") {
		echo "<a href='actions/p_radiologi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_igd' && $PID == "p_igd") {
		echo "<a href='actions/p_igd.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_laboratorium' && $PID == "p_laboratorium") {
		echo "<a href='actions/p_laboratorium.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&poli=".$_POST["mPOLI"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_riwayat_penyakit' && $PID == "p_riwayat_penyakit") {
		echo "<a href='actions/p_riwayat_penyakit.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_catatan_kebidanan' && $PID == "p_catatan_kebidanan") {
		echo "<a href='actions/p_catatan_kebidanan.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_catatan_bayi' && $PID == "p_catatan_bayi") {
		echo "<a href='actions/p_catatan_bayi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_ginekologi' && $PID == "p_ginekologi") {
		echo "<a href='actions/p_ginekologi.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}elseif ($d->trans_form == 'p_obstetri' && $PID == "p_obstetri") {
		echo "<a href='actions/p_obstetri.delete.php?del=$d1->id&tbl=tindakan&list={$_POST["list"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."'>".icon("del-left")."</a>&nbsp;";
	}
	
	}else{;}
	echo $d1->layanan.$tokit."</td>";
        
		echo "<td class=TBL_BODY2 width='12%' align=center>".number_format($d1->qty)." $d1->satuan</td>";
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

//=====AKOMODASI =========================================
	$inap=getFromTable ("select count(id) from rs00010 where no_reg='$reg2'");
	$hitung= pg_query($con,"select  extract(day from current_timestamp - a.ts_check_in) as hari,
					CASE WHEN 
					extract(hour from current_timestamp - a.ts_check_in) >=3 AND 
					extract(hour from current_timestamp - a.ts_check_in) < 12 THEN
					0.5
					WHEN
					extract(hour from current_timestamp - a.ts_check_in) >= 12 THEN
					1
					END as jam,b.harga,
					extract(day from current_timestamp - a.ts_check_in) * b.harga as akomodasi
					from rs00010 a
					join rs00012 as c on a.bangsal_id = c.id
					join rs00012 as b on substr(c.hierarchy,1,6) || '000000000' = b.hierarchy
					where a.awal=1 and a.no_reg='$reg2'");
	if($inap>0){
		$d5 = pg_fetch_object($hitung);
		 //echo "<td class=TBL_BODY2>&nbsp;</td>";
		 echo "<tr><td class=TBL_BODY2>".date("d-m-Y")."</td>";
		 echo "<td class=TBL_BODY2 colspan=6 align=left bgcolor='#00CCCC'><b>SEWA KAMAR RAWAT INAP</b></td>";//Garis lari
		 echo "<td class=TBL_BODY2 align=center bgcolor='#00CCCC'><b>".($d5->hari+$d5->jam)." HARI</b></td>";
		 echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>&nbsp;".number_format((($d5->hari+$d5->jam)*$d5->harga),2)."</b></td>";//Menyesuaikan format angka dan penyesuaian garis by Me 05102011
		 echo "<td class=TBL_BODY2 align=left bgcolor='#00CCCC'><b></b></td></tr>";
		 
		   
		  
	}
		
		
		
//========================================================



//---<<<<<<<<<<<<<<<<<< PEMBELIAN OBAT >>>>>>>>>>>>>>>>>>>>>


$obat_belum_dibayar = 0.00;
$rec = getFromTable ("select count(id) from rs00008 ".
				     "where trans_type = 'OBA' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
// tokit, "and referensi != 'F'" added

if ($rec > 0 ) {
	$obat_belum_dibayar = getFromTable ("select sum(qty*harga) from rs00008 ".
		"where trans_type = 'OBA' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
	$SQL =
		"select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans,  ".
		"obat, qty, dosis, c.tdesc as satuan, sum(harga*qty) as tagihan, pembayaran, trans_group, d.tdesc as kategori, a.trans_form ".
		"from rs00008 a, rs00015 b, rs00001 c, rs00001 d ".
		"where to_number(a.item_id,'999999999999') = b.id  ".
		"and b.satuan_id = c.tc and a.trans_type = 'OBA' ".
		"and c.tt = 'SAT' ".
		"and b.kategori_id = d.tc and d.tt = 'GOB' ".
		"and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'".
		"group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.dosis, a.pembayaran, a.trans_group, c.tdesc, a.trans_form ";
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
                //echo " $d13->trans_form ";
                if ($d1->trans_form == '320' && $PID == "320") {
                echo "<a href='actions/retur.delete.php?del=$d1->id&id=$d1->obat_id&qty=$d1->qty&tbl=retur&rg=".$_GET["rg"]."'>".icon("del-left","Hapus")."</a>";
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
		echo "<td class=TBL_BODY2 width='12%'align=center>".number_format($d1->qty)." $d1->satuan</td>";
		echo "<td class=TBL_BODY2 width='12%' align=right>$d1->dosis (Dosis)</td>";
               echo "<td class=TBL_BODY2 width='12%' align=right>&nbsp;</td>";
		echo "</tr>";
	}
	pg_free_result($r1);




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
        echo "<td class=TBL_BODY2 width='12%' align=centre>".number_format($d1->qty)." $d1->satuan</td>";
        echo "<td class=TBL_BODY2 width='12%' align=left>-".number_format($tagihan,2)."</td>";
	    echo "<td class=TBL_BODY2 width='12%' align=centre>&nbsp;</td>";
         echo "</tr>";
     }
    pg_free_result($r1);

   
}

echo "<td class=TBL_BODY2>&nbsp;</td>";
echo "<td class=TBL_BODY2 colspan=12 align=left bgcolor='#00CCCC'><b>PEMBELIAN OBAT APOTIK</b></td>";
   
$rowsPemakaianObat      = pg_query($con, " SELECT id, tanggal_entry, waktu_entry, item_id, qty, tagihan, referensi, dibayar_penjamin, trans_type 
                             FROM rs00008 
                             WHERE trans_type IN ('OB1','BHP') AND rs00008.no_reg = '".$_GET["rg"]."' order by id");
$rowsPemakaianRacikan   = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty, tagihan::integer, referensi::integer, dibayar_penjamin::integer, trans_type  
                             FROM rs00008 
                             WHERE trans_type = 'RCK' AND rs00008.no_reg = '".$_GET["rg"]."'");


?>
<!-- ---------------------- Start Buat tabel hasil input obat -------------------->
<table width='100%' border=1>
	<tr>
		<td class="TBL_HEAD" width='3%'><center>No.</center></td>
		<td class="TBL_HEAD" width='12%' ><center>Tanggal</center></td>
		<td class="TBL_HEAD"><center>Obat</center></td>
		<td class="TBL_HEAD" width='8%'><center>Jumlah</center></td>
		<td class="TBL_HEAD" width='8%'><center>Tagihan</center></td>
		<td class="TBL_HEAD" width='8%'><center>Penjamin</center></td>
		<td class="TBL_HEAD" width='8%'><center>Selisih</center></td>
	</tr>	
        <tr>
		<td class="TBL_BODY" colspan="7"><span style="font-weight: bold;">Obat Resep</span></td>
	</tr>
<?php
        $iData          = 0;
        $iObat          = 0;
        $total          = 0;
        $totalPenjamin  = 0;
        $totalSelisih   = 0;
        while($row=pg_fetch_array($rowsPemakaianObat)){
            $iData++;
            $iObat++;
            $total          = $total + $row["tagihan"];
            $totalPenjamin  = $totalPenjamin + $row["dibayar_penjamin"];
            $totalSelisih   = $totalSelisih + ($row["tagihan"]-$row["dibayar_penjamin"]);
            
            $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga::integer,rs00016.harga_car_drs,
rs00016.harga_car_rsrj,rs00016.harga_car_rsri,rs00016.harga_inhealth_drs,rs00016.harga_inhealth_rs,
rs00016.harga_jam_ri,rs00016.harga_jam_rj,rs00016.harga_kry_kelinti,rs00016.harga_kry_kelbesar,
rs00016.harga_kry_kelgratisri,rs00016.harga_kry_kelrespoli,rs00016.harga_kry_kel,
rs00016.harga_kry_kelgratisrj,rs00016.harga_umum_ri,rs00016.harga_umum_rj::integer,
rs00016.harga_umum_ikutrekening,rs00016.harga_gratis_rj,rs00016.harga_gratis_ri,
rs00016.harga_pen_bebas,rs00016.harga_nempil,rs00016.harga_nempil_apt 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
            $arrWaktuEntry = explode('.', $row["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
$margin = getFromTable("select item_id from rs00008 where no_reg='$noReg' and trans_type='OBM'");

if($margin=='001'){$harga = (int)$obat["harga_car_drs"];}
		else if($margin=='002'){
				   $harga = (int)$obat["harga_car_rsrj"];}
		else if($margin=='003'){
				   $harga = (int)$obat["harga_car_rsri"];}
		else if($margin=='004'){
				   $harga = (int)$obat["harga_inhealth_drs"];}
		else if($margin=='005'){
				   $harga = (int)$obat["harga_inhealth_rs"];}
		else if($margin=='006'){
				   $harga = (int)$obat["harga_jam_ri"];}
		else if($margin=='007'){
			           $harga = (int)$obat["harga_jam_rj"];}
		else if($margin=='008'){
				   $harga = (int)$obat["harga_kry_kelinti"];}
		else if($margin=='009'){
				   $harga = (int)$obat["harga_kry_kelbesar"];}
		else if($margin=='010'){
				   $harga = (int)$obat["harga_kry_kelgratisri"];}
		else if($margin=='011'){
				   $harga = (int)$obat["harga_kry_kelrespoli"];}
		else if($margin=='012'){
				   $harga = (int)$obat["harga_kry_kel"];}
		else if($margin=='013'){
				   $harga = (int)$obat["harga_kry_kelgratisrj"];}
		else if($margin=='014'){
				   $harga = (int)$obat["harga_umum_ri"];}
		else if($margin=='015'){
				   $harga = (int)$obat["harga_umum_rj"];}
		else if($margin=='016'){
				   $harga = (int)$obat["harga_umum_ikutrekening"];}
		else if($margin=='017'){
				   $harga = (int)$obat["harga_gratis_rj"];}
		else if($margin=='018'){
				   $harga = (int)$obat["harga_gratis_ri"];}
		else if($margin=='019'){
				   $harga = (int)$obat["harga_pen_bebas"];}
		else if($margin=='020'){
				   $harga = (int)$obat["harga_nempil"];}
		else{
				   $harga = (int)$obat["harga_nempil_apt"];}
		$jasareturn=0;
?>
	<tr>
		<td class="TBL_BODY" align="right"><?=$iObat?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
		<td class="TBL_BODY" align="left">
                    <input type="hidden" id="obat_id_<?php echo $row["id"]?>" value="<?=$obat["id"]?>" />
                    <input type="hidden" id="harga_<?php echo $row["id"]?>" value="<?=$harga?>" />
		    <input type="hidden" id="hargareturn_<?php echo $row["id"]?>" value="<?=$obat["harga"]?>" />
                    <input type="hidden" id="jasa_<?php echo $row["id"]?>" value="<?=$row["referensi"]?>" />
		    <input type="hidden" id="jasareturn_<?php echo $row["id"]?>" value="<?=$jasareturn?>" />
                    <input type="hidden" id="tagihan_<?php echo $row["id"]?>" value="<?=$row["tagihan"]?>" />
                    <input type="hidden" id="tipe_<?php echo $row["id"]?>" value="<?=$row["trans_type"]?>" />
                    <span id="obat_nama_<?php echo $row["id"]?>"><?=$obat["obat"]?></span>
                </td>
		<td class="TBL_BODY" align="right">
                    <span id="qty_<?php echo $row["id"]?>"><?=$row["qty"]?></span>
                    <span id="satuan_<?php echo $row["id"]?>"><?=$obat["satuan"]?></span>
                </td>
		<td class="TBL_BODY" align="right"><?=number_format($row["tagihan"],'0','','.')?></td>
		<td class="TBL_BODY" align="right"><span id="penjamin_<?php echo $row["id"]?>"><?=number_format($row["dibayar_penjamin"],'0','','.')?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $row["id"]?>"><?=number_format($row["tagihan"]-$row["dibayar_penjamin"],'0','','.')?></span></td>
	</tr>
<?php
        }
?>
            <tr>
		<td class="TBL_BODY" colspan="7"><span style="font-weight: bold;">Obat Racikan</span></td>
	</tr>
    <?php
        $iRacikan         = 0;
        while($rowRacikan = pg_fetch_array($rowsPemakaianRacikan)){
            $iRacikan++;
            $iData++;
            $total          = $total + $rowRacikan["tagihan"];
            $totalPenjamin  = $totalPenjamin + $rowRacikan["dibayar_penjamin"];
            $totalSelisih   = $totalSelisih + ($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]);
            
            $sqlObatR = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga::integer,rs00016.harga_car_drs,
rs00016.harga_car_rsrj,rs00016.harga_car_rsri,rs00016.harga_inhealth_drs,rs00016.harga_inhealth_rs,
rs00016.harga_jam_ri,rs00016.harga_jam_rj,rs00016.harga_kry_kelinti,rs00016.harga_kry_kelbesar,
rs00016.harga_kry_kelgratisri,rs00016.harga_kry_kelrespoli,rs00016.harga_kry_kel,
rs00016.harga_kry_kelgratisrj,rs00016.harga_umum_ri,rs00016.harga_umum_rj::integer,
rs00016.harga_umum_ikutrekening,rs00016.harga_gratis_rj,rs00016.harga_gratis_ri,
rs00016.harga_pen_bebas,rs00016.harga_nempil,rs00016.harga_nempil_apt 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $rowRacikan["item_id"] );
            $obatR = pg_fetch_array($sqlObatR);
            $arrWaktuEntry2 = explode('.', $rowRacikan["waktu_entry"]);
            $arrJamEntry2 = explode(':', $arrWaktuEntry2[0]);
$margin = getFromTable("select item_id from rs00008 where no_reg='$noReg' and trans_type='OBM'");

if($margin=='001'){$harga = (int)$obatR["harga_car_drs"];}
		else if($margin=='002'){
				   $hargaR = (int)$obatR["harga_car_rsrj"];}
		else if($margin=='003'){
				   $hargaR = (int)$obatR["harga_car_rsri"];}
		else if($margin=='004'){
				   $hargaR = (int)$obatR["harga_inhealth_drs"];}
		else if($margin=='005'){
				   $hargaR = (int)$obatR["harga_inhealth_rs"];}
		else if($margin=='006'){
				   $hargaR = (int)$obatR["harga_jam_ri"];}
		else if($margin=='007'){
			           $hargaR = (int)$obatR["harga_jam_rj"];}
		else if($margin=='008'){
				   $hargaR = (int)$obatR["harga_kry_kelinti"];}
		else if($margin=='009'){
				   $hargaR = (int)$obatR["harga_kry_kelbesar"];}
		else if($margin=='010'){
				   $hargaR = (int)$obatR["harga_kry_kelgratisri"];}
		else if($margin=='011'){
				   $hargaR = (int)$obatR["harga_kry_kelrespoli"];}
		else if($margin=='012'){
				   $hargaR = (int)$obatR["harga_kry_kel"];}
		else if($margin=='013'){
				   $hargaR = (int)$obatR["harga_kry_kelgratisrj"];}
		else if($margin=='014'){
				   $hargaR = (int)$obatR["harga_umum_ri"];}
		else if($margin=='015'){
				   $hargaR = (int)$obatR["harga_umum_rj"];}
		else if($margin=='016'){
				   $hargaR = (int)$obatR["harga_umum_ikutrekening"];}
		else if($margin=='017'){
				   $hargaR = (int)$obatR["harga_gratis_rj"];}
		else if($margin=='018'){
				   $hargaR = (int)$obatR["harga_gratis_ri"];}
		else if($margin=='019'){
				   $hargaR = (int)$obatR["harga_pen_bebas"];}
		else if($margin=='020'){
				   $hargaR = (int)$obatR["harga_nempil"];}
		else{
				   $hargaR = (int)$obatR["harga_nempil_apt"];}  
		$jasareturn=0;          
?>
	<tr>
            
		<td class="TBL_BODY" align="right"><?=$iRacikan?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($rowRacikan["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry2[0].':'.$arrJamEntry2[1]?></td>
		<td class="TBL_BODY" align="left">
			<input type="hidden" id="obat_id_<?php echo $rowRacikan["id"]?>" value="<?=$obatR["id"]?>" />
			<input type="hidden" id="harga_<?php echo $rowRacikan["id"]?>" value="<?=$hargaR?>" />
			<input type="hidden" id="hargareturn_<?php echo $rowRacikan["id"]?>" value="<?=$obatR["harga"]?>" />
			<input type="hidden" id="jasa_<?php echo $rowRacikan["id"]?>" value="<?=$rowRacikan["referensi"]?>" />
			<input type="hidden" id="jasareturn_<?php echo $rowRacikan["id"]?>" value="<?=$jasareturn?>" />
			<input type="hidden" id="tagihan_<?php echo $rowRacikan["id"]?>" value="<?=$rowRacikan["tagihan"]?>" />
			<input type="hidden" id="tipe_<?php echo $row["id"]?>" value="<?=$row["trans_type"]?>" />
			<span id="obat_nama_<?php echo $rowRacikan["id"]?>"><?=$obatR["obat"]?></span>
        </td>
		<td class="TBL_BODY" align="right">
                    <span id="qty_<?php echo $rowRacikan["id"]?>"><?=$rowRacikan["qty"]?></span>
                    <span id="satuan_<?php echo $rowRacikan["id"]?>"><?=$obatR["satuan"]?></span>
                </td>
		<td class="TBL_BODY" align="right"><?=number_format($rowRacikan["tagihan"],'0','','.')?></td>
		<td class="TBL_BODY" align="right"><span id="penjamin_<?php echo $rowRacikan["id"]?>"><?=number_format($rowRacikan["dibayar_penjamin"],'0','','.')?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $rowRacikan["id"]?>"><?=number_format($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"],'0','','.')?></span></td>
	</tr>
<?php
        }
        echo '<input type="hidden" name="max_i" id="max_i" value="'.$iData.'">';
?>        
	<tr>
		<td class="TBL_HEAD" colspan="4" align="right">T O T A L </td>
		<td class="TBL_HEAD" align="right" ><?=number_format($total,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalPenjamin,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalSelisih,'0','','.')?>&nbsp;&nbsp;</td>
	</tr>
</table>
<table  width='100%%'>
    <tr>
        <td class="TBL_BODY" align="center" width='33%'><a href="javascript: cetakkwitansi1(<? echo (int) $_GET["rg"];?>)" ></a></td>
        <td class="TBL_BODY" align="center" width='33%'><a href="javascript: cetakTransaksi(<? echo (int) $_GET["rg"];?>)" ></a></td>
    </tr>	
</table>
<?php
$rowsReturn = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty_return, tagihan, referensi, dibayar_penjamin, trans_type  
                             FROM rs00008_return 
                             WHERE trans_type = 'OB1' AND rs00008_return.no_reg = '".$_GET["rg"]."' ");
?>
<div align="LEFT" class="FORM_TITLE"><b>Return</b></div>
<table width='100%'>
	<tr>
		<td class="TBL_HEAD" width='3%'><center>No.</center></td>
		<td class="TBL_HEAD" width='12%' ><center>Tanggal</center></td>
		<td class="TBL_HEAD"><center>Obat</center></td>
		<td class="TBL_HEAD" width='7%'><center>Jumlah</center></td>
		<td class="TBL_HEAD" width='7%'><center>Tagihan</center></td>
		<td class="TBL_HEAD" width='7%'><center>Penjamin</center></td>
		<td class="TBL_HEAD" width='7%'><center>Selisih</center></td>
	</tr>
<?php
        $iData          = 0;$jml_return=0;
        while($row=pg_fetch_array($rowsReturn)){
            $iData++;
            
            $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
            $arrWaktuEntry = explode('.', $row["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
	    $jml_return+=$row["tagihan"];
?>
	<tr>
            <td class="TBL_BODY" align="right"><?=$iData?></td>
            <td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
            <td class="TBL_BODY" align="left"><?=$obat["obat"]?></td>
            <td class="TBL_BODY" align="right"><?=$row["qty_return"]?></td>
            <td class="TBL_BODY" align="right"><?=number_format($row["tagihan"],'0','','.') ?></td>
            <td class="TBL_BODY" align="right"><?=number_format($row["dibayar_penjamin"],'0','','.')?></td>
            <td class="TBL_BODY" align="right"><?=number_format($row["tagihan"]-$row["dibayar_penjamin"],'0','','.')?></td>
	</tr>
<?php
        }
        echo '<input type="hidden" name="max_i_return" id="max_i_return" value="'.$iData.'">';
?>        
</table>
<table  width='100%%'>
    <tr>
        <td class="TBL_BODY" align="center" width='34%'><a href="javascript: cetakReturn(<? echo (int) $_GET["rg"];?>)" ></a></td>
    </tr>	
</table>
<!-- ---------------------- End Buat tabel hasil input obat -------------------- -->

<script>
    $('#check_all_return').click(function(){
        if($('#check_all_return').is(':checked')){
            $('.check_return').attr("checked",true);
        }else{
            $('.check_return').attr("checked",false);
        }        
    })
    $('#check_all_obat').click(function(){
        if($('#check_all_obat').is(':checked')){
            $('.check_obat').attr("checked",true);
        }else{
            $('.check_obat').attr("checked",false);
        }        
    })
    
    function cetakReturn() {
        maxIReturn = $('#max_i_return').val();
        selectedToPrint = '&max_return='+maxIReturn;
        for(iReturn=0;iReturn<=maxIReturn;iReturn++){
            if($('#cetak_return_'+iReturn).is(':checked') == true){
                obatReturnSelected = $('#cetak_return_'+iReturn).val();
                selectedToPrint = selectedToPrint+'&obat_id_'+iReturn+'='+obatReturnSelected;
            }
        }
        window.open('includes/cetak.rincian_obat_return.php?rg=<?php echo $_GET['rg']?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    }
    
    function cetakTransaksi() {
        maxI = $('#max_i').val();
        selectedToPrint = '&max_cetak='+maxI;
        for(i=0;i<=maxI;i++){
            if($('#cetak_'+i).is(':checked') == true){
                obatSelected = $('#cetak_'+i).val();
                selectedToPrint = selectedToPrint+'&cetak_'+i+'='+obatSelected;
            }
        }
        window.open('includes/cetak.rincian_obat_selected.php?rg=<?php echo $_GET['rg']?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    }
</script>
<?php
function tanggal($tanggal) {
        $arrTanggal = explode('-', $tanggal);

        $hari = $arrTanggal[2];
        $bulan = $arrTanggal[1];
        $tahun = $arrTanggal[0];

        $result = $hari . ' ' . bulan($bulan) . ' ' . $tahun;

        return $result;
    }

function bulan($params) {
    switch ($params) {
        case 1:
            $bln = "Jan";
            break;
        case 2:
            $bln = "Peb";
            break;
        case 3:
            $bln = "Mar";
            break;
        case 4:
            $bln = "Apr";
            break;
        case 5:
            $bln = "Mei";
            break;
        case 6:
            $bln = "Jun";
            break;
        case 7:
            $bln = "Jul";
            break;
        case 8:
            $bln = "Agu";
            break;
        case 9:
            $bln = "Sep";
            break;
        case 10:
            $bln = "Okt";
            break;
        case 11:
            $bln = "Nop";
            break;
        case 12:
            $bln = "Des";
            break;
            break;
    }
    return $bln;
}

function updateTagihanUntukKasir($con, $rg){
            // ---- insert juga rs00005 untuk kasir
        $sqlTotalBiayaObat      = pg_query($con, "SELECT SUM(tagihan) as jumlah_tagihan, SUM(dibayar_penjamin) as jumlah_penjamin  
                            FROM rs00008 
                            WHERE no_reg = '".$rg."' ");
        $totalBiayaObat = pg_fetch_array($sqlTotalBiayaObat);

        if($totalBiayaObat['jumlah_tagihan'] > 0){
            $totalBiayaObatVal = $totalBiayaObat['jumlah_tagihan'];
        }else{
            $totalBiayaObatVal = 0;
        }
        
        if($totalBiayaObat['jumlah_penjamin'] > 0){
            $totalBiayaObatValPenjamin = $totalBiayaObat['jumlah_penjamin'];
        }else{
            $totalBiayaObatValPenjamin = 0;
        }
        
        // cek dulu di tabel rs00005 klo datanya udah ada di update, klo g ada di insert aja cuy
        $sqlCek = pg_query($con, "SELECT jumlah FROM  rs00005 WHERE reg = '".$rg."' AND kasir = 'RJL' AND layanan = '320RJ_SWD' ");
        
        if(pg_num_rows($sqlCek) > 0){
            pg_query($con, "UPDATE  rs00005  SET  jumlah = ".((int)$totalBiayaObatVal-(int)$totalBiayaObatValPenjamin)." 
                WHERE reg = '".$rg."' AND kasir = 'RJL' AND layanan = '320RJ_SWD' ");
        }else{
            pg_query($con, "INSERT INTO rs00005 VALUES( nextval('kasir_seq'), '".$rg."', ".
        "CURRENT_DATE, 'RJL', 'Y', 'N', '320RJ_SWD', ".((int)$totalBiayaObatVal-(int)$totalBiayaObatValPenjamin).", 'N')");
        }
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
echo "</tr>";
echo "<tr>";
echo "<td colspan=10></td>";
echo "</tr>";

echo "<tr>";
echo "<th class=TBL_HEAD2 colspan=8 align=RIGHT>JUMLAH RETUR&nbsp; : &nbsp;</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>-".number_format($jml_return,2)."</th>";
echo "</tr>";

echo "<tr>";
echo "<td colspan=10></td>";
echo "</tr>";
echo "<tr>";
echo "<th class=TBL_HEAD2 colspan=8 align=RIGHT>JUMLAH TAGIHAN &nbsp; : &nbsp;</th>";
echo "<th class=TBL_HEAD2 align=RIGHT>".
     number_format(($d1->tagihan+$bangsal_sudah_posting+$obat_belum_dibayar)-$pembayaran-$jml_return,2)."</th>";
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
echo "<hr noshade size=1>";

$SQLINAP="select  
	a.tagih, 
	a.bayar as bayar,a.sisa as sisa
	
	from rsv0012 a 
	left join rs00006 b on a.id = b.id 
	left join rs00001 c on b.status_akhir_pasien = c.tc and c.tt = 'SAP' 
	left join rs00010 e on e.no_reg=a.id
	join rs00012 as g on e.bangsal_id = g.id
	join rs00012 as f on substr(g.hierarchy,1,6) || '000000000' = f.hierarchy
	where e.awal=1 and a.id='$reg2'";

$sisa=($d2->tagih)-($d2->bayar);
	
 $r2 = pg_query($con,$SQLINAP);
    if ($d2 = pg_fetch_object($r2)){
    	echo "<table width='100%'><tr>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;</td>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>TOTAL TAGIHAN :</b></td>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>".number_format($d2->tagih,2)."<b></td>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;&nbsp;</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;</td>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;</td>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>TOTAL RETUR OBAT :</b></td>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>".number_format($d2->bayar -$jml_return,2)."<b></td>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;&nbsp;</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;</td>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;</td>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>PEMBAYARAN :</b></td>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>".number_format($d2->bayar,2)."<b></td>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;&nbsp;</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;</td>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;</td>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>SISA :</b></td>";
	echo "<td class=TBL_BODY2 align=right bgcolor='#00CCCC'><b>".number_format($d2->sisa - $jml_return,2)."</b></td>";
	echo "<td class=TBL_BODY2 colspan=7 bgcolor='#FFFFFF'>&nbsp;&nbsp;&nbsp;</td>";
	echo "</tr></table>";
    }
echo "</table>";



//        }
?>
