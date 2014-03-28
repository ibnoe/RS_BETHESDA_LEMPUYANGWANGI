<?php

$PID = "lap_labarugi";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if($_GET["tc"] == "view") {
/*
*/
} else {
    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-rugilaba.png' align='absmiddle' > LAPORAN LABA RUGI");
		//title_excel("neraca");
		title_excel("lap_labarugi&mPeriode=".$_GET["mPeriode"]."");
    } else {
    	title("<img src='icon/akuntansi-rugilaba.png' align='absmiddle' > LAPORAN LABA RUGI");
		//title_excel("neraca");
		title_excel("lap_labarugi&mPeriode=".$_GET["mPeriode"]."");
    }
    echo "<br>";
    //$ext = "OnChange = 'Form1.submit();'";
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

    echo "<br>";
    
}
//---------------wildan 04/2011---------------

$pendapatan = " select a.no_akun, b.nama, sum(kredit) as jumlah from jurnal_umum a, akun_master b
                where a.no_akun=b.kode and substring(a.no_akun,1,1) in ('2') and (a.tanggal_akun between '$sql1' and '$sql2') and kredit != 0
                group by a.no_akun, b.nama
                order by a.no_akun";
$beban      = " select a.no_akun, b.nama, sum(debet) as jumlah from jurnal_umum a, akun_master b
                where a.no_akun=b.kode and substring(a.no_akun,1,1) in ('3','4') and (a.tanggal_akun between '$sql1' and '$sql2') and debet!= 0
                group by a.no_akun, b.nama
                order by a.no_akun";

        @$r1 = pg_query($con,$pendapatan);
        @$n1 = pg_num_rows($r1);
        
        @$r2 = pg_query($con,$beban);
        @$n2 = pg_num_rows($r2);

        $max_row1= 200 ;
        $mulai1 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai1){$mulai1=1;}

?>
<table width="100%">
	<tr>
		<td align="center" class="TBL_JUDUL">LAPORAN LABA-RUGI</td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?></td>
	</tr>
</table>

<TABLE BORDER="0" width="75%" CLASS=TBL_BORDER align="center">
    <tr>
        <td colspan="3" class="TBL_HEAD" >PENDAPATAN</td>
    </tr>
      <?
        $row1=0;
        $totalpendapatan=0;
	$i= 1 ;
	$j= 1 ;
	$last_id=1;
	while (@$row1 = pg_fetch_array($r1)){
              if (($j<=$max_row1) AND ($i >= $mulai1)){
              $no=$i;
	   ?>
            <tr>
                <td class="TBL_BODY" align="left"><?=$row1["no_akun"] ?> </td>
                <td class="TBL_BODY" align="left"><?=$row1["nama"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row1["jumlah"],2,",",".") ?></td>
            </tr>
            
            <?
            $totalpendapatan=$totalpendapatan+$row1["jumlah"];
            ;$j++;}
        $i++;		
        } 
        
        ?>
        <tr>
            <td class="TBL_BODY" colspan="2" align="Right"><b><u><i>TOTAL PENDAPATAN &nbsp;&nbsp;</i></u></b></td>
            <td class="TBL_BODY" align="right"><b><u><i><?=number_format($totalpendapatan,2,",",".")?></i></u></b></td>
        </tr>
        <tr>
            <td colspan="3" class="TBL_HEAD" >BEBAN & KEWAJIBAN </td>
        </tr>
      <?
        $row2=0;
        $totalbeban=0;
	$i2= 1 ;
	$j2= 1 ;
	$last_id=1;
	while (@$row2 = pg_fetch_array($r2)){
              if (($j2<=$max_row1) AND ($i2 >= $mulai1)){
              $no=$i2;
	   ?>
            <tr>
                <td class="TBL_BODY" align="left"><?=$row2["no_akun"] ?> </td>
                <td class="TBL_BODY" align="left"><?=$row2["nama"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row2["jumlah"],2,",",".") ?></td>
            </tr>
            
            <?
            $totalbeban=$totalbeban+$row2["jumlah"];
            ;$j2++;}
        $i2++;		
        } 
        
        ?>
        <tr>
            <td class="TBL_BODY" colspan="2" align="Right"><b><u><i>TOTAL BEBAN &nbsp;&nbsp;</i></u></b></td>
            <td class="TBL_BODY" align="right"><b><u><i><?=number_format($totalbeban,2,",",".")?></i></u></b></td>
        </tr>
        <tr>
            <td class="TBL_HEAD" colspan="2" align="Right"><b><u><i>TOTAL LABA/RUGI &nbsp;&nbsp;</i></u></b></td>
            <td class="TBL_HEAD" align="right"><b><u><i><?=number_format($totalpendapatan-$totalbeban,2,",",".")?></i></u></b></td>
        </tr>
</TABLE>
