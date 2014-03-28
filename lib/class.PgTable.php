<?php

// CREATED     : 2004-03-08
// LAST UPDATED: 2005-12-13
// hery , june 28,2007 => modify navButton + colHidden 

require_once("class.BaseTable.php");

class PgTable extends BaseTable
{

    var $DbConn;
    var $SQL;
    var $SQLCounter;

    var $RowsPerPage;
    var $ShowRowNumber =  true;
    var $ShowDownload;
    var $AutoFieldsName;

    var $DefaultSort;
    var $DefaultOrder;
    var $DefaultFormatDate;
    var $DisableSort;
    var $DisableScrollBar;
    var $DisableNavButton;
    var $DisableStatusBar;

    var $ColHeader;
    var $ColFooter;
    var $ColAlign;
    var $ColFormatMoney;
    var $ColFormatDecimal;
    var $ColFormatDate;
    var $ColFormatPeriod;
    var $ColFormatHtml;
    var $ColFormatBold;
    var $ColDisableSort;
    var $ColRowSpan;
    var $ColHidden;
    var $ColTotal;
    var $ColColor; //hso
    
    var $ShowSQL;
    var $ShowSQLExecTime;
    
    var $DownloadEndLine;
    var $DownloadDelimiter;
    var $Signature;
    
    var $msgs;
    var $locale;
    var $unit;
    
    function PgTable($conn, $width = "")
    {
    	$this->DbConn = $conn;
        $this->RowsPerPage = 20;
        $this->BaseTable($width);
        $this->DefaultFormatDate = "%d %b %Y";
        $this->locale = "en_US";
        
        $this->msgs["en_US"][0] = "%d to %d from %d";
        $this->msgs["en_US"][1] = "SQL Exec Time: %.16f seconds";
        
        $this->msgs["id_ID"][0] = "%d sampai %d dari %d";        
        $this->msgs["id_ID"][1] = "Waktu Eksekusi SQL: %.16f detik";
        
        
       // $this->Signature = "RSAU SALAMUN";
    }

    function setlocale($loc)
    {
        $this->locale = $loc;
    }
    
    function addUnit($column, $value, $description, $default = false)
    {
        $tmp['val'] = $value;
        $tmp['des'] = $description;
        $tmp['def'] = $default;
        if (is_array($this->unit[$column])) {
            array_push($this->unit[$column], $tmp);
        } else {
            $this->unit[$column][0] = $tmp;
        }
    }

    function addLimit($SQL)
    {
        if ($_GET["tblstart"]) {
            return trim($SQL) .
                    " LIMIT {$this->RowsPerPage} OFFSET " .
                    $_GET["tblstart"];
        } else {
            return trim($SQL) .
                    " LIMIT {$this->RowsPerPage} OFFSET 0";
        }
    }

    function addSortOrder($SQL)
    {
        if ($_GET["sort"]) {
            $sort = $_GET["sort"];
        } elseif ($this->DefaultSort) {
            $sort = $this->DefaultSort;
        }
        if ($_GET["order"]) {
            $order = $_GET["order"];
        } elseif ($this->DefaultOrder) {
            $order = $this->DefaultOrder;
        }
        if ($sort) {
            return trim($SQL) . " ORDER BY $sort $order";
        } else {
            return trim($SQL);
        }
    }
    
