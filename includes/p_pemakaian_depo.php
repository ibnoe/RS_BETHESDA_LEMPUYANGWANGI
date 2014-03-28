<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 09-05-2004

$PID = "p_pemakaian_depo";
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
    }
    
    
    if (strlen($_GET["ob4_id"]) > 0 && $_GET["ob4_jumlah_pakai"] > 0) {
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
        $_SESSION["ob4"]["obat"][$cnt]["tglPakai"]   = $_GET["ob4_tglPakai"];
        $_SESSION["ob4"]["obat"][$cnt]["keterangan"]  = $_GET["ob4_keterangan"];              
        $_SESSION["ob4"]["obat"][$cnt]["jumlah_pakai"] = $_GET["ob4_jumlah_pakai"];
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
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["jumlah_pakai"] = $_GET["editjumlah_pakai"];
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["keterangan"] = $_GET["editketerangan"];
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["tglPakai"] = $_GET["edittglPakai"];
        //$_SESSION["ob4"]["obat"][$_GET["editrow"]]["total"]  =
            //$_SESSION["ob4"]["obat"][$_GET["editrow"]]["jumlah"] *
            //$_SESSION["ob4"]["obat"][$_GET["editrow"]]["harga"];
    }
    header("Location: $SC?p=$PID&action=new&depo_id=".$_GET["depo_id"]);
    exit;
}

