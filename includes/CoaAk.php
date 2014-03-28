<?php
//$PID = "CoaAk";
//$SC = $_SERVER["SCRIPT_NAME"];

//require_once("lib/dbconn.php");
//require_once("lib/form.php");
//require_once("lib/class.PgTable.php");
//require_once("lib/functions.php");

//include ("CoaAk.txt");


//title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>POSTING JURNAL</b>");
//title_excel("CoaAk");

//-- start variable visite
//$visiteIGDLunas + $visitePenjaminIGDLunas + $visiteRajalLunas + $visitePenjaminRajalLunas + $visiteRanapLunas + $visitePenjaminRanapLunas
//$visiteIGDNoLunas + $visitePenjaminIGDNoLunas + $visiteRajalNoLunas + $visitePenjaminRajalNoLunas + $visiteRanapNoLunas + $visitePenjaminRanapNoLunas
//-- end variable visite

//-- start variable alat
//$alatIGDLunas + $alatPenjaminIGDLunas + $alatRajalLunas + $alatPenjaminRajalLunas + $alatRanapLunas + $alatPenjaminRanapLunas
//$alatIGDNoLunas + $alatPenjaminIGDNoLunas + $alatRajalNoLunas + $alatPenjaminRajalNoLunas + $alatRanapNoLunas + $alatPenjaminRanapNoLunas
//-- end variable alat

//-- start variable radiologi
//$radiologiIGDLunas + $radiologiPenjaminIGDLunas + $radiologiRajalLunas + $radiologiPenjaminRajalLunas + $radiologiRanapLunas + $radiologiPenjaminRanapLunas
//$radiologiIGDNoLunas + $radiologiPenjaminIGDNoLunas + $radiologiRajalNoLunas + $radiologiPenjaminRajalNoLunas + $radiologiRanapNoLunas + $radiologiPenjaminRanapNoLunas
//-- end variable radiologi

//-- start variable tindakan
//$tindakanIGDLunas + $tindakanPenjaminIGDLunas + $tindakanRajalLunas + $tindakanPenjaminRajalLunas + $tindakanRanapLunas + $tindakanPenjaminRanapLunas
//$tindakanIGDNoLunas + $tindakanPenjaminIGDNoLunas + $tindakanRajalNoLunas + $tindakanPenjaminRajalNoLunas + $tindakanRanapNoLunas + $tindakanPenjaminRanapNoLunas
//-- end variable tindakan

//-- start variable konsultasi
//$konsultasiIGDLunas + $konsultasiPenjaminIGDLunas + $konsultasiRajalLunas + $konsultasiPenjaminRajalLunas + $konsultasiRanapLunas + $konsultasiPenjaminRanapLunas
//$konsultasiIGDNoLunas + $konsultasiPenjaminIGDNoLunas + $konsultasiRajalNoLunas + $konsultasiPenjaminRajalNoLunas + $konsultasiRanapNoLunas + $konsultasiPenjaminRanapNoLunas
//-- end variable konsultasi

//-- start variable laboratorium
//$laboratoriumIGDLunas + $laboratoriumPenjaminIGDLunas + $laboratoriumRajalLunas + $laboratoriumPenjaminRajalLunas + $laboratoriumRanapLunas + $laboratoriumPenjaminRanapLunas
//$laboratoriumIGDNoLunas + $laboratoriumPenjaminIGDNoLunas + $laboratoriumRajalNoLunas + $laboratoriumPenjaminRajalNoLunas + $laboratoriumRanapNoLunas + $laboratoriumPenjaminRanapNoLunas
//-- end variable laboratorium

//-- start variable ambulance
//$ambulanceIGDLunas + $ambulancePenjaminIGDLunas + $ambulanceRajalLunas + $ambulancePenjaminRajalLunas + $ambulanceRanapLunas + $ambulancePenjaminRanapLunas
//$ambulanceIGDNoLunas + $ambulancePenjaminIGDNoLunas + $ambulanceRajalNoLunas + $ambulancePenjaminRajalNoLunas + $ambulanceRanapNoLunas + $ambulancePenjaminRanapNoLunas
//-- end variable ambulance

