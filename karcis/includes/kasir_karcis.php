<? // efrizal, 06-01-2011
   
$PID = "kasir_karcis";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("<img src='icon/informasi-2.gif' align='absmiddle' >  KASIR KARCIS");
if(strlen($_GET["e"]) > 0) {
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    if($_GET["e"] == "new") {
     
            $r8 = pg_query($con,"select max(id) as id from kasir_karcis");
            $d8 = pg_fetch_object($r8);
            pg_free_result($r8);

unset($_SESSION["jmk"]);
  //echo "".$_COOKIE["SELECT_KARCIS"]  ;
if (isset($_SESSION["SELECT_KARCIS"])) {

    $_SESSION["jmk"]["id"]   = $_SESSION["SELECT_KARCIS"];
    $efri = getFromTable("select jmk from master_karcis where id = '".$_SESSION["SELECT_KARCIS"]."'");
    $_SESSION["jmk"]["jmk"] =
        getFromTable("select tdesc from rs00001 where tt = 'JMK' and tc = '".$efri."'");
    $_SESSION["jmk"]["code"] =
        getFromTable("select code from master_karcis where id = '".$_SESSION["SELECT_KARCIS"]."'");
    $_SESSION["jmk"]["harga"] =
    	getFromTable("select harga  from master_karcis where id = '".$_SESSION["SELECT_KARCIS"]."'");
    unset($_SESSION["SELECT_KARCIS"]);
    
}
echo "<form action='actions/kasir_karcis.insert.php' method=POST name=Formx>";
echo "<table border=0>";
echo "<TR>\n";
echo "<TD width='157' CLASS=FORM_SUBTITLE ALIGN=left COLSPAN=3>Input Karcis</TD>\n";
echo "</TR>\n\n";
echo "<tr><td class=FORM>Kode Karcis</td><td class=FORM>:</td>";
echo "    <td class=FORM width=1><input style='text-align:left' type=TEXT name=id size=5 maxlength=5 value='".$_SESSION["jmk"]["id"]."' disabled></td>";
echo "    <td class=FORM width=500><a href='javascript:selectkarcis()'>".icon("view")."</a></td></tr>";
echo "<tr><td class=FORM>Jenis Karcis</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=text name=jmk size=50 maxlength=50 value='".$_SESSION["jmk"]["jmk"]."' disabled></td></tr>";
echo "<tr><td class=FORM>Nama Layanan</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=text name=code size=50 maxlength=50 value='".$_SESSION["jmk"]["code"]."' disabled></td></tr>";
echo "<tr><td class=FORM>Harga</td><td class=FORM>:</td>";
echo "	  <td class=FORM colspan=2><input type=text name=harga size=50 maxlength=50 value='".$_SESSION["jmk"]["harga"]."' disabled></td></tr>";
echo "<tr><td class=FORM>Nama </td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=nama size=50 maxlength=50 value='".$_SESSION["jmk"]["nama"]."'></td></tr>";
echo "<tr><td class=FORM>Alamat</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=alamat size=50 maxlength=50 value='".$_SESSION["jmk"]["alamat"]."'></td></tr>";
echo "<tr><td class=FORM colspan=1><input type=HIDDEN name=idk size=50 maxlength=50 value='".$_SESSION["jmk"]["id"]."'></td><td class=FORM>&nbsp;</td>";
echo "    <td class=FORM colspan=2><input type=SUBMIT value='Submit'></td></tr>";
echo "</tr></table>";
echo "</form>";
echo "\n<script language='JavaScript'>\n";
echo "function selectkarcis() {\n";
echo "    sWin = window.open('popup/karcis.php', 'xWin',".
     "    'width=500,height=400,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

    } else {
        $r2 = pg_query($con,
            "select * ".
            "from kasir_karcis ".
            "where id='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
            $ri = pg_query($con,"select harga from master_karcis");
            $di = pg_fetch_object($ri);
            pg_free_result($ri);
            //$_GET["did"] = str_pad(((int) $d2->id), 3, "0", STR_PAD_LEFT);
            $_GET["did"] = $d2->poli;
//$_SESSION["SELECT_KARCIS"]= $_GET["did"];
unset($_SESSION["jmk"]);
  //echo "".$_COOKIE["SELECT_KARCIS"]  ;
//$_SESSION["SELECT_KARCIS"]= $_GET["did"];
if (isset($_SESSION["SELECT_KARCIS"])) {
    $_SESSION["jmk"]["id"]   = $_SESSION["SELECT_KARCIS"];
    $efri = getFromTable("select jmk from master_karcis where id = '".$_SESSION["SELECT_KARCIS"]."'");
    $_SESSION["jmk"]["jmk"] =
        getFromTable("select tdesc from rs00001 where tt = 'JMK' and tc = '".$efri."'");
    $_SESSION["jmk"]["code"] =
        getFromTable("select code from master_karcis where id = '".$_SESSION["SELECT_KARCIS"]."'");
    $_SESSION["jmk"]["harga"] =
    	getFromTable("select harga  from master_karcis where id = '".$_SESSION["SELECT_KARCIS"]."'");
    $_SESSION["jmk"]["nama"] = $d2->nama;
    $_SESSION["jmk"]["alamat"] = $d2->alamat;
    unset($_SESSION["SELECT_KARCIS"]);
}else{
    
    $_SESSION["SELECT_KARCIS"]= $_GET["did"];
    $_SESSION["jmk"]["id"]   = $_SESSION["SELECT_KARCIS"];
    $efri = getFromTable("select jmk from master_karcis where id = '".$_SESSION["SELECT_KARCIS"]."'");
    $_SESSION["jmk"]["jmk"] =
        getFromTable("select tdesc from rs00001 where tt = 'JMK' and tc = '".$efri."'");
    $_SESSION["jmk"]["code"] =
        getFromTable("select code from master_karcis where id = '".$_SESSION["SELECT_KARCIS"]."'");
    $_SESSION["jmk"]["harga"] =
    	getFromTable("select harga  from master_karcis where id = '".$_SESSION["SELECT_KARCIS"]."'");
    $_SESSION["jmk"]["nama"] = $d2->nama;
    $_SESSION["jmk"]["alamat"] = $d2->alamat;
    unset($_SESSION["SELECT_KARCIS"]);
}
echo "<form action='actions/kasir_karcis.update.php' method=POST name=Formx>";
echo "<table border=0>";
echo "<TR>\n";
echo "<TD width='157' CLASS=FORM_SUBTITLE ALIGN=left COLSPAN=3>Edit Karcis</TD>\n";
echo "</TR>\n\n";
echo "<tr><td class=FORM>Kode Karcis</td><td class=FORM>:</td>";
echo "    <td class=FORM width=1><input style='text-align:left' type=TEXT name=id size=5 maxlength=5 value='".$_SESSION["jmk"]["id"]."' disabled></td>";
echo "    <td class=FORM width=500><a href='javascript:selectkarcis()'>".icon("view")."</a></td></tr>";
echo "<tr><td class=FORM>Jenis Karcis</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=text name=jmk size=50 maxlength=50 value='".$_SESSION["jmk"]["jmk"]."' disabled></td></tr>";
echo "<tr><td class=FORM>Nama Layanan</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=text name=code size=50 maxlength=50 value='".$_SESSION["jmk"]["code"]."' disabled></td></tr>";
echo "<tr><td class=FORM>Harga</td><td class=FORM>:</td>";
echo "	  <td class=FORM colspan=2><input type=text name=harga size=50 maxlength=50 value='".$_SESSION["jmk"]["harga"]."' disabled></td></tr>";
echo "<tr><td class=FORM>Nama </td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=nama size=50 maxlength=50 value='".$_SESSION["jmk"]["nama"]."'></td></tr>";
echo "<tr><td class=FORM>Alamat</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=alamat size=50 maxlength=50 value='".$_SESSION["jmk"]["alamat"]."'></td></tr>";
echo "<tr><td class=FORM colspan=1><input type=HIDDEN name=idk size=50 maxlength=50 value='".$_SESSION["jmk"]["id"]."'></td><td class=FORM colspan=1><input type=HIDDEN name=idp size=50 maxlength=50 value='".$_GET["e"]."'></td>";
echo "    <td class=FORM colspan=2><input type=SUBMIT value='Submit'></td></tr>";
echo "</tr></table>";
echo "</form>";
echo "\n<script language='JavaScript'>\n";
echo "function selectkarcis() {\n";
echo "    sWin = window.open('popup/karcis.php', 'xWin',".
     "    'width=500,height=400,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

    }
} else {
    echo "<BR>";
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
//   echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
//    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

    echo "</TR></FORM></TABLE></DIV>";
    $tglhariini = date("Y-m-d", time());
    $t = new PgTable($con, "100%");
    $t->SQL = 
        "select a.nama, a.alamat, a.tanggal_reg, c.tdesc, b.code, a.id as dummy ".
        "from kasir_karcis a ".
        "left join master_karcis b ON b.id = a.poli  ".
        "left join rs00001 c ON c.tt = 'JMK' and c.tc = b.jmk  ".
        "where ".
        "date(a.tanggal_reg) = '$tglhariini' and ".
        "(upper(nama) LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR upper(alamat) LIKE '%".strtoupper($_GET["search"])."%') ".
        //"GROUP BY a.nama, a.alamat, a.tanggal_reg, b.tdesc, a.id  ".
		"ORDER by (a.tanggal_reg) desc".
		"";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColAlign[4] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#5#>'>".icon("edit","Edit")."</A>".
            "<A CLASS=TBL_HREF HREF='actions/kasir_karcis.delete.php?p=$PID&id=<#5#>'>".icon("delete","Hapus")."</A>".
			"<A CLASS=TBL_HREF HREF='includes/cetak.karcis.php?rg=<#5#>'>".icon("print","Cetak")."</A>";;;
    $t->ColHeader = array("NAMA", "ALAMAT", "TANGGAL/WAKTU","KARCIS","POLIKLINIK","");
    
    $t->execute();
     echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Data Baru </A></DIV>";
}
?>
