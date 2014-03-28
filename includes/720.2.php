<?php // Nugraha, Sat May  1 10:22:31 WIT 2004

echo "<hr noshade size=1>";
title("Tindakan Medis");
echo "<br>";

$r = pg_query($con,
    "select distinct trans_group ".
    "from rs00008 ".
    "where no_reg = '$reg' ".
    "and trans_type in ('LTM', 'DIA', 'OB1', 'ICD')");
while ($d = pg_fetch_object($r)) {
    echo "<hr noshade color=#dddddd size=1>";
    echo "<table border=0 cellspacing=0 width='100%'><tr>";
    echo "<td class=TBL_BODY3 valign=TOP width='20%'><b>";
    echo "#$d->trans_group<BR>".
         date("d/m/Y", pgsql2mktime( getFromTable(
             "select tanggal_trans from rs00008 where trans_group = $d->trans_group")));
    echo "</b></td><td class=TBL_BODY3 valign=TOP width='40%'>";

    // keterangan diagnosa
    $x = getFromTable(
         "select description ".
         "from rs00009, rs00008 ".
         "where rs00008.id = rs00009.trans_id ".
         "and rs00008.trans_type = 'DIA' ".
         "and rs00008.trans_group = $d->trans_group"
         );
    if (strlen($x) > 0) {
        echo "<b>Keterangan Diagnosa:</b><br>$x<br><br>";
    }

    // ICD
    $r1 = pg_query($con,
        "select diagnosis_code, description ".
        "from rs00019, rs00008 ".
        "where rs00008.item_id = rs00019.diagnosis_code ".
        "and rs00008.trans_type = 'ICD' ".
        "and rs00008.trans_group = $d->trans_group"
        );
    if (pg_num_rows($r1) > 0) {
        echo "<b>ICD:</b><br>";
        echo "<table border=0 cellspacing=0 cellpadding=0>";
        while ($d1 = pg_fetch_object($r1)) {
            echo "<tr>";
            echo "<td class=TBL_BODY3>$d1->diagnosis_code</TD>";
            echo "<td class=TBL_BODY3>$d1->description</TD>";
            echo "</tr>";
        }
        echo "</table><br>";
    }
    pg_free_result($r1);

    // LTM
    $r1 = pg_query($con,
        "select layanan ".
        "from rs00034, rs00008 ".
        "where to_number(rs00008.item_id,'999999999999') = rs00034.id ".
        "and rs00008.trans_type = 'LTM' ".
        "and rs00008.trans_group = $d->trans_group"
        );

    if (pg_num_rows($r1) > 0) {
    echo "<b>Layanan Tindakan Medis:</b><ul>";
    }


    while ($d1 = pg_fetch_object($r1)) {
        echo "<li>";
        echo "$d1->layanan";
        echo "</li>";
    }
    pg_free_result($r1);
    echo "</ul>";

    echo "</td><td class=TBL_BODY3 valign=TOP width='40%'>";

    // resep
    $r1 = pg_query($con,
        "select obat, qty, tdesc as satuan, description as dosis ".
        "from rs00015, rs00001, rs00008 ".
        "left join rs00009 on rs00008.id = rs00009.trans_id ".
        "where to_number(rs00008.item_id,'999999999999') = rs00015.id ".
        "and rs00008.trans_type = 'OB1' ".
        "and rs00008.trans_group = $d->trans_group ".
        "and rs00015.satuan_id = rs00001.tc ".
        "and rs00001.tt = 'SAT' "
        );
    if (pg_num_rows($r1) > 0) {
        echo "<B>Resep:</B><br>";
        echo "<ul>";
        while ($d1 = pg_fetch_object($r1)) {
            echo "<li>";
            echo "$d1->obat";
            echo $d1->qty == 0 ? "" : ", $d1->qty $d1->satuan";
            echo ", $d1->dosis";
            echo "</li>";
        }
        echo "</ul>";
    }
    pg_free_result($r1);

    echo "</td>";
    echo "</tr></table>";
}
pg_free_result($r);

?>