//-- start variable esg
//$esgIGDLunas + $esgPenjaminIGDLunas + $esgRajalLunas + $esgPenjaminRajalLunas + $esgRanapLunas + $esgPenjaminRanapLunas
//$esgIGDNoLunas + $esgPenjaminIGDNoLunas + $esgRajalNoLunas + $esgPenjaminRajalNoLunas + $esgRanapNoLunas + $esgPenjaminRanapNoLunas
//-- end variable esg

//-- start variable oksigen
//$oksigenIGDLunas + $oksigenPenjaminIGDLunas + $oksigenRajalLunas + $oksigenPenjaminRajalLunas + $oksigenRanapLunas + $oksigenPenjaminRanapLunas
//$oksigenIGDNoLunas + $oksigenPenjaminIGDNoLunas + $oksigenRajalNoLunas + $oksigenPenjaminRajalNoLunas + $oksigenRanapNoLunas + $oksigenPenjaminRanapNoLunas
//-- end variable oksigen

//-- start variable fisioteraphi
//$fisioteraphiIGDLunas + $fisioteraphiPenjaminIGDLunas + $fisioteraphiRajalLunas + $fisioteraphiPenjaminRajalLunas + $fisioteraphiRanapLunas + $fisioteraphiPenjaminRanapLunas
//$fisioteraphiIGDNoLunas + $fisioteraphiPenjaminIGDNoLunas + $fisioteraphiRajalNoLunas + $fisioteraphiPenjaminRajalNoLunas + $fisioteraphiRanapNoLunas + $fisioteraphiPenjaminRanapNoLunas
//-- end variable fisioteraphi

//-- start variable administrasi
//$administrasiIGDLunas + $administrasiPenjaminIGDLunas + $administrasiRajalLunas + $administrasiPenjaminRajalLunas + $administrasiRanapLunas + $administrasiPenjaminRanapLunas
//$administrasiIGDNoLunas + $administrasiPenjaminIGDNoLunas + $administrasiRajalNoLunas + $administrasiPenjaminRajalNoLunas + $administrasiRanapNoLunas + $administrasiPenjaminRanapNoLunas
//-- end variable administrasi

//-- start variable lain
//$lainIGDLunas + $lainPenjaminIGDLunas + $lainRajalLunas + $lainPenjaminRajalLunas + $lainRanapLunas + $lainPenjaminRanapLunas
//$lainIGDNoLunas + $lainPenjaminIGDNoLunas + $lainRajalNoLunas + $lainPenjaminRajalNoLunas + $lainRanapNoLunas + $lainPenjaminRanapNoLunas
//-- end variable lain

//-- start variable pemeriksaan
//$pemeriksaanIGDLunas + $pemeriksaanPenjaminIGDLunas + $pemeriksaanRajalLunas + $pemeriksaanPenjaminRajalLunas + $pemeriksaanRanapLunas + $pemeriksaanPenjaminRanapLunas
//$pemeriksaanIGDNoLunas + $pemeriksaanPenjaminIGDNoLunas + $pemeriksaanRajalNoLunas + $pemeriksaanPenjaminRajalNoLunas + $pemeriksaanRanapNoLunas + $pemeriksaanPenjaminRanapNoLunas
//-- end variable pemeriksaan

//-- start variable akomodasi
//$akomodasiIGDLunas + $akomodasiPenjaminIGDLunas + $akomodasiRajalLunas + $akomodasiPenjaminRajalLunas + $akomodasiRanapLunas + $akomodasiPenjaminRanapLunas
//$akomodasiIGDNoLunas + $akomodasiPenjaminIGDNoLunas + $akomodasiRajalNoLunas + $akomodasiPenjaminRajalNoLunas + $akomodasiRanapNoLunas + $akomodasiPenjaminRanapNoLunas
//-- end variable akomodasi

