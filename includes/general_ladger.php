<?php

$PID = "general_ladger";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > LAPORAN BUKU BESAR");
		//title_excel("general_ladger");
		title_excel("general_ladger&mPeriode=".$_GET["mPeriode"]."");
    } else {
    	title("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > LAPORAN BUKU BESAR");
		//title_excel("general_ladger");
		title_excel("general_ladger&mPeriode=".$_GET["mPeriode"]."");
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

$sql = "select a.no_akun, b.nama from jurnal_umum a, akun_master b
        where a.no_akun=b.kode and (a.tanggal_akun between '$sql_1' and '$sql_2')
        group by a.no_akun, b.nama
        order by a.no_akun ";



        @$r1 = pg_query($con,$sql);
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
		<td align="center" class="TBL_JUDUL">GENERAL LADGER</td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?></td>
	</tr>
</table>

<br>
<br>
<TABLE BORDER="0" width="100%" CLASS="TBL_BORDER">
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
                <td class="TBL_HEAD2" colspan="5" align="left"><?=$row1["no_akun"] ?> - <?=$row1["nama"] ?></td>
            </tr>
                    <tr>
                        <td class="TBL_HEAD" align="center">Tanggal</td>
                        <td class="TBL_HEAD" align="center">No. Akun</td>
                        <td class="TBL_HEAD" align="center">Keterangan</td>
                        <td class="TBL_HEAD" align="center">Debet</td>
                        <td class="TBL_HEAD" align="center">Kredit</td>
                    </tr>
            <?

            $sql2 = "select no_akun, to_char(tanggal_akun,'dd Mon yyyy') as tanggal_akun, keterangan, sum(debet) as debet, sum(kredit) as kredit
                    from jurnal_umum
                    where (tanggal_akun between '$sql_1' and '$sql_2')
                    group by no_akun, tanggal_akun, keterangan";

            @$r2 = pg_query($con,$sql2);
            @$n2 = pg_num_rows($r2);

            $max_row2= 200 ;
            $mulai2 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai2){$mulai2=1;}

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

            ?>
            <tr>
                <td class="TBL_BODY" align="center"><?=$row2["tanggal_akun"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row2["no_akun"] ?></td>
                <td class="TBL_BODY" align="left"><?=$row2["keterangan"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row2["debet"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row2["kredit"],2,",",".") ?></td>
            </tr>
	   <?
             $totaldebet=$totaldebet+$row2["debet"];
             $totalkredit=$totalkredit+$row2["kredit"];

             $total=$totaldebet-$totalkredit;
             ;$j2++;}
          $i2++;}
        }?>
            <tr>
		<td class="TBL_BODY" colspan="4" align="Right"><b><u><i>TOTAL <?= $row1["nama"]?></i></u></b></td>
		<td class="TBL_BODY" align="right"><b><u><i><?=number_format($total,2,",",".")?></i></u></b></td>
            </tr>
            <tr>
                <td colspan="5" ><? ?></td>
            </tr>
            
            <?;$j++;}
        $i++;		
        } 
        ?>
</TABLE>