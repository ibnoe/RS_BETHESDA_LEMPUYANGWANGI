<?php

session_start();

$PID = "p_riwayat_penyakit";

require_once("../lib/dbconn.php");

$_GET["rg"] = $_GET[rg];
$rg = isset($_GET["rg"])
        ? $_GET["rg"]
        : $_POST["rg"];
$mr = isset($_GET["mr"])
        ? $_GET["mr"]
        : $_POST["mr"];
$ri = isset($_GET["ri"])
        ? $_GET["ri"]
        : $_POST["ri"];
//echo "rg=".$_GET["rg"];exit;
if ($_SESSION[uid] == "kasir1") {
    $kasir = "RJL";
    $lyn = getFromTable("select layanan from rs00005 where reg='" . $_GET[rg] . "' and layanan not in (99997,99998,99999)");

//echo "lyn: $lyn"; exit();
} elseif ($_SESSION[uid] == "kasir2") {
    $kasir = "RIN";
    $lyn = 0;
} else {
    $status = getFromTable("select rawat_inap from rs00006 where id = '" . $_GET[rg] . "'");
    if ($status == "Y") {
        $kasir = "RJL";
    } elseif ($status == "N") {
        $kasir = "IGD";
    } else {
        $kasir = "RIN";
    }

    $lyn = 99997;
}

if ($_GET[tbl] == "bayar") {
    $SQL = "delete from rs00005 where " .
            "id = " . $_GET["del"];
} elseif ($_GET[tbl] == "tindakan") {
    $SQL = "delete from rs00008 where " .
            "id = " . $_GET["del"];


    $lab_or_rad = getFromTable("select trans_form from rs00008 where id = " . $_GET[del]);

    if ($lab_or_rad == "LAB") {
        $lyn = 99998;
    } elseif ($lab_or_rad == "RAD") {
        $lyn = 99999;
    }

//    echo "labrad: $lab_or_rad - $lyn"; exit();
    $jml = getFromTable("select (tagihan) as jumlah from rs00008 where id=" . $_GET[del]);
    $jmlx = 0 - $jml;


//echo "$lyn $jmlx "; exit();    
    pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, " .
            "is_karcis, layanan, jumlah, is_bayar,user_id) " .
            "values (nextval('kasir_seq'), '" . $_GET[rg] . "', CURRENT_DATE, '$kasir', 'N', 'N', $lyn, $jmlx, 'N','" . $_SESSION["uid"] . "') ");
} elseif ($_GET[tbl] == "obat1" OR $_GET[tbl] == 'bhp') {
    $add = "&sub2=".$_GET['sub2']."&list=layanan";
    $SQL = "delete from rs00008 where " .
            "id = " . $_GET["del"];

    $jml = getFromTable("select (qty * harga) as jumlah from rs00008 where id=" . $_GET[del]);
    $jmlx = 0 - $jml;

    pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, " .
            "is_karcis, layanan, jumlah, is_bayar) " .
            "values (nextval('kasir_seq'), '" . $_GET[rg] . "', CURRENT_DATE, '$kasir', 'Y', 'N', 99997, $jmlx, 'N') ");

    $cek_karcis = getFromTable("select jumlah from rs00005 where reg = '" . $_GET[rg] . "' and is_karcis = 'Y'");
    $totalObat = getFromTable("select sum(jumlah) from rs00005 where reg = '" . $_GET[rg] . "' and is_obat = 'Y' and layanan != 99995");
    if ($cek_karcis == 4500) {
        if ($totalObat <= 2000) {
            pg_query("delete from rs00005 where reg = '" . $_GET[rg] . "' and layanan = 99995");
        }
    } elseif ($cek_karcis == 9000) {
        if ($totalObat <= 4000) {
            pg_query("delete from rs00005 where reg = '" . $_GET[rg] . "' and layanan = 99995");
        }
    }
} elseif ($_GET[tbl] == "konsultasi") {
	$add ="&list=konsultasi";
    $vis1 = getFromTable("select vis_1 from c_visit where oid=" . $_GET["oid"] . "");
    $vis15 = getFromTable("select vis_15 from c_visit where oid=" . $_GET["oid"] . "");
    if ($vis1 == "" and $vis15 == "") {
        $SQL = "delete from c_visit where " .
                "oid = " . $_GET["oid"];
    } else {
        $SQL = "update c_visit set id_konsul='' where " .
                "oid = " . $_GET["oid"];
    }
}
  elseif ($_GET[tbl] == "del_paket") {
    //hapus layanan paket
    $add = "&sub2=".$_GET['sub2']."&list=layanan";
    $jmlx = getFromTable("SELECT tagihan FROM rs00008 WHERE id=".$_GET['del']);
    $SQL_Insert = "INSERT INTO rs00005(reg, tgl_entry, kasir, is_obat, is_karcis, layanan, jumlah, is_bayar, user_id) 
								VALUES('".$_GET['rg']."', CURRENT_DATE, 'RIN', 'N', 'N', 'P', -".$jmlx.", 'N','".$_SESSION['uid']."')";
    $SQL = "DELETE FROM rs00008 WHERE id=".$_GET['del'];
}

pg_query($con,
        $SQL);

header("Location: ../index2.php?p=$PID&rg1=" . $_GET["rg"] . "&rg=" . $_GET["rg"] . "&ri=" . $_GET["ri"] . "&mr=" . $_GET["mr"] . "$add");
exit;
?>
