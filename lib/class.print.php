<?
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/visit_setting.php");
//hso
/*
function title_print($title)
{
    if ($GLOBALS['print']) {
    	echo "<br><br><DIV ALIGN=CENTER CLASS=FORM_TITLE><B>$title</B></DIV>\n";

    } else {
        echo "<DIV ALIGN=LEFT CLASS=FORM_TITLE><B>$title</B></DIV>\n";
        echo "<td width=1 align=right><a href=\"javascript:printPage()\"><img border=0 src=\"images/printer.gif\"></a></td>";
        echo "</tr></table>\n";
        echo "<script language=\"JavaScript\">\n";
        echo "function printPage() {\n";
        echo "  oWin = window.open('print.php?{$_SERVER['QUERY_STRING']}', 'zWin', 'width=800,height=600,scrollbars=yes');\n";
        echo "  oWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
    }
}
*/
function printer_start_page($SQL)
{
		
	
		$r = pg_query($con,$SQL);
		$n = pg_num_rows($r);
	    if($n > 0) $d = pg_fetch_array($r);
	    pg_free_result($r);
}


?>