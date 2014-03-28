<?php

$PID = "lap_neraca_saldo";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

    //------------------------------------------------------- mulai
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-neracasaldo.png' align='absmiddle' > LAPORAN NERACA SALDO");
		//title_excel("lap_neraca_saldo");
		title_excel("lap_neraca_saldo&mPeriode=".$_GET["mPeriode"]."");
    } else {
    	title_print("<img src='icon/akuntansi-neracasaldo.png' align='absmiddle' > LAPORAN NERACA SALDO");
		//title_excel("lap_neraca_saldo");
		title_excel("lap_neraca_saldo&mPeriode=".$_GET["mPeriode"]."");
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
        where (tanggal_akun between '$sql1' and '$sql2')
        group by no_akun, nama
        order by no_akun ";

@$r1 = pg_query($con,$sqla);
@$n1 = pg_num_rows($r1);

	$max_row1= 200 ;
        $mulai1 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai1){$mulai1=1;}
        
        $max_row2= 200 ;
        $mulai2 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai2){$mulai2=1;}
	
//title_print("");
?>

<table width="100%">
	<tr>
		<td align="center" class="TBL_JUDUL">NERACA SALDO</td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?></td>
	</tr>
</table>
<br>
<br>
<TABLE BORDER="0" width="100%" CLASS="TBL_BORDER">
    <tr>
       <td class="TBL_HEAD" align="center">No. Akun</td>
       <td class="TBL_HEAD" align="center">Nama Akun</td>
       <td class="TBL_HEAD" align="center">Debet</td>
       <td class="TBL_HEAD" align="center">Kredit</td>
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
            </tr>
            
            <?
            $total3=$total3+$total2;
            ;$j++;}
        $i++;		
        } 
        ?>
        <tr>
            <td class="TBL_HEAD" colspan="2" align="center"><b><u>T  O  T  A  L</u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($total3,2,",",".")?></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><?=number_format($total3,2,",",".")?></u></b></td>
        </tr>
</TABLE>