//-- start variable transfusi
//$transfusiIGDLunas + $transfusiPenjaminIGDLunas + $transfusiRajalLunas + $transfusiPenjaminRajalLunas + $transfusiRanapLunas + $transfusiPenjaminRanapLunas
//$transfusiIGDNoLunas + $transfusiPenjaminIGDNoLunas + $transfusiRajalNoLunas + $transfusiPenjaminRajalNoLunas + $transfusiRanapNoLunas + $transfusiPenjaminRanapNoLunas
//-- end variable transfusi

//-- start variable rujukan
//$rujukanIGDLunas + $rujukanPenjaminIGDLunas + $rujukanRajalLunas + $rujukanPenjaminRajalLunas + $rujukanRanapLunas + $rujukanPenjaminRanapLunas
//$rujukanIGDNoLunas + $rujukanPenjaminIGDNoLunas + $rujukanRajalNoLunas + $rujukanPenjaminRajalNoLunas + $rujukanRanapNoLunas + $rujukanPenjaminRanapNoLunas
//-- end variable rujukan

//-- start variable sewa
//$sewaIGDLunas + $sewaPenjaminIGDLunas + $sewaRajalLunas + $sewaPenjaminRajalLunas + $sewaRanapLunas + $sewaPenjaminRanapLunas
//$sewaIGDNoLunas + $sewaPenjaminIGDNoLunas + $sewaRajalNoLunas + $sewaPenjaminRajalNoLunas + $sewaRanapNoLunas + $sewaPenjaminRanapNoLunas
//-- end variable sewa

//-- start variable pendaftaran
//$pendaftaranIGDLunas + $pendaftaranPenjaminIGDLunas + $pendaftaranRajalLunas + $pendaftaranPenjaminRajalLunas + $pendaftaranRanapLunas + $pendaftaranPenjaminRanapLunas
//$pendaftaranIGDNoLunas + $pendaftaranPenjaminIGDNoLunas + $pendaftaranRajalNoLunas + $pendaftaranPenjaminRajalNoLunas + $pendaftaranRanapNoLunas + $pendaftaranPenjaminRanapNoLunas
//-- end variable pendaftaran

//-- start variable usg
//$usgIGDLunas + $usgPenjaminIGDLunas + $usgRajalLunas + $usgPenjaminRajalLunas + $usgRanapLunas + $usgPenjaminRanapLunas
//$usgIGDNoLunas + $usgPenjaminIGDNoLunas + $usgRajalNoLunas + $usgPenjaminRajalNoLunas + $usgRanapNoLunas + $usgPenjaminRanapNoLunas
//-- end variable usg

//-- start variable ekg
//$ekgIGDLunas + $ekgPenjaminIGDLunas + $ekgRajalLunas + $ekgPenjaminRajalLunas + $ekgRanapLunas + $ekgPenjaminRanapLunas
//$ekgIGDNoLunas + $ekgPenjaminIGDNoLunas + $ekgRajalNoLunas + $ekgPenjaminRajalNoLunas + $ekgRanapNoLunas + $ekgPenjaminRanapNoLunas
//-- end variable ekg

//-- start variable rm
//$rmIGDLunas + $rmPenjaminIGDLunas + $rmRajalLunas + $rmPenjaminRajalLunas + $rmRanapLunas + $rmPenjaminRanapLunas
//$rmIGDNoLunas + $rmPenjaminIGDNoLunas + $rmRajalNoLunas + $rmPenjaminRajalNoLunas + $rmRanapNoLunas + $rmPenjaminRanapNoLunas
//-- end variable rm

