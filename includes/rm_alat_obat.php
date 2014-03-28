<?php
//  hery-- may 28, 2007 

$PID = "rm_alat_obat";
$SC = $_SERVER["SCRIPT_NAME"];

session_start();

if (!empty($_SESSION[uid])) {

require_once("startup.php");
require_once("lib/visit_setting.php");

//echo "<hr noshade size=1>";

if ($_GET['act'] == "total"){
	if (!$GLOBALS['print']){
		title_print("<img src='icon/medical-record-2.gif' align='absmiddle' >  REKAM MEDIS PENGGUNAAN ALAT DAN OBAT");
	}else{
		title_print("<img src='icon/medical-record.gif' align='absmiddle' >  REKAM MEDIS PENGGUNAAN ALAT DAN OBAT");		
	}
		if($_GET["tc"] == "view") {
		    title("Rincian Pengeluaran Barang");
		    $r = pg_query($con, "select b.obat,a.harga,c.tdesc as satuan,d.tdesc as kategori ".
		                    "from rs00008 a, rs00015 b, rs00001 c, rs00001 d ".
		                    "where a.item_id = '".$_GET["v"]."' ".
		                    "and to_number(a.item_id, '999999999999') = b.id ".
		                    "and b.satuan_id = c.tc and c.tt='SAT' ".
		                    "and b.kategori_id = d.tc and d.tt='GOB'");
		
		    $n = pg_num_rows($r);
		    if($n > 0) $d = pg_fetch_object($r);
		    pg_free_result($r);
		
		    $f = new ReadOnlyForm();
		    $f->text("Nama Barang ", $d->obat);
		    $f->text("Satuan ",$d->satuan);
		    $f->text("Harga " ,$d->harga);
		    $f->text("Kategori ", $d->kategori);
		    $f->execute();
		
		    if (!$GLOBALS['print']){
		    	echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</DIV>";
		    }
		    echo "<br>";
		    
		    $t = new PgTable($con, "100%");
		    
		    $r2 = pg_query($con, "select sum(a.qty) as jum, sum(a.qty*a.harga) as nilai ".
		              "from rs00008 a ".
		              "left join rs00015 b on to_number(a.item_id::text, '999999999999'::text) = b.id ".
		              "left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
		              "where (a.tanggal_trans between '".$_GET[ts_check_in1]."' and '".$_GET[ts_check_in2]."') and ".
			      "a.trans_type='OB1' and a.item_id ='".$_GET[v]."' ");
		
		    $d2 = pg_fetch_object($r2);
		    pg_free_result($r2);
		
		    $t->SQL = "select e.nama, no_reg, ".
		              "to_char(a.tanggal_trans,'DD Mon YYYY')as tgl_trans_str, ".
		              "qty as jum, a.harga, (qty*harga) as nilai ".
		              "from rs00008 a, rs00015 b ,rs00001 c, rs00006 d, rs00002 e ".
		              "where (a.tanggal_trans between '".$_GET[ts_check_in1]."' and '".$_GET[ts_check_in2]."') and ".
			      "a.trans_type='OB1' and ".
		              "to_number(a.item_id::text, '999999999999'::text) = b.id ".
		              "and b.satuan_id = c.tc and c.tt='SAT' ".
		              "and a.no_reg = d.id and d.mr_no = e.mr_no ".
		              "and item_id ='".$_GET["v"]."'";
		
		
				    if (!isset($_GET[sort])) {
				           $_GET[sort] = "nama";
				           $_GET[order] = "asc";
				    }
				    $t->setlocale("id_ID");
				    $t->ShowRowNumber = true;
				    $t->ColAlign[2] = "CENTER";
				    //$t->ColFormatMoney[4] = "%!+#2n";
				    //$t->ColFormatMoney[5] = "%!+#2n";
				    $t->ColFormatNumber[3] = 0;
				    $t->ColFormatNumber[4] = 2;
				    $t->ColFormatNumber[5] = 2;
				    $t->ColHeader = array("NAMA PASIEN","NO.REG","TANGGAL","QTY","HARGA","TOTAL");
				    $t->ColFooter[3] =  number_format($d2->jum,0,',','.');
				    //$t->ColFooter[4] =  number_format($d2->harga,2);
				    $t->ColFooter[5] =  number_format($d2->nilai,2,',','.');
				    if (!$GLOBALS['print']){
						$t->RowsPerPage = 20;
				    }else {
				    	$t->RowsPerPage = 30;				    
						$t->DisableNavButton = true;
						$t->DisableScrollBar = true;
					}
				    $t->execute();
				
		} else {
				title("Laporan Pengeluaran Obat per Kategori");
				if (!$GLOBALS['print']){
					echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</DIV>";
				}
							
				echo "<table width='100%'><tr><td width='50%' align='left'>";
				$f = new Form("$SC?p=$PID&act=total", "GET", "NAME=Form1");
				$f->PgConn = $con;
				$f->hidden("p", $PID);
				$f->hidden("act", "total");
				$f->hidden("mDETIL","S");
				if ($GLOBALS['print']){
					$ext = "disabled";
				}
					$f->selectSQL("mOBT", "Kategori Obat",
					    "select '' as tc, '' as tdesc union ".
					    "select tc, tdesc ".
					    "from rs00001 ".
					    "where tt = 'GOB' and (tc='001' or tc='002' or tc='003') ".
					    "order by tdesc", $_GET["mOBT"], $ext);
								
				include("xxx2");
				
				$f->submit ("OK",$ext);
				$f->execute();				
								   
				echo "</td><td align='right'>";				    
				    // search box
				   // echo "<BR>";
				    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='$SC?p=$PID&act=total'><TR>";
				    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
				    echo "<INPUT TYPE=HIDDEN NAME=act VALUE='total'>";
				    echo "<INPUT TYPE=HIDDEN NAME=mDETIL VALUE='".$_GET["mDETIL"]."'>";
				    echo "<INPUT TYPE=HIDDEN NAME=mOBT VALUE='".$_GET["mOBT"]."'>";
				    echo "<INPUT TYPE=HIDDEN NAME=tanggal1D VALUE='".$_GET["tanggal1D"]."'>";
				    echo "<INPUT TYPE=HIDDEN NAME=tanggal1M VALUE='".$_GET["tanggal1M"]."'>";
				    echo "<INPUT TYPE=HIDDEN NAME=tanggal1Y VALUE='".$_GET["tanggal1Y"]."'>";
				    echo "<INPUT TYPE=HIDDEN NAME=tanggal2D VALUE='".$_GET["tanggal2D"]."'>";
				    echo "<INPUT TYPE=HIDDEN NAME=tanggal2M VALUE='".$_GET["tanggal2M"]."'>";
				    echo "<INPUT TYPE=HIDDEN NAME=tanggal2Y VALUE='".$_GET["tanggal2Y"]."'>";
				    if (!$GLOBALS['print']){
					    echo "<TD><font class=SUB_MENU>NAMA BARANG:</font> <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
					    echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
				    }else{
				    	echo "<TD><font class=SUB_MENU>NAMA BARANG:</font> <INPUT disabled TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
					    echo "<TD><INPUT disabled TYPE=SUBMIT VALUE=' CARI '></TD>";
				    }
				    echo "</TR></FORM></TABLE></DIV>";
				echo "</td></tr></table>";
				    
				$t = new PgTable($con, "100%");
							
				if ($_GET["mDETIL"] == 'S') {
				    // summary
				    $r2 = pg_query($con, 
				            "select sum(a.qty) as jum,  ".
				            "sum(a.qty*a.harga) as nilai ".
				            "from rs00008 a ".
				            "     left join rs00015 b on to_number(a.item_id, '999999999999') = b.id and b.kategori_id = '".$_GET["mOBT"]."' ".
				            "     left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
				            "where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
					    "a.trans_type='OB1' ".
				            "and upper(obat) LIKE '%".strtoupper($_GET["search"])."%' ");
				            //"group by a.item_id,b.obat,c.tdesc,b.kategori_id,a.harga");
				
				
				    $d2 = pg_fetch_object($r2);
				    pg_free_result($r2);
				
				    if (!isset($_GET[sort])) {
				           $_GET[sort] = "obat";
				           $_GET[order] = "asc";
				    }
				
				
				    //$t = new PgTable($con, "100%");
				    $t->SQL = "select b.obat, c.tdesc, sum(qty) as jum, a.harga, ".
				            "sum(qty*harga) as nilai, a.item_id as dummy ".
				            "from rs00008 a, rs00015 b, rs00001 c ".
				            "where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
					    "a.trans_type='OB1' and ".
				            "to_number(a.item_id, '999999999999') = b.id and ".
				            "b.kategori_id = '".$_GET["mOBT"]."' ".
				            "and b.satuan_id = c.tc and c.tt='SAT' ".
				            "and upper(obat) LIKE '%".strtoupper($_GET["search"])."%' ".
				            "group by a.item_id,b.obat,c.tdesc,b.kategori_id,a.harga";
				
				
				    $t->setlocale("id_ID");
				    $t->ShowRowNumber = true;				   
				    $t->ColAlign[1] = "CENTER";
				    $t->ColAlign[5] = "CENTER";
				    //$t->ColFormatMoney[2] = "%!+#2n";
				    //$t->ColFormatMoney[3] = "%!+#2n";
				    //$t->ColFormatMoney[4] = "%!+.2n";
				
				    $t->ColFormatNumber[2] = 0;
				    $t->ColFormatNumber[3] = 2;
				    $t->ColFormatNumber[4] = 2;
				
				    $t->ColHeader = array("NAMA BARANG", "SATUAN", "QTY","HARGA", "TOTAL","RINCIAN");
				    $t->ColFooter[2] =  number_format($d2->jum,0);
				    $t->ColFooter[4] =  number_format($d2->nilai,2,',','.');
					if (!$GLOBALS['print']){
						$t->RowsPerPage = 20;
						$t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=total&tc=view&v=<#5#>&ts_check_in1=$ts_check_in1&ts_check_in2=$ts_check_in2'>".icon("view","View")."</A>";
					}else {
						 $t->RowsPerPage = 30;
						$t->ColFormatHtml[5] = icon("view","View");
						$t->DisableNavButton = true;
						$t->DisableScrollBar = true;
					}
				    $t->execute();
				}
		}	
				
}elseif ($_GET['act'] ==  "detail"){
		if (!$GLOBALS['print']){
			title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > REKAM MEDIS PENGGUNAAN ALAT DAN OBAT");
		}else {
    		title_print("<img src='icon/medical-record.gif' align='absmiddle' > REKAM MEDIS PENGGUNAAN ALAT DAN OBAT");
		}
	 			 $sql = "select e.nama,e.mr_no,e.pangkat_gol,e.nrp_nip,e.kesatuan, sum(qty*harga) as total ".
						"from rs00008 a, rs00015 b ,rs00001 c, rs00006 d, rs00002 e  ".
						"where to_number(a.item_id::text, '999999999999'::text) = b.id ".
						"	and b.satuan_id = c.tc and c.tt='SAT' ".
						"	and a.no_reg = d.id and d.mr_no = e.mr_no ".
						"	and a.item_id not in ('-') and e.mr_no = '".$_GET['mr']."' ".
						"	group by e.nama,e.mr_no,e.pangkat_gol,e.nrp_nip,e.kesatuan "; 
					
				$r = pg_query($con,$sql );
				$d = pg_fetch_object($r);
			    pg_free_result($r);
			    	$mrno = $d->mr_no;

		if ($_GET['mr'] == $mrno){
			
		    $f= new ReadOnlyForm();
		    $f->text("<B>Nama</B>","<B>".$d->nama ."</B>");
		    $f->text("<B>MR.NO</B>","<B>".$d->mr_no ."</B>");
		    $f->text("Pangkat / Golongan",$d->pangkat_gol);
		    $f->text("NIP / NRP ",$d->nrp_nip);
		    $f->text("Kesatuan",$d->kesatuan);
		    $f->execute();
		    if (!$GLOBALS['print']){
				echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</DIV>";
		    }else {
		    	echo "<br>";
		    }
			echo "<DIV>";
				
			$t = new PgTable($con, "100%");
		    $t->SQL = "select a.no_reg, to_char(a.tanggal_trans,'dd Mon YYYY')as tgl_trans_str,b.obat,c.tdesc as satuan, qty as jum, a.harga, (qty*harga) as nilai ".
						"from rs00008 a, rs00015 b ,rs00001 c, rs00006 d, rs00002 e ".
						"where 	to_number(a.item_id::text, '999999999999'::text) = b.id  ".
						//	and a.trans_type='OB1'  ".
						"	and b.satuan_id = c.tc ".
						"	and c.tt='SAT' ".
						"	and a.no_reg = d.id ".
						"	and d.mr_no = e.mr_no ".
						"	and a.item_id not in ('-') ".
						"	and e.mr_no = '{$_GET['mr']}' "; 
		    $t->setlocale("id_ID");
		    $t->ColFormatNumber[4] = 0;
			$t->ColFormatNumber[5] = 2;
			$t->ColFormatNumber[6] = 2;
			//$t->ColFormatMoney[5] = "%!+#2n";
			//$t->ColFormatMoney[6] = "%!+.2n";	
		   	$t->ShowRowNumber = true;
		    $t->ColHeader = array("NO.REG","TGL.TRANSAKSI","OBAT","SATUAN","JUMLAH","HARGA","TOTAL");
		    $t->ColAlign = array("left","center","left","left","left","center","right","right");
			$t->ColFooter[6] =  number_format($d->total,2,',','.');
			if (!$GLOBALS['print']){
				$t->RowsPerPage = $ROWS_PER_PAGE;	
			}else {
				$t->RowsPerPage = 30;
				$t->DisableNavButton = true;
				$t->DisableScrollBar = true;
			}
			$t->execute();
			
			echo "</div>";
		}else {
			$r2 = pg_query($con,"select nama, mr_no from rs00002 where mr_no='".$_GET["mr"]."'");
				$d2 = pg_fetch_object($r2);
			    pg_free_result($r2);
			 
			$f= new ReadOnlyForm();
		    $f->text("<B>Nama</B>","<B>".$d2->nama ."</B>");
		    $f->text("<B>MR.NO</B>","<B>".$d2->mr_no ."</B>");
		    $f->execute();
		    
			echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</DIV>";
			echo "<br>";
			echo $d2->nama." Tidak menggunakan alat atau obat....";
		}
			
}else {
			
		if (!$GLOBALS['print']){
			title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > REKAM MEDIS PENGGUNAAN ALAT DAN OBAT");
			$ext = "OnChange = 'Form1.submit();'";
    	}else {
    		title_print("<img src='icon/medical-record.gif' align='absmiddle' > REKAM MEDIS PENGGUNAAN ALAT DAN OBAT");
    		$ext = "disabled";
    	}
	    //echo "<br>";
	    echo "<table width='100%'><tr><td width='50%' align='left'>";
	    $f = new Form($SC, "GET", "NAME=Form1");
	    $f->PgConn = $con;
	    $f->hidden("p", $PID);
	    
	    $f->selectArray("mBULAN","Bulan",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
	         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
			 "11"=>"November","12"=>"Desember"),$_GET["mBULAN"],$ext);       
		
	    $f->selectSQL("mTAHUN", "T a h u n",
	        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006"
	        , $_GET["mTAHUN"],$ext);
	        
		$f->execute();
	    
		$start_tgl = mktime(0,0,0,$_GET[mBULAN],1,$_GET[mTAHUN]);
	    $max_tgl = date("t", $start_tgl);
	    $end_tgl = mktime(0,0,0,$_GET[mBULAN],$max_tgl,$_GET[mTAHUN]);
	    $start_tgl = date("Y-m-d", $start_tgl);
	    $end_tgl = date("Y-m-d", $end_tgl);
			
				echo "</td><td width='50%' align='right' valign='middle'>";
					$f = new Form($SC, "GET","NAME=Form2");
				    $f->hidden("p", $PID);
				    if (!$GLOBALS['print']){
				    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
					}else { 
					   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
					}
				    $f->execute();
			    	if ($msg) errmsg("Error:", $msg);
				echo "</td></tr></table>";
				
		if (!$GLOBALS['print']) {
			echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&act=total'><U>Total Pemakaian Alat & Obat </U></DIV><br>";		
		}
				
		$tglhariini = date("Y-m-d", time());
			$SQL = "select a.mr_no,a.nama,to_char(a.tgl_reg,'dd-mm-yyyy') as tanggal_reg,a.pangkat_gol, ".
					"	a.nrp_nip,a.kesatuan, b.tdesc as type  ".
					"from rs00002 a ".
					"left join rs00001 b ON a.tipe_pasien = b.tc and b.tt='JEP' ";
					
			if ($_GET["search"]) {
				$SQLWHERE =
					"WHERE (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.mr_no like '%".$_GET["search"]."%' ".
					" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
					" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%') ";
			}elseif ($_GET["mBULAN"] || $_GET["mTAHUN"]) {
				$SQLWHERE = "where (a.tgl_reg >=  '$start_tgl' and a.tgl_reg <= '$end_tgl') ";
			}else {
				$SQLWHERE = "where a.tgl_reg = '$tglhariini' ";
			}
					//echo $SQL;			
			echo "<DIV >";
			echo "<br>";
			
				$t = new PgTable($con, "100%");
			  	$t->SQL = "$SQL $SQLWHERE" ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			    $t->ColHeader = array( "NO.MR", "NAMA","TGL PERIKSA","PANGKAT","NRP/NIP","KESATUAN","TYPE PASIEN");
			    $t->ColAlign = array("center","left","center","left","center","left","left");
			    if(!$GLOBALS['print']){
			    	$t->RowsPerPage = $ROWS_PER_PAGE;	
					$t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=detail&mr=<#0#>'><#1#></A>";
			    }else{
			    	$t->RowsPerPage = 30;
			    	$t->DisableNavButton = true;
				$t->DisableScrollBar = true;
				
			    }
				$t->execute();
				
			echo "</div>";
}	
}
?>