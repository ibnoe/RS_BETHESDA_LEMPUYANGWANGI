<?

$PID = "lap_index_pegawai";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("startup.php");

//echo "<br>";
$is_selected = getFromTable(
    "select count(id) ".
    "from rs00018 ".
    "where unit_medis_fungsional_id like '%" . $_GET["mPEG"] . "%' ".
    "    and id like '%" . $_GET["mJAB"] . "%'") > 0;
if (!$GLOBALS['print']){
title_print("<img src='icon/informasi-2.gif' align='absmiddle' > LAPORAN INDEX DATA PEGAWAI");
title_excel("p=lap_index_pegawai");
}


         if (isset($_GET["e"])) {
            $ext = "DISABLED";
        } else {
            $ext = "OnChange = 'Form1.submit();'";
        }
        echo "<br>";
        if (!$GLOBALS['print']){
		$f = new Form($SC, "GET", "NAME=Form1");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->selectSQL("mPEG", "Unit Medis",
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'PEG' and tc!='000' ".
            "order by tdesc", $_GET["mPEG"],
            $ext);
        $f->selectSQL("mJAB", "Pendidikan",
            "select '' as id, '' as jabatan_medis_fungsional union " .
            "select id, jabatan_medis_fungsional ".
            "from rs00018 ".
            "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
            "order by jabatan_medis_fungsional", $_GET["mJAB"],
            $ext);
		
        $f->execute();
		}else{
		$f = new Form($SC, "GET", "NAME=Form1");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->selectSQL("mPEG", "Unit Medis",
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'PEG' and tc!='000' ".
            "order by tdesc", $_GET["mPEG"],
            "disabled");
        $f->selectSQL("mJAB", "Pendidikan",
            "select '' as id, '' as jabatan_medis_fungsional union " .
            "select id, jabatan_medis_fungsional ".
            "from rs00018 ".
            "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
            "order by jabatan_medis_fungsional", $_GET["mJAB"],
            "disabled");
		
		$f->execute();
		}


    if ($is_selected) {
	//unit medis
	$sql_unit="	select a.tc, a.tdesc 
				from rs00001 a
				left join rs00018 b on b.unit_medis_fungsional_id = a.tc
				left join rs00017 c on c.jabatan_medis_fungsional_id = b.unit_medis_fungsional_id
				where a.tt = 'PEG' and a.tc!='000' and a.tc like '%".$_GET["mPEG"]."%' 
				group by a.tc,a.tdesc
				order by a.tc ";
		@$r1 = pg_query($con,$sql_unit);
        @$n1 = pg_num_rows($r1);

		$max_row= 9999999999 ;
		$mulai = $HTTP_GET_VARS["rec"] ;
		if (!$mulai){$mulai=1;}
		

		?>
		<br><br>
<table width="100%" align="center">
	<tr>
		<td align="center" class="TBL_JUDUL">INDEX DATA PEGAWAI RSUD<br>Dr. ACHMAD MOCHTAR BUKITTINGGI</td>
	</tr>
       
</table>
<br><br>
  <table CLASS=TBL_BORDER width="100%" border="0">

    <tr>
		<td class="TBL_HEAD" rowspan="2" width="4%"><div align="center">NO. </div></td>
		<td class="TBL_HEAD" rowspan="2" ><div align="center">NAMA PEGAWAI </div></td>
		<td class="TBL_HEAD" rowspan="2" width="4%"><div align="center">GOL</div></td>
		<td class="TBL_HEAD" colspan="3" ><div align="center">GAJI</div></td>
		<td class="TBL_HEAD" colspan="3" ><div align="center">COMPETENCY/PENDIDIKAN</div></td>
		<td class="TBL_HEAD" colspan="3" ><div align="center">RISK</div></td>
		<td class="TBL_HEAD" colspan="3" ><div align="center">EMERGENCY</div></td>
		<td class="TBL_HEAD" colspan="3" ><div align="center">POSITION</div></td>
		<td class="TBL_HEAD" colspan="3" ><div align="center">PERFORMANCE</div></td>
		<td class="TBL_HEAD" rowspan="2" ><div align="center">SKOR<br>TOTAL</div></td>
    </tr>
	
	<tr>
		<td class="TBL_HEAD" width="4%"><div align="center">INDEX </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">RATING </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">SCORE</div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">INDEX </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">RATING </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">SCORE</div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">INDEX </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">RATING </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">SCORE</div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">INDEX </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">RATING </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">SCORE</div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">INDEX </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">RATING </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">SCORE</div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">INDEX </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">RATING </div></td>
		<td class="TBL_HEAD" width="4%"><div align="center">SCORE</div></td>
    </tr>
    <?
	
    $row1=0;
    $i= 1 ;
    $j= 1 ;
    $last_id=1;
    while (@$row1 = pg_fetch_array($r1)){
        if (($j<=$max_row) AND ($i >= $mulai)){
                $no=$i
                ?>
                <tr valign="top" class="<? ?>" >
					<hr><td class="TBL_BODY" align="left" colspan="22"><b><?=$row1["tc"] ?> - <?=$row1["tdesc"] ?></b></td>
                </tr>
				<?
//Pendidikan
	$sql_pdk="	select a.id, a.jabatan_medis_fungsional, a.unit_medis_fungsional_id 
				from rs00018 a 
		where a.unit_medis_fungsional_id like '%".$_GET["mPEG"]."%' and  a.id like '%".$_GET["mJAB"]."%'
		group by a.id, a.jabatan_medis_fungsional,a.unit_medis_fungsional_id order by a.id, a.unit_medis_fungsional_id,a.jabatan_medis_fungsional ";
	@$r2 = pg_query($con,$sql_pdk);
	@$n2 = pg_num_rows($r2);

	$max_row2= 9999999999 ;
	$mulai2 = $HTTP_GET_VARS["rec"] ;
	if (!$mulai2){$mulai2=1;}
		
    $row2=0;
    $i2= 1 ;
    $j2= 1 ;
    $last_id2=1;
    while (@$row2 = pg_fetch_array($r2)){
        if (($j2<=$max_row2) AND ($i2 >= $mulai2)){
                $no2=$i2;
		if ($row1["tc"]==$row2["unit_medis_fungsional_id"]){
	?>
		<tr >
		<td class="TBL_BODY2" align="left" colspan="22"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$row2["unit_medis_fungsional_id"] ?> - <?=$row2["id"] ?>  - <?=$row2["jabatan_medis_fungsional"] ?></b></td>
		</tr>
    <?
	//isi
	$sql3 = "select a.jabatan_medis_fungsional_id,a.nama, c.tdesc as gol, 
	e.index_gaji, 1 as rating_gaji, (e.index_gaji * 1) as score_gaji,
	g.index as pendidikan,  3 as rating_pen, (g.index * 3) as score_pen,
	h.index as risk,  3 as rating_risk, (h.index * 3) as score_risk,
	i.index as emergency,  3 as rating_eme, (i.index * 3) as score_eme,
	j.index as posisi,  3 as rating_pos, (j.index * 3) as score_pos,
	((e.index_gaji * 1) *2) as forma,  4 as rating_forma, 
	(((e.index_gaji * 1) *2) * 4) as score_forma, 
	((e.index_gaji * 1)+(g.index * 3)+(h.index * 3)+(i.index * 3)+(j.index * 3)+((e.index_gaji * 2)  * 4)) as total,
	a.id
from rs00017 a 
	left outer join rs00027 d ON a.rs00027_id = d.id 
	left outer join index_pegawai e ON e.id_dok = a.id::text
	left outer join index_gaji g ON g.tc = e.pendidikan_id and g.tt='CMP'
	left outer join index_gaji h ON h.tc = e.risk_id and h.tt='RSK'
	left outer join index_gaji i ON i.tc = e.emergency_id and i.tt='EMG'
	left outer join index_gaji j ON j.tc = e.posisi_id and j.tt='PST'
	left outer join rs00018 f ON f.id = a.jabatan_medis_fungsional_id 
	left outer join rs00001 b ON b.tc = a.jjd_id   and b.tt='JJD' 
	left outer join rs00001 c ON c.tc = e.gol  and c.tt='GRP' 
where (a.status = 'peg' or a.status is null)  and a.jabatan_medis_fungsional_id like '%".$_GET["mJAB"]."%'
	group by a.jabatan_medis_fungsional_id,a.id,a.nama, c.tdesc,e.gaji,e.index_gaji,g.index, h.index,i.index,j.index
	 ";
//echo $sql;
		@$r3 = pg_query($con,$sql3);
        @$n3 = pg_num_rows($r3);

		$max_row3= 9999999999 ;
		$mulai3 = $HTTP_GET_VARS["rec"] ;
		if (!$mulai3){$mulai3=1;}
	    
    $jumlah3= 0;
    $row3=0;
    $i3= 1 ;
    $j3= 1 ;
    $last_id3=1;
    while (@$row3 = pg_fetch_array($r3)){
        if (($j3<=$max_row3) AND ($i3 >= $mulai3)){
                $no3=$i3;
				//if ($row3["jabatan_medis_fungsional_id"]==$row2["id"]){
				if ($row2["id"]==$row3["jabatan_medis_fungsional_id"]){
                ?>
                <tr>
                        <td class="TBL_BODY" align="center"><?= $no3 ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row3["nama"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["gol"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row3["index_gaji"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["rating_gaji"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["score_gaji"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row3["pendidikan"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row3["rating_pen"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["score_pen"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["risk"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row3["rating_risk"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["score_risk"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["emergency"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row3["rating_eme"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["score_eme"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["posisi"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row3["rating_pos"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["score_pos"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row3["forma"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row3["rating_forma"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row3["score_forma"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row3["total"] ?> </td>
                </tr>	
		<?
		;$j3++;}

    $i3++;}
	}
	
	
		;$j2++;}

    $i2++;}
	}
	
	;$j++;
        }
        $i++;
	}
	
	
		

?>
  </table>
  <?
    }
?>
