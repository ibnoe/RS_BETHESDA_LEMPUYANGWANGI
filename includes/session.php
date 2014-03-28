<?php
		if (isset($_GET["del"])) {
		    $temp = $_SESSION["layanan"];
		    unset($_SESSION["layanan"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del"]) $_SESSION["layanan"][count($_SESSION["layanan"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&poli=$POLI&sub=layanan&sub2=nonpaket");
		    	exit;
		
		/* Untuk menambahkan hapus Paket layanan */
		/* Agung Sunandar 16:28 26/06/2012       */
		
		}elseif (isset($_GET["del1"])) {
		    $temp = $_SESSION["layanan2"];
		    unset($_SESSION["layanan2"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del1"]) $_SESSION["layanan2"][count($_SESSION["layanan2"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&poli=$POLI&sub=layanan&sub2=paket");
		    	exit;
		    
		} elseif (isset($_GET["del-icd"])) {
		    $temp = $_SESSION["icd"];
		    unset($_SESSION["icd"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-icd"]) $_SESSION["icd"][count($_SESSION["icd"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&poli=$POLI&sub=icd");
		    	exit;
		} elseif (isset($_GET["del-icd9"])) {
		    $temp = $_SESSION["icd9"];
		    unset($_SESSION["icd9"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-icd9"]) $_SESSION["icd9"][count($_SESSION["icd9"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd9&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&poli=$POLI&sub=icd9");
		    	exit;
		    
		} elseif (isset($_GET["del-obat"])) {
		    $temp = $_SESSION["obat"];
		    unset($_SESSION["obat"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-obat"]) $_SESSION["obat"][count($_SESSION["obat"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=resepobat&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&poli=$POLI&sub=obat");
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
		    $r = @pg_query($con,"SELECT * FROM RSV0004 WHERE ID = '".$_GET["obat"]."'");
		    $d = @pg_fetch_object($r);
		    @pg_free_result($r);
		
		    if (is_array($_SESSION["obat"])) {
		        $cnt = count($_SESSION["obat"]);
		    } else {
		        $cnt = 0;
		    }
		    
		    if (!empty($d->obat)) {
		        $_SESSION["obat"][$cnt]["id"]     = $_GET["obat"];
		        $_SESSION["obat"][$cnt]["desc"]   = $d->obat;
		        $_SESSION["obat"][$cnt]["dosis"]  = $_GET["dosis_obat"];
		        $_SESSION["obat"][$cnt]["jumlah"] = $_GET["jumlah_obat"];
		        $_SESSION["obat"][$cnt]["harga"]  = $d->harga;
		        $_SESSION["obat"][$cnt]["total"]  = $d->harga * $_GET["jumlah_obat"];
		        $_SESSION["obat"][$cnt]["is_racikan"] = $_GET["is_racikan"];
		        unset($_SESSION["SELECT_OBAT"]);
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=resepobat&rg=" . $_GET["rg"]."&poli=$POLI&mr=" . $_GET["mr"] . "&sub=obat");
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
		} elseif (isset($_GET["icd"])) {
		    $r = pg_query($con,"SELECT * FROM RSV0005 WHERE DIAGNOSIS_CODE = '" . $_GET["icd"] . "'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);
		    if (is_array($_SESSION["icd"])) {
		        $cnt = count($_SESSION["icd"]);
		    } else {
		        $cnt = 0;
		    }
		    
		    if (strlen($d->description) > 0) {
		        $_SESSION["icd"][$cnt]["id"]   = $_GET["icd"];
		        $_SESSION["icd"][$cnt]["desc"] = $d->description;
		        $_SESSION["icd"][$cnt]["kate"] = $d->category;
		        unset($_SESSION["SELECT_ICD"]);
		    }
		    header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    exit;
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
			
			if($_GET["ciko"] == 'YA'){
                $_SESSION["layanan"][$cnt]["diskon"]  = ($_GET["persen"]/100) * ((((25*$harga)/100)+$harga) * $_GET["jumlah"]);
            }else{
                $_SESSION["layanan"][$cnt]["diskon"]  = ($_GET["persen"]/100) * ($harga * $_GET["jumlah"]);
            }
			
            $_SESSION["layanan"][$cnt]["ciko"]  = $_GET["ciko"];
            
            if($_GET["ciko"] == 'YA'){
                $_SESSION["layanan"][$cnt]["total"]  = ((((25*$harga)/100)+$harga) * $_GET["jumlah"]) - ($_GET["persen"]/100) * ((((25*$harga)/100)+$harga) * $_GET["jumlah"]);
            }else{
                $_SESSION["layanan"][$cnt]["total"]  = ($harga * $_GET["jumlah"]) - ($_GET["persen"]/100) * ($harga * $_GET["jumlah"]);
            }
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

            header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&sub=2&sub2=" . $_GET["sub2"]."");
            exit;

    }
?>