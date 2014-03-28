<?	

$PID = "pengunjung_rumah_sakit";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/setting.php"); 	

	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("pengunjung_rumah_sakit&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."");		
   	    //edit_laporan("input_pengunjung_rumah_sakit");
	}else {
		
	}
 
	$f = new Form($SC, "GET", "NAME=Form1");
	//title_excel("rekap_kunjungan&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mRAWAT=".$_GET["mRAWAT"]."&mPASIEN=".$_GET["mPASIEN"]."");
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
        
    	$f->execute();
	}
echo "<br>";
subtitle_print("DATA KUNJUNGAN PASIEN RUMAH SAKIT");
//subtitle_print("Triwulan : ".$set_triwulan);

subtitle_print("FORMULIR RL1 - PENGUNJUNG RUMAH SAKIT");
subtitle_print($set_header[0]." ".$set_header[1]);
//subtitle_rs("No. Kode RS : ".$set_kode_rs);    
echo "<br>";
     		
	$Baru = getFromTable("select count(is_baru)  from pengunjung where is_baru='Y' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')");
	$lama = getFromTable("select count(is_baru)  from pengunjung where is_baru='N' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')");

?> 		
<table align="center" CLASS=TBL_BORDER WIDTH='39%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
 <tr valign="top" class="TBL_HEAD">
    
    <td width="20%" align="center" class="TBL_HEAD">PENGUNJUNG BARU</td>
    <td width="20%" align="center" class="TBL_HEAD">PENGUNJUNG LAMA</td>
	<td width="20%" align="center" class="TBL_HEAD">TOTAL PENGUNJUNG</td>
  </tr>
   <tr valign="top" class="TBL_HEAD">
    <td width="20%" align="center" class="TBL_BODY"><?echo $Baru;?></td>
    <td width="20%" align="center" class="TBL_BODY"><?echo $lama;?></td>
    <td width="20%" align="center" class="TBL_BODY"><?echo $Baru+$lama;?></td>
  </tr>
</table>
<?
echo "<br>";
$sql="select a.tanggal_reg, (select count(b.is_baru) from pengunjung b where b.tanggal_reg=a.tanggal_reg AND b.is_baru='Y') as baru, 
(select count(c.is_baru) from pengunjung c where c.tanggal_reg=a.tanggal_reg AND c.is_baru='N') as lama
from pengunjung a
where (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')
group by a.tanggal_reg ";

	@$r1 = pg_query($con,$sql);
    @$n1 = pg_num_rows($r1);

	$max_row= 9999999999 ;
	$mulai = $HTTP_GET_VARS["rec"] ;
	if (!$mulai){$mulai=1;}

echo "<br>";
echo "<br>";
subtitle_print("DATA RINCIAN KUNJUNGAN PASIEN RUMAH SAKIT");
//subtitle_print("Triwulan : ".$set_triwulan);
echo "<br>";
?>
<table align="center" CLASS=TBL_BORDER WIDTH='65%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
 <tr valign="top" class="TBL_HEAD">
    <td width="5%" align="center" class="TBL_HEAD">NO.</td>
	<td width="20%" align="center" class="TBL_HEAD">TANGGAL REG.</td>
    <td width="20%" align="center" class="TBL_HEAD">PENGUNJUNG BARU</td>
    <td width="20%" align="center" class="TBL_HEAD">PENGUNJUNG LAMA</td>
	<td width="20%" align="center" class="TBL_HEAD">TOTAL PENGUNJUNG</td>
  </tr>
  
  <?
    $row1=0;
    $i= 1 ;
    $j= 1 ;
    $last_id=1;
    while (@$row1 = pg_fetch_array($r1)){
        if (($j<=$max_row) AND ($i >= $mulai)){
                $no=$i;
				

                ?>
                <tr valign="top" class="<? ?>" >
                        <td class="TBL_BODY" align="center"><?=$no?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["tanggal_reg"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["baru"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["lama"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["baru"] + $row1["lama"]?> </td>
                </tr>

                <?;$j++;
        }
        $i++;
} 
?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
