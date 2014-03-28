<?php


require_once("../dompdf/dompdf_config.inc.php");

$alfa = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',);
$str = "";

$str .= "<style>
    p{
        line-height: 3px;
}
strong{
        line-height: 5px;
}
    table,td,th{
        border: 1px solid #000000;
        border-collapse: collapse;
        padding: 3px;
}
</style>
";


$dompdf = new DOMPDF();
$dompdf->load_html("test.html");
$dompdf->set_paper("my2", "portrait");
$dompdf->render();
//$dompdf->stream($filename . ".pdf");