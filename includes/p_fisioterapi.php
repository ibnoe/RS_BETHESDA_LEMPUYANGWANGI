<?php 	
		// Agung S. Menambahkan group by pada riwayat_klinik
		//Agung Sunandar 12:41 07/06/2012 menambahkan field yang kurang pada tab riwayat klinik
		// Agung Sunandar 13:23 07/06/2012 menambahkan operasi pada tab riwayat klinik

session_start();
$PID = "p_fisioterapi";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/visit_setting.php");
//--fungsi column color-------------- Agung Sunandar 22:58 26/06/2012
function color( $dstr, $r ) {

	    if($_GET['list2']=="tab1"){
	    	if ($dstr[8] == 'BELUM ADA TAGIHAN' ){
	    		return "<font color=red><b>{$dstr[$r]}</b></font>";
	    	}else{
	    		return "<font color=blue><b>{$dstr[$r]}</b></font>";
	    	}
	    }else{
	    	if ($dstr[7] == 'BELUM ADA TAGIHAN' ){
	    		return "<font color=red><b>{$dstr[$r]}</b></font>";
	    	}else{
	    		return "<font color=blue><b>{$dstr[$r]}</b></font>";
	    	}
	    }
}
//-------------------------------       	
$_GET["mPOLI"]=$setting_poli["fisioterapi"];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];

