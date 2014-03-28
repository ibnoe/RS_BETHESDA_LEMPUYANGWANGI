<?
//heri sept 2007
//udah di cek

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["catatan_bayi"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.*,c.tdesc as rujukan 
					from c_visit_ri a 
					
					left join rs00001 c on a.id_rujukan=CAST (c.tc AS INTEGER) and c.tt='LYN' 
					where a.no_reg='{$_GET['id']}' and a.id_ri='F02' ";
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
					<td class='TBL_BODY' colspan='3'>
						<table class="FORM" align="left" border="0" width="100%" cellpadding="2" cellspacing="1">
							<tr><td class="form" align="left" colspan="5"><b><u>I B U</u></b> </td></tr>
							<tr valign="top">
								<td class="form" width="20%">Umur : <?=$d["vis_1"]?>&nbsp; tahun &nbsp;</td>
								<td class="form" width="20%">Para : <?=$d["vis_2"]?></td>
								<td class="form" width="20%">Prematur : <?=$d["vis_3"]?></td>
								<td class="form" width="20%">Abortus : <?=$d["vis_4"]?></td>
								<td class="form" width="20%">Anak</td>
							</tr>
							<tr valign="top">
								<td class="form">Hidup : <?=$d["vis_5"]?></td>
								<td class="form" colspan="3">Sc : <?=$d["vis_6"]?></td>								
							</tr>
							<tr valign="top">
								<td class="form" colspan="2">Riwayat Penyakit Tuberkolosisi : <?=$d["vis_7"]?></td>
								<td class="form">Venerik : <?=$d["vis_8"]?></td>
								<td class="form">VDRL : <?=$d["vis_9"]?></td>
								<td class="form">WR : <?=$d["vis_10"]?> </td>
							</tr>
							<tr valign="top">
								<td class="form" colspan="2">Diabetes : <?=$d["vis_11"]?></td>
								<td class="form" colspan="2">Vitium Cordis : <?=$d["vis_12"]?></td>
								<td class="form">Lain-lain : <?=$d["vis_13"]?></td>								
							</tr>
							<tr valign="top">
								<td class="form" colspan="2">Golongan Darah : RH Ayah : <?=$d["vis_14"]?></td>
								<td class="form">RH Ibu : <?=$d["vis_15"]?></td>
								<td class="form" colspan="2">Coombs tes : <?=$d["vis_16"]?></td>								
							</tr>
							<tr valign="top">
								<td class="form">Jenis Persalinan : </td>
								<td class="form" colspan="4" height="30"><?=$d["vis_17"]?></td>														
							</tr>
							<tr valign="top">
								<td class="form">Atas Indikasi : </td>
								<td class="form" colspan="4"><?=$d["vis_18"]?></td>														
							</tr>
							<tr valign="top">
								<td class="form">komplikasi : </td>
								<td class="form" colspan="4" height="30"><?=$d["vis_19"]?></td>														
							</tr>
							<tr valign="top">
								<td class="form" colspan="5"><u>Pengobatan ibu yang dapat mempengaruhi bayi </u> </td>
							</tr>
							<tr valign="top">
								<td class="form" colspan="2">Obat : <?=$d["vis_20"]?></td>
								<td class="form">Cara Pemberian : <?=$d["vis_21"]?></td>
								<td class="form" colspan="2">Pemberian Terakhir : <?=$d["vis_22"]?></td>														
							</tr>
							<tr valign="top">
								<td class="form" colspan="2">Anestesi/analgesi: Obat : <?=$d["vis_23"]?></td>
								<td class="form">Cara Pemberian : <?=$d["vis_24"]?></td>
								<td class="form" colspan="2">Pemberian Terakhir : <?=$d["vis_25"]?></td>														
							</tr>
							<tr><td class="form" align="left" colspan="5"><br><b><u>B A Y I </u></b> </td></tr>
							<tr valign="top">
								<td class="form" colspan="2">Lahir tanggal : <?=$d["vis_26"]?></td>
								<td class="form">Jam : <?=$d["vis_27"]?></td>
								<td class="form">Kelamin : <?=$d["vis_28"]?></td>														
								<td class="form">Kembar : <?=$d["vis_29"]?></td>														
							</tr>
							<tr><td class="form" align="left" colspan="5"><u>Penilaian Bayi dengan apgar score 60 detik, setelah lahir lengkap. </u></td></tr>							
							<tr>
								<td colspan="5">	
								<?
									if ($d["vis_30"] != ""){
										$b1 = "<b>";
										$cek1 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_31"] != ""){
										$b2 = "<b>";
										$cek2 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_32"] != ""){
										$b3 = "<b>";
										$cek3 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_34"] != ""){
										$b4 = "<b>";
										$cek4 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_35"] != ""){
										$b5 = "<b>";
										$cek5 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_36"] != ""){
										$b6 = "<b>";
										$cek6 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_38"] != ""){
										$b7 = "<b>";
										$cek7 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_39"] != ""){
										$b8 = "<b>";
										$cek8 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_40"] != ""){
										$b9 = "<b>";
										$cek9 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_42"] != ""){
										$b10 = "<b>";
										$cek10 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_43"] != ""){
										$b11 = "<b>";
										$cek11 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_44"] != ""){
										$b12 = "<b>";
										$cek12 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_46"] != ""){
										$b13 = "<b>";
										$cek13 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_47"] != ""){
										$b14 = "<b>";
										$cek14 = "</b><img src='images/icon-cek.gif'>";
									}
									if ($d["vis_48"] != ""){
										$b15 = "<b>";
										$cek15 = "</b><img src='images/icon-cek.gif'>";
									}
								?>								
									<table class="tbl_border" width="100%" border=0 cellspacing=1 cellpadding=2>  
										<tr class="TBL_HEAD" >
											<td align="center">Tanda</td>
											<td align="center">0</td>
											<td align="center">1</td>
											<td align="center">2</td>
											<td align="center">Jumlah Nilai</td>								
										</tr>							
										<tr class="TBL_BODY" >
											<td align="left">Frekuensi Jantung</td>
											<td align="center"><?=$b1?>Tak ada<?=$cek1?></td>
											<td align="center"><?=$b2?>100<?=$cek2?></td>
											<td align="center"><?=$b3?>100<?=$cek3?></td>
											<td align="center"><?=$d["vis_33"]?></td>
										</tr>
										<tr class="TBL_BODY" >
											<td align="left">Usaha Nafas</td>
											<td align="center"><?=$b4?>Tak ada<?=$cek4?></td>
											<td align="center"><?=$b5?>Lambat, tak teratur<?=$cek5?></td>
											<td align="center"><?=$b6?>Menangis Kuat<?=$cek6?></td>
											<td align="center"><?=$d["vis_37"]?></td>
										</tr>
										<tr class="TBL_BODY" >
											<td align="left">Tonus Otot</td>
											<td align="center"><?=$b7?>Lumpuh<?=$cek7?></td>
											<td align="center"><?=$b8?>Ext, flexi sedikit<?=$cek8?></td>
											<td align="center"><?=$b9?>Gerakan aktif<?=$cek9?></td>
											<td align="center"><?=$d["vis_41"]?></td>
										</tr>
										<tr class="TBL_BODY" >
											<td align="left">Reflex</td>
											<td align="center"><?=$b10?>Tak terjawab<?=$cek10?></td>
											<td align="center"><?=$b11?>Gerakan sedikit<?=$cek11?></td>
											<td align="center"><?=$b12?>Menangis<?=$cek12?></td>
											<td align="center"><?=$d["vis_45"]?></td>
										</tr>
										<tr class="TBL_BODY" >
											<td align="left">Warna</td>
											<td align="center"><?=$b13?>Biru/pucat<?=$cek13?></td>
											<td align="center"><?=$b14?>Tubuh kemerahan,<br> tangan & kaki biru<?=$cek14?></td>
											<td align="center"><?=$b15?>Kemerahan<?=$cek15?></td>
											<td align="center"><?=$d["vis_49"]?></td>
										</tr>
									</table>	
								</td>
							</tr>
							<tr><td class="form" colspan="5"><u>Resuitasi</u></td></tr>
							<tr valign="top">
								<td class="form" colspan="2">Pemberian Oxygen saja : <?=$d["vis_50"]?> &nbsp; &nbsp; menit</td>
								<td class="form" colspan="3">Peniupan : <?=$d["vis_51"]?> &nbsp; &nbsp; menit</td>								
							</tr>
							<tr valign="top">
								<td class="form" colspan="2">Pemberian Oxygen dalam tekanan : <?=$d["vis_52"]?> &nbsp; &nbsp; menit</td>
								<td class="form" colspan="3">Pernafasan mulut ke mulut : <?=$d["vis_53"]?> &nbsp; &nbsp; menit</td>								
							</tr>
							<tr><td class="form" colspan="5"><u>Kisah Resuitasi</u></td></tr>
							<tr valign="top">
								<td class="form" colspan="2">Waktu sampai bernafas teratur : <?=$d["vis_54"]?> &nbsp; &nbsp; menit</td>
								<td class="form" colspan="3">Pengobatan bayi : <?=$d["vis_55"]?> &nbsp; &nbsp; menit</td>								
							</tr>
							<tr valign="top">
								<td class="form" colspan="5">Waktu sampai menangis : <?=$d["vis_56"]?> &nbsp; &nbsp; menit</td>												
							</tr>
							
							<tr><td class="form" colspan="5"><u>Pemeriksaan bayi setelah lahir</u></td></tr>
							<tr valign="top">
								<td class="form">Kepala : </td>
								<td class="form" colspan="4" height="20"><?=$d["vis_57"]?> </td>								
							</tr>
							<tr valign="top">
								<td class="form">Kulit : <?=$d["vis_58"]?> </td>
								<td class="form">Jantung : <?=$d["vis_59"]?> </td>
								<td class="form">Mata  : <?=$d["vis_60"]?> </td>
								<td class="form">Paru : <?=$d["vis_61"]?> </td>
								<td class="form">Hidung : <?=$d["vis_62"]?> </td>																
							</tr>
							<tr valign="top">
								<td class="form">Abdomen : <?=$d["vis_63"]?> </td>
								<td class="form">Mulut : <?=$d["vis_64"]?> </td>
								<td class="form">Anus  : <?=$d["vis_65"]?> </td>
								<td class="form">Telinga : <?=$d["vis_66"]?> </td>
								<td class="form">Extremitas : <?=$d["vis_67"]?> </td>																
							</tr>
							<tr valign="top">
								<td class="form">Anjuran : </td>
								<td class="form" colspan="4" height="30"><?=$d["vis_68"]?> </td>								
							</tr>
							<tr valign="top">
								<td class="form" colspan="2">
									<table class='tbl_border' border='0' width='100%' cellspacing=1 cellpadding=0>
										<tr>
											<td class="tbl_body" align="center">Ukuran Bayi</td>
										</tr>	
										<tr>
											<td class="tbl_body" align="left">
												<table border="0" width="100%">
													<tr>
														<td class="form" align="left" width="35%">Berat Badan</td>
														<td class="form" align="left" width="45%">: <?=$d["vis_74"]?></td>
														<td class="form" align="right" width="10%">/gram</td>
													</tr>
													<tr>
														<td class="form" align="left">Panjang Badan</td>
														<td class="form" align="left">: <?=$d["vis_75"]?></td>
														<td class="form" align="right">cm</td>
													</tr>
													<tr>
														<td class="form" align="left">B i p</td>
														<td class="form" align="left">: <?=$d["vis_76"]?></td>
														<td class="form" align="right">cm</td>
													</tr>
													<tr>
														<td class="form" align="left">OM</td>
														<td class="form" align="left">: <?=$d["vis_77"]?></td>
														<td class="form" align="right">cm</td>
													</tr>
													<tr>
														<td class="form" align="left">OF</td>
														<td class="form" align="left">: <?=$d["vis_78"]?></td>
														<td class="form" align="right">cm</td>
													</tr>															
												</table>												
											</td>
										</tr>
									</table>
								</td>			
								<td class="form" colspan="3" align="right">
									<table class='tbl_border' border='0' width='90%' cellspacing=1 cellpadding=0>
										<tr>
											<td class="tbl_body" align="center">Meninggal</td>
										</tr>
										<tr>
											<td class="tbl_body" align="left">
												<table border="0" width="100%">
													<tr>
														<td class="form" align="left">Meninggal tanggal</td>
														<td class="form" align="left">: <?=$d["vis_69"]?></td>														
														<td class="form" align="left">Jam : <?=$d["vis_70"]?></td>														
													</tr>
													<tr>
														<td class="form" align="left" width="30%">Umur Bayi </td>
														<td class="form" align="left" width="40%">: <?=$d["vis_71"]?></td>
														<td class="form" align="right" width="30%">menit/jam</td>
													</tr>
													<tr valign="top">
														<td class="form" align="left">Sebab kematian</td>														
														<td class="form" align="left" colspan="2" height="30">: <?=$d["vis_72"]?></td>														
													</tr>
													<tr>
														<td class="form" align="left">Obduksi </td>														
														<td class="form" align="left" colspan="2">: <?=$d["vis_73"]?></td>														
													</tr>																
												</table>												
											</td>
										</tr>
									</table>
								</td>
													
							</tr>
						</table>			
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
