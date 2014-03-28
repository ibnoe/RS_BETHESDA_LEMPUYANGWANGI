<?php

include("../lib/dbconn.php");
$q = strtolower($_GET["q"]);
if (!$q)
    return;

$strQuery = "SELECT nama FROM rs00002 WHERE nama LIKE '%" . strtoupper($q) . "%'";
$result = pg_query($con, "$strQuery");
if ($result) {
    $i=0;
    while ($ors = pg_fetch_array($result)) {
        $rerurn[$i]['nama'] = $ors['nama'];
    $i++;
    }
}
//print_r('<pre>');
//print_r($rerurn);
//print_r('</pre>');
echo json_encode($rerurn);