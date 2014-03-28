<?

echo "<table border='0' width='100%'><tr><td width='70%' align='left'>";
		
    	if (!$GLOBALS['print']){
			//$ext = "OnChange = 'Form1.submit();'";
			//$ext2 = "OnChange = 'Form.submit();'";
			
		    $f = new Form($SC, "GET", "NAME=Form1");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		    $f->hidden("sub", $_GET["sub"]);		  	
	  		$f->selectMonthYear("mBULAN","Berdasarkan Tgl.Masuk pada  Bulan",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
						         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
								 "11"=>"November","12"=>"Desember"),$_GET["mBULAN"],$ext,
								 "mTAHUN", "Tahun","select distinct to_char(tanggal_reg,'YYYY'), to_char(tanggal_reg,'YYYY') from rs00006 ",
						         $_GET["mTAHUN"],$ext );
			//$f->submit("Tampilkan");
			$f->execute();		
			
			$f = new Form($SC, "GET", "NAME=Form");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		    $f->hidden("sub", $_GET["sub"]);
	  		$f->selectMonthYear("mBULAN2","Berdasarkan Entri Data pada Bulan",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
						         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
								 "11"=>"November","12"=>"Desember"),$_GET["mBULAN2"], $ext2,
								 "mTAHUN2", "Tahun","select distinct to_char(tanggal_reg,'YYYY'), to_char(tanggal_reg,'YYYY') from rs00006 ",
						         $_GET["mTAHUN2"],$ext2);	  	
			$f->submit("Tampilkan");
			$f->execute();
    	}elseif ($_GET["mBULAN"] || $_GET["mTAHUN"]){
    		$f = new Form($SC, "GET", "NAME=Form1");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		    $f->hidden("sub", $_GET["sub"]);
	  		$f->selectMonthYear("mBULAN","Berdasarkan Tgl.Masuk pada  Bulan",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
						         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
								 "11"=>"November","12"=>"Desember"),$_GET["mBULAN"],"disabled",
								 "mTAHUN", "Tahun","select distinct to_char(tanggal_reg,'YYYY'), to_char(tanggal_reg,'YYYY') from rs00006 ",
						         $_GET["mTAHUN"],"disabled" );
			//$f->submit("Tampilkan");
			$f->execute();
    	}elseif ($_GET["mBULAN2"] || $_GET["mTAHUN2"]){
    		$f = new Form($SC, "GET", "NAME=Form");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		    $f->hidden("sub", $_GET["sub"]);
	  		$f->selectMonthYear("mBULAN2","Berdasarkan Entri Data pada Bulan",Array("0"=>" ","1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
						         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
								 "11"=>"November","12"=>"Desember"),$_GET["mBULAN2"], "disabled",
								 "mTAHUN2", "Tahun","select distinct to_char(tanggal_reg,'YYYY'), to_char(tanggal_reg,'YYYY') from rs00006 ",
						         $_GET["mTAHUN2"],"disabled");	  	
			$f->submit("Tampilkan");
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
		    $f->hidden("sub", $_GET["sub"]);
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
			$t->ColFormatHtml[1] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&sub={$_GET["sub"]}&act=detail&id=<#2#>&mr=<#0#>'><#1#></A>";
	   		$t->RowsPerPage = $ROWS_PER_PAGE;			
	   	}else {
	   		$t->RowsPerPage = 20;
	    	$t->DisableNavButton = true;
	    	$t->DisableScrollBar = true;
	    	$t->DisableSort = true;
	   	}
		$t->execute();  	

	echo "</div>";

?>
