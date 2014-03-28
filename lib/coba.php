<?php
include("../lib/dbconn.php");
$q = strtolower($_GET["q"]);
if (!$q) return;
			
                     $strQuery = "SELECT nama FROM rs00002 WHERE nama ILIKE '%$q%' LIMIT 10 OFFSET 0";
	                 $result = pg_query($con, "$strQuery");	
	                 if ($result) 
					 { while($ors = pg_fetch_array($result)) 
					     {	echo $ors['nama']. "\n";
	                     }
					  }
		              
								        

?>