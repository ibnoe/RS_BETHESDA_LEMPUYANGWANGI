<?
//heri 23 juli 2007

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["hasil_USG"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.*,c.tdesc as rujukan ,to_char(a.tanggal_reg,'dd Mon YYYY')as tgl_periksa, to_char(a.tanggal_reg,'HH24:MM:SS')as waktu
					from c_visit_ri a 
					
					left join rs00001 c on a.id_rujukan= CAST(c.tc AS NUMERIC) and c.tt='LYN' 
					where a.no_reg='{$_GET['id']}' and a.id_ri='K04' ";
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
			$f->text("Tgl Periksa",$d["tgl_periksa"]);	
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
		   // echo "</td></tr></table>"; 
		 		    
		echo "</td></tr>";
			
		echo "<tr><td colspan=3 height=250 valign=top>".$d["vis_1"];		
		echo "</td></tr></table>";
		
				//include(rm_tindakan);
		
		echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