	function download( $filename, $title = null ) {
		if ($_GET['download'] == "y") {
			header("Content-Type: text/csv");
			header("Content-Disposition: attachment; filename=$filename");
        	$endline = $this->DownloadEndLine;
        	$delimiter = $this->DownloadDelimiter;
        	
        	if (strlen(trim($title)) > 0) echo "{$title}{$endline}{$endline}";
        	
            $rs = pg_query($this->DbConn, $this->SQL);

			$f = pg_num_fields($rs);
        	for ($i = 0; $i < $f; $i++) {
            	$fields[$i]->name = pg_field_name($rs, $i);
            	if ($i > 0) echo $delimiter;
            	if (!isset($this->ColHeader[$i])) {
                	if ($this->AutoFieldsName) {
                    	echo "\"" . ucwords(strtolower(str_replace("_", " ", $fields[$i]->name))) . "\"";
                	} else {
                    	echo "\"" . $fields[$i]->name . "\"";
                	}
            	} else {
            	    echo "\"" . $this->ColHeader[$i] . "\"";
            	}
        	}
        	echo $endline;
        	
        	while ($dt = pg_fetch_array($rs, null, PGSQL_NUM)) {
	        	for ($i = 0; $i < $f; $i++) {
	        		if ($i > 0) echo $delimiter;
	        		echo "\"{$dt[$i]}\"";
	        	}
	        	echo $endline;
        	}
        	
			pg_free_result($rs);
			exit;
		}
	}
	
	function _number_format( $number, $num_decimal_places )
	{
	    $loc = setlocale(LC_ALL, 0);
	    if (preg_match("/indonesia/i", $loc) || preg_match("/id_id/i", $loc)) {
	        $thousands_separator = ".";
	        $dec_separator = ",";
	    } else {
	        $thousands_separator = ",";
	        $dec_separator = ".";
	    }
	    return number_format($number, $num_decimal_places, $dec_separator, $thousands_separator);
	}
	
	function show_as_form()
	{
        @$rs = pg_query($this->DbConn, $this->addLimit($this->addSortOrder($this->SQL)));
        $f = pg_num_fields($rs);
        $i = 0;
        for ($i = 0; $i < $f; $i++) {
            $fields[$i] = pg_field_name($rs, $i);
            $ftype[$i]  = pg_field_type($rs, $i);
        }
        
        $first = true;   
        BaseTable::printTableOpen();
        while ($dt = pg_fetch_array($rs, null, PGSQL_NUM)) {
            if ($first) {
                $first = false;
            } else {
                echo "<tr>\n";
                echo "<td colspan=2 class=TBL_FRM_BODY>&nbsp;</td>\n";
                echo "</tr>";
            }
            foreach ($dt as $k => $v) {
                if (isset($this->ColAlign[$i])) {
                    $falg[$k] = $this->ColAlign[$k];
                } elseif ($ftype[$k] == "numeric") {
                    $falg[$k] = "RIGHT";
                } elseif ($ftype[$k] == "datetime" || $ftype[$k] == "date") {
                    $falg[$k] = "CENTER";
                } else {
                    $falg[$k] = "LEFT";
                }
                if (isset($this->ColFormatPeriod[$k])) {
                    $tm = mktime(0, 0, 0, substr($dt[$k], 4, 2), 1, substr($dt[$k], 0, 4));
                    $dt[$k] = strftime($this->ColFormatPeriod[$k], $tm);
                }
                if ($ftype[$k] == "numeric" && isset($this->ColFormatMoney[$k])) {
                    $fval[$k] = money_format($this->ColFormatMoney[$k], $dt[$k]);
                } elseif ($ftype[$k] == "numeric" && isset($this->ColFormatDecimal[$k])) {
                    $fval[$k] = $this->_number_format($dt[$k], $this->ColFormatDecimal[$k]);
                } elseif ($ftype[$k] == "datetime" || $ftype[$k] == "date") {
                    if (isset($this->ColFormatDate[$k])) {
                        $fval[$k] = $this->xstrftime($this->ColFormatDate[$k], $this->mysql2mktime($dt[$k]));
                    } else {
                        $fval[$k] = $this->xstrftime($this->DefaultFormatDate, $this->mysql2mktime($dt[$k]));
                    }
                } elseif(isset($this->ColFormatHtml[$k])) {
                    $fval[$k] = $this->ColFormatHtml[$k];
                    for ($j = 0; $j < $f; $j++) {
                        $fval[$k] = str_replace("<#$j#>", $dt[$j], $fval[$k]);
                    }
                } else {
                    $fval[$k] = $dt[$k];
                }
                if (!isset($this->ColFormatHtml[$k])) $fval[$k] = str_replace(" ", "&nbsp;", $fval[$k]);
                if (!isset($this->ColHidden[$k])) {
                    $style = $this->ColFormatBold[$k] ? "TBL_FRM_BODY_BOLD" : "TBL_FRM_BODY";
                    echo "<tr>\n";
                    if (isset($this->ColHeader[$k])) {
                        echo "<td class=$style>{$this->ColHeader[$k]}</td>\n";
                    } else {
                        echo "<td class=$style>{$fields[$k]}</td>\n";
                    }
                    echo "<td class=$style align=\"{$falg[$k]}\">{$fval[$k]}</td>\n";
                    echo "</tr>";
                }
            }
        }
        BaseTable::printTableClose();
        pg_free_result($rs);
	}

