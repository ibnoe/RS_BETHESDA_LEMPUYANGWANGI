
 <?	
  
 $PID = "pelayanan_rawat_inap";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	 
require_once("lib/setting.php"); 
subtitle_print("Laporan Sewa Linen");
subtitle_print("Bulan : Juli");
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

<table CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
  <tr>
    <td class="TBL_HEAD" align="center" >No.</td>
    <td class="TBL_HEAD" align="center" >Jenis Linen</td>
    <td class="TBL_HEAD" align="center" >Jumlah Peminjaman Triwulan</td>
    <td class="TBL_HEAD" align="center" >Lama Peminjaman</td>
    <td class="TBL_HEAD" align="center" >Total Pendapatan</td>
    
  </tr>
  
  

  	
  <tr>
    <td class="TBL_BODY" align="center">1</td>
    <td class="TBL_BODY" align="left">Seprai</td>
    <td class="TBL_BODY" align="center">5 Kali</td>
    <td class="TBL_BODY" align="center">20 Hari</td>
    <td class="TBL_BODY" align="center">Rp. 100.000</td>
   
  </tr>
  
  <tr>
    <td class="TBL_BODY" align="center">2</td>
    <td class="TBL_BODY" align="left">Bed Cover</td>
    <td class="TBL_BODY" align="center">15 Kali</td>
    <td class="TBL_BODY" align="center">100 Hari</td>
    <td class="TBL_BODY" align="center">Rp. 400.000</td>
  </tr>
  
  <tr>
  	<td class="TBL_HEAD" align="center" colspan="4">TOTAL</td>
	<td class="TBL_HEAD" align="center">Rp. 500.000</td>
  </tr>
  
  
</table>
<p>&nbsp;</p>	