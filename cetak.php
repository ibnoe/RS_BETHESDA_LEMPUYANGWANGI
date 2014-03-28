<html>

<head>
<link rel=stylesheet href=cetak.css type=text/css>
</head>


<table cellpadding=2 cellspacing=1 border=1>
<tr bgcolor=cccccc>
<th>No</th>
<th>LAYANAN</th>
<th>TARIF</th>
<th>GOLONGAN</th>
<th>HARGA</th>
</tr>
<?

require_once("lib/dbconn.php");
$no = 0;
$q = pg_query("select a.layanan,b.tdesc as kelas,c.tdesc as golongan,a.harga  from rs00034 a ".
     "left join rs00001 b on a.klasifikasi_tarif_id = b.tc and b.tt = 'KTR' ".
     "left join rs00001 c on a.golongan_tindakan_id = c.tc and c.tt = 'GTD' ".
     "where substr(hierarchy,1,9) = '006001008' order by golongan_tindakan_id,layanan");

while ($qr = pg_fetch_object($q)) {

if ($bg == "ffffff") {
   $bg = "eeeeee";
} else {
   $bg = "ffffff";
}


echo "<tr bgcolor=$bg><td>$no</td>";
echo "<td>$qr->layanan</td>";
echo "<td align=center>$qr->kelas</td>";
echo "<td align=center>$qr->golongan</td>";
echo "<td align=right>".(int)$qr->harga."</td></tr>";
$no++;


}

$qn = pg_num_rows($q);
echo "<br>Total: ".$qn;

?>

</table>
