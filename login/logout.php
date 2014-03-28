<?
session_start();
session_destroy();
session_unset();

//$_SESSION[vuid]="";

Header("Location: ../index.php");
?>

