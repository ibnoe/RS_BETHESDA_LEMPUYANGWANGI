<?
$_GET['search'] = trim($_GET['search']);
// app, 08-09-2007
$PID = "p_layanan_rawat_inap1";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();
require_once("startup.php");
function color( $dstr, $r ) {
	    //if ($dstr[7] == '-') {
	    	if ($dstr[7] == 'Sudah Keluar' ){
	    		return "<font color=RED>{$dstr[$r]}</font>";
	    	}elseif ($dstr[7] == 'Masih Dirawat'){
	    		return "<font color=BLUE>{$dstr[$r]}</font>";
	    	}
	    //}else return $dstr[$i];
}

title_print("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  LAYANAN RAWAT INAP");

            echo "<div align=right>";
            $f = new Form($SC, "GET","NAME=Form2");
            $f->hidden("p", $PID);
	    $f->hidden("sub", 1);
	    if (!$GLOBALS['print']){
	    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
		}else { 
		   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
		}
	    $f->execute();
    	if ($msg) errmsg("Error:", $msg);
    	echo "</div>";
		//---------------------
		echo "<br>";
$tglhariini = date("Y-m-d", time());
//echo $_SESSION[gr];
		if ($_SESSION[gr]=="RSSH-RI-KEBIDANAN"){
			$bangsal = " f.bangsal like '%KAMAR PERAWATAN PASIEN DEWASA KEBIDANAN%' ";
		}elseif($_SESSION[gr]=="RSSH-RI-PERINATOLOGI"){
			$bangsal = " f.bangsal like '%KAMAR PERAWATAN PERINATOLOGI%' ";
		}elseif ($_SESSION[gr]=="RSSH-RI-ANAK"){	
			$bangsal = " f.bangsal like '%KAMAR PERAWATAN PASIEN ANAK%' ";
		}elseif ($_SESSION[gr]=="RSSH-RI-BEDAH"){
			$bangsal = " f.bangsal like '%KAMAR PERAWATAN PASIEN DEWASA BEDAH & DALAM%' ";
		}elseif ($_SESSION[gr]=="KSO_PERI"){
			$bangsal = " f.bangsal like '%SHOFA BAYI%' ";
		}elseif($_SESSION[gr]=="KSO_MUL"){
			$bangsal = "f.bangsal like '%MULTAZAM%' ";
		}elseif($_SESSION[gr]=="RI_ICU" or $_SESSION[gr]=="RI_ICCU"){
			//$bangsal = "ICU";
			$bangsal = " (f.bangsal like '%ICU%' or f.bangsal like '%ICCU%') ";
		//}elseif($_SESSION[gr]=="RI_ICCU"){
		//	$bangsal = "ICCU";
		//	$bangsal = " f.bangsal like '%INTERNE WANITA%' ";
		}elseif($_SESSION[gr]=="RI_ANAK"){
			//$bangsal = "ANAK";	
			$bangsal = " f.bangsal like '%ANAK%' ";
        }elseif($_SESSION[gr]=="RI_KO"){
			//$bangsal = "OPERASI";	
			$bangsal = " f.bangsal like '%OPERASI%' ";
        }elseif($_SESSION[gr]=="RI_KEBID"){
			//$bangsal = "KEBIDANAN";	
			$bangsal = " f.bangsal like '%KEBIDANAN%' ";
        }elseif($_SESSION[gr]=="RI_MATA"){
			//$bangsal = "MATA";	
			$bangsal = " f.bangsal like '%MATA%' ";
        }elseif($_SESSION[gr]=="RI_K_INTER"){
			//$bangsal = "KELAS INTERNE";	
			$bangsal = " f.bangsal like '%KELAS INTERNE%' ";
        }elseif($_SESSION[gr]=="RI_PARU"){
			//$bangsal = "PARU";	
			$bangsal = " f.bangsal like '%PARU%' ";
        }elseif($_SESSION[gr]=="RI_PRE_IGD"){
			//$bangsal = "PREOP IGD";	
			$bangsal = " f.bangsal like '%PREOP IGD%' ";
        }elseif($_SESSION[gr]=="RI_VIP_CM"){
 			//$bangsal = "VIP CINDUA";
			$bangsal = " f.bangsal like '%VIP CINDUA%' ";			
		}elseif($_SESSION[gr]=="VVIP"){
 			//$bangsal = "MATO";	
			$bangsal = " f.bangsal like '%MATO%' ";
        }elseif($_SESSION[gr]=="RI_AMBUN"){
 			//$bangsal = "AMBUN";
			$bangsal = " f.bangsal like '%AMBUN%' ";
		}elseif($_SESSION[gr]=="RI_THT"){
 			//$bangsal = "THT";
			$bangsal = " f.bangsal like '%THT%' ";
		}elseif($_SESSION[gr]=="RI_ZAL"){
 			//$bangsal = "ZAL";
			$bangsal = " f.bangsal like '%ZAL%' ";
		}elseif($_SESSION[gr]=="RI_PARU"){
 			//$bangsal = "PARU";
			$bangsal = " f.bangsal like '%PARU%' ";
		}elseif($_SESSION[gr]=="RI_BAYI"){
 			//$bangsal = "BAYI";
			$bangsal = " f.bangsal like '%BAYI%' ";
		}else{
			$bangsal = "f.bangsal like '%%'";
		}
            //echo $_SESSION[gr];   
