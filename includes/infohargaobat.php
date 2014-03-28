<? // Agung SUnandar 0:34 07/07/2012 Membetulkan stock obak
$PID = "infohargaobat";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
//require_once("lib/dbconn.php");
require_once("lib/form.php");
//require_once("lib/class.PgTable.php");
//require_once("lib/functions.php");

if (!$GLOBALS['print']){
title_print("<img src='icon/daftar-2.gif' align='absmiddle' >  INFO HARGA OBAT");
//title_excel("520");
title_excel("OBAT&mOBT=".$_GET["mOBT"]."");
}else{
title("<img src='icon/daftar-2.gif' align='absmiddle' >  INFO HARGA OBAT");
title_excel("OBAT&mOBT=".$_GET["mOBT"]."");
}
echo "<br>";
    if (isset($_GET["e"])) {
        $ext = "DISABLED";
    } else {
        $ext = "OnChange = 'Form1.submit();'";
    }
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	if (!$GLOBALS['print']){
    $f->selectSQL("mOBT", "Kategori Inventory",
        "select '' as tc, '' as tdesc union " .
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt = 'GOB' and tc != '000' ".
        "order by tc", $_GET["mOBT"],
        $ext);
	}else{
	
	echo "KATEGORI OBAT :  ";
	$kat=getFromTable("select tdesc from rs00001 where tt = 'GOB' and tc='".$_GET["mOBT"]."'");
	//echo $_GET["mOBT"];
	echo $kat;
	}
    $f->execute();
    $f->hidden ("mOBT", $_GET["mOBT"]);
	
//	if ($_GET["mOBT"]!=""){

	if($_GET['mOBT'] && ($_GET['search']=='') && ($_GET['mOBT'] != '000')){
	    $kategori = " a.kategori_id = '" . $_GET["mOBT"] . "' and ";
	}
	else if(($_GET['search']!='') && ($_GET['mOBT'] != '000') && ($_GET['mOBT'] != '')){
		$_GET['mOBT'] = '';
	}

 $syntax =
    "select a.obat,c.tdesc as satuan,harga, harga_car_drs,
  harga_car_rsrj,  harga_car_rsri,  harga_inhealth_drs,  harga_inhealth_rs,
  harga_jam_ri,  harga_jam_rj,  harga_kry_kelinti,  harga_kry_kelbesar,
  harga_kry_kelgratisri,  harga_kry_kelrespoli,  harga_kry_kel,  harga_kry_kelgratisrj,
  harga_umum_ri,  harga_umum_rj,  harga_umum_ikutrekening,  harga_gratis_rj,
  harga_gratis_ri,  harga_pen_bebas,  harga_nempil,  harga_nempil_apt ".
    "from rs00015 a, rs00016 b, rs00001 c, rs00016a x ".
    "where a.id = b.obat_id and ".
    "a.id = x.obat_id and ".
	"a.status = '1' and ".
    //"a.kategori_id ='".$_GET["mOBT"]."' and ".
    "$kategori ".
	"a.satuan_id = c.tc and c.tt='SAT' and ".
    "upper(obat) LIKE '%".strtoupper($_GET["search"])."%'";
/*
 else{

echo "select tdesc from rs00001 where tt = 'GOB' and tc='".$_GET["mOBT"]."'";

echo $syntax2 =
    "select a.obat,c.tdesc as satuan,b.harga,x.qty_ri ".
    "from rs00015 a, rs00016 b, rs00001 c, rs00016a x ".
    "where a.id = b.obat_id and ".
    "a.id = x.obat_id and ".
    "a.status = '1' and ".
    "a.satuan_id = c.tc and c.tt='SAT' and ".
    "upper(obat) LIKE '%".strtoupper($_GET["search"])."%'";
 /*
 }
*/

echo "<BR>";
echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
echo "<INPUT TYPE=HIDDEN NAME=mOBT VALUE='".$_GET["mOBT"]."'>";
if (!$GLOBALS['print']){
echo "<TD><font class=SUB_MENU>NAMA BARANG:</font> <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
}
echo "</TR></FORM></TABLE></DIV>";

//if (!isset($_GET[sort])) {
//       $_GET[sort] = "a.obat";
//       $_GET[order] = "asc";
//}

$t = new PgTable($con, "100%");

//ini_set('display_errors',0);
$r2 = pg_query($con,
    "select sum(b.harga) as harga, x.*  ".
    "from rs00015 a, rs00016 b, rs00001 c, rs00016a x ".
    "where a.id = b.obat_id and ".
    "a.id = x.obat_id and ".
	"a.status = '1' and ".
    //"a.kategori_id ='".$_GET["mOBT"]."' and ".
    "$kategori ".
	"a.satuan_id = c.tc and c.tt='SAT' and ".
    "upper(obat) LIKE '%".strtoupper($_GET["search"])."%'"
	//"order by a.obat ASC"
	);

$d2 = pg_fetch_object($r2);
pg_free_result($r2);

/*
 $t->SQL =
    "select a.obat,c.tdesc as satuan,b.harga,x.qty_ri ".
    "from rs00015 a, rs00016 b, rs00001 c, rs00016a x ".
    "where a.id = b.obat_id and ".
    "a.id = x.obat_id and ".
    "a.kategori_id ='".$_GET["mOBT"]."' and ".
    "a.satuan_id = c.tc and c.tt='SAT' and ".
    "upper(obat) LIKE '%".strtoupper($_GET["search"])."%'
 ";
*/
$t->SQL =$syntax;

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
//$t->RowsPerPage = 1000;
if(!$GLOBALS['print']){
	$t->RowsPerPage = 25; 
}else{
	$t->RowsPerPage = pg_num_rows(pg_query($syntax));	
}
$t->ColAlign[3] = "RIGHT";
$t->ColAlign[1] = "LEFT";
$t->ColAlign[4] = "RIGHT";
$t->ColAlign[5] = "RIGHT";
$t->ColFormatNumber[2] = 2;
$t->ColFormatNumber[3] = 0;
$t->ColFormatNumber[4] = 0;
$t->ColFormatNumber[5] = 0;

$t->ColHeader = array("NAMA OBAT", "SATUAN", "HARGA NETTO","RAJAL OBAT <BR> LUAR DAN TABLET","RAJAL INJEKSI DAN ALKES","RAJAL TAGIHAN",
					  "HV","BON KARYAWAN","RAJAL KARYAWAN","ROS","RANAP UMUM KELAS III","RANAP UMUM KELAS II - VIP","RANAP IBU KELAS III (KHUSUS)",
					  "RANAP IBU KELAS III - VIP","RANAP BAYI KELAS III (KHUSUS)","RANAP BAYI KELAS III - VIP","RANAP KARYAWAN","KELUARGA INTI",
					  "RANAP IBU TAGIHAN KELAS III (KHUSUS)","RANAP IBU TAGIHAN KELAS III - VIP","RANAP UMUM TAGIHAN KELAS II - I","RANAP UMUM TAGIHAN KELAS III",
					  "ASURANSI","RAJAL RESEP LUAR");

$t->ColFooter[1] =  "Total";
$t->ColFooter[2] =  number_format($d2->harga,2);
$t->ColFooter[3] =  number_format($d2->qty_ri,2);
$t->ColFooter[4] =  number_format($d2->total,2);
	
$t->execute();

?>
