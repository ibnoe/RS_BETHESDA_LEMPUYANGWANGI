<?

$PID = "grafik_obat";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/setting.php");
subtitle_print("DATA 10 OBAT TERBANYAK");
//subtitle_print("Triwulan : ".$set_triwulan);
//subtitle_rs("FORMULIR RL1 - PENGUNJUNG RUMAH SAKIT");
//subtitle_rs($set_header[0]." ".$set_header[1]);
//subtitle_rs("No. Kode RS : ".$set_kode_rs);

	if(!$GLOBALS['print']){
	 //	title_print("");
	//	title_excel("pengunjung_rumah_sakit");
   	//    edit_laporan("input_pengunjung_rumah_sakit");
	}else {

	}
    $f = new Form($SC, "GET", "NAME=Form1");
     title_print("");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
        include(xxx2);

    $f->submit ("TAMPILKAN");
    $f->execute();
    //    $okeh =" name=\"reg\"";


				//$SQL = "select * from grafik1 order by no";
   $SQL = "SELECT a.id, a.obat,case when sum(b.qty) is null then 0 else sum(b.qty) end as jml
   FROM rs00015 a
   left JOIN rs00008 b ON b.item_id::text = a.id::text AND b.trans_type::text = 'OB1'
   where (b.tanggal_entry between '$ts_check_in1' and '$ts_check_in2')
   GROUP BY a.id, a.obat
   ORDER BY (jml) desc
   LIMIT 10";
   $r1 = pg_query($con,$SQL);


 

?>



<table align="center" CLASS=TBL_BORDER WIDTH='39%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr valign="top" class="TBL_HEAD">
    <td width="20%" align="center" class="TBL_BODY"><b>KODE</b></td>
    <td width="70%" align="center" class="TBL_BODY"><b>OBAT</b></td>
    <td width="10%" align="center" class="TBL_BODY"><b>JUMLAH</b></td>
  </tr>
<?
//$ke = 1;
 //for ($ke=1;$ke<=10;$ke++) {
while ($n1 = pg_fetch_row($r1)) {

    ?>
 <tr valign="top" class="TBL_HEAD">
    <td width="20%" align="center" class="TBL_BODY"><?=$n1[0] ?></td>
    <td width="70%" align="left" class="TBL_BODY"><?=$n1[1] ?></td>
    <td width="10%" align="center" class="TBL_BODY"><?=$n1[2] ?> </td>
    <?//echo "<script>alert(\".\");</script>";?>
 </tr>

<? } ?>  <? //} ?>
</table>

<TR>
  <TD  rowspan="2" colspan="2" align="center"><? include("123Includes/FusionCharts.php");

		$strXML = "<graph caption='GRAFIK 10 OBAT TERBANYAK' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='' decimalPrecision='0' xAxisName='kode' yAxisName='jumlah'>";
   $SQL = "SELECT a.id, a.obat,case when sum(b.qty) is null then 0 else sum(b.qty) end as jml
   FROM rs00015 a
   left JOIN rs00008 b ON b.item_id::text = a.id::text AND b.trans_type::text = 'OB1'
   where (b.tanggal_entry between '$ts_check_in1' and '$ts_check_in2')
   GROUP BY a.id, a.obat
   ORDER BY (jml) desc
   LIMIT 10";
   $r1 = pg_query($con,$SQL);
   while ($n1 = pg_fetch_row($r1)) {
   $strXML .= "<set name='" . $n1[0] . "' value='" . $n1[2] . "' />";
   }
                /*$ke = 1;
		for ($ke=1;$ke<=10;$ke++) {
		$strQuery3 = getfromtable("SELECT  count(b.id) as jml
                                          FROM rsv0005 a
                                          left JOIN rs00008 b ON a.diagnosis_code = b.item_id AND b.trans_type = 'ICD'
                                          --where count(b.id) = 3
                                          GROUP BY a.diagnosis_code, a.description
                                          ORDER BY (jml) desc
                                          LIMIT 10 ");

			$strXML .= "<set name='" . $ke . "' value='" . $strQuery3 . "' />";
		}*/


	//Finally, close <graph> element
	$strXML .= "</graph>";
        //echo "<script>alert(\"$strXML.\");</script>";
        //echo "<script>alert(\"$strQuery3.\");</script>";
	//Create the chart - Pie 3D Chart with data from $strXML
	echo renderChartHTML("FusionCharts/FCF_Column3D.swf", "", $strXML, "2", 800, 300);
	 //echo renderChartHTML("FusionCharts/FCF_Line.swf", "Data/Data.xml", "", "myFirst", 800, 250);?> </TD>
			  </TR>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
