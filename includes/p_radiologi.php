<?php 	// Nugraha, Sun Apr 18 18:58:42 WIT 2004
      	// sfdn, 22-04-2004: hanya merubah beberapa title
      	// sfdn, 23-04-2004: tambah harga obat
      	// sfdn, 30-04-2004
      	// sfdn, 09-05-2004
      	// sfdn, 18-05-2004: age
      	// sfdn, 02-06-2004
      	// Nugraha, Sun Jun  6 18:14:41 WIT 2004 : Paket Transaksi
      	// sfdn, 24-12-2006 --> layanan hanya diberikan kpd. pasien yang blm. lunas
        // rs00006.is_bayar = 'N'
        // sfdn, 27-12-2006
        // Agung S. Menambahkan group by pada riwayat_klinik
		//Agung Sunandar 12:41 07/06/2012 menambahkan field yang kurang pada tab riwayat klinik
		// Agung Sunandar 13:23 07/06/2012 menambahkan operasi pada tab riwayat klinik

session_start();
$PID = "p_radiologi";
$SC = $_SERVER["SCRIPT_NAME"];
require_once("lib/visit_setting.php");
require_once("startup.php");

//--fungsi column color-------------- Agung Sunandar 22:58 26/06/2012
function color( $dstr, $r ) {

	    if($_GET['list2']=="tab1"){
	    	if ($dstr[8] == 'BELUM ADA TAGIHAN' ){
	    		return "<font color=red><b>{$dstr[$r]}</b></font>";
	    	}else{
	    		return "<font color=blue><b>{$dstr[$r]}</b></font>";
	    	}
	    }else{
	    	if ($dstr[7] == 'BELUM ADA TAGIHAN' ){
	    		return "<font color=red><b>{$dstr[$r]}</b></font>";
	    	}else{
	    		return "<font color=blue><b>{$dstr[$r]}</b></font>";
	    	}
	    }
}
//-------------------------------     

$_GET["mPOLI"]=$setting_poli["radiologi"];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];

// Tambahan BHP
$POLI=$setting_poli["radiologi"];
// ======================================

include ("session.php");
echo "<table border=0 width='100%'><tr><td>";



title_print("<img src='icon/radiologi-2.gif' align='absmiddle' > LAYANAN RADIOLOGI");
unset($_GET["layanan"]);

$reg = $_GET["rg"];
$reg2 = $_GET["rg"];//identifikasi rincian.php

include ("tab.php");

