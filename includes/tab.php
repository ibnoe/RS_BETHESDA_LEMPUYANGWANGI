<?php

	$tab_disabled = array("pemeriksaan"=>true, "input_operasi"=>true,"layanan"=>true, "icd"=>true, "icd9"=>true, "riwayat"=>true,"riwayat_klinik"=>true,"unit_rujukan"=>true,"konsultasi"=>true,"resepobat"=>true);
	if ($_GET["act"] == "del" ) {
	$tab_disabled = array("pemeriksaan"=>false,"input_operasi"=>true, "layanan"=>false, "icd"=>false, "icd9"=>false, "riwayat"=>false,"riwayat_klinik"=>false,"unit_rujukan"=>false,"konsultasi"=>false,"resepobat"=>false);
	$tab_disabled[$_GET["sub"]] = true;
	$tab_disabled[$_POST["sub"]] = true;
	}
	
	$T = new TabBar();
	$T->addTab("$SC?p=$PID&list=pemeriksaan&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr ", "Hasil Pemeriksaan Pasien"	, $tab_disabled["pemeriksaan"]);
	if($_GET['p']=='p_operasi'){
	$T->addTab("$SC?p=$PID&list=input_operasi&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr&sub=input_operasi", "Input Operasi",$tab_disabled["input_operasi"]);
	}
	$T->addTab("$SC?p=$PID&list=layanan&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr&sub=layanan&sub2=nonpaket", "layanan / Tindakan"	, $tab_disabled["layanan"]);
	$T->addTab("$SC?p=$PID&list=icd&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr&sub=icd", "Pilih I C D"	, $tab_disabled["icd"]);
	$T->addTab("$SC?p=$PID&list=icd9&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr&sub=icd9", "Pilih I C D 9"	, $tab_disabled["icd9"]);
	$T->addTab("$SC?p=$PID&list=riwayat&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Riwayat Klinik"	, $tab_disabled["riwayat"]);
	$T->addTab("$SC?p=$PID&list=riwayat_klinik&rg=$rg&mr=$mr", "Riwayat Medis"	, $tab_disabled["riwayat_klinik"]);
	$T->addTab("$SC?p=$PID&list=unit_rujukan&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Status Akhir Pasien"	, $tab_disabled["unit_rujukan"]);
	$T->addTab("$SC?p=$PID&list=konsultasi&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Konsultasi"	, $tab_disabled["konsultasi"]);
    $T->addTab("$SC?p=$PID&list=resepobat&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Resep Obat"	, $tab_disabled["resepobat"]);


?>