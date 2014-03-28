<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 09-05-2004

$PID = "p_pengadaan_depo";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$tgl_sekarang = date("d-m-Y", time());
$tglhariini = date("Y-m-d", time());
if ($_GET["httpHeader"] == "1") {
    if (isset($_GET["nomor_invoice"])) {
        $_SESSION["ob4"]["nomor-invoice"] = $_GET["nomor_invoice"];
    }if (isset($_GET["ket_depo"])) {
        $_SESSION["ob4"]["ket-depo"] = $_GET["ket_depo"];
    }if (isset($_GET["status"])) {
        $_SESSION["ob4"]["status"] = $_GET["status"];
    }if (isset($_GET["tgl_pengadaan"])) {
        $_SESSION["ob4"]["tgl-pengadaan"] = $_GET["tgl_pengadaan"];
    }
       
    if (strlen($_GET["ob4_id"]) > 0 && $_GET["ob4_jumlah_minta"] > 0 && $_GET["ob4_jumlah_beri"] > 0) {
        if (is_array($_SESSION["ob4"]["obat"])) {
            $cnt = count($_SESSION["ob4"]["obat"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_GET["ob4_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        $r2 = pg_query($con, "select * from rs00028 where id = '".$_GET["ob4_id_supplier"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        
        $_SESSION["ob4"]["obat"][$cnt]["id"]     = $d1->id;
        $_SESSION["ob4"]["obat"][$cnt]["obat"]   = $d1->obat;
        $_SESSION["ob4"]["obat"][$cnt]["id_dist"]= $d2->id;
        $_SESSION["ob4"]["obat"][$cnt]["nama"]   = $d2->nama;
        $_SESSION["ob4"]["obat"][$cnt]["satuan"] = $d1->satuan;
        $_SESSION["ob4"]["obat"][$cnt]["tglK"]   = $_GET["ob4_tglK"];
        $_SESSION["ob4"]["obat"][$cnt]["tgl_pengadaan"]   = $_GET["tgl_pengadaan"];
        $_SESSION["ob4"]["obat"][$cnt]["batch"]  = $_GET["ob4_batch"];              
        $_SESSION["ob4"]["obat"][$cnt]["jumlah_minta"] = $_GET["ob4_jumlah_minta"];
        $_SESSION["ob4"]["obat"][$cnt]["jumlah_beri"] = $_GET["ob4_jumlah_beri"];
        $_SESSION["ob4"]["obat"][$cnt]["status"] = $_GET["ob4_status"];
        //$_SESSION["ob4"]["obat"][$cnt]["harga"]  = $d1->harga;
        //$_SESSION["ob4"]["obat"][$cnt]["total"]  = $d1->harga * $_GET["ob4_jumlah"];
        unset($_SESSION["SELECT_OBAT"]);
        unset($_SESSION["SELECT_SUPPLIER"]);
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
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["jumlah_minta"] = $_GET["editjumlah_minta"];
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["jumlah_beri"] = $_GET["editjumlah_beri"];
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["status"] = $_GET["editstatus"];
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["batch"] = $_GET["editbatch"];
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["tglK"] = $_GET["edittglK"];
        //$_SESSION["ob4"]["obat"][$_GET["editrow"]]["total"]  =
            //$_SESSION["ob4"]["obat"][$_GET["editrow"]]["jumlah"] *
            //$_SESSION["ob4"]["obat"][$_GET["editrow"]]["harga"];
    }
    header("Location: $SC?p=$PID&action=new&status=".$_GET["status"]."&depo_id=".$_GET["depo_id"]."&tgl_pengadaan=".$_GET["tgl_pengadaan"]);
    exit;
}

title("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  TRANSAKSI PENGADAAN DEPO");

if ($_GET["action"] == "new") {  

echo "<form action=$SC>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    echo "<br>";
    $f->selectSQL("depo_id", "Nama Layanan",
                    "select '' as tc, '-' as tdesc union ".
                    "SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc not in ('206','207','208','201','000') order by tdesc "
                    ,$_GET["depo_id"],$ext);
	//$f->submit("&nbsp; OK &nbsp;",$ext);					
	$f->execute();
    
        ?>
        <SCRIPT language="JavaScript">
            document.Form1.depo_id.selectedIndex = -1;
            function setPoli( v )
            {
                document.Form1.depo_id.disabled = v == "N";
                document.Form1.depo_id.selectedIndex = document.Form1.depo_id.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.depo_id.selectedIndex : -1;
            }
        </SCRIPT>
        <?php
       
//".$_SESSION["ob4"]["tgl-pengadaan"]."
echo "<table border=0>";
echo "<td class=FORM width='162'>Tanggal Pengadaan</td><td class=FORM>:</td>";
echo "<td><input type=text size=10 maxlength=15 name=tgl_pengadaan value='".$_SESSION["ob4"]["tgl-pengadaan"]."' style='text-align:right'><td>08-07-2007 (Tanggal-Bulan-Tahun)</td></tr>";
echo "<tr><td class=FORM>Nomor Invoice</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=nomor_invoice size=30 maxlength=30 value='".$_SESSION["ob4"]["nomor-invoice"]."'></td></tr>";
echo "<tr><td class=FORM>Keterangan</td><td class=FORM>:</td>";
echo "    <td class=FORM colspan=2><input type=TEXT name=ket_depo size=62 maxlength=100 value='".$_SESSION["ob4"]["ket-depo"]."'></td></tr>";
echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
echo "    <td class=FORM colspan=2><input type=SUBMIT value='&nbsp; OK &nbsp;'></td></tr>";
echo "</table>";
echo "</form>";


echo "\n<script language='JavaScript'>\n";
echo "function selectSupplier() {\n";
echo "    sWin = window.open('popup/supplier.php', 'xWin',".
        " 'width=500,height=400,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

if (isset($_SESSION["ob4"]["nomor-invoice"])) {

    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }
    
    if ($_SESSION["SELECT_SUPPLIER"]) {
	
    	$r2 = pg_query($con, "select * from rs00028 where id = '".$_SESSION["SELECT_SUPPLIER"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
  
	}

    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("KODE", "NAMA OBAT","KODE","NAMA SUPPLIER","JUMLAH PERMINTAAN","JUMLAH PEMBERIAN", "SATUAN",
    				"TGL KADALUARSA","NOMOR BATCH","STATUS",""));
    if (is_array($_SESSION["ob4"]["obat"])) {
        $total = 0.00;
        foreach($_SESSION["ob4"]["obat"] as $k => $o ) {
            if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
                echo "<form action=$SC>";
                echo "<input type=hidden name=p value=$PID>";
                echo "<input type=hidden name=editrow value=$k>";
                echo "<input type=hidden name=httpHeader value=1>";
                echo "<input type=hidden name=depo_id value=".$_GET[depo_id].">";
                //echo "<input type=hidden name=status value=".$_GET[status].">";
                $t->printRow(
                    Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                        $o["obat"],$o["id_dist"],$o["nama"],
                        "<input type=text size=5 maxlength=10 name=editjumlah_minta value='".$o["jumlah_minta"]."' style='text-align:right'>",
                        "<input type=text size=5 maxlength=10 name=editjumlah_beri value='".$o["jumlah_beri"]."' style='text-align:right'>",
                        $o["satuan"],
                         "<input type=text size=10 maxlength=15 name=edittglK value='".$o["tglK"]."' style='text-align:right'>",
                         "<input type=text size=10 maxlength=15 name=editbatch value='".$o["batch"]."' style='text-align:right'>",
                         "<select name =editstatus onchange='javascript:formx.submit()'>
                		<option value=value='".$o["status"]."'></option>
                		<option value='DITUNDA' if ($_GET[editstatus] == 'DITUNDA') selected>DITUNDA</option>
                		<option value='DITOLAK' if ($_GET[editstatus] == 'DITOLAK') selected>DITOLAK</option>
                		<option value='DISETUJUI' if ($_GET[editstatus] == 'DISETUJUI') selected>DISETUJUI</option></select>",
                        //number_format($o["harga"],2),
                        //number_format($o["total"],2),
                        "<input type=submit value='Update'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID&action=new&depo_id={$_GET["depo_id"]}\"'>" ),
                    Array( "CENTER",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "RIGHT",
                        "CENTER",
                        "RIGHT",
                        "RIGHT" )
                    );
                echo "</form>";
            } else {
                $t->printRow(
                    Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                        $o["obat"],$o["id_dist"],$o["nama"],
                        $o["jumlah_minta"],
                        $o["jumlah_beri"],
                        $o["satuan"],
                        $o["tglK"],
                        $o["batch"],
                        $o["status"],
                        //number_format($o["harga"],2),
                        //number_format($o["total"],2),
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k&action=new&depo_id={$_GET["depo_id"]}'>".icon("del-left")."</a>".
                        " &nbsp; " .
                        "<a href='$SC?p=$PID&edit=$k&action=new&depo_id={$_GET["depo_id"]}'>".icon("edit")."</a>" ),
                    Array( "CENTER",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "RIGHT",
                        "CENTER",
                        "RIGHT",
                        "RIGHT" )
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
        echo "<input type=hidden name=depo_id value=".$_GET[depo_id].">";
        $t->printRow(
            Array( 
            	"<input type=text size=5 maxlength=10 name=ob4_id style='text-align:center' value=$d1->id>"."&nbsp;<a href='javascript:selectObat()'>".icon("view")."</a>",
                $d1->obat,"<input type=text size=5 maxlength=10 name=ob4_id_supplier style='text-align:center' value=$d2->id>"."&nbsp;<a href='javascript:selectSupplier()'>".icon("view")."</a>",
                $d2->nama,
                "<input type=text size=5 maxlength=10 name=ob4_jumlah_minta value=1 style='text-align:right'>",
                "<input type=text size=5 maxlength=10 name=ob4_jumlah_beri value=1 style='text-align:right'>",
                $d1->satuan,
                "<input type=text size=7 maxlength=10 name=ob4_tglK value='".$tgl_sekarang."' style='text-align:right'>",
                "<input type=text size=7 maxlength=10 name=ob4_batch value='".$_SESSION["ob4"]["obat"]["batch"]."' style='text-align:right'>",
                //$d1->harga,
                //number_format($total,2),
                "<select name =ob4_status>
                <option value='".$_SESSION["ob4"]["obat"]["status"]."' selected></option>
                <option value='DITUNDA' if ($_GET[status] == 'DITUNDA') selected>DITUNDA</option>
                <option value='DITOLAK' if ($_GET[status] == 'DITOLAK') selected>DITOLAK</option>
                <option value='DISETUJUI' if ($_GET[status] == 'DISETUJUI') selected>DISETUJUI</option></select>",
                "<input type=submit value=OK>" ),
                    Array( "CENTER",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "RIGHT",
                        "CENTER",
                        "RIGHT",
                        "RIGHT" )
            );
        echo "</FORM>";
     
    }
    $t->printTableClose();



    if (is_array($_SESSION["ob4"]["obat"])) {
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/p_pengadaan_depo.insert.php' method=POST name=Form10>";
        //echo "<input type=hidden name=status value=".$_GET["status"].">";
        echo "<input type=hidden name=depo_id value=".$_GET[depo_id].">";
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
}else {
	$f = new Form($SC, "GET", "NAME=Form2");
$f->hidden("p",$PID);
$ext = "OnChange = 'Form2.submit();'";
        $f->PgConn = $con;
        
        $f->selectSQL("depo_id", "Nama Layanan",
						"select '' as tc, 'SEMUA LAYANAN' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc not in ('206','207','208','201','000') order by tdesc "
						,$_GET["depo_id"],$ext);
if($_GET["depo_id"] == ''){
$f->submit(" Tampilkan ");
$f->execute();
    if (!empty($_GET[tanggal1D]) or isset($ts_check_in1)) {
       $tmbh = "and (a.pengadaan_tgl between '$ts_check_in1' and '$ts_check_in2') ";
    }

    $t = new PgTable($con, "100%");
    
    $t->SQL = "select b.tdesc,c.obat,sum(a.pengadaan_jml_permintaan) as jml_permintaan,
   			  sum(a.pengadaan_jml_pemberian)as jml_pemberian 
			  From f_pengadaan_depo a 
			  Left join rs00001 b on b.tc = a.depo_id and b.tt ='LYN'
			  left join rs00015 c on c.id = a.obat_id  
			 
    		  where c.obat <> ''
			  Group by depo_id,b.tdesc,a.obat_id,c.obat ";
    
        if (!isset($_GET[sort])) {
           $_GET[sort] = "a.depo_id";
           $_GET[order] = "asc";
	}

    $t->ColHeader = array("KLINIK","NAMA OBAT","TOTAL PERMINTAAN", "TOTAL PEMBERIAN");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "LEFT";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[2] = "RIGHT";
    $t->RowsPerPage = 20;
    $t->execute();
    echo "<br>";
   echo "<div align=right>";
   echo "<img src='images/icon-new.png' align='absmiddle'> <A HREF='$SC?p=$PID&action=new&depo_id={$_GET["depo_id"]}'>Tambah Pengadaan</A>";
   echo "</div>";	
}elseif ($ext = "OnChange = 'Form2.submit();'" && $_GET["depo_id"]) {
include("xxx2");

$f->submit(" Tampilkan ");
$f->execute();
    if (!empty($_GET[tanggal1D]) or isset($ts_check_in1)) {
       $tmbh = "and (a.pengadaan_tgl between '$ts_check_in1' and '$ts_check_in2') ";
    }

    $t = new PgTable($con, "100%");
   	$t->SQL = "select to_char(a.pengadaan_tgl,'dd MON yyyy')as pengadaan_tgl,c.obat,sum(a.pengadaan_jml_permintaan) as jml_permintaan,
   			  sum(a.pengadaan_jml_pemberian)as jml_pemberian 
			  From f_pengadaan_depo a 
			  Left join rs00001 b on b.tc = a.depo_id and b.tt ='LYN'
			  left join rs00015 c on c.id = a.obat_id  
			  Where a.depo_id ='".$_GET["depo_id"]."'
			  $tmbh
			  Group by a.pengadaan_tgl,a.depo_id,a.obat_id,c.obat ";
    
        if (!isset($_GET[sort])) {
           $_GET[sort] = "a.depo_id";
           $_GET[order] = "asc";
	}

    $t->ColHeader = array("TANGGAL PENGADAAN","NAMA OBAT","TOTAL PERMINTAAN", "TOTAL PEMBERIAN");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[2] = "RIGHT";
    $t->RowsPerPage = 20;
    $t->execute();
    echo "<br>";
   echo "<div align=right>";
   echo "<img src='images/icon-new.png' align='absmiddle'> <A HREF='$SC?p=$PID&action=new&depo_id={$_GET["depo_id"]}'>Tambah Pengadaan</A>";
   echo "</div>";

}

}
?>