//-- start variable Apotik Klinik
//$obatIGDLunas + $obatPenjaminIGDLunas + $obatRajalLunas + $obatPenjaminRajalLunas + $obatRanapLunas + $obatPenjaminRanapLunas
//$obatIGDNoLunas + $obatPenjaminIGDNoLunas + $obatRajalNoLunas + $obatPenjaminRajalNoLunas + $obatRanapNoLunas + $obatPenjaminRanapNoLunas
//-- end variable Apotik Klinik

//-- start variable Apotik Umum
//$obatApotikUmum + $obatApotikUmumPenjamin
//-- end variable Apotik Umum

//-- start variable Return Apotik
//$obatRetur + $obatReturPenjamin
//-- end variable Return Apotik


/*
?>


<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD"align="center">VISITE</td>
				<td class="TBL_HEAD"align="center">ALAT</td>
				<td class="TBL_HEAD"align="center">RADIOLOGI</td>
				<td class="TBL_HEAD"align="center">TINDAKAN	</td>
				<td class="TBL_HEAD"align="center">KONSULTASI</td>
				<td class="TBL_HEAD"align="center">LABORATORIUM</td>
				<td class="TBL_HEAD"align="center">AMBULANCE</td>
				<td class="TBL_HEAD"align="center">ESG/ECG</td>
				<td class="TBL_HEAD"align="center">OKSIGEN/NO2</td>
				<td class="TBL_HEAD"align="center">FISIOTERAPHI/NEBULIZER</td>
				<td class="TBL_HEAD"align="center">ADMINISTRASI</td>
				<td class="TBL_HEAD"align="center">Lain Lain</td>
				<td class="TBL_HEAD"align="center">PEMERIKSAAN DOKTER</td>
				<td class="TBL_HEAD"align="center">AKOMODASI</td>
				<td class="TBL_HEAD"align="center">TRANSFUSI</td>
				<td class="TBL_HEAD"align="center">JASA RUJUKAN</td>
				<td class="TBL_HEAD"align="center">SEWA KAMAR OPERASI</td>
				<td class="TBL_HEAD"align="center">PENDAFTARAN</td>
				<td class="TBL_HEAD"align="center">USG</td>
				<td class="TBL_HEAD"align="center">EKG</td>
				<td class="TBL_HEAD"align="center">RM</td>
			</tr>
			
			<tr valign="top" class="TBL_BODY" >  
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
				<td class="TBL_BODY" align="center"><?php echo $tindakanRajalLunas; ?> </td>
			</tr>	

</TABLE>
<?php
*/



//*Pendapatan Ranap (410103)
$ranap=($visiteRanapLunas*(30/100))+($visitePenjaminRanapLunas*(30/100))+$akomodasiPenjaminRanapLunas+$akomodasiRanapLunas;

//Pendapatan Non Operasional (420188)
$admin=$administrasiRajalLunas + $administrasiPenjaminRajalLunas+$administrasiRajalNoLunas + 
$administrasiPenjaminRajalNoLunas+$administrasiRanapNoLunas+
$administrasiPenjaminIGDNoLunas+$administrasiPenjaminIGDLunas+
$administrasiPenjaminRanapNoLunas+$sewaRanapNoLunas+
$sewaPenjaminRanapNoLunas;

//* Radiologi (410108)
$radiologi=$radiologiRajalLunas + $radiologiPenjaminRajalLunas + $radiologiRanapLunas + $radiologiPenjaminRanapLunas+
$usgRajalLunas + $usgPenjaminRajalLunas + $usgRanapLunas + $usgPenjaminRanapLunas+$radiologiPenjaminRajalNoLunas+
$radiologiRanapNoLunas+
$esgRanapNoLunas+
$esgPenjaminRanapNoLunas+ $radiologiIGDLunas + $radiologiPenjaminIGDLunas+ $radiologiIGDNoLunas + $radiologiPenjaminIGDNoLunas+ $esgIGDLunas + $esgPenjaminIGDLunas;

