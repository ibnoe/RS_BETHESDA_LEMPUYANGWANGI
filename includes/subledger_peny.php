<?php 

// Wildan ST. 17 Feb 2014 -> Subledger untuk Penginputan Akun

$PID = "subledger_peny";
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
    
    header("Location: $SC?p=$PID&edit=tambah&id=$_GET[id]");
    exit;
}

title("<img src='icon/akuntansi-subledger.png' align='absmiddle' >  Edit Jurnal");
echo "<br>";

?>
<script language='javascript'>

function checkinput()
{
    var password=document.getElementById("password");

if ((password.value==""))
    {
    alert ('Silahkan anda masukan password!');
    return false;
    }
else if ((password.value!="coa"))
    {
    alert ('Password salah. SIlahkan anda ulangi!');
    document.formx.password.value="";
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
if ($_GET["edit"]== "tambah" and $_GET["id"]!= null){
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
        $r3 = pg_query($con, "select * from akun_master where kode = '".$_SESSION["SELECT_JURNAL"]."'");
        $d3 = pg_fetch_object($r3);
        pg_free_result($r3);
    }
    
    $t->printTableOpen();
    $t->printTableHeader(Array("Kode Akun", "Nama Akun", "Keterangan", "Debet/Kredit",  "Jumlah (Rp.)",""));
    if (is_array($_SESSION["jurnal"]["akun"])) {
        $totald = 0.00;
        $totalk = 0.00;
        foreach($_SESSION["jurnal"]["akun"] as $k => $o) {
            if ($k == $_GET["edit1"] && strlen($_GET["edit1"]) > 0) {
                echo "<form action=$SC>";
                echo "<input type=hidden name=p value=$PID>";
                echo "<input type=hidden name=id value=$_GET[id]>";
                echo "<input type=hidden name=editrow value=$k>";
                echo "<input type=hidden name=httpHeader value=1>";
                //echo "<input type=hidden name=status value=".$_GET[status].">";
                $t->printRow(
                    Array( $o["kode"], $o["nama"],
                        "<input type=text size=60 maxlength=200 name=editketerangan value='".$o["keterangan"]."' style='text-align:left'>",
                        $x_racikan,
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
                $t->printRow(
                    Array( $o["kode"], $o["nama"],$o["keterangan"], $o["ket"],
                        number_format($o["harga"],2,',','.'),
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k&id=$_GET[id]'>".icon("del-left","Hapus")."</a>".
                        " &nbsp; " .
                        "<a href='$SC?p=$PID&edit1=$k&id=$_GET[id]'>".icon("edit","Edit")."</a>" ),
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

    if ($_GET["edit"] == "tambah") {
    	
        echo "<form action=$SC>";
        echo "<input type=hidden name=p value=$PID>";
        echo "<input type=hidden name=id value=$_GET[id]>";
        echo "<input type=hidden name=httpHeader value=1>";
        $t->printRow(
            Array( 
            	"<input type=text size=5 maxlength=10 name=jurnal_id style='text-align:center' value=$d3->kode>"."&nbsp;<a href='javascript:selectJurnal()'>".icon("view")."</a>",
                $d3->nama,
                "<input type=text size=60 maxlength=200 name=jurnal_keterangan value='' style='text-align:left'>",
                $x_racikan,
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
        echo "<input type=hidden name=p value=$PID>";
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
    
}elseif ($_GET["edit"]== "edit" and $_GET["id"]!= null){
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


    $f = new Form("actions/subledger.update.php", "POST", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("id", $_GET["id"]);
    $f->hidden("no_akun", $_GET["kode"]);
    
    $r1 = pg_query($con, "select a.no_akun,b.nama,a.keterangan,a.ket,a.debet,a.kredit 
                          from jurnal_umum a, akun_master b
                          where a.no_akun=b.kode and a.id = ".$_GET["id"]." and a.no_akun='".$_GET["kode"]."'
                          group by a.no_akun,b.nama,a.keterangan,a.ket,a.debet,a.kredit ");
    $d1 = pg_fetch_object($r1);
    pg_free_result($r1);
    

    $f->text("no_akun","No. Akun",15,15,$d1->no_akun,disabled);	
    $f->text("nama","Nama Akun",50,100,$d1->nama,disabled);	
    $f->text("keterangan","Keterangan",50,120,$d1->keterangan);
    $f->selectArray("ket", "Debet/Kredit",Array("Debet" => "Debet", "Kredit" => "Kredit"),$d1->ket);
    if ($d1->ket == "Debet"){
    $f->text("jumlah","Jumlah",15,15,$d1->debet);
    }else{
       $f->text("jumlah","Jumlah",15,15,$d1->kredit); 
    }
    $f->submit("Simpan");
    $f->execute();
   
    
    echo "\n<script language='JavaScript'>\n";
    echo "function selectJurnal() {\n";
    echo "    sWin = window.open('popup/akun1.php', 'xWin', 'width=550,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
    
}elseif ($_GET["edit"]== "view" and $_GET["id"]!= null){
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

    $t = new PgTable($con, "100%");   
    $t->SQL = "  select a.no_akun,b.nama,a.keterangan,a.ket, to_char(a.debet+a.kredit,'999,999,999,999.99') as jumlah,a.id 
                 from jurnal_umum a, akun_master b 
                 where a.no_akun=b.kode and a.id=".$_GET["id"]."
                 group by a.id,a.tanggal_akun,a.no_akun,b.nama,a.keterangan,a.ket,a.debet,a.kredit 
                 ";

    $t->ColHeader = array("Kode Akun", "Nama Akun", "Keterangan", "Debet/Kredit",  "Jumlah (Rp.)","");
    $t->ColAlign = array("CENTER","LEFT","left","CENTER","RIGHT","CENTER");
    $t->setlocale("id_ID");
    $t->ShowRowNumber = false;
    $t->RowsPerPage = 20;
    $t->DisableNavButton = true;
    $t->DisableStatusBar = true;
    $t->DisableScrollBar = true;
    $t->ColFooter[5]=  "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=tambah&id=".$_GET["id"]."'>".
                        icon("Journal2","Tambah Akun")."</A>";
    $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=edit&id=".$_GET["id"]."&kode=<#0#>'>".
                        icon("edit","Edit Jurnal")."</A>";
    
    $t->execute();
    
    $tot_d=getFromTable("select sum(debet) from jurnal_umum where id=".$_GET["id"]." and ket='Debet'");
    $tot_k=getFromTable("select sum(kredit) from jurnal_umum where id=".$_GET["id"]." and ket='Kredit'");
    
        echo "<form action='$SC?p=$PID&a=sukses' method=POST name=Form10 onSubmit='return checktotal()'>";
        echo "<table>";
        echo "<tr><td class=FORM>Total Debet</td><td class=FORM>:</td>";
        echo "    <td class=FORM colspan=2><input type=text name=tot_debet1 id=tot_debet size=10 maxlength=50 value= '".number_format($tot_d,2,',','.')."' disabled></td></tr>";
        echo "<tr><td class=FORM>Total Kredit</td><td class=FORM>:</td>";
        echo "    <td class=FORM colspan=2><input type=text name=tot_kredit1 id=tot_kredit size=10 maxlength=50 value= '".number_format($tot_k,2,',','.')."' disabled></td></tr>";
        echo "</table>";
        echo "<input type=submit value=' &nbsp; Selesai &nbsp; '>";
        echo "</form>";
        echo "</div>";

    
}else{

if ($_GET["a"]=="sukses"){

$f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->hidden("a", "sukses");

    if (!$GLOBALS['print']){
	    $f->selectSQL("mPeriode", "Periode","select '' as kode, '' as ket_riwulan union SELECT kode,ket_triwulan FROM triwulan order by ket_riwulan ASC ",$_GET["mPeriode"], "");

		$sql1=getFromtable("select bln_awal from triwulan where kode='".$_GET["mPeriode"]."'");
		$sql2=getFromtable("select bln_akhir from triwulan where kode='".$_GET["mPeriode"]."'");
		$f->submit ("TAMPILKAN");
	    $f->execute();

	} else {
		$f->selectSQL("mPeriode", "Periode","select '' as kode, '' as ket_riwulan union SELECT kode,ket_triwulan FROM triwulan order by ket_riwulan ASC ",$_GET["mPeriode"], "disabled");

		$sql1=getFromtable("select bln_awal from triwulan where kode='".$_GET["mPeriode"]."'");
		$sql2=getFromtable("select bln_akhir from triwulan where kode='".$_GET["mPeriode"]."'");

	    $f->execute();
	}


    $sql=" select a.no_faktur,a.keterangan, sum(b.debet) as tot_debet, sum(b.kredit) as tot_kredit, to_char(a.tanggal,'dd Mon yyyy') as tanggal_akun,a.id 
        from jurnal_umum_m a, jurnal_umum b
        where a.id=b.id and (a.tanggal between '$sql1' and '$sql2') and a.jns_akun != 'KSR'
        group by a.no_faktur,a.keterangan,a.tanggal,a.id ";

        @$r1 = pg_query($con,$sql);
        @$n1 = pg_num_rows($r1);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;
	if (!$mulai){$mulai=1;}
        
        ?>
        <br>
        <table align="center" border=1 width="75%" CLASS=TBL_BORDER CELLSPACING=1>
            <tr align="center" class="TBL_HEAD">
		<td class="TBL_HEAD"><b>   TANGGAL</b></td>
		<td class="TBL_HEAD"><b>   NO. FAKTUR</b></td>
                <td class="TBL_HEAD"><b>   KETERANGAN</b></td>
		<td class="TBL_HEAD"><b>   DEBIT</b></td>
		<td class="TBL_HEAD"><b>  KREDIT</b></td>
                <td class="TBL_HEAD"><b>  UPDATE</b></td>
            </tr>
            <?
            $totdebet= 0;
            $totkredit= 0;
            $row1=0;
            $i= 1 ;
            $j= 1 ;
            $last_id=1;
            while (@$row1 = pg_fetch_array($r1)){
                if (($j<=$max_row) AND ($i >= $mulai)){
                        $no=$i
                        ?>
                        <tr valign="top" class="<? ?>" >
                                <td class="TBL_BODY" align="center"><?=$row1["tanggal_akun"] ?> </td>
                                <td class="TBL_BODY" align="center"><?=$row1["no_faktur"] ?> </td>
                                <td class="TBL_BODY" align="left"><?=$row1["keterangan"] ?> </td>
                                <td class="TBL_BODY" align="right"><?=number_format($row1["tot_debet"] ,2,",",".")?></td>
                                <td class="TBL_BODY" align="right"><?=number_format($row1["tot_kredit"] ,2,",",".")?></td>
                                <td align="center" class="TBL_BODY" valign="middle"><?="<A CLASS=TBL_HREF HREF='$SC?p=$PID&id=".$row1["id"]."&edit=view'>".
                        icon("edit","Input Faktur")."</A>";?></td>
                        </tr>

                        <?
                        $totdebet=$totdebet+$row1["tot_debet"] ;
                        $totkredit=$totkredit+$row1["tot_kredit"] ;
                        ?>
                        <?;$j++;
                }
                $i++;
                
            }
            ?>
        </table>
        <?

}else{
echo "<form method=post action='$SC?p=$PID&a=sukses' name=formx onSubmit='return checkinput()'>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
echo "<table border=0>";
echo "<tr><td class=FORM>Password</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=password name=password id=password size=30 maxlength=50 value=''></td></tr>";
echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
echo "    <td class=FORM colspan=2><input type=SUBMIT value='Submit' ></td></tr>";
echo "</tr></table>";
//	$t = new Form(" method=post action='$SC?p=$PID&a=sukses' name=formx onSubmit='return checkinput()'");
//	$t->PgConn = $con;
//	$t->password("password","Password","20","20","");
//	$t->submit("Submit");
//	$t->execute();
echo "</form>";
}


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
