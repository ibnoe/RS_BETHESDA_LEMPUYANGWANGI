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
$PID = "p_pemakaian_alat_keperawatan";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/visit_setting.php");
$tglhariini = date("d-m-Y", time());
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
$_GET["mPOLI"]=$setting_ri["pemakaian_alat_keperawatan"];
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

	$tab_disabled = array("pemeriksaan"=>true, "layanan"=>true, "icd"=>true, "riwayat"=>true,"riwayat_klinik"=>true, "konsultasi"=>true);
	if ($_GET["act"] == "del" ) {
	$tab_disabled = array("pemeriksaan"=>false, "layanan"=>false, "icd"=>false, "riwayat"=>false,"riwayat_klinik"=>false, "konsultasi"=>false);
	$tab_disabled[$_GET["sub"]] = true;
	$tab_disabled[$_POST["sub"]] = true;
	}
	$T = new TabBar();
	$T->addTab("$SC?p=$PID&list=pemeriksaan&rg1=" . $_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr ", "Input Data"	, $tab_disabled["pemeriksaan"]);
	//$T->addTab("$SC?p=$PID&list=layanan&rg1=" . $_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr&sub=layanan", "layanan / Tindakan"	, $tab_disabled["layanan"]);
	$T->addTab("$SC?p=$PID&list=icd&rg1=" . $_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr&sub=icd", "Pilih I C D"	, $tab_disabled["icd"]);
	$T->addTab("$SC?p=$PID&list=riwayat&rg1=" . $_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr", "Riwayat Pemakaian Alat dan Tindakan Keperawatan"	, $tab_disabled["riwayat"]);
	$T->addTab("$SC?p=$PID&list=riwayat_klinik&rg1=" . $_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr", "Riwayat Medis"	, $tab_disabled["riwayat_klinik"]);
	//$T->addTab("$SC?p=$PID&list=konsultasi&rg1={$_GET["rg1"]}&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr", "Konsultasi"	, $tab_disabled["konsultasi"]);

if ($reg > 0) {
	$r1 = pg_query($con,
	"select tdesc from rs00001 where tt='LRI' and tc='{$_GET["ri"]}'");
	$n1 = pg_num_rows($r1);
	if($n1 > 0) $d1 = pg_fetch_object($r1);
	pg_free_result($r1);
	
	title_print("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  $d1->tdesc");
                title_excel("p_pemakaian_alat_keperawatan&tblstart=".$_GET['tblstart']."&list=".$_GET['list']."&rg1=".$_GET['rg1']."&ri=".$_GET['ri']."&act=".$_GET['act']."&mr=".$_GET['mr']."&rg=".$_GET['rg']."&oid=".$_GET['oid']."");

    
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
		$f->text("<b>"."NRP/NIP",$d->nrp_nip);
		//$f->text("Kedatangan",$d->datang);
		$f->execute();
		echo "</td><td align=left valign=top>";
		$f = new ReadOnlyForm();
		//$f->text("<b>"."Golongan Darah", $d->gol_darah);
		$f->text("<b>"."Pangkat", $d->pangkat_gol);
		$f->text("<b>"."Kesatuan",$d->kesatuan);
		$f->text("<b>"."Umur", $umur);
		
		$f->execute();
		echo "</td><td align=left valign=top>";
		$f = new ReadOnlyForm();
		//$f->text("<b>"."Agama",$d->agama);
		$f->text("<b>"."Seks",$d->jenis_kelamin);
		$f->text("<b>"."Tanggal Masuk",$d->tgl_masuk);
		//$f->text("<b>"."Tanggal Keluar",$d->tgl_keluar);
		$f->text("<b>"."Ruang",$d->bangsal);
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
        $f = new Form("actions/p_alat_keperawatan.insert.php");
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
                    Array($l["id"], $l["desc"],$l["kate"], "<A HREF='$SC?p=$PID&list=icd&rg1={$_GET["rg1"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=".$_GET["mr"]."&del-icd=$k&httpHeader=1'>".icon("del-left")."</A>"), Array("CENTER", "LEFT", "LEFT","CENTER")
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
        
        echo "<form name='Form9' action='actions/p_alat_keperawatan.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<input type=hidden name=rg1 value='".$_GET["rg1"]."'>";
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
                        "<A HREF='$SC?p=$PID&list=layanan&rg1={$_GET["rg1"]}&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=".$_GET["mr"]."&del=$k&httpHeader=1'>".icon("del-left")."</A>"),
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
        echo "<form name='Form10' action='actions/p_alat_keperawatan.insert.php' method=POST>";
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
				$sql = 	"select a.*,to_char(a.tanggal_reg,'DD MON YYYY HH24:MI:SS')as tanggal_reg,f.layanan,l.bangsal ".
						"from c_visit_ri a ". 
						"left join rsv0002 c on a.no_reg=c.id ".
						"left join rs00006 d on d.id = a.no_reg ".
						"left join rs00008 e on e.no_reg = a.no_reg ".
						"left join rs00034 f on 'f.id' = e.item_id ".
						//"left join rs00017 g on a.vis_1 = g.id ".
						//"left join rs00017 h on a.vis_2 = h.id ".
						"join rs00010 as i on a.no_reg = i.no_reg ".
    					"join rs00012 as j on i.bangsal_id = j.id ".
						"join rs00012 as k on k.hierarchy = substr(j.hierarchy,1,6) || '000000000' ".
						"join rs00012 as l on l.hierarchy = substr(j.hierarchy,1,3) || '000000000000' ".
						"where a.no_reg='{$_GET['rg']}' and a.id_ri= '{$_GET["mPOLI"]}' and a.tanggal_reg ='{$_GET["tgl"]}' ";
				$r = pg_query($con,$sql);
				$n = pg_num_rows($r);
			    if($n > 0) $d = pg_fetch_array($r);
			    pg_free_result($r);
				//echo $sql;exit;			
			    $_GET['id'] = $_GET['rg'] ;	
	 			
			echo"<div class=box>";
			echo "<table width='100%' border='0'><tr><td colspan='3'>";
			echo"<div class=form_subtitle>PEMAKAIAN ALAT DAN TINDAKAN KEPERAWATAN</div>";
			//echo "</td></tr>";
    		echo "<tr><td  valign=top>";
    		$f = new ReadOnlyForm();
    		$f->title1("<U>ALAT</U>");
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_1"],$d[4]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_2"],$d[5] );
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_3"],$d[6]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_4"],$d[7]);
			$f->text("Cairan Infus","");
			$f->text("&nbsp;&nbsp;&nbsp;a.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_5"],$d[8]);
			$f->text("&nbsp;&nbsp;&nbsp;b.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_6"],$d[9]);
			$f->text("&nbsp;&nbsp;&nbsp;c.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_7"],$d[10]);
			$f->text("&nbsp;&nbsp;&nbsp;d.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_8"],$d[11]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_9"],$d[12]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_10"],$d[13]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_11"],$d[14]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_12"],$d[15]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_13"],$d[16]);	
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_14"],$d[17] );
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_15"],$d[18]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_16"],$d[19]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_17"],$d[20]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_18"],$d[21]);
			$f->execute();
			echo "</td><td valign=top>";
    		$f = new ReadOnlyForm();
			
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_19"],$d[22]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_20"],$d[23]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_21"],$d[24]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_22"],$d[25]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_23"],$d[26]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_24"],$d[27]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_48"],$d[51]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_49"],$d[52]);
			
			$f->title1("<U>TINDAKAN</U>");
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_25"],$d[28]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_26"],$d[29]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_27"],$d[30]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_28"],$d[31]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_29"],$d[32]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_30"],$d[33]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_31"],$d[34]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_32"],$d[35]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_33"],$d[36]);
			$f->text("Injectie","");
			$f->text("&nbsp;&nbsp;&nbsp;a.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_34"],$d[37]);
			$f->execute();
			echo "</td><td valign=top>";
    		$f = new ReadOnlyForm();
			$f->text("&nbsp;&nbsp;&nbsp;b.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_35"],$d[38]);
			$f->text("&nbsp;&nbsp;&nbsp;c.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_36"],$d[39]);
			$f->text("&nbsp;&nbsp;&nbsp;d.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_37"],$d[40]);
			$f->title1("");
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_38"],$d[41]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_39"],$d[42]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_40"],$d[43]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_41"],$d[44]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_42"],$d[45]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_43"],$d[46]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_44"],$d[47]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_45"],$d[48]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_46"],$d[49]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_47"],$d[50]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_50"],$d[53]);
			$f->text($visit_ri_pemakaian_alat_keperawatan["vis_51"],$d[54]);		
			$f->execute();	
    		echo "</td></tr>";
  			echo "<tr><td colspan='3'>";
  			echo "<br>";
  			include(rm_tindakan3);
  			
  			echo "</td></tr></table>";

			
			}else {
				echo"<div align=center class=form_subtitle1>RIWAYAT PEMAKAIAN ALAT DAN TINDAKAN KEPERAWATAN</div>";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
				 $sql = "SELECT A.NO_REG,A.VIS_1,A.VIS_2,A.VIS_3,A.VIS_4,A.TANGGAL_REG,'DUMMY' ". 
					   "FROM C_VISIT_RI A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   "WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_ri = '{$_GET["mPOLI"]}' 
                                           group by A.NO_REG,A.VIS_1,A.VIS_2,A.VIS_3,A.VIS_4,A.TANGGAL_REG ORDER BY A.VIS_1 ";
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	$t->ColHidden[6]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("NO REGISTRASI","TANGGAL","INFUS SET","TRANSFUSI SET","ABOCATH","DETAIL");
			   	$t->ColAlign = array("center","center","left","left","left","center","center");
				$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat&rg1={$_GET["rg1"]}&ri=".$_GET["mPOLI"]."&act=detail&mr=".$_GET["mr"]."&rg=<#0#>&tgl=<#5#>'>".icon("view","View")."</A>";	
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
						left join rs00034 f on f.id::text = e.item_id::text
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
				$sql =   "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS')AS WAKTU,C.TDESC,D.NAMA,A.OID ". 
					   "FROM C_VISIT A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG= B.ID  ".
					   "LEFT JOIN RS00001 C ON A.ID_POLI = CAST(C.TC as numeric) AND C.TT='LYN'".
					   "LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID ".
					   "WHERE B.MR_NO = '".$_GET["mr"]."'
                                            group by A.NO_REG,A.TANGGAL_REG,C.TDESC,D.NAMA,A.OID ";
					
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
    	echo "<br>";
    		$sql2 =	"SELECT A.*,E.BANGSAL_ID,H.BANGSAL FROM C_VISIT_RI A 
    				JOIN RS00010 AS E ON A.NO_REG = E.NO_REG 
    				JOIN RS00012 AS F ON E.BANGSAL_ID = F.ID 
					JOIN RS00012 AS G ON G.hierarchy = substr(F.hierarchy,1,6) || '000000000' 
					JOIN RS00012 AS H ON H.hierarchy = substr(F.hierarchy,1,3) || '000000000000'
    				WHERE A.ID_RI='{$_GET["ri"]}' AND A.NO_REG='{$_GET["rg1"]}'";
			$r=pg_query($con,$sql2);
			$n = pg_num_rows($r);
			if($n > 0) $d2 = pg_fetch_array($r);
			pg_free_result($r);
			
			$sql3 = "select to_char(a.ts_check_in,'dd Mon yyyy')as tgl_masuk,to_char(a.ts_check_in,'HH24:MI:SS')as jam_masuk,".
						"to_char(a.ts_calc_stop,'dd Mon yyyy')as tgl_keluar,to_char(a.ts_calc_stop,'HH24:MI:SS')as jam_keluar, ".
						"extract(day from case when a.ts_calc_stop is null then current_timestamp else a.ts_calc_stop end - a.ts_calc_start)as jumlah_hari ".
						"from rs00010 a where a.no_reg='{$_GET["rg"]}'";
			$r3 = pg_query($con,$sql3);
			$n3 = pg_num_rows($r3);
			if ($n3 >0) $d3 = pg_fetch_object($r3);
			pg_free_result($r3);
				
				    
				if ($_GET['act'] == "edit") {
						echo "<font color='#000000' size='2'> Edit Pemakaian Alat/Tindakan Keperawatan</font>";
						$f = new Form("actions/p_alat_keperawatan.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d2["no_reg"]);
					    $f->hidden("f_tanggal_reg",$_GET["tmp48"]);
						$f->hidden("list","pemeriksaan");
						$f->hidden("rawatan",$rawatan);
						$f->hidden("f_id_rujukan",$d->poli);
					    $f->hidden("mr",$_GET["mr"]);
					    $f->hidden("f_id_ri",$_GET["ri"]);
					    $f->hidden("f_user_id",$_SESSION[uid]);
					    $f->hidden("rg1",$_GET[rg1]);
					 
					    echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
			$f->calendar("f_vis_1","Tanggal",15,15,$_GET["tmp1"],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
			$f->title1("<U>I. ALAT</U>");
			$f->text("f_vis_2",$visit_ri_pemakaian_alat_keperawatan["vis_2"],15,50,$_GET["tmp2"],$ext);
			$f->text("f_vis_3",$visit_ri_pemakaian_alat_keperawatan["vis_3"],15,50,$_GET["tmp3"],$ext);
			$f->text("f_vis_4",$visit_ri_pemakaian_alat_keperawatan["vis_4"],15,50,$_GET["tmp4"],$ext);
			$f->title2("Cairan Infus");
			$f->text4("f_vis_5","&nbsp;&nbsp;&nbsp;&nbsp;a.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_5"],15,50,$_GET["tmp5"],
					  "f_vis_6","&nbsp;&nbsp;&nbsp;b.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_6"],15,50,$_GET["tmp6"],
					  "f_vis_7","&nbsp;&nbsp;&nbsp;&nbsp;c.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_7"],15,50,$_GET["tmp7"],
					  "f_vis_8","&nbsp;&nbsp;&nbsp;d.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_8"],15,50,$_GET["tmp8"],$ext);
			$f->text("f_vis_9","&nbsp;&nbsp;&nbsp;&nbsp;e.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_9"],15,50,$_GET["tmp9"],$ext);
			
			$f->text4("f_vis_10",$visit_ri_pemakaian_alat_keperawatan["vis_10"],15,50,$_GET["tmp10"],
					  "f_vis_11","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_11"],15,50,$_GET["tmp11"],
					  "f_vis_12",$visit_ri_pemakaian_alat_keperawatan["vis_12"],15,50,$_GET["tmp12"],
					  "f_vis_13","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_13"],15,50,$_GET["tmp13"],$ext);
			
			$f->text4("f_vis_14",$visit_ri_pemakaian_alat_keperawatan["vis_14"],15,50,$_GET["tmp14"],
					  "f_vis_15","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_15"],15,50,$_GET["tmp15"],
					  "f_vis_16",$visit_ri_pemakaian_alat_keperawatan["vis_16"],15,50,$_GET["tmp16"],
					  "f_vis_17","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_17"],15,50,$_GET["tmp17"],$ext);
			
			$f->text4("f_vis_18",$visit_ri_pemakaian_alat_keperawatan["vis_18"],15,50,$_GET["tmp18"],
					  "f_vis_19","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_19"],15,50,$_GET["tmp19"],
					  "f_vis_20",$visit_ri_pemakaian_alat_keperawatan["vis_20"],15,50,$_GET["tmp20"],
					  "f_vis_21","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_21"],15,50,$_GET["tmp21"],$ext);
			$f->text4("f_vis_22",$visit_ri_pemakaian_alat_keperawatan["vis_22"],15,50,$_GET["tmp22"],
					  "f_vis_23","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_23"],15,50,$_GET["tmp23"],
					  "f_vis_24",$visit_ri_pemakaian_alat_keperawatan["vis_24"],15,50,$_GET["tmp24"],
					  "f_vis_48","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_48"],15,50,$_GET["tmp49"],$ext);
			$f->text("f_vis_49",$visit_ri_pemakaian_alat_keperawatan["vis_49"],15,50,$_GET["tmp50"],$ext);
			$f->title1("<U>II. TINDAKAN</U>");
			$f->text4("f_vis_25",$visit_ri_pemakaian_alat_keperawatan["vis_25"],15,50,$_GET["tmp25"],
					  "f_vis_26","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_26"],15,50,$_GET["tmp26"],
					  "f_vis_27",$visit_ri_pemakaian_alat_keperawatan["vis_27"],15,50,$_GET["tmp27"],
					  "f_vis_28","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_28"],15,50,$_GET["tmp28"],$ext);
			
			$f->text4("f_vis_29",$visit_ri_pemakaian_alat_keperawatan["vis_29"],15,50,$_GET["tmp29"],
					  "f_vis_30","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_30"],15,50,$_GET["tmp30"],
					  "f_vis_31",$visit_ri_pemakaian_alat_keperawatan["vis_31"],15,50,$_GET["tmp31"],
					  "f_vis_32","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_32"],15,50,$_GET["tmp32"],$ext);
			$f->text("f_vis_33",$visit_ri_pemakaian_alat_keperawatan["vis_33"],15,50,$_GET["tmp33"],$ext);
			$f->text("f_vis_43",$visit_ri_pemakaian_alat_keperawatan["vis_43"],15,50,$_GET["tmp43"],$ext);
			$f->title2("Injectie");
			$f->text4("f_vis_37","&nbsp;&nbsp;&nbsp;&nbsp;a.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_37"],15,50,$_GET["tmp37"],
					  "f_vis_35","&nbsp;&nbsp;&nbsp;b.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_35"],15,50,$_GET["tmp35"],
					  "f_vis_36","&nbsp;&nbsp;&nbsp;&nbsp;c.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_36"],15,50,$_GET["tmp36"],
					  "f_vis_34","&nbsp;&nbsp;&nbsp;d.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_34"],15,50,$_GET["tmp34"],$ext);
			$f->text4("f_vis_38",$visit_ri_pemakaian_alat_keperawatan["vis_38"],15,50,$_GET["tmp38"],
					  "f_vis_39","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_39"],15,50,$_GET["tmp39"],
					  "f_vis_40",$visit_ri_pemakaian_alat_keperawatan["vis_40"],15,50,$_GET["tmp40"],
					  "f_vis_41","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_41"],15,50,$_GET["tmp41"],$ext);
			$f->text4("f_vis_42",$visit_ri_pemakaian_alat_keperawatan["vis_42"],15,50,$_GET["tmp42"],
					  "f_vis_46","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_46"],15,50,$_GET["tmp46"],
					  "f_vis_44",$visit_ri_pemakaian_alat_keperawatan["vis_44"],15,50,$_GET["tmp44"],
					  "f_vis_45","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_45"],15,50,$_GET["tmp45"],$ext);
			$f->text("f_vis_47",$visit_ri_pemakaian_alat_keperawatan["vis_47"],15,50,$_GET["tmp47"],$ext);
			$f->text("f_vis_50",$visit_ri_pemakaian_alat_keperawatan["vis_50"],15,50,$_GET["tmp51"],$ext);
			$f->text("f_vis_51",$visit_ri_pemakaian_alat_keperawatan["vis_51"],15,50,$_GET["tmp52"],$ext);
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			//unset($_SESSION["SELECT_EMP"]);
			//unset($_SESSION["SELECT_EMP2"]);
			echo"</div>";
			echo "<div align=right><b>RM 08.b </b></div>";  
					   
				}elseif ($_GET['act'] == "tambah") {
					echo "<font color='#000000' size='2'>Tambah Pemakaian Alat/Tindakan Keperawatan</font>";
					$f = new Form("actions/p_alat_keperawatan.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("f_id_rujukan",$d->poli);
					$f->hidden("list","pemeriksaan");
					$f->hidden("rawatan",$rawatan);
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_ri",$_GET["ri"]);
					$f->hidden("f_user_id",$_SESSION[uid]);
					$f->hidden("rg1",$_GET[rg1]);
					
			
				    
				//$f->calendar("tanggal_reg","Tanggal Registrasi",15,15,$d2[1],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
					
			echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
			$f->calendar("f_vis_1","Tanggal",15,15,"","Form2","icon/calendar.gif","Pilih Tanggal",$ext);
			$f->title1("<U>I. ALAT</U>");
			$f->text("f_vis_2",$visit_ri_pemakaian_alat_keperawatan["vis_2"],15,50,"",$ext);
			$f->text("f_vis_3",$visit_ri_pemakaian_alat_keperawatan["vis_3"],15,50,"",$ext);
			$f->text("f_vis_4",$visit_ri_pemakaian_alat_keperawatan["vis_4"],15,50,"",$ext);
			$f->title2("Cairan Infus");
			$f->text4("f_vis_5","&nbsp;&nbsp;&nbsp;&nbsp;a.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_5"],15,50,"",
					  "f_vis_6","&nbsp;&nbsp;&nbsp;b.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_6"],15,50,"",
					  "f_vis_7","&nbsp;&nbsp;&nbsp;&nbsp;c.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_7"],15,50,"",
					  "f_vis_8","&nbsp;&nbsp;&nbsp;d.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_8"],15,50,"",$ext);
			$f->text("f_vis_9","&nbsp;&nbsp;&nbsp;&nbsp;e.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_9"],15,50,"",$ext);
			
			$f->text4("f_vis_10",$visit_ri_pemakaian_alat_keperawatan["vis_10"],15,50,"",
					  "f_vis_11","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_11"],15,50,"",
					  "f_vis_12",$visit_ri_pemakaian_alat_keperawatan["vis_12"],15,50,"",
					  "f_vis_13","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_13"],15,50,"",$ext);
			
			$f->text4("f_vis_14",$visit_ri_pemakaian_alat_keperawatan["vis_14"],15,50,"",
					  "f_vis_15","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_15"],15,50,"",
					  "f_vis_16",$visit_ri_pemakaian_alat_keperawatan["vis_16"],15,50,"",
					  "f_vis_17","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_17"],15,50,"",$ext);
			
			$f->text4("f_vis_18",$visit_ri_pemakaian_alat_keperawatan["vis_18"],15,50,"",
					  "f_vis_19","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_19"],15,50,"",
					  "f_vis_20",$visit_ri_pemakaian_alat_keperawatan["vis_20"],15,50,"",
					  "f_vis_21","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_21"],15,50,"",$ext);
			$f->text4("f_vis_22",$visit_ri_pemakaian_alat_keperawatan["vis_22"],15,50,"",
					  "f_vis_23","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_23"],15,50,"",
					  "f_vis_24",$visit_ri_pemakaian_alat_keperawatan["vis_24"],15,50,"",
					  "f_vis_48","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_48"],15,50,"",$ext);
			$f->text("f_vis_49",$visit_ri_pemakaian_alat_keperawatan["vis_49"],15,50,"",$ext);
			$f->title1("<U>II. TINDAKAN</U>");
			$f->text4("f_vis_25",$visit_ri_pemakaian_alat_keperawatan["vis_25"],15,50,"",
					  "f_vis_26","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_26"],15,50,"",
					  "f_vis_27",$visit_ri_pemakaian_alat_keperawatan["vis_27"],15,50,"",
					  "f_vis_28","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_28"],15,50,"",$ext);
			
			$f->text4("f_vis_29",$visit_ri_pemakaian_alat_keperawatan["vis_29"],15,50,"",
					  "f_vis_30","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_30"],15,50,"",
					  "f_vis_31",$visit_ri_pemakaian_alat_keperawatan["vis_31"],15,50,"",
					  "f_vis_32","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_32"],15,50,"",$ext);
			$f->text("f_vis_33",$visit_ri_pemakaian_alat_keperawatan["vis_33"],15,50,"",$ext);
			$f->text("f_vis_43",$visit_ri_pemakaian_alat_keperawatan["vis_43"],15,50,"",$ext);
			$f->title2("Injectie");
			$f->text4("f_vis_37","&nbsp;&nbsp;&nbsp;&nbsp;a.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_37"],15,50,"",
					  "f_vis_35","&nbsp;&nbsp;&nbsp;b.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_35"],15,50,"",
					  "f_vis_36","&nbsp;&nbsp;&nbsp;&nbsp;c.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_36"],15,50,"",
					  "f_vis_34","&nbsp;&nbsp;&nbsp;d.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_34"],15,50,"",$ext);
			$f->text4("f_vis_38",$visit_ri_pemakaian_alat_keperawatan["vis_38"],15,50,"",
					  "f_vis_39","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_39"],15,50,"",
					  "f_vis_40",$visit_ri_pemakaian_alat_keperawatan["vis_40"],15,50,"",
					  "f_vis_41","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_41"],15,50,"",$ext);
			$f->text4("f_vis_42",$visit_ri_pemakaian_alat_keperawatan["vis_42"],15,50,"",
					  "f_vis_46","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_46"],15,50,"",
					  "f_vis_44",$visit_ri_pemakaian_alat_keperawatan["vis_44"],15,50,"",
					  "f_vis_45","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_45"],15,50,"",$ext);
			$f->text("f_vis_47",$visit_ri_pemakaian_alat_keperawatan["vis_47"],15,50,"",$ext);
			$f->text("f_vis_50",$visit_ri_pemakaian_alat_keperawatan["vis_50"],15,50,"",$ext);
			$f->text("f_vis_51",$visit_ri_pemakaian_alat_keperawatan["vis_51"],15,50,"",$ext);
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			//unset($_SESSION["SELECT_EMP"]);
			//unset($_SESSION["SELECT_EMP2"]);
			echo"</div>";
			echo "<div align=right><b>RM.08.a</b></div>";
    		}
				else {
					if($n > 0) {
						$ext= "disabled";
					}else {
						$ext = "";
					}
				//---------------------------------------------------------------------------------			
						
					$f = new Form("actions/p_alat_keperawatan.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("f_id_rujukan",$d->poli);
					$f->hidden("list","pemeriksaan");
					$f->hidden("rawatan",$rawatan);
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_ri",$_GET["ri"]);
					$f->hidden("f_user_id",$_SESSION[uid]);
					$f->hidden("bangsal_id",$d2["bangsal_id"]);
					$f->hidden("f_vis_4",$d3->tgl_masuk);
					$f->hidden("f_vis_5",$d3->jam_masuk);
					$f->hidden("f_vis_6",$d3->tgl_keluar);
					$f->hidden("f_vis_7",$d3->jam_keluar);
					$f->hidden("f_vis_10",$d3->jumlah_hari);
					$f->hidden("rg1",$_GET[rg1]);
				    
			$sql = 	"SELECT A.NO_REG,A.VIS_1,A.VIS_2,A.VIS_3,A.VIS_4,A.VIS_5,A.VIS_6,A.VIS_7,A.VIS_8,A.VIS_9,A.VIS_10 ".
					",A.VIS_11,A.VIS_12,A.VIS_13,A.VIS_14,A.VIS_15,A.VIS_16,A.VIS_17,A.VIS_18,A.VIS_19,A.VIS_20,A.VIS_21".
					",A.VIS_22,A.VIS_23,A.VIS_24,A.VIS_25,A.VIS_26,A.VIS_27,A.VIS_28,A.VIS_29,A.VIS_30,A.VIS_31,A.VIS_32".
					",A.VIS_33,A.VIS_34,A.VIS_35,A.VIS_36,A.VIS_37,A.VIS_38,A.VIS_39,A.VIS_40,A.VIS_41,A.VIS_42,A.VIS_43".
					",A.VIS_44,A.VIS_45,A.VIS_46,A.VIS_47,A.TANGGAL_REG,A.VIS_48,A.VIS_49,A.VIS_50,A.VIS_51,'DUMMY'". 
					"FROM C_VISIT_RI A ".
					"LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					"WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_ri = '{$_GET["mPOLI"]}' ORDER BY A.VIS_1 ";
					   
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			    $t->ColHidden[6]=true;$t->ColHidden[7]=true;$t->ColHidden[8]=true;$t->ColHidden[9]=true;$t->ColHidden[10]=true;$t->ColHidden[11]=true;
			    $t->ColHidden[12]=true;$t->ColHidden[13]=true;$t->ColHidden[14]=true;$t->ColHidden[15]=true;$t->ColHidden[16]=true;$t->ColHidden[17]=true;
			    $t->ColHidden[18]=true;$t->ColHidden[19]=true;$t->ColHidden[20]=true;$t->ColHidden[21]=true;$t->ColHidden[22]=true;$t->ColHidden[23]=true;
			    $t->ColHidden[24]=true;$t->ColHidden[25]=true;$t->ColHidden[26]=true;$t->ColHidden[27]=true;$t->ColHidden[28]=true;$t->ColHidden[29]=true;
			    $t->ColHidden[30]=true;$t->ColHidden[31]=true;$t->ColHidden[32]=true;$t->ColHidden[33]=true;$t->ColHidden[34]=true;$t->ColHidden[35]=true;
			    $t->ColHidden[36]=true;$t->ColHidden[37]=true;$t->ColHidden[38]=true;$t->ColHidden[39]=true;$t->ColHidden[40]=true;$t->ColHidden[41]=true;
			    $t->ColHidden[42]=true;$t->ColHidden[43]=true;$t->ColHidden[44]=true;$t->ColHidden[45]=true;$t->ColHidden[46]=true;$t->ColHidden[47]=true;
			    $t->ColHidden[48]=true;$t->ColHidden[49]=true;$t->ColHidden[50]=true;$t->ColHidden[51]=true;$t->ColHidden[52]=true;$t->ColHidden[53]=true;
			    $t->RowsPerPage = 5;
			    $t->ColHeader = array("NO REGISTRASI","TANGGAL","INFUS SET","TRANSFUSI SET","ABOCATH","");
			   	$t->ColAlign[0] = "center";$t->ColAlign[1] = "center";$t->ColAlign[2] = "left";$t->ColAlign[3] = "left";
			   	$t->ColAlign[4] = "left";$t->ColAlign[53] = "center";
				$t->ColFormatHtml[53] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=pemeriksaan&rg1={$_GET["rg1"]}&ri=".$_GET["mPOLI"]."&act=edit&mr=".$_GET["mr"]."&rg=<#0#>
				&tmp1=<#1#>&tmp2=<#2#>&tmp3=<#3#>&tmp4=<#4#>&tmp5=<#5#>&tmp6=<#6#>&tmp7=<#7#>&tmp8=<#8#>&tmp9=<#9#>&tmp10=<#10#>
				&tmp11=<#11#>&tmp12=<#12#>&tmp13=<#13#>&tmp14=<#14#>&tmp15=<#15#>&tmp16=<#16#>&tmp17=<#17#>&tmp18=<#18#>&tmp19=<#19#>&tmp20=<#20#>
				&tmp21=<#21#>&tmp22=<#22#>&tmp23=<#23#>&tmp24=<#24#>&tmp25=<#25#>&tmp26=<#26#>&tmp27=<#27#>&tmp28=<#28#>&tmp29=<#29#>&tmp30=<#30#>
				&tmp31=<#31#>&tmp32=<#32#>&tmp33=<#33#>&tmp34=<#34#>&tmp35=<#35#>&tmp36=<#36#>&tmp37=<#37#>&tmp38=<#38#>&tmp39=<#39#>&tmp40=<#40#>
				&tmp41=<#41#>&tmp42=<#42#>&tmp43=<#43#>&tmp44=<#44#>&tmp45=<#45#>&tmp46=<#46#>&tmp47=<#47#>&tmp48=<#48#>&tmp49=<#49#>&tmp50=<#50#>&tmp51=<#51#>&tmp52=<#52#>
				'>".icon("edit","Edit")."</A>"."&nbsp;&nbsp;"."<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat&rg1={$_GET["rg1"]}&ri=".$_GET["mPOLI"]."&act=detail&mr=".$_GET["mr"]."&rg=<#0#>&tgl=<#48#>'>".icon("view","Detail")."</A>";	
				
				$t->execute();
				
				echo "<br>";
				echo "<div align=left><input type=button value=' Tambah ' OnClick=\"window.location = './index2.php?p=$PID&rg1={$_GET["rg1"]}&rg=$rg&mr={$_GET['mr']}&ri={$_GET["ri"]}&act=tambah';\">\n";
			echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
			if ($d2["vis_1"]!= ''){
				$f->calendar("f_vis_1","Tanggal",15,15,$d2["vis_1"],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
			}else{
				$f->calendar("f_vis_1","Tanggal",15,15,$tglhariini,"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
			}
			
			$f->title1("<U>I. ALAT</U>");
			$f->text("f_vis_2",$visit_ri_pemakaian_alat_keperawatan["vis_2"],15,50,$d2["vis_2"],$ext);
			$f->text("f_vis_3",$visit_ri_pemakaian_alat_keperawatan["vis_3"],15,50,$d2["vis_3"],$ext);
			$f->text("f_vis_4",$visit_ri_pemakaian_alat_keperawatan["vis_4"],15,50,$d2["vis_4"],$ext);
			$f->title2("Cairan Infus");
			$f->text4("f_vis_5","&nbsp;&nbsp;&nbsp;&nbsp;a.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_5"],15,50,$d2["vis_5"],
					  "f_vis_6","&nbsp;&nbsp;&nbsp;b.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_6"],15,50,$d2["vis_6"],
					  "f_vis_7","&nbsp;&nbsp;&nbsp;&nbsp;c.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_7"],15,50,$d2["vis_7"],
					  "f_vis_8","&nbsp;&nbsp;&nbsp;d.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_8"],15,50,$d2["vis_8"],$ext);
			$f->text("f_vis_9","&nbsp;&nbsp;&nbsp;&nbsp;e.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_9"],15,50,$d2["vis_9"],$ext);
			
			$f->text4("f_vis_10",$visit_ri_pemakaian_alat_keperawatan["vis_10"],15,50,$d2["vis_10"],
					  "f_vis_11","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_11"],15,50,$d2["vis_11"],
					  "f_vis_12",$visit_ri_pemakaian_alat_keperawatan["vis_12"],15,50,$d2["vis_12"],
					  "f_vis_13","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_13"],15,50,$d2["vis_13"],$ext);
			
			$f->text4("f_vis_14",$visit_ri_pemakaian_alat_keperawatan["vis_14"],15,50,$d2["vis_14"],
					  "f_vis_15","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_15"],15,50,$d2["vis_15"],
					  "f_vis_16",$visit_ri_pemakaian_alat_keperawatan["vis_16"],15,50,$d2["vis_16"],
					  "f_vis_17","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_17"],15,50,$d2["vis_17"],$ext);
			
			$f->text4("f_vis_18",$visit_ri_pemakaian_alat_keperawatan["vis_18"],15,50,$d2["vis_18"],
					  "f_vis_19","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_19"],15,50,$d2["vis_19"],
					  "f_vis_20",$visit_ri_pemakaian_alat_keperawatan["vis_20"],15,50,$d2["vis_20"],
					  "f_vis_21","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_21"],15,50,$d2["vis_21"],$ext);
			$f->text4("f_vis_22",$visit_ri_pemakaian_alat_keperawatan["vis_22"],15,50,$d2["vis_22"],
					  "f_vis_23","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_23"],15,50,$d2["vis_23"],
					  "f_vis_24",$visit_ri_pemakaian_alat_keperawatan["vis_24"],15,50,$d2["vis_24"],
					  "f_vis_48","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_48"],15,50,$d2["vis_48"],$ext);
			$f->text("f_vis_49",$visit_ri_pemakaian_alat_keperawatan["vis_49"],15,50,$d2["vis_49"],$ext);
			$f->title1("<U>II. TINDAKAN</U>");
			$f->text4("f_vis_25",$visit_ri_pemakaian_alat_keperawatan["vis_25"],15,50,$d2["vis_25"],
					  "f_vis_26","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_26"],15,50,$d2["vis_26"],
					  "f_vis_27",$visit_ri_pemakaian_alat_keperawatan["vis_27"],15,50,$d2["vis_27"],
					  "f_vis_28","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_28"],15,50,$d2["vis_28"],$ext);
			
			$f->text4("f_vis_29",$visit_ri_pemakaian_alat_keperawatan["vis_29"],15,50,$d2["vis_29"],
					  "f_vis_30","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_30"],15,50,$d2["vis_30"],
					  "f_vis_31",$visit_ri_pemakaian_alat_keperawatan["vis_31"],15,50,$d2["vis_31"],
					  "f_vis_32","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_32"],15,50,$d2["vis_32"],$ext);
			$f->text("f_vis_33",$visit_ri_pemakaian_alat_keperawatan["vis_33"],15,50,$d2["vis_33"],$ext);
			$f->text("f_vis_43",$visit_ri_pemakaian_alat_keperawatan["vis_43"],15,50,$d2["vis_43"],$ext);
			$f->title2("Injectie");
			$f->text4("f_vis_37","&nbsp;&nbsp;&nbsp;&nbsp;a.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_37"],15,50,$d2["vis_37"],
					  "f_vis_35","&nbsp;&nbsp;&nbsp;b.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_35"],15,50,$d2["vis_35"],
					  "f_vis_36","&nbsp;&nbsp;&nbsp;&nbsp;c.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_36"],15,50,$d2["vis_36"],
					  "f_vis_34","&nbsp;&nbsp;&nbsp;d.&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_34"],15,50,$d2["vis_34"],$ext);
			$f->text4("f_vis_38",$visit_ri_pemakaian_alat_keperawatan["vis_38"],15,50,$d2["vis_38"],
					  "f_vis_39","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_39"],15,50,$d2["vis_39"],
					  "f_vis_40",$visit_ri_pemakaian_alat_keperawatan["vis_40"],15,50,$d2["vis_40"],
					  "f_vis_41","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_41"],15,50,$d2["vis_41"],$ext);
			$f->text4("f_vis_42",$visit_ri_pemakaian_alat_keperawatan["vis_42"],15,50,$d2["vis_42"],
					  "f_vis_46","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_46"],15,50,$d2["vis_46"],
					  "f_vis_44",$visit_ri_pemakaian_alat_keperawatan["vis_44"],15,50,$d2["vis_44"],
					  "f_vis_45","&nbsp;&nbsp;&nbsp;".$visit_ri_pemakaian_alat_keperawatan["vis_45"],15,50,$d2["vis_45"],$ext);
			$f->text("f_vis_47",$visit_ri_pemakaian_alat_keperawatan["vis_47"],15,50,$d2["vis_47"],$ext);
			$f->text("f_vis_50",$visit_ri_pemakaian_alat_keperawatan["vis_50"],15,50,$d2["vis_50"],$ext);
			$f->text("f_vis_51",$visit_ri_pemakaian_alat_keperawatan["vis_51"],15,50,$d2["vis_51"],$ext);
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			//unset($_SESSION["SELECT_EMP"]);
			//unset($_SESSION["SELECT_EMP2"]);
			echo"</div>";
			echo "<div align=right><b>RM 08.b </b></div>";
    	
    }
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
