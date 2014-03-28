<?
//heri 23 juli 2007

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["laporan_anestesi"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.*,b.nama,c.tdesc as rujukan 
					from c_visit_ri a 
					left join rs00017 b on a.id_dokter = b.id 
					left join rs00001 c on a.id_rujukan=c.tc and c.tt='LYN' 
					where a.no_reg='{$_GET['id']}' ";
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

			echo "<table border='1' width='100%' cellspacing=0 cellpadding=0>";
			echo "<tr><td valign=top width='32%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama","<b>". $d2->nama."</b>");
		    $f->text("No.RM","<b>". $d2->mr_no."</b>");
		    $f->text("No.Reg",$d2->id);//formatRegNo($d2->id)
		    $f->execute();
		    echo "</td><td valign=top align=left width='25%'>";
			$f = new ReadOnlyForm();
			$f->text("Tgl Masuk",$d2->tanggal_reg);	
		    $f->text("Umur",$d2->umur);
			$f->text("Seks",$d2->jenis_kelamin);
			$f->execute();
		    echo "</td><td valign=top align=left width='43%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Pangkat & NRP/NIP", $d2->pangkat_gol." ".$d2->nrp_nip );
		    //$f->text("NRP / NIP",$d2->nrp_nip);
		    $f->text("Kesatuan",$d2->kesatuan);
		    $f->text("Ruang ",$bangsal);
		    $f->execute();
		    echo "</td></tr></table>"; 
		 		    
		$max = count($visit_ri_riwayat_penyakit_kebidanan) ; 
		$i = 1;
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td>";
			$f = new ReadOnlyForm2();
			$f->text("Rujukan",$d["rujukan"]);
			while ($i<= $max) {		
				if ($visit_ri_riwayat_penyakit_kebidanan["vis_".$i."F"] == "edit") {
				
					$f->text($visit_ri_riwayat_penyakit_kebidanan["vis_".$i],$d[3+$i] );
				}
				if ($visit_ri_riwayat_penyakit_kebidanan["vis_".$i."F"] == "edit2") {
				
					$f->text($visit_ri_riwayat_penyakit_kebidanan["vis_".$i],$d[3+$i] );
				}
				if ($visit_ri_riwayat_penyakit_kebidanan["vis_".$i."F"] == "memo") {
				
					$f->text($visit_ri_riwayat_penyakit_kebidanan["vis_".$i],$d[3+$i]);
				}
				
				$i++ ; 	
			}
			$f->text("Dokter",$d["nama"]);
			$f->execute();
		echo "</td></tr></table>"; 
		
				include(rm_tindakan);
		
		echo "</DIV>";
		//echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
		//echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
