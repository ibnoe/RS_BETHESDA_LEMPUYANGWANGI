<?php
        echo"<div align=center class=form_subtitle1>T I N D A K A N</div>";
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
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd9'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        echo "</form>";
        echo "<td valign=top>";

        $namaICD9 = getFromTable("SELECT nama FROM icd_9 WHERE kode = '".$_SESSION["SELECT_ICD9"]."'");
        
        $t = new BaseTable("100%");
        $t->printTableOpen();
        echo "<FORM ACTION='$SC' NAME=Form11>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd9'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t->printTableHeader(Array("KODE ICD 9", "DESKRIPSI", "&nbsp;"));
        if (is_array($_SESSION["icd9"])) {
            foreach($_SESSION["icd9"] as $k => $l) {
                $t->printRow2(
                    Array($l["id"], $l["desc"], "<A HREF='$SC?p=$PID&list=icd9&rg1=".$_GET["rg1"]."&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=".$_GET["mr"]."&del-icd9=$k&httpHeader=1'>".icon("del-left")."</A>"), Array("CENTER", "LEFT", "CENTER")
                );
            }
        }
	$t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=icd9 STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_ICD9"]."'>&nbsp;<A HREF='javascript:selectICD9()'><IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaICD9, "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "CENTER")
        );
		// --- eof 27-12-2006 ---
        echo "</FORM>";
        
        $t->printTableClose();
        echo "\n<script language='JavaScript'>\n";
        echo "function selectICD9() {\n";
        echo "sWin = window.open('popup/icd_9.php', 'xWin', 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
        
        echo "<form name='Form9' action='actions/p_pelayanan.insert.php' method=POST>";
		echo "<input type=hidden name=p value='".$_GET["p"]."'>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=poli VALUE='".$_GET["mPOLI"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd9'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<br><div align=right><input type=button value='Simpan' onClick='document.Form9.submit()'>&nbsp;</div>";
        echo "</form>";
     
		    // DIAGNOSA
	$rec1 = getFromTable ("select count(id) from rs00008 where trans_type = 'CD9' and no_reg ='".$_GET["rg"]."'");
        
	if ($rec1 > 0) {

            $f = new Form("");
            echo "<br>";
            $f->title1("Data Tindakan");
            $f->execute();

            $t = new PgTable($con, "100%");
            $t->SQL = "select a.item_id, b.nama, a.oid  from rs00008 a 
                                left join icd_9 b on b.kode = a.item_id
                                where trans_type='CD9' and a.no_reg ='".$_GET["rg"]."' order by tanggal_entry";		   
            $t->setlocale("id_ID");
            $t->ShowRowNumber = true;
            $t->RowsPerPage = $ROWS_PER_PAGE;
            $t->ColHeader = array("KODE ICD","TINDAKAN (ICD 9)", 'HAPUS');
            $t->ColAlign = array("center","left","center");
            $t->DisableScrollBar = true;
            $t->DisableStatusBar = true;	
            $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='actions/p_icd9_ri.delete.php?p=$PID&sub=icd9&list=icd9&mr=".$_GET["mr"]."&poli=".$_GET["mPOLI"]."&rg=".$_GET["rg"]."&id=<#2#>'>".icon("delete","Hapus")."</A>";			
            $t->execute();
	}
	echo "<br>";
?>