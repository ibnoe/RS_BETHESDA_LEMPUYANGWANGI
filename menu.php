<?php
// Nugraha, 03/02/2004
//          07/02/2004

// sfdn, 21-12-2006

session_start();
require_once("lib/dbconn.php");
echo "menu = new Menu('basemenu');\n";
echo "menu.bg=['#198D19','#FFFFFF','#198D19','#FFFFFF'];\n";
echo "menu.cl=['#FFFFFF','#198D19','#FFFFFF','#198D19'];\n";
echo "menu.mf='arial';\n";
echo "menu.mfs='12px';\n";
echo "menu.mfw='normal';\n";
echo "menu.smf='arial';\n";
echo "menu.smfs='12px';\n";
echo "menu.smfw='normal';\n";
echo "menu.bc='#198D19';\n";
echo "menu.bw='1';\n";
/*echo "menu = new Menu('basemenu');\n";
echo "menu.bg=['#2D405B','#58565d','#2D405B','#58565d'];\n";
echo "menu.cl=['#58565d','#2D405B','#58565d','#2D405B'];\n";
echo "menu.mf='arial';\n";
echo "menu.mfs='12px';\n";
echo "menu.mfw='normal';\n";
echo "menu.smf='arial';\n";
echo "menu.smfs='12px';\n";
echo "menu.smfw='normal';\n";
echo "menu.bc='#58565d';\n";
echo "menu.bw='1';\n";*/

// Home Button 

  echo "addM(menu,'m{1}','HOME','index3.php',200);\n";
  	    
if ($_SESSION[uid]) {
 echo "addSM(menu,'m{1}','&nbsp;','');\n";
 echo "addSM(menu,'m{1}','&#187; Set Password','index2.php?p=set_password');\n";
 echo "addSM(menu,'m{1}','&nbsp;','');\n";
 echo "addM(menu,'','&#187;','',200);\n";
  
	
 // $tambah = " id IN (select appl_id from rs_grup_menu where gr_id ='".$_SESSION[gr]."') ";
  
 //$sql = "SELECT * FROM rs99999 WHERE SUBSTRING(ID FROM 3 FOR 2) = '00' $tambah ORDER BY sort_order";


	/* Menu Utama */ 
	$sql = "SELECT * FROM rs99999 WHERE SUBSTRING(id FROM 3 FOR 2) = '00' ORDER BY sort_order";
	
	$menu_sql = "select appl_id from rs_grup_menu where gr_id ='".$_SESSION[gr]."' and SUBSTRING(ID FROM 3 FOR 2) != '00'";
	if ($_SESSION[gr] == 'root'){
		$menu_sql = "SELECT id FROM rs99999 WHERE SUBSTRING(id FROM 3 FOR 2) != '00' ORDER BY sort_order";
	}   
	
	$r1 = pg_query($con, $sql);
	$menu_root = "" ; 
	
	while ($d1 = pg_fetch_object($r1)) {
	
	    //$root_menu = substr($d1->id,0,2) ; 
	    $r2 = pg_query($con, "SELECT * FROM rs99999 ".
	          "WHERE SUBSTRING(id FROM 1 FOR 2) = '".substr($d1->id,0,2)."' ".
	          "AND ".
                  //"id =  ".
                  "id IN (".$menu_sql.") ".
	          "or ".
                  "menu = '-'  ".
                  "ORDER BY sort_order");

	    
	    if (pg_num_rows($r2) > 0) {
	    	//echo "addM(menu,'m{$d1->id}','$d1->menu','',250);\n";
	    	$menu_root = "addM(menu,'m{$d1->id}','$d1->menu','',250);\n";
	    	$xN = 0 ; 
	    	$ada_sub = false ; 
	    	$menu_sub ="";
	        while ($d2 = pg_fetch_object($r2)) {
	            
	            if ($d2->menu == "-") {
	            	$xN++ ;
	            	if ($xN < 2)
                            $menu_sub .= "addSM(menu,'m{$d1->id}','&nbsp;','$d2->href');\n";
	                
	            } else {
	            	//if (in_array($d2->id, $y)) {	
	            		$xN = 0 ; 
	            		$ada_sub = true ;            	
	            		$menu_sub .= "addSM(menu,'m{$d1->id}','&#187; $d2->menu','$d2->href');\n";
	        	//}
	            }
	        }
	    }
	    pg_free_result($r2);
	    
	    if ($ada_sub){
	    	echo $menu_root ;
 	    	echo $menu_sub ;
	    }
	}
	echo "addM(menu,'m{x}','[ LOGOUT ]','login/logout.php',200);\n";
	pg_free_result($r1);
}  


echo "writeStyle(menu);\n";
?>
