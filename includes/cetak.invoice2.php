<?php
require_once("../dompdf/dompdf_config.inc.php");
$str="<HTML>
<table>
	<tr>
		<td>test</td>
	</tr>
</table>
";
$dompdf=new DOMPDF();
$dompdf->load_html($str);
$dompdf->set_paper("A4","portrait");
$dompdf->render();
$dompdf->stream('test.pdf');
?>