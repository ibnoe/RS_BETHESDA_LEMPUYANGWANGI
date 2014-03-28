<?php

$PID = "sms_index_pbk";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$number=$_GET['number'];
$name=$_GET['name'];
$group=$_GET['groupid'];
$hidden=$_GET['number_hidden'];
pg_query("UPDATE pbk SET number='".$number."', name='".$name."',groupid='".$group."' WHERE number='".$hidden."'");

header("Location: ../index2.php?p=$PID");
exit;

?>
