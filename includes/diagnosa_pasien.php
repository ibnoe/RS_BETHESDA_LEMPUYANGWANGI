<? // Agung S. 25-10-2011


$PID = "diagnosa_pasien";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title("<img src='icon/medical-record-2.gif' align='absmiddle' > Laporan Diagnosa");
    } else {
    	title("<img src='icon/medical-record.gif' align='absmiddle' > Laporan Diagnosa");
    }
    //title("LAPORAN PENDAPATAN JASA MEDIS");
    //$ext = "OnChange = 'Form1.submit();'";
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
	title_excel("diagnosa_pasien");

    $f->PgConn = $con;
    $f->hidden("p", $PID);

    
	if (!$GLOBALS['print']){
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
		    
	    $tgl_sakjane = $_GET[tanggal2D] + 1;	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
	
	    }

    $f->selectArray("rawat_inap", "U n i t",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "onChange='document.Form1.submit();'; ");
	$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
	if ($_GET["rawat_inap"]=="Y"){
	$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
                                             SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
                                             order by tdesc ",$_GET["mRAWAT"], "");
	}elseif ($_GET["rawat_inap"]=="I"){
	$f->selectSQL("mINAP", "Bangsal ","select d.bangsal, d.bangsal as bangsal
                       from rs00010 as a 
                           join rs00012 as b on a.bangsal_id = b.id 
                           join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
                           join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
                           join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
group by d.bangsal
order by d.bangsal " ,$_GET["mINAP"], "");
	}else{}
	
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
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
			
	    } else {
		    
	    $tgl_sakjane = $_GET[tanggal2D] + 1;	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
	
	    }

    	$f->execute();
	}

    echo "<br>";
