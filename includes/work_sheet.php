<? // Wildan 20 feb 2014


$PID = "work_sheet";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-worksheet.png' align='absmiddle' > KERTAS KERJA");
		title_excel("work_sheet&mPeriode=".$_GET["mPeriode"]."");
    } else {
    	title("<img src='icon/akuntansi-worksheet.png' align='absmiddle' > KERTAS KERJA");
    }
    echo "<br>";
    //$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

    if (!$GLOBALS['print']){
	    $f->selectSQL("mPeriode", "Periode","select '' as kode, '' as ket_riwulan union SELECT kode,ket_triwulan FROM triwulan order by ket_riwulan ASC ",$_GET["mPeriode"], "");
		
		$sql_1=getFromtable("select bln_awal from triwulan where kode='".$_GET["mPeriode"]."'");
		$sql_2=getFromtable("select bln_akhir from triwulan where kode='".$_GET["mPeriode"]."'");
		$ket=getFromtable("select keterangan from triwulan where kode='".$_GET["mPeriode"]."'");
		$f->submit ("TAMPILKAN");
	    $f->execute();

	} else {
		$f->selectSQL("mPeriode", "Periode","select '' as kode, '' as ket_riwulan union SELECT kode,ket_triwulan FROM triwulan order by ket_riwulan ASC ",$_GET["mPeriode"], "disabled");
		
		$sql_1=getFromtable("select bln_awal from triwulan where kode='".$_GET["mPeriode"]."'");
		$sql_2=getFromtable("select bln_akhir from triwulan where kode='".$_GET["mPeriode"]."'");
		$ket=getFromtable("select keterangan from triwulan where kode='".$_GET["mPeriode"]."'");
	    $f->execute();
	}
	
    echo "<br>";

    echo "<br>";
    

//---------------wildan 02/2014---------------

$sqla=" select no_akun, nama from rsv_jurnal_umum 
		where (tanggal_akun between '$sql_1' and '$sql_2')
        group by no_akun,nama
        order by no_akun";

@$r1 = pg_query($con,$sqla);
@$n1 = pg_num_rows($r1);

		$max_row1= 200 ;
        $mulai1 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai1){$mulai1=1;}
        
        $max_row2= 200 ;
        $mulai2 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai2){$mulai2=1;}
        
?>

<table width="100%">
	<tr>
		<td align="center" class="TBL_JUDUL">KERTAS KERJA</td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?></td>
	</tr>
</table>
<br>
<br>

<TABLE BORDER="1" width="100%" CLASS=TBL_BORDER>
    <tr>
		<td class="TBL_HEAD" width="9%" rowspan="2"><div align="center">No. Akun </div></td>
		<td class="TBL_HEAD" width="18%" rowspan="2"><div align="center">Nama Akun </div></td>
		<td class="TBL_HEAD" colspan="2"><div align="center">Neraca Saldo </div></td>
		<td class="TBL_HEAD" colspan="2"><div align="center">Laba-Rugi</div></td>
		<td class="TBL_HEAD" colspan="2"><div align="center">Neraca</div></td>
	</tr>
	<tr>
		<td class="TBL_HEAD" width="12%"><div align="center">Debet</div></td>
		<td class="TBL_HEAD" width="12%"><div align="center">Kredit</div></td>
		<td class="TBL_HEAD" width="12%"><div align="center">Debet</div></td>
		<td class="TBL_HEAD" width="12%"><div align="center">Kredit</div></td>
		<td class="TBL_HEAD" width="12%"><div align="center">Debet</div></td>
		<td class="TBL_HEAD" width="13%"><div align="center">Kredit</div></td>
	</tr>

      <?
        $row1=0;
		$i= 1 ;
		$j= 1 ;
		$last_id=1;
		while (@$row1 = pg_fetch_array($r1)){
              if (($j<=$max_row1) AND ($i >= $mulai1)){
              $no=$i;
	   ?>
            <tr>
                <td class="TBL_BODY"  align="center"><?=$row1["no_akun"] ?> </td>
                <td class="TBL_BODY"  align="left"><?=$row1["nama"] ?></td>
            
            <?
			// untuk neraca saldo ===========================================
            $sqlb = "select no_akun, sum(debet) as debet, sum(kredit) as kredit
                    from rsv_jurnal_umum
                    where (tanggal_akun between '$sql_1' and '$sql_2') 
                    group by no_akun  ";

            @$r2 = pg_query($con,$sqlb);
            @$n2 = pg_num_rows($r2);

            $max_row2= 200 ;
            $mulai2 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai2){$mulai2=1;}
            
            $total2=0;
            $row2=0;
            $totaldebet=0;
            $totalkredit=0;
            $i2= 1 ;
            $j2= 1 ;
            $last_id2=1;
            while (@$row2 = pg_fetch_array($r2)){
                  if (($j2<=$max_row2) AND ($i2 >= $mulai2)){
                     $no2=$i2;
                  if($row2["no_akun"]==$row1["no_akun"]){

					 $totaldebet=$totaldebet+$row2["debet"];
					 $totalkredit=$totalkredit+$row2["kredit"];

					 $total=$totaldebet-$totalkredit;
             ;$j2++;}
          $i2++;}
        }
        
        if ($total>0){
        ?> 
		<td class="TBL_BODY" align="right"><b><?=number_format($total,2,",",".")?></b></td>
                <td class="TBL_BODY" align="right"><b><?=number_format(0,2,",",".")?></b></td>
          <?}else{ 
            $total2=$total * -1;
              ?>  
                <td class="TBL_BODY" align="right"><b><?=number_format(0,2,",",".")?></b></td>
                <td class="TBL_BODY" align="right"><b><?=number_format($total2,2,",",".")?></b></td>
            <?} ?>  
			<?
			// untuk laba-rugi ===========================================
			
			$laba_rugi= " select no_akun, 0 as kredit, sum(kredit) as debet from rsv_jurnal_umum 
							where substring(no_akun,1,1) in ('2') and (tanggal_akun between '$sql_1' and '$sql_2') 
							group by no_akun
							union
							select no_akun, sum(debet) as kredit, 0 as debet from rsv_jurnal_umum 
							where substring(no_akun,1,1) in ('3','4') and (tanggal_akun between '$sql_1' and '$sql_2') 
							group by no_akun
							union
							select no_akun, 0 as debet, 0 as kredit
							from rsv_jurnal_umum
							where substring(no_akun,1,1) not in ('2','3','4') and (tanggal_akun between '$sql_1' and '$sql_2') 
							group by no_akun
							order by no_akun";

            $r3 = pg_query($con,$laba_rugi);
            $n3 = pg_num_rows($r3);

            $max_row3= 200 ;
            $mulai3 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai3){$mulai3=1;}
            
            
            $row3=0;
            $i3= 1 ;
            $j3= 1 ;
            $last_id3=1;
            while ($row3 = pg_fetch_array($r3)){
                  if (($j3<=$max_row3) AND ($i3 >= $mulai3)){
                     $no3=$i3;
                  if($row3["no_akun"]==$row1["no_akun"]){
					?>
					<td class="TBL_BODY" align="right"><b><?=number_format($row3["debet"],2,",",".")?></b></td>
					<td class="TBL_BODY" align="right"><b><?=number_format($row3["kredit"],2,",",".")?></b></td>
					<?
					 $totaldebetlaba=$totaldebetlaba+$row3["debet"];
					 $totalkreditlaba=$totalkreditlaba+$row3["kredit"];
					 
					 
             ;$j3++;}
			 
          $i3++;}
        }
        
		// untuk NERACA ===========================================
			
			$neraca= " 		select no_akun, sum(debet) as debet, sum(kredit) as kredit from rsv_jurnal_umum 
							where substring(no_akun,1,2) in ('11','12') and (tanggal_akun between '$sql_1' and '$sql_2') 
							group by no_akun
							union
							select no_akun, sum(debet) as debet, sum(kredit) as debet from rsv_jurnal_umum 
							where substring(no_akun,1,2) in ('41','42') and (tanggal_akun between '$sql_1' and '$sql_2') 
							group by no_akun
							union
							select no_akun, sum(debet) as debet, sum(kredit) as debet from rsv_jurnal_umum 
							where substring(no_akun,1,1) in ('5') and (tanggal_akun between '$sql_1' and '$sql_2') 
							group by no_akun
							union
							select no_akun, 0 as debet, 0 as kredit
							from rsv_jurnal_umum
							where substring(no_akun,1,1) not in ('1','4','5') and (tanggal_akun between '$sql_1' and '$sql_2') 
							group by no_akun
							order by no_akun";

            $r4 = pg_query($con,$neraca);
            $n4 = pg_num_rows($r4);

            $max_row4= 200 ;
            $mulai4 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai4){$mulai4=1;}
            
            
            $row4=0;
            $i4= 1 ;
            $j4= 1 ;
            $last_id4=1;
            while ($row4 = pg_fetch_array($r4)){
                  if (($j4<=$max_row4) AND ($i4 >= $mulai4)){
                     $no4=$i4;
                  if($row4["no_akun"]==$row1["no_akun"]){
					?>
					<td class="TBL_BODY" align="right"><b><?=number_format($row4["debet"],2,",",".")?></b></td>
					<td class="TBL_BODY" align="right"><b><?=number_format($row4["kredit"],2,",",".")?></b></td>
					<?
					 $totaldebetneraca=$totaldebetneraca+$row4["debet"];
					 $totalkreditneraca=$totalkreditneraca+$row4["kredit"];
					 
					 
             ;$j4++;}
			 
          $i4++;}
        }
        ?> 
				
            </tr>
            
            <?
			$laba=$totaldebetlaba-$totalkreditlaba;
			
            $total3=$total3+$total2;
            ;$j++;}
        $i++;		
        } 
        ?>
		
        <tr>
            <td class="TBL_HEAD" colspan="2" align="center"><b><u>SUB TOTAL</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($total3,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($total3,2,",",".")?></u></b></td>
			<td class="TBL_HEAD" align="right"><b><u><?=number_format($totaldebetlaba,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($totalkreditlaba,2,",",".")?></u></b></td>
			<td class="TBL_HEAD" align="right"><b><u><?=number_format($totaldebetneraca,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($totalkreditneraca,2,",",".")?></u></b></td>
        </tr>
		<tr>
            <td class="TBL_HEAD" colspan="2" align="center"><b><u>TOTAL LABA/RUGI</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format(0,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format(0,2,",",".")?></u></b></td>
			<? if ($totaldebetlaba > $totalkreditlaba) { 
				   $tot_laba=$totalkreditlaba + $laba; ?>
			<td class="TBL_HEAD" align="right"><b><u><?=number_format(0,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($laba,2,",",".")?></u></b></td>
			<?}else{
				  $tot_laba=$totaldebetlaba + $laba; ?>
			<td class="TBL_HEAD" align="right"><b><u><?=number_format($laba,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format(0,2,",",".")?></u></b></td>
			<?}?>
			
			<? if ($totaldebetneraca > $totalkreditneraca) { 
				   $tot_neraca=$totalkreditneraca + $laba; ?>
			<td class="TBL_HEAD" align="right"><b><u><?=number_format(0,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($laba,2,",",".")?></u></b></td>
			<?}else{
				  $tot_neraca=$totaldebetneraca + $laba; ?>
			<td class="TBL_HEAD" align="right"><b><u><?=number_format($laba,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format(0,2,",",".")?></u></b></td>
			<?}?>
        </tr>
		<tr>
            <td class="TBL_HEAD" colspan="2" align="center"><b><u> T O T A L </u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($total3,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($total3,2,",",".")?></u></b></td>
			<td class="TBL_HEAD" align="right"><b><u><?=number_format($tot_laba,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($tot_laba,2,",",".")?></u></b></td>
			<td class="TBL_HEAD" align="right"><b><u><?=number_format($tot_neraca,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($tot_neraca,2,",",".")?></u></b></td>
        </tr>
</TABLE>