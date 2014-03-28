<?
//heri 23 juli 2007

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["hasil_EKG"];

	if ($_GET['act'] ==  "detail"){
		
			 $sql = "select a.*,c.tdesc as rujukan ,to_char(a.tanggal_reg,'dd Mon YYYY')as tgl_periksa, to_char(a.tanggal_reg,'HH24:MM:SS')as waktu
					from c_visit_ri a 
					
					left join rs00001 c on a.id_rujukan= CAST(c.tc AS NUMERIC) and c.tt='LYN' 
					where a.no_reg='{$_GET['id']}' and a.id_ri='K03' ";
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
								"a.pangkat_gol,a.nrp_nip,a.kesatuan, a.jenis_kelamin,a.waktu_reg ".
								"from rsv_pasien2 a  ".
								"where a.id= '{$_GET['id']}'";			
						
						$r2 = pg_query($con,$sql2);
						$n2 = pg_num_rows($r2);
					    if($n2 > 0) $d2 = pg_fetch_object($r2);
					    pg_free_result($r2);
		    			    
			echo "<DIV>";
			//echo "<br>";

			echo "<table border='1' width='100%' cellspacing=0 cellpadding=0>";
			echo "<tr><td valign=top width='37%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama","<b>". $d2->nama."</b>");		    
		    $f->text("Tgl.Perekaman",$d["tgl_periksa"] ."&nbsp;&nbsp;&nbsp;Pukul : ".$d["waktu"]);
		    $f->text($visit_ri_hasil_EKG["vis_1"],$d["vis_1"]);
		    $f->execute();
		    echo "</td><td valign=top align=left width='24%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM","<b>". $d2->mr_no."</b>");
		    $f->text("Umur",$d2->umur);
			$f->text("Seks",$d2->jenis_kelamin);
			$f->execute();
		    echo "</td><td valign=top align=left width='40%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Pangkat / NRP/NIP", $d2->pangkat_gol." ".$d2->nrp_nip );
		    $f->text("Kesatuan",$d2->kesatuan);
		    $f->text("Ruang ",$bangsal);
		    $f->execute();
		    //echo "</td></tr></table>"; 
		 		    
		echo "</td></tr><tr><td colspan=3>";
			echo "<table border=1 width='100%' cellspacing=3 cellpadding=3><tr valign='top'>";
				echo "<td width='50%' height='80' align='left'> ";
					echo "<b>".$visit_ri_hasil_EKG["vis_2"]."</b><br>".$d["vis_2"];
				echo "</td><td>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_8"]."</b><br>".$d["vis_8"];
				echo "</td></tr><tr valign='top'><td width='50%' height='80' align='left'>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_3"]."</b><br>".$d["vis_3"];
				echo "</td><td>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_9"]."</b><br>".$d["vis_9"];
				echo "</td></tr><tr valign='top'><td width='50%' height='80' align='left'>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_4"]."</b><br>".$d["vis_4"];
				echo "</td><td>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_10"]."</b><br>".$d["vis_10"];
				echo "</td></tr><tr valign='top'><td width='50%' height='80' align='left'>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_5"]."</b><br>".$d["vis_5"];
				echo "</td><td>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_11"]."</b><br>".$d["vis_11"];
				echo "</td></tr><tr valign='top'><td width='50%' height='80' align='left'>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_6"]."</b><br>".$d["vis_6"];
				echo "</td><td>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_12"]."</b><br>".$d["vis_12"];
				echo "</td></tr><tr valign='top'><td width='50%' height='80' align='left'>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_7"]."</b><br>".$d["vis_7"];
				echo "</td><td>";	
					echo "<b>".$visit_ri_hasil_EKG["vis_13"]."</b><br>".$d["vis_13"];
								
			echo "</td></tr></table>"; 
		echo "</td></tr></table>"; 
		
				//include(rm_tindakan);
		
		echo "</DIV>";
		//echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
		//echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