// Tambahan BHP
$POLI=$setting_poli["fisioterapi"];
// ======================================

		if (isset($_GET["del"])) {
		    $temp = $_SESSION["layanan"];
		    unset($_SESSION["layanan"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del"]) $_SESSION["layanan"][count($_SESSION["layanan"])] = $v;
		    }
				header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&poli=".$_GET["mPOLI"]."&mr=" . $_GET["mr"]."&sub=layanan&sub2=nonpaket");
		    	exit;
		
		/* Untuk menambahkan hapus Paket layanan */
		/* Agung Sunandar 16:28 26/06/2012       */
		
		}elseif (isset($_GET["del1"])) {
		    $temp = $_SESSION["layanan2"];
		    unset($_SESSION["layanan2"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del1"]) $_SESSION["layanan2"][count($_SESSION["layanan2"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&poli=".$_GET["mPOLI"]."&mr=" . $_GET["mr"]."&sub=layanan&sub2=paket");
		    	exit;
		    
		} elseif (isset($_GET["del-icd"])) {
		    $temp = $_SESSION["icd"];
		    unset($_SESSION["icd"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-icd"]) $_SESSION["icd"][count($_SESSION["icd"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    	exit;
		} elseif (isset($_GET["del-icd9"])) {
		    $temp = $_SESSION["icd9"];
		    unset($_SESSION["icd9"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-icd9"]) $_SESSION["icd9"][count($_SESSION["icd9"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd9&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=icd9");
		    	exit;      
		} elseif (isset($_GET["del-obat"])) {
		    $temp = $_SESSION["obat"];
		    unset($_SESSION["obat"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-obat"]) $_SESSION["obat"][count($_SESSION["obat"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=obat");
		    	exit;
		    // Tambahan BHP    
		} elseif (isset($_GET["del-obat2"])) {
		    $temp = $_SESSION["obat2"];
		    unset($_SESSION["obat2"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-obat2"]) $_SESSION["obat2"][count($_SESSION["obat2"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&poli=$POLI&sub=layanan&sub2=bhp");
		    	exit; 
		// ==============================================    

		} elseif (isset($_GET["del-pjm"])) {
		    $temp = $_SESSION["pjm"][$_GET["del-pjm"]];
		    unset($_SESSION["pjm"][$_GET["del-pjm"]]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-emp"])
		            $_SESSION["pjm"][$_GET["del-pjm"]][count($_SESSION["pjm"][$_GET["del-pjm"]])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=pjm");
		    	exit;
		    
		} elseif (isset($_GET["s2note"])) {
		    $_SESSION["s2note"] = $_GET["s2note"];
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    	exit;
		    
		} elseif (isset($_GET["obat"])) {
		    $r = pg_query($con,"SELECT * FROM RSV0004 WHERE ID = '".$_GET["obat"]."'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);
		
		    if (is_array($_SESSION["obat"])) {
		        $cnt = count($_SESSION["obat"]);
		    } else {
		        $cnt = 0;
		    }
		    
		    if (!empty($d->obat)) {
		        $_SESSION["obat"][$cnt]["id"]     = $_GET["obat"];
		        $_SESSION["obat"][$cnt]["desc"]   = $d->obat;
		        //$_SESSION["obat"][$cnt]["dosis"]  = $_GET["dosis_obat"];
		        $_SESSION["obat"][$cnt]["jumlah"] = $_GET["jumlah_obat"];
		        $_SESSION["obat"][$cnt]["harga"]  = $d->harga;
		        $_SESSION["obat"][$cnt]["persen"]  = $_GET["persen"];
				$_SESSION["obat"][$cnt]["diskon"]  = ($_GET["persen"]/100) * ($d->harga * $_GET["jumlah_obat"]);
		        $_SESSION["obat"][$cnt]["total"]  = ($d->harga * $_GET["jumlah_obat"]) - ($_GET["persen"]/100) * ($d->harga * $_GET["jumlah_obat"]);
		        //$_SESSION["obat"][$cnt]["satuan"] = $d->satuan;
		        unset($_SESSION["SELECT_OBAT"]);
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=obat");
		    	exit;
    
		} elseif (isset($_GET["icd"])) {
		    $r = pg_query($con,"SELECT * FROM RSV0005 WHERE DIAGNOSIS_CODE = '" . $_GET["icd"] . "'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);
		    if (is_array($_SESSION["icd"])) {
		        $cnt = count($_SESSION["icd"]);
		    } else {
		        $cnt = 0;
		    }
                } elseif (isset($_GET["icd9"])) {
                    
		    $r = pg_query($con,"SELECT * FROM icd_9 WHERE kode = '" . $_GET["icd9"] . "'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);
		    if (is_array($_SESSION["icd9"])) {
		        $cnt = count($_SESSION["icd9"]);
		    } else {
		        $cnt = 0;
		    }
		    
		    if (strlen($d->nama) > 0) {
		        $_SESSION["icd9"][$cnt]["id"]   = $_GET["icd9"];
		        $_SESSION["icd9"][$cnt]["desc"] = $d->nama;
		        unset($_SESSION["SELECT_ICD9"]);
		    }
		    header("Location: $SC?p=" . $_GET["p"] . "&list=icd9&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=icd9");
		    exit;     
		    // Tambahan BHP 
		} elseif (isset($_GET["obat2"])) {
		    $r = @pg_query($con,"SELECT * FROM RSV0004 WHERE ID = '".$_GET["obat2"]."'");
		    $d = @pg_fetch_object($r);
		    @pg_free_result($r);
		
		    if (is_array($_SESSION["obat2"])) {
		        $cnt = count($_SESSION["obat2"]);
		    } else {
		        $cnt = 0;
		    }
		    
                    $cek_qty= getFromTable("select 'qty_'||tc from rs00001 where tt='GDP' and tc_poli='".$_GET["poli"]."' ") ;
                    
		    if (!empty($d->obat)) {
		        $_SESSION["obat2"][$cnt]["id"]     = $_GET["obat2"];
		        $_SESSION["obat2"][$cnt]["desc"]   = $d->obat;
		        $_SESSION["obat2"][$cnt]["satuan"]  = $_GET["satuan"];
		        $_SESSION["obat2"][$cnt]["jumlah"] = $_GET["jumlah_obat"];
		        $_SESSION["obat2"][$cnt]["harga"]  = $d->harga;
		        $_SESSION["obat2"][$cnt]["persen"]  = $_GET["persen"];
				$_SESSION["obat2"][$cnt]["diskon"]  = ($_GET["persen"]/100) * ($d->harga * $_GET["jumlah_obat"]);
		        $_SESSION["obat2"][$cnt]["total"]  = ($d->harga * $_GET["jumlah_obat"]) - ($_GET["persen"]/100) * ($d->harga * $_GET["jumlah_obat"]);
                $_SESSION["obat2"][$cnt]["stok"]   = $cek_qty;
		        $_SESSION["obat2"][$cnt]["is_racikan"] = $_GET["is_racikan"];
                $_SESSION["obat2"][$cnt]["nip"] = '0';
		        unset($_SESSION["SELECT_OBAT2"]);
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&poli=$POLI&sub=layanan&sub2=bhp");
		    	exit; 
		// ==============================================  

		    if (strlen($d->description) > 0) {
		        $_SESSION["icd"][$cnt]["id"]   = $_GET["icd"];
		        $_SESSION["icd"][$cnt]["desc"] = $d->description;
		        $_SESSION["icd"][$cnt]["kate"] = $d->category;
		        unset($_SESSION["SELECT_ICD"]);
		    }
		    header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    exit;
		    
		} elseif (isset($_GET["layanan"])) {
			
		    $r = pg_query($con,"SELECT * FROM RSV0034 WHERE ID = '" . $_GET["layanan"] . "'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);

    $gol_tindakan = getFromTable("select golongan_tindakan_id from rs00034 where id='".$_GET["layanan"]."'");
   // $is_range = $d->harga_atas > 0 || $d->harga_bawah > 0;

    if ($d->id) {
    //   if (($is_range && isset($_GET["harga"])) || (!$is_range)) {
            if (is_array($_SESSION["layanan"])) {
                $cnt = count($_SESSION["layanan"]);
            } else {
                $cnt = 0;
            }
           
            $dokter = getFromTable("select nama from rs00017 where id = '".$_SESSION[SELECT_EMP]."'");
            $harga = $is_range ? $_GET["harga"] : $d->harga;
            $_SESSION["layanan"][$cnt]["id"]     = str_pad($_GET["layanan"],5,"0",STR_PAD_LEFT);
            
            if ($d->klasifikasi_tarif) $embel= " - ".$d->klasifikasi_tarif;
            $_SESSION["layanan"][$cnt]["nama"]   = $d->layanan . $embel;
            $_SESSION["layanan"][$cnt]["jumlah"] = $_GET["jumlah"];
            $_SESSION["layanan"][$cnt]["satuan"] = $d->satuan;
            $_SESSION["layanan"][$cnt]["harga"]  = $harga;
            $_SESSION["layanan"][$cnt]["persen"]  = $_GET["persen"];
			$_SESSION["layanan"][$cnt]["diskon"]  = ($_GET["persen"]/100) * ($harga * $_GET["jumlah"]);
            $_SESSION["layanan"][$cnt]["total"]  = ($harga * $_GET["jumlah"]) - ($_GET["persen"]/100) * ($harga * $_GET["jumlah"]);
            $_SESSION["layanan"][$cnt]["dokter"]  = $dokter;
            $_SESSION["layanan"][$cnt]["nip"]  = $_SESSION[SELECT_EMP];
            

		} 

            unset($_SESSION["SELECT_LAYANAN"]);
            unset($_SESSION["SELECT_EMP"]);

            header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&sub=layanan&sub2=nonpaket");
            exit;
            

    
}
		/* Untuk menambahkan hapus Paket layanan */
		/* Agung Sunandar 16:28 26/06/2012       */

elseif (isset($_GET["layanan2"])) {
			
		    $r = pg_query($con,"SELECT * FROM rs99996 WHERE ID = '" . $_GET["layanan2"] . "'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);


    if ($d->id) {
            if (is_array($_SESSION["layanan2"])) {
                $cnt = count($_SESSION["layanan2"]);
            } else {
                $cnt = 0;
            }

            //$sumber_pendapatan_id = getFromTable("select sumber_pendapatan_id from rsv0034 where id = '" . $_SESSION["SELECT_LAYANAN"] . "'");

            $dokter = getFromTable("select nama from rs00017 where id = '".$_SESSION[SELECT_EMP2]."'");
            $harga = $d->harga_paket;
            $_SESSION["layanan2"][$cnt]["id"]     = str_pad($_GET["layanan2"],5,"0",STR_PAD_LEFT);
            $cek_qty= getFromTable("select 'qty_'||tc from rs00001 where tt='GDP' and tc_poli='".$_GET["poli"]."' ") ;
            //if ($d->klasifikasi_tarif) $embel= " - ".$d->klasifikasi_tarif;
            $_SESSION["layanan2"][$cnt]["nama"]   = $d->description;
			$_SESSION["layanan2"][$cnt]["jumlah"] = $_GET["jumlah"];
            $_SESSION["layanan2"][$cnt]["satuan"] = "KALI";
            $_SESSION["layanan2"][$cnt]["harga"]  = $d->harga_paket;
			$_SESSION["layanan2"][$cnt]["persen"]  = $_GET["persen"];
			$_SESSION["layanan2"][$cnt]["diskon"]  = ($_GET["persen"]/100) * ($d->harga_paket * $_GET["jumlah"]);
            $_SESSION["layanan2"][$cnt]["total"]  = ($d->harga_paket * $_GET["jumlah"]) - ($_GET["persen"]/100) * ($d->harga_paket * $_GET["jumlah"]);
            $_SESSION["layanan2"][$cnt]["dokter"]  = $dokter;
			$_SESSION["layanan2"][$cnt]["stok"]  = $cek_qty;
            $_SESSION["layanan2"][$cnt]["nip"]  = $_SESSION[SELECT_EMP2];

            }
            unset($_SESSION["SELECT_LAYANAN2"]);
            unset($_SESSION["SELECT_EMP"]);
            unset($_SESSION["SELECT_EMP2"]);

            header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&sub=2&sub2=paket");
            exit;

    }
echo "<table border=0 width='100%'><tr><td>";
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  UNIT FISIOTERAPI");
//title_print();
title_excel("p_fisioterapi&list=".$_GET["list"]."&rg=".$_GET["rg"]."&poli=".$_GET["poli"]."&mr=".$_GET["mr"]." ");
echo "</td></tr></table>";

unset($_GET["layanan"]);

$reg = $_GET["rg"];
$reg2 = $_GET["rg"];

	$tab_disabled = array("pemeriksaan_dr"=>true,"pemeriksaan_dr1"=>true,"pemeriksaan_op"=>true,"pemeriksaan_ot"=>true,"pemeriksaan_tw"=>true, "layanan"=>true, "icd"=>true, "icd9"=>true, "riwayat"=>true,"riwayat_klinik"=>true,"unit_rujukan"=>true,"konsultasi"=>true);
	if ($_GET["act"] == "del" ) {
	$tab_disabled = array("pemeriksaan_dr"=>false,"pemeriksaan_dr1"=>false,"pemeriksaan_op"=>false,"pemeriksaan_ot"=>false,"pemeriksaan_tw"=>false, "layanan"=>false, "icd"=>false, "icd9"=>false, "riwayat"=>false,"riwayat_klinik"=>false,"unit_rujukan"=>false,"konsultasi"=>false);
	$tab_disabled[$_GET["sub"]] = true;
	$tab_disabled[$_POST["sub"]] = true;
	}
	
	$T = new TabBar();
	$T->addTab("$SC?p=$PID&list=pemeriksaan_dr&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr ", "Dr. Specialist RM"	, $tab_disabled["pemeriksaan_dr"]);
	$T->addTab("$SC?p=$PID&list=pemeriksaan_dr1&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr ", "Fisioterapi"	, $tab_disabled["pemeriksaan_dr1"]);
	$T->addTab("$SC?p=$PID&list=pemeriksaan_op&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr ", "Ortotik Prostetik"	, $tab_disabled["pemeriksaan_op"]);
	$T->addTab("$SC?p=$PID&list=pemeriksaan_ot&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr ", "Okupasi Terapi"	, $tab_disabled["pemeriksaan_ot"]);
	$T->addTab("$SC?p=$PID&list=pemeriksaan_tw&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr ", "Terapi Wicara"	, $tab_disabled["pemeriksaan_tw"]);
	$T->addTab("$SC?p=$PID&list=layanan&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr&sub=layanan", "layanan / Tindakan"	, $tab_disabled["layanan"]);
	$T->addTab("$SC?p=$PID&list=icd&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr&sub=icd", "Pilih I C D"	, $tab_disabled["icd"]);
	$T->addTab("$SC?p=$PID&list=icd9&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr&sub=icd9", "Pilih I C D 9"	, $tab_disabled["icd9"]);
	$T->addTab("$SC?p=$PID&list=riwayat&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Riwayat Klinik"	, $tab_disabled["riwayat"]);
	$T->addTab("$SC?p=$PID&list=riwayat_klinik&rg=$rg&mr=$mr", "Riwayat Medis"	, $tab_disabled["riwayat_klinik"]);
	$T->addTab("$SC?p=$PID&list=unit_rujukan&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Status Akhir Pasien"	, $tab_disabled["unit_rujukan"]);
	$T->addTab("$SC?p=$PID&list=konsultasi&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Konsultasi"	, $tab_disabled["konsultasi"]);

if ($reg > 0) {
    include ("keterangan");

    $total = 0.00;
    if (is_array($_SESSION["layanan"])) {
        foreach($_SESSION["layanan"] as $k => $l) {
            $total += $l["total"];
        }
    }

    //echo "<div class=box>";
if ($_GET["sub"] == "byr") {
        title("Total Tagihan: Rp. ".number_format($total,2));
        echo "<br><br><br>";
        $f = new Form("actions/p_fisioterapi.insert.php");
        $f->hidden("rg",$_GET["rg"]);
        $f->hidden("mr",$_GET["mr"]);
        $f->hidden("poli",$_GET["poli"]);
	$f->hidden("sub",$_GET["sub"]);
        $f->hidden("byr",$total);
        //$f->text("byr","Jumlah Pembayaran",15,15,$total,"STYLE='text-align:right'");
        $f->submit(" Simpan &amp; Bayar ");
        $f->execute();
    
} elseif ($_GET["list"] == "icd") {  // -------- ICD
	if(!$GLOBALS['print']){
		$T->show(6);
	}else{}
    include("icd.php");
	
        include("rincian3.php");
        
} elseif ($_GET["list"] == "icd9") {  // -------- ICD
	if(!$GLOBALS['print']){
		$T->show(7);
	}else{}
    include("icd9.php");
	
        include("rincian3.php");
        
    }elseif ($_GET["list"] == "layanan") { // ----------------------------- LAYANAN MEDIS
    	if(!$GLOBALS['print']){
    	$T->show(5);
    	}else{}
        //-------------------------------------------------------------------------------------------
		//start check status bayar pasien
		$StatusBayar = getFromTable("select statusbayar from rsv0012 where id = '".$_GET["rg"]."'");
		$StatusTagih = getFromTable("select tagih from rsv0012 where id = '".$_GET["rg"]."'");
		$StatusInap = getFromTable("select rawat_inap from rs00006 where id = '".$_GET["rg"]."'");
		if($StatusInap == 'I'){
			$StatusCHK = getFromTable("select count(trans_types) from rs00010 where no_reg = '".$_GET["rg"]."' and trans_types='CHK'");
		}else{
			$StatusCHK = 1;
		}
		$cekKonsul = getFromTable("select max(tanggal_konsul) from c_visit where no_reg = '".$_GET["rg"]."' and id_konsul='".$_GET["mPOLI"]."'");
		//var_dump($StatusInap);
		//end check status bayar pasien
		
		if(($StatusBayar=='LUNAS')&&($StatusTagih > 0) && $StatusCHK!=0 && $cekKonsul!=date('Y-m-d')){
			echo "<div align=center><br>";
			echo "<font color=red size=3><blink>PERINGATAN </blink>!</font><br>";
			echo "<font color=red size=3>TAGIHAN PASIEN <font color=navy><blink>".$StatusBayar."</blink></font>, INPUT LAYANAN CLOSED<br>";
			echo "</div>";
		}else{	
			include ("pelayanan.php");
        }
		//-------------------------------------------------------------------------------------------
		
		       
       include("rincian3.php"); 
	   
        echo "\n<script language='JavaScript'>\n";
	    echo "function selectLayanan() {\n";
	   	echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai(tag) {\n";
        echo "    sWin = window.open('popup/pegawai.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        
    if (empty($_GET[sub])) {
	    echo "function refreshSubmit() {\n";
	    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
	    echo "}\n";
	    echo "refreshSubmit();\n";
	    }
	    echo "</script>\n";
		
		
		
    } elseif($_GET["list"] == "riwayat") {
    	if(!$GLOBALS['print']){
    	$T->show(8);
    	}else{}
    	if ($_GET["act"] == "detail") {
			//detail fisioterapi  najla 09012011
			if($_GET['poli']==205){
				$sql = "select a.*,b.nama,g.nama as dok_1, h.nama as dok_2, i.nama as dok_3,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,f.layanan 
						from c_visit a 
						left join rs00017 b on a.id_dokter = B.ID 
						left join rs00017 g on a.id_perawat = g.id 
						left join rs00017 h on a.id_perawat1 = h.id
                                                left join rs00017 i on a.id_perawat2 = i.id 
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00034 f on f.id::text = e.item_id
						where a.no_reg='{$_GET['rg']}' and a.id_poli='205' ";
				$r = pg_query($con,$sql);
				$n = pg_num_rows($r);
			    if($n > 0) $d = pg_fetch_array($r);
			    pg_free_result($r);
				//echo $sql;exit;			
			    $_GET['id'] = $_GET['rg'] ;	
	 			
			echo"<div class=box>";
			echo "<table width='100%' border='0'><tr><td colspan='2'>";
			echo"<div class=form_subtitle>PEMERIKSAAN PASIEN</div>";
			echo "</td></tr>";
    		echo "<tr><td valign=top>";
			$f = new ReadOnlyForm();
			$f->text("Tanggal Pemeriksaan","<b>".$d["tanggal_reg"]);
			$f->title1("<U>PEMERIKSAAN</U>","LEFT");
			$f->text($visit_fisioterapi["vis_1"],$d[3] );
			$f->text($visit_fisioterapi["vis_2"],$d[4]);
			$f->text($visit_fisioterapi["vis_3"],$d[5]);
			$f->text($visit_fisioterapi["vis_4"],$d[6] );
			$f->text($visit_fisioterapi["vis_5"],$d[7] );
			$f->text($visit_fisioterapi["vis_6"],$d[8] );
			$f->text($visit_fisioterapi["vis_7"],$d[9] );
			$f->text($visit_fisioterapi["vis_8"],$d[10]);
			echo "</td><td valign=top>";
			$f->title1("<U>Ortetik Prostetik</U>","LEFT");
			//$f->text("Dokter Spesialis RM/Fisioterapi",$d["nama"]);
			$f->text($visit_fisioterapi["vis_17"],$d[17]);
			$f->title1("<U>Okupasi Terapi</U>","LEFT");	
			$f->text("Fisioteraphis 2",$d["dok_2"]);
			$f->text($visit_fisioterapi["vis_23"],$d[23]);	
			$f->title1("<U>Terapi Wicara</U>","LEFT");
			$f->text("Fisioteraphis 1",$d["dok_1"]);
			$f->text($visit_fisioterapi["vis_29"],$d[29]);		
			$f->execute();
			echo "</td></tr>";
  			echo "<tr><td colspan='3'>";
  			echo "<br>";
  			include(rm_tindakan3);
  			echo "</td><td>";
  			echo "</td></tr></table>";
			}
			
			//pemeriksaan oleh dokter
			else{
			$sql = "select a.*,b.nama,g.nama as dok_1, h.nama as dok_2, i.nama as dok_3,j.nama as dok_4,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,f.layanan 
					from c_visit a 
					left join rs00017 b on a.id_dokter = B.ID 
					left join rs00017 g on a.id_perawat = g.id 
					left join rs00017 h on a.id_perawat1 = h.id
					left join rs00017 i on a.id_perawat2 = i.id
					left join rs00017 j on a.id_perawat3 = j.id
					left join rsv0002 c on a.no_reg=c.id 
					left join rs00006 d on d.id = a.no_reg
					left join rs00008 e on e.no_reg = a.no_reg
					left join rs00034 f on 'f.id' = e.item_id
					where a.no_reg='{$_GET['rg']}' and a.id_poli='205' ";
				$r = pg_query($con,$sql);
				$n = pg_num_rows($r);
			    if($n > 0) $d = pg_fetch_array($r);
			    pg_free_result($r);
				//echo $sql;exit;			
			    $_GET['id'] = $_GET['rg'] ;	
	 			
			echo"<div class=box>";
			echo "<table width='100%' border='0'><tr><td colspan='3'>";
			echo"<div class=form_subtitle>PEMERIKSAAN PASIEN</div>";
			echo "</td></tr>";
    		echo "<tr><td valign=top>";
			$f = new ReadOnlyForm();
			$f->text("Tanggal Pemeriksaan","<b>".$d["tanggal_reg"]);
			$f->title1("<U>PEMERIKSAAN</U>","LEFT");
			$f->title1("<U>Dokter Spesialis RM</U>","LEFT");
			$f->text("Dokter Pemeriksa",$d["nama"]);
			$f->text($visit_fisioterapi["vis_1"],$d[3] );
			$f->text($visit_fisioterapi["vis_2"],$d[4]);
			$f->text($visit_fisioterapi["vis_3"],$d[5]);
			$f->text($visit_fisioterapi["vis_4"],$d[6] );
			$f->text($visit_fisioterapi["vis_5"],$d[7] );
			$f->text($visit_fisioterapi["vis_6"],$d[8] );
			$f->title1("<U>Hasil Fisioterapi</U>","LEFT");
            $f->text("Perawat/Fisioterapis",$d["dok_4"]);
			$f->text($visit_fisioterapi["vis_11"],$d[13]);
			$f->text($visit_fisioterapi["vis_12"],$d[14]);
			$f->text($visit_fisioterapi["vis_13"],$d[15]);
			$f->text($visit_fisioterapi["vis_14"],$d[16]);
			$f->text($visit_fisioterapi["vis_15"],$d[17]);
			$f->text($visit_fisioterapi["vis_16"],$d[18]);
			echo "</td><td valign=top>";
			$f->title1("<U>Okupasi Terapi</U>","LEFT");
            $f->text("Petugas Okupasi Terapi",$d["dok_3"]);			
			$f->text($visit_fisioterapi["vis_18"],$d[20]);	
			$f->text($visit_fisioterapi["vis_19"],$d[21]);	
			$f->text($visit_fisioterapi["vis_20"],$d[22]);	
			$f->text($visit_fisioterapi["vis_21"],$d[23]);	
			$f->text($visit_fisioterapi["vis_22"],$d[24]);	
			$f->text($visit_fisioterapi["vis_23"],$d[25]);	
			$f->execute();
			echo "</td><td valign=top>";
			$f = new ReadOnlyForm();
			$f->title1("<U>Ortetik Prostetik</U>","LEFT");
            $f->text("Petugas Ortetik Prostetik",$d["dok_1"]);
			$f->text($visit_fisioterapi["vis_30"],$d[32]);	
			$f->text($visit_fisioterapi["vis_31"],$d[33]);	
			$f->text($visit_fisioterapi["vis_32"],$d[34]);	
			$f->text($visit_fisioterapi["vis_33"],$d[35]);	
			$f->text($visit_fisioterapi["vis_34"],$d[36]);	
			$f->text($visit_fisioterapi["vis_35"],$d[37]);		
			$f->title1("<U>Terapi Wicara</U>","LEFT");
			$f->text("Petugas Terapi Wicara",$d["dok_2"]);
			$f->text($visit_fisioterapi["vis_24"],$d[26]);	
			$f->text($visit_fisioterapi["vis_25"],$d[27]);	
			$f->text($visit_fisioterapi["vis_26"],$d[28]);	
			$f->text($visit_fisioterapi["vis_27"],$d[29]);	
			$f->text($visit_fisioterapi["vis_28"],$d[30]);	
			$f->text($visit_fisioterapi["vis_29"],$d[31]);			
			$f->execute();
			echo "</td></tr>";
  			echo "<tr><td colspan='3'>";

  			echo "<br>";
  			include(rm_tindakan3);
  			echo "</td><td>";
  			echo "</td></tr></table>";
			}

			
			}
			else {
				echo"<div align=center class=form_subtitle1>RIWAYAT PENYAKIT PASIEN</div>";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
				$sql = "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,C.TDESC,D.NAMA,A.ID_POLI::text,a.oid ". 
					   "FROM C_VISIT A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   "LEFT JOIN RS00001 C ON A.ID_POLI = C.TC_POLI AND C.TT='LYN'".
					   "LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID ".
					   "LEFT JOIN RS00001 E ON A.ID_KONSUL = E.TC AND E.TT='LYN'".
					   "WHERE A.user_id!='' and B.MR_NO = '".$_GET["mr"]."' AND A.ID_POLI != 100 and A.ID_POLI::text='205'
						GROUP BY A.NO_REG,A.TANGGAL_REG,C.TDESC,D.NAMA,A.ID_POLI,a.oid
						
                                            ";
					
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	$t->ColHidden[6]= true;
			   	$t->ColHidden[1]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("TANGGAL PEMERIKSAAN","","WAKTU KUNJUNGAN","KLINIK","DOKTER PEMERIKSA","DETAIL");
			   	$t->ColAlign = array("center","center","center","left","left","left","center","center");
				$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat&act=detail&polinya=<#5#>&mr=".$_GET["mr"]."&rg=<#0#>&oid=<#6#>'>".icon("view","View")."</A>";	
				$t->execute();
				
				echo"<br>";
         		echo"</div>";
				echo "</td></tr></table></div>";
    	
			}
    }elseif($_GET["list"] == "riwayat_klinik") {
    	if(!$GLOBALS['print']){
    	$T->show(9);
    	}else{}
    	
		include("riwayat_klinik.php");
                        
   } elseif ($_GET["list"] == "unit_rujukan"){
    	$T->show(10);
    	echo"<br>";
		$StatusBayar = getFromTable("select statusbayar from rsv0012 where id = '".$_GET["rg"]."'");
		$StatusTagih = getFromTable("select tagih from rsv0012 where id = '".$_GET["rg"]."'");
		$StatusInap = getFromTable("select rawat_inap from rs00006 where id = '".$_GET["rg"]."'");
		if($StatusInap == 'I'){
			$StatusCHK = getFromTable("select count(trans_types) from rs00010 where no_reg = '".$_GET["rg"]."' and trans_types='CHK'");
		}else{
			$StatusCHK = 1;
		}
		$cekKonsul = getFromTable("select max(tanggal_konsul) from c_visit where no_reg = '".$_GET["rg"]."' and id_konsul='".$_GET["mPOLI"]."'");
		//var_dump($StatusInap);
		//end check status bayar pasien
    	if(($StatusBayar=='LUNAS')&&($StatusTagih > 0) && $StatusCHK!=0 && $cekKonsul!=date('Y-m-d')){
			echo "<div align=center><br>";
			echo "<font color=red size=3><blink>PERINGATAN </blink>!</font><br>";
			echo "<font color=red size=3>TAGIHAN PASIEN <font color=navy><blink>".$StatusBayar."</blink></font>, INPUT LAYANAN CLOSED<br>";
			echo "</div>";
		}else{
    
	include ("unit_rujukan.php");
	}
				        	
    }elseif ($_GET["list"] == "konsultasi"){
    	$T->show(11);
    	echo"<br>";
    	
    	include("p_fisioterapi.konsultasi.php");
	
	//pemeriksaan oleh dokter najla 09012011
	}elseif ($_GET["list"] == "pemeriksaan_op"){
    	$T->show(2);
		$sql2 =	"SELECT A.*,B.NAMA as perawat FROM C_VISIT A 
    				LEFT JOIN RS00017 B ON A.id_perawat = B.ID
    				WHERE A.ID_POLI=205 AND A.NO_REG='$rg'"; 
	    	$r=pg_query($con,$sql2);
	    	$n = pg_num_rows($r);		    	
			    if($n > 0) $d2 = pg_fetch_array($r);
			    pg_free_result($r);
				//-------------------------tambah for update------hery 08072007
				echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&list=pemeriksaan_op&act=edit';\">\n";   
				//echo "<input type='image' src='images/icon-edit.png' action='edit' >";
				    
				if ($_GET['act'] == "edit"){
						echo "<font color='#000000' size='2'> >>Edit Pemeriksaan Pasien</font>";
						$f = new Form("actions/p_fisioterapi.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d2["no_reg"]);
					    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
						$f->hidden("list","pemeriksaan_op");
					    $f->hidden("mr",$_GET["mr"]);
					    $f->hidden("f_id_poli","205");
					    $f->hidden("f_user_id",$_SESSION[uid]);
					   
				}else {
					if($n > 0){
						$ext= "disabled";
					}else {
						$ext = "";
					}
				//---------------------------------------------------------------------------------			
					echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";	
					$f = new Form("actions/p_fisioterapi.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("list","pemeriksaan_dr");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli","205");
				    $f->hidden("f_user_id",$_SESSION[uid]);
			}
				    
				//$f->calendar("tanggal_reg","Tanggal Registrasi",15,15,$d2[1],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
					
				    //echo"<div align=left class=FORM_SUBTITLE1>ANAMNESA PASIEN</div>";
				    
				    if (isset($_SESSION["SELECT_EMP"])) {
    					$_SESSION["perawat"]["id"] = $_SESSION["SELECT_EMP"];
    					$_SESSION["perawat"]["nama"] =
        				getFromTable(
                                        "select nama from rs00017 where id = '".$_SESSION["perawat"]["id"]."'");
			            
                                        $f->textAndButton3("f_id_perawat","Petugas OP",2,10,$_SESSION["perawat"]["id"],$ext,"nm2",30,70,$_SESSION["perawat"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");	
			           
					}elseif ($d2["id_perawat"] != '') {
							$f->textAndButton3("f_id_perawat","Petugas OP",2,10,$d2["id_perawat"],$ext,"nm2",30,70,$d2["perawat"],$ext,"...",$ext,"OnClick='selectPegawai();';");
					}else{
						$f->textAndButton3("f_id_perawat","Petugas OP",2,10,0,$ext,"nm2",30,70,$d2["perawat"],$ext,"...",$ext,"OnClick='selectPegawai();';");
					}
                                        
                                        
				    
					$max = 36 ; 
					$i = 1;
					while ($i<= $max) {	
						if ($i==30 or $i>=30 AND $i<=35 )
						$f->textarea("f_vis_".$i,$visit_fisioterapi["vis_".$i] ,1, $visit_fisioterapi["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
				
					
				    		
				    
	 		$i++ ; 	
			}
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			echo"</div>";
			
    	
    
    //pemeriksaan
    
    echo "</div>";
    
	    echo "\n<script language='JavaScript'>\n";
	    echo "function selectLayanan() {\n";
	   	echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai(tag) {\n";
        echo "    sWin = window.open('popup/pegawai.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        
        
    if (empty($_GET[sub])) {
	    echo "function refreshSubmit() {\n";
	    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
	    echo "}\n";
	    echo "refreshSubmit();\n";
	    }
	    echo "</script>\n";
		}
	

//=======================================================================================
//pemeriksaan oleh dokter joko 22122011
	elseif ($_GET["list"] == "pemeriksaan_dr1"){
    	$T->show(1);
	$sql2 =	"SELECT A.*,B.NAMA AS dokter,c.NAMA AS perawat5 FROM C_VISIT A 
    				LEFT JOIN RS00017 B ON A.id_dokter = B.ID
                                LEFT JOIN RS00017 c ON A.id_perawat3 = B.ID
    				WHERE A.ID_POLI=205 AND A.NO_REG='$rg'"; 
	    	$r=pg_query($con,$sql2);
	    	$n = pg_num_rows($r);		    	
			    if($n > 0) $d2 = pg_fetch_array($r);
			    pg_free_result($r);
				//-------------------------tambah for update------hery 08072007
				echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&list=pemeriksaan_dr1&act=edit';\">\n";   
				//echo "<input type='image' src='images/icon-edit.png' action='edit' >";
				    
				if ($_GET['act'] == "edit"){
						echo "<font color='#000000' size='2'> >>Edit Pemeriksaan Pasien</font>";
						$f = new Form("actions/p_fisioterapi.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d2["no_reg"]);
					    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
						$f->hidden("list","pemeriksaan_dr1");
					    $f->hidden("mr",$_GET["mr"]);
					    $f->hidden("f_id_poli","205");
					    $f->hidden("f_user_id",$_SESSION[uid]);
					   
				}else {
					if($n > 0){
						$ext= "disabled";
					}else {
						$ext = "";
					}
				//---------------------------------------------------------------------------------			
					echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";	
					$f = new Form("actions/p_fisioterapi.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("list","pemeriksaan_dr1");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli","205");
				    $f->hidden("f_user_id",$_SESSION[uid]);
			}
				    
				    if (isset($_SESSION["SELECT_EMP5"])) {
    					$_SESSION["perawat5"]["id"] = $_SESSION["SELECT_EMP5"];
    					$_SESSION["perawat5"]["nama"] =
        				getFromTable(
                                        "select nama from rs00017 where id = '".$_SESSION["perawat5"]["id"]."'");
			            
                                        $f->textAndButton3("f_id_perawat3","Perawat/Fisioterapis",2,10,$_SESSION["perawat5"]["id"],$ext,"nm5",30,70,$_SESSION["perawat5"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai5();';");	
			           
					}elseif ($d2["id_perawat3"] != '') {
							$f->textAndButton3("f_id_perawat3","Perawat/Fisioterapis",2,10,$d2["id_perawat3"],$ext,"nm5",30,70,$d2["perawat5"],$ext,"...",$ext,"OnClick='selectPegawai5();';");
					}else{
						$f->textAndButton3("f_id_perawat3","Perawat/Fisioterapis",2,10,0,$ext,"nm5",30,70,$d2["perawat5"],$ext,"...",$ext,"OnClick='selectPegawai5();';");
					}
                                        
					$max = 17 ; 
					$i = 1;
					while ($i<= $max) {	
						if ($i==11 or $i>=11 AND $i<=16 )
						$f->textarea("f_vis_".$i,$visit_fisioterapi["vis_".$i] ,1, $visit_fisioterapi["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
				    
	 		$i++ ; 	
			}
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			echo"</div>";
    //pemeriksaan
    
    echo "</div>";
    
	    echo "\n<script language='JavaScript'>\n";
	    echo "function selectLayanan() {\n";
	   	echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai2(tag) {\n";
        echo "    sWin = window.open('popup/pegawai2.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "function selectPegawai5(tag) {\n";
        echo "    sWin = window.open('popup/pegawai5.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
    if (empty($_GET[sub])) {
	    echo "function refreshSubmit() {\n";
	    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
	    echo "}\n";
	    echo "refreshSubmit();\n";
	    }
	    echo "</script>\n";
		}

//pemeriksaan oleh dokter najla 09012011
	elseif ($_GET["list"] == "pemeriksaan_dr"){
    	$T->show(0);
	$sql2 =	"SELECT A.*,B.NAMA AS dokter,c.NAMA AS perawat5 FROM C_VISIT A 
    				LEFT JOIN RS00017 B ON A.id_dokter = B.ID
                                LEFT JOIN RS00017 c ON A.id_perawat3 = B.ID
    				WHERE A.ID_POLI=205 AND A.NO_REG='$rg'"; 
	    	$r=pg_query($con,$sql2);
	    	$n = pg_num_rows($r);		    	
			    if($n > 0) $d2 = pg_fetch_array($r);
			    pg_free_result($r);
				//-------------------------tambah for update------hery 08072007
				echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&list=pemeriksaan_dr&act=edit';\">\n";   
				//echo "<input type='image' src='images/icon-edit.png' action='edit' >";
				    
				if ($_GET['act'] == "edit"){
						echo "<font color='#000000' size='2'> >>Edit Pemeriksaan Pasien</font>";
						$f = new Form("actions/p_fisioterapi.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d2["no_reg"]);
					    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
						$f->hidden("list","pemeriksaan_dr");
					    $f->hidden("mr",$_GET["mr"]);
					    $f->hidden("f_id_poli","205");
					    $f->hidden("f_user_id",$_SESSION[uid]);
					   
				}else {
					if($n > 0){
						$ext= "disabled";
					}else {
						$ext = "";
					}
				//---------------------------------------------------------------------------------			
					echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";	
					$f = new Form("actions/p_fisioterapi.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("list","pemeriksaan_dr");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli","205");
				    $f->hidden("f_user_id",$_SESSION[uid]);
			}
				    
				    
				     if (isset($_SESSION["SELECT_EMP2"])) {
    					$_SESSION["dokter"]["id"] = $_SESSION["SELECT_EMP2"];
    					$_SESSION["dokter"]["nama"] =
        				getFromTable("select nama from rs00017 where id = '".$_SESSION["dokter"]["id"]."'");
			            
                        $f->textAndButton3("f_id_dokter","Dokter Pemeriksa",2,10,$_SESSION["dokter"]["id"],$ext,"nm3",30,70,$_SESSION["dokter"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai2();';");	
			           
						}elseif ($d2["id_dokter"] != '') {
								$f->textAndButton3("f_id_dokter","Dokter Pemeriksa",2,10,$d2["id_dokter"],$ext,"nm3",30,70,$d2["dokter"],$ext,"...",$ext,"OnClick='selectPegawai2();';");
						}else{
							$f->textAndButton3("f_id_dokter","Dokter Pemeriksa",2,10,0,$ext,"nm3",30,70,$d2["dokter"],$ext,"...",$ext,"OnClick='selectPegawai2();';");
						}
                                        
					$max = 11 ; 
					$i = 1;
					while ($i<= $max) {	
						if ($i<6 or $i<7 )
						$f->textarea("f_vis_".$i,$visit_fisioterapi["vis_".$i] ,1, $visit_fisioterapi["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
				    
	 		$i++ ; 	
			}
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			echo"</div>";
			
    //pemeriksaan
    
    echo "</div>";
    
	    echo "\n<script language='JavaScript'>\n";
	    echo "function selectLayanan() {\n";
	   	echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai2(tag) {\n";
        echo "    sWin = window.open('popup/pegawai2.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "function selectPegawai5(tag) {\n";
        echo "    sWin = window.open('popup/pegawai5.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
    if (empty($_GET[sub])) {
	    echo "function refreshSubmit() {\n";
	    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
	    echo "}\n";
	    echo "refreshSubmit();\n";
	    }
	    echo "</script>\n";
		}
	
//=======================================================================================
	//pemeriksaan oleh dokter najla 09012011
	elseif ($_GET["list"] == "pemeriksaan_tw"){
    	$T->show(4);
	$sql2 =	"SELECT A.*,B.NAMA as perawat1 FROM C_VISIT A 
    				LEFT JOIN RS00017 B ON A.ID_PERAWAT1 = B.ID
    				WHERE A.ID_POLI=205 AND A.NO_REG='$rg'"; 
	    	$r=pg_query($con,$sql2);
	    	$n = pg_num_rows($r);		    	
			    if($n > 0) $d2 = pg_fetch_array($r);
			    pg_free_result($r);
				//-------------------------tambah for update------hery 08072007
				echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&list=pemeriksaan_tw&act=edit';\">\n";   
				    
				if ($_GET['act'] == "edit"){
						echo "<font color='#000000' size='2'> >>Edit Pemeriksaan Pasien</font>";
						$f = new Form("actions/p_fisioterapi.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d2["no_reg"]);
					    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
						$f->hidden("list","pemeriksaan_tw");
					    $f->hidden("mr",$_GET["mr"]);
					    $f->hidden("f_id_poli","205");
					    $f->hidden("f_user_id",$_SESSION[uid]);
					   
				}else {
					if($n > 0){
						$ext= "disabled";
					}else {
						$ext = "";
					}
				//---------------------------------------------------------------------------------			
					echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";	
					$f = new Form("actions/p_fisioterapi.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("list","pemeriksaan_tw");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli","205");
				    $f->hidden("f_user_id",$_SESSION[uid]);
			}
				    
				    if (isset($_SESSION["SELECT_EMP3"])) {
    					$_SESSION["PERAWAT1"]["id"] = $_SESSION["SELECT_EMP3"];
    					$_SESSION["PERAWAT1"]["nama"] =
        				getFromTable("select nama from rs00017 where id = '".$_SESSION["PERAWAT1"]["id"]."'");
			            
                        $f->textAndButton3("f_id_perawat1","Petugas TW",2,10,$_SESSION["PERAWAT1"]["id"],$ext,"nm4",30,70,$_SESSION["PERAWAT1"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai3();';");	
			           
						}elseif ($d2["id_perawat1"] != '') {
								$f->textAndButton3("f_id_perawat1","Petugas TW",2,10,$d2["id_perawat1"],$ext,"nm4",30,70,$d2["perawat1"],$ext,"...",$ext,"OnClick='selectPegawai3();';");
						}else{
							$f->textAndButton3("f_id_perawat1","Petugas TW",2,10,0,$ext,"nm4",30,70,$d2["perawat1"],$ext,"...",$ext,"OnClick='selectPegawai3();';");
						}
				    
					$max = 30 ; 
					$i = 1;
					while ($i<= $max) {	
						if ($i==24 or $i>=24 AND $i<=29 )
						$f->textarea("f_vis_".$i,$visit_fisioterapi["vis_".$i] ,1, $visit_fisioterapi["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
				
	 		$i++ ; 	
			}
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			echo"</div>";
    //pemeriksaan
    
    echo "</div>";
    
	    echo "\n<script language='JavaScript'>\n";
	    echo "function selectLayanan() {\n";
	   	echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai3(tag) {\n";
        echo "    sWin = window.open('popup/pegawai3.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        
    if (empty($_GET[sub])) {
	    echo "function refreshSubmit() {\n";
	    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
	    echo "}\n";
	    echo "refreshSubmit();\n";
	    }
	    echo "</script>\n";
		}
//=================================================================================		
		//pemeriksaaan oleh fisioterapis
	else {       //pemeriksaan
    	if(!$GLOBALS['print']){
    	$T->show(3);
    	}
		
		
		else{}
    	
    		$sql2 =	"SELECT A.*,B.NAMA perawat2 FROM C_VISIT A 
    				LEFT JOIN RS00017 B ON A.ID_PERAWAT2 = B.ID
    				WHERE A.ID_POLI=205 AND A.NO_REG='$rg'"; 
	    	$r=pg_query($con,$sql2);
	    	$n = pg_num_rows($r);		    	
			    if($n > 0) $d2 = pg_fetch_array($r);
			    pg_free_result($r);
				//-------------------------tambah for update------hery 08072007
				echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&list=pemeriksaan_ot&act=edit';\">\n";   
				//echo "<input type='image' src='images/icon-edit.png' action='edit' >";
				    
				if ($_GET['act'] == "edit"){
						echo "<font color='#000000' size='2'> >>Edit Pemeriksaan Pasien</font>";
						$f = new Form("actions/p_fisioterapi.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d2["no_reg"]);
					    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
						$f->hidden("list","pemeriksaan_ot");
					    $f->hidden("mr",$_GET["mr"]);
					    $f->hidden("f_id_poli","205");
					    $f->hidden("f_user_id",$_SESSION[uid]);
					   
				}else {
					if($n > 0){
						$ext= "disabled";
					}else {
						$ext = "";
					}
				//---------------------------------------------------------------------------------			
					echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";	
					$f = new Form("actions/p_fisioterapi.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("list","pemeriksaan_tw");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli","205");
				    $f->hidden("f_user_id",$_SESSION[uid]);
			}
				    
				    if (isset($_SESSION["SELECT_EMP4"])) {
    					$_SESSION["PERAWAT2"]["id"] = $_SESSION["SELECT_EMP4"];
    					$_SESSION["PERAWAT2"]["nama"] =
        				getFromTable("select nama from rs00017 where id = '".$_SESSION["PERAWAT2"]["id"]."'");
			            
                        $f->textAndButton3("f_id_perawat2","Petugas OT",2,10,$_SESSION["PERAWAT2"]["id"],$ext,"nm5",30,70,$_SESSION["PERAWAT2"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai4();';");	
			           
						}elseif ($d2["id_perawat2"] != '') {
								$f->textAndButton3("f_id_perawat2","Petugas OT",2,10,$d2["id_perawat2"],$ext,"nm5",30,70,$d2["perawat2"],$ext,"...",$ext,"OnClick='selectPegawai4();';");
						}else{
							$f->textAndButton3("f_id_perawat2","Petugas OT",2,10,0,$ext,"nm5",30,70,$d2["perawat2"],$ext,"...",$ext,"OnClick='selectPegawai4();';");
						}
				    
					$max = 24 ; 
					$i = 1;
					while ($i<= $max) {	
						if ($i==18 or $i>=18 AND $i<=23 )
						$f->textarea("f_vis_".$i,$visit_fisioterapi["vis_".$i] ,1, $visit_fisioterapi["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
				
	 		$i++ ; 	
			}
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			echo"</div>";
    //pemeriksaan
    
    echo "</div>";
    
	    echo "\n<script language='JavaScript'>\n";
	    echo "function selectLayanan() {\n";
	   	echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai4(tag) {\n";
        echo "    sWin = window.open('popup/pegawai4.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        
    if (empty($_GET[sub])) {
	    echo "function refreshSubmit() {\n";
	    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
	    echo "}\n";
	    echo "refreshSubmit();\n";
	    }
	    echo "</script>\n";
		}
		echo "\n<script language='JavaScript'>\n";
	    echo "function selectLayanan() {\n";
	   	echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai3(tag) {\n";
        echo "    sWin = window.open('popup/pegawai3.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        /* Untuk Layanan Paket             */
		/* Agung Sunandar 16:53 26/06/2012 */
	    echo "function selectLayanan2() {\n";
	   	echo "    sWin = window.open('popup/layanan_paket.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai2(tag) {\n";
        echo "    sWin = window.open('popup/pegawai2.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
    if (empty($_GET[sub])) {
	    echo "function refreshSubmit() {\n";
	    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
	    echo "}\n";
	    echo "refreshSubmit();\n";
	    }
	    echo "</script>\n";
   		
} else {
	//update tab pasien App.25-11-07
	echo"<br>";
	$tab_disabled = array("tab1"=>true, "tab2"=>true);
	$T1 = new TabBar();
	$T1->addTab("$SC?p=$PID&list2=tab1&list=layanan", "Daftar Pasien Rujukan"	, $tab_disabled["tab1"]);
	$T1->addTab("$SC?p=$PID&list2=tab2&list=layanan", "Daftar Pasien Klinik"	, $tab_disabled["tab2"]);

    if($_GET['list2']=="tab1"){
    	$T1->show(0);
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->hidden("poli",$_GET["mPOLI"]);
    $f->hidden("list2",tab1);
   
   		echo "<div align='right' valign='middle'>";	
		$f = new Form($SC, "GET","NAME=Form4");
	    $f->hidden("p", $PID);
	    $f->hidden("list2","tab1");
	    if (!$GLOBALS['print']){
	    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form4.submit();'");
		}else { 
		   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
		}
	    $f->execute();
    	if ($msg) errmsg("Error:", $msg);
    	echo "</div>";
		//---------------------
		echo "<br>";
		
	$SQLSTR = 	"select distinct a.mr_no,a.id,upper(a.nama)as nama,tanggal(b.tanggal_konsul,0)||' '||to_char(b.waktu_konsul,'hh:mi:ss') as tgl,a.alm_tetap,a.kesatuan,a.tdesc,CASE WHEN a.rawat_inap='I' THEN 'RAWAT INAP'
                             WHEN a.rawat_inap='N' THEN 'INSTALASI GAWAT DARURAT'
			     ELSE c.tdesc end as rawatan,
	a.statusbayar
				from rsv_pasien4 a 
				left join c_visit b on b.no_reg = a.id
				left join c_visit_operasi d on d.no_reg = a.id
				left join rs00001 c on c.tc_poli = b.id_poli and c.tt='LYN' or c.tc_poli = d.id_poli and c.tt='LYN'
				WHERE (b.id_konsul='".$_GET["mPOLI"]."' or d.id_konsul='".$_GET["mPOLI"]."')";
		// 24-12-2006 --> tambahan 'where is_bayar = 'N'
		//status_akhir,rawatan di query sementara di tutup
        
		$tglhariini = date("Y-m-d", time());
    if (strlen($_GET["mPOLI"]) > 0 ) {
		$SQLWHERE =
			"AND b.TANGGAL_KONSUL = '$tglhariini' AND".
			"	(UPPER(a.NAMA) LIKE '%".strtoupper($_GET["search"])."%') ";
	}
	if ($_GET["search"]) {
		$SQLWHERE =
			"and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
					" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
					" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ) ";
	}
	if (!isset($_GET[sort])) {

           $_GET[sort] = "a.id";
           $_GET[order] = "asc";
	}
	$rstr=pg_query($con, "$SQLSTR $SQLWHERE ");
	$dstr = pg_fetch_array($rstr); 
	   	$t = new PgTable($con, "100%");
	    $t->SQL = "$SQLSTR $SQLWHERE ";
	    $t->setlocale("id_ID");
	    $t->ShowRowNumber = true;
	    $t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
	    $t->RowsPerPage = $ROWS_PER_PAGE;
	    $t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&list=layanan&sub2=nonpaket'><#2#>";
	   	$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","WAKTU KONSUL","ALAMAT","PEKERJAAN","TIPE PASIEN","UNIT ASAL","STATUS");
	    $t->ColColor[8] = "color";
	    $t->execute();
	    echo"<br><div class=NOTE>Catatan : Daftar pasien diurut berdasarkan no antrian</div><br>";	
    }else{
    	$T1->show(1);	
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->hidden("poli",$_GET["mPOLI"]);
   
   		echo "<div align='right' valign='middle'>";	
		$f = new Form($SC, "GET","NAME=Form2");
	    $f->hidden("p", $PID);
	    $f->hidden("list2","tab2");
	    if (!$GLOBALS['print']){
	    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
		}else { 
		   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
		}
	    $f->execute();
    	if ($msg) errmsg("Error:", $msg);
    	echo "</div>";
		//---------------------
		echo "<br>";
		
	$SQLSTR = 	"select distinct a.mr_no,a.id,upper(a.nama)as nama,tanggal(a.tanggal_reg,0)||' '||to_char(waktu_reg,'hh:mi:ss') as tgl,a.alm_tetap,a.kesatuan,a.tdesc,
	a.statusbayar
				from rsv_pasien4 a 
				left join c_visit b on b.no_reg = a.id
				WHERE a.poli='".$_GET["mPOLI"]."'";
		// 24-12-2006 --> tambahan 'where is_bayar = 'N'
		//status_akhir,rawatan di query sementara di tutup
        
		$tglhariini = date("Y-m-d", time());
    if (strlen($_GET["mPOLI"]) > 0 ) {
		$SQLWHERE =
			"AND a.TANGGAL_REG = '$tglhariini' AND".
			"	(UPPER(a.NAMA) LIKE '%".strtoupper($_GET["search"])."%') ";
	}
	if ($_GET["search"]) {
		$SQLWHERE =
			"and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
					" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
					" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ) ";
	}
	if (!isset($_GET[sort])) {

           $_GET[sort] = "a.id";
           $_GET[order] = "asc";
	}
	$rstr=pg_query($con, "$SQLSTR $SQLWHERE ");
	$dstr = pg_fetch_array($rstr); 
	   	$t = new PgTable($con, "100%");
	    $t->SQL = "$SQLSTR $SQLWHERE ";
	    $t->setlocale("id_ID");
	    $t->ShowRowNumber = true;
	    $t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
	    $t->RowsPerPage = $ROWS_PER_PAGE;
	    $t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&list=layanan&sub2=nonpaket'><#2#>";
	   	$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","WAKTU REGISTRASI","ALAMAT","PEKERJAAN","TIPE PASIEN","STATUS");
	    $t->ColColor[7] = "color";
	    $t->execute();
	    echo"<br><div class=NOTE>Catatan : Daftar pasien di urut berdasarkan no antrian</div><br>";
    }
}
?>
