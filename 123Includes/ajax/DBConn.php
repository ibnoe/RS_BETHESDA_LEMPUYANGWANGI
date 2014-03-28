<?php
$db_host = "localhost";
$db_port = 5433;
$db_user = "postgres";
$db_pass = "1234";
$db_name = "onemedic_bethesda";

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




// In this page, we open the connection to the Database
// In this page, we open the connection to the Database
// Our MySQL database (blueprintdb) for the Blueprint Application
// Function to connect to the DB
/*function connectToDB() {
    // These four parameters must be changed dependent on your MySQL settings
    $hostdb = 'HostURL';   // MySQl host
    $userdb = 'username';    // MySQL username
    $passdb = 'password';    // MySQL password
    $namedb = 'factorydb'; // MySQL database name

    //$link = mysql_connect ("localhost:3306", "username", "password");
	//$link = mysql_connect ($hostdb, $userdb, $passdb);
	$link = mysql_connect ();

    if (!$link) {
        // we should have connected, but if any of the above parameters
        // are incorrect or we can't access the DB for some reason,
        // then we will stop execution here
        die('Could not connect: ' . mysql_error());
    }

    $db_selected = mysql_select_db($namedb);
    if (!$db_selected) {
        die ('Can\'t use database : ' . mysql_error());
    }
    return $link;
}*/
?>