<?php // Nugraha, Thu Apr 29 17:10:51 WIT 2004

$PID = "740";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

function getLevel($hcode)
{
    if (strlen($hcode) != 15) return 0;
    if (substr($hcode,  4, 12) == str_repeat("0", 12)) return 1;
    if (substr($hcode,  7,  9) == str_repeat("0",  9)) return 2;
    if (substr($hcode, 10,  6) == str_repeat("0",  6)) return 3;
    if (substr($hcode, 13,  3) == str_repeat("0",  3)) return 4;
    return 5;
}

//title("Info Layanan");
title("<img src='icon/keuangan-2.gif' align='absmiddle' >  Info Layanan");
echo "<br>";

if ($_GET["action"] == "new") {
    $f = new Form("actions/834.insert.php", "POST");
    $f->PgConn = $con;
    $f->hidden("parent", $_GET["parent"]);
    $f->hidden("f_is_group", $_GET["grp"]);
    if ($_GET["grp"] == "Y") {
        $f->text("f_layanan","Nama Group Layanan",50,255,$_GET["layanan"]);
    } else {
        $f->text("f_layanan","Nama Layanan",50,255,$_GET["layanan"]);
        $f->selectSQL("f_satuan_id", "Satuan",
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'SAT' ".
            "order by tdesc", "");
        $f->selectSQL("f_klasifikasi_tarif_id", "Klasifikasi Tarif",
            "select '' as tt, '' as tdesc union ".
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'KTR' ".
            "order by tdesc", "");
        $f->selectSQL("f_sumber_pendapatan_id", "Sumber Pendapatan",
            "select '' as tt, '' as tdesc union ".
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'SBP' ".
            "order by tdesc", "");
        $f->selectSQL("f_golongan_tindakan_id", "Golongan Tindakan",
            "select '' as tt, '' as tdesc union ".
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'GTD' ".
            "order by tdesc", "");
        $f->selectSQL("f_tipe_pasien_id", "Tipe Pasien",
            "select '' as tt, '' as tdesc union ".
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'JEP' ".
            "order by tdesc", "");
        $f->text("f_harga","Harga",12,12,"0.00","style='text-align:right'");
        $f->text("f_harga_atas", "Harga Atas" ,12,12,"0.00","style='text-align:right'");
        $f->text("f_harga_bawah","Harga Bawah",12,12,"0.00","style='text-align:right'");
    }
    $f->submit(" Simpan ");
    $f->execute();
} elseif ($_GET["action"] == "edit") {
    $r = pg_query($con, "select * from rs00034 where id = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);

    $f = new Form("actions/834.update.php", "POST");
    $f->PgConn = $con;
    $f->hidden("id", $_GET["e"]);
    $f->hidden("parent", $_GET["parent"]);
    if ($_GET["grp"] == "Y") {
        $f->text("f_layanan","Nama Group Layanan",50,255,$d->layanan);
    } else {
        $f->text("f_layanan","Nama Layanan",50,255,$d->layanan);
        $f->selectSQL("f_satuan_id", "Satuan",
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'SAT' ".
            "order by tdesc", $d->satuan_id);
        $f->selectSQL("f_klasifikasi_tarif_id", "Klasifikasi Tarif",
            "select '' as tt, '' as tdesc union ".
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'KTR' ".
            "order by tdesc", $d->klasifikasi_tarif_id);
        $f->selectSQL("f_sumber_pendapatan_id", "Sumber Pendapatan",
            "select '' as tt, '' as tdesc union ".
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'SBP' ".
            "order by tdesc", $d->sumber_pendapatan_id);
        $f->selectSQL("f_golongan_tindakan_id", "Golongan Tindakan",
            "select '' as tt, '' as tdesc union ".
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'GTD' ".
            "order by tdesc", $d->golongan_tindakan_id);
        $f->selectSQL("f_tipe_pasien_id", "Tipe Pasien",
            "select '' as tt, '' as tdesc union ".
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'JEP' ".
            "order by tdesc", $d->tipe_pasien_id);
        $f->text("f_harga","Harga",12,12,$d->harga,"style='text-align:right'");
        $f->text("f_harga_atas", "Harga Atas" ,12,12,$d->harga_atas,"style='text-align:right'");
        $f->text("f_harga_bawah","Harga Bawah",12,12,$d->harga_bawah,"style='text-align:right'");
    }
    $f->submit(" Simpan ");
    $f->execute();
} else {

    $ext = "OnChange = 'Form1.submit();'";
    $level = 0;
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->selectSQL("L1", "Grup Layanan",
        "select '' as hierarchy, '' as layanan union " .
        "select hierarchy, layanan ".
        "from rs00034 ".
        "where substr(hierarchy,4,12) = '000000000000' ".
        "and is_group = 'Y' and (status is null or status='1') ".
        "order by layanan", $_GET["L1"],
        $ext);
    if (strlen($_GET["L1"]) > 0) $level = 1;
    if (getFromTable(
            "select hierarchy, layanan ".
            "from rs00034 ".
            "where substr(hierarchy,7,9) = '000000000' ".
            "and substr(hierarchy,1,3) = '".substr($_GET["L1"],0,3)."' ".
            "and hierarchy != '".$_GET["L1"]."' ".
            "and is_group = 'Y' and (status is null or status='1')")
        && strlen($_GET["L1"]) > 0) {
        $f->selectSQL("L2", "Sub Grup Layanan",
            "select '' as hierarchy, '' as layanan union " .
            "select hierarchy, layanan ".
            "from rs00034 ".
            "where substr(hierarchy,7,9) = '000000000' ".
            "and substr(hierarchy,1,3) = '".substr($_GET["L1"],0,3)."' ".
            "and hierarchy != '".$_GET["L1"]."' ".
            "and is_group = 'Y' and (status is null or status='1') ".
            "order by layanan", $_GET["L2"],
            $ext);
        if (strlen($_GET["L2"]) > 0) $level = 2;
        if (getFromTable(
                "select hierarchy, layanan ".
                "from rs00034 ".
                "where substr(hierarchy,10,6) = '000000' ".
                "and substr(hierarchy,1,6) = '".substr($_GET["L2"],0,6)."' ".
                "and hierarchy != '".$_GET["L2"]."' ".
                "and is_group = 'Y' and (status is null or status='1')")
            && strlen($_GET["L1"]) > 0
            && strlen($_GET["L2"]) > 0) {
            $f->selectSQL("L3", "",
                "select '' as hierarchy, '' as layanan union " .
                "select hierarchy, layanan ".
                "from rs00034 ".
                "where substr(hierarchy,10,6) = '000000' ".
                "and substr(hierarchy,1,6) = '".substr($_GET["L2"],0,6)."' ".
                "and hierarchy != '".$_GET["L2"]."' ".
                "and is_group = 'Y' and (status is null or status='1') ".
                "order by layanan", $_GET["L3"],
                $ext);
            if (strlen($_GET["L3"]) > 0) $level = 3;
            if (getFromTable(
                    "select hierarchy, layanan ".
                    "from rs00034 ".
                    "where substr(hierarchy,13,3) = '000' ".
                    "and substr(hierarchy,1,9) = '".substr($_GET["L3"],0,9)."' ".
                    "and hierarchy != '".$_GET["L3"]."' ".
                    "and is_group = 'Y' and (status is null or status='1')")
                && strlen($_GET["L1"]) > 0
                && strlen($_GET["L2"]) > 0
                && strlen($_GET["L3"]) > 0) {
                $f->selectSQL("L4", "",
                    "select '' as hierarchy, '' as layanan union " .
                    "select hierarchy, layanan ".
                    "from rs00034 ".
                    "where substr(hierarchy,13,3) = '000' ".
                    "and substr(hierarchy,1,9) = '".substr($_GET["L3"],0,9)."' ".
                    "and hierarchy != '".$_GET["L3"]."' ".
                    "and is_group = 'Y' and (status is null or status='1') ".
                    "order by layanan", $_GET["L4"],
                    $ext);
                    if (strlen($_GET["L4"]) > 0) $level = 4;
            }
        }
    }
    $f->execute();

    $SQL1 = "select a.layanan, e.tdesc as pendapatan, d.tdesc as golongan, f.tdesc as pasien, ".
            "c.tdesc as klasifikasi_tarif, b.tdesc as satuan, ".
            //"a.harga_atas, a.harga_bawah, ".
            "a.harga, ".
            //a.jasa_dokter, a.jasa_asisten, a.jasa_rs, a.alat, a.bahan,  ".
            "g.jasa_medis, case when a.status='0' then 'Tidak Aktif' else 'Aktif' end as status ".
            "from rs00034 as a ".
            "left join rs00001 as b on a.satuan_id = b.tc and b.tt = 'SAT' ".
            "left join rs00001 as c on a.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
            "left join rs00001 as d on a.golongan_tindakan_id = d.tc and d.tt = 'GTD' ".
            "left join rs00001 as e on a.sumber_pendapatan_id = e.tc and e.tt = 'SBP' ".
            "left join rs00001 as f on a.tipe_pasien_id = f.tc and f.tt = 'JEP' ".
            "left join rs00021 as g on a.rs00021_id = g.id ".
            "where substr(a.hierarchy,1,".($level*3).") = '".substr($_GET["L$level"],0,($level*3))."' ".
            "and a.hierarchy <> '".$_GET["L$level"]."' ".
            "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
            str_repeat("0",15-(($level*3)+3))."'";
    $SQL2 = "select a.layanan ".
            "from rs00034 as a ".
            "left join rs00001 as b on a.satuan_id = b.tc and b.tt = 'SAT' ".
            "left join rs00001 as c on a.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
            "left join rs00001 as d on a.golongan_tindakan_id = c.tc and c.tt = 'GTD' ".
            "left join rs00001 as e on a.sumber_pendapatan_id = e.tc and e.tt = 'SBP' ".
            "left join rs00001 as f on a.tipe_pasien_id = f.tc and f.tt = 'JEP' ".
            "left join rs00021 as g on a.rs00021_id = g.id ".
            "where substr(a.hierarchy,1,".($level*3).") = '".substr($_GET["L$level"],0,($level*3))."' ".
            "and a.hierarchy <> '".$_GET["L$level"]."' ".
            "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
            str_repeat("0",15-(($level*3)+3))."'";
    $SQL3 = "select is_group ".
            "from rs00034 ".
            "where substr(hierarchy,1,".($level*3).") = '".substr($_GET["L$level"],0,($level*3))."' ".
            "and hierarchy <> '".$_GET["L$level"]."' ".
            "and substr(hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
            str_repeat("0",15-(($level*3)+3))."'";

    $isGroup = getFromTable($SQL3);
    
    $SQL1 .= " and (a.status is null or a.status='1')";
	$SQL2 .= " and (a.status is null or a.status='1')";

    echo "<br>";
    echo "<div align=RIGHT>";
    /*
    if ($isGroup != "Y")
        echo "<A HREF='$SC?p=$PID&action=new&parent=".$_GET["L$level"]."&grp=N'>Tambah Layanan</A><br>";
    if ($isGroup != "N")
        echo "<A HREF='$SC?p=$PID&action=new&parent=".$_GET["L$level"]."&grp=Y'>Tambah Group Layanan</A><br>";
    */

    echo "</DIV>";
    if ($isGroup == "Y") {
        $t = new PgTable($con, "100%");
        $t->SQL = $SQL2;
        $t->setlocale("id_ID");
        $t->ShowRowNumber = true;
        $t->RowsPerPage = 10;
        /*
        $t->ColFormatHtml[1] =
            "<A CLASS=TBL_HREF HREF='".
            "$SC?p=$PID&action=edit&parent=".$_GET["L$level"]."&grp=Y&e=<#1#>".
            "'>".icon("edit","Edit")."</A></nobr>";
        */

        $t->ColHeader = Array("GRUP LAYANAN", "&nbsp;");
        $t->ColAlign[1] = "CENTER";
        $t->execute();
    }
    if ($isGroup == "N") {
        $t = new PgTable($con, "100%");
        $t->SQL = $SQL1;
        $t->setlocale("id_ID");
        $t->ShowRowNumber = true;
        $t->RowsPerPage = 50;
        //$t->ColFormatMoney[6] = "%!+#2n";
        //$t->ColFormatMoney[7] = "%!+#2n";
        //$t->ColFormatMoney[8] = "%!+#2n";
        /*
        $t->ColFormatHtml[9] =
            "<A CLASS=TBL_HREF HREF='".
            "$SC?p=$PID&action=edit&parent=".$_GET["L$level"]."&grp=N&e=<#9#>".
            "'>".icon("edit","Edit")."</A></nobr>";
        */
        $t->ColHeader = Array("LAYANAN","PENDAPATAN", "GOL. TINDAKAN","TIPE PASIEN","KLASIFIKASI TARIF", "SATUAN",
                            //"HARGA ATAS", "HARGA BAWAH",      
                            "HARGA", //"JASA DOKTER", "JASA ASISTEN", "JASA RS", "ALAT", "BAHAN",
                             "SUMBER DANA",
							"STATUS");
        $t->execute();
    }

}

?>
