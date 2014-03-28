<? // tokit, 2004 09 08

//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root"|| $_SESSION[uid] == "laborat" || $_SESSION[uid] == "radiologi") {

$PID = "150";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (strlen($_GET["registered"]) == 0) $_GET["registered"] = "Y";
title("<img src='icon/daftar-2.gif' align='absmiddle' > HAPUS REGISTRASI PASIEN");

echo "<br>";

$f = new Form($SC, "GET", "NAME=Form2");
$f->hidden("p",$PID);
$f->hidden("registered", "Y");
$f->hidden("q","search");
if (empty($_GET[e])) { 
$f->text("search","Pencarian <font color='red'>(NO.REG)",40,40,$_GET["search"]);
$f->submit(" Cari ");
}
$f->execute();

if ($_GET["e"]) {
	//get status bayar
	$isBayar = getFromTable("select is_bayar from rs00008 where no_reg = '".$_GET["e"]."'");
	
    if (empty($_GET[sure])) {
    	
		if ($isBayar == 'N') {
		    echo "<div align=center>";
		    echo "<form action='index2.php' method='get'>";
		    echo "<font color=red size=3>PERINGATAN !</font><br>";
		    echo "<font class=SUB_MENU>Data Registrasi Pasien <font color=navy>'".$_GET[e]."'</font> akan Dihapus.</font><br><br>";
		    echo "<input type=hidden name=p value=$PID>";
		    echo "<input type=hidden name=e value=".$_GET[e].">";
		    echo "<input type=submit name=sure value='::YA::'>";
		    echo "<input type=submit name=sure value='::TIDAK::'>";
		    echo "</form>";
		    echo "</div>";
		} else {
			echo "<div align=center>";
			echo "<font color=red size=3>PERINGATAN !</font><br>";
	 	   	echo "<font class=SUB_MENU>Data Registrasi Pasien <font color=navy>'".$_GET[e]."'</font> tidak bisa Dihapus,</font><br>";
			echo "<font class=SUB_MENU>Karena telah melakukan pembayaran.</font><br><br>";
			echo "<A HREF='index2.php?p=$PID&registered=Y&q=search&search=00'>".icon("back","Kembali")."</a><br>";
			echo "<font color=green size=2>Kembali !</font>";
			echo "</div>";
		}
	
    } elseif ($_GET[sure] == "::YA::") {
		
		$tagihan=getFromTable("select sum(tagihan) from rs00008 where no_reg='".$_GET[e]."'");
		
		if ($tagihan!=0){
		echo"<br>";
		echo"<br>";
		echo"<center> <table>";
		echo"<tr>";
		echo"<td>";
		echo  "<CENTER> <FONT SIZE='4' color='red'> Ada tagihan pada pasien dengan No. Registrasi ".$_GET[e]." memiliki tagihan.
		<br>Silahkan hapus Tagihan / Layanan atau hubungi perawat untuk di konfirmasi! </FONT></CENTER>";
		echo"<td>";echo"<tr>";
		echo"</table> </center>";
		echo"<br>";
		echo"<br>";
		
		echo "<script language=javascript>\n";
	    echo "<!--\n";
	    echo "window.location=\"index2.php?p=$PID;\n";
	    echo "-->\n";
	    echo "</script>\n";
		
		}else{
	    $reg = $_GET[e];
	    pg_query("delete from rs00005 where reg    = '$reg'");
	    pg_query("delete from rs00006 where id     = '$reg'");
	    pg_query("delete from rs00008 where no_reg = '$reg'");    
	    pg_query("delete from rs00010 where no_reg = '$reg'");
	    
	    //========= Wildan ST 03/04/2013 hystory user
	    pg_query("insert into history_user " .
		            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
		            "values".
		            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Hapus Registrasi Pasien', ".
		            "'Front Office -> Hapus Registrasi Pasien','Data Registrasi Pasien dengan No.Reg $reg telah Dihapus', ".
		            "'".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')");
		//=========
	    
	    echo "<script language=javascript>\n";
	    echo "<!--\n";
	    echo "window.location=\"index2.php?p=$PID&registered=Y&q=search&search=00\";\n";
	    echo "-->\n";
	    echo "</script>\n";
		}
    
    } else {

	    echo "<script language=javascript>\n";
	    echo "<!--\n";
	    echo "window.location=\"index2.php?p=$PID&registered=Y&q=search&search=00\";\n";
	    echo "-->\n";
	    echo "</script>\n";    
    
    }

} else {
    
    if ($_GET["registered"] == "Y" && $_GET["q"] == "search" && strlen($_GET["search"]) > 0) {
    
	$SQLSTR = "select a.mr_no, b.id, upper(a.nama)as nama,a.jenis_kelamin,a.umur,a.kesatuan, c.tdesc as poli, ".
			  "d.tdesc, b.id as href FROM  rs00006 b ".
              "left join rs00002 a on a.mr_no = b.mr_no  ".
			  "left join rs00001 c on b.poli = c.tc_poli  and c.tt='LYN' ".
			  "left join rs00001 d on b.tipe = d.tc AND d.tt = 'JEP' ".
              "where upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "OR b.id LIKE '%".$_GET["search"]."%' ".

              "OR a.mr_no LIKE '%".$_GET["search"]."%' ".
              "OR upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' " ;   

	// tambah urutan
    if (!isset($_GET[sort])) {
           $_GET[sort] = "mr_no";
           $_GET[order] = "asc";
	}   
	
	if ($_SESSION[uid] == "igd") {
	    $SQLWHERE = "AND (c.tc_poli = 100 OR c.tc_poli=0)";		//$SQLWHERE = "AND (b.poli = 10 OR b.poli=0)";
	//} elseif ($_SESSION[uid] == "daftar") {
	//    $SQLWHERE = "AND c.tc<>100 AND c.tc<>0";		//$SQLWHERE = "AND b.poli<>10 AND b.poli<>0";
	} elseif ($_SESSION[uid] == "laborat") {
		$SQLWHERE = "AND c.tc=203";
	} elseif ($_SESSION[uid] == "radiologi") {
		$SQLWHERE = "AND c.tc=204";
	}

 // echo $SQLSTR.$SQLWHERE ; 

        $t = new PgTable($con, "100%");
        $t->SQL = "$SQLSTR $SQLWHERE";
        
        $t->ColHeader = array("NO.MR","NO.REG","NAMA","JNS KELAMIN","UMUR (Tahun)","PEKERJAAN","RAWATAN","TIPE PASIEN","HAPUS");
        $t->ShowRowNumber = true;
        $t->ColAlign[0] = "CENTER";
		$t->ColAlign[1] = "CENTER";
                $t->ColAlign[3] = "CENTER";
                $t->ColAlign[4] = "CENTER";
		$t->ColAlign[8] = "CENTER";	
        $t->RowsPerPage = 25;
        //$t->DisableStatusBar = true;
        $t->ColFormatHtml[8] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#8#>'>".icon("delete","Hapus")."</A>";
        $t->execute();
    }
}


//} // end of $_SESSION[uid] == daftar || igd
?>
