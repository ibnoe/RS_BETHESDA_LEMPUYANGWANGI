<?php

session_start();


require_once("lib/class.PgTable.php");
require_once("lib/class.BaseTable.php");
require_once("lib/class.TabBar.php");
require_once("lib/form.php");
require_once("lib/functions.php");

$con = pg_connect("host={$db_host} port={$db_port} user={$db_user} password={$db_pass} dbname={$db_name}");
//$con = $db_conn;
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : $default_lang;

if (strtoupper(substr(PHP_OS, 0, 3)) === "WIN") {
    $locale = $lang_locale["WINDOWS"][$lang];
} else {
    $locale = $lang_locale["UNIX"][$lang];
}
setlocale(LC_ALL, $locale);

?>