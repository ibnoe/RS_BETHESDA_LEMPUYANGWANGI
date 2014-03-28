<html>
<head>
<meta http-equiv="refresh" content="300" charset=iso-8859-1">
</html>
</head>
<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004
   // sfdn, 24-12-2006	

$PID = "sms_pendaftaran";
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
    echo "<td><b><font size=4>SMS PENDAFTARAN</size></b></td>";
    echo "</tr>";
    echo "</table>";
$t = new PgTable($con, "100%");

$t->SQL = "SELECT mr_no,nama,nohp,rawatan,tgl_reg,dummy from sms_reg";

        if (!isset($_GET[sort])) {
           $_GET[sort] = "tgl_reg";
           $_GET[order] = "desc";
	}
 $t->ColHeader = array("Nomor MR","Nama","No. HP","Rawatan","Tgl Registrasi","REGISTRASI");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
	$t->ColAlign[4] = "CENTER";
	$t->ColAlign[5] = "CENTER";
	$t->ColFormatHtml[5] = "<nobr>
	   					<A CLASS=TBL_HREF "."HREF='$SC?p=120&q=reg&mr_no=<#0#>'>".icon("ok","Registrasi")."</A>
                				</nobr>";
$t->ColAlign[11] = "CENTER";
 $t->execute();
// ---- end ----
  }
?>