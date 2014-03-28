<?php

$PID = "jurnal_umum";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

    //------------------------------------------------------- mulai
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-jurnalumum.png' align='absmiddle' > Jurnal Umum");
		//title_excel("jurnal_umum");
		title_excel("jurnal_umum&mPeriode=".$_GET["mPeriode"]."");
    } else {
    	title_print("<img src='icon/akuntansi-jurnalumum.png' align='absmiddle' > Jurnal Umum");
		//title_excel("jurnal_umum");
		title_excel("jurnal_umum&mPeriode=".$_GET["mPeriode"]."");
    }
    
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

    if (!$GLOBALS['print']){
	    $f->selectSQL("mPeriode", "Periode","select '' as kode, '' as ket_riwulan union SELECT kode,ket_triwulan FROM triwulan order by ket_riwulan ASC ",$_GET["mPeriode"], "");
		
		$sql1=getFromtable("select bln_awal from triwulan where kode='".$_GET["mPeriode"]."'");
		$sql2=getFromtable("select bln_akhir from triwulan where kode='".$_GET["mPeriode"]."'");
		$f->submit ("TAMPILKAN");
	    $f->execute();

	} else {
		$f->selectSQL("mPeriode", "Periode","select '' as kode, '' as ket_riwulan union SELECT kode,ket_triwulan FROM triwulan order by ket_riwulan ASC ",$_GET["mPeriode"], "disabled");
		
		$sql1=getFromtable("select bln_awal from triwulan where kode='".$_GET["mPeriode"]."'");
		$sql2=getFromtable("select bln_akhir from triwulan where kode='".$_GET["mPeriode"]."'");

	    $f->execute();
	}
        
    echo "<br>";

$sql="  select to_char(tanggal_akun,'dd Mon yyyy') as tanggal_akun, no_akun, nama,debet,kredit
        from rsv_jurnal_umum
        where (tanggal_akun between '$sql1' and '$sql2')
        order by tanggal_akun,no_akun,debet";

@$r1 = pg_query($con,$sql);
@$n1 = pg_num_rows($r1);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
	
//title_print("");
?>

<TABLE align="center" border=1 width="75%" CLASS=TBL_BORDER CELLSPACING=1>
	<tr align="center" class="TBL_HEAD">  	
		<td class="TBL_HEAD"><b>   TANGGAL</b></td>
		<td class="TBL_HEAD"><b>   NO.AKUN</b></td>
                <td class="TBL_HEAD"><b>   NAMA AKUN</b></td>
		<td class="TBL_HEAD"><b>   DEBIT</b></td>
		<td class="TBL_HEAD"><b>  KREDIT</b></td>
	</tr>
	
	<?	
			$totbaru= 0;
			$totulang= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$no=$i 	
					?>		
				 	<tr valign="top" class="<? ?>" > 
						<td class="TBL_BODY" align="center"><?=$row1["tanggal_akun"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row1["no_akun"] ?> </td>
                                                <td class="TBL_BODY" align="left"><?=$row1["nama"] ?> </td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["debet"] ,2,",",".")?></td>
						<td class="TBL_BODY" align="right"><?=number_format($row1["kredit"] ,2,",",".")?></td>
					</tr>	

					<?
					$totbaru=$totbaru+$row1["debet"] ;
					$totulang=$totulang+$row1["kredit"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr valign="top" class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="center" colspan="3" height="25" valign="middle"><b> TOTAL </b></td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($totbaru,2,",",".") ?></b></td>
                                        <td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($totulang,2,",",".") ?></b></td>
					</tr>	

</table>