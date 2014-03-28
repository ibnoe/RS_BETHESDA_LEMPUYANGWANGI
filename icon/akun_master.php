<?php // Nugraha, Thu Apr 29 17:10:51 WIT 2004
      // sfdn, 14-05-2004

$PID = "akun_master";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

function getLevel($hcode)
{
    if (strlen($hcode) != 15) return 0;
    if (substr($hcode,  4, 12) == str_repeat("0", 12)) return 1;
    if (substr($hcode,  7,  9) == str_repeat("0",  9)) return 2;
    if (substr($hcode, 10,  6) == str_repeat("0",  6)) return 3;
    if (substr($hcode, 13,  3) == str_repeat("0",  3)) return 4;
    return 5;
}

title("<img src='icon/akuntansi-masterakun.png' align='absmiddle' >  Data Master Akun");
echo "<br>";

if ($_GET["action"] == "new") {
	    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";

    $f = new Form("actions/akun_master.insert.php", "POST");
    $f->PgConn = $con;
    $f->hidden("parent", $_GET["parent"]);
    $f->hidden("f_is_group", $_GET["grp"]);
    if ($_GET["grp"] == "Y") {
		$f->hidden("f_kode","Kode Akun",50,255,$_GET["kode"]);
        $f->text("f_nama","Nama Group Akun",50,255,$_GET["nama"]);
    } else {
		$f->hidden("f_kode","Kode Akun",50,255,$_GET["kode"]);
        $f->text("f_nama","Nama Akun",50,255,$_GET["nama"]);
    }
    $f->submit(" Simpan ");
    $f->execute();
} elseif ($_GET["action"] == "edit") {
	    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";

    $r = @pg_query($con, "select * from akun_master where id = ".$_GET[e]." ");
    $n = @pg_num_rows($r);
    if($n > 0)	$d = pg_fetch_object($r);
    @pg_free_result($r);

    $f = new Form("actions/akun_master.update.php", "POST");
    $f->PgConn = $con;
    $f->hidden("id", $_GET["e"]);
    $f->hidden("parent", $_GET["parent"]);
    $f->hidden("sort", $_GET["sort"]);
    $f->hidden("order", $_GET["order"]);
    $f->hidden("tblstart", $_GET["tblstart"]);


    if ($_GET["grp"] == "Y") {
		$f->hidden("f_kode","Kode Akun",50,255,$d->kode);
        $f->text("f_nama","Nama Group Akun",50,255,$d->nama);
    } else {
		$f->hidden("f_kode","Kode Akun",50,255,$d->kode);
        $f->text("f_nama","Nama Akun",50,255,$d->nama);
    }
    $f->submit(" Simpan ");
    $f->execute();
} else {


if (empty($_GET[sure])) {

    $ext = "OnChange = 'Form1.submit();'";
    $level = 0;
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->selectSQL("L1", "Grup Akun",
        "select '' as hierarchy, '' as nama union " .
        "select hierarchy, nama ".
        "from akun_master ".
        "where substr(hierarchy,4,12) = '000000000000' ".
        "and is_group = 'Y' ".
        "order by nama", $_GET["L1"],
        $ext);
    if (strlen($_GET["L1"]) > 0) $level = 1;
    if (getFromTable(
            "select hierarchy, nama ".
            "from akun_master ".
            "where substr(hierarchy,7,9) = '000000000' ".
            "and substr(hierarchy,1,3) = '".substr($_GET["L1"],0,3)."' ".
            "and hierarchy != '".$_GET["L1"]."' ".
            "and is_group = 'Y'")
        && strlen($_GET["L1"]) > 0) {
        $f->selectSQL("L2", "Sub Grup akun",
            "select '' as hierarchy, '' as nama union " .
            "select hierarchy, nama ".
            "from akun_master ".
            "where substr(hierarchy,7,9) = '000000000' ".
            "and substr(hierarchy,1,3) = '".substr($_GET["L1"],0,3)."' ".
            "and hierarchy != '".$_GET["L1"]."' ".
            "and is_group = 'Y' ".
            "order by nama", $_GET["L2"],
            $ext);
        if (strlen($_GET["L2"]) > 0) $level = 2;
        if (getFromTable(
                "select hierarchy, nama ".
                "from akun_master ".
                "where substr(hierarchy,10,6) = '000000' ".
                "and substr(hierarchy,1,6) = '".substr($_GET["L2"],0,6)."' ".
                "and hierarchy != '".$_GET["L2"]."' ".
                "and is_group = 'Y'")
            && strlen($_GET["L1"]) > 0
            && strlen($_GET["L2"]) > 0) {
	            $f->selectSQL("L3", "Kategori ",
	                "select '' as hierarchy, '' as nama union " .
	                "select hierarchy, nama ".
	                "from akun_master ".
	                "where substr(hierarchy,10,6) = '000000' ".
	                "and substr(hierarchy,1,6) = '".substr($_GET["L2"],0,6)."' ".
	                "and hierarchy != '".$_GET["L2"]."' ".
	                "and is_group = 'Y' ".
	                "order by nama", $_GET["L3"],
	                $ext);
	            if (strlen($_GET["L3"]) > 0) $level = 3;
	            if (getFromTable(
	                    "select hierarchy, nama ".
	                    "from akun_master ".
	                    "where substr(hierarchy,13,3) = '000' ".
	                    "and substr(hierarchy,1,9) = '".substr($_GET["L3"],0,9)."' ".
	                    "and hierarchy != '".$_GET["L3"]."' ".
	                    "and is_group = 'Y'")
	                && strlen($_GET["L1"]) > 0
	                && strlen($_GET["L2"]) > 0
	                && strlen($_GET["L3"]) > 0) {
	                $f->selectSQL("L4", "Sub Kategori ",
	                    "select '' as hierarchy, '' as nama union " .
	                    "select hierarchy, nama ".
	                    "from akun_master ".
	                    "where substr(hierarchy,13,3) = '000' ".
	                    "and substr(hierarchy,1,9) = '".substr($_GET["L3"],0,9)."' ".
	                    "and hierarchy != '".$_GET["L3"]."' ".
	                    "and is_group = 'Y' ".
	                    "order by nama", $_GET["L4"],
	                    $ext);
	                    if (strlen($_GET["L4"]) > 0) $level = 4;                    
 	                    
	                    
	                    
	            } // End Level 3
	            
	            
	            
        } // End Level 2 
        
    }
    $f->execute();
    

    $SQL1 = "select a.kode, a.nama, a.id ".
            "from akun_master as a ".
            "where substr(a.hierarchy,1,".($level*3).") = '".substr($_GET["L$level"],0,($level*3))."' ".
            "and a.hierarchy <> '".$_GET["L$level"]."' ".
            "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
            str_repeat("0",15-(($level*3)+3))."' ";
    $SQL1Counter =
            "select count(*) ".
            "from akun_master as a ".
            "where substr(a.hierarchy,1,".($level*3).") = '".substr($_GET["L$level"],0,($level*3))."' ".
            "and a.hierarchy <> '".$_GET["L$level"]."' ".
            "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
            str_repeat("0",15-(($level*3)+3))."'";
    $SQL2 = "select a.kode, a.nama, a.id ".
            "from akun_master as a ".
            "where substr(a.hierarchy,1,".($level*3).") = '".substr($_GET["L$level"],0,($level*3))."' ".
            "and a.hierarchy <> '".$_GET["L$level"]."' ".
            "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
            str_repeat("0",15-(($level*3)+3))."' ";

        if ($_GET["search"]) {
		$SQL1 .=
			" and  (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%') ";
		$SQL2 .=
			" and  (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%') ";


	}


    $SQL2Counter =
            "select count(*) ".
            "from akun_master as a ".
            "where substr(a.hierarchy,1,".($level*3).") = '".substr($_GET["L$level"],0,($level*3))."' ".
            "and a.hierarchy <> '".$_GET["L$level"]."' ".
            "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
            str_repeat("0",15-(($level*3)+3))."'";
    $SQL3 = "select is_group ".
            "from akun_master ".
            "where substr(hierarchy,1,".($level*3).") = '".substr($_GET["L$level"],0,($level*3))."' ".
            "and hierarchy <> '".$_GET["L$level"]."' ".
            "and substr(hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
            str_repeat("0",15-(($level*3)+3))."'";

    $isGroup = getFromTable($SQL3);

    if ($level == 4){$isGroup = "N" ;}

     
    echo "<div align=RIGHT>";
    
    echo $spacer;
//    if ($isGroup != "N")

    echo "<form action='index2.php' method='get' NAME=Form2>";
//    echo "<font class=SUB_MENU>NAMA:</font> <input type=text name=search>&nbsp;";
     echo "<font class=SUB_MENU>Pencarian:</font> <input type=text name=search>&nbsp;";
    echo "<input type=hidden name='p' value='$PID'>";
    echo "<input type=hidden name='L1' value='".$_GET[L1]."'>";
    echo "<input type=hidden name='L2' value='".$_GET[L2]."'>";
    echo "<input type=hidden name='L3' value='".$_GET[L3]."'>";
    echo "<input type=hidden name='L4' value='".$_GET[L4]."'>";
    echo "<input type=hidden name='L5' value='".$_GET[L5]."'>";
 //   echo "<input type=submit value=' CARI '>";
    echo " <input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> ";
    
    echo "</form>";
    echo "</DIV>";
    if ($isGroup == "Y") {
        $t = new PgTable($con, "100%");
        $t->SQL = $SQL2;
        $t->SQLCounter = $SQL2Counter;
        $t->setlocale("id_ID");
        $t->ShowRowNumber = true;
        $t->RowsPerPage = 20;
        $t->ColFormatHtml[2] =
            "<A CLASS=TBL_HREF HREF='".
            "$SC?p=$PID&action=edit&parent=".$_GET["L$level"]."&grp=Y&e=<#2#>".
            "'>".icon("edit","Edit")."</A> <A CLASS=TBL_HREF HREF='".
            "actions/akun_master.delete.php?grp=Y&e=<#2#>".
            "'>".icon("delete","Hapus")."</A>";
        $t->ColHeader = Array("KODE","GRUP AKUN", "&nbsp;");
        $t->ColAlign[2] = "CENTER";
        //$t->ShowSQLExecTime = true;
        //$t->ShowSQL = true;
        
        if (!isset($_GET[sort])) {

           $_GET[sort] = "id";
           $_GET[order] = "asc";
	}
        $t->execute();
        echo "<img src='icon/group.gif' align='absmiddle'> <A HREF='$SC?p=$PID&action=new&parent=".$_GET["L$level"]."&grp=Y'>Tambah Group Akun 1</A><br>";    

    }
    
    if ($isGroup == "N") {
        $t = new PgTable($con, "100%");
        $t->SQL = $SQL1;
        $t->SQLCounter = $SQL1Counter;
        $t->setlocale("id_ID");
        $t->ShowRowNumber = true;
        $t->RowsPerPage = 20;
        /*
        $t->ColFormatMoney[6] = "%!+#2n";
        $t->ColFormatMoney[7] = "%!+#2n";
        $t->ColFormatMoney[8] = "%!+#2n";
        */
        
        //$del_link = "<A CLASS=TBL_HREF HREF='actions/akun_master.delete.php?grp=Y&sort=".$_GET[sort]."&order=".$_GET[order]."&e=<#10#>";
        if (isset($_GET[L1])) {
           $del_link = "<A CLASS=TBL_HREF HREF='actions/akun_master.delete.php?grp=N&sort=".$_GET[sort]."&order=".$_GET[order]."&e=<#2#>&L1=".$_GET[L1]."&tblstart=".$_GET[tblstart]."'>".icon("delete","Hapus")." ";
        }
        if (isset($_GET[L2])) {
           $del_link = "<A CLASS=TBL_HREF HREF='actions/akun_master.delete.php?grp=N&sort=".$_GET[sort]."&order=".$_GET[order]."&e=<#2#>&L1=".$_GET[L1]."&L2=".$_GET[L2]."&tblstart=".$_GET[tblstart]."'>".icon("delete","Hapus")." ";
        }
        if (isset($_GET[L3])) {
           $del_link = "<A CLASS=TBL_HREF HREF='actions/akun_master.delete.php?grp=N&sort=".$_GET[sort]."&order=".$_GET[order]."&e=<#2#>&L1=".$_GET[L1]."&L2=".$_GET[L2]."&L3=".$_GET[L3]."&tblstart=".$_GET[tblstart]."'>".icon("delete","Hapus")." ";
        }
        if (isset($_GET[L4])) {
           $del_link = "<A CLASS=TBL_HREF HREF='actions/akun_master.delete.php?grp=N&sort=".$_GET[sort]."&order=".$_GET[order]."&e=<#2#>&L1=".$_GET[L1]."&L2=".$_GET[L2]."&L3=".$_GET[L3]."&L4=".$_GET[L4]."&tblstart=".$_GET[tblstart]."'>".icon("delete","Hapus")." ";
        }
        if (isset($_GET[L5])) {
           $del_link = "<A CLASS=TBL_HREF HREF='actions/akun_master.delete.php?grp=N&sort=".$_GET[sort]."&order=".$_GET[order]."&e=<#2#>&L1=".$_GET[L1]."&L2=".$_GET[L2]."&L3=".$_GET[L3]."&L4=".$_GET[L4]."&L5=".$_GET[L5]."&tblstart=".$_GET[tblstart]."'>".icon("delete","Hapus")." ";
        }
        $del_link .= "</a>";
        $t->ColFormatHtml[2] =
            "<A CLASS=TBL_HREF HREF='".
            "$SC?p=$PID&action=edit&parent=".$_GET["L$level"]."&grp=N&e=<#2#>&sort=".$_GET[sort]."&order=".$_GET[order]."&tblstart=".$_GET[tblstart].
            "'>".icon("edit","Edit")."</A> $del_link";
		$t->ColAlign[2] = "CENTER";
        $t->ColHeader = Array("KODE", "NAMA",  "Edit<br><img src='images/spacer.gif' width=50 height=1 border=0>");
        //$t->ShowSQLExecTime = true;
        //$t->ShowSQL = true;
        if (!isset($_GET[sort])) {

           $_GET[sort] = "id";
           $_GET[order] = "desc";
	}

        $t->execute();
    }
 
   If (!$isGroup){ echo "<img src='icon/group.gif' align='absmiddle'> <A HREF='$SC?p=$PID&action=new&parent=".$_GET["L$level"]."&grp=Y'>Tambah Group Akun [2]</A><br>";    }
   if ($isGroup != "Y")
        echo "<img src='icon/file_edit.gif' align='absmiddle'> <A HREF='$SC?p=$PID&action=new&parent=".$_GET["L$level"]."&grp=N'>Tambah Akun </A>" ;

} else {

    $data = getFromTable("select nama from akun_master where id=".$_GET[e]);

    if ($grp == "Y") {

    echo "<div align=center>";
    echo "<form action='actions/akun_master.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Group Master <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=grp value=Y>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    echo "<input type=submit name=sure value='::YA::'>&nbsp;";
    echo "<input type=submit name=sure value='::TIDAK::'>";
    echo "</form>";
    echo "</div>";

    } else {

    echo "<div align=center>";
    echo "<form action='actions/akun_master.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Master Akun <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=grp value=N>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    echo "<input type=hidden name=sort value=".$_GET[sort].">";
    echo "<input type=hidden name=order value=".$_GET[order].">";
    echo "<input type=hidden name=tblstart value=".$_GET[tblstart].">";
    echo "<input type=hidden name=L1 value=".$_GET[L1].">";
    echo "<input type=hidden name=L2 value=".$_GET[L2].">";
    echo "<input type=hidden name=L3 value=".$_GET[L3].">";
    echo "<input type=hidden name=L4 value=".$_GET[L4].">";
    echo "<input type=hidden name=L5 value=".$_GET[L5].">";
    echo "<input type=submit name=sure value='::YA::'>&nbsp;";
    echo "<input type=submit name=sure value='::TIDAK::'>";
    echo "</form>";
    echo "</div>";


    }

}

}

?>
