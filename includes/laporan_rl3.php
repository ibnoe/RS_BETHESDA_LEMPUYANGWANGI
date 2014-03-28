<?	  
$PID = "laporan_rl3";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA DASAR RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL3");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 	  
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("laporan_rl3");
        edit_laporan("input_rl3");
	}else {
	
			
	}
	
	$SQL = "select * from rl300003 order by urut";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}    
 
?>
<table align="center">
	<td><table>
			<tr>
				<td>1.</td>
				<td>NomorKodeRS</td>
				<td>:</td>
				<td >
					<table border="1" cellpadding="2" cellspacing="1" class="TBL_BORDER">
						<td>1</td> <td>2</td> <td>3</td> <td>4</td> 
						<td>5</td> <td>6</td> <td>7</td> <td>8</td>
					</table>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>2.</td>
				<td>Nama Rumah Sakit</td>
				<td>:</td>
				<td>
						<table border="1" cellpadding="2" cellspacing="1" class="TBL_BORDER">
						<td>X</td> <td>Y</td> <td>Z</td> <td>&nbsp; &nbsp;</td> 
						<td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td>
						<td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td> 
						<td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td>
						</table>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
				<td> 
						<table border="1" cellpadding="2" cellspacing="1" class="TBL_BORDER">
						<td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td> 
						<td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td> 
						<td>&nbsp; &nbsp;</td>
						<td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td> 
						<td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td></table>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>3.</td>
				<td>Jenis Rumah Sakit</td>
				<td>: </td>
				<td></td>
				<td>
					<table border="1" cellpadding="2" cellspacing="1" class="TBL_BORDER">
					<td>&nbsp; &nbsp;</td>
					</table>
				</td>
			</tr>
			<tr>
				<td>4.</td>
				<td>Kelas Rumah Sakit</td>
				<td>: </td>
				<td></td>
				<td>
					<table border="1" cellpadding="2" cellspacing="1" class="TBL_BORDER">
					<td>&nbsp; &nbsp;</td>
					</table>
				</td>
			</tr>
			<tr>
				<td>5.</td>
				<td>Nama Direktur RS</td>
				<td>: </td>
				<td></td>
			</tr>
			<tr>
				<td>5.</td>
				<td>Alamat Lokasi RS</td>
				<td>: </td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td>Kab/Kota</td>
				<td>: </td>
				<td></td>
				<td>Kode Pos
					<table border="1" cellpadding="2" cellspacing="1" class="TBL_BORDER">
					<td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td>
					<td>&nbsp; &nbsp;</td> <td>&nbsp; &nbsp;</td>
					<td>&nbsp; &nbsp;</td>
					</table>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>Telepon/Fax/Email</td>
				<td>: </td>
				<td>
				(&nbsp;&nbsp;&nbsp;&nbsp;)-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				/&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				/&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td>7.</td>
				<td>Surat Izin Penetapan</td>
				<td>: </td>
			</tr>
			<tr>
				<td></td>
				<td>a. Nomor</td>
				<td>: </td>
			</tr>
			<tr>
				<td></td>
				<td>b. Tanggal</td>
				<td>: </td>
			</tr>
			<tr>
				<td></td>
				<td>c. Oleh</td>
				<td>: </td>
			</tr>
			<tr>
				<td></td>
				<td>d. Sifat</td>
				<td>: </td>
			</tr>
			<tr>
				<td></td>
				<td>e. Masa Berlaku</td>
				<td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>Sampai Tahun</td>
			</tr>
		</table>
	</td>
	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	
	<td>
		<table>
			<tr>
				<td>8.</td>
				<td>Kepemilikan RS</td>
				<td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td>a. Nama</td>
				<td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td>b. Status</td>
				<td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td>9.</td>
				<td>Khusus Swasta</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td>Islam
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
				</td>
				<td>Hindu
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
				</td>
				<td>Perorangan
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td>Katholik
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
				</td>
				<td>Budha
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
				</td>
				<td>Perusahaan
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td>Protestan
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
				</td>
				<td>Organisasi sosial
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
				</td>
			</tr>
			<tr>
				<td>10.</td>
				<td>Akreditasi RS</td>
				<td>:</td>
			</tr>
			<tr>
				<td></td>
				<td>Pentahapan</td>
				<td>:</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td>I</td><td>II</td><td>III</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td>
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
					5 Pelayanan &nbsp;&nbsp;&nbsp;&nbsp;
				</td>
				<td>
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
					12 Pelayanan
				</td>
				<td>
					<table border="1" cellpadding="1" cellpadding="1"><td></td></table>
					16 Pelayanan
				</td>
			</tr>
			<tr><td>&nbsp;&nbsp;</td></tr>
			<tr><td>&nbsp;&nbsp;</td></tr>
			<tr><td>&nbsp;&nbsp;</td></tr>
			<tr><td>&nbsp;&nbsp;</td></tr>
			<tr><td>&nbsp;&nbsp;</td></tr>
			
		</table>
	</td>