if ($reg > 0) {
	include ("keterangan");

    $total = 0.00;
    if (is_array($_SESSION["layanan"])) {
        foreach($_SESSION["layanan"] as $k => $l) {
            $total += $l["total"];
        }
    }

    //echo "<div class=box>";
if ($_GET["sub"] == "byr") {
        title("Total Tagihan: Rp. ".number_format($total,2));
        echo "<br><br><br>";
        $f = new Form("actions/p_radiologi.insert.php");
        $f->hidden("rg",$_GET["rg"]);
        $f->hidden("mr",$_GET["mr"]);
        $f->hidden("poli",$_GET["poli"]);
	$f->hidden("sub",$_GET["sub"]);
        $f->hidden("byr",$total);
        //$f->text("byr","Jumlah Pembayaran",15,15,$total,"STYLE='text-align:right'");
        $f->submit(" Simpan &amp; Bayar ");
        $f->execute();
    
} elseif ($_GET["list"] == "icd") {  // -------- ICD
		if(!$GLOBALS['print']){
		$T->show(2);
		}else{}
		
        include ("icd.php");
	
        include("rincian3.php");
        
    }
 elseif ($_GET["list"] == "icd9") {  // -------- ICD9
		if(!$GLOBALS['print']){
		$T->show(3);
		}else{}
		
        include ("icd9.php");
	
        include("rincian3.php");
        
    }
elseif ($_GET["list"] == "layanan") { // ----------------------------- LAYANAN MEDIS
    	if(!$GLOBALS['print']){
    	$T->show(1);
    	}else{}
		
        //-------------------------------------------------------------------------------------------
		//start check status bayar pasien
		$StatusBayar = getFromTable("select statusbayar from rsv0012 where id = '".$_GET["rg"]."'");
		$StatusTagih = getFromTable("select tagih from rsv0012 where id = '".$_GET["rg"]."'");
		$StatusInap = getFromTable("select rawat_inap from rs00006 where id = '".$_GET["rg"]."'");
		if($StatusInap == 'I'){
			$StatusCHK = getFromTable("select count(trans_types) from rs00010 where no_reg = '".$_GET["rg"]."' and trans_types='CHK'");
		}else{
			$StatusCHK = 1;
		}
		$cekKonsul = getFromTable("select max(tanggal_konsul) from c_visit where no_reg = '".$_GET["rg"]."' and id_konsul='".$_GET["mPOLI"]."'");
		//var_dump($StatusInap);
		//end check status bayar pasien
		
		if(($StatusBayar=='LUNAS')&&($StatusTagih > 0) && $StatusCHK!=0 && $cekKonsul!=date('Y-m-d')){
			echo "<div align=center><br>";
			echo "<font color=red size=3><blink>PERINGATAN </blink>!</font><br>";
			echo "<font color=red size=3>TAGIHAN PASIEN <font color=navy><blink>".$StatusBayar."</blink></font>, INPUT LAYANAN CLOSED<br>";
			echo "</div>";
		}else{	
			include ("pelayanan.php");
        }
		//-------------------------------------------------------------------------------------------
        
		include("rincian3.php");        
    } elseif($_GET["list"] == "riwayat") {
    	if(!$GLOBALS['print']){
		$T->show(4);
	}else{}
    	if ($_GET["act"] == "detail") {
               
                                		
            $sql = "select a.*,(b.nama)as periksa,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,f.layanan,(g.nama)as pengirim,(h.nama)as operator,i.nama as kamar, j.nama as admin
						from c_visit a 
						left join rs00017 b on a.id_dokter = B.ID 
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00034 f on f.id::text = e.item_id
						left join rs00017 g on g.id = a.id_perawat
                                                left join rs00017 h on h.id = a.id_perawat1
                                                LEFT JOIN RS00017 i ON A.ID_PERAWAT2 = i.id
                                                LEFT JOIN RS00017 j ON A.ID_PERAWAT3 = j.id	
						where a.no_reg='{$_GET['rg']}' and a.oid='{$_GET['oid']}' ";
				$r = pg_query($con,$sql);
				$n = pg_num_rows($r);
			    if($n > 0) $d = pg_fetch_array($r);
			    pg_free_result($r);
				//echo $sql;exit;			
			    $_GET['id'] = $_GET['rg'] ;	
	 			
			echo"<div class=box>";
			echo "<table width='100%' border='0'><tr><td colspan='2'>";
			echo"<div class=form_subtitle>PEMERIKSAAN PASIEN</div>";
			echo "</td></tr>";
    		echo "<tr><td valign=top>";
    		$f = new ReadOnlyForm();
			$f->text("Tanggal Pemeriksaan","<b>".$d["tanggal_reg"]);
			$f->text($visit_radiologi["vis_1"],$d[3] );
			$f->text($visit_radiologi["vis_2"],$d[4]);
			$f->text($visit_radiologi["vis_7"],$d[9]);
			$f->text($visit_radiologi["vis_8"],$d[10]);
			$f->text($visit_radiologi["vis_3"],$d[5]);
			$f->text($visit_radiologi["vis_4"],$d[6]);
			$f->text($visit_radiologi["vis_5"],$d[7]);
			$f->title1("<U>UKURAN FILM</U>");
			$f->text($visit_radiologi["vis_9"],$d[11]);
			$f->text($visit_radiologi["vis_10"],$d[12]);
			$f->text($visit_radiologi["vis_11"],$d[13]);
			$f->text($visit_radiologi["vis_12"],$d[14]);
			$f->text($visit_radiologi["vis_13"],$d[15]);
			$f->text($visit_radiologi["vis_14"],$d[16]);
			$f->text($visit_radiologi["vis_15"],$d[17]);
			$f->text($visit_radiologi["vis_16"],$d[18]);
			$f->title1("<U>KONTRAST</U>");
			$f->text($visit_radiologi["vis_17"],$d[19]);
			$f->text($visit_radiologi["vis_18"],$d[20]);
			$f->title1("<U>FAKTOR EKSPASI</U>");
			$f->text($visit_radiologi["vis_19"],$d[21]);
			$f->text($visit_radiologi["vis_20"],$d[22]);
			$f->text($visit_radiologi["vis_21"],$d[23]);
			$f->text("<b>Dokter Pemeriksa</b>",$d["periksa"]);
			$f->text("<b>Dokter Pengirim</b>",$d["pengirim"]);
            $f->text("<b>Petugas Kamar Gelap",$d["kamar"]);
            $f->text("<b>Radiografer</b>",$d["operator"]);
            $f->text("<b>Admin</b>",$d["admin"]);
			$f->execute();
    		echo "</td></tr>";
  			echo "<tr><td colspan='3'>";
  			echo "<br>";
  			include(rm_tindakan3);
  			echo "</td><td>";
  			echo "</td></tr></table>";

			
			}else {
				echo"<div align=center class=form_subtitle1>RIWAYAT PENYAKIT PASIEN</div>";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
				$sql = "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,A.VIS_3,A.oid ". 
					   "FROM C_VISIT A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   "WHERE  A.user_id != '' and B.MR_NO = '".$_GET["mr"]."' AND A.ID_POLI = '{$_GET["mPOLI"]}' ";
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	//$t->ColHidden[4]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("NO REGISTRASI","TANGGAL PEMERIKSAAN","WAKTU KUNJUNGAN","NO FOTO","DETAIL");
			   	$t->ColAlign = array("center","center","center","center","center");
				$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat&act=detail&mr=".$_GET["mr"]."&rg=<#0#>&oid=<#4#>'>".icon("view","View")."</A>";	
				$t->execute();
				
				echo"<br>";
         		echo"</div>";
				echo "</td></tr></table></div>";
    	
			}
}elseif($_GET["list"] == "riwayat_klinik") {
    	if(!$GLOBALS['print']){
		$T->show(5);
	}else{}
	
	include ("riwayat_klinik.php");
	
}elseif ($_GET["list"] == "unit_rujukan"){
    	$T->show(6);
		$StatusBayar = getFromTable("select statusbayar from rsv0012 where id = '".$_GET["rg"]."'");
		$StatusTagih = getFromTable("select tagih from rsv0012 where id = '".$_GET["rg"]."'");
		$StatusInap = getFromTable("select rawat_inap from rs00006 where id = '".$_GET["rg"]."'");
		if($StatusInap == 'I'){
			$StatusCHK = getFromTable("select count(trans_types) from rs00010 where no_reg = '".$_GET["rg"]."' and trans_types='CHK'");
		}else{
			$StatusCHK = 1;
		}
		$cekKonsul = getFromTable("select max(tanggal_konsul) from c_visit where no_reg = '".$_GET["rg"]."' and id_konsul='".$_GET["mPOLI"]."'");
		//var_dump($StatusInap);
		//end check status bayar pasien
    	if(($StatusBayar=='LUNAS')&&($StatusTagih > 0) && $StatusCHK!=0 && $cekKonsul!=date('Y-m-d')){
			echo "<div align=center><br>";
			echo "<font color=red size=3><blink>PERINGATAN </blink>!</font><br>";
			echo "<font color=red size=3>TAGIHAN PASIEN <font color=navy><blink>".$StatusBayar."</blink></font>, INPUT LAYANAN CLOSED<br>";
			echo "</div>";
		}else{
    
	include ("unit_rujukan.php");
	}
				        	
}elseif ($_GET["list"] == "konsultasi"){
    	$T->show(7);
    	echo"<br>";
    	
	include ("p_radiologi.konsultasi.php");
		
}elseif ($_GET["list"] == "resepobat"){ //RESEP OBAT
    	$T->show(8);
    	//echo"<br>";
    	
 include ("resep_obat.php");
 
 include("rincianobat.php"); 
    }else {       //pemeriksaan
    	if(!$GLOBALS['print']){
		$T->show(0);
	}else{}


 	if ($_GET['act'] == "edit"){
	$sql2 =	"SELECT A.*,(B.NAMA)as pemeriksa,(C.NAMA)AS pengirim,(F.NAMA)AS operator,g.nama as kamar, h.nama as admin FROM C_VISIT A 
    				LEFT JOIN RS00017 B ON A.ID_DOKTER = B.ID
    				LEFT JOIN RS00017 C ON A.ID_PERAWAT = C.ID
					LEFT JOIN RS00017 F ON A.ID_PERAWAT1 = F.ID
					LEFT JOIN RS00017 g ON A.ID_PERAWAT2 = g.ID
					LEFT JOIN RS00017 h ON A.ID_PERAWAT3 = h.ID
    				WHERE A.ID_POLI={$_GET["poli"]} AND A.NO_REG='$rg' AND A.OID='".$_GET["oid"]."'"; 
	
    	$r=pg_query($con,$sql2);
    	$n = pg_num_rows($r);
    	
	    if($n > 0) $d2 = pg_fetch_array($r);
	    pg_free_result($r);
    		//-------------------------tambah for update------hery 08072007
	}		   
			?>


<script>
function cetaksurat(tag) {
    sWin = window.open('includes/cetak.radiologi.php?rg=' + tag+'', 'xWin', 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');
    sWin.focus();
}
</script>

<?php
    //echo "\n<script language='JavaScript'>\n";
    //echo "function cetaksurat(tag) {\n";
    //echo "    sWin = window.open('includes/cetak.radiologi.php?rg=" . $_GET["rg"] . "', 'xWin'," .
    //" 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');\n";
    //echo "    sWin.focus();\n";
    //echo "}\n";
    //echo "</script>\n";

?>

<table width="10%" border="0">
	<tr>
		<td width="10%" align="center"><? echo "<div align=center><input type=button value=' Tambah ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&poli={$_GET["poli"]}&act=new';\">\n"; ?> </a></td>
    </tr>
	<tr>
        <td align="center"> &nbsp;</td>
    </tr>
</table>
			<?
	echo "<font color='#000000' size='2'><b> Data Pemeriksaan Pasien</b></font>";
	echo "<br>";echo "<br>";
	
	$sql1 =	"SELECT distinct A.*,(B.NAMA)as pemeriksa,(C.NAMA)AS pengirim,(F.NAMA)AS operator,g.nama as kamar, h.nama as admin,A.oid as id 
			FROM C_VISIT A 
    				LEFT JOIN RS00017 B ON A.ID_DOKTER = B.ID
    				LEFT JOIN RS00017 C ON A.ID_PERAWAT = C.ID
					LEFT JOIN RS00017 F ON A.ID_PERAWAT1 = F.ID
					LEFT JOIN RS00017 g ON A.ID_PERAWAT2 = g.ID
					LEFT JOIN RS00017 h ON A.ID_PERAWAT3 = h.ID
    				WHERE  A.user_id != '' and A.ID_POLI='204' AND A.NO_REG='$rg' "; 
	
	@$r1 = pg_query($con,$sql1);
	@$n1 = pg_num_rows($r1);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
	
		
	?>
	<table width='100%'>
		<tr>
			<td width='5%' class='TBL_HEAD' align='center'>No</td>
			<td width='8%' class='TBL_HEAD' align='center'>Tanggal</td>
			<td width='20%' class='TBL_HEAD' align='center'>Jenis Pemeriksaan</td>
			<td width='20%' class='TBL_HEAD' align='center'>Diagnosa</td>
			<td width='20%' class='TBL_HEAD' align='center'>Hasil Bacaan</td>
			<td width='5%' class='TBL_HEAD' align='center'>Edit</td>
			<td width='5%' class='TBL_HEAD' align='center'>Print</td>
		</tr>
		
		<?	
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$no=$i 	
					?>		
				 	<tr valign="top" class="<? ?>" > 
					<td width='5%' class='TBL_BODY' align='center'><?=$no ?> </td>
					<td width='8%' class='TBL_BODY' align='left'><?=$row1["tanggal_reg"] ?> </td>
					<td width='20%' class='TBL_BODY' align='left'><?=$row1["vis_1"] ?> </td>
					<td width='20%' class='TBL_BODY' align='left'><?=$row1["vis_2"] ?> </td>
					<td width='20%' class='TBL_BODY' align='left'><?=$row1["vis_7"] ?> </td>
					<td width='5%' class='TBL_BODY' align='center'><? echo "<div align=center><input type=button value=' &nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&poli={$_GET["poli"]}&oid=".$row1[id]."&act=edit';\">\n"; ?> </td>
					<td width='5%' class='TBL_BODY' align='center'><a href="javascript: cetaksurat(<?=$row1[id]?>)" ><img src="images/cetak.gif" border="0"></a> </td>
					</tr>	

					<?					
					;$j++;					
				}
				$i++;
					
			} 
			?>
			
	</table>
	<script type="text/javascript" src="./lib/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	tinymce.init({
	  selector:"#f_vis_7",
	  toolbar : false,
	  menubar : false
	});
	</script>
	<?
	echo "<br>";echo "<br>";
	if ($_GET['act'] == "edit" or $_GET['act'] == "new" ){
	if ($_GET['act'] == "edit"){
					echo "<font color='#000000' size='2'> >>Edit Pemeriksaan Pasien</font>";
					echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
					$f = new Form("actions/p_radiologi.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","edit");
					$f->hidden("f_no_reg",$d2["no_reg"]);
                    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
					$f->hidden("list","pemeriksaan");
					$f->hidden("mr",$_GET["mr"]);
					$f->hidden("f_id_poli",$_GET["poli"]);
					$f->hidden("f_user_id",$_SESSION[uid]);
					

			} elseif ($_GET['act'] == "new"){
				$ext = "";
				
				echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
					$f = new Form("actions/p_radiologi.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
                    $f->hidden("f_no_reg",$d->id);
					$f->hidden("list","pemeriksaan");
                    $f->hidden("mr",$_GET["mr"]);
				   	$f->hidden("f_id_poli",$_GET["poli"]);
                    $f->hidden("f_user_id",$_SESSION[uid]);
					
			}else {
				
				 if($n > 0){
					$ext= "disabled";
				}else { 
					$ext = "";
				}
			//---------------------------------------------------------------------------------			
					    	
					echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
					$f = new Form("actions/p_radiologi.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
                    $f->hidden("f_no_reg",$d->id);
					$f->hidden("list","pemeriksaan");
                    $f->hidden("mr",$_GET["mr"]);
				   	$f->hidden("f_id_poli",$_GET["poli"]);
                    $f->hidden("f_user_id",$_SESSION[uid]);
			}
			
			if (isset($_SESSION["SELECT_EMP"])) {
    				$_SESSION["DOKTER"]["id"] = $_SESSION["SELECT_EMP"];
    				$_SESSION["DOKTER"]["nama"] =
        			getFromTable(
			        "select nama from rs00017 where id = '".$_SESSION["DOKTER"]["id"]."'");
    				$f->textAndButton3("f_id_dokter","Dokter Pemeriksa (Radiolog)",2,10,$_SESSION["DOKTER"]["id"],$ext,"nm2",30,70,$_SESSION["DOKTER"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");
            }elseif ($d2["id_dokter"] != '') {
					$f->textAndButton3("f_id_dokter","Dokter Pemeriksa (Radiolog)",2,10,$d2["id_dokter"],$ext,"nm2",30,70,$d2["pemeriksa"],$ext,"...",$ext,"OnClick='selectPegawai();';");
			}else{
					$f->textAndButton3("f_id_dokter","Dokter Pemeriksa (Radiolog)",2,10,0,$ext,"nm2",30,70,$d2["pemeriksa"],$ext,"...",$ext,"OnClick='selectPegawai();';");
			}
                                        
			if (isset($_SESSION["SELECT_EMP2"])) {
    				$_SESSION["DOKTER"]["id2"] = $_SESSION["SELECT_EMP2"];
    				$_SESSION["DOKTER"]["nama2"] =
        			getFromTable(
			        "select nama from rs00017 where id = '".$_SESSION["DOKTER"]["id2"]."'");
    				$f->textAndButton3("f_id_perawat","Dokter Pengirim",2,10,$_SESSION["DOKTER"]["id2"],$ext,"nm3",30,70,$_SESSION["DOKTER"]["nama2"],$ext,"...",$ext,"OnClick='selectPegawai2();';");
            }elseif ($d2["id_perawat"] != '') {
						$f->textAndButton3("f_id_perawat","Dokter Pengirim",2,10,$d2["id_perawat"],$ext,"nm2",30,70,$d2["pengirim"],$ext,"...",$ext,"OnClick='selectPegawai2();';");
			}else{
						$f->textAndButton3("f_id_perawat","Dokter Pengirim",2,10,0,$ext,"nm2",30,70,$d2["pengirim"],$ext,"...",$ext,"OnClick='selectPegawai2();';");
			}

			if (isset($_SESSION["SELECT_EMP3"])) {
    					$_SESSION["DOKTER"]["id3"] = $_SESSION["SELECT_EMP3"];
    					$_SESSION["DOKTER"]["nama3"] =
        				getFromTable(
			            "select nama from rs00017 where id = '".$_SESSION["DOKTER"]["id3"]."'");
    					$f->textAndButton3("f_id_perawat1","Radiografer",2,10,$_SESSION["DOKTER"]["id3"],$ext,"nm4",30,70,$_SESSION["DOKTER"]["nama3"],$ext,"...",$ext,"OnClick='selectPegawai3();';");
                                        }elseif ($d2["id_perawat1"] != '') {
						$f->textAndButton3("f_id_perawat1","Radiografer",2,10,$d2["id_perawat1"],$ext,"nm2",30,70,$d2["operator"],$ext,"...",$ext,"OnClick='selectPegawai3();';");
					}else{
						$f->textAndButton3("f_id_perawat1","Radiografer",2,10,0,$ext,"nm2",30,70,$d2["operator"],$ext,"...",$ext,"OnClick='selectPegawai3();';");
					}

			if (isset($_SESSION["SELECT_EMP4"])) {
    					$_SESSION["DOKTER"]["id4"] = $_SESSION["SELECT_EMP4"];
    					$_SESSION["DOKTER"]["nama4"] =
        				getFromTable(
			            "select nama from rs00017 where id = '".$_SESSION["DOKTER"]["id4"]."'");
    					$f->textAndButton3("f_id_perawat2","Petugas Kamar Gelap",2,10,$_SESSION["DOKTER"]["id4"],$ext,"nm4",30,70,$_SESSION["DOKTER"]["nama4"],$ext,"...",$ext,"OnClick='selectPegawai4();';");
                                        }elseif ($d2["id_perawat2"] != '') {
						$f->textAndButton3("f_id_perawat2","Petugas Kamar Gelap",2,10,$d2["id_perawat2"],$ext,"nm4",30,70,$d2["kamar"],$ext,"...",$ext,"OnClick='selectPegawai4();';");
					}else{
						$f->textAndButton3("f_id_perawat2","Petugas Kamar Gelap",2,10,0,$ext,"nm2",30,70,$d2["kamar"],$ext,"...",$ext,"OnClick='selectPegawai4();';");
					}
                         if (isset($_SESSION["SELECT_EMP5"])) {
    					$_SESSION["DOKTER"]["id5"] = $_SESSION["SELECT_EMP5"];
    					$_SESSION["DOKTER"]["nama5"] =
        				getFromTable(
			            "select nama from rs00017 where id = '".$_SESSION["DOKTER"]["id5"]."'");
    					$f->textAndButton3("f_id_perawat3","Admin",2,10,$_SESSION["DOKTER"]["id5"],$ext,"nm5",30,70,$_SESSION["DOKTER"]["nama5"],$ext,"...",$ext,"OnClick='selectPegawai5();';");
                                        }elseif ($d2["id_perawat3"] != '') {
						$f->textAndButton3("f_id_perawat3","Admin",2,10,$d2["id_perawat3"],$ext,"nm5",30,70,$d2["admin"],$ext,"...",$ext,"OnClick='selectPegawai5();';");
					}else{
						$f->textAndButton3("f_id_perawat3","Admin",2,10,0,$ext,"nm5",30,70,$d2["admin"],$ext,"...",$ext,"OnClick='selectPegawai5();';");
					}
                                        
			$f->textarea("f_vis_1",$visit_radiologi["vis_1"] ,3, $visit_radiologi["vis_1"."W"],ucfirst($d2["vis_1"]),$ext);
			$f->textarea("f_vis_2",$visit_radiologi["vis_2"] ,3, $visit_radiologi["vis_2"."W"],ucfirst($d2["vis_2"]),$ext);
			$f->textarea("f_vis_7",$visit_radiologi["vis_7"] ,3, $visit_radiologi["vis_7"."W"],ucfirst($d2["vis_7"]),"id='f_vis_7'");
			$f->textarea("f_vis_8",$visit_radiologi["vis_8"] ,3, $visit_radiologi["vis_8"."W"],ucfirst($d2["vis_8"]),$ext);
			$f->text("f_vis_3",$visit_radiologi["vis_3"],10,10,ucfirst($d2["vis_3"]),$ext);
			$f->calendar("f_vis_4","Tanggal",15,15,$d2["vis_4"],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
			$f->text("f_vis_5",$visit_radiologi["vis_5"],50,50,ucfirst($d2["vis_5"]),$ext);
			$f->subtitle ("USG");
			$f->textarea("f_vis_22",$visit_radiologi["vis_22"] ,3, $visit_radiologi["vis_22"."W"],ucfirst($d2["vis_22"]),$ext);
			$f->textarea("f_vis_23",$visit_radiologi["vis_23"] ,3, $visit_radiologi["vis_23"."W"],ucfirst($d2["vis_23"]),$ext);
			$f->textarea("f_vis_24",$visit_radiologi["vis_24"] ,3, $visit_radiologi["vis_24"."W"],ucfirst($d2["vis_24"]),$ext);
			$f->textarea("f_vis_25",$visit_radiologi["vis_25"] ,3, $visit_radiologi["vis_25"."W"],ucfirst($d2["vis_25"]),$ext);
			$f->textarea("f_vis_26",$visit_radiologi["vis_26"] ,3, $visit_radiologi["vis_26"."W"],ucfirst($d2["vis_26"]),$ext);
			$f->textarea("f_vis_27",$visit_radiologi["vis_27"] ,3, $visit_radiologi["vis_27"."W"],ucfirst($d2["vis_27"]),$ext);
			$f->subtitle ("Ukuran Film");
			$f->textinfo("f_vis_9",$visit_radiologi["vis_9"],1,50,ucfirst($d2["vis_9"]),"Lembar",$ext);
			$f->textinfo("f_vis_10",$visit_radiologi["vis_10"],1,50,ucfirst($d2["vis_10"]),"Lembar",$ext);
			$f->textinfo("f_vis_11",$visit_radiologi["vis_11"],1,50,ucfirst($d2["vis_11"]),"Lembar",$ext);
			$f->textinfo("f_vis_12",$visit_radiologi["vis_12"],1,50,ucfirst($d2["vis_12"]),"Lembar",$ext);
			$f->textinfo("f_vis_13",$visit_radiologi["vis_13"],1,50,ucfirst($d2["vis_13"]),"Lembar",$ext);
			$f->textinfo("f_vis_14",$visit_radiologi["vis_14"],1,50,ucfirst($d2["vis_14"]),"Lembar",$ext);
			$f->textinfo("f_vis_15",$visit_radiologi["vis_15"],1,50,ucfirst($d2["vis_15"]),"Lembar",$ext);
			$f->textinfo("f_vis_16",$visit_radiologi["vis_16"],1,50,ucfirst($d2["vis_16"]),"Lembar",$ext);
			$f->subtitle("Kontrast");
			$f->text("f_vis_17",$visit_radiologi["vis_17"],1,50,ucfirst($d2["vis_17"]),$ext);
			$f->text("f_vis_18",$visit_radiologi["vis_18"],1,50,ucfirst($d2["vis_18"]),$ext);
                        $f->subtitle ("Faktor Ekspasi");
                        $f->textinfo("f_vis_19",$visit_radiologi["vis_19"],10,50,ucfirst($d2["vis_19"]),"KV",$ext);
			$f->textinfo("f_vis_20",$visit_radiologi["vis_20"],10,50,ucfirst($d2["vis_20"]),"MA/S",$ext);
			$f->textinfo("f_vis_21",$visit_radiologi["vis_21"],10,50,ucfirst($d2["vis_21"]),"Jarak",$ext);
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			
			$f->execute();
			echo"</div>";
}
}
    
    //pemeriksaan
    
    echo "</div>";
    
	    echo "\n<script language='JavaScript'>\n";
	    echo "function selectLayanan() {\n";
	   	echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai(tag) {\n";
        echo "    sWin = window.open('popup/pegawai.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "function selectPegawai2(tag) {\n";
        echo "    sWin = window.open('popup/pegawai2.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "function selectPegawai3(tag) {\n";
        echo "    sWin = window.open('popup/pegawai3.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "function selectPegawai4(tag) {\n";
        echo "    sWin = window.open('popup/pegawai4.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "function selectPegawai5(tag) {\n";
        echo "    sWin = window.open('popup/pegawai5.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
    if (empty($_GET[sub])) {
	    echo "function refreshSubmit() {\n";
	    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
	    echo "}\n";
	    echo "refreshSubmit();\n";
	    }
	    echo "</script>\n";
   		echo "\n<script language='JavaScript'>\n";
	    echo "function selectLayanan() {\n";
	   	echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai3(tag) {\n";
        echo "    sWin = window.open('popup/pegawai3.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        /* Untuk Layanan Paket             */
		/* Agung Sunandar 16:53 26/06/2012 */
	    echo "function selectLayanan2() {\n";
	   	echo "    sWin = window.open('popup/layanan_paket.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
	    echo "    sWin.focus();\n";
	    echo "}\n";
        echo "function selectPegawai2(tag) {\n";
        echo "    sWin = window.open('popup/pegawai2.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
    if (empty($_GET[sub])) {
	    echo "function refreshSubmit() {\n";
	    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
	    echo "}\n";
	    echo "refreshSubmit();\n";
	    }
	    echo "</script>\n";
} else {
	//update tab pasien App.25-11-07
	echo"<br>";
	$tab_disabled = array("tab1"=>true, "tab2"=>true);
	$T1 = new TabBar();
	$T1->addTab("$SC?p=$PID&list2=tab1&list=layanan", "Daftar Pasien Rujukan"	, $tab_disabled["tab1"]);
	$T1->addTab("$SC?p=$PID&list2=tab2&list=layanan", "Daftar Pasien Klinik"	, $tab_disabled["tab2"]);

    if($_GET['list2']=="tab1"){
    	$T1->show(0);
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->hidden("poli",$_GET["mPOLI"]);
    $f->hidden("list2",tab1);
   
   		echo "<div align='right' valign='middle'>";	
		$f = new Form($SC, "GET","NAME=Form4");
	    $f->hidden("p", $PID);
	    $f->hidden("list2","tab1");
	    if (!$GLOBALS['print']){
	    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form4.submit();'");
		}else { 
		   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
		}
	    $f->execute();
    	if ($msg) errmsg("Error:", $msg);
    	echo "</div>";
		//---------------------
		echo "<br>";
		
	$SQLSTR = 	"select distinct a.mr_no,a.id,upper(a.nama)as nama,tanggal(b.tanggal_konsul,0)||' '||to_char(b.waktu_konsul,'hh:mi:ss') as tgl,a.alm_tetap,a.tdesc,CASE WHEN a.rawat_inap='I' THEN 'RAWAT INAP'
                             WHEN a.rawat_inap='N' THEN 'INSTALASI GAWAT DARURAT'
			     ELSE c.tdesc end as rawatan,a.statusbayar
				from rsv_pasien4 a 
				left join c_visit b on b.no_reg = a.id
				left join c_visit_operasi d on d.no_reg = a.id
				left join rs00001 c on c.tc_poli = b.id_poli and c.tt='LYN' or c.tc_poli = d.id_poli and c.tt='LYN'
				WHERE (b.id_konsul='".$_GET["mPOLI"]."' or d.id_konsul='".$_GET["mPOLI"]."')";
		// 24-12-2006 --> tambahan 'where is_bayar = 'N'
		//status_akhir,rawatan di query sementara di tutup
        
$tglhariini = date("Y-m-d", time());
    if (strlen($_GET["mPOLI"]) > 0 ) {
		$SQLWHERE =
			"AND b.TANGGAL_KONSUL = '$tglhariini' AND".
			"	(UPPER(a.NAMA) LIKE '%".strtoupper($_GET["search"])."%') ";
	}

		/*$tglhariini = date("Y-m-d", time());
    if (strlen($_GET["mPOLI"]) > 0 ) {
		$SQLWHERE =
			"AND".
			"	(UPPER(a.NAMA) LIKE '%".strtoupper($_GET["search"])."%') ";
	}*/
	if ($_GET["search"]) {
		$SQLWHERE =
			"and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
					" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
					" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ) ";
	}
	if (!isset($_GET[sort])) {

           $_GET[sort] = "a.id";
           $_GET[order] = "asc";
	}
	$rstr=pg_query($con, "$SQLSTR $SQLWHERE ");
   // $n = pg_num_rows($rstr);		    	
	$dstr = pg_fetch_array($rstr); 
	   	$t = new PgTable($con, "100%");
	    $t->SQL = "$SQLSTR $SQLWHERE ";
	    $t->setlocale("id_ID");
	    $t->ShowRowNumber = true;
	    $t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT");	
	    $t->RowsPerPage = $ROWS_PER_PAGE;
	    $t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&list=layanan&sub2=nonpaket'><#2#>";
	    //(awal)$t->ColFormatHtml[8] = "<A HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&act=periksa'><INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Periksa' >";
	   	//$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL  REG","WAKTU REG","NAMA PASIEN","PANGKAT","NRP/NIP","KESATUAN","LOKET","TIPE PASIEN","STATUS");
	   	$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","WAKTU KONSUL","ALAMAT","TIPE PASIEN","UNIT ASAL","STATUS");
	    $t->ColColor[8] = "color";
	    //$t->ColRowSpan[2] = 2;
	    $t->execute();
	    echo"<br><div class=NOTE>Catatan : Daftar pasien diurut berdasarkan no antrian</div><br>";	
    }else{
    	$T1->show(1);	
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->hidden("poli",$_GET["mPOLI"]);
   
   		echo "<div align='right' valign='middle'>";	
		$f = new Form($SC, "GET","NAME=Form2");
	    $f->hidden("p", $PID);
	    $f->hidden("list2","tab2");
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
		
	$SQLSTR = 	"select distinct a.mr_no,a.id,upper(a.nama)as nama,tanggal(a.tanggal_reg,0)||' '||to_char(waktu_reg,'hh:mi:ss') as tgl,a.alm_tetap,a.tdesc,a.statusbayar
				from rsv_pasien4 a 
				left join c_visit b on b.no_reg = a.id
				WHERE a.poli='".$_GET["mPOLI"]."'";
		// 24-12-2006 --> tambahan 'where is_bayar = 'N'
		//status_akhir,rawatan di query sementara di tutup
        
		$tglhariini = date("Y-m-d", time());
    if (strlen($_GET["mPOLI"]) > 0 ) {
		$SQLWHERE =
			"AND a.TANGGAL_REG = '$tglhariini' AND".
			"	(UPPER(a.NAMA) LIKE '%".strtoupper($_GET["search"])."%') ";
	}
	if ($_GET["search"]) {
		$SQLWHERE =
			"and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
					" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
					" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ) ";
	}
	if (!isset($_GET[sort])) {

           $_GET[sort] = "a.id";
           $_GET[order] = "asc";
	}
	$rstr=pg_query($con, "$SQLSTR $SQLWHERE ");
   // $n = pg_num_rows($rstr);		    	
	$dstr = pg_fetch_array($rstr); 
	   	$t = new PgTable($con, "100%");
	    $t->SQL = "$SQLSTR $SQLWHERE ";
	    $t->setlocale("id_ID");
	    $t->ShowRowNumber = true;
	    $t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT");	
	    $t->RowsPerPage = $ROWS_PER_PAGE;
	    $t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&list=layanan&sub2=nonpaket'><#2#>";
	    //(awal)$t->ColFormatHtml[8] = "<A HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&act=periksa'><INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Periksa' >";
	   	//$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL  REG","WAKTU REG","NAMA PASIEN","PANGKAT","NRP/NIP","KESATUAN","LOKET","TIPE PASIEN","STATUS");
	   	$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","WAKTU REGISTRASI","ALAMAT","TIPE PASIEN","STATUS");
	    $t->ColColor[7] = "color";
	    //$t->ColRowSpan[2] = 2;
	    $t->execute();
	    echo"<br><div class=NOTE>Catatan : Daftar pasien di urut berdasarkan no antrian</div><br>";
    }
	
}
  
?>
