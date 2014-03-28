<?php // Nugraha, Fri Apr 23 14:33:21 WIT 2004

session_start();

require_once("lib/functions.php");

echo "<center>";
echo "<br><br><br><br>";
echo "<table width='60%'><tr><td class=TBL_BODY>";
echo "<div class=BOX align=center>";
if ($_SESSION["dialog"]["title"]) {
    echo "<big><big><b>".$_SESSION["dialog"]["title"]."</b></big></big>";
}
if ($_SESSION["dialog"]["desc"]) {
    echo "<br><br>";
    echo "<big>".$_SESSION["dialog"]["desc"]."</big>";
}
echo "<br><br>";
if (is_array($_SESSION["dialog"]["button"])) {
    echo "<br><br>";
    foreach ($_SESSION["dialog"]["button"] as $b) {
        echo "&nbsp;";
        echo "<input type=button value='".$b["capt"]."' onClick='window.location=\"".$b["href"]."\"'>";
        echo "&nbsp;";
    }
}
echo "</div>";
echo "</td></tr></table>";
echo "</center>";

unset($_SESSION["dialog"]);

?>