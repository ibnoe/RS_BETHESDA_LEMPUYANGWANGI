<?

$PID = "index_pegawai";
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
    "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
    "    and id = '" . $_GET["mJAB"] . "'") > 0;
if (!$GLOBALS['print']){
title_print("<img src='icon/informasi-2.gif' align='absmiddle' > INDEX DATA PEGAWAI");
}



if($_GET["action"]=="edit") {
		$r2 = pg_query($con,
            "select a.nama,a.nip, e.gol as gol, e.gaji, (e.gaji/100000) as index_gaji,e.pendidikan_id as pendidikan, e.risk_id as risk,
					e.emergency_id as emergency,e.posisi_id as posisi,a.id
			from rs00017 a 
				left outer join rs00027 d ON a.rs00027_id = d.id 
				left outer join index_pegawai e ON e.id_dok = a.id::text
				left outer join index_gaji g ON g.tc = e.pendidikan_id and g.tt='CMP'
				left outer join index_gaji h ON h.tc = e.risk_id and h.tt='RSK'
				left outer join index_gaji i ON h.tc = e.emergency_id and i.tt='EMG'
				left outer join index_gaji j ON h.tc = e.posisi_id and j.tt='PST'
				left outer join rs00018 f ON f.id = a.jabatan_medis_fungsional_id 
				left outer join rs00001 b ON b.tc = a.jjd_id   and b.tt='JJD' 
				left outer join rs00001 c ON e.gol = c.tc and c.tt='GRP' ".
            "where a.id = '".$_GET["id"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
		
		$f = new Form("$SC", "GET", "name='Form2'");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->hidden("mPEG", $_GET["mPEG"]);
        $f->hidden("mJAB", $_GET["mJAB"]);
	$f->hidden("f_id_dok", $_GET["id"]);
        $f->hidden("action", $_GET["action"]);
        $f->hidden("e", "new");
        $f->text("id","ID Pegawai",12,12,$_GET["id"],"DISABLED");
        $f->text("nama","N a m a",50,50,$d2->nama,"DISABLED");
        $f->text("nip","N I P",30,30,$d2->nip,"DISABLED");
        $f->selectSQL("f_gol", "Golongan",
                  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from rs00001 ".
                  "where tt = 'GRP' and tc != '000' ".
                  "order by tdesc",
                  $d2->gol);
        $f->text("f_gaji","Gaji",30,30,$d2->gaji);
		$f->text("index_gaji","Index Gaji",30,30,number_format($d2->index_gaji,0,0,0),"disabled");
		$f->selectSQL("f_pendidikan_id", "Pendidikan Terakhir",
                  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from index_gaji ".
                  "where tt = 'CMP' and tc != '000' ".
                  "order by tc",
                  $d2->pendidikan);
		$f->selectSQL("f_risk_id", "Risk",
                  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from index_gaji ".
                  "where tt = 'RSK' and tc != '000' ".
                  "order by tc",
                  $d2->risk);		
		$f->selectSQL("f_emergency_id", "Emergency",
                  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from index_gaji ".
                  "where tt = 'EMG' and tc != '000' ".
                  "order by tc",
                  $d2->emergency);	
		$f->selectSQL("f_posisi_id", "Position",
                  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from index_gaji ".
                  "where tt = 'PST' and tc != '000' ".
                  "order by tc",
                  $d2->posisi);
        $f->submit(" Simpan ", "onClick='Form2.method=\"POST\";Form2.action=\"actions/809_index.insert.php\";'");
        $f->execute();

}elseif($_GET["action"]=="view") {
	echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&&mPEG={$_GET["mPEG"]}&mJAB={$_GET["mJAB"]}'>".icon("back","Kembali")."</a></DIV>";
		$r2 = pg_query($con,
            "select a.nama,a.nip,f.jabatan_medis_fungsional as jabatan,b.tdesc as jjg, e.gol as gol, e.gaji, 
				(e.gaji/100000) as index_gaji,g.tdesc as pendidikan,g.index as index_com, h.tdesc as risk,h.index as index_risk,
				i.tdesc as emergency,i.index as index_eme, j.tdesc as posisi,j.index as index_pos
			from rs00017 a 
				left outer join rs00027 d ON a.rs00027_id = d.id 
				left outer join index_pegawai e ON e.id_dok = a.id::text
				left outer join index_gaji g ON g.tc = e.pendidikan_id and g.tt='CMP'
				left outer join index_gaji h ON h.tc = e.risk_id and h.tt='RSK'
				left outer join index_gaji i ON i.tc = e.emergency_id and i.tt='EMG'
				left outer join index_gaji j ON j.tc = e.posisi_id and j.tt='PST'
				left outer join rs00018 f ON f.id = a.jabatan_medis_fungsional_id 
				left outer join rs00001 b ON b.tc = a.jjd_id   and b.tt='JJD' 
				left outer join rs00001 c ON e.gol = c.tc and c.tt='GRP' ".
            "where a.id = '".$_GET["id"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
echo "<br><br>";		
		titlecashier4('RSUD Dr. ACHMAD MOCHTAR BUKITTINGGI');

?>
<br>
<br>
<table border="0" width="50%">
	<tr>
		<td class="TITLE_SIM3" width="35%"><b>Nama</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->nama; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Nip</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->nip; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Jabatan</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->jabatan; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Jabatan Medis Fungsional</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->jjg; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Gaji</b></td>
		<td class="TITLE_SIM3"><b>: Rp. <?= number_format($d2->gaji,2,",","."); ?></b></td>
	</tr>
</table>
<br>
<br>
<table border="0" width="100%">
	<tr>
		<td class="TBL_HEAD" width="5%" ALIGN="CENTER"><b>NO.</b></td>
		<td class="TBL_HEAD" width="35%" ALIGN="CENTER"><b>OBJECT</b></td>
		<td class="TBL_HEAD" width="20%" ALIGN="CENTER"><b>INDEX</b></td>
		<td class="TBL_HEAD" width="20%" ALIGN="CENTER"><b>RATING</b></td>
		<td class="TBL_HEAD" width="20%" ALIGN="CENTER"><b>SCORE</b></td>
	</tr>
	<tr>
		<td class="TBL_BODY" ><div align="center"><b>1.</b></div></td>
		<td class="TBL_BODY" ><b>Basic <br>&nbsp;&nbsp;&nbsp;&nbsp;(Gaji Pokok : Rp. <?= number_format($d2->gaji,2,",","."); ?> / Rp. 100.000,00)</b></td>
		<td class="TBL_BODY" ><div align="center"><b><?= number_format($d2->index_gaji); ?></b></div></td>
		<td class="TBL_BODY" ><div align="center"><b>1</b></div></td>
		<td class="TBL_BODY" ><div align="center"><b><?= number_format($d2->index_gaji*1); ?></b></div></td>
	</tr>
	<tr>
		<td class="TBL_BODY" ><div align="center"><b>2.</b></div></td>
		<td class="TBL_BODY" ><b>Competency / Pendidikan <br>&nbsp;&nbsp;&nbsp;&nbsp; <?= $d2->pendidikan; ?> </b></td>
		<td class="TBL_BODY" ><div align="center"><b><?= $d2->index_com; ?></b></div></td>
		<td class="TBL_BODY" ><div align="center"><b>3</b></div></td>
		<td class="TBL_BODY" ><div align="center"><b><?= number_format($d2->index_com*3); ?></b></div></td>
	</tr>
	<tr>
		<td class="TBL_BODY" ><div align="center"><b>3.</b></div></td>
		<td class="TBL_BODY" ><b>Risk <br>&nbsp;&nbsp;&nbsp;&nbsp; <?= $d2->risk; ?> </b></td>
		<td class="TBL_BODY" ><div align="center"><b><?= $d2->index_risk; ?></b></div></td>
		<td class="TBL_BODY" ><div align="center"><b>3</b></div></td>
		<td class="TBL_BODY" ><div align="center"><b><?= number_format($d2->index_risk*3); ?></b></div></td>
	</tr>
	<tr>
		<td class="TBL_BODY" ><div align="center"><b>4.</b></div></td>
		<td class="TBL_BODY" ><b>Emergency <br>&nbsp;&nbsp;&nbsp;&nbsp; <?= $d2->emergency; ?> </b></td>
		<td class="TBL_BODY" ><div align="center"><b><?= $d2->index_eme; ?></b></div></td>
		<td class="TBL_BODY" ><div align="center"><b>3</b></div></td>
		<td class="TBL_BODY" ><div align="center"><b><?= number_format($d2->index_eme*3); ?></b></div></td>
	</tr>
	<tr>
		<td class="TBL_BODY" ><div align="center"><b>5.</b></div></td>
		<td class="TBL_BODY" ><b>Emergency <br>&nbsp;&nbsp;&nbsp;&nbsp; <?= $d2->posisi; ?> </b></td>
		<td class="TBL_BODY" ><div align="center"><b><?= $d2->index_pos; ?></b></div></td>
		<td class="TBL_BODY" ><div align="center"><b>3</b></div></td>
		<td class="TBL_BODY" ><div align="center"><b><?= number_format($d2->index_pos*3); ?></b></div></td>
	</tr>
	<tr>
		<td class="TBL_BODY" ><div align="center"><b>6.</b></div></td>
		<td class="TBL_BODY" ><b>Performance <br>&nbsp;&nbsp;&nbsp;&nbsp; 2 x Basic Index = Index Kinerja </b></td>
		<td class="TBL_BODY" ><div align="center"><b><?= number_format($d2->index_gaji)*2; ?></b></div></td>
		<td class="TBL_BODY" ><div align="center"><b>4</b></div></td>
		<td class="TBL_BODY" ><div align="center"><b><?= (number_format($d2->index_gaji)*2)*4; ?></b></div></td>
	</tr>
	<? $total = number_format($d2->index_gaji*1) + number_format($d2->index_com*3) +
				number_format($d2->index_risk*3) + number_format($d2->index_eme*3) +
				number_format($d2->index_pos*3) + ((number_format($d2->index_gaji)*2)*4) ;?>
	<tr>
		<td class="TBL_BODY" colspan="4"><div align="center"><b>TOTAL</b></div></td>
		<td class="TBL_BODY" ><div align="center"><b><?= number_format($total); ?></b></div></td>
	</tr>
</table>
<?		

} else {
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
		$bln=date('M Y');
		echo "<table width='100%'><tr><td align='right'>";
		$f = new Form($SC, "GET","NAME=Form4");
	    $f->hidden("p", $PID);
		$f->hidden("mPEG", $_GET["mPEG"]);
		$f->hidden("mJAB", $_GET["mJAB"]);
	    if (!$GLOBALS['print']){
	    	$f->search("search","Pencarian Nama atau Alamat",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form4.submit();'");
		}
	    $f->execute();
		echo "</td></tr></table> <br><br>";
    if ($is_selected) {
/* 		if (!$GLOBALS['print']){
		echo "<DIV ALIGN=CENTER><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2 ><TR>";
        echo "<TD ALIGN=CENTER CLASS=TBL_JUDUL>PENCARIAN DATA PEGAWAI RSUD Dr. ACHMAD MOCHTAR BUKITTINGGI</TD>";
        echo "</TR><TR><TD ALIGN=CENTER CLASS=TBL_JUDUL> </TD>";
        echo "</TR><tr>";
		
		echo "</tr></FORM></TABLE></DIV>";
		} */

        $sql =
            "select a.nama, c.tdesc as gol, e.gaji, e.index_gaji,g.tdesc as pendidikan, h.tdesc as risk,i.tdesc as emergency,j.tdesc as posisi,a.id
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
			where a.jabatan_medis_fungsional_id = '".$_GET["mJAB"]."' and (upper(a.nama) like '%".strtoupper($_GET["search"])."%' or upper(a.alamat) like '%".strtoupper($_GET["search"])."%') ".
               "and (a.status = 'peg' or a.status is null)  
				group by a.id,a.nama, c.tdesc,e.gaji,e.index_gaji,g.tdesc, h.tdesc,i.tdesc,j.tdesc	order by a.nama ";

		@$r1 = pg_query($con,$sql);
        @$n1 = pg_num_rows($r1);

		$max_row= 9999999999 ;
		$mulai = $HTTP_GET_VARS["rec"] ;
		if (!$mulai){$mulai=1;}

		$unit=getFromTable("select tdesc from rs00001 where tt = 'PEG' and tc='".$_GET["mPEG"]."'");
        $jab=getFromTable("select jabatan_medis_fungsional from rs00018 
							where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' and id = '".$_GET["mJAB"]."'");
			
		?>
<table width="100%" align="center">
	<tr>
		<td align="center" class="TBL_JUDUL">INDEX DATA PEGAWAI RSUD Dr. ACHMAD MOCHTAR BUKITTINGGI</td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $unit ?> - <?= $jab?></td>
	</tr>
       
</table>
<br>
  <table CLASS=TBL_BORDER width="100%" border="0">

    <tr>
      <td class="TBL_HEAD" width="4%"><div align="center">NO. </div></td>
      <td class="TBL_HEAD" ><div align="center">NAMA PEGAWAI </div></td>
      <td class="TBL_HEAD" width="4%"><div align="center">GOL</div></td>
      <td class="TBL_HEAD" ><div align="center">GAJI</div></td>
      <td class="TBL_HEAD" width="5%"><div align="center">INDEX<br>GAJI</div></td>
      <td class="TBL_HEAD" ><div align="center">COMPETENCY<BR>PENDIDIKAN</div></td>
      <td class="TBL_HEAD" ><div align="center">RISK</div></td>
      <td class="TBL_HEAD" ><div align="center">EMERGENCY</div></td>
	  <td class="TBL_HEAD" ><div align="center">POSITION</div></td>
	  <? if (!$GLOBALS['print']){ ?>
	  <td class="TBL_HEAD" width="4%"><div align="center">EDIT</div></td>
	  <td class="TBL_HEAD" width="4%"><div align="center">VIEW</div></td>
	  <? } ?>
    </tr>
    <?
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
                        <td class="TBL_BODY" align="left"><?=$row1["nama"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["gol"] ?> </td>
						<td class="TBL_BODY" align="right">Rp. <?=number_format($row1["gaji"] ,2,",",".")?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["index_gaji"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["pendidikan"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["risk"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["emergency"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["posisi"] ?> </td>
						<? if (!$GLOBALS['print']){ ?>
						<td class="TBL_BODY" align="center"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=edit&mPEG=".$_GET["mPEG"]."&mJAB=".$_GET["mJAB"]."&id=".$row1['id']."'>".
                        icon("edit","Edit")."</A>"; ?> </td>
						<td class="TBL_BODY" align="center"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=view&mPEG=".$_GET["mPEG"]."&mJAB=".$_GET["mJAB"]."&id=".$row1['id']."'>".
                        icon("view","Lihat")."</A>"; ?> </td>
						<? } ?>
                </tr>

                <?;$j++;
        }
        $i++;
}
?>
  </table>

  <?
    }
}
?>
