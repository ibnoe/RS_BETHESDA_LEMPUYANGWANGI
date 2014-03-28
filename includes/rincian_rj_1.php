<? // Agung Sunandar , Menampilkan lap. Buku Besar Klinik


$PID = "rincian_rj_1";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > RINCIAN PENERIMAAN RAWAT JALAN");
		title_excel("rincian_rj_1&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mRAWAT=".$_GET["mRAWAT"]."");
    } else {
    	title("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > RINCIAN PENERIMAAN RAWAT JALAN");
    }
    echo "<br>";
    //$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

    if (!$GLOBALS['print']){
	    if (!isset($_GET['tanggal1D'])) {
			$tanggal1D = date("d", time());
			$tanggal1M = date("m", time());
			$tanggal1Y = date("Y", time());
			$tanggal2D = date("d", time());
			$tanggal2M = date("m", time());
			$tanggal2Y = date("Y", time());

	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

	    } else {

	    $tgl_sakjane = $_GET[tanggal2D] + 1;
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");

	    }
	    $f->selectSQL("mRAWAT", "Jenis Rawat","select distinct(a.poli::text) as tc, b.tdesc AS tdesc
                                                  from rs00006 a, rs00001 b
                                                  where b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y'
                                                  ", $_GET["mRAWAT"],"");

    	$f->submit ("TAMPILKAN");
    	$f->execute();
	} else {
		if (!isset($_GET['tanggal1D'])) {
			$tanggal1D = date("d", time());
			$tanggal1M = date("m", time());
			$tanggal1Y = date("Y", time());
			$tanggal2D = date("d", time());
			$tanggal2M = date("m", time());
			$tanggal2Y = date("Y", time());

	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

	    } else {

	    $tgl_sakjane = $_GET[tanggal2D] + 1;
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");

	    }
	  $f->selectSQL("mRAWAT", "Jenis Rawat",
    			  "select distinct(a.poli::text) as tc, b.tdesc AS tdesc
                           from rs00006 a, rs00001 b
                           where b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y' ", $_GET["mRAWAT"],"disabled");

    	$f->execute();
	}

    echo "<br>";

    echo "<br>";


//---------------agung 04/2011---------------
if ($_GET["mRAWAT"]=="-"){
$sql = "select a.poli, b.tdesc from rsv_layanan a
        left join rs00001 b on a.poli::text=b.tc and b.tt='LYN'
        group by a.poli, b.tdesc
        order by a.poli, b.tdesc ";
}else{
$sql = "select a.poli, b.tdesc from rsv_layanan a
        left join rs00001 b on a.poli::text=b.tc and b.tt='LYN'
        where a.poli='".$_GET["mRAWAT"]."'
        group by a.poli, b.tdesc
        order by a.poli, b.tdesc ";
}
        @$r1 = pg_query($con,$sql);
        @$n1 = pg_num_rows($r1);

        $max_row1= 200 ;
        $mulai1 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai1){$mulai1=1;}


if ($_GET["mRAWAT"]=="114") {
	include ("rincian_rj_obstetri.php");
	}elseif ($_GET["mRAWAT"]=="106"){
		include ("rincian_rj_tht.php");
		}elseif ($_GET["mRAWAT"]=="109"){
			include ("rincian_rj_kulit.php");
			}elseif ($_GET["mRAWAT"]=="103"){
				include ("rincian_rj_interne.php");
				}elseif ($_GET["mRAWAT"]=="107"){
					include ("rincian_rj_bedah.php");
					}elseif ($_GET["mRAWAT"]=="113"){
						include ("rincian_rj_paru.php");
						}elseif ($_GET["mRAWAT"]=="112"){
							include ("rincian_rj_jantung.php");
							}elseif ($_GET["mRAWAT"]=="115"){
								include ("rincian_rj_ginekologi.php");
								}elseif ($_GET["mRAWAT"]=="116"){
									include ("rincian_rj_jiwa.php");
									}elseif ($_GET["mRAWAT"]=="104"){
										include ("rincian_rj_anak.php");
										}elseif ($_GET["mRAWAT"]=="102"){
											include ("rincian_rj_mata.php");
											}elseif ($_GET["mRAWAT"]=="105"){
												include ("rincian_rj_gigi.php");
												}elseif ($_GET["mRAWAT"]=="111"){
													include ("rincian_rj_gizi.php");
													}						
?>
