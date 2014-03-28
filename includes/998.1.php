<?php // Nugraha, Tue Jun  1 20:49:24 WIT 2004
	  // Agung SUnandar 12:42 05/06/2012

$PID = "998";

if (strlen($_GET["id"]) > 0 && empty($_GET[sure])) {
    if ($_GET["id"] == "new") {
        title("Paket Baru");
            echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
 
        $f = new Form("actions/998.1.insert.php?configtype=1", "POST");
        $f->text("description","Nama Paket",40,100,"");
		$f->text("harga","Harga Paket",20,40,"");
        $f->submit(" Simpan ");
        $f->execute();
    } else {
        $description = getFromTable("select description from rs99996 where id = '".$_GET["id"]."'");
        $harga = getFromTable("select harga_paket from rs99996 where id = '".$_GET["id"]."'");        
        title("Edit Paket: $description");
        echo "<br>";
        $f = new Form("actions/998.1.update.php?configtype=1", "POST");
        $f->hidden("id", $_GET["id"]);
        $f->text("description","Nama Paket",40,100,$description);
        $f->text("harga","Harga Paket",20,40,$harga);
        
        $f->submit(" Simpan ");
        $f->execute();

        echo "<div align=right><a href='$SC?p=$PID&configtype=1'>Kembali Ke Paket Transaksi Layanan</a></div>";
        echo "<hr size=1 noshade>";
        echo "<b>\"$description\" terdiri dari layanan berikut:</b><br><br>";

        echo "<FORM ACTION='actions/998.1.insert.php?configtype=1' METHOD=POST>";
        echo "<INPUT TYPE=HIDDEN NAME=id VALUE='" . $_GET["id"] . "'>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "Layanan", "Tipe Pasien", "Jumlah", "Satuan", "Harga Satuan", "Harga Total", ""));
        $r1 = pg_query($con,
            " select rs99997.item_id, layanan,b.tdesc as ket_tipe_pasien, qty, harga, harga_atas, harga_bawah,  rs00001.tdesc, rs99997.id ".
            " from rs99997                                                                                             ".
            " join rs99996 on rs99996.id = rs99997.preset_id and rs99997.trans_type = 'LYN'                            ".
            " left join rs00034 on rs00034.id = rs99997.item_id                                                        ".
            " left join rs00001 on rs00034.satuan_id = rs00001.tc and tt = 'SAT'                                       ".
            " left join rs00001 b on rs99997.tipe_pasien = b.tc and b.tt = 'JEP'                                       ".
            "where rs99997.preset_id = '".$_GET["id"]."'"
            );
        while ($d1 = pg_fetch_object($r1)) {
        $t->printRow2(
            Array($d1->item_id, $d1->layanan,$d1->ket_tipe_pasien, $d1->qty, $d1->tdesc,
                number_format($d1->harga,2), number_format($d1->harga * $d1->qty,2),
                "<A HREF='actions/998.1.delete.php?configtype=1&id=$d1->id&return=".$_GET["id"]."'>".icon("del-left")."</A>"),
            Array("CENTER", "LEFT","CENTER", "RIGHT", "LEFT", "RIGHT", "RIGHT", "CENTER")
            );
        }
        pg_free_result($r1);
 //---///       
        $x_tipe_pasien = " <SELECT NAME='tipe_pasien'>\n";
        $xr = pg_query($con, "select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' order by tc asc ");
        while($xd = pg_fetch_array($xr)) {
            $x_tipe_pasien  .= "<OPTION VALUE='$xd[0]'>$xd[1]</OPTION>\n";
        }
        pg_free_result($xr);
        $x_tipe_pasien .= "</SELECT></TD>\n";


        if (isset($_SESSION["SELECT_LAYANAN"])) {
            $xr = pg_query($con,"select layanan from rsv0034 where id = '" . $_SESSION["SELECT_LAYANAN"] . "'");
            $xd = pg_fetch_object($xr);
            pg_free_result($xr);

        }
                
//---///
        
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_LAYANAN"]."'>&nbsp;<A HREF='javascript:selectLayanan()'><IMG BORDER=0 SRC='images/icon-view.png'></A>", $xd->layanan,
            
                      $x_tipe_pasien
            , "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1")."'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", $d->satuan, $xd->harga, "", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Tambah'>"),
            Array("CENTER", "LEFT", "CENTER", "LEFT", "RIGHT", "LEFT", "CENTER")
        );
        $t->printTableClose();
        echo "</FORM>";
		
		
		//OBAT Agung Sunandar 13:06 05/06/2012
		echo "<hr size=1 noshade>";
        echo "<b>\"$description\" terdiri dari obat berikut:</b><br><br>";

        echo "<FORM ACTION='actions/998.1.insert.php?configtype=1' METHOD=POST>";
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
        $t->printRow2(
            Array($d1->item_id, $d1->obat,$d1->satuan, $d1->qty, 
                number_format($d1->harga,2), number_format($d1->harga * $d1->qty,2),
                "<A HREF='actions/998.1.delete.php?configtype=1&id=$d1->id&return=".$_GET["id"]."'>".icon("del-left")."</A>"),
            Array("CENTER", "LEFT","LEFT", "CENTER", "RIGHT", "RIGHT", "CENTER")
            );
        }
        pg_free_result($r1);

        if (isset($_SESSION["SELECT_OBAT"])) {
            $xr = pg_query($con,"select * from rsv0004 where id = '" . $_SESSION["SELECT_OBAT"] . "'");
            $xd = pg_fetch_object($xr);
            pg_free_result($xr);

        }
   
        
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=obat STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_OBAT"]."'>&nbsp;<A HREF='javascript:selectObat()'><IMG BORDER=0 SRC='images/icon-view.png'></A>", $xd->obat,
            $xd->satuan
            , "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1")."'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>",  $xd->harga, "", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Tambah'>"),
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
		
		echo "\n<script language='JavaScript'>\n";
        echo "function selectObat() {\n";
        echo "    sWin = window.open('popup/obat.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
    }
} else {

    if ($_GET[sure] == "false") {

    $data = getFromTable("select description from rs99996 where id=".$_GET[id]);

    echo "<div align=center>";
    echo "<form action='actions/998.1.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Paket <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=id value=".$_GET[id].">";
    echo "<input type=hidden name=configtype value=1>";
    echo "<input type=submit name=sure value='::YA::'>";
    echo "<input type=submit name=sure value='::TIDAK::'>";
    echo "</form>";
    echo "</div>";

    
    
    } else {



    // search
    echo "<div align=right>";
    echo "<form action='index2.php' method='get' NAME=Form2>";
  //  echo "<font class=SUB_MENU>PAKET LAYANAN:</font> <input type=text name=search>&nbsp;";
    echo "<input type=hidden name='p' value='$PID'>";
    echo "<input type=hidden name='configtype' value='".$_GET[configtype]."'>";
 //   echo "<input type=submit value=' CARI '>";
    
    
    echo "<font class=SUB_MENU>Pencarian:</font> <input type=text name=search>&nbsp;";
    echo " <input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> ";
    
    echo "</form>";
    echo "</DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select description, 'Rp. '||to_char(harga_paket,'999,999,999.99') as harga, id ".
              "from rs99996 ".
              "where trans_type = 'LYN' ".
	      "and upper(description) like '%".strtoupper($_GET[search])."%'";
    $t->ColHeader = array("NAMA PAKET","HARGA PAKET", "&nbsp;");
    $t->ShowRowNumber = true;
	$t->ColAlign[1] = "RIGHT";
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[2] = "<nobr>".
                           "<A CLASS=TBL_HREF HREF='$SC?p=$PID&configtype=1&id=<#2#>&e=edit'>".icon("edit","Edit")."</A> &nbsp; ".
                           "<A CLASS=TBL_HREF HREF='actions/998.1.delete.php?configtype=1&id=<#2#>'>".icon("delete","Hapus")."</A>".
                           "</nobr>";
    $t->execute();

    echo "<br/><div align=LEFT> <img src='icon/group.gif' align='absmiddle'> <a href='$SC?p=$PID&configtype=".$_GET[configtype]."&id=new'>Tambah Paket Layanan</a><br/><br/></div>";
    }

}

?>

