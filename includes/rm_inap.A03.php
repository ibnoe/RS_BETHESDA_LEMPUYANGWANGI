<?
//heri 23 august 2007
// status = udah di tes.

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["resume_bayi"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.*,c.nama,c.mr_no,c.umur,c.jenis_kelamin,c.nama_ayah,c.pangkat_gol,c.nrp_nip,c.kesatuan,c.agama, ".
					"	to_char(f.ts_check_in,'dd Mon YYYY')as tgl_masuk,to_char(f.ts_calc_stop,'dd Mon yyyy')as tgl_keluar, ".
					"	to_char(a.tanggal_reg,'DD MON YYYY HH24:MI:SS')as tanggal_reg,(g.nama)as merawat,(h.nama)as mengirim,c.jabatan,c.sukubangsa ".
					"from c_visit_ri a ".
					"left join rsv_pasien2 c on a.no_reg=c.id ".
					"join rs00010 as f on f.no_reg = c.id  ".
					"left join rs00017 g on CAST (a.vis_1 AS INTEGER) = g.id  ".
					"left join rs00017 h on CAST (a.vis_2 AS INTEGER) = h.id ".
					"where a.no_reg='{$_GET['id']}' and a.id_ri= 'A03' ";
						
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
		   	echo "<tr><td class='tbl_body' valign=top  >";
			?>
			<table border="0" width="100%" cellpadding="2" cellspacing="2">
				<tr  valign="top">
					<td class="form" width="15%">Diagnosis Akhir</td>
					<td class="form" width="2%">:</td>
					<td class="form" width="83%">1. <?=$d[7]?>   <br>
									2. <?=$d[8]?>   <br>
									3. <?=$d[9]?>   <br>
					</td>
				</tr>
				<tr valign="top">
					<td class="form">Riwayat Kelahiran</td>
					<td class="form">:</td>
					<td class="form"><p>G.P.A. Kelahiran &nbsp;&nbsp; <?=$d[10]?>  &nbsp;&nbsp;&nbsp;&nbsp; minggu, Letak :&nbsp;&nbsp;<?=$d[27]?> <br>
						Tgl. :&nbsp;&nbsp; <?=$d[11]?> &nbsp;&nbsp;&nbsp;&nbsp;    Jam 	<?=$d[12]?> &nbsp;&nbsp;&nbsp;&nbsp;		<br>													
						Secara :&nbsp;&nbsp; <?=$d[13]?> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;   a.i : &nbsp;&nbsp;	<??> &nbsp;&nbsp;&nbsp;&nbsp;		<br>													
						Dengan Apgar score : &nbsp;&nbsp;<?=$d[14]?> &nbsp;&nbsp;&nbsp;&nbsp;    air ketuban : &nbsp;&nbsp;<?=$d[15]?> &nbsp;&nbsp;&nbsp;&nbsp;		<br>													
						</p>
					</td>
				</tr>
				<tr valign="top">
					<td class="form">Pemeriksaan</td>
					<td class="form">:</td>
					<td class="form"><p>Seorang bayi <?=$d[28]?> dengan berat badan  &nbsp;&nbsp;<?=$d[16]?> &nbsp;&nbsp;&nbsp;&nbsp;    gram 	<br>
							Panjang badan  &nbsp;&nbsp;<?=$d[17]?> &nbsp;&nbsp;&nbsp;&nbsp;  cm, Lingkar Kepala  &nbsp;&nbsp;<?=$d[18]?> &nbsp;&nbsp;&nbsp;&nbsp; cm <br>
							Kepala : 	&nbsp;&nbsp;<?=$d[19]?> &nbsp;&nbsp;&nbsp;&nbsp;		<br>	
						    Kelainan Fisik Lainnya :   &nbsp;&nbsp;<?=$d[20]?> &nbsp;&nbsp;&nbsp;&nbsp;  
					    </p>
						<p>Laboratorium &nbsp;&nbsp;<?=$d[21]?> &nbsp;&nbsp;&nbsp;&nbsp;</p>
					</td>
				</tr>
				<tr valign="top">
					<td class="form">Tindak Lanjut <br> (Follow Up) </td>
					<td class="form">:</td>
					<td class="form"><p>Segera setelah lahir diberi tetes mata untuk pencegahan dan <br>
						<?=$d[29]?> &nbsp;&nbsp;<br>
						Minum : <?=$d[22]?> &nbsp;&nbsp;&nbsp;&nbsp; <br>
						Berat badan minimum <?=$d[23]?> &nbsp;&nbsp;&nbsp;&nbsp; grm hari ke <?=$d[24]?> &nbsp;&nbsp;&nbsp;&nbsp;<br>
						Hari ke : <?=$d[25]?> &nbsp;&nbsp;&nbsp;&nbsp; pulang dengan berat badan : <?=$d[26]?> &nbsp;&nbsp;&nbsp;&nbsp; grm <br>
						Keadaan Umum baik.
						</p>
					</td>
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
