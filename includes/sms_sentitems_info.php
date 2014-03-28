<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004
   // sfdn, 24-12-2006	

$PID = "sms_sentitems";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

// 24-12-2006
    if ($_SESSION[uid] == "kasir2") {
       $what = "RAWAT INAP";
       $sqlayanan = "NOT LIKE '%IGD%'";	
    } elseif ($_SESSION[uid] == "kasir1") {
       $what = "RAWAT JALAN";
       $sqlayanan = "NOT LIKE '%IGD%'";
    } else {
       $what = "IGD";
       $sqlayanan = "LIKE '%IGD%'";
    
  echo "<table>";
    echo "<tr>";
    echo "<td><img src='icon/cellphone_icon.gif' align='absmiddle' ><b><font size=4>SMS Info</size></b></td>";
    echo "</tr>";
    echo "</table>";

if(!$GLOBALS['print']){
        sms_index("sms_index_info");
        sms_outbox("sms_outbox_info");
        }else {
	}

$t = new PgTable($con, "100%");

$t->SQL = "SELECT receivingdatetime,sendernumber,textdecoded FROM inbox";

        if (!isset($_GET[sort])) {
           $_GET[sort] = "ReceivingDateTime";
           $_GET[order] = "desc";
	}
 $t->ColHeader = array("Tanggal Penerima","No. Pengirim","Pesan","&nbsp;");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColAlign[10] = "CENTER";
$t->ColAlign[11] = "CENTER";
 $t->execute();
// ---- end ----
  }  
?>