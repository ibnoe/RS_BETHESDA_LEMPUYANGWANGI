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
$PID = "p_obsteteri";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/visit_setting.php");

//--fungsi column color-------------- Agung Sunandar 22:58 26/06/2012
function color( $dstr, $r ) {
	    if($_GET['list2']=="tab1"){
	    	if ($dstr[7] == 'BELUM ADA TAGIHAN' ){
	    		return "<font color=red><b>{$dstr[$r]}</b></font>";
	    	}else{
	    		return "<font color=blue><b>{$dstr[$r]}</b></font>";
	    	}
	    }else{
	    	if ($dstr[6] == 'BELUM ADA TAGIHAN' ){
	    		return "<font color=red><b>{$dstr[$r]}</b></font>";
	    	}else{
	    		return "<font color=blue><b>{$dstr[$r]}</b></font>";
	    	}
	    }
}
//-------------------------------
$_GET["mPOLI"]=$setting_poli["kebidanan_obstetri"];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];
// Tambahan BHP
//$POLI=$setting_poli["kebidanan_obstetri"];
// ======================================

include ("session.php");


unset($_GET["layanan"]);

$reg = $_GET["rg"];
$reg2 = $_GET["rg"];
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle'> KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (OBSTETRI)");
title_excel("p_obsteteri&list=".$_GET['list']."&rg=".$_GET['rg']."&poli=".$_GET['poli']."&mr=".$_GET['mr']."&sub=".$_GET['sub']."&act=".$_GET['act']."&polinya=".$_GET['polinya']."&tblstart=".$_GET['tblstart']."");

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
        $f = new Form("actions/p_obsteteri.insert.php");
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
		$PID = 'p_obsteteri';
        include ("icd.php");
	
        include("rincian3.php");
} elseif ($_GET["list"] == "icd9") {  // -------- ICD
		if(!$GLOBALS['print']){
		$T->show(3);
		}else{}
		$PID = 'p_obsteteri';
        include ("icd9.php");
	
        include("rincian3.php");
        
    }elseif ($_GET["list"] == "layanan") { // ----------------------------- LAYANAN MEDIS
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
				$sql = "select a.*,b.nama,h.nama as perawat,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,f.layanan 
						from c_visit a 
						left join rs00017 b on a.id_dokter = b.id
						left join rs00017 h on a.id_perawat = h.id
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						--left join rs00034 f on f.id = trim(e.item_id,0)
						left join rs00034 f on 'f.id' = e.item_id
						where a.no_reg='{$_GET['rg']}' ";
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
			$f->title1("<U>RIWAYAT PASIEN</U>","LEFT");
			$f->text($visit_obsteteri["vis_1"],$d[3] );
			$f->text($visit_obsteteri["vis_2"],$d[4]);
			$f->text($visit_obsteteri["vis_3"],$d[5]);
			$f->text($visit_obsteteri["vis_4"],$d[6]);
			$f->text($visit_obsteteri["vis_5"],$d[7]);
			$f->text($visit_obsteteri["vis_50"],$d[52]);
			$f->text($visit_obsteteri["vis_51"],$d[53] );
			$f->text($visit_obsteteri["vis_6"],$d[8]);
			$f->text($visit_obsteteri["vis_7"],$d[9]);
			$f->text($visit_obsteteri["vis_8"],$d[10]);
			$f->text($visit_obsteteri["vis_9"],$d[11]."&nbsp;Orang");
			$f->text($visit_obsteteri["vis_10"],$d[12]."&nbsp;Orang");
			$f->text($visit_obsteteri["vis_11"],$d[13]."&nbsp;Orang");
			$f->text($visit_obsteteri["vis_12"],$d[14]."&nbsp;Orang");
			$f->text($visit_obsteteri["vis_13"],$d[15]."&nbsp;Orang");
			$f->text($visit_obsteteri["vis_14"],$d[16]."&nbsp;Orang");
			$f->text($visit_obsteteri["vis_15"],$d[17]);
			$f->title1("<U>RIWAYAT KELAHIRAN</U>","LEFT");
			$f->text($visit_obsteteri["vis_16"],$d[18]);
			$f->text($visit_obsteteri["vis_17"],$d[19] );
			$f->text($visit_obsteteri["vis_18"],$d[20]);
			$f->text($visit_obsteteri["vis_19"],$d[21]);
			$f->text($visit_obsteteri["vis_20"],$d[22]."&nbsp;Gram");
			$f->text($visit_obsteteri["vis_21"],$d[23]."&nbsp;Cm");
			$f->title1("<U>PERTUMBUHAN DAN PERKEMBANGAN</U>","LEFT");
			$f->text($visit_obsteteri["vis_22"],$d[24]);
			$f->text($visit_obsteteri["vis_23"],$d[25]);
			$f->title1("<U>IMUNISASI</U>","LEFT");
			$f->text($visit_obsteteri["vis_24"],$d[26]);
			$f->text($visit_obsteteri["vis_25"],$d[27] );
			
			$f->execute();
			echo "</td><td valign=top>";
    		$f = new ReadOnlyForm();
			$f->text($visit_obsteteri["vis_26"],$d[28]);
			$f->text($visit_obsteteri["vis_27"],$d[29]);
			$f->text($visit_obsteteri["vis_28"],$d[30]);
			$f->text($visit_obsteteri["vis_29"],$d[31]);
			$f->title1("<U>PENYAKIT TERDAHULU</U>","LEFT");
			$f->text($visit_obsteteri["vis_30"],$d[32]);
			$f->text($visit_obsteteri["vis_31"],$d[33]);
			$f->text($visit_obsteteri["vis_32"],$d[34]);
			$f->text($visit_obsteteri["vis_33"],$d[35] );
			$f->text($visit_obsteteri["vis_34"],$d[36]);
			$f->text($visit_obsteteri["vis_35"],$d[37]);
			$f->text($visit_obsteteri["vis_36"],$d[38]);
			$f->text($visit_obsteteri["vis_37"],$d[39]);
			$f->title1("<U>RIWAYAT OBSTERIK</U>","LEFT");
			$f->text($visit_obsteteri["vis_38"],$d[40]);
			$f->text($visit_obsteteri["vis_39"],$d[41]);
			$f->title1("<U>PEMERIKSAAN UMUM</U>","LEFT");
			$f->text($visit_obsteteri["vis_40"],$d[42]);
			$f->text($visit_obsteteri["vis_41"],$d[43] );
			$f->text($visit_obsteteri["vis_42"],$d[44]);
			$f->text($visit_obsteteri["vis_43"],$d[45]);
			$f->text($visit_obsteteri["vis_44"],$d[46]);
			$f->text($visit_obsteteri["vis_45"],$d[47]);
			$f->text($visit_obsteteri["vis_46"],$d[48]);
			$f->text($visit_obsteteri["vis_47"],$d[49]);
			$f->text($visit_obsteteri["vis_48"],$d[50]);
			$f->text($visit_obsteteri["vis_49"],$d[51] );
			$f->text($visit_obsteteri["vis_52"],$d[54]."&nbsp;mm Hg");
			$f->text($visit_obsteteri["vis_53"],$d[55]."&nbsp;Kg");
			$f->title1("<U>DOKTER PEMERIKSA</U>","LEFT");
			$f->text("Dokter",$d["nama"]);
			$f->text("Perawat",$d["perawat"]);
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
				$sql = "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,A.VIS_40,'DUMMY' ". 
					   "FROM C_VISIT A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   "WHERE A.VIS_1 != '' and B.MR_NO = '".$_GET["mr"]."' AND A.ID_POLI = '{$_GET["mPOLI"]}' ";
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	//$t->ColHidden[4]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("NO REGISTRASI","TANGGAL PEMERIKSAAN","WAKTU KUNJUNGAN","KELUHAN UTAMA","DETAIL");
			   	$t->ColAlign = array("center","center","center","left","center");
				$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat&act=detail&mr=".$_GET["mr"]."&rg=<#0#>'>".icon("view","View")."</A>";	
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
    	
	include ("p_obsteteri.konsultasi.php");
		
}elseif ($_GET["list"] == "resepobat"){ //RESEP OBAT
    	$T->show(8);
    	//echo"<br>";
    	
 include ("resep_obat.php");
 
 include("rincianobat.php"); 


		
    }else {    
    	   //pemeriksaan
    	if(!$GLOBALS['print']){
    	$T->show(0);
    	}else{}
    	$sql2 =	"SELECT A.*,B.NAMA,D.nama as perawat FROM C_VISIT A 
    				LEFT JOIN RS00017 B ON A.ID_DOKTER = B.ID
					LEFT JOIN RS00017 D ON A.ID_perawat  = D.ID
    				WHERE A.ID_POLI={$_GET["mPOLI"]} AND A.NO_REG='$rg'"; 
    	$r=pg_query($con,$sql2);
    	$n = pg_num_rows($r);
    	
	    if($n > 0) $d2 = pg_fetch_array($r);
	    pg_free_result($r);
	     //-------------------------tambah for update------hery 08072007
			echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&poli={$_GET["poli"]}&act=edit';\">\n";   
			//echo "<input type='image' src='images/icon-edit.png' action='edit' >";
			    
			if ($_GET['act'] == "edit"){
					echo "<font color='#000000' size='2'> >>Edit Pemeriksaan Pasien</font>";
					
					$f = new Form("actions/p_obsteteri.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","edit");
					$f->hidden("f_no_reg",$d2["no_reg"]);
				    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
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
			
				$f = new Form("actions/p_obsteteri.insert.php", "POST", "NAME=Form2");
				$f->hidden("act","new");
				$f->hidden("f_no_reg",$d->id);
				$f->hidden("list","pemeriksaan");
				$f->hidden("mr",$_GET["mr"]);
				$f->hidden("f_id_poli",$_GET["poli"]);
				$f->hidden("f_user_id",$_SESSION[uid]);	
			}
			
	    	echo "<table border=1 width='100%' cellspacing=1 cellpadding=0><tr><td width='100%'>";
			echo "<table width='100%' border='1' cellspacing=0 cellpadding=0><tr><td>";
			
			//untuk dokter
					if (isset($_SESSION["SELECT_EMP"])) {
    					$_SESSION["DOKTER"]["id"] = $_SESSION["SELECT_EMP"];
    					$_SESSION["DOKTER"]["nama"] =
        				getFromTable(
                                        "select nama from rs00017 where id = '".$_SESSION["DOKTER"]["id"]."'");
			            
                                        $f->textAndButton3("f_id_dokter","Dokter Pemeriksa",2,10,$_SESSION["DOKTER"]["id"],$ext,"nm2",30,70,$_SESSION["DOKTER"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");	
			            
    					//unset($_SESSION["SELECT_EMP"]);
					}elseif ($d2["id_dokter"] != '') {
							$f->textAndButton3("f_id_dokter","Dokter Pemeriksa",2,10,$d2["id_dokter"],$ext,"nm2",30,70,$d2["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");
					}else{
						$f->textAndButton3("f_id_dokter","Dokter Pemeriksa",2,10,0,$ext,"nm2",30,70,$d2["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");
					}
					//untuk perawat
                                        if (isset($_SESSION["SELECT_EMP2"])) {
    					$_SESSION["PERAWAT"]["id"] = $_SESSION["SELECT_EMP2"];
    					$_SESSION["PERAWAT"]["nama"] =
        				getFromTable(
                                        "select nama from rs00017 where id = '".$_SESSION["PERAWAT"]["id"]."'");
			            
                                        $f->textAndButton3("f_id_perawat","Perawat",2,10,$_SESSION["PERAWAT"]["id"],$ext,"nm3",30,70,$_SESSION["PERAWAT"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai2();';");	
			            
    					//unset($_SESSION["SELECT_EMP2"]);
					}elseif ($d2["id_perawat"] != '') {
						$f->textAndButton3("f_id_perawat","Perawat",2,10,$d2["id_perawat"],$ext,"nm3",30,70,$d2["perawat"],$ext,"...",$ext,"OnClick='selectPegawai2();';");
					}else{
						$f->textAndButton3("f_id_perawat","Perawat",2,10,0,$ext,"nm3",30,70,$d2["perawat"],$ext,"...",$ext,"OnClick='selectPegawai2();';");
					}
			$max = count($visit_obsteteri) ; 
			$i = 1;
			while ($i<= $max) {		
					
	 		$i++ ; 	
			}
			$f->text("f_vis_1",$visit_obsteteri["vis_1"],50,30,ucfirst($d2["vis_1"]),$ext);
	 		$f->textarea("f_vis_2",$visit_obsteteri["vis_2"],1,$visit_obsteteri["vis_2"."W"],ucfirst($d2["vis_2"]),$ext);
	 		$f->calendar("f_vis_3","Tanggal",15,15,$d2["vis_3"],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
	 		$f->text("f_vis_4",$visit_obsteteri["vis_4"],30,30,ucfirst($d2["vis_4"]),$ext);
	 		$f->text("f_vis_5",$visit_obsteteri["vis_5"],30,30,ucfirst($d2["vis_5"]),$ext);
	 		$f->title1("<U>RIWAYAT SOSIAL</U>");
	 		$f->text("f_vis_50",$visit_obsteteri["vis_50"],30,30,ucfirst($d2["vis_50"]),$ext);
	 		if($d2["vis_51"]=="Belum"){
				//$f->radio_btn($visit_igd["vis_10"],"tst1","Ya","Ya","test2","Tidak","Tidak",$ext);
			    	$f->checkbox2($visit_obsteteri["vis_51"],"f_vis_51","Belum","Belum","CHECKED","Sudah","Sudah","",$ext);
			    }elseif ($d2["vis_51"]=="Sudah"){
			    	//$f->radio_btn($visit_igd["vis_10"],"f_vis_10","Ya","Tidak","Tidak",$ext);
			    	$f->checkbox2($visit_obsteteri["vis_51"],"f_vis_51","Belum","Belum","","Sudah","Sudah","CHECKED",$ext);
			    }else{
			    	//$f->radio_btn($visit_igd["vis_10"],"tst1","Ya","tst2","Tidak","",$ext);
			    	$f->checkbox2($visit_obsteteri["vis_51"],"f_vis_51","Belum","Belum","","Sudah","Sudah","",$ext);
			    }
	 		$f->text("f_vis_6",$visit_obsteteri["vis_6"],50,50,ucfirst($d2["vis_6"]),$ext);
	 		$f->text("f_vis_7",$visit_obsteteri["vis_7"],10,10,ucfirst($d2["vis_7"]),$ext);
	 		$f->text("f_vis_8",$visit_obsteteri["vis_8"],50,50,ucfirst($d2["vis_8"]),$ext);
	 		$f->textinfo("f_vis_9",$visit_obsteteri["vis_9"],10,30,$d2["vis_9"],"Orang",$ext);
	 		$f->textinfo("f_vis_10",$visit_obsteteri["vis_10"],10,30,$d2["vis_10"],"Orang",$ext);
	 		$f->textinfo("f_vis_11",$visit_obsteteri["vis_11"],10,30,$d2["vis_11"],"Orang",$ext);
	 		$f->textinfo("f_vis_12",$visit_obsteteri["vis_12"],10,30,$d2["vis_12"],"Orang",$ext);
	 		$f->textinfo("f_vis_13",$visit_obsteteri["vis_13"],10,30,$d2["vis_13"],"Orang",$ext);
	 		$f->textinfo("f_vis_14",$visit_obsteteri["vis_14"],10,30,$d2["vis_14"],"Orang",$ext);
	 		if($d2["vis_15"]=="Kurang"){
			
			    	$f->checkbox2($visit_obsteteri["vis_15"],"f_vis_15","Kurang","Kurang","CHECKED","Cukup","Cukup","",$ext);
			    }elseif ($d2["vis_15"]=="Cukup"){
			
			    	$f->checkbox2($visit_obsteteri["vis_15"],"f_vis_15","Kurang","Kurang","","Cukup","Cukup","CHECKED",$ext);
			    }else{
			
			    	$f->checkbox2($visit_obsteteri["vis_15"],"f_vis_15","Kurang","Kurang","","Cukup","Cukup","",$ext);
			    }
	 		//$f->text_3("f_vis_16",$visit_obsteteri["vis_16"],20,20,$d2["vis_16"],"","f_vis_17",$visit_obsteteri["vis_17"],20,20,$d2["vis_17"],"","f_vis_18",$visit_obsteteri["vis_18"],20,20,$d2["vis_18"],"",$ext);
			$f->text("f_vis_16",$visit_obsteteri["vis_16"],30,30,ucfirst($d2["vis_16"]),$ext);
			$f->text("f_vis_17",$visit_obsteteri["vis_17"],30,30,ucfirst($d2["vis_17"]),$ext);
			$f->text("f_vis_18",$visit_obsteteri["vis_18"],30,30,ucfirst($d2["vis_18"]),$ext);
	 		$f->text("f_vis_19",$visit_obsteteri["vis_19"],30,30,ucfirst($d2["vis_19"]),$ext);
			$f->textinfo("f_vis_20",$visit_obsteteri["vis_20"],10,30,$d2["vis_20"],"Gram",$ext);
			$f->textinfo("f_vis_21",$visit_obsteteri["vis_21"],10,30,$d2["vis_21"],"Cm",$ext);
			$f->title1("<U>PERTUMBUHAN DAN PERKEMBANGAN (MOTORIK, BICARA, GIGI, GELIGI)</U>");
			$f->textarea("f_vis_22",$visit_obsteteri["vis_22"],1,$visit_obsteteri["vis_22"."W"],ucfirst($d2["vis_22"]),$ext);
			$f->title1("<U>CATATAN MAKANAN (JENIS, JUMLAH, MUTU)</U>");
			$f->textarea("f_vis_23",$visit_obsteteri["vis_23"],1,$visit_obsteteri["vis_23"."W"],ucfirst($d2["vis_23"]),$ext);
			$f->title1("<U>IMUNISASI (DASAR,ULANGAN DAN TANGGAL / UMUR)</U>");
			$f->text_6i("","f_vis_24",$visit_obsteteri["vis_24"],30,30,ucfirst($d2["vis_24"]),"","f_vis_25",$visit_obsteteri["vis_25"],30,30,ucfirst($d2["vis_25"]),"",
			"f_vis_26",$visit_obsteteri["vis_26"],30,30,ucfirst($d2["vis_26"]),"","f_vis_27",$visit_obsteteri["vis_27"],30,30,ucfirst($d2["vis_27"]),"",
			"f_vis_28",$visit_obsteteri["vis_28"],30,30,ucfirst($d2["vis_28"]),"","f_vis_29",$visit_obsteteri["vis_29"],30,30,ucfirst($d2["vis_29"]),"",$ext);
			$f->title1("<U>PENYAKIT TERDAHULU</U>");
			$f->text_8i("f_vis_30",$visit_obsteteri["vis_30"],30,30,ucfirst($d2["vis_30"]),"","f_vis_31",$visit_obsteteri["vis_31"],30,30,ucfirst($d2["vis_31"]),"",
			"f_vis_32",$visit_obsteteri["vis_32"],30,30,ucfirst($d2["vis_32"]),"","f_vis_33",$visit_obsteteri["vis_33"],30,30,ucfirst($d2["vis_33"]),"",
			"f_vis_34",$visit_obsteteri["vis_34"],30,30,ucfirst($d2["vis_34"]),"","f_vis_35",$visit_obsteteri["vis_35"],30,30,ucfirst($d2["vis_35"]),"",
			"f_vis_36",$visit_obsteteri["vis_36"],30,30,ucfirst($d2["vis_36"]),"","f_vis_37",$visit_obsteteri["vis_37"],30,30,ucfirst($d2["vis_37"]),"",$ext);
			$f->title1("<U>RIWAYAT OBSTERIK</U>");
			$f->text("f_vis_38",$visit_obsteteri["vis_38"],30,30,ucfirst($d2["vis_38"]),$ext);
	 		$f->textarea("f_vis_39",$visit_obsteteri["vis_39"],1,$visit_obsteteri["vis_39"."W"],ucfirst($d2["vis_39"]),$ext);
	 		$f->textarea("f_vis_40",$visit_obsteteri["vis_40"],1,$visit_obsteteri["vis_40"."W"],ucfirst($d2["vis_40"]),$ext);
	 		$f->textarea("f_vis_41",$visit_obsteteri["vis_41"],1,$visit_obsteteri["vis_41"."W"],ucfirst($d2["vis_41"]),$ext);
	 		$f->textarea("f_vis_42",$visit_obsteteri["vis_42"],1,$visit_obsteteri["vis_42"."W"],ucfirst($d2["vis_42"]),$ext);
	 		$f->textarea("f_vis_43",$visit_obsteteri["vis_43"],1,$visit_obsteteri["vis_43"."W"],ucfirst($d2["vis_43"]),$ext);
	 		$f->textarea("f_vis_44",$visit_obsteteri["vis_44"],1,$visit_obsteteri["vis_44"."W"],ucfirst($d2["vis_44"]),$ext);
	 		$f->textarea("f_vis_45",$visit_obsteteri["vis_45"],1,$visit_obsteteri["vis_45"."W"],ucfirst($d2["vis_45"]),$ext);
	 		$f->textarea("f_vis_46",$visit_obsteteri["vis_46"],1,$visit_obsteteri["vis_46"."W"],ucfirst($d2["vis_46"]),$ext);
	 		$f->textarea("f_vis_47",$visit_obsteteri["vis_47"],1,$visit_obsteteri["vis_47"."W"],ucfirst($d2["vis_47"]),$ext);
	 		$f->textarea("f_vis_48",$visit_obsteteri["vis_48"],1,$visit_obsteteri["vis_48"."W"],ucfirst($d2["vis_48"]),$ext);
	 		$f->textarea("f_vis_49",$visit_obsteteri["vis_49"],1,$visit_obsteteri["vis_49"."W"],ucfirst($d2["vis_49"]),$ext);
			$f->textinfo("f_vis_52",$visit_obsteteri["vis_52"],10,30,$d2["vis_52"],"mm Hg",$ext);
			$f->textinfo("f_vis_53",$visit_obsteteri["vis_53"],10,30,$d2["vis_53"],"Kg",$ext);
                        $f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			echo"</td></tr>";
    	
  		echo "</tr></table>";

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


		/* Untuk Layanan Paket             */
		/* Agung Sunandar 16:53 26/06/2012 */
	    //echo "\n<script language='JavaScript'>\n";
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
    //echo "<DIV class=BOX>";
    
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->hidden("poli",$_GET["mPOLI"]);
     	//hery 09072007---------
   		echo "<div align='right' valign='middle'>";	
		$f = new Form($SC, "GET","NAME=Form2");
	    $f->hidden("p", $PID);
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
	
$SQLSTR = 	"SELECT A.MR_NO, A.ID,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL_REG,SUBSTR(A.WAKTU_REG,1,8)AS WAKTU_REG,A.NAMA, ".
				"A.TIPE_DESC AS LAYANAN, 'DUMMY'  ".
				"FROM RSV_PASIEN A ".
				"LEFT JOIN RS00006 B ON A.ID=B.ID ".
				"LEFT JOIN RS00001 C ON B.POLI=C.TC AND TT='LYN' ".
            	"WHERE B.POLI='".$_GET["mPOLI"]."' AND B.is_bayar = 'N'";
		// 24-12-2006 --> tambahan 'where a.is_bayar = 'N'
        
		$tglhariini = date("Y-m-d", time());
    if (strlen($_GET["mPOLI"]) > 0 ) {
		$SQLWHERE =
			"AND A.TANGGAL_REG = '$tglhariini' AND B.IS_BAYAR = 'N' AND B.POLI ='".$_GET["mPOLI"]."' AND ".
			"	(UPPER(A.NAMA) LIKE '%".strtoupper($_GET["search"])."%') ";
	} else {
		$SQLWHERE =
			"AND A.TANGGAL_REG = '$tglhariini' AND B.IS_BAYAR = 'N' AND ".
                        "(UPPER(A.NAMA) LIKE '%".strtoupper($_GET["search"])."%' ) ";
	}
	if ($_GET["search"]) {
		$SQLWHERE =
			"AND ((UPPER(A.NAMA) LIKE '%".strtoupper($_GET["search"])."%') AND B.IS_BAYAR = 'N' OR ".
                        "	A.MR_NO LIKE '%".$_GET["search"]."%' OR".
                        "       A.ID LIKE '%".$_GET["search"]."%')";
	}
	if (!isset($_GET[sort])) {

           $_GET[sort] = "WAKTU_REG";
           $_GET[order] = "ASC";
	}
	
	//echo $SQLSTR,$SQLWHERE;exit;
   	$t = new PgTable($con, "100%");
   	$t->SQL = "$SQLSTR $SQLWHERE ";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign = array("left","CENTER","CENTER","CENTER","LEFT","left","left","left","left","left","left");	
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[1] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}'><#1#>";
    $t->ColFormatHtml[8] = "<A HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&act=periksa'><INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Periksa' >";
    //$t->ColFormatMoney[2] = "%!+#2n";
    $t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL  REG","WAKTU REG","NAMA PASIEN","PANGKAT","NRP/NIP","KESATUAN","TIPE PASIEN");
    $t->ColColor[6] = "color";
	$t->execute();
    echo"<br><div class=NOTE>Catatan : Daftar pasien di urut berdasarkan no antrian</div><br>";
}
  //include("rincian.php");
?>
