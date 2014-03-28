<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 23-04-2004

$PID = "360";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/visit_setting.php");

if ($_GET["httpHeader"] == "1") {
        

    if (strlen($_GET["ob5_id"]) > 0 && $_GET["ob5_jumlah"] > 0) {
        //$ra = pg_query($con, "select * from rs00016a where obat_id = '".$_GET["ob5_id"]."'");
        //$da = pg_fetch_object($ra);
        //pg_free_result($ra);
        $da = getFromTable("select gudang from rs00016a where obat_id = '".$_GET["ob5_id"]."'");
        if ($_GET["ob5_jumlah"] > $da){
        //if ($_GET["ob5_jumlah"] > $da->gudang){
            echo "<script>alert(\"Tidak ada data barang sebanyak itu.\");</script>";
            unset($_SESSION["SELECT_OBAT"]);
            //$_GET[kode] = $_GET[kode];
            //header("Location: $SC?p=$PID&kode=".$_GET[kode]);
            exit;
        }
        if (is_array($_SESSION["ob5"]["obat"])) {
            $cnt = count($_SESSION["ob5"]["obat"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_GET["ob5_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        $_SESSION["ob5"]["obat"][$cnt]["id"]     = $d1->id;
        $_SESSION["ob5"]["obat"][$cnt]["obat"]   = $d1->obat;
        $_SESSION["ob5"]["obat"][$cnt]["satuan"] = $d1->satuan;
        $_SESSION["ob5"]["obat"][$cnt]["jumlah"] = $_GET["ob5_jumlah"];
        $_SESSION["ob5"]["obat"][$cnt]["harga"]  = $d1->harga;
        $_SESSION["ob5"]["obat"][$cnt]["total"]  = $d1->harga * $_GET["ob5_jumlah"];
        unset($_SESSION["SELECT_OBAT"]);
    }
    if (isset($_GET["del"])) {
        $temp = $_SESSION["ob5"]["obat"];
        unset($_SESSION["ob5"]["obat"]);
        $cnt = 0;
        foreach ($temp as $k => $v) {
            if ($k != $_GET["del"]) {
                $_SESSION["ob5"]["obat"][$cnt] = $v;
                $cnt++;
            }
        }
    }
    if (isset($_GET["editrow"])) {
        $_SESSION["ob5"]["obat"][$_GET["editrow"]]["jumlah"] = $_GET["editjumlah"];
        $_SESSION["ob5"]["obat"][$_GET["editrow"]]["total"]  =
            $_SESSION["ob5"]["obat"][$_GET["editrow"]]["jumlah"] *
            $_SESSION["ob5"]["obat"][$_GET["editrow"]]["harga"];
    }
    header("Location: $SC?p=$PID&kode=".$_GET[kode]);
    exit;
}

title("<IMG SRC='icon/rawat-inap-2.gif' align='absmiddle' >  TRANSAKSI PENERIMAAN OBAT");
echo "<br>";
echo "<form action=$SC name=formx>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
echo "<table border=0>";
echo "<tr><td class=FORM>Untuk Layanan</td><td class=FORM>:</td>";
echo "    <td class=FORM width=1><select name=kode onchange='javascript: formx.submit();'>";
echo "				<option value=''></option>";
/*echo "			<option value='112'"; if ($_GET[kode] == "112") echo "selected"; echo ">POLIKLINIK JANTUNG</option>";
echo "				<option value='101'"; if ($_GET[kode] == "101") echo "selected"; echo ">POLIKLINIK UMUM</option>";
echo "				<option value='102'"; if ($_GET[kode] == "102") echo "selected"; echo ">POLIKLINIK MATA</option>";
echo "				<option value='103'"; if ($_GET[kode] == "103") echo "selected"; echo ">POLIKLINIK PENYAKIT DALAM</option>";
echo "				<option value='104'"; if ($_GET[kode] == "104") echo "selected"; echo ">POLIKLINIK ANAK</option>";
echo "				<option value='105'"; if ($_GET[kode] == "105") echo "selected"; echo ">POLIKLINIK GIGI DAN MULUT</option>";
echo "				<option value='106'"; if ($_GET[kode] == "106") echo "selected"; echo ">POLIKLINIK THT</option>";
echo "				<option value='107'"; if ($_GET[kode] == "107") echo "selected"; echo ">POLIKLINIK BEDAH</option>";
echo "				<option value='109'"; if ($_GET[kode] == "109") echo "selected"; echo ">POLIKLINIK KULIT DAN KELAMIN</option>";
echo "				<option value='110'"; if ($_GET[kode] == "110") echo "selected"; echo ">POLIKLINIK AKUPUNKTUR</option>";
echo "				<option value='113'"; if ($_GET[kode] == "113") echo "selected"; echo ">POLIKLINIK PARU / ALERGI</option>";
echo "				<option value='114'"; if ($_GET[kode] == "114") echo "selected"; echo ">POLIKLINIK OBSTETRI</option>";
echo "				<option value='115'"; if ($_GET[kode] == "115") echo "selected"; echo ">POLIKLINIK GINEKOLOGI</option>";
echo "				<option value='116'"; if ($_GET[kode] == "116") echo "selected"; echo ">POLIKLINIK PSIKIATRI</option>";
echo "				<option value='205'"; if ($_GET[kode] == "205") echo "selected"; echo ">POLIKLINIK FISIOTERAPI</option>";
echo "				<option value='203'"; if ($_GET[kode] == "203") echo "selected"; echo ">LABORATORIUM</option>";
echo "				<option value='204'"; if ($_GET[kode] == "204") echo "selected"; echo ">RADIOLOGI</option>"; */
//echo "				<option value='RJ' "; if ($_GET[kode] == "RJ") echo "selected"; echo ">APOTIK RAWAT JALAN</option>";
//echo "				<option value='RI' "; if ($_GET[kode] == "RI") echo "selected"; echo ">APOTIK RAWAT INAP</option>";
echo "				<option value='apotek' "; if ($_GET[kode] == "apotek") echo "selected"; echo ">APOTIK</option>";
echo "    			</select></td>";
echo "    <td class=FORM></td></tr>";
echo "</table>";
echo "</form>";


if ($_GET[kode]) {

    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }

    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("KODE", "Nama Obat", "Jumlah", "Satuan",
                               "Harga Satuan", "Harga Total", ""));
    if (is_array($_SESSION["ob5"]["obat"])) {
        $total = 0.00;
        foreach($_SESSION["ob5"]["obat"] as $k => $o) {
            if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
                echo "<form action=$SC>";
                echo "<input type=hidden name=p value=$PID>";
		echo "<input type=hidden name=kode value=".$_GET[kode].">";
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
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID&kode={$_GET["kode"]}\"'>" ),
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
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k&kode={$_GET["kode"]}'>".icon("del-left")."</a>".
                        " &nbsp; " .
                        "<a href='$SC?p=$PID&edit=$k&kode={$_GET["kode"]}'>".icon("edit")."</a>" ),
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
	echo "<input type=hidden name=kode value=".$_GET[kode].">";
        echo "<input type=hidden name=httpHeader value=1>";
        $t->printRow(
            Array( "<input type=text size=5 maxlength=10 name=ob5_id style='text-align:center' value=$d1->id>".
                "&nbsp;<a href='javascript:selectObat()'>".icon("view")."</a>",
                $d1->obat,
                "<input type=text size=5 maxlength=10 name=ob5_jumlah value=1 style='text-align:right'>",
                $d1->satuan,
                $d1->harga,
                number_format($total,2),
                "<input type=submit value=OK>" ),
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

    if (is_array($_SESSION["ob5"]["obat"])) {
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/360.insert.php' method=POST name=Form10>";
		echo "<input type=hidden name=kode value=".$_GET[kode].">";
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
