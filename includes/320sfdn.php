<?php // Nugraha, Sun Apr 18 18:58:42 WIT 2004
      // sfdn, 22-04-2004: hanya merubah beberapa title
      // sfdn, 23-04-2004: tambah harga obat
      // sfdn, 30-04-2004
      // sfdn, 09-05-2004
      // sfdn, 18-05-2004: age
      // sfdn, 02-06-2004
      // Nugraha, Sun Jun  6 18:14:41 WIT 2004 : Paket Transaksi
	  // sfdn, 06-06-2004 : fungsi umur
	  // sfdn, 08-06-2004


$PID = "320";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();

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
    if (strlen($d->obat) > 0) {
        $_SESSION["obat"][$cnt]["id"]     = $_GET["obat"];
        $_SESSION["obat"][$cnt]["desc"]   = $d->obat;
        $_SESSION["obat"][$cnt]["dosis"]  = $_GET["dosis_obat"];
        $_SESSION["obat"][$cnt]["jumlah"] = $_GET["jumlah_obat"];
        $_SESSION["obat"][$cnt]["harga"]  = $d->harga;
        $_SESSION["obat"][$cnt]["satuan"] = $d->satuan;
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

    $is_range = $d->harga_atas > 0 || $d->harga_bawah > 0;

    if ($d->id) {
        if (($is_range && isset($_GET["harga"])) || (!$is_range)) {
            if (is_array($_SESSION["layanan"])) {
                $cnt = count($_SESSION["layanan"]);
            } else {
                $cnt = 0;
            }
            $harga = $is_range ? $_GET["harga"] : $d->harga;
            $_SESSION["layanan"][$cnt]["id"]     = str_pad($_GET["layanan"],5,"0",STR_PAD_LEFT);
            $_SESSION["layanan"][$cnt]["nama"]   = $d->layanan . " - " . $d->klasifikasi_tarif;
            $_SESSION["layanan"][$cnt]["jumlah"] = $_GET["jumlah"];
            $_SESSION["layanan"][$cnt]["satuan"] = $d->satuan;
            $_SESSION["layanan"][$cnt]["harga"]  = $harga;
            $_SESSION["layanan"][$cnt]["total"]  = $harga * $_GET["jumlah"];
            unset($_SESSION["SELECT_LAYANAN"]);
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

title("Pelayanan/Tindakan Medis");
echo "<br>";

unset($_GET["layanan"]);
// unset($_GET["jumlah"]);

$reg = $_GET["rg"];
if ($reg > 0) {
    if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where to_number(id,'9999999999') = $reg ".
                     "and status = 'A'") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}
	if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where to_number(id,'9999999999') = $reg ".
                     "and status_akhir_pasien != '-'") > 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak dpt. dipergunakan. Pasien tlh. Dilayani";
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
		"	case when a.rujukan ='Y' then 'Rujukan' else 'Non-Rujukan' end as datang ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc and h.tt = 'JDP' ".
        "WHERE a.id = lpad($reg,10,'0')");

    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Nomor Registrasi", formatRegNo($d->id));
    $f->text("Nomor MR", $d->mr_no);
    $f->text("Nama", $d->nama);
    /*$f->text("Pasien Dari", $d->rawat_inap == "Y" ? "Rawat Inap" : "Rawat Jalan" );*/
    // diganti oleh sfdn
    $f->text("Pasien Dari",$d->rawatan);
    $f->text("Jenis Kedatangan",$d->datang);
    // akhir ganti
    $f->execute();
    echo "</td><td align=center valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Alamat", "$d->alm_tetap $d->kota_tetap $d->pos_tetap");
    $f->text("Telepon", $d->tlp_tetap);
    $f->text("Tanggal", date("d F Y"));
    $f->text("Tipe Pasien", $d->tipe_desc);
    $f->text("Umur", umur($d->umur));
    $f->execute();
    echo "</td><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    echo "<table border=0 width='100%'>";
    echo "<tr><td class=TBL_BODY>Diagnosa Sementara:</td></tr>";
    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
    echo "</table>";
    $f->execute();
    echo "</td></tr></table>";

    echo "<form name=Form3>";
    echo "<input name=b1 type=button value='Tindakan/Layanan' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."\";'>&nbsp;";
    echo "<input name=b2 type=button value='Diagnosa / ICD' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=icd\";'>&nbsp;";
    echo "<input name=b3 type=button value='Resep / Obat' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=obat\";'>&nbsp;";
    echo "<input name=b4 type=button value='SMF' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=pjm\";'>";
    echo "</form>";


    $total = 0.00;
    if (is_array($_SESSION["layanan"])) {
        foreach($_SESSION["layanan"] as $k => $l) {
            $total += $l["total"];
        }
    }
    echo "<div class=box>";
    if ($_GET["sub"] == "byr") {
        title("Total Tagihan: Rp. ".number_format($total,2));
        echo "<br><br><br>";
        $f = new Form("actions/320.insert.php");
        $f->hidden("rg",$_GET["rg"]);
        $f->text("byr","Jumlah Pembayaran",15,15,$total,"STYLE='text-align:right'");
        $f->submit(" Simpan &amp; Bayar ");
        $f->execute();
    } elseif ($_GET["sub"] == "icd") {
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
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=icd STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_ICD"]."'>&nbsp;<A HREF='javascript:selectICD()'><IMG BORDER=0 SRC='../images/icon-view.png'></A>", $namaICD, "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Tambah'>"),
            Array("CENTER", "LEFT", "CENTER")
        );
        echo "</FORM>";
        $t->printTableClose();

        echo "</td></tr></table>";

        echo "\n<script language='JavaScript'>\n";
        echo "function selectICD() {\n";
        echo "    sWin = window.open('popup/icd.php', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";

    } elseif ($_GET["sub"] == "obat") {
        title("Resep / Obat");
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b3.disabled = true;\n";
        echo "</script>\n";

        if ($_SESSION["SELECT_OBAT"]) $namaObat = getFromTable("select obat from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        echo "<FORM ACTION='$SC' NAME=Form12>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "Nama Obat", "Jumlah", "Satuan", "Dosis", ""));
        if (is_array($_SESSION["obat"])) {
            foreach($_SESSION["obat"] as $k => $l) {
                $t->printRow(
                    Array($l["id"], $l["desc"], $l["jumlah"], $l["satuan"], $l["dosis"],  "<A HREF='$SC?p=$PID&regno=".$_GET["rg"]."&del-obat=$k&httpHeader=1'>".icon("del-left")."</A>"), Array("CENTER", "LEFT", "CENTER", "LEFT", "LEFT", "CENTER")
                );
            }
        }
        $t->printRow(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=obat STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_OBAT"]."'>&nbsp;<A HREF='javascript:selectObat()'><IMG BORDER=0 SRC='../images/icon-view.png'></A>", $namaObat, "<INPUT VALUE='".(isset($_GET["jumlah_obat"]) ? $_GET["jumlah_obat"] : "1")."'NAME=jumlah_obat OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", $d->satuan, "<INPUT TYPE=TEXT NAME='dosis_obat' size=20 maxlength=60>", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Tambah'>"),
            Array("CENTER", "LEFT", "CENTER", "LEFT", "CENTER", "CENTER")
        );
        $t->printTableClose();
        echo "</FORM>";

        echo "\n<script language='JavaScript'>\n";
        echo "function selectObat() {\n";
        echo "    sWin = window.open('popup/obat.php', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";

    } elseif ($_GET["sub"] == "pjm") {
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
                                    icon("edit","Tambah")."</a>"
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
/*
                    if ($first) {
                        $first = false;
                        $t->printRow(
                            Array($d1->description, "$d1->prosen %", $v["name"],
                                "<a href='$SC?p=$PID&regno=".$_GET["rg"].
                                    "&httpHeader=1&sub=pjm&del-pjm=".$d1->id.
                                    "&del-emp=".$k."'>".icon("del-left", "Hapus")."</a>",
                                "<a href='javascript:selectPegawai(\"$d1->id\")'>".
                                    icon("edit","Tambah")."</a>"
                            ),
                            Array("LEFT", "RIGHT", "LEFT", "CENTER", "CENTER"));
                    } else {
                        $t->printRow(
                            Array("", "", $v["name"],
                                "<a href='$SC?p=$PID&regno=".$_GET["rg"].
                                    "&httpHeader=1&sub=pjm&del-pjm=".$d1->id.
                                    "&del-emp=".$k."'>".icon("del-left", "Hapus")."</a>", ""
                            ),
                            Array("LEFT", "RIGHT", "LEFT", "CENTER", "CENTER"));
                    }


*/


                }
            } else {
                $t->printRow(
                    Array($d1->description, "",
                        "", ""),
                    Array("LEFT", "LEFT", "CENTER", "CENTER"));
/*

                $t->printRow(
                    Array($d1->description, "$d1->prosen %", "",
                        "", ""),
                    Array("LEFT", "RIGHT", "LEFT", "CENTER", "CENTER"));

*/


            }
        }
        pg_free_result($r1);
        $t->printTableClose();
        echo "</FORM>";

        echo "\n<script language='JavaScript'>\n";
        echo "function selectPegawai(tag) {\n";
        echo "    sWin = window.open('popup/pegawai.php?tag=' + tag, 'xWin',".
             " 'width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";

    } else {
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
        $t->printTableHeader(Array("KODE", "Layanan", "Jumlah", "Satuan",
            "Harga Satuan", "Harga Total", ""));
        if (is_array($_SESSION["layanan"])) {
            $total = 0.00;
            foreach($_SESSION["layanan"] as $k => $l) {
                $t->printRow(
                    Array($l["id"], $l["nama"], $l["jumlah"], $l["satuan"],
                        number_format($l["harga"],2), number_format($l["total"],2),
                        "<A HREF='$SC?p=$PID&regno=".$_GET["rg"]."&del=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "RIGHT", "LEFT", "RIGHT", "RIGHT", "CENTER")
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
        $t->printRow(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_LAYANAN"]."'>&nbsp;<A HREF='javascript:selectLayanan()'><IMG BORDER=0 SRC='../images/icon-view.png'></A>", $d->layanan . " - " . $d->klasifikasi_tarif, "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1")."'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", $d->satuan, $hargaHtml, "", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Tambah' DISABLED>"),
            Array("CENTER", "LEFT", "CENTER", "LEFT", "RIGHT", "LEFT", "CENTER")
        );
        $t->printRow(
            Array("", "", "", "", "", number_format($total,2),""),
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
        echo "<table border=0 width='100%'><td>";
	    $kodepoli = getFromTable("select poli from rs00006 where id = lpad('".$_GET["rg"]."',10,'0')");
		$pintu = getFromTable("select rawat_inap from rs00006 where id = lpad('".$_GET["rg"]."',10,'0')");
	    if ($pintu == "N") {
			$kodepoli=10;	
		}
    	$r2 = pg_query($con, "select * from rs99996 where trans_type = 'LYN' and poli = $kodepoli order by description");
        echo "<form name='Form10' action='actions/998.1.load.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<select name=preset>";
        //$r2 = pg_query($con, "select * from rs99996 where trans_type = 'LYN' order by description");
        while ($d2 = pg_fetch_object($r2)) {
            if ($d2->id == $_SESSION["LAST_PRESET"]) {
                echo "<option selected value='$d2->id'>$d2->description</option>'";
            } else {
                echo "<option value='$d2->id'>$d2->description</option>'";
            }
        }
        pg_free_result($r2);
        echo "</select>";
        echo "<input type='submit' value='Transaksi Paket'>";

        echo "</td><td align=right>";

        echo "<form name='Form9' action='actions/320.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<input type=button value='Simpan' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=sim\"'>&nbsp;";
        if ($total > 0) echo "<input type=button value='Simpan &amp; Bayar' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=byr\"'>&nbsp;";
        echo "</form>";

        echo "</td></tr></table>";
    }

    echo "\n<script language='JavaScript'>\n";
    echo "function selectLayanan() {\n";
    echo "    sWin = window.open('popup/layanan.php', 'xWin', 'width=600,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "function refreshSubmit() {\n";
    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
    echo "}\n";
    echo "refreshSubmit();\n";
    echo "</script>\n";
	
} else {
    echo "<DIV class=BOX>";
	$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    echo "<br>";
    $f->selectSQL("mPOLI", "P O L I",
        "select '' as tc, '' as tdesc union ".
		"select ltrim(rtrim(to_char(id,'999999999'))) as tc,layanan as tdesc ".
		"from rs00034  ".
		"where substr(hierarchy,1,3)='002' and ".
   		"	substr(hierarchy,4,3) NOT IN ('000') and ".
   		"	is_group ='Y' and substr(hierarchy,1,6) ".
		"		NOT IN ('002087','002000','002086','002084')", $_GET["mPOLI"],$ext);
    $f->execute();
	echo "<br>";
	$SQLSTR = 
        "select a.id, a.tanggal_reg, d.nama, ".
		"	case when a.rawat_inap='Y' then (select x.layanan from rs00034 x where x.id = a.poli) ".
		"		else (select x.layanan from rs00034 x where x.id=10) end as layanan, ".
        "   case when a.rawat_inap='Y' then 'RAWAT JALAN' else 'IGD' end as rawatan, ".
        "   b.tdesc as pasien, ".
		"	case when rujukan='N' then 'Non-Rujukan' else 'Rujukan' end as datang  ".
        "from rs00006 a  ".
        "   left join rs00001 b ON a.tipe = b.tc and b.tt='JEP' ".
        "   left join rs00002 d ON a.mr_no = d.mr_no ";
	if (strlen($_GET["mPOLI"]) > 0) {	
		$SQLWHERE =
			"where poli ='".$_GET["mPOLI"]."' and is_karcis='N'";
	} else {
		$SQLWHERE = " where is_karcis='N'";	
	}
	$t = new PgTable($con, "100%");
    $t->SQL = "$SQLSTR $SQLWHERE ";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[4] = "CENTER";
	$t->ColAlign[5] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&rg=<#0#>'><#0#></A>";
    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColHeader = array("NO.REG","TANGGAL  REGISTRASI","NAMA PASIEN","P O L I","LOKET","TIPE PASIEN","KEDATANGAN");
    $t->execute();
/*	
    $f = new Form($SC, "GET");
    $f->hidden("p", $PID);
    $f->text("rg","Masukkan Nomor Registrasi",10,10,$_GET["rg"]);
    $f->submit(" Transaksi ");
    $f->execute();
    if ($msg) errmsg("Error:", $msg);
*/

    echo "</DIV>";
}

?>
