<? // sfdn, 30-04-2004

$PID = "apotik_umum";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");


    
    
if($_GET["e"]) {
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&e=new'>".icon("back","Kembali")."</a></DIV>";
    if($_GET["e"] == "new") {

            $r8 = pg_query($con,"select max(code) as code from apotik_umum");
            $d8 = pg_fetch_object($r8);
            pg_free_result($r8);
            $_GET["code"] = ((int) $d8->code) + 1;
            //$_GET["code"] = str_pad(((int) $d8->code) + 1, 5, "0", STR_PAD_LEFT);
            $tglhariini = date("Y-m-d", time());
            $jam 	= getFromTable("select to_char(CURRENT_TIMESTAMP,'HH24:MI:SS') as jam");
/*        $f = new Form("actions/hrd_shift.insert.php");
        
        //$f->text("code","Kode",3,3,$_GET["code"],"DISABLED");
        $f->hidden("f_code",$_GET["code"]);
        $f->text("f_nama","Nama",30,30,"");
        $f->text("f_mr","MR",10,10,"");
        $f->text("f_umur","Umur",10,10,"");
        $f->hidden("f_tanggal",$tglhariini);
        $f->hidden("f_jam",$jam);
        $f->selectArray("f_sex", "Jenis Kelamin",Array("Pria" => "Pria", "Wanita" => "Wanita"), "Pria", "");
        //$f->textinfo("f_jm_mulai","Jadwal Masuk",8,8,"00:00:00","(Jam:Menit:Detik, contoh ==> 08:09:16)",$ext);
        //$f->textinfo("f_jm_selesai","Jadwal Pulang",8,8,"00:00:00","(Jam:Menit:Detik, contoh ==> 08:09:16)",$ext);
*/

if ($_GET["httpHeader"] == "1") {
    if (isset($_GET["nama"])) {
        $_SESSION["um1"]["nama"] = $_GET["nama"];
    }
    if (isset($_GET["mr"])) {
        $_SESSION["um1"]["mr"] = $_GET["mr"];
    }
    if (isset($_GET["umur"])) {
        $_SESSION["um1"]["umur"] = $_GET["umur"];
    }
    if (isset($_GET["kelamin"])) {
        $_SESSION["um1"]["kelamin"] = $_GET["kelamin"];
    }
    if (strlen($_GET["um1_id"]) > 0 && $_GET["um1_jumlah"] > 0) {
        if (is_array($_SESSION["um1"]["obat"])) {
            $cnt = count($_SESSION["um1"]["obat"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_GET["um1_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        $_SESSION["um1"]["obat"][$cnt]["id"]     = $d1->id;
        $_SESSION["um1"]["obat"][$cnt]["obat"]   = $d1->obat;
        $_SESSION["um1"]["obat"][$cnt]["satuan"] = $d1->satuan;
        $_SESSION["um1"]["obat"][$cnt]["jumlah"] = $_GET["um1_jumlah"];
        $_SESSION["um1"]["obat"][$cnt]["harga"]  = $d1->harga;
        $_SESSION["um1"]["obat"][$cnt]["total"]  = $d1->harga * $_GET["um1_jumlah"];
        unset($_SESSION["SELECT_OBAT"]);
    }
    if (isset($_GET["del"])) {
        $temp = $_SESSION["um1"]["obat"];
        unset($_SESSION["um1"]["obat"]);
        $cnt = 0;
        foreach ($temp as $k => $v) {
            if ($k != $_GET["del"]) {
                $_SESSION["um1"]["obat"][$cnt] = $v;
                $cnt++;
            }
        }
    }
    if (isset($_GET["editrow"])) {
        $_SESSION["um1"]["obat"][$_GET["editrow"]]["jumlah"] = $_GET["editjumlah"];
        $_SESSION["um1"]["obat"][$_GET["editrow"]]["total"]  =
            $_SESSION["um1"]["obat"][$_GET["editrow"]]["jumlah"] *
            $_SESSION["um1"]["obat"][$_GET["editrow"]]["harga"];
    }
    header("Location: $SC?p=$PID");
    exit;
}

        title("Transaksi Apotik Baru");
        echo "<BR>";
        //echo "".$_COOKIE["SELECT_SUPPLIER"]  ;
        echo "<form action=$SC name=formx>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
echo "<table border=0>";
echo "<tr><td class=FORM>Kode </td><td class=FORM>:</td>";
echo "    <td class=FORM width=1><input name=code1 size=10 maxlength=10 value='".$_GET["code"]."' DISABLED></td>";
echo "<tr><td class=FORM>Nama </td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=text name=nama size=40 maxlength=50 value='".$_SESSION["um1"]["nama"]."' ></td></tr>";
echo "<tr><td class=FORM>MR </td><td class=FORM>:</td>";
echo "	  <td class=FORM colspan=2><input type=text name=mr size=10 maxlength=10 value='".$_SESSION["um1"]["mr"]."' ></td></tr>";
echo "<tr><td class=FORM>Umur </td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=umur size=5 maxlength=5 value='".$_SESSION["um1"]["umur"]."'></td></tr>";
echo "<TR ><TD CLASS=FORM>Tanggal </TD><TD CLASS=FORM>:</TD>\n";
echo "    <TD CLASS=FORM colspan=2><INPUT TYPE=TEXT NAME=tanggal SIZE=10 MAXLENGTH=12 VALUE='".$tglhariini."' disabled></td></tr>";
echo "<TR ><TD CLASS=FORM>Jam </TD><TD CLASS=FORM>:</TD>\n";
echo "    <TD CLASS=FORM colspan=2><INPUT TYPE=TEXT NAME=jam SIZE=10 MAXLENGTH=12 VALUE='".$jam."' disabled></td></tr>";
echo "<tr><td class=FORM>Jenis Kelamin </td><td class=FORM>:</td>";
echo "    <td class=FORM width=1><select name=kelamin ;'>";
echo "      <option value=''></option>";
echo "      <option value='RJ' "; if ($_GET[kelamin] == "Pria") echo "selected"; echo ">Pria</option>";
echo "      <option value='RI' "; if ($_GET[kelamin] == "Wanita") echo "selected"; echo ">Wanita</option>";
//echo "<A HREF=\"#\" onClick=\"cal.select(document.forms['formx'].tanggal_pengadaan,'tanggalan','dd-MM-yyyy'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' SRC='icon/calendar.gif' TITLE='Pilih' ></A></TD>\n";
echo "</TR>\n\n";
echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
echo "    <td class=FORM colspan=2><input type=SUBMIT value='Submit' name=san></td></tr>";
echo "</tr></table>";
echo "</form>";
//echo "\n<script language='JavaScript'>\n";
//echo "function selectSupplier() {\n";
//echo "    sWin = window.open('popup/supplier.php', 'xWin',".
//     "    'width=500,height=400,menubar=no,scrollbars=yes');\n";
//echo "    sWin.focus();\n";
//echo "}\n";
//echo "</script>\n";

//if (isset($_SESSION["um1"]["nama"]) && isset($_GET["code"]) ) {
if ( ($_GET["san"]) ) {
    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }

    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("KODE", "Nama Obat", "Jumlah", "Satuan",
                               "Harga Satuan", "Harga Total",""));
    if (is_array($_SESSION["um1"]["obat"])) {
        $total = 0.00;
        foreach($_SESSION["um1"]["obat"] as $k => $o) {
            if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
                echo "<form action=$SC>";
                echo "<input type=hidden name=p value=$PID>";
                echo "<input type=hidden name=editrow value=$k>";
                echo "<input type=hidden name=httpHeader value=1>";
                //echo "<input type=hidden name=status value=".$_GET[status].">";
                $t->printRow(
                    Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                        $o["obat"],
                        "<input type=text size=5 maxlength=10 name=editjumlah value='".$o["jumlah"]."' style='text-align:right'>",
                        $o["satuan"],
                        number_format($o["harga"],2,',','.'),
                        number_format($o["total"],2,',','.'),
                        "<input type=submit value='Simpan'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID\"'>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "RIGHT",
                        "RIGHT",
                        "CENTER")
                    );
                echo "</form>";
            } else {
                $t->printRow(
                    Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                        $o["obat"],
                        $o["jumlah"],
                        $o["satuan"],
                        number_format($o["harga"],2,',','.'),
                        number_format($o["total"],2,',','.'),
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k'>".icon("del-left","Hapus")."</a>".
                        " &nbsp; " .
                        "<a href='$SC?p=$PID&edit=$k'>".icon("edit","Edit")."</a>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "RIGHT",
                        "RIGHT",
                        "CENTER")
                    );
            }
            $total += $o["total"];
        }
    }

    if (strlen($_GET["edit"]) == 0) {

        echo "<form action=$SC>";
        echo "<input type=hidden name=p value=$PID>";
        echo "<input type=hidden name=httpHeader value=1>";
        //echo "<input type=hidden name=status value=".$_GET[status].">";
        $t->printRow(
            Array(
            	"<input type=text size=5 maxlength=10 name=um1_id style='text-align:center' value=$d1->id>"."&nbsp;<a href='javascript:selectObat()'>".icon("view")."</a>",
                $d1->obat,
                "<input type=text size=5 maxlength=10 name=um1_jumlah value=1 style='text-align:right'>",
                $d1->satuan,
                $d1->harga,
                number_format($total,2,',','.'),
                "<input type=submit value=Tambah&nbsp;Obat>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "RIGHT",
                        "RIGHT",
                        "CENTER")
                    );
        echo "</FORM>";
    }
    $t->printTableClose();



    if (is_array($_SESSION["um1"]["obat"])) {
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/apotik_umum.insert.php' method=POST name=Form10>";
        //echo "<input type=hidden name=status value=".$_GET["status"].">";
        echo "<input type=submit value=' &nbsp; Simpan &nbsp; '>";
        echo "</form>";
        echo "</div>";
    }


    echo "\n<script language='JavaScript'>\n";
    echo "function selectObat() {\n";
    echo "    sWin = window.open('popup/obat.php', 'xWin', 'width=550,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
}

    } /*else {
        $r2 = pg_query($con,
            "select * ".
            "from hrd_shift ".
            "where code='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $f = new Form("actions/hrd_shift.update.php");
        $f->subtitle("Edit Data Shift");
        echo "<BR>";
        $f->hidden("code",$_GET["e"]);
        $f->textinfo("f_code","Kode",3,3,$_GET["e"],"","DISABLED");
        $f->text("f_shift","Nama Shift",30,30,$d2->shift);
        $f->textinfo("f_jm_mulai","Jadwal Masuk",8,8,$d2->jm_mulai,"(Jam:Menit:Detik, contoh ==> 08:09:16)",$ext);
        $f->textinfo("f_jm_selesai","Jadwal Pulang",8,8,$d2->jm_selesai,"(Jam:Menit:Detik, contoh ==> 08:09:16)",$ext);
    }*/

    //$f->submit(" Simpan ");
    //$f->execute();
   /* echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }*/
} else {
        if (!$GLOBALS['print']){
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > APOTIK NON PASIEN");
	}else{
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > LAPORAN APOTIK NON PASIEN ");
	}
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    include(xxx2);
    if (!$GLOBALS['print']){
        $f->text("search","Search Nama",50,50,$_GET["search"]);
	$f->submit ("TAMPILKAN");
	}
    $f->execute();
    

    $tb = new PgTable($con, "100%");
    $tb->SQL =
        "select nama, mr, to_char(tanggal,'DD-MM-YYYY') as tgl, umur, sex, code as href ".
        "from apotik_umum ".
        "where ".
        //"(upper(code) LIKE '%".strtoupper($_GET["search"])."%' ".
        //"OR upper(shift) LIKE '%".strtoupper($_GET["search"])."%' ".
        "(tanggal between '$ts_check_in1' and '$ts_check_in2') ".
        "and upper(nama) LIKE '%".strtoupper($_GET["search"])."%' ".
        "";
    $tb->setlocale("id_ID");
    $tb->ShowRowNumber = true;
    $tb->RowsPerPage = $ROWS_PER_PAGE;
    $tb->ColAlign[0] = "CENTER";
    $tb->ColAlign[5] = "CENTER";
    $tb->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#5#>'>".icon("view","View")."</A>";
            //"<A CLASS=TBL_HREF HREF='actions/hrd_shift.delete.php?p=$PID&code=<#4#>'>".icon("delete","Hapus")."</A>";;
    $tb->ColHeader = array("NAMA", "NO MR", "TANGGAL", "UMUR", "J.KELAMIN","");

    $tb->execute();
    if (!$GLOBALS['print']){
	echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Transaksi Baru </A></DIV>";
	}
     
}
?>
