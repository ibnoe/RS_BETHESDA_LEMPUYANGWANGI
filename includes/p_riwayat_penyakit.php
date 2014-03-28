
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
		// App,02-06-2007 --> Developer

session_start();
$PID = "p_riwayat_penyakit";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/visit_setting.php");

// Start Ambil data dokter DPJP dari perujuk (Rawat Jalan atau IGD)
$sqlDokterDPJP_Rujukan       = pg_query($con,"SELECT rs00017.id, rs00017.nama FROM rs00006 JOIN rs00017 ON rs00017.id = rs00006.dokter_dpjp_id WHERE rs00006.id = '".$_GET['rg']."'");
$dokterDPJP_Rujukan          = pg_fetch_array($sqlDokterDPJP_Rujukan);
$dokterDPJPID_Rujukan_Id     = $dokterDPJP_Rujukan['id'];
$dokterDPJPID_Rujukan_Nama   = $dokterDPJP_Rujukan['nama'];
// End Ambil data dokter DPJP dari perujuk (Rawat Jalan atau IGD)
            
//--fungsi column color-------------- 
function color( $dstr, $r ) {
	    //if ($dstr[7] == '-') {
	    	if ($dstr[10] == 'Sudah Diperiksa' ){
	    		return "<font color=#FF33FF>{$dstr[$r]}</font>";
	    	}elseif ($dstr[10] == 'Menunggu'){
	    		return "<font color=#66FFCC>{$dstr[$r]}</font>";
	    	}elseif ($dstr[10] == 'Bayar Angsur'){
	    		return "<font color=#FF9900>{$dstr[$r]}</font>";
	    	}elseif ($dstr[10] == 'Bayar Lunas'){
	    		return "<font color=#FF3300>{$dstr[$r]}</font>";
	    	}
	    	return "<font color=#0000FF>{$dstr[$r]}</font>";
	    //}else return $dstr[$i];
}
//-------------------------------       	
$_GET["mPOLI"]=$setting_ri["riwayat_penyakit_pemeriksaan_fisik"];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];

		if (isset($_GET["del"])) {
		    $temp = $_SESSION["layanan"];
		    unset($_SESSION["layanan"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del"]) $_SESSION["layanan"][count($_SESSION["layanan"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"]."&sub=layanan&sub2=nonpaket");
		    	exit;
		}elseif (isset($_GET["del1"])) {
			$temp = $_SESSION["layanan2"];
			unset($_SESSION["layanan2"]);
			foreach ($temp as $k => $v) {
				if ($k != $_GET["del1"])
					$_SESSION["layanan2"][count($_SESSION["layanan2"])] = $v;
			}
			header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"] . "&mr=" . $_GET["mr"] . "&ri=".$_GET["ri"]."&sub=layanan&sub2=paket");
			exit;    
		} elseif (isset($_GET["del-icd"])) {
		    $temp = $_SESSION["icd"];
		    unset($_SESSION["icd"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-icd"]) $_SESSION["icd"][count($_SESSION["icd"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    	exit;
		} elseif (isset($_GET["del-icd9"])) {
		    $temp = $_SESSION["icd9"];
		    unset($_SESSION["icd9"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-icd9"]) $_SESSION["icd9"][count($_SESSION["icd9"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd9&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=icd9");
		    	exit;
		} elseif (isset($_GET["del-obat"])) {
		    $temp = $_SESSION["obat"];
		    unset($_SESSION["obat"]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-obat"]) $_SESSION["obat"][count($_SESSION["obat"])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=obat");
		    	exit;
		    // Tambahan BHP    
		} elseif (isset($_GET["del-obat2"])) {
			$temp = $_SESSION["obat2"];
			unset($_SESSION["obat2"]);
			foreach ($temp as $k => $v) {
				if ($k != $_GET["del-obat2"])
					$_SESSION["obat2"][count($_SESSION["obat2"])] = $v;
			}
			header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"] . "&mr=" . $_GET["mr"] . "&ri=".$_GET["ri"]."&sub=layanan&sub2=bhp");
			exit;

			// ==============================================       
		} elseif (isset($_GET["del-pjm"])) {
		    $temp = $_SESSION["pjm"][$_GET["del-pjm"]];
		    unset($_SESSION["pjm"][$_GET["del-pjm"]]);
		    foreach ($temp as $k => $v) {
		        if ($k != $_GET["del-emp"])
		            $_SESSION["pjm"][$_GET["del-pjm"]][count($_SESSION["pjm"][$_GET["del-pjm"]])] = $v;
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=pjm");
		    	exit;
		    
		} elseif (isset($_GET["s2note"])) {
		    $_SESSION["s2note"] = $_GET["s2note"];
		    	header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=icd");
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
		        $_SESSION["obat"][$cnt]["total"]  = $d->harga * $_GET["jumlah_obat"];
		        //$_SESSION["obat"][$cnt]["satuan"] = $d->satuan;
		        unset($_SESSION["SELECT_OBAT"]);
		    }
		    	header("Location: $SC?p=" . $_GET["p"] . "&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=obat");
		    	exit;
		// Tambahan BHP 
		} elseif (isset($_GET["obat2"])) {
			$r = @pg_query($con, "SELECT * FROM RSV0004 WHERE ID = '" . $_GET["obat2"] . "'");
			$d = @pg_fetch_object($r);
			@pg_free_result($r);

			if (is_array($_SESSION["obat2"])) {
				$cnt = count($_SESSION["obat2"]);
			} else {
				$cnt = 0;
			}

			$bangsal=getFromTable("select bangsal from rsv_akomodasi_inap where no_reg='$rg' group by bangsal");
			$cek_qty = getFromTable("select 'qty_'||tc from rs00001 where tt='GDP' and upper(tdesc) like '%".strtoupper($bangsal)."%' ");

			if (!empty($d->obat)) {
				$_SESSION["obat2"][$cnt]["id"] = $_GET["obat2"];
				$_SESSION["obat2"][$cnt]["desc"] = $d->obat;
				$_SESSION["obat2"][$cnt]["satuan"] = $_GET["satuan"];
				$_SESSION["obat2"][$cnt]["jumlah"] = $_GET["jumlah_obat"];
				$_SESSION["obat2"][$cnt]["harga"] = $d->harga;
				$_SESSION["obat2"][$cnt]["total"] = $d->harga * $_GET["jumlah_obat"];
				$_SESSION["obat2"][$cnt]["penjamin"] = $_GET["penjamin_obat"];
				$_SESSION["obat2"][$cnt]["stok"] = $_GET["gudang"];
				$_SESSION["obat2"][$cnt]["is_racikan"] = $_GET["is_racikan"];
				$_SESSION["obat2"][$cnt]["nip"] = '0';
				unset($_SESSION["SELECT_OBAT2"]);
			}
			header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"] . "&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=layanan&sub2=bhp");
			exit;

			// ==============================================  
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
		    header("Location: $SC?p=" . $_GET["p"] . "&list=icd&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=icd");
		    exit;
		} elseif (isset($_GET["icd9"])) {
                    
		    $r = pg_query($con,"SELECT * FROM icd_9 WHERE kode = '" . $_GET["icd9"] . "'");
		    $d = pg_fetch_object($r);
		    pg_free_result($r);
		    if (is_array($_SESSION["icd9"])) {
		        $cnt = count($_SESSION["icd9"]);
		    } else {
		        $cnt = 0;
		    }
		    
		    if (strlen($d->nama) > 0) {
		        $_SESSION["icd9"][$cnt]["id"]   = $_GET["icd9"];
		        $_SESSION["icd9"][$cnt]["desc"] = $d->nama;
		        unset($_SESSION["SELECT_ICD9"]);
		    }
		    header("Location: $SC?p=" . $_GET["p"] . "&list=icd9&rg1=" . $_GET["rg1"]."&rg=" . $_GET["rg"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=icd9");
		    exit;
		} elseif (isset($_GET["layanan"])) {

			$r = pg_query($con, "SELECT * FROM RSV0034 WHERE ID = '" . $_GET["layanan"] . "'");
			$d = pg_fetch_object($r);
			pg_free_result($r);

			$gol_tindakan = getFromTable("select golongan_tindakan_id from rs00034 where id='" . $_GET["layanan"] . "'");
			// $is_range = $d->harga_atas > 0 || $d->harga_bawah > 0;

			if ($d->id) {
				//   if (($is_range && isset($_GET["harga"])) || (!$is_range)) {
				if (is_array($_SESSION["layanan"])) {
					$cnt = count($_SESSION["layanan"]);
				} else {
					$cnt = 0;
				}

				$dokter = getFromTable("select nama from rs00017 where id = '" . $_SESSION[SELECT_EMP] . "'");
				$harga = $is_range ? $_GET["harga"] : $d->harga;
				$_SESSION["layanan"][$cnt]["id"] = str_pad($_GET["layanan"], 5, "0", STR_PAD_LEFT);

				if ($d->klasifikasi_tarif)
					$embel = " - " . $d->klasifikasi_tarif;
				$_SESSION["layanan"][$cnt]["nama"] = $d->layanan . $embel;
				$_SESSION["layanan"][$cnt]["jumlah"] = $_GET["jumlah"];
				$_SESSION["layanan"][$cnt]["satuan"] = $d->satuan;
				$_SESSION["layanan"][$cnt]["harga"] = $harga;
				$_SESSION["layanan"][$cnt]["diskon"] = (double) $_GET['diskon'] * $_GET["jumlah"];
				$_SESSION["layanan"][$cnt]["diskon_per"] = (double) $_GET['diskon_per'];
				$total = ($harga * $_GET["jumlah"]) - ($_GET["persen"]/100) * ($harga * $_GET["jumlah"]);
				$harga_cito = ($_GET['cito']=='on') ? 0.25 * $total : 0;
				$_SESSION["layanan"][$cnt]["harga_cito"] = $harga_cito;
				$_SESSION["layanan"][$cnt]["total"]  = $total + $harga_cito;
				$_SESSION["layanan"][$cnt]["dokter"] = $dokter;
				$_SESSION["layanan"][$cnt]["cito"]  = ($_GET['cito']=='on') ? 'YA' : 'TIDAK';
				$_SESSION["layanan"][$cnt]["nip"] = $_SESSION[SELECT_EMP];
			}

			unset($_SESSION["SELECT_LAYANAN"]);
			unset($_SESSION["SELECT_EMP"]);

			header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"] . "&rg1=" . $_GET["rg1"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sub=layanan&sub2=nonpaket");
			exit;
		}
		/* Untuk menambahkan hapus Paket layanan */
		/* Agung Sunandar 16:28 26/06/2012       */

		elseif (isset($_GET["layanan2"])) {

			$r = pg_query($con, "SELECT * FROM rs99996 WHERE ID = '" . $_GET["layanan2"] . "'");
			$d = pg_fetch_object($r);
			pg_free_result($r);


			if ($d->id) {
				if (is_array($_SESSION["layanan2"])) {
					$cnt = count($_SESSION["layanan2"]);
				} else {
					$cnt = 0;
				}

				//$sumber_pendapatan_id = getFromTable("select sumber_pendapatan_id from rsv0034 where id = '" . $_SESSION["SELECT_LAYANAN"] . "'");

				$dokter = getFromTable("select nama from rs00017 where id = '" . $_SESSION[SELECT_EMP2] . "'");
				$harga = $d->harga_paket;
				$_SESSION["layanan2"][$cnt]["id"] = str_pad($_GET["layanan2"], 5, "0", STR_PAD_LEFT);
				$cek_qty = getFromTable("select 'qty_'||tc from rs00001 where tt='GDP' and tc_poli='" . $_GET["poli"] . "' ");
				//if ($d->klasifikasi_tarif) $embel= " - ".$d->klasifikasi_tarif;
				$_SESSION["layanan2"][$cnt]["nama"] = $d->description;
				$_SESSION["layanan2"][$cnt]["jumlah"] = $_GET["jumlah"];
				$_SESSION["layanan2"][$cnt]["satuan"] = "KALI";
				$_SESSION["layanan2"][$cnt]["harga"] = $d->harga_paket;
				$_SESSION["layanan2"][$cnt]["total"] = $d->harga_paket * $_GET["jumlah"];
				$_SESSION["layanan2"][$cnt]["dokter"] = $dokter;
				$_SESSION["layanan2"][$cnt]["stok"] = $_GET["gudang"];
				$_SESSION["layanan2"][$cnt]["nip"] = $_SESSION[SELECT_EMP2];
			}
			unset($_SESSION["SELECT_LAYANAN2"]);
			unset($_SESSION["SELECT_EMP"]);
			unset($_SESSION["SELECT_EMP2"]);

			header("Location: $SC?p=" . $_GET["p"] . "&list=layanan&rg=" . $_GET["rg"] . "&rg1=" . $_GET["rg1"]."&ri=".$_GET["ri"]."&mr=" . $_GET["mr"] . "&sublayanan&sub2=paket");
			exit;
		}


unset($_GET["layanan"]);

$reg = (int) $_GET["rg"];
$reg1 = (int) $_GET["rg1"];

	$tab_disabled = array("pemeriksaan"=>true,"obat"=>true, "layanan"=>true, "icd"=>true, "icd9"=>true, "riwayat_klinik"=>true,"konsultasi"=>true);
	if ($_GET["act"] == "del" ) {
	$tab_disabled = array("pemeriksaan"=>false,"obat"=>false,  "layanan"=>false, "icd"=>false, "icd9"=>false, "riwayat_klinik"=>false,"konsultasi"=>false);
	$tab_disabled[$_GET["sub"]] = true;
	$tab_disabled[$_POST["sub"]] = true;
	}
	$T = new TabBar();
	$T->addTab("$SC?p=$PID&list=pemeriksaan&rg1=" . $_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr ", "Pemeriksaan Pasien"	, $tab_disabled["pemeriksaan"]);
	$T->addTab("$SC?p=$PID&list=layanan&rg1=" . $_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr&sub=layanan&sub2=nonpaket", "layanan / Tindakan"	, $tab_disabled["layanan"]);
	$T->addTab("$SC?p=$PID&list=icd&rg1=" . $_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr&sub=icd", "Pilih I C D"	, $tab_disabled["icd"]);
	$T->addTab("$SC?p=$PID&list=icd9&rg1=" . $_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr&sub=icd9", "Pilih I C D 9"	, $tab_disabled["icd9"]);
	//$T->addTab("$SC?p=$PID&list=riwayat&rg1=" . $_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr", "Riwayat Penyakit"	, $tab_disabled["riwayat"]);
	$T->addTab("$SC?p=$PID&list=riwayat_klinik&rg1=" 	.$_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr", "Riwayat Klinik"	, $tab_disabled["riwayat_klinik"]);
	$T->addTab("$SC?p=$PID&list=konsultasi&rg=$rg&rg1="	.$_GET["rg1"]."&rg=$rg&ri=".$_GET["mPOLI"]."&mr=$mr", "Konsultasi"	, $tab_disabled["konsultasi"]);

if ($reg > 0) {
	$r1 = pg_query($con,
	"select tdesc from rs00001 where tt='LRI' and tc='{$_GET["ri"]}'");
	$n1 = pg_num_rows($r1);
	if($n1 > 0) $d1 = pg_fetch_object($r1);
	pg_free_result($r1);
	
	title_print("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  $d1->tdesc");
                title_excel("p_riwayat_penyakit&tblstart=".$_GET['tblstart']."&list=".$_GET['list']."&rg1=".$_GET['rg1']."&ri=".$_GET['ri']."&act=".$_GET['act']."&mr=".$_GET['mr']."&rg=".$_GET['rg']."&oid=".$_GET['oid']."");

    if(getFromTable("SELECT poli FROM rs00006 WHERE id = '{$_GET['rg']}'")==210){
	$sql = "SELECT a.poli,b.nama, b.mr_no,a.id,tanggal(tanggal_reg,3),to_char(c.ts_check_in,'dd Mon YYYY')as tgl_masuk,
age(a.tanggal_reg::timestamp with time zone, b.tgl_lahir::timestamp with time zone) AS umur,to_char(c.ts_check_in,'DD MON YYYY')as tanggal_reg,
f.bangsal || '/' || e.bangsal || '/' || h.tdesc || '/' || d.bangsal AS bangsal_info,f.bangsal,
CASE WHEN b.jenis_kelamin::text = 'L'::text THEN 'Laki-laki'::text
            ELSE 'Perempuan'::text
        END AS jenis_kelamin, a.diagnosa_sementara,e.bangsal
 FROM rs00006 a 
 JOIN rs00002 b ON a.mr_no = b.mr_no
 left join rs00010 c on a.id = c.no_reg
 left join rs00012 d on c.bangsal_id = d.id 
 left join rs00012 e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' 
 left join rs00012 f on f.hierarchy = substr(e.hierarchy,1,3) || '000000000000'  
 left join rs00001 h on e.klasifikasi_tarif_id = h.tc and h.tt = 'KTR'
 WHERE a.id = '".$_GET['rg1']."' AND c.ts_calc_stop IS NULL";	
	}
    else{
	$sql="select DISTINCT a.id,a.mr_no,a.nama,a.umur,a.tgl_lahir,a.tmp_lahir,to_char(b.ts_check_in,'DD MON YYYY')as tanggal_reg,
		a.status_akhir,a.diagnosa_sementara, a.jenis_kelamin,a.pangkat_gol,a.nrp_nip,a.kesatuan,b.bangsal_id,
		e.bangsal || '/' || d.bangsal || '/' || g.tdesc || '/' || c.bangsal AS bangsal_info,e.bangsal,
		a.poli,a.rawatan,a.nama_ayah,a.agama,to_char(f.ts_check_in,'dd Mon YYYY')as tgl_masuk,to_char(f.ts_calc_stop,'dd Mon yyyy')as tgl_keluar 
		from rsv_pasien2 a 
		join rs00010 as b on a.id = b.no_reg 
		join rs00012 as c on b.bangsal_id = c.id 
		join rs00012 as d on d.hierarchy = substr(c.hierarchy,1,6) || '000000000' 
		join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,3) || '000000000000' 
		join rs00001 as g on d.klasifikasi_tarif_id = g.tc and g.tt = 'KTR'
		join rs00010 as f on f.no_reg = a.id 
	      where a.id = '".$_GET['rg1']."' AND b.ts_calc_stop IS NULL ";	
	}
	/*$sql="select a.id,a.mr_no,a.nama,a.umur,a.tgl_lahir,a.tmp_lahir,to_char(b.ts_check_in,'DD MON YYYY')as tanggal_reg,
				a.status_akhir,a.diagnosa_sementara, a.jenis_kelamin,a.pangkat_gol,a.nrp_nip,a.kesatuan,b.bangsal_id,e.bangsal,
				a.poli,a.rawatan,a.nama_ayah,a.agama,to_char(f.ts_check_in,'dd Mon YYYY')as tgl_masuk,to_char(f.ts_calc_stop,'dd Mon yyyy')as tgl_keluar 
				from rsv_pasien2 a 
				join rs00010 as b on a.id = b.no_reg join rs00012 as c on b.bangsal_id = c.id 
				join rs00012 as d on d.hierarchy = substr(c.hierarchy,1,6) || '000000000' 
				join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,3) || '000000000000' 
				join rs00010 as f on f.no_reg = a.id
				where a.id = '".(string)$_GET['rg']."'";*/


/*$sql = 
        "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, ".
        "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, ".
        "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, ".
        "    e.alm_tetap, e.kota_tetap, e.pos_tetap, e.tlp_tetap, ".
        "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
        "    c.tdesc AS penjamin, a.no_jaminan, a.rujukan, a.rujukan_rs_id, ".
        "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
        "    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, ".
        "        CASE ".
        "            WHEN a.rawat_inap = 'I' THEN 'Rawat Inap'  ".
        "            WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan' ".
        "            ELSE 'IGD' ".
        "        END AS rawatan, ".
        "        age(a.tanggal_reg , e.tgl_lahir ) AS umur, ".
	"	case when a.rujukan = 'Y' then 'Rujukan' ".
	"	     when a.rujukan ='U' then 'Unit Lain'  else 'Non-Rujukan' ".
        "       end as datang,  ".
        "   i.tdesc as  poli,to_char(n.ts_check_in,'dd Mon YYYY')as tgl_masuk,to_char(n.ts_calc_stop,'dd Mon yyyy')as tgl_keluar,j.bangsal_id,m.bangsal ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc and h.tt = 'JDP' ".
	"   left join rs00001 i on i.tc_poli = a.poli ".
	"   join rs00010 as j on a.id = j.no_reg join rs00012 as k on j.bangsal_id = k.id". 
	"   join rs00012 as l on l.hierarchy = substr(k.hierarchy,1,6) || '000000000'". 
	"   join rs00012 as m on m.hierarchy = substr(l.hierarchy,1,3) || '000000000000'". 
	"   join rs00010 as n on n.no_reg = a.id".
	"   WHERE a.id = '".(string)$_GET['rg']."'";*/
   

    $r = pg_query($con,$sql);
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
        $sqlGetDokterDPJP =	"SELECT(C.NAMA)AS dokter_dpjp FROM C_VISIT_RI A 
                LEFT JOIN RS00017 C ON A.VIS_1::text = C.ID::text
                LEFT JOIN RS00017 D ON A.VIS_2::text = D.ID::text
                LEFT JOIN RS00017 E ON A.id_dokter::text = E.ID::text
                WHERE A.ID_RI='{$_GET['ri']}'::text AND A.NO_REG='{$_GET['rg1']}'::text";
        $rGetDokterDPJP=pg_query($con,$sqlGetDokterDPJP);
        $d2GetDokterDPJP = pg_fetch_array($rGetDokterDPJP);
        
        if(!empty($d2GetDokterDPJP)){
            $dokterDPJPNama = $d2GetDokterDPJP['dokter_dpjp'];
        }else{
            $dokterDPJPNama = $dokterDPJPID_Rujukan_Nama;
        }
         
	echo "<hr noshade size='1'>";
		echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top>";
		$f = new ReadOnlyForm();
		$f->text("<b>"."Nama",$d->nama);
		$f->text("<b>"."No RM",$d->mr_no);
		$f->text("<b>"."No Reg", formatRegNo($_GET["rg1"]));
		$f->execute();
		echo "</td><td align=left valign=top>";
		$f = new ReadOnlyForm();
		$f->text("<b>"."NRP/NIP/Pangkat",$d->nrp_nip." / ".$d->pangkat_gol);
		$f->text("<b>"."Kesatuan",$d->kesatuan);
		$f->text("<b>"."Tanggal Masuk",getFromTable("SELECT tanggal(ts_check_in::date,3) FROM rs00010 WHERE id = (SELECT MIN(id) FROM rs00010 WHERE no_reg='".$_GET["rg1"]."')"));
		$f->execute();
		echo "</td><td align=left valign=top>";
		$f = new ReadOnlyForm();
		$f->text("<b>"."Umur", $umur);
		$f->text("<b>"."Seks",$d->jenis_kelamin);
		$f->text("<b>"."Bangsal", $d->bangsal_info);
		$f->execute();
		echo "</td><td valign=top>";
	    $f = new ReadOnlyForm();
	    echo "<table border=0 width='100%'>";
	    echo "<tr><td class=TBL_BODY><strong>Dokter Poli</strong>&nbsp;:</td></tr>";
	    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
	    echo "<tr><td class=TBL_BODY><strong>Dokter DPJP</strong>&nbsp;:</td></tr>";
	    echo "<tr><td align=justify class=TBL_BODY>".$dokterDPJPNama."</td></tr>";
	    echo "</table>";
            $f->execute();
		echo "</td></tr></table>";
		echo"<hr noshade size='2'>";
        
// Agung Sunandar 2:20 28/07/2012 menambahkan bangsal untuk BHP dan PAKET LAYANAN
$ruang = $d->bangsal;
$kode_gudang = getFromTable ("select 'qty_'||tc from rs00001 where tt='GDP' and  (upper(tdesc) LIKE '%".strtoupper($ruang)."%') ");

		
    echo "</div>";
 	if(!$GLOBALS['print']){
        echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=p_layanan_rawat_inap&rg={$_GET["rg"]}&mr={$_GET["mr"]}&rg1={$_GET["rg1"]}'>".icon("back","Kembali")."</a></DIV>";
    	echo"<br>";
 	}   
    $total = 0.00;
    if (is_array($_SESSION["layanan"])) {
        foreach($_SESSION["layanan"] as $k => $l) {
            $total += $l["total"];
        }
    }
    
if ($_GET["sub"] == "byr") {
        title("Total Tagihan: Rp. ".number_format($total,2));
        echo "<br><br><br>";
        $f = new Form("actions/p_riwayat_penyakit.insert.php");
        $f->hidden("rg",$_GET["rg"]);
        $f->hidden("mr",$_GET["mr"]);
        $f->hidden("ri",$_GET["ri"]);
        $f->hidden("poli",$d->poli);
	$f->hidden("sub",$_GET["sub"]);
        $f->hidden("byr",$total);
        $f->submit(" Simpan &amp; Bayar ");
        $f->execute();

        
} elseif ($_GET["list"] == "icd") {  // -------- ICD
	if(!$GLOBALS['print']){	
	$T->show(2);
	}
        echo"<div align=center class=form_subtitle1>KLASIFIKASI PENYAKIT</div>";
        echo "<table width='100%' border=0 cellspacing=0 cellpadding=0><tr>";
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b2.disabled = true;\n";
        echo "document.Form3.b4.disabled = false;\n";
        echo "</script>\n";
        echo "<form action='$SC'>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg1 VALUE='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        echo "</form>";
        echo "<td valign=top>";

        $namaICD = getFromTable("SELECT DESCRIPTION FROM RSV0005 WHERE DIAGNOSIS_CODE = '".$_SESSION["SELECT_ICD"]."'");
        $katICD = getFromTable("SELECT CATEGORY FROM RSV0005 WHERE DIAGNOSIS_CODE = '".$_SESSION["SELECT_ICD"]."'");
        
        $t = new BaseTable("100%");
        $t->printTableOpen();
        echo "<FORM ACTION='$SC' NAME=Form11>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg1 VALUE='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t->printTableHeader(Array("KODE ICD", "KETERANGAN","KATEGORI", "&nbsp;"));
        
        if (is_array($_SESSION["icd"])) {
            foreach($_SESSION["icd"] as $k => $l) {
                $t->printRow2(
                    Array($l["id"], $l["desc"],$l["kate"], "<A HREF='$SC?p=$PID&list=icd&rg1=".$_GET["rg1"]."&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=".$_GET["mr"]."&del-icd=$k&httpHeader=1'>".icon("del-left")."</A>"), Array("CENTER", "LEFT", "LEFT","CENTER")
                );
            }
        }
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=icd STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_ICD"]."'>&nbsp;<A HREF='javascript:selectICD()'><IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaICD,"$katICD", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "LEFT","CENTER")
        );
		// --- eof 27-12-2006 ---
        echo "</FORM>";
        
        $t->printTableClose();
        echo "\n<script language='JavaScript'>\n";
        echo "function selectICD() {\n";
        echo "sWin = window.open('popup/icd.php', 'xWin', 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
        
        echo "<form name='Form9' action='actions/p_riwayat_penyakit.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<input type=hidden name=rg1 value='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<br><div align=right><input type=button value='Simpan' onClick='document.Form9.submit()'>&nbsp;</div>";
        echo "</form>";
     
	 // DIAGNOSA
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
		$t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='actions/p_icd_ri.delete.php?p=$PID&sub=icd&list=icd&mr=".$_GET["mr"]."&poli=".$_GET["mPOLI"]."&rg=".$_GET["rg"]."&id=<#3#>'>".icon("delete","Hapus")."</A>";			
		$t->execute();
	}
	
        include("rincian.php");
} elseif ($_GET["list"] == "icd9") {  // -------- ICD 9
	if(!$GLOBALS['print']){	
	$T->show(3);
	}
        echo"<div align=center class=form_subtitle1>T I N D A K A N</div>";
        echo "<table width='100%' border=0 cellspacing=0 cellpadding=0><tr>";
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b2.disabled = true;\n";
        echo "document.Form3.b4.disabled = false;\n";
        echo "</script>\n";
        echo "<form action='$SC'>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg1 VALUE='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd9'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        echo "</form>";
        echo "<td valign=top>";

        $namaICD9 = getFromTable("SELECT nama FROM icd_9 WHERE kode = '".$_SESSION["SELECT_ICD9"]."'");
    
        $t = new BaseTable("100%");
        $t->printTableOpen();
        echo "<FORM ACTION='$SC' NAME=Form12>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg1 VALUE='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t->printTableHeader(Array("KODE ICD 9", "DESKRIPSI", "&nbsp;"));
        if (is_array($_SESSION["icd9"])) {
            foreach($_SESSION["icd9"] as $k => $l) {
                $t->printRow2(
                    Array($l["id"], $l["desc"], "<A HREF='$SC?p=$PID&list=icd9&rg1=".$_GET["rg1"]."&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=".$_GET["mr"]."&del-icd9=$k&httpHeader=1'>".icon("del-left")."</A>"), Array("CENTER", "LEFT", "CENTER")
                );
            }
        }
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=icd9 STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_ICD9"]."'>&nbsp;<A HREF='javascript:selectICD9()'><IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaICD9, "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "CENTER")
        );
		// --- eof 27-12-2006 ---
        echo "</FORM>";
        
        $t->printTableClose();
        echo "\n<script language='JavaScript'>\n";
        echo "function selectICD9() {\n";
        echo "sWin = window.open('popup/icd_9.php', 'xWin', 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
        
        echo "<form name='Form99' action='actions/p_riwayat_penyakit.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<input type=hidden name=rg1 value='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='icd9'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<br><div align=right><input type=button value='Simpan' onClick='document.Form99.submit()'>&nbsp;</div>";
        echo "</form>";
     
	$rec1 = getFromTable ("select count(id) from rs00008 ".	// sfdn, 27-12-2006 --> melakukan testing apakah ada data diagnosa
						  "where trans_type = 'CD9' and no_reg ='".$_GET["rg"]."'");
	if ($rec1 > 0) {

            $f = new Form("");
            echo "<br>";
            $f->title1("Data Tindakan");
            $f->execute();

            $t = new PgTable($con, "100%");
            $t->SQL = "select a.item_id, b.nama, a.oid  from rs00008 a 
                                left join icd_9 b on b.kode = a.item_id
                                where trans_type='CD9' and a.no_reg ='".$_GET["rg"]."' order by tanggal_entry";		   
            $t->setlocale("id_ID");
            $t->ShowRowNumber = true;
            $t->RowsPerPage = $ROWS_PER_PAGE;
            $t->ColHeader = array("KODE ICD","TINDAKAN (ICD 9)", 'HAPUS');
            $t->ColAlign = array("center","left","center");
            $t->DisableScrollBar = true;
            $t->DisableStatusBar = true;	
            $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='actions/p_icd9_ri.delete.php?p=$PID&sub=icd9&list=icd9&mr=".$_GET["mr"]."&poli=".$_GET["mPOLI"]."&rg=".$_GET["rg"]."&id=<#2#>'>".icon("delete","Hapus")."</A>";			
            $t->execute();
	}
	
        include("rincian.php");        
  } elseif ($_GET["sub"] == "obat") { // -------- OBAT

        echo"<div align=center class=form_subtitle1>RESEP OBAT</div>";
        echo "<table width='100%' border=0 cellspacing=0 cellpadding=0><tr>";
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b2.disabled = true;\n";
        echo "document.Form3.b4.disabled = false;\n";
        echo "</script>\n";
        echo "<form action='$SC'>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg1 VALUE='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='obat'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        echo "</form>";
        echo "<td valign=top>";echo "<script language='JavaScript'>\n";
        echo "document.Form3.b3.disabled = true;\n";
        echo "</script>\n";


        if ($_SESSION["SELECT_OBAT"]) {
           $namaObat = getFromTable("select obat from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
           $hargaObat = getFromTable("select harga from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        }

        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "Nama Obat", "Jumlah", "Harga Satuan", "Harga Total", ""));


        if (is_array($_SESSION["obat"])) {
            foreach($_SESSION["obat"] as $k => $l) {
                $t->printRow2(
                    Array($l["id"], $l["desc"], $l["jumlah"], number_format($l["harga"],2), number_format($l["total"],2),
                    "<A HREF='$SC?p=$PID&regno=".$_GET["rg"]."&del-obat=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "CENTER", "RIGHT", "RIGHT", "CENTER")
                );
            }
        }
        
	// sfdn, 27-12-2006 -> pembetulan directory icon = ../simrs/images/*.png
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit2()' NAME=obat STYLE='text-align:center' TYPE=TEXT SIZE=5
            MAXLENGTH=10 VALUE='".$_SESSION["SELECT_OBAT"]."'>&nbsp;<A HREF='javascript:selectObat()'>
            <IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaObat,
            "<INPUT VALUE='".(isset($_GET["jumlah_obat"]) ? $_GET["jumlah_obat"] : "1")."'NAME=jumlah_obat
            OnKeyPress='refreshSubmit2()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>",
            number_format($hargaObat,2), "",
            "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "CENTER", "RIGHT", "RIGHT", "CENTER")
        );
	// --- eof 27-12-2006 ---
        $t->printTableClose();
        echo "</FORM>";
        echo "\n<script language='JavaScript'>\n";
        echo "function selectObat() {\n";
        echo "    sWin = window.open('popup/obat.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
        
        include("rincian.php");
    }elseif ($_GET["list"] == "layanan") { // ----------------------------- LAYANAN MEDIS
    	if(!$GLOBALS['print']){
    	$T->show(1);
    	}		
		/* Untuk Layanan Paket             */
        /* Agung Sunandar 16:53 26/06/2012 */
        echo"<hr noshade size='2'>";
        echo "<form name=Form88>";
        echo "<input name=b3 type=button value='Layanan Non Paket'      onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&rg1=".$_GET["rg1"]."&ri=".$_GET["ri"]."&mr=$mr&list=layanan&sub2=nonpaket\";'>&nbsp;";
        echo "<input name=b10 type=button value='Layanan Paket' onClick='window.location=\"$SC?p=$PID&rg=" . $_GET["rg"] . "&rg1=".$_GET["rg1"]."&ri=".$_GET["ri"]."&mr=$mr&list=layanan&sub2=paket\";'>&nbsp;";

        // Tambahan BHP 
        echo "<input name=b13 type=button value='Tambah Barang Habis Pakai' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&rg1=".$_GET["rg1"]."&ri=".$_GET["ri"]."&mr=$mr&list=layanan&sub2=bhp\";'>&nbsp;";
        // ==============================================  

        echo "</form>";
		
        if ($_GET[sub2] == "nonpaket") {
            echo"<div align=center class=form_subtitle1>LAYANAN DAN TINDAKAN MEDIS (NON PAKET)</div>";
        } elseif ($_GET[sub2] == "paket") {
            echo"<div align=center class=form_subtitle1>LAYANAN DAN TINDAKAN MEDIS (PAKET)</div>";
        } else {
            echo"<div align=center class=form_subtitle1>TAMBAH BARANG HABIS PAKAI</div>";
        }
		
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b1.disabled = true;\n";
        echo "document.Form3.b2.disabled = false;\n";
        echo "document.Form3.b4.disabled = false;\n";
        echo "</script>\n";

        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg1 VALUE='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='layanan'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=ri VALUE='".$_GET["ri"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";

        if ($_GET[sub2] == "nonpaket") {
            echo "<script language='JavaScript'>\n";
            echo "document.Form88.b3.disabled = true;\n";
            echo "</script>\n";
            $t = new BaseTable("100%");
            $t->printTableOpen();
            $t->printTableHeader(Array("KODE", "LAYANAN", "YANG MELAKUKAN TINDAKAN", "JUMLAH", "SATUAN",
                "HARGA SATUAN","(%) DISCOUNT","(Rp.) DISCOUNT", "CITO","HARGA TOTAL", ""));

            if (is_array($_SESSION["layanan"])) {
                $total = 0.00;
                foreach ($_SESSION["layanan"] as $k => $l) {

                    $q = pg_query("SELECT B.TDESC AS KELAS_TARIF, SUBSTR(A.HIERARCHY,1,6) AS HIE FROM RS00034 A " .
                            "LEFT JOIN RS00001 B ON A.KLASIFIKASI_TARIF_ID = B.TC AND B.TT = 'KTR' " .
                            "WHERE A.ID = $l[id]");
                    $qr = pg_fetch_object($q);

                    if ($qr->hie == "003002") {
                        $tambahan = " - " . $qr->kelas_tarif;
                    }

                    $t->printRow2(
                            Array($l["id"], $l["nama"] . $tambahan, $l["dokter"], $l["jumlah"], $l["satuan"],
                        number_format($l["harga"], 2),                         
                        $l["diskon_per"],
                        $l["diskon"],
                        $l["cito"],
                        number_format($l["total"], 2),
                        "<A HREF='$SC?p=$PID&list=layanan&rg=" . $_GET["rg"] . "&mr=" . $_GET["mr"] . "&del=$k&httpHeader=1'>" . icon("del-left") . "</A>"), Array("CENTER", "LEFT", "CENTER", "RIGHT", "LEFT", "RIGHT", "RIGHT", "RIGHT","RIGHT", "CENTER")
                    );
                    $total += $l["total"];
                }
            }
            if (isset($_SESSION["SELECT_LAYANAN"])) {
                $r = pg_query($con, "select * from rsv0034 where id = '" . $_SESSION["SELECT_LAYANAN"] . "'");
                $d = pg_fetch_object($r);
                pg_free_result($r);
				$ext = "  ";
            }else {
                $ext = " disabled ";
            }
            // sfdn, 27-12-2006 -> pembetulan directory gambar = ../simrs/images/*.png
            $t->printRow2(
                    Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='" . $_SESSION["SELECT_LAYANAN"] .
                "'>&nbsp;<A HREF='javascript:selectLayanan()'><IMG BORDER=0 SRC='images/icon-view.png'></A>",
                $d->layanan . " - " . $d->klasifikasi_tarif, "<INPUT OnKeyPress='refreshSubmit()' NAME=dokter STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=12 VALUE='"
                . $_SESSION["SELECT_EMP"] . "'>&nbsp;<A HREF='javascript:selectPegawai()'><IMG BORDER=0 SRC='images/icon-view.png'></a>", "<INPUT VALUE='" . (isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1") .
                "'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", $d->satuan, 
                "<input id='harga_satuan' name='harga_satuan' style='text-align:right;' type='text' value='$d->harga' />",
                "<input id='persen' name='diskon_per' onkeyup='hitung_diskon(\"persen\",this.value)' size='8' style='text-align:right;' type='text' value='0' />",
                "<input id='diskon' name='diskon' onkeyup='hitung_diskon(\"diskon\",this.value)' size='8' style='text-align:right;' type='text' value='0' />",
                "<input type='checkbox' name='cito'/>",
                "", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK' $ext>"), Array("CENTER", "LEFT", "CENTER", "CENTER", "LEFT", "RIGHT","RIGHT","RIGHT", "LEFT", "CENTER")
            );
            // --- eof 27-12-2006 ---
            $t->printRow2(
                    Array("", "", "", "", "","","", "","", number_format($total, 2), ""), Array("RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT")
            );
            $t->printTableClose();
            echo "</FORM>";
        } elseif ($_GET[sub2] == "paket") {
            /* Untuk Layanan Paket             */
            /* Agung Sunandar 16:53 26/06/2012 */
            echo "<script language='JavaScript'>\n";
            echo "document.Form88.b10.disabled = true;\n";
            echo "</script>\n";

            $t = new BaseTable("100%");
            $t->printTableOpen();
            $t->printTableHeader(Array("KODE", "LAYANAN", "YANG MELAKUKAN TINDAKAN", "JUMLAH", "SATUAN",
                "HARGA SATUAN", "HARGA TOTAL", ""));

            if (is_array($_SESSION["layanan2"])) {
                $total = 0.00;
                foreach ($_SESSION["layanan2"] as $k => $l) {

                    $t->printRow2(
                            Array($l["id"], $l["nama"], $l["dokter"], $l["jumlah"], $l["satuan"],
                        number_format($l["harga"], 2), number_format($l["total"], 2),
                        "<A HREF='$SC?p=$PID&list=layanan&rg=" . $_GET["rg"] . "&mr=" . $_GET["mr"] . "&sub=" . $_GET["sub"] . "&sub2=" . $_GET["sub2"] . "&del1=$k&httpHeader=1'>" . icon("del-left") . "</A>"), Array("CENTER", "LEFT", "CENTER", "RIGHT", "LEFT", "RIGHT", "RIGHT", "CENTER")
                    );
                    $total += $l["total"];
                }
            }

            if (isset($_SESSION["SELECT_LAYANAN2"])) {
                $r = pg_query($con, "select * from rs99996 where id = '" . $_SESSION["SELECT_LAYANAN2"] . "'");
                $d = pg_fetch_object($r);
                pg_free_result($r);
                $hrg = ($d->harga_paket);
                $ext = " ";
            } else {
                $ext = " disabled ";
            }

            $t->printRow2(
                    Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan2 STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='" . $_SESSION["SELECT_LAYANAN2"] .
                "'>&nbsp;<A HREF='javascript:selectLayanan2()'><IMG BORDER=0 SRC='images/icon-view.png'></A>",
                $d->description, "<INPUT OnKeyPress='refreshSubmit()' NAME=dokter2 STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=12 VALUE='"
                . $_SESSION["SELECT_EMP2"] . "'>&nbsp;<A HREF='javascript:selectPegawai2()'><IMG BORDER=0 SRC='images/icon-view.png'></a>", "<INPUT VALUE='" . (isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1") .
                "'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", "KALI", $hrg,
                "<INPUT NAME=gudang TYPE=hidden VALUE='$kode_gudang'>", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK' $ext >"), Array("CENTER", "LEFT", "CENTER", "CENTER", "LEFT", "RIGHT", "LEFT", "CENTER")
            );
            // untuk warning pada stok obat dalam paket yang nol  Agung SUnandar 9:02 16/07/2012

            $cek_qty = getFromTable("select 'qty_'||tc from rs00001 where tt='GDP' and tdesc ='" . $kode . "' ");


            $SQL = "select a.*, b.obat,b.satuan,c.$kode_gudang as qty_ruang  from rs99997 a
					left join rsv0004 b on b.id=a.item_id 
					left join rs00016a c on c.obat_id=a.item_id
					where a.preset_id = '" . $d->id . "' and a.trans_type='OBI' ";
            @$r1 = pg_query($con, $SQL);
            @$n1 = pg_num_rows($r1);

            $SQL2 = "select a.*, b.layanan, c.tdesc as satuan  from rs99997 a
					left join rs00034 b on b.id=a.item_id 
					left join rs00001 c on c.tt='SAT' and c.tc=b.satuan_id
					where a.preset_id = '" . $d->id . "' and a.trans_type='LYN' ";
            @$r2 = pg_query($con, $SQL2);
            @$n2 = pg_num_rows($r2);

            $max_row = 30;
            $mulai = $HTTP_GET_VARS["rec"];
            if (!$mulai) {
                $mulai = 1;
            }

            $row2 = 0;
            $i2 = 1;
            $j2 = 1;
            $last_id = 1;

            while (@$row2 = pg_fetch_array($r2)) {
                $wrna = " color='black' ";
                if (($j2 <= $max_row) AND ($i2 >= $mulai)) {
                    $class_nya = "TBL_BODY";
                    $no = $i2
                    ?>

                    <tr>	
                        <td class="TBL_BODY"><font <?= $wrna ?>><b>Tindakan Medis</b></font></td>
                        <td class="TBL_BODY"><font <?= $wrna ?>><b><?= $row2["layanan"] ?></b></font></td>

                        <td class="TBL_BODY"><font <?= $wrna ?>><b>&nbsp;</b></font></td>
                        <td class="TBL_BODY"><font <?= $wrna ?>><b><?= $row2["qty"] ?></b></font></td>
                        <td class="TBL_BODY"><font <?= $wrna ?>><b><?= $row2["satuan"] ?></b></font></td>
                        <td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
                        <td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
                        <td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
                    </tr>

                    <?
                    ;
                    $j++;
                }
                $i++;
            }

            $row1 = 0;
            $i = 1;
            $j = 1;
            $last_id = 1;

            while (@$row1 = pg_fetch_array($r1)) {
                IF ($row1["qty_ruang"] <= 0) {
                    $wrna = " color='red' ";
                } else {
                    $wrna = " color='black' ";
                }
                if (($j <= $max_row) AND ($i >= $mulai)) {
                    $class_nya = "TBL_BODY";
                    $no = $i
                    ?>

                    <tr>	
                        <td class="TBL_BODY"><font <?= $wrna ?>><b>Obat/BHP</b></font></td>
                        <td class="TBL_BODY"><font <?= $wrna ?>><b><?= $row1["obat"] ?></b></font></td>

                        <td class="TBL_BODY"><font <?= $wrna ?>><b>Stok Ruangan <?= $row1["qty_ruang"] ?></b></font></td>
                        <td class="TBL_BODY"><font <?= $wrna ?>><b><?= $row1["qty"] ?></b></font></td>
                        <td class="TBL_BODY"><font <?= $wrna ?>><b><?= $row1["satuan"] ?></b></font></td>
                        <td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
                        <td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
                        <td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
                    </tr>

                    <?
                    ;
                    $j++;
                }
                $i++;
            }


            // end dr warning Obat
            $t->printRow2(
                    Array("", "", "", "", "", "", number_format($total, 2), ""), Array("RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT")
            );
            $t->printTableClose();
            echo "</FORM>";
        } else {
            /* Untuk Tambah BHP                */
            /* Agung Sunandar 16:53 26/06/2012 */
            echo "<script language='JavaScript'>\n";
            echo "document.Form88.b13.disabled = true;\n";
            echo "</script>\n";
            // Tambahan BHP       
            if ($_SESSION["SELECT_OBAT2"]) {
                $namaObat = getFromTable("select obat from rsv0004 where id = '" . $_SESSION["SELECT_OBAT2"] . "'");
                $hargaObat = getFromTable("select harga from rsv0004 where id = '" . $_SESSION["SELECT_OBAT2"] . "'");
                $satuan = getFromTable("select satuan from rsv0004 where id = '" . $_SESSION["SELECT_OBAT2"] . "'");
                $ext = "  ";
            } else {
                $ext = " disabled ";
            }

            $x_racikan = " <SELECT NAME='is_racikan'>\n";
            $x_racikan .= "<OPTION VALUE=N>N</OPTION>\n";
            $x_racikan .= "<OPTION VALUE=Y>Y</OPTION>\n";
            "</SELECT></TD>\n";

            $qty = getFromTable ("select tc from rs00001 where tt='GDP' and  (upper(tdesc) LIKE '%".strtoupper($ruang)."%') ");
            $q = getFromTable("select $kode_gudang from rs00016a where obat_id='" . $_SESSION["SELECT_OBAT2"] . "'");

            $x_jumlah2 = " <input type='text' name='jumlah_obat' id='jumlah_obat' value='1' size='3' style='text-align:right;'>\n";
            $x_penjamin2 = " <input type='checkbox' name='penjamin_obat' id='penjamin_obat' value='1' />\n";

            $t = new BaseTable("100%");
            $t->printTableOpen();
            $t->printTableHeader(Array("KODE", "Nama Obat/BHP", "Satuan", "Jumlah", "Harga Satuan", "Harga Total", "Penjamin", ""));


            if (is_array($_SESSION["obat2"])) {


                foreach ($_SESSION["obat2"] as $k => $l) {
                    if( $l["penjamin"] == 1 ){
                        $statusPenjamin = 'YA';
                    }else{
                        $statusPenjamin = '';
                    }
                    
                    $t->printRow2(
                            Array($l["id"], $l["desc"], $l["satuan"], $l["jumlah"], number_format($l["harga"], 2), number_format($l["total"], 2), $statusPenjamin,
                        "<A HREF='$SC?p=$PID&list=resepobat&rg=" . $_GET["rg"] . "&mr=" . $_GET["mr"] . "&del-obat2=$k&httpHeader=1'>" . icon("del-left") . "</A>"), Array("CENTER", "LEFT", "CENTER", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "CENTER")
                    );
                }
            }

            // sfdn, 27-12-2006 -> pembetulan directory icon = ../simrs/images/*.png
            $t->printRow2(
                    Array("<INPUT OnKeyPress='refreshSubmit2()' NAME=obat2 STYLE='text-align:center' TYPE=TEXT SIZE=5
            MAXLENGTH=10 VALUE='" . $_SESSION["SELECT_OBAT2"] . "'>&nbsp;<A HREF='javascript:selectObat()'>
            <IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaObat,
                $satuan, //penambahan dosis
                $x_jumlah2,
                number_format($hargaObat, 2), 
                0, $x_penjamin2."<INPUT NAME=gudang TYPE=hidden VALUE='$kode_gudang'>",
                "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK' $ext>"), Array("CENTER", "LEFT", "CENTER", "LEFT", "RIGHT", "RIGHT", "CENTER", "CENTER", "CENTER")
            );
            // --- eof 27-12-2006 ---
            $t->printTableClose();
            echo "</FORM>";

            echo "\n<script language='JavaScript'>\n";
            echo "function selectObat() {\n";
            echo "    sWin = window.open('popup/obat004.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
            echo "    sWin.focus();\n";
            echo "}\n";
            echo "</script>\n";
        }
        // ===============================================	
		
        echo "<form name='Form10' action='actions/p_riwayat_penyakit.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<input type=hidden name=rg1 value='".$_GET["rg1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='layanan'>";
		echo "<INPUT TYPE=HIDDEN NAME=sub2 VALUE='".$_GET["sub2"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<input type=hidden name=ri value='".$_GET["ri"]."'>";
        echo "<input type=hidden name=list value='layanan'>";
        echo "<br><div align=right><input type=button value='Simpan' onClick='document.Form10.submit()'>&nbsp;";
        echo "</form>";
       
       include("rincian.php"); 
       
    } elseif($_GET["list"] == "riwayat") {
    	if(!$GLOBALS['print']){
    	$T->show(3);
    	}
    	if ($_GET["act"] == "detail") {
				$sql = 	"select a.*,to_char(a.tanggal_reg,'DD MON YYYY HH24:MI:SS')as tanggal_reg,f.layanan,(g.nama)as merawat,(h.nama)as mengirim,(i.nama)as konsul ".
						"from c_visit_ri a ". 
						"left join rsv0002 c on a.no_reg=c.id 
                                                left join rs00006 d on d.id = a.no_reg 
                                                left join rs00008 e on e.no_reg = a.no_reg 
                                                left join rs00034 f on f.id::text = e.item_id
                                                left join rs00017 g on a.vis_1 = g.id::text 
                                                left join rs00017 h on a.vis_2 = h.id::text
left join rs00017 i on a.id_dokter::text = i.id::text												".
						"where a.no_reg='{$_GET['rg']}' and a.id_ri= '{$_GET["ri"]}' ";
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
			$f->text("<B>".$visit_ri_riwayat_penyakit["vis_1"]."</B>",$d["merawat"]);
			$f->text("<B>".$visit_ri_riwayat_penyakit["vis_2"]."</B>",$d["mengirim"] );
			$f->text("<B>"."Konsulen"."</B>",$d["konsul"] );
			$f->text($visit_ri_riwayat_penyakit["vis_3"],$d[6]);
			$f->text($visit_ri_riwayat_penyakit["vis_4"],$d[7] );
    		$f->title1("<U>ANAMNESA</U>","LEFT");	
			$f->text($visit_ri_riwayat_penyakit["vis_5"],$d[8]);
			$f->title1("<U>STATUS PRAESENS</U>","LEFT");
			$f->text($visit_ri_riwayat_penyakit["vis_6"],$d[9]);
			$f->text($visit_ri_riwayat_penyakit["vis_7"],$d[10]);
			$f->text($visit_ri_riwayat_penyakit["vis_8"],$d[11] );    
			$f->text($visit_ri_riwayat_penyakit["vis_9"],$d[12]);
			$f->text($visit_ri_riwayat_penyakit["vis_10"],$d[13]."&nbsp;Celcius" );
			$f->text($visit_ri_riwayat_penyakit["vis_11"],$d[14]);
			$f->text($visit_ri_riwayat_penyakit["vis_12"],$d[15] );	
			$f->text($visit_ri_riwayat_penyakit["vis_13"],$d[16] );
			$f->text($visit_ri_riwayat_penyakit["vis_14"],$d[17]);
			$f->text($visit_ri_riwayat_penyakit["vis_15"],$d[18]);
			$f->text($visit_ri_riwayat_penyakit["vis_16"],$d[19]);
			$f->text($visit_ri_riwayat_penyakit["vis_17"],$d[20] );	
			$f->text($visit_ri_riwayat_penyakit["vis_18"],$d[21] );
			$f->text($visit_ri_riwayat_penyakit["vis_19"],$d[22]);
			$f->text($visit_ri_riwayat_penyakit["vis_20"],$d[23]);
			$f->text($visit_ri_riwayat_penyakit["vis_21"],$d[24]);
			$f->execute();
			echo "</td><td valign=top>";
    		$f = new ReadOnlyForm();
			
			$f->text($visit_ri_riwayat_penyakit["vis_22"],$d[25] );	
			$f->text($visit_ri_riwayat_penyakit["vis_23"],$d[26]."&nbsp;mm Hg" );
			$f->text($visit_ri_riwayat_penyakit["vis_24"],$d[27]."&nbsp;/ Menit");
			$f->text($visit_ri_riwayat_penyakit["vis_25"],$d[28]);
			$f->text($visit_ri_riwayat_penyakit["vis_26"],$d[29]);
			$f->text($visit_ri_riwayat_penyakit["vis_27"],$d[30] );	
			$f->text($visit_ri_riwayat_penyakit["vis_28"],$d[31] );
			$f->text($visit_ri_riwayat_penyakit["vis_29"],$d[32]);
			$f->text($visit_ri_riwayat_penyakit["vis_30"],$d[33]);
			$f->text($visit_ri_riwayat_penyakit["vis_31"],$d[34]);
			$f->text($visit_ri_riwayat_penyakit["vis_32"],$d[35] );
			$f->text($visit_ri_riwayat_penyakit["vis_33"],$d[36] );
				
			$f->title1("<U>LABORATORIUM</U>","LEFT");
			$f->text($visit_ri_riwayat_penyakit["vis_34"],$d[37]);
			$f->text($visit_ri_riwayat_penyakit["vis_35"],$d[38]);
			$f->text($visit_ri_riwayat_penyakit["vis_36"],$d[39]);
			$f->text($visit_ri_riwayat_penyakit["vis_37"],$d[40] );	
			$f->text($visit_ri_riwayat_penyakit["vis_38"],$d[41] );
			$f->text($visit_ri_riwayat_penyakit["vis_39"],$d[42]);
			$f->text($visit_ri_riwayat_penyakit["vis_40"],$d[43]);
			$f->text($visit_ri_riwayat_penyakit["vis_41"],$d[44]);
			$f->text($visit_ri_riwayat_penyakit["vis_42"],$d[45] );	
			$f->text($visit_ri_riwayat_penyakit["vis_43"],$d[46] );
			$f->text($visit_ri_riwayat_penyakit["vis_44"],$d[47]);
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
				$sql = "SELECT A.NO_REG,to_char(A.TANGGAL_REG,'DD MON YYYY HH24:MI:SS')as tgl_reg,(G.NAMA)AS MERAWAT,(H.NAMA)AS MENGIRIM,'DUMMY' ". 
					   "FROM C_VISIT_RI A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   "LEFT JOIN RS00017 G ON A.VIS_1 = G.ID::text ".
					   "LEFT JOIN RS00017 H ON A.VIS_2 = H.ID::text ".
					   "WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_ri = '{$_GET["mPOLI"]}' group by A.NO_REG,A.TANGGAL_REG, G.NAMA, H.NAMA ";
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	//$t->ColHidden[4]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("NO REGISTRASI","TANGGAL/JAM KUNJUNGAN","DOKTER DPJP","DOKTER RUANGAN","DETAIL");
			   	$t->ColAlign = array("center","center","left","left","center");
				$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat&rg1=".$_GET["rg1"]."&ri=".$_GET["mPOLI"]."&act=detail&mr=".$_GET["mr"]."&rg=<#0#>'>".icon("view","View")."</A>";	
				$t->execute();
				
				echo"<br>";
         		echo"</div>";
				echo "</td></tr></table></div>";
    	
			}
    }elseif($_GET["list"] == "riwayat_klinik") {
    	if(!$GLOBALS['print']){
    	$T->show(4);
    	}
    	if ($_GET["act"] == "detail_klinik") {
				$sql = "select a.*,b.nama,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,f.layanan,a.id_poli 
						from c_visit a 
						left join rs00017 b on a.id_dokter = b.id 
						left join rsv0002 c on a.no_reg=c.id 
						left join rs00006 d on d.id = a.no_reg
						left join rs00008 e on e.no_reg = a.no_reg
						left join rs00034 f on f.id::text = e.item_id
						where a.no_reg='{$_GET['rg']}' and a.oid='{$_GET['oid']}' ";
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
    		$poli=$d["id_poli"];
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
    		elseif ($poli == $setting_poli["saraf"]){
    			include(detail_saraf);
    		}
    		elseif ($poli == $setting_poli["psikiatri"]){
    			include(detail_psikiatri);
    		}
    		elseif ($poli == $setting_poli["fisioterapi"]){
    			include(detail_fisioterapi);
    		}
    		elseif ($poli == $setting_poli["radiologi"]){
    			include(detail_radiologi);
    		}
    		else{
    			include(detail_laboratorium);
    		}
    		
			}else {
				echo"<div align=center class=form_subtitle1>RIWAYAT PENYAKIT PASIEN</div>";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
				$sql = "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,to_char (A.TANGGAL_REG,'hh:mm:ss') AS WAKTU,C.TDESC,D.NAMA,A.OID ". 
					   "FROM C_VISIT A ".
					   "LEFT JOIN RS00006 B ON A.NO_REG=B.ID ".
					   "LEFT JOIN RS00001 C ON A.ID_POLI::text = C.TC AND C.TT='LYN'".
					   "LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID ".
					   "WHERE A.user_id != '' and B.MR_NO = '".$_GET["mr"]."'
                                            group by A.NO_REG,A.TANGGAL_REG,C.TDESC,D.NAMA,A.OID ";
					
				$t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			   	$t->ColHidden[5]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("NO REGISTRASI","TANGGAL KUNJUNGAN","WAKTU KUNJUNGAN","KLINIK","DETAIL");
			   	$t->ColAlign = array("center","center","center","left","center","center");
				$t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat_klinik&rg1=".$_GET["rg1"]."&ri=".$_GET["mPOLI"]."&act=detail_klinik&mr=".$_GET["mr"]."&rg=<#0#>&oid=<#5#>'>".icon("view","View")."</A>";	
				$t->execute();
				
				echo"<br>";
         		echo"</div>";
				echo "</td></tr></table></div>";
    	
			}
/* Add By Yudha 10/01/2008 */
			
    }elseif ($_GET["list"] == "konsultasi"){
    	$T->show(5);
    	echo"<br>";
    	
    	//$laporan = getFromTable("select tdesc from rs00001 where tt='LRI' and tc = '".$_SESSION[SELECT_LAP]."'");
    	$f = new Form("actions/p_riwayat_penyakit.insert.php", "POST", "NAME=Form2");

 


					$f->hidden("act","new2");
					$f->hidden("f_no_reg",$_GET["rg"]);
					$f->hidden("list","konsultasi");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli",$_GET["poli"]);
					$f->hidden("f_id_ri",$_GET["ri"]);
				    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
				    $f->hidden("f_user_id",$_SESSION[uid]);
				    $f->hidden("konsultasi",$_GET["konsultasi"]);
				    
					echo"<br>";
 				 	$tipe 	     = getFromTable("select no_reg from c_visit where no_reg='".$_GET["rg"]."'  ");
					$ext = "" ;
					if ($tipe){
							$ext = "" ;
							$konsul = getFromTable("select vis_80 from c_visit_ri where no_reg='".$_GET["rg"]."'  ");
					}
				    $f->PgConn=$con;
					$f->selectSQL("konsultasi","Unit Yang Dituju", "select '-' as tc , '-' as tdesc union select tc,tdesc from rs00001 where tt='LYN' and tc not in ('000','100','111','201','202','206','207','208') order by tdesc",$konsul,$ext);
				    $f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
				    $f->execute();
				    echo"<br>";
					echo "<b>Pasien di Konsul ke Poli:</b><br>";
					$t = new PgTable($con, "100%");
					$t->SQL = "select b.tdesc, a.oid from c_visit a left join rs00001 b on b.tc=a.id_konsul and b.tt='LYN'  where  a.no_reg='".$_GET[rg]."' and (a.id_poli::text='".$_GET["poli"]."' or a.id_ri='".$_GET["ri"]."') and a.id_konsul != '' ";
					$t->setlocale("id_ID");
					$t->ShowRowNumber = true;
					$t->ColAlign = array("LEFT","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
					$t->RowsPerPage = $ROWS_PER_PAGE;
					$t->ColFormatHtml[1] = "<A CLASS=SUB_MENU1 HREF='actions/$PID.delete.php?p=$PID&oid=<#1#>&ri=E05&tbl=konsultasi&mr=".$_GET[mr]."&rg=".$_GET[rg]."&f_id_poli=".$_GET["poli"]."'>". icon("delete","Edit Status pekerjaan")."</A>";
					$t->ColHeader = array("KONSUL KE", "HAPUS");
					//$t->ColRowSpan[2] = 2;
					$t->execute();
					
				    echo"<br><font color=black>&nbsp;* Catatan : Hasil Pemeriksaan Pasien harus diisi minimal Dokter Pemeriksa</font><br>";
    } 
    
    else {       //pemeriksaan
    	if(!$GLOBALS['print']){
    	$T->show(0);
    	}
                        
    		$sql2 =	"SELECT A.*,(C.NAMA)AS merawat,(D.NAMA)AS mengirim,(E.NAMA)AS konsul FROM C_VISIT_RI A 
    				LEFT JOIN RS00017 C ON A.VIS_1::text = C.ID::text
    				LEFT JOIN RS00017 D ON A.VIS_2::text = D.ID::text
					LEFT JOIN RS00017 E ON A.id_dokter::text = E.ID::text
    				WHERE A.ID_RI='{$_GET['ri']}'::text AND A.NO_REG='{$_GET['rg1']}'::text";
			$r=pg_query($con,$sql2);
			$n = pg_num_rows($r);
			if($n > 0) $d2 = pg_fetch_array($r);
			pg_free_result($r);
				//-------------------------tambah for update------hery 08072007
				echo "<div align=left><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=$PID&rg=$rg&mr={$_GET['mr']}&ri={$_GET["ri"]}&rg1=$rg&act=edit';\">\n";   
				if ($_GET['act'] == "edit") {
						echo "<font color='#000000' size='2'> >>Edit Pemeriksaan Pasien</font>";
						$f = new Form("actions/p_riwayat_penyakit.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("f_no_reg",$d2["no_reg"]);
					    $f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
						$f->hidden("list","pemeriksaan");
						$f->hidden("rawatan",$rawatan);
						$f->hidden("f_id_rujukan",$d->poli);
					    $f->hidden("mr",$_GET["mr"]);
					    $f->hidden("f_id_ri",$_GET["ri"]);
					    $f->hidden("f_user_id",$_SESSION[uid]);
					    $f->hidden("rg1",$_GET[rg1]);
					   
				}else {
					if($n > 0) {
						$ext= "disabled";
					}else {
						$ext = "";
					}
				//---------------------------------------------------------------------------------			
						
					$f = new Form("actions/p_riwayat_penyakit.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$_GET["rg"]);
					$f->hidden("f_id_rujukan",$d->poli);
					$f->hidden("list","pemeriksaan");
					$f->hidden("rawatan",$rawatan);
                                        $f->hidden("mr",$_GET["mr"]);
                                        $f->text("f_id_ri",'E05',null,null,'E05',null);
					$f->hidden("f_user_id",$_SESSION[uid]);
					$f->hidden("rg1",$_GET[rg1]);
					$f->hidden("id_dokter","vis_1");
			}
			
                            // start create dropdown dokter DPJP
                            if(!empty($d2["vis_1"])){
                                $dokterDPJPID = $d2["vis_1"];
                            }else{
                                $dokterDPJPID = $dokterDPJPID_Rujukan_Id;
                            }
                            $sqlDokterDPJP  = pg_query($con,"SELECT id, nama FROM rs00017 WHERE pangkat like '%DOKTER%' ORDER BY nama ASC");
                            $arrDokterDPJP  = array();
                            while($rowDokterDPJP = pg_fetch_array($sqlDokterDPJP)){
                                $arrDokterDPJP[$rowDokterDPJP['id']] = $rowDokterDPJP['nama'];
                            }
                            $disabled = (empty($dokterDPJPID)||($_GET['act']=='edit')) ? '' : 'disabled';
                            $f->selectArray("f_vis_1 $disabled","Dokter DPJP",$arrDokterDPJP, $dokterDPJPID,'');
                            // end create dropdown dokter DPJP					
                            // start create dropdown dokter RUANGAN
                            $sqlDokterRuangan  = pg_query($con,"SELECT id, nama FROM rs00017 WHERE pangkat like '%DOKTER%' ORDER BY nama ASC");                            
                            $arrDokterRuangan  = array();
                            while($rowDokterRuangan = pg_fetch_array($sqlDokterRuangan)){
                                $arrDokterRuangan[$rowDokterRuangan['id']] = $rowDokterRuangan['nama'];
                            }
                            $disabled = (empty($d2["vis_2"])||($_GET['act']=='edit')) ? '' : 'disabled';
                            $f->selectArray("f_vis_2 $disabled","Dokter Ruangan",$arrDokterRuangan, $d2["vis_2"],'');
                            // end create dropdown dokter RUANGAN
					
                            // start create dropdown dokter KONSUL
                            $sqlDokterKonsul   = pg_query($con,"SELECT id, nama FROM rs00017 WHERE pangkat like '%DOKTER%' ORDER BY nama ASC");                            
                            $arrDokterKonsul   = array();
                            while($rowDokterKonsul = pg_fetch_array($sqlDokterKonsul)){
                                $arrDokterKonsul[$rowDokterKonsul['id']] = $rowDokterKonsul['nama'];
                            }
                            $disabled = (empty($d2["id_dokter"])||($_GET['act']=='edit')) ? '' : 'disabled';
                            $f->selectArray("f_vis_2 $disabled","Dokter Konsulen",$arrDokterKonsul, $d2["id_dokter"],'');
                            // end create dropdown dokter KONSUL
					
					
                                        
			//$f->hidden("f_id_dokter",$dokterDPJPID);
			$f->text("f_vis_3",$visit_ri_riwayat_penyakit["vis_3"],50,50,$d2["vis_3"],$ext);
			$f->textarea("f_vis_4",$visit_ri_riwayat_penyakit["vis_4"] ,1, $visit_ri_riwayat_penyakit["vis_4"."W"],$d2["vis_4"],$ext);
			$f->textarea("f_vis_5",$visit_ri_riwayat_penyakit["vis_5"] ,1, $visit_ri_riwayat_penyakit["vis_5"."W"],$d2["vis_5"],$ext);
			$f->title1("<U>STATUS PRAESENS</U>");
			$f->text_8i("f_vis_6",$visit_ri_riwayat_penyakit["vis_6"],30,30,$d2["vis_6"],"","f_vis_7",$visit_ri_riwayat_penyakit["vis_7"],30,30,$d2["vis_7"],"",
			"f_vis_8",$visit_ri_riwayat_penyakit["vis_8"],30,30,$d2["vis_8"],"","f_vis_9",$visit_ri_riwayat_penyakit["vis_9"],30,30,$d2["vis_9"],"",
			"f_vis_10",$visit_ri_riwayat_penyakit["vis_10"],10,10,$d2["vis_10"],"Celcius","f_vis_11",$visit_ri_riwayat_penyakit["vis_11"],30,30,$d2["vis_11"],"",
			"f_vis_12",$visit_ri_riwayat_penyakit["vis_12"],30,30,$d2["vis_12"],"","f_vis_13",$visit_ri_riwayat_penyakit["vis_13"],30,30,$d2["vis_13"],"",$ext);
			$f->text_8i("f_vis_14",$visit_ri_riwayat_penyakit["vis_14"],30,30,$d2["vis_14"],"","f_vis_15",$visit_ri_riwayat_penyakit["vis_15"],30,30,$d2["vis_15"],"",
			"f_vis_16",$visit_ri_riwayat_penyakit["vis_16"],30,30,$d2["vis_16"],"","f_vis_17",$visit_ri_riwayat_penyakit["vis_17"],30,30,$d2["vis_17"],"",
			"f_vis_18",$visit_ri_riwayat_penyakit["vis_18"],30,30,$d2["vis_18"],"","f_vis_19",$visit_ri_riwayat_penyakit["vis_19"],30,30,$d2["vis_19"],"",
			"f_vis_20",$visit_ri_riwayat_penyakit["vis_20"],30,30,$d2["vis_20"],"","f_vis_21",$visit_ri_riwayat_penyakit["vis_21"],30,30,$d2["vis_21"],"",$ext);
			$f->text_8i("f_vis_22",$visit_ri_riwayat_penyakit["vis_22"],30,30,$d2["vis_22"],"","f_vis_23",$visit_ri_riwayat_penyakit["vis_23"],10,10,$d2["vis_23"],"mm Hg",
			"f_vis_24",$visit_ri_riwayat_penyakit["vis_24"],10,10,$d2["vis_24"],"/Menit","f_vis_25",$visit_ri_riwayat_penyakit["vis_25"],30,30,$d2["vis_25"],"",
			"f_vis_26",$visit_ri_riwayat_penyakit["vis_26"],30,30,$d2["vis_26"],"","f_vis_27",$visit_ri_riwayat_penyakit["vis_27"],30,30,$d2["vis_27"],"",
			"f_vis_28",$visit_ri_riwayat_penyakit["vis_28"],30,30,$d2["vis_28"],"","f_vis_29",$visit_ri_riwayat_penyakit["vis_29"],30,30,$d2["vis_29"],"",$ext);
			$f->text("f_vis_30",$visit_ri_riwayat_penyakit["vis_30"],50,50,$d2["vis_30"],$ext);
			$f->text("f_vis_31",$visit_ri_riwayat_penyakit["vis_31"],50,50,$d2["vis_31"],$ext);
			$f->text("f_vis_32",$visit_ri_riwayat_penyakit["vis_32"],50,50,$d2["vis_32"],$ext);
			$f->text("f_vis_33",$visit_ri_riwayat_penyakit["vis_33"],50,50,$d2["vis_33"],$ext);
			$f->title1("<U>LABORATORIUM</U>");
			$f->text_6i("","f_vis_34",$visit_ri_riwayat_penyakit["vis_34"],30,30,$d2["vis_34"],"","f_vis_35",$visit_ri_riwayat_penyakit["vis_35"],30,30,$d2["vis_35"],"",
			"f_vis_36",$visit_ri_riwayat_penyakit["vis_36"],30,30,$d2["vis_36"],"","f_vis_37",$visit_ri_riwayat_penyakit["vis_37"],30,30,$d2["vis_37"],"",
			"f_vis_38",$visit_ri_riwayat_penyakit["vis_38"],30,30,$d2["vis_38"],"","f_vis_39",$visit_ri_riwayat_penyakit["vis_39"],30,30,$d2["vis_39"],"",$ext);
			$f->text("f_vis_40",$visit_ri_riwayat_penyakit["vis_40"],30,30,$d2["vis_40"],$ext);
			$f->text("f_vis_41",$visit_ri_riwayat_penyakit["vis_41"],50,50,$d2["vis_41"],$ext);
			$f->text("f_vis_42",$visit_ri_riwayat_penyakit["vis_42"],50,50,$d2["vis_42"],$ext);
			$f->text("f_vis_43",$visit_ri_riwayat_penyakit["vis_43"],50,50,$d2["vis_43"],$ext);
			$f->textarea("f_vis_44",$visit_ri_riwayat_penyakit["vis_44"] ,1, $visit_ri_riwayat_penyakit["vis_44"."W"],$d2["vis_44"],$ext);
			$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
			$f->execute();
			echo"</div>";
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
		/* Untuk Layanan Paket             */
    /* Agung Sunandar 16:53 26/06/2012 */
    echo "function selectLayanan2() {\n";
    echo "    sWin = window.open('popup/layanan_paket.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
        
    if (empty($_GET[sub])) {
	    echo "function refreshSubmit() {\n";
	   // echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
	    echo "}\n";
	    echo "refreshSubmit();\n";
	    }
	    echo "</script>\n";   		
}   
?>
<table>
    <tr>
        <td align="right" colspan="" class="TBL_BODY">Cetak BHP</td>
        <td width="15%" align="center" class="TBL_BODY"><a href="javascript: cetakBHP('<?php echo $_GET['rg']?>')"><img border="0" src="images/cetak.gif"></a></td>
        <td align="right" colspan="" class="TBL_BODY">Cetak BHP Dipilih</td>
        <td width="15%" align="center" class="TBL_BODY"><a href="javascript: submitBHP()"><img border="0" src="images/cetak.gif"></a></td>
    </tr>	
</table>

<script>
function cetakBHP(reg) {
sWin = window.open('includes/cetak.rincian_bhp.php?rg='+reg, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
sWin.focus();
}

function hitung_diskon(idx,val){
	var harga_satuan = parseFloat(document.Form8.harga_satuan.value);
	if(idx=='diskon'){
		var diskon = parseFloat(document.Form8.diskon.value);
		document.Form8.diskon_per.value =  (diskon/harga_satuan)*100;
		}
	else if(idx=='persen'){
		var diskon = parseFloat(document.Form8.diskon_per.value);
		document.Form8.diskon.value =  harga_satuan*(diskon/100);
		}		
	}
</script>
