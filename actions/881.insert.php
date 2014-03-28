<?php // Nugraha, 22/02/2004

$PID = "881";

require_once("../lib/dbconn.php");
//require_once("../lib/querybuilder.php");

$default_password =  MD5("12345");
//$default_password =  MD5($_POST["f_uid"]."2007");
/*
$qb = New InsertQuery();
$qb->TableName = "rs99995";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id","nextval('rs99995_seq')");
 $SQL = $qb->build();

*/

    $SQL = "insert into rs99995 " .
           "(id, uid,nama,posisi,password,grup_id) ".
           "values".
           "(nextval('rs99995_seq'),'".$_POST["f_uid"]."','".$_POST["f_nama"]."','".$_POST["f_posisi"]."','".$default_password."','".$_POST["f_grup_id"]."')";
     

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
