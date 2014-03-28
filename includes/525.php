<?php // Agung Sunandar 0:20 30/06/2012 -> Retur Barang

$PID = "525";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$_GET["penanggung_jawab"] = "AGUNG SUPRIHATIN, S.Si, Apt.";
$tgl_sekarang = date("d-m-Y", time());
if ($_GET["httpHeader"] == "1") {
    if (isset($_GET["nomor_po"])) {
        $_SESSION["ob4"]["nomor-po"] = $_GET["nomor_po"];
    }
    if (isset($_GET["penanggung_jawab"])) {
        $_SESSION["ob4"]["penanggung-jawab"] = $_GET["penanggung_jawab"];
    }
	if (isset($_GET["ppn"])) {
        $_SESSION["ob4"]["ppn"] = $_GET["ppn"];
    }
	if (isset($_GET["disc1"])) {
        $_SESSION["ob4"]["disc1"] = $_GET["disc1"];
    }
	if (isset($_GET["disc2"])) {
        $_SESSION["ob4"]["disc2"] = $_GET["disc2"];
    }
    if (isset($_GET["tanggal_pengadaan"])) {
        $_SESSION["ob4"]["tanggal-pengadaan"] = $_GET["tanggal_pengadaan"];
    }
    if (strlen($_GET["ob4_id"]) > 0 && $_GET["ob4_jumlah"] > 0) {
        if (is_array($_SESSION["ob4"]["obat"])) {
            $cnt = count($_SESSION["ob4"]["obat"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_GET["ob4_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        $_SESSION["ob4"]["obat"][$cnt]["id"]     = $d1->id;
        $_SESSION["ob4"]["obat"][$cnt]["obat"]   = $d1->obat;
        $_SESSION["ob4"]["obat"][$cnt]["satuan"] = $d1->satuan;   
        $_SESSION["ob4"]["obat"][$cnt]["jumlah"] = $_GET["ob4_jumlah"];
        $_SESSION["ob4"]["obat"][$cnt]["ket"] = $_GET["ob4_ket"];
        unset($_SESSION["SELECT_OBAT"]);
    }
    if (isset($_GET["del"])) {
        $temp = $_SESSION["ob4"]["obat"];
        unset($_SESSION["ob4"]["obat"]);
        $cnt = 0;
        foreach ($temp as $k => $v) {
            if ($k != $_GET["del"]) {
                $_SESSION["ob4"]["obat"][$cnt] = $v;
                $cnt++;
            }
        }
    }
    if (isset($_GET["editrow"])) {
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["jumlah"] = $_GET["editjumlah"];
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["ket"] = $_GET["editket"];
    }
    header("Location: $SC?p=$PID");
    exit;
}

title("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  RETUR FARMASI");
echo "<br>";
  echo "".$_COOKIE["SELECT_SUPPLIER"]  ;
if (isset($_SESSION["SELECT_SUPPLIER"])) {
	
    $_SESSION["ob4"]["supplier1"]["id"]   = $_SESSION["SELECT_SUPPLIER"];
    $_SESSION["ob4"]["supplier1"]["name"] =
        getFromTable("select nama from rs00028 where id = '".$_SESSION["SELECT_SUPPLIER"]."'");
    $_SESSION["ob4"]["supplier1"]["alamat"] =
    	getFromTable("select (alamat_jln1||', '||alamat_kota)as alamat  from rs00028 where id = '".$_SESSION["SELECT_SUPPLIER"]."'");    
    unset($_SESSION["SELECT_SUPPLIER"]);
  
}
echo "<form action=$SC name=formx onSubmit='return checkinput()'>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
echo "<table border=0>";
echo "<tr><td class=FORM>Kode Pemasok</td><td class=FORM>:</td>";
echo "    <td class=FORM width=1><input style='text-align:center' type=TEXT name=supplier1 size=15 maxlength=10 value='".$_SESSION["ob4"]["supplier1"]["id"]."' DISABLED></td>";
echo "    <td class=FORM width=500><a href='javascript:selectSupplier()'>".icon("view")."</a></td></tr>";
echo "<tr><td class=FORM>Nama Pemasok</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=text name=nama  size=40 maxlength=50 value='".$_SESSION["ob4"]["supplier1"]["name"]."' disabled></td></tr>";
echo "<tr><td class=FORM>Alamat</td><td class=FORM>:</td>";
echo "	  <td class=FORM colspan=2><input type=text name=alamat size=60 maxlength=200 value='".$_SESSION["ob4"]["supplier1"]["alamat"]."' disabled></td></tr>";
echo "<tr><td class=FORM>Nomor Retur</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=nomor_po id=nomor_po size=15 maxlength=20 value='".$_SESSION["ob4"]["nomor-po"]."'></td></tr>";
echo "<TR ><TD CLASS=FORM>Tanggal Retur </TD><TD CLASS=FORM>:</TD>\n";
echo "<TD CLASS=FORM VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=tanggal_pengadaan id=tanggal_pengadaan SIZE=10 MAXLENGTH=12 VALUE='".$_SESSION["ob4"]["tanggal-pengadaan"]."'>\n";
echo "<A HREF=\"#\" onClick=\"cal.select(document.forms['formx'].tanggal_pengadaan,'tanggalan','yyyy-MM-dd'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' SRC='icon/calendar.gif' TITLE='Pilih' ></A></TD>\n";
echo "</TR>\n\n";
echo "<tr><td class=FORM>Penanggung Jawab</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=penanggung_jawab id=penanggung_jawab size=30 maxlength=50 value='AGUNG SUPRIHATIN, S.Si, Apt.'></td></tr>";

// Agung SUnandar 0:20 30/06/2012 menghilangkan ppn dan discount
/* echo "<tr><td class=FORM>PPN</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=ppn id=ppn size=10 maxlength=10 value='".$_SESSION["ob4"]["ppn"]."'>%</td></tr>";

echo "<tr><td class=FORM>Discount 1</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=disc1 id=disc1 size=10 maxlength=10 value='".$_SESSION["ob4"]["disc1"]."'>(Rp)</td></tr>";

echo "<tr><td class=FORM>Discount 2</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=disc2 id=disc2 size=10 maxlength=10 value='".$_SESSION["ob4"]["disc2"]."'>(Rp)</td></tr>";
*/
echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
echo "    <td class=FORM colspan=2><input type=SUBMIT value='Submit' ></td></tr>"; 
echo "</tr></table>";
echo "</form>";
echo "\n<script language='JavaScript'>\n";
echo "function selectSupplier() {\n";
echo "    sWin = window.open('popup/supplier.php', 'xWin',".
     "    'width=500,height=400,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

$cek_po=getFromTable("select retur_id from rs00016b where retur_id='".$_SESSION["ob4"]["nomor-po"]."'");
//echo $cek_po;
?>
<script language='javascript'>

function checkinput()
{
    var nomor_po=document.getElementById("nomor_po");
	var tanggal_pengadaan=document.getElementById("tanggal_pengadaan");
    var penanggung_jawab=document.getElementById("penanggung_jawab");
    var ppn=document.getElementById("ppn");
    var disc1=document.getElementById("disc1");
    var disc2=document.getElementById("disc2");

if ((tanggal_pengadaan.value=="") || (penanggung_jawab.value=="") || (nomor_po.value=="") || (ppn.value=="") || (disc1.value=="") || (disc2.value==""))
    {
    alert ('Maaf data anda belum lengkap Mohon dilengkapi');
    return False;
    }
		else if ((nomor_po.value=='<?=$cek_po?>')) 
		{
		alert ('Maaf No. Retur sudah tersedia!');
		return False;
		}			
				else
				{
				return True;
				}
}

</script>
<?

if ($_SESSION["ob4"]["nomor-po"] != $cek_po ) {

    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }
	$q=  getFromTable("select qty_ri from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'");
	
	$x_jumlah = " <SELECT name='ob4_jumlah'>\n";
          for ($i='1'; $i<=$q; $i++){
                 if ($i=='1') {
             $x_jumlah  .= "<option value=$i selected>$i</option>\n";

          }
            else {
            $x_jumlah  .= " <option value=$i>$i</option>\n";
          }
          }
        "</SELECT>\n";
		
	$x_jumlah2 = " <SELECT name='ob4_jumlah'>\n";
          for ($i='1'; $i<=$q; $i++){
                 if ($i=='1') {
             $x_jumlah2  .= "<option value=$i selected>$i</option>\n";

          }
            else {
            $x_jumlah2  .= " <option value=$i>$i</option>\n";
          }
          }
        "</SELECT>\n";
		
    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("KODE", "Nama Obat", "Jumlah", "Satuan","Keterangan",""));
    if (is_array($_SESSION["ob4"]["obat"])) {
        $total = 0.00;
        foreach($_SESSION["ob4"]["obat"] as $k => $o) {
            if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
                echo "<form action=$SC>";
                echo "<input type=hidden name=p value=$PID>";
                echo "<input type=hidden name=editrow value=$k>";
                echo "<input type=hidden name=httpHeader value=1>";
                $t->printRow(
                    Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                        $o["obat"],
                        $x_jumlah,
                        $o["satuan"],
                        $o["ket"],
                        "<input type=submit value='Simpan'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID\"'>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER","LEFT",
                        "CENTER")
                    );
                echo "</form>";
            } else {
                $t->printRow(
                    Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                        $o["obat"],
                        $o["jumlah"],
                        $o["satuan"],
                        $o["ket"],
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k'>".icon("del-left","Hapus")."</a>"),
                        //" &nbsp; " .
                        //"<a href='$SC?p=$PID&edit=$k'>".icon("edit","Edit")."</a>" 
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER","LEFT",
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
            	"<input type=text size=5 maxlength=10 name=ob4_id style='text-align:center' value=$d1->id>"."&nbsp;<a href='javascript:selectObat()'>".icon("view")."</a>",
                $d1->obat,
                $x_jumlah,
                $d1->satuan,
				"<input type=text size=40 maxlength=200 name=ob4_ket value='' style='text-align:left'>",
                "<input type=submit value=Tambah&nbsp;Obat>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
						"CENTER",
                        "CENTER")
                    );
        echo "</FORM>";
    }
    $t->printTableClose();



    if (is_array($_SESSION["ob4"]["obat"])) {
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/525.insert.php' method=POST name=Form10>";
        //echo "<input type=hidden name=status value=".$_GET["status"].">";
        echo "<input type=submit value=' &nbsp; Simpan &nbsp; '>";
        echo "</form>";
        echo "</div>";
    }


    echo "\n<script language='JavaScript'>\n";
    echo "function selectObat() {\n";
    echo "    sWin = window.open('popup/obat_apotek.php', 'xWin', 'width=550,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
}

?>
