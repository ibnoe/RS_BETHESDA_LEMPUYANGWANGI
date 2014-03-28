<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004
      // sfdn, 30-04-2004

$PID = "807";
$OBT = $_POST["mOBT"];

if ($_POST['f_satuan_id'] == "") $_POST['f_satuan_id'] = "999";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb1 = New InsertQuery();
$qb1->TableName = "rs00015";
$qb1->HttpAction = "POST";
$qb1->VarPrefix = "f_";
$qb1->addFieldValue("id", "nextval('rs00015_seq')");
$SQL1 = $qb1->build();

pg_query($con, $SQL1);

$SQL2 = "insert into rs00016 (id, obat_id, harga) ".
       "values (nextval('rs00016_seq'),currval('rs00015_seq'),'".$_POST["harga"]."')";

// tokit: buat stok awal = 0 utk apotek rj dan ri
pg_query("insert into rs00016a (obat_id, qty_rj, qty_ri) ".
	"values (currval('rs00015_seq'), 0, 0)");

@$err = pg_query($con, $SQL2);

if($err == false) {
    header("Location: ../index2.php?p=$PID&err=".
        urlencode(pg_last_error($con))."&e=new");
    exit;
} else {
    header("Location: ../index2.php?p=$PID&mOBT=$OBT");
    exit;
}

?>
