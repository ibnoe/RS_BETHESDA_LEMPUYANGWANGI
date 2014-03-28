<?php

include("../lib/dbconn.php");
$q = strtolower($_GET["q"]);
if (!$q)
    return;

$strQuery = "SELECT tc,tdesc FROM rs00001 WHERE tt='JEP' AND upper(tdesc) LIKE '%" . strtoupper($q) . "%' AND tc <> '000'";
$result = pg_query($con, "$strQuery");
if ($result) {
    $i=0;
    while ($ors = pg_fetch_array($result)) {
        $rerurn[$i]['tdesc'] = $ors['tdesc'];
        $rerurn[$i]['tc'] = $ors['tc'];
    $i++;
    }
}
//print_r('<pre>');
//print_r($rerurn);
//print_r('</pre>');
echo json_encode($rerurn);