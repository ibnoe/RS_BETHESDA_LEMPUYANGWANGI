<?php 
session_start();

$PID = "pendapatan_dokter";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
?>
<link rel="stylesheet" type='text/css' href="jquery-ui.custom.css"/>
<script type="text/javascript" src="plugin/jquery-1.8.2.js"></script>
<script type="text/javascript" src="plugin/jquery-ui.js"></script>
<script language="javascript">
$(document).ready(function(){
$("input:text[name='dokter']").autocomplete({
		type:'GET',
		source:function(request,response){
		$.ajax({
			url:'./lib/getPegawai.php',
			data: {term : request.term},
			dataType : 'json',
			success : function(data){
				response(data);
			},
		});
	},
		selectFirst: true,
		select: function( event, ui ) {
			$("input:hidden[name='id_dokter']").val(ui.item.id);
		},
	});
});	
</script>	
<?php
//$_GET["id_dokter"]
//$_GET["dokter"]
//$_SESSION['nama_usr']
//echo '<pre>';
//var_dump($_GET["id_dokter"]);
//die;

if($_GET["tc"] == "view") {

    pendapatan_dokter("Pendapatan Per Dokter");
	
	$tp = getFromTable(
               "select a.jabatan_medis_fungsional from rs00018 a, rs00017 b ".
               "where  b.jabatan_medis_fungsional_id=a.id and b.id = '".$_GET["dok"]."'");
	
	if ($_GET["inap"]!="I"){		   
    $nama = getFromTable(
		
               "select nama from rsv_jasa_medis ".
               "where  id_dokter = '".$_GET["dok"]."' group by nama");
    }else{
	$nama = getFromTable(
               "select nama from rsv_jasa_medis_i ".
               "where  id_dokter = '".$_GET["dok"]."' group by nama");
	}
	$pasien = getFromTable(
               "select tipe_p from rsv_jasa_medis ".
               "where  tipe = '".$_GET["tipe"]."' group by tipe_p");
	$poli = getFromTable(
               "select tdesc from rsv_jasa_medis ".
               "where tc = '".$_GET["poli"]."' group by tdesc");

    $r = pg_query($con,
        "select to_char(to_date('".$_GET["t1"]."','YYYY-MM-DD'),'DD-MON-YYYY') as tgl");
    $d = pg_fetch_object($r);
    pg_free_result($r);
    $bulan = $d->tgl;

    $r1 = pg_query($con,
        "select to_char(to_date('".$_GET["t2"]."','YYYY-MM-DD'),'DD-MON-YYYY') as tgl1");
    $d1 = pg_fetch_object($r1);
    pg_free_result($r1);
    $bulan1 = $d1->tgl1;

if (!$GLOBALS['print']){
	title_excel("pendapatan_dokter&tc=".$_GET["tc"]."&dok=".$_GET["dok"]."&t1=".$_GET["t1"]."&t2=".$_GET["t2"]."&tipe=".$_GET["tipe"]."&inap=".$_GET["inap"]."&poli=".$_GET["poli"]."");}

	echo " <BR><DIV ALIGN=RIGHT><img src=\"icon/back.gif\" align=absmiddle ><A CLASS=SUB_MENU HREF='index2.php".
            "?p=$PID'>".
            "  Kembali  </A></DIV>";
    $f = new Form("");
	echo "<br>";
	echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><FONT SIZE='1em'><b> PERIODE </td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $bulan s/d $bulan1 </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> NAMA DOKTER </td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $nama </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> JABATAN MEDIS FUNGSIONAL </td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $tp </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> TIPE PASIEN</td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $pasien </td>";
	echo "</tr>";
	echo "<tr>";
	if ($_GET["inap"]!="I"){		   
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> POLI </td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $poli </td>";
    }else{
	echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b> RUANGAN </td>";
		echo "<td bgcolor='WHITE'><FONT SIZE='0.7em'><b>: $_GET[poli] </td>";
	}
		
	echo "</tr>";
echo "</table>";

    $f->execute();
    echo "<br>";
	title("Rincian Layanan");

	//echo "<br>";

if ($_GET["inap"]!="I"){	
$sql="select tanggal(a.tanggal_entry,0) as tanggal, a.no_reg,c.mr_no,c.nama, a.layanan, a.harga_atas, a.harga_bawah, a.tagihan,a.jasa_dokter*a.qty as jasa_dokter,a.jasa_asisten*a.qty as jasa_asisten,a.jasa_rs*a.qty as jasa_rs,a.alat*a.qty as alat,a.bahan*a.qty as bahan,a.dll*a.qty as dll,a.diskon
	from rsv_jasa_medis a
	left join rs00006 b on a.no_reg=b.id
	left join rs00002 c on c.mr_no=b.mr_no 
	where (a.tanggal_entry between '".$_GET["t1"]."' and '".$_GET["t2"]."') and a.tipe='".$_GET["tipe"]."' and a.is_inap='".$_GET["inap"]."' and id_dokter='".$_GET["dok"]."' and tc= '".$_GET["poli"]."'
	order by a.tanggal_entry ASC";
}else{
$sql="select tanggal(a.tanggal_entry,0) as tanggal, a.no_reg,c.mr_no,c.nama, a.layanan, a.harga_atas, a.harga_bawah, a.tagihan,a.jasa_dokter*a.qty as jasa_dokter,a.jasa_asisten*a.qty as jasa_asisten,a.jasa_rs*a.qty as jasa_rs,a.alat*a.qty as alat,a.bahan*a.qty as bahan,a.dll*a.qty as dll,a.diskon
	from rsv_jasa_medis_i a
	left join rs00006 b on a.no_reg=b.id
	left join rs00002 c on c.mr_no=b.mr_no 
	where (a.tanggal_entry between '".$_GET["t1"]."' and '".$_GET["t2"]."') and a.tipe='".$_GET["tipe"]."' and a.is_inap='".$_GET["inap"]."' and id_dokter='".$_GET["dok"]."'
	order by a.tanggal_entry ASC";
}

@$r1 = pg_query($con,$sql);
			@$n1 = pg_num_rows($r1);
			
   			$max_row= $n1 ;//30
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=0 CELLPADDING=1>
			<tr class="NONE" bgcolor="#00CCCC">     	
				<td rowspan="2" class="TBL_HPD" width="3%" align="center"><B>NO</B></td>
				<td rowspan="2" width="10%" class="TBL_HPD"align="center"><B>TANGGAL</B></td>
				<td rowspan="2" class="TBL_HPD"align="center"><B>NO.REG</B></td>
				<td rowspan="2" class="TBL_HPD"align="center"><B>NO.MR</B></td>
				<td rowspan="2" width="25%" class="TBL_HPD"align="center"><B>NAMA PASIEN</B></td>
				<td rowspan="2" width="30%" class="TBL_HPD"align="center"><B>LAYANAN</B></td>
				<td colspan="7" width="20%" align="center" class="TBL_HPD"><B>PEMBAGIAN JASA MEDIS (Rp.)</B></td>
				<!--<td rowspan="2" width="6%" align="center" class="TBL_HPD"><B>ALAT (Rp.)</B></td>-->
				<!--<td rowspan="2" width="6%" align="center" class="TBL_HPD"><B>BAHAN (Rp.)</B></td>-->
				<!--<td rowspan="2" width="6%" align="center" class="TBL_HPD"><B>ADMINISTRASI (Rp.)</B></td>-->
				<td rowspan="2" width="6%" align="center" class="TBL_HPD"><B>TOTAL (Rp.)</B></td>
			</tr>
			<tr class="NONE" bgcolor="#00CCCC">
				<td width="5%" align="center" class="TBL_HPD"><B>DOKTER (Rp.)</B></td>
				<td width="5%" align="center" class="TBL_HPD"><B>DISKON DOKTER (Rp.)</B></td>
				<td width="5%" align="center" class="TBL_HPD"><B>ASISTEN (Rp.)</B></td>
				<td width="5%" align="center" class="TBL_HPD"><B>RS (Rp.)</B></td>
				<td width="5%" align="center" class="TBL_HPD"><B>ALAT (Rp.)</B></td>
				<td width="5%" align="center" class="TBL_HPD"><B>BAHAN (Rp.)</B></td>
				<td width="5%" align="center" class="TBL_HPD"><B>ADMINISTRASI (Rp.)</B></td>
			</tr>
	
		<?	
			$jml_js=0;
			$jml_jd=0;
			$jml_jp=0;
			$jml_jr=0;
			$jml_ja=0;
			$jml_jb=0;
			$jml_jadm=0;
			$jml= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td bgcolor="#00CCCC" class="TBL_BPD" align="center"><?=$no ?> </td>
			        	<td class="TBL_BPD" align="center"><?=$row1["tanggal"] ?> </td>
						<td align="left" class="TBL_BPD"><?=$row1["no_reg"] ?></td>
						<td align="left" class="TBL_BPD"><?=$row1["mr_no"] ?></td>
						<td align="left" class="TBL_BPD"><?=$row1["nama"] ?></td>
						<td align="left" class="TBL_BPD"><?=$row1["layanan"] ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jasa_dokter"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["diskon"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jasa_asisten"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jasa_rs"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["alat"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["bahan"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["dll"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["tagihan"],2,",",".") ?></td>
						
					</tr>	
					<?
					$jml_js=$jml_js+$row1["jasa_dokter"] ;
					$jml_jd=$jml_jd+$row1["diskon"] ;
					$jml_jp=$jml_jp+$row1["jasa_asisten"] ;
					$jml_jr=$jml_jr+$row1["jasa_rs"] ;
					$jml_ja=$jml_ja+$row1["alat"] ;
					$jml_jb=$jml_jb+$row1["bahan"] ;
					$jml_jadm=$jml_jadm+$row1["dll"] ;
					$jml=$jml+$row1["tagihan"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr class="NONE" bgcolor="#00CCCC">  
			        	<td align="center" colspan="6" height="25" valign="middle"> TOTAL (Rp.)</td>
			        	<td align="right" valign="middle"><?=number_format($jml_js,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml_jd,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml_jp,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml_jr,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml_ja,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml_jb,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml_jadm,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml,2,",",".") ?></td>
					</tr>	
</table>

<!--<table><tr><td><FONT COLOR="#000000"><?//echo "Note 1:<I><B><marquee> Untuk memastikan seluruh tindakan terinput bersama dengan petugas yang melakukan tindakan Pada baris <font color='#FF0000'>YANG MELAKUKAN TINDAKAN KOSONG</font> seluruh jumlah komponen jasa (Jasa Dokter, Jasa Asisten, Jasa RS, Alat, Bahan) bernilai nol(0.00)<marquee></B></I>" ?></td></tr></table>-->

<table>
	<tr>
		<td>
			<FONT COLOR="#000000">
				<?echo 
					"Note 1:<I><B>
					<br>Untuk memastikan seluruh <u>Tindakan/Pemeriksaan</u> terinput bersama dengan <u>dokter/petugas yang melakukan tindakan</u> Pada baris 
					<font color='#FF0000'>YANG MELAKUKAN TINDAKAN KOSONG</font> itu artinya yang melakukan tindakan masih kosong.
					</B></I>" 
				?>
			</FONT>
		</td>
	</tr>
</table>
<table>
	<tr>
		<td>
			<FONT COLOR="#000000">
				<?echo 
					"Note 2:<I><B>
					<br>LAPORAN MASIH BRUTO
					</B></I>" 
				?>
			</FONT>
		</td>
	</tr>
</table>
<table>
	<tr>
		<td>
			<FONT COLOR="#000000">
				<?echo 
					"Note 3:<I><B>
					<br>Ketentuan dari Master Tarif:
					<br>1. (Pemeriksaan) Jasa Dokter Specialis 			= 100%
					<br>2. (Pemeriksaan) Jasa Dokter Sub Specialis 		= 100%
					<br>3. (Pemeriksaan) Jasa Dokter Umum 					= 50% , Jasa RS 		= 50%
					<br>4. (Tindakan)	  Jasa Dokter Specialis & Umum 		= 70% , Jasa RS 		= 30%
					<br>5. Pila Klinik										= 60% , Jasa RS 		= 40%
					</B></I>" 
				?>
			</FONT>
		</td>
	</tr>
</table>

<?    
} else {
   if (!$GLOBALS['print']){
		pendapatan_dokter("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Per Dokter");
	title_excel("pendapatan_dokter&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&rawat_inap=".$_GET["rawat_inap"]."&mRAWAT=".$_GET["mRAWAT"]."&dokter=".$_GET["dokter"]."&mPASIEN=".$_GET["mPASIEN"]."");	//title_excel("pembagian_jm&tc=".$_GET["tc"]."&dok=".$_GET["dok"]."&t1=".$_GET["t1"]."&t2=".$_GET["t2"]."&tipe=".$_GET["tipe"]."&inap=".$_GET["inap"]."&poli=".$_GET["poli"]."");
//title_excel("pembagian_jm");

    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pendapatan Per Dokter");
    }
	
	$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	if (!$GLOBALS['print']) {
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
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
		
	    }
		$f->selectArray("rawat_inap", "<font color='red'>* U n i t</font>",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "onChange='document.Form1.submit();'; ");
		$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
		if ($_GET["rawat_inap"]=="Y"){
		$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
												 SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
												 order by tdesc ",$_GET["mRAWAT"], "");
		}elseif ($_GET["rawat_inap"]=="I"){
		
		$f->selectSQL("mINAP", "Ruangan ","select '' as bangsal1, '' as bangsal2 union select d.bangsal as bangsal1, d.bangsal as bangsal2
						   from rs00010 as a 
							   join rs00012 as b on a.bangsal_id = b.id 
							   join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
							   join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
							   join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
		group by bangsal1
		order by bangsal1 " ,$_GET["mINAP"], "");
			}else{}
		

		//-- select session user dokter
		$namaDok = getFromTable("SELECT id FROM rs00017 WHERE nama LIKE '%".$_SESSION['nama_usr']."%'");
		if (
				//dr. umum
				$_SESSION['nama_usr'] == 'dr. TATANG HARTONO' or
				$_SESSION['nama_usr'] == 'dr. YANTI IVANA' or
				$_SESSION['nama_usr'] == 'dr. DIANA SUTOPO' or
				$_SESSION['nama_usr'] == 'dr. MMA. DEWI LESTARI' or
				$_SESSION['nama_usr'] == 'dr. MARSHAL SOEKARNO' or
				$_SESSION['nama_usr'] == 'dr. HERLINA GUNAWAN' or
				$_SESSION['nama_usr'] == 'dr. IDA AYU TRIASTUTI' or
				$_SESSION['nama_usr'] == 'dr. FIONA AMELIA' or
				$_SESSION['nama_usr'] == 'dr. ENVAN WIDYA CHRISNAWAN ' or
				$_SESSION['nama_usr'] == 'dr. ANGIEDA SOEPARTO' or
				$_SESSION['nama_usr'] == 'dr. SYAM SUHARYONO' or
				$_SESSION['nama_usr'] == 'dr. DEBORA PRATITA ACCHEDYA' or
				$_SESSION['nama_usr'] == 'dr. EPSI MARWATI' or
				$_SESSION['nama_usr'] == 'dr. SAK LIUNG' or
				$_SESSION['nama_usr'] == 'dr. INDRA SETIAWAN' or
				$_SESSION['nama_usr'] == 'dr. AGUNG WIDINUGROHO' or
				$_SESSION['nama_usr'] == 'dr. PETRUS HARI HARJATI' or
				$_SESSION['nama_usr'] == 'dr. WU LAN' or
				// dr. gigi
				$_SESSION['nama_usr'] == 'Drg. NICHOLAS ADI PERDANA SUSANTO' or
				$_SESSION['nama_usr'] == 'Drg. EDDY WONGSOSUSILO, Sp.KGA.' or
				$_SESSION['nama_usr'] == 'Drg. CYRILLA PRIMA ANGGITA MAHARANI' or
				$_SESSION['nama_usr'] == 'Drg. GENE RIZKY NATALIA GUNAWAN' or
				// dr. penyakit dalam
				$_SESSION['nama_usr'] == 'dr. SAPTO PRIATMO, Sp.PD.' or
				$_SESSION['nama_usr'] == 'dr. LISA KURNIA SARI, Sp.PD.' or
				// dr. saraf
				$_SESSION['nama_usr'] == ' dr. ADELYNA MELIALA, Sp.S.' or
				$_SESSION['nama_usr'] == 'Prof.DR.dr. SRIÂ SUTARNI, Sp.S.(K).' or
				$_SESSION['nama_usr'] == 'dr. ESDRAS ARDI PRAMUDITA, M.Sc.,Sp.S.' or
				// dr. obsgyn
				$_SESSION['nama_usr'] == 'dr. TRIANTO SUSETYO, Sp.OG.' or
				$_SESSION['nama_usr'] == 'dr. THERESIA AVILLA RIRIEL KUSUMOSIH, Sp.OG."' or
				$_SESSION['nama_usr'] == 'dr. H. RAHARDJO, Sp.OG.' or
				$_SESSION['nama_usr'] == 'dr. LUKAS BUDI GUNAWAN, Sp.OG.' or
				$_SESSION['nama_usr'] == 'dr. ESTYA DEWI WIDYASARI, Sp.OG.' or
				$_SESSION['nama_usr'] == 'dr. TRI BUDIANTO, Sp.OG.' or
				$_SESSION['nama_usr'] == 'dr. KASIRUN KOSIM, Sp.OG.' or
				// dr. anak
				$_SESSION['nama_usr'] == 'dr. BAMBANG HADI BAROTO, Sp.A.' or
				$_SESSION['nama_usr'] == 'dr. DEVIE KRISTIANI, Sp.A.' or
				$_SESSION['nama_usr'] == 'dr. MARGARETA YULIANI, Sp.A.' or
				// dr. bedah
				$_SESSION['nama_usr'] == 'dr. GAPONG SUKO WIRATMO, Sp.B.' or
				$_SESSION['nama_usr'] == 'dr. OKTO PRASETYA MUDA, Sp.B.' or
				$_SESSION['nama_usr'] == 'dr. YULIUS CANDRA ADI PURWADI, Sp.BA.' or
				// dr. THT
				$_SESSION['nama_usr'] == 'dr. DYAH AYU KARTIKA DEWANTI, M.Sc.,Sp.THT-KL.' or
				//dr. kulit dan kelamin
				$_SESSION['nama_usr'] == 'dr. ARUM KRISMI, M.Sc.,Sp.KK.' or
				$_SESSION['nama_usr'] == 'dr. INDIAH PERWITASARI YUSWARINI.,Sp.KK' or
				// dr. jiwa
				$_SESSION['nama_usr'] == 'dr. VENNY PUNGUS, Sp.KJ.' or
				// dr. anestesi
				$_SESSION['nama_usr'] == 'dr. GOBING SABARDI, Sp.AN.' or
				$_SESSION['nama_usr'] == 'dr. ERRY GUTHOMO, Sp.AN.' or
				$_SESSION['nama_usr'] == 'dr. BAMBANG SURYONO, Sp.An.' or
				$_SESSION['nama_usr'] == 'dr. PANDIT SAROSA,Sp.An.' or
				// dr. radiologi
				$_SESSION['nama_usr'] == 'dr. SUDARMADJI, Sp.Rad.' or
				$_SESSION['nama_usr'] == 'dr. MERARI PANTI ASTUTI, Sp.Rad.' or
				// dr. patologi klinik
				$_SESSION['nama_usr'] == 'dr. FENTY, Sp.PK.'
			) {
			$f->text("nama_dokter", "Pelaku Tindakan", 50, 100, $_SESSION['nama_usr'], "style='text-align:left; font-weight:bold; font-size:11px; background-color:#99FF66;' disabled");
			$f->hidden("dokter", $_SESSION['nama_usr']);
			$f->hidden("id_dokter", $namaDok);
		} else {
			$f->text("dokter", "Pelaku Tindakan", 50, 100, getFromTable("SELECT nama FROM rs00017 WHERE id = ".$_GET['id_dokter']));
			$f->hidden("id_dokter", $_GET['id_dokter']);
		}
		//--
		
		//$f->text("dokter", "Pelaku Tindakan", 50, 100, getFromTable("SELECT nama FROM rs00017 WHERE id = ".$_GET['id_dokter']));
		//$f->hidden("id_dokter", $_GET['id_dokter']);
		//$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  //"select c.tc as tc, c.tdesc as tdesc ".
    			  //"from rs00008 a, rs00006 b, rs00001 c ".
    			  //"where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP' order by tdesc asc", $_GET["mPASIEN"],"");
/* 		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE c.tc_poli=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "102"); */
							 
		$f->selectArray("asuransi", "Asuransi",Array(""=>"", "UM"=>"Pasien Umum", "AS"=>"Pasien Asuransi"),
                     $_GET["asuransi"], "onChange='document.Form1.submit();'; ");
		$_GET["asuransi"] = strlen($_GET["asuransi"]) == "" ? "" : $_GET["asuransi"];
		if ($_GET["asuransi"]=="UM"){
			$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where tc IN ('000','001') and a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP' order by tdesc asc", $_GET["mPASIEN"],"");
		} else if ($_GET["asuransi"]=="AS"){
			$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where tc NOT IN ('000','001') and a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP' order by tdesc asc", $_GET["mPASIEN"],"");
		} else {
		
		}
							 
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
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
	    }
		$f->selectArray("rawat_inap", "<font color='red'>* U n i t</font>",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "disabled");
		$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
		if ($_GET["rawat_inap"]=="Y"){
		$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
												 SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
												 order by tdesc ",$_GET["mRAWAT"], "disabled");
		}elseif ($_GET["rawat_inap"]=="I"){
		$f->selectSQL("mINAP", "Ruangan ","select '' as bangsal1, '' as bangsal2 union select d.bangsal as bangsal1, d.bangsal as bangsal2
						   from rs00010 as a 
							   join rs00012 as b on a.bangsal_id = b.id 
							   join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
							   join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
							   join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
		group by d.bangsal1
		order by d.bangsal1 " ,$_GET["mINAP"], "disabled");
			}else{}
		
		//$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  //"select c.tc as tc, c.tdesc as tdesc ".
    			  //"from rs00008 a, rs00006 b, rs00001 c ".
    			  //"where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"disabled");
/* 		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE c.tc_poli=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "disabled"); */
		$f->selectArray("asuransi", "Asuransi",Array(""=>"", "UM"=>"Pasien Umum", "AS"=>"Pasien Asuransi"),
                     $_GET["asuransi"], "disabled");
		$_GET["asuransi"] = strlen($_GET["asuransi"]) == "" ? "" : $_GET["asuransi"];
		if ($_GET["asuransi"]=="UM"){
			$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where tc IN ('000','001') and a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP' order by tdesc asc", $_GET["mPASIEN"],"disabled");
		} else if ($_GET["asuransi"]=="AS"){
			$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where tc NOT IN ('000','001') and a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP' order by tdesc asc", $_GET["mPASIEN"],"disabled");
		} else {
		
		}
	}


		if(!empty($_GET['dokter'])){
		  $SQL_WHERE = " AND id_dokter = ".$_GET['id_dokter']."";
		}
		
		if ($_GET["asuransi"]=="UM" && (empty($_GET['mPASIEN']) || $_GET['mPASIEN'] == '')){
		  $SQL_TIPE = " AND tipe like '%001%'";
		} else if ($_GET["asuransi"]=="AS" && (empty($_GET['mPASIEN']) || $_GET['mPASIEN'] == '')){
		  $SQL_TIPE = " AND tipe not like '%001%'";
		} else if(!empty($_GET['mPASIEN']) || $_GET['mPASIEN'] != ''){
		  $SQL_TIPE = " AND tipe like '%".$_GET['mPASIEN']."%'";
		}
		
		if($_GET["rawat_inap"] == "Y" or $_GET["rawat_inap"] == "N" ){
		$SQL1=" select is_inap,tipe,tipe_p,id_dokter,nama,tdesc,tc, sum(tagihan) as jumlah,sum(jasa_dokter*qty) as jst,sum(jasa_asisten*qty) as jpt,sum(jasa_rs*qty) as jrt,sum(alat*qty) as jat ,sum(bahan*qty) as jbt, sum(dll*qty) as jadm, sum(diskon) as jsd
				from rsv_jasa_medis
				where (tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and is_inap like '%".$_GET["rawat_inap"]."%' and tc like '%".$_GET["mRAWAT"]."%' $SQL_WHERE $SQL_TIPE
				group by id_dokter,nama,tdesc, tipe,is_inap,tipe,tipe_p,tc 
				order by nama ";
		}else if($_GET["rawat_inap"] == "I"){
		$SQL1=" select is_inap,tipe,tipe_p,id_dokter,nama,bangsal_id, bangsal, bangsal2, sum(tagihan) as jumlah,sum(jasa_dokter*qty) as jst,sum(jasa_asisten*qty) as jpt,sum(jasa_rs*qty) as jrt,sum(alat*qty) as jat ,sum(bahan*qty) as jbt, sum(dll*qty) as jadm, sum(diskon) as jsd
				from rsv_jasa_medis_i
				where (tanggal_entry between '$ts_check_in1' and '$ts_check_in2')  $SQL_WHERE $SQL_TIPE and is_inap like '%".$_GET["rawat_inap"]."%' and (bangsal like '%".$_GET["mINAP"]."%' or bangsal2 like '%".$_GET["mINAP"]."%')

				group by is_inap,tipe,tipe_p,id_dokter,nama, bangsal_id, bangsal, bangsal2
				order by nama ";
		} else if ($_GET["rawat_inap"] == "" and $_GET["tanggal1D"] != '') {
			?>
				<script type="text/javascript">
					alert("Silahkan pilih Unit terlebih dahulu,\n(IGD, Rawat Jalan atau Rawat Inap) yang ingin ditampilkan.");
					history.back();
				</script>
			<?php
		}

			@$r1 = pg_query($con,$SQL1);
			@$n1 = pg_num_rows($r1);
			
   			$max_row= $n1 ;//30
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=1>
			<tr class="NONE" bgcolor="#00CCCC">     	
				<td rowspan="2" class="TBL_HPD" width="4%" align="center"><B>NO</B></td>
				
				<td rowspan="2" width="25%" class="TBL_HPD" align="center"><B>NAMA</B></td>
				
				<? if($_GET["rawat_inap"] != "I"){ ?>
				<td rowspan="2" width="24%" class="TBL_HPD" align="center"><B>POLI</B></td>
				<? }else{ ?>
				<td rowspan="2" class="TBL_HPD" align="center"><B>RUANGAN</B></td>
				<? } ?>
				
				<td rowspan="2" width="10%" class="TBL_HPD" align="center"><B>TIPE PASIEN</B></td>
				<td colspan="7" align="center" class="TBL_HPD"><B>PEMBAGIAN JASA MEDIS (Rp.)</B></td>
				<!--<td rowspan="2" width="10%" align="center" class="TBL_HPD"><B>ALAT (Rp.)</B></td>-->
				<!--<td rowspan="2" width="10%" align="center" class="TBL_HPD"><B>BAHAN (Rp.)</B></td>-->
				<!--<td rowspan="2" width="10%" align="center" class="TBL_HPD"><B>ADMINISTRASI (Rp.)</B></td>-->
				<td rowspan="2" width="10%" align="center" class="TBL_HPD"><B>TOTAL PELAYANAN (Rp.)</B></td>
				<td rowspan="2" width="5%" align="center" class="TBL_HPD"><B>DETAIL</B></td>
			</tr>
			<tr class="NONE" bgcolor="#00CCCC">
				<td  align="center" class="TBL_HPD"><B>DOKTER (Rp.)</B></td>
				<td align="center" class="TBL_HPD"><B>DISKON DOKTER (Rp.)</B></td>

				<td width="10%" align="center" class="TBL_HPD"><B>ASISTEN (Rp.)</B></td>
				<td width="10%" align="center" class="TBL_HPD"><B>RS (Rp.)</B></td>
				<td width="10%" align="center" class="TBL_HPD"><B>ALAT (Rp.)</B></td>
				<td width="10%" align="center" class="TBL_HPD"><B>BAHAN (Rp.)</B></td>
				<td width="10%" align="center" class="TBL_HPD"><B>ADMINISTRASI (Rp.)</B></td>
			</tr>
			
	
		<?	
			$jml_jst=0 ;
		    $jml_jsd=0 ;
			$jml_jpt=0 ;
			$jml_jrt=0 ;
			$jml_jat=0 ;
			$jml_jbt=0 ;
			$jml_jadm=0 ;
			$jml= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BPD" align="center" bgcolor="#00CCCC"><?=$no ?> </td>
						
						<? if($row1["nama"]!=''){?>
						<td align="left" class="TBL_BPD"><?=$row1["nama"] ?></td>
						<?}else{?>
						<td align="left" class="TBL_BPD"><FONT COLOR="#FF0000"><CENTER><B><I>YANG MELAKUKAN TINDAKAN KOSONG</I></B></CENTER></td>	
						<?}?>
						
						<? if($_GET["rawat_inap"] != "I"){ ?>
						<td align="left" class="TBL_BPD"><?=$row1["tdesc"] ?></td>
						<? }else{ ?>
						<td align="left" class="TBL_BPD"><?=$row1["bangsal2"]." / ".$row1["bangsal"] ?></td>
						<? }?>

						<td align="left" class="TBL_BPD"><?=$row1["tipe_p"] ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jst"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jsd"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jpt"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jrt"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jat"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jbt"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jadm"],2,",",".") ?></td>
						<td align="right" class="TBL_BPD" valign="middle"><?=number_format($row1["jumlah"],2,",",".") ?></td>
						<? if($_GET["rawat_inap"] != "I"){ ?>
						<td align="center" class="TBL_BPD" valign="middle" bgcolor="#00CCCC"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&dok=".$row1['id_dokter']."&t1=$ts_check_in1"."&t2=$ts_check_in2&tipe=".$row1['tipe']."&inap=".$row1['is_inap'] ."&poli=".$row1['tc'] ."'>".icon("view","View")."</A>";?></td>
						<? }else{ ?>
						<td bgcolor="#00CCCC" align="center" class="TBL_BPD" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&dok=".$row1['id_dokter']."&t1=$ts_check_in1"."&t2=$ts_check_in2&tipe=".$row1['tipe']."&inap=".$row1['is_inap'] ."&poli=".$row1['bangsal2'] ."'>".icon("view","View")."</A>";?></td>
						<? } ?>
					</tr>	
					<?

					$jml_jst=$jml_jst+$row1["jst"] ;
					$jml_jsd=$jml_jsd+$row1["jsd"];
					$jml_jpt=$jml_jpt+$row1["jpt"] ;
					$jml_jrt=$jml_jrt+$row1["jrt"] ;
					$jml_jat=$jml_jat+$row1["jat"] ;
					$jml_jbt=$jml_jbt+$row1["jbt"] ;
					$jml_jadm=$jml_jadm+$row1["jadm"] ;
					$jml=$jml+$row1["jumlah"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr class="NONE" bgcolor="#00CCCC">  
			        	<td class="TBL_HPD" align="center" colspan="4" height="25" valign="middle"><B> TOTAL </B></td>
			        	<td class="TBL_HPD" align="right" valign="middle"><B><?=number_format($jml_jst,2,",",".") ?></B></td>
					<td class="TBL_HPD" align="right" valign="middle"><B><?=number_format($jml_jsd,2,",",".") ?></B></td>
						<td class="TBL_HPD" align="right" valign="middle"><B><?=number_format($jml_jpt,2,",",".") ?></B></td>
					<td class="TBL_HPD" align="right" valign="middle"><B><?=number_format($jml_jrt,2,",",".") ?></B></td>
						<td class="TBL_HPD" align="right" valign="middle"><B><?=number_format($jml_jat,2,",",".") ?></B></td>
					<td class="TBL_HPD" align="right" valign="middle"><B><?=number_format($jml_jbt,2,",",".") ?></B></td>
					<td class="TBL_HPD" align="right" valign="middle"><B><?=number_format($jml_jadm,2,",",".") ?></B></td>
						<td class="TBL_HPD" align="right" valign="middle"><B><?=number_format($jml,2,",",".") ?></B></td>
						<td class="TBL_HPD" align="right" valign="middle">&nbsp;</td>
					</tr>	
</table>

<?
}
?>
