<?
// Class Form
// Nugraha, 25/12/2003, just created
// Nugraha, 03/02/2004, ReadOnlyForm added
// Nugraha, 13/02/2004, optimized for postgresql
//                      internal db connection removed
//          22/02/2004, checkBoxSQL() added
// Hery,    13/05/1007,  

/*
<HTML>
<script type="text/javascript" src="plugin/jquery.js"></script>
<script type='text/javascript' src='plugin/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='plugin/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='plugin/thickbox-compressed.js'></script>
<script type='text/javascript' src='plugin/jquery.autocomplete.js'></script>
<script type='text/javascript' src='plugin/localdata.js'></script>

<link rel="stylesheet" type="text/css" href="plugin/jquery.autocomplete.css" />


<script type="text/javascript">
$().ready(function() {

	function log(event, data, formatted) {
		$("<li>").html( !data ? "No match!" : "Selected: " + formatted).appendTo("#result");
	}

	function formatItem(row) {
		return row[0] + " (<strong>id: " + row[1] + "</strong>)";
	}
	function formatResult(row) {
		return row[0].replace(/(<.+?>)/gi, '');
	}

	$("#suggest1").focus().autocomplete(cities);

	$("#pasien1").focus().autocomplete(nama);
	$("#month").autocomplete(months, {
		minChars: 0,
		max: 12,
		autoFill: true,
		mustMatch: true,
		matchContains: false,
		scrollHeight: 220,
		formatItem: function(data, i, total) {
			// don't show the current month in the list of values (for whatever reason)
			if ( data[0] == months[new Date().getMonth()] )
				return false;
			return data[0];
		}
	});
	$("#suggest13").autocomplete(emails, {
		minChars: 0,
		width: 310,
		matchContains: "word",
		autoFill: false,
		formatItem: function(row, i, max) {
			return i + "/" + max + ": \"" + row.name + "\" [" + row.to + "]";
		},
		formatMatch: function(row, i, max) {
			return row.name + " " + row.to;
		},
		formatResult: function(row) {
			return row.to;
		}
	});
	$("#singleBirdRemote").autocomplete("search.php", {
		width: 260,
		selectFirst: false
	});
	$("#AUTOTEXT").autocomplete("lib/coba.php", {
		width: 260,
		selectFirst: false
	});
	$("#suggest14").autocomplete(cities, {
		matchContains: true,
		minChars: 0
	});
	$("#suggest3").autocomplete(cities, {
		multiple: true,
		mustMatch: true,
		autoFill: true
	});
	$("#suggest4").autocomplete('search.php', {
		width: 300,
		multiple: true,
		matchContains: true,
		formatItem: formatItem,
		formatResult: formatResult
	});
	$("#imageSearch").autocomplete("images.php", {
		width: 320,
		max: 4,
		highlight: false,
		scroll: true,
		scrollHeight: 300,
		formatItem: function(data, i, n, value) {
			return "<img src='images/" + value + "'/> " + value.split(".")[0];
		},
		formatResult: function(data, value) {
			return value.split(".")[0];
		}
	});
	$("#tags").autocomplete(["c++", "java", "php", "coldfusion", "javascript", "asp", "ruby", "python", "c", "scala", "groovy", "haskell", "pearl"], {
		width: 320,
		max: 4,
		highlight: false,
		multiple: true,
		multipleSeparator: " ",
		scroll: true,
		scrollHeight: 300
	});


	$(":text, textarea").result(log).next().click(function() {
		$(this).prev().search();
	});
	$("#singleBirdRemote").result(function(event, data, formatted) {
		if (data)
			$(this).parent().next().find("input").val(data[1]);
	});
	$("#suggest4").result(function(event, data, formatted) {
		var hidden = $(this).parent().next().find(">:input");
		hidden.val( (hidden.val() ? hidden.val() + ";" : hidden.val()) + data[1]);
	});
    $("#suggest15").autocomplete(cities, { scroll: true } );
	$("#scrollChange").click(changeScrollHeight);

	$("#thickboxEmail").autocomplete(emails, {
		minChars: 0,
		width: 310,
		matchContains: true,
		highlightItem: false,
		formatItem: function(row, i, max, term) {
			return row.name.replace(new RegExp("(" + term + ")", "gi"), "<strong>$1</strong>") + "<br><span style='font-size: 80%;'>Email: &lt;" + row.to + "&gt;</span>";
		},
		formatResult: function(row) {
			return row.to;
		}
	});

	$("#clear").click(function() {
		$(":input").unautocomplete();
	});
});

function changeOptions(){
	var max = parseInt(window.prompt('Please type number of items to display:', jQuery.Autocompleter.defaults.max));
	if (max > 0) {
		$("#suggest1").setOptions({
			max: max
		});
	}
}

function changeScrollHeight() {
    var h = parseInt(window.prompt('Please type new scroll height (number in pixels):', jQuery.Autocompleter.defaults.scrollHeight));
    if(h > 0) {
        $("#suggest1").setOptions({
			scrollHeight: h
		});
    }
}

function changeToMonths(){
	$("#suggest1")
		// clear existing data
		.val("")
		// change the local data to months
		.setOptions({data: months})
		// get the label tag
		.prev()
		// update the label tag
		.text("Month (local):");
}
</script>
</HTML>
*/

class Form
{

    var $PgConn;
    var $b;

    function Form($action, $method = "POST", $ext = "")
    {
        $this->b .= "<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
        $this->b .= "<FORM ACTION='$action' $ext METHOD='$method'>\n";
    }

