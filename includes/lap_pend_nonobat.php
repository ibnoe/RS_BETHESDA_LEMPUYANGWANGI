<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004
   // sfdn, 24-12-2006

$PID = "lap_pend_nonobat";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

// 24-12-2006
    if ($_SESSION[uid] == "kasir2") {
       $what = "RAWAT INAP";
       $sqlayanan = "NOT LIKE '%IGD%'";	
    } elseif ($_SESSION[uid] == "kasir1") {
       $what = "RAWAT JALAN";
       $sqlayanan = "NOT LIKE '%IGD%'";
    } else {
       $what = "IGD";
       $sqlayanan = "LIKE '%IGD%'";
    }
// ---- end ----

if($_GET["tc"] == "view") {
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan dari Non Obat");
		//title_excel("lap_pend_nonobat&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."");
		title_excel("lap_pend_nonobat&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."");
		
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Pendapatan dari Non Obat");
		
    }
    
    if (!$GLOBALS['print']) {
    	echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    }

    if ($_GET["e"] == "Y") {
        $unit = "Rawat Jalan";
    } elseif  ($_GET["e"] == "N"){
        $unit = "IGD";
    } elseif ($_GET["e"] == "I"){
        $unit = "Rawat Inap";
    } else {
        $unit = "Semua";
    }
	if($_GET["u"] != '' ){
    $pasien = getFromTable(
               "select tdesc from rs00001 ".
               "where tc_poli = '".$_GET["u"]."' and tt='JEP'");
	}else{
	$pasien = "Semua Tipe Pasien";
	}
    $r = pg_query($con, "select tanggal(to_date('".$_GET["f"]."','YYYYMMDD'),3) as tgl");
	//$r = pg_query($con, "select tanggal(to_date(".$_GET["f"].",'YYYYMMDD'),3) as tgl");
    $d = pg_fetch_object($r);
    pg_free_result($r);

    $bulan = $d->tgl;
    $tgl_year = substr($_GET[f],0,4);
    $tgl_mnth = substr($_GET[f],4,2);
    $tgl_day = substr($_GET[f],6,2);
    
    if(!$GLOBALS['print']){
	    $f = new Form("");
	    $f->subtitle1("Tanggal Transaksi    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $tgl_day-$tgl_mnth-$tgl_year");
	    $f->subtitle1("U n i t / Rawatan    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $unit");
	    $f->subtitle1("Tipe Pasien / Kesatuan : $pasien");
	    $f->execute();
    } else {
    	$f = new Form("");
	    $f->titleme("Tanggal Transaksi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $tgl_day-$tgl_mnth-$tgl_year");
	    $f->titleme("U n i t / Rawatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $unit");
	    $f->titleme("Tipe Pasien / Kesatuan : $pasien");
	    $f->execute();
    }

    echo "<br>";
//    $t = new PgTable($con, "100%");
	//benerin kondisi supaya hasil ga dikali jumlah pasien (28102010 najla)
/*     $r2 = pg_query($con,
              "select sum(a.tagihan) as jum,a.tanggal_trans ".
              "from rs00008 a ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "where b.rawat_inap='".$_GET["e"]."' and ".
              "     to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     a.trans_type='LTM' and b.tipe like '%".$_GET["u"]."%' group by a.tanggal_trans " );

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
     */
//if ($d2->tanggal_trans > "2011-11-17"){
//benerin kondisi supaya hasil ga dikali jumlah pasien (28102010 najla)
if ($_GET["e"]=="I"){
$SQL = "select c.mr_no, a.no_reg,c.nama,h.bangsal || ' / ' || g.bangsal || ' / ' || i.tdesc || ' / ' || f.bangsal as bangsal, ".
              "     e.layanan, sum(a.tagihan) as tagih,k.nama,
				case 	when a.trans_form='p_peny_dalam' then 'POLIKLINIK INTERNE'
						when a.trans_form='p_saraf' then 'POLIKLINIK SARAF'
						when a.trans_form='p_mata' then 'POLIKLINIK MATA'
						when a.trans_form='p_jantung' then 'POLIKLINIK JANTUNG'
						when a.trans_form='p_laboratorium' then 'LAYANAN LABORATORIUM'
						when a.trans_form='p_radiologi' then 'LAYANAN RADIOLOGI'
						when a.trans_form='p_psikiatri' then 'POLIKLINIK JIWA'
						when a.trans_form='p_ginekologi' then 'KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (GINEKOLOGI)'
						when a.trans_form='p_bedah' then 'POLIKLINIK BEDAH'
						when a.trans_form='p_fisioterapi' then 'UNIT REHABILITASI MEDIK'
						when a.trans_form='p_igd' then 'INSTALASI GAWAT DARURAT'
						when a.trans_form='p_kulit_kelamin' then 'POLIKLINIK KULIT DAN KELAMIN'
						when a.trans_form='p_paru' then 'POLIKLINIK PARU'
						when a.trans_form='p_anak' then 'POLIKLINIK ANAK'
						when a.trans_form='p_gigi' then 'POLIKLINIK GIGI DAN MULUT'
						when a.trans_form='p_operasi' then 'LAYANAN OPERASI'
						when a.trans_form='p_gizi' then 'POLIKLINIK GIZI'
						else 'BANGSAL RAWATAN'	end as poli_input	  ".
              "from rs00008 a  ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "     left join rs00010 j ON b.id = j.no_reg 
				join rs00012 as f on j.bangsal_id = f.id 
				join rs00012 as g on g.hierarchy = substr(f.hierarchy,1,6) || '000000000' 
				join rs00012 as h on h.hierarchy = substr(f.hierarchy,1,3) || '000000000000' 
				join rs00001 as i on g.klasifikasi_tarif_id = i.tc and i.tt = 'KTR'
				left join rs99995 k ON a.user_id = k.uid
				left join rs00002 c ON b.mr_no = c.mr_no ".
              "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
              "     left join rs00034 e ON to_number(a.item_id,'999999999999') = e.id ".
              "where ".
              " to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     b.rawat_inap ='".$_GET["e"]."' and ".
              "     a.trans_type = 'LTM' ".
              "and d.tc::text like '%".$_GET["u"]."%'  and a.referensi != 'P' ".	
              "group by c.mr_no, c.nama, c.pangkat_gol, k.nama, a.no_reg, e.layanan,h.bangsal,g.bangsal,i.tdesc,f.bangsal,a.trans_form";
}else{
    $SQL = "select c.mr_no, a.no_reg,c.nama as nm_pasien,f.tdesc,".
              "     e.layanan, sum(a.tagihan) as tagih,g.nama as user_input,
		case 	when a.trans_form='p_peny_dalam' then 'POLIKLINIK INTERNE'
				when a.trans_form='p_saraf' then 'POLIKLINIK SARAF'
				when a.trans_form='p_mata' then 'POLIKLINIK MATA'
				when a.trans_form='p_jantung' then 'POLIKLINIK JANTUNG'
				when a.trans_form='p_laboratorium' then 'LAYANAN LABORATORIUM'
				when a.trans_form='p_radiologi' then 'LAYANAN RADIOLOGI'
				when a.trans_form='p_psikiatri' then 'POLIKLINIK JIWA'
				when a.trans_form='p_ginekologi' then 'KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (GINEKOLOGI)'
				when a.trans_form='p_bedah' then 'POLIKLINIK BEDAH'
				when a.trans_form='p_fisioterapi' then 'UNIT REHABILITASI MEDIK'
				when a.trans_form='p_igd' then 'INSTALASI GAWAT DARURAT'
				when a.trans_form='p_kulit_kelamin' then 'POLIKLINIK KULIT DAN KELAMIN'
				when a.trans_form='p_paru' then 'POLIKLINIK PARU'
				when a.trans_form='p_anak' then 'POLIKLINIK ANAK'
				when a.trans_form='p_gigi' then 'POLIKLINIK GIGI DAN MULUT'
				when a.trans_form='p_operasi' then 'LAYANAN OPERASI'
				when a.trans_form='p_gizi' then 'POLIKLINIK GIZI'
				else 'BANGSAL RAWATAN' end as poli_input			  ".
              "from rs00008 a  ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "     left join rs99995 g ON a.user_id = g.uid
					left join rs00002 c ON b.mr_no = c.mr_no ".
              "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
					left join rs00001 f ON (b.poli::text = f.tc and f.tt = 'LYN') ".
              "     left join rs00034 e ON to_number(a.item_id,'999999999999') = e.id ".
              "where ".
              " to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     b.rawat_inap ='".$_GET["e"]."' and ".
              "     a.trans_type = 'LTM' ".
              "and d.tc::text like '%".$_GET["u"]."%' and a.referensi != 'P' ".	
              "group by c.mr_no, c.nama,g.nama , f.tdesc, a.no_reg, e.layanan,a.trans_form ";
}

@$r1 = pg_query($con,$SQL);
@$n1 = pg_num_rows($r1);

$max_row= 100 ;
$mulai = $HTTP_GET_VARS["rec"] ;	
if (!$mulai){$mulai=1;} 


?>
<Font size='2'><b>Pelayanan Non Paket</b></font>
<pre>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">NO.MR</td>
				<td class="TBL_HEAD"align="center">NO.REG</td>
				<td class="TBL_HEAD"align="center">NAMA PASIEN</td>
				<td class="TBL_HEAD"align="center">POLI/BANGSAL</td>
				<td align="center" class="TBL_HEAD">NAMA LAYANAN/TINDAKAN</td>
				<td width="10%" align="center" class="TBL_HEAD">TOTAL HARGA (Rp.)</td>
				<td width="10%" align="center" class="TBL_HEAD">USER/INPUT</td>
				<td align="center" class="TBL_HEAD">POLI INPUT</td>
			</tr>
			
	
		<?	
			$jml_js= 0;
			$jml_jp= 0;
			$jml= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			//Agung Sunandar 15:07 11/07/2012 menambahkan Navigasi langkah 1
			$batas = 100 ;
			$halaman = $_GET['halaman'];
			if(empty($halaman)){
				$posisi = 0;
				$halaman = 1;
			}else{
				$posisi = ($halaman-1)*$batas;
			}

			//Agung Sunandar 15:07 11/07/2012 menampilkan data Navigasi  langkah 2
			$tampil="$SQL limit $batas OFFSET $posisi ";
			$hasil=pg_query($tampil);
			$no=$posisi+1;
			while ($row1=pg_fetch_array($hasil)){

			?>
			<tr valign="top" class="<?=$class_nya?>" >  
									<td class="TBL_BODY" align="center"><?=$no ?> </td>
									<td class="TBL_BODY" align="center"><?=$row1["mr_no"] ?> </td>
									<td align="left" class="TBL_BODY"><?=$row1["no_reg"] ?></td>
									<td align="left" class="TBL_BODY"><?=$row1["nm_pasien"] ?></td>
									<td align="left" class="TBL_BODY"><?=$row1["tdesc"] ?></td>
									<td align="left" class="TBL_BODY"><?=$row1["layanan"] ?></td>
									<td align="right" class="TBL_BODY"><?=number_format($row1["tagih"],2,",",".") ?></td>
									<td align="left" class="TBL_BODY"><?=$row1["user_input"] ?></td>
									<td align="left" class="TBL_BODY"><?=$row1["poli_input"] ?></td>
									
								</tr>	
								<?
				$jml_js=$jml_js+$row1["tagih"] ;
			$no++;

			} 
			?>
			
					<tr >  
			        	<td class="TBL_HEAD" align="center" colspan="6" height="25" valign="middle"> TOTAL </td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($jml_js,2,",",".") ?></td>
						<td class="TBL_HEAD" align="right" valign="middle">&nbsp;</td>
						<td class="TBL_HEAD" align="right" valign="middle">&nbsp;</td>
					</tr>	
					<?


echo "</table><br>";

//Agung Sunandar 15:07 11/07/2012 Untuk menampilkan page langkah 3
$tampil2=pg_query($SQL);
$jmldata=pg_num_rows($tampil2);
$jmlhalaman=ceil($jmldata/$batas);
$file=$SCR;

//link ke halaman sebelumnya
if($halaman > 1){
$previous=$halaman-1;
echo "<a href=$file?p=$PID&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."&halaman=1&halaman2=".$_GET["halaman2"]."> << First</a> | <a href=$file?p=$PID&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."&halaman=$previous&halaman2=".$_GET["halaman2"]."> < Previous</a> ";
}else{
echo "<< First | < Previous | ";
}

//Tampilkan link halaman 1, 2, 3 ...
for($i=1;$i<=$jmlhalaman;$i++)
	if($i != $halaman){
		echo "<a href=$file?p=$PID&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."&halaman=$i&halaman2=".$_GET["halaman2"].">$i</a> | ";
	}else{
		echo "<b>$i</b> | ";
	}

//link ke halaman berikutnya (next)
if($halaman<$jmlhalaman){
	$next=$halaman+1;
	echo "<a href=$file?p=$PID&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."&halaman=$next&halaman2=".$_GET["halaman2"]."> Next </a> | <a href=$file?p=$PID&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."&halaman=$jmlhalaman&halaman2=".$_GET["halaman2"]."> Last >> </a>";
}else{
	echo "Next > | Last >>";
}

echo "<p>Total Pasien : <b>$jmldata</b> Layanan</p>";

?>
</pre>
<br>
<br>

<?
if ($_GET["e"]=="I"){
$SQL1 = "select c.mr_no, a.no_reg,c.nama,h.bangsal || ' / ' || g.bangsal || ' / ' || i.tdesc || ' / ' || f.bangsal as bangsal, ".
              "     e.description, sum(a.tagihan) as tagih,k.nama,
				case 	when a.trans_form='p_peny_dalam' then 'POLIKLINIK INTERNE'
						when a.trans_form='p_saraf' then 'POLIKLINIK SARAF'
						when a.trans_form='p_mata' then 'POLIKLINIK MATA'
						when a.trans_form='p_jantung' then 'POLIKLINIK JANTUNG'
						when a.trans_form='p_laboratorium' then 'LAYANAN LABORATORIUM'
						when a.trans_form='p_radiologi' then 'LAYANAN RADIOLOGI'
						when a.trans_form='p_psikiatri' then 'POLIKLINIK JIWA'
						when a.trans_form='p_ginekologi' then 'KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (GINEKOLOGI)'
						when a.trans_form='p_bedah' then 'POLIKLINIK BEDAH'
						when a.trans_form='p_fisioterapi' then 'UNIT REHABILITASI MEDIK'
						when a.trans_form='p_igd' then 'INSTALASI GAWAT DARURAT'
						when a.trans_form='p_kulit_kelamin' then 'POLIKLINIK KULIT DAN KELAMIN'
						when a.trans_form='p_paru' then 'POLIKLINIK PARU'
						when a.trans_form='p_anak' then 'POLIKLINIK ANAK'
						when a.trans_form='p_gigi' then 'POLIKLINIK GIGI DAN MULUT'
						when a.trans_form='p_operasi' then 'LAYANAN OPERASI'
						when a.trans_form='p_gizi' then 'POLIKLINIK GIZI'
						else 'BANGSAL RAWATAN'	end as poli_input	  
				from rs00008 a  
				left join rs00006 b ON a.no_reg = b.id 
				left join rs00010 j ON b.id = j.no_reg 
				join rs00012 as f on j.bangsal_id = f.id 
				join rs00012 as g on g.hierarchy = substr(f.hierarchy,1,6) || '000000000' 
				join rs00012 as h on h.hierarchy = substr(f.hierarchy,1,3) || '000000000000' 
				join rs00001 as i on g.klasifikasi_tarif_id = i.tc and i.tt = 'KTR'
				left join rs99995 k ON a.user_id = k.uid
				left join rs00002 c ON b.mr_no = c.mr_no 
				left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
				left join rs99996 e ON to_number(a.item_id,'999999999999') = e.id 
				
				where to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and 
				b.rawat_inap ='".$_GET["e"]."' and a.trans_type = 'LTM'  and d.tc::text like '%".$_GET["u"]."%'  and a.referensi = 'P' 
				group by c.mr_no, c.nama, c.pangkat_gol, k.nama, a.no_reg, e.description,h.bangsal,g.bangsal,i.tdesc,f.bangsal,a.trans_form ";
}else{
    $SQL1 = "select c.mr_no, a.no_reg,c.nama as nm_pasien,f.tdesc,".
              "     e.description, sum(a.tagihan) as tagih,g.nama as user_input,
		case 	when a.trans_form='p_peny_dalam' then 'POLIKLINIK INTERNE'
				when a.trans_form='p_saraf' then 'POLIKLINIK SARAF'
				when a.trans_form='p_mata' then 'POLIKLINIK MATA'
				when a.trans_form='p_jantung' then 'POLIKLINIK JANTUNG'
				when a.trans_form='p_laboratorium' then 'LAYANAN LABORATORIUM'
				when a.trans_form='p_radiologi' then 'LAYANAN RADIOLOGI'
				when a.trans_form='p_psikiatri' then 'POLIKLINIK JIWA'
				when a.trans_form='p_ginekologi' then 'KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (GINEKOLOGI)'
				when a.trans_form='p_bedah' then 'POLIKLINIK BEDAH'
				when a.trans_form='p_fisioterapi' then 'UNIT REHABILITASI MEDIK'
				when a.trans_form='p_igd' then 'INSTALASI GAWAT DARURAT'
				when a.trans_form='p_kulit_kelamin' then 'POLIKLINIK KULIT DAN KELAMIN'
				when a.trans_form='p_paru' then 'POLIKLINIK PARU'
				when a.trans_form='p_anak' then 'POLIKLINIK ANAK'
				when a.trans_form='p_gigi' then 'POLIKLINIK GIGI DAN MULUT'
				when a.trans_form='p_operasi' then 'LAYANAN OPERASI'
				when a.trans_form='p_gizi' then 'POLIKLINIK GIZI'
				else 'BANGSAL RAWATAN' end as poli_input			 
			from rs00008 a  
			left join rs00006 b ON a.no_reg = b.id 
			left join rs99995 g ON a.user_id = g.uid
			left join rs00002 c ON b.mr_no = c.mr_no 
			left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
			left join rs00001 f ON (b.poli::text = f.tc and f.tt = 'LYN')
			left join rs99996 e ON to_number(a.item_id,'999999999999') = e.id 
			
			where to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and b.rawat_inap ='".$_GET["e"]."' and 
			a.trans_type = 'LTM'  and d.tc::text like '%".$_GET["u"]."%' and a.referensi = 'P' 
			
			group by c.mr_no, c.nama,g.nama , f.tdesc, a.no_reg, e.description,a.trans_form";
}



?>
<Font size='2'><b>Pelayanan Paket</b></font>
<pre><TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">NO.MR</td>
				<td class="TBL_HEAD"align="center">NO.REG</td>
				<td class="TBL_HEAD"align="center">NAMA PASIEN</td>
				<td class="TBL_HEAD"align="center">POLI/BANGSAL</td>
				<td align="center" class="TBL_HEAD">NAMA LAYANAN/TINDAKAN</td>
				<td width="10%" align="center" class="TBL_HEAD">TOTAL HARGA (Rp.)</td>
				<td width="10%" align="center" class="TBL_HEAD">USER/INPUT</td>
				<td align="center" class="TBL_HEAD">POLI INPUT</td>
			</tr>
			
	
		<?	
			$jml_js2= 0;
			$jml_jp2= 0;
			$jml2= 0;
			$row2=0;
			$i2= 1 ;
			$j2= 1 ;
			$last_id2=1;			
			//Agung Sunandar 15:07 11/07/2012 menambahkan Navigasi langkah 1
			$batas2 = 100 ;
			$halaman2 = $_GET['halaman2'];
			if(empty($halaman2)){
				$posisi2 = 0;
				$halaman2 = 1;
			}else{
				$posisi2 = ($halaman2-1)*$batas2;
			}

			//Agung Sunandar 15:07 11/07/2012 menampilkan data Navigasi  langkah 2
			$tampil2="$SQL1 limit $batas OFFSET $posisi2 ";
			$hasil2=pg_query($tampil2);
			$no2=$posisi2+1;
			while ($row2=pg_fetch_array($hasil2)){

			?>
			<tr valign="top" class="<?=$class_nya?>" >  
									<td class="TBL_BODY" align="center"><?=$no2 ?> </td>
									<td class="TBL_BODY" align="center"><?=$row2["mr_no"] ?> </td>
									<td align="left" class="TBL_BODY"><?=$row2["no_reg"] ?></td>
									<td align="left" class="TBL_BODY"><?=$row2["nm_pasien"] ?></td>
									<td align="left" class="TBL_BODY"><?=$row2["tdesc"] ?></td>
									<td align="left" class="TBL_BODY"><?=$row2["description"] ?></td>
									<td align="right" class="TBL_BODY"><?=number_format($row2["tagih"],2,",",".") ?></td>
									<td align="left" class="TBL_BODY"><?=$row2["user_input"] ?></td>
									<td align="left" class="TBL_BODY"><?=$row2["poli_input"] ?></td>
									
								</tr>	
								<?
				$jml_js2=$jml_js2+$row2["tagih"] ;
			$no++;

			} 
			?>
			
					<tr >  
			        	<td class="TBL_HEAD" align="center" colspan="6" height="25" valign="middle"> TOTAL </td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($jml_js2,2,",",".") ?></td>
						<td class="TBL_HEAD" align="right" valign="middle">&nbsp;</td>
						<td class="TBL_HEAD" align="right" valign="middle">&nbsp;</td>
					</tr>	
</table></pre><br>
<?

//Agung Sunandar 15:07 11/07/2012 Untuk menampilkan page langkah 3
$tampil22=pg_query($SQL1);
$jmldata2=pg_num_rows($tampil22);
$jmlhalaman2=ceil($jmldata2/$batas2);
$file=$SCR;

//link ke halaman sebelumnya
if($halaman2 > 1){
$previous2=$halaman2-1;
echo "<a href=$file?p=$PID&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."&halaman=1&halaman2=1> << First</a> | <a href=$file?p=$PID&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."&halaman=".$_GET["halaman"]."&halaman2=$previous2> < Previous</a> ";
}else{
echo "<< First | < Previous | ";
}

//Tampilkan link halaman 1, 2, 3 ...
for($i2=1;$i2<=$jmlhalaman2;$i2++)
	if($i2 != $halaman2){
		echo "<a href=$file?p=$PID&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."&halaman2=$i2&halaman=".$_GET["halaman"].">$i</a> | ";
	}else{
		echo "<b>$i2</b> | ";
	}

//link ke halaman berikutnya (next)
if($halaman2<$jmlhalaman2){
	$next2=$halaman2+1;
	echo "<a href=$file?p=$PID&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."&halaman=$next2&halaman=".$_GET["halaman"]."> Next </a> | <a href=$file?p=$PID&tc=".$_GET["tc"]."&e=".$_GET["e"]."&f=".$_GET["f"]."&u=".$_GET["u"]."&halaman2=$jmlhalaman&halaman=".$_GET["halaman"]."> Last >> </a>";
}else{
	echo "Next > | Last >>";
}

echo "<p>Total Pasien : <b>$jmldata2</b> Layanan</p>";

?>

<br>
<br>
<? 
} else {
    // search box
    title("<img src='icon/keuangan-2.gif' align='absmiddle' > LAPORAN PENDAPATAN NON-OBAT");
	//title_excel("lap_pend_nonobat");
	title_excel("lap_pend_nonobat&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."");
		
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);


    if (!isset($_GET['tanggal1D'])) {

	$tanggal1D = date("d", time());
	$tanggal1M = date("m", time());
	$tanggal1Y = date("Y", time());
	$tanggal2D = date("d", time());
	$tanggal2M = date("m", time());
	$tanggal2Y = date("Y", time());

    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
	$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
	$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

    } else {

	$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
    }

    /*
    $f->selectSQL("mUNIT", "U N I T",
        "select '' as tc, '' as tdesc union ".
        "select distinct(b.rawat_inap) as tc, case when b.rawat_inap='Y' then 'RAWAT JALAN' when b.rawat_inap='I' then 'RAWAT INAP' else 'IGD' end as tdesc ".
        "from rs00008 a, rs00006 b ".
        "where a.trans_type = 'LTM' and a.no_reg = b.id ", $_GET["mUNIT"],
        $ext);
    */
    
    $f->selectArray("mUNIT", "U N I T",
        Array(""=>"", "Y" => "Rawat Jalan", "I" => "Rawat Inap", "N" => "IGD"), $_GET["mUNIT"],
        $ext);


    $f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='JEP' and tc != '000' Order By tdesc Asc;", $_GET["mPASIEN"],
        $ext);

    $f->submit ("TAMPILKAN");
    $f->execute();
    echo "<br>";
    
    if (!empty($_GET[mUNIT])) {
       $SQL_a = " and b.rawat_inap = '".$_GET["mUNIT"]."' ";
    } else {
       $SQL_a = " and b.rawat_inap = '".$_GET["mUNIT"]."' ";
    }

    if (!empty($_GET[mPASIEN])) {
       $SQL_b = " and b.tipe like '%".$_GET["mPASIEN"]."%' ";
    } else {
       $SQL_b = " and b.tipe like '%".$_GET["mPASIEN"]."%' ";
    }

    if (strlen($_GET["search"]) > 0) {
        $r2 = pg_query($con, "select sum(jum) as jum,rawatan ".
              "from rsv0010 ".
              "where upper(rawatan) LIKE '%".strtoupper($_GET["search"])."%' ".
              "group by rawatan");
    } else {
	//benerin kondisi supaya hasil ga dikali jumlah pasien (28102010 najla)
        $r2 = pg_query($con,
                "select sum(a.tagihan) as jum ".
                "from rs00008 a ".
                "   left join rs00006 b ON a.no_reg = b.id ".
                "where a.trans_type='LTM' and ".
                "   (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2')  ".
                "   $SQL_a ".
                "   $SQL_b ");
    }
    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
