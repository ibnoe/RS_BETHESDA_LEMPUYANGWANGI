<?
//heri 30 august 2007
//udah di cek

$PID = "rm_inap";
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
					   "WHERE b.mr_no = '".$_GET["mr"]."' AND a.id_ri = 'H02' ";
					   
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
		
		include("rm_inap2.php");	
		
	}
//}
?>
