<?php
//nugraha 
//hso june 21 ,2007 + align


class TabBar
{
    
    var $_tabs;
    var $_selected;
    var $_width;
    
    function TabBar( $width = "100%", $align = "center" )
    {
        $this->_selected = 0;
        $this->_width = $width;
        $this->_align = $align;
    }
    
    function addTab( $href, $caption, $enabled = true ) {
        if (is_array($this->_tabs)) {
            $new_idx = sizeof($this->_tabs);
        } else {
            $new_idx = 0;
        }
        $this->_tabs[$new_idx]["href"] = $href;
        $this->_tabs[$new_idx]["caption"] = $caption;
        $this->_tabs[$new_idx]["enabled"] = $enabled;
    }
    
    function show( $tabIndex = 0 )
    {
        if (isset($tabIndex)) $this->_selected = $tabIndex;
		//echo "<DIV class='animatedtabs'>\n";
        echo "<TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=\"{$this->_width}\" ALIGN=\"{$this->_align}\" CLASS=\"tab_frame\"><TR><TD>\n";
        
        echo "<TABLE CELLSPACING=0><TR>\n";
        foreach ($this->_tabs as $k => $v) {
            if ($k == $this->_selected) {
                $style = "tab_cell_active";
                $style_href = "tab_href_active";
            } else {
                $style = "tab_cell_inactive";
                $style_href = "tab_href_inactive";
            }
            if ($v["enabled"] == false) $style = "tab_cell_disabled";
            echo "<TD>&nbsp;</TD>";
            echo "<TD CLASS=\"{$style}\">";
            if ($v["enabled"]) {
                echo "<A HREF=\"{$v['href']}\" CLASS=\"{$style_href}\">{$v['caption']}</A>";
            } else {
                echo $v['caption'];
            }
            echo "</TD>\n";
        }
        echo "</TR></TABLE>\n";
        
        echo "</TD></TR></TABLE>\n";
		//echo "</DIV>\n";
		
    }
    
}


?>
