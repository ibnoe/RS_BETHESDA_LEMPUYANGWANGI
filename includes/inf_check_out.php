<?php  
//Febri 24112012
//Gema Perbangsa, 19092013 Menambahkan batal checkout

$PID = "inf_check_out";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;
require_once("startup.php");
   title("<img src='icon/rawat-inap-2.gif' align='absmiddle' > INFORMASI CHECK OUT");
if($_GET['batal']==1){   
	$SQL = "select distinct b.mr_no, b.alm_tetap as alamat, a.no_reg, upper(b.nama)as nama, 
            ( SELECT ts_check_in FROM rs00010 x WHERE x.id = 
			(SELECT max(id) FROM rs00010 WHERE no_reg = a.no_reg AND awal::text = '1'))  AS check_in,
            a.ts_calc_stop as check_out, a.nama as user, d.hierarchy  from 
            rs00010 as a
            join rs00006 as c on a.no_reg = c.id 
            join rs00002 as b on c.mr_no = b.mr_no 
            join rs00012 as d on d.id= a.bangsal_id  where a.nama is not null AND c.id = '".$_GET['rg']."'";
    $row = pg_fetch_array(pg_query($SQL));
    ?>
    <form action="actions/batal_checkout.php" method="post" onsubmit="return confirm('Yakin Mau di Batalkan ??')">
    <input type="hidden" name="no_reg" value="<?php echo $row['no_reg'];?>"/>
	<table>
		<tr>
			<td class="TBL_BODY">No.CM</td><td class="TBL_BODY">:</td><td class="TBL_BODY"><?php echo $row['mr_no'];?></td>
		</tr>
		<tr>
			<td class="TBL_BODY">No.Reg</td><td class="TBL_BODY">:</td><td class="TBL_BODY"><?php echo $row['no_reg'];?></td>
		</tr>
		<tr>
			<td class="TBL_BODY">Nama</td><td class="TBL_BODY">:</td><td class="TBL_BODY"><?php echo $row['nama'];?></td>
		</tr>
		<tr>
			<td class="TBL_BODY">Alamat</td><td class="TBL_BODY">:</td><td class="TBL_BODY"><?php echo $row['alamat'];?></td>
		</tr>
		<tr>
			<td class="TBL_BODY">Check In</td><td class="TBL_BODY">:</td><td class="TBL_BODY"><?php echo $row['check_in'];?></td>
		</tr>
		<tr>
			<td class="TBL_BODY">Check Out</td><td class="TBL_BODY">:</td><td class="TBL_BODY"><?php echo $row['check_out'];?></td>
		</tr>
		<tr>
			<td class="TBL_BODY">User</td><td class="TBL_BODY">:</td><td class="TBL_BODY"><?php echo $row['user'];?></td>
		</tr>
		<tr>
			<td colspan="3"><input type="submit" value="Batal"/></td>
		</tr>
	</table>
	</form>
    <?php
}
else{
		if (!$GLOBALS['print']){
			$f = new Form($SC, "GET","NAME=Form1");
			$f->PgConn = $con;
			$f->hidden("p", $PID);	
			if (!isset($_GET['tanggal1D'])) {

				$tanggal1D = date("d", time());
				$tanggal1M = date("m", time());
				$tanggal1Y = date("Y", time());
				$tanggal2D = date("d", time());
				$tanggal2M = date("m", time());
				$tanggal2Y = date("Y", time());

				$ts_check_in1 = date("Y-m-d", mktime(0, 0, 0, 0, 0, 0));
				$ts_check_in2 = date("Y-m-d", mktime(0, 0, 0, 0, 0, 0));
				$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0, 0, 0, $tanggal1M, $tanggal1D, $tanggal1Y)), "");
				$f->selectDate("tanggal2", "s/d", getdate(mktime(0, 0, 0, $tanggal2M, $tanggal2D, $tanggal2Y)), "");
			} else {
				$ts_check_in1 = date("Y-m-d", mktime(0, 0, 0, $_GET["tanggal1M"], $_GET["tanggal1D"], $_GET["tanggal1Y"]));
				$ts_check_in2 = date("Y-m-d", mktime(0, 0, 0, $_GET["tanggal2M"], $_GET["tanggal2D"], $_GET["tanggal2Y"]));
				$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0, 0, 0, $_GET["tanggal1M"], $_GET["tanggal1D"], $_GET["tanggal1Y"])), "");
				$f->selectDate("tanggal2", "s/d", getdate(mktime(0, 0, 0, $_GET["tanggal2M"], $_GET["tanggal2D"], $_GET["tanggal2Y"])), "");
			}
			$f->selectSQL("L1", "Ruangan",
				"select '' as hierarchy, '' as bangsal union " .
				"select hierarchy, bangsal ".
				"from rs00012 ".
				"where substr(hierarchy,4,6) = '000000' ".
				"and is_group = 'Y' ".
				"order by bangsal", $_GET["L1"],
				" ");
			
			$f->selectSQL("tipe", "Tipe Pasien",
				"select '' as tc, '' as tdesc union ".
				"select tc, tdesc ".
				"from rs00001  ".
				"where tt='JEP' and tc != '000' order by tdesc", $_GET["tipe"],
				"");
			$f->submit("TAMPILKAN");
			$f->execute();
		}
		else{
			if (!isset($_GET['tanggal1D'])) {

				$tanggal1D = date("d", time());
				$tanggal1M = date("m", time());
				$tanggal1Y = date("Y", time());
				$tanggal2D = date("d", time());
				$tanggal2M = date("m", time());
				$tanggal2Y = date("Y", time());


				$ts_check_in1 = date("Y-m-d", mktime(0, 0, 0, 0, 0, 0));
				$ts_check_in2 = date("Y-m-d", mktime(0, 0, 0, 0, 0, 0));

			} else {
				?>
				<table>
					<tr>
						<td>
							Dari Tanggal
						</td>
						<td>
							: <?php echo date("Y-m-d", mktime(0, 0, 0, $_GET["tanggal1M"], $_GET["tanggal1D"], $_GET["tanggal1Y"]));
				?>
						</td>
					</tr>
					<tr>
						<td>
							s/d
						</td>
						<td>
							: <?php echo date("Y-m-d", mktime(0, 0, 0, $_GET["tanggal2M"], $_GET["tanggal2D"], $_GET["tanggal2Y"]));
				?>
						</td>
					</tr>

					<tr>
						<td>
							Ruangan
						</td>
						<td>
							: <?php
				$mRuangan = getFromTable("select bangsal from rs00012 where hierarchy='".$_GET["L1"]."'");
				if ($mRuangan) {
					echo $mRuangan;
				} else {
					echo "Semua Ruangan";
				}
				?>
						</td>
					</tr>

				</table>
                        <?
                        $ts_check_in1 = date("Y-m-d", mktime(0, 0, 0, $_GET["tanggal1M"], $_GET["tanggal1D"], $_GET["tanggal1Y"]));
                        $ts_check_in2 = date("Y-m-d", mktime(0, 0, 0, $_GET["tanggal2M"], $_GET["tanggal2D"], $_GET["tanggal2Y"]));
                    }
                    
                 //   $f->execute();
		}
		echo "<BR>";		
		echo "<div align=right>";
		$f = new Form($SC, "GET","NAME=Form2");
	    $f->hidden("p", $PID);
            $f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
	    $f->execute();
            echo "</div>";echo "<BR>";
		
            $SQL =
            "select distinct b.mr_no, b.alm_tetap as alamat, a.no_reg, upper(b.nama)as nama, f.tdesc AS tipe_pasien,
            ( SELECT ts_check_in FROM rs00010 x WHERE x.id = 
			(SELECT max(id) FROM rs00010 WHERE no_reg = a.no_reg AND awal::text = '1'))  AS check_in,
            a.ts_calc_stop as check_out, a.nama as user, d.hierarchy  from 
            rs00010 as a
            join rs00006 as c on a.no_reg = c.id
			join rs00001 as f on c.tipe = f.tc AND f.tt = 'JEP' 
            join rs00002 as b on c.mr_no = b.mr_no 
            join rs00012 as d on d.id= a.bangsal_id 
            where a.nama is not null ";		
            if($_GET["L1"]){
                    $SQL .="and f.tc = '".$_GET["tipe"]."' and substr(hierarchy,1,3) = '".substr($_GET["L1"],0,3)."' and hierarchy != '".$_GET["L1"]."' ";
            }
            if ($_GET[search]) {
                    $SQL .= " and (upper(b.nama) like '%".strtoupper($_GET[search])."%' or a.no_reg like '%".$_GET[search]."%' or b.mr_no like '%".$_GET[search]."%') ";
            }else{
                    $SQL .= "and f.tc = '".$_GET["tipe"]."' and (date(a.ts_calc_stop) between '$ts_check_in1'::date and '$ts_check_in2'::date)";
            }
            $result = pg_query($con,$SQL);
