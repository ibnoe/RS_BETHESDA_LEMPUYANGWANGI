<?
//heri 29 August 2007
//status udah dicek

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["laporan_pembedahan"];

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
		    			    
			echo "<DIV>";
			//echo "<br>";

			echo "<table class='tbl_border' border='0' width='100%' cellspacing=1 cellpadding=0>";
			echo "<tr><td class='tbl_body' valign=top width='34%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama","<b>". $d2->nama."</b>");
		    $f->text("Umur",$d2->umur);
			$f->text("DD/Diagnosis",$d2->diagnosa_sementara);
		    $f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='23%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM","<b>". $d2->mr_no."</b>");
			$f->text("Tgl Masuk",$d2->tanggal_reg);
		    $f->text("Seks",$d2->jenis_kelamin);
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='43%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Pangkat / NRP", $d2->pangkat_gol." ".$d2->nrp_nip );
		    $f->text("Kesatuan",$d2->kesatuan);
		    $f->text("Ruang ",$bangsal);
		    $f->execute();
		    echo "</td></tr></table>"; 
		    
		    $sql = "select vis_1, vis_2, vis_3, vis_4, vis_5, vis_6, vis_7, vis_8, vis_9, vis_10, vis_11, vis_12, vis_13, ".
		    		" 	vis_14, vis_15, vis_16 ".
		    		"from c_visit_ri ".					
					"where no_reg='{$_GET['id']}' and id_ri= '{$_GET["mPOLI"]}' ";
					
			$r = pg_query($con,$sql);
			$n = pg_num_rows($r);
		    if($n > 0) $d = pg_fetch_array($r);
		    pg_free_result($r);
		    
		?>
		    <table class="tbl_border" border="0" width="100%" cellspacing="1" cellpadding="5">
			    <tr>
			    	<td align="left" class="tbl_body" width="45%">Bedah : <?=$d["vis_1"]?></td>
			    	<td align="left" class="tbl_body" width="30%">Asisten Bedah : <?=$d["vis_2"]?></td>
			    	<td align="left" class="tbl_body" width="25%">Konsulen : <?=$d["vis_5"]?></td>		    
			    </tr>
			    <tr>
			    	<td align="left" class="tbl_body">Anestesi : <?=$d["vis_3"]?></td>
			    	<td align="left" class="tbl_body" colspan="2">Asisten Anestesi : <?=$d["vis_4"]?></td>
			    </tr>
				<tr>
			    	<td align="left" class="tbl_body">Pra. Bedah : <?=$d["vis_6"]?></td>
			    	<td align="left" class="tbl_body" colspan="2">Diagnosa Pasca Bedah : <?=$d["vis_7"]?></td>
			    </tr>
			    <tr>
			    	<td align="left" class="tbl_body"></td>
			    	<td align="left" class="tbl_body" colspan="2">Posisi : <?=$d["vis_8"]?></td>
			    </tr>
			</table>
			
					<? 
						if ($d["vis_10"] == "Besar"){ 
				    		$b1 = "<b>";
				    		$check1 = "</b><img src='images/icon-cek.gif'>";
				    	}elseif ($d["vis_10"] == "Sedang"){ 
				    		$b2 = "<b>";
				    		$check2 = "</b><img src='images/icon-cek.gif'>";
				    	}elseif ($d["vis_10"] == "Kecil"){ 
				    		$b3 = "<b>";
				    		$check3 = "</b><img src='images/icon-cek.gif'>";
				    	}
				    	
				    	if ($d["vis_11"] == "Berencana"){ 
				    		$b4 = "<b>";
				    		$check4 = "</b><img src='images/icon-cek.gif'>";
				    	}elseif ($d["vis_11"] == "G.Darurat"){ 
				    		$b5 = "<b>";
				    		$check5 = "</b><img src='images/icon-cek.gif'>";
				    	}
				    	
				    	if ($d["vis_15"] == "Local"){ 
				    		$b6 = "<b>";
				    		$check6 = "</b><img src='images/icon-cek.gif'>";
				    	}elseif ($d["vis_15"] == "Spinal"){ 
				    		$b7 = "<b>";
				    		$check7 = "</b><img src='images/icon-cek.gif'>";
				    	}elseif ($d["vis_15"] == "Umum"){ 
				    		$b8 = "<b>";
				    		$check8 = "</b><img src='images/icon-cek.gif'>";
				    	}
				    ?>
		
			<table class="tbl_border" border="0" width="100%" cellspacing="1" cellpadding="5">
			    <tr>
			    	<td align="center" class="tbl_body" colspan="5"><b>JENIS PEMBEDAHAN ( <?=$d["vis_9"]?> )</b> </td>		    
			    </tr>
			    <tr>					    
			    	<td align="center" class="tbl_body" width="20%">1.<?=$b1?> Besar <?=$check1?> </td>			    
			    	<td align="center" class="tbl_body" width="20%">2.<?=$b2?> Sedang <?=$check2?></td>
			    	<td align="center" class="tbl_body" width="20%">3.<?=$b3?> Kecil <?=$check3?></td>
			    	<td align="center" class="tbl_body" width="20%">1.<?=$b4?> Berencana <?=$check4?></td>
			    	<td align="center" class="tbl_body" width="20%">2.<?=$b5?> Gawat Darurat <?=$check5?></td>
			    </tr>
				<tr>
			    	<td align="left" class="tbl_body" colspan="2">Mulai (Pukul) : <?=$d["vis_12"]?></td>
			    	<td align="left" class="tbl_body" colspan="2">Selesai (Pukul) : <?=$d["vis_13"]?></td>
			    	<td align="left" class="tbl_body">Lama Pembedahan : <?=$d["vis_14"]?></td>
			    </tr>
			    <tr>
			    	<td align="left" class="tbl_body" colspan="2">Anestesi *:</td>
			    	<td align="center" class="tbl_body">1.<?=$b6?> Local <?=$check6?></td>
			    	<td align="center" class="tbl_body">2.<?=$b7?> Spinal <?=$check7?></td>
			    	<td align="center" class="tbl_body">3.<?=$b8?> Umum <?=$check8?></td>
			    </tr>
			    <tr>
			    	<td align="left" class="tbl_body" valign="top" colspan="5" height="300">
				    	<b>Laporan Pembedahan : </b>
				    	<p>( Uraian dimulai dari bagian tubuh yang di bedah, cara penemuan tindakan yang dilakukan,
				    		exploitasi, indikasi dan tindakan macam penutup luka, dengan lengkap dan jelas,
				    		jaringan yang dikeluarkan drainage, darah yang keluar.)
				    		
				    	</p>			    	
				    	<?=$d["vis_16"]?>
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
