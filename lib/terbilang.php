<!--<html>
<head>
  <title>Fungsi terbilang</title>
</head>
<body>
<form action="terbilang.php" method="post">
        Masukkan angka: <input type="text" value="" name="angka">
    <input type="submit" value="Send">
</form>
<pre>
<?php
$angka = isset($_POST['angka']) ? $_POST['angka'] : "0";
if ($angka)
{
        echo number_format($angka, 0) . "<br>";
        echo ucwords(Terbilang($angka));
}
?>
</pre>
</body>
</html>
-->
<?php

function Terbilang($x)
{
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  if ($x < 12)
    return " " . $abil[$x];
  elseif ($x < 20)
    return Terbilang($x - 10) . "belas";
  elseif ($x < 100)
    return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
  elseif ($x < 200)
    return " seratus" . Terbilang($x - 100);
  elseif ($x < 1000)
    return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
  elseif ($x < 2000)
    return " seribu" . Terbilang($x - 1000);
  elseif ($x < 1000000)
    return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
  elseif ($x < 1000000000)
    return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}

?>
