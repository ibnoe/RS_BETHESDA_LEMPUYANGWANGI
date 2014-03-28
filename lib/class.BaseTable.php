<?php

class BaseTable
{

    var $pTblWidth;
    
    function BaseTable($width = "")
    {
        $this->pTblWidth   = $width;
    }

    function xstrftime ($format = "", $timestamp = false)
    {
        // solusi sementara
        return date("d M Y", $timestamp);
    }
    
    function setQueryString($newVal)
    {
        if (strlen($_SERVER["QUERY_STRING"]) > 0) {
            $orgQstr = explode("&",$_SERVER["QUERY_STRING"]);
            foreach ($orgQstr as $q) {
                $x = explode("=", $q);
                $newQstr[$x[0]] = $x[1];
            }
        }
        foreach ($newVal as $k => $v) {
            $newQstr[$k] = $v;
        }       
        $first = true;
        foreach ($newQstr as $k => $v) {
            if ($first) {
                $first = false;
            } else {
                $Qstr .= "&";
            }
            $Qstr .= $k."=".$v;
        }
        return $_SERVER["SCRIPT_NAME"]."?".$Qstr;
    }

    function getmicrotime()
    {
        list($usec, $sec) = explode(" ",microtime()); 
        return ((float)$usec + (float)$sec); 
    }
    
    function printTableOpen()
    {
        echo "<TABLE CLASS=TBL_BORDER WIDTH='$this->pTblWidth' BORDER=0 CELLSPACING=0 CELLPADDING=1><TR><TD>\n";
        echo "<TABLE WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>\n";
    }
    
    function printTableClose()
    {
        echo "</TABLE>\n";      
        echo "</TD></TR></TABLE>\n";
    }
    
    function printTableHeader($headers)
    {
        echo "<TR>\n";
        for ($n = 0; $n < count($headers); $n++) {
            echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>$headers[$n]</TD>\n";
        }
        echo "</TR>\n";
    }

    function printTableFooter($footer)
    {
        echo "<TR>\n";
        for ($n = 0; $n < count($footer); $n++) {
            echo "    <TD CLASS=TBL_FOOT ALIGN=CENTER>$footer[$n]</TD>\n";
        }
        echo "</TR>\n";
    }

     /**
    function printRow($row, $align)
    {
        echo "<TR ".$style.">\n";
        for ($n = 0; $n < count($row); $n++) {
            echo "    <TD CLASS='TBL_BODY' ALIGN='$align[$n]'>$row[$n]</TD>\n";
        }
        echo "</TR>\n";
    }
   */ 
	
	 function printRow3($row, $align)
    {
        echo "<TR>\n";
        for ($n = 0; $n < count($row); $n++) {
            echo "    <TD CLASS=TBL_BODY ALIGN=$align[$n]>$row[$n]</TD>\n";
        }
        echo "</TR>\n";
    }
	
	function printRow($row, $align)
    {
       	$wew = $row[0];
		$wew2 = strlen($wew) - 1;
		$wew3 = substr($wew,0,$wew2);
		$test = $wew3%2;               
       if($wew3%2 == 0){
	    $zzz= "#EDEDED";
	   }
	   else
	   {
	   $zzz= "#D0D0D0";
	   }
          echo "<TR bgcolor='".$zzz."' id='tr".$wew3."' onmouseover=tr".$wew3.".style.background='#99ff99' onmouseout =tr".$wew3.".style.background='".$zzz."'>\n";     
		for ($n = 0; $n < count($row); $n++)
        {        
			echo "<TD CLASS=TBL_BODY1 ALIGN=$align[$n]>$row[$n]</TD>\n";        
        }
        echo "</TR>\n";
    }
      

    function printRow2($row, $align)
    {
        echo "<TR>\n";
        for ($n = 0; $n < count($row); $n++) {
            echo "    <TD CLASS=TBL_BODY ALIGN=$align[$n]>$row[$n]</TD>\n";
        }
        echo "</TR>\n";
    }
    function printColSpan($span, $row, $align)
    {
        echo "<TR>\n";
        echo "    <TD CLASS=TBL_BODY COLSPAN=$span ALIGN=$align>$row</TD>\n";
        echo "</TR>\n";
    }
    
    function errmsg($msg)
    {
        echo "<FONT COLOR=BLACK SIZE=3 FACE='Times New Roman'>" .
             "<BIG><BIG><BIG><FONT COLOR=RED><B>Ooops!</B></FONT></BIG></BIG></BIG>".
             "<BR><B>MyTable Class Error:</B><br>$msg</FONT>\n";
    }
  
    function mysql2mktime($mysqlStr)
    {
        $s1 = explode(" ",$mysqlStr);
        $s2 = explode("-",$s1[0]);
        $s3 = explode(":",$s1[1]);
        
        return mktime($s3[0],$s3[1],$s3[2],$s2[1],$s2[2],$s2[0],-1);
        
    }
    
    function canMovePrev($rowCount)
    {
        return $_GET["tblstart"] > 0;
    }
    
    function canMoveNext($rowCount)
    {
        return $_GET["tblstart"] < $rowCount - $this->RowsPerPage;
    }
    
    function hrefMovePrev($rowCount)
    {
        $rec = $_GET["tblstart"] - $this->RowsPerPage;
        if ($rec < 0) $rec = 0;
        return BaseTable::setQueryString(Array("tblstart" => $rec));
    }
    
    function hrefMoveNext($rowCount)
    {
        $rec = $_GET["tblstart"] + $this->RowsPerPage;
        if ($rec > $rowCount - $this->RowsPerPage) $rec = $rowCount - $this->RowsPerPage;
        return BaseTable::setQueryString(Array("tblstart" => $rec));
    }

    function hrefMoveFirst($rowCount)
    {
        return BaseTable::setQueryString(Array("tblstart" => 0));
    }
    
    function hrefMoveLast($rowCount)
    {
        $rec = $rowCount - $this->RowsPerPage;
        if ($rec < 0) $rec = 0;
        return BaseTable::setQueryString(Array("tblstart" => $rec));
    }

    function ScrollBar($rows)
    {
        $grid = 40;
        if ($_GET["tblstart"]) {
            $tblStart = $_GET["tblstart"];
        } else {
            $tblStart = 0;
        }
        $minRows = $rows - $this->RowsPerPage;
        if ($minRows < 0) $minRows = 0;
        $ret .= "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=1 CLASS=SCR_BORDER><TR><TD>";
        $ret .= "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0><TR>";
        for ($i = 0; $i <= $grid; $i++) {
            $n = round(($minRows/$grid) * ($i + 0));
            $m = round(($minRows/$grid) * ($i + 1));
            if ($tblStart >= $n && $tblStart <= $m) {
                $ret .= "<TD CLASS=SCR_HEAD>";
            } else {
                $ret .= "<TD CLASS=SCR_BODY>";
            }
            $ret .= "<A STYLE='text-decoration:none' HREF='".
                    $this->setQueryString(Array("tblstart" => $n))."'>&nbsp;&nbsp;</A>";
            $ret .= "</TD>";
        }
        $ret .= "</TR></TABLE>";
        $ret .= "</TD></TR></TABLE>";
        return $ret;
    }
    
    function ClassInfo()
    {
        return Array(
                "version"            => "0.1",
                "last_updated"       => "Mon Mar  8 19:22:41 WIT 2004",
                "original_filename"  => "class.BaseTable.php",
                "author"             => "MA Nugraha <nugraha@arpaa.com>"
            );
    }

}

?>