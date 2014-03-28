<? 

/* 

Modul Name 	: Update Grup Pengguna Aplikasi 
Create by 	: Yudha 
Note 		: Base on 810.php, 

*/

$PID = "882";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");


$level = 0;
if (strlen($_GET["L1"]) > 0) $level = 1;

if(isset($_GET["e"])) {
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>"; 

	if ($level == 0 ) {
	    $r = pg_query($con, "select * from rs_grup_user where gr_id = '".$_GET["e"]."'
                                 ");
	    $n = pg_num_rows($r);
	 
	    if($n > 0) $d = pg_fetch_object($r);
	    pg_free_result($r);
	    
		switch ($_GET["act"]) {
			case "new":
				$x_title = "Grup Pengguna baru"; 
				$x_key1 = "DISABLED" ;
				$x_key2 = "" ;
				$x_field = "" ;
				$x_btn = "SIMPAN" ;
				$x_action = "actions/882.insert.php";
			    break;		
			case "edit":
				$x_title = "Edit Grup Pengguna "; 
				$x_key1 = "DISABLED" ;
				$x_key2 = "READONLY" ;
				$x_field = "" ;
				$x_btn = "SIMPAN" ;
				$x_action = "actions/882.update.php";
			    break;
			case "del":
				$x_title = "HAPUS Grup Pengguna <br/> &#187; &#187; Grup Pengguna Dibawah ini Akan di Hapus !"; 
				$x_key1 = "DISABLED" ;
				$x_key2 = "readonly" ;
				$x_field = "DISABLED" ;
				$x_btn = "HAPUS" ;
				$x_action = "actions/882.delete.php";
			    break;    
		}	
		
	    title($x_title);     
	          
	        $f = new Form($x_action, "POST");
		$f->hidden("f_level",$level);
	        $f->text("f_gr_id","KODE",20,20,$d->gr_id,$x_key2);
	        $f->text("f_gr_nama","Nama Grup",50,50,$d->gr_nama,$x_field);
	        $f->text("f_gr_ket","Keterangan",50,50,$d->gr_ket,$x_field);
	       
	    $f->PgConn = $con;  
	                  
	    $f->submit($x_btn);
	    $f->execute();
	}
  	if ($level == 1 ) {
	    $SQL = "select a.appl_id,b.menu,a.gr_id ".
		      "from rs_grup_menu a left join rs99999 b on a.appl_id = b.id  ".
		      "Where a.gr_id = '".$_GET["L1"]."' AND a.appl_id = '".$_GET["e"]."'
                          group by a.appl_id,b.menu,a.gr_id " ;  		
	    $r = pg_query($con, $SQL );
	    $n = pg_num_rows($r);
	 
	    if($n > 0) $d = pg_fetch_object($r);
	    pg_free_result($r);
	    
		switch ($_GET["act"]) {
			case "new":
				$x_title = "Aplikasi baru";
				$x_key1 = "DISABLED" ;
				$x_key2 = "" ;
				$x_field = "" ;
				$x_btn = "SIMPAN" ;
				$x_action = "actions/882.insert.php";
				
				// checkBoxSQL($name, $capt, $sql, $ext = "")
				$ext = "";
				title($x_title); 
				 
				$f = new Form($x_action, "POST");
				$f->PgConn = $con;
				$f->hidden("f_level",$level);
				$f->hidden("f_gr_id",$_GET["L1"]);
				$f->text("f_grup","Grup ",10,10,$_GET["L1"],$x_field);
			
//				$f->checkBoxSQL("ap", "Aplikasi ","SELECT id,menu FROM rs99999 WHERE SUBSTRING(ID FROM 3 FOR 2) = '00' AND id NOT IN (select appl_id from rs_grup_menu where gr_id ='".$_GET["L1"]."') ",$ext);
				$f->checkBoxSQL_menu("ap", "Aplikasi ","SELECT id,menu FROM rs99999 WHERE  menu <> '-' order by sort_order asc ", "select appl_id from rs_grup_menu where gr_id ='".$_GET["L1"]."' ");


				$f->submit($x_btn);
			    	$f->execute();						
			    break;		
 
			case "del":
				$x_title = "HAPUS Aplikasi ";
				$x_subtitle = "&#187; &#187; Aplikasi Untuk Grup ".$d->gr_id." Dibawah ini Akan di Hapus !"; 
				$x_key1 = "DISABLED" ;
				$x_key2 = "readonly" ;
				$x_field = "DISABLED" ;
				$x_btn = "HAPUS" ;
				$x_action = "actions/882.delete.php";
				
				    title($x_title);  
   	          
				        $f = new Form($x_action, "POST");
				        $f->subtitle($x_subtitle);

					$f->hidden("f_level",$level);
					$f->hidden("f_gr_id",$d->gr_id);
					$f->hidden("f_appl_id",$d->appl_id);
				        $f->text("f_grup","Grup ",10,10,$d->gr_id,$x_field);
				        $f->text("f_aplikasi","Aplikasi",50,50,$d->menu,$x_field);
			 	       
				    $f->PgConn = $con;  	                  
				    $f->submit($x_btn);
				    $f->execute();				
			    break;    
		}	
		

	}  
    
} else {
    
    title("Tabel Master: Grup Pengguna");
        
    $ext = "OnChange = 'Form1.submit();'";

    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->selectSQL("L1", "Grup ",
        "select '' as gr_id, '-' as gr_id union " .
        "select TRIM(gr_id),TRIM(gr_nama) ".
        "from rs_grup_user where gr_id <> 'root' ", $_GET["L1"],
        $ext);
    $f->execute();

	switch ($level) {
		case 0 : 
			   $t = new PgTable($con, "100%");
			    $t->SQL = 
			        "select gr_nama,gr_ket,gr_id ".
			        "from rs_grup_user where gr_id <> 'root' group by gr_nama,gr_ket,gr_id" ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			    $t->ColAlign[2] = "CENTER";
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    
			    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=edit&e=<#2#>'>".icon("edit","Edit")."</A>"."<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=del&e=<#2#>'>".icon("delete","Delete")."</A>";
			    $t->ColHeader = array("Nama Grup", "Keterangan", "Update");
			    
			    $t->execute();
			    
			    echo "<BR><DIV ALIGN=LEFT> <img src=\"icon/user.gif\" align=\"absmiddle\"> <A CLASS=SUB_MENU ".
			         "HREF='index2.php?p=$PID&act=new&e=0'>GRUP PENGGUNA BARU</A></DIV>";						
			Break;
		case 1 : 

			   $t = new PgTable($con, "100%");
			    $t->SQL = 
			        "select a.appl_id,b.menu,a.gr_id ".
			        "from rs_grup_menu a left join rs99999 b on a.appl_id = b.id  ".
			        "Where a.gr_id = '".$_GET["L1"]."' ORDER BY a.appl_id" ;
			        
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			    $t->ColAlign[2] = "CENTER";
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=del&L1=".$_GET["L1"]."&e=<#0#>'>".icon("delete","Delete")."</A>";
			    $t->ColHeader = array("Kode", "Nama Aplikasi/Modul", "Update");
			    
			    $t->execute();
			    
			    echo "<BR><DIV ALIGN=LEFT> <img src=\"icon/informasi.gif\" align=\"absmiddle\"> <A CLASS=SUB_MENU ".
			         "HREF='index2.php?p=$PID&act=new&L1=".$_GET["L1"]."&e=0'>Aplikasi Baru</A></DIV>";		
		
			Break ;
	}
 
}
?>
