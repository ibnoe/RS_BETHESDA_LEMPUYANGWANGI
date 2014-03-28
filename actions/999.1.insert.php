<?php // Nugraha, Wed Jun  2 16:19:34 WIT 2004

session_start();

$PID = "999";

require_once("../lib/dbconn.php");

if (strlen($_POST["description"]) > 0 ) {
 /*   $SQL = "insert into rs99996 " .
           "(id, trans_type, description) ".
           "values".
           "(nextval('rs99996_seq'),'LYN','".$_POST["description"]."')";
   */
    $SQL = "insert into rs99996 " .
           "(id, trans_type, description,poli) ".
           "values".
           "(nextval('rs99996_seq'),'LYN','".$_POST["description"]."','".$_POST["f_poli"]."')";
                      
    pg_query($con, $SQL);
    $curr = getFromTable("select currval('rs99996_seq')");
}

if (strlen($_POST["layanan"]) > 0 && strlen($_POST["jumlah"]) > 0) {
    $SQL = "insert into rs99997 " .
           "(id, preset_id, item_id, qty,tipe_pasien) ".
           "values".
           "(nextval('rs99997_seq'),'".$_POST["id"]."','".$_POST["layanan"]."','".$_POST["jumlah"]."','".$_POST["tipe_pasien"]."')";
    pg_query($con, $SQL);
    $curr = $_POST["id"];
    unset($_SESSION["SELECT_LAYANAN"]);
}

header("Location: ../index2.php?p=$PID&id=$curr");
exit;

print_r($_POST);

?>
