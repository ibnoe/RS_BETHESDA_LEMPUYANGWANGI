<?
// app, 08-09-2007
$PID = "p_layanan_rawat_inap_bhp";
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

title_print("<img src='icon/apotek1-icon.png' align='absmiddle' width=48 >  LAYANAN BHP PASIEN RAWAT INAP");

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
		if ($_SESSION[gr]=="KSO_AROF"){
			$bangsal = " f.bangsal like '%AROFAH%' ";
		}elseif($_SESSION[gr]=="KSO_MINA"){
			$bangsal = " f.bangsal like '%MINA%' ";
		}elseif ($_SESSION[gr]=="KSO_MAR"){
			$bangsal = " f.bangsal like '%MARWAH%' ";
		}elseif ($_SESSION[gr]=="KSO_SHOFA"){
			$bangsal = " f.bangsal like '%SHOFA PERSALINAN%' ";
		}elseif ($_SESSION[gr]=="KSO_PERI"){
			$bangsal = " f.bangsal like '%SHOFA BAYI%' ";
		}elseif($_SESSION[gr]=="KSO_MUL"){
			$bangsal = "f.bangsal like '%MULTAZAM%' ";
		}elseif($_SESSION[gr]=="RI_ICU" or $_SESSION[gr]=="RI_ICCU"){
			$bangsal = " (f.bangsal like '%ICU%' or f.bangsal like '%ICCU%') ";
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
$SQLSTR = "select b.mr_no, a.no_reg, upper(b.nama)as nama, ".
          "    to_char(a.ts_check_in,'DD MON YYYY HH24:MI:SS') as tgl_masuk,g.tdesc, ".
	  "    b.alm_tetap,f.bangsal || ' / ' || e.bangsal|| ' / ' || d.bangsal as bangsal ".
          "from rs00010 as a 
           ".
          "    join rs00006 as c on a.no_reg = c.id ".
          "    join rs00002 as b on c.mr_no = b.mr_no ".
          "    join rs00012 as d on a.bangsal_id = d.id ".
          "    join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
          "    join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' ".
	   "	 left join rs00001 g on g.tc = b.tipe_pasien and g.tt='JEP' ".
          "where a.ts_calc_stop is null and $bangsal
            "; 


if ($_GET[search]) {
    $SQLSTR .= " and (upper(b.nama) like '%".strtoupper($_GET[search])."%' or a.no_reg like '%".$_GET[search]."%' or b.mr_no like '%".$_GET[search]."%') ";

}


$t = new PgTable($con, "100%");
$ORDER = "";
if(empty($_GET[order])){
$ORDER = "ORDER BY a.ts_check_in desc";
}
$t->SQL = "$SQLSTR group by c.status,b.mr_no, a.no_reg, b.nama,a.ts_check_in,g.tdesc,b.alm_tetap,f.bangsal,e.bangsal,d.bangsal $ORDER";
$t->ColHeader = array("NO MR", "NO REG", "NAMA", "TGL/JAM MASUK","TIPE PASIEN", "ALAMAT", "RUANGAN",  "STATUS");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[1] = "CENTER";
$t->ColAlign[7] = "CENTER";
$t->ColAlign[3] = "CENTER";
$t->ColColor[7] = "color";
$t->RowsPerPage = $ROWS_PER_PAGE;
$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=apotik_bhp_rwi&rg=<#1#>'><#2#></A>";
//$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_riwayat_penyakit&rg=<#1#>&rg1=<#1#>&ri=E05&mr=<#0#>&ri=$ri&rg1=<#1#>&list=layanan&sub2=bhp'><#2#></A>";
$t->execute();
unset($_SESSION["BANGSAL"]["desc"]);
unset($_SESSION["BANGSAL"]["id"]);
?>