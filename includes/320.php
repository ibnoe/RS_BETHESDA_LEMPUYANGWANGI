<?php // Nugraha, Sun Apr 18 18:58:42 WIT 2004
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

$PID = "320";
$PIDmain = "370"; //$PID destination tombol back by Me 29092011
$SC = $_SERVER["SCRIPT_NAME"];
$AKSES = $_GET["tt"];

session_start();
$title_layanan = "";

if (!empty($_SESSION[gr])) {

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/visit_setting.php");

$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];


//$mr = isset($_GET["mr"])? $_GET["mr"] : $d->mr_no;
if (isset($_GET["del"])) {
    $temp = $_SESSION["layanan"];
    unset($_SESSION["layanan"]);
    foreach ($temp as $k => $v) {
        if ($k != $_GET["del"]) $_SESSION["layanan"][count($_SESSION["layanan"])] = $v;
    }
    header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["regno"]);
    exit;
} elseif (isset($_GET["del-icd"])) {
    $temp = $_SESSION["icd"];
    unset($_SESSION["icd"]);
    foreach ($temp as $k => $v) {
        if ($k != $_GET["del-icd"]) $_SESSION["icd"][count($_SESSION["icd"])] = $v;
    }
    header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["regno"] . "&sub=icd");
    exit;
} elseif (isset($_GET["del-obat"])) {
    $temp = $_SESSION["obat"];
    unset($_SESSION["obat"]);
    foreach ($temp as $k => $v) {
        if ($k != $_GET["del-obat"]) $_SESSION["obat"][count($_SESSION["obat"])] = $v;
    }
    header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["regno"] . "&sub=obat");
    exit;
} elseif (isset($_GET["del-pjm"])) {
    $temp = $_SESSION["pjm"][$_GET["del-pjm"]];
    unset($_SESSION["pjm"][$_GET["del-pjm"]]);
    foreach ($temp as $k => $v) {
        if ($k != $_GET["del-emp"])
            $_SESSION["pjm"][$_GET["del-pjm"]][count($_SESSION["pjm"][$_GET["del-pjm"]])] = $v;
    }
    header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["regno"] . "&sub=pjm");
    exit;
} elseif (isset($_GET["s2note"])) {
    $_SESSION["s2note"] = $_GET["s2note"];
    header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"] . "&sub=icd");
    exit;
} elseif (isset($_GET["obat"])) {
    $r = pg_query($con,"select * from rsv0004 where id = '".$_GET["obat"]."'");
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
	$_SESSION["obat"][$cnt]["dosis"]  = $_GET["dosis"];
	$_SESSION["obat"][$cnt]["ket_racikan"]  = $_GET["ket_racikan"];
        //$_SESSION["obat"][$cnt]["satuan"] = $d->satuan;
        unset($_SESSION["SELECT_OBAT"]);
    }
    header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"] . "&sub=obat");
    exit;
} elseif (isset($_GET["icd"])) {
    $r = pg_query($con,"select * from rsv0005 where diagnosis_code = '" . $_GET["icd"] . "'");
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
        unset($_SESSION["SELECT_ICD"]);
    }
    header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"] . "&sub=icd");
    exit;
} elseif (isset($_GET["layanan"])) {
    $r = pg_query($con,"select * from rsv0034 where id = '" . $_GET["layanan"] . "'");
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

               $t = pg_query($con,"select * from rs00034 where hierarchy like '006001007%' and golongan_tindakan_id = '$gol_tindakan'");
               $tr = pg_fetch_object($t);
               //pg_free_result($t);

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

               $t = pg_query($con,"select * from rs00034 where hierarchy like '006003006%' and golongan_tindakan_id = '$gol_tindakan'");
               $tr = pg_fetch_object($t);
               //pg_free_result($t);

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

               $t = pg_query($con,"select * from rs00034 where hierarchy like '006001007%' and golongan_tindakan_id = '$gol_tindakan'");
               $tr = pg_fetch_object($t);
               //pg_free_result($t);

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

            header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"]);
            exit;
        } elseif ($is_range) {
            $_SESSION["SELECT_LAYANAN"] = $_GET["layanan"];
            header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"] . "&jumlah=" . $_GET["jumlah"]);
            exit;
        }
    } else {
        header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"]);
        exit;
    }
}
 
title("<img src='icon/rawat-inap-2_asli.gif' align='absmiddle' >Layanan & Tindakan Medis Rawat Inap");
echo "<br>";


unset($_GET["layanan"]);
// unset($_GET["jumlah"]);

$reg = $_GET["rg"];
$reg2 = $_GET["rg"];

