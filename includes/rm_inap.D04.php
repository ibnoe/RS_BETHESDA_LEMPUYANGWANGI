<?
//heri 23 august 2007
// status = udah di tes.

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["dokumen_surat_pengantar"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.*,c.nama,c.mr_no,c.umur,c.jenis_kelamin,c.nama_ayah,c.pangkat_gol,c.nrp_nip,c.kesatuan,c.agama, ".
					"	to_char(f.ts_check_in,'dd Mon YYYY')as tgl_masuk,to_char(f.ts_calc_stop,'dd Mon yyyy')as tgl_keluar, ".
					"	to_char(a.tanggal_reg,'DD MON YYYY HH24:MI:SS')as tanggal_reg,c.jabatan,c.sukubangsa ".
					"from c_visit_ri a ".
					"left join rsv_pasien2 c on a.no_reg=c.id ".
					"join rs00010 as f on f.no_reg = c.id  ".
					//"left join rs00017 g on cast (a.vis_1 as integer) = g.id  ".
					//"left join rs00017 h on cast (a.vis_2 as integer) = h.id ".
					"where a.no_reg='{$_GET['id']}' and a.id_ri= '{$_GET["mPOLI"]}' ";
					
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
			 				    
			echo "<DIV>";
			//echo "<br>";
			
			echo "<table class='tbl_border' border='0' width='100%' cellspacing=1 cellpadding=0>";
			echo "<tr><td class='tbl_body' valign=top width='32%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama","<b>". $d["nama"]."</b>");
		    $f->text("Tgl Masuk",$d["tgl_masuk"]);	
		    $f->text("Umur",$d["umur"]);
		   	$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='25%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM","<b>". $d["mr_no"]."</b>");
		    $f->text("No.Reg",$d["no_reg"]);//formatRegNo($d2->id)
		    $f->text("Seks",$d["jenis_kelamin"]);
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='43%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Pangkat & NRP/NIP", $d["pangkat_gol"]." ".$d["nrp_nip"] );
		    //$f->text("NRP / NIP",$d2->nrp_nip);
		    $f->text("Kesatuan",$d["kesatuan"]);
		    $f->text("Ruang ",$bangsal);
		    $f->execute();
		    //echo "</td></tr></table>"; 
		   
		 		    
		echo "</td></tr><tr><td class='tbl_body' colspan=3 height=750 valign=top><br>";
			//echo "<p class=FORM align=left >";
			echo $d[4];
			
		echo "</td></tr></table>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
