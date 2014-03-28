<?
//heri 23 august 2007
// status = udah di tes.

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["resume_kebidanan"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.*,c.nama,c.mr_no,c.umur,c.jenis_kelamin,c.nama_ayah,c.pangkat_gol,c.nrp_nip,c.kesatuan,c.agama, ".
					"	to_char(f.ts_check_in,'dd Mon YYYY')as tgl_masuk,to_char(f.ts_calc_stop,'dd Mon yyyy')as tgl_keluar, ".
					"	to_char(a.tanggal_reg,'DD MON YYYY HH24:MI:SS')as tanggal_reg,(g.nama)as merawat,(h.nama)as mengirim,c.jabatan,c.sukubangsa ".
					"from c_visit_ri a ".
					"left join rsv_pasien2 c on a.no_reg=c.id ".
					"join rs00010 as f on f.no_reg = c.id  ".
					"left join rs00017 g on CAST(a.vis_1 AS INTEGER) = g.id  ".
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
			$f->text2b("Bangsal",$bangsal,"Alat Pengirim",$d[46]);
			$f->execute();
		    echo "</td></tr>";
		   	echo "<tr><td class='tbl_body' valign=top  >";
			
    		echo "<TABLE BORDER=0 CELLSPACING=2 CELLPADDING='1'>";
  			echo "<tr><TD CLASS='FORM_SUBTITLE1' ALIGN='left' COLSPAN='8'>I.&nbsp;&nbsp; DIAGNOSA OBSTETRIK </TD></tr>";
  			echo "<tr><TD CLASS='FORM' ALIGN='left'>&nbsp;&nbsp;1.&nbsp; {$visit_ri_resume_keb['vis_1']}</td><td>:</td><td CLASS='FORM' COLSPAN='5'>{$d[4]}</TD></tr>";
  			echo "<tr><TD CLASS='FORM' ALIGN='left'>&nbsp;&nbsp;2.&nbsp; {$visit_ri_resume_keb['vis_2']}</td><td>:</td><td CLASS='FORM' COLSPAN='5'>{$d[5]}</TD></tr>";
  			echo "<tr><TD CLASS='FORM' ALIGN='left'>&nbsp;&nbsp;3.&nbsp; {$visit_ri_resume_keb['vis_3']}</td><td>:</td><td CLASS='FORM' COLSPAN='5'>{$d[6]}</TD></tr>";
  			echo "<tr><TD CLASS='FORM' ALIGN='left'>&nbsp;&nbsp;4.&nbsp; Bayi</td><td>:</td><td CLASS='FORM'><i>{$visit_ri_resume_keb['vis_4']} :</i></td><td CLASS='FORM'>{$d[7]}&nbsp;&nbsp;</TD>";
  			echo "    <TD CLASS='FORM' ALIGN='left'><i>{$visit_ri_resume_keb['vis_5']} :</i></td><td CLASS='FORM'>{$d[8]}&nbsp;&nbsp;</TD>";
  			echo "    <TD CLASS='FORM' ALIGN='left'><i>{$visit_ri_resume_keb['vis_6']} :</i></td><td CLASS='FORM'>{$d[9]}&nbsp;&nbsp; g &nbsp;&nbsp;</TD>";
  			echo "    <TD CLASS='FORM' ALIGN='left'><i>{$visit_ri_resume_keb['vis_7']} :</i></td><td CLASS='FORM'>{$d[10]}&nbsp;&nbsp; cm </TD>";
  			echo "</tr>";
  			echo "<tr><td></td><td></td><TD CLASS='FORM' ALIGN='left'><i>{$visit_ri_resume_keb['vis_8']}:</i></td><td CLASS='FORM'>{$d[11]}&nbsp;&nbsp;</TD></tr>";
			echo "</table>";
			
			$f = new ReadOnlyForm();
    		$f->title1("II.&nbsp;&nbsp; RIWAYAT OBSTETRIK / RIWAYAT PENYAKIT TERDAHULU","LEFT");
			$f->text("&nbsp; 1. Penyakit obstetrik terdahulu ",$d[12]);
			$f->text("&nbsp; 2. Penyakit / operasi terdahulu ",$d[13] );
			$f->execute();
			
			?>
				<div  class="FORM_SUBTITLE1" align="left" >III.&nbsp;&nbsp; RIWAYAT KEHAMILAN SEKARANG  </div>			
				<div class="m_c_text" align="left" > 1. Haid terakhir tgl : <?=$d[14]?> &nbsp;&nbsp; Lamanya : &nbsp;&nbsp; <?=$d[15]?>&nbsp;&nbsp; hari, Sebelumnya tgl : &nbsp;&nbsp; <?=$d[16]?>  </div>			
				<div class="m_c_text" align="left" >2. Tafsiran Persalinan : <?=$d[17]?> &nbsp;&nbsp;   </div>			
				<div class="m_c_text" align="left" >3. Kelainan pada kehamilan/ penyulit pada kehamilan ini : <?=$d[18]?> &nbsp;&nbsp;   </div>			
				<div class="m_c_text" align="left" >4. Obat Khusus / jamu yang pernah di makan : <?=$d[19]?> &nbsp;&nbsp;   </div>			
				<div class="m_c_text" align="left" >5. Hasil Laboratorium   </div>
					<table  border="0" width="90%" cellpadding="1" cellspacing="1">
						<tr><td>&nbsp;&nbsp;</td>
							<td><i>HB</i></td> <td>: <?=$d[20]?>&nbsp; g </td>
							<td><i>%Gol Darah :</i> <?=$d[21]?></td>
							<td><i>Faktor Rh :</i> <?=$d[22]?></td>
							<td><i>Lekosit :</i> <?=$d[23]?> &nbsp; /mm</td> 
						</tr>
						<tr ><td>&nbsp;</td>
							<td><i>Wr</i></td> <td>: <?=$d[24]?> </td>
							<td colspan="2"><i>VDR :</i> <?=$d[25]?></td>
							<td><i>KAHN :</i> <?=$d[26]?></td>							
						</tr>
						<tr><td>&nbsp;</td>
							<td><i>Gula Darah</i></td> <td colspan="2">:&nbsp;<i>Nuther : </i><?=$d[27]?>  </td>
							<td colspan="2"><i>2 jam/ post prandial :</i> <?=$d[28]?></td>							
						</tr>
						<tr><td>&nbsp;</td>
							<td><i>Urine</i></td> <td colspan="2">:  <?=$d[29]?>  </td>
							<td colspan="2"><i>Lain-lain :</i> <?=$d[30]?></td>							
						</tr>
					</table>		
					
				<div  class="FORM_SUBTITLE1" align="left" >IV.&nbsp;&nbsp; RIWAYAT PERSALINAN SEKARANG  </div>			
					<table  border="0" width="90%" cellpadding="2" cellspacing="2">
						<tr><td width="3%" align="center">1. </td>
							<td>Kala 1 :<i> Lamanya </i>  &nbsp;<?=$d[31]?>&nbsp;  </td>
							<td><i>Jenis Kelamin :</i> <?=$d[32]?> &nbsp;</td>
							<td><i>Tindakan :</i> <?=$d[33]?> &nbsp;</td>							
						</tr>
						<tr><td align="center"></td>
							<td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Ketuban Pecah dini : </i>  &nbsp;<?=$d[34]?>&nbsp;  </td>
													
						</tr>
						<tr><td align="center">2. </td>
							<td>Kala 2 : <i>Lamanya </i>&nbsp;<?=$d[35]?>&nbsp;  </td>
							<td><i>Jenis Kelamin :</i> <?=$d[36]?> &nbsp;</td>
							<td><i>Tindakan :</i> <?=$d[37]?> &nbsp;</td>							
						</tr>
						<tr><td align="center">3. </td>
							<td>Kala 3 :<i> Lamanya </i>&nbsp;<?=$d[38]?>&nbsp;  </td>
							<td><i>Jenis Kelamin :</i> <?=$d[39]?> &nbsp;</td>
							<td><i>Tindakan :</i> <?=$d[40]?> &nbsp;</td>							
						</tr>
						<tr><td align="center">4. </td>
							<td>Kala 4 : <i>Lamanya </i>&nbsp;<?=$d[41]?>&nbsp;  </td>
							<td><i>Jenis Kelamin :</i> <?=$d[42]?> &nbsp;</td>
							<td><i>Tindakan :</i> <?=$d[43]?> &nbsp;</td>							
						</tr>
					</table>			
								
			<?		
    		echo "</td></tr></table>";
  			
    		//include(rm_tindakan);
  			
		echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
