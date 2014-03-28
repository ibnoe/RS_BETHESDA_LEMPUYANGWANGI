<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 21-04-2004
   // sfdn, 08-06-2004
   // sfdn, 12-06-2004
   // tokit aja, 09-09-2004

$PID = "480";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 50;
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if ($_GET["mLAPOR"] == "002") {
    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));

	$judul	= getFromTable("select tdesc from rs00001 where tc='".$_GET["mLAPOR"]."' and tt='LMR'");
	$prd1	= getFromTable("select to_char(to_date('$ts_check_in1','YYYY-MM-DD'),'DD MON YYYY')");
	$prd2	= getFromTable("select to_char(to_date('$ts_check_in2','YYYY-MM-DD'),'DD MON YYYY')");

    title($judul);
    echo "<br>";
    $f = new Form("");
    $f->subtitle("Periode : $prd1 s/d $prd2");
    $f->execute();
    $t = new PgTable($con, "100%");
    $t->SQL =
    	"select a.layanan, ".
    	"	(select count(no_reg) ".
        "		from rsv0040 ".
        "		where substr(hierarchy,1,6)=substr(a.hierarchy,1,6) and ".
	"			tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
    	"	) as pasien, ".
    	"	(select count(no_reg) ".
        "		from rsv0040 ".
        "		where is_baru='Y' and ".
        "		substr(hierarchy,1,6)=substr(a.hierarchy,1,6) and ".
	"			tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
    	"	) as baru, ".
    	"	(select count(no_reg) ".
        "		from rsv0040 ".
        "		where is_baru='T' and ".
        "		substr(hierarchy,1,6)=substr(a.hierarchy,1,6) and ".
	"			tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
    	"	) as lama, a.hierarchy as dummy ".
	"from rs00034 a ".
	"where substr(a.hierarchy,1,3)='002' and ".
	"		substr(a.hierarchy,4,3) NOT IN ('000') and ".
	"	is_group ='Y' and substr(a.hierarchy,1,6) NOT IN ('002087','002000','002086','002084')";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
	$t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
	$t->ColAlign[4] = "CENTER";
    $t->ColHeader = Array("P O L I", "PASIEN MASUK", "B A R U", "L A M A","V i e w");
	$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&v=<#4#>'>".icon("view","View")."</A>";	
    $t->execute();

} else {
    title("LAPORAN LOKET RAWAT JALAN");
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    //$f = new Form($SC, "GET");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    
    

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
	    
    //$tgl_sakjane = $_GET[tanggal2D] + 1;

    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");

    //echo "Xxx: $ts_check_in2"; exit();	
	}

        $f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt='JEP' and tc != '000' ", $_GET["mPASIEN"],
        $ext);
        
        if ($_GET[mPASIEN] == "") {
           $tipe_pasien = "";
        } else {
           $tipe_pasien = " and rs00006.tipe = '".$_GET[mPASIEN]."'";
        }

	//------
 $r = pg_query($con,
	"select a.hierarchy, a.layanan, ".
	"	(select sum(p.qty) ".
	"		   from rs00008 p ".
	"				left join rs00006 r ON r.id = p.no_reg and r.tipe like '%".$_GET[mPASIEN]."%' ".
	"				left join rs00034 s ON r.poli = s.id ".
	"		   where p.is_inout = 'I' and p.trans_type IN ('RJN') ".
	"				 and substr(s.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"			     and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"	) as pasien, ".

	"	(select sum(x.tagihan) ".
	"		from rs00008 x ".
	"			left join rs00034 y ON to_number(x.item_id,'999999999999') = y.id ".
	"                       left join rs00006 zz ON zz.id = x.no_reg ".
	"		where  substr(y.hierarchy,7,3)='001' and substr(y.hierarchy,1,6) NOT IN ('002085','002086') ".
	"			   and substr(y.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"			   and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			   and x.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"	) as jumjs, ".

        "	(select sum(x.tagihan) ".
	"		from rs00008 x ".
	"			left join rs00034 y ON to_number(x.item_id,'999999999999') = y.id ".
	"                       left join rs00006 zz ON zz.id = x.no_reg ".
	"		where  substr(y.hierarchy,7,3)='002' and substr(y.hierarchy,1,6) NOT IN ('002085','002086') ".
	"			   and substr(y.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"			   and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			   and x.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			   and substr(y.hierarchy,1,6) IN ('002082','002083','002016','002017') ".
	"	) as jumbpumum, ".

	"	(select sum(x.tagihan)  ".
	"	from rs00008 x ".
	"		left join rs00034 y ON to_number(x.item_id,'999999999') = y.id ".
	"                       left join rs00006 zz ON zz.id = x.no_reg ".
	"	where x.trans_type='LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"		and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"		and substr(y.hierarchy,7,3) IN ('002') ".
	"		and substr(y.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"		and substr(y.hierarchy,1,6) NOT IN ('002082','002083','002085','002086') ".
	"	) as jumbpspesialis, ".

	"	(select sum(x.tagihan)  ".
	"	from rs00008 x ".
	"	   left join rs00034 y ON to_number(x.item_id,'999999999') = y.id ".
	"                       left join rs00006 zz ON zz.id = x.no_reg ".
	"	where x.trans_type='LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"		   and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"		   and (substr(y.hierarchy,7,3) IN ('003') OR y.layanan like '%OBAT%') ".
	"		   and substr(y.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"		   and substr(y.hierarchy,1,6) NOT IN ('002085') ".
	"	       and substr(y.hierarchy,1,6) IN ('002082','002083') ".
	"	) as jumobatumum, ".

	"	(select sum(x.tagihan)  ".
	"	from rs00008 x ".
	"	   left join rs00034 y ON to_number(x.item_id,'999999999') = y.id ".
	"                       left join rs00006 zz ON zz.id = x.no_reg ".
	"	where x.trans_type='LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"	       and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"		   and (substr(y.hierarchy,7,3) IN ('003') OR y.layanan like '%OBAT%') ".
	"		   and substr(y.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"	   and substr(y.hierarchy,1,6) NOT IN ('002082','002083','002085','002086') ".
	"	) as jumobatspesialis, ".

	"	((select sum(x.tagihan)  ".
	"		from rs00008 x ".
	"			left join rs00034 y ON  to_number(x.item_id,'999999999999') = y.id ".
	"                       left join rs00006 zz ON zz.id = x.no_reg ".
	"		where  substr(y.hierarchy,7,3)='001' ".
	"			   and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			   and substr(y.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"			   and x.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"		       and substr(y.hierarchy,1,6) NOT IN ('002085','002086') ".
	"	) ".
	"	+ ".
	"	(select sum(x.tagihan) ".
	"		from rs00008 x ".
	"			left join rs00034 y ON to_number(x.item_id,'999999999999') = y.id ".
	"                       left join rs00006 zz ON zz.id = x.no_reg ".
	"		where  substr(y.hierarchy,7,3)='002' ".
	"			   and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			   and substr(y.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"			   and x.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			   and substr(y.hierarchy,1,6) IN ('002082','002083') ".
	"		       and substr(y.hierarchy,1,6) NOT IN ('002085','002086') ".
	"	) ".
	"	+ ".
	"	(select sum(x.tagihan)   ".
	"	from rs00008 x ".
	"		left join rs00034 y ON to_number(x.item_id,'999999999') = y.id ".
	"                       left join rs00006 zz ON zz.id = x.no_reg ".
	"	where x.trans_type='LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"		and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"		and substr(y.hierarchy,7,3) IN ('002') ".
	"		and substr(y.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"	    and substr(y.hierarchy,1,6) NOT IN ('002082','002083','002085','002086') ".
	"	)  ".
	"	+ ".
	"	(select sum(x.tagihan)  ".
	"	from rs00008 x ".
	"	         left join rs00034 y ON to_number(x.item_id,'999999999') = y.id ".
	"                       left join rs00006 zz ON zz.id = x.no_reg ".
	"	where x.trans_type='LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"          and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"   	   and (substr(y.hierarchy,7,3) IN ('003') OR y.layanan like '%OBAT%') ".
	"		   and substr(y.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"	       and substr(y.hierarchy,1,6) IN ('002082','002083') ".
	"		   and substr(y.hierarchy,1,6) NOT IN ('002085') ".
	"	) ".
	"	+ ".
	"	(select sum(x.tagihan) ".
	"	from rs00008 x ".
	"	   left join rs00034 y ON to_number(x.item_id,'999999999') = y.id ".
	"                       left join rs00006 zz ON zz.id = x.no_reg ".
	"	where x.trans_type='LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"          and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"		   and (substr(y.hierarchy,7,3) IN ('003') OR y.layanan like '%OBAT%') ".
	"		   and substr(y.hierarchy,1,3)= substr(a.hierarchy,1,3) ".
	"	   	   and substr(y.hierarchy,1,6) NOT IN ('002082','002083','002085','002086') ".
	"	))  ".
	"	as jumbaris ".
	"from rs00034 a ".
	"where substr(a.hierarchy,1,3)='002' ".
	"  and a.is_group ='Y' ".
	"  and substr(a.hierarchy,1,6) NOT IN ('002085','002084','002010','002086') ".
	"  and length(rtrim(a.hierarchy,'0'))!=3 ");


        //"	and substr(a.hierarchy,4,12)='000000000000'  ");



 	$d = pg_fetch_object($r);
        pg_free_result($r);