//* Piutang Jasa Medik  (210504)
$piutangJasMed=$konsultasiRanapLunas+$konsultasiPenjaminRanapLunas+	$pemeriksaanRanapLunas+$pemeriksaanPenjaminRanapLunas;

//*Laboratorium (410107)
$lab=($laboratoriumRajalLunas*(50/100)) + ($laboratoriumPenjaminRajalLunas*(50/100)) + 
$laboratoriumRanapLunas + $laboratoriumPenjaminRanapLunas+$laboratoriumIGDNoLunas+
$laboratoriumPenjaminIGDNoLunas+$laboratoriumRanapNoLunas+$laboratoriumPenjaminRanapNoLunas+ $laboratoriumIGDLunas + $laboratoriumPenjaminIGDLunas;

//*Pendapatan Rehab Medik (410109)
$fisio=$fisioteraphiRajalLunas + $fisioteraphiPenjaminRajalLunas + $fisioteraphiRanapLunas + $fisioteraphiPenjaminRanapLunas+$fisioteraphiRanapNoLunas+
$fisioteraphiPenjaminRanapNoLunas+
$fisioteraphiRajalNoLunas+
$fisioteraphiPenjaminRajalNoLunas+
$fisioteraphiIGDNoLunas+
$fisioteraphiPenjaminIGDNoLunas+$usgRanapNoLunas+
$usgPenjaminRanapNoLunas+$fisioteraphiIGDLunas + $fisioteraphiPenjaminIGDLunas+$usgIGDLunas + $usgPenjaminIGDLunas;


//*Pendapatan Non Operasional Lainnya (420188)
$lain2=$lainRanapLunas+$lainPenjaminRanapLunas+
$lainRanapNoLunas+
$lainPenjaminRanapNoLunas+
$pendaftaranRanapNoLunas+
$pendaftaranPenjaminRanapNoLunas;


//*Utang Jasa Dokter (210504)
$konsul=($konsultasiRajalLunas*(70/100)) + ($konsultasiPenjaminRajalLunas*(70/100)) + 
($pemeriksaanRajalLunas*(70/100)) + ($pemeriksaanPenjaminRajalLunas*(70/100)) + 
($tindakanRajalLunas*(50/100)) + ($tindakanPenjaminRajalLunas*(50/100))+
($konsultasiRajalNoLunas*(70/100)) + ($konsultasiPenjaminRajalNoLunas*(70/100)) + 
($pemeriksaanRajalNoLunas*(70/100)) + ($pemeriksaanPenjaminRajalNoLunas*(70/100)) + 
($tindakanRajalNoLunas*(50/100)) + ($tindakanPenjaminRajalNoLunas*(50/100))+
($pemeriksaanRanapNoLunas*(70/100)) + ($pemeriksaanPenjaminRanapNoLunas*(70/100)) + 
($pemeriksaanRanapLunas*(70/100)) + ($pemeriksaanPenjaminRanapLunas*(70/100)) + 
($tindakanRanapNoLunas*(50/100)) + ($tindakanPenjaminRanapNoLunas*(50/100))+
($tindakanRanapLunas*(50/100)) + ($tindakanPenjaminRanapLunas*(50/100)) +
($visiteRanapLunas*(70/100))+($visitePenjaminRanapLunas*(70/100))+
($visiteRanapNoLunas*(70/100))+($visitePenjaminRanapNoLunas*(70/100))+
($pemeriksaanIGDNoLunas*(50/100)) + ($pemeriksaanIGDLunas*(50/100)) + 
($pemeriksaanPenjaminIGDNoLunas*(50/100))+($pemeriksaanPenjaminIGDLunas*(50/100))+
($tindakanIGDLunas*(50/100)) +($tindakanIGDNoLunas*(50/100)) + 
($tindakanPenjaminIGDLunas*(50/100))+ ($tindakanPenjaminIGDNoLunas*(50/100))+
($konsultasiIGDLunas*(70/100))+ ($konsultasiIGDNoLunas*(70/100)) + 
($konsultasiPenjaminIGDNoLunas*(30/100))+ ($konsultasiPenjaminIGDLunas*(30/100))+
($laboratoriumIGDLunas*(50/100)) + ($laboratoriumIGDNoLunas*(50/100)) + 
($laboratoriumPenjaminIGDLunas*(50/100)) +($laboratoriumPenjaminIGDNoLunas*(50/100)) + 
($ambulanceIGDLunas*(50/100)) + ($ambulanceIGDNoLunas*(50/100)) + 
($ambulancePenjaminIGDNoLunas*(50/100))+ ($ambulancePenjaminIGDLunas*(50/100));

