<?
//heri 29 August 2007
// udah di cek

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["asuhan_keperawatan"];

	if ($_GET['act'] ==  "detail"){
		
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
								"a.pangkat_gol,a.nrp_nip,a.kesatuan, a.jenis_kelamin,a.diagnosa_sementara ".
								"from rsv_pasien2 a  ".
								"where a.id= '{$_GET['id']}'";			
						
						$r2 = pg_query($con,$sql2);
						$n2 = pg_num_rows($r2);
					    if($n2 > 0) $d2 = pg_fetch_object($r2);
					    pg_free_result($r2);
					    
			$sql = "select to_char(tanggal_reg,'dd Mon YYYY, HH:MM')as tanggal_reg,vis_1, vis_2, vis_3, vis_4, vis_5, vis_6, vis_7, vis_8, vis_9 ".
		    		"from c_visit_ri ".					
					"where no_reg='{$_GET['id']}' and id_ri= '{$_GET["mPOLI"]}' ";
					
			$r = pg_query($con,$sql);
			$n = pg_num_rows($r);
		    if($n > 0) $d = pg_fetch_array($r);
		    pg_free_result($r);
		    			    
			echo "<DIV>";
			//echo "<br>";

			echo "<table class='tbl_border' border='0' width='100%' cellspacing=1 cellpadding=0>";
			echo "<tr><td class='tbl_body' valign=top width='34%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama","<b>". $d2->nama."</b>");
		    $f->text("Umur",$d2->umur);
			$f->text("Tanggal",$d["tanggal_reg"]);
		    $f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='23%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM","<b>". $d2->mr_no."</b>");
			$f->text("No.Reg", $d2->id);
			$f->text("Seks",$d2->jenis_kelamin);
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='43%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Pangkat / NRP", $d2->pangkat_gol." ".$d2->nrp_nip );
		    $f->text("Kesatuan",$d2->kesatuan);
		    $f->text("Ruang ",$bangsal);
		    $f->execute();
		    echo "</td></tr></table><br>"; 
		    
		    
	 	?>
	 		<table class="tbl_border" border="0" width="100%" cellspacing="1" cellpadding="0">
	 			<tr>
	 				<td align="left" class="tbl_body" colspan="2"><p><b>ANAMNESA</b></p>
	 					<table class="form" border="0" cellspacing="2" cellpadding="3">
	 						<tr valign="top">
			 					<td class="form" align="left">I. </td>
			 					<td class="form" align="left">Keluhan Utama  </td>
			 					<td class="form">:</td>
			 					<td class="form" align="left" height="200"><p><?=$d["vis_1"]?></p></td>
			 				</tr>
			 				<tr valign="top">
			 					<td class="form" align="left">II. </td>
			 					<td class="form" colspan="3" align="left">Riwayat Penyakit  </td>
			 				</tr>
			 				<tr valign="top">
			 					<td class="form" align="left"></td>
			 					<td class="form" align="left">a. &nbsp; Dahulu  </td>
			 					<td class="form">:</td>
			 					<td class="form" align="left"><p><?=$d["vis_2"]?></p></td>
			 				</tr>	
			 				<tr valign="top">
			 					<td class="form" align="left"></td>
			 					<td class="form" align="left">b. &nbsp; Sekarang  </td>
			 					<td class="form">:</td>
			 					<td class="form" align="left"><p><?=$d["vis_3"]?></p></td>
			 				</tr> 						 		
	 					</table><br>    
	 				</td>
	 			</tr>
	 			<tr valign="top">
	 				<td align="left" class="tbl_body" width="50%" >
	 					<table class="form" border="0" cellspacing="2" cellpadding="3">
	 						<tr valign="top">
			 					<td class="form" align="left">III. </td>
			 					<td class="form" align="left">Analisa  </td>				 							 					
			 				</tr>
			 				<tr valign="top">
			 					<td class="form" align="left"></td>
			 					<td class="form" colspan="2" rowspan="5" align="left"><?=$d["vis_4"]?>  </td>
			 				</tr>
			 			</table><br>
			 		</td>
			 		<td align="left" class="tbl_body" width="50%" >
			 			<table class="form" border="0" cellspacing="2" cellpadding="3">
	 						<tr valign="top">
			 					<td class="form" align="left">IV. </td>
			 					<td class="form" align="left">Masalah Keperawatan  </td>
			 				</tr>	
			 				<tr valign="top">
			 					<td class="form" align="right">1. </td>
			 					<td class="form" align="left"><?=$d["vis_5"]?></td>			 					
			 				</tr> 
			 				<tr valign="top">
			 					<td class="form" align="right">2. </td>
			 					<td class="form" align="left"><?=$d["vis_6"]?></td>			 					
			 				</tr>
			 				<tr valign="top">
			 					<td class="form" align="right">3. </td>
			 					<td class="form" align="left"><?=$d["vis_7"]?></td>			 					
			 				</tr>
			 				<tr valign="top">
			 					<td class="form" align="right">4. </td>
			 					<td class="form" align="left"><?=$d["vis_8"]?></td>			 					
			 				</tr>
			 				<tr valign="top">
			 					<td class="form" align="right">5. </td>
			 					<td class="form" align="left"><?=$d["vis_9"]?></td>			 					
			 				</tr>						 		
	 					</table><br>
	 				</td>
	 			</tr>	 		
	 		</table>    
		
		<?		
				//include(rm_tindakan);
		
		echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