$SQL =
	"select a.layanan, ".
	"   case when (select sum(p.qty) ".
	"   from rs00008 p ".
	"			left join rs00006 r ON r.id = p.no_reg  and r.tipe like '%".$_GET[mPASIEN]."%'  ".
	"			left join rs00034 s ON r.poli = s.id ".
	"	   where p.is_inout = 'I' and p.trans_type IN ('RJN') ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			 and substr(s.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.qty) ".
	"	   from rs00008 p ".
	"			left join rs00006 r ON r.id = p.no_reg  and r.tipe like '%".$_GET[mPASIEN]."%'  ".
	"			left join rs00034 s ON r.poli = s.id ".
	"	   where p.is_inout = 'I' and p.trans_type IN ('RJN') ".
	"			 and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			 and substr(s.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"	as pasien, ".
/*	"	(select min(x.no_kwitansi) ".
	"	   from rs00008 x ".
	"		   left join rs00034 y ON ".
	"				to_number(x.item_id,'999999999')= y.id ".
	"		   left join rs00034 z ON ".
	"				substr(y.hierarchy,1,6) = substr(z.hierarchy,1,6) ".
	"	   where x.no_kwitansi!=0 and x.trans_type='LTM' ".
	"			and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"		   and substr(y.hierarchy,1,6)=substr(a.hierarchy,1,6) ".
	"		   and z.is_group='Y' ".
	"	) as kwimin, ".
	"	(select max(x.no_kwitansi) ".
	"	   from rs00008 x ".
	"		   left join rs00034 y ON ".
	"				to_number(x.item_id,'999999999')= y.id ".
	"		   left join rs00034 z ON ".
	"				substr(y.hierarchy,1,6) = substr(z.hierarchy,1,6) ".
	"	   where x.no_kwitansi!=0 and x.trans_type='LTM' ".
	"			and x.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"		   and substr(y.hierarchy,1,6)=substr(a.hierarchy,1,6) ".
	"		   and z.is_group='Y' ".
	"	) as kwimak, ".
*/	"  case when (select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='001' ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM'  and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and substr(q.hierarchy,7,3)='001' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"  as js, ".

	"   case when (select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM'  and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and (substr(q.hierarchy,4,3) IN ('082','083','016','017')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and (substr(q.hierarchy,4,3) IN ('082','083','016','017')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"  as bpumum, ".

	"   case when (select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and (substr(q.hierarchy,4,3) IN ".
	"			   ('005','003','004','002','001','006','008','009','086')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and (substr(q.hierarchy,4,3) IN ".
	"			   ('005','003','004','002','001','006','008','009','086')) ".
	"	   and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"   as bpspesialis, ".

	"   case when (select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
        "	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and (substr(q.hierarchy,4,3) IN ('082','083')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and substr(q.hierarchy,7,3)='003' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and (substr(q.hierarchy,4,3) IN ('082','083')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"	as obatumum, ".

	"	case when (select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
        "	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and (substr(q.hierarchy,4,3) IN ".
	"			   ('005','003','004','002','001','006','008','009','086')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and substr(q.hierarchy,7,3)='003' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and (substr(q.hierarchy,4,3) IN ".
	"			   ('005','003','004','002','001','006','008','009','086')) ".
	"	        and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"   as obatspesialis, ".

	"  (case when (select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
        "	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='001' ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='001' ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"  + ".
	"  case when (select sum(p.tagihan) ".
	"   from rs00008 p ".
	"		left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and (substr(q.hierarchy,4,3) IN ('082','083','016','017')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and (substr(q.hierarchy,4,3) IN ('082','083','016','017')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"   + ".
	"   case when (select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM'  and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and (substr(q.hierarchy,4,3) IN ".
	"			   ('005','003','004','002','001','006','008','009','086')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and (substr(q.hierarchy,4,3) IN ".
	"			   ('005','003','004','002','001','006','008','009','086')) ".
	"	   and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"  + ".
	"   case when (select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and (substr(q.hierarchy,4,3) IN ('082','083')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='003' ".
	"			and (substr(q.hierarchy,4,3) IN ('082','083')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"	+ ".
	"	case when (select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='002' ".
	"			and (substr(q.hierarchy,4,3) IN ".
	"			   ('005','003','004','002','001','006','008','009','086')) ".
	"			and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) isnull ".
	"	   then 0 ".
	"	   else ".
	"			(select sum(p.tagihan) ".
	"	   from rs00008 p ".
	"			left join rs00034 q ON to_number(p.item_id,'999999999') = q.id ".
	"                       left join rs00006 zz ON zz.id = p.no_reg ".
	"	   where p.trans_type = 'LTM' and zz.tipe like '%".$_GET[mPASIEN]."%' ".
	"			and p.tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
	"			and substr(q.hierarchy,7,3)='003' ".
	"			and (substr(q.hierarchy,4,3) IN ".
	"			   ('005','003','004','002','001','006','008','009','086')) ".
	"	   and substr(q.hierarchy,1,6)= substr(a.hierarchy,1,6)) end ".
	"   ) as jumdatar ".
	"from rs00034 a ".
	"where substr(a.hierarchy,1,3)='002' ".
	"  and a.is_group ='Y' ".
	"  and substr(a.hierarchy,1,6) NOT IN ('002085','002084','002010','002086') ".
	"  and a.layanan not like '%VISUM ET%'".
	"  and length(rtrim(a.hierarchy,'0'))!=3 ";

    //$f->submit(" Laporan ", "'actions/430.lap.".$_GET["mPEG"].".php'");
    $f->submit(" Laporan ");
    $f->execute();

	//$f->hidden("mLAPOR",$_GET["mLAPOR"]);
	//$f->hidden("ts_check_in1",'$ts_check_in1');
	//$f->hidden("ts_check_in2",'$ts_check_in2');


    if (empty($_GET[sort])) {
       $_GET[sort] = "layanan";
       $_GET[order] = "asc";
    }

    $t = new PgTable($con, "100%");
    $t->SQL =   $SQL;
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
	$t->ColFormatNumber[4] = 2;
	$t->ColFormatNumber[5] = 2;
	$t->ColFormatNumber[6] = 2;
	$t->ColFormatNumber[7] = 2;
	$t->ColFormatNumber[2] = 2;
	$t->ColFormatNumber[3] = 2;
    $t->ColHeader = Array("UNIT LAYANAN", "PASIEN","JASA SARANA", "B/P<br>UMUM",
                          "B/P SPESIALIS", "OBAT R/J PAKET", "OBAT R/J LANJUT","TOTAL");
	$t->ColFooter[1] =  number_format($d->pasien,0);
	$t->ColFooter[2] =  number_format($d->jumjs,2,',','.');
	$t->ColFooter[3] =  number_format($d->jumbpumum,2,',','.');
	$t->ColFooter[4] =  number_format($d->jumbpspesialis,2,',','.');
	$t->ColFooter[5] =  number_format($d->jumobatumum,2,',','.');
	$t->ColFooter[6] =  number_format($d->jumobatspesialis,2,',','.');
	$t->ColFooter[7] =  number_format($d->jumbaris,2,',','.');
    $t->execute();
}

?>
