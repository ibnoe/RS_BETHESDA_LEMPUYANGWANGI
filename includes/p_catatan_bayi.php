<?php 	// Nugraha, Sun Apr 18 18:58:42 WIT 2004
      	// sfdn, 22-04-2004: hanya merubah beberapa title
      	// sfdn, 23-04-2004: tambah harga obat
      	// sfdn, 30-04-2004
      	// sfdn, 09-05-2004
      	// sfdn, 18-05-2004: age
      	// sfdn, 02-06-2004
      	// Nugraha, Sun Jun  6 18:14:41 WIT 2004 : Paket Transaksi
      	// sfdn, 24-12-2006 --> layanan hanya diberikan kpd. pasien yang blm. lunas
		// rs00006.is_bayar = 'N'
		// sfdn, 27-12-2006
		// App,02-06-2007 --> Developer

session_start();
$PID = "p_catatan_bayi";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/visit_setting.php");
//--fungsi column color-------------- 
function color( $dstr, $r ) {
	    //if ($dstr[7] == '-') {
	    	if ($dstr[10] == 'Sudah Diperiksa' ){
	    		return "<font color=#FF33FF>{$dstr[$r]}</font>";
	    	}elseif ($dstr[10] == 'Menunggu'){
	    		return "<font color=#66FFCC>{$dstr[$r]}</font>";
	    	}elseif ($dstr[10] == 'Bayar Angsur'){
	    		return "<font color=#FF9900>{$dstr[$r]}</font>";
	    	}elseif ($dstr[10] == 'Bayar Lunas'){
	    		return "<font color=#FF3300>{$dstr[$r]}</font>";
	    	}
	    	return "<font color=#0000FF>{$dstr[$r]}</font>";
	    //}else return $dstr[$i];
}
//-------------------------------       	
$_GET["mPOLI"]=$setting_ri["catatan_bayi"];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];


		if (isset($_GET["del"])) {
		    $temp = $_SESSION["layanan"];
		    unset($_SESSION["layanan"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del"]) $_SESSION["layanan"][count($_SESSION["layanan"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."&sub=layanan");
		    	exit;
		    
		} elseif (isset($_GET["del-icd"])) {
		    $temp = $_SESSION["icd"];
		    unset($_SESSION["icd"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-icd"]) $_SESSION["icd"][count($_SESSION["icd"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    	exit;
		    
		} elseif (isset($_GET["del-obat"])) {
		    $temp = $_SESSION["obat"];
		    unset($_SESSION["obat"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-obat"]) $_SESSION["obat"][count($_SESSION["obat"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=obat");
		    	exit;
		    
		} elseif (isset($_GET["del-pjm"])) {
		    $temp = $_SESSION["pjm"][$_GET["del-pjm"]];
		    unset($_SESSION["pjm"][$_GET["del-pjm"]]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-emp"])
		            $_SESSION["pjm"][$_GET["del-pjm"]][count($_SESSION["pjm"][$_GET["del-pjm"]])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=pjm");
		    	exit;
		    
		} elseif (isset($_GET["s2note"])) {
		    $_SESSION["s2note"] = $_GET["s2note"];
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=icd");
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
		        $_SESSION["obat"][$cnt]["total"]  = $d->harga * $_GET["jumlah_obat"];
		        //$_SESSION["obat"][$cnt]["satuan"] = $d->satuan;
		        unset($_SESSION["SELECT_OBAT"]);
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=obat");
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
		    
		    if (strlen($d->description) > 0) {
		        $_SESSION["icd"][$cnt]["id"]   = $_GET["icd"];
		        $_SESSION["icd"][$cnt]["desc"] = $d->description;
		        $_SESSION["icd"][$cnt]["kate"] = $d->category;
		        unset($_SESSION["SELECT_ICD"]);
		    }
		    header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    exit;
		    
		} elseif (isset($_GET["layanan"])) {
			
		    $r = pg_query($con,"SELECT * FROM RSV0034 WHERE ID = '" . $_GET["layanan"] . "'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);

    $gol_tindakan = getFromTable("select golongan_tindakan_id from rs00034 where id='".$_GET["layanan"]."'");
    $is_range = $d->harga_atas > 0 || $d->harga_bawah > 0;

    if ($d->id) {
        if (($is_range && isset($_GET["harga"])) || (!$is_range)) {
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
            $_SESSION["layanan"][$cnt]["total"]  = $harga * $_GET["jumlah"];
            $_SESSION["layanan"][$cnt]["dokter"]  = $dokter;
            $_SESSION["layanan"][$cnt]["nip"]  = $_SESSION[SELECT_EMP];
            
            
            // tindakan non operatif
            if (substr($d->hierarchy,0,9) == "006001008") {

	               $t = pg_query($con,"SELECT * FROM RS00034 WHERE HIERARCHY LIKE '006001007%' AND GOLONGAN_TINDAKAN_ID = '$gol_tindakan'");
	               $tr = pg_fetch_object($t);
	               
	            do {
	            $cnt++;
	            $harga = $tr->harga;
	            $_SESSION["layanan"][$cnt]["id"]     = str_pad($tr->id,5,"0",STR_PAD_LEFT);
	            if ($tr->klasifikasi_tarif) $embel= " - ".$tr->klasifikasi_tarif;
	            $_SESSION["layanan"][$cnt]["nama"]   = $tr->layanan . $embel;
	            $_SESSION["layanan"][$cnt]["jumlah"] = $_GET["jumlah"];
	            $_SESSION["layanan"][$cnt]["satuan"] = $tr->satuan;
	            $_SESSION["layanan"][$cnt]["harga"]  = $harga;
	            $_SESSION["layanan"][$cnt]["total"]  = $harga * $_GET["jumlah"];
	            
	            } while ($tr = pg_fetch_object($t));
            }


            // tindakan operatif
            if (substr($d->hierarchy,0,9) == "006003002") {

               $t = pg_query($con,"SELECT * FROM RS00034 WHERE HIERARCHY LIKE '006003006%' AND GOLONGAN_TINDAKAN_ID = '$gol_tindakan'");
               $tr = pg_fetch_object($t);
         
	            do {
	            $cnt++;
	            $harga = $tr->harga;
	            $_SESSION["layanan"][$cnt]["id"]     = str_pad($tr->id,5,"0",STR_PAD_LEFT);
	            if ($tr->klasifikasi_tarif) $embel= " - ".$tr->klasifikasi_tarif;
	            $_SESSION["layanan"][$cnt]["nama"]   = $tr->layanan . $embel;
	            $_SESSION["layanan"][$cnt]["jumlah"] = $_GET["jumlah"];
	            $_SESSION["layanan"][$cnt]["satuan"] = $tr->satuan;
	            $_SESSION["layanan"][$cnt]["harga"]  = $harga;
	            $_SESSION["layanan"][$cnt]["total"]  = $harga * $_GET["jumlah"];
	            } while ($tr = pg_fetch_object($t));

            }

            // tindakan rawat jalan
            if (substr($d->hierarchy,0,9) == "006001001") {

               $t = pg_query($con,"SELECT * FROM RS00034 WHERE HIERARCHY LIKE '006001007%' AND GOLONGAN_TINDAKAN_ID = '$gol_tindakan'");
               $tr = pg_fetch_object($t);
               
	            do {
	            $cnt++;
	            $harga = $tr->harga;
	            $_SESSION["layanan"][$cnt]["id"]     = str_pad($tr->id,5,"0",STR_PAD_LEFT);
	            if ($tr->klasifikasi_tarif) $embel= " - ".$tr->klasifikasi_tarif;
	            $_SESSION["layanan"][$cnt]["nama"]   = $tr->layanan . $embel;
	            $_SESSION["layanan"][$cnt]["jumlah"] = $_GET["jumlah"];
	            $_SESSION["layanan"][$cnt]["satuan"] = $tr->satuan;
	            $_SESSION["layanan"][$cnt]["harga"]  = $harga;
	            $_SESSION["layanan"][$cnt]["total"]  = $harga * $_GET["jumlah"];
	            } while ($tr = pg_fetch_object($t));

            }

            unset($_SESSION["SELECT_LAYANAN"]);
            unset($_SESSION["SELECT_EMP"]);

            header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."&sub=layanan");
            exit;
            
        } elseif ($is_range) {
            $_SESSION["SELECT_LAYANAN"] = $_GET["layanan"];
            header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]. "&jumlah=" . $_GET["jumlah"]);
            exit;
        }
    } else {
        header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."&sub=layanan");
        exit;
    }
}


unset($_GET["layanan"]);

$reg = $_GET["rg"];
$reg1 = (int) $_GET["rg1"];
	$tab_disabled = array("pemeriksaan"=>true, "layanan"=>true, "icd"=>true, "riwayat"=>true,"riwayat_klinik"=>true, "konsultasi"=>true );
	if ($_GET["act"] == "del" ) {
	$tab_disabled = array("pemeriksaan"=>false, "layanan"=>false, "icd"=>false, "riwayat"=>false,"riwayat_klinik"=>false, "konsultasi"=>false);
	$tab_disabled[$_GET["sub"]] = true;
	$tab_disabled[$_POST["sub"]] = true;
	}
	$T = new TabBar();
	$T->addTab("$SC?p=$PID&list=pemeriksaan&rg1={$_GET["rg1"]}&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr ", "Input Catatan Bayi"	, $tab_disabled["pemeriksaan"]);
	//$T->addTab("$SC?p=$PID&list=layanan&rg1={$_GET["rg1"]}&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr&sub=layanan", "layanan / Tindakan"	, $tab_disabled["layanan"]);
	$T->addTab("$SC?p=$PID&list=icd&rg1={$_GET["rg1"]}&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr&sub=icd", "Pilih I C D"	, $tab_disabled["icd"]);
	$T->addTab("$SC?p=$PID&list=riwayat&rg1={$_GET["rg1"]}&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr", "Catatan Riwayat Bayi"	, $tab_disabled["riwayat"]);
	$T->addTab("$SC?p=$PID&list=riwayat_klinik&rg1={$_GET["rg1"]}&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr", "Riwayat Rawat Jalan"	, $tab_disabled["riwayat_klinik"]);
	//$T->addTab("$SC?p=$PID&list=konsultasi&rg1={$_GET["rg1"]}&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr", "Konsultasi"	, $tab_disabled["konsultasi"]);

if ($reg > 0) {
	$r1 = pg_query($con,
	"select tdesc from rs00001 where tt='LRI' and tc='{$_GET["ri"]}'");
	$n1 = pg_num_rows($r1);
	if($n1 > 0) $d1 = pg_fetch_object($r1);
	pg_free_result($r1);
	
	title_print("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  $d1->tdesc");
                title_excel("p_catatan_bayi&tblstart=".$_GET['tblstart']."&list=".$_GET['list']."&rg1=".$_GET['rg1']."&ri=".$_GET['ri']."&act=".$_GET['act']."&mr=".$_GET['mr']."&rg=".$_GET['rg']."&oid=".$_GET['oid']."");

    
		$sql="select a.id,a.mr_no,a.nama,a.umur,a.tgl_lahir,a.tmp_lahir,to_char(b.ts_check_in,'DD MON YYYY')as tanggal_reg,
				a.status_akhir,a.diagnosa_sementara, a.jenis_kelamin,a.pangkat_gol,a.nrp_nip,a.kesatuan,b.bangsal_id,e.bangsal,
				a.poli,a.rawatan,a.nama_ayah,a.agama,to_char(f.ts_check_in,'dd Mon YYYY')as tgl_masuk,to_char(f.ts_calc_stop,'dd Mon yyyy')as tgl_keluar 
				from rsv_pasien2 a 
				join rs00010 as b on a.id = b.no_reg join rs00012 as c on b.bangsal_id = c.id 
				join rs00012 as d on d.hierarchy = substr(c.hierarchy,1,6) || '000000000' 
				join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,3) || '000000000000' 
				join rs00010 as f on f.no_reg = a.id
				where a.id = '$reg1'";
	
    $r = pg_query($con,$sql);
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    $rawatan = $d->rawatan;
    // ambil bangsal
    $id_max = getFromTable("select max(id) from rs00010 where no_reg = '".$_GET["rg"]."'");
    if (!empty($id_max)) {
    $bangsal = getFromTable("select c.bangsal || ' / ' || e.tdesc ".
                       "from rs00010 as a ".
                       "    join rs00012 as b on a.bangsal_id = b.id ".
                       "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
                       //"    join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
                       "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
                       "where a.id = '$id_max'");
    }
    $umure = umur($d->umur);
    $umure = explode(" ",$umure);
    $umur = $umure[0]." Tahun";

	//===============update to rs00006 (status pemeriksaan)=============
    if($_GET['act'] == "periksa"){
	//pg_query("update rs00006 set periksa='Y' where id =lpad('".$_GET["rg"]."',10,'0')");
	}
	echo "<hr noshade size='1'>";
		echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top>";
		$f = new ReadOnlyForm();
		$f->text("<b>"."Nama",$d->nama);
		$f->text("<b>"."No RM",$d->mr_no);
		$f->text("<b>"."No Reg", formatRegNo($_GET["rg1"]));
		//$f->text("Kedatangan",$d->datang);
		$f->execute();
		echo "</td><td align=left valign=top>";
		$f = new ReadOnlyForm();
		$f->text("<b>"."NRP/NIP/Pangkat",$d->nrp_nip." / ".$d->pangkat_gol);
		$f->text("<b>"."Kesatuan",$d->kesatuan);
		$f->text("<b>"."Tanggal Masuk",$d->tanggal_reg);
		$f->execute();
		echo "</td><td align=left valign=top>";
		$f = new ReadOnlyForm();
		$f->text("<b>"."Umur", $umur);
		$f->text("<b>"."Seks",$d->jenis_kelamin);
		$f->text("<b>"."Ruang",$d->bangsal);
		$f->execute();
		echo "</td><td valign=top>";
	    $f = new ReadOnlyForm();
	    echo "<table border=0 width='100%'>";
	    echo "<tr><td class=TBL_BODY><strong>Diagnosa Sementara</strong>&nbsp;:</td></tr>";
	    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
	    echo "</table>";
    	$f->execute();
		echo "</td></tr></table>";
		echo"<hr noshade size='2'>";
        
    echo "</div>";
 	if(!$GLOBALS['print']){
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=p_layanan_rawat_inap&rg={$_GET["rg"]}&mr={$_GET["mr"]}&rg1={$_GET["rg1"]}'>".icon("back","Kembali")."</a></DIV>";
    	echo"<br>";
 	}
    	
    //disini

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
        $f = new Form("actions/p_catatan_bayi.insert.php");
        $f->hidden("rg",$_GET["rg"]);
        $f->hidden("mr",$_GET["mr"]);
        $f->hidden("ri",$_GET["ri"]);
        $f->hidden("poli",$d->poli);
	$f->hidden("sub",$_GET["sub"]);
        $f->hidden("byr",$total);
        //$f->text("byr","Jumlah Pembayaran",15,15,$total,"STYLE='text-align:right'");
        $f->submit(" Simpan &amp; Bayar ");
        $f->execute();
    
} elseif ($_GET["list"] == "icd") {  // -------- ICD
	if(!$GLOBALS['print']){	
	$T->show(1);
	}
        echo"<div align=center class=form_subtitle1>KLASIFIKASI PENYAKIT</div>";
        echo "<table width='100%' border=0 cellspacing=0 cellpadding=0><tr>";
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b2.disabled = true;\n";
        echo "document.Form3.b4.disabled = false;\n";
        echo "</script>\n";
        echo "<form action='$SC'>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg1 VALUE='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        echo "</form>";
        echo "<td valign=top>";

        $namaICD = getFromTable("SELECT DESCRIPTION FROM RSV0005 WHERE DIAGNOSIS_CODE = '".$_SESSION["SELECT_ICD"]."'");
        $katICD = getFromTable("SELECT CATEGORY FROM RSV0005 WHERE DIAGNOSIS_CODE = '".$_SESSION["SELECT_ICD"]."'");
        
        $t = new BaseTable("100%");
        $t->printTableOpen();
        echo "<FORM ACTION='$SC' NAME=Form11>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg1 VALUE='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t->printTableHeader(Array("KODE ICD", "KETERANGAN","KATEGORI", "&nbsp;"));
        
        if (is_array($_SESSION["icd"])) {
            foreach($_SESSION["icd"] as $k => $l) {
                $t->printRow(
                    Array($l["id"], $l["desc"],$l["kate"], "<A HREF='$SC?p=$PID&list=icd&rg1=".$_GET["rg1"]."&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=".$_GET["mr"]."&del-icd=$k&httpHeader=1'>".icon("del-left")."</A>"), Array("CENTER", "LEFT", "LEFT","CENTER")
                );
            }
        }
		// sfdn, 27-12-2006 --> pembetulan directory icon = ../simrs/images/*.png
        $t->printRow(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=icd STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_ICD"]."'>&nbsp;<A HREF='javascript:selectICD()'><IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaICD,"$katICD", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "LEFT","CENTER")
        );
		// --- eof 27-12-2006 ---
        echo "</FORM>";
        
        $t->printTableClose();
        //echo "</td></tr></table>";
        echo "\n<script language='JavaScript'>\n";
        echo "function selectICD() {\n";
        echo "sWin = window.open('popup/icd.php', 'xWin', 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
        
        echo "<form name='Form9' action='actions/p_catatan_bayi.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg1 VALUE='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<br><div align=right><input type=button value='Simpan' onClick='document.Form9.submit()'>&nbsp;</div>";
        echo "</form>";
     
        include("rincian.php");
        
    }elseif ($_GET["list"] == "layanan") { // ----------------------------- LAYANAN MEDIS
    	if(!$GLOBALS['print']){
    	$T->show(1);
    	}
        echo"<div align=center class=form_subtitle1>LAYANAN DAN TINDAKAN MEDIS</div>";
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b1.disabled = true;\n";
        echo "document.Form3.b2.disabled = false;\n";
        echo "document.Form3.b4.disabled = false;\n";
        echo "</script>\n";

        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg1 VALUE='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='layanan'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=ri VALUE='".$_GET["ri"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        


        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "LAYANAN", "YANG MELAKUKAN TINDAKAN", "JUMLAH", "SATUAN",
            "HARGA SATUAN", "HARGA TOTAL", ""));
            
        if (is_array($_SESSION["layanan"])) {
            $total = 0.00;
            foreach($_SESSION["layanan"] as $k => $l) {

                $q = pg_query("SELECT B.TDESC AS KELAS_TARIF, SUBSTR(A.HIERARCHY,1,6) AS HIE FROM RS00034 A ".
                        "LEFT JOIN RS00001 B ON A.KLASIFIKASI_TARIF_ID = B.TC AND B.TT = 'KTR' ".
                        "WHERE A.ID = $l[id]");
                $qr = pg_fetch_object($q);

                if ($qr->hie == "003002") {
                   $tambahan = " - ".$qr->kelas_tarif;

                }

                $t->printRow(
                    Array($l["id"], $l["nama"].$tambahan, $l["dokter"], $l["jumlah"], $l["satuan"],
                        number_format($l["harga"],2), number_format($l["total"],2),
                        "<A HREF='$SC?p=$PID&list=layanan&rg1=".$_GET["rg1"]."&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=".$_GET["mr"]."&del=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "CENTER","RIGHT", "LEFT", "RIGHT", "RIGHT", "CENTER")
                );
                $total += $l["total"];
            }
        }
        
        if (isset($_SESSION["SELECT_LAYANAN"])) {
            $r = pg_query($con,"select * from rsv0034 where id = '" . $_SESSION["SELECT_LAYANAN"] . "'");
            $d = pg_fetch_object($r);
            pg_free_result($r);

            $is_range = $d->harga_atas > 0 || $d->harga_bawah > 0;
            $harga = $is_range ? $_GET["harga"] : $d->harga;

            $hargaHtml = $is_range ?
                "<INPUT TYPE=TEXT NAME=harga SIZE=10 MAXLENGTH=12 VALUE='$d->harga'>" : $d->harga;
        }
		// sfdn, 27-12-2006 -> pembetulan directory gambar = ../simrs/images/*.png
        $t->printRow(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_LAYANAN"].
			"'>&nbsp;<A HREF='javascript:selectLayanan()'><IMG BORDER=0 SRC='images/icon-view.png'></A>",
			$d->layanan . " - " . $d->klasifikasi_tarif, "<INPUT OnKeyPress='refreshSubmit()' NAME=dokter STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=12 VALUE='"
                        .$_SESSION["SELECT_EMP"]."'>&nbsp;<A HREF='javascript:selectPegawai()'><IMG BORDER=0 SRC='images/icon-view.png'></a>", "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1").
			"'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", $d->satuan, $hargaHtml,
			"", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK' >"),
            Array("CENTER", "LEFT", "CENTER","CENTER", "LEFT", "RIGHT", "LEFT", "CENTER")
        );
		// --- eof 27-12-2006 ---
        $t->printRow(
            Array("", "", "", "", "", "", number_format($total,2),""),
            Array("RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT")
        );
        $t->printTableClose();
        echo "</FORM>";
        
        if (isset($_SESSION["SELECT_LAYANAN"]) && $is_range) {
            echo "<br>";
            info("Informasi Harga:",
                "$d->unit_layanan, $d->sub_unit_layanan, $d->layanan<BR>".
                "Harga: <big>Rp. $d->harga_bawah</big> sampai dengan <big>Rp. $d->harga_atas</big>");
        }
        echo "<form name='Form10' action='actions/p_catatan_bayi.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<input type=hidden name=rg1 value='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<input type=hidden name=list value='layanan'>";
        echo "<br><div align=right><input type=button value='Simpan' onClick='document.Form10.submit()'>&nbsp;";
        echo "</form>";
       
       include("rincian.php"); 
       
    } elseif($_GET["list"] == "riwayat") {
    	if(!$GLOBALS['print']){
    	$T->show(2);
    	}
    	if ($_GET["act"] == "detail") {
				$sql = 	"select a.*,to_char(a.tanggal_reg,'DD MON YYYY HH24:MI:SS')as tanggal_reg,f.layanan ".
						"from c_visit_ri a ". 
						"left join rsv0002 c on a.no_reg=c.id ".
						"left join rs00006 d on d.id = a.no_reg ".
						"left join rs00008 e on e.no_reg = a.no_reg ".
						"left join rs00034 f on f.id::text = e.item_id ".
						"where a.no_reg='{$_GET['rg']}' and a.id_ri= '{$_GET["mPOLI"]}' and a.oid= '{$_GET["oid"]}'";
				$r = pg_query($con,$sql);
				$n = pg_num_rows($r);
			    if($n > 0) $d = pg_fetch_array($r);
			    pg_free_result($r);
				//echo $sql;exit;			
			    $_GET['id'] = $_GET['rg'] ;	
	 			
			echo"<div class=box>";
			echo "<table width='100%' border='0'><tr><td colspan='2'>";
			echo"<div class=form_subtitle>DATA PASIEN</div>";
			echo "</td></tr>";
    		echo "<tr><td valign=top>";
    		$f = new ReadOnlyForm();
			$f->text($visit_ri_catatan_bayi["vis_1"],$d[4]."&nbsp;Tahun");
			$f->text($visit_ri_catatan_bayi["vis_2"],$d[5]);
			$f->text($visit_ri_catatan_bayi["vis_3"],$d[6]);
			$f->text($visit_ri_catatan_bayi["vis_4"],$d[7] );	
			$f->text($visit_ri_catatan_bayi["vis_5"],$d[8]."&nbsp;Orang");
			$f->text($visit_ri_catatan_bayi["vis_6"],$d[9]);
			$f->text($visit_ri_catatan_bayi["vis_7"],$d[10]);
			$f->text($visit_ri_catatan_bayi["vis_8"],$d[11] );    
			$f->text($visit_ri_catatan_bayi["vis_9"],$d[12]);
			$f->text($visit_ri_catatan_bayi["vis_10"],$d[13]);
			$f->text($visit_ri_catatan_bayi["vis_11"],$d[14]);
			$f->text($visit_ri_catatan_bayi["vis_12"],$d[15] );	
			$f->text($visit_ri_catatan_bayi["vis_13"],$d[16] );
			$f->title1("<U>GOLONGN DARAH</U>","LEFT");
			$f->text($visit_ri_catatan_bayi["vis_14"],$d[17]);
			$f->text($visit_ri_catatan_bayi["vis_15"],$d[18]);
			$f->text($visit_ri_catatan_bayi["vis_16"],$d[19]);
			$f->text($visit_ri_catatan_bayi["vis_17"],$d[20] );	
			$f->text($visit_ri_catatan_bayi["vis_18"],$d[21] );
			$f->text($visit_ri_catatan_bayi["vis_19"],$d[22]);
			$f->title1("<U>PENGOBATAN IBU YANG DAPAT MEMPENGARUHI BAYI</U>");
			$f->text($visit_ri_catatan_bayi["vis_20"],$d[23]);
			$f->text($visit_ri_catatan_bayi["vis_21"],$d[24]);
			$f->text($visit_ri_catatan_bayi["vis_22"],$d[25] );	
			$f->title1("<U>ANESTESI / ANALGESI</U>");
			$f->text($visit_ri_catatan_bayi["vis_23"],$d[26]."&nbsp;mm Hg" );
			$f->text($visit_ri_catatan_bayi["vis_24"],$d[27]."&nbsp;/ Menit");
			$f->text($visit_ri_catatan_bayi["vis_25"],$d[28]);
			$f->title1("<U>BAYI</U>");
			$f->text($visit_ri_catatan_bayi["vis_26"],$d[29]);
			$f->text($visit_ri_catatan_bayi["vis_27"],$d[30] );	
			$f->text($visit_ri_catatan_bayi["vis_28"],$d[31] );
			$f->text($visit_ri_catatan_bayi["vis_29"],$d[32]);
			$f->title1("<U>PENILAIAN BAYI DENGAN APGAR SCORE 60 DETIK, SETELAH LAHIR LENGKAP</U>");
//			if ($d["vis_30"] == "CHECKED"){
//			$f->checkbox3("Frekuensi Jantung","Tidak ada",$d["vis_30"],"[0]",$ext);
//			}elseif($d["vis_31"] == "CHECKED"){
//			$f->checkbox3("Frekuensi Jantung","100",$d["vis_31"],"[1]",$ext);	
//			}elseif($d["vis_32"] == "CHECKED"){
//			$f->checkbox3("Frekuensi Jantung","100",$d["vis_32"],"[2]",$ext);	
//			}
			$f->text($visit_ri_catatan_bayi["vis_33"],$d["vis_33"],$ext);
			if ($d["vis_34"] == "CHECKED"){
			$f->checkbox3("Usaha Nafas","Tidak ada",$d["vis_34"],"[0]",$ext);
			}
//                        elseif($d["vis_35"] == "CHECKED"){
//			$f->checkbox3("Usaha Nafas","Lambat dan tidak teratur",$d["vis_35"],"[1]",$ext);	
//			}elseif($d["vis_36"] == "CHECKED"){
//			$f->checkbox3("Usaha Nafas","Mengangis kuat",$d["vis_36"],"[2]",$ext);	
//			}
//			$f->text($visit_ri_catatan_bayi["vis_37"],$d["vis_37"],$ext);
//			if ($d["vis_38"] == "CHECKED"){
//			$f->checkbox3("Tonus Otot","Lumpuh",$d["vis_38"],"[0]",$ext);
//			}elseif($d["vis_39"] == "CHECKED"){
//			$f->checkbox3("Tonus Otot","Ext, flexi sedikit",$d["vis_39"],"[1]",$ext);	
//			}
			$f->execute();
			echo "</td><td valign=top>";
    		$f = new ReadOnlyForm();
			
//			if($d["vis_40"] == "CHECKED"){
//			$f->checkbox3("Tonus Otot","Gerakan Aktif",$d["vis_40"],"[2]",$ext);	
//			}
			$f->text($visit_ri_catatan_bayi["vis_41"],$d["vis_41"],$ext);
			if ($d["vis_42"] == "CHECKED"){
			$f->checkbox3("Reflex","Tidak terjawab",$d["vis_42"],"[0]",$ext);
			}
                        if($d["vis_43"] == "CHECKED"){
			$f->checkbox3("Reflex","Gerakan sedikit",$d["vis_43"],"[1]",$ext);	
			}
//                        if($d["vis_44"] == "CHECKED"){
//			$f->checkbox3("Reflex","Menangis",$d["vis_44"],"[1]",$ext);	
//			}
//			$f->text($visit_ri_catatan_bayi["vis_45"],$d["vis_45"],$ext);
//			if ($d["vis_46"] == "CHECKED"){
//			$f->checkbox3("Waktu","Biru / Pucat",$d["vis_46"],"[0]",$ext);
//			}
//                        if($d["vis_47"] == "CHECKED"){
//			$f->checkbox3("Waktu","Tubuh kemerahan, tangan dan kaki biru",$d["vis_47"],"[1]",$ext);	
//			}
//                        if($d["vis_48"] == "CHECKED"){
//			$f->checkbox3("Waktu","Kemerahan",$d["vis_48"],"[2]",$ext);	
//			}
			$f->text($visit_ri_catatan_bayi["vis_49"],$d["vis_49"],$ext);	
			$f->title1("<U>RESUITASI</U>","LEFT");
			$f->text($visit_ri_catatan_bayi["vis_50"],$d[53]."&nbsp;Menit");
			$f->text($visit_ri_catatan_bayi["vis_51"],$d[54]."&nbsp;Menit");
			$f->text($visit_ri_catatan_bayi["vis_52"],$d[55]."&nbsp;Menit");
			$f->text($visit_ri_catatan_bayi["vis_53"],$d[56]."&nbsp;Menit" );
			$f->title1("<U>KISAH RESUITASI</U>","LEFT");	
			$f->text($visit_ri_catatan_bayi["vis_54"],$d[57]."&nbsp;Menit");
			$f->text($visit_ri_catatan_bayi["vis_55"],$d[58]."&nbsp;Menit");
			$f->text($visit_ri_catatan_bayi["vis_56"],$d[59]."&nbsp;Menit");
			$f->text($visit_ri_catatan_bayi["vis_57"],$d[60]);
			$f->title1("<U>PEMERIKSAAN BAYI SETELAH LAHIR</U>","LEFT");
			$f->text($visit_ri_catatan_bayi["vis_58"],$d[61] );	
			$f->text($visit_ri_catatan_bayi["vis_59"],$d[62] );
			$f->text($visit_ri_catatan_bayi["vis_60"],$d[63]);
			$f->text($visit_ri_catatan_bayi["vis_61"],$d[64]);
			$f->text($visit_ri_catatan_bayi["vis_62"],$d[65]);
			$f->text($visit_ri_catatan_bayi["vis_63"],$d[66] );	
			$f->text($visit_ri_catatan_bayi["vis_64"],$d[67] );
			$f->text($visit_ri_catatan_bayi["vis_65"],$d[68]);
			$f->text($visit_ri_catatan_bayi["vis_66"],$d[69]);
			$f->text($visit_ri_catatan_bayi["vis_67"],$d[70]);
			$f->text($visit_ri_catatan_bayi["vis_68"],$d[71] );	
			$f->text($visit_ri_catatan_bayi["vis_69"],$d[72] );
			$f->text($visit_ri_catatan_bayi["vis_70"],$d[73]);
			$f->text($visit_ri_catatan_bayi["vis_71"],$d[74]);
			$f->text($visit_ri_catatan_bayi["vis_72"],$d[75]);
			$f->text($visit_ri_catatan_bayi["vis_73"],$d[76]);
			$f->text($visit_ri_catatan_bayi["vis_74"],$d[77]."&nbsp;Cm");
			$f->text($visit_ri_catatan_bayi["vis_75"],$d[78]."&nbsp;Cm");
			$f->text($visit_ri_catatan_bayi["vis_76"],$d[79]."&nbsp;Cm");
			$f->text($visit_ri_catatan_bayi["vis_77"],$d[80]."&nbsp;Cm");
			$f->text($visit_ri_catatan_bayi["vis_78"],$d[81]."&nbsp;Cm");
			$f->execute();
    		echo "</td></tr>";
  			echo "<tr><td colspan='3'>";
  			echo "<br>";
  			include(rm_tindakan3);
  			echo "</td><td>";
  			echo "</td></tr></table>";

			
			}else {
				echo"<div align=center class=form_subtitle1>CATATAN RIWAYAT BAYI</div>";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
				$sql = "SELECT A.NO_REG,to_char(A.TANGGAL_REG,'DD MON YYYY HH24:MI:SS')as tgl_reg,A.VIS_3,A.VIS_4,A.OID ". 
					   "FROM C_VISIT_RI A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   "WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_ri = '{$_GET["mPOLI"]}' 
                                           group by A.NO_REG,A.TANGGAL_REG,A.VIS_3,A.VIS_4,A.OID ";
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	//$t->ColHidden[4]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("NO REGISTRASI","TANGGAL/JAM KUNJUNGAN","TANGGAL LAHIR","JENIS KELAMIN","DETAIL");
			   	$t->ColAlign = array("center","center","left","left","center");
				$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat&rg1={$_GET["rg1"]}&ri=".$_GET["mPOLI"]."&act=detail&mr=".$_GET["mr"]."&rg=<#0#>&oid=<#4#>'>".icon("view","View")."</A>";	
				$t->execute();
				
				echo"<br>";
         		echo"</div>";
				echo "</td></tr></table></div>";
    	
			}
    }elseif($_GET["list"] == "riwayat_klinik") {
    	if(!$GLOBALS['print']){
    	$T->show(3);
    	}
    	if ($_GET["act"] == "detail_klinik") {
				$sql = "select a.*,b.nama,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,f.layanan,a.id_poli 
						from c_visit a 
						left join rs00017 b on a.id_dokter = b.id 
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00034 f on 'f.id' = e.item_id
						where a.no_reg='{$_GET['rg']}' and a.oid='{$_GET['oid']}' ";
				$r = pg_query($con,$sql);
				$n = pg_num_rows($r);
			    if($n > 0) $d = pg_fetch_array($r);
			    pg_free_result($r);
				//echo $sql;exit;			
			    $_GET['id'] = $_GET['rg'] ;	
	 			
			//echo"<div class=box>";
			echo "<table width='100%' border='0'><tr><td colspan='2'>";
			//echo"<div class=form_subtitle>PEMERIKSAAN PASIEN</div>";
			echo "</td></tr>";
    		echo "<tr><td>";
    		$f = new ReadOnlyForm();
    		$poli=$d["id_poli"];
    		$f->text("Poli","<b>".$poli);
    		if ($poli == $setting_poli["igd"]) {
    			include(detail_igd);
    		}elseif ($poli == $setting_poli["umum"]){
    			include(detail_umum);
    		}elseif ($poli == $setting_poli["mata"]){
    			include(detail_mata);
    		}elseif ($poli == $setting_poli["peny_dalam"]){
    			include(detail_peny_dalam);
    		}
    		elseif ($poli == $setting_poli["anak"]){
    			include(detail_anak);
    		}
    		elseif ($poli == $setting_poli["gigi"]){
    			include(detail_gigi);
    		}
    		elseif ($poli == $setting_poli["tht"]){
    			include(detail_tht);
    		}
    		elseif ($poli == $setting_poli["bedah"]){
    			include(detail_bedah);
    		}
    		elseif ($poli == $setting_poli["kulit_kelamin"]){
    			include(detail_kulit_kelamin);
    		}
    		elseif ($poli == $setting_poli["akupunktur"]){
    			include(detail_akupunktur);
    		}
    		elseif ($poli == $setting_poli["jantung"]){
    			include(detail_jantung);
    		}
    		elseif ($poli == $setting_poli["paru"]){
    			include(detail_paru);
    		}
    		elseif ($poli == $setting_poli["kebidanan_obstetri"]){
    			include(detail_obstetri);
    		}
    		elseif ($poli == $setting_poli["kebidanan_ginekologi"]){
    			include(detail_ginekologi);
    		}
    		elseif ($poli == $setting_poli["saraf"]){
    			include(detail_saraf);
    		}
    		elseif ($poli == $setting_poli["psikiatri"]){
    			include(detail_psikiatri);
    		}
    		elseif ($poli == $setting_poli["fisioterapi"]){
    			include(detail_fisioterapi);
    		}
    		elseif ($poli == $setting_poli["radiologi"]){
    			include(detail_radiologi);
    		}
    		else{
    			include(detail_laboratorium);
    		}
    		
			}else {
				echo"<div align=center class=form_subtitle1>RIWAYAT PENYAKIT PASIEN</div>";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
				$sql = "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS')AS WAKTU,C.TDESC,D.NAMA,A.OID ". 
					   "FROM C_VISIT A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG= B.ID  ".
					   "LEFT JOIN RS00001 C ON A.ID_POLI = CAST(C.TC as numeric) AND C.TT='LYN'".
					   "LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID ".
					   "WHERE B.MR_NO = '".$_GET["mr"]."'
                                            group by A.NO_REG,A.TANGGAL_REG,C.TDESC,D.NAMA,A.OID  ";
					
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	$t->ColHidden[5]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("NO REGISTRASI","TANGGAL KUNJUNGAN","WAKTU KUNJUNGAN","KLINIK","DETAIL");
			   	$t->ColAlign = array("center","center","center","left","center","center");
				$t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat_klinik&rg1={$_GET["rg1"]}&ri=".$_GET["mPOLI"]."&act=detail_klinik&mr=".$_GET["mr"]."&rg=<#0#>&oid=<#5#>'>".icon("view","View")."</A>";	
				$t->execute();
				
				echo"<br>";
         		echo"</div>";
				echo "</td></tr></table></div>";
    	
			}

}elseif ($_GET["list"] == "konsultasi"){
    	$T->show(6);
    	echo"<br>";
    	
    	//$laporan = getFromTable("select tdesc from rs00001 where tt='LRI' and tc = '".$_SESSION[SELECT_LAP]."'");
    	$f = new Form("actions/p_riwayat_penyakit.insert.php", "POST", "NAME=Form2");

 


					$f->hidden("act","new2");
					$f->hidden("f_no_reg",$_GET["rg"]);
					$f->hidden("list","konsultasi");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli",$_GET["poli"]);
				    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
				    $f->hidden("f_user_id",$_SESSION[uid]);
				    $f->hidden("konsultasi",$_GET["konsultasi"]);
				    
					echo"<br>";
 				 	$tipe 	     = getFromTable("select no_reg from c_visit where no_reg='".$_GET["rg"]."'  ");
					$ext = "disabled" ;
					if ($tipe){
							$ext = "" ;
							$konsul = getFromTable("select vis_80 from c_visit_ri where no_reg='".$_GET["rg"]."'  ");
					}
				    $f->PgConn=$con;
					$f->selectSQL("konsultasi","Unit Yang Dituju", "select '-' as tc , '-' as tdesc union select tc,tdesc from rs00001 where tt='LYN' and tc not in ('000','100','111','201','202','206','207','208') order by tdesc",$konsul,$ext);
				    $f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
				    $f->execute();
				    
				    echo"<br><font color=black>&nbsp;* Catatan : Hasil Pemeriksaan Pasien harus diisi minimal Dokter Pemeriksa</font><br>";
    }

    else {       //pemeriksaan
    	if(!$GLOBALS['print']){
    	$T->show(0);
    	}
    	
    		$sql2 =	"SELECT A.*,(C.NAMA)AS merawat,(D.NAMA)AS mengirim FROM C_VISIT_RI A
    				LEFT JOIN RS00017 C ON A.VIS_1 = 'C.ID'
    				LEFT JOIN RS00017 D ON A.VIS_2 = 'D.ID'
    				WHERE A.ID_RI='{$_GET["ri"]}' AND A.NO_REG='{$_GET["rg1"]}'";
			$r=pg_query($con,$sql2);
			$n = pg_num_rows($r);
			if($n > 0) $d2 = pg_fetch_array($r);
			pg_free_result($r);
				//-------------------------tambah for update------hery 08072007
				echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg1={$_GET["rg1"]}&rg=$rg&mr={$_GET['mr']}&ri={$_GET["ri"]}&act=edit';\">\n";   
				//echo "<input type='image' src='images/icon-edit.png' action='edit' >";
				    
				if ($_GET['act'] == "edit") {
						echo "<font color='#000000' size='2'> >>Edit Pemeriksaan Pasien</font>";
						$f = new Form("actions/p_catatan_bayi.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d2["no_reg"]);
					    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
						$f->hidden("list","pemeriksaan");
						$f->hidden("rawatan",$rawatan);
						$f->hidden("f_id_rujukan",$d->poli);
					    $f->hidden("mr",$_GET["mr"]);
					    $f->hidden("f_id_ri",$_GET["ri"]);
					    $f->hidden("f_user_id",$_SESSION[uid]);
					    $f->hidden("rg1",$_GET[rg1]);
					   
				}else {
					if($n > 0) {
						$ext= "disabled";
					}else {
						$ext = "";
					}
				//---------------------------------------------------------------------------------			
						
					$f = new Form("actions/p_catatan_bayi.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("f_id_rujukan",$d->poli);
					$f->hidden("list","pemeriksaan");
					$f->hidden("rawatan",$rawatan);
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_ri",$_GET["ri"]);
					$f->hidden("f_user_id",$_SESSION[uid]);
					$f->hidden("rg1",$_GET[rg1]);
			}
				    
				//$f->calendar("tanggal_reg","Tanggal Registrasi",15,15,$d2[1],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
					
			echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
			$f->textinfo("f_vis_1",$visit_ri_catatan_bayi["vis_1"],10,10,$d2["vis_1"],"Tahun",$ext);
			$f->text("f_vis_2",$visit_ri_catatan_bayi["vis_2"],50,50,$d2["vis_2"],$ext);
			$f->text("f_vis_3",$visit_ri_catatan_bayi["vis_3"],50,50,$d2["vis_3"],$ext);
			$f->text("f_vis_4",$visit_ri_catatan_bayi["vis_4"],50,50,$d2["vis_4"],$ext);
			$f->textinfo("f_vis_5",$visit_ri_catatan_bayi["vis_5"],10,10,$d2["vis_5"],"Orang",$ext);
			$f->text("f_vis_6",$visit_ri_catatan_bayi["vis_6"],30,30,$d2["vis_6"],$ext);
			$f->textarea("f_vis_7",$visit_ri_catatan_bayi["vis_7"] ,1, $visit_ri_catatan_bayi["vis_7"."W"],$d2["vis_7"],$ext);
			$f->text_6i("","f_vis_8",$visit_ri_catatan_bayi["vis_8"],30,30,$d2["vis_8"],"","f_vis_9",$visit_ri_catatan_bayi["vis_9"],30,30,$d2["vis_9"],"",
			"f_vis_10",$visit_ri_catatan_bayi["vis_10"],30,30,$d2["vis_10"],"","f_vis_11",$visit_ri_catatan_bayi["vis_11"],30,30,$d2["vis_11"],"",
			"f_vis_12",$visit_ri_catatan_bayi["vis_12"],30,30,$d2["vis_12"],"","f_vis_13",$visit_ri_catatan_bayi["vis_13"],30,30,$d2["vis_13"],"",$ext);
			$f->title1("<U>GOLONGAN DARAH</U>");
			$f->text("f_vis_14",$visit_ri_catatan_bayi["vis_14"],30,30,$d2["vis_14"],$ext);
			$f->text("f_vis_15",$visit_ri_catatan_bayi["vis_15"],30,30,$d2["vis_15"],$ext);
			$f->text("f_vis_16",$visit_ri_catatan_bayi["vis_16"],30,30,$d2["vis_16"],$ext);
			$f->textarea("f_vis_17",$visit_ri_catatan_bayi["vis_17"] ,1, $visit_ri_catatan_bayi["vis_17"."W"],$d2["vis_17"],$ext);
			$f->text("f_vis_18",$visit_ri_catatan_bayi["vis_18"],30,30,$d2["vis_18"],$ext);
			$f->textarea("f_vis_19",$visit_ri_catatan_bayi["vis_19"] ,1, $visit_ri_catatan_bayi["vis_19"."W"],$d2["vis_19"],$ext);
			$f->title1("<U>PENGOBATAN IBU YANG DAPAT MEMPENGARUHI BAYI</U>");
			$f->text("f_vis_20",$visit_ri_catatan_bayi["vis_20"],30,30,$d2["vis_20"],$ext);
			$f->text("f_vis_21",$visit_ri_catatan_bayi["vis_21"],30,30,$d2["vis_21"],$ext);
			$f->calendar("f_vis_22",$visit_ri_catatan_bayi["vis_22"],15,15,$d2["vis_22"],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
			$f->title1("<U>ANESTESI / ANALGESI</U>");
			$f->text("f_vis_23",$visit_ri_catatan_bayi["vis_23"],30,30,$d2["vis_23"],$ext);
			$f->text("f_vis_24",$visit_ri_catatan_bayi["vis_24"],30,30,$d2["vis_24"],$ext);
			$f->calendar("f_vis_25",$visit_ri_catatan_bayi["vis_25"],15,15,$d2["vis_25"],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
			$f->title1("<U>BAYI</U>");
			$f->calendar("f_vis_26",$visit_ri_catatan_bayi["vis_26"],15,15,$d2["vis_26"],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
			if($d2["vis_27"] != ''){
			$f->textinfo("f_vis_27",$visit_ri_catatan_bayi["vis_27"],15,15,$d2["vis_27"],"(Jam:Menit, 08:09)",$ext);
			}else{
			$f->textinfo("f_vis_27",$visit_ri_catatan_bayi["vis_27"],15,15,"00:00","(Jam:Menit, 08:09)",$ext);
			}
			if($d2["vis_28"]=="Laki-Laki"){
			    	$f->checkbox2($visit_ri_catatan_bayi["vis_28"],"f_vis_28","Laki-Laki","Laki-Laki","CHECKED","Perempuan","Perempuan","",$ext);
			    }elseif ($d2["vis_28"]=="Perempuan"){
			    	$f->checkbox2($visit_ri_catatan_bayi["vis_28"],"f_vis_28","Laki-Laki","Laki-Laki","","Perempuan","Perempuan","CHECKED",$ext);
			    }else{
			    	$f->checkbox2($visit_ri_catatan_bayi["vis_28"],"f_vis_28","Laki-Laki","Laki-Laki","","Perempuan","Perempuan","",$ext);
			    }
			$f->text("f_vis_29",$visit_ri_catatan_bayi["vis_29"],30,50,$d2["vis_29"],$ext);
			$f->title1("<U>PENILAIAN BAYI DENGAN APGAR SCORE 60 DETIK, SETELAH LAHIR LENGKAP</U>");
			$f->checkbox3("&nbsp;Frekuensi Jantung","f_vis_30","Tak Ada","CHECKED",$d2["vis_30"],"f_vis_31","100","CHECKED",$d2["vis_31"],"f_vis_32","100","CHECKED",$d2["vis_32"],$ext);
			$f->text("f_vis_33",$visit_ri_catatan_bayi["vis_33"],10,10,$d2["vis_33"],$ext);
			$f->checkbox3("&nbsp;Usaha Nafas","f_vis_34","Tak Ada","CHECKED",$d2["vis_34"],"f_vis_35","Lambat, Tak Teratur","CHECKED",$d2["vis_35"],"f_vis_36","Menangis Kuat","CHECKED",$d2["vis_36"],$ext);
			$f->text("f_vis_37",$visit_ri_catatan_bayi["vis_37"],10,10,$d2["vis_37"],$ext);
			$f->checkbox3("&nbsp;Tonus Otot","f_vis_38","Lumpuh","CHECKED",$d2["vis_38"],"f_vis_39","Ext, Flexi Sedikit","CHECKED",$d2["vis_39"],"f_vis_40","Gerakan Aktif","CHECKED",$d2["vis_40"],$ext);
			$f->text("f_vis_41",$visit_ri_catatan_bayi["vis_41"],10,10,$d2["vis_41"],$ext);
			$f->checkbox3("&nbsp;Reflex","f_vis_42","Tak Terjawab","CHECKED",$d2["vis_42"],"f_vis_43","Gerakan Sedikit","CHECKED",$d2["vis_43"],"f_vis_44","Menangis","CHECKED",$d2["vis_44"],$ext);
			$f->text("f_vis_45",$visit_ri_catatan_bayi["vis_45"],10,10,$d2["vis_45"],$ext);
			$f->checkbox3("&nbsp;Warna","f_vis_46","Biru / Pucat","CHECKED",$d2["vis_46"],"f_vis_47","Tubuh Kemerahan, Tangan dan Kaki Biru","CHECKED",$d2["vis_47"],"f_vis_48","Kemerahan","CHECKED",$d2["vis_48"],$ext);
			$f->text("f_vis_49",$visit_ri_catatan_bayi["vis_49"],10,10,$d2["vis_49"],$ext);
			$f->title1("<U>RESUITASI DAN KISAH RESUITASI</U>");
			$f->textinfo("f_vis_50",$visit_ri_catatan_bayi["vis_50"],20,10,$d2["vis_50"],"/Menit",$ext);
			$f->textinfo("f_vis_51",$visit_ri_catatan_bayi["vis_51"],20,10,$d2["vis_51"],"/Menit",$ext);
			$f->textinfo("f_vis_52",$visit_ri_catatan_bayi["vis_52"],20,10,$d2["vis_52"],"/Menit",$ext);
			$f->textinfo("f_vis_53",$visit_ri_catatan_bayi["vis_53"],20,10,$d2["vis_53"],"/Menit",$ext);
			$f->textinfo("f_vis_54",$visit_ri_catatan_bayi["vis_54"],20,10,$d2["vis_54"],"/Menit",$ext);
			$f->textinfo("f_vis_55",$visit_ri_catatan_bayi["vis_55"],20,10,$d2["vis_55"],"/Menit",$ext);
			$f->textinfo("f_vis_56",$visit_ri_catatan_bayi["vis_56"],20,10,$d2["vis_56"],"/Menit",$ext);
			$f->title1("<U>PEMERIKSAAN BAYI SETELAH LAHIR</U>");
			$f->textarea("f_vis_57",$visit_ri_catatan_bayi["vis_57"] ,1, $visit_ri_catatan_bayi["vis_57"."W"],$d2["vis_57"],$ext);
			$f->text_8i("f_vis_58",$visit_ri_catatan_bayi["vis_58"],30,50,$d2["vis_58"],"","f_vis_59",$visit_ri_catatan_bayi["vis_59"],30,50,$d2["vis_59"],"",
			"f_vis_60",$visit_ri_catatan_bayi["vis_60"],30,50,$d2["vis_60"],"","f_vis_61",$visit_ri_catatan_bayi["vis_61"],30,50,$d2["vis_61"],"",
			"f_vis_62",$visit_ri_catatan_bayi["vis_62"],30,50,$d2["vis_62"],"","f_vis_63",$visit_ri_catatan_bayi["vis_63"],30,50,$d2["vis_63"],"",
			"f_vis_64",$visit_ri_catatan_bayi["vis_64"],30,50,$d2["vis_64"],"","f_vis_65",$visit_ri_catatan_bayi["vis_65"],30,50,$d2["vis_65"],"",$ext);
			$f->text("f_vis_66",$visit_ri_catatan_bayi["vis_66"],30,50,$d2["vis_66"],$ext);
			$f->text("f_vis_67",$visit_ri_catatan_bayi["vis_67"],30,50,$d2["vis_67"],$ext);
			$f->textarea("f_vis_68",$visit_ri_catatan_bayi["vis_68"] ,1, $visit_ri_catatan_bayi["vis_68"."W"],$d2["vis_68"],$ext);
			$f->calendar("f_vis_69",$visit_ri_catatan_bayi["vis_69"],15,15,$d2["vis_69"],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
			if($d2["vis_70"] != ''){
			$f->textinfo("f_vis_70",$visit_ri_catatan_bayi["vis_70"],15,15,$d2["vis_70"],"(Jam:Menit, 08:09)",$ext);
			}else{
			$f->textinfo("f_vis_70",$visit_ri_catatan_bayi["vis_70"],15,15,"00:00","(Jam:Menit, 08:09)",$ext);
			}
			$f->textinfo("f_vis_71",$visit_ri_catatan_bayi["vis_71"],10,10,$d2["vis_71"],"(Menit/Jam)",$ext);
			$f->text("f_vis_72",$visit_ri_catatan_bayi["vis_72"],50,50,$d2["vis_72"],$ext);
			$f->text("f_vis_73",$visit_ri_catatan_bayi["vis_73"],50,50,$d2["vis_73"],$ext);
			$f->textinfo("f_vis_74",$visit_ri_catatan_bayi["vis_74"],10,10,$d2["vis_74"],"/Gram",$ext);
			$f->textinfo("f_vis_75",$visit_ri_catatan_bayi["vis_75"],10,10,$d2["vis_75"],"Cm",$ext);
			$f->textinfo("f_vis_76",$visit_ri_catatan_bayi["vis_76"],10,10,$d2["vis_76"],"Cm",$ext);
			$f->textinfo("f_vis_77",$visit_ri_catatan_bayi["vis_77"],10,10,$d2["vis_77"],"Cm",$ext);
			$f->textinfo("f_vis_78",$visit_ri_catatan_bayi["vis_78"],10,10,$d2["vis_78"],"Cm",$ext);
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			//unset($_SESSION["SELECT_EMP"]);
			//unset($_SESSION["SELECT_EMP2"]);
			echo"</div>";
			
    	
    }
    
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
   		
} 
  
?>
