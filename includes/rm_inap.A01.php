<?
//heri 23 august 2007
// status = udah di tes.

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["resume_dewasa_anak"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.*,c.nama,c.mr_no,c.umur,c.jenis_kelamin,c.nama_ayah,c.pangkat_gol,c.nrp_nip,c.kesatuan,c.agama, ".
					"	to_char(f.ts_check_in,'dd Mon YYYY')as tgl_masuk,to_char(f.ts_calc_stop,'dd Mon yyyy')as tgl_keluar, ".
					"	to_char(a.tanggal_reg,'DD MON YYYY HH24:MI:SS')as tanggal_reg,(g.nama)as merawat,(h.nama)as mengirim,c.jabatan,c.sukubangsa ".
					"from c_visit_ri a ".
					"left join rsv_pasien2 c on a.no_reg=c.id ".
					"join rs00010 as f on f.no_reg = c.id  ".
					"left join rs00017 g on CAST (a.vis_1 AS INTEGER) = g.id  ".
					"left join rs00017 h on CAST (a.vis_2 AS INTEGER) = h.id ".
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
			echo "<tr><td class='tbl_body' valign=top  >";
			$f = new ReadOnlyForm2();
			$f->text2b("Nama Lengkap",$d["nama"],"No. RM",$d["mr_no"]);
			$f->text2b("Umur",$d["umur"],"Suku Bangsa",$d["sukubangsa"]);
			$f->text2b("Jenis Kelamin",$d["jenis_kelamin"],"Agama",$d["agama"]);
			$f->text2b("Keluarga dari",$d["nama_ayah"],"Tanggal Masuk",$d["tgl_masuk"]);
			$f->text2b("Pangkat/ NRP",$d["pangkat_gol"]."&nbsp;". $d["nrp_nip"],"Tanggal Keluar",$d["tgl_keluar"]);
			$f->text2b("Jabatan",$d["jabatan"],"Dr. yang merawat",$d["merawat"]);
			$f->text2b("Kesatuan",$d["kesatuan"],"Dr. Pengirim",$d["mengirim"]);
			$f->text2b("Bangsal",$bangsal,"Alat Pengirim",$d[6]);
			$f->execute();
		    echo "</td></tr>";
		    
		    //echo "<table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=0>";
			echo "<tr><td class='tbl_body' valign=top  >";
    		$f = new ReadOnlyForm();
    		$f->title1("DIAGNOSA","LEFT");
			$f->text($visit_ri_resume["vis_4"],$d[7] );
			$f->text($visit_ri_resume["vis_5"],$d[8]);
			$f->text($visit_ri_resume["vis_6"],$d[9]);
			$f->text($visit_ri_resume["vis_7"]."&nbsp;&nbsp;&nbsp;&nbsp;",$d[10]);
			$f->text($visit_ri_resume["vis_8"],$d[11] );    
			$f->text($visit_ri_resume["vis_9"],$d[12]);
			$f->text($visit_ri_resume["vis_10"],$d[13]);
			$f->title1("LABORATORIUM","LEFT");
			$f->text($visit_ri_resume["vis_11"],$d[14]);
			$f->text($visit_ri_resume["vis_12"],$d[15] );			
			$f->text($visit_ri_resume["vis_13"],$d[16] );
			$f->text($visit_ri_resume["vis_14"],$d[17]);
			$f->text($visit_ri_resume["vis_15"],$d[18]);
			$f->text($visit_ri_resume["vis_16"],$d[19]);
			$f->text($visit_ri_resume["vis_17"],$d[20] );	
			$f->text($visit_ri_resume["vis_18"],$d[21] );
			$f->title1("KEPERLUAN","LEFT");
			$f->text($visit_ri_resume["vis_19"],$d[22]);
			$f->text($visit_ri_resume["vis_20"],$d[23]);
			$f->text($visit_ri_resume["vis_21"],$d[24]);
			$f->text($visit_ri_resume["vis_22"],$d[25] );
			$f->execute();	
    		echo "</td></tr></table>";
  			
    		//include(rm_tindakan);
  					
		echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
