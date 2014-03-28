<?php
   	  title("Resep / Obat");
        echo "<script language='JavaScript'>\n";
       echo "document.Form3.b3.disabled = true;\n";
	   echo "document.Form3.b1.disabled = false;\n";
        echo "document.Form3.b2.disabled = false;\n";
        echo "document.Form3.b4.disabled = false;\n";
        echo "</script>\n";


        if ($_SESSION["SELECT_OBAT"]) {
           $namaObat = getFromTable("select obat from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
           $hargaObat = getFromTable("select harga from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
		   $ext = "";
        }else{
		$ext = "disabled";
		}
		
		$x_racikan = " <SELECT NAME='is_racikan'>\n";
        $x_racikan  .= "<OPTION VALUE=N>N</OPTION>\n";
        $x_racikan  .= "<OPTION VALUE=Y>Y</OPTION>\n";
        "</SELECT></TD>\n";
		
        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
//   echo "<a href='$SC?p=$PID&rg=".$_GET[rg]."&sub=retur'>[RETUR OBAT]</a>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "Nama Obat", "Dosis", "Ket. Racikan","Jumlah", "Harga Satuan", "Harga Total", ""));


        if (is_array($_SESSION["obat"])) {

            foreach($_SESSION["obat"] as $k => $l) {
                $t->printRow2(
                    Array($l["id"], $l["desc"],$l["dosis"], $l["is_racikan"], $l["jumlah"], number_format($l["harga"],2), number_format($l["total"],2),
                    "<A HREF='$SC?p=$PID&list=resepobat&rg=".$_GET["rg"]."&mr=".$_GET["mr"]."&del-obat=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "CENTER", "RIGHT", "RIGHT", "CENTER")
                );
            }
        }
        
	// sfdn, 27-12-2006 -> pembetulan directory icon = ../simrs/images/*.png
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit2()' NAME=obat STYLE='text-align:center' TYPE=TEXT SIZE=5
            MAXLENGTH=10 VALUE='".$_SESSION["SELECT_OBAT"]."'>&nbsp;<A HREF='javascript:selectObat()'>
            <IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaObat,
			"<INPUT VALUE='".(isset($_GET["dosis"]) ? $_GET["dosis"] : "3 x 1")."' NAME=dosis_obat
            OnKeyPress='refreshSubmit2()' TYPE=TEXT SIZE=30 MAXLENGTH=30 VALUE='0' STYLE='text-align:left'>",//penambahan dosis
            $x_racikan,
            "<INPUT VALUE='".(isset($_GET["jumlah_obat"]) ? $_GET["jumlah_obat"] : "1")."'NAME=jumlah_obat
            OnKeyPress='refreshSubmit2()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>",
            number_format($hargaObat,2), "",
            "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK' $ext>"),
            Array("CENTER", "LEFT", "CENTER","LEFT", "CENTER", "RIGHT", "RIGHT", "CENTER")
        );
	// --- eof 27-12-2006 ---
        $t->printTableClose();
        echo "</FORM>";

        echo "\n<script language='JavaScript'>\n";
        echo "function selectObat() {\n";
        echo "    sWin = window.open('popup/obat_apotek1.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";

echo "<table border=0 width='100%'><tr>";
	echo "<td align=right valign=top>";

  echo "<form name='Form9' action='actions/p_pelayanan.insert.php' method=POST>";
        echo "<input type=hidden name=p value='".$_GET["p"]."'>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='resepobat'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub2 VALUE='".$_GET["sub2"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<INPUT TYPE=HIDDEN NAME=poli VALUE='".$_GET["mPOLI"]."'>";
        echo "<input type=hidden name=list value='resepobat'>";
		echo "<input type=hidden name=sub2 value='".$_GET["sub2"]."'>";
        echo "<input type=button value='Simpan' onClick='document.Form9.submit()'>&nbsp;";
        //if ($total > 0) echo "<input type=button value='Simpan &amp; Bayar' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=byr\"'>&nbsp;";
        echo "</form>";
//$att = "320RJJANTUNG" ;
?>