</table>
</br></br>
11.Fasilitas tempat tidur rawat inap
<table align="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="2">NO URUT </td>
        <td class="TBL_HEAD" align="center" rowspan="2">JENIS PELAYANAN / RUANG RAWAT INAP *)</td>
        <td rowspan="2" align="center" class="TBL_HEAD">JUMLAH TT TERSEDIA</td>
        <td colspan="5" align="center" class="TBL_HEAD">PERINCIAN TEMPAT TIDUR PER-KELAS</td>
        <td rowspan="2" align="center" class="TBL_HEAD">NO</td>
        </tr>
    
    <tr>
        <td class="TBL_HEAD" align="center" >KELAS UTAMA</td>
        <td class="TBL_HEAD" align="center" >KELAS I</td>
        <td class="TBL_HEAD" align="center" >KELAS II</td>
        <td class="TBL_HEAD" align="center" > KELAS III</td>
        <td class="TBL_HEAD" align="center" >TANPA KELAS</td>
     </tr>
	 <tr>
        <td class="TBL_HEAD" align="center">1</td>
        <td class="TBL_HEAD" align="center">2</td>
        <td align="center" class="TBL_HEAD">3</td>
        <td align="center" class="TBL_HEAD">4</td>
        <td class="TBL_HEAD" align="center">5</td>
        <td class="TBL_HEAD" align="center">6</td>
        <td class="TBL_HEAD" align="center">7</td>
        <td class="TBL_HEAD" align="center">8</td>
        <td class="TBL_HEAD" align="center">9</td>
       </tr>
	 <?	
			$tot1= 0;
			$tot2= 0;
			$tot3= 0;
			$tot4= 0;
			$tot5= 0;
			$tot6= 0;
			
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while ($row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
    <tr>
      <td class="TBL_BODY" align="center"><?=$no ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["jenis"] ?></td>
      <td align="center" class="TBL_BODY"><?=$row1["jumlah_tt"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["utama"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kelas_1"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kelas_2"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kelas_3"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["no_kelas"] ?></td>
      <td class="TBL_BODY" align="center"><?=$no ?></td>
          </tr>
	  <?
					
					$tot1=$tot1+$row1["jumlah_tt"] ;
					$tot2=$tot2+$row1["utama"] ;
					$tot3=$tot3+$row1["kelas_1"] ;
					$tot4=$tot4+$row1["kelas_2"] ;
					$tot5=$tot5+$row1["kelas_3"] ;
					$tot6=$tot6+$row1["no_kelas"] ;
					
					
					
					
					
					?>
   <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
    <tr>
      <td class="TBL_HEAD" align="center">99</td>
      <td class="TBL_HEAD" align="center"><div align="justify">JUMLAH</div></td>
      <td class="TBL_HEAD" align="center"><?=$tot1 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot2 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot3 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot4 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot5 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot6 ?></td>
      </tr>
</table>
<p>&nbsp;</p>
