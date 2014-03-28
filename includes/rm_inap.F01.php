<?
//heri 23 juli 2007

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["catatan_riwayat_kebidanan"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.*,b.nama as jaga,b.nama as ruangan,c.tdesc as rujukan 
					from c_visit_ri a 
					left join rs00017 b on CAST (a.vis_1 AS INTEGER) = b.id 
					left join rs00017 d on CAST (a.vis_2 AS INTEGER) = d.id 
					left join rs00001 c on a.id_rujukan=CAST (c.tc AS INTEGER) and c.tt='LYN' 
					where a.no_reg='{$_GET['id']}' and a.id_ri='F01' ";
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
						$sql2 = "select a.id,a.mr_no,a.nama,a.umur,a.tgl_lahir,a.tmp_lahir,a.tanggal_reg,a.status_akhir,a.diagnosa_sementara, ".
								"a.pangkat_gol,a.nrp_nip,a.kesatuan, a.jenis_kelamin ".
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
		    echo "</td></tr>"; 
		    ?>
		    	<tr valign="top" align="left">
					<td class='TBL_BODY' colspan='3'><br>
						<table class="FORM" align="left" border="0" width="100%" cellpadding="2" cellspacing="1">
							<tr><td class="form" align="left" width="18%">Dokter Jaga </td><td width="2%">:</td><td width="24%" class="form"><?=$d["jaga"]?></td>
								<td class="form" align="left" width="17%">Dokter Ruangan :</td><td class="form" width="39%"><?=$d["ruangan"]?></td></tr>
							<tr><td class="form" align="left">Diagnosis </td><td>:</td><td class="form" colspan="3"><?=$d2->diagnosa_sementara ?></td></tr>
							<tr><td class="form" align="left">Penyakit Bersamaan </td><td>:</td><td class="form" colspan="3"><?=$d["vis_3"]?></td></tr>
							<tr><td class="form" align="left">Penyakit Dahulu</td><td>:</td><td class="form" colspan="3"><?=$d["vis_4"]?></td></tr>
							<tr><td class="form" align="left">Tanggal / Pukul</td><td>:</td><td class="form" colspan="3"><?=$d["vis_5"]?> &nbsp;/ &nbsp; <?=$d["vis_6"]?></td></tr>
						</table>
					</td>
				</tr>
				<tr valign="top" align="left">
					<td class='TBL_BODY' colspan='3'><br>						
   						<table class="FORM" align="left" border="0" width="100%" cellpadding="2" cellspacing="1">
							<tr><td class="form" align="left" colspan="6"><b>ANAMNESA</b> </td></tr>
							<tr>
								<td class="form" width="2%">1.</td>
								<td class="form" colspan="2">Alasan Dirawat dari keterangannya : <?=$d["vis_7"]?></td>
							</tr>
							<tr valign="top">
								<td class="form" width="2%">2.</td>
								<td class="form" width="6%">Haid :</td>
								<td class="form">
									<table border="0" width="100%">
										<tr valign="top">
											<td class="form" width="14%">Nebarche</td>
											<td width="20%" align="left" class="form">: <?=$d["vis_8"]?></td>
											<td class="form" width="15%">Cylus</td>
											<td width="24%" align="left" class="form">: <?=$d["vis_11"]?></td>
											<td class="form" width="6%">Hari </td>
											<td width="21%" align="left" class="form">&nbsp;&nbsp; <?=$d["vis_13"]?></td>
										</tr>
										<tr valign="top">
											<td class="form" width="14%">Lamanya Haid </td>
											<td width="20%" align="left" class="form">: <?=$d["vis_9"]?></td>
											<td class="form" width="15%">Banyaknya </td>
											<td align="left" class="form" colspan="3">: <?=$d["vis_12"]?></td>
										</tr>
										<tr valign="top">
											<td class="form" width="14%">Dysmenorrhoe </td>
											<td width="20%" align="left" class="form">: <?=$d["vis_10"]?></td>
											<td class="form" width="15%">Haid Terakhir </td>
											<td align="left" class="form" colspan="3">: <?=$d["vis_14"]?></td>
										</tr>
										<tr>
																						
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="form" width="2%">3.</td>
								<td class="form" colspan="2">Perkawinan : <?=$d["vis_15"]?> &nbsp;&nbsp;&nbsp; kali, Dengan suami sekarang  : <?=$d["vis_16"]?> &nbsp;&nbsp; tahun</td>
							</tr>
							<tr>
								<td class="form" width="2%">4.</td>
								<td class="form" colspan="2">Riwayat Kehamilan dan persalinan yang lalu : </td>
							</tr>
							<tr>
								<td class="form" width="2%"></td>
								<td class="form" colspan="2">
									<table width="100%">
            							<tr>
											<td width="10%">KE </td>
											<td width="20%" class="form">Hidup/ Mati </td>
											<td width="30%" class="form">Ditolong oleh </td>
											<td width="40%" class="form">Keterangan lain </td>
										</tr>
										<tr>
											<td width="10%">1. </td>
											<td width="20%" class="form"><?=$d["vis_17"]?> </td>
											<td width="30%" class="form"><?=$d["vis_18"]?></td>
											<td width="40%" class="form"><?=$d["vis_19"]?></td>
										</tr>
										<tr>
											<td width="10%">2. </td>
											<td width="20%" class="form"><?=$d["vis_20"]?> </td>
											<td width="30%" class="form"><?=$d["vis_21"]?></td>
											<td width="40%" class="form"><?=$d["vis_22"]?></td>
										</tr>
										<tr>
											<td width="10%">3. </td>
											<td width="20%" class="form"><?=$d["vis_23"]?> </td>
											<td width="30%" class="form"><?=$d["vis_24"]?></td>
											<td width="40%" class="form"><?=$d["vis_25"]?></td>
										</tr>
										<tr>
											<td width="10%">4. </td>
											<td width="20%" class="form"><?=$d["vis_26"]?> </td>
											<td width="30%" class="form"><?=$d["vis_27"]?></td>
											<td width="40%" class="form"><?=$d["vis_28"]?></td>
										</tr>
										<tr>
											<td width="10%">5. </td>
											<td width="20%" class="form"><?=$d["vis_29"]?> </td>
											<td width="30%" class="form"><?=$d["vis_30"]?></td>
											<td width="40%" class="form"><?=$d["vis_31"]?></td>
										</tr>
									</table>
								</td>				
							</tr>
							<tr>
								<td class="form" width="2%">5.</td>
								<td class="form" colspan="2">Penyakit Operasi yang lalu : <?=$d["vis_32"]?></td>
							</tr>	
							<tr>
								<td class="form" width="2%">6.</td>
								<td class="form" colspan="2">Kehamilan Sekarang : Haid Terakhir <?=$d["vis_33"]?> &nbsp;&nbsp;taksiran partus</td>
							</tr>
							<tr>
								<td class="form" width="2%"></td>
								<td class="form" colspan="2">Pengawasan kehamilan : <?=$d["vis_34"]?>  di : <?=$d["vis_35"]?></td> 
							</tr>
							<tr>
								<td class="form" width="2%">7.</td>
								<td class="form" colspan="2">Hal ikhwal  kehamilan sampai sekarang : <?=$d["vis_36"]?></td>
							</tr>
						</table>
					</td>		
				</tr> 
				<tr valign="top" align="left">
					<td class='TBL_BODY' colspan='3'><br>						
   						<table class="FORM" align="left" border="0" width="100%" cellpadding="2" cellspacing="1">
							<tr><td class="form" align="left" colspan="4"><b>STATUS PRAESENS</b> </td></tr>
							<tr>
								<td class="form" colspan="4">Keadaan Umum</td>								
							</tr>	
							<tr>
								<td class="form">Nadi : <?=$d["vis_37"]?></td>								
								<td class="form">/menit, Tensi :<?=$d["vis_38"]?></td>								
								<td class="form">Suhu : <?=$d["vis_39"]?></td>								
								<td class="form">Pernafasan : <?=$d["vis_40"]?> &nbsp; /menit</td>								
							</tr>
							<tr>
								<td class="form" >Cor </td>								
								<td class="form" colspan="3">: <?=$d["vis_41"]?></td>								
							</tr>
							<tr>
								<td class="form" >Pulmo </td>								
								<td class="form" colspan="3">: <?=$d["vis_42"]?></td>								
							</tr>
							<tr>
								<td class="form" >Hepar </td>								
								<td class="form" colspan="3">: <?=$d["vis_43"]?></td>								
							</tr>
							<tr>
								<td class="form" >Lien </td>								
								<td class="form" colspan="3">: <?=$d["vis_44"]?></td>								
							</tr>
							<tr>
								<td class="form" >Extremitas </td>								
								<td class="form" colspan="3">: <?=$d["vis_45"]?></td>								
							</tr>
							<tr>
								<td class="form" >Lain-lain </td>								
								<td class="form" colspan="3">: <?=$d["vis_46"]?></td>								
							</tr>			
						</table>
					</td>
				</tr>
				<tr valign="top" align="left">
					<td class='TBL_BODY' colspan='3'><br>						
   						<table class="FORM" align="left" border="0" width="100%" cellpadding="2" cellspacing="1">
							<tr><td class="form" align="left" colspan="3"><b><U>STATUS OBSTETRIKUS</U></b> </td></tr>
							<tr valign="top">
								<td class="form" width="2%">1.</td>
								<td width="23%" class="form">Pemeriksaan Luar : </td>
								<td width="75%" class="form">
									<table class="FORM" align="left" border="0" width="100%" cellpadding="2" cellspacing="1">
										<tr><td class="form" align="left" colspan="2">Tinggi Fundus uteri : <?=$d["vis_47"]?></td></tr>
										<tr><td class="form" align="left">Letak Anak : <?=$d["vis_48"]?></td><td class="form">Punggung : <?=$d["vis_49"]?></td> </tr>
										<tr><td class="form" align="left">Denyut Jantung Anak : <?=$d["vis_50"]?> &nbsp;&nbsp;/menit </td><td class="form"> His : <?=$d["vis_51"]?></td> </tr>
										<tr><td class="form" align="left" colspan="2">Lain-lain : <?=$d["vis_52"]?></td></tr>
									</table>	
								</td>
							</tr>
							<tr valign="top">
								<td class="form" width="2%">2.</td>								
								<td class="form">Pemeriksaan Dalam</td>								
								<td class="form">: <?=$d["vis_53"]?></td>								
							</tr>
							<tr valign="top">
								<td class="form" width="2%">2.</td>								
								<td class="form">Pemeriksaan Panggul Dalam</td>								
								<td class="form">: <?=$d["vis_54"]?></td>								
							</tr>			
						</table>
					</td>
				</tr>
				<tr valign="top" align="left">
					<td class='TBL_BODY' colspan='3'><br>						
   						<table class="FORM" align="left" border="0" width="100%" cellpadding="2" cellspacing="1">
							<tr><td class="form" align="left" colspan="4"><b><U>PEMERIKSAAN LABORATORIUM</U></b> </td></tr>
							<tr valign="top">
								<td class="form" width="5%">1.</td>
								<td width="12%" class="form">Darah </td>
								<td width="1%" class="form">:</td>
								<td width="82%" class="form">
									<table class="FORM" align="left" border="0" width="100%" cellpadding="2" cellspacing="1">
										<tr>
											<td class="form" align="left">Hb. : <?=$d["vis_55"]?></td>
											<td class="form" align="left">Leukosit : <?=$d["vis_56"]?></td>
											<td class="form" align="left">Hitung Jenis : <?=$d["vis_57"]?> </td>
											<td class="form" align="left">VDRL : <?=$d["vis_58"]?></td>
										</tr>
									</table>	
								</td>
							</tr>
							<tr valign="top">
								<td colspan="3"></td>
								<td width="82%" class="form">
									<table class="FORM" align="left" border="0" width="100%" cellpadding="2" cellspacing="1">
										<tr>
											<td class="form" align="left">LED : <?=$d["vis_59"]?></td>
											<td class="form" align="left">WR : <?=$d["vis_60"]?></td>
											<td class="form" align="left">Kahn : <?=$d["vis_61"]?></td> 
											<td class="form" align="left">&nbsp;</td> 
										</tr>										
									</table>	
								</td>
							</tr>
							<tr valign="top">
								<td class="form" width="5%">2.</td>								
								<td class="form" width="12%" >Urine</td>								
								<td class="form" width="1%" >:</td>								
								<td class="form"><?=$d["vis_62"]?></td>								
							</tr>
							<tr valign="top">
								<td class="form" width="5%">3.</td>								
								<td class="form" width="12%">Fecces</td>								
								<td class="form" width="1%">:</td>								
								<td class="form"><?=$d["vis_63"]?></td>								
							</tr>
							<tr valign="top">
								<td class="form" width="5%">4.</td>								
								<td class="form" width="12%">Lain-lain</td>								
								<td class="form" width="1%">:</td>								
								<td class="form"><?=$d["vis_64"]?></td>								
							</tr>			
						</table>
					</td>
				</tr>
				<tr valign="top" align="left">
					<td class='TBL_BODY' colspan='3' height="30"><b><u>KESIMPULAN</u><b> : <?=$d["vis_65"] ?></td>	
				</tr>
				<tr valign="top" align="left">
					<td class='TBL_BODY' colspan='3' height="20"><b><u>RIWAYAT PERSALINAN</u><b></td>
				</tr>		
				<tr valign="top" align="left">
					<td class='TBL_BODY' colspan='3'>
					<?
						$sql = "SELECT vis_1,vis_2,vis_3 ".
								"FROM C_CATATAN  WHERE  no_reg = '{$_GET['id']}' ";
							   
						$t = new PgTable($con, "100%");
					    $t->SQL = $sql ;
					    $t->setlocale("id_ID");
					    $t->ShowRowNumber = true;
					    //$t->ColRowSpan[]
					    $t->ColHeader = array("TANGGAL","JAM","K E T E R A N G A N");
					   	$t->ColAlign = array("center","center","left","center");
					   	if ($GLOBALS['print']){
					   		$t->RowsPerPage = 20;
					    	$t->DisableNavButton = true;
					    	$t->DisableScrollBar = true;
					    	$t->DisableSort = true;
					   	}else {
					   		$t->RowsPerPage = $ROWS_PER_PAGE;
					   	}
						$t->execute();   
					?>		
					</td>
				</tr>				
			</table>
			<?
		
			//	include(rm_tindakan);
		
		echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
