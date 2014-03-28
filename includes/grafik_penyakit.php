<?	

$PID = "grafik_penyakit";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/setting.php"); 	
subtitle_print("DATA 10 PENYAKIT TERBANYAK");



    $f = new Form($SC, "GET", "NAME=Form1");
     title_print("");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
        include(xxx2);


   $f->selectArray("rawat_inap", "U n i t",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "onChange='document.Form1.submit();'; ");
	$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
	if ($_GET["rawat_inap"]=="Y"){
	$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
                                             SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
                                             order by tdesc ",$_GET["mRAWAT"], "");
	}elseif ($_GET["rawat_inap"]=="I"){
	$f->selectSQL("mINAP", "Bangsal ","select d.bangsal, d.bangsal as bangsal
                       from rs00010 as a 
                           join rs00012 as b on a.bangsal_id = b.id 
                           join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
                           join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
                           join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
group by d.bangsal
order by d.bangsal " ,$_GET["mINAP"], "");
	}else{}
    $f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' order by tdesc ASC ",$_GET[mPASIEN],"");
    $f->submit ("TAMPILKAN");
    $f->execute();
    
	if ($_GET["rawat_inap"]=="I"){
   $SQL = "SELECT a.diagnosis_code, a.description, count(b.id) as jml
   FROM rsv0005 a
   left JOIN rs00008 b ON a.diagnosis_code = b.item_id AND b.trans_type = 'ICD'
   left JOIN rs00006 c ON c.id = b.no_reg
   left join rs00010 e ON c.id = e.no_reg 
   join rs00012 as f on e.bangsal_id = f.id 
   join rs00012 as h on h.hierarchy = substr(f.hierarchy,1,3) || '000000000000' 
   where (b.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and (h.bangsal like '%".$_GET["mINAP"]."%') and c.tipe like '%".$_GET[mPASIEN]."%' and (c.rawat_inap like '%".$_GET[rawat_inap]."%')
   GROUP BY a.diagnosis_code, a.description
   ORDER BY (jml) desc
   LIMIT 10";
   }else{
   $SQL = "SELECT a.diagnosis_code, a.description, count(b.id) as jml
   FROM rsv0005 a
   left JOIN rs00008 b ON a.diagnosis_code = b.item_id AND b.trans_type = 'ICD'
   left JOIN rs00006 c ON c.id = b.no_reg
   where (b.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and c.poli::text like '%".$_GET["mRAWAT"]."%' and c.tipe like '%".$_GET[mPASIEN]."%' and (c.rawat_inap like '%".$_GET[rawat_inap]."%')
   GROUP BY a.diagnosis_code, a.description
   ORDER BY (jml) desc
   LIMIT 10";
   }
   
   $r1 = pg_query($con,$SQL);
				
	
 

?> 		
		
						 

<table align="center" CLASS=TBL_BORDER WIDTH='39%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr valign="top" class="TBL_HEAD">
    <td width="20%" align="center" class="TBL_BODY"><b>KODE</b></td>
    <td width="70%" align="center" class="TBL_BODY"><b>DIAGNOSA</b></td>
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

		$strXML = "<graph caption='GRAFIK 10 PENYAKIT TERBANYAK' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='' decimalPrecision='0' xAxisName='kode' yAxisName='jumlah'>";
/*    $SQL = "SELECT a.diagnosis_code, a.description, count(b.id) as jml
   FROM rsv0005 a
   left JOIN rs00008 b ON a.diagnosis_code = b.item_id AND b.trans_type = 'ICD'
   left JOIN rs00006 c ON c.id = b.no_reg
   where (b.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and c.poli::text like '%".$_GET["mRAWAT"]."%'
   GROUP BY a.diagnosis_code, a.description
   ORDER BY (jml) desc
   LIMIT 10"; */
   
   	if ($_GET["rawat_inap"]=="I"){
   $SQL = "SELECT a.diagnosis_code, a.description, count(b.id) as jml
   FROM rsv0005 a
   left JOIN rs00008 b ON a.diagnosis_code = b.item_id AND b.trans_type = 'ICD'
   left JOIN rs00006 c ON c.id = b.no_reg
   left join rs00010 e ON c.id = e.no_reg 
   join rs00012 as f on e.bangsal_id = f.id 
   join rs00012 as h on h.hierarchy = substr(f.hierarchy,1,3) || '000000000000' 
   where (b.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and (h.bangsal like '%".$_GET["mINAP"]."%') and c.tipe like '%".$_GET[mPASIEN]."%' and (c.rawat_inap like '%".$_GET[rawat_inap]."%')
   GROUP BY a.diagnosis_code, a.description
   ORDER BY (jml) desc
   LIMIT 10";
   }else{
   $SQL = "SELECT a.diagnosis_code, a.description, count(b.id) as jml
   FROM rsv0005 a
   left JOIN rs00008 b ON a.diagnosis_code = b.item_id AND b.trans_type = 'ICD'
   left JOIN rs00006 c ON c.id = b.no_reg
   where (b.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and c.poli::text like '%".$_GET["mRAWAT"]."%' and c.tipe like '%".$_GET[mPASIEN]."%' and (c.rawat_inap like '%".$_GET[rawat_inap]."%')
   GROUP BY a.diagnosis_code, a.description
   ORDER BY (jml) desc
   LIMIT 10";
   }
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
