<?
//heri sept 2007
//udah di tes
$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["laporan_pemakaian_alat"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.*,b.nama,c.tdesc as rujukan,to_char(a.tanggal_reg,'DD - MM - YYYY')as tgl_entry
					from c_visit_ri a 
					left join rs00017 b on CAST (a.vis_2 AS INTEGER) = b.id 
					left join rs00001 c on a.id_rujukan=CAST (c.tc AS NUMERIC) and c.tt='LYN' 
					where a.no_reg='{$_GET['id']}' and a.id_ri= 'G03' ";
			$r = pg_query($con,$sql);
			$n = pg_num_rows($r);
		    if($n > 0) $d = pg_fetch_array($r);
		    pg_free_result($r);
		
			 	// ambil bangsal
			    $id_max = getFromTable("select max(id) from rs00010 where no_reg = '".$_GET["id"]."'");
			    if (!empty($id_max)) {
			    $bangsal = getFromTable("select c.bangsal || ' / ' || e.tdesc ".
			                       "from rs00010 as a ".
			                       "    join rs00012 as b on a.bangsal_id = b.id ".
			                       "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
			                       //"    join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
			                       "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
			                       "where a.id = '$id_max'");
			    }
			    //echo $bangsal;
						$sql2 = "select a.id,a.mr_no,a.nama,a.umur,a.tgl_lahir,a.tmp_lahir,a.tanggal_reg,a.status_akhir, ".
								"a.pangkat_gol,a.nrp_nip,a.kesatuan, a.jenis_kelamin ".
								"from rsv_pasien2 a  ".
								"where a.id= '{$_GET['id']}'";			
						
						$r2 = pg_query($con,$sql2);
						$n2 = pg_num_rows($r2);
					    if($n2 > 0) $d2 = pg_fetch_object($r2);
					    pg_free_result($r2);
		    			    
			echo "<DIV>";
			//echo "<br>";

			echo "<table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=0>";
			echo "<tr><td class='TBL_BODY' valign=top width='32%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama","<b>". $d2->nama."</b>");
		    $f->text("Umur",$d2->umur);
			$f->text("Tgl Masuk",$d2->tanggal_reg);
		    $f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='23%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM","<b>". $d2->mr_no."</b>");
			$f->text("No.Reg",$d2->id);
			$f->text("Seks",$d2->jenis_kelamin);
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='43%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Pangkat / NRP", $d2->pangkat_gol." ".$d2->nrp_nip );
		    $f->text("Kesatuan",$d2->kesatuan);
		    $f->text("Ruang ",$bangsal);
		    $f->execute();
		    echo "</td></tr>";  
		    echo "<tr><td class='TBL_BODY' valign=top colspan='3'>";
		    ?>
				<br>
		    	<table width="100%">
		    		<tr valign="top">
		    			<td align="left" class="form" width="50%">Dosis : <?=$d["vis_1"]?></td>
		    			<td align="left" class="form" width="50%">Jenis Tindakan : <?=$d["vis_56"]?></td>
		    		</tr>
		    	</table>
		    	<br>
		    <?
		    echo "</td></tr>";
		    echo "<tr><td class='TBL_BODY' valign=top colspan='3'>";
		    		 		    			
				?> <br>
					<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>  
						<tr class='TBL_HEAD' valign='top' >
							<td align='center' width="5%" ><b>No</b></td>
							<td align='center' width="65%"><b>Jenis Obat-obatan dan Alkes</b> </td>
							<td align='center' width="15%"><b>Satuan</b></td>	
							<td align='center' width="15%"><b>Jumlah</b></td>
						</tr>	
												
						<tr class="TBL_BODY" >
							<td align="center">1.</td><td align="left"><b>Infus</b></td><td>&nbsp;</td><td>&nbsp;</td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">1.<?=$d["vis_4"]?></td>
							<td align="center"><?=$d["vis_9"]?></td>
							<td align="center"><?=$d["vis_14"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">2.<?=$d["vis_5"]?></td>
							<td align="center"><?=$d["vis_10"]?></td>
							<td align="center"><?=$d["vis_15"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">3.<?=$d["vis_6"]?></td>
							<td align="center"><?=$d["vis_11"]?></td>
							<td align="center"><?=$d["vis_16"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">4.<?=$d["vis_7"]?></td>
							<td align="center"><?=$d["vis_12"]?></td>
							<td align="center"><?=$d["vis_17"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">5.<?=$d["vis_8"]?></td>
							<td align="center"><?=$d["vis_13"]?></td>
							<td align="center"><?=$d["vis_18"]?></td>
						</tr>
						
						<tr class="TBL_BODY" >
							<td align="center">2.</td><td align="left"><b>Obat-obatan</b></td><td>&nbsp;</td><td>&nbsp;</td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">1.<?=$d["vis_19"]?></td>
							<td align="center"><?=$d["vis_24"]?></td>
							<td align="center"><?=$d["vis_29"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">2.<?=$d["vis_20"]?></td>
							<td align="center"><?=$d["vis_25"]?></td>
							<td align="center"><?=$d["vis_30"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">3.<?=$d["vis_21"]?></td>
							<td align="center"><?=$d["vis_26"]?></td>
							<td align="center"><?=$d["vis_31"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">4.<?=$d["vis_22"]?></td>
							<td align="center"><?=$d["vis_27"]?></td>
							<td align="center"><?=$d["vis_32"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">5.<?=$d["vis_23"]?></td>
							<td align="center"><?=$d["vis_28"]?></td>
							<td align="center"><?=$d["vis_33"]?></td>
						</tr>
						
						<tr class="TBL_BODY" >
							<td align="center">3.</td><td align="left"><b>Alat Kesehatan</b></td><td>&nbsp;</td><td>&nbsp;</td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">1.<?=$d["vis_34"]?></td>
							<td align="center"><?=$d["vis_41"]?></td>
							<td align="center"><?=$d["vis_48"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">2.<?=$d["vis_35"]?></td>
							<td align="center"><?=$d["vis_42"]?></td>
							<td align="center"><?=$d["vis_49"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">3.<?=$d["vis_36"]?></td>
							<td align="center"><?=$d["vis_43"]?></td>
							<td align="center"><?=$d["vis_50"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">4.<?=$d["vis_37"]?></td>
							<td align="center"><?=$d["vis_44"]?></td>
							<td align="center"><?=$d["vis_51"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">5.<?=$d["vis_38"]?></td>
							<td align="center"><?=$d["vis_45"]?></td>
							<td align="center"><?=$d["vis_52"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">6.<?=$d["vis_39"]?></td>
							<td align="center"><?=$d["vis_46"]?></td>
							<td align="center"><?=$d["vis_53"]?></td>
						</tr>
						<tr class="TBL_BODY" >
							<td>&nbsp;</td>
							<td align="left">7.<?=$d["vis_40"]?></td>
							<td align="center"><?=$d["vis_47"]?></td>
							<td align="center"><?=$d["vis_54"]?></td>
						</tr>
					</table><br>										
				<?
			echo "</td></tr><tr><td class='TBL_BODY' valign=top colspan='3' height='40'>Alat yang sengaja ditinggalkan pada pasien : {$d["vis_55"]} </td></tr>";	
			echo "</td></tr></table>";
			?><br><br>
			<table width="100%">
				<tr valign="top"><td width="232" align="center" class="form">&nbsp;</td><td colspan="3">&nbsp;</td><td width="253" class="form" align="center" height="10">Bandung,<?=$d["tgl_entry"]?></td></tr>
				<tr valign="top"><td width="232" align="center" class="form">Mengetahui</td><td colspan="4">&nbsp;</td></tr>
				<tr valign="top"><td class="form" align="center"> Spesialis Bedah</td><td colspan="3">&nbsp;</td><td width="253" class="form" align="center" height="60">Instrumentator</td></tr>
				<tr><td class="form" align="center"><?=$d["nama"]?></td><td colspan="3">&nbsp;</td><td class="form" width="253" align="center"><?=$d["vis_3"] ?></td></tr>
				<tr><td class="form" align="center">----------------------------------</td><td colspan="3">&nbsp;</td><td class="form" width="253" align="center">----------------------------------</td></tr>
			</table>
			
			<?	 
		/*	$f = new ReadOnlyForm();
			$f->title1("Mengetahui");
			$f->text("Dokter Spesialis Bedah",$d["nama"]);
			$f->text("Istrumentator",$d["vis_3"]);
			$f->execute();
		*/
				//include(rm_tindakan);
		
		echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
