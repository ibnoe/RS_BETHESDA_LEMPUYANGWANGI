<? 

/* 

Modul Name 	: Update Pengguna Aplikasi 
Create by 	: Yudha 
Note 		: Base on 810.php, 

*/

$PID = "881";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if(isset($_GET["e"])) {

    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
 

    $r = pg_query($con, "select * from rs99995 where id = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
 
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
 	$x_passwd ="" ;
	switch ($_GET["act"]) {
		case "new":
			$x_title = "Pengguna baru"; $x_subtitle = ""; 
			$x_key1 = "DISABLED" ;
			$x_key2 = "" ;
			$x_field = "" ;
			$x_btn = "SIMPAN" ;
			$x_action = "actions/881.insert.php";
			//$x_passwd = MD5("12345");
		    break;		
		case "edit":
			$x_title = "Edit Pengguna ";$x_subtitle = "";  
			$x_key1 = "DISABLED" ;
			$x_key2 = "" ;
			$x_field = "" ;
			$x_btn = "SIMPAN" ;
			$x_action = "actions/881.update.php";
			$x_passwd = $d->password ;
		    break;
		case "del":
			$x_title = "HAPUS Pengguna"; 
			$x_subtitle = "&#187; &#187; Pengguna Dibawah Ini Akan Dihapus !"; 
			
			$x_key1 = "DISABLED" ;
			$x_key2 = "DISABLED" ;
			$x_field = "DISABLED" ;
			$x_btn = "HAPUS" ;
			$x_action = "actions/881.delete.php";
		    break;    
		case "pass":
			$x_title = "SET PASSWORD Pengguna"; 
			$x_subtitle = "&#187; &#187; Password Pengguna ini akan di kembalikan ke seting awal !"; 
			
			$x_key1 = "DISABLED" ;
			$x_key2 = "DISABLED" ;
			$x_field = "DISABLED" ;
			$x_btn = "SET PASSWORD" ;
			$x_action = "actions/881.update.php";
			//$x_passwd = MD5($d->uid."2010");
			$x_passwd = MD5("12345");
		    break; 
	}	
	
    title($x_title);     
           
        $f = new Form($x_action, "POST");
        $f->subtitle($x_subtitle);
        $f->hidden("id",$d->id);
        $f->hidden("f_password",$x_passwd );
        $f->text("id","KODE",5,5,$d->id,$x_key1);
        $f->text("f_uid","User ID ",20,20,$d->uid,$x_key2);
        $f->text("f_nama","Nama ",50,50,$d->nama,$x_field);
        $f->text("f_posisi","Posisi/Jabatan",50,50,$d->posisi,$x_field);
               
    $f->PgConn = $con;
    $f->selectSQL("f_grup_id", "Grup Pengguna",
                  "select '-' as gr_id,'-' as gr_nama union ".
                  "select gr_id,gr_nama from rs_grup_user  ",                    
                  $d->grup_id,$x_field);    
                  
    $f->submit($x_btn);
    $f->execute();
} else {
    
    title("Tabel Master: Pengguna Aplikasi");
    
    
    
    // search box
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
//    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
//    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";
    
    
    echo "</TR></FORM></TABLE></DIV>";

    $var_cari = "";
    if ($_GET["search"]){
    	$var_cari = "where upper(uid) LIKE '%".strtoupper($_GET["search"])."%' or upper(nama) LIKE '%".strtoupper($_GET["search"])."%' " ; 
    	}

    $t = new PgTable($con, "100%");
    $t->SQL = 
        "select uid,nama,posisi,grup_id,id ".
        "from rs99995  ".$var_cari ;


    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[4] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=pass&e=<#4#>'>".icon("pass","PASSWORD")."</A> <A CLASS=TBL_HREF HREF='$SC?p=$PID&act=edit&e=<#4#>'>".icon("edit","Edit")."</A> <A CLASS=TBL_HREF HREF='$SC?p=$PID&act=del&e=<#4#>'>".icon("delete","Delete")."</A>";
    $t->ColHeader = array("ID","Nama","Posisi","Grup", "Update");
    
    $t->execute();
    
    echo "<BR><DIV ALIGN=LEFT> <img src=\"icon/user.gif\" align=\"absmiddle\"> <A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&act=new&e=0'>PENGGUNA BARU</A></DIV>";
}
?>
