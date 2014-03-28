<?php // Agung Sunandar

$PID = "internal_transfer1";
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
    
    
    if (strlen($_GET["ob4_id"]) > 0 && $_GET["jumlah_obat"] > 0) {
        if (is_array($_SESSION["ob4"]["obat"])) {
            $cnt = count($_SESSION["ob4"]["obat"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_GET["ob4_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        
        $_SESSION["ob4"]["obat"][$cnt]["id"]     = $d1->id;
		$_SESSION["ob4"]["obat"][$cnt]["batch"]  = $d1->batch;
        $_SESSION["ob4"]["obat"][$cnt]["obat"]   = $d1->obat;
        $_SESSION["ob4"]["obat"][$cnt]["satuan"] = $d1->satuan;
        $_SESSION["ob4"]["obat"][$cnt]["keterangan"]  = $_GET["ob4_keterangan"];              
        $_SESSION["ob4"]["obat"][$cnt]["jumlah_pakai"] = $_GET["jumlah_obat"];
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

    }
    header("Location: $SC?p=$PID&action=new&poli_asal=".$_GET["poli_asal"]."&poli_tujuan=".$_GET["poli_tujuan"]."");
    exit;
}


if ($_GET["action"] == "new") { 
title("<img src='icon/apotik-2.gif' align='absmiddle' >  TRANSAKSI INTERNAL TRANSFER  NON BANGSAL (TAMBAH)");
echo "<br>"; 
echo "<form action=$SC>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
$ext = "onchange='javascript:Form1.submit()'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->selectSQL("poli_asal", "Dari ",
						"select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc in ('003','020','021','022','023') order by tdesc "
						,$_GET["poli_asal"],$ext);
    $f->selectSQL("poli_tujuan", "Tujuan ",
						"select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc in ('003','020','021','022','023','".$_GET["poli_asal"]."') order by tdesc "
						,$_GET["poli_tujuan"],$ext);
	$f->submit("OK",$ext);					
    $f->execute();
    echo "<br>";
        ?>
        <SCRIPT language="JavaScript">
            document.Form1.poli_tujuan.selectedIndex = -1;
            function setPoli( v )
            {
                document.Form1.poli_tujuan.disabled = v == "N";
                document.Form1.poli_tujuan.selectedIndex = document.Form1.poli_tujuan.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.poli_tujuan.selectedIndex : -1;
            }
			
			document.Form1.poli_asal.selectedIndex = -1;
            function setPoli( v )
            {
                document.Form1.poli_asal.disabled = v == "N";
                document.Form1.poli_asal.selectedIndex = document.Form1.poli_asal.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.poli_asal.selectedIndex : -1;
            }
        </SCRIPT>
        <?php
$depo=$_GET["poli_tujuan"];
if (isset($depo)==$_GET["poli_tujuan"]) {

    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }
		if ($_GET["poli_asal"]=="004") {
           $q=  getFromTable("select qty_apotek1 from rs00016a where obat_id=$d1->id"); 
		   }elseif ($_GET["poli_asal"]=="005") {
           $q=  getFromTable("select qty_apotek2 from rs00016a where obat_id=$d1->id"); 
		   }elseif ($_GET["poli_asal"]=="006") {
           $q=  getFromTable("select qty_apotek3 from rs00016a where obat_id='".$d1->id."'"); 
		   }elseif ($_GET["poli_asal"]=="007") {
           $q=  getFromTable("select qty_apotek4 from rs00016a where obat_id='".$d1->id."'"); 
		   }elseif ($_GET["poli_asal"]=="009") {
           $q=  getFromTable("select qty_apotek5 from rs00016a where obat_id='".$d1->id."'"); 
		   }elseif ($_GET["poli_asal"]=="009") {
           $q=  getFromTable("select qty_apotek6 from rs00016a where obat_id='".$d1->id."'"); 
		   }elseif ($_GET["poli_asal"]=="010") {
           $q=  getFromTable("select qty_apotek7 from rs00016a where obat_id='".$d1->id."'"); 
		   }elseif ($_GET["poli_asal"]=="011") {
           $q=  getFromTable("select qty_apotek8 from rs00016a where obat_id='".$d1->id."'"); 
		   }elseif ($_GET["poli_asal"]=="012") {
           $q=  getFromTable("select qty_apotek9 from rs00016a where obat_id='".$d1->id."'"); 
		   }elseif ($_GET["poli_asal"]=="003") {
           $q=  getFromTable("select gudang from rs00016a where obat_id='".$d1->id."'"); 
		   }
		   
		   $x_jumlah = " <SELECT name='jumlah_obat'>\n";
          for ($i='1'; $i<=$q; $i++){
		  
                 if ($i=='1') {
             $x_jumlah  .= "<option value=$i selected>$i</option>\n";

          }
            else {
            $x_jumlah  .= " <option value=$i>$i</option>\n";
          }
          }
        "</SELECT>\n";
		
    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("BATCH ID","KODE", "NAMA OBAT","SATUAN","JUMLAH", "KETERANGAN",""));
    if (is_array($_SESSION["ob4"]["obat"])) {
        $total = 0.00;
        foreach($_SESSION["ob4"]["obat"] as $k => $o ) {
            if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
                echo "<form action=$SC onSubmit='return validasi2()' name=formx>";
                echo "<input type=hidden name=p value=$PID>";
                echo "<input type=hidden name=editrow value=$k>";
                echo "<input type=hidden name=httpHeader value=1>";
                echo "<input type=hidden name=poli_tujuan value=".$_GET[poli_tujuan].">";
				echo "<input type=hidden name=poli_asal value=".$_GET[poli_asal].">";
                
                $t->printRow(
                    Array( str_pad($o["batch"],6,"0",STR_PAD_LEFT),
						$o["id"],
                        $o["obat"],
                        $o["satuan"],
                        $x_jumlah,
                        "<input type=text size=40 maxlength=50 name=editketerangan value='".$o["keterangan"]."' style='text-align:left'>",
                        //"<input type=text size=10 maxlength=15 name=edittglPakai value='".$o["tglPakai"]."' style='text-align:right'>",
                        "<input type=submit value='Update'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}\"'>" ),
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
                    Array( str_pad($o["batch"],6,"0",STR_PAD_LEFT),
                        $o["id"],
						$o["obat"],
                        $o["satuan"],
                        $o["jumlah_pakai"],
                        $o["keterangan"],
                        //$o["tglPakai"],
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}'>".icon("del-left")."</a>"),
                        //" &nbsp; " ,
                        //"<a href='$SC?p=$PID&edit=$k&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}'>".icon("edit")."</a>" ),
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

    if (strlen($_GET["edit"]) == "0") {
        
		echo "<form action=$SC name=formx>";
        echo "<input type=hidden name=p value=$PID>";
        echo "<input type=hidden name=httpHeader value=1>";
        echo "<input type=hidden name=poli_asal value=".$_GET[poli_asal].">";
		echo "<input type=hidden name=poli_tujuan value=".$_GET[poli_tujuan].">";
		
		
		   if ($_GET["poli_asal"]=="004") {
		   $q=  getFromTable("select qty_apotek1 from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'"); 
		   }elseif ($_GET["poli_asal"]=="005") {
           $q=  getFromTable("select qty_apotek2 from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'"); 
		   }elseif ($_GET["poli_asal"]=="006") {
           $q=  getFromTable("select qty_apotek3 from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'"); 
		   }elseif ($_GET["poli_asal"]=="007") {
           $q=  getFromTable("select qty_apotek4 from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'"); 
		   }elseif ($_GET["poli_asal"]=="009") {
           $q=  getFromTable("select qty_apotek5 from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'"); 
		   }elseif ($_GET["poli_asal"]=="009") {
           $q=  getFromTable("select qty_apotek6 from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'"); 
		   }elseif ($_GET["poli_asal"]=="010") {
           $q=  getFromTable("select qty_apotek7 from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'"); 
		   }elseif ($_GET["poli_asal"]=="011") {
           $q=  getFromTable("select qty_apotek8 from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'"); 
		   }elseif ($_GET["poli_asal"]=="012") {
           $q=  getFromTable("select qty_apotek9 from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'"); 
		   }elseif ($_GET["poli_asal"]=="003") {
           $q=  getFromTable("select gudang from rs00016a where obat_id='".$_SESSION["SELECT_OBAT"]."'"); 
		   }
		   
		   $x_jumlah = " <SELECT name='jumlah_obat'>\n";
          for ($i='1'; $i<=$q; $i++){
		  
                 if ($i=='1') {
             $x_jumlah  .= "<option value=$i selected>$i</option>\n";

          }
            else {
            $x_jumlah  .= " <option value=$i>$i</option>\n";
          }
          }
        "</SELECT>\n";
		
        $t->printRow(
            Array( 
            	"<input type=text size=20 maxlength=20 name=ob4_id1 style='text-align:center' value=$d1->batch>"."&nbsp;<a href='javascript:selectObat()'><input type=hidden size=20 name=ob4_id value=$d1->id>".icon("view")."</a>",
				$d1->id,
				$d1->obat,
                $d1->satuan,
				$x_jumlah,
                //$x_jumlah,
                "<input type=text size=40 maxlength=50 name=ob4_keterangan value='".$_SESSION["ob4"]["obat"]["keterangan"]."' style='text-align:left'>",
                //"<input type=text size=7 maxlength=10 name=ob4_tglPakai value='".$tgl_sekarang."' style='text-align:right'>",
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
        echo "<form action='actions/internal_transfer1.insert.php' method=POST name=Form10>";
        echo "<input type=hidden name=poli_tujuan value=".$_GET[poli_tujuan].">";
		echo "<input type=hidden name=poli_asal value=".$_GET[poli_asal].">";
        echo "<input type=submit value=' &nbsp; Simpan &nbsp; '>";
        echo "</form>";
        echo "</div>";
    }

	
    echo "\n<script language='JavaScript'>\n";
    echo "function selectObat() {\n";
    echo "    sWin = window.open('popup/obat_gudang.php', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
}
} else if($_GET["action"] == "view") {
echo "<table>";
echo "<tr>";
echo "<td>".title("<img src='icon/apotik-2.gif' align='absmiddle' >  RINCIAN OBAT INTERNAL TRANSFER  NON BANGSAL ")."</td>";
echo "<td>".title_print("")."</td>";
echo "</tr>";
//title("<img src='icon/apotik-2.gif' align='absmiddle' >  RINCIAN OBAT INTERNAL TRANSFER");
//title_print("");
echo "</table>";

    
    $tanggal = getFromTable(
               "select to_char(tanggal_trans,'DD Mon YYYY') from internal_transfer_m ".
               "where kode_transaksi='".$_GET["f"]."' ");


    $f = new Form("");

echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NO. TRANSAKSI </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["f"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> ASAL </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["e"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> TUJUAN </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["g"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> TANGGAL TRANSAKSI</td>";
		echo "<td bgcolor='B0C4DE'><b>: $tanggal </td>";
	echo "</tr>";
	
echo "</table>";

    $f->execute();
    
    if (!$GLOBALS['print']){
    	//echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    } else {
    	"";
    }


    echo "<br>";
    $t = new PgTable($con, "100%");   
   	$t->SQL = "select a.obat,b.batch_id,b.jumlah,b.keterangan,nm_user from internal_transfer_d b, rs00015 a where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id";

    $t->ColHeader = array("NAMA OBAT", "BATCH ID","QTY","KETERANGAN","USER ENTRY");
	$t->ColAlign = array("LEFT","LEFT","CENTER","LEFT","LEFT");
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
	//$t->ColFooter [5]=  number_format($d2->jml_tagihan,2,',','.');
    $t->execute();
}else {
title("<img src='icon/apotik-2.gif' align='absmiddle' >  TRANSAKSI INTERNAL TRANSFER  NON BANGSAL");
echo "<br>"; 

	//include ("xxx2");
	
	$f = new Form($SC, "GET", "NAME=Form2");
	$f->hidden("p",$PID);
    $f->PgConn = $con;
	
	$ext = "onchange='javascript:Form2.submit()'";        
	
    $t = new PgTable($con, "100%");
   	$t->SQL = "select tanggal(a.tanggal_trans,0),c.tdesc as poli_asal, b.tdesc,a.kode_transaksi 
			   From internal_transfer_m a, rs00001 b, rs00001 c 
			   where c.tt='GDP' and b.tc=a.poli_tujuan and c.tc=a.poli_asal and b.tt='GDP' and a.status='1'
			   Group by a.tanggal_trans,c.tdesc ,b.tdesc,a.kode_transaksi "; 

        if (!isset($_GET[sort])) {
           $_GET[sort] = "tanggal_trans";
           $_GET[order] = "asc";
	}

    $t->ColHeader = array("TANGGAL","POLI ASAL","POLI TUJUAN","VIEW");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[3] = "CENTER";
	$t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=view&f=<#3#>&e=<#1#>&g=<#2#>'>".icon("view","View")."</A>";
    $t->RowsPerPage = 20;
    $t->execute();
    echo "<br>";
   echo "<div align=right>";
   echo "<img src='icon/apotik.gif' align='absmiddle'> <A HREF='$SC?p=$PID&action=new&poli_asal=&poli_tujuan='>Tambah Pengadaan</A>";
   echo "</div>";	
}

?>