//*Pendapatan Rajal (410102)
$PenRajal=($tindakanRajalLunas*(50/100)) +($tindakanRajalNoLunas*(50/100)) + 
($tindakanPenjaminRajalLunas*(50/100))+ ($tindakanPenjaminRajalNoLunas*(50/100)) + 
($konsultasiRajalLunas*(30/100))+ ($konsultasiRajalNoLunas*(30/100)) + 
($konsultasiPenjaminRajalNoLunas*(30/100))+ ($konsultasiPenjaminRajalLunas*(30/100)) +
($laboratoriumRajalLunas*(50/100)) + ($laboratoriumRajalNoLunas*(50/100)) + 
($laboratoriumPenjaminRajalLunas*(50/100)) +($laboratoriumPenjaminRajalNoLunas*(50/100)) + 
($ambulanceRajalLunas*(50/100)) + ($ambulanceRajalNoLunas*(50/100)) + 
($ambulancePenjaminRajalNoLunas*(50/100))+ ($ambulancePenjaminRajalLunas*(50/100)) + 
($pemeriksaanRajalNoLunas*(30/100)) + ($pemeriksaanRajalLunas*(30/100))+ 
($pemeriksaanPenjaminRajalNoLunas*(30/100))+($pemeriksaanPenjaminRajalLunas*(30/100));


//* IGD Lunas (410101)
$igd=($pemeriksaanIGDNoLunas*(50/100)) + ($pemeriksaanIGDLunas*(50/100)) + 
($pemeriksaanPenjaminIGDNoLunas*(50/100))+($pemeriksaanPenjaminIGDLunas*(50/100))+
($tindakanIGDLunas*(50/100)) +($tindakanIGDNoLunas*(50/100)) + 
($tindakanPenjaminIGDLunas*(50/100))+ ($tindakanPenjaminIGDNoLunas*(50/100))+
($konsultasiIGDLunas*(30/100))+ ($konsultasiIGDNoLunas*(30/100)) + 
($konsultasiPenjaminIGDNoLunas*(30/100))+ ($konsultasiPenjaminIGDLunas*(30/100))+
($laboratoriumIGDLunas*(50/100)) + ($laboratoriumIGDNoLunas*(50/100)) + 
($laboratoriumPenjaminIGDLunas*(50/100)) +($laboratoriumPenjaminIGDNoLunas*(50/100)) + 
($ambulanceIGDLunas*(50/100)) + ($ambulanceIGDNoLunas*(50/100)) + 
($ambulancePenjaminIGDNoLunas*(50/100))+ ($ambulancePenjaminIGDLunas*(50/100));


//*Pendapatan Farmasi (410106)
$farmasi=$obatIGDLunas + $obatPenjaminIGDLunas + $obatRajalLunas + $obatPenjaminRajalLunas + $obatRanapLunas + $obatPenjaminRanapLunas+
$obatIGDNoLunas + $obatPenjaminIGDNoLunas + $obatRajalNoLunas + $obatPenjaminRajalNoLunas+ $obatRanapNoLunas + $obatPenjaminRanapNoLunas;