    function title($capt,$align="LEFT")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM_TITLE ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function title1($capt,$align="LEFT")
    {
    	$this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM_SUBTITLE1 ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
	
    function title2($capt,$align="LEFT")
    {
    	$this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function titleme($capt,$align="LEFT")
    {
    	$this->b .= "<TR>\n";
        $this->b .= "<TD width='257' CLASS=FORM_SUBTITLE1 ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function subtitle($capt,$align="LEFT")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM_SUBTITLE ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function subtitle1($capt,$align="LEFT")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='257' CLASS=FORM_SUBTITLE ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function subtitle2($capt,$align="LEFT")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='457' CLASS=FORM_SUBTITLE ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }

    function file($name, $capt, $size, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM><b>$capt</b></TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=FILE NAME=$name SIZE=$size></TD>\n";
        $this->b .= "</TR>\n\n";
    }

    function text($name, $capt, $size, $maxlength, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    function text2column_A($name, $capt, $size, $maxlength, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='400' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD>\n";
        //$this->b .= "</TR>\n\n";
    }
    
    function text2column_B($name, $capt, $size, $maxlength, $defval, $ext = "")
    {
        //$this->b .= "<TR>\n";
        $this->b .= "<TD width='325' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
   function textauto($name, $capt, $size, $maxlength, $defval, $ext = "") {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT ID=AUTOTEXT NAME=$name SIZE=$size " .
                "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD>\n";
        // $this->b .= "</TR>\n\n";
    }

    function textauto_all($name, $id, $capt, $size, $maxlength, $defval, $ext = "") {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS='design10a'>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS='design10'><INPUT  TYPE=TEXT ID=$id NAME=$name SIZE=$size " .
                "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD>\n";
    }
	function textauto_all2($name, $id, $capt, $size, $maxlength, $defval, $ext = "",$ext2 = "") {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS='design10a'>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS='design10'><INPUT  TYPE=TEXT ID=$id NAME=$name SIZE=$size " .
                "MAXLENGTH=$maxlength $ext VALUE='$defval'>$ext2</TD>\n";
    }
    function text_view($name, $capt, $size, $maxlength, $defval,$popup,$icon, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;&nbsp;<a href='javascript:$popup()'>".icon("$icon")."</a></TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    function textinfo($name, $capt, $size, $maxlength, $defval, $capt2, $ext = "")
    {
        $this->b .= "<TR >\n";
       	$this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM VALIGN=MIDDLE><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;&nbsp;$capt2</TD>\n";        
        $this->b .= "</TR>\n\n";
    }
    function search($name, $capt, $size, $maxlength, $defval, $src,$alt, $ext = "")
    {
        $this->b .= "<TR >\n";
       	$this->b .= "<TD CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT ID=AUTOTEXT NAME=$name SIZE=$size MAXLENGTH=$maxlength $ext VALUE='$defval'></TD>\n";
        $this->b .= "<TD CLASS=FORM VALIGN='ABSMIDDLE'><INPUT TYPE='IMAGE' $ext SRC='$src' TITLE='$alt' ></TD>\n";        
        $this->b .= "</TR>\n\n";
    }
    //---14juli07hery
    function calendar($nametxt, $capt, $size, $maxlength, $defval,$name_form, $src_img,$alt, $ext = "" )
    {
    	

   		$this->b .= "<TR >\n";
       	$this->b .= "<TD CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=$nametxt SIZE=$size MAXLENGTH=$maxlength $ext VALUE='$defval'>\n";
        $this->b .= "<A HREF=\"#\" onClick=\"cal.select(document.forms['$name_form'].$nametxt,'tanggalan','dd-MM-yyyy'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' $ext SRC='$src_img' TITLE='$alt' ></A></TD>\n";        
        $this->b .= "</TR>\n\n";
   
   
    }
    function calendar1($nametxt, $capt, $size, $maxlength, $defval,$name_form, $src_img,$alt, $ext = "" )
    {


   		$this->b .= "<TR >\n";
       	$this->b .= "<TD CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=$nametxt SIZE=$size MAXLENGTH=$maxlength $ext VALUE='$defval'>\n";
        $this->b .= "<A HREF=\"#\" onClick=\"cal.select(document.forms['$name_form'].$nametxt,'tanggalan','yyyy-MM-dd'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' $ext SRC='$src_img' TITLE='$alt' ></A></TD>\n";
        $this->b .= "</TR>\n\n";


    }
    function textarea1($header,$name, $capt, $rows, $cols, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TR>\n<TD COLSPAN='6' ALIGN=LEFT CLASS=FORM_SUBTITLE1>$header</TD></TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><TEXTAREA $ext NAME=$name ROWS=$rows COLS=$cols>";
        $this->b .= "$defval</TEXTAREA></TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    //heri 13 may 2007
    function text4($name, $capt, $size, $maxlength, $defval, $name2, $capt2, $size2, $maxlength2, $defval2, $name3, $capt3, $size3, $maxlength3, $defval3, $name4, $capt4, $size4, $maxlength4, $defval4,$ext="")
    {
        $this->b .= "</TABLE><TABLE  BORDER=0 CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "<TD WIDTH=159 CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt2</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext VALUE='$defval2'></TD></TR>\n";
        $this->b .= "<TR>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM>$capt3</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext VALUE='$defval3'></TD>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM>$capt4</TD>\n";
        $this->b .= "<TD CLASS=FORM >:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name4 SIZE=$size4 ".
                    "MAXLENGTH=$maxlength4 $ext VALUE='$defval4'></TD></TR>\n";
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_5($header,$name, $capt, $size, $maxlength, $defval,$info, $name2, $capt2, $size2, $maxlength2, $defval2,
    				$info2, $name3, $capt3, $size3, $maxlength3, $defval3,$info3, $name4, $capt4, $size4, $maxlength4,
    				$defval4,$info4, $name5, $capt5, $size5, $maxlength5, $defval5,$info5,$ext = "")
    {
        $this->b .= "</TABLE><BR><TABLE  BORDER=0 CELLSPACING=2 CELLPADDING=2 WIDTH=65%><TR>\n";
        $this->b .= "<TR>\n<TD COLSPAN='6' ALIGN=LEFT CLASS=FORM_SUBTITLE1>$header</TD></TR>\n";
        $this->b .= "<TD WIDTH=157 CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;$info</TD>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt2</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext VALUE='$defval2'>&nbsp;$info2</TD></TR>\n";
        $this->b .= "<TR>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM>$capt3</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext VALUE='$defval3'>&nbsp;$info3</TD>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM>$capt4</TD>\n";
        $this->b .= "<TD CLASS=FORM >:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name4 SIZE=$size4 ".
                    "MAXLENGTH=$maxlength4 $ext VALUE='$defval4'>&nbsp;$info4</TD></TR>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM>$capt5</TD>\n";
        $this->b .= "<TD CLASS=FORM >:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name5 SIZE=$size5 ".
                    "MAXLENGTH=$maxlength5 $ext VALUE='$defval5'>&nbsp;$info5</TD></TR>\n";
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_3x($name, $capt, $size, $maxlength, $defval,$info, $name2, $capt2, $size2, $maxlength2, $defval2,$info2, $name3, $capt3, $size3, $maxlength3, $defval3,$info3,$ext = "")
    {
        $this->b .= "<TABLE  BORDER=0 CELLSPACING=2  WIDTH=82%><TR>\n";
        $this->b .= "<TD WIDTH=160 CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;$info</TD>\n";
        $this->b .= "<TD CLASS=FORM >$capt2</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext VALUE='$defval2'>&nbsp;$info2</TD>\n";
        $this->b .= "<TD CLASS=FORM >$capt3</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext VALUE='$defval3'>&nbsp;$info3</TD></TR>\n";            
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_check($no,$name1,$val1,$check1,$capt1,$name2,$val2,$check2,$capt2,
    					$capt3,$name3,$defval3,$capt4,$name4,$defval4,$ext = "")
    {
        $this->b .= "</TABLE><TABLE  BORDER=0 CELLSPACING=0 CELLPADDING=2 WIDTH=100%><TR>\n";
        $this->b .= "<tr><td width='5%' align='center'>$no</td>\n";
        $this->b .= "<TD WIDTH=15% CLASS=FORM><INPUT $ext TYPE=CHECKBOX NAME=$name1 VALUE=$val1 $check1>$capt1
        <INPUT $ext TYPE=CHECKBOX NAME=$name2 VALUE=$val2 $check2>$capt2</TD>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM>$capt3</TD>\n";
        $this->b .= "<TD CLASS=FORM >:</TD>\n";
    	$this->b .= "<TD CLASS=FORM><input name=$name3 VALUE='$defval3' $ext type=text size='30' maxlength='20'></td>";
    	$this->b .= "<TD WIDTH=100 CLASS=FORM>$capt4</TD>\n";
        $this->b .= "<TD CLASS=FORM >:</TD>\n";
    	$this->b .= "<TD CLASS=FORM><input name=$name4 VALUE='$defval4' $ext type=text size='50' maxlength='20'></td>";
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
   
    function text_mata($header,$gbr1,$judul,$gbr2,$name0,$maxlength0,$defval0,$name1,$maxlength1,$defval1,$name,$maxlength,$defval,$name2,$maxlength2,$defval2,$name3,$maxlength3,$defval3,$name4,$maxlength4,$defval4,$name5,
    					$maxlength5,$defval5,$name6,$maxlength6,$defval6,$name7,$maxlength7,$defval7,$name8,$maxlength8,$defval8,$name9,$maxlength9,$defval9,$name10,$maxlength10,$defval10,$name11,$maxlength11,$defval11,
    					$name12,$maxlength12,$defval12,$name13,$maxlength13,$defval13,$name14,$maxlength14,$defval14,$name15,$maxlength15,$defval15,$name16,$maxlength16,$defval16,$name17,$maxlength17,$defval17,$name18,
    					$maxlength18,$defval18,$header2,$name19,$maxlength19,$defval19,$name20,$maxlength20,$defval20,$name21,$maxlength21,$defval21,$name22,$maxlength22,$defval22,$name23,$maxlength23,$defval23,$name24,
    					$maxlength24,$defval24,$name25,$maxlength25,$defval25,$name26,$maxlength26,$defval26,$name27,$maxlength27,$defval27,$name28,$maxlength28,$defval28,$name29,$maxlength29,$defval29,$name30,$maxlength30,
    					$defval30,$name31,$maxlength31,$defval31,$name32,$maxlength32,$defval32,$ext="")
    {
    	$this->b .= "</TABLE><TABLE WIDTH=760 BORDER=0 ><TR>\n";
    	$this->b .= "<TR>\n<TD COLSPAN='4' ALIGN=LEFT CLASS=FORM_SUBTITLE1>$header</TD></TR>\n";
    	$this->b .=	"<TD ALIGN='CENTER' CLASS=FORM_SUBTITLE1><B>OD<B></TD>";
   	 	$this->b .=	"<TD ROWSPAN = 2 WIDTH=200 ALIGN='CENTER'><b>$judul<b></TD>";
    	$this->b .=	"<TD ALIGN='CENTER' CLASS=FORM_SUBTITLE1><B>OS<B></TD></TR>";
  		$this->b .=	"<TD ALIGN='CENTER'><$gbr1></TD>";
    	$this->b .=	"<TD ALIGN='CENTER'><$gbr2></TD></TR>\n";	
    	
    	$this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name0 SIZE=40 MAXLENGTH=$maxlength0 $ext VALUE='$defval0'></TD>\n";
    	$this->b .= "<TD ALIGN=CENTER><b>KETERANGAN GAMBAR</b></TD>\n";
    	$this->b .= "<TD><INPUT TYPE=TEXT NAME=$name1  SIZE=40 MAXLENGTH=$maxlength1 $ext VALUE='$defval1'></TD></TR>\n";
    	
    	$this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name SIZE=40 MAXLENGTH=$maxlength $ext VALUE='$defval'></TD>\n";
    	$this->b .= "<TD ALIGN=CENTER><b>PALPEBRA</b></TD>\n";
    	$this->b .= "<TD><INPUT TYPE=TEXT NAME=$name2  SIZE=40 MAXLENGTH=$maxlength2 $ext VALUE='$defval2'></TD></TR>\n";
    	
    	$this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name3 SIZE=40 MAXLENGTH=$maxlength3 $ext VALUE='$defval3'></TD>\n";
    	$this->b .= "<TD ALIGN=CENTER><b>CONJUNCTIVA</b></TD>\n";
    	$this->b .= "<TD><INPUT TYPE=TEXT NAME=$name4  SIZE=40 MAXLENGTH=$maxlength4 $ext VALUE='$defval4'></TD></TR>\n";
	  	
    	$this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name5 SIZE=40 MAXLENGTH=$maxlength5 $ext VALUE='$defval5'></TD>\n";
	    $this->b .= "<TD ALIGN=CENTER><b>CORNEA</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name6  SIZE=40 MAXLENGTH=$maxlength6 $ext VALUE='$defval6'></TD></TR>\n";
	    
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name7  SIZE=40 MAXLENGTH=$maxlength7 $ext VALUE='$defval7'></TD>\n";
	    $this->b .= "<TD ALIGN=CENTER><b>C.O.A</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name8  SIZE=40 MAXLENGTH=$maxlength8 $ext VALUE='$defval8'></TD></TR>\n";
	  	
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name9  SIZE=40 MAXLENGTH=$maxlength9 $ext VALUE='$defval9'></TD>\n";
	    $this->b .= "<TD ALIGN=CENTER><b>IRIS</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name10  SIZE=40 MAXLENGTH=$maxlength10 $ext VALUE='$defval10'></TD></TR>\n";
	  	
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name11  SIZE=40 MAXLENGTH=$maxlength11 $ext VALUE='$defval11'></TD>\n";
	    $this->b .= "<TD ALIGN=CENTER><b>PUPIL</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name12 SIZE=40 MAXLENGTH=$maxlength12 $ext VALUE='$defval12'></TD></TR>\n";
	  	
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name13 SIZE=40 MAXLENGTH=$maxlength13 $ext VALUE='$defval13'></TD>\n";
	    $this->b .= "<TD ALIGN=CENTER><b>LENSA</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name14  SIZE=40 MAXLENGTH=$maxlength14 $ext VALUE='$defval14'></TD></TR>\n";
	  	
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name15  SIZE=40 MAXLENGTH=$maxlength15 $ext VALUE='$defval15'></TD>\n";
	    $this->b .= "<TD ALIGN=CENTER><b>VITREOUS</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name16  SIZE=40 MAXLENGTH=$maxlength16 $ext VALUE='$defval16'></TD></TR>\n";
	  	
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name17  SIZE=40 MAXLENGTH=$maxlength17 $ext VALUE='$defval17'></TD>\n";
	    $this->b .= "<TD ALIGN=CENTER><b>HUMOR</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name18  SIZE=40 MAXLENGTH=$maxlength18 $ext VALUE='$defval18'></TD></TR>\n";
	  	
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name19  SIZE=40 MAXLENGTH=$maxlength19 $ext VALUE='$defval19'></TD>\n";
	    $this->b .= "<TD ALIGN=CENTER><b>PUNDUSKOPI</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name20  SIZE=40 MAXLENGTH=$maxlength20 $ext VALUE='$defval20'></TD></TR>\n";
  		
	    $this->b .= "<TR ALIGN='CENTER'height='20'>\n<TD COLSPAN='4' ALIGN=LEFT CLASS=FORM_SUBTITLE1>$header2</TD></TR>\n";
  		$this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name21  SIZE=40 MAXLENGTH=$maxlength21 $ext VALUE='$defval21'></TD>\n";
  		$this->b .= "<TD ALIGN=CENTER><b>VISUS</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name22  SIZE=40 MAXLENGTH=$maxlength22 $ext VALUE='$defval22'></TD></TR>\n";
	  	
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name23  SIZE=40 MAXLENGTH=$maxlength23 $ext VALUE='$defval23'></TD>\n";
	    $this->b .= "<TD ALIGN=CENTER><b>KOREKASI</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name24  SIZE=40 MAXLENGTH=$maxlength24 $ext VALUE='$defval24'></TD></TR>\n";
	  	
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name25  SIZE=40 MAXLENGTH=$maxlength25 $ext VALUE='$defval25'></TD>\n";
	    $this->b .= "<TD ALIGN=CENTER><b>KACAMATA</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name26  SIZE=40 MAXLENGTH=$maxlength26 $ext VALUE='$defval26'></TD></TR>\n";
	  	
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name27  SIZE=40 MAXLENGTH=$maxlength27 $ext VALUE='$defval27'></TD>\n";
	  	$this->b .= "<TD ALIGN=CENTER><b>APLANSI</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name28 SIZE=40 MAXLENGTH=$maxlength28 $ext VALUE='$defval28'></TD></TR>\n";
	    
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name29  SIZE=40 MAXLENGTH=$maxlength29 $ext VALUE='$defval29'></TD>\n";
	  	$this->b .= "<TD ALIGN=CENTER><b>TENOMETRI</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name30 SIZE=40 MAXLENGTH=$maxlength30 $ext VALUE='$defval30'></TD></TR>\n";
	    
	    $this->b .= "<TR ALIGN='CENTER'height='20'><TD><INPUT TYPE=TEXT NAME=$name31  SIZE=40 MAXLENGTH=$maxlength31 $ext VALUE='$defval31'></TD>\n";
	  	$this->b .= "<TD ALIGN=CENTER><b>ANEL</b></TD>\n";
	    $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name32  SIZE=40 MAXLENGTH=$maxlength32 $ext VALUE='$defval32'></TD></TR>\n";
	    $this->b .= "<TR ALIGN='CENTER' height='20'><TD></TD>\n";
	  	$this->b .= "<TD></TD>\n";
	    $this->b .= "<TD></TD></TR>\n";
		$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
     function text_4($header,$name, $capt, $size, $maxlength, $defval,$info, $name2, $capt2, $size2, $maxlength2, $defval2,
    			    $info2, $name3, $capt3, $size3, $maxlength3, $defval3,$info3, $name4, $capt4, $size4, $maxlength4,
    			    $defval4,$info4,$ext = "")
    {
        $this->b .= "<TABLE  BORDER=0 CELLSPACING=2 CELLPADDING=2 WIDTH=65%><TR>\n";
        $this->b .= "<TR>\n<TD COLSPAN='6' ALIGN=LEFT CLASS=FORM_SUBTITLE1>$header</TD></TR>\n";
        $this->b .= "<TD WIDTH=156 CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;$info</TD>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt2</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD WIDTH=150 CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext VALUE='$defval2'>&nbsp;$info2</TD></TR>\n";
        $this->b .= "<TR>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM>$capt3</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext VALUE='$defval3'>&nbsp;$info3</TD>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM>$capt4</TD>\n";
        $this->b .= "<TD CLASS=FORM >:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name4 SIZE=$size4 ".
                    "MAXLENGTH=$maxlength4 $ext VALUE='$defval4'>&nbsp;$info4</TD></TR>\n";
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_6i($header,$name, $capt, $size, $maxlength, $defval,$info, $name2, $capt2, $size2, $maxlength2, $defval2,
    				$info2,$name3, $capt3, $size3, $maxlength3, $defval3,
    				$info3,$name4, $capt4, $size4, $maxlength4, $defval4,
    				$info4,$name5, $capt5, $size5, $maxlength5, $defval5,
    				$info5,$name6, $capt6, $size6, $maxlength6, $defval6,
    				$info6,$ext = "")
    {
        $this->b .= "</TABLE><TABLE  BORDER=0 CELLSPACING=2 CELLPADDING=2 WIDTH=85%><TR>\n";
        $this->b .= "<TR>\n<TD COLSPAN='6' ALIGN=LEFT CLASS=FORM_SUBTITLE1>$header</TD></TR>\n";
        $this->b .= "<TD WIDTH=155 CLASS=FORM>$capt </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;$info</TD>\n";
                    
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt2 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext VALUE='$defval2'>&nbsp;$info2</TD></TR>\n";
        $this->b .= "<TR>\n";            
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt3 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext VALUE='$defval3'>&nbsp;$info3</TD>\n";
                    
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt4 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name4 SIZE=$size4 ".
                    "MAXLENGTH=$maxlength4 $ext VALUE='$defval4'>&nbsp;$info4</TD></TR>\n";
        $this->b .= "<TR>\n";            
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt5 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name5 SIZE=$size5 ".
                    "MAXLENGTH=$maxlength5 $ext VALUE='$defval5'>&nbsp;$info5</TD>\n";
                    
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt6 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name6 SIZE=$size6 ".
                    "MAXLENGTH=$maxlength6 $ext VALUE='$defval6'>&nbsp;$info6</TD></TR>\n";
    
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    
    function text_8i($name, $capt, $size, $maxlength, $defval,$info, $name2, $capt2, $size2, $maxlength2, $defval2,
    				$info2,$name3, $capt3, $size3, $maxlength3, $defval3,$info3,$name4, $capt4, $size4, $maxlength4, $defval4,
    				$info4,$name5, $capt5, $size5, $maxlength5, $defval5,$info5,$name6, $capt6, $size6, $maxlength6, $defval6,
    				$info6,$name7, $capt7, $size7, $maxlength7, $defval7,$info7,$name8, $capt8, $size8, $maxlength8, $defval8,
    				$info8,$ext = "")
    {
        $this->b .= "</TABLE><TABLE  BORDER=0 CELLSPACING=2 CELLPADDING=2 WIDTH=85%><TR>\n";
        $this->b .= "<TD WIDTH=156 CLASS=FORM>$capt </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;$info</TD>\n";
                    
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt2 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext VALUE='$defval2'>&nbsp;$info2</TD></TR>\n";
        $this->b .= "<TR>\n";            
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt3 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext VALUE='$defval3'>&nbsp;$info3</TD>\n";
                    
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt4 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name4 SIZE=$size4 ".
                    "MAXLENGTH=$maxlength4 $ext VALUE='$defval4'>&nbsp;$info4</TD></TR>\n";
        $this->b .= "<TR>\n";            
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt5 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name5 SIZE=$size5 ".
                    "MAXLENGTH=$maxlength5 $ext VALUE='$defval5'>&nbsp;$info5</TD>\n";
                    
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt6 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name6 SIZE=$size6 ".
                    "MAXLENGTH=$maxlength6 $ext VALUE='$defval6'>&nbsp;$info6</TD></TR>\n";
           
        $this->b .= "<TR>\n";            
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt7 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name7 SIZE=$size7 ".
                    "MAXLENGTH=$maxlength7 $ext VALUE='$defval7'>&nbsp;$info7</TD>\n";
                    
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt8 </TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name8 SIZE=$size8 ".
                    "MAXLENGTH=$maxlength8 $ext VALUE='$defval8'>&nbsp;$info8</TD></TR>\n";
    
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    
    function text_ginekologi($header,$name, $capt, $size, $maxlength, $defval,$info, $name2, $capt2, $size2, $maxlength2, $defval2,
    			    $info2, $name3, $capt3, $size3, $maxlength3, $defval3,$info3, $name4, $capt4, $size4, $maxlength4,
    			    $defval4,$info4,$name5, $capt5, $size5, $maxlength5, $defval5,$info5,$name6, $capt6, $size6, $maxlength6, $defval6,$info6,
    			    $name7, $capt7, $size7, $maxlength7, $defval7,$info7,$name8, $capt8, $size8, $maxlength8, $defval8,$info8,$ext = "")
    {
        $this->b .= "</TABLE><TABLE  BORDER=0 CELLSPACING=2 CELLPADDING=2 WIDTH=100%><TR>\n";
        $this->b .= "<TR>\n<TD COLSPAN='6' ALIGN=LEFT CLASS=FORM_SUBTITLE1>$header</TD></TR>\n";
  		$this->b .= "<TR><TD WIDTH=150 CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;$info</TD>\n";
                    
		$this->b .= "<TD WIDTH=150 CLASS=FORM>$capt2</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext VALUE='$defval2'>&nbsp;$info2</TD></TR>\n";
                    
    	$this->b .= "<TR><TD WIDTH=150 CLASS=FORM>$capt3</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext VALUE='$defval3'>&nbsp;$info3</TD>\n";
                    
		$this->b .= "<TD WIDTH=150 CLASS=FORM>$capt5</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name5 SIZE=$size5 ".
                    "MAXLENGTH=$maxlength5 $ext VALUE='$defval5'>&nbsp;$info5</TD></TR>\n";
  
    	$this->b .= "<TR><TD WIDTH=150 CLASS=FORM>$capt6</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name6 SIZE=$size6 ".
                    "MAXLENGTH=$maxlength6 $ext VALUE='$defval6'>&nbsp;$info6</TD>\n";
                    
		/*$this->b .= "<TD WIDTH=150 CLASS=FORM>$capt6</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name6 SIZE=$size6 ".
                    "MAXLENGTH=$maxlength6 $ext VALUE='$defval6'>&nbsp;$info6</TD></TR>\n";
                    
    	$this->b .= "<TR><TD WIDTH=150 CLASS=FORM>$capt7</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name7 SIZE=$size7 ".
                    "MAXLENGTH=$maxlength7 $ext VALUE='$defval7'>&nbsp;$info7</TD>\n";
                    
		$this->b .= "<TD WIDTH=150 CLASS=FORM>$capt8</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name8 SIZE=$size8 ".
                    "MAXLENGTH=$maxlength8 $ext VALUE='$defval8'>&nbsp;$info8</TD>\n";*/

        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_3($name, $capt, $size, $maxlength, $defval,$info, $name2, $capt2, $size2, $maxlength2, $defval2,$info2, $name3, $capt3, $size3, $maxlength3, $defval3,$info3,$ext = "")
    {
        $this->b .= "</TABLE><TABLE  BORDER=0 CELLSPACING=2 CELLPADDING=2 WIDTH=65%><TR>\n";
        //$this->b .= "<TR>\n<TD COLSPAN='6' ALIGN=LEFT CLASS=FORM_SUBTITLE1>$header</TD></TR>\n";
        $this->b .= "<TD WIDTH=157 CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;$info</TD>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM >$capt2</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext VALUE='$defval2'>&nbsp;$info2</TD></TR>\n";
        $this->b .= "<TR>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM>$capt3</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext VALUE='$defval3'>&nbsp;$info3</TD>\n";
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_2($name, $capt, $size, $maxlength, $defval,$info, $name2, $capt2, $size2, $maxlength2, $defval2,$info2,$ext = "")
    {
        $this->b .= "<TABLE  BORDER=0 CELLSPACING=2  WIDTH=55%><TR>\n";
        $this->b .= "<TD WIDTH=157 CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM ><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;$info</TD>\n";
        $this->b .= "<TD CLASS=FORM >$capt2</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext VALUE='$defval2'>&nbsp;$info2</TD></TR>\n";
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_gambar($header,$judul,$gbr,$name, $capt, $size, $maxlength,$defval,$ext = "",$name2, $capt2, $size2, $maxlength2,$defval2, $ext2 = "",$name3, $capt3, $size3, $maxlength3,$defval3, $ext3 = "",$name4, $capt4, $size4, $maxlength4,$defval4, $ext4 = "",$name5, $capt5, $size5, $maxlength5,$defval5, $ext5 = "")
    {
        $this->b .=	"</TABLE><TABLE WIDTH='61%' BORDER='0'><TR>";
        $this->b .= "<TR>\n<TD COLSPAN='4' ALIGN=LEFT CLASS=FORM_SUBTITLE1>$header</TD></TR>\n";
    	$this->b .= "<TR>\n<TD COLSPAN='1' ALIGN=CENTER CLASS=FORM_SUBTITLE1>$judul</TD></TR>\n";
        $this->b .=	"<TD WIDTH = '15%' ROWSPAN='5'><$gbr></TD>";
        $this->b .=	"<TD WIDTH = '30%' CLASS=FORM>$capt</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD></TR>\n";
        
        $this->b .= "<TR><TD>$capt2</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext2 VALUE='$defval2'></TD></TR>\n";
                    
        $this->b .= "<TR><TD>$capt3</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext3 VALUE='$defval3'></TD></TR>\n";
                    
        $this->b .= "<TR><TD>$capt4</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name4 SIZE=$size4 ".
                    "MAXLENGTH=$maxlength4 $ext4 VALUE='$defval4'></TD></TR>\n";
                    
        $this->b .= "<TR><TD>$capt5</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name5 SIZE=$size5 ".
                    "MAXLENGTH=$maxlength5 $ext5 VALUE='$defval5'></TD></TR>\n";
        
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_gambar2($header,$gbr,$gbr2,$name, $capt, $size, $maxlength,$defval,$ext = "",$name2, $capt2, $size2, $maxlength2,$defval2,
     					  $ext2 = "")
    {
        $this->b .=	"</TABLE><TABLE WIDTH='61%' BORDER='0'><TR>";
    	$this->b .= "<TR>\n<TD COLSPAN='4' ALIGN=LEFT CLASS=FORM_SUBTITLE1>$header</TD></TR>\n";
        $this->b .=	"<TD WIDTH = '15%' ROWSPAN='5'><$gbr></TD>";
        $this->b .=	"<TD WIDTH = '15%' ROWSPAN='5'><$gbr2></TD>";
        $this->b .=	"<TD WIDTH = '30%' CLASS=FORM>$capt</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD></TR>\n";
        
        $this->b .= "<TR><TD WIDTH = '12%' CLASS=FORM>$capt2</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext2 VALUE='$defval2'></TD></TR>\n";
                    
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_gambar3($judul,$gbr,$name, $capt, $size, $maxlength,$defval,$ext = "",$name2, $capt2, $size2, $maxlength2,$defval2, $ext2 = "",$name3, $capt3, $size3, $maxlength3,$defval3, $ext3 = "")
    {
        $this->b .=	"</TABLE><TABLE WIDTH='61%' BORDER='0'><TR>";
    	$this->b .= "<TR>\n<TD COLSPAN='1' ALIGN=CENTER CLASS=FORM_SUBTITLE1>$judul</TD></TR>\n";
        $this->b .=	"<TD WIDTH = '15%' ROWSPAN='5'><$gbr></TD>";
        $this->b .=	"<TD WIDTH = '30%' CLASS=FORM>$capt</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD></TR>\n";
        
        $this->b .= "<TR><TD WIDTH = '12%' CLASS=FORM>$capt2</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext2 VALUE='$defval2'></TD></TR>\n";
                    
        $this->b .= "<TR><TD WIDTH = '12%' CLASS=FORM>$capt3</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext3 VALUE='$defval3'></TD></TR>\n";
                    
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_gambar4($judul,$gbr,$name, $capt, $size, $maxlength,$defval,$ext = "",$name2, $capt2, $size2, $maxlength2,$defval2, $ext2 = "",$name3, $capt3, $size3, $maxlength3,$defval3, $ext3 = "",$name4, $capt4, $size4, $maxlength4,$defval4, $ext4 = "")
    {
        $this->b .=	"</TABLE><TABLE WIDTH='80%' BORDER='0'><TR>";
    	$this->b .= "<TR>\n<TD COLSPAN='1' CLASS=FORM_SUBTITLE1>$judul</TD></TR>\n";
        $this->b .=	"<TD WIDTH = '157' ROWSPAN='5'><$gbr></TD>";
        $this->b .=	"<TD CLASS=FORM>$capt</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD></TR>\n";
        
        $this->b .= "<TR><TD WIDTH = '12%' CLASS=FORM>$capt2</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext2 VALUE='$defval2'></TD></TR>\n";
                    
        $this->b .= "<TR><TD WIDTH = '12%' CLASS=FORM>$capt3</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name3 SIZE=$size3 ".
                    "MAXLENGTH=$maxlength3 $ext3 VALUE='$defval3'></TD></TR>\n";
                    
        $this->b .= "<TR><TD WIDTH = '12%' CLASS=FORM>$capt4</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD><INPUT TYPE=TEXT NAME=$name4 SIZE=$size4 ".
                    "MAXLENGTH=$maxlength4 $ext4 VALUE='$defval4'></TD></TR>\n";
                    
        $this->b .= "</TR></TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
    }
    
    function text_gigi($header1,$gbr1,$name, $capt, $size, $maxlength,$defval,$ext = "")
    {
        $this->b .=	"</TABLE><TABLE WIDTH='61%' BORDER='0'>";
    	$this->b .= "<TR>\n<TD COLSPAN='4'CLASS=FORM_SUBTITLE1>$header1</TD></TR>\n";
        $this->b .=	"<TR><TD COLSPAN='4'><$gbr1></TD></TR>";
        
        $this->b .=	"<TR><TD WIDTH = '160' CLASS=FORM>$capt</TD>";
        $this->b .= "<TD WIDTH = '7' CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD></TR>\n";
    
        $this->b .= "</TR></TABLE><BR><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2>\n";
      
    }
    
    function password($name, $capt, $size, $maxlength, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=password NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'></TD>\n";
        $this->b .= "</TR>\n\n";
    }

    function textarea($name, $capt, $rows, $cols, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><TEXTAREA $ext NAME=$name ROWS=$rows COLS=$cols>";
        $this->b .= "$defval</TEXTAREA></TD>\n";
        $this->b .= "</TR>\n\n";
    }
    

    function textAndButton($name, $capt, $size, $maxlength, $defval, $ext, $bCapt, $bExt)
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>".
                    "<INPUT TYPE=BUTTON $bExt VALUE='$bCapt'></TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    function textAndButton2($name, $capt, $size, $maxlength, $defval, $ext, $bCapt, $bExt,$bExt2)
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>".
                    "<INPUT TYPE=BUTTON $bExt $bExt2 VALUE='$bCapt'></TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    function textAndButton3($name, $capt,$size,$maxlength, $defval, $ext,$name2,$size2,$maxlength2, $defval2,$ext2, $bCapt, $bExt,$bExt2)
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE='$defval'>&nbsp;&nbsp;<INPUT TYPE=TEXT NAME=$name2 SIZE=$size2 ".
                    "MAXLENGTH=$maxlength2 $ext2 VALUE='$defval2'>".
                    "<INPUT TYPE=BUTTON $bExt $bExt2 VALUE='$bCapt'></TD>\n";
        $this->b .= "</TR>\n\n";
    }

    function hidden($name,$value)
    {
        $this->b .= "<INPUT TYPE=HIDDEN NAME=$name VALUE='$value'>\n";
    }

    function selectArray($name, $capt, $arr, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><SELECT $ext NAME=$name>\n";
        foreach($arr as $key => $val) {
            $this->b .= "<OPTION ";
            if($key == $defval) $this->b .= "SELECTED ";
            $this->b .= "VALUE='$key'>$val</OPTION>\n";
        }
        $this->b .= "</SELECT></TD>\n";
        $this->b .= "</TR>\n\n";
    }
	//tambahan select array najla 07012011 nambahin id biar pake javascriptnya gampang
	function selectArrayID($name, $capt, $arr, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><SELECT $ext ID=$name>\n";
        foreach($arr as $key => $val) {
            $this->b .= "<OPTION ";
            if($key == $defval) $this->b .= "SELECTED ";
            $this->b .= "VALUE='$key'>$val</OPTION>\n";
        }
        $this->b .= "</SELECT></TD>\n";
        $this->b .= "</TR>\n\n";
    }

    function selectSQL($name, $capt, $sql, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><SELECT $ext NAME=$name>\n";
        $r = @pg_query($this->PgConn, $sql);
        while($d = @pg_fetch_array($r)) {
            $this->b .= "<OPTION ";
            if($d[0] == $defval) $this->b .= "SELECTED ";
            $this->b .= "VALUE='$d[0]'>$d[1]</OPTION>\n";
        }
        @pg_free_result($r);
        $this->b .= "</SELECT></TD>\n";
        $this->b .= "</TR>\n\n";
    }
	function selectArray2($name, $capt, $arr, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><SELECT $ext NAME=$name>\n";
        foreach($arr as $key => $val) {
            $this->b .= "<OPTION ";
            if($key == $defval) $this->b .= "SELECTED ";
            $this->b .= "VALUE='$key'>$val</OPTION>\n";
        }
        $this->b .= "</SELECT></TD>\n";
        $this->b .= "</TR>\n\n";
    }

    function selectSQL2($name, $capt, $sql, $defval, $ext = "",$info = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><SELECT $ext NAME=$name>\n";
        $r = pg_query($this->PgConn, $sql);
        while($d = pg_fetch_array($r)) {
            $this->b .= "<OPTION ";
            if($d[0] == $defval) $this->b .= "SELECTED ";
            $this->b .= "VALUE='$d[0]'>$d[1]</OPTION>\n";
        }
        pg_free_result($r);
        $this->b .= "</SELECT> &nbsp; $info</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function selectMonthYear($name, $capt, $arr, $defval,$ext = "", $name2, $capt2, $sql, $defval2, $ext2 = "")
    {
        $this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "<TD CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><SELECT $ext NAME=$name>\n";
        foreach($arr as $key => $val) {
            $this->b .= "<OPTION ";
            if($key == $defval) $this->b .= "SELECTED ";
            $this->b .= "VALUE='$key'>$val</OPTION>\n";
        }
        $this->b .= "</SELECT></TD>\n";
        
        $this->b .= "<TD CLASS=FORM>$capt2</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><SELECT $ext2 NAME=$name2>\n";
        $r = pg_query($this->PgConn, $sql);
        while($d = pg_fetch_array($r)) {
            $this->b .= "<OPTION ";
            if($d[0] == $defval2) $this->b .= "SELECTED ";
            $this->b .= "VALUE='$d[0]'>$d[1]</OPTION>\n";
        }
        pg_free_result($r);
        $this->b .= "</SELECT></TD>\n";
        $this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    }
    
    function selectDate($name, $capt, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM width=300>\n";

        // day of month
        $this->b .= "<SELECT $ext NAME={$name}D>\n";
        for($n = 1; $n <= 31; $n++) {
            $this->b .= "<OPTION ";
            if($n == $defval["mday"]) $this->b .= "SELECTED ";
            $this->b .= "VALUE=$n>$n</OPTION>\n";
        }
        $this->b .= "</SELECT>\n";
        
        // month
        $m = array("Januari","Februari","Maret","April",
                    "Mei","Juni","Juli","Agustus","September",
                    "Oktober","November","Desember");
        $this->b .= "<SELECT $ext NAME={$name}M>\n";
        for($n = 0; $n <= 11; $n++) {
            $this->b .= "<OPTION ";
            if($n == $defval["mon"]-1) $this->b .= "SELECTED ";
            $this->b .= "VALUE=".($n+1).">$m[$n]</OPTION>\n";
        }
        $this->b .= "</SELECT>\n";
        
        // year
        $this->b .= "<INPUT TYPE=TEXT NAME={$name}Y SIZE=4 ".
                    "MAXLENGTH=4 $ext VALUE='".$defval["year"]."'>\n";

        $this->b .= "</TD>\n";
        $this->b .= "</TR>\n\n";
    }

	function selectDate_reg($name, $capt, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS='design10a'>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=design10 width=400>\n";

        // day of month
        $this->b .= "<SELECT CLASS='hari_d' $ext NAME={$name}D>\n";
        for($n = 1; $n <= 31; $n++) {
            $this->b .= "<OPTION ";
            if($n == $defval["mday"]) $this->b .= "SELECTED ";
            $this->b .= "VALUE=$n>$n</OPTION>\n";
        }
        $this->b .= "</SELECT>\n";
        
        // month
        //$m = array("Januari","Februari","Maret","April",
        //            "Mei","Juni","Juli","Agustus","September",
        //            "Oktober","November","Desember");
		$m = array("1","2","3","4",
                    "5","6","7","8","9",
                    "10","11","12");
		
        $this->b .= "<SELECT CLASS='hari_m' $ext NAME={$name}M>\n";
        for($n = 0; $n <= 11; $n++) {
            $this->b .= "<OPTION ";
            if($n == $defval["mon"]-1) $this->b .= "SELECTED ";
            $this->b .= "VALUE=".($n+1).">$m[$n]</OPTION>\n";
        }
        $this->b .= "</SELECT>\n";
        
        // year
        $this->b .= "<INPUT CLASS='hari_y' TYPE=TEXT NAME={$name}Y SIZE=4 ".
                    "MAXLENGTH=4 $ext VALUE='".$defval["year"]."'>\n";

        $this->b .= "</TD>\n";
        $this->b .= "</TR>\n\n";
    }
	
    function radio_btn($capt,$name1,$capt1,$val1,$name2,$capt2,$val2,$ext = "")
    {
    	$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2><TR>\n";
    	$this->b .= "<TR>\n";
        $this->b .= "<TD WIDTH=157 CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD WIDTH=102 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name1 VALUE='$val1' >$capt1</TD>\n";
        $this->b .= "<TD WIDTH=102 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name2 VALUE='$val2' >$capt2</TD>\n";
        $this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    
    }
    function checkBoxSQL($name, $capt, $sql, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM VALIGN=TOP>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM VALIGN=TOP>:</TD>\n";
        $this->b .= "<TD CLASS=FORM VALIGN=TOP>\n";
        $r = pg_query($this->PgConn, $sql);
        while($d = pg_fetch_array($r)) {
            $this->b .= "<INPUT TYPE=CHECKBOX NAME='{$name}[".$d[0]."]'";
            if($d[2]) $this->b .= " CHECKED";
            $this->b .= ">" . $d[1] . "<BR>\n";
        }
        pg_free_result($r);
        $this->b .= "</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
       
    function checkBoxSQL_menu($name, $capt, $sql, $ext)
    {
    /* Fungsi ini hanya untuk mengelompokan menu dari table rs99999 */
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='200' CLASS=FORM VALIGN=TOP>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM VALIGN=TOP>:</TD>\n";
        $this->b .= "<TD CLASS=FORM VALIGN=TOP>\n";
        
        $x = pg_query($this->PgConn, $ext);
        $i = 1 ; 
        $y[0]='x#x';
        while($d = pg_fetch_array($x)) { 
        	$y[$i] = $d[0] ;
        	$i++ ;
	}
        	        
        $r = pg_query($this->PgConn, $sql);
        while($d = pg_fetch_array($r)) {
        	if (Substr($d[0], 2, 2) == "00") {
        		$this->b .= "<BR><b>" . $d[1] . "</b><BR>\n";
        	}Else{
	            $this->b .= "<INPUT TYPE=CHECKBOX NAME='{$name}[".$d[0]."]'";
	            if (in_array($d[0], $y)) $this->b .= " CHECKED DISABLED ";
	            $this->b .= ">" . $d[1] . "<BR>\n";
	         }
        }
        pg_free_result($r);
        $this->b .= "</TD>\n";
        $this->b .= "</TR>\n\n";
    }  
    
    // june 18, 2007
    function checkbox($capt,$name1,$capt1,$defval1,$name2,$capt2,$defval2,$name3,$capt3,$defval3)
    {
    	$this->b .= "<TR></TABLE><hr noshade color=#999999 size=1><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=3>\n";
    	$this->b .= "<TR>\n";
        $this->b .= "<TD WIDTH=150 CLASS=FORM valign=$valign>$capt</TD><TR>\n";
        $this->b .= "<TD WIDTH=150 CLASS=FORM valign=$valign><INPUT TYPE=CHECKBOX NAME=$name1 VALUE=$defval1 CHECKED>$capt1</TD>\n";
        $this->b .= "<TD WIDTH=150 CLASS=FORM valign=$valign><INPUT TYPE=CHECKBOX NAME=$name2 VALUE=$defval2 CHECKED>$capt2</TD>\n";
        $this->b .= "<TD WIDTH=150 CLASS=FORM valign=$valign><INPUT TYPE=CHECKBOX NAME=$name3 VALUE=$defval3 CHECKED>$capt3</TD>\n";
        $this->b .= "</TR></TABLE><hr noshade width=95% color=#999999 size=1><TABLE CELLSPACING=2 CELLPADDING=3>\n\n";
    
    }
    
    function checkbox_1($name1,$check1,$val1,$capt1,$ext="")
    {
    	$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='50%'><TR>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM valign=$valign><INPUT $ext TYPE=CHECKBOX NAME=$name1 VALUE=$val1 $check1>$capt1</TD>\n";
        $this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    
    }
    function checkbox_2($name1,$val1,$capt1,$name2,$val2,$capt2)
    {
    	$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2 WIDTH='50%'><TR>\n";
    	$this->b .= "<TD WIDTH=100 CLASS=FORM valign=$valign><INPUT TYPE=CHECKBOX NAME=$name1 VALUE=$val1 >$capt1</TD>\n";
        $this->b .= "<TD WIDTH=100 CLASS=FORM valign=$valign><INPUT TYPE=CHECKBOX NAME=$name2 VALUE=$val2 >$capt2</TD>\n";
        $this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    
    }
    function checkbox_5($name,$val1,$check1,$capt1,$val2,$check2,$capt2,$val3,$check3,$capt3,$val4,$check4,$capt4,$val5,$check5,$capt5,$ext="")
    {
    	$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2 WIDTH='50%'><TR>\n";
    	$this->b .= "<TD CLASS=FORM valign=$valign><INPUT $ext TYPE=CHECKBOX NAME=$name VALUE=$val1 $check1>$capt1</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign><INPUT $ext TYPE=CHECKBOX NAME=$name VALUE=$val2 $check2>$capt2</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign><INPUT $ext TYPE=CHECKBOX NAME=$name VALUE=$val3 $check3>$capt3</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign><INPUT $ext TYPE=CHECKBOX NAME=$name VALUE=$val4 $check4>$capt4</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign><INPUT $ext TYPE=CHECKBOX NAME=$name VALUE=$val5 $check5>$capt5</TD>\n";
        $this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    
    }
    
    function checkbox3i($capt,$name,$capt1,$val1,$check1,$capt2,$val2,$check2,$capt3,$val3,$check3,$ext = "")
    {
    	$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2><TR>\n";
    	$this->b .= "<TD WIDTH=157 CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD WIDTH=157 CLASS=FORM valign=$valign><INPUT $ext TYPE=CHECKBOX NAME=$name VALUE=$val1 $check1>$capt1</TD>\n";
        $this->b .= "<TD WIDTH=157 CLASS=FORM valign=$valign><INPUT $ext TYPE=CHECKBOX NAME=$name VALUE=$val2 $check2>$capt2</TD>\n";
        $this->b .= "<TD WIDTH=157 CLASS=FORM valign=$valign><INPUT $ext TYPE=CHECKBOX NAME=$name VALUE=$val3 $check3>$capt3</TD>\n";
        $this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    
    }
    
    function checkbox2($capt,$name,$capt1,$val1,$check1,$capt2,$val2,$check2,$ext = "")
    {
    	$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2><TR>\n";
    	$this->b .= "<TD WIDTH=157 CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD WIDTH=157 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name VALUE=$val1 $check1>$capt1</TD>\n";
        $this->b .= "<TD WIDTH=157 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name VALUE=$val2 $check2>$capt2</TD>\n";
        $this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    
    }
    function checkbox3($capt,$name1,$capt1,$val1,$check1,$name2,$capt2,$val2,$check2,$name3,$capt3,$val3,$check3,$ext = "")
    {
    	$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0><TR>\n";
    	$this->b .= "<TD WIDTH=157 CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name1 VALUE=$val1 $check1>$capt1&nbsp;(0)</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name2 VALUE=$val2 $check2>$capt2&nbsp;(1)</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name3 VALUE=$val3 $check3>$capt3&nbsp;(2)</TD>\n";
        $this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    
    }
    function checkbox1($capt,$name,$capt1,$val1,$check1,$capt2,$val2,$check2,$capt3,$val3,$check3,$ext="")
    {
    	$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2><TR>\n";
    	$this->b .= "<TD WIDTH=150 CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD WIDTH=102 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name VALUE=$val1 $check1>$capt1</TD>\n";
        $this->b .= "<TD WIDTH=102 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name VALUE=$val2 $check2>$capt2</TD>\n";
        $this->b .= "<TD WIDTH=102 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name VALUE=$val3 $check3>$capt3</TD>\n";
        $this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    
    }
    function checkbox4($capt,$name,$capt1,$val1,$check1,$capt2,$val2,$check2,$capt3,$val3,$check3,$capt4,$val4,$check4,$ext = "")
    {
    	$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2><TR>\n";
    	$this->b .= "<TD WIDTH=150 CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD WIDTH=102 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name VALUE=$val1 $check1>$capt1</TD>\n";
        $this->b .= "<TD WIDTH=102 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name VALUE=$val2 $check2>$capt2</TD>\n";
        $this->b .= "<TD WIDTH=102 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name VALUE=$val3 $check3>$capt3</TD>\n";
        $this->b .= "<TD WIDTH=102 CLASS=FORM valign=$valign><INPUT $ext TYPE=RADIO NAME=$name VALUE=$val4 $check4>$capt4</TD>\n";
        $this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    
    }
    
    function gbr1($gbr)
    {
    	$this->b .= "</TABLE><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2 WIDTH='50%'><TR>\n";
    	$this->b .=	"<TD WIDTH = '15%' ROWSPAN='5'><$gbr></TD>";
    	$this->b .= "</TR></TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    }

	/*function textUmur($name, $capt, $size, $maxlength, $defval1,$defval2,$defval3, $ext = "")
    {
		eval("\$defval = \"$defval\";");
		$this->b .= "<TR>\n";
        //$this->b .= "<TD width='157' CLASS=FORM >$capt</TD>\n";
        $this->b .= "<TD width='200' class='design10a' >$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD class='design10'><INPUT TYPE=TEXT 
					NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE=\"$defval1\">
					<INPUT TYPE=TEXT 
					NAME=bulanlahir SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE=\"$defval2\">
					<INPUT TYPE=TEXT 
					NAME=harilahir SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE=\"$defval3\"></TD>\n";
        $this->b .= "</TR>\n\n";
	}*/
	
	function textUmur($name, $capt, $size, $maxlength, $defval1,$defval2,$defval3, $ext = "")
    {
		eval("\$defval = \"$defval\";");
		$this->b .= "<TR>\n";
        //$this->b .= "<TD width='157' CLASS=FORM >$capt</TD>\n";
        $this->b .= "<TD width='200' class='design10a' >$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD class='design10'><INPUT TYPE=TEXT 
					NAME=$name SIZE=$size ".
                    "MAXLENGTH=$maxlength $ext VALUE=\"$defval1\">Tahun</TD>\n";
        $this->b .= "</TR>\n\n";
	}
    function textUmurTahunBulanHari($name, $capt, $size, $maxlength, $defval, $ext = "")
    {
		$this->b .= "<TR>\n";
        $this->b .= "<TD width='200' class='design10a' >$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD class='design10'>
					<INPUT TYPE='TEXT' NAME='{$name['tahun']}' SIZE='{$size['tahun']}' MAXLENGTH='{$maxlength['tahun']}' {$ext['tahun']} VALUE=\"{$defval['tahun']}\">Tahun
					<INPUT TYPE='TEXT' NAME='{$name['bulan']}' SIZE='{$size['bulan']}' MAXLENGTH='{$maxlength['bulan']}' {$ext['bulan']} VALUE=\"{$defval['bulan']}\">Bulan
					<INPUT TYPE='TEXT' NAME='{$name['hari']}' SIZE='{$size['hari']}' MAXLENGTH='{$maxlength['hari']}' {$ext['hari']} VALUE=\"{$defval['hari']}\">Hari
					</TD>\n";
        $this->b .= "</TR>\n\n";
	}    
    function text_x3($header1,$name,$defval,$name2,$defval2,$name3,$defval3,$name4,$defval4,$name5,$defval5,$name6,$defval6,
    					$name7,$defval7,$name8,$defval8,$name9,$defval9,$name10,$defval10,$name11,$defval11,$name12,$defval12,
    					$name13,$defval13,$name14,$defval14,$name15,$defval15,$name16,$defval16,$ext = "")
    {
	    $this->b .= "</TABLE><TABLE BORDER=0 WIDTH='71%'><TR>\n";
  		$this->b .= "<tr><td colspan='4'>$judul</td></tr>";
  		$this->b .= "<TR>\n<TD COLSPAN='5'CLASS=FORM_SUBTITLE1>$header1</TD></TR>\n";
  		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE='19'".
                    "MAXLENGTH='20' $ext VALUE='$defval'></TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE='10'".
                    "MAXLENGTH='10' $ext VALUE='$defval2'></TD>\n";
    	$this->b .= "<td WIDTH='20'><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name3 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval3'></TD>\n";
    	$this->b .= "<td WIDTH='20'><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name4 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval4'></TD><TD>Cm</TD></TR>\n";
                    
	    $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name5 SIZE='19'".
                    "MAXLENGTH='20' $ext VALUE='$defval5'></TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name6 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval6'></TD>\n";
    	$this->b .= "<td WIDTH='20'><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name7 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval7'></TD>\n";
    	$this->b .= "<td WIDTH='20'><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name8 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval8'></TD><TD>Cm</TD></TR>\n";

	    $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name9 SIZE='19'".
                    "MAXLENGTH='20' $ext VALUE='$defval9'></TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name10 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval10'></TD>\n";
    	$this->b .= "<td><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name11 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval11'></TD>\n";
    	$this->b .= "<td><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name12 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval12'></TD><TD>Cm</TD></TR>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name13 SIZE='19'".
                    "MAXLENGTH='20' $ext VALUE='$defval13'></TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name14 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval14'></TD>\n";
    	$this->b .= "<td><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name15 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval15'></TD>\n";
    	$this->b .= "<td><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name16 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval16'></TD><TD>Cm</TD></TR>\n";
                    
        $this->b .= "</TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    }
    
    function text_x1($capt,$name,$defval,$name2,$defval2,$name3,$defval3,$ext = "")
    {
	    $this->b .= "</TABLE><TABLE BORDER=0 WIDTH='71%'><TR>\n";
  		$this->b .= "<tr><td colspan='4'>$judul</td></tr>";
  		$this->b .=	"<TD WIDTH = '30%' CLASS=FORM>$capt</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE='10'".
                    "MAXLENGTH='10' $ext VALUE='$defval'></TD>\n";
    	$this->b .= "<td WIDTH='20'><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval2'></TD>\n";
    	$this->b .= "<td WIDTH='20'><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name3 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval3'></TD><TD>Cm</TD></TR>\n";
                  
    	$this->b .= "</TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    }
    
    function text_x4($header1,$capt,$name,$defval,$name2,$defval2,$name3,$defval3,$capt2,$name4,$defval4,$name5,$defval5,
    				$name6,$defval6,$capt3,$name7,$defval7,$name8,$defval8,$name9,$defval9,$ext = "")
    {
	    $this->b .= "</TABLE><TABLE BORDER=0 WIDTH='71%'><TR>\n";
  		$this->b .= "<tr><td colspan='4'>$judul</td></tr>";
  		$this->b .= "<TR>\n<TD COLSPAN='5'CLASS=FORM_SUBTITLE1>$header1</TD></TR>\n";
  		$this->b .=	"<TD  CLASS=FORM>$capt</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name SIZE='10'".
                    "MAXLENGTH='10' $ext VALUE='$defval'></TD>\n";
    	$this->b .= "<td WIDTH='20'><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name2 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval2'></TD>\n";
    	$this->b .= "<td WIDTH='20'><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name3 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval3'></TD><TD>Cm</TD></TR>\n";
                    
	    $this->b .=	"<TR><TD WIDTH = '30%' CLASS=FORM>$capt2</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name4 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval4'></TD>\n";
    	$this->b .= "<td><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name5 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval5'></TD>\n";
    	$this->b .= "<td><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name6 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval6'></TD><TD>Cm</TD></TR>\n";

	    $this->b .=	"<TR><TD WIDTH = '30%' CLASS=FORM>$capt3</TD>";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name7 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval7'></TD>\n";
    	$this->b .= "<td><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name8 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval8'></TD>\n";
    	$this->b .= "<td><b>x</b></td>";
		$this->b .= "<TD CLASS=FORM><INPUT TYPE=TEXT NAME=$name9 SIZE='10' ".
                    "MAXLENGTH='10' $ext VALUE='$defval9'></TD><TD>Cm</TD></TR>\n";
                    
        $this->b .= "</TABLE><TABLE CELLSPACING=2 CELLPADDING=2>\n\n";
    }
    
    function rotext($capt, $defval)
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' VALIGN=TOP CLASS=FORM><NOBR><b>$capt</b></NOBR></TD>\n";
        $this->b .= "<TD VALIGN=TOP CLASS=FORM>:</TD>\n";
        $this->b .= "<TD VALIGN=TOP CLASS=FORM ALIGN=JUSTIFY>$defval</TD>\n";
        $this->b .= "</TR>\n\n";
    }

    function info($text,$text2)
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width '157' CLASS=FORM>$text</TD>\n";
        $this->b .= "<TD VALIGN=TOP CLASS=FORM>&nbsp;</TD>\n";
        $this->b .= "<TD VALIGN=TOP CLASS=FORM>$text2</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    function submit($capt, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM COLSPAN=2>&nbsp;</TD>\n";
        $this->b .= "<TD width='157' CLASS=FORM><INPUT $ext TYPE=SUBMIT ".
                    "VALUE='$capt'></TD>\n";
        $this->b .= "</TR>\n\n";
    }

    function submitAndCancel($capt, $ext = "", $cancelval, $cancelhref, $cancelext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM COLSPAN=2>&nbsp;</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT $ext TYPE=SUBMIT ".
                    "VALUE='$capt'>&nbsp;".
                    "<INPUT $cancelext TYPE=BUTTON VALUE=\"$cancelval\" ".
                    "OnClick=\"$cancelhref\"></TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    function execute()
    {
        $this->b .= "</FORM>\n";
        $this->b .= "</TABLE>\n";
        echo $this->b;
    }

}


class ReadOnlyForm
{

    var $b;

    function ReadOnlyForm()
    {
        $this->b .= "<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2 >\n";
    }

    function title($capt,$align="LEFT")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM_TITLE ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
     function title1($capt,$align="LEFT")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM_SUBTITLE1 ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    function subtitle($capt,$align="LEFT")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM_SUBTITLE ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    function textdoc($name, $capt, $rows, $cols, $defval, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM><TEXTAREA $ext NAME=$name ROWS=$rows COLS='100'>";
        $this->b .= "$defval</TEXTAREA></TD>\n";
        $this->b .= "</TR>\n\n";
    }

    function text($capt, $defval, $valign="top")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign>$defval</TD>\n";
        $this->b .= "</TR>\n\n";
    }
     
    function info($text,$text2)
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD VALIGN=TOP CLASS=FORM>$text</TD>\n";
        $this->b .= "<TD VALIGN=TOP CLASS=FORM>&nbsp;</TD>\n";
        $this->b .= "<TD VALIGN=TOP CLASS=FORM>$text2</TD>\n";
        $this->b .= "</TR>\n\n";
    }

    function execute()
    {
        $this->b .= "</TABLE>\n";
        echo $this->b;
    }
}

//added hery ...june 1,2007
class ReadOnlyForm2
{

    var $b;

    function ReadOnlyForm2()
    {
        $this->b .= "<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2 width='100%'>\n";
    }

    function title($capt,$align="LEFT")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM_TITLE ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
	function title1($capt,$align="LEFT")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM_SUBTITLE1 ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function subtitle($capt,$align="LEFT")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD width='157' CLASS=FORM_SUBTITLE ALIGN=$align COLSPAN=3>$capt</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    function text2i($capt, $defval,$info1,$capt2, $defval2,$info2, $valign="top")
    {
        $this->b .= "<TR><TD colspan=3><TABLE border=0 WIDTH='100%'  CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "	<TD WIDTH='100' CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM WIDTH='13%' valign=$valign>$defval $info1</TD>\n";
        
        $this->b .= "	<TD WIDTH='100' CLASS=FORM valign=$valign>$capt2</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM WIDTH='157' valign=$valign>$defval2 $info2</TD>\n";
        $this->b .= "	</TR></TABLE>\n\n";
        $this->b .= "</TD></TR>\n\n";
    }
    
    function text($capt, $defval, $valign="top",$height="")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD  WIDTH='150' CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "<TD WIDTH='2' CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "<TD $height CLASS=FORM valign=$valign>$defval</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function hr()
    {
    	$this->b .="<TR><td colspan=3><hr noshade color=#999999 size=1>\n";
    	$this->b .= "</TR>\n\n";
    }
    // june 18, 2007
    function checkbox1($capt,$capt1,$defval1,$valign="top")
    {
    	$this->b .= "<TR><TD colspan=3><TABLE border=0 WIDTH='100%'  CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "	<TD WIDTH=50 CLASS=FORM valign=$valign>$capt</TD>\n";
    	$this->b .= "	<TD CLASS=FORM valign=$valign><INPUT  TYPE=CHECKBOX NAME=name VALUE=$defval1 CHECKED>$capt1</TD>\n";
        $this->b .= "	</TR></TABLE>\n";
        $this->b .= "</TD></TR>\n";
    }
    function checkbox2($capt,$capt1,$defval1,$capt2,$defval2,$valign="top")
    {
    	$this->b .= "<TR><TD colspan=3><TABLE border=0 WIDTH='100%'  CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "	<TD WIDTH=1 CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "	<TD WIDTH=200 CLASS=FORM valign=$valign><INPUT  TYPE=CHECKBOX NAME=name VALUE=$defval1 CHECKED>$capt1</TD>\n";
        $this->b .= "	<TD WIDTH=200 CLASS=FORM valign=$valign><INPUT  TYPE=CHECKBOX NAME=name VALUE=$defval2 CHECKED>$capt2</TD>\n";
        $this->b .= "	</TR></TABLE>\n";
        $this->b .= "</TD></TR>\n";
    }
    
    function checkbox3($capt,$defval,$capt1,$defval1,$capt2,$defval2,$capt3,$defval3,$valign="top")
    {
    	$this->b .= "<TR><TD colspan=3><TABLE border=0 WIDTH='100%'  CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "	<TD  WIDTH=150 CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign >$defval</TD>\n";
        //$this->b .= "	</TR><TR>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign><INPUT  TYPE=CHECKBOX NAME=name VALUE=$defval1 CHECKED>$capt1</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign><INPUT  TYPE=CHECKBOX NAME=name VALUE=$defval2 CHECKED>$capt2</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign><INPUT TYPE=CHECKBOX NAME=name VALUE=$defval3 CHECKED>$capt3</TD>\n";
        $this->b .= "	</TR></TABLE>\n";
        $this->b .= "</TD></TR>\n";
    
    }
    
    function text_u($capt, $defval, $valign="top",$t2=":")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD WIDTH='25%' CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "<TD WIDTH='2%' CLASS=FORM valign=$valign>$t2</TD>\n";
        $this->b .= "<TD CLASS=FORM2 valign=$valign>$defval</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function text_tmp_tglLahir($capt, $defval, $defval2, $valign="top")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD WIDTH='150' CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "<TD CLASS=FORM valign=$valign>$defval , $defval2</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    
    function text2($capt1, $defval1,$capt2, $defval2, $valign="top")
    {
        $this->b .= "<TR><TD colspan=3><TABLE border=0 WIDTH='100%'  CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "	<TD WIDTH='15%' CLASS=FORM valign=$valign>$capt1</TD>\n";
        $this->b .= "	<TD WIDTH='2%' CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD WIDTH='33%' CLASS=FORM valign=$valign>$defval1</TD>\n";
        $this->b .= "	<TD WIDTH='15%' CLASS=FORM valign=$valign>$capt2</TD>\n";
        $this->b .= "	<TD WIDTH='2%' CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD WIDTH='33%' CLASS=FORM valign=$valign>$defval2</TD>\n";
        $this->b .= "	</TR></TABLE>\n";
        $this->b .= "</TD></TR>\n";
    }
    function text2b($capt1, $defval1,$capt2, $defval2, $valign="top")
    {
        $this->b .= "<TR><TD colspan=3><TABLE border=0 WIDTH='100%'  CELLSPACING=1 CELLPADDING=0><TR>\n";
        $this->b .= "	<TD WIDTH='15%' CLASS=FORM valign=$valign>$capt1</TD>\n";
        $this->b .= "	<TD WIDTH='2%' CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD WIDTH='33%' CLASS=FORM valign=$valign>$defval1</TD>\n";
        $this->b .= "	<TD WIDTH='15%' CLASS=FORM valign=$valign>$capt2</TD>\n";
        $this->b .= "	<TD WIDTH='2%' CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD WIDTH='33%' CLASS=FORM valign=$valign>$defval2</TD>\n";
        $this->b .= "	</TR></TABLE>\n";
        $this->b .= "</TD></TR>\n";
    }
    
    function text3($capt1, $defval1,$capt2, $defval2,$capt3, $defval3, $valign="top")
    {
        $this->b .= "<TR><TD colspan=3><TABLE border=0 WIDTH='100%'  CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "	<TD WIDTH='15%' CLASS=FORM valign=$valign>$capt1</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD WIDTH='15%' CLASS=FORM valign=$valign>$defval1</TD>\n";
        $this->b .= "	<TD WIDTH='15%' CLASS=FORM valign=$valign>$capt2</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD WIDTH='15%' CLASS=FORM valign=$valign>$defval2</TD>\n";
        $this->b .= "	<TD WIDTH='15%' CLASS=FORM valign=$valign>$capt3</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD WIDTH='15%' CLASS=FORM valign=$valign>$defval3</TD>\n";
        $this->b .= "	</TR></TABLE><hr noshade color=#999999 size=1>\n";
        $this->b .= "</TD></TR>\n";
    }
	
    function text3b($capt1, $defval1,$capt2, $defval2,$capt3, $defval3, $valign="top")
    {
        $this->b .= "<TR><td colspan=3><TABLE border=0 WIDTH='100%'  CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>$capt1</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD WIDTH='25%' CLASS=FORM valign=$valign>$defval1</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>$capt2</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD WIDTH='25%' CLASS=FORM valign=$valign>$defval2</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>$capt3</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD WIDTH='25%' CLASS=FORM valign=$valign>$defval3</TD>\n";
        $this->b .= "	</TR></table>\n";
        $this->b .= "</Td></TR>\n\n";
    }
    
    function text4x($capt, $defval1, $defval2, $defval3,$satuan, $valign="top")
    {
        $this->b .= "<TR><TD colspan=3><TABLE border=0 WIDTH='100%'  CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "	<TD WIDTH='150' CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>$defval1</TD>\n";
       	$this->b .= "	<TD CLASS=FORM valign=$valign>x</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>$defval2</TD>\n";
       	$this->b .= "	<TD CLASS=FORM valign=$valign>x</TD>\n";
       	$this->b .= "	<TD CLASS=FORM valign=$valign>$defval3</TD>\n";
       	$this->b .= "	<TD CLASS=FORM valign=$valign>$satuan</TD>\n";
        $this->b .= "	</TR></TABLE>\n";
        $this->b .= "</TD></TR>\n\n";
    }
    
    function text4($capt, $defval,$capt2, $defval2,$capt3, $defval3,$capt4, $defval4, $valign="top")
    {
        $this->b .= "<TR><TD colspan=3><hr noshade color=#999999 size=1><TABLE border=0 WIDTH='100%'  CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "	<TD WIDTH='12%' CLASS=FORM valign=$valign>$capt</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM WIDTH='13%' valign=$valign>$defval</TD>\n";
        
        $this->b .= "	<TD WIDTH='11%' CLASS=FORM valign=$valign>$capt2</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM WIDTH='13%' valign=$valign>$defval2</TD>\n";
        
        //$this->b .= "<TR>\n";
        $this->b .= "	<TD WIDTH='10%' CLASS=FORM valign=$valign>$capt3</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM WIDTH='13%' valign=$valign>$defval3</TD>\n";
        
        $this->b .= "	<TD WIDTH='10%' CLASS=FORM valign=$valign>$capt4</TD>\n"; 
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM WIDTH='13%' valign=$valign>$defval4</TD>\n";
        $this->b .= "	</TR></TABLE><hr noshade color=#999999 size=1>\n";
        $this->b .= "</TD></TR>\n\n";
    }
    function text4_darah($capt,$capt1, $defval1,$capt2, $defval2,$capt3, $defval3,$capt4, $defval4, $valign="top",$t2=":")
    {
        $this->b .= "<TR><TD colspan=3><hr noshade color=#999999 size=1><TABLE border=0 WIDTH='100%'  CELLSPACING=2 CELLPADDING=2><TR>\n";
        $this->b .= "	<TD width=15%>$capt</TD>\n";
        $this->b .= "	<TD WIDTH='5%' CLASS=FORM  valign=$valign>$capt1</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM WIDTH='13%' valign=$valign>$defval1</TD>\n";
        
        $this->b .= "	<TD WIDTH='10%' CLASS=FORM valign=$valign>$capt2</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM WIDTH='13%' valign=$valign>$defval2</TD>\n";
        
        //$this->b .= "<TR>\n";
        $this->b .= "	<TD WIDTH='10%' CLASS=FORM valign=$valign>$capt3</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM WIDTH='13%' valign=$valign>$defval3</TD>\n";
        
        $this->b .= "	<TD WIDTH='5%' CLASS=FORM valign=$valign>$capt4</TD>\n"; 
        $this->b .= "	<TD CLASS=FORM valign=$valign>$t2</TD>\n";
        $this->b .= "	<TD CLASS=FORM WIDTH='13%' valign=$valign>$defval4</TD>\n";
        $this->b .= "	</TR></TABLE><hr noshade color=#999999 size=1>\n";
        $this->b .= "</TD></TR>\n\n";
    }
    function text_gambar($header,$judul,$gbr,$capt,$defval, $capt2, $defval2,$capt3,$defval3, $capt4, $defval4,$capt5,$defval5, $valign)
    {
        $this->b .= "<TR><TD colspan=3><hr noshade color=#999999 size=1><TABLE border=0 WIDTH='61%'  CELLSPACING=2 CELLPADDING=2>\n";
    	$this->b .= "	<TR><TD COLSPAN='4' ALIGN=LEFT CLASS=FORM>$header</TD></TR>\n";
    	$this->b .= "	<TR><TD COLSPAN='1' ALIGN=CENTER CLASS=FORM_SUBTITLE1>$judul</TD></TR>\n";
        $this->b .=	"	<TR><TD WIDTH = '15%' ROWSPAN='5'><$gbr></TD>";
        $this->b .=	"	<TD WIDTH = '25%' CLASS=FORM>$capt</TD>";
        $this->b .= "	<TD WIDTH = 3 CLASS=FORM>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign >$defval</TD></TR>\n";
        
        $this->b .= "	<TR><TD>$capt2</TD>";
        $this->b .= "	<TD CLASS=FORM>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign >$defval2</TD></TR>\n";
                    
        $this->b .= "	<TR><TD>$capt3</TD>";
        $this->b .= "	<TD CLASS=FORM>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM align=$valign >$defval3</TD></TR>\n";
                    
        $this->b .= "	<TR><TD>$capt4</TD>";
        $this->b .= "	<TD CLASS=FORM>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign >$defval4</TD></TR>\n";
                    
        $this->b .= "	<TR><TD>$capt5</TD>";
        $this->b .= "	<TD CLASS=FORM>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign >$defval5</TD></TR>\n";        
        $this->b .= "	</TR></TABLE>\n";
        $this->b .= "</TD></TR>\n";
    }
    function text_gambar3($judul,$gbr,$capt,$defval, $capt2, $defval2,$capt3,$defval3,$valign)
    {
        $this->b .= "<TR><TD colspan=3><hr noshade color=#999999 size=1><TABLE border=0 WIDTH='61%'  CELLSPACING=2 CELLPADDING=2>\n";
    	$this->b .= "	<TR><TD COLSPAN='1' ALIGN=CENTER CLASS=FORM_SUBTITLE1>$judul</TD></TR>\n";
        $this->b .=	"	<TR><TD WIDTH = '15%' ROWSPAN='5'><$gbr></TD>";
        $this->b .=	"	<TD WIDTH = '25%' CLASS=FORM>$capt</TD>";
        $this->b .= "	<TD WIDTH = 3 CLASS=FORM>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM valign=$valign >$defval</TD></TR>\n";
        
        $this->b .= "	<TR><TD WIDTH = '12%' CLASS=FORM>$capt2</TD>";
        $this->b .= "	<TD CLASS=FORM>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM align=$valign >$defval2</TD></TR>\n";
                    
        $this->b .= "	<TR><TD WIDTH = '12%' CLASS=FORM>$capt3</TD>";
        $this->b .= "	<TD CLASS=FORM>:</TD>\n";
        $this->b .= "	<TD CLASS=FORM align=$valign >$defval3</TD></TR>\n";                    
        $this->b .= "	</TR></TABLE>\n";
        $this->b .= "</TD></TR>\n";
    }
    
    function text_mata($judul,$name_gbr1,$name_gbr2,$gbr1,$gbr2,$capt0,$defval_L0,$defval_R0,$capt1,$defval_L1,$defval_R1,$capt2,$defval_L2,$defval_R2,$capt3,$defval_L3,$defval_R3,$capt4,$defval_L4,$defval_R4,
    					$capt5,$defval_L5,$defval_R5,$capt6,$defval_L6,$defval_R6,$capt7,$defval_L7,$defval_R7,$capt8,$defval_L8,$defval_R8,$capt9,$defval_L9,$defval_R9,$capt10,$defval_L10,$defval_R10,$valign)
    {
    	$this->b .= "<TR><TD colspan=3><hr noshade color=#999999 size=1><TABLE border=0 WIDTH='61%' align='center' CELLSPACING=2 CELLPADDING=2>\n";
    	$this->b .=	"	<TR><TD ALIGN='CENTER' CLASS=FORM_SUBTITLE1><B>$name_gbr1<B></TD>";
   	 	$this->b .=	"	<TD ROWSPAN = 2 WIDTH=200 ALIGN='CENTER'><b>$judul<b></TD>";
    	$this->b .=	"	<TD ALIGN='CENTER' CLASS=FORM_SUBTITLE1><B>$name_gbr2<B></TD></TR>\n";
  		$this->b .=	"	<TR><TD ALIGN='CENTER'>$gbr1</TD>";
    	$this->b .=	"	<TD ALIGN='CENTER'>$gbr2</TD></TR>\n";	
    	
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L0</TD><TD ALIGN=CENTER>$capt0</TD><TD CLASS=FORM align=$valign >$defval_R0</TD></TR>\n";
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L1</TD><TD ALIGN=CENTER>$capt1</TD><TD CLASS=FORM align=$valign >$defval_R1</TD></TR>\n";
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L2</TD><TD ALIGN=CENTER>$capt2</TD><TD CLASS=FORM align=$valign >$defval_R2</TD></TR>\n";
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L3</TD><TD ALIGN=CENTER>$capt3</TD><TD CLASS=FORM align=$valign >$defval_R3</TD></TR>\n";
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L4</TD><TD ALIGN=CENTER>$capt4</TD><TD CLASS=FORM align=$valign >$defval_R4</TD></TR>\n";
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L5</TD><TD ALIGN=CENTER>$capt5</TD><TD CLASS=FORM align=$valign >$defval_R5</TD></TR>\n";
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L6</TD><TD ALIGN=CENTER>$capt6</TD><TD CLASS=FORM align=$valign >$defval_R6</TD></TR>\n";
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L7</TD><TD ALIGN=CENTER>$capt7</TD><TD CLASS=FORM align=$valign >$defval_R7</TD></TR>\n";
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L8</TD><TD ALIGN=CENTER>$capt8</TD><TD CLASS=FORM align=$valign >$defval_R8</TD></TR>\n";
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L9</TD><TD ALIGN=CENTER>$capt9</TD><TD CLASS=FORM align=$valign >$defval_R9</TD></TR>\n";
    	$this->b .= "	<TR><TD CLASS=FORM align=$valign >$defval_L10</TD><TD ALIGN=CENTER>$capt10</TD><TD CLASS=FORM align=$valign >$defval_R10</TD></TR>\n";    	    	
		$this->b .= "	</TR></TABLE><hr noshade color=#999999 size=1>\n";
        $this->b .= "</TD></TR>\n";
    }
    function info($text,$text2="")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD WIDTH='150' VALIGN=TOP CLASS=FORM>$text</TD>\n";
        $this->b .= "<TD VALIGN=TOP CLASS=FORM>&nbsp;</TD>\n";
        $this->b .= "<TD VALIGN=TOP CLASS=FORM>$text2</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function info4($text)
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD COLSPAN='4' VALIGN=TOP CLASS=FORM>$text</TD>\n";
        $this->b .= "</TR>\n\n";
    }
    function file($name, $capt, $size, $ext = "")
    {
        $this->b .= "<TR>\n";
        $this->b .= "<TD CLASS=FORM><b>$capt</b></TD>\n";
        $this->b .= "<TD CLASS=FORM>:</TD>\n";
        $this->b .= "<TD CLASS=FORM><INPUT TYPE=FILE NAME=$name SIZE=$size></TD>\n";
        $this->b .= "</TR>\n\n";
    }


    function execute()
    {
        $this->b .= "</TABLE>\n";
        echo $this->b;
    }
}

?>
