<?php
//Include the code
require_once ('../lib/dbconn.php');
require_once ('phplot-5/phplot.php');


	$sql = pg_query($con,
					"SELECT a.vis_2||'('||a.vis_3 ||')' as tanggal,A.VIS_4,a.vis_5,a.vis_6
					FROM c_visit_ri a LEFT JOIN rs00006 b ON a.no_reg=b.id WHERE a.no_reg='{$_GET["id"]}' and a.id_ri = '{$_GET["p"]}' ") 
						or die(pg_errormessage()); 

		if (!$sql) exit();
		//Define some data
		$data = array();
		$n_rows = pg_num_rows($sql);
		for ($i = 0; $i < $n_rows; $i++) 
			$data[] = pg_fetch_row($sql, $i);
		//...
		
//echo "Grafik Ibu";		
//Define the object
$plot = new PHPlot();
$plot->SetDataValues($data);
//Turn off X axis ticks and labels because they get in the way:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetDrawXDataLabelLines('1');
$plot->SetXLabel('Tanggal(jam)');
$plot->SetYLabel('Suhu, Pernafasan, Nadi');
$plot->SetPlotType("linepoints");
//$plot->SetDrawVertTicks("1");
//$plot->SetDrawDashedGrid("a");
//legend
$plot->SetLegend('Pernafasan');
$plot->SetLegend('Nadi');
$plot->SetLegend('Suhu');
$plot->SetLineSpacing("1");

//$plot->PrintImage();
//Draw it
$plot->DrawGraph();


?>