title("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  TRANSAKSI PEMAKAIAN DEPO");
echo "<br>";
if ($_GET["action"] == "new") {  
echo "<form action=$SC>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
$ext = "onchange='javascript:Form1.submit()'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    
    $f->selectSQL("depo_id", "Nama Layanan",
						"select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('206','207','208','201','000') order by tdesc "
						,$_GET["depo_id"],$ext);
	$f->submit("OK",$ext);					
    $f->execute();
    echo "<br>";
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
$depo=$_GET["depo_id"];
if (isset($depo)==$_GET["depo_id"]) {

    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }

    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("KODE", "NAMA OBAT","SATUAN","JUMLAH PEMAKAIAN", "KETERANGAN","TANGGAL PEMAKAIAN",""));
    if (is_array($_SESSION["ob4"]["obat"])) {
        $total = 0.00;
        foreach($_SESSION["ob4"]["obat"] as $k => $o ) {
            if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
                echo "<form action=$SC>";
                echo "<input type=hidden name=p value=$PID>";
                echo "<input type=hidden name=editrow value=$k>";
                echo "<input type=hidden name=httpHeader value=1>";
                echo "<input type=hidden name=depo_id value=".$_GET[depo_id].">";
                
                $t->printRow(
                    Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                        $o["obat"],
                        $o["satuan"],
                        "<input type=text size=5 maxlength=10 name=editjumlah_pakai value='".$o["jumlah_pakai"]."' style='text-align:right'>",
                        "<input type=text size=40 maxlength=50 name=editketerangan value='".$o["keterangan"]."' style='text-align:left'>",
                         "<input type=text size=10 maxlength=15 name=edittglPakai value='".$o["tglPakai"]."' style='text-align:right'>",
                        "<input type=submit value='Update'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID&action=new&depo_id={$_GET["depo_id"]}\"'>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER")
                    );
                echo "</form>";
            } else {
                $t->printRow(
                    Array( str_pad($o["id"],6,"0",STR_PAD_LEFT),
                        $o["obat"],
                        $o["satuan"],
                        $o["jumlah_pakai"],
                        $o["keterangan"],
                        $o["tglPakai"],
                        //number_format($o["harga"],2),
                        //number_format($o["total"],2),
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k&action=new&depo_id={$_GET["depo_id"]}'>".icon("del-left")."</a>".
                        " &nbsp; " .
                        "<a href='$SC?p=$PID&edit=$k&action=new&depo_id={$_GET["depo_id"]}'>".icon("edit")."</a>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "LEFT",
                        "CENTER",
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
        echo "<input type=hidden name=depo_id value=".$_GET[depo_id].">";
        $t->printRow(
            Array( 
            	"<input type=text size=5 maxlength=10 name=ob4_id style='text-align:center' value=$d1->id>"."&nbsp;<a href='javascript:selectObat()'>".icon("view")."</a>",
                $d1->obat,
                $d1->satuan,
                "<input type=text size=5 maxlength=10 name=ob4_jumlah_pakai value=1 style='text-align:right'>",
                "<input type=text size=40 maxlength=50 name=ob4_keterangan value='".$_SESSION["ob4"]["obat"]["keterangan"]."' style='text-align:left'>",
                "<input type=text size=7 maxlength=10 name=ob4_tglPakai value='".$tgl_sekarang."' style='text-align:right'>",
                //$d1->harga,
                //number_format($total,2,
                "<input type=submit value=OK>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER")
            );
        echo "</FORM>";
     
    }
    $t->printTableClose();



    if (is_array($_SESSION["ob4"]["obat"])) {
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/p_pemakaian_depo.insert.php' method=POST name=Form10>";
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
        $f->PgConn = $con;
$ext = "onchange='javascript:Form2.submit()'";        
        $f->selectSQL("depo_id", "Nama Layanan",
						"select '' as tc, 'SEMUA LAYANAN' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('206','207','208','201','000') order by tdesc "
						,$_GET["depo_id"],$ext);
if($_GET["depo_id"] == ''){
$f->submit(" Tampilkan ");
$f->execute();
    if (!empty($_GET[tanggal1D]) or isset($ts_check_in1)) {
       $tmbh = "and (a.pemakaian_tgl_entry between '$ts_check_in1' and '$ts_check_in2') ";
    }
    
    $t = new PgTable($con, "100%");
   	$t->SQL = "select b.tdesc,c.obat,sum(a.pemakaian_jml) as jml_pemakaian,a.pemakaian_ket 
			   From f_pemakaian_depo a 
			   Left join rs00001 b on b.tc = a.depo_id and b.tt ='LYN' 
			   left join rs00015 c on c.id = a.obat_id
			   where c.obat <> '' 
			   
			   Group by depo_id,b.tdesc,a.obat_id,c.obat,a.pemakaian_ket"; 

        if (!isset($_GET[sort])) {
           $_GET[sort] = "a.depo_id";
           $_GET[order] = "asc";
	}

    $t->ColHeader = array("KLINIK","NAMA OBAT","TOTAL PEMAKAIAN","KETERANGAN");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "LEFT";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[2] = "RIGHT";
    $t->RowsPerPage = 10;
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
       $tmbh = "and (a.pemakaian_tgl_entry between '$ts_check_in1' and '$ts_check_in2') ";
    }
     $t = new PgTable($con, "100%");
   	$t->SQL = "select to_char(a.pemakaian_tgl_entry,'dd MON yyyy')as pemakaian_tgl_entry,c.obat,sum(a.pemakaian_jml) as jml_pemakaian 
			   From f_pemakaian_depo a 
			   Left join rs00001 b on b.tc = a.depo_id and b.tt ='LYN' 
			   left join rs00015 c on c.id = a.obat_id  
			   Where a.depo_id ='".$_GET["depo_id"]."'
			   $tmbh
			   Group by a.pemakaian_tgl_entry,a.depo_id,a.obat_id,c.obat "; 
	
        if (!isset($_GET[sort])) {
           $_GET[sort] = "a.depo_id";
           $_GET[order] = "asc";
	}

    $t->ColHeader = array("TANGGAL PEMAKAIAN","NAMA OBAT","TOTAL PEMAKAIAN");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[2] = "RIGHT";
    $t->RowsPerPage = 10;
    $t->execute();
    echo "<br>";
   echo "<div align=right>";
   echo "<img src='images/icon-new.png' align='absmiddle'> <A HREF='$SC?p=$PID&action=new&depo_id={$_GET["depo_id"]}'>Tambah Pengadaan</A>";
   echo "</div>";
}

}

?>
