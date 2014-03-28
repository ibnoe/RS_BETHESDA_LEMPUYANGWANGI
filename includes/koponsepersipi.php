<?php
$PID = "koponsepersipi";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

echo "<br>";
title_print("Konfigurasi Konversi Satuan ");
echo "<br>";

$dt_obt=pg_query($con,"select * from rsv0004 where id = ".$_GET[e]."");

$d3 = pg_fetch_object($dt_obt);
pg_free_result($dt_obt);


// data untuk rincian table konversi
$f = new Form("", "POST");
$f->hidden("id", $_GET["id"]);
$f->text("description","Kode Obat",20,40,$d3->id,"disabled");
$f->text("harga","Nama Obat",50,40,$d3->obat,"disabled");
$f->text("satuan","Satuan Jual",50,40,$d3->satuan,"disabled");
$f->execute();

        echo "<div align=right><a href='$SC?p=807&mOBT=".$_GET["o"]."'>Kembali ke Master Inventory</a></div>";
        echo "<FORM ACTION='actions/koponsepersipi.insert.php?configtype=1' METHOD=POST>";
        echo "<INPUT TYPE=HIDDEN NAME=kode_obat VALUE='" . $d3->id . "'>";
        echo "<INPUT TYPE=HIDDEN NAME=o VALUE='" .$_GET["o"]. "'>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("Jumlah perSatuan", "Satuan Utama", "Jumlah Isi", "Satuan Konversi","Simpan"));
        $r1 = pg_query($con,
            "select a.kode_trans,a.jumlah1,b.tdesc as satuan1,c.tdesc as satuan2, a.jumlah2,a.harga_jual
			 from rs00016d a 
			left join rs00001 b on b.tc=a.satuan1 and b.tt='SAT'
			left join rs00001 c on c.tc=a.satuan2 and c.tt='SAT'
			 where a.kode_obat=".$d3->id."
			");
        while ($d1 = pg_fetch_object($r1)) {
        $t->printRow2(
            Array( $d1->jumlah2, $d1->satuan2,$d1->jumlah1,$d1->satuan1,
                "<A HREF='actions/koponsepersipi.delete.php?kode_trans=$d1->kode_trans&e=$d3->id&o=".$_GET["o"]."'>".icon("del-left")."</A>"),
            Array("CENTER", "LEFT","CENTER", "left", "RIGHT","center",  "RIGHT", "CENTER")
            );
        }
        pg_free_result($r1);
 //---///       
        /* $x_satuan = " <SELECT NAME='satuan1'>\n";
        $xr = pg_query($con, "select tc, tdesc from rs00001 where tt = 'SAT' and tc != '000' order by tc asc ");
        while($xd = pg_fetch_array($xr)) {
            $x_satuan  .= "<OPTION VALUE='$xd[0]'>$xd[1]</OPTION>\n";
        }
        pg_free_result($xr);
        $x_satuan .= "</SELECT></TD>\n"; */
		
		
		$x_satuan2 = " <SELECT NAME='satuan2'>\n";
        $xr = pg_query($con, "select tc, tdesc from rs00001 where tt = 'SAT' and tc != '000' order by tc asc ");
        while($xd = pg_fetch_array($xr)) {
            $x_satuan2  .= "<OPTION VALUE='$xd[0]'>$xd[1]</OPTION>\n";
        }
        pg_free_result($xr);
        $x_satuan2 .= "</SELECT></TD>\n";


        if (isset($_SESSION["SELECT_LAYANAN"])) {
            $xr = pg_query($con,"select layanan from rsv0034 where id = '" . $_SESSION["SELECT_LAYANAN"] . "'");
            $xd = pg_fetch_object($xr);
            pg_free_result($xr);

        }
                
        
        $t->printRow2(
            Array("<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1")."'NAME=jumlah2 OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=10 MAXLENGTH=10 VALUE='1' readonly STYLE='text-align:right'>", 
			$x_satuan2, 
			"<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1")."' NAME=jumlah1 OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1'  STYLE='text-align:right'>", 
			"<INPUT VALUE='".$d3->satuan_id."'NAME=satuan1 OnKeyPress='refreshSubmit()' TYPE=hidden SIZE=10 MAXLENGTH=20 STYLE='text-align:right'>".$d3->satuan, 
            "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Tambah'>"),
            Array("CENTER", "LEFT","CENTER", "left","RIGHT", "center",   "LEFT", "CENTER")
        );
        $t->printTableClose();
        echo "</FORM>";
		
		
/* 		//OBAT Agung Sunandar 13:06 05/06/2012
		echo "<hr size=1 noshade>";
        echo "<b>\"$description\" terdiri dari obat berikut:</b><br><br>";

        echo "<FORM ACTION='actions/koponsepersipi.1.insert.php?configtype=1' METHOD=POST>";
        echo "<INPUT TYPE=HIDDEN NAME=id VALUE='" . $_GET["id"] . "'>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "Obat",  "Satuan","Jumlah", "Harga Satuan", "Harga Total", ""));
        $r1 = pg_query($con,
            " select rs99997.item_id, obat,satuan, rs99997.qty, harga, rs99997.id 
				from rs99997                                                                                             
				join rs99996 on rs99996.id = rs99997.preset_id and rs99997.trans_type = 'OBI'                            
				left join rsv0004 on rsv0004.id = rs99997.item_id ".
            " where rs99997.preset_id = '".$_GET["id"]."'"
            );
        while ($d1 = pg_fetch_object($r1)) {
        $t->printRow(
            Array($d1->item_id, $d1->obat,$d1->satuan, $d1->qty, 
                number_format($d1->harga,2), number_format($d1->harga * $d1->qty,2),
                "<A HREF='actions/koponsepersipi.1.delete.php?configtype=1&id=$d1->id&return=".$_GET["id"]."'>".icon("del-left")."</A>"),
            Array("CENTER", "LEFT","LEFT", "CENTER", "RIGHT", "RIGHT", "CENTER")
            );
        }
        pg_free_result($r1);

        if (isset($_SESSION["SELECT_OBAT"])) {
            $xr = pg_query($con,"select * from rsv0004 where id = '" . $_SESSION["SELECT_OBAT"] . "'");
            $xd = pg_fetch_object($xr);
            pg_free_result($xr);

        }
   
        
        $t->printRow(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=obat STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_OBAT"]."'>&nbsp;<A HREF='javascript:selectObat()'><IMG BORDER=0 SRC='images/icon-view.png'></A>", $xd->obat,
            $xd->satuan
            , "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1")."'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>",  $xd->harga, "", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Tambah'>"),
            Array("CENTER", "LEFT", "CENTER", "LEFT", "RIGHT", "LEFT", "CENTER")
        );
        $t->printTableClose();
        echo "</FORM>";
 */
        echo "\n<script language='JavaScript'>\n";
        echo "function selectLayanan() {\n";
        echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
		
		echo "\n<script language='JavaScript'>\n";
        echo "function selectObat() {\n";
        echo "    sWin = window.open('popup/obat.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
    
	
?>
