<?php 
// Nugraha, Sat Apr 24 16:39:35 WIT 2004
	 //  Ian, 30 Nov 2007 0:56 WIB
	 // ary, 24 Feb 2010 -> Developing database

//session_start();
$PID = "350";

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");



		
		
				
        $ra = pg_query($con, "select count(id) as jumlah from rs00017 ");
        $da = pg_fetch_object($ra);
        pg_free_result($ra);
		echo $da->jumlah;
        
?>