//*Piutang Pasien Inap (110401)
$piutangRanap=
($visiteRanapNoLunas*(30/100))+($visitePenjaminRanapNoLunas*(30/100))+
$akomodasiPenjaminRanapNoLunas+
$akomodasiRanapNoLunas;


//Piutang Pasien Pulang (110402)
if ($_GET["status"]=="BELUM LUNAS"){
$piutangPasPul=
$farmasi+
$alatRajalNoLunas+
$alatPenjaminRajalNoLunas+
$radiologiPenjaminRajalNoLunas+
$radiologiRajalNoLunas+
$tindakanRajalNoLunas+
$tindakanPenjaminRajalNoLunas+
$konsultasiRajalNoLunas+
$konsultasiPenjaminRajalNoLunas+
$laboratoriumRajalNoLunas+
$laboratoriumPenjaminRajalNoLunas+
$ambulanceRajalNoLunas+
$ambulancePenjaminRajalNoLunas+
$administrasiRajalNoLunas+
$administrasiPenjaminRajalNoLunas+
$lainRajalNoLunas+
$lainPenjaminRajalNoLunas+
$pemeriksaanRajalNoLunas+
$pemeriksaanPenjaminRajalNoLunas+
$transfusiRajalNoLunas+
$transfusiPenjaminRajalNoLunas+
$rujukanRajalNoLunas+
$rujukanPenjaminRajalNoLunas+
$sewaRajalNoLunas+
$sewaPenjaminRajalNoLunas+
$pendaftaranRajalNoLunas+
$pendaftaranPenjaminRajalNoLunas+
$usgRajalNoLunas+
$usgPenjaminRajalNoLunas+
$rmRajalNoLunas+
$rmPenjaminRajalNoLunas+
$visiteIGDNoLunas+
$visitePenjaminIGDNoLunas+
$akomodasiPenjaminIGDNoLunas+
$akomodasiIGDNoLunas+
$radiologiPenjaminRajalNoLunas+
$radiologiIGDNoLunas+
$tindakanIGDNoLunas+
$tindakanPenjaminIGDNoLunas+
$konsultasiIGDNoLunas+
$konsultasiPenjaminIGDNoLunas+
$ambulanceIGDNoLunas+
$ambulancePenjaminIGDNoLunas+
$administrasiIGDNoLunas+
$administrasiPenjaminIGDNoLunas+
$lainIGDNoLunas+
$lainPenjaminIGDNoLunas+
$pemeriksaanIGDNoLunas+
$pemeriksaanPenjaminIGDNoLunas+
$transfusiIGDNoLunas+
$transfusiPenjaminIGDNoLunas+
$rujukanIGDNoLunas+
$rujukanPenjaminIGDNoLunas+
$pendaftaranIGDNoLunas+
$pendaftaranPenjaminIGDNoLunas+
$usgIGDNoLunas+
$usgPenjaminIGDNoLunas;
}

//*Pendapatan Kamar Operasi (410105)
$PendKamOp=$sewaRanapLunas+$sewaPenjaminRanapLunas+$sewaRanapNoLunas+$sewaPenjaminRanapNoLunas;

/* Pendapatan Jasa RS bergantung asal pendapatannya.
$tindakanRanapLunas
$tindakanPenjaminRanapLunas
*/

/*Jasa Tindakan perawat
$laboratoriumRajalLunas 50%
$laboratoriumPenjaminRajalLunas 50%
$ambulanceRajalLunas 50%
$ambulancePenjaminRajalLunas 50%
$jtp=($tindakanRajalLunas*(50/100)) + ($tindakanPenjaminRajalLunas*(50/100)) + ($konsultasiRajalLunas*(50/100)) + ($konsultasiPenjaminRajalLunas*(70/100))+
($laboratoriumRajalLunas*(50/100)) + ($laboratoriumPenjaminRajalLunas*(50/100)) + ($ambulanceRajalLunas*(50/100)) + ($ambulancePenjaminRajalLunas*(50/100))
*/

//* Pendapatan Alat
$alat=$alatRajalLunas + $alatPenjaminRajalLunas + $alatRanapLunas + $alatPenjaminRanapLunas+
$alatIGDNoLunas+
$alatPenjaminIGDNoLunas+
$alatRanapNoLunas+
$alatPenjaminRanapNoLunas;



/*Pendapatan Kendaraan
$ambulanceRajalLunas + $ambulancePenjaminRajalLunas + $ambulanceRanapLunas + $ambulancePenjaminRanapLunas+
$ambulanceRanapNoLunas+
$ambulancePenjaminRanapNoLunas+ 
$ambulanceIGDLunas + $ambulancePenjaminIGDLunas ;
*/

//*Pendapatan Diagnostik
$esg=$esgRajalLunas + $esgPenjaminRajalLunas + $esgRanapLunas + $esgPenjaminRanapLunas+
$ekgRajalLunas + $ekgPenjaminRajalLunas + $ekgRanapLunas + $ekgPenjaminRanapLunas+
$esgIGDNoLunas+
$esgPenjaminIGDNoLunas+
$ekgIGDNoLunas+
$ekgPenjaminIGDNoLunas+
$ekgRajalNoLunas+
$ekgPenjaminRajalNoLunas+
$esgRajalNoLunas+
$esgPenjaminRajalNoLunas+
$ekgRanapNoLunas+
$ekgPenjaminRanapNoLunas+ $ekgIGDLunas + $ekgPenjaminIGDLunas;



//*Pendapatan Oksigen tambahkan 1 level di bawah pendapatan farmasi
$oksigen=$oksigenRajalLunas + $oksigenPenjaminRajalLunas + $oksigenRanapLunas + $oksigenPenjaminRanapLunas+
$oksigenRanapNoLunas+$oksigenPenjaminRanapNoLunas+$oksigenIGDNoLunas+$oksigenPenjaminIGDNoLunas+
$oksigenRajalNoLunas+
$oksigenPenjaminRajalNoLunas+ $oksigenIGDLunas + $oksigenPenjaminIGDLunas;



/*Pendapatan transfusi
$transfusiRajalLunas + $transfusiPenjaminRajalLunas + $transfusiRanapLunas + $transfusiPenjaminRanapLunas+
$transfusiRanapNoLunas+
$transfusiPenjaminRanapNoLunas + $transfusiIGDLunas;
*/


/*Pendapatan Rujukan
$rujukanRajalLunas + $rujukanPenjaminRajalLunas + $rujukanRanapLunas + $rujukanPenjaminRanapLunas+
$rujukanRanapNoLunas+
$rujukanPenjaminRanapNoLunas+ $rujukanPenjaminIGDLunas+ $rujukanIGDLunas
*/


/*Pendapatan lain2
$rmRanapLunas;
$rmPenjaminRanapLunas;
$rmRanapNoLunas+
$rmPenjaminRanapNoLunas+
$rmIGDNoLunas+
$rmPenjaminIGDNoLunas;
*/

/*Utang Dana karyawan
$tindakanRajalLunas 50%
$tindakanPenjaminRajalLunas 50%
*/

//Pendapatan diterima di muka (210801)
$InputDeposit2=$deposit;


//*Pendapatan Kas Besar
if ($_GET['status']!="LUNAS"){
//$piutangPasPul;
//$kasbesar=$PenRajal+$konsul+$admin+$radiologi+$lab+$fisio+$InputDeposit;
}else{
$kasbesar=$farmasi+$PenRajal+$konsul+$admin+$radiologi+$lab+$fisio+$InputDeposit+
$piutangPasPul+$igd;
}

//*Pendapatan Kas Besar
//if ($_GET['status']!="RAWAT INAP"){$piutangPasPul='0';
//}else{
//$piutangPasPul;
//}


?>