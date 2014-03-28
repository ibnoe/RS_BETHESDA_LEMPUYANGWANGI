<?
//heri 30 august 2007
//udah di cek

$PID = "lap_proses_keperawatan";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/visit_setting.php");

//if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
//$_GET["mPOLI"]=
$setting_ri["proses_keperawatan"];

	if ($_GET['act'] ==  "detail"){
		
			// ambil bangsal
			    $id_max = getFromTable("select max(id) from rs00010 where no_reg = '".$_GET["id"]."'");
			    if (!empty($id_max)) {
			    $bangsal = getFromTable("select c.bangsal || ' / ' || e.tdesc ".
			                       "from rs00010 as a ".
			                       "    join rs00012 as b on a.bangsal_id = b.id ".
			                       "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
			                       //"    join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
			                       "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
			                       "where a.id = '$id_max'");
			    }
			    //echo $bangsal;
						$sql2 = "select a.id,a.mr_no,a.nama,a.umur,a.tgl_lahir,a.tmp_lahir,a.tanggal_reg,a.status_akhir, ".
								"a.pangkat_gol,a.nrp_nip,a.kesatuan, a.jenis_kelamin,a.diagnosa_sementara ".
								"from rsv_pasien2 a  ".
								"where a.id= '{$_GET['id']}'";			
						
						$r2 = pg_query($con,$sql2);
						$n2 = pg_num_rows($r2);
					    if($n2 > 0) $d2 = pg_fetch_object($r2);
					    pg_free_result($r2);
		    			    
			echo "<DIV>";
			//echo "<br>";

			echo "<table class='tbl_border' border='0' width='100%' cellspacing=1 cellpadding=0>";
			echo "<tr><td class='tbl_body' valign=top width='34%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama","<b>". $d2->nama."</b>");
		    $f->text("Umur",$d2->umur);
			$f->text("Tgl Masuk",$d2->tanggal_reg);
		    $f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='23%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM","<b>". $d2->mr_no."</b>");
			$f->text("No.Reg", $d2->id);
			$f->text("Seks",$d2->jenis_kelamin);
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='43%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Pangkat / NRP", $d2->pangkat_gol." ".$d2->nrp_nip );
		    $f->text("Kesatuan",$d2->kesatuan);
		    $f->text("Ruang ",$bangsal);
		    $f->execute();
		    echo "</td></tr></table><br>"; 
		   
		    $sql = "SELECT a.vis_5|| ' / ' ||a.vis_1 as tgl_jam,a.vis_2,a.vis_3 ,a.vis_4,a.vis_6 ". 
					   "FROM c_visit_ri a ".
					   "LEFT JOIN RS00006 b ON a.no_reg=b.id ".
					   //"LEFT JOIN RS00017 C ON A.VIS_5 = C.ID ".
					   "WHERE b.mr_no = '".$_GET["mr"]."' AND a.id_ri = '{$_GET["mPOLI"]}' ";
					   
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			    $t->ColHeader = array("Tgl / Jam","Diagnosa Keperawatan","Rencana dan Tindakan Keperawatan","Evaluasi ","Nama Jelas");
			   	$t->ColAlign = array("center","left","left","left","left");
				if ($GLOBALS['print']){
			   		$t->RowsPerPage = 20;
			    	$t->DisableNavButton = true;
			    	$t->DisableScrollBar = true;
			    	$t->DisableSort = true;
			   	}else {$t->RowsPerPage = $ROWS_PER_PAGE;}
				$t->execute();  
		
				//include(rm_tindakan);
		
		echo "</DIV>";
		
				
	}else {
		
		echo "<table border='0' width='100%'><tr><td width='70%' align='left'>";
		
    	if (!$GLOBALS['print']){
			$ext = "OnChange = 'Form1.submit();'";
			$ext2 = "OnChange = 'Form.submit();'";
			
		    $f = new Form($SC, "GET", "NAME=Form1");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		    //$f->hidden("sub", $_GET["sub"]);		  	
	  		$f->selectMonthYear("mBULAN","Berdasarkan Tgl.Masuk pada  Bulan",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
						         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
								 "11"=>"November","12"=>"Desember"),$_GET["mBULAN"],$ext,
								 "mTAHUN", "Tahun","select distinct to_char(tanggal_reg,'YYYY'), to_char(tanggal_reg,'YYYY') from rs00006 ",
						         $_GET["mTAHUN"],$ext );
			$f->execute();		
			
			$f = new Form($SC, "GET", "NAME=Form");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		   // $f->hidden("sub", $_GET["sub"]);
	  		$f->selectMonthYear("mBULAN2","Berdasarkan Entri Data pada Bulan",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
						         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
								 "11"=>"November","12"=>"Desember"),$_GET["mBULAN2"], $ext2,
								 "mTAHUN2", "Tahun","select distinct to_char(tanggal_reg,'YYYY'), to_char(tanggal_reg,'YYYY') from rs00006 ",
						         $_GET["mTAHUN2"],$ext2);	  	
			$f->execute();
    	}elseif ($_GET["mBULAN"] || $_GET["mTAHUN"]){
    		$f = new Form($SC, "GET", "NAME=Form1");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		   // $f->hidden("sub", $_GET["sub"]);
	  		$f->selectMonthYear("mBULAN","Berdasarkan Tgl.Masuk pada  Bulan",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
						         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
								 "11"=>"November","12"=>"Desember"),$_GET["mBULAN"],"disabled",
								 "mTAHUN", "Tahun","select distinct to_char(tanggal_reg,'YYYY'), to_char(tanggal_reg,'YYYY') from rs00006 ",
						         $_GET["mTAHUN"],"disabled" );
			$f->execute();
    	}elseif ($_GET["mBULAN2"] || $_GET["mTAHUN2"]){
    		$f = new Form($SC, "GET", "NAME=Form");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		    //$f->hidden("sub", $_GET["sub"]);
	  		$f->selectMonthYear("mBULAN2","Berdasarkan Entri Data pada Bulan",Array("0"=>" ","1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
						         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
								 "11"=>"November","12"=>"Desember"),$_GET["mBULAN2"], "disabled",
								 "mTAHUN2", "Tahun","select distinct to_char(tanggal_reg,'YYYY'), to_char(tanggal_reg,'YYYY') from rs00006 ",
						         $_GET["mTAHUN2"],"disabled");	  	
			$f->execute();
    	}
			
		    $start_tgl = mktime(0,0,0,$_GET[mBULAN],1,$_GET[mTAHUN]);
		    $max_tgl = date("t", $start_tgl);
		    $end_tgl = mktime(0,0,0,$_GET[mBULAN],$max_tgl,$_GET[mTAHUN]);
		    $start_tgl = date("Y-m-d", $start_tgl);
		    $end_tgl = date("Y-m-d", $end_tgl);
					    
		    $start_tgl2 = mktime(0,0,0,$_GET[mBULAN2],1,$_GET[mTAHUN2]);
		    $max_tgl2 = date("t", $start_tgl2);
		    $end_tgl2 = mktime(0,0,0,$_GET[mBULAN2],$max_tgl2,$_GET[mTAHUN2]);
		    $start_tgl2 = date("Y-m-d", $start_tgl2);
		    $end_tgl2 = date("Y-m-d", $end_tgl2);
			
	echo "</td><td width='30%' align='right' valign='middle'>";
			$f = new Form($SC, "GET","NAME=Form2");
		    $f->hidden("p", $PID);
		  //  $f->hidden("sub", $_GET["sub"]);
		    if (!$GLOBALS['print']){
		    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
			}elseif ($_GET["search"]) { 
			   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
			}
		    $f->execute();
	    	if ($msg) errmsg("Error:", $msg);
	echo "</td></tr></table>";
		    
        
    		$tglhariini = substr(date("Y-m-d", time()),0,10);    		 
						
			$SQL = "select f.mr_no,f.nama,f.id,f.pangkat_gol,f.nrp_nip,f.kesatuan,to_char(min(a.ts_check_in),'dd Mon YYYY') as ts_check_in, ".
					"(select to_char(max(ts_calc_stop),'dd Mon yyyy')as tgl_keluar from rs00010 where a.no_reg=no_reg and id=(select max(id) from rs00010 where no_reg =a.no_reg) ) as check_out,  ".
					"case when f.status = 'P' Then 'Check-Out/Pindah' else 'Dirawat' end as status ".
					"from rs00010 a ".
					"join rsv_pasien2 f on a.no_reg=f.id ".
					"join rs00012 as b on a.bangsal_id = b.id ".
					"join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
					"join rs00001 as g on f.poli = g.tc_poli and g.tt = 'LYN' ".
					" join c_visit_ri c on a.no_reg=c.no_reg ". 
					"where c.id_ri= '{$_GET["mPOLI"]}' ";
																	
	if ($_GET["search"]) {
		$SQLWHERE =
			"and (upper(f.nama) LIKE '%".strtoupper($_GET["search"])."%' or f.id like '%".$_GET['search']."%' or f.mr_no like '%".$_GET["search"]."%' ".
			" or upper(f.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or f.nrp_nip like '%".$_GET['search']."%' ".
			" or upper(f.kesatuan) like '%".strtoupper($_GET["search"])."%' or upper(f.alm_tetap) like '%".strtoupper($_GET["search"])."%') ".
			"group by f.mr_no,f.id,f.nama,f.pangkat_gol,f.nrp_nip,f.kesatuan,c.no_reg,a.no_reg,g.tdesc,f.status ";
		
	}elseif ($_GET["mBULAN"] || $_GET["mTAHUN"]) {
		$SQLWHERE = "and (a.ts_check_in >=  '$start_tgl' and a.ts_check_in <= '$end_tgl') ".
					"group by f.mr_no,f.id,f.nama,f.pangkat_gol,f.nrp_nip,f.kesatuan,c.no_reg,a.no_reg,g.tdesc,f.status ";
	
	}elseif ($_GET["mBULAN2"] || $_GET["mTAHUN2"]) {
		$SQLWHERE = "and (c.tanggal_reg >=  '$start_tgl2' and c.tanggal_reg <= '$end_tgl2') ".
					"group by f.mr_no,f.id,f.nama,f.pangkat_gol,f.nrp_nip,f.kesatuan,c.no_reg,a.no_reg,g.tdesc,f.status ";
				  
	}else {
		$SQLWHERE = "and  to_char(c.tanggal_reg,'DD Mon YYYY')= '$tglhariini' ".
					"group by f.mr_no,f.id,f.nama,f.pangkat_gol,f.nrp_nip,f.kesatuan,c.no_reg,a.no_reg,g.tdesc,f.status ";
				 
	}
				
	echo "<DIV ><br>";
		$t = new PgTable($con, "100%");
	    $t->SQL = "$SQL $SQLWHERE" ;
	    $t->ShowRowNumber = true;
		$t->ColHidden[3] = true;
		$t->ColHeader = array("NO.RM","NAMA PASIEN","PANGKAT","","NRP/NIP","KESATUAN","TGL.MASUK","TGL.KELUAR","STATUS");
		$t->ColAlign = array("left","left","left","left","left","left","center","center");		
		if (!$GLOBALS['print']){
			$t->ColFormatHtml[1] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&act=detail&id=<#2#>&mr=<#0#>'><#1#></A>";
	   		$t->RowsPerPage = $ROWS_PER_PAGE;			
	   	}else {
	   		$t->RowsPerPage = 20;
	    	$t->DisableNavButton = true;
	    	$t->DisableScrollBar = true;
	    	$t->DisableSort = true;
	   	}
		$t->execute();  	

	echo "</div>";

		
	}
//}
?>
