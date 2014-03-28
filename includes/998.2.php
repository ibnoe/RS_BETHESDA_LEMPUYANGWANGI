f<?php // Nugraha, Tue Jun  1 20:49:24 WIT 2004

$PID = "998";

if (strlen($_GET["id"]) > 0 && empty($_GET[sure])) {
    if ($_GET["id"] == "new") {
        title("Paket Baru");
            echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
        $f = new Form("actions/998.2.insert.php?configtype=".$_GET[configtype], "POST");
        $f->text("description","Nama Paket Laboratorium",40,100,"");
        $f->submit(" Simpan ");
        $f->execute();
    } else {
        $description = getFromTable("select description from rs99996 where id = '".$_GET["id"]."'");
        title("Edit Paket: $description");
        echo "<br>";
        $f = new Form("actions/998.2.update.php?configtype=".$_GET[configtype], "POST");
        $f->hidden("id", $_GET["id"]);
        $f->text("description","Nama Paket",40,100,$description);
        $f->submit(" Simpan ");
        $f->execute();

        echo "<div align=right><a href='$SC?p=$PID&configtype=".$_GET[configtype]."'>Kembali Ke Paket Laboratorium</a></div>";
        echo "<hr size=1 noshade>";
        echo "<b>\"$description\" terdiri dari layanan berikut:</b><br><br>";

        echo "<FORM ACTION='actions/998.2.insert.php?configtype=".$_GET[configtype]."' METHOD=POST>";
        echo "<INPUT TYPE=HIDDEN NAME=id VALUE='" . $_GET["id"] . "'>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "Layanan", "Jumlah", "Satuan", "Harga Satuan", "Harga Total", ""));
        $r1 = pg_query($con,
            "select rs99997.item_id, layanan, qty, harga, harga_atas, harga_bawah, tdesc, rs99997.id " .
            "from rs99997 " .
            "    join rs99996 on rs99996.id = rs99997.preset_id and rs99996.trans_type = 'LAB' ".
            "    left join rs00034 on rs00034.id = rs99997.item_id ".
            "    left join rs00001 on rs00034.satuan_id = rs00001.tc and tt = 'SAT' ".
            "where rs99997.preset_id = '".$_GET["id"]."'"
            );
        while ($d1 = pg_fetch_object($r1)) {
        $t->printRow(
            Array($d1->item_id, $d1->layanan, $d1->qty, $d1->tdesc,
                number_format($d1->harga,2), number_format($d1->harga * $d1->qty,2),
                "<A HREF='actions/998.2.delete.php?id=$d1->id&return=".$_GET["id"]."&configtype=".$_GET[configtype]."'>".icon("del-left")."</A>"),
            Array("CENTER", "LEFT", "RIGHT", "LEFT", "RIGHT", "RIGHT", "CENTER")
            );
        }
        pg_free_result($r1);
        
        
       	if (!isset($_SESSION["SELECT_LAYANAN"])) $_SESSION["SELECT_LAYANAN"] = 0;

	$r1 = pg_query($con,
            "select a.layanan, b.tdesc as jenis_jasa, c.tdesc as satuan, a.harga ".
            "from rs00034 a ".
            "    left join rs00001 b on b.tc = a.sumber_pendapatan_id and b.tt = 'SBP' ".
	    "    left join rs00001 c on c.tc = a.satuan_id and c.tt = 'SAT' ".
            "where id = '".$_SESSION["SELECT_LAYANAN"]."'"
            );

        $d = pg_fetch_object($r1);

        $t->printRow(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_LAYANAN"].
            "'>&nbsp;<A HREF='javascript:selectLayanan()'><IMG BORDER=0 SRC='images/icon-view.png'></A>",
            $d->layanan, "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1")."'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>",
            $d->satuan, $d->harga, "", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Tambah'>"),
            Array("CENTER", "LEFT", "CENTER", "LEFT", "RIGHT", "LEFT", "CENTER")
        );
        $t->printTableClose();
        echo "</FORM>";

        echo "\n<script language='JavaScript'>\n";
        echo "function selectLayanan() {\n";
        echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
    }
} else {


    if ($_GET[sure] == "false") {

    $data = getFromTable("select description from rs99996 where id=".$_GET[id]);

    echo "<div align=center>";
    echo "<form action='actions/998.2.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Paket <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=id value=".$_GET[id].">";
    echo "<input type=hidden name=configtype value=2>";
    echo "<input type=submit name=sure value='::YA::'>";
    echo "<input type=submit name=sure value='::TIDAK::'>";
    echo "</form>";
    echo "</div>";

    
    
    } else {




    // search
    echo "<div align=right>";
    echo "<form action='index2.php' method='get' NAME=Form2>";
    //echo "<font class=SUB_MENU>PAKET LAYANAN:</font> <input type=text name=search>&nbsp;";
    echo "<input type=hidden name='p' value='$PID'>";
    echo "<input type=hidden name='configtype' value='".$_GET[configtype]."'>";
   // echo "<input type=submit value=' CARI '>";
   
    echo "<font class=SUB_MENU>Pencarian:</font> <input type=text name=search>&nbsp;";
    echo " <input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> ";
   
    
    echo "</form>";
    echo "</DIV>";


    $t = new PgTable($con, "100%");
    $t->SQL = "select description, id ".
              "from rs99996 ".
              "where trans_type = 'LAB' ".
	      "and upper(description) like '%".strtoupper($_GET[search])."%'";
    $t->ColHeader = array("NAMA PAKET", "&nbsp;");
    $t->ShowRowNumber = true;
    $t->ColAlign[1] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[1] = "<nobr>".
                           "<A CLASS=TBL_HREF HREF='$SC?p=$PID&configtype=".$_GET[configtype]."&id=<#1#>'>".icon("edit","Edit")."</A> &nbsp; ".
                           "<A CLASS=TBL_HREF HREF='actions/998.2.delete.php?configtype=".$_GET[configtype]."&id=<#1#>'>".icon("delete","Hapus")."</A>".
                           "</nobr>";
    $t->execute();
    echo "<div align=LEFT><img src='icon/group.gif' align='absmiddle'> <a href='$SC?p=$PID&configtype=".$_GET[configtype]."&id=new'>Tambah Paket Laboratorium</a></div>";

    }

}

?>

