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

$PID = "320APOTIK";
$SC = $_SERVER["SCRIPT_NAME"];
$AKSES = $_GET["tt"];

session_start();
$title_layanan = "";

if (!empty($_SESSION[gr])) {

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

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
        $_SESSION["obat"][$cnt]["dosis"]  = $_GET["dosis"];
        $_SESSION["obat"][$cnt]["jumlah"] = $_GET["jumlah_obat"];
        $_SESSION["obat"][$cnt]["harga"]  = $d->harga;
        $_SESSION["obat"][$cnt]["total"]  = $d->harga * $_GET["jumlah_obat"];
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
 
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >Layanan Apotek Rawat Inap");
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
    $f->text("Tanggal", date("d F Y"));
    $f->text("<nobr>Tipe Pasien</nobr>", $d->tipe_desc);
    $f->text("Umur", $umur);
    $f->execute();
    echo "</td><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    echo "<table border=0 width='100%'>";
    echo "<tr><td class=TBL_BODY>Diagnosa Sementara:</td></tr>";
    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
    echo "</table>";
    $f->execute();
    echo "</td></tr></table>  " ;

    echo "<form name=Form3>";
    if ($_SESSION[uid] != "apotikri" && $_SESSION[uid] != "apotikrj") {
 //   echo "<input name=b1 type=button value='Tindakan/Layanan' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."\";'>&nbsp;";
    }
 
     if ($_SESSION[uid] == "apotikri" || $_SESSION[uid] == "apotikrj" || $_SESSION[uid] == "root") {
 //   echo "<input name=b3 type=button value='Resep / Obat' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=obat\";'>&nbsp;";
    }
    
    /* By Yudha */
    

    if ($AKSES == "ri" || $AKSES == "rj" || $_SESSION[uid] == "root" || $_SESSION[uid] == "rumahsakit" || $_SESSION[gr] == "APOTIKRI") {
    	echo "<input name=b3 type=button value='Resep / Obat' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=obat\";'>&nbsp;";
    }  
    	
if ($AKSES == "tm" || $_SESSION[uid] == "root" || $_SESSION[uid] == "rumahsakit") {

//    echo "<input name=b1 type=button value='Tindakan/Layanan' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."\";'>&nbsp;";
 //   echo "<input name=b2 type=button value='Diagnosa / ICD' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=icd\";'>&nbsp;";
        	
    }
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
        $f = new Form("actions/320APOTIK.insert.php");
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
                $t->printRow(
                    Array($l["id"], $l["desc"], "<A HREF='$SC?p=$PID&regno=".$_GET["rg"]."&del-icd=$k&httpHeader=1'>".icon("del-left")."</A>"), Array("CENTER", "LEFT", "CENTER")
                );
            }
        }
         $t->printRow(
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

    } elseif ($_GET["sub"] == "obat") { // -------- OBAT


        title("Resep / Obat");
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b3.disabled = true;\n";
        echo "</script>\n";


        if ($_SESSION["SELECT_OBAT"]) {
           $namaObat = getFromTable("select obat from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
           $hargaObat = getFromTable("select harga from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        }

        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        echo "<a href='$SC?p=$PID&rg=".$_GET[rg]."&sub=retur'>[RETUR OBAT]</a>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        //$t->printTableHeader(Array("KODE", "Nama Obat", "Jumlah", "Satuan", "Dosis", ""));
        $t->printTableHeader(Array("KODE", "Nama Obat","Dosis", "Jumlah", "Harga Satuan", "Harga Total", ""));


        if (is_array($_SESSION["obat"])) {


            foreach($_SESSION["obat"] as $k => $l) {
                $t->printRow(
                    Array($l["id"], $l["desc"],$l["dosis"], $l["jumlah"], number_format($l["harga"],2), number_format($l["total"],2),
                    "<A HREF='$SC?p=$PID&regno=".$_GET["rg"]."&del-obat=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "CENTER", "RIGHT", "RIGHT", "CENTER")
                );
            }



        }
        
	// sfdn, 27-12-2006 -> pembetulan directory icon = ../simrs/images/*.png
        $t->printRow(
            Array("<INPUT OnKeyPress='refreshSubmit2()' NAME=obat STYLE='text-align:center' TYPE=TEXT SIZE=5
            MAXLENGTH=10 VALUE='".$_SESSION["SELECT_OBAT"]."'>&nbsp;<A HREF='javascript:selectObat()'>
            <IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaObat,"<INPUT VALUE='".(isset($_GET["dosis"]) ? $_GET["dosis"] : "3x1")."'NAME=dosis
            OnKeyPress='refreshSubmit2()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>",
            "<INPUT VALUE='".(isset($_GET["jumlah_obat"]) ? $_GET["jumlah_obat"] : "1")."'NAME=jumlah_obat
            OnKeyPress='refreshSubmit2()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>",
            number_format($hargaObat,2), "",
            "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "CENTER", "RIGHT", "RIGHT", "CENTER")
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

    } elseif ($_GET["sub"] == "pjm") {   // -------- JASA MEDIS
        if (isset($_GET["pjmtype"])) $_SESSION["pjmtype"] = $_GET["pjmtype"];
        title("SMF ($d->tipe_desc)");
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b4.disabled = true;\n";
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
                        $t->printRow(
                            Array($d1->description, $v["name"],
                                "<a href='$SC?p=$PID&regno=".$_GET["rg"].
                                    "&httpHeader=1&sub=pjm&del-pjm=".$d1->id.
                                    "&del-emp=".$k."'>".icon("del-left", "Hapus")."</a>",
                                "<a href='javascript:selectPegawai(\"$d1->id\")'>".
                                    icon("edit","OK")."</a>"
                            ),
                            Array("LEFT", "LEFT", "CENTER", "CENTER"));
                    } else {
                        $t->printRow(
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
                $t->printRow(
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

    } elseif ($_GET["sub"] == "retur") { // -------- RETUR

        title("Retur Obat");
        echo "<br>";

        if ($_GET[sub2] == 1) {

$q = @pg_query(
    "select TO_CHAR(a.tanggal_trans,'dd-mm-yyyy') as tanggal_trans, b.obat, c.tdesc as satuan, a.item_id, a.harga, a.qty, d.qty as retur, (a.qty*a.harga) as jumlah, a.no_reg as dummy ".
    "from rs00008 a ".
    "     left join rs00015 b on a.item_id = b.id ".
    "     left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
    "     left join rs00008 d on d.referensi = a.id and d.trans_type = 'RET' ".
    "where a.id = '$_GET[id]'");


$qr = @pg_fetch_object($q);
$sisa = $qr->qty - $qr->retur;

    $f = new ReadOnlyForm();
    $f->title("Data Obat");
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
  //  "     left join rs00015 b on a.item_id = b.id ".
  "     left join rs00015 b on a.item_id = b.id_obat ".
    "     left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
 //   "     left join rs00008 d on d.referensi = a.id and d.trans_type = 'RET' ".
   "     left join rs00008 d on d.referensi = a.id_transaksi and d.trans_type = 'RET' ".
    "where a.trans_type='OB1' and a.no_reg= '$_GET[rg]' ".
    "group by a.tanggal_trans, b.obat, c.tdesc, a.harga, a.qty, a.id";

/*$t->SQL =
//    "select a.tanggal_trans, b.obat, c.tdesc as satuan, a.harga, a.qty, sum(d.qty) as retur, (a.qty*a.harga) as jumlah, a.id as dummy ".
    "select TO_CHAR(a.tanggal_trans,'dd-mm-yyyy') as tanggal_trans, b.obat, c.tdesc as satuan, a.harga, a.qty, sum(d.qty) as retur, a.id as dummy ".
    "from rs00008 a ".
    "     left join rs00015 b on a.item_id = b.id ".
    "     left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
    "     left join rs00008 d on d.referensi = a.id and d.trans_type = 'RET' ".
    "where a.trans_type='OB1' and a.no_reg='".$_GET[rg]."' ".
    "group by a.tanggal_trans, b.obat, c.tdesc, a.harga, a.qty, a.id";*/

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
        $t->printTableHeader(Array("KODE", "Layanan", "Dokter", "Jumlah", "Satuan",
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

                $t->printRow(
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
        $t->printRow(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_LAYANAN"].
			"'>&nbsp;<A HREF='javascript:selectLayanan()'><IMG BORDER=0 SRC='images/icon-view.png'></A>",
			$d->layanan . " - " . $d->klasifikasi_tarif, "<INPUT OnKeyPress='refreshSubmit()' NAME=dokter STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=12 VALUE='"
                        .$_SESSION["SELECT_EMP"]."'>&nbsp;<A HREF='javascript:selectPegawai()'><IMG BORDER=0 SRC='images/icon-view.png'></a>", "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1").
			"'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", $d->satuan, $hargaHtml,
			"", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK' DISABLED>"),
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
    }

    echo "</div>";

    if ($_GET["sub"] != "byr") {


	echo "<table border=0 width='100%'><tr>";
	echo "<td align=right valign=top>";
        if ($_GET[sub] != "retur") {
	//echo "<table border=0 width='100%'><td>";
        echo "<form name='Form9' action='actions/320APOTIK.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<input type=button value='Simpan' onClick='document.Form9.submit()'>&nbsp;";
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
    if ($_SESSION[uid] == "apotikri" || $_SESSION[uid] == "apotikrj" || $_SESSION[gr] == "daftar" || $_SESSION[uid] == "daftarri" || $_SESSION[uid] == "root") {

	    $ext = "OnChange = 'Form1.submit();'";
	    $f = new Form($SC, "GET", "NAME=Form1");
	    $f->PgConn = $con;
	    $f->hidden("p", $PID);
	    echo "<br>";
		echo "<TABLE BORDER='0' width='100%'><tr><td align='left'>";
	    $f->selectSQL("mPOLI", "P O L I",
			        "select '' as tc, '' as tdesc union ".
				"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' order by tdesc "
				, $_GET["mPOLI"],$ext);
	
		$f->execute();

    } 		// end of $_SESSION[gr] == rj || root
		
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
	$SQLSTR =
        "select d.mr_no, a.id, TO_CHAR(a.tanggal_reg,'dd-mm-yyyy') as tanggal_reg, d.nama, ".
//	"	(select x.layanan from rs00034 x where x.id = a.poli) ".
	"	(SELECT x.tdesc FROM rs00001 x WHERE x.tt = 'LYN' AND x. tc_poli=a.poli)".
	"		as layanan, ".
    "   case when a.rawat_inap='Y' then 'RAWAT JALAN' ".
	"	 	when a.rawat_inap='I' then 'RAWAT INAP ' else 'IGD' end as rawatan, ".
    "   	b.tdesc as pasien, ".
	"   case when a.rujukan='N' then 'Non-Rujukan' ".
	"	 	when a.rujukan='U' then 'Unit Lain' else 'Rujukan' end as datang  ".
    "	from rs00006 a  ".
    "   left join rs00001 b ON a.tipe = b.tc and b.tt='JEP' ".
    "   left join rs00002 d ON a.mr_no = d.mr_no ";

 // "   left join  rsv0012 x ON  x.mr_no = d.mr_no AND x.reg = d.id ";
 
	// 24-12-2006 --> tambahan 'where a.is_bayar = 'N'
	
        $tglhariini = date("Y-m-d", time());
	if (strlen($_GET["mPOLI"]) > 0 ) {
		$SQLWHERE =
			"where  a.poli ='".$_GET["mPOLI"]."' and ".
			"	(upper(d.nama) LIKE '%".strtoupper($_GET["search"])."%') ";
	} else {
		$SQLWHERE =
			//"where tanggal_reg = '$tglhariini' and ".
		 	"where	 (upper(d.nama) LIKE '%".strtoupper($_GET["search"])."%' ) ";
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
        
       
 //	 $SQLWHERE4 = "AND a.is_bayar = 'Y'";
$SQLWHERE3 = "AND a.rawat_inap = 'I'";
	 $SQLWHERE4 = "AND a.status = 'A'";
 	
 	//$SQLWHERE3 = "AND 'LUNAS' <> (select x.statusbayar from rsv0012 x where x.mr_no = d.mr_no AND x.id = a.id)";

	if (!isset($_GET[sort])) {

           $_GET[sort] = "id";
           $_GET[order] = "asc";
	}
//awalnya $_SESSION [gr]
    if ($_SESSION[uid] == "apotik" || $_SESSION[uid] == "apotikrj" || $_SESSION[uid] == "root" || $_SESSION[uid] == "rumahsakit"|| $_SESSION[gr] == "APOTIKRI") {
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
    $t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tt=$AKSES&rg=<#1#>$tambah'><#1#></A>";
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

  include("rincianobat.php");
  include("rincianapotik.php");
//  echo count($_SESSION[obat]);

} else {
  echo "<br><br><br><br><center><b>Session kadaluarsa. Login dulu.</b></center>";

}  // end of !empty($_SESSION[gr])


?>
