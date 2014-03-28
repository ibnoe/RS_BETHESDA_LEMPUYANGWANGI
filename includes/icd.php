<?php
        echo"<div align=center class=form_subtitle1>KLASIFIKASI PENYAKIT</div>";
        echo "<table width='100%' border=0 cellspacing=0 cellpadding=0><tr>";
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b2.disabled = true;\n";
        echo "document.Form3.b4.disabled = false;\n";
        echo "</script>\n";
        echo "<form action='$SC'>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='".$PID."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=poli VALUE='".$_GET["mPOLI"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        echo "</form>";
        echo "<td valign=top>";

        $namaICD = getFromTable("SELECT DESCRIPTION FROM RSV0005 WHERE DIAGNOSIS_CODE = '".$_SESSION["SELECT_ICD"]."'");
        $katICD = getFromTable("SELECT CATEGORY FROM RSV0005 WHERE DIAGNOSIS_CODE = '".$_SESSION["SELECT_ICD"]."'");
        
        $t = new BaseTable("100%");
        $t->printTableOpen();
        echo "<FORM ACTION='$SC' NAME=Form11>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t->printTableHeader(Array("KODE ICD", "KETERANGAN","KATEGORI", "&nbsp;"));
        
        if (is_array($_SESSION["icd"])) {
            foreach($_SESSION["icd"] as $k => $l) {
                $t->printRow2(
                    Array($l["id"], $l["desc"],$l["kate"], "<A HREF='$SC?p=$PID&list=icd&rg=".$_GET["rg"]."&mr=".$_GET["mr"]."&del-icd=$k&httpHeader=1'>".icon("del-left")."</A>"), 
                    Array("CENTER", "LEFT", "LEFT","CENTER")
                );
            }
        }
		// sfdn, 27-12-2006 --> pembetulan directory icon = ../simrs/images/*.png
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=icd STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_ICD"]."'>&nbsp;<A HREF='javascript:selectICD()'><IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaICD,"$katICD", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "LEFT","CENTER")
        );
		// --- eof 27-12-2006 ---
        echo "</FORM>";
        
        $t->printTableClose();
        //echo "</td></tr></table>";
        echo "\n<script language='JavaScript'>\n";
        echo "function selectICD() {\n";
        echo "sWin = window.open('popup/icd.php', 'xWin', 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
        
        echo "<form name='Form9' action='actions/p_pelayanan.insert.php' method=POST>";
		echo "<input type=hidden name=p value='".$_GET["p"]."'>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=poli VALUE='".$_GET["mPOLI"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<br><div align=right><input type=button value='Simpan' onClick='document.Form9.submit()'>&nbsp;</div>";
        echo "</form>";
     
		    // DIAGNOSA
	$rec1 = getFromTable ("select count(id) from rs00008 ".	// sfdn, 27-12-2006 --> melakukan testing apakah ada data diagnosa
						  "where trans_type = 'ICD' and no_reg ='".$_GET["rg"]."'");
	if ($rec1 > 0) {

		$f = new Form("");
		echo "<br>";
		$f->title1("Data Diagnosa");
		$f->execute();
		
		$t = new PgTable($con, "100%");
		$t->SQL = "select a.item_id,b.description,b.category,a.oid from rs00008 a 
				   left join rsv0005 b on b.diagnosis_code = a.item_id
				   where trans_type='ICD' and a.no_reg ='".$_GET["rg"]."' order by tanggal_entry";		   
		$t->setlocale("id_ID");
		$t->ShowRowNumber = true;
		$t->RowsPerPage = $ROWS_PER_PAGE;
		$t->ColHeader = array("KODE ICD","DESKRIPSI ICD","DIAGNOSA","HAPUS");
		$t->ColAlign = array("center","left","left","center");
		$t->DisableScrollBar = true;
		$t->DisableStatusBar = true;	
		$t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='actions/p_icd.delete.php?p=$PID&sub=icd&list=icd&mr=".$_GET["mr"]."&poli=".$_GET["mPOLI"]."&rg=".$_GET["rg"]."&id=<#3#>'>".icon("delete","Hapus")."</A>";			
		$t->execute();
	}
	
	echo "<br>";
?>