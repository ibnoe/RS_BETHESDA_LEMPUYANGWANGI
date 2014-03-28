<?
//heri 23 august 2007
// status = udah di tes.

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["riwayat_penyakit_pemeriksaan_fisik"];

	if ($_GET['act'] ==  "detail"){
		
			 $sql = "select a.*,c.nama,c.mr_no,c.umur,c.jenis_kelamin,c.nama_ayah,c.pangkat_gol,c.nrp_nip,c.kesatuan,c.agama, ".
					"	to_char(f.ts_check_in,'dd Mon YYYY')as tgl_masuk,to_char(f.ts_calc_stop,'dd Mon yyyy')as tgl_keluar, ".
					"	to_char(a.tanggal_reg,'DD MON YYYY HH24:MI:SS')as tanggal_reg,g.nama as merawat,h.nama as mengirim,i.nama as konsul,c.jabatan,c.sukubangsa ".
					"from c_visit_ri a ".
					"left join rsv_pasien2 c on a.no_reg=c.id ".
					"join rs00010 as f on f.no_reg = c.id  ".
					"left join rs00017 g on a.vis_1::numeric = g.id::numeric  ".
					"left join rs00017 h on a.vis_2::numeric = h.id::numeric ".
					"left join rs00017 i on a.id_dokter::numeric = i.id::numeric ".
					"where a.no_reg='{$_GET['id']}' and a.id_ri= 'E05' ";
					
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
			echo "<table width='100%' border='0'>";
    		echo "<tr><td width='50%' align='left'>";
    		$f = new ReadOnlyForm();
			$f->text("Nama Pasien","<b>".$d["nama"]."</b>");
			$f->text("Tanggal/Jam Pemeriksaan",$d["tanggal_reg"]);
			$f->text($visit_ri_riwayat_penyakit["vis_46"],$d[49] );
			$f->text($visit_ri_riwayat_penyakit["vis_3"],$d[6]);
			$f->text($visit_ri_riwayat_penyakit["vis_4"],$d[7] );
			$f->text("<b>ANAMNESA</b>",$d[8] );
			$f->execute();
			echo "</td><td>";
			$f = new ReadOnlyForm();
			$f->text("No. RM ","<b>".$d["mr_no"]."</b>" );
			$f->text($visit_ri_riwayat_penyakit["vis_1"],$d["merawat"] );
			$f->text($visit_ri_riwayat_penyakit["vis_2"],$d["mengirim"]);
			$f->text("Dokter Konsul",$d["konsul"]);
			$f->info("","");
			$f->text($visit_ri_riwayat_penyakit["vis_45"],$d[48]);
			$f->execute();
    		echo "</td></tr><tr><td colspan='2'>";
    		
			$f = new ReadOnlyForm2();
			$f->hr();
			//$f->text_u("<b><u>".$visit_ri_riwayat_penyakit["vis_5"]."</u></b>",$d[8]."&nbsp;" );			
			$f->title1("STATUS PRAESENS","LEFT");
			$f->text_u("1.  ".$visit_ri_riwayat_penyakit["vis_6"],$d[9]."&nbsp;"  );
			$f->text_u("2.  ".$visit_ri_riwayat_penyakit["vis_7"],$d[10]."&nbsp;" );
			$f->text_u("3.  ".$visit_ri_riwayat_penyakit["vis_8"],$d[11]."&nbsp;" );    
			$f->text_u("4.  ".$visit_ri_riwayat_penyakit["vis_9"],$d[12]."&nbsp;" );
			$f->text_u("5.  ".$visit_ri_riwayat_penyakit["vis_10"],$d[13]."&nbsp;Celcius" );
			$f->text_u("6.  ".$visit_ri_riwayat_penyakit["vis_11"],$d[14]."&nbsp;" );
			$f->text_u("7.  ".$visit_ri_riwayat_penyakit["vis_12"],$d[15]."&nbsp;" );	
			$f->text_u("8.  ".$visit_ri_riwayat_penyakit["vis_13"],$d[16]."&nbsp;" );
			$f->text_u("9.  ".$visit_ri_riwayat_penyakit["vis_14"],$d[17]."&nbsp;" );
			$f->text_u("10. ".$visit_ri_riwayat_penyakit["vis_15"],$d[18]."&nbsp;" );
			$f->text_u("11. ".$visit_ri_riwayat_penyakit["vis_16"],$d[19]."&nbsp;" );
			$f->text_u("12. ".$visit_ri_riwayat_penyakit["vis_17"],$d[20]."&nbsp;" );	
			$f->text_u("13. ".$visit_ri_riwayat_penyakit["vis_18"],$d[21]."&nbsp;" );
			$f->text_u("14. ".$visit_ri_riwayat_penyakit["vis_19"],$d[22]."&nbsp;" );
			$f->text_u("15. ".$visit_ri_riwayat_penyakit["vis_20"],$d[23]."&nbsp;" );
			$f->text_u("16. ".$visit_ri_riwayat_penyakit["vis_21"],$d[24]."&nbsp;" );
			$f->text_u("17. ".$visit_ri_riwayat_penyakit["vis_22"],$d[25]."&nbsp;" );	
			$f->text_u("18. ".$visit_ri_riwayat_penyakit["vis_23"],$d[26]."&nbsp;mm Hg" );
			$f->text_u("19. ".$visit_ri_riwayat_penyakit["vis_24"],$d[27]."&nbsp;/ Menit");
			$f->text_u("20. ".$visit_ri_riwayat_penyakit["vis_25"],$d[28]."&nbsp;" );
			$f->text_u("21. ".$visit_ri_riwayat_penyakit["vis_26"],$d[29]."&nbsp;" );
			$f->text_u("22. ".$visit_ri_riwayat_penyakit["vis_27"],$d[30]."&nbsp;" );	
			$f->text_u("23. ".$visit_ri_riwayat_penyakit["vis_28"],$d[31]."&nbsp;" );
			$f->text_u("24. ".$visit_ri_riwayat_penyakit["vis_29"],$d[32]."&nbsp;" );
			$f->text_u("25. ".$visit_ri_riwayat_penyakit["vis_30"],$d[33]."&nbsp;" );
			$f->text_u("26. ".$visit_ri_riwayat_penyakit["vis_31"],$d[34]."&nbsp;" );
			$f->text_u("27. ".$visit_ri_riwayat_penyakit["vis_32"],$d[35]."&nbsp;" );
			$f->text_u("28. ".$visit_ri_riwayat_penyakit["vis_33"],$d[36]."&nbsp;" );
			$f->execute();
			
			$f = new ReadOnlyForm2();
			$f->title1("LABORATORIUM","LEFT");
			$f->text4_darah("1. Darah",$visit_ri_riwayat_penyakit["vis_34"],$d[37],$visit_ri_riwayat_penyakit["vis_35"],$d[38],$visit_ri_riwayat_penyakit["vis_36"],$d[39],$visit_ri_riwayat_penyakit["vis_37"],$d[40],"");
			$f->text4_darah("",$visit_ri_riwayat_penyakit["vis_38"],$d[41],$visit_ri_riwayat_penyakit["vis_39"],$d[42],$visit_ri_riwayat_penyakit["vis_40"],$d[43],"","","","");
			$f->text("2. ".$visit_ri_riwayat_penyakit["vis_41"],$d[44]);
			$f->text("3. ".$visit_ri_riwayat_penyakit["vis_42"],$d[45] );	
			$f->text("4. ".$visit_ri_riwayat_penyakit["vis_43"],$d[46] );
			$f->text_u("<b>".$visit_ri_riwayat_penyakit["vis_44"]."</b>",$d[47]."&nbsp;");
			//$f->hr();
			$f->execute();
			
    		echo "</td></tr></table>";
		
				//include(rm_tindakan);
		
		echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
