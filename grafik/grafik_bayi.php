<?php
//Include the code
require_once ('../lib/dbconn.php');
//require_once ('../lib/class.PgTable.php');
require_once ('phplot-5/phplot.php');


	$sql = pg_query($con,
					"SELECT a.vis_2||'('||a.vis_3 ||')' as tanggal,A.VIS_4,a.vis_5
					FROM c_visit_ri a LEFT JOIN rs00006 b ON a.no_reg=b.id WHERE a.no_reg='{$_GET["id"]}' and a.id_ri = '{$_GET["p"]}' ") 
						or die(pg_errormessage()); 

		if (!$sql) exit();
		//Define some data
		$data = array();
		$n_rows = pg_num_rows($sql);
		for ($i = 0; $i < $n_rows; $i++) 
			$data[] = pg_fetch_row($sql, $i);
		//...
//Define the object
$plot = new PHPlot();
$plot->SetDataValues($data);
//Turn off X axis ticks and labels because they get in the way:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetDrawXDataLabelLines('a');
$plot->SetXLabel('Tanggal(jam)');
$plot->SetYLabel('Berat Badan, Suhu');
//legend
$plot->SetLegend('Berat Badan');
$plot->SetLegend('Suhu');
//Draw it
$plot->DrawGraph();

?>