$a=$_GET["rawat_inap"];
if ($_GET["rawat_inap"]=='Y'){
$b=$_GET["mRAWAT"];
}elseif($_GET["rawat_inap"]=='N'){
$b= "100";
}

 if ($_GET["rawat_inap"]=='Y' or $_GET["rawat_inap"]=='N'){
 $sql="  select item_id, description, sum(umur7hari) as umur7hari,sum(umur28hari) as umur28hari,sum(umur1thn) as umur1thn,sum(umur4thn) as umur4thn,sum(umur9thn) as umur9thn,
	sum(umur14thn) as umur14thn,sum(umur19thn) as umur19thn,sum(umur44thn) as umur44thn,sum(umur54thn) as umur54thn,sum(umur59thn) as umur59thn,
	sum(umur69thn) as umur69thn,sum(umur70thn) as umur70thn,sum(jk_l) as jk_l,sum(jk_p) as jk_p,sum(jumlah) as jumlah
from rsv_icd2
where rawat_inap = '$a' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2') and poli::text = '$b' 
group by item_id, description
order by item_id ";
 }elseif ($_GET["rawat_inap"]=='I'){
 $sql="  select a.item_id, a.description, sum(umur7hari) as umur7hari,sum(umur28hari) as umur28hari,sum(umur1thn) as umur1thn,sum(umur4thn) as umur4thn,sum(umur9thn) as umur9thn,
	sum(umur14thn) as umur14thn,sum(umur19thn) as umur19thn,sum(umur44thn) as umur44thn,sum(umur54thn) as umur54thn,sum(umur59thn) as umur59thn,
	sum(umur69thn) as umur69thn,sum(umur70thn) as umur70thn,sum(jk_l) as jk_l,sum(jk_p) as jk_p,sum(jumlah) as jumlah,h.bangsal
		from rsv_icd2 a
		join rs00012 as f on a.bangsal_id = f.id 
		join rs00012 as h on h.hierarchy = substr(f.hierarchy,1,3) || '000000000000' 
		where a.rawat_inap like '%".$_GET["rawat_inap"]."%' and (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') and (h.bangsal like '%".$_GET["mINAP"]."%')
		group by a.item_id, a.description,h.bangsal
		order by a.item_id ";
 
 }


 if ($_GET["rawat_inap"]=='I'){
 $rawat='RAWAT INAP';
 }elseif ($_GET["rawat_inap"]=='N'){
 $rawat='IGD';
 }elseif ($_GET["rawat_inap"]=='-'){
 $rawat='SEMUA POLI';
 }else{
 $rawat='RAWAT JALAN';
 $poli=getFromTable("select tdesc from rs00001 where tt='LYN' and tc='".$_GET["mRAWAT"]."' ");

 $ket = 'PADA ';
 }
        @$r1 = pg_query($con,$sql);
        @$n1 = pg_num_rows($r1);

	$max_row= 999999999 ;
	$mulai = $HTTP_GET_VARS["rec"] ;
	if (!$mulai){$mulai=1;}
title_print("");
?>
<table width="100%" align="center">
	<tr>
		<td align="center" class="TBL_JUDUL">DIAGNOSA PENDERITA <?= $rawat?></td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?><?= $poli?></td>
	</tr>
       
</table>
<br>
  <table CLASS=TBL_BORDER width="100%" border="0">
    <tr>
      <td class="TBL_HEAD" width="3%" rowspan="2"><div align="center">No.</div></td>
      <td class="TBL_HEAD" width="27%" rowspan="2"><div align="center">Diagnosa</div></td>
      <td class="TBL_HEAD" colspan="12"><div align="center">Jumlah Pasien (Menurut Golongan Umur) dalam Tahun </div></td>
      <td class="TBL_HEAD" colspan="2"><div align="center">Kelamin</div></td>
      <td class="TBL_HEAD" width="5%" rowspan="2"><div align="center">Jumlah</div></td>
      <td class="TBL_HEAD" width="4%" rowspan="2"><div align="center">Ket.</div></td>
    </tr>
    <tr>
      <td class="TBL_HEAD" width="4%"><div align="center">0-7 Hr </div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">8-28 Hr </div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">&lt;1</div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">1-4</div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">5-9</div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">10-14</div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">15-19</div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">20-44</div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">45-54</div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">55-59</div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">60-69</div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">70+</div></td>
      <td class="TBL_HEAD" width="5%"><div align="center">L</div></td>
      <td class="TBL_HEAD" width="5%"><div align="center">P</div></td>
    </tr>
    <?
    $totL= 0;
    $totP= 0;
    $jumlah= 0;
    $row1=0;
    $i= 1 ;
    $j= 1 ;
    $last_id=1;
    while (@$row1 = pg_fetch_array($r1)){
        if (($j<=$max_row) AND ($i >= $mulai)){
                $no=$i
                ?>
                <tr valign="top" class="<? ?>" >
                        <td class="TBL_BODY" align="center"><?=$no ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["description"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur7hari"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur28hari"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur1thn"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur4thn"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur9thn"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur14thn"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur19thn"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur44thn"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur54thn"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur59thn"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur69thn"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["umur70thn"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["jk_l"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["jk_p"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"><?=$row1["jumlah"] ?> </td>
                        <td class="TBL_BODY" valign="middle" align="center"> </td>
                </tr>

                <?
                $totL=$totL+$row1["jk_l"] ;
                $totP=$totP+$row1["jk_p"] ;
                $jumlah=$jumlah+$row1["jumlah"] ;
                ?>
                <?;$j++;
        }
        $i++;
}
?>

                <tr valign="top" class="TBL_HEAD">
                <td class="TBL_HEAD" align="center" colspan="14" height="25" valign="middle"><b> TOTAL </b></td>
                <td class="TBL_HEAD" align="center" valign="middle"><b><?=$totL ?></b></td>
                <td class="TBL_HEAD" align="center" valign="middle"><b><?=$totP ?></b></td>
                <td class="TBL_HEAD" align="center" valign="middle"><b><?=$jumlah ?></b></td>
                <td class="TBL_HEAD" align="center" valign="middle"><b> </b></td>
                </tr>


  </table>