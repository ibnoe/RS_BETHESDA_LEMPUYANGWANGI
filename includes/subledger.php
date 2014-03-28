<?php 

// Wildan ST. 18 Feb 2014

$PID = "subledger";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$tgl_sekarang = date("d-m-Y", time());

if ($_GET["httpHeader"] == "1") {
    if (strlen($_GET["jurnal_id"]) > 0 ) {
        if (is_array($_SESSION["jurnal"]["akun"])) {
            $cnt = count($_SESSION["jurnal"]["akun"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from akun_master where kode = '".$_GET["jurnal_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        

    if (!empty($d1->kode)) {
        $_SESSION["jurnal"]["akun"][$cnt]["id"]         = $_GET["id"];
        $_SESSION["jurnal"]["akun"][$cnt]["tanggal"]    = $_GET["tanggal"];
        $_SESSION["jurnal"]["akun"][$cnt]["kode"]       = $_GET["jurnal_id"];
        $_SESSION["jurnal"]["akun"][$cnt]["nama"]       = $d1->nama;
        $_SESSION["jurnal"]["akun"][$cnt]["keterangan"] = $_GET["jurnal_keterangan"];   
        $_SESSION["jurnal"]["akun"][$cnt]["ket"]        = $_GET["ket"];
        $_SESSION["jurnal"]["akun"][$cnt]["harga"]      = $_GET["jurnal_harga"];
        }
        unset($_SESSION["SELECT_JURNAL"]);
    }
    
    if (isset($_GET["del"])) {
        $temp = $_SESSION["jurnal"]["akun"];
        unset($_SESSION["jurnal"]["akun"]);
        $cnt = 0;
        foreach ($temp as $k => $v) {
            if ($k != $_GET["del"]) {
                $_SESSION["jurnal"]["akun"][$cnt] = $v;
                $cnt++;
            }
        }
    }
    
    if (isset($_GET["editrow"])) {
        $_SESSION["jurnal"]["akun"][$_GET["editrow"]]["keterangan"] = $_GET["editketerangan"];
	$_SESSION["jurnal"]["akun"][$_GET["editrow"]]["ket"] = $_GET["ket"];
        $_SESSION["jurnal"]["akun"][$_GET["editrow"]]["harga"]  = $_GET["editharga"];
    }
    
    header("Location: $SC?p=$PID&id=$_GET[id]");
    exit;
}

title("<img src='icon/akuntansi-subledger.png' align='absmiddle' >  Input Jurnal");
echo "<br>";

?>
<script language='javascript'>

function checkinput()
{
    var tanggal=document.getElementById("tanggal");
    var no_faktur=document.getElementById("no_faktur");
    var keterangan=document.getElementById("keterangan");

if ((tanggal.value=="") || (no_faktur.value=="") || (keterangan.value==""))
    {
    alert ('Maaf data anda belum lengkap Mohon dilengkapi');
    return false;
    }
else
    {
    return True;
    }
}


function checktotal()
{
var debet=document.getElementById("tot_debet");
var kredit=document.getElementById("tot_kredit");

if (debet.value!=kredit.value)
    {
    alert ('Total Debet dan Kredit tidak sama');
    return false;
    }
else
    {
    return True;
    }
}

</script>

<?


 if ($_GET["id"]!= null){
     
    $r2 = pg_query($con, "select * from jurnal_umum_m where id = ".$_GET["id"]."");
    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

echo "<form name=formx >";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=id value=$_GET[id]>";
echo "<input type=hidden name=httpHeader value=1>";
echo "<table border=0>";
echo "<tr><td class=FORM>Tanggal</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=tanggal id=tanggal size=30 maxlength=50 value='".$d2->tanggal."' disabled></td></tr>";
echo "<tr><td class=FORM>No. Faktur</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=no_faktur id=no_faktur size=30 maxlength=50 value='".$d2->no_faktur."' disabled></td></tr>";
echo "<tr><td class=FORM>Keterangan</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><textarea cols=30 rows=3 name=keterangan id=keterangan disabled>".$d2->keterangan."</textarea></td></tr>";
echo "</table>";
echo "</form>";


$t = new BaseTable("100%");

$x_racikan   = "<SELECT NAME='ket'>\n";
$x_racikan  .= "<OPTION VALUE=Debet>Debet</OPTION>\n";
$x_racikan  .= "<OPTION VALUE=Kredit>Kredit</OPTION>\n";
"</SELECT></TD>\n";

    if ($_SESSION["SELECT_JURNAL"]) {
        $r1 = pg_query($con, "select * from akun_master where kode = '".$_SESSION["SELECT_JURNAL"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }
    
    $t->printTableOpen();
    $t->printTableHeader(Array("Kode Akun", "Nama Akun", "Keterangan", "Debet/Kredit",  "Jumlah (Rp.)",""));
    if (is_array($_SESSION["jurnal"]["akun"])) {
        $totald = 0.00;
        $totalk = 0.00;
        foreach($_SESSION["jurnal"]["akun"] as $k => $o) {
            if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
				
                echo "<form action=$SC>";
                echo "<input type=hidden name=p value=$PID>";
                echo "<input type=hidden name=id value=$_GET[id]>";
                echo "<input type=hidden name=editrow value=$k>";
                echo "<input type=hidden name=httpHeader value=1>";
                //echo "<input type=hidden name=status value=".$_GET[status].">";
                $t->printRow2(
                    Array( $o["kode"], $o["nama"],
                        "<input type=text size=60 maxlength=200 name=editketerangan value='".$o["keterangan"]."' style='text-align:left'>",
                        //$x_racikan,
						"<input type=text size=20 maxlength=20 name=ket value='".$o["akun_type"]."' style='text-align:right'>",
                        "<input type=text size=20 maxlength=20 name=editharga value='".$o["harga"]."' style='text-align:right'>",
                        "<input type=submit value='Simpan'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID&id=$_GET[id]\"'>" ),
                    Array( "CENTER",
                        "LEFT",
                        "left",
                        "CENTER",
                        "RIGHT",
                        "RIGHT",
                        "CENTER")
                    );
                echo "</form>";
            } else {
                $t->printRow2(
                    Array( $o["kode"], $o["nama"],$o["keterangan"], $o["ket"],
                        number_format($o["harga"],2,',','.'),
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k&id=$_GET[id]'>".icon("del-left","Hapus")."</a>".
                        " &nbsp; " .
                        "<a href='$SC?p=$PID&edit=$k&id=$_GET[id]'>".icon("edit","Edit")."</a>" ),
                    Array( "CENTER",
                        "LEFT",
                        "left",
                        "CENTER",
                        "RIGHT",
                        "RIGHT",
                        "CENTER")
                    );
            }
            if($o["ket"]=="Debet"){
                $totald += $o["harga"];
            }elseif($o["ket"]=="Kredit"){
                $totalk += $o["harga"];  
            }
        }
    }

    if (strlen($_GET["edit"]) == 0) {
		
        echo "<form action=$SC>";
        echo "<input type=hidden name=p value=$PID>";
        echo "<input type=hidden name=id value=$_GET[id]>";
        echo "<input type=hidden name=httpHeader value=1>";
        //echo "<input type=hidden name=status value=".$_GET[status].">";
        $t->printRow2(
            Array( 
            	"<input type=text size=5 maxlength=10 name=jurnal_id style='text-align:center' value=$d1->kode>"."&nbsp;<a href='javascript:selectJurnal()'>".icon("view")."</a>",
                $d1->nama,
                "<input type=text size=60 maxlength=200 name=jurnal_keterangan value='' style='text-align:left'>",
                //$x_racikan,
				"<input type=text size=5 maxlength=10 name=ket style='text-align:center' value=$d1->akun_type>",
                "<input type=text size=20 maxlength=20 name=jurnal_harga value='' style='text-align:right'>",
                "<input type=submit value=Tambah>" ),
                    Array( "CENTER",
                        "LEFT",
                        "left",
                        "CENTER",
                        "RIGHT",
                        "RIGHT",
                        "CENTER")
                    );
        echo "</FORM>";
    }
    $t->printTableClose();



    if (is_array($_SESSION["jurnal"]["akun"])) {
        
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/subledger.insert.php' method=POST name=Form10 onSubmit='return checktotal()'>";
        echo "<input type=hidden name=id value=$_GET[id]>";
        echo "<input type=hidden name=p value=$PID]>";
        echo "<input type=hidden name=tot_debet value=$totald>";
        echo "<input type=hidden name=tot_kredit value=$totalk>";
        echo "<table>";
        echo "<tr><td class=FORM>Total Debet</td><td class=FORM>:</td>";
        echo "    <td class=FORM colspan=2><input type=text name=tot_debet1 id=tot_debet size=10 maxlength=50 value= '".number_format($totald,2,',','.')."' disabled></td></tr>";
        echo "<tr><td class=FORM>Total Kredit</td><td class=FORM>:</td>";
        echo "    <td class=FORM colspan=2><input type=text name=tot_kredit1 id=tot_kredit size=10 maxlength=50 value= '".number_format($totalk,2,',','.')."' disabled></td></tr>";
        echo "</table>";
        echo "<input type=submit value=' &nbsp; Simpan &nbsp; '>";
        echo "</form>";
        echo "</div>";
    }


    echo "\n<script language='JavaScript'>\n";
    echo "function selectJurnal() {\n";
    echo "    sWin = window.open('popup/akun1.php', 'xWin', 'width=550,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
    
}else{

echo "<form method=post action='actions/jurnal_umum_m.insert.php' name=formx onSubmit='return checkinput()'>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
echo "<table border=0>";
echo "<TR ><TD CLASS=FORM>Tanggal </TD><TD CLASS=FORM>:</TD>\n";
echo "<TD CLASS=FORM VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=tanggal id=tanggal SIZE=10 MAXLENGTH=12 VALUE='".$_SESSION["jurnal"]["tanggal-pengadaan"]."'>\n";
echo "<A HREF=\"#\" onClick=\"cal.select(document.forms['formx'].tanggal,'tanggalan','yyyy-MM-dd'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' SRC='icon/calendar.gif' TITLE='Pilih' ></A></TD>\n";
echo "</TR>\n\n";
echo "<tr><td class=FORM>No. Faktur</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=no_faktur id=no_faktur size=30 maxlength=50 value=''></td></tr>";
echo "<tr><td class=FORM>Keterangan</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><textarea cols=30 rows=3 name=keterangan id=keterangan></textarea></td></tr>";
echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
echo "    <td class=FORM colspan=2><input type=SUBMIT value='Submit' ></td></tr>";
echo "</tr></table>";
echo "</form>";



}


echo "
<script type='text/javascript'>
function selesai()
{
var sip = '' + reg;
var stay= confirm('Apakah Anda yakin sudah selesai periksa?')
if (!stay) {
window.location='$SC?p=$PID';
}else{
window.location=sip;
}
}
</script>";
?>
