<?
//heri sept 2007
// udah dites
$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["ringkasan_masuk_keluar"];

	if ($_GET['act'] ==  "detail2"){
		
			$sql = "select a.*,b.nama as dr_pengirim, d.nama as dr_ruangan, c.tdesc as rujukan 
					from c_visit_ri a 
					left join rs00017 b on cast (a.vis_1 as integer) = b.id 
					left join rs00017 d on cast (a.vis_2 as integer) = d.id 
					left join rs00001 c on a.id_rujukan=cast (c.tc as numeric) and c.tt='LYN' 
					where a.no_reg='{$_GET['id']}' and a.id_ri='C03' ";
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
						$sql2 = "select a.id,a.mr_no,a.nama,a.umur,a.tgl_lahir,a.tmp_lahir,a.tanggal_reg,a.status_akhir, a.pangkat_gol,a.nrp_nip,a.kesatuan, ".
								"		a.jenis_kelamin, a.status_nikah,a.agama,a.gol_darah,a.resus_faktor,a.nama_ibu,a.nama_ayah,a.pekerjaan,a.nm_penanggung,a.alm_keluarga,a.hub_penanggung ".
								"from rsv_pasien2 a  ".
								"where a.id= '{$_GET['id']}'";			
						
						$r2 = pg_query($con,$sql2);
						$n2 = pg_num_rows($r2);
					    if($n2 > 0) $d2 = pg_fetch_object($r2);
					    pg_free_result($r2);
		    			    
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
		    echo "</td></tr></table>"; 
		    ?>
		    <table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=3>
		    	<tr valign="top" align="center">
					<td class="TBL_BODY" width="18%" height="40">Tempat & Tgl.Lahir <br>&nbsp; <?=$d2->tmp_lahir ?><br> <?=$d2->tgl_lahir ?></td>		
					<td class="TBL_BODY" width="16%">Status <p>&nbsp; <?=$d2->status_nikah ?></td>		
					<td class="TBL_BODY" width="16%">Agama <p>&nbsp; <?=$d2->agama ?></td>		
					<td class="TBL_BODY" width="18%">Pendidikan Terakhir <p>&nbsp; <? ?></td>		
					<td class="TBL_BODY" width="16%">Gol.Darah <p>&nbsp; <?=$d2->gol_darah ?></td>		
					<td class="TBL_BODY" width="16%">Faktor Resus <p>&nbsp; <?=$d2->resus_faktor ?></td>		
				</tr>
				<tr valign="top" align="center">
					<td class="TBL_BODY" colspan="2" height="40">Nama Lengkap Ibu <p>&nbsp; <?=$d2->nama_ibu ?></td>		
					<td class="TBL_BODY" colspan="2">Nama Lengkap Ayah <p> <?=$d2->nama_ayah ?></td>		
					<td class="TBL_BODY" colspan="2">Pekerjaan Ayah/Suami/Istri/Sendiri <p> <?=$d2->pekerjaan ?></td>							
				</tr>
				<tr valign="top" align="center">
					<td class="TBL_BODY" colspan="2" height="60">Nama yang bertanggung <br> jawab terhadap pasien <p>&nbsp; <?=$d2->nm_penanggung ?></td>		
					<td class="TBL_BODY" colspan="2">Alamat Penanggung<br> Jawab <p> <?=$d2->alm_penanggung ?></td>		
					<td class="TBL_BODY" colspan="2">Hubungan terhadap <br> Pasien <p> <?=$d2->hub_penanggung ?></td>							
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="3" height="30">Dokter yang mengirim : <?=$d["dr_pengirim"] ?></td>		
					<td class="TBL_BODY" colspan="3">Alamat &nbsp; &nbsp; : <?=$d["vis_3"] ?></td>						
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="3" height="30">Dokter Ruangan &nbsp; &nbsp; &nbsp; &nbsp;  : <?=$d["dr_ruangan"] ?></td>		
					<td class="TBL_BODY" colspan="3">Ruangan : <?=$d["vis_21"] ?></td>						
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="2" height="30">Tanggal Masuk &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : <?=$d["vis_4"] ?></td>		
					<td class="TBL_BODY">Pukul : <?=$d["vis_5"] ?></td>
					<td class="TBL_BODY" colspan="2">Tanggal Keluar &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  : <?=$d["vis_6"] ?></td>		
					<td class="TBL_BODY">Pukul : <?=$d["vis_7"] ?></td>						
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="2" height="30">Tanggal Meninggal &nbsp; &nbsp;  : <?=$d["vis_8"] ?></td>		
					<td class="TBL_BODY">Pukul : <?=$d["vis_9"] ?></td>
					<td class="TBL_BODY" colspan="3">Jumlah hari perawatan  : <?=$d["vis_10"] ?></td>							
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="3" height="30">Pindah Ke &nbsp; &nbsp;  : <?=$d["vis_11"] ?></td>		
					<td class="TBL_BODY" colspan="3">Tanda tangan perawat yang menerima : <?=$d["vis_22"] ?></td>							
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="6" height="30">Diagnosa Sementara  : <?=$d["vis_12"] ?></td>							
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="5" height="30">Diagnosa Akhir  : <?=$d["vis_13"] ?></td>							
					<td class="TBL_BODY">No.Kode  : </td>							
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="6" height="30">Diagnosa Sekunder termasuk kompilkasi/menifestasi  : <?=$d["vis_14"] ?></td>							
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="5" height="30">Operasi  : <?=$d["vis_15"] ?></td>							
					<td class="TBL_BODY">No.Kode  : </td>							
				</tr>
				<tr valign="top" align="left">
					<td class="TBL_BODY" colspan="6" height="50">Sebab-sebab dan tempat terjadinya kecelakaan  : <?=$d["vis_16"] ?></td>							
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="5" height="30">Pengobatan dilanjutkan di  : <?=$d["vis_18"] ?></td>							
					<td class="TBL_BODY">Tanggal  : <?=$d["vis_19"] ?></td>							
				</tr>
				<tr valign="middle" align="left">
					<td class="TBL_BODY" colspan="6" height="30">Otopsi  : <?=$d["vis_20"] ?></td>							
				</tr>
			</table><br>
			<table width="100%">
				<tr valign="top"><td colspan="5">&nbsp;</td><td width="16" class="form" align="center" height="10">Tanda Tangan</td></tr>
				<tr valign="top"><td colspan="5">&nbsp;</td><td width="16" class="form" align="center" height="60">Dokter Ruangan</td></tr>
				<tr><td colspan="5">&nbsp;</td><td class="form" width="16" align="center"><?=$d["dr_ruangan"] ?></td></tr>
				<tr><td colspan="5">&nbsp;</td><td class="form" width="16" align="center">______________________</td></tr>
			</table>
			<?
				//include(rm_tindakan);
		
		echo "</DIV>";
		
	}elseif ($_GET['act'] ==  "detail"){
			
			$SQL3 = "select mr_no, nama, pangkat_gol, nrp_nip, kesatuan from rsv_pasien2 where mr_no='{$_GET["mr"]}' ";
				$r3 = pg_query($con,$SQL3);
				$n3 = pg_num_rows($r3);
				if($n3 > 0) $d3 = pg_fetch_object($r3);
				pg_free_result($r3);
			$f = new Form($SC, "GET","");
		    $f->hidden("p", $PID);
		    $f->hidden("sub", $_GET["sub"]);
		    $f->rotext("Nama Lengkap","<b>".$d3->nama."</b>");
		    $f->rotext("No.MR",$d3->mr_no);
		    $f->rotext("Pangkat",$d3->pangkat_gol);
		    $f->rotext("NRP / NIP",$d3->nrp_nip);
		    $f->rotext("Kesatuan",$d3->kesatuan);
		    $f->execute();
				
		    			        
		    		$tglhariini = substr(date("Y-m-d", time()),0,10);    		 
								
					 $SQL = "select f.id,d.bangsal ,to_char(min(a.ts_check_in),'dd Mon YYYY') as ts_check_in, ".
							"(select to_char(max(ts_calc_stop),'dd Mon yyyy')as tgl_keluar from rs00010 where a.no_reg=no_reg and id=(select max(id) from rs00010 where no_reg =a.no_reg) ) as check_out,  ".
							"f.mr_no,case when f.status = 'P' Then 'Check-Out/Pindah' else 'Dirawat' end as status ".
							"from rs00010 a ".
							"join rsv_pasien2 f on a.no_reg=f.id ".
							"join rs00012 as b on a.bangsal_id = b.id ".
							"join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
							//"join rs00001 as e on d.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
							"join rs00001 as g on f.poli = g.tc_poli and g.tt = 'LYN' ".
							"left join c_visit_ri c on a.no_reg=c.no_reg ". 
							"where f.mr_no= '{$_GET["mr"]}' ".
							"group by f.mr_no,f.id,d.bangsal,c.no_reg,a.no_reg,g.tdesc,f.status ";
					
						
			echo "<DIV ><br>";
				$t = new PgTable($con, "100%");
			    $t->SQL = $SQL  ;
			    $t->ShowRowNumber = true;
				$t->ColHidden[5] = true;
				$t->ColHeader = array("NO.REG","BANGSAL","TGL.MASUK","TGL.KELUAR","STATUS");
				$t->ColAlign = array("center","left","center","center","center");		
				if (!$GLOBALS['print']){
					$t->ColFormatHtml[0] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&sub={$_GET["sub"]}&act=detail2&id=<#0#>&mr=<#4#>'><#0#></A>";
			   		$t->RowsPerPage = $ROWS_PER_PAGE;			
			   	}else {
			   		$t->RowsPerPage = 20;
			    	$t->DisableNavButton = true;
			    	$t->DisableScrollBar = true;
			    	$t->DisableSort = true;
			   	}
				$t->execute();  	
		
			echo "</div>";

	}else {
		
		
		include("rm_inap2.php");	
		
	}
}
?>
