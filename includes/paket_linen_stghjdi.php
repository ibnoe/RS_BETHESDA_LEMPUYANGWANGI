<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 09-05-2004

$PID = "paket_linen_stghjdi";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

//ga tau buat apa, tapi jgn dihapus
if ($_GET["httpHeader"] == "1") {

    if (isset($_GET["nomor_invoice"])) {
        $_SESSION["ob4"]["nomor-invoice"] = $_GET["nm_bangsal"];
		echo $_GET["nm_bangsal"];
    }
    
     //buat insert ke db  
    if (strlen($_GET["ob4_id"]) > 0 ) {
        if (is_array($_SESSION["ob4"]["obat"])) {
            $cnt = count($_SESSION["ob4"]["obat"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from linen where id = '".$_GET["ob4_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        $_SESSION["ob4"]["obat"][$cnt]["id"]     = $d1->id;
        $_SESSION["ob4"]["obat"][$cnt]["obat"]   = $d1->no_seri;
        $_SESSION["ob4"]["obat"][$cnt]["id_dist"]= $d1->jenis_linen;
		$_SESSION["ob4"]["obat"][$cnt]["bangsal"]= $_SESSION["BANGSAL"]["id"];
        unset($_SESSION["SELECT_OBAT"]);
    }
	
	//hapus item
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
    
    header("Location: $SC?p=$PID&action=new&&depo_id=".$_GET["depo_id"]."");
    exit;
}

title("<img src='icon/rawat-inap-2.gif' align='absmiddle' > Distribusi Par");
if ($_GET["action"] == "new") {  

//id paket
echo "<form action=$SC>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    echo "<br>";
   $f->text("depo_id","ID PAR",30,30,"<OTOMATIS>","DISABLED");
	$f->textAndButton("nm_bangsal","Bangsal",60,70,$_SESSION["BANGSAL"]["desc"],"DISABLED","...",
        "OnClick='selectBangsal();';");			
	$f->execute();
    
	//pilih bangsal
   	echo "\n<script language='JavaScript'>\n";
	echo "function selectBangsal() {\n";
	echo "    sWin = window.open('popup/bangsal_linen.php', 'xWin', 	'width=600,height=400,menubar=no,scrollbars=yes');\n";
	echo "    sWin.focus();\n";
	echo "}\n";
	echo "</script>\n";
	
	//masukun detail bangsal
	if (isset($_SESSION["SELECT_BANGSAL"])) {
    	$_SESSION["BANGSAL"]["id"] = $_SESSION["SELECT_BANGSAL"];
    	$_SESSION["BANGSAL"]["desc"] =
        	getFromTable(
            "select c.bangsal || ' / ' || b.bangsal || ' / ' || a.bangsal ".
            "from rs00012 as a ".
            "    join rs00012 as b on b.hierarchy = substr(a.hierarchy,1,6) || '000000000' ".
            "    join rs00012 as c on c.hierarchy = substr(a.hierarchy,1,3) || '000000000000' ".
            "where a.id = '".$_SESSION["BANGSAL"]["id"]."'"
        );
    	unset($_SESSION["SELECT_BANGSAL"]);
		}     
	echo "<table border=0>";
	echo "    <td class=FORM colspan=2><input type=SUBMIT value='&nbsp; OK &nbsp;'></td></tr>";
	echo "</table>";
	echo "</form>";


//masukin item
    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from linen where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }
    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("ID", "SERIAL","JENIS",""));
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
                    Array( $o["id"],
                        $o["obat"],$o["id_dist"],
                        "<input type=submit value='Update'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID&action=new&depo_id={$_GET["depo_id"]}\"'>" ),
                    Array( "CENTER",
                        "CENTER",
                        "RIGHT",
                        "RIGHT" )
                    );
                echo "</form>";
            } 
			
			else {
                $t->printRow(
                    Array( $o["id"],
                        $o["obat"],$o["id_dist"],
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k&action=new&depo_id={$_GET["depo_id"]}'>".icon("del-left")."</a>".
                        " &nbsp; " ),
                    Array( "CENTER",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                         )
                    );
            }
        }
    }
    if (strlen($_GET["edit"]) == 0) {
        echo "<form action=$SC?p=$PID&edit=$k&action=new>";
        echo "<input type=hidden name=p value=$PID>";
        echo "<input type=hidden name=httpHeader value=1>";
        $t->printRow(
            Array( 
            	"<input type=text size=5 maxlength=10 name=ob4_id style='text-align:center' value=$d1->id>"."&nbsp;<a href='javascript:selectObat()'>".icon("view")."</a>",
                $d1->no_seri,
                $d1->jenis_linen,
                "<input type=submit value=OK>" ),
                    Array( "CENTER",
                        "CENTER",
                        "CENTER",
                        "CENTER"
                         )
            );
        echo "</FORM>";
     
    }
    $t->printTableClose();
    if (is_array($_SESSION["ob4"]["obat"])) {
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/paket_linen.insert.php' method=POST name=Form10>";
        echo "<input type=submit value=' &nbsp; Simpan &nbsp; '>";
        echo "</form>";
        echo "</div>";
    }
	
	//select linen
    echo "\n<script language='JavaScript'>\n";
    echo "function selectObat() {\n";
    echo "    sWin = window.open('popup/linen_pilih.php', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
}

else {
		$t = new PgTable($con, "100%");
		$t->SQL = "select d.id_par as par, c.bangsal || ' / ' || b.bangsal || ' / ' || a.bangsal as bangsal from par d,rs00012 as a join rs00012 as b on b.hierarchy = substr(a.hierarchy,1,6) || '000000000' join rs00012 as c on c.hierarchy = substr(a.hierarchy,1,3) || '000000000000' where a.id = d.nama_bangsal";
		if (!isset($_GET[sort])) {
           $_GET[sort] = "bangsal";
           $_GET[order] = "asc";
		   }
  		$t->ColHeader = array("ID PAR","Ruangan","Edit/Hapus");
   		$t->ShowRowNumber = true;
   		$t->ColAlign[0] = "CENTER";
		$t->ColAlign[1] = "CENTER";
    	$t->ColAlign[2] = "CENTER";
		$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<##>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='$SC?p=$PID&n=<##>'>".icon("delete","Hapus")."</A>".
            "</nobr>"; 
    	$t->ColAlign[10] = "CENTER";	
		$t->ColAlign[11] = "CENTER";
 		$t->execute();
	
   echo "<img src='images/icon-new.png' align='absmiddle'> <A HREF='$SC?p=$PID&action=new>Tambah Paket</A>";
   echo "</div>";	

}
?>
