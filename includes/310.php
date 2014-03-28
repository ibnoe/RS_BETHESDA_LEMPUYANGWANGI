<?php // Nugraha, Fri Apr  2 01:16:17 WIT 2004

$PID = "310";
$SC = $_SERVER["SCRIPT_NAME"];

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
    header("Location: $SC?p=" . $_GET["p"] . "&reg=" . $_GET["regno"]);
    exit;
} elseif (isset($_GET["layanan"])) {
    $r = pg_query($con,"select * from rsv0003 where id = '" . $_GET["layanan"] . "'");
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
            $_SESSION["layanan"][$cnt]["nama"]   = $d->nama_layanan . ($d->dengan_klasifikasi_tarif == "Y" ? ", $d->klasifikasi_tarif" : "");
            $_SESSION["layanan"][$cnt]["jumlah"] = $_GET["jumlah"];
            $_SESSION["layanan"][$cnt]["satuan"] = $d->satuan;
            $_SESSION["layanan"][$cnt]["harga"]  = $harga;
            $_SESSION["layanan"][$cnt]["total"]  = $harga * $_GET["jumlah"];
            unset($_SESSION["SELECT_LAYANAN"]);
            header("Location: $SC?p=" . $_GET["p"] . "&reg=" . $_GET["reg"]);
            exit;
        } elseif ($is_range) {
            $_SESSION["SELECT_LAYANAN"] = $_GET["layanan"];
            header("Location: $SC?p=" . $_GET["p"] . "&reg=" . $_GET["reg"] . "&jumlah=" . $_GET["jumlah"]);
            exit;
        }
    } else {
        header("Location: $SC?p=" . $_GET["p"] . "&reg=" . $_GET["reg"]);
        exit;
    }
}

title("Transaksi Layanan");
echo "<br>";

unset($_GET["layanan"]);
// unset($_GET["jumlah"]);

$reg = (int) $_GET["reg"];
if ($reg > 0) {
    if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where to_number(id,'9999999999') = $reg ".
                     "and status = 'A'") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}

if ($reg > 0) {
    $r = pg_query($con, "select * ".
                        "from rsv0002 ".
                        "where to_number(id,'9999999999') = $reg");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    echo "<table border=0 width='100%'><tr><td>";
    $f = new ReadOnlyForm();
    $f->text("Nomor Registrasi", formatRegNo($d->id));
    $f->text("Nomor MR", $d->mr_no);
    $f->text("Tanggal", date("d F Y"));
    $f->text("Jam", date("h:i:s"));
    $f->execute();
    echo "</td><td align=right>";
    $f = new ReadOnlyForm();
    $f->text("Nama", $d->nama);
    $f->text("Alamat", "$d->alm_tetap $d->kota_tetap $d->pos_tetap");
    $f->text("Telepon", $d->tlp_tetap);
    $f->text("Pasien Dari", $d->rawat_inap == "Y" ? "Rawat Inap" : "Rawat Jalan" );
    $f->execute();
    echo "</td></tr></table>";
    
    echo "<FORM ACTION='$SC' NAME=Form8>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
    echo "<INPUT TYPE=HIDDEN NAME=reg VALUE='".$_GET["reg"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("KODE", "Layanan", "Jumlah", "Satuan", "Harga Satuan", "Harga Total", ""));
    if (is_array($_SESSION["layanan"])) {
        $total = 0.00;
        foreach($_SESSION["layanan"] as $k => $l) {
            $t->printRow(
                Array($l["id"], $l["nama"], $l["jumlah"], $l["satuan"], number_format($l["harga"],2), number_format($l["total"],2),
                      "<A HREF='$SC?p=$PID&regno=".$_GET["reg"]."&del=$k&httpHeader=1'>".icon("del-left")."</A>"),
                Array("CENTER", "LEFT",     "RIGHT",      "LEFT",       "RIGHT",     "RIGHT", "CENTER")
            );
            $total += $l["total"];
        }
    }
    if (isset($_SESSION["SELECT_LAYANAN"])) {
        $r = pg_query($con,"select * from rsv0003 where id = '" . $_SESSION["SELECT_LAYANAN"] . "'");
        $d = pg_fetch_object($r);
        pg_free_result($r);
        
        $is_range = $d->harga_atas > 0 || $d->harga_bawah > 0;
        $harga = $is_range ? $_GET["harga"] : $d->harga;
        
        $hargaHtml = $is_range ? "<INPUT TYPE=TEXT NAME=harga SIZE=10 MAXLENGTH=12 VALUE='$d->harga'>" : $d->harga;
    }
    $t->printRow(
        Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_LAYANAN"]."'>&nbsp;<A HREF='javascript:selectLayanan()'><IMG BORDER=0 SRC='../images/icon-view.png'></A>", $d->nama_layanan, "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1")."'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", $d->satuan, $hargaHtml, "", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Tambah' DISABLED>"),
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
             "$d->unit_layanan, $d->sub_unit_layanan, $d->nama_layanan<BR>".
             "Harga: <big>Rp. $d->harga_bawah</big> sampai dengan <big>Rp. $d->harga_atas</big>");
    }
    echo "<br>";
    echo "<form name='Form9' action='actions/310.insert.php' method=POST>\n";
    if (is_array($_SESSION["layanan"])) {
        $n = 0;
        foreach($_SESSION["layanan"] as $k => $l) {
            echo "<INPUT TYPE=HIDDEN NAME='id[$n]'     VALUE='".$l["id"].     "'>\n";
            echo "<INPUT TYPE=HIDDEN NAME='jumlah[$n]' VALUE='".$l["jumlah"]. "'>\n";
            echo "<INPUT TYPE=HIDDEN NAME='harga[$n]'  VALUE='".$l["harga"].  "'>\n";
            $n++;
        }
    }
    echo "<table border=0 width='100%'><td align=right>";
    echo "<input type=button value='Simpan' onClick='document.Form9.submit()'>&nbsp;";
    echo "<input type=button value='Simpan &amp; Bayar'>&nbsp;";
    echo "<input type=button value='Batal'>";
    echo "</td></tr></table></form>";
    
    echo "\n<script language='JavaScript'>\n";
    echo "function selectLayanan() {\n";
    echo "    sWin = window.open('popup/layanan.php', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "function refreshSubmit() {\n";
    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
    echo "}\n";
    echo "refreshSubmit();\n";
    echo "</script>\n";

} else {
    echo "<DIV class=BOX>";
    $f = new Form($SC, "GET");
    $f->hidden("p", $PID);
    $f->text("reg","Masukkan Nomor Registrasi",10,10,$_GET["reg"]);
    $f->submit(" Transaksi ");
    $f->execute();
    if ($msg) errmsg("Error:", $msg);
    echo "</DIV>";
}

?>