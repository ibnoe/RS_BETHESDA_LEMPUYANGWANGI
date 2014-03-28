<?php
ini_set('display_errors',1);
require_once '../lib/dbconn.php';

pg_query("UPDATE rs00010 SET ts_calc_stop = NULL WHERE id = (SELECT MAX(id) FROM rs00010 WHERE no_reg='".$_POST['no_reg']."')");
pg_query("UPDATE rs00006 SET status = '' WHERE id = '".$_POST['no_reg']."'");

header('Location:../index2.php?p=inf_check_out');

