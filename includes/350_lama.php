<?php // Agung Sunandar -> Pengadaan Barang

$PID = "350s";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$_GET["penanggung_jawab"] = "Agung Suprihatin, S.Si, Apt";
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
    if (strlen($_GET["ob4_id"]) > 0) {
        
		if (is_array($_SESSION["ob4"]["obat"])) {
            $cnt = count($_SESSION["ob4"]["obat"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_GET["ob4_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
		
		$r2 = pg_query($con, "select * from rs00016d where kode_trans='".$_GET[kode_kon]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
		
		$r3 = pg_query($con, "select a.kode_trans,  b.tdesc as satuan1, a.jumlah2,a.jumlah1, c.tdesc as satuan2 
			from rs00016d a, rs00001 b, rs00001 c 
			where a.satuan1=b.tc and b.tt='SAT' and a.satuan2=c.tc and c.tt='SAT' and a.kode_trans='".$_GET[kode_kon]."'");
        $d3 = pg_fetch_object($r3);
        pg_free_result($r3);
		$satuan1 = getFromTable("select tc from rs00001 where tdesc='$_GET[satuan]'");
		$satuan2 = getFromTable("select tc from rs00001 where tdesc='$_GET[satuan2]'");
        $_SESSION["ob4"]["obat"][$cnt]["id"]     = $d1->id;
        $_SESSION["ob4"]["obat"][$cnt]["obat"]   = $d1->obat;
        $_SESSION["ob4"]["obat"][$cnt]["harga_beli"]   = $d1->harga_beli;
        $_SESSION["ob4"]["obat"][$cnt]["satuan1"] = $satuan1;   
        $_SESSION["ob4"]["obat"][$cnt]["satuan2"] = $satuan2;
		$_SESSION["ob4"]["obat"][$cnt]["sat1"] = $_GET["satuan"];   
        $_SESSION["ob4"]["obat"][$cnt]["sat2"] = $_GET["satuan2"];
        $_SESSION["ob4"]["obat"][$cnt]["kode_trans"]  = $satuan2;
        $_SESSION["ob4"]["obat"][$cnt]["jumlah"]  = $_GET["ob4_jumlah1"];
        $_SESSION["ob4"]["obat"][$cnt]["jumlah1"]  = $_GET["jumlah"];
        $_SESSION["ob4"]["obat"][$cnt]["total"]  = $_GET["ob4_jumlah1"] * $_GET["jumlah"];
        $_SESSION["ob4"]["obat"][$cnt]["diskon"]  = $_GET["diskon"];
        unset($_SESSION["SELECT_OBAT"]);
        unset($_SESSION["SELECT_KONVERSI"]);
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
		$_SESSION["ob4"]["obat"][$_GET["editrow"]]["harga"] = $_GET["editharga"];
		$_SESSION["ob4"]["obat"][$_GET["editrow"]]["harga_beli"] = $_GET["editharga_beli"];
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["total"]  =
		$_SESSION["ob4"]["obat"][$_GET["editrow"]]["jumlah"] *
		$_SESSION["ob4"]["obat"][$_GET["editrow"]]["harga"];
    }
    header("Location: $SC?p=$PID");
    exit;
}
?>
<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.autocomplete.css">
<?php
title("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  PENGADAAN PERBEKALAN FARMASI");
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
echo "<form action=$SC name=formx onSubmit='return checkinput()' class='design10a'>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
echo "<table border=0>";
echo "<tr><td class='design10a'>Kode Pemasok</td><td class=FORM>:</td>";
echo "    <td class=design10 width=1><input style='text-align:center' type=TEXT name=supplier1 size=15 maxlength=10 value='".$_SESSION["ob4"]["supplier1"]["id"]."' DISABLED></td>";
echo "    <td class=FORM width=500><a href='javascript:selectSupplier()'>".icon("view")."</a></td></tr>";
echo "<tr><td class='design10a'>Nama Pemasok</td><td class=FORM>:</td>";
echo "    <td class=design10 colspan=2><input type=text name=nama  size=40 maxlength=50 value='".$_SESSION["ob4"]["supplier1"]["name"]."' disabled></td></tr>";
echo "<tr><td class='design10a'>Alamat</td><td class=FORM>:</td>";
echo "	  <td class=design10 colspan=2><input type=text name=alamat size=60 maxlength=200 value='".$_SESSION["ob4"]["supplier1"]["alamat"]."' disabled></td></tr>";
echo "<tr><td class=design10a>Nomor PO</td><td class=FORM>:</td>";
$po_id = getFromTable("select count(po_id) from c_po where date_part('month',tanggal_entry)='".(int) date('m')."' and date_part('year',tanggal_entry)='".(int) date('Y')."' ");
$no_po = str_pad(((int) ($po_id+10)), 4, "0", STR_PAD_LEFT);
//echo $no_po;
echo "    <td class=design10 colspan=2><input type=TEXT name=nomor_po id=nomor_po size=15 maxlength=20 value='P-".$no_po."".date('m')."".date('y')."' readonly></td></tr>";
echo "<TR ><TD CLASS=design10a>Tanggal Pengadaan </TD><TD CLASS=FORM>:</TD>\n";
echo "<TD CLASS=design10 VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=tanggal_pengadaan id=tanggal_pengadaan SIZE=10 MAXLENGTH=12 VALUE='".$_SESSION["ob4"]["tanggal-pengadaan"]."'>\n";
echo "<A HREF=\"#\" onClick=\"cal.select(document.forms['formx'].tanggal_pengadaan,'tanggalan','yyyy-MM-dd'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' SRC='icon/calendar.gif' TITLE='Pilih' ></A></TD>\n";
echo "</TR>\n\n";
echo "<tr><td class=design10a>Penanggung Jawab</td><td class=FORM>:</td>";
echo "    <td class=design10 colspan=2><input type=TEXT name=penanggung_jawab id=penanggung_jawab size=30 maxlength=50 value='Agung Suprihatin, S.Si., Apt.' readonly></td></tr>";

echo "<tr><td class=design10a>PPN</td><td class=FORM>:</td>";
echo "    <td class=design10 colspan=2><input type=TEXT name=ppn id=ppn size=10 maxlength=10 value='".$_SESSION["ob4"]["ppn"]."'>%</td></tr>";

/* echo "<tr><td class=FORM>Discount 1</td><td class=FORM>:</td>";
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

$cek_po=getFromTable("select po_id from c_po where po_id='".$_SESSION["ob4"]["nomor-po"]."'");
//echo $cek_po;
?>
<script language='javascript'>

function checkinput()
{
    var nomor_po=document.getElementById("nomor_po");
	var tanggal_pengadaan=document.getElementById("tanggal_pengadaan");
    var penanggung_jawab=document.getElementById("penanggung_jawab");
    var ppn=document.getElementById("ppn");
    /* var disc1=document.getElementById("disc1");
    var disc2=document.getElementById("disc2"); */

if ((tanggal_pengadaan.value=="") || (penanggung_jawab.value=="") || (nomor_po.value=="") || (ppn.value=="") /* || (disc1.value=="") || (disc2.value=="") */)
    {
    alert ('Maaf data anda belum lengkap Mohon dilengkapi');
    return False;
    }
		else if ((nomor_po.value=='<?=$cek_po?>')) 
		{
		alert ('Maaf No. PO sudah tersedia!');
		return False;
		}			
				else
				{
				return True;
				}
}

</script>
<?

if ($_SESSION["ob4"]["nomor-po"] != $cek_po && $_SESSION["ob4"]["tanggal-pengadaan"]!='' && $_SESSION["ob4"]["penanggung-jawab"]!='' && $_SESSION["ob4"]["ppn"]!='' ) {

    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }
	
	if ($_SESSION["SELECT_KONVERSI"]) {
        $r2 = pg_query($con, "select a.kode_trans,  b.tdesc as satuan1, a.jumlah2,a.jumlah1, c.tdesc as satuan2 
			from rs00016d a, rs00001 b, rs00001 c 
			where a.satuan1=b.tc and b.tt='SAT' and a.satuan2=c.tc and c.tt='SAT' and a.kode_trans = '".$_SESSION["SELECT_KONVERSI"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
		
		$ext=" ";
    }else{$ext="disabled";}

    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("KODE", "Nama Obat", "","Jumlah Beli", "Satuan Beli",
                               "Isi perSatuan Beli","Jumlah perSatuan Beli", "Total Obat","Harga Beli","Diskon",""));
    if (is_array($_SESSION["ob4"]["obat"])) {
        $total = 0;
        foreach($_SESSION["ob4"]["obat"] as $k => $o) {
            if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
                echo "<form action=$SC>";
                echo "<input type=hidden name=p value=$PID>";
                echo "<input type=hidden name=editrow value=$k>";
                echo "<input type=hidden name=httpHeader value=1>";
                $t->printRow(
                    Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                        $o["obat"],
						"<INPUT OnKeyPress='refreshSubmit()' NAME=kode_kon STYLE='text-align:center' TYPE=hidden SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_KONVERSI"]."'>&nbsp;<!-- A HREF='javascript:selectKonversi()'><IMG BORDER=0 SRC='images/icon-conversion.png'></A -->",
                        "<input type=text size=5 maxlength=10 name=editjumlah value='".$o["jumlah"]."' style='text-align:right'>",
                        $o["satuan"],
                        "<input type=text size=20 maxlength=20 name=editharga value='".$o["harga"]."' style='text-align:right'>",
                        "<input type=text size=20 maxlength=20 name=editharga_beli value='".$o["harga_beli"]."' style='text-align:right'>",
                        "",
                        "<input type=submit value='Simpan'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID\"'>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "RIGHT",
                        "RIGHT",
                        "RIGHT",
                        "CENTER")
                    );
                echo "</form>";
            } else {
                $t->printRow(
                    Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                        $o["obat"],
						"",
                        $o["jumlah"],
                        $o["sat2"],
                        $o["jumlah1"],
                        $o["sat1"],
                        $o["total"]." ".$o["sat1"],
                        $o["harga_beli"],
                        $o["diskon"]." % ",
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k'>".icon("del-left","Hapus")."</a>".
                        " &nbsp; "),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "RIGHT",
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
        $t->printRow3(
            Array( 
            	"<input type=text size=5 maxlength=10 name=ob4_id style='text-align:center' value=$d1->id>"."&nbsp;<a href='javascript:selectObat()'>".icon("view")."</a>",
                $d1->obat,
                "<INPUT OnKeyPress='refreshSubmit()' NAME=kode_kon STYLE='text-align:center' TYPE=hidden SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_KONVERSI"]."'>&nbsp;<!-- A HREF='javascript:selectKonversi()'><IMG BORDER=0 SRC='images/icon-conversion.png'></A -->",
                "<input type=text size=5 maxlength=10 name=ob4_jumlah1 value=1 style='text-align:right'>",
                "<input type=text size=8 name=satuan2 id='satuan' >",
                "<input type=text size=8 name=jumlah maxlength=10 value=1 style='text-align:right'>",
                "<input type=hidden name=satuan value='".$d1->satuan."' >".$d1->satuan,
				"",
				$d1->harga_beli,
				"<input type=text size=3 maxlength=3 name=diskon value='0' > %",
                "<input type=submit value=Tambah&nbsp;Obat >" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "RIGHT",
                        "RIGHT",
                        "RIGHT",
                        "CENTER")
                    );
        echo "</FORM>";
    }
    $t->printTableClose();



    if (is_array($_SESSION["ob4"]["obat"])) {
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/350.insert.php' method=POST name=Form10>";
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
	
	echo "\n<script language='JavaScript'>\n";
    echo "function selectKonversi() {\n";
    echo "    sWin = window.open('popup/konversi.php?obt_id=$_SESSION[SELECT_OBAT]', 'xWin', 'width=550,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
}

?>
<script>
    $(function() {
    $("#satuan").autocomplete(
{
source:"./includes/get_sat.php?stat=0",
messages: {
			noResults: "",
			results: function( amount ) {
				
			}
		},
selectFirst: true,
close: function(event, ui){
$.ajax({
	type:'GET',
	url:'./includes/get_sat.php?stat=1',
	data: 'term='+$("#satuan").val(),
	dataType : 'json',
	success : function(data){
		  $('#obat_id').val('');
                $('#qty').val('');
                $("#jasa").val('');
                $("#harga").val('');
                $("#penjamin").val('');
                $("#selisih").val('');
                $("#stok").empty();
                var obatId = data[0].id;
                var obatNama = data[0].obat;
                var obatSatuan = data[0].satuan;
                var obatJasa = data[0].jasa;
                var obatHarga = data[0].harga;
                var obatStok = data[0].stok;
                
                if(parseInt(obatStok) < 1){
                    alert('stok kosong !');
                    return false;
                }
                
                $('#satuan').val(obatSatuan);
                $('#obat_id').val(obatId);
                $("#jasa").val(parseInt(obatJasa));
                $("#harga").val(obatHarga);
                $("#stok").html(obatStok);
                $('#penjamin').val(0);
                $('#is_penjamin').attr('checked', false);
                $('#is_racikan').attr('checked', false);        
				},
			});
		}
		});
	});
</script>