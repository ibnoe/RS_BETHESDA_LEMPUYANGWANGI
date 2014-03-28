<?php


$PID = "lap_aruskas";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-aruskas.png' align='absmiddle' > LAPORAN ARUS KAS");
		//title_excel("lap_aruskas");
		title_excel("lap_aruskas&mPeriode=".$_GET["mPeriode"]."");
    } else {
    	title("<img src='icon/akuntansi-aruskas.png' align='absmiddle' > LAPORAN ARUS KAS");
		//title_excel("lap_aruskas");
		title_excel("lap_aruskas&mPeriode=".$_GET["mPeriode"]."");
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
    

//---------------agung 04/2011---------------

$sql = "select no_akun, nama from rsv_jurnal_umum
        where (tanggal_akun between '$sql_1' and '$sql_2')
        group by no_akun, nama, tanggal_akun
        order by no_akun ";

        @$r1 = pg_query($con,$sql);
        @$n1 = pg_num_rows($r1);

        $max_row1= 200 ;
        $mulai1 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai1){$mulai1=1;}

?>
<table width="75%" align="center">
	<tr>
		<td align="center" class="TBL_JUDUL">LAPORAN ARUS KAS</td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?></td>
	</tr>
        <tr>
		<td align="right" > dalam Rupiah (Rp)</td>
	</tr>
</table>