<?

$PID = "history_user";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

   if (!$GLOBALS['print']){
    	//title("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Per Kasir");
		title_print("<img src='icon/jam.gif' align='absmiddle' > History User");
    } else {
    	title("<img src='icon/jam.gif' align='absmiddle' > History User");
    }
    
	
	$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	if (!$GLOBALS['print']) {
	    if (!isset($_GET['tanggal1D'])) {

		$tanggal1D = date("d", time());
		$tanggal1M = date("m", time());
		$tanggal1Y = date("Y", time());
		$tanggal2D = date("d", time());
		$tanggal2M = date("m", time());
		$tanggal2Y = date("Y", time());

	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

	    } else {
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
	    }

		$f->selectSQL("mMENU", "Menu","select '' as item_id, '' as item_id union ".
    			  "select item_id as item_id, item_id as item_id ".
    			  "from history_user ", $_GET["mMENU"],"");
				  
		$f->selectSQL("mUSER", "User Id","select '' as user_id, '' as user_id union ".
    			  "select user_id as user_id, user_id as user_id ".
    			  "from history_user ", $_GET["mUSER"],"");
		
		$f->submit ("TAMPILKAN");
	    $f->execute();

	} else {
		if (!isset($_GET['tanggal1D'])) {

		$tanggal1D = date("d", time());
		$tanggal1M = date("m", time());
		$tanggal1Y = date("Y", time());
		$tanggal2D = date("d", time());
		$tanggal2M = date("m", time());
		$tanggal2Y = date("Y", time());

	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

	    } else {
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
	    }
		$f->selectSQL("mMENU", "Menu","select '' as item_id, '' as item_id union ".
    			  "select item_id as item_id, item_id as item_id ".
    			  "from history_user ", $_GET["mMENU"],"Disabled");
				  
		$f->selectSQL("mUSER", "User Id","select '' as user_id, '' as user_id union ".
    			  "select user_id as user_id, user_id as user_id ".
    			  "from history_user ", $_GET["mUSER"],"Disabled");
	    $f->execute();
	}


    echo "<br>";

	$SQL = "select  to_char(tanggal_entry, 'DD MON YYYY'), to_char(waktu_entry,'hh24:mi:ss'), trans_form, item_id, keterangan,user_id,nama_user 
	from history_user 
	where (tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and item_id like '%".$_GET[mMENU]."%' and user_id like '%".$_GET[mUSER]."%'";


    if (empty($_GET[sort])) {
	$_GET[sort] = "tanggal_entry, waktu_entry";
	$_GET[order] = "asc";
    }

    $t = new PgTable($con, "100%");
    $t->SQL = "$SQL";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColHeader = array("TANGGAL ENTRY","WAKTU ENTRY","TEMPAT","MENU","KETERANGAN","USER ID","NAMA USER");
	$t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT");
	//$t->ColFooter[6]=  number_format($d2->jmlbayar,2,',','.');
	
    $t->execute();

?>