?>
<table width="100%" border="1">
    <tr>
        <td class="TBL_HEAD" align="center" width="50">NO.</td>
        <td class="TBL_HEAD" align="center" width="80">NOMOR REG.</td>
        <td class="TBL_HEAD" align="center" width="80">NOMOR MR</td>
        <td class="TBL_HEAD" align="center" width="150">NAMA</td>
        <td class="TBL_HEAD" align="center" width="">ALAMAT</td>
        <td class="TBL_HEAD" align="center" width="">TIPE PASIEN</td>
        <td class="TBL_HEAD" align="center" width="140">CHECK IN</td>
        <td class="TBL_HEAD" align="center" width="140">CHECK OUT</td>
        <td class="TBL_HEAD" align="center" width="120">USER</td>
        <td class="TBL_HEAD" align="center" width="120">ICD 9</td>        
		<td class="TBL_HEAD" align="center" width="120">BATAL<br>CHECKOUT</td>
    </tr>
<?php
	$i = 1;
    while ($row = pg_fetch_array($result)){
	$no = $i;
?>		
    <tr valign="top"> 
        <td class="TBL_BODY" align="left"><?=$no ?> </td>
        <td class="TBL_BODY" align="left"><?=$row["no_reg"] ?> </td>
        <td class="TBL_BODY" align="left"><?=$row["mr_no"] ?> </td>
        <td class="TBL_BODY" align="left"><?=$row["nama"] ?> </td>
        <td class="TBL_BODY" align="left"><?=$row["alamat"] ?> </td>
        <td class="TBL_BODY" align="left"><?=$row["tipe_pasien"] ?> </td>
        <td class="TBL_BODY" align="left"><?=$row["check_in"] ?></td>    
        <td class="TBL_BODY" align="left"><?=$row["check_out"] ?> </td>    
        <td class="TBL_BODY" align="left"><?=$row["user"] ?> </td>    
        <td class="TBL_BODY" align="center"><a href="index2.php?p=p_riwayat_penyakit&list=icd9&rg1=<?=$row["no_reg"] ?>&rg=<?=$row["no_reg"] ?>&ri=E05&mr=<?=$row["mr_no"] ?>&sub=icd9">[ Catat ]</a></td>    
	<td class="TBL_BODY" align="center"><a href="index2.php?p=inf_check_out&batal=1&rg=<?php echo $row['no_reg']?>">[ Batal ]</a></td>
<?php
	$i++;
    }
}    
?>
</table>
