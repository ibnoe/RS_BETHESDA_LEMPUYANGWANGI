 <?	
  
 $PID = "kunjungan_rawat_jalan";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	  
		
	if(!$GLOBALS['print']){
		title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > KEGIATAN KEBIDANAN DAN PERINATOLOGI");
	}else {
		title_print("<img src='icon/medical-record.gif' align='absmiddle' > KEGIATAN KEBIDANAN DAN PERINATOLOGI");
	}
	
  

?>

<table CLASS=TBL_BORDER WIDTH='50%' BORDER=0 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="2">No</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Jenis Kegiatan</td>
        <td class="TBL_HEAD" align="center" colspan="2">Rujukan</td>
        <td class="TBL_HEAD" align="center" colspan="2">Non Rujukan</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Dirujuk Keatas</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">Jml</td>
        <td class="TBL_HEAD" align="center">Mati</td>
        <td class="TBL_HEAD" align="center">Jml</td>
        <td class="TBL_HEAD" align="center">Mati</td>
    </tr>
    <tr>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
    </tr>
</table>
