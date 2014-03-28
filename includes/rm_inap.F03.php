<?
//heri 26 august 2007
//status udah di cek

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["catatan_harian_penyakit"];

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
			$f->text("DD/Diagnosis",$d2->diagnosa_sementara);
		    $f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='23%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM","<b>". $d2->mr_no."</b>");
			$f->text("Tgl Masuk",$d2->tanggal_reg);
		    $f->text("Seks",$d2->jenis_kelamin);
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='43%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Pangkat / NRP", $d2->pangkat_gol." ".$d2->nrp_nip );
		    $f->text("Kesatuan",$d2->kesatuan);
		    $f->text("Ruang ",$bangsal);
		    $f->execute();
		    echo "</td></tr></table><br>"; 
		   
		    $sql = "SELECT A.VIS_6|| ' / ' ||A.VIS_1 as tgl_jam,A.VIS_2,A.VIS_3 ,C.NAMA ,A.VIS_4 ". 
					   "FROM C_VISIT_RI A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   "LEFT JOIN RS00017 C ON CAST (A.VIS_5 AS INTEGER) = C.ID ".
					   "WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_RI = '{$_GET["mPOLI"]}' ";
					   
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			    //$t->ColRowSpan[]
			    $t->ColHeader = array("TGL / JAM","CATATAN DOKTER","INSTRUKSI/MEDIKASI","DOKTER","PENERIMA INSTRUKSI");
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
}
?>