//benerin kondisi supaya hasil ga dikali jumlah pasien (28102010 najla)

    $SQL = 	"select tanggal(a.tanggal_trans,0) as tanggal_trans_str, 
				case when b.rawat_inap='I' then 'RAWAT INAP' 
					 when b.rawat_inap='Y' then 'RAWAT JALAN' else 'IGD' end as rawatan, 
			to_char(sum(a.tagihan),'999,999,999,999.99') as jum, to_char(a.tanggal_trans,'YYYYMMDD') as flg1 
			from rs00008 a 
			left join rs00006 b ON a.no_reg = b.id 
			where a.trans_type='LTM' and (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') $SQL_a  $SQL_b 
			group by a.tanggal_trans, b.rawat_inap ";
	
	if (!isset($_GET[sort])) {
           $_GET[sort] = "tanggal_trans";
           $_GET[order] = "asc";
	}

    $t = new PgTable($con, "100%");
    $t->SQL = "$SQL";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "right";
	$t->ColAlign[3] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&e=".$_GET["mUNIT"]."&f=<#3#>&u=".$_GET["mPASIEN"]."'>".
                        	icon("view","View")."</A>";
    //$t->ColFormatMoney[2] = "%!+#2n";
    $t->ColHeader = array("TANGGAL TRANSAKSI","U N I T","JUMLAH TRANSAKSI (Rp.)", "V i e w");
    $t->ColFooter[2] =  number_format($d2->jum,2);
    $t->execute();

}

?>