	function execute($show_as_form = false)
    {
        if ($show_as_form) {
            $this->show_as_form();
            return;
        }
        setlocale(LC_ALL, $this->locale);
        $time_start = $this->getmicrotime();
        if (is_array($this->ColRowSpan)) {
            if (is_array($this->ColTotal)) {
                $sql = $this->SQL;
            } else {
                $sql = $this->addLimit($this->SQL);
            }
            $this->DisableSort = true;
        } else {
            if (is_array($this->ColTotal)) {
                $sql = $this->addSortOrder($this->SQL);
            } else {
                $sql = $this->addLimit($this->addSortOrder($this->SQL));
            }
        }
        @$rs = pg_query($this->DbConn, $sql);
        $time_exec  = $this->getmicrotime() - $time_start;
        
        if (!$rs) {
            $this->errmsg( @pg_last_error($this->DbConn) .
                           "<BR><BR><B>Your SQL statement is:</B><BR>" .
                           nl2br("<CODE>$sql</CODE>"));
            return;
        }

        if (strlen($this->SQLCounter) > 0) {
            $rc = pg_query($this->DbConn, $this->SQLCounter);
            $dc = pg_fetch_array($rc);
            pg_free_result($rc);
            $rowCount = $dc[0];
        } else {
            $rc = pg_query($this->DbConn, "SELECT COUNT(*) AS CNT FROM ($this->SQL) AS TBLCNT");
            $dc = pg_fetch_object($rc);
            pg_free_result($rc);
            $rowCount = $dc->cnt;
        }

        BaseTable::printTableOpen();
        // fetch fields info into array
        $f = pg_num_fields($rs);
        $i = 0;
        for ($i = 0; $i < $f; $i++) {
            unset($f_unit);
            $fields[$i] = pg_field_name($rs, $i);
            $ftype[$i]  = pg_field_type($rs, $i);
            if (!isset($this->ColHeader[$i])) {
                if ($this->AutoFieldsName) {
                    $this->ColHeader[$i] = ucwords(strtolower(str_replace("_", " ", $fields[$i])));
                } else {
                    $this->ColHeader[$i] = $fields[$i];
                }
            }
            if ($_GET["sort"] == $i + 1 && $_GET["order"] == "ASC") {
                $fhref[$i] = BaseTable::setQueryString(
                                Array( "sort"     => $i + 1,
                                       "order"    => "DESC",
                                       "tblstart" => 0) );
            } else {
                $fhref[$i] = BaseTable::setQueryString(
                                Array( "sort"     => $i + 1,
                                       "order"    => "ASC",
                                       "tblstart" => 0) );
            }
            if (is_array($this->unit[$i])) {
                foreach ($this->unit[$i] as $uk => $uv) if ($uv['def']) $d_unit[$i] = $uk;
                foreach ($this->unit[$i] as $uk => $uv) if (isset($_GET["u{$i}"])) $d_unit[$i] = $_GET["u{$i}"];
                foreach ($this->unit[$i] as $uk => $uv) {
                    if (isset($f_unit)) {
                        $f_unit .= " | ";
                    } else {
                        $f_unit  = "<br>";
                    }
                    if ($uk == $d_unit[$i]) {
                        $f_unit .= "<span class=TBL_UNIT_ACTIVE>{$uv['des']}</span>";
                    } else {
                        $f_unit .= "<a class=TBL_UNIT_INACTIVE href=\"".BaseTable::setQueryString(array("u{$i}" => $uk))."\"><small>{$uv['des']}</small></a>";
                    }
                }
                $f_unit = "<span class=TBL_UNIT_INACTIVE>$f_unit</span>";
            }
            if ($this->DisableSort || $this->ColDisableSort[$i]) {
                $header[$i] = "<SPAN CLASS=TBL_HEAD>{$this->ColHeader[$i]}</SPAN>{$f_unit}";
            } else {
                $header[$i] = "<A CLASS=TBL_HEAD HREF='$fhref[$i]'>{$this->ColHeader[$i]}</A>{$f_unit}";
            }
        }
        $tmp = $header;
        $header = array();
        foreach ($tmp as $k => $v) {
            if (!$this->ColHidden[$k]) {
                array_push($header, $v);
            }
        }
        if ($this->ShowRowNumber) array_unshift($header, "NO");
        BaseTable::printTableHeader($header);

        // fetch contents
        if (is_array($this->ColTotal)) {
            $tmp = array();
            $start = isset($_GET['tblstart']) ? $_GET['tblstart'] : 0;
            $cnt = 0;
            foreach ($this->ColTotal as $k => $v) $total[$k] = 0;
            while ($dt = pg_fetch_array($rs, null, PGSQL_NUM)) {
                if ($cnt >= $start && $cnt < $start + $this->RowsPerPage) {
                    array_push($tmp, $dt);
                }
                foreach ($this->ColTotal as $k => $v) {
                    if (is_array($this->unit[$k])) {
                        $total[$k] += ($dt[$k] *= $this->unit[$k][$d_unit[$k]]['val']);
                    } else {
                        $total[$k] += $dt[$k];
                    }
                }
                $cnt++;
            }
        } else {
            $tmp = array();
            while ($dt = pg_fetch_array($rs, null, PGSQL_NUM)) {
                array_push($tmp, $dt);
            }
        }
            
        
        // build rowspan matrix
        for ($i = 0; $i < $f; $i++) {
            foreach ($tmp as $j => $dt) {
                $rsp[$j][$i] = 1;
            }
        }

        if (is_array($this->ColRowSpan)) {
            asort($this->ColRowSpan);
            foreach ($this->ColRowSpan as $i => $z) {
                unset($last_data);
                unset($last_indx);
                foreach ($tmp as $j => $dt) {
                    if ($this->ColRowSpan[$i] > 0) {
                        if ($dt[$i] == $last_data) {
                            $stop_rowspan = false;
                            unset($found);
                            for ($p = $this->ColRowSpan[$i]-1; $p > 0; $p--) {
                                for ($q = 0; $q < $f; $q++) {
                                    if ($this->ColRowSpan[$q] == $p) {
                                        $found = $q;
                                        break;
                                    }
                                }
                            }
                            if (isset($found)) {
                                $stop_rowspan = $rsp[$j][$found] >= 1;
                            }
                            if ($stop_rowspan) {
                                $last_indx = $j;
                                $rsp[$j][$i] = 1;
                            } else {
                                $rsp[$last_indx][$i]++;
                                $rsp[$j][$i] = 0;
                            }
                        } else {
                            $last_indx = $j;
                            $rsp[$j][$i] = 1;
                        }
                    } else {
                        $rsp[$j][$i] = 1;
                    }
                    $last_data = $dt[$i];
                }
            }
        }        
        
        $rowNum = (int) $_GET["tblstart"];
        foreach ($tmp as $r => $dt) {
            for ($i = 0; $i < $f; $i++) {
                if (is_array($this->unit[$i])) $dt[$i] *= $this->unit[$i][$d_unit[$i]]['val'];
                if (isset($this->ColAlign[$i])) {
                    $falg[$i] = $this->ColAlign[$i];
                } elseif ($ftype[$i] == "numeric") {
                    $falg[$i] = "RIGHT";
                } elseif ($ftype[$i] == "datetime" || $ftype[$i] == "date") {
                    $falg[$i] = "CENTER";
                } else {
                    $falg[$i] = "LEFT";
                }
                //-----hery-----kolom warna---
                if (isset($this->ColColor[$i])) {
                	if (function_exists($this->ColColor[$i])) {
                		eval("\$udfval = {$this->ColColor[$i]}(\$dt, \$i);");
                        $dt[$i] = $udfval;
                	}
                }
                //-----------------------------
                if (isset($this->ColFormatPeriod[$i])) {
                    $tm = mktime(0, 0, 0, substr($dt[$i], 4, 2), 1, substr($dt[$i], 0, 4));
                    $dt[$i] = strftime($this->ColFormatPeriod[$i], $tm);
                }
                if ($ftype[$i] == "numeric" && isset($this->ColFormatMoney[$i])) {
                    $fval[$i] = money_format($this->ColFormatMoney[$i], $dt[$i]);
                } elseif ($ftype[$i] == "numeric" && isset($this->ColFormatDecimal[$i])) {
                    $fval[$i] = $this->_number_format($dt[$i], $this->ColFormatDecimal[$i]);
                } elseif ($ftype[$i] == "datetime" || $ftype[$i] == "date") {
                    if (isset($this->ColFormatDate[$i])) {
                        $fval[$i] = $this->xstrftime($this->ColFormatDate[$i], $this->mysql2mktime($dt[$i]));
                    } else {
                        $fval[$i] = $this->xstrftime($this->DefaultFormatDate, $this->mysql2mktime($dt[$i]));
                    }
                } elseif(isset($this->ColFormatHtml[$i])) {
                    $fval[$i] = $this->ColFormatHtml[$i];
                    for ($j = 0; $j < $f; $j++) {
                        $fval[$i] = str_replace("<#$j#>", $dt[$j], $fval[$i]);
                    }
                } else {
                    $fval[$i] = $dt[$i];
                }
                if (!isset($this->ColFormatHtml[$i]) && !isset($this->ColColor[$i])) $fval[$i] = str_replace(" ", "&nbsp;", $fval[$i]);//hery
               // if (!isset($this->ColFormatHtml[$i])) $fval[$i] = str_replace(" ", "&nbsp;", $fval[$i]);
            }
            $rowNum++;
            $frsp = $rsp[$r];
            if ($this->ShowRowNumber) {
                array_unshift($fval, "$rowNum.");
                array_unshift($falg, "RIGHT");
                array_unshift($frsp, 1);
            }
            $tmp1 = $fval;
            $tmp2 = $falg;
            $tmp3 = $frsp;
            $fval = array();
            $falg = array();
            $frsp = array();
            foreach ($tmp1 as $k1 => $v2) {
                if ((!isset($this->ColHidden[$k1])) || $this->ColHidden[$k1] == false) {
                    array_push($fval, $v2);
                    array_push($falg, $tmp2[$k1]);
                    array_push($frsp, $tmp3[$k1]);
                }
            }
            BaseTable::printRow($fval, $falg, $frsp);
            unset($fval);
            unset($falg);
        }
        
        if (is_array($this->ColTotal)) {
            for ($i = 0; $i < $f; $i++) {
                if (!isset($total[$i])) $total[$i] = "";
                if (strlen($total[$i]) == 0) continue;
                if (isset($this->ColAlign[$i])) {
                    $falg[$i] = $this->ColAlign[$i];
                } elseif ($ftype[$i] == "numeric") {
                    $falg[$i] = "RIGHT";
                } elseif ($ftype[$i] == "datetime" || $ftype[$i] == "date") {
                    $falg[$i] = "CENTER";
                } else {
                    $falg[$i] = "LEFT";
                }
                if ($ftype[$i] == "numeric" && isset($this->ColFormatMoney[$i])) {
                    $total[$i] = money_format($this->ColFormatMoney[$i], $total[$i]);
                } elseif ($ftype[$i] == "numeric" && isset($this->ColFormatDecimal[$i])) {
                    $total[$i] = $this->_number_format($total[$i], $this->ColFormatDecimal[$i]);
                }
                $colspan[$i] = 1;
            }
            if (is_array($total))   ksort($total);
            if (is_array($falg))    ksort($falg);
            if (is_array($colspan)) ksort($colspan);
            if ($this->ShowRowNumber) {
                array_unshift($total, "");
                array_unshift($falg, "RIGHT");
                array_unshift($colspan, 1);
            }
            $tmp1 = $total;
            $tmp2 = $falg;
            $tmp3 = $colspan;
            $total = array();
            $falg = array();
            $colspan = array();
            foreach ($tmp1 as $k1 => $v2) {
                if ((!isset($this->ColHidden[$k1])) || $this->ColHidden[$k1] == false) {
                    array_push($total, $v2);
                    array_push($falg, $tmp2[$k1]);
                    array_push($colspan, $tmp3[$k1]);
                }
            }
            
            $start_span = -1;
            for ($i = 0; $i < $f; $i++) {
                if (trim(strlen($total[$i])) == 0) {
                    $start_span = $i;
                } else {
                    break;
                }
            }
            for ($i = 0; $i <= $start_span; $i++) {
                if ($i == 0) {
                    $colspan[$i] = $start_span + 1;
                    $total[$i] = "TOTAL";
                    $falg[$i] = "CENTER";
                } else {
                    $colspan[$i] = 0;
                }
            }
            BaseTable::printTableFooter($total, $falg, null, $colspan);
        }
        
        if (is_array($this->ColFooter)) {
            for ($i = 0; $i < $f; $i++)
                if (isset($this->ColFooter[$i])) {
                    $foot[$i] = $this->ColFooter[$i];
                } else {
                    $foot[$i] = "&nbsp;";
                }
            if ($this->ShowRowNumber)
                array_unshift($foot, "&nbsp;");
            BaseTable::printTableFooter($foot);
        }

        if ($this->ShowRowNumber) $f += 1;
        
        if (!$this->DisableStatusBar) {
            $ctl .= "<TABLE WIDTH='100%' BORDER=0 CELLSPACING=0 CELLPADDING=0><TR>";
            $ctl .= "<TD CLASS=TBL_BODY><b>";           
            $ctl .= sprintf($this->msgs[$this->locale][0],
                            $_GET["tblstart"] + 1,
                            pg_num_rows($rs) < $this->RowsPerPage ? pg_num_rows($rs) : $_GET["tblstart"] + $this->RowsPerPage,
                            $rowCount);
            $ctl .= "</b></TD>";
           
            if ($this->ShowSQLExecTime) {
                $ctl .= "<TD CLASS=TBL_BODY><b>";
                $ctl .= sprintf($this->msgs[$this->locale][1], $time_exec);
                $ctl .= "</b></TD>";
            }
    
            if ($this->ShowDownload) {
                $ctl .= "<TD ALIGN=CENTER>";
                $ctl .= "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0><TR>";
                $ctl .= "<TD CLASS=TBL_BODY><A HREF='" . BaseTable::setQueryString(array("download" => "y")) . "'><IMG BORDER=0 SRC='images/csv.gif'></A></TD>";
                $ctl .= "<TD CLASS=TBL_BODY><b><A HREF='" . BaseTable::setQueryString(array("download" => "y")) . "'>Download</A></b></TD>";
                $ctl .= "</TR></TABLE>";
                $ctl .= "</TD>";
            }

            if (!$this->DisableNavButton) {
                $ctl .= "<TD ALIGN=RIGHT>";
                $ctl .= "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2><TR>";
                $ctl .= "<TD CLASS=TBL_BODY ALIGN=RIGHT>";
                if ($rowCount < $this->RowsPerPage){
                	$ctl .= "<TD CLASS=TBL_BODY><IMG BORDER=0 SRC='images/movefirst-d.png'></TD>";
                    $ctl .= "<TD CLASS=TBL_BODY><IMG BORDER=0 SRC='images/moveprev-d.png'></TD>";
                
                } elseif ($this->canMovePrev($rowCount)) {
                    $ctl .= "<TD CLASS=TBL_BODY><A CLASS=TBL_HREF HREF='" . $this->hrefMoveFirst($rowCount) . "'>" .
                            "<IMG BORDER=0 SRC='images/movefirst.png'></A></TD>";
                    $ctl .= "<TD CLASS=TBL_BODY><A CLASS=TBL_HREF HREF='" . $this->hrefMovePrev($rowCount) . "'>" .
                            "<IMG BORDER=0 SRC='images/moveprev.png'></A></TD>";
                } else {
                    $ctl .= "<TD CLASS=TBL_BODY><IMG BORDER=0 SRC='images/movefirst-d.png'></TD>";
                    $ctl .= "<TD CLASS=TBL_BODY><IMG BORDER=0 SRC='images/moveprev-d.png'></TD>";
                }
            }
            
            if (!$this->DisableScrollBar) {
                if ($this->DisableNavButton) {
                    $ctl .= "<TD CLASS=TBL_BODY ALIGN=RIGHT>" . $this->ScrollBar($rowCount) . "</TD>";
                } else {
                    $ctl .= "<TD CLASS=TBL_BODY ALIGN=CENTER>" . $this->ScrollBar($rowCount) . "</TD>";
                }
            }
            if (!$this->DisableNavButton) {
            	if ($rowCount < $this->RowsPerPage){
                	$ctl .= "<TD CLASS=TBL_BODY><IMG BORDER=0 SRC='images/movenext-d.png'></TD>";
                    $ctl .= "<TD CLASS=TBL_BODY><IMG BORDER=0 SRC='images/movelast-d.png'></TD>";
                
                } elseif ($this->canMoveNext($rowCount)) {
                    $ctl .= "<TD CLASS=TBL_BODY><A CLASS=TBL_HREF HREF='" . $this->hrefMoveNext($rowCount) . "'>" .
                            "<IMG BORDER=0 SRC='images/movenext.png'></A></TD>";
                    $ctl .= "<TD CLASS=TBL_BODY><A CLASS=TBL_HREF HREF='" . $this->hrefMoveLast($rowCount) . "'>" .
                            "<IMG BORDER=0 SRC='images/movelast.png'></A></TD>";
                } else {
                    $ctl .= "<TD CLASS=TBL_BODY><IMG BORDER=0 SRC='images/movenext-d.png'></TD>";
                    $ctl .= "<TD CLASS=TBL_BODY><IMG BORDER=0 SRC='images/movelast-d.png'></TD>";
                }
                $ctl .= "</TD>";
                $ctl .= "</TR></TABLE>";
                $ctl .= "</TD>";
            }
                    
            $ctl .= "</TR></TABLE>";
            
            BaseTable::printColSpan($f, $ctl, "CENTER");
        }
        
        if ($this->ShowSQL)
            BaseTable::printColSpan($f,
                "<B>SQL Debug:</B><BR><CODE>$sql</CODE>",
                "LEFT");
        
        BaseTable::printTableClose();
        
        pg_free_result($rs);
      /*  
        if (strlen($this->Signature) > 0) {
            echo "<div align=right style=\"font-family: tahoma, arial; font-size: 10px; font-weight: bold;\">{$this->Signature}</div>\n";
        }
        */
    }

    function ClassInfo()
    {
        return Array(
                "version"            => "0.2",
                "last_updated"       => "Mon Mar  8 19:27:02 WIT 2004",
                "original_filename"  => "class.PgTable.php",
                "author"             => "MA Nugraha <nugraha@arpaa.com>"
            );
    }

}

?>