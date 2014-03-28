<?php

// Nugraha, Sat May  8 16:54:44 WIT 2004
//if ($_SESSION[uid] == "igd" || $_SESSION[uid] == "kasir2" || $_SESSION[uid] == "root") {


$PID = "370";
$SC = $_SERVER["SCRIPT_NAME"];
/*
  require_once("lib/dbconn.php");
  require_once("lib/form.php");
  require_once("lib/class.PgTable.php");
  require_once("lib/functions.php");
 */
//-------hery 10-07-2007------tabbar----------------
require_once("startup.php");


if (!$GLOBALS['print']) {
    title("<img src='icon/rawat-inap-2.gif' align='absmiddle' > LAYANAN RAWAT INAP");

    title_print("");
    title_excel("370&tblstart=" . $_GET['tblstart']);
}

//title("<img src='icon/rawat-inap-2.gif' align='absmiddle' > LAYANAN RAWAT INAP");
//title("<img src='icon/ico_ugd.gif' align='absmiddle' > <font color='gray'>LAYANAN RAWAT INAP</font>");
echo "<br>";

$tab_disabled = array("pasien" => true, "daftar" => true, "ruangan" => true);
if ($_GET["act"] == "view") {
    $tab_disabled = array("pasien" => false, "daftar" => false, "ruangan" => false);
    $tab_disabled[$_GET["sub"]] = true;
    $tab_disabled[$_POST["sub"]] = true;
}
$T = new TabBar();
if ($_SESSION[gr] == "RI" || $_SESSION[gr] == "RI-ANAK" || $_SESSION[gr] == "RI-MATA" || $_SESSION[gr] == "RI-INTERNE" || $_SESSION[gr] == "RI-BEDAH" || $_SESSION[gr] == "RI-KEBIDAN" || $_SESSION[gr] == "RI-VIPA" || $_SESSION[gr] == "RI-VIPB" || $_SESSION[gr] == "ICU" || $_SESSION[gr] == "RI-KELAS3" || $_SESSION[gr] == "RI-ANGGREK") {
    $T->addTab("$SC?p=$PID&list=pasien&sub=1", " Pasien ", $tab_disabled["pasien"]);
    $T->addTab("$SC?p=$PID&list=daftar&sub=2", " Pendaftaran ", $tab_disabled["daftar"]);
    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
} elseif ($_SESSION[gr] == "daftar") {
    $T->addTab("$SC?p=$PID&list=pasien&sub=1", " Pasien ", $tab_disabled["pasien"]);
    $T->addTab("$SC?p=$PID&list=daftar&sub=2", " Pendaftaran ", $tab_disabled["daftar"]);
    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
} elseif ($_SESSION[uid] == "root") {
    $T->addTab("$SC?p=$PID&list=pasien&sub=1", " Pasien ", $tab_disabled["pasien"]);
    $T->addTab("$SC?p=$PID&list=daftar&sub=2", " Pendaftaran ", $tab_disabled["daftar"]);
    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
} elseif ($_SESSION[uid] == "rumahsakit") {
    $T->addTab("$SC?p=$PID&list=pasien&sub=1", " Pasien ", $tab_disabled["pasien"]);
    $T->addTab("$SC?p=$PID&list=daftar&sub=2", " Pendaftaran ", $tab_disabled["daftar"]);
    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
} elseif ($_SESSION[uid] == "igd") {
    //$T->addTab("$SC?p=$PID&list=pasien&sub=1", " Pasien "	, $tab_disabled["pasien"]);
    $T->addTab("$SC?p=$PID&list=daftar&sub=2", " Pendaftaran ", $tab_disabled["daftar"]);
    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "2";
} else {
    $T->addTab("$SC?p=$PID&list=pasien&sub=1", " Pasien ", $tab_disabled["pasien"]);
    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
    $T->addTab("$SC?p=$PID&list=daftar&sub=2", " Pendaftaran ", $tab_disabled["daftar"]);
    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
}
// $T->addTab("$SC?p=$PID&list=ruangan&sub=3", " Ruangan "	, $tab_disabled["ruangan"]);
/*
  echo "<form name=Form3>";
  echo "<input name=b1 type=button value='Pasien' onClick='window.location=\"$SC?p=$PID&sub=1\";'".
  ($_GET["sub"] == "1" || $_GET["sub"] == "" ? " DISABLED" : "").">&nbsp;";
  echo "<input name=b2 type=button value='Pendaftaran' onClick='window.location=\"$SC?p=$PID&sub=2\";'".
  ($_GET["sub"] == "2" ? " DISABLED" : "").">&nbsp;";
  //echo "<input name=b3 type=button value='Ruangan' onClick='window.location=\"$SC?p=$PID&sub=3\";'".
  //     ($_GET["sub"] == "3" ? " DISABLED" : "").">&nbsp;";
  echo "</form>";
 */

if (file_exists("includes/$PID.$sub.php"))
    include_once("includes/$PID.$sub.php");


//} // end of $_SESSION[uid] == igd || root
?>
