<?php // Nugraha, Sat May  1 09:58:26 WIT 2004
      // sfdn, 01-06-2004

/*************************
          CHECKOUT
*************************/

$PID = "370";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/functions.php");

$T->show(0);
echo "<hr noshade size=1>";
$reg = $_GET["rg"];
$sub = 4;


// data terakhir (recor terakhir) seorang pasian tercatat sbg. penghuni bangsal
$id_max = getFromTable("select max(id) from rs00010 ".
                        "where no_reg = '".$_GET["rg"]."'");


$id_bangsal = getFromTable("select bangsal_id from rs00010 ".
                        "where no_reg = '$reg'");

$skrg = time();
$ts_check_in = date("Y-m-d H:i:s", $skrg);
$tgl = date("d", $skrg);
$bln = date("m", $skrg);
$thn = date("Y", $skrg);
$jam = date("H",$skrg);

if ($jam >= 12) {
    $ts_calc_start = date("Y-m-d", $skrg).
        " 12:00:00";
} else {
    $ts_calc_start = date("Y-m-d", mktime(0,0,0,$bln,$tgl-1,$thn)).
        " 12:00:00";
}

$x = pg_query($con,
        "select a.id, a.ts_check_in::date, e.bangsal, d.bangsal as ruangan, b.bangsal as bed, ".
        "    c.tdesc as klasifikasi_tarif, ".
        "    extract(day from current_timestamp - a.ts_calc_start) as qty, ".
        "    d.harga as harga_satuan, ".
        "    extract(day from current_timestamp - a.ts_calc_start) * d.harga as harga ".
        "from rs00010 as a ".
        "    join rs00012 as b on a.bangsal_id = b.id ".
        "    join rs00012 as d on substr(b.hierarchy,1,6) || '000000000' = d.hierarchy ".
        "    join rs00012 as e on substr(b.hierarchy,1,3) || '000000000000' = e.hierarchy ".
        "    join rs00001 as c on d.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
        "where to_number(a.no_reg,'9999999999') = $reg and ts_calc_stop is null");
$xxx = pg_fetch_object($x);
//febri 21112012
$user=getFromTable("select nama from rs99995 where uid ='".$_SESSION[uid]."'");
$SQL = "update rs00010 set ts_calc_stop=CURRENT_TIMESTAMP, nama='$user' where id = '$id_max'";
//$SQL1 = "insert into rs00010 (id, no_reg, bangsal_id, ts_check_in, ts_calc_start) ".
//       "values (nextval('rs00010_seq'),'$reg','$id_bangsal',".
//       "'$ts_check_in'::timestamp,'$ts_calc_start'::timestamp)";

if (empty($_GET[ket]) && $xxx->qty == 0) {
   $qty = 1;
   $harga = $xxx->harga_satuan * $qty;
} else {
   $qty = $xxx->qty;
   $harga = $xxx->harga_satuan * $xxx->qty;

}

/* $SQL2 = "insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg, ".
                "qty,           harga,       tagihan".
            ") values (".
                "nextval('rs00008_seq'), 'POS', '$PID', nextval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '$reg',  " .
                $qty.",".$xxx->harga_satuan.",".$harga.")";

$SQL3 = "insert into rs00005 ".
        "VALUES(currval('kasir_seq'), '$reg', CURRENT_DATE, 'RIN', 'N', 'N', 99996, $harga, 'N') ";
 */
$SQL4 = "update rs00006 set status = 'P' where id = '$reg'";

pg_query("select nextval('kasir_seq')");
//pg_query($con, $SQL2);
pg_query($con, $SQL);
//pg_query($con, $SQL3);
pg_query($con, $SQL4);

        echo "<table border=0 align='center'><tr><td>";
        echo "<div class = box align='center'>";
		echo "<B>CHECK OUT PASIEN DENGAN NO REGISTRASI&nbsp;&nbsp;{$_GET["rg"]}&nbsp;&nbsp;TELAH DIPROSES <B>";
        echo "<br><br><input type=button value='OK'  onClick='window.location=\"$SC?p=$PID&list=pasien\"'>";
	//echo "</td></td></tr>";
        //echo "<tr><td>";
        //echo "<br><br><input type=button value='CENCEL'  onClick='window.location=\"$SC?p=$PID&list=pasien&rg=".$_GET["rg"]."\">";
        echo "</div>";
        echo "</td></tr></table>";

//if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");

//exit;

if (empty($_GET[ket])) {
?>
<!--
<script language=javascript>

window.location = "index2.php?p=<?= $PID;?>&list=pasien&rg=<?= $reg;?>&sub=4";

</script>
-->
<?
} else {
?>

<!--
<script language=javascript>

window.location = "index2.php?p=<?echo $PID;?>";

</script>
-->

<?
}
?>
