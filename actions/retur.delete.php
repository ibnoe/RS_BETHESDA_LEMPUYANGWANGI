<?php // Nugraha, 18/02/2004

session_start();

$PID = "320";

require_once("../lib/dbconn.php");
$bangsal1=$_GET["bangsal"]; 

if ($_GET[tbl] == "retur") {


    $SQL = "delete from rs00008 where ".
    	   "id = ".$_GET["del"];

    $jml = getFromTable("select (qty * harga) as jumlah from rs00008 where id=".$_GET[del]);
    $jmlx = 0 - $jml;
	$SQL1 = "update rs00005 set jumlah= jumlah - $jml where ".
    	   "reg = '".$_GET["rg"]."' and is_obat='Y' ";
    /*pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
	    "is_karcis, layanan, jumlah, is_bayar) ".
	    "values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, '$kasir', 'Y', 'N', 90000, $jmlx, 'N') ");

    if ($_SESSION[uid] == "apotek rj") {
    pg_query("update rs00016a set qty_rj = qty_rj - ".$_GET[qty].
	    "where obat_id = ".$_GET[id]);
    } elseif ($_SESSION[uid] == "apotek ri") {
    pg_query("update rs00016a set qty_ri = qty_ri - ".$_GET[qty].
	    "where obat_id = ".$_GET[id]);

    }*/

		if ($bangsal1 == "AMBUN SURI"){
		$sql3 = ("update rs00016a set qty_apotek1 = qty_apotek1 + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
        }elseif ($bangsal1 == "BAYI"){
		$sql3 = ("update rs00016a set qty_apotek2 = qty_apotek2 + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
        }elseif ($bangsal1 == "BEDAH PRIA"){
        $sql3 = ("update rs00016a set qty_apotek3 = qty_apotek3 + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "BEDAH WANITA"){
        $sql3 = ("update rs00016a set qty_apotek4 = qty_apotek4 + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "ICU"){
        $sql3 = ("update rs00016a set qty_apotek5 = qty_apotek5 + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "INTERNE PRIA"){
        $sql3 = ("update rs00016a set qty_apotek6 = qty_apotek6 + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "INTERNE WANITA"){
        $sql3 = ("update rs00016a set qty_apotek7 = qty_apotek7 + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "JANTUNG"){
        $sql3 = ("update rs00016a set qty_apotek8 = qty_apotek8 + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "KEBIDANAN"){
        $sql3 = ("update rs00016a set qty_apotek9 = qty_apotek9 + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "KELAS INTERNE"){
        $sql3 = ("update rs00016a set qty_igd = qty_igd + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "NEUROLOGI"){
        $sql3 = ("update rs00016a set qty_fisio = qty_fisio + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "PARU"){
        $sql3 = ("update rs00016a set qty_lab = qty_lab + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "RAWAT INAP MATA"){
        $sql3 = ("update rs00016a set qty_operasi = qty_operasi + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "THT"){
        $sql3 = ("update rs00016a set qty_radio = qty_radio + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "VIP CINDUA MATO"){
        $sql3 = ("update rs00016a set qty_anak = qty_anak + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}elseif ($bangsal1 == "ZAL ANAK"){
        $sql3 = ("update rs00016a set qty_gigi = qty_gigi + ".$_GET[qty].
        " where obat_id = '".$_GET[id]."'");
		}
 pg_query($con, $SQL);
 pg_query($con, $SQL1);
 pg_query($con, $sql3);

 header("Location: ../index2.php?p=$PID&rg=".$_GET["rg"]."&sub=".$_GET["sub"]."");	
}




 exit;

?>
