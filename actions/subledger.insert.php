<?php 
// Wildan ST. 18 Feb 2014

session_start();
if ($_POST["p"]=="subledger]"){
$PID = "subledger";
}else{
    $PID = "subledger_peny";
}
require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$tanggal=getFromTable("select tanggal from jurnal_umum_m where id=".$_POST["id"]."");

$tr = new PgTrans;
$tr->PgConn = $con;

if ($PID=="subledger"){
if (is_array($_SESSION["jurnal"]["akun"])) {
   foreach ($_SESSION["jurnal"]["akun"] as $v) {
       if ($v["ket"]=="Debet"){
        $tr->addSQL(
            "insert into jurnal_umum (" .
                "id, tanggal_akun,  no_akun, keterangan, debet, kredit, user_id, nm_kasir, ket,waktu_entry,jns_akun) values (
                ".$v["id"].", '$tanggal', '".$v["kode"]."', '".$v["keterangan"]."',".$v["harga"].",0,'".$_SESSION[uid]."','".$_SESSION[nama_usr]."','".$v["ket"]."',CURRENT_TIME,'SUB')");
       }elseif ($v["ket"]=="Kredit"){
        $tr->addSQL(
            "insert into jurnal_umum (" .
                "id, tanggal_akun,  no_akun, keterangan, debet, kredit, user_id, nm_kasir, ket,waktu_entry,jns_akun) values (
                ".$v["id"].", '$tanggal', '".$v["kode"]."', '".$v["keterangan"]."',0,".$v["harga"].",'".$_SESSION[uid]."','".$_SESSION[nama_usr]."','".$v["ket"]."',CURRENT_TIME,'SUB')");
       }
        $tr->addSQL("update jurnal_umum_m set tot_debet ='".$_POST["tot_debet"]."', tot_kredit ='".$_POST["tot_kredit"]."'  where id = ".$v["id"]."");
    }
}
}else{
    if (is_array($_SESSION["jurnal"]["akun"])) {
   foreach ($_SESSION["jurnal"]["akun"] as $v) {
       if ($v["ket"]=="Debet"){
        $tr->addSQL(
            "insert into jurnal_umum (" .
                "id, tanggal_akun,  no_akun, keterangan, debet, kredit, user_id, nm_kasir, ket,waktu_entry,jns_akun) values (
                ".$v["id"].", '$tanggal', '".$v["kode"]."', '".$v["keterangan"]."',".$v["harga"].",0,'".$_SESSION[uid]."','".$_SESSION[nama_usr]."','".$v["ket"]."',CURRENT_TIME,'SUB')");
       }elseif ($v["ket"]=="Kredit"){
        $tr->addSQL(
            "insert into jurnal_umum (" .
                "id, tanggal_akun,  no_akun, keterangan, debet, kredit, user_id, nm_kasir, ket,waktu_entry,jns_akun) values (
                ".$v["id"].", '$tanggal', '".$v["kode"]."', '".$v["keterangan"]."',0,".$v["harga"].",'".$_SESSION[uid]."','".$_SESSION[nama_usr]."','".$v["ket"]."',CURRENT_TIME,'SUB')");
       }
        $tr->addSQL("update jurnal_umum_m set tot_debet =tot_debet + ".$_POST["tot_debet"].", tot_kredit =tot_kredit + ".$_POST["tot_kredit"]."  where id = ".$v["id"]."");
    }
}
}
if ($tr->execute()) {

unset($_SESSION["jurnal"]["akun"]);

//if ($PID=="subledger"){
header("Location: ../index2.php?p=$PID");
//}elseif ($PID=="subledger_peny"){
//    header("Location: ../index2.php?p=$PID&edit=view&id=$v[id]");
//}
exit;
}else {
    echo $tr->ErrMsg;
}

?>
