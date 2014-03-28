 <?php
// Agung Sunandar
	
/* By YGR  */
$jns_kasir = array(
	"rj"=>"RAWAT JALAN", 
	"ri"=>"RAWAT INAP",
	"igd"=>"IGD",
) ;
$kasirnya = $_GET["kas"] ;
// if ($_SESSION[uid] == "kasir2" || $_SESSION[uid] == "igd"|| $_SESSION[uid] == "kasir1"|| $_SESSION[uid] == "root") {



/*End of By YGR */

$PID = "888";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");


echo "</br>";
echo "<table width='100%'>";
echo "<tr><td>";
title("<img src='icon/kasir-2.gif' align='absmiddle' > <A CLASS=SUB_MENU ".
         "HREF='index2.php?p=335&t1=&kas=".$_GET["kas"]."'><font color='black'>KASIR ".$jns_kasir[$kasirnya]."</font></A>");
echo "</td></tr></table>";
echo "</td><td>";
title("<img src='icon/rawat-jalan-2_asli.gif' align='absmiddle' > INPUT DEPOSIT ".$jns_kasir[$kasirnya]);
echo "</td></tr></table>";

echo "</br>";

if ($_GET[rg] && $kasirnya == 'ri'){ //FORM RI

	$sub = isset($_GET["sub"]) ? $_GET["sub"] : "4";
    if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");

} else if ($_GET[rg] && ($kasirnya == 'rj' || $kasirnya == 'igd')){ //FORM RJ

	$sub = isset($_GET["sub"]) ? $_GET["sub"] : "5";
    if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");

} else if ($kasirnya == 'ri') { //LIST RI

	echo "<div align=right>";
				$f = new Form($SC, "GET","NAME=Form2");
				$f->hidden("p", $PID);
				$f->hidden("kas", $_GET["kas"]);
			if (!$GLOBALS['print']){
				$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
			}else { 
				$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
			}
			$f->execute();
			if ($msg) errmsg("Error:", $msg);
			echo "</div><br>";
	$SQLSTR = "select b.mr_no, a.no_reg, upper(b.nama)as nama, ".
			  "    to_char(a.ts_check_in,'DD MON YYYY HH24:MI:SS') as tgl_masuk,g.tdesc, ".
		  "    b.alm_tetap,f.bangsal || ' / ' || e.bangsal|| ' / ' || d.bangsal as bangsal, 
					case when c.status = 'P' then 'Sudah Keluar' else 'Masih Dirawat' end as status,
				to_char((select sum(z.jumlah) from rs00044 z where z.no_reg = a.no_reg),'999,999,999.99') as deposit,a.no_reg,a.no_reg ".
			  "from rs00010 as a 
			   ".
			  "    join rs00006 as c on a.no_reg = c.id ".
			  "    join rs00002 as b on c.mr_no = b.mr_no ".
			  "    join rs00012 as d on a.bangsal_id = d.id ".
			  "    join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
			  "    join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' ".
		   "	 left join rs00001 g on g.tc = b.tipe_pasien and g.tt='JEP' ".
			  "where a.ts_calc_stop is null 
				"; 


	if ($_GET[search]) {
		$SQLSTR .= " and (upper(b.nama) like '%".strtoupper($_GET[search])."%' or a.no_reg like '%".$_GET[search]."%' or b.mr_no like '%".$_GET[search]."%') ";

	}

	$t = new PgTable($con, "100%");
	$t->SQL = "$SQLSTR group by c.status,b.mr_no, a.no_reg, b.nama,a.ts_check_in,g.tdesc,b.alm_tetap,f.bangsal,e.bangsal,d.bangsal ";

	if (!isset($_GET[order])) {

			   $_GET[order] = "a.ts_check_in";
			   $_GET[order] = "asc";
	}

	if (!isset($_GET[sort])) {

			   $_GET[sort] = "a.no_reg";
			   $_GET[order] = "desc";
	}

	$t->ColHeader = array("NO MR", "NO REG", "NAMA", "TGL/JAM MASUK","TIPE PASIEN", "ALAMAT",
						  "RUANGAN",  "STATUS","DEPOSIT","EDIT","PRINT<br>KWITANSI");
	$t->ShowRowNumber = true;
	$t->ColAlign[0] = "CENTER";
	$t->ColAlign[1] = "CENTER";
	$t->ColAlign[7] = "CENTER";
	$t->ColAlign[8] = "right";
	$t->ColAlign[3] = "CENTER";
	$t->ColColor[7] = "color";
	$t->ColAlign[9] = "CENTER";
	$t->ColAlign[10] = "CENTER";
	$t->RowsPerPage = $ROWS_PER_PAGE;

	$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=888&sub=4&rg=<#1#>&mr=<#0#>&kas=$kasirnya&rg1=<#1#>'><#2#></A>";
	$t->ColFormatHtml[9] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&sub=4&e=<#9#>&rg=<#1#>&mr=<#0#>&kas=$kasirnya'>".icon("edit","Edit")."</A>";

	//$t->ColFormatHtml[10] = "<a href='javascript: cetakrinciansementara(<#1#>)' ><img src='images/cetak.gif' border='0'></a>";
	$t->ColFormatHtml[10] = "<nobr><A CLASS=TBL_HREF HREF='includes/cetak.888_ri.php?rg=<#1#>'>".icon("ok","Cetak")."</A></nobr>";
	$t->execute();


	//echo "\n<script language='JavaScript'>\n";
	//echo "function cetakrinciansementara(tag) {\n";
	//echo "    sWin = window.open('includes/cetak.sementara1.php?rg=' + tag+'&kas=".$_GET["kas"]."', 'xWin',".
		 //" 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');\n";
	//echo "    sWin.focus();\n";
	//echo "}\n";
	//echo "</script>\n";
	
} else if ($kasirnya == 'rj' || $kasirnya == 'igd') { //LIST RJ
    //-----------------------------------------------------------------------------------------------------------------------------
	echo "<div align=right>";
		$f = new Form($SC, "GET","NAME=Form2");
		$f->hidden("p", $PID);
		$f->hidden("kas", $_GET["kas"]);
	if (!$GLOBALS['print']){
		$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
	}else { 
		$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
	}
	$f->execute();
	if ($msg) errmsg("Error:", $msg);
	echo "</div><br>";
	
	//-----------------------------------------------------------------------------------------------------------------------------
	
	/*$SQLSTR = "SELECT rs00002.mr_no as mr_no, 
							rs00006.id AS no_reg, 
							rs00002.nama, 
							to_char(rs00006.tanggal_reg,'DD MON YYYY') || ' ' || rs00006.waktu_reg::time(0) as tgl_reg, 
							rs00002.alm_tetap as alamat, 
							rs00001.tdesc AS tipe_pasien, 
							A.tdesc AS poli,
							to_char((select sum(z.jumlah) from rs00044 z where z.no_reg = rs00006.id),'999,999,999.99') as deposit,
							rs00006.id AS no_reg, rs00006.id AS no_reg
                FROM rs00006
                            JOIN rs00002 ON rs00002.mr_no = rs00006.mr_no
                            JOIN rs00001 ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
                            JOIN rs00001 A ON A.tc = rs00006.poli::text AND A.tt = 'LYN'";*/
							
	$SQLSTR = "select a.mr_no as mr_no, a.id as no_reg, upper(a.nama)as nama, 
		a.tgl_reg as tgl_reg, a.asal as poli, a.pasien as tipe_pasien, a.tagih,
		case when a.sisa < 0 then a.sisa * -1 else a.sisa end, 
		to_char((select sum(z.jumlah) from rs00044 z where z.no_reg = a.id),'999,999,999.99') as deposit, a.id as no_reg, a.id as no_reg
		from rsv0012 a 
		where a.statusbayar = 'BELUM LUNAS' and a.asal = 'POLIKLINIK GIGI DAN MULUT'";
	
	if ($_GET[search]) {
		$SQLSTR .= " and (upper(a.nama) like '%".strtoupper($_GET[search])."%' or a.id like '%".$_GET[search]."%' or a.mr_no like '%".$_GET[search]."%') ";

	}

	//-----------------------------------------------------------------------------------------------------------------------------
	$t = new PgTable($con, "100%");
	$t->SQL = "$SQLSTR";
	
	if (!isset($_GET[sort])) {

			   $_GET[sort] = "a.id";
			   $_GET[order] = "desc";
	}

	$t->ColHeader = array("NO MR", "NO REG", "NAMA", "TANGGAL REG", "POLI", "TIPE PASIEN","TOTAL TAGIHAN", "SISA TAGIHAN","DEPOSIT","EDIT","PRINT<br>KWITANSI");
	$t->ShowRowNumber = true;
	$t->ColAlign[0] = "CENTER";
	$t->ColAlign[1] = "CENTER";
	$t->ColAlign[2] = "LEFT";
	$t->ColAlign[3] = "CENTER";
	$t->ColAlign[4] = "CENTER";
	$t->ColAlign[5] = "CENTER";
	$t->ColAlign[6] = "RIGHT";
	$t->ColAlign[7] = "RIGHT";
	$t->ColAlign[8] = "RIGHT";
	$t->ColAlign[9] = "CENTER";
	$t->ColAlign[10] = "CENTER";
	$t->RowsPerPage = $ROWS_PER_PAGE;
	
	$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=888&sub=5&rg=<#1#>&mr=<#0#>&kas=$kasirnya&rg1=<#1#>'><#2#></A>";
	$t->ColFormatHtml[9] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&sub=5&e=<#9#>&rg=<#1#>&mr=<#0#>&kas=$kasirnya'>".icon("edit","Edit")."</A>";

	//$t->ColFormatHtml[10] = "<a href='javascript: cetakrinciansementara2(<#1#>)' ><img src='images/cetak.gif' border='0'></a>";
	$t->ColFormatHtml[10] = "<nobr><A CLASS=TBL_HREF HREF='includes/cetak.888_rj.php?rg=<#1#>'>".icon("ok","Cetak")."</A></nobr>";
	$t->execute();
	
	//-----------------------------------------------------------------------------------------------------------------------------

	//echo "\n<script language='JavaScript'>\n";
	//echo "function cetakrinciansementara2(tag) {\n";
	//echo "    sWin = window.open('includes/cetak.sementara2.php?rg=' + tag+'&kas=".$_GET["kas"]."', 'xWin',".
	//	 " 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');\n";
	//echo "    sWin.focus();\n";
	//echo "}\n";
	//echo "</script>\n";

}
?>
