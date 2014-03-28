<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 23-04-2004
      // sfdn, 09-05-2004

$PID = "551";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if ($_GET["httpHeader"] == "1") {
    if (isset($_GET["nomor_bukti"])) {
        $_SESSION["ob7"]["nomor-bukti"] = $_GET["nomor_bukti"];
    }
    if (strlen($_GET["ob7_id"]) > 0 && $_GET["ob7_jumlah"] > 0) {
        if (is_array($_SESSION["ob7"]["obat"])) {
            $cnt = count($_SESSION["ob7"]["obat"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_GET["ob7_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        $_SESSION["ob7"]["obat"][$cnt]["id"]     = $d1->id;
        $_SESSION["ob7"]["obat"][$cnt]["obat"]   = $d1->obat;
        $_SESSION["ob7"]["obat"][$cnt]["satuan"] = $d1->satuan;
        $_SESSION["ob7"]["obat"][$cnt]["jumlah"] = $_GET["ob7_jumlah"];
        $_SESSION["ob7"]["obat"][$cnt]["harga"]  = $d1->harga;
        $_SESSION["ob7"]["obat"][$cnt]["total"]  = $d1->harga * $_GET["ob7_jumlah"];
        unset($_SESSION["SELECT_OBAT"]);
    }
    if (isset($_GET["del"])) {
        $temp = $_SESSION["ob7"]["obat"];
        unset($_SESSION["ob7"]["obat"]);
        $cnt = 0;
        foreach ($temp as $k => $v) {
            if ($k != $_GET["del"]) {
                $_SESSION["ob7"]["obat"][$cnt] = $v;
                $cnt++;
            }
        }
    }
    if (isset($_GET["editrow"])) {
        $_SESSION["ob7"]["obat"][$_GET["editrow"]]["jumlah"] = $_GET["editjumlah"];
        $_SESSION["ob7"]["obat"][$_GET["editrow"]]["total"]  =
            $_SESSION["ob7"]["obat"][$_GET["editrow"]]["jumlah"] *
            $_SESSION["ob7"]["obat"][$_GET["editrow"]]["harga"];
    }
    header("Location: $SC?p=$PID");
    exit;
}

title("Transaksi Penerimaan Barang Inst. Gizi");
echo "<br>";

if (isset($_SESSION["SELECT_SUPPLIER"])) {
    $_SESSION["ob7"]["supplier"]["id"]   = $_SESSION["SELECT_SUPPLIER"];
    $_SESSION["ob7"]["supplier"]["name"] =
        getFromTable("select nama from rs00028 where id = '".$_SESSION["SELECT_SUPPLIER"]."'");
    unset($_SESSION["SELECT_SUPPLIER"]);
}

echo "<form action=$SC>";
        echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
echo "<table border=0>";
echo "<tr><td class=FORM>Kode Pemasok</td><td class=FORM>:</td>";
echo "    <td class=FORM width=1><input style='text-align:center' type=TEXT name=supplier size=5 maxlength=10 value='".$_SESSION["ob7"]["supplier"]["id"]."' DISABLED></td>";
echo "    <td class=FORM><a href='javascript:selectSupplier()'>".icon("view")."</a></td></tr>";
echo "<tr><td class=FORM>Nama Pemasok</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2>".$_SESSION["ob7"]["supplier"]["name"]."</td></tr>";
echo "<tr><td class=FORM>Nomor Bukti</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=nomor_bukti size=30 maxlength=30 value='".$_SESSION["ob7"]["nomor-bukti"]."'></td></tr>";
echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
echo "    <td class=FORM colspan=2><input type=SUBMIT value='Submit'></td></tr>";
echo "</table>";
echo "</form>";

echo "\n<script language='JavaScript'>\n";
echo "function selectSupplier() {\n";
echo "    sWin = window.open('popup/supplier.php', 'xWin',".
        " 'width=500,height=400,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

if (isset($_SESSION["ob7"]["supplier"]["id"]) && isset($_SESSION["ob7"]["nomor-bukti"])) {

    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }

    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("KODE", "Nama Barang", "Jumlah", "Satuan",
                               "Harga Satuan", "Harga Total", ""));
    if (is_array($_SESSION["ob7"]["obat"])) {
        $total = 0.00;
        foreach($_SESSION["ob7"]["obat"] as $k => $o) {
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
            Array( "<input type=text size=5 maxlength=10 name=ob7_id style='text-align:center' value=$d1->id>".
                "&nbsp;<a href='javascript:selectObat()'>".icon("view")."</a>",
                $d1->obat,
                "<input type=text size=5 maxlength=10 name=ob7_jumlah value=1 style='text-align:right'>",
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

    if (is_array($_SESSION["ob7"]["obat"])) {
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/551.insert.php' method=POST name=Form10>";
        echo "<input type=submit value=' &nbsp; Simpan &nbsp; '>";
        echo "</form>";
        echo "</div>";
    }

    echo "\n<script language='JavaScript'>\n";
    echo "function selectObat() {\n";
    echo "    sWin = window.open('popup/obat.php', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";

}

?>
