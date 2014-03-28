<? 
$PID = "neraca1";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

    //------------------------------------------------------- mulai
    if (!$GLOBALS['print']){
    	title("<img src='icon/keuangan-2.gif' align='absmiddle' > Jurnal Umum");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Jurnal Umum");
    }
    
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	if (!$GLOBALS['print']) {
	    if (!isset($_GET['tanggal1D'])) {

		$tanggal1D = date("d", time());
		$tanggal1M = date("m", time());
		$tanggal1Y = date("Y", time());
		$tanggal2D = date("d", time());
		$tanggal2M = date("m", time());
		$tanggal2Y = date("Y", time());

	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

	    } else {
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
	    }

    
	$f->submit ("TAMPILKAN");
	    $f->execute();

	} else {
		if (!isset($_GET['tanggal1D'])) {
		$tanggal1D = date("d", time());
		$tanggal1M = date("m", time());
		$tanggal1Y = date("Y", time());
		$tanggal2D = date("d", time());
		$tanggal2M = date("m", time());
		$tanggal2Y = date("Y", time());

	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "disabled");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "disabled");
	    } else {
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
		
	    }

	    $f->execute();
	}
	
    echo "<br>";

$sql="select to_char(tanggal_akun,'dd-mm-yyyy') as tanggal_akun,no_akun,keterangan, sum(debet) as debet,sum(kredit) as kredit from jurnal_umum 
where (tanggal_akun between '$ts_check_in1' and '$ts_check_in2') group by tanggal_akun,no_akun,keterangan order by tanggal_akun,no_akun";

@$r1 = pg_query($con,$sql);
@$n1 = pg_num_rows($r1);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
	
title_print("");
?>
<TABLE align="center" border=1 width="75%">
	<tr align="center" class="TBL_HEAD">  	
		<td ><b>   TANGGAL</b></td>
		<td ><b>   NO.AKUN</b></td>
		<td ><b>  KETERANGAN</b></td>
		<td ><b>   DEBIT</b></td>
		<td ><b>  KREDIT</b></td>
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
						<td align="center"><?=$row1["tanggal_akun"] ?> </td>
						<td align="center"><?=$row1["no_akun"] ?> </td>
						<td align="left"><?=$row1["keterangan"] ?> </td>
						<td align="right"><?=number_format($row1["debet"] ,2,",",".")?></td>
						<td align="right"><?=number_format($row1["kredit"] ,2,",",".")?></td>
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
			        	<td align="center" colspan="3" height="25" valign="middle"><b> TOTAL </b></td>
			        	<td align="right" valign="middle"><b><?=number_format($totbaru,2,",",".") ?></b></td>
						<td align="right" valign="middle"><b><?=number_format($totulang,2,",",".") ?></b></td>
					</tr>	

</table>