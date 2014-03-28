<?php 
$PID = "lap_askep";
$SC = $_SERVER["SCRIPT_NAME"];
//require_once("lib/dbconn.php");
require_once("startup.php");
require_once("lib/functions.php");
//ini_set('display_errors',1);
if($_GET["tc"] == "view") {
    pendapatan_dokter("Pendapatan Perawat");	
	$tp = getFromTable(
               "select a.jabatan_medis_fungsional from rs00018 a, rs00017 b ".
               "where  b.jabatan_medis_fungsional_id=a.id and b.id = '".$_GET["dok"]."'");
	$nama = getFromTable("SELECT nama FROM rs00017 WHERE id = '".$_GET['dok']."'");
    $pasien = getFromTable("SELECT tdesc FROM rs00001 WHERE tc = '".$_GET['tipe']."' AND tt='JEP'"); 
	$poli = getFromTable(
               "select tdesc from rsv_jasa_medis ".
               "where tc = '".$_GET["poli"]."' group by tdesc");
	$bulan = tanggal_format($_GET["t1"], 'd-M-Y');    
    $bulan1 = tanggal_format($_GET["t2"], 'd-M-Y');
if (!$GLOBALS['print']){
	echo " <br/><div align='right'><img src=\"icon/back.gif\" align='absmiddle'><a class='SUB_MENU' href='index2.php".
            "?p=$PID'>Kembali</a></div>";
	title_excel("pendapatan_dokter&tc=".$_GET["tc"]."&dok=".$_GET["dok"]."&t1=".$_GET["t1"]."&t2=".$_GET["t2"]."&tipe=".$_GET["tipe"]."&inap=".$_GET["inap"]."&poli=".$_GET["poli"]."");}
    $f = new Form("");
	echo "<br>";
	echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><FONT SIZE='1em'><b> PERIODE </td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $bulan s/d $bulan1 </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> NAMA DOKTER </td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $nama </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> JABATAN MEDIS FUNGSIONAL </td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $tp </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> TIPE PASIEN</td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $pasien </td>";
	echo "</tr>";
	echo "<tr>";
	if (($_GET["inap"]=="Y")||($_GET["inap"]=="N")){		   
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> POLI </td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $poli </td>";
    }else if ($_GET["inap"]=="I"){
	echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> BANGSAL </td>";
	echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $_GET[poli] </td>";
	}else if (($_GET["inap"]=="A")||($_GET["inap"]=="O")){		
	echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> TINDAKAN </td>";
	echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>";
	echo ($_GET["inap"]=="A")? ": ANESTESI":": OPERASI";
	echo "</td>";
	}
	echo "</tr>";
	echo "</table>";

    $f->execute();
    echo "<br>";
	title("Rincian Layanan");
//Jika Rawat Jalan / IGD	
if (($_GET["inap"]=="Y") || ($_GET["inap"]=="N")){	
$sql="select tanggal(a.tanggal_entry,0) as tanggal,a.tipe, a.no_reg,c.mr_no,c.nama, a.layanan, a.harga_atas, a.harga_bawah, a.tagihan,a.jasmed_rj as jasa_dokter, (a.tagihan-jasmed_rj) AS jasa_rs,a.diskon
from rsv_jasa_medis a
left join rs00006 b on a.no_reg=b.id
left join rs00002 c on c.mr_no=b.mr_no 
where (a.tanggal_entry between '".$_GET["t1"]."' and '".$_GET["t2"]."') and a.tipe='".$_GET["tipe"]."' and a.is_inap='".$_GET["inap"]."' and id_dokter='".$_GET["dok"]."' and tc= '".$_GET["poli"]."'";

}
//Jika Rawat Inap
else if($_GET["inap"]=="I"){
$sql="select tanggal(a.tanggal_entry,0) as tanggal, a.no_reg,c.mr_no,c.nama, a.layanan, a.harga_atas, a.harga_bawah, a.tagihan,a.jasmed_ri AS jasa_dokter,(a.tagihan-jasmed_ri) AS jasa_rs,a.diskon
from rsv_jasa_medis_i a
left join rs00006 b on a.no_reg=b.id
left join rs00002 c on c.mr_no=b.mr_no 
where (a.tanggal_entry between '".$_GET["t1"]."' and '".$_GET["t2"]."') and bangsal2 = '".$_GET['poli']."' and a.tipe='".$_GET["tipe"]."' and a.is_inap='".$_GET["inap"]."' and id_dokter='".$_GET["dok"]."'";
}
//echo $sql;
@$r1 = pg_query($con,$sql);
			@$n1 = pg_num_rows($r1);			
   			$max_row= $n1 ;//30
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
//Jika Rawat Jalan / IGD /  Rawat Inap			
if (($_GET["inap"]=="Y") || ($_GET["inap"]=="N") || ($_GET["inap"]=="I")){			
?>
<table align="center" class="TBL_BORDER" WIDTH='100%' border="1" cellspacing="0" cellpadding="1">
			<tr class="NONE" bgcolor="#00CCCC">     	
				<td class="TBL_HPD" width="3%" align="center"><b>NO</b></td>
				<td width="10%" class="TBL_HPD" align="center"><b>TANGGAL</B></td>
				<td class="TBL_HPD" align="center"><b>NO.REG</b></td>
				<td class="TBL_HPD" align="center"><b>NO.MR</b></td>
				<td width="25%" class="TBL_HPD" align="center"><b>NAMA PASIEN</b></td>
				<td width="30%" class="TBL_HPD" align="center"><b>LAYANAN</b></td>
				<td width="20%" align="center" class="TBL_HPD"><b>DISKON</b></td>
				<td width="6%" align="center" class="TBL_HPD"><b>TOTAL (Rp.)</b></td>
			</tr>	
		<?	
			$jml_js=0;
			$jml_jd=0;
			$jml_jp=0;
			$jml_jr=0;
			$jml_ja=0;
			$jml_jb=0;
			$jml= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i;
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td bgcolor="#00CCCC" class="TBL_BPD" align="center"><?=$no ?> </td>
			        	<td class="TBL_BPD" align="center"><?=$row1["tanggal"] ?> </td>
						<td align="left" class="TBL_BPD"><?=$row1["no_reg"] ?></td>
						<td align="left" class="TBL_BPD"><?=$row1["mr_no"] ?></td>
						<td align="left" class="TBL_BPD"><?=$row1["nama"] ?></td>
						<td align="left" class="TBL_BPD"><?=$row1["layanan"] ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["diskon"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["tagihan"],2,",",".") ?></td>						
					</tr>	
					<?
					$jml_js=$jml_js+$row1["jasa_dokter"] ;
					$jml_jd=$jml_jd+$row1["diskon"] ;
					$jml_jr=$jml_jr+$row1["jasa_rs"] ;
					$jml=$jml+$row1["tagihan"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>		
					<tr class="NONE" bgcolor="#00CCCC">  
			        	<td align="center" colspan="6" height="25" valign="middle"> TOTAL (Rp.)</td>
						<td align="right" valign="middle"><?=number_format($jml_jd,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml,2,",",".") ?></td>
					</tr>	
</table>
<?php 
}
/** end $_GET["tc"] == "view" */   
} else {
   if (!$GLOBALS['print']){
		pendapatan_dokter("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Perawat");
	title_excel("pendapatan_dokter&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&rawat_inap=".$_GET["rawat_inap"]."&mRAWAT=".$_GET["mRAWAT"]."&mPASIEN=".$_GET["mPASIEN"]."");	
	//title_excel("pembagian_jm&tc=".$_GET["tc"]."&dok=".$_GET["dok"]."&t1=".$_GET["t1"]."&t2=".$_GET["t2"]."&tipe=".$_GET["tipe"]."&inap=".$_GET["inap"]."&poli=".$_GET["poli"]."");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pendapatan Per Dokter");
    }	
	$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	if (!$GLOBALS['print']) {
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
		$f->selectArray("rawat_inap", "Tipe Jasa Medis",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap", // "A" => "Anestesi",  "O" => "Operasi"
		),$_GET[rawat_inap], "onChange='document.Form1.submit();'; ");
		$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
		if ($_GET["rawat_inap"]=="Y"){
		$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
												 SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
												 order by tdesc ",$_GET["mRAWAT"], "");
		}else if ($_GET["rawat_inap"]=="I"){
		$f->selectSQL("mINAP", "Bangsal ","SELECT d.bangsal, d.bangsal AS bangsals
						FROM rs00010 AS a 
						JOIN rs00012 AS b ON a.bangsal_id = b.id 
						JOIN rs00012 AS c ON c.hierarchy = SUBSTR(b.hierarchy,1,6) || '000000000' 
						JOIN rs00012 AS d ON d.hierarchy = SUBSTR(b.hierarchy,1,3) || '000000000000' 
						JOIN rs00001 AS e ON c.klasifikasi_tarif_id = e.tc AND e.tt = 'KTR'
						GROUP BY d.bangsal
						UNION
						SELECT '',''
						ORDER BY bangsals " ,$_GET["mINAP"], "");
			}
		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"");
/* 		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE c.tc_poli=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "102"); */							 
	    $f->submit ("TAMPILKAN");
	    $f->execute();

	} else {
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
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
	    }
		$f->selectArray("rawat_inap", "U n i t",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "disabled");
		$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
		if ($_GET["rawat_inap"]=="Y"){
		$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
												 SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
												 order by tdesc ",$_GET["mRAWAT"], "disabled");
		}else if ($_GET["rawat_inap"]=="I"){
		$f->selectSQL("mINAP", "Bangsal ","select d.bangsal, d.bangsal as bangsal
						   from rs00010 as a 
							   join rs00012 as b on a.bangsal_id = b.id 
							   join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
							   join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
							   join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
		group by d.bangsal
		order by d.bangsal " ,$_GET["mINAP"], "disabled");
			}else{}
		
		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"disabled");
/* 		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE c.tc_poli=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "disabled"); */
	}
		if($_GET["rawat_inap"] == "Y" or $_GET["rawat_inap"] == "N" ){
		$SQL1=" select count(is_inap) AS jml_pasien,is_inap,tipe,tipe_p,id_dokter,nama,tdesc,tc, sum(tagihan) as jumlah,sum(jasmed_rj) as jst,sum(tagihan-jasmed_rj) as jrt_rs,sum(diskon) as jsd
				from rsv_jasa_medis
				where (tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and is_inap like '%".$_GET["rawat_inap"]."%' and tc like '%".$_GET["mRAWAT"]."%' and tipe like '%".$_GET["mPASIEN"]."%' AND id_rs_18 = '230'
				group by id_dokter,nama,tdesc, tipe,is_inap,tipe,tipe_p,tc 
				order by nama ";
		}else if ($_GET["rawat_inap"]=="I"){
		$SQL1=" select is_inap,tipe,tipe_p,id_dokter,nama,sum(harga_atas) as js, sum(harga_bawah) as jp, sum(tagihan) as jumlah, bangsal2, sum(jasmed_ri) as jst,sum(tagihan-jasmed_ri) as jrt_rs,sum(diskon) as jsd
				from rsv_jasa_medis_i
				where (tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and tipe like '%".$_GET["mPASIEN"]."%' and is_inap like '%".$_GET["rawat_inap"]."%' and (bangsal like '%".$_GET["mINAP"]."%' or bangsal2 like '%".$_GET["mINAP"]."%') 
				AND id_rs_18 = '230'
				group by is_inap,tipe,tipe_p,id_dokter,nama, bangsal2
				order by nama ";

		}
			@$r1 = pg_query($con,$SQL1);
			@$n1 = pg_num_rows($r1);
			//echo $n1;
   			$max_row= $n1 ;//30
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}
			if($_GET["rawat_inap"] == "Y" || $_GET["rawat_inap"] == "N" || $_GET["rawat_inap"] == "I" ){ 
?>
<table align="center" class='TBL_BORDER' width='100%' border='1' cellspacing='1' cellpadding='1'>
			<tr class="NONE" bgcolor="#00CCCC">     	
				<td class="TBL_HPD" width="4%" align="center"><b>NO</b></td>				
				<td width="25%" class="TBL_HPD" align="center"><b>NAMA</b></td>
				<? if($_GET["rawat_inap"] != "I"){ ?>
				<td width="24%" class="TBL_HPD" align="center"><b>POLI</b></td>
				<? }else{ ?>
				<td class="TBL_HPD" align="center"><b>BANGSAL</b></td>
				<? } ?>
				<td width="10%" class="TBL_HPD" align="center"><b>TIPE PASIEN</b></td>
				<td align="center" class="TBL_HPD"><b>JASA (Rp.)</b></td>
				<td width="10%" align="center" class="TBL_HPD"><b>PELAYANAN (Rp.)</b></td>
				<td width="5%" align="center" class="TBL_HPD"><b>DETAIL</b></td>
			</tr>	
		<?php	
			$jml_jst=0 ;
		        $jml_jsd=0 ;
			$jml_jpt=0 ;
			$jml_jrt=0 ;
			$jml_jat=0 ;
			$jml_jbt=0 ;
			$jml= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i ;		
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BPD" align="center" bgcolor="#00CCCC"><?=$no ?> </td>
						<? if($row1["nama"]!=''){?>
						<td align="left" class="TBL_BPD"><?=$row1["nama"] ?></td>
						<?}else{?>
						<td align="left" class="TBL_BPD"><font color="#00CCCC"><center><b><i>Administrasi</i></b></center></td>						<?}?>						
						<? if($_GET["rawat_inap"] != "I"){ ?>
						<td align="left" class="TBL_BPD"><?=$row1["tdesc"] ?></td>
						<? }else{ ?>
						<td align="left" class="TBL_BPD"><?=$row1["bangsal2"] ?></td>
						<? }?>
						<td align="left" class="TBL_BPD"><?=$row1["tipe_p"] ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jsd"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jumlah"],2,",",".") ?></td>
						<? if($_GET["rawat_inap"] != "I"){ ?>
						<td align="center" class="TBL_BPD" valign="middle" bgcolor="#00CCCC"><?=$t->ColFormatHtml[2] = "<a class='TBL_HREF' href='$SC?p=$PID&tc=view&dok=".$row1['id_dokter']."&t1=$ts_check_in1"."&t2=$ts_check_in2&tipe=".$row1['tipe']."&inap=".$row1['is_inap'] ."&poli=".$row1['tc'] ."'>".icon("view","View")."</a>";?></td>
						<? }else{ ?>
						<td bgcolor="#00CCCC" align="center" class="TBL_BPD" valign="middle"><?=$t->ColFormatHtml[2] = "<a class='TBL_HREF' href='$SC?p=$PID&tc=view&dok=".$row1['id_dokter']."&t1=$ts_check_in1"."&t2=$ts_check_in2&tipe=".$row1['tipe']."&inap=".$row1['is_inap'] ."&poli=".$row1['bangsal2'] ."'>".icon("view","View")."</a>";?></td>
						<? } ?>
					</tr>	
					<?php

					$jml_jst=$jml_jst+$row1["jst"] ;
					$jml_jsd=$jml_jsd+$row1["jsd"];
					$jml_jrt=$jml_jrt+$row1["jrt_rs"] ;
					$jml=$jml+$row1["jumlah"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){
					$last_id=$row1->no_reg;
					}		
			} 
			?>			
					<tr class="NONE" bgcolor="#00CCCC">  
			        	<td class="TBL_HPD" align="center" colspan="4" height="25" valign="middle"><b> TOTAL </b></td>
					<td class="TBL_HPD" align="right" valign="middle"><b><?=number_format($jml_jsd,2,",",".") ?></b></td>
						<td class="TBL_HPD" align="right" valign="middle"><b><?=number_format($jml,2,",",".") ?></b></td>
						<td class="TBL_HPD" align="right" valign="middle">&nbsp;</td>
					</tr>	
</table>

<?php
}
?>

<?php
}
?>
