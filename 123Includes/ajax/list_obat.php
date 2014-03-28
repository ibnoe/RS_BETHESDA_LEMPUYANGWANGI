<?php

include("../../lib/dbconn.php");
$q = strtolower($_GET["q"]);
if (!$q)
    return;

$strQuery = "SELECT id,obat FROM rs00015 WHERE upper(obat) LIKE '%" . strtoupper($q) . "%'";
$result = pg_query($con, "$strQuery");
if ($result) {
    $i=0;
    while ($ors = pg_fetch_array($result)) {
        $rerurn[$i]['id'] = $ors['id'];
        $rerurn[$i]['obat'] = $ors['obat'];
    $i++;
    }
}
//print_r('<pre>');
//print_r($rerurn);
//print_r('</pre>');
echo json_encode($rerurn);