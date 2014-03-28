<?php
require_once("lib/setting.php");
require_once("lib/terbilang.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once 'tagihan_ri';
/**
$total = $PX+$ekg+$konsultasi+$paket+$sewaKamarOperasi+$anestesi+$operasi+$adminRI+$askep+$admin+$konsultasiDokter+
		 $tindakan + $visite + $layananDokter + $konsul + $alat + $bhp + $obat + $laborat + $radiologi + $usg + 
		  $oksigen + $fisio + $ambulan + $akomodasi + $lain + $akomodasiMakan;
	$totalPenjamin = $PXPenjamin+$ekgPenjamin+$konsultasiPenjamin+$paketPenjamin+$sewaKamarOperasiPenjamin+$anestesiPenjamin+$operasiPenjamin+$adminPenjaminRI+$askepPenjamin+$adminPenjamin + $konsultasiDokterPenjamin+
			 $tindakanPenjamin + $visitePenjamin + $layananDokterPenjamin + $konsulPenjamin + $alatPenjamin + $bhpPenjamin + $obatPenjamin + $laboratPenjamin + $radiologiPenjamin + $usgPenjamin +
			 $oksigenPenjamin + $fisioPenjamin + $ambulanPenjamin + $akomodasiPenjamin + $lainPenjamin + $akomodasiMakanPenjamin;
			 */ 

echo '<br>PX:'.$PX;
echo '<br>EKG:'.$ekg;
echo '<br>KONSULTASI:'.$konsultasi;
echo '<br>PAKET:'.$paket;
echo '<br>SEWA KAMAR OPERASI:'.$sewaKamarOperasi;
echo '<br>ANESTESI:'.$anestesi;
echo '<br>OPERASI:'.$operasi;
echo '<br>ADMINISTRASI RAWAT INAP:'.$adminRI;
echo '<br>ASUHAN KEPERAWATAN:'.$askep;
echo '<br>PENDAFTARAN/ADMINISTRASI:'.$admin;
echo '<br>KONSULTASI DOKTER:'.$konsultasiDokter;
echo '<br>TINDAKAN:'.$tindakan;
echo '<br>VISITE:'.$visite;
echo '<br>LAYANAN DOKTER:'.$layananDokter;
echo '<br>KONSULTASI:'.$konsul;
echo '<br>ALAT:'.$alat;
echo '<br>BHP:'.$bhp;
echo '<br>OBAT:'.$obat;
echo '<br>LABORATORIUM:'.$laborat;
echo '<br>RADIOLOGI:'.$radiologi;
echo '<br>USG:'.$usg;
echo '<br>OKSIGEN:'.$oksigen;
echo '<br>FISIOTERAPI:'.$fisio;
echo '<br>AMBULAN:'.$ambulan;
echo '<br>AKOMODASI:'.$akomodasi;
echo '<br>LAIN - LAIN:'.$lain;
echo '<br>AKOMODASI MAKAN:'.$akomodasiMakan;			