if(getFromTable("SELECT COUNT(id) FROM rs00012 WHERE bangsal = (SELECT split_part(grup_id,'-',2) FROM rs99995 WHERE uid = '".$_SESSION['uid']."')") > 0){ 
$GRUP = getFromTable("SELECT split_part(grup_id,'-',2) FROM rs99995 WHERE uid = '".$_SESSION['uid']."'");
$SQL_GRUP = "AND (f.bangsal ILIKE '%".$GRUP."%'";
  if($GRUP=='MINA'){
  $SQL_GRUP .= " OR f.bangsal ILIKE '%KHODIJAH%'";
  }
  else if($GRUP=='AROFAH'){
  $SQL_GRUP .= " OR f.bangsal ILIKE '%ZENAB%'";
  }
$SQL_GRUP .= ')';
}  
$SQLSTR = "select b.mr_no, a.no_reg, upper(b.nama)as nama, ".
          "    to_char(h.ts_check_in,'DD MON YYYY HH24:MI:SS') as tgl_masuk,g.tdesc, ".
	  "    b.alm_tetap,f.bangsal || ' / ' || e.bangsal || '/' || i.tdesc ||  '/' || d.bangsal as bangsal,  
                case when c.status = 'P' then 'Sudah Keluar' else 'Masih Dirawat' end as status ".
          "from rs00010 as a 
           ".
          "    join rs00006 as c on a.no_reg = c.id ".
          "    join rs00002 as b on c.mr_no = b.mr_no ".
          "    join rs00012 as d on a.bangsal_id = d.id ".
	  "    join rs00010 as h ON h.id = (SELECT min(id) FROM rs00010 WHERE a.no_reg=no_reg)".
          "    join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
          "    join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' ".$SQL_GRUP.
	  "    join rs00001 i on i.tc = e.klasifikasi_tarif_id and i.tt='KTR' ".
	  "    left join rs00001 g on g.tc = c.tipe and g.tt='JEP' ".
          "where a.ts_calc_stop is null and $bangsal
            "; 


if ($_GET[search]) {
    $SQLSTR .= " and (upper(b.nama) like '%".strtoupper($_GET[search])."%' or a.no_reg like '%".$_GET[search]."%' or b.alm_tetap ilike '%".$_GET[search]."%' or b.mr_no ilike '%".$_GET[search]."%') ";

}


$t = new PgTable($con, "100%");
$ORDER = "";
if(empty($_GET[order])){
$ORDER = "ORDER BY e.bangsal ASC";
}
$t->SQL = "$SQLSTR group by c.status,b.mr_no, a.no_reg, b.nama,h.ts_check_in,g.tdesc,b.alm_tetap,f.bangsal,i.tdesc,e.bangsal,d.bangsal $ORDER";
/*
if (!isset($_GET[order])) {

           $_GET[order] = "a.ts_check_in";
           $_GET[sort] = "asc";
}
if (!isset($_GET[sort])) {

           $_GET[sort] = "a.no_reg";
           $_GET[order] = "desc";
}
*/
$t->ColHeader = array("NO MR", "NO REG", "NAMA", "TGL/JAM MASUK","TIPE PASIEN", "ALAMAT",
                      "RUANGAN",  "STATUS");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[1] = "CENTER";
$t->ColAlign[7] = "CENTER";
$t->ColAlign[3] = "CENTER";
$t->ColColor[7] = "color";
$t->RowsPerPage = $ROWS_PER_PAGE;
$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_layanan_rawat_inap&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
$t->execute();
unset($_SESSION["BANGSAL"]["desc"]);
unset($_SESSION["BANGSAL"]["id"]);


?>