if ($reg > 0) {
    if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where to_number(id,'9999999999') = $reg ".
		     " ") == 0) {
                     //"and status = 'A'") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}

if ($reg > 0) {
    $r = pg_query($con,
        "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, ".
        "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, ".
        "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, ".
        "    e.alm_tetap, e.kota_tetap, e.pos_tetap, e.tlp_tetap, ".
        "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
        "    c.tdesc AS penjamin, a.no_jaminan, a.rujukan, a.rujukan_rs_id, ".
        "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
        "    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, ".
        "        CASE ".
        "            WHEN a.rawat_inap = 'I' THEN 'Rawat Inap'  ".
        "            WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan' ".
        "            ELSE 'IGD' ".
        "        END AS rawatan, ".
        "        age(a.tanggal_reg , e.tgl_lahir ) AS umur, ".
	"	case when a.rujukan = 'Y' then 'Rujukan' ".
	"	     when a.rujukan ='U' then 'Unit Lain'  else 'Non-Rujukan' ".
        "       end as datang,  ".
        "   i.tdesc as  poli ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc and h.tt = 'JDP' ".
        /* -- edited 120210
		-- mengganti type data i.tc menjadi integer
		*/
		"   left join rs00001 i on i.tc_poli = a.poli ".
        //"WHERE a.id = lpad('$reg',10,'0')");
		"WHERE a.id = '$reg'");
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
    $umur = $umure[0]." thn";


    echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("No Reg.", formatRegNo($d->id));
    $f->text("No MR", $d->mr_no);
    $f->text("Nama", $d->nama);
    /*$f->text("Pasien Dari", $d->rawat_inap == "Y" ? "Rawat Inap" : "Rawat Jalan" );*/
    // diganti oleh sfdn
    $f->text("Pasien Dari",$d->rawatan);
    if ($rawatan == "Rawat Jalan") {
       $f->text("Poli",$d->poli);
    } else {
       $f->text("Bangsal",$bangsal);
    }

    $f->text("Kedatangan",$d->datang);
    // akhir ganti

    $f->execute();
    echo "</td><td align=center valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Alamat", "$d->alm_tetap $d->kota_tetap $d->pos_tetap");
    $f->text("Telepon", $d->tlp_tetap);
    $f->text("Tanggal Masukl", $d->tanggal_reg);
    $f->text("<nobr>Tipe Pasien</nobr>", $d->tipe_desc);
    $f->text("Umur", $d->umur);
    if ($d->jenis_kelamin == P){
	$f->text("Jenis Kelamin", "PEREMPUAN");	
    }else{
	$f->text("Jenis Kelamin", "LAKI-LAKI");
    }
    $f->execute();
    echo "</td><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    echo "<table border=0 width='100%'>";
    echo "<tr><td class=TBL_BODY>Diagnosa Sementara:</td></tr>";
    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
    echo "</table>";
    $f->execute();
    echo "</td></tr></table>  " ;
	//Tombol navigasi back by Me, 29092011
	if(!$GLOBALS['print']){
    	echo " <BR><DIV ALIGN=RIGHT><img src=\"icon/back.gif\" align=absmiddle ><A CLASS=SUB_MENU HREF='index2.php".
            "?p=$PIDmain'>".
            "  Kembali  </A></DIV>";
            	
	}else{}
 	
    	echo"<br>";
    	
    echo "<form name=Form3>";
    //if ($_SESSION[uid] != "apotikri" && $_SESSION[uid] != "apotikrj") {
    //echo "<input name=b1 type=button value='Tindakan/Layanan' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."\";'>&nbsp;";}
 
    //if ($_SESSION[uid] == "apotikri" || $_SESSION[uid] == "apotikrj" || $_SESSION[uid] == "root") {
    //echo "<input name=b3 type=button value='Resep / Obat' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=obat\";'>&nbsp;";}
    

    // Perbaikan Session User untuk menampilkan tombol Tindakan/Layanan, Riwayat Medis, Resep Obat, ICD pada user selain super user, 6 Agustus 2011 by Me
    //echo "<input name=b0 type=button value='Hasil Pemeriksaan Pasien' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&mr=$d->mr_no&sub=pemeriksaan&act=new\";'>&nbsp;";
    
    //echo "<input name=b1 type=button value='Tindakan / Layanan' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."\";'>&nbsp;";

    //echo "<input name=b2 type=button value='Diagnosa / ICD' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=icd\";'>&nbsp;";
   
    //echo "<input name=b4 type=button value='Riwayat Medis' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&mr=$d->mr_no&sub=riwayat\";'>&nbsp;";
    
    //echo "<input name=b6 type=button value='Status Pasien' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&mr=$d->mr_no&sub=unit_rujukan\";'>&nbsp;";

    //echo "<input name=b5 type=button value='Penunjang' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&mr=$d->mr_no&sub=konsultasi\";'>&nbsp;";

    echo "<input name=b3 type=button value='Resep / Obat' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=obat\";'>&nbsp;";
    

    //echo "<input name=b4 type=button value='SMF' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=pjm\";'>";
    echo "</form>";


    $total = 0.00;
    if (is_array($_SESSION["layanan"])) {
        foreach($_SESSION["layanan"] as $k => $l) {
            $total += $l["total"];
        }
    }

//  echo count($_SESSION[obat]); exit;

    echo "<div class=box>";

    if ($_GET["sub"] == "byr") {
        title("Total Tagihan: Rp. ".number_format($total,2));
        echo "<br><br><br>";
        $f = new Form("actions/320.insert.php");
        $f->hidden("rg",$_GET["rg"]);
	$f->hidden("sub",$_GET["sub"]);
        $f->hidden("byr",$total);
        //$f->text("byr","Jumlah Pembayaran",15,15,$total,"STYLE='text-align:right'");
        $f->submit(" Simpan &amp; Bayar ");
        $f->execute();
    } elseif ($_GET["sub"] == "icd") {  // -------- ICD
        title("Diagnosa / ICD");
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b2.disabled = true;\n";
        echo "</script>\n";

        echo "<table width='100%' border=0 cellspacing=0 cellpadding=0><tr><td valign=top width=1>";

        echo "<form action='$SC'>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        echo "<textarea name=s2note rows=8 cols=40>".$_SESSION["s2note"]."</textarea>";
        echo "<br><input type=submit value=' Simpan '>";
        echo "</form>";

        echo "</td><td valign=top>";
        echo "&nbsp;";
        echo "</td><td valign=top>";

        $namaICD = getFromTable("SELECT description FROM rsv0005 WHERE diagnosis_code = '".$_SESSION["SELECT_ICD"]."'");
        $t = new BaseTable("100%");
        $t->printTableOpen();
        echo "<FORM ACTION='$SC' NAME=Form11>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t->printTableHeader(Array("Kode ICD", "Keterangan", "&nbsp;"));
        if (is_array($_SESSION["icd"])) {
            foreach($_SESSION["icd"] as $k => $l) {
                $t->printRow2(
                    Array($l["id"], $l["desc"], "<A HREF='$SC?p=$PID&regno=".$_GET["rg"]."&del-icd=$k&httpHeader=1'>".icon("del-left")."</A>"), Array("CENTER", "LEFT", "CENTER")
                );
            }
        }
         $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=icd STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_ICD"]."'>&nbsp;<A HREF='javascript:selectICD()'><IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaICD, "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "CENTER")
        );
	// --- eof 27-12-2006 ---
        echo "</FORM>";
        $t->printTableClose();

        echo "</td></tr></table>";

        echo "\n<script language='JavaScript'>\n";
        echo "function selectICD() {\n";
        echo "    sWin = window.open('popup/icd.php', 'xWin', 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
	//include("rincian2.php");

    } elseif ($_GET["sub"] == "obat") { // -------- OBAT


        title("Resep / Obat");
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b3.disabled = true;\n";
        echo "</script>\n";
	
	$x_racikan = " <SELECT NAME='ket_racikan'>\n";
        $x_racikan  .= "<OPTION VALUE=N>N</OPTION>\n";
        $x_racikan  .= "<OPTION VALUE=Y>Y</OPTION>\n";
        "</SELECT></TD>\n";


        if ($_SESSION["SELECT_OBAT"]) {
           $namaObat = getFromTable("select obat from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
           $hargaObat = getFromTable("select harga from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        }

        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
	echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        //echo "<a href='$SC?p=$PID&rg=".$_GET[rg]."&sub=retur'>[RETUR OBAT]</a>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        //$t->printTableHeader(Array("KODE", "Nama Obat", "Jumlah", "Satuan", "Dosis", ""));
        $t->printTableHeader(Array("KODE", "Nama Obat", "Jumlah", "Harga Satuan", "Harga Total", "Dosis", "Ket. Racikan", ""));


        if (is_array($_SESSION["obat"])) {


            foreach($_SESSION["obat"] as $k => $l) {
                $t->printRow2(
                    Array($l["id"], $l["desc"], $l["jumlah"], number_format($l["harga"],2), number_format($l["total"],2), $l["dosis"], $l["ket_racikan"],
                    "<A HREF='$SC?p=$PID&regno=".$_GET["rg"]."&del-obat=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "CENTER", "RIGHT", "RIGHT", "CENTER", "CENTER", "CENTER")
                );
            }



        }
        
	// sfdn, 27-12-2006 -> pembetulan directory icon = ../simrs/images/*.png
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit2()' NAME=obat STYLE='text-align:center' TYPE=TEXT SIZE=5
            MAXLENGTH=10 VALUE='".$_SESSION["SELECT_OBAT"]."'>&nbsp;<A HREF='javascript:selectObat()'>
            <IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaObat,
            "<INPUT VALUE='".(isset($_GET["jumlah_obat"]) ? $_GET["jumlah_obat"] : "1")."'NAME=jumlah_obat
            OnKeyPress='refreshSubmit2()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>",
            number_format($hargaObat,2), "", "<INPUT VALUE='".(isset($_GET["dosis"]) ? $_GET["dosis"]:"3 x 1")."'NAME=dosis OnKeyPress='refreshSubmit2()' TYPE=TEXTAREA SIZE=10 MAXLENGTH=10 VALUE='0' STYLE='text-align:center'>", $x_racikan,
            "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "CENTER", "RIGHT", "RIGHT", "CENTER", "CENTER", "CENTER")
        );
	// --- eof 27-12-2006 ---
        $t->printTableClose();
        echo "</FORM>";

        echo "\n<script language='JavaScript'>\n";
        echo "function selectObat() {\n";
        echo "    sWin = window.open('popup/obat.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
	//include("rincianobat.php");
	//include("rincian_ri.php");

  } elseif ($_GET["sub"] == "riwayat") { // -------- OBAT


        	//if(!$GLOBALS['print']){
		//$T->show(4);
	//}else{}
	/* - edited 100210 -
		- menghapus fungsi trim() dan mengganti type data entitas f.id menjadi character varying
	*/
    	if ($_GET["act"] == "detail_klinik") {
				$sql = "select a.*,b.nama,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,a.id_poli,f.layanan 
						from c_visit a
						left join rs00017 b on a.id_dokter = b.id
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00034 f on 'f.id' = e.item_id
						where a.no_reg='{$_GET['rug']}' ";
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
    		$poli=$_GET["polinya"];
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
    		                
                elseif ($poli == $setting_poli["operasi"]){


    			include(detail_operasi);
    		}
    		elseif ($poli == $setting_poli["laboratorium"]){
    			include(detail_laboratorium);
    		}
                else {
    			include(detail_radiologi);
    		}
    		
			}else {
				echo"<div align=center class=form_subtitle1>RIWAYAT PEMERIKSAAN PASIEN</div>";
                                echo "<script language='JavaScript'>\n";
                                echo "document.Form3.b4.disabled = true;\n";
                                echo "</script>\n";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
		/* -- edited 100210 
		- merubah numeric = character varying ke character varying = character varying
		- merubah fungsi substr() menjadi to_char pada tanggal_reg*/

$nomor_mr = getFromTable("select mr_no from rs00006 where id = '".$_GET["rg"]."'");
//$nomor_mr = 107;

				$sql = "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,C.TDESC,D.NAMA,A.ID_POLI,'DUMMY' ". 
					   "FROM C_VISIT A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   //merubah numeric = character varying ke character varying = character varying
					   "LEFT JOIN RS00001 C ON A.ID_POLI = C.TC_POLI AND C.TT='LYN'".
					   //merubah type data integer = integer
					   "LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID ".
					   //end of changing
					   "LEFT JOIN RS00001 E ON A.ID_KONSUL = E.TC AND E.TT='LYN'".
					   //"WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_KONSUL = '' AND A.ID_POLI != {$_GET["mPOLI"]} ";
					   // edited 100210 (mengganti query string mPOLI menjadi poli yang dituju)
//					   "WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_KONSUL = '' AND A.ID_POLI != 100 ";
					   "WHERE B.MR_NO = '$nomor_mr' AND A.ID_KONSUL = '' AND A.ID_POLI != 100";
					
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	$t->ColHidden[6]= true;
			   	$t->ColHidden[1]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("TANGGAL PEMERIKSAAN","","WAKTU KUNJUNGAN","KLINIK","DOKTER PEMERIKSA","DETAIL");
			   	$t->ColAlign = array("center","center","center","left","left","left","center","center");
			//	$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&sub=riwayat&act=detail_klinik&polinya=<#5#>&mr=$nomor_mr&rg=<#0#>'>".icon("view","View")."</A>";	
	$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&sub=riwayat&act=detail_klinik&polinya=<#5#>&mr=$nomor_mr&rug=<#0#>&rg=$_GET[rg]'>".icon("view","View")."</A>";
				$t->execute();
				
				echo"<br>";
         		echo"</div>";
				echo "</td></tr></table></div>";
    	
			}

    } elseif ($_GET["sub"] == "pjm") {   // -------- JASA MEDIS
        if (isset($_GET["pjmtype"])) $_SESSION["pjmtype"] = $_GET["pjmtype"];
        title("SMF ($d->tipe_desc)");
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b7.disabled = true;\n";
        echo "</script>\n";
        $f = new Form($SC, "GET", "name='Form13'");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->hidden("rg", $_GET["rg"]);
        $f->hidden("sub", $_GET["sub"]);
        $f->selectSQL("pjmtype", "SMF",
					  "select '' as id, '' as jasa_medis union ".
                      "select id, jasa_medis from rs00021 where tipe_pasien_id = '$d->tipe'",
                      $_SESSION["pjmtype"],"OnChange='document.Form13.submit()'");
        $f->execute();

        if (isset($_SESSION["SELECT_EMP"]) && is_array($_SESSION["pjm"][$_SESSION["tag"]])) {
            $cnt = count($_SESSION["pjm"][$_SESSION["tag"]]);
            if ($cnt == 1 && $_SESSION["pjm"][$_SESSION["tag"]][0]["id"] == "---") {
                $_SESSION["pjm"][$_SESSION["tag"]][0]["id"]   = $_SESSION["SELECT_EMP"];
                $_SESSION["pjm"][$_SESSION["tag"]][0]["name"] =
                    getFromTable("select nama from rs00017 where nip = '".$_SESSION["SELECT_EMP"]."'");
            } else {
                $_SESSION["pjm"][$_SESSION["tag"]][$cnt]["id"]   = $_SESSION["SELECT_EMP"];
                $_SESSION["pjm"][$_SESSION["tag"]][$cnt]["name"] =
                    getFromTable("select nama from rs00017 where nip = '".$_SESSION["SELECT_EMP"]."'");
            }
            unset($_SESSION["SELECT_EMP"]);
            unset($_SESSION["tag"]);
        }

        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        //$t->printTableHeader(Array("Grup Pembagian", "Prosentase", "Penerima", "&nbsp;", "&nbsp;"));
		$t->printTableHeader(Array("Kelompok", "SMF", "&nbsp;", "&nbsp;"));
        $r1 = pg_query($con,
            "select * from rs00020 where pembagian_jasa_medis_id = '".$_SESSION["pjmtype"]."'");
        while ($d1 = pg_fetch_object($r1)) {
            if (!is_array($_SESSION["pjm"]["$d1->id"])) {
                $_SESSION["pjm"]["$d1->id"][0]["id"]   = "---";
                $_SESSION["pjm"]["$d1->id"][0]["name"] = "-";
            }
            if ($d1->is_person == "Y") {
                $first = true;
                foreach ($_SESSION["pjm"]["$d1->id"] as $k => $v) {
                    if ($first) {
                        $first = false;
                        $t->printRow2(
                            Array($d1->description, $v["name"],
                                "<a href='$SC?p=$PID&regno=".$_GET["rg"].
                                    "&httpHeader=1&sub=pjm&del-pjm=".$d1->id.
                                    "&del-emp=".$k."'>".icon("del-left", "Hapus")."</a>",
                                "<a href='javascript:selectPegawai(\"$d1->id\")'>".
                                    icon("edit","OK")."</a>"
                            ),
                            Array("LEFT", "LEFT", "CENTER", "CENTER"));
                    } else {
                        $t->printRow2(
                            Array("", $v["name"],
                                "<a href='$SC?p=$PID&regno=".$_GET["rg"].
                                    "&httpHeader=1&sub=pjm&del-pjm=".$d1->id.
                                    "&del-emp=".$k."'>".icon("del-left", "Hapus")."</a>", ""

/*
                            Array("", "", $v["name"],
                                "<a href='$SC?p=$PID&regno=".$_GET["rg"].
                                    "&httpHeader=1&sub=pjm&del-pjm=".$d1->id.
                                    "&del-emp=".$k."'>".icon("del-left", "Hapus")."</a>", ""
*/
                            ),
                            Array("LEFT", "LEFT", "CENTER", "CENTER"));
                    }
                }
            } else {
                $t->printRow2(
                    Array($d1->description, "",
                        "", ""),
                    Array("LEFT", "LEFT", "CENTER", "CENTER"));
            }
 

        }
        pg_free_result($r1);
        $t->printTableClose();
        echo "</FORM>";

        echo "\n<script language='JavaScript'>\n";
        echo "function selectPegawai(tag) {\n";
        echo "    sWin = window.open('popup/pegawai.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";

 } elseif ($_GET["sub"] == "konsultasi") { // -------- RETUR

//$T->show(6);
	title ("Penunjang");
    	//echo"<br>";
	echo "<script language='JavaScript'>\n";
        echo "document.Form3.b5.disabled = true;\n"; //mendisable button Rujukan by me, 24 agustus 2011 ; 11:20
        echo "</script>\n";
    	
    	//$laporan = getFromTable("select tdesc from rs00001 where tt='LRI' and tc = '".$_SESSION[SELECT_LAP]."'");
    	$f = new Form("actions/320.insert.php", "POST", "NAME=Form2"); //memperbaiki nama link skrip insert by Me 24 Agustus 2011, 10.14wib 
					$f->hidden("act","new2");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("sub","konsultasi");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_ri","$poli");
				    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
				    $f->hidden("f_user_id",$_SESSION[uid]);
				    $f->hidden("konsultasi",$_GET["konsultasi"]);
				    
					echo"<br>";
					$konsul = getFromTable("select id_konsul from c_visit_ri where no_reg='".$_GET["rg"]."' and id_ri='".$_GET["poli"]."'");
				    $f->PgConn=$con;
					$f->selectSQL("konsultasi","Unit Yang Dituju", "select '' as tc, '' as tdesc union select tc,tdesc from rs00001 where tt='LYN' and tc not in ('000','100','111','201','202','206','207','208','999') order by tdesc",$konsul,$ext);
				    $f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
				    $f->execute();
		echo"<br><font color=black>&nbsp;* Catatan : Hasil Pemeriksaan Pasien harus diisi minimal Dokter Pemeriksa</font><br>";

}elseif ($_GET["sub"] == "unit_rujukan"){
    	//$T->show(5);
    	//echo"<br>";
	title("Status Pasien");
	echo "<script language='JavaScript'>\n";
        echo "document.Form3.b6.disabled = true;\n"; //mendisable button konsultasi
        echo "</script>\n";
    	//$laporan = getFromTable("select tdesc from rs00001 where tt='LRI' and tc = '".$_SESSION[SELECT_LAP]."'");
    	$f = new Form("actions/320.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new1");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("sub","unit_rujukan");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
				    $f->hidden("f_id_ri","$poli");
				    $f->hidden("f_user_id",$_SESSION[uid]);
				    $f->hidden("status_akhir",$_GET["status_akhir"]);

					echo"<br>";
					$tipe = getFromTable("select status_akhir_pasien from rs00006 where id='".$_GET["rg"]."'");
				    $f->PgConn=$con;
					$f->selectSQL("status_akhir","Status Akhir Pasien", "select '' as tc, '' as tdesc union select tc , tdesc from rs00001 where tt='SAP' and tc not in ('000','012') order by tdesc", $tipe,$ext);
				    $f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
				    $f->execute();
    } elseif ($_GET["sub"] == "retur") { // -------- RETUR

        title("Retur Obat");
        echo "<br>";

        if ($_GET[sub2] == 1) {

$q = pg_query(
    "select TO_CHAR(a.tanggal_trans,'dd-mm-yyyy') as tanggal_trans, b.obat, c.tdesc as satuan, a.item_id, a.harga, a.qty, d.qty as retur, (a.qty*a.harga) as jumlah, a.no_reg as dummy ".
    "from rs00008 a ".
    "     left join rs00015 b on a.item_id = b.id ".
    "     left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
    "     left join rs00008 d on d.referensi = a.id and d.trans_type = 'RET' ".
    "where a.id = ".$_GET[id]);


$qr = pg_fetch_object($q);
$sisa = $qr->qty - $qr->retur;

    $f = new ReadOnlyForm();
    //$f->title("Data Obat");
    $f->text("Obat ID",$qr->item_id);
    $f->text("Nama Obat",$qr->obat);
    $f->text("Satuan",$qr->satuan);
    $f->text("Harga",number_format($qr->harga,2,',','.'));
    $f->text("Jumlah",$sisa);
    //$f->text("Total",$qr->qty_rj);
    //$f->text("Stok Apotek R/I",$qr->qty_ri);
    $f->execute();

    //$totalret = $qr->qty*$qr->harga;

    echo "<br>";
    if ($sisa > 0) {
    $f = new Form("actions/retur.insert.php", "POST", "NAME=Form1");
    //$f->PgConn = $con;
    $f->hidden("retur_id",$_GET[id]);
    $f->hidden("rg",$_GET[rg]);
    $f->hidden("sub",$_GET[sub]);
    $f->hidden("rawatan",$rawatan);
    $f->hidden("harga",$qr->harga);

    $f->hidden("sisa",$sisa);
    $f->hidden("id",$qr->item_id);


    $f->text("retur","Retur",10,"","","");

    $f->submit(" Simpan ");
    $f->execute();
    }



        } else {


if (empty($_GET[sort])) {
   $_GET[sort] = "tanggal_trans";
}

$t = new PgTable($con, "100%");

$t->SQL =
//    "select a.tanggal_trans, b.obat, c.tdesc as satuan, a.harga, a.qty, sum(d.qty) as retur, (a.qty*a.harga) as jumlah, a.id as dummy ".
    "select TO_CHAR(a.tanggal_trans,'dd-mm-yyyy') as tanggal_trans, b.obat, c.tdesc as satuan, a.harga, a.qty, sum(d.qty) as retur, a.id as dummy ".
    "from rs00008 a ".
    "     left join rs00015 b on a.item_id = b.id ".
    "     left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
    "     left join rs00008 d on d.referensi = a.id and d.trans_type = 'RET' ".
    "where a.trans_type='OB1' and a.no_reg='".$_GET[rg]."' ".
    "group by a.tanggal_trans, b.obat, c.tdesc, a.harga, a.qty, a.id";

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
$t->RowsPerPage = 50;
$t->ColAlign[3] = "RIGHT";
$t->ColAlign[4] = "RIGHT";
$t->ColAlign[5] = "RIGHT";
$t->ColAlign[6] = "CENTER";
//$t->ColAlign[7] = "CENTER";

$t->ColFormatNumber[3] = 2;
$t->ColFormatNumber[4] = 0;
$t->ColFormatNumber[5] = 0;
//$t->ColFormatNumber[6] = 0;



//$t->ColFormatMoney[2] = "%!+#2n";

//$t->ColHeader = array("NAMA OBAT", "SATUAN", "HARGA", "AWAL","TERIMA","KELUAR","AKHIR");
$t->ColHeader = array("TANGGAL", "NAMA OBAT", "SATUAN", "HARGA", "JUMLAH","RETUR","");
$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&rg=".$_GET[rg]."&sub=retur&id=<#6#>&sub2=1'>".icon("view","View")."</A>";
$t->execute();
        }   // end of sub2 = 1



    } elseif ($_GET["sub"] == "pemeriksaan"){
	
	echo "<script language='JavaScript'>\n";
        echo "document.Form3.b0.disabled = true;\n";
        echo "</script>\n";
	echo "<br>";
	
	$poli='999';
	$rg=$_GET["rg"];
	$sql2 = "SELECT A.*,B.NAMA FROM C_VISIT_RI A
				LEFT JOIN RS00017 B ON A.ID_DOKTER = B.ID
				WHERE A.NO_REG='$rg'";
	$r=pg_query($con,$sql2);
	$n = pg_num_rows($r);
	if($n > 0) $d2 = pg_fetch_array($r);
			 pg_free_result($r);
			 if($n == 0){
						$ext= "disabled";
					}else{
						$ext = "";
						}
	echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&sub=pemeriksaan&act=edit';\"$ext>\n";
	if ($_GET['act'] == "edit"){
						echo "<font color='#000000' size='2'> >>Edit Pemeriksaan Pasien</font>";
						$f = new Form("actions/320.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d2["no_reg"]);
					    	$f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
						$f->hidden("sub","pemeriksaan");
					    	$f->hidden("mr",$_GET["mr"]);
					    	$f->hidden("f_id_ri",$poli);
					    	$f->hidden("f_user_id",$_SESSION[uid]);
					   
				}else { if($n >0){
						$ext= "disabled";
					}else{
						$ext = "";
						}
					$f = new Form("actions/320.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("sub","pemeriksaan");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_ri",$poli);
				    $f->hidden("f_user_id",$_SESSION[uid]);
					}
	
	echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
					//echo"<div align=left class=FORM_SUBTITLE1><U>RIWAYAT PASIEN</U></div>";
					title("Hasil Pemeriksaan Pasien");

					echo "<br>";
					
			if (isset($_SESSION["SELECT_EMP"])) {
    					$_SESSION["DOKTER"]["id"] = $_SESSION["SELECT_EMP"];
    					$_SESSION["DOKTER"]["nama"] =


        				getFromTable(
			            "select nama from rs00017 where id = '".$_SESSION["DOKTER"]["id"]."'");
    					$f->textAndButton3("f_id_dokter","Dokter/Petugas Pemeriksa",2,10,$_SESSION["DOKTER"]["id"],$ext,"nm2",30,70,$_SESSION["DOKTER"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");
			            unset($_SESSION["SELECT_EMP"]);
					}else{
						$f->textAndButton3("f_id_dokter","Dokter/Petugas Pemeriksa",2,10,$d2["id_dokter"],$ext,"nm2",30,70,$d2["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");
					}

			$f->textarea("f_vis_1",$visit_ri_hasilpemeriksaan["vis_1"] ,1, $visit_ri_hasilpemeriksaan["vis_1"."W"],ucfirst($d2["vis_1"]),$ext);
			$f->title1("<U>PEMERIKSAAN FISIK</U>");
			$f->text_4("","f_vis_2",$visit_ri_hasilpemeriksaan["vis_2"],10,20,ucfirst($d2["vis_2"]),"","f_vis_3",$visit_ri_hasilpemeriksaan["vis_3"],10,20,$d2["vis_3"],"mm Hg","f_vis_4",$visit_ri_hasilpemeriksaan["vis_4"],10,20,$d2["vis_4"],"/Menit","f_vis_5",$visit_ri_hasilpemeriksaan["vis_5"],10,20,ucfirst($d2["vis_5"]),"",$ext);
			$f->text_4("","f_vis_6",$visit_ri_hasilpemeriksaan["vis_6"],10,20,ucfirst($d2["vis_6"]),"","f_vis_7",$visit_ri_hasilpemeriksaan["vis_7"],10,20,$d2["vis_7"],"Kg","f_vis_8",$visit_ri_hasilpemeriksaan["vis_8"],10,20,$d2["vis_8"],"&deg;C","f_vis_9",$visit_ri_hasilpemeriksaan["vis_9"],10,20,ucfirst($d2["vis_9"]),"",$ext);
			$f->textarea("f_vis_10",$visit_ri_hasilpemeriksaan["vis_10"] ,1, $visit_ri_hasilpemeriksaan["vis_10"."W"],ucfirst($d2["vis_10"]),$ext);
				    $f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
				    $f->execute();
	
	} else { // ----------------------------- LAYANAN MEDIS
        title("Layanan/Tindakan Medis");
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b1.disabled = true;\n";
        echo "</script>\n";

        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";


        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "LAYANAN", "PETUGAS", "JUMLAH", "SATUAN",
            "Harga Satuan", "Harga Total", ""));
        if (is_array($_SESSION["layanan"])) {
            $total = 0.00;
            foreach($_SESSION["layanan"] as $k => $l) {

                $q = pg_query("SELECT b.tdesc AS kelas_tarif, substr(a.hierarchy,1,6) AS hie FROM rs00034 a ".
                        "LEFT JOIN rs00001 b ON a.klasifikasi_tarif_id = b.tc AND b.tt = 'KTR' ".
                        "WHERE a.id = $l[id]");
                $qr = pg_fetch_object($q);

                if ($qr->hie == "003002") {
                   $tambahan = " - ".$qr->kelas_tarif;

                }

                $t->printRow2(
                    Array($l["id"], $l["nama"].$tambahan, $l["dokter"], $l["jumlah"], $l["satuan"],
                        number_format($l["harga"],2), number_format($l["total"],2),
                        "<A HREF='$SC?p=$PID&regno=".$_GET["rg"]."&del=$k&httpHeader=1'>".icon("del-left")."</A>"),
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
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_LAYANAN"].
			"'>&nbsp;<A HREF='javascript:selectLayanan()'><IMG BORDER=0 SRC='images/icon-view.png'></A>",
			$d->layanan . " - " . $d->klasifikasi_tarif, "<INPUT OnKeyPress='refreshSubmit()' NAME=dokter STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=12 VALUE='"
                        .$_SESSION["SELECT_EMP"]."'>&nbsp;<A HREF='javascript:selectPegawai()'><IMG BORDER=0 SRC='images/icon-view.png'></a>", "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1").
			"'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", $d->satuan, $hargaHtml,
			"", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK' DISABLED>"),
            Array("CENTER", "LEFT", "CENTER","CENTER", "LEFT", "RIGHT", "LEFT", "CENTER")
        );
	// --- eof 27-12-2006 ---
        $t->printRow2(
            Array("", "", "", "", "", "", number_format($total,2),""),
            Array("RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT")
        );
        $t->printTableClose();
        echo "</FORM>";

        if (isset($_SESSION["SELECT_LAYANAN"]) && $is_range) {
            echo "<br>";
            info("Informasi Jasa:",
                "$d->unit_layanan, $d->sub_unit_layanan, $d->layanan<BR>".
                "Jasa Sarana: <big>Rp. $d->harga_bawah</big>; Jasa Pelayanan <big>Rp. $d->harga_atas</big>");
        }
	//include("rincian2.php");
	//include("rincian_ri.php");
    }

    echo "</div>";

    if ($_GET["sub"] != "byr") {


	echo "<table border=0 width='100%'><tr>";
	echo "<td align=right valign=top>";
        if ($_GET[sub] != "retur") {
	//echo "<table border=0 width='100%'><td>";
        echo "<form name='Form9' action='actions/320.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        if ($_GET["sub"] != "riwayat" and $_GET["sub"] != "pemeriksaan") {
        echo "<input type=button value='Simpan' onClick='document.Form9.submit()'>&nbsp;";
        }
        //if ($total > 0) echo "<input type=button value='Simpan &amp; Bayar' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=byr\"'>&nbsp;";
        echo "</form>";
        }
        echo "</td></tr>";

        if (empty($_GET["sub"])) {

        if ($_SESSION[gr] == "laborat"  || $_SESSION[gr] == "root") {

        echo "<form name='Form10' action='actions/998.1.load.php' method=POST>";
        echo "<tr><td>";
	// ------------ paket laboratorium
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
	//echo "<select name=preset>";
	//$kodepoli = getFromTable("select poli from rs00006 where id = lpad('".$_GET["rg"]."',10,'0')");
    	//$r2 = pg_query($con, "select * from rs99996 where trans_type = 'LYN' and poli = $kodepoli order by description");
	//if (getFromTable("select rawat_inap from rs00006 where id = lpad('".$_GET["rg"]."',10,'0')") == "I") {
	//	$r2 = pg_query($con, "select * from rs99996 where trans_type = '' order by description");
	//}
      //  $r2 = pg_query($con, "select * from rs99996 where trans_type = 'LAB' order by description");
     //   while ($d2 = pg_fetch_object($r2)) {
     //      if ($d2->id == $_SESSION["LAST_PRESET"]) {
      //          echo "<option selected value='$d2->id'>$d2->description</option>'";
      //      } else {
      //          echo "<option value='$d2->id'>$d2->description</option>'";
     //       }
      //  }
     //   pg_free_result($r2);
      //  echo "</select>";
     //   echo "<input type='submit' value='LABORATORIUM123'>";
	//echo "</td></tr>";
        echo "</form>";

        } // end of $_SESSION[gr] == laborat || root


        if ($_SESSION[gr] == "radiologi" || $_SESSION[gr] == "root") {

        echo "<form name='Form10' action='actions/998.1.load.php' method=POST>";
        echo "<tr><td>";
	// ------------ paket radiologi
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";

/*	echo "<select name=preset>";
        $r2 = pg_query($con, "select * from rs99996 where trans_type = 'RAD' order by description");
        while ($d2 = pg_fetch_object($r2)) {
           if ($d2->id == $_SESSION["LAST_PRESET"]) {
                echo "<option selected value='$d2->id'>$d2->description</option>'";
            } else {
                echo "<option value='$d2->id'>$d2->description</option>'";
            }
        }

        pg_free_result($r2);
        echo "</select>";
        echo "<input type='submit' value='RADIOLOGI'>";
        echo "</td></tr>";*/
        echo "</form>";

        } // end of $_SESSION[gr] == radiologi || root

        }

        echo "</table>";


    }

    echo "\n<script language='JavaScript'>\n";
    echo "function selectLayanan() {\n";
    echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    
    //echo "\n<script language='JavaScript'>\n";
        echo "function selectPegawai(tag) {\n";
        echo "    sWin = window.open('popup/pegawai.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        //echo "</script>\n";
        

    if (empty($_GET[sub])) {
    echo "function refreshSubmit() {\n";
    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
    echo "}\n";
    echo "refreshSubmit();\n";

    }

    if ($_GET[sub] == "obat") {

    echo "function refreshSubmit2() {\n";
    echo "    document.Form8.submitButton.disabled = Number(document.Form8.obat.value) == 0 || Number(document.Form8.jumlah_obat.value == 0);\n";
    echo "}\n";
    echo "refreshSubmit2();\n";
    }

    echo "</script>\n";

} else {
    echo "<DIV class=BOX>";
/* Rawat Jalan */
//awalnya [gr]
    //if ($_SESSION[uid] == "apotikri" || $_SESSION[uid] == "apotikrj" || $_SESSION[gr] == "daftar" || $_SESSION[uid] == "daftarri" || $_SESSION[uid] == "root") {

	    $ext = "OnChange = 'Form1.submit();'";
	    $f = new Form($SC, "GET", "NAME=Form1");
	    $f->PgConn = $con;	if ($_GET["act"] == "detail_klinik") {
				$sql = "select a.*,b.nama,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,a.id_poli,f.layanan 
						from c_visit a 
						left join rs00017 b on a.id_dokter = b.id
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00034 f on 'f.id' = e.item_id
						where a.no_reg='{$_GET['rg']}' ";
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
    		$poli=$_GET["polinya"];
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
                elseif ($poli == $setting_poli["operasi"]){
    			include(detail_operasi);
    		}
    		else{
    			include(detail_laboratorium);
    		}
    		
			}else {
				echo"<div align=center class=form_subtitle1>RIWAYAT PEMERIKSAAN PASIEN</div>";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
		/* -- edited 100210 
		- merubah numeric = character varying ke character varying = character varying
		- merubah fungsi substr() menjadi to_char pada tanggal_reg*/
				$sql = "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,C.TDESC,D.NAMA,A.ID_POLI,'DUMMY' ". 
					   "FROM C_VISIT A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   //merubah numeric = character varying ke character varying = character varying
					   "LEFT JOIN RS00001 C ON A.ID_POLI = C.TC_POLI AND C.TT='LYN'".
					   //merubah type data integer = integer
					   "LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID ".
					   //end of changing
					   "LEFT JOIN RS00001 E ON A.ID_KONSUL = E.TC AND E.TT='LYN'".
					   //"WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_KONSUL = '' AND A.ID_POLI != {$_GET["mPOLI"]} ";
					   // edited 100210 (mengganti query string mPOLI menjadi poli yang dituju)
					//   "WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_KONSUL = '' AND A.ID_POLI != 100 ";
                                        "WHERE B.MR_NO = ".$d>mr_no." AND A.ID_KONSUL = '' AND A.ID_POLI != 100 ";
					
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	$t->ColHidden[6]= true;
			   	$t->ColHidden[1]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("TANGGAL PEMERIKSAAN","","WAKTU KUNJUNGAN","KLINIK","DOKTER PEMERIKSA","DETAIL");
			   	$t->ColAlign = array("center","center","center","left","left","left","center","center");
				$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&sub=riwayat&act=detail_klinik&polinya=<#5#>&mr=".$_GET["mr"]."&rg=<#0#>'>".icon("view","View")."</A>";	
				$t->execute();
				
				echo"<br>";
         		echo"</div>";
				echo "</td></tr></table></div>";
    	
			}
	    $f->hidden("p", $PID);
	    echo "<br>";
		echo "<TABLE BORDER='0' width='100%'><tr><td align='left'>";
	    $f->selectSQL("mPOLI", "P O L I",
			        "select '' as tc, '' as tdesc union ".
				"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' order by tdesc "
				, $_GET["mPOLI"],$ext);
	
		$f->execute();

    //} 		// end of $_SESSION[gr] == rj || root
		
			/*
		        echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
		        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
		        echo "<INPUT TYPE=HIDDEN NAME=mPOLI VALUE='".$_GET["mPOLI"]."'>";
		        echo "<TD class=SUB_MENU>NO.MR / NO.REG / NAMA : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
		        echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
		        echo "</TR></FORM></TABLE></DIV>";
			*/
			//------heri ----------------
		echo "</td><td ALIGN=RIGHT>";
			$f = new Form($SC, "GET","NAME=Form2");
		    $f->hidden("p", $PID);
		    if (!$GLOBALS['print']){
		    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
			}else { 
			   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
			}
		    $f->execute();
	    	if ($msg) errmsg("Error:", $msg);
			//---------------------
		echo "</td></tr></table>";		
		echo "<br>";
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

	$SQLSTR =
        "select d.mr_no, a.id, TO_CHAR(a.tanggal_reg,'dd-mm-yyyy') as tanggal_reg, d.nama, ".
//	"	(select x.layanan from rs00034 x where x.id = a.poli) ".
	"	(SELECT x.tdesc FROM rs00001 x WHERE x.tt = 'LYN' AND x. tc_poli=a.poli)".
	"		as layanan, ".
    "   case when a.rawat_inap='Y' then 'RAWAT JALAN' ".
	"	 	when a.rawat_inap='I' then 'RAWAT INAP ' else 'IGD' end as rawatan, ".
/*"(select c.bangsal || ' / ' || e.tdesc ".
                       "from rs00010 as a ".
                       "    join rs00012 as b on a.bangsal_id = b.id ".
                       "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
                      "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
                       "where a.id = '$id_max') as bangsal,".*/

    "   	b.tdesc as pasien, ".
	"   case when a.rujukan='N' then 'Non-Rujukan' ".
	"	 	when a.rujukan='U' then 'Unit Lain' else 'Rujukan' end as datang  ".
    "	from rs00006 a  ".
    "   left join rs00001 b ON a.tipe = b.tc and b.tt='JEP' ".
    "   left join rs00002 d ON a.mr_no = d.mr_no ";
// "    join rs00010 as e on a.id = e.no_reg ".
 //"    join rs00012 as f on e.bangsal_id = f.id ";

 // "   left join  rsv0012 x ON  x.mr_no = d.mr_no AND x.reg = d.id ";
 
	// 24-12-2006 --> tambahan 'where a.is_bayar = 'N'
	
        $tglhariini = date("Y-m-d", time());
	if (strlen($_GET["mPOLI"]) > 0 ) {
		$SQLWHERE =
			"where  a.poli ='".$_GET["mPOLI"]."' and ".
			"	(upper(d.nama) LIKE '%".strtoupper($_GET["search"])."%') ";
	} else {
		$SQLWHERE =
			"where tanggal_reg = '$tglhariini' and ".
		 	"	 (upper(d.nama) LIKE '%".strtoupper($_GET["search"])."%' ) ";
	}

	if ($_GET["search"]) {
		$SQLWHERE =
			"where  ((upper(d.nama) LIKE '%".strtoupper($_GET["search"])."%') OR ".
                        "	d.mr_no LIKE '%".$_GET["search"]."%'  or ".
                        "       a.id LIKE '%".$_GET["search"]."%') ";

	}

        if ($_SESSION[gr] == "igd") {
           $SQLWHERE2 = "and a.rawat_inap='N' ";
           $title_layanan = "IGD" ;
        } elseif ($_SESSION[gr] == "rj") {
           $SQLWHERE2 = "and a.rawat_inap='Y' ";
            $title_layanan = "RAWAT JALAN " ;
        } elseif ($_SESSION[gr] == "ri") {
           $SQLWHERE2 = "and a.rawat_inap='I' ";
           $title_layanan = "RAWAT INAP " ;
        } else {
           $SQLWHERE2 = " ";
        }
        
       
 	 $SQLWHERE3 = "AND a.is_bayar = 'N'";
$SQLWHERE4 = "AND a.rawat_inap = 'I'";
 	
 	//$SQLWHERE3 = "AND 'LUNAS' <> (select x.statusbayar from rsv0012 x where x.mr_no = d.mr_no AND x.id = a.id)";

	if (!isset($_GET[sort])) {

           $_GET[sort] = "id";
           $_GET[order] = "asc";
	}
//awalnya $_SESSION [gr]
    if ($_SESSION[uid] == "apotikri" || $_SESSION[uid] == "apotikrj") {
       $tambah = "&sub=obat";
       $title_layanan = "APOTEK " ;
    } else {
       $tambah = "";
    }

// echo $SQLSTR.$SQLWHERE.$SQLWHERE1.$SQLWHERE2.$SQLWHERE3 ;
title($title_layanan);

    $t = new PgTable($con, "100%");
    $t->SQL = "$SQLSTR $SQLWHERE $SQLWHERE2 $SQLWHERE3 $SQLWHERE4";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[3] = "CENTER";

    $t->ColAlign[4] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->ColAlign[6] = "CENTER";

    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&sub=obat&tt=$AKSES&rg=<#1#>$tambah'><#1#></A>";
    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL  REGISTRASI","NAMA PASIEN","P O L I","LOKET","TIPE PASIEN","KEDATANGAN");
	//$t->ShowSQL = true;
    $t->execute();


/*
    $f = new Form($SC, "GET");
    $f->hidden("p", $PID);
    $f->text("rg","Masukkan Nomor Registrasi",10,10,$_GET["rg"]);
    $f->submit(" OK ");
    $f->execute();
    if ($msg) errmsg("Error:", $msg);
*/
    echo "</DIV>";
}

//echo count($_SESSION[obat]);
include("rincian_ri.php");

//  echo count($_SESSION[obat]);

} else {
  echo "<br><br><br><br><center><b>Session kadaluarsa. Login dulu.</b></center>";

}  // end of !empty($_SESSION[gr])


?>
