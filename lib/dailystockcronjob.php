<?php

// @TODO
// Run The Query
include 'dbconn.php';

date_default_timezone_set('Asia/Jakarta');

function unix_to_human($time = '') {
    $r = date('Y', $time) . '-' . date('m', $time) . '-' . date('d', $time) . ' ';
    return $r;
}

function human_to_unix($datestr = '') {
    if ($datestr == '') {
        return FALSE;
    }

    $datestr = trim($datestr);
    $datestr = preg_replace("/\040+/", ' ', $datestr);

    if (!preg_match('/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(?::[0-9]{1,2})?(?:\s[AP]M)?$/i', $datestr)) {
        return FALSE;
    }

    $split = explode(' ', $datestr);

    $ex = explode("-", $split['0']);

    $year = (strlen($ex['0']) == 2) ? '20' . $ex['0'] : $ex['0'];
    $month = (strlen($ex['1']) == 1) ? '0' . $ex['1'] : $ex['1'];
    $day = (strlen($ex['2']) == 1) ? '0' . $ex['2'] : $ex['2'];

    $ex = explode(":", $split['1']);

    $hour = (strlen($ex['0']) == 1) ? '0' . $ex['0'] : $ex['0'];
    $min = (strlen($ex['1']) == 1) ? '0' . $ex['1'] : $ex['1'];

    if (isset($ex['2']) && preg_match('/[0-9]{1,2}/', $ex['2'])) {
        $sec = (strlen($ex['2']) == 1) ? '0' . $ex['2'] : $ex['2'];
    } else {
        // Unless specified, seconds get set to zero.
        $sec = '00';
    }

    if (isset($split['2'])) {
        $ampm = strtolower($split['2']);

        if (substr($ampm, 0, 1) == 'p' AND $hour < 12)
            $hour = $hour + 12;

        if (substr($ampm, 0, 1) == 'a' AND $hour == 12)
            $hour = '00';

        if (strlen($hour) == 1)
            $hour = '0' . $hour;
    }

    return mktime($hour, $min, $sec, $month, $day, $year);
}

$yesterday = human_to_unix(date('y-m-d h:i:s'));
$time = $yesterday;
$yesterday = $yesterday + 86400;
$date_stock = unix_to_human($yesterday);
$timestamp = unix_to_human($time)."".date('H:i:s');
$sql = "select * 
    FROM rs00016a order by obat_id asc ";
$query = pg_query($sql);

while ($result = pg_fetch_array($query)) {
    $sqlInsert = "INSERT INTO daily_stock ( timestamp,date_stock,obat_id,qty_rj,qty_ri,qty_igd,gudang,";
    for ($i = 1; $i <= 41; $i++) {
        $sqlInsert .= str_pad($i, 7, "qty_00", STR_PAD_LEFT) . ',';
    }
    $sqlInsert .= "harga_average )";
    $sqlInsert .= " VALUES ( ";
    $sqlInsert .= " '{$timestamp}','{$date_stock}',{$result['obat_id']},{$result['qty_rj']},{$result['qty_ri']},{$result['qty_igd']},{$result['gudang']},";
    for ($i = 1; $i <= 41; $i++) {
        $sqlInsert .= $result[str_pad($i, 7, "qty_00", STR_PAD_LEFT)] . ',';
    }
    $sqlInsert .= 0 . " ) ";
    $queryInsert = pg_query($sqlInsert);
}
echo $sqlInsert;
