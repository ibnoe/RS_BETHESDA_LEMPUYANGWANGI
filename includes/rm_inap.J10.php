<?
//heri 23 juli 2007

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["lembar_konsultasi"];

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
								"a.pangkat_gol,a.nrp_nip,a.kesatuan, a.jenis_kelamin ".
								"from rsv_pasien2 a  ".
								"where a.id= '{$_GET['id']}'";			
						
						$r2 = pg_query($con,$sql2);
						$n2 = pg_num_rows($r2);
					    if($n2 > 0) $d2 = pg_fetch_object($r2);
					    pg_free_result($r2);
		    			    
							$SQL3 = "select to_char(a.tanggal_reg,'dd mm YYYY  HH:MM')as tgl_periksa,a.vis_1,a.vis_2,a.vis_3,a.vis_4,a.vis_5,a.vis_6,b.nama as dokter ".
									"from c_visit_ri a ".
									"LEFT JOIN RS00017 b ON CAST (a.vis_7 as INTEGER) = b.id ".
									"where a.no_reg='{$_GET['id']}' and a.id_ri='{$_GET["mPOLI"]}' ";	
									
							$r3 = pg_query($con,$SQL3);
							$n3 = pg_num_rows($r3);
							$d = pg_fetch_array($r3);
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
		    //echo "</td></tr></table>"; 
		 		    
		echo "</td></tr><tr><td colspan=3 class='tbl_body'>";
		 		    
			$f = new ReadOnlyForm2();
			//$f->text("Rujukan",$d["rujukan"]);
			
			$f->text3b("Tanggal",$d["tgl_periksa"],"Dokter",$d["dokter"],$visit_ri_lembar_konsultasi["vis_1"],$d["vis_1"]);
			$f->hr();
			$f->text($visit_ri_lembar_konsultasi["vis_2"],$d["vis_2"]);			
			$f->text($visit_ri_lembar_konsultasi["vis_3"],$d["vis_3"],"top","height='150'");
			$f->execute();
			$f = new ReadOnlyForm();	
			$f->text($visit_ri_lembar_konsultasi["vis_4"],$d["vis_4"]);
			$f->execute();
			$f = new ReadOnlyForm2();	
			$f->hr();
			$f->text($visit_ri_lembar_konsultasi["vis_5"],$d["vis_5"],"top","height='100'");			
			$f->text($visit_ri_lembar_konsultasi["vis_6"],$d["vis_6"],"top","height='100'");			
			$f->execute();
		echo "</td></tr></table>"; 
		
				//include(rm_tindakan);
		
		echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
