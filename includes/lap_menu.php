
 <?	
  
 $PID = "pelayanan_rawat_inap";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	 
require_once("lib/setting.php"); 
subtitle_print("Standard Resep Menu");
subtitle_print(" </br>");
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
  <tr >
    <td class="TBL_HEAD" align="center" >No.</td>
    <td class="TBL_HEAD" align="center" >ID menu</td>
    <td class="TBL_HEAD" align="center" >Nama Menu</td>
    <td class="TBL_HEAD" align="center" >Bahan</td>
    
    
  </tr>
  
  

  	
  <tr height="50">
    <td class="TBL_BODY" align="center">1</td>
    <td class="TBL_BODY" align="center">K001</td>
    <td class="TBL_BODY" align="center">Bubur Kacang Hijau</td>
    <td class="TBL_BODY" align="left">    * 200 gram kacang hijau, kupas, rendam 1 jam
</br>    * 2 potong gula merah (± 100 gram)
    * 100 gram gula pasir</br>
    * 1 Liter air</br>
    * 3 lembar daun pandan</br>
    * 50 gram sagu tani + 50 ml air, aduk rata</br>
</td>
    
   
  </tr>
  
  <tr height="50">
    <td class="TBL_BODY" align="center">2</td>
    <td class="TBL_BODY" align="center">P001</td>
    <td class="TBL_BODY" align="center">Kering Tempe</td>
    <td class="TBL_BODY" align="left">   * 300 gram tempe, iris tipis 1/2 cm x 3 cm, goreng kering</br>
    * 3 buah cabai merah, iris tipis, goreng, tiriskan</br>
    * 1 sendok makan bawang merah goreng</br>
    * 1 lembar daun salam</br>
    * 1 siung bawang putih</br>
    * 1/2 cm lengkuas</br>
    * 1 sendok teh asam</br>
    * 1 sendok teh garam</br>
    * 75 gram gula merah</br>
    * 50 cc air</td>
  
  </tr>
  
  
  
  
</table>
<p>&nbsp;</p>	