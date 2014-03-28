<?php
//Untuk db_conn masih ada bugs...!!!
$db_host = "localhost";
$db_port = 5432;
$db_user = "postgres";
$db_pass = "1234";
$db_name = "onemedic_bethesda_server";

$default_page = "login/index.php";
$con = pg_connect("host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass");

function getFromTable($sql)
{
    //echo $sql;
	global $con;
    $r1 = @pg_query($con, $sql);
    if($d1 = @pg_fetch_array($r1)) {
        $ret = $d1[0];
    } else {
        $ret = NULL;
    }
   @pg_free_result($r1);
    return $ret;
}

?>
