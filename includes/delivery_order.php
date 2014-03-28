
 <?	
  
 $PID = "pelayanan_rawat_inap";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	 
require_once("lib/setting.php"); 
subtitle_print("Purchase Order");
subtitle_print("Instalasi Gizi");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);
		
	if(!$GLOBALS['print']){
        title_print(""); 
		title_excel("pelayanan_rawat_inap");
		edit_laporan("input_pelayanan_rawat_inap");	
		
	}else {
		
	}

$SQL = "select * from rl100002 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}    
	
 

?>
<div align="right">
<table>
	<tr>
		<td>No DO</td>
		<td>:</td>
		<td colspan="2">ID0101</td>
	</tr>
	<tr>
		<td>No PO</td>
		<td>:</td>
		<td>IG0101</td>
		<td><input type="button" value="cari"></td>
	</tr>
	<tr>
		<td>Tanggal</td>
		<td>:</td>
		<td colspan="2">27-Agustus-2010</td>
	</tr>
</table>
</div>

<table CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
  <tr>
    <td class="TBL_HEAD" align="center" >No.</td>
    <td class="TBL_HEAD" align="center" >Nama Bahan</td>
    <td class="TBL_HEAD" align="center" >Harga</td>
    <td class="TBL_HEAD" align="center" >Jumlah</td>
    <td class="TBL_HEAD" align="center" >Total</td>
	<td class="TBL_HEAD" align="center" >Status</td>
    
  </tr>
  
  

  	
  <tr>
    <td class="TBL_BODY" align="center">1</td>
    <td class="TBL_BODY" align="center">beras</td>
    <td class="TBL_BODY" align="center">Rp. 7.000</td>
    <td class="TBL_BODY" align="center">5</td>
    <td class="TBL_BODY" align="center">Rp. 35.000</td>
	 <td class="TBL_BODY" align="center"><input type="checkbox" value="ada">Ada</td>
   
  </tr>
  
 
  <tr>
  	<td class="TBL_HEAD" align="center" colspan="4">TOTAL</td>
	<td class="TBL_HEAD" align="center" colspan="2">Rp. 35.000</td>
  </tr>
  
  
</table>
<div align="right">
<input type="submit" value="Cetak" onClick="window.location='http://localhost/rumahsakit_br/includes/print_po.php'">
</div>
<p>&nbsp;</p>	