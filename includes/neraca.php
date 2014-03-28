<?php

$PID = "neraca";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

    //------------------------------------------------------- mulai
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-neraca.png' align='absmiddle' > LAPORAN NERACA");
		//title_excel("neraca");
		title_excel("neraca&mPeriode=".$_GET["mPeriode"]."");
    } else {
    	title_print("<img src='icon/akuntansi-neraca.png' align='absmiddle' > LAPORAN NERACA");
		//title_excel("neraca");
		title_excel("neraca&mPeriode=".$_GET["mPeriode"]."");
    }
    
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

    if (!$GLOBALS['print']){
	    $f->selectSQL("mPeriode", "Periode","select '' as kode, '' as ket_riwulan union SELECT kode,ket_triwulan FROM triwulan order by ket_riwulan ASC ",$_GET["mPeriode"], "");
		
		$sql1=getFromtable("select bln_awal from triwulan where kode='".$_GET["mPeriode"]."'");
		$sql2=getFromtable("select bln_akhir from triwulan where kode='".$_GET["mPeriode"]."'");
                
                $ket=getFromtable("select keterangan from triwulan where kode='".$_GET["mPeriode"]."'");
		$f->submit ("TAMPILKAN");
	    $f->execute();

	} else {
		$f->selectSQL("mPeriode", "Periode","select '' as kode, '' as ket_riwulan union SELECT kode,ket_triwulan FROM triwulan order by ket_riwulan ASC ",$_GET["mPeriode"], "disabled");
		
		$sql1=getFromtable("select bln_awal from triwulan where kode='".$_GET["mPeriode"]."'");
		$sql2=getFromtable("select bln_akhir from triwulan where kode='".$_GET["mPeriode"]."'");
                
                $ket=getFromtable("select keterangan from triwulan where kode='".$_GET["mPeriode"]."'");

	    $f->execute();
	}
        
    echo "<br>";

$sqla="  select no_akun, nama from rsv_jurnal_umum 
        where (tanggal_akun between '$sql1' and '$sql2') and substring(no_akun,1,2) in ('11')
        group by no_akun, nama
        order by no_akun ";// UNTUK AKTIVA LANCAR

@$r1 = pg_query($con,$sqla);
@$n1 = pg_num_rows($r1);

$sqlc="  select no_akun, nama from rsv_jurnal_umum 
        where (tanggal_akun between '$sql1' and '$sql2') and substring(no_akun,1,2) in ('12')
        group by no_akun, nama
        order by no_akun ";//UNTUK AKTIVA TETAP

@$r3 = pg_query($con,$sqlc);
@$n3 = pg_num_rows($r3);

$sqld="  select no_akun, nama from rsv_jurnal_umum 
        where (tanggal_akun between '$sql1' and '$sql2') and substring(no_akun,1,2) in ('41')
        group by no_akun, nama
        order by no_akun ";//UNTUK hutang & kewajiban JANGKA PENDEK

@$r5 = pg_query($con,$sqld);
@$n5 = pg_num_rows($r5);

$sqle="  select no_akun, nama from rsv_jurnal_umum 
        where (tanggal_akun between '$sql1' and '$sql2') and substring(no_akun,1,2) in ('42')
        group by no_akun, nama
        order by no_akun ";//UNTUK hutang & kewajiban JANGKA PANJANG

@$r7 = pg_query($con,$sqle);
@$n7 = pg_num_rows($r7);

$sqlg="  select no_akun, nama from rsv_jurnal_umum 
        where (tanggal_akun between '$sql1' and '$sql2') and substring(no_akun,1,1) in ('5')
        group by no_akun, nama
        order by no_akun ";//UNTUK hutang & kewajiban JANGKA PANJANG

@$r9 = pg_query($con,$sqlg);
@$n9 = pg_num_rows($r9);


        $max_row1= 200 ;
        $mulai1 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai1){$mulai1=1;}
        
        $max_row2= 200 ;
        $mulai2 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai2){$mulai2=1;}
        
        $max_row3= 200 ;
        $mulai3 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai3){$mulai3=1;}
        
        $max_row5= 200 ;
        $mulai5 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai5){$mulai5=1;}
        
        $max_row7= 200 ;
        $mulai7 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai7){$mulai7=1;}
        
        $max_row9= 200 ;
        $mulai9 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai9){$mulai9=1;}
	
//title_print("");

// untuk total laba-rugi =======================================================
//==============================================================================
$pendapatan = " select a.no_akun, b.nama, sum(kredit) as jumlah from jurnal_umum a, akun_master b
                where a.no_akun=b.kode and substring(a.no_akun,1,1) in ('2') and (a.tanggal_akun between '$sql1' and '$sql2') and kredit != 0
                group by a.no_akun, b.nama
                order by a.no_akun";
$beban      = " select a.no_akun, b.nama, sum(debet) as jumlah from jurnal_umum a, akun_master b
                where a.no_akun=b.kode and substring(a.no_akun,1,1) in ('3','4') and (a.tanggal_akun between '$sql1' and '$sql2') and debet!= 0
                group by a.no_akun, b.nama
                order by a.no_akun";

        @$r11 = pg_query($con,$pendapatan);
        @$n11 = pg_num_rows($r11);
        
        @$r12 = pg_query($con,$beban);
        @$n12 = pg_num_rows($r12);

        $max_row11= 200 ;
        $mulai11 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai11){$mulai11=1;}
        
        $max_row12= 200 ;
        $mulai12 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai12){$mulai12=1;}

?>

<TABLE BORDER="0" width="75%" CLASS=TBL_BORDER align="center">

      <?
        $row11=0;
        $totalpendapatan11=0;
	$i11= 1 ;
	$j11= 1 ;
	$last_id=1;
	while (@$row11 = pg_fetch_array($r11)){
              if (($j11<=$max_row11) AND ($i11 >= $mulai11)){
              $no=$i11;

            $totalpendapatan11=$totalpendapatan11+$row11["jumlah"];
            ;$j11++;}
        $i11++;		
        } 
        
        $row12=0;
        $totalbeban12=0;
	$i12= 1 ;
	$j12= 1 ;
	$last_id=1;
	while (@$row12 = pg_fetch_array($r12)){
              if (($j12<=$max_row12) AND ($i12 >= $mulai12)){
              $no=$i12;

            $totalbeban12=$totalbeban12+$row12["jumlah"];
            ;$j12++;}
        $i12++;		
        } 
        
        $tot_laba=$totalpendapatan11-$totalbeban12;
        ?>

</TABLE>


<table width="100%">
	<tr>
		<td align="center" class="TBL_JUDUL">NERACA</td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?></td>
	</tr>
</table>
<br>
<br>
<table width="100%" BORDER="1">
    <tr>
        <td width="50%" valign="top">
<TABLE BORDER="1" width="100%" CLASS="TBL_BORDER">
    <tr>
       <td class="TBL_HEAD" colspan ="3" align="center">AKTIVA</td>
    </tr>
    <tr>
       <td class="TBL_BODY" colspan ="3" align="left"><b>AKTIVA LANCAR</b></td>
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

            $sqlb = "select no_akun, sum(debet) as debet, sum(kredit) as kredit
                    from rsv_jurnal_umum
                    where (tanggal_akun between '$sql1' and '$sql2') 
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
        
        ?> 
		<td class="TBL_BODY" align="right"><b><?=number_format($total,2,",",".")?></b></td>
            </tr>
            
            <?
            $totallancar=$totallancar+$total;
            ;$j++;}
        $i++;		
        } 
        ?>
        <tr>
            <td class="TBL_HEAD" colspan="2" align="right"><b><u>TOTAL &nbsp; AKTIVA &nbsp; LANCAR</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($totallancar,2,",",".")?></u></b></td>
        </tr>

        <!--===================================================================-->
        <!--===================================================================-->
        <!--===================================================================-->
        
        <tr>
           <td class="TBL_BODY" colspan ="3" align="left"><b>AKTIVA TETAP</b></td>
        </tr>
         <?
        $row3=0;
	$i3= 1 ;
	$j3= 1 ;
	$last_id3=1;
	while (@$row3 = pg_fetch_array($r3)){
              if (($j3<=$max_row3) AND ($i3 >= $mulai3)){
              $no=$i3;
	   ?>
            <tr>
                <td class="TBL_BODY"  align="center"><?=$row3["no_akun"] ?> </td>
                <td class="TBL_BODY"  align="left"><?=$row3["nama"] ?></td>
            
            <?

            $sql4 = "select no_akun, sum(debet) as debet, sum(kredit) as kredit
                    from rsv_jurnal_umum
                    where (tanggal_akun between '$sql1' and '$sql2') 
                    group by no_akun  ";

            @$r4 = pg_query($con,$sql4);
            @$n4 = pg_num_rows($r4);

            $max_row4= 200 ;
            $mulai4 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai4){$mulai4=1;}
            
            $total4=0;
            $row4=0;
            $totaldebet4=0;
            $totalkredit4=0;
            $i4= 1 ;
            $j4= 1 ;
            $last_id4=1;
            while (@$row4 = pg_fetch_array($r4)){
                  if (($j4<=$max_row4) AND ($i4 >= $mulai4)){
                     $no4=$i4;
                  if($row4["no_akun"]==$row3["no_akun"]){

             $totaldebet4=$totaldebet4+$row4["debet"];
             $totalkredit4=$totalkredit4+$row4["kredit"];

             $total4=$totaldebet4-$totalkredit4;
             ;$j2++;}
          $i2++;}
        }
        
        ?> 
		<td class="TBL_BODY" align="right"><b><?=number_format($total4,2,",",".")?></b></td>
            </tr>
            
            <?
            $totaltetap=$totaltetap+$total4;
            ;$j++;}
        $i++;		
        } 
        $tot_aktiva=$totaltetap+$totallancar;
        ?>
        <tr>
            <td class="TBL_HEAD" colspan="2" align="right"><b><u>TOTAL&nbsp;AKTIVA&nbsp;TETAP</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($totaltetap,2,",",".")?></u></b></td>
        </tr>
        
        <tr>
            <td class="TBL_HEAD" colspan="2" align="right"><b><u>TOTAL&nbsp;AKTIVA</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($tot_aktiva,2,",",".")?></u></b></td>
        </tr>
        
</TABLE>
        </td>
        <!--===================================================================-->
        <!--================KEWAJIBAN/HUTANG & MODAL===========================-->
        <!--===================================================================-->      
        <td width="50%" valign="top">
<TABLE BORDER="1" width="100%" CLASS="TBL_BORDER" >
    <tr>
       <td class="TBL_HEAD" colspan ="3" align="center">HUTANG/KEWAJIBAN & MODAL</td>
    </tr>
    <tr>
       <td class="TBL_BODY" colspan ="3" align="left"><b>HUTANG/KEWAJIBAN JANGKA PENDEK</b></td>
    </tr>
      <?
        $row5=0;
	$i5= 1 ;
	$j5= 1 ;
	$last_id=1;
	while (@$row5 = pg_fetch_array($r5)){
              if (($j5<=$max_row5) AND ($i5 >= $mulai5)){
              $no=$i5;
	   ?>
    
            <tr>
                <td class="TBL_BODY"  align="center"><?=$row5["no_akun"] ?> </td>
                <td class="TBL_BODY"  align="left"><?=$row5["nama"] ?></td>
            
            <?

            $sqlf = "select no_akun, sum(debet) as debet, sum(kredit) as kredit
                    from rsv_jurnal_umum
                    where (tanggal_akun between '$sql1' and '$sql2') 
                    group by no_akun  ";

            @$r6 = pg_query($con,$sqlf);
            @$n6 = pg_num_rows($r6);

            $max_row6= 200 ;
            $mulai6 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai6){$mulai6=1;}
            
            $total6=0;
            $row6=0;
            $totaldebet6=0;
            $totalkredit6=0;
            $i6= 1 ;
            $j6= 1 ;
            $last_id6=1;
            while (@$row6 = pg_fetch_array($r6)){
                  if (($j6<=$max_row6) AND ($i6 >= $mulai6)){
                     $no6=$i6;
                  if($row6["no_akun"]==$row5["no_akun"]){

             $totaldebet6=$totaldebet6+$row6["debet"];
             $totalkredit6=$totalkredit6+$row6["kredit"];

             $total6=$totaldebet6-$totalkredit6;
			 if ($total6 < 0){
			 $total6=$total6 * -1;
			 }
             ;$j6++;}
          $i6++;}
        }
        
        ?> 
		<td class="TBL_BODY" align="right"><b><?=number_format($total6,2,",",".")?></b></td>
            </tr>
            
            <?
            $totalhutanglancar=$totalhutanglancar+$total6;
            ;$j5++;}
        $i5++;		
        } 
        
        //IF ($totalhutanglancar > 0){
        ?>
        
        <tr>
            <td class="TBL_HEAD" colspan="2" align="right"><b><u>TOTAL &nbsp; HUTANG/KEWAJIBAN &nbsp; JANGKA&nbsp; PENDEK</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($totalhutanglancar,2,",",".")?></u></b></td>
        </tr>
        <?//}?>
        <!--===================================================================-->
        <!--===================================================================-->
        <!--===================================================================-->
        
        <tr>
                <td class="TBL_BODY" colspan ="3" align="left"><b>HUTANG/KEWAJIBAN JANGKA PANJANG</b></td>
        </tr>
         <?
        
        
        $row7=0;
	$i7= 1 ;
	$j7= 1 ;
	$last_id7=1;
	while (@$row7 = pg_fetch_array($r7)){
              if (($j7<=$max_row7) AND ($i7 >= $mulai7)){
              $no=$i7;
	   ?>
            
            <tr>
                <td class="TBL_BODY"  align="center"><?=$row7["no_akun"] ?> </td>
                <td class="TBL_BODY"  align="left"><?=$row7["nama"] ?></td>
            
            <?

            $sql8 = "select no_akun, sum(debet) as debet, sum(kredit) as kredit
                    from rsv_jurnal_umum
                    where (tanggal_akun between '$sql1' and '$sql2') 
                    group by no_akun  ";

            @$r8 = pg_query($con,$sql8);
            @$n8 = pg_num_rows($r8);

            $max_row8= 200 ;
            $mulai8 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai8){$mulai8=1;}
            
            $total8=0;
            $row8=0;
            $totaldebet8=0;
            $totalkredit8=0;
            $i8= 1 ;
            $j8= 1 ;
            $last_id8=1;
            while (@$row8 = pg_fetch_array($r8)){
                  if (($j8<=$max_row8) AND ($i8 >= $mulai8)){
                     $no8=$i8;
                  if($row8["no_akun"]==$row7["no_akun"]){

             $totaldebet8=$totaldebet8+$row8["debet"];
             $totalkredit8=$totalkredit8+$row8["kredit"];

             $total8=($totaldebet8-$totalkredit8);
			 
			 if ($total8 < 0){
			 $total8=$total8 * -1;
			 }
             ;$j8++;}
          $i8++;}
        }
        
        ?> 
		<td class="TBL_BODY" align="right"><b><?=number_format($total8,2,",",".")?></b></td>
            </tr>
            
            <?
            $totalhutangtetap=$totalhutangtetap+$total8;
            ;$j7++;}
        $i7++;}		
         
        $tot_hutang=$totalhutangtetap+$totalhutanglancar;
        ?>
        <tr>
            <td class="TBL_HEAD" colspan="2" align="right"><b><u>TOTAL&nbsp;HUTANG&nbsp;JANGKA&nbsp;PANJANG</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($totalhutangtetap,2,",",".")?></u></b></td>
        </tr>
        <!--===================================================================-->
        <!--===================================================================-->
        <!--===================================================================-->
        
        <tr>
            <td class="TBL_HEAD" colspan="2" align="right"><b><u>TOTAL&nbsp;HUTANG/KEWAJIBAN&nbsp;&&nbsp;MODAL</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($tot_hutang,2,",",".")?></u></b></td>
        </tr>
        
        <tr>
                <td class="TBL_BODY" colspan ="3" align="left"><b>MODAL</b></td>
        </tr>
        <?
        $row9=0;
	$i9= 1 ;
	$j9= 1 ;
	$last_id9=1;
	while (@$row9 = pg_fetch_array($r9)){
              if (($j9<=$max_row9) AND ($i9 >= $mulai9)){
              $no=$i9;
	   ?>
            
            <tr>
                <td class="TBL_BODY"  align="center"><?=$row9["no_akun"] ?> </td>
                <td class="TBL_BODY"  align="left"><?=$row9["nama"] ?></td>
            
            <?

            $sql10 = "select no_akun, sum(debet) as debet, sum(kredit) as kredit
                    from rsv_jurnal_umum
                    where (tanggal_akun between '$sql1' and '$sql2') 
                    group by no_akun  ";

            @$r10 = pg_query($con,$sql10);
            @$n10 = pg_num_rows($r10);

            $max_row10= 200 ;
            $mulai10 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai10){$mulai10=1;}
            
            $total10=0;
            $row10=0;
            $totaldebet10=0;
            $totalkredit10=0;
            $i10= 1 ;
            $j10= 1 ;
            $last_id10=1;
            while (@$row10 = pg_fetch_array($r10)){
                  if (($j10<=$max_row10) AND ($i10 >= $mulai10)){
                     $no10=$i10;
                  if($row10["no_akun"]==$row9["no_akun"]){

             $totaldebet10=$totaldebet10+$row10["debet"];
             $totalkredit10=$totalkredit10+$row10["kredit"];
             
             $total10=($totaldebet10-$totalkredit10);
             if ($total10 < 0){
                $total10=$total10 * -1; 
             }
			 
             ;$j10++;}
          $i10++;}
        }
        
        ?> 
		<td class="TBL_BODY" align="right"><b><?=number_format($total10,2,",",".")?></b></td>
            </tr>
            
            <?
            $totalmodal=$totalmodal+$total10;
            ;$j9++;}
        $i9++;}		
         
        $totalhutangmodal=$totalmodal+$tot_hutang;
        ?>
        <tr>
            <td class="TBL_HEAD" colspan="2" align="right"><b><u>TOTAL&nbsp;MODAL</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($totalmodal,2,",",".")?></u></b></td>
        </tr>
        <tr>
            <td class="TBL_HEAD"  colspan="2" align="right"><b><u>TOTAL LABA/RUGI </u></b></td>
            <td class="TBL_HEAD"  align="right"><b><u><?=number_format($tot_laba,2,",",".") ?></u></b></td>
        </tr>
        <tr>
            <td class="TBL_HEAD" colspan="2" align="right"><b><u>TOTAL&nbsp;HUTANG/KEWAJIBAN&nbsp;&&nbsp;MODAL</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($totalhutangmodal+$tot_laba,2,",",".")?></u></b></td>
        </tr>
        
        
</TABLE>
        </td>
        
    </tr>
</table>