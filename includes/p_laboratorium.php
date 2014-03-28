<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.autocomplete.css">
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
$PID = "p_laboratorium";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/visit_setting.php");
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
$_GET["mPOLI"]=$setting_poli["laboratorium"];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];

// Tambahan BHP
$POLI=$setting_poli["laboratorium"];
// ======================================


if ($_GET["httpHeader"] == "1" && $_GET['list'] != "layanan" && $_GET['sub'] != "icd") {
    
    if (strlen($_GET["ob4_id"]) > 0) {
        if (is_array($_SESSION["ob4"]["obat"])) {
            $cnt = count($_SESSION["ob4"]["obat"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from c_pemeriksaan_lab where id = '".$_GET["ob4_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        $_SESSION["ob4"]["obat"][$cnt]["id"]     = $d1->id;
        $_SESSION["ob4"]["obat"][$cnt]["obat"]   = $d1->parameter;
        $_SESSION["ob4"]["obat"][$cnt]["satuan"] = $d1->satuan;              
        $_SESSION["ob4"]["obat"][$cnt]["hasil"] = $_GET["ob4_hasil"];
        $_SESSION["ob4"]["obat"][$cnt]["rentang_normal"]  = $d1->rentang_normal;
	$_SESSION["ob4"]["obat"][$cnt]["keterangan"] = $_GET["ob4_keterangan"];
        unset($_SESSION["SELECT_LAB"]);
    }
    if (isset($_GET["del-lab"])) {
        $temp = $_SESSION["ob4"]["obat"];
        unset($_SESSION["ob4"]["obat"]);
        $cnt = 0;
        foreach ($temp as $k => $v) {
            if ($k != $_GET["del-lab"]) {
                $_SESSION["ob4"]["obat"][$cnt] = $v;
                $cnt++;
            }
        }
    }
    if (isset($_GET["editrow"])) {
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["hasil"] = $_GET["edithasil"];
	$_SESSION["ob4"]["obat"][$_GET["editrow"]]["keterangan"] = $_GET["editketerangan"];
    }
    
   			header("Location: $SC?p=" . $_GET["p"] . "&list=pemeriksaan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]);
		    exit;
//lab end
}


		if (isset($_GET["del"])) {
		    $temp = $_SESSION["layanan"];
		    unset($_SESSION["layanan"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del"]) $_SESSION["layanan"][count($_SESSION["layanan"])] = $v;
		    }
				header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&poli=".$_GET["mPOLI"]."&mr=" . $_GET["mr"]."&sub=layanan&sub2=nonpaket");
		    	exit;
		
		/* Untuk menambahkan hapus Paket layanan */
		/* Agung Sunandar 16:28 26/06/2012       */
		
		}elseif (isset($_GET["del1"])) {
		    $temp = $_SESSION["layanan2"];
		    unset($_SESSION["layanan2"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del1"]) $_SESSION["layanan2"][count($_SESSION["layanan2"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&poli=".$_GET["mPOLI"]."&mr=" . $_GET["mr"]."&sub=layanan&sub2=paket");
		    	exit;
		    
		} elseif (isset($_GET["del-icd"])) {
		    $temp = $_SESSION["icd"];
		    unset($_SESSION["icd"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-icd"]) $_SESSION["icd"][count($_SESSION["icd"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    	exit;
		    
		} elseif (isset($_GET["del-obat"])) {
		    $temp = $_SESSION["obat"];
		    unset($_SESSION["obat"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-obat"]) $_SESSION["obat"][count($_SESSION["obat"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=obat");
		    	exit;
		    // Tambahan BHP    
		} elseif (isset($_GET["del-obat2"])) {
		    $temp = $_SESSION["obat2"];
		    unset($_SESSION["obat2"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-obat2"]) $_SESSION["obat2"][count($_SESSION["obat2"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&poli=$POLI&sub=layanan&sub2=bhp");
		    	exit; 
		// ==============================================    

		} elseif (isset($_GET["del-pjm"])) {
		    $temp = $_SESSION["pjm"][$_GET["del-pjm"]];
		    unset($_SESSION["pjm"][$_GET["del-pjm"]]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-emp"])
		            $_SESSION["pjm"][$_GET["del-pjm"]][count($_SESSION["pjm"][$_GET["del-pjm"]])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=pjm");
		    	exit;
		    
		} elseif (isset($_GET["s2note"])) {
		    $_SESSION["s2note"] = $_GET["s2note"];
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    	exit;
		    
		} elseif (isset($_GET["obat"])) {
		    $r = pg_query($con,"SELECT * FROM RSV0004 WHERE ID = '".$_GET["obat"]."'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);
		
		    if (is_array($_SESSION["obat"])) {
		        $cnt = count($_SESSION["obat"]);
		    } else {
		        $cnt = 0;
		    }
		    
		    if (!empty($d->obat)) {
		        $_SESSION["obat"][$cnt]["id"]     = $_GET["obat"];
		        $_SESSION["obat"][$cnt]["desc"]   = $d->obat;
		        //$_SESSION["obat"][$cnt]["dosis"]  = $_GET["dosis_obat"];
		        $_SESSION["obat"][$cnt]["jumlah"] = $_GET["jumlah_obat"];
		        $_SESSION["obat"][$cnt]["harga"]  = $d->harga;
		        $_SESSION["obat"][$cnt]["persen"]  = $_GET["persen"];
				$_SESSION["obat"][$cnt]["diskon"]  = ($_GET["persen"]/100) * ($d->harga * $_GET["jumlah_obat"]);
		        $_SESSION["obat"][$cnt]["total"]  = ($d->harga * $_GET["jumlah_obat"]) - ($_GET["persen"]/100) * ($d->harga * $_GET["jumlah_obat"]);
		        //$_SESSION["obat"][$cnt]["satuan"] = $d->satuan;
		        unset($_SESSION["SELECT_OBAT"]);
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=obat");
		    	exit;
    
		} elseif (isset($_GET["icd"])) {
		    $r = pg_query($con,"SELECT * FROM RSV0005 WHERE DIAGNOSIS_CODE = '" . $_GET["icd"] . "'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);
		    if (is_array($_SESSION["icd"])) {
		        $cnt = count($_SESSION["icd"]);
		    } else {
		        $cnt = 0;
		    }
		    
		    if (strlen($d->description) > 0) {
		        $_SESSION["icd"][$cnt]["id"]   = $_GET["icd"];
		        $_SESSION["icd"][$cnt]["desc"] = $d->description;
		        $_SESSION["icd"][$cnt]["kate"] = $d->category;
		        unset($_SESSION["SELECT_ICD"]);
		    }
		    header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    exit;
		    
		} elseif (isset($_GET["layanan"])) {
			
		    $r = pg_query($con,"SELECT * FROM RSV0034 WHERE ID = '" . $_GET["layanan"] . "'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);

    $gol_tindakan = getFromTable("select golongan_tindakan_id from rs00034 where id='".$_GET["layanan"]."'");
   // $is_range = $d->harga_atas > 0 || $d->harga_bawah > 0;

    if ($d->id) {
    //   if (($is_range && isset($_GET["harga"])) || (!$is_range)) {
            if (is_array($_SESSION["layanan"])) {
                $cnt = count($_SESSION["layanan"]);
            } else {
                $cnt = 0;
            }
           
            $dokter = getFromTable("select nama from rs00017 where id = '".$_SESSION[SELECT_EMP]."'");
            $harga = $is_range ? $_GET["harga"] : $d->harga;
            $_SESSION["layanan"][$cnt]["id"]     = str_pad($_GET["layanan"],5,"0",STR_PAD_LEFT);
            
            if ($d->klasifikasi_tarif) $embel= " - ".$d->klasifikasi_tarif;
            $_SESSION["layanan"][$cnt]["nama"]   = $d->layanan . $embel;
            $_SESSION["layanan"][$cnt]["jumlah"] = $_GET["jumlah"];
            $_SESSION["layanan"][$cnt]["satuan"] = $d->satuan;
            $_SESSION["layanan"][$cnt]["harga"]  = $harga;
            $_SESSION["layanan"][$cnt]["persen"]  = $_GET["persen"];
			$_SESSION["layanan"][$cnt]["diskon"]  = ($_GET["persen"]/100) * ($harga * $_GET["jumlah"]);
            $_SESSION["layanan"][$cnt]["total"]  = ($harga * $_GET["jumlah"]) - ($_GET["persen"]/100) * ($harga * $_GET["jumlah"]);
            $_SESSION["layanan"][$cnt]["dokter"]  = $dokter;
            $_SESSION["layanan"][$cnt]["nip"]  = $_SESSION[SELECT_EMP];
            

		} 

            unset($_SESSION["SELECT_LAYANAN"]);
            unset($_SESSION["SELECT_EMP"]);
echo "<script type='text/javascript'>
window.location='index2.php?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&sub=layanan&sub2=nonpaket';
</script>
";
          //  header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&sub=layanan&sub2=nonpaket");
            exit;
            

    
}
		/* Untuk menambahkan hapus Paket layanan */
		/* Agung Sunandar 16:28 26/06/2012       */

elseif (isset($_GET["layanan2"])) {
			
		    $r = pg_query($con,"SELECT * FROM rs99996 WHERE ID = '" . $_GET["layanan2"] . "'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);


    if ($d->id) {
            if (is_array($_SESSION["layanan2"])) {
                $cnt = count($_SESSION["layanan2"]);
            } else {
                $cnt = 0;
            }
            //$sumber_pendapatan_id = getFromTable("select sumber_pendapatan_id from rsv0034 where id = '" . $_SESSION["SELECT_LAYANAN"] . "'");

            $dokter = getFromTable("select nama from rs00017 where id = '".$_SESSION[SELECT_EMP2]."'");
            $harga = $d->harga_paket;
            $_SESSION["layanan2"][$cnt]["id"]     = str_pad($_GET["layanan2"],5,"0",STR_PAD_LEFT);
            $cek_qty= getFromTable("select 'qty_'||tc from rs00001 where tt='GDP' and tc_poli='".$_GET["poli"]."' ") ;
            //if ($d->klasifikasi_tarif) $embel= " - ".$d->klasifikasi_tarif;
            $_SESSION["layanan2"][$cnt]["nama"]   = $d->description;
			$_SESSION["layanan2"][$cnt]["jumlah"] = $_GET["jumlah"];
            $_SESSION["layanan2"][$cnt]["satuan"] = "KALI";
            $_SESSION["layanan2"][$cnt]["harga"]  = $d->harga_paket;
			$_SESSION["layanan2"][$cnt]["persen"]  = $_GET["persen"];
			$_SESSION["layanan2"][$cnt]["diskon"]  = ($_GET["persen"]/100) * ($d->harga_paket * $_GET["jumlah"]);
            $_SESSION["layanan2"][$cnt]["total"]  = ($d->harga_paket * $_GET["jumlah"]) - ($_GET["persen"]/100) * ($d->harga_paket * $_GET["jumlah"]);
            $_SESSION["layanan2"][$cnt]["dokter"]  = $dokter;
			$_SESSION["layanan2"][$cnt]["stok"]  = $cek_qty;
            $_SESSION["layanan2"][$cnt]["nip"]  = $_SESSION[SELECT_EMP2];

            }
            unset($_SESSION["SELECT_LAYANAN2"]);
            unset($_SESSION["SELECT_EMP"]);
            unset($_SESSION["SELECT_EMP2"]);
echo "<script type='text/javascript'>
window.location='index2.php?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&sub=2&sub2=" . $_GET["sub2"]."';
</script>
";
          //  header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&sub=2&sub2=" . $_GET["sub2"]."");
            exit;

    }
echo "<table border=0 width='100%'><tr><td>";
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  LAYANAN LABORATORIUM");
echo "</td></tr></table>";

unset($_GET["layanan"]);

$reg = $_GET["rg"];
$reg2 = $_GET["rg"];

	$tab_disabled = array("pemeriksaan"=>true, "catatan"=>true,"layanan"=>true, "icd"=>true, "riwayat"=>true,"riwayat_klinik"=>true,"unit_rujukan"=>true,"konsultasi"=>true);
	if ($_GET["act"] == "del" ) {
	$tab_disabled = array("pemeriksaan"=>false, "catatan"=>false,"layanan"=>false, "icd"=>false, "riwayat"=>false,"riwayat_klinik"=>false,"unit_rujukan"=>false,"konsultasi"=>false);
	$tab_disabled[$_GET["sub"]] = true;
	$tab_disabled[$_POST["sub"]] = true;
	}
	
	$T = new TabBar();
	$T->addTab("$SC?p=$PID&list=pemeriksaan&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr ", "Hasil Pemeriksaan Pasien"	, $tab_disabled["pemeriksaan"]);
	$T->addTab("$SC?p=$PID&list=catatan&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Catatan Laboratorium"	, $tab_disabled["catatan"]);
	$T->addTab("$SC?p=$PID&list=layanan&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr&sub=layanan", "layanan / Tindakan"	, $tab_disabled["layanan"]);
	$T->addTab("$SC?p=$PID&list=icd&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr&sub=icd", "Pilih I C D"	, $tab_disabled["icd"]);
	$T->addTab("$SC?p=$PID&list=riwayat&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Riwayat Klinik"	, $tab_disabled["riwayat"]);
	$T->addTab("$SC?p=$PID&list=riwayat_klinik&rg=$rg&mr=$mr", "Riwayat Medis"	, $tab_disabled["riwayat_klinik"]);
	$T->addTab("$SC?p=$PID&list=unit_rujukan&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Status Akhir Pasien"	, $tab_disabled["unit_rujukan"]);
	$T->addTab("$SC?p=$PID&list=konsultasi&rg=$rg&poli=".$_GET["mPOLI"]."&mr=$mr", "Konsultasi"	, $tab_disabled["konsultasi"]);

if ($reg > 0) {
    $r = pg_query($con,
      "select a.id,a.mr_no,a.nama,age(a.tanggal_reg::timestamp with time zone, b.tgl_lahir::timestamp with time zone) AS umur,a.tanggal_reg,c.diagnosa_sementara,a.rawat_inap, 
	CASE
            WHEN b.jenis_kelamin::text = 'L'::text THEN 'Laki-laki'::text
            ELSE 'Perempuan'::text
        END AS jenis_kelamin,b.pangkat_gol,b.nrp_nip,b.kesatuan,g.tdesc as tipe_desc 
        from rsv_pasien4 a  
        left join rs00002 b on b.mr_no=a.mr_no
        left join rs00006 c on c.id=a.id
        LEFT JOIN rs00001 g ON c.tipe::text = g.tc::text AND g.tt::text = 'JEP'::text
        WHERE A.ID = '$reg'");

    
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    $rawatan = $d->rawatan;

    // ambil bangsal
    $id_max = getFromTable("select max(id) from rs00010 where no_reg = '".$_GET["rg"]."'");
    if (!empty($id_max)) {
    $bangsal = getFromTable("select c.bangsal || ' / ' || e.tdesc ".
                       "from rs00010 as a ".
                       "    join rs00012 as b on a.bangsal_id = b.id ".
                       "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
                       //"    join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
                       "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
                       "where a.id = '$id_max'");
    }
    $umure = umur($d->umur);
    $umure = explode(" ",$umure);
    $umur = $umure[0]." Tahun";

	//===============update to rs00006 (status pemeriksaan)=============
    if($_GET['act'] == "periksa"){
	//pg_query("update rs00006 set periksa='Y' where id =lpad('".$_GET["rg"]."',10,'0')");
	}
	include 'keterangan';
	/*	echo "<hr noshade size='1'>";
    echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top>";
    $f = new ReadOnlyForm();
    $f->text("<b>"."Nama",$d->nama);
    $f->text("<b>"."No RM",$d->mr_no);
    $f->text("<b>"."No Reg.", formatRegNo($d->id));
    //$f->text("Kedatangan",$d->datang);
    $f->execute();
    echo "</td><td align=left valign=top>";
    $f = new ReadOnlyForm();
    $f->text("<b>"."NRP/NIP",$d->nrp_nip);
    $f->text("<b>"."Pangkat/Gol",ucwords($d->pangkat_gol));
    $f->text("<b>"."Kesatuan/Pekerjaan",ucwords($d->kesatuan)); 
    $f->execute();
    echo "</td><td align=left valign=top>";
    $f = new ReadOnlyForm();
    $f->text("<b>"."Umur", "$d->umur");
    $f->text("<b>"."Seks",$d->jenis_kelamin);
    if($d->rawat_inap=='I'){
    $f->text("<b>"."Rawat Inap",$bangsal);
    }else if($d->rawat_inap=='N'){
    $f->text("<b>"."Ruang","IGD");
    }else{
    $f->text("<b>"."Ruang",$d->poli);
    }
    $f->execute();
    echo "</td><td valign=top>";
    $f = new ReadOnlyForm();
    echo "<table border=0 width='100%'>";
    echo "<tr><td class=TBL_BODY><strong>Dokter Pemeriksa:</strong></td></tr>";
    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
    echo "</table>";
    $f->execute();
    
    echo "</td></tr></table>";
    echo"<hr noshade size='2'>";
        
    echo "</div>";
 	echo " <BR><DIV ALIGN=RIGHT><img src=\"icon/back.gif\" align=absmiddle ><A CLASS=SUB_MENU HREF='index2.php".
            "?p=$PID'>".
            "  Kembali  </A></DIV>";
    	echo"<br>";
    	
    //disini
*/
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
        $f = new Form("actions/p_laboratorium.insert.php");
        $f->hidden("rg",$_GET["rg"]);
        $f->hidden("mr",$_GET["mr"]);
        $f->hidden("poli",$_GET["poli"]);
	$f->hidden("sub",$_GET["sub"]);
        $f->hidden("byr",$total);
        //$f->text("byr","Jumlah Pembayaran",15,15,$total,"STYLE='text-align:right'");
        $f->submit(" Simpan &amp; Bayar ");
        $f->execute();
    
}elseif ($_GET["list"] == "catatan") {  // -------- catatan
		if(!$GLOBALS['print']){
		$T->show(1);
		}else{}
        /*$sql2 =	"SELECT A.*,B.NAMA FROM C_VISIT A 
    				LEFT JOIN RS00017 B ON A.ID_DOKTER = B.ID
    				WHERE A.ID_POLI={$_GET["mPOLI"]} AND A.NO_REG='$rg'"; 
	    	$r=pg_query($con,$sql2);
	    	$n = pg_num_rows($r);		    	
			    if($n > 0) $d6 = pg_fetch_array($r);
			    pg_free_result($r);
				//-------------------------tambah for update------hery 08072007
				echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&list=catatan&poli={$_GET["poli"]}&act=edit';\">\n";   
				//echo "<input type='image' src='images/icon-edit.png' action='edit' >";
				    
				if ($_GET['act'] == "edit"){
						echo "<font color='#000000' size='2'> >>Edit Catatan</font>";
						$f = new Form("actions/p_laboratorium.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d6["no_reg"]);
					    $f->hidden("f_tanggal_reg",$d6["tanggal_reg"]);
						$f->hidden("list","riwayat");
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
						
					$f = new Form("actions/p_laboratorium.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("list","riwayat");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli",$_GET["poli"]);
				    $f->hidden("f_user_id",$_SESSION[uid]);
			}
				    
				echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";*/
	$sql2 =	"SELECT A.*,(B.NAMA) AS DOKTER, 
					(C.NAMA) AS DOKTER2, (D.NAMA) AS PEMERIKSA 
					FROM C_VISIT A 
					LEFT JOIN RS00017 B ON A.ID_DOKTER = B.ID 
					LEFT JOIN RS00017 C ON A.ID_DOKTER2 = C.ID 
					LEFT JOIN RS00017 D ON A.ID_PERAWAT = D.ID 
    				WHERE A.ID_POLI={$_GET["mPOLI"]} AND A.NO_REG='$rg'"; 
	    	$r=pg_query($con,$sql2);
	    	$n = pg_num_rows($r);		    	
			    if($n > 0) $d6 = pg_fetch_array($r);
			    pg_free_result($r);
				//-------------------------tambah for update------hery 08072007
				echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&list=catatan&poli={$_GET["poli"]}&act=edit';\">\n";   
				//echo "<input type='image' src='images/icon-edit.png' action='edit' >";
				    
				if ($_GET['act'] == "edit"){
						echo "<font color='#000000' size='2'> >>Edit Catatan Laboratorium</font>";
						$f = new Form("actions/p_laboratorium.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d6["no_reg"]);
					    $f->hidden("f_tanggal_reg",$d6["tanggal_reg"]);
						$f->hidden("list","catatan");
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
					$f = new Form("actions/p_laboratorium.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("list","catatan");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli",$_GET["poli"]);
				    $f->hidden("f_user_id",$_SESSION[uid]);
			}
				if (isset($_SESSION["SELECT_EMP"])) {
    					$_SESSION["DOKTER"]["id"] = $_SESSION["SELECT_EMP"];
    					$_SESSION["DOKTER"]["nama"] =
        				getFromTable(
			            "select nama from rs00017 where id = '".$_SESSION["DOKTER"]["id"]."'");
    					$f->textAndButton3("f_id_dokter","Dokter Penanggung Jawab",2,10,$_SESSION["DOKTER"]["id"],$ext,"nm2",30,70,$_SESSION["DOKTER"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");
			           // unset($_SESSION["SELECT_EMP"]);
					}else{
						$f->textAndButton3("f_id_dokter","Dokter Penanggung Jawab",2,10,$d6["id_dokter"],$ext,"nm2",30,70,$d6["dokter"],$ext,"...",$ext,"OnClick='selectPegawai();';");
					}
					
				if (isset($_SESSION["SELECT_EMP2"])) {
					$_SESSION["DOKTER2"]["id2"] = $_SESSION["SELECT_EMP2"];
					$_SESSION["DOKTER2"]["nama2"] = 
					getFromTable(
					"select nama from rs00017 where id = '".$_SESSION["DOKTER2"]["id2"]."'");
					$f->textAndButton3("f_id_dokter2","Dokter Pengirim",2,10,$_SESSION["DOKTER2"]["id2"],$ext,"nm3",30,70,$_SESSION["DOKTER2"]["nama2"],$ext,"...",$ext,"OnClick='selectPegawai2();';");
					}else{
					$f->textAndButton3("f_id_dokter2","Dokter Pengirim",2,10,$d6["id_dokter2"],$ext,"nm3",30,70,$d6["dokter2"],$ext,"...",$ext,"OnClick='selectPegawai2();';");
					
					}
					
				if (isset($_SESSION["SELECT_EMP3"])) {
					$_SESSION["PEMERIKSA"]["id3"] = $_SESSION["SELECT_EMP3"];
					$_SESSION["PEMERIKSA"]["nama3"] = 
					getFromTable(
					"select nama from rs00017 where id = '".$_SESSION["PEMERIKSA"]["id3"]."'");
					$f->textAndButton3("f_id_perawat","Pemeriksa",2,10,$_SESSION["PEMERIKSA"]["id3"],$ext,"nm4",30,70,$_SESSION["PEMERIKSA"]["nama3"],$ext,"...",$ext,"OnClick='selectPegawai3();';");
					}else{
					$f->textAndButton3("f_id_perawat","Pemeriksa",2,10,$d6["id_perawat"],$ext,"nm4",30,70,$d6["pemeriksa"],$ext,"...",$ext,"OnClick='selectPegawai3();';");
					
					}
					
				
				$f->textarea("f_vis_1",$visit_laboratorium["vis_1"],6,50,$d6["vis_1"],$ext);
				$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
				$f->execute();
		echo"</div>";
     
       // Tambahan BHP 
} elseif (isset($_GET["obat2"])) {
		    $r = @pg_query($con,"SELECT * FROM RSV0004 WHERE ID = '".$_GET["obat2"]."'");
		    $d = @pg_fetch_object($r);
		    @pg_free_result($r);
		
		    if (is_array($_SESSION["obat2"])) {
		        $cnt = count($_SESSION["obat2"]);
		    } else {
		        $cnt = 0;
		    }
		    
                    $cek_qty= getFromTable("select 'qty_'||tc from rs00001 where tt='GDP' and tc_poli='".$_GET["poli"]."' ") ;
                    
		    if (!empty($d->obat)) {
		        $_SESSION["obat2"][$cnt]["id"]     = $_GET["obat2"];
		        $_SESSION["obat2"][$cnt]["desc"]   = $d->obat;
		        $_SESSION["obat2"][$cnt]["satuan"]  = $_GET["satuan"];
		        $_SESSION["obat2"][$cnt]["jumlah"] = $_GET["jumlah_obat"];
		        $_SESSION["obat2"][$cnt]["harga"]  = $d->harga;
				$_SESSION["obat2"][$cnt]["persen"]  = $_GET["persen"];
				$_SESSION["obat2"][$cnt]["diskon"]  = ($_GET["persen"]/100) * ($d->harga * $_GET["jumlah_obat"]);
		        $_SESSION["obat2"][$cnt]["total"]  = ($d->harga * $_GET["jumlah_obat"]) - ($_GET["persen"]/100) * ($d->harga * $_GET["jumlah_obat"]);
                $_SESSION["obat2"][$cnt]["stok"]   = $cek_qty;
		        $_SESSION["obat2"][$cnt]["is_racikan"] = $_GET["is_racikan"];
                $_SESSION["obat2"][$cnt]["nip"] = '0';
		        unset($_SESSION["SELECT_OBAT2"]);
		    }
			echo "<script type='text/javascript'>
window.location='index2.php?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&poli=$POLI&sub=layanan&sub2=bhp';
</script>
";
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"]."&mr=" . $_GET["mr"]."&poli=$POLI&sub=layanan&sub2=bhp");
		    	exit; 
		// ==============================================  

        
    } elseif ($_GET["list"] == "icd") {  // -------- ICD
	if(!$GLOBALS['print']){
		$T->show(3);
	}else{}
        echo"<div align=center class=form_subtitle1>KLASIFIKASI PENYAKIT</div>";
        echo "<table width='100%' border=0 cellspacing=0 cellpadding=0><tr>";
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b2.disabled = true;\n";
        echo "document.Form3.b4.disabled = false;\n";
        echo "</script>\n";
        echo "<form action='$SC'>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        echo "</form>";
        echo "<td valign=top>";

        $namaICD = getFromTable("SELECT DESCRIPTION FROM RSV0005 WHERE DIAGNOSIS_CODE = '".$_SESSION["SELECT_ICD"]."'");
        $katICD = getFromTable("SELECT CATEGORY FROM RSV0005 WHERE DIAGNOSIS_CODE = '".$_SESSION["SELECT_ICD"]."'");
        
        $t = new BaseTable("100%");
        $t->printTableOpen();
        echo "<FORM ACTION='$SC' NAME=Form801>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t->printTableHeader(Array("KODE ICD", "KETERANGAN","KATEGORI", "&nbsp;"));
        
        if (is_array($_SESSION["icd"])) {
            foreach($_SESSION["icd"] as $k => $l) {
                $t->printRow2(
                    Array($l["id"], $l["desc"],$l["kate"], "<A HREF='$SC?p=$PID&list=icd&rg=".$_GET["rg"]."&mr=".$_GET["mr"]."&del-icd=$k&httpHeader=1'>".icon("del-left")."</A>"), Array("CENTER", "LEFT", "LEFT","CENTER")
                );
            }
        }
		// sfdn, 27-12-2006 --> pembetulan directory icon = ../simrs/images/*.png
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=icd STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_ICD"]."'>&nbsp;<A HREF='javascript:selectICD()'><IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaICD,"$katICD", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "LEFT","CENTER")
        );
		// --- eof 27-12-2006 ---
        echo "</FORM>";
        
        $t->printTableClose();
        //echo "</td></tr></table>";
        echo "\n<script language='JavaScript'>\n";
        echo "function selectICD() {\n";
        echo "sWin = window.open('popup/icd.php', 'xWin', 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
        
        echo "<form name='Form9' action='actions/p_laboratorium.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<br><div align=right><input type=button value='Simpan' onClick='document.Form9.submit()'>&nbsp;</div>";
        echo "</form>";
		
     $rec1 = getFromTable ("select count(id) from rs00008 ".	// sfdn, 27-12-2006 --> melakukan testing apakah ada data diagnosa
						  "where trans_type = 'ICD' and no_reg ='".$_GET["rg"]."'");
	if ($rec1 > 0) {

		$f = new Form("");
		echo "<br>";
		$f->title1("Data Diagnosa");
		$f->execute();
		
		$t = new PgTable($con, "100%");
		$t->SQL = "select a.item_id,b.description,b.category,a.oid from rs00008 a 
				   left join rsv0005 b on b.diagnosis_code = a.item_id
				   where trans_type='ICD' and a.no_reg ='".$_GET["rg"]."' order by tanggal_entry";		   
		$t->setlocale("id_ID");
		$t->ShowRowNumber = true;
		$t->RowsPerPage = $ROWS_PER_PAGE;
		$t->ColHeader = array("KODE ICD","DESKRIPSI ICD","DIAGNOSA","HAPUS");
		$t->ColAlign = array("center","left","left","center");
		$t->DisableScrollBar = true;
		$t->DisableStatusBar = true;	
		$t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='actions/p_icd.delete.php?p=$PID&sub=icd&list=icd&mr=".$_GET["mr"]."&poli=".$_GET["mPOLI"]."&rg=".$_GET["rg"]."&id=<#3#>'>".icon("delete","Hapus")."</A>";			
		$t->execute();
	}
        include("rincian3.php");
        
    }elseif ($_GET["list"] == "layanan") { // ----------------------------- LAYANAN MEDIS
    	if(!$GLOBALS['print']){
    	$T->show(2);
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
				$sql = "select a.*,b.nama,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,to_char(a.tanggal_reg,'HH24:MI')as waktu,a.vis_1,f.layanan 
						from c_visit a 
						left join rs00017 b on a.id_dokter = b.id 
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00034 f on f.id::text = e.item_id
						where a.no_reg='{$_GET['rg']}' and a.id_poli={$_GET['mPOLI']}";
				$r = pg_query($con,$sql);
				$n = pg_num_rows($r);
			    if($n > 0) $d = pg_fetch_array($r);
			    pg_free_result($r);
				//echo $sql;exit;			
			    $_GET['id'] = $_GET['rg'] ;	
	 			
			echo"<div class=box>";
			echo "<table width='100%' border='0'><tr><td colspan='2'>";
			echo"<div class=form_subtitle>HASIL PEMERIKSAAN PASIEN</div>";
			echo "</td></tr>";
    		echo "<tr><td valign=top>";
    		$f = new ReadOnlyForm();
			$f->text("Tanggal Pemeriksaan","<b>".$d["tanggal_reg"]);
			$f->text("Waktu Pemeriksaan","<b>".$d["waktu"] );
			$f->title1("<U>PEMERIKSAAN</U>","LEFT");
			$f->text($visit_laboratorium["vis_1"],$d[3] );
			$f->text($visit_laboratorium["vis_2"],$d[4]);
			$f->text($visit_laboratorium["vis_3"],$d[5]);
			$f->execute();
    		echo "</td></tr>";
  			echo "<tr><td colspan='3'>";
  			echo "<br>";
  			$f = new ReadOnlyForm();
$f->title1("<U>HASIL PEMERIKSAAN LABORATORIUM</U>");
$f->execute();
$rowsPemeriksaanRawatJalan      = pg_query($con, "select a.id,a.no_reg,a.tanggal_entry,a.waktu_entry,a.item_id, b.jenis,b.parameter,a.hasil,b.satuan,b.rentang_normal,a.keterangan 
		  from c_catatan a
		  left join c_pemeriksaan_lab b on a.item_id = b.id
		  where a.no_reg ='".$_GET['rg']."' and a.id_poli = '203' and is_inap!='I' order by id");

$rowsPemeriksaanRawatInap   = pg_query($con, "select a.id,a.no_reg,a.tanggal_entry,a.waktu_entry,a.item_id, b.jenis,b.parameter,a.hasil,b.satuan,b.rentang_normal,a.keterangan 
		  from c_catatan a
		  left join c_pemeriksaan_lab b on a.item_id = b.id
		  where a.no_reg ='".$_GET['rg']."' and a.id_poli = '203' and is_inap='I' order by id");

?>
<!-- ---------------------- Start Buat tabel hasil input hasil pemeriksaan -------------------->
<table width='100%'>
	<tr>
		<td class="TBL_HEAD" width='3%'><center>No.</center></td>
		<td class="TBL_HEAD" width='16%' ><center>Tanggal</center></td>
		<td class="TBL_HEAD"><center>Nama Pemeriksaan</center></td>
		<td class="TBL_HEAD" width='8%'><center>Hasil</center></td>
		<td class="TBL_HEAD" width='18%'><center>Rentang Normal</center></td>
		<td class="TBL_HEAD" width='8%'><center>Satuan</center></td>
		<td class="TBL_HEAD" width='15%'><center>Keterangan</center></td>
	</tr>	
        <tr>
		<td class="TBL_BODY" colspan="7"><span style="font-weight: bold;">Hasil Pemeriksaan Rawat Jalan</span></td>
	</tr>
<?php
        $iData          = 0;
        $iObat          = 0;
        while($row=pg_fetch_array($rowsPemeriksaanRawatJalan)){
            $iData++;
            $iObat++;
            
            $sqlObat = pg_query($con, "SELECT DISTINCT c_pemeriksaan_lab.id,c_pemeriksaan_lab.parameter,c_pemeriksaan_lab.satuan,c_pemeriksaan_lab.rentang_normal
    FROM c_pemeriksaan_lab 
    WHERE is_group='N' AND id=". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
            $arrWaktuEntry = explode('.', $row["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
?>
	<tr>
	<td class="TBL_BODY" align="right"><?=$iObat?></td>
	<td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
	<td class="TBL_BODY" align="left">
             <input type="hidden" id="id<?php echo $row["id"]?>" value="<?=$obat["id"]?>" />
             <span id="parameter_<?php echo $row["id"]?>"><?=$obat["parameter"]?></span>
        </td>
	<td class="TBL_BODY" align="left">
        <span id="hasil_<?php echo $row["id"]?>"><?=$row["hasil"]?></span>
        </td>
	<td class="TBL_BODY" align="left"><span id="rentang_normal_<?php echo $row["id"]?>"><?=$obat["rentang_normal"]?></span></td>
	<td class="TBL_BODY" align="left">
	<span id="satuan_<?php echo $row["id"]?>"><?=$obat["satuan"]?></td>
	<td class="TBL_BODY" align="left">
        <span id="keterangan_<?php echo $row["id"]?>"><?=$row["keterangan"]?></span>
     </tr>
<?php
        }
?>
            <tr>
		<td class="TBL_BODY" colspan="7"><span style="font-weight: bold;">Hasil Pemeriksaan Rawat Inap</span></td>
	</tr>
    <?php
        $iRacikan         = 0;
        while($rowRacikan = pg_fetch_array($rowsPemeriksaanRawatInap)){
            $iRacikan++;
            $iData++;
            
            $sqlObatR = pg_query($con, "SELECT DISTINCT c_pemeriksaan_lab.id,c_pemeriksaan_lab.parameter,c_pemeriksaan_lab.satuan,c_pemeriksaan_lab.rentang_normal
FROM c_pemeriksaan_lab WHERE is_group='N' AND id=". $rowRacikan["item_id"] );
            $obatR = pg_fetch_array($sqlObatR);
            $arrWaktuEntry2 = explode('.', $rowRacikan["waktu_entry"]);
            $arrJamEntry2 = explode(':', $arrWaktuEntry2[0]);
      
?>
	<tr>
            
		<td class="TBL_BODY" align="right"><?=$iRacikan?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($rowRacikan["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry2[0].':'.$arrJamEntry2[1]?></td>
		<td class="TBL_BODY" align="left">
			<input type="hidden" id="id_<?php echo $rowRacikan["id"]?>" value="<?=$obatR["id"]?>" />
			<span id="parameter_<?php echo $rowRacikan["id"]?>"><?=$obatR["parameter"]?></span></td>
		<td class="TBL_BODY" align="left">
        <span id="hasil_<?php echo $rowRacikan["id"]?>"><?=$rowRacikan["hasil"]?></span>
        </td>
	<td class="TBL_BODY" align="left"><span id="rentang_normal_<?php echo $rowRacikan["id"]?>"><?=$obatR["rentang_normal"]?></span></td>
	<td class="TBL_BODY" align="left">
	<span id="satuan_<?php echo $rowRacikan["id"]?>"><?=$obatR["satuan"]?></td>
	<td class="TBL_BODY" align="left">
        <span id="keterangan_<?php echo $rowRacikan["id"]?>"><?=$rowRacikan["keterangan"]?></span>
    </tr>
<?php
        }
        echo '<input type="hidden" name="max_i" id="max_i" value="'.$iData.'">';
?>        
	
</table>
<?php
/*    
$SQLTR = "select b.jenis,b.parameter,a.vis_2,b.satuan,b.rentang_normal 
		  from c_catatan a
		  left join c_pemeriksaan_lab b on CAST (a.vis_1 as numeric) = b.id
		  where a.no_reg ='{$_GET['rg']}' and a.id_ri = '{$_GET['mPOLI']}' order by tanggal";

$t = new PgTable($con, "100%");
$t->SQL = "$SQLTR";
$t->setlocale("id_ID");
$t->ShowRowNumber = true;
$t->ColAlign = array("LEFT","LEFT","CENTER","CENTER","CENTER");
$t->ColHeader = array("JENIS PEMERIKSAAN","ITEM","HASIL","SATUAN","RENTANG NORMAL");
if(!$GLOBALS['print']){
		$t->RowsPerPage = 10;
    }else{
    	$t->RowsPerPage = 10;
    	$t->DisableNavButton = true;
    	$t->DisableScrollBar = true;
    	
    }

$t->execute(); */
  			echo "<br>";
  			include(rm_tindakan3);
  			echo "</td><td>";
  			echo "</td></tr></table>";

			
			}else {
				echo"<div align=center class=form_subtitle1>RIWAYAT PEMERIKSAAN PASIEN</div>";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
				$sql = "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,A.VIS_3 ". 
					   "FROM C_VISIT A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   "WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_POLI = '{$_GET["mPOLI"]}' ";
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	//$t->ColHidden[4]= true;
			    $t->RowsPerPage = 10;
			    $t->ColHeader = array("NO REGISTRASI","TANGGAL PEMERIKSAAN","WAKTU PEMERIKSAAN","DETAIL");
			   	$t->ColAlign = array("center","center","center","center","center");
				$t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat&act=detail&mr=".$_GET["mr"]."&rg=<#0#>'>".icon("view","View")."</A>";	
				$t->execute();
				
				echo"<br>";
         		echo"</div>";
				echo "</td></tr></table></div>";
    	
			}
    }elseif($_GET["list"] == "riwayat_klinik") {
    	if(!$GLOBALS['print']){
    	$T->show(5);
    	}else{}
    	if ($_GET["act"] == "detail_klinik") {
                                    
                                    $sql = "select a.*,b.nama,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,f.layanan,a.id_poli 
						from c_visit a 
						left join rs00017 b on a.id_dokter = B.ID 
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00034 f on f.id::text = e.item_id::text
						where a.no_reg='{$_GET['rg']}' and a.oid='{$_GET['oid']}'";
                                    
				$r = pg_query($con,$sql);
				$n = pg_num_rows($r);
			    if($n > 0) $d = pg_fetch_array($r);
			    pg_free_result($r);
				//echo $sql;exit;			
			    $_GET['id'] = $_GET['rg'] ;	
	 			
			//echo"<div class=box>";
			echo "<table width='100%' border='0'><tr><td colspan='2'>";
			//echo"<div class=form_subtitle>PEMERIKSAAN PASIEN</div>";
			echo "</td></tr>";
    		echo "<tr><td>";
    		$f = new ReadOnlyForm();
    		$poli=$_GET["polinya"];
    		$f->text("Poli","<b>".$poli);
    		if ($poli == $setting_poli["igd"]) {
    			include(detail_igd);
    		}elseif ($poli == $setting_poli["umum"]){
    			include(detail_umum);
    		}elseif ($poli == $setting_poli["mata"]){
    			include(detail_mata);
    		}elseif ($poli == $setting_poli["peny_dalam"]){
    			include(detail_peny_dalam);
    		}
    		elseif ($poli == $setting_poli["anak"]){
    			include(detail_anak);
    		}
    		elseif ($poli == $setting_poli["gigi"]){
    			include(detail_gigi);
			}elseif ($poli == $setting_poli["gizi"]){
    			include(detail_gizi);
    		}
    		elseif ($poli == $setting_poli["tht"]){
    			include(detail_tht);
    		}
    		elseif ($poli == $setting_poli["bedah"]){
    			include(detail_bedah);
    		}
    		elseif ($poli == $setting_poli["kulit_kelamin"]){
    			include(detail_kulit_kelamin);
    		}
    		elseif ($poli == $setting_poli["akupunktur"]){
    			include(detail_akupunktur);
    		}
    		elseif ($poli == $setting_poli["jantung"]){
    			include(detail_jantung);
    		}
    		elseif ($poli == $setting_poli["paru"]){
    			include(detail_paru);
    		}
    		elseif ($poli == $setting_poli["kebidanan_obstetri"]){
    			include(detail_obstetri);
    		}
    		elseif ($poli == $setting_poli["kebidanan_ginekologi"]){
    			include(detail_ginekologi);
    		}
    		elseif ($poli == $setting_poli["psikiatri"]){
    			include(detail_psikiatri);
    		}
    		elseif ($poli == $setting_poli["laboratorium"]){
    			include(detail_laboratorium);
    		}
                elseif ($poli == $setting_poli["operasi"]){
    			include(detail_operasi);
    		}
    		elseif ($poli == $setting_poli["saraf"]){
    			include(detail_saraf);
    		}
    		elseif ($poli == $setting_poli["radiologi"]){
    			include(detail_radiologi);
    		}
                elseif ($poli == "A01"){
    			include(detail_resume_anak);
    		}
                elseif ($poli == "A01"){
    			include(detail_resume_anak);
    		}
                elseif ($poli == "A02"){
    			include(detail_resume_kebidanan);
    		}
                elseif ($poli == "A03"){
    			include(detail_resume_bayi);
    		}
                elseif ($poli == "B01"){
    			include(detail_grafik_suhu);
    		}
                elseif ($poli == "B02"){
    			include(detail_grafik_ibu);
    		}
                elseif ($poli == "B03"){
    			include(detail_grafik_bayi);
    		}
                elseif ($poli == "C03"){
    			include(detail_ringkasan_masuk_keluar);
    		}
                elseif ($poli == "D04"){
    			include(detail_dokumen_surat_pengantar);
    		}
                elseif ($poli == "E05"){
    			include(detail_riwayat_penyakit);
    		}
                elseif ($poli == "F01"){
    			include(detail_catatan_kebidanan);
    		}
                elseif ($poli == "F02"){
    			include(detail_catatan_bayi_baru);
    		}
                elseif ($poli == "F03"){
    			include(detail_catatan_harian);
    		}
                elseif ($poli == "G02"){
    			include(detail_catatan_laporan_pembedahan);
    		}
                elseif ($poli == "K02"){
    			include(detail_hasil_radiologi);
    		}
                elseif ($poli == "J10"){
    			include(detail_lembar_konsultasi);
    		}
                elseif ($poli == "G03"){
    			include(detail_alat_pembedahan);
    		}
                elseif ($poli == "I02"){
    			include(detail_pasien_anak);
    		}
                elseif ($poli == "F04"){
    			include(detail_catatan_perkembangan_bayi);
                }
                elseif ($poli == "K03"){
    			include(detail_hasil_ekg);
                }
                 elseif ($poli == "I03"){
    			include(detail_obstetri);
                }
                 elseif ($poli == "H03"){
    			include(detail_pemakaian_alat_keperawatan);
                }
                elseif ($poli == "H01"){
    			include(detail_asuhan_keperawatan);
//                }
//                elseif ($poli == "I01"){
//    			include(detail_pengawasan_khusus_pasien_dewasa);
                }
                elseif ($poli == "K01"){
    			include(detail_hasil_labor_patologi);
                }
                elseif ($poli == "K04"){
    			include(detail_hasil_usg);
                }
                elseif ($poli == "I01"){
    			include(detail_pasien_dewasa);
                }
                elseif ($poli == "H02"){
       			include(detail_catatan_proses_keperawatan);
                }
    		else{
                    
    			include(detail_laboratorium);
    		}
    		
			}else {
				echo"<div align=center class=form_subtitle1>RIWAYAT PENYAKIT PASIEN</div>";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
				$sql = "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,C.TDESC,D.NAMA,A.ID_POLI::text,a.oid ". 
					   "FROM C_VISIT A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   "LEFT JOIN RS00001 C ON A.ID_POLI = C.TC_POLI AND C.TT='LYN'".
					   "LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID ".
					   "LEFT JOIN RS00001 E ON A.ID_KONSUL = E.TC AND E.TT='LYN'".
					   "WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_POLI != 100
						GROUP BY A.NO_REG,A.TANGGAL_REG,C.TDESC,D.NAMA,A.ID_POLI,a.oid
						union 
						SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,'RAWAT INAP - '||C.TDESC,D.NAMA,A.ID_RI,a.oid
						FROM C_VISIT_RI A 
						LEFT JOIN RS00006 B ON A.NO_REG=B.ID 
						LEFT JOIN RS00001 C ON A.ID_RI::text = C.TC::text AND C.TT='LRI'
						LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID 
						WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_RI::text != 100::text
						GROUP BY A.NO_REG,A.TANGGAL_REG,C.TDESC,D.NAMA,A.ID_RI,a.oid
						union 
						SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,'PELAYANAN OPERASI',D.NAMA,'209',a.oid
						FROM C_VISIT_OPERASI A 
						LEFT JOIN RS00006 B ON A.NO_REG=B.ID 
						LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID 
						WHERE B.MR_NO = '".$_GET["mr"]."'
						GROUP BY A.NO_REG,A.TANGGAL_REG,D.NAMA,a.oid
                                            ";
					
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	$t->ColHidden[6]= true;
			   	$t->ColHidden[1]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("TANGGAL PEMERIKSAAN","","WAKTU KUNJUNGAN","KLINIK","DOKTER PEMERIKSA","DETAIL");
			   	$t->ColAlign = array("center","center","center","left","left","left","center","center");
				$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat_klinik&act=detail_klinik&polinya=<#5#>&mr=".$_GET["mr"]."&rg=<#0#>&oid=<#6#>'>".icon("view","View")."</A>";	
				$t->execute();
				
				echo"<br>";
         		echo"</div>";
				echo "</td></tr></table></div>";
    	
			}
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
    
    	echo"<br>";
    	//$laporan = getFromTable("select tdesc from rs00001 where tt='LRI' and tc = '".$_SESSION[SELECT_LAP]."'");
    	$f = new Form("actions/p_laboratorium.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new1");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("list","unit_rujukan");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_tanggal_reg",$d6["tanggal_reg"]);
				    $f->hidden("f_id_poli",$_GET["poli"]);
				    $f->hidden("f_user_id",$_SESSION[uid]);
				    $f->hidden("status_akhir",$_GET["status_akhir"]);
				    
					echo"<br>";
					$tipe = getFromTable("select status_akhir_pasien from rs00006 where id='".$_GET["rg"]."'");
				    $f->PgConn=$con;
					$f->selectSQL("status_akhir","Status Akhir Pasien", "select '' as tc, '' as tdesc union select tc , tdesc from rs00001 where tt='SAP' and tc not in ('000')", $tipe,$ext);
				    $f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
				    $f->execute();
				    
			}
				        	
    }elseif ($_GET["list"] == "konsultasi"){
    	$T->show(7);
    	echo"<br>";
    	
    	//$laporan = getFromTable("select tdesc from rs00001 where tt='LRI' and tc = '".$_SESSION[SELECT_LAP]."'");
    	$f = new Form("actions/p_laboratorium.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new2");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("list","konsultasi");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli",$_GET["poli"]);
				    $f->hidden("f_tanggal_reg",$d6["tanggal_reg"]);
				    $f->hidden("f_user_id",$_SESSION[uid]);
				    $f->hidden("konsultasi",$_GET["konsultasi"]);
				    
					echo"<br>";
					$konsul = getFromTable("select id_konsul from c_visit where no_reg='".$_GET["rg"]."' and id_poli='".$_GET["poli"]."'");
				    $f->PgConn=$con;
					$f->selectSQL("konsultasi","Unit Yang Dituju", "select tc,tdesc from rs00001 where tt='LYN' and tc not in ('000','100','111','201','202','206','207','208') order by tdesc",$konsul,$ext);
				    $f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
				    $f->execute();
				echo "<b>Pasien di Konsul ke Poli:</b><br>";
					$t = new PgTable($con, "100%");
					$t->SQL = "select b.tdesc, a.oid from c_visit a left join rs00001 b on b.tc=a.id_konsul and b.tt='LYN'  where  a.no_reg='".$_GET[rg]."' and a.id_poli='".$_GET["poli"]."' and a.id_konsul != '' ";
					$t->setlocale("id_ID");
					$t->ShowRowNumber = true;
					$t->ColAlign = array("LEFT","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
					$t->RowsPerPage = $ROWS_PER_PAGE;
					$t->ColFormatHtml[1] = "<A CLASS=SUB_MENU1 HREF='actions/p_laboratorium.delete.php?p=$PID&oid=<#1#>&tbl=konsultasi&mr=".$_GET[mr]."&rg=".$_GET[rg]."&f_id_poli=".$_GET["poli"]."'>". icon("delete","Edit Status pekerjaan")."</A>";
					$t->ColHeader = array("KONSUL KE", "HAPUS");
					$t->execute();
		echo"<br><font color=black>&nbsp;* Catatan : Hasil Pemeriksaan Pasien harus diisi minimal Dokter Pemeriksa</font><br>";
    }else {       //pemeriksaan
    	if(!$GLOBALS['print']){
    	$T->show(0);
    	} /*else{}
  	
    		$r1 = pg_query($con, "select b.*, b.parameter,b.satuan,b.rentang_normal ".
        	       	    "from rs_grup_lab a ".
		            "left join c_pemeriksaan_lab b on b.id::character varying = a.lab_item ".
        	            "where a.no_reg='$reg' and a.lab_item=b.id::character varying ");
            $d1 = pg_fetch_object($r1);
            pg_free_result($r1); */	
	if($_GET["editlab"]){
		$_SESSION["SELECT_LAB"]='';
	}else if($_GET["deletelab"]){
		pg_query($con, "delete from c_catatan where id = '" . $_GET["id"] . "' and item_id = '" . $_GET["item_id"] . "'");
	}
	if ($_SESSION["SELECT_LAB"]) {
            $r1 = pg_query($con, "select * from c_pemeriksaan_lab where id = '" . $_SESSION["SELECT_LAB"] . "'");
            $d1 = pg_fetch_object($r1);
            pg_free_result($r1);
        }
	echo '<form action="actions/p_laboratoriumLB_insert.php">';
	echo '<br/>';
	
            $t = new BaseTable("100%");
            $t->printTableOpen();
            $t->printTableHeader(Array("KODE","JENIS PAKET"));
			echo "<form action=$SC>";
            echo "<input type=hidden name=p value=$PID>";
            echo "<input type=hidden name=httpHeader value=1>";
            echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='" . $_GET["rg"] . "'>";
            echo "<INPUT TYPE=HIDDEN NAME=list VALUE='pemeriksaan'>";
            echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='" . $_GET["mr"] . "'>";
            echo "<INPUT TYPE=HIDDEN NAME=poli VALUE='" . $_GET["mPOLI"] . "'>";
            //echo "<input type=hidden name=status value=".$_GET[status].">";
            $t->printRow2(
                    Array(
                "<input readonly type=text size=15 maxlength=10 name=id style='text-align:center' value=$d1->id>" . "&nbsp;<a href='javascript:selectLab()' >" . icon("view") . "</a>",
                "<input readonly type=text size=30 maxlength=10 name=parameter style='text-align:center' value=$d1->parameter>"), Array("CENTER",
                "CENTER")
            );
            echo "</FORM>";
        ?>
            <!--tbody>
                <tr>
                    <td>
                        <input type="hidden" name="id" id="id" value="">
                        <input type="text" name="parameter" id="parameter" size="30" value="">
                    </td>
                    <td><input type="text" name="hasil" id="hasil" size="20" value="" style="text-align: right;"> </td> 
                    <td><input type="text" name="rentang_normal" id="rentang_normal" size="20" value="" style="text-align: right;" disabled></td>
		    <td><input type="text" name="satuan" id="satuan" size="10" value="" style="text-align: right;" disabled></td>
		    <td><input type="text" name="keterangan" id="keterangan" size="20" value="" style="text-align: left;"></td>
                    
                    <td><input type="button" id="save-obat" value=" OK " /></td>
                </tr>
            </tbody-->
<?php
            $t->printTableClose(); 
	//echo $_SESSION["SELECT_LAB"];
	echo '<form action="actions/p_laboratoriumLB_insert.php">';
        echo '<input type="hidden" name="rg" id="rg" value="'.$_GET['rg'].'" />';
        echo '<input type="hidden" name="mr" id="mr" value="'.$_GET['mr'].'" />';
        echo '<input type="hidden" name="SC" id="SC" value="'.$SC.'" />';
		?>

    <table width="100%" border="1">
        <tr>
            <td class="TBL_HEAD">KODE</td>
            <td class="TBL_HEAD">JENIS PEMERIKSAAN</td>
            <td class="TBL_HEAD">HASIL PEMERIKSAAN</td>
            <td class="TBL_HEAD">RENTANG NORMAL</td>
            <td class="TBL_HEAD">SATUAN</td>
            <td class="TBL_HEAD">KETERANGAN</td>
        </tr>
	<?php
		if($_GET["editlab"]){
			$SQL2="select a.id, a.parameter, a.satuan, a.rentang_normal,b.hasil, b.keterangan from c_pemeriksaan_lab a left join c_catatan b on a.id= b.item_id where b.id =".$_GET["id"]."
			and b.item_id=".$_GET["item_id"]; 
		}else{
			$SQL2="select id, parameter,satuan,rentang_normal
		from c_pemeriksaan_lab where substr(hierarchy,0,4)=substr('".$d1->hierarchy."',0,4) and id !='".$d1->id."'
		";
		}
        @$r2 = pg_query($con, $SQL2);
        @$n2 = pg_num_rows($r2);

        $max_row = 30;
        $mulai = $HTTP_GET_VARS["rec"];
        if (!$mulai) {
            $mulai = 1;
        }
		
		$no=0;
        $row2 = 0;
        $i2 = 1;
        $j2 = 1;
        $last_id = 1;
        while (@$row2 = pg_fetch_array($r2)) {
            if (($j2 <= $max_row) AND ($i2 >= $mulai)) {
                $no = $i2;
                ?>
                <tr>
                    <td class="TBL_BODY" align="center" width="3%"><?=$row2["id"]?>
					<input type="hidden" size="25" id="id<?=$no?>" name="id<?=$no?>" value="<?=$row2["id"]?>">
					</td>
                    <td class="TBL_BODY" align="left"><?=$row2["parameter"]?>
					<input type="hidden" size="25" id="jenis<?=$no?>" name="jenis<?=$no?>" value="<?=$row2["parameter"]?>">
					</td>
                    <td class="TBL_BODY" width="" align="center">
						<?php if($_GET["editlab"]){ ?>
						<input type="hidden" size="25" id="id_lab" name="id_lab" value="<?=$_GET["id"]?>">
						<input type="hidden" size="25" id="edit" name="edit" value="edit">
                        <input type="text" size="25" id="hasil<?=$no?>" name="hasil<?=$no?>" value="<?=$row2["hasil"]?>">
						<?php } else {?>
                        <input type="text" size="25" id="hasil<?=$no?>" name="hasil<?=$no?>">
						<?php }?>
                   </td>
                    <td class="TBL_BODY" align="center"><?=$row2["rentang_normal"]?>
					<input type="hidden" size="25" id="rentang_normal<?=$no?>" name="rentang_normal<?=$no?>" value="<?=$row2["rentang_normal"]?>">
					</td>
                    <td class="TBL_BODY" align="center"><?=$row2["satuan"]?>
					<input type="hidden" size="25" id="satuan<?=$no?>" name="satuan<?=$no?>" value="<?=$row2["satuan"]?>">
					</td>
					<td class="TBL_BODY" width="" align="center">
						<?php if($_GET["editlab"]){?>
						<input type="text" size="25" id="ket<?=$no?>" name="ket<?=$no?>" value="<?=$row2["keterangan"]?>">
						<?php } else {?>
                        <input type="text" size="25" id="ket<?=$no?>" name="ket<?=$no?>">
						<?php }?>
                   </td>
                </tr>
                <?PHP
                $j2++;
            }
            $i2++;
        }
        ?>
                <tr>
                    <td class="TBL_BODY" colspan=8 align="right">
					<input type="hidden" size="25" name="no" value="<?=$no?>">
					<input type=submit value='Simpan' >
					</td>
                </tr>
    </table>
<?php
	echo '</form>';
   echo '<form id="list_obat_created" method="GET" action="includes/cetak.rincian_obat_selected.php" target="_blank">';
        echo '<input type="hidden" name="rg" value="'.$_GET['rg'].'" /> ';
        echo '<div id=list_pemakaian_obat></div>';
        echo '</form>';

	$result = pg_query($con, "SELECT DISTINCT c_pemeriksaan_lab.id,c_pemeriksaan_lab.parameter,c_pemeriksaan_lab.satuan,c_pemeriksaan_lab.rentang_normal
    FROM c_pemeriksaan_lab 
    WHERE is_group='N' ORDER BY c_pemeriksaan_lab.parameter ASC");

?>
<script>
    $(function() {
	var rg = $('#rg').val();
        //$('#list_item_pemeriksaan').load('actions/320LB_insert.php?rg='+rg);
	$('#list_pemakaian_obat').load('actions/320LB_insert.php?rg='+rg);	

	var data = [
            <?php 
            while ($row = pg_fetch_array($result))
            {//View Table Data
                $id = $row["id"];					//^c_pemeriksaan_lab.id
                $parameter = str_replace("'","/",$row["parameter"]);	//^c_pemeriksaan_lab.parameter
                $satuan = $row["satuan"];			//^c_pemeriksaan_lab.satuan
		$rentangnormal = $row["rentang_normal"];	//^c_pemeriksaan_lab.rentang_normal
                echo "{";
                echo "id: ".$id .", ";
                echo "value: '".$parameter ."', ";
                echo "satuan: '".$satuan ."', ";
                echo "rentang_normal: '".$rentangnormal ."',";
                echo "},";
            }//View Table Data
            ?>
                        ""
        ];
	//Auto Complete Start
	$( "#parameter" ).autocomplete({
            source: data,
            messages: {
			noResults: "",
			results: function( amount ) {
				
			}
		},
            minLength: 2,
            select: function (event, ui) {//Check Data View
                $('#id').val('');
                $('#satuan').val('');
                $("#rentang_normal").val('');
                var pemId = ui.item.id;
                var pemParameter = ui.item.value;
                var pemSatuan = ui.item.satuan;
                var pemRentangnormal = ui.item.rentang_normal;
                
                $('#parameter').val(pemParameter);
                $('#id').val(pemId);
                $("#satuan").val(pemSatuan);
                $("#rentang_normal").val(pemRentangnormal);             
            }//Check Data View
        });//Auto Complete End
	
	$("#hasil").keyup( function(){
            var pemHasil = $('#hasil').val();
        });//Hasil
	
	$("#keterangan").keyup( function(){
            var pemKeterangan = $('#keterangan').val();
        });	
	
	$('#save-obat').click(function(){//Save Data
            valRg		= $('#rg').val();//^ Nomor Registrasi
            valpemId		= $('#id').val();
            valHasil		= $('#hasil').val();
            if(valHasil == ''){
                alert('Hasil Pemeriksaan belum diisi !');
                return false;
            }
	    valKeterangan	= $('#keterangan').val();
            
            $.post('actions/320LB_insert.php?rg='+valRg,
                        {
                            rg: valRg,
                            id: valpemId,
                            hasil: valHasil,
			    keterangan: valKeterangan                            
                        }
                    ).success(function(data){
                                        $('#id').val('');
                                        $('#parameter').val('');
                                        $("#hasil").val('');
                                        $("#satuan").val('');
                                        $("#rentang_normal").val('');
					$("#keterangan").val('');
                                        $('#list_pemakaian_obat').empty();
                                        $('#list_pemakaian_obat').html(data);
                                        $('#save-obat').val('OK');
                                     });            
        })//Save Data

     });

	function edit_data_obat(id){
        var pemId = $('#id'+id).val(); 
        var pemParameter = $('#parameter'+id).text();
        var hasil = $('#hasil'+id).text();
	var satuan = $('#satuan'+id).text();
	var rentang_normal = $('#rentang_normal'+id).text();
	var ket = $('#ket'+id).text();
        //$('#save-obat').val('Update');
   
        $('#id1').val( pemId );
        $('#parameter1').val( pemParameter );
        $('#hasil1').val(hasil);
	$('#satuan1').val(satuan);
	$('#rentang_normal1').val(rentang_normal);
	$('#ket1').val(ket);
		/*if(tipe == 'RCK'){
			$('#is_racikan').val(1);
		}else{
			$('#is_racikan').val(0);
		}*/
    }
	 function delete_data_obat(id){
         var valRg   = $('#rg').val();
         var pemId  = $('#id_'+id).val();
         var hasil     = $('#hasil_'+id).text();
         
         $.post('actions/320LB_insert.php?rg='+valRg+'&del=true',
                        {
                            id: pemId,
                            hasil: hasil,
                            id: id
                        }
                    ).success(function(data){ 
                                        $('#list_pemakaian_obat').empty();
                                        $('#list_pemakaian_obat').html(data);
										$('#list_pemakaian_obat').load('actions/320LB_insert.php?rg=<?php echo $_GET["rg"]?>');
                                     });   
    }

function cetakkwitansi2(tag) {
        sWin = window.open('includes/cetak.pemeriksaan_rawatjalan.php?rg=<?php echo $_GET['rg']?>&kas=<?php echo $_GET['rg']?>', 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
        sWin.focus();
    }
</script>
<?

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
		echo "function selectLab() {\n";
	   	echo "    sWin = window.open('popup/laboratorium.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
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
		
	$SQLSTR = 	"select distinct a.mr_no,a.id,upper(a.nama)as nama,tanggal(b.tanggal_konsul,0)||' '||to_char(b.waktu_konsul,'hh24:mi:ss') as tgl,a.alm_tetap,a.kesatuan,a.tdesc,CASE WHEN a.rawat_inap='I' THEN 'RAWAT INAP'
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

/*		$tglhariini = date("Y-m-d", time());
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
	    $t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
	    $t->RowsPerPage = $ROWS_PER_PAGE;
	    $t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&list=layanan&sub2=nonpaket'><#2#>";
	    //(awal)$t->ColFormatHtml[8] = "<A HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&act=periksa'><INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Periksa' >";
	   	//$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL  REG","WAKTU REG","NAMA PASIEN","PANGKAT","NRP/NIP","KESATUAN","LOKET","TIPE PASIEN","STATUS");
	   	$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","WAKTU KONSUL","ALAMAT","PEKERJAAN","TIPE PASIEN","UNIT ASAL","STATUS");
	    $t->ColColor[8] = "color";
	    //$t->ColRowSpan[2] = 2;
	    $t->execute();
	    echo"<br><div class=NOTE>Catatan : Daftar pasien di urut berdasarkan no antrian</div><br>";	
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
		
	$SQLSTR = 	"select distinct a.mr_no,a.id,upper(a.nama)as nama,tanggal(a.tanggal_reg,0)||' '||to_char(waktu_reg,'hh24:mi:ss') as tgl,a.alm_tetap,a.kesatuan,a.tdesc,a.statusbayar
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

	//Filter Data Pasien berdasarkan login dokter nya Hosana Lippo Cikarang
	$DokterLogin = getFromTable("select id_dokter from rs99995 where uid='" . $_SESSION["uid"] . "'");
		//var_dump($DokterLogin);
		if($DokterLogin>0){
		$SQLWHERE = 
			" AND a.id_dokter = '$DokterLogin' AND a.TANGGAL_REG = '$tglhariini'";
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
	    $t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
	    $t->RowsPerPage = $ROWS_PER_PAGE;
	    $t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&list=layanan&sub2=nonpaket'><#2#>";
	    //(awal)$t->ColFormatHtml[8] = "<A HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&act=periksa'><INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Periksa' >";
	   	//$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL  REG","WAKTU REG","NAMA PASIEN","PANGKAT","NRP/NIP","KESATUAN","LOKET","TIPE PASIEN","STATUS");
	   	$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","WAKTU REGISTRASI","ALAMAT","PEKERJAAN","TIPE PASIEN","STATUS");
	    $t->ColColor[7] = "color";
	    //$t->ColRowSpan[2] = 2;
	    $t->execute();
	    echo"<br><div class=NOTE>Catatan : Daftar pasien di urut berdasarkan no antrian</div><br>";
    }
	
}
  
?>

<script type="text/javascript">
    $(function() {
		$("#persen").keyup( function(){
            var hargaSatuan1 = parseFloat($('#harga').val());
            var hargaPersen = parseFloat($('#persen').val());
            
            if(hargaPersen > 100){
                alert('diskon tidak boleh lebih dari harga satuan !');
                $('#persen').val(0);
                $('#diskon').val(0);
                return false;
            } else {
            	jumlahHargaPersen = (hargaPersen/100)*hargaSatuan1;
            	$('#diskon').val(jumlahHargaPersen);
            }
        });
        
        $("#diskon").keyup( function(){
        	var hargaSatuan2 = parseFloat($('#harga').val());
            var hargaDiskon = parseFloat($('#diskon').val());
            
            if(hargaDiskon > hargaSatuan2){
                alert('diskon tidak boleh lebih dari harga satuan !');
                $('#persen').val(0);
                $('#diskon').val(0);
                return false;
            } else {
            	jumlahHargaDiskon = (hargaDiskon/hargaSatuan2)*100 ;
            	$('#persen').val(jumlahHargaDiskon);
            } 
        });
    });
</script>
<?php
function tanggal($tanggal) {
        $arrTanggal = explode('-', $tanggal);

        $hari = $arrTanggal[2];
        $bulan = $arrTanggal[1];
        $tahun = $arrTanggal[0];

        $result = $hari . ' ' . bulan($bulan) . ' ' . $tahun;

        return $result;
    }

function bulan($params) {
    switch ($params) {
        case 1:
            $bln = "Jan";
            break;
        case 2:
            $bln = "Peb";
            break;
        case 3:
            $bln = "Mar";
            break;
        case 4:
            $bln = "Apr";
            break;
        case 5:
            $bln = "Mei";
            break;
        case 6:
            $bln = "Jun";
            break;
        case 7:
            $bln = "Jul";
            break;
        case 8:
            $bln = "Agu";
            break;
        case 9:
            $bln = "Sep";
            break;
        case 10:
            $bln = "Okt";
            break;
        case 11:
            $bln = "Nop";
            break;
        case 12:
            $bln = "Des";
            break;
            break;
    }
    return $bln;
}

?>
