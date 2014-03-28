<?php // Nugraha, Thu Apr 22 23:29:37 WIT 2004
      // sfdn, 08-05-2004
	  // tokit, 11-07-2004 

$PID = "340";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");


if ($_GET["httpHeader"] == "1") {
    if (strlen($_GET["ob2_id"]) > 0 && $_GET["ob2_jumlah"] > 0) {
        if (is_array($_SESSION["ob2"])) {
            $cnt = count($_SESSION["ob2"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_GET["ob2_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        $_SESSION["ob2"][$cnt]["id"]     = $d1->id;
        $_SESSION["ob2"][$cnt]["obat"]   = $d1->obat;
        $_SESSION["ob2"][$cnt]["satuan"] = $d1->satuan;
        $_SESSION["ob2"][$cnt]["jumlah"] = $_GET["ob2_jumlah"];
        $_SESSION["ob2"][$cnt]["harga"]  = $d1->harga;
        $_SESSION["ob2"][$cnt]["total"]  = $d1->harga * $_GET["ob2_jumlah"];
        unset($_SESSION["SELECT_OBAT"]);
    }
    if (isset($_GET["del"])) {
        $temp = $_SESSION["ob2"];
        unset($_SESSION["ob2"]);
        $cnt = 0;
        foreach ($temp as $k => $v) {
            if ($k != $_GET["del"]) {
                $_SESSION["ob2"][$cnt] = $v;
                $cnt++;
            }
        }
    }
    if (isset($_GET["editrow"])) {
        $_SESSION["ob2"][$_GET["editrow"]]["jumlah"] = $_GET["editjumlah"];
        $_SESSION["ob2"][$_GET["editrow"]]["total"]  =
            $_SESSION["ob2"][$_GET["editrow"]]["jumlah"] * $_SESSION["ob2"][$_GET["editrow"]]["harga"];
    }
    if (strlen($_GET["resep"]) > 0) {
        $r1 = pg_query($con,
            "select rsv0004.id, obat, qty, satuan, rsv0004.harga, ".
            "rs00009.description as dosis, rs00008.id as ref ".
            "from rsv0004 join rs00008 on rs00008.item_id = rsv0004.id and rs00008.trans_type = 'OB1' ".
            "left join rs00009 on rs00008.id = rs00009.trans_id ".
            "where trans_group = '".$_GET["resep"]."'"
        );
        $cnt = 0;
        unset($_SESSION["ob2"]);
        unset($_SESSION["resep"]);
        while ($d1 = pg_fetch_object($r1)) {
            if ($cnt == 0) {
                $_SESSION["resep"] = $_GET["resep"];
            }
            $_SESSION["ob2"][$cnt]["id"]     = $d1->id;
            $_SESSION["ob2"][$cnt]["obat"]   = $d1->obat;
            $_SESSION["ob2"][$cnt]["satuan"] = $d1->satuan;
            $_SESSION["ob2"][$cnt]["jumlah"] = $d1->qty;
            $_SESSION["ob2"][$cnt]["harga"]  = $d1->harga;
            $_SESSION["ob2"][$cnt]["total"]  = $d1->harga * $d1->qty;
            $_SESSION["ob2"][$cnt]["dosis"]  = $d1->dosis;
            $_SESSION["ob2"][$cnt]["ref"]    = $d1->ref;
            $cnt++;
        }
        pg_free_result($r1);
    }
    header("Location: $SC?p=$PID");
    exit;
}

title("Transaksi Obat");
echo "<br>";

echo "<table border=0 width='100%'><tr><td>";
    if (isset($_SESSION["resep"])) {
        $rg = (int) getFromTable("select no_reg from rs00008 where trans_group = '".$_SESSION["resep"]."'");
        $r2 = pg_query($con, "select * ".
                             "from rsv0002 ".
                             "where to_number(id,'9999999999') = $rg");
        $n = pg_num_rows($r2);
        if($n > 0) $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        // tambahan sfdn
        $rawat = "RAWAT INAP";
        if ($d2->rawat_inap == "N") {
            $rawat = "RAWAT JALAN";
        } elseif ($d2->rawat_inap == "I") {
            $rawat = "IGD";
        }
        // akhir tambahan sfdn
        $f = new ReadOnlyForm();
        $f->text("Nomor MR", $d2->mr_no);
        $f->text("Nomor Registrasi", formatRegNo($d2->id));
        $f->text("Tanggal Reg.", $d2->tanggal_reg_str);
        $f->text("Nama", $d2->nama);
        //$f->text("Pasien Dari", $d2->rawat_inap == "Y" ? "Rawat Inap" : "Rawat Jalan" );
        // diganti oleh sfdn
        $f->text("Pasien Dari",$rawat);
        // akhir ganti
        $f->execute();
    }
echo "</td><td valign=bottom>";
    echo "<form action=$SC>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=httpHeader value=1>";
    echo "<table border=0 width='100%'><tr>";
    echo "<td align=right class=TBL_BODY width='99%'><b>Resep #</b></td>";
    echo "<td align=right><input type=text size=6 maxlength=10 name=resep style='text-align:center' value='".$_SESSION["resep"]."'></td>";
    echo "</tr><tr>";
    echo "<td align=right colspan=2><input type=submit value='Submit'></td>";
    echo "</tr>";
    if (isset($_SESSION["resep"]))
        echo "<tr><td align=right class=TBL_BODY colspan=2><a class=TBL_HREF href='$SC?p=$PID&httpHeader=1&resep=0'>Klik disini untuk transaksi tanpa resep</a></td></tr>";
    echo "</table>";
    echo "</form>";
echo "</td></tr></table>";
echo "<br>";

if ($_SESSION["SELECT_OBAT"]) {
    $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
    $d1 = pg_fetch_object($r1);
    pg_free_result($r1);
}

if (isset($_SESSION["resep"])) {
    $ref = getFromTable(
               "select referensi from rs00008 ".
               "where trans_group = '".$_SESSION["resep"]."' ".
               "and trans_type = 'OB1'"
           );
    if ($ref == "F") {
        info("Informasi:", "Resep dengan nomor ".$_SESSION["resep"]." sudah pernah dilayani.");
        echo "<br>";
    }
}

$t = new BaseTable("100%");
$t->printTableOpen();
$t->printTableHeader(Array("KODE", "Nama Obat", "Jumlah", "Satuan",
                           "Harga Satuan", "Harga Total", ""));
if (is_array($_SESSION["ob2"])) {
    $total = 0.00;
    foreach($_SESSION["ob2"] as $k => $o) {
        if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
            echo "<form action=$SC>";
            echo "<input type=hidden name=p value=$PID>";
            echo "<input type=hidden name=editrow value=$k>";
            echo "<input type=hidden name=httpHeader value=1>";
            $t->printRow(
                Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                    $o["obat"],
                    "<input type=text size=5 maxlength=10 name=editjumlah value='".$o["jumlah"]."' style='text-align:right'>",
                    $o["satuan"],
                    number_format($o["harga"],2),
                    number_format($o["total"],2),
                    "<input type=submit value='Update'>".
                    " &nbsp; " .
                    "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID\"'>" ),
                Array( "CENTER",
                    "LEFT",
                    "RIGHT",
                    "LEFT",
                    "RIGHT",
                    "RIGHT",
                    "CENTER" )
                );
            echo "</form>";
        } else {
            $t->printRow(
                Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                    $o["obat"],
                    $o["jumlah"],
                    $o["satuan"],
                    number_format($o["harga"],2),
                    number_format($o["total"],2),
                    "<a href='$SC?p=$PID&httpHeader=1&del=$k'>".icon("del-left")."</a>".
                    " &nbsp; " .
                    "<a href='$SC?p=$PID&edit=$k'>".icon("edit")."</a>" ),
                Array( "CENTER",
                    "LEFT",
                    "RIGHT",
                    "LEFT",
                    "RIGHT",
                    "RIGHT",
                    "CENTER" )
                );
        }
        $total += $o["total"];
    }
}

if (strlen($_GET["edit"]) == 0) {
    echo "<form action=$SC>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=httpHeader value=1>";
    $t->printRow(
        Array( "<input type=text size=5 maxlength=10 name=ob2_id style='text-align:center' value=$d1->id>".
            "&nbsp;<a href='javascript:selectObat()'>".icon("view")."</a>",
            $d1->obat,
            "<input type=text size=5 maxlength=10 name=ob2_jumlah value=1 style='text-align:right'>",
            $d1->satuan,
            $d1->harga,
            number_format($total,2),
            "<input type=submit value=Tambah>" ),
        Array( "CENTER",
            "LEFT",
            "CENTER",
            "LEFT",
            "RIGHT",
            "RIGHT",
            "CENTER" )
        );
    echo "</FORM>";
}
$t->printTableClose();

if (is_array($_SESSION["ob2"])) {
    echo "<br>";
    echo "<div align=right>";
    echo "<form action='actions/340.insert.php' method=POST name=Form10>";
    echo "<input type=hidden name=tanpa_bayar value=0>";
    echo "<input type=submit value='Simpan &amp; Bayar'>";
    if (isset($_SESSION["resep"]) && $d2->rawat_inap == "Y") {
        echo " &nbsp; <input type=submit value='Simpan' onClick='document.Form10.tanpa_bayar.value=\"1\";'>";
    }
    echo "</form>";
    echo "</div>";
}

echo "\n<script language='JavaScript'>\n";
echo "function selectObat() {\n";
echo "    sWin = window.open('popup/obat.php', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

?>
