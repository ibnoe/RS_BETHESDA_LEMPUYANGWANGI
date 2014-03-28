<?
$PID = "ipsrs";
$SC = $_SERVER["SCRIPT_NAME"];
?>
<meta http-equiv="refresh" content="120" charset=iso-8859-1">
<?
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("startup.php");

//if (!$GLOBALS['print']){
//title_print("<img src='icon/informasi-2.gif' align='absmiddle' > INSTALASI PEMELIHARAAN SARANA");
//}
echo "<br>";

if ($_GET["action"]=="status") {
	$r2 = pg_query($con,
            "select a.*,to_char(a.tanggal,'dd Mon yyyy') as tanggal1 ".
            "from rs80808 a ".
            "where a.id_ipsrs = '".$_GET["id"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
	title("<img src='icon/informasi-2.gif' align='absmiddle' > INSTALASI PEMELIHARAAN SARANA");	
	if ($d2->status == "0"){
	$status="Belum Dikerjakan";
	}elseif ($d2->status == "1"){
	$status="Sudah Selesai Tanpa Suku Cadang";
    }elseif ($d2->status == "2"){
	$status="Sudah Selesai menggunakan Suku Cadang";
    }elseif ($d2->status == "3"){
	$status="Diusulkan Membuat RAB (SWAKELOLA)";
	}elseif ($d2->status == "4"){
	$status="Diusulkan Kepada Pihak ke-Tiga";
	}elseif ($d2->status == "5"){
	$status="Pemeliharaan Rutin di Ruangan jam 09.00 s/d 11.00";
	}elseif ($d2->status == "6"){
	$status="Pemeliharaan Berkala Mingguan Jum'at & Sabtu jam 09.00 s/d 11.00";
	}elseif ($d2->status == "7"){
	$status="Pengecekan & Pemasangan Oksigen";
	}else{$status="Pengecekan & Pemasangan Gas Medis";}
	?>
	<table border="0" width="50%">
	<tr>
		<td class="TITLE_SIM3" width="35%"><b>Nomor</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->nomor; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Nama Lokasi</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->id_ruang; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Waktu Melapor</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->tanggal1; ?> / <?= $d2->waktu; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Nama Pelapor</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->pelapor; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Nama Petugas Pelaksana</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->pekerja; ?></b></td>
	</tr>
</table>
<br>
	<?   
        
$f = new Form("$SC", "GET", "name='Form2'");
$r2 = pg_query($con,
    "select * ".
    "from rs80808 ".
    "where id_ipsrs = '".$_GET["id"]."'");
$d2 = pg_fetch_object($r2);
pg_free_result($r2);

$f->PgConn = $con;
$f->hidden("p", $PID);
$f->hidden("action", $_GET["action"]);
$f->hidden("id", $_GET["id"]);
$f->calendar1("f_tgl_selesai","Tanggal Selesai",15,15,$d2->tgl_selesai,"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
$f->text("f_waktu_selesai","Waktu Selesai",15,15,date("H:i:s"));
$f->selectArray("f_status", "Status Pekerjaan",Array("1" => "Sudah Selesai Tanpa Suku Cadang", 
                                                     "2" => "Sudah Selesai menggunakan Suku Cadang", 
                                                     "3" => "Diusulkan Membuat RAB (SWAKELOLA)",
                                                     "4" => "Diusulkan Kepada Pihak ke-Tiga",
													 "5" => "Pemeliharaan Rutin di Ruangan jam 09.00 s/d 11.00",
													 "6" => "Pemeliharaan Berkala Mingguan Jum'at & Sabtu jam 09.00 s/d 11.00",
													 "7" => "Pengecekan & Pemasangan Oksigen",
													 "8" => "Pengecekan & Pemasangan gas Medis"),$d2->jns_kegiatan);
$f->textarea("f_catatan_hasil","Catatan Hasil Pekerjaan",4,40,$d2->catatan_hasil);
$f->submit(" Simpan ", "onClick='Form2.method=\"POST\";Form2.action=\"actions/ipsrs.insert.php\";'");
$f->execute();
        
        
}elseif ($_GET["action"]=="tambah" or $_GET["action"]=="view") {
        title("<img src='icon/informasi-2.gif' align='absmiddle' > INSTALASI PEMELIHARAAN SARANA");
	$r2 = pg_query($con,
            "select a.*,to_char(a.tanggal,'dd Mon yyyy') as tanggal1 ".
            "from rs80808 a ".
            "where a.id_ipsrs = '".$_GET["id"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
		
	if ($d2->status == "0"){
	$status="Belum Dikerjakan";
	}elseif ($d2->status == "1"){
	$status="Sudah Selesai Tanpa Suku Cadang";
    }elseif ($d2->status == "2"){
	$status="Sudah Selesai menggunakan Suku Cadang";
    }elseif ($d2->status == "3"){
	$status="Diusulkan Membuat RAB (SWAKELOLA)";
	}elseif ($d2->status == "4"){
	$status="Diusulkan Kepada Pihak ke-Tiga";
	}elseif ($d2->status == "5"){
	$status="Pemeliharaan Rutin di Ruangan jam 09.00 s/d 11.00";
	}elseif ($d2->status == "6"){
	$status="Pemeliharaan Berkala Mingguan Jum'at & Sabtu jam 09.00 s/d 11.00";
	}elseif ($d2->status == "7"){
	$status="Pengecekan & Pemasangan Oksigen";
	}else{$status="Pengecekan & Pemasangan Gas Medis";}
	?>
	<table border="0" width="50%">
	<tr>
		<td class="TITLE_SIM3" width="35%"><b>Nomor</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->nomor; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Nama Lokasi</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->id_ruang; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Waktu Melapor</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->tanggal1; ?> / <?= $d2->waktu; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Nama Pelapor</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->pelapor; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Nama Petugas Pelaksana</b></td>
		<td class="TITLE_SIM3"><b>: <?= $d2->pekerja; ?></b></td>
	</tr>
	<tr>
		<td class="TITLE_SIM3"><b>Status</b></td>
		<td class="TITLE_SIM3"><b>: <?= $status; ?></b></td>
	</tr>
</table>
<br>
	<?
	echo "<table width='75%'><tr><td width='75%'>";
	if ($_GET["action"]=="view") {
	titlecashier4('DATA SUKU CADANG YANG DIPERLUKAN');
	}else{
	titlecashier4('INPUT DATA SUKU CADANG YANG DIPERLUKAN');
	}
	echo "</td></tr></table><br>";
	
	$sql=" select * 
        from rs80888
        where id_ipsrs='".$_GET["id"]."' group by id_ipsrs, suku_cadang,jumlah,id
        order by suku_cadang  ";
		
		@$r1 = pg_query($con,$sql);
        @$n1 = pg_num_rows($r1);

		$max_row= 9999999999 ;
		$mulai = $HTTP_GET_VARS["rec"] ;
		if (!$mulai){$mulai=1;}
		

?>
<script>
function cetaksurat(tag) {
    sWin = window.open('includes/cetak.ipsrs.php?id=' + tag+'&kas=', 'xWin', 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');
    sWin.focus();
}
</script>

<table width="75%" border="0">
	<tr>
		<? if ($_GET["action"]=="view") { ?>
        <td align="right"> <a href="javascript: cetaksurat(<? echo (int) $_GET[id];?>)" ><img src="images/cetak.gif" border="0"></a></td>
        <? }?>
		<td align="right" width="5%"> <DIV ALIGN=RIGHT><A HREF='/onemedic/index2.php?p=ipsrs'><IMG ALT='Kembali' BORDER=0 SRC='images/icon-back.png' title='Kembali' ></a></DIV></td>
    </tr>
</table>

<br>

  <table CLASS=TBL_BORDER width="75%" border="0">

    <tr>
      <td class="TBL_HEAD" width="4%"><div align="center">NO. </div></td>
      <td class="TBL_HEAD" width="10%"><div align="center">NAMA SUKU CADANG</div></td>
	  <td class="TBL_HEAD" width="4%"><div align="center">JUMLAH</div></td>
	   <? if ($_GET["action"]=="tambah") { ?>
	  <td class="TBL_HEAD" width="4%"><div align="center">HAPUS </div></td>
	  <? }?>
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
					<td class="TBL_BODY" align="left"><?=$row1["suku_cadang"] ?> </td>
					<td class="TBL_BODY" align="center"><?=$row1["jumlah"] ?> </td>
					<? if ($_GET["action"]=="tambah") { ?>
					<? if (!$GLOBALS['print']){ ?>
						<td class="TBL_BODY" align="center"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=hapus&id=".$row1['id']."&id_ipsrs=".$_GET['id']."'>".
                        icon("delete","Hapus")."</A>"; ?> </td>
					<? } ?>
					<? } ?>
                </tr>

                <?;$j++;
        }
        $i++;
}

?>
</table>
<br>
<?
if ($_GET["action"]=="tambah") { 
		$f = new Form("$SC", "GET", "name='Form2'");	
        $f->PgConn = $con;
        $f->hidden("p", $PID);
		$f->hidden("id", $_GET["id"]);
        $f->hidden("action", $_GET["action"]);
		$f->text("suku_cadang","Suku Cadang",15,15,"");
		$f->text("jumlah","Jumlah",5,6,""); 
		$f->submit(" Simpan ", "onClick='Form2.method=\"POST\";Form2.action=\"actions/ipsrs.insert.php\";'");
		$f->execute();
	}	
 }elseif($_GET["action"]=="hapus") {
        title("<img src='icon/informasi-2.gif' align='absmiddle' > INSTALASI PEMELIHARAAN SARANA");
        $r = pg_query($con, "SELECT * FROM rs80888 where id='".$_GET['id']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
	
echo"<form action='actions/ipsrs.insert.php'>
			<table>
			<h1>Anda yakin menghapus data ini?</h1>
				<tr>	
					<td>Nama Suku Cadang </td><td>:</td>
					<td><input type='text' name='suku_cadang' value='".$d->suku_cadang."' readonly></td>
					
				</tr>
				<tr>
					<td>jumlah</td><td>:</td>
					<td><input type='text' name='jenis_linen' value='".$d->jumlah."' readonly></td>
				</tr>

				<tr>
					<input type='submit' value=YA></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type='hidden' name='id' value='".$d->id."' readonly></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type='hidden' name='id_ipsrs' value='".$d->id_ipsrs."' readonly></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type='hidden' name='action' value='hapus' readonly></td>
				</tr>
			</table>
		</form>";
}elseif($_GET["action"]=="hapus1") {
        title("<img src='icon/informasi-2.gif' align='absmiddle' > INSTALASI PEMELIHARAAN SARANA");
        $r = pg_query($con, "SELECT * FROM rs80808 where id_ipsrs='".$_GET['id']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
	
echo"<form action='actions/ipsrs.insert.php'>
			<table>
			<h1>Anda yakin menghapus data dengan nomor ".$d->nomor." ini?</h1>

				<tr>
					<input type='submit' value=YA></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type='hidden' name='id_ipsrs' value='".$d->id_ipsrs."' readonly></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type='hidden' name='action' value='hapus1' readonly></td>
				</tr>
			</table>
		</form>";
}elseif ($_GET["action"]=="new" or $_GET["action"]=="edit") {
        title("<img src='icon/informasi-2.gif' align='absmiddle' > INSTALASI PEMELIHARAAN SARANA");
	echo "\n<script language='JavaScript'>\n";
	echo "function selectPasien() {\n";
	echo "    sWin = window.open('popup/bangsal1.php', 'xWin', 	'width=600,height=400,menubar=no,scrollbars=yes');\n";
	echo "    sWin.focus();\n";
	echo "}\n";
	echo "</script>\n";
	
	$f = new Form("$SC", "GET", "name='Form2'");
	$r2 = pg_query($con,
            "select * ".
            "from rs80808 ".
            "where id_ipsrs = '".$_GET["id"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
		
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->hidden("action", $_GET["action"]);
        $f->hidden("id", $_GET["id"]);
        if ($_GET["action"]=="new"){
        $f->textAndButton("f_id_ruang","Ruangan",35,200,$_SESSION["SELECT_BANGSAL"],$ext,"...","OnClick='selectPasien();';");
        }elseif($_GET["action"]=="edit"){
            $f->textAndButton("f_id_ruang","Ruangan",35,200,$d2->id_ruang,$ext,"...","OnClick='selectPasien();';");
        }
        $f->calendar1("f_tanggal","Tanggal",15,15,$d2->tanggal,"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
		if ($d2->waktu == null){
        $f->text("f_waktu","Waktu",15,15,date("H:i:s"));
		}else{
		$f->text("f_waktu","Waktu",15,15,$d2->waktu);
		}
        $f->text("f_nomor","Nomor",20,50,$d2->nomor);
        $f->selectArray("f_jns_kegiatan", "Jenis Kegiatan",Array("E" => "Elektromedik", "S" => "Sipil"),$d2->jns_kegiatan);
        $f->textarea("f_catatan_jns","Nama Alat/Nama Kelas",4,40,$d2->catatan_jns);
        $f->textarea("f_catatan","Permasalahan",4,40,$d2->catatan);
        $f->text("f_pelapor","Nama Pelapor",30,100,$d2->pelapor);
        $f->text("f_pekerja","Nama Pelaksana",30,100,$d2->pekerja);
        $f->submit(" Simpan ", "onClick='Form2.method=\"POST\";Form2.action=\"actions/ipsrs.insert.php\";'");
        $f->execute();
?>
<script>
function cetakform(tag) {
    sWin = window.open('includes/cetak.formipsrs.php?id=' + tag+'&kas=', 'xWin', 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');
    sWin.focus();
}
</script>

<table width="25%" border="0">
	<tr>
		
        <td align="right"> <a href="javascript: cetakform(<? echo (int) $_GET[id];?>)" ><img src="images/cetak.gif" border="0"></a></td>
    </tr>
</table> 
<? 
}else{ 
    title_print("<img src='icon/informasi-2.gif' align='absmiddle' > INSTALASI PEMELIHARAAN SARANA");
    title_excel("ipsrs&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y]."&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y]."&tblstart=".$_GET[tblstart]."");
    echo "<br>";
	$wkthariini = date("H:i:s", time());
	
	//echo $wkthariini;
    $f = new Form($SC, "GET", "NAME=Form1");
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
        
        echo "<table width='100%'><tr><td align='right'>";
		$f = new Form($SC, "GET","NAME=Form4");
	    $f->hidden("p", $PID);
	    if (!$GLOBALS['print']){
	    	$f->search("search","Pencarian Nomor",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form4.submit();'");
		}
	    $f->execute();
		echo "</td></tr></table> <br><br>";

    echo "<br>";
        if ($_GET["search"]){
	$sql =" select tanggal(tanggal,0) || ' ' || waktu as tanggal, waktu, nomor,id_ruang, 
			case when jns_kegiatan = 'E' then 'Elektomedik' 
				 when jns_kegiatan = 'S' then 'Sipil' end as jns_kegiatan,catatan_jns,catatan, pelapor,pekerja ,
			case when status='0' then 'Belum Dikerjakan'
				 when status='1' then 'Tidak Selesai'
                                 when status='2' then 'Dialihkan pada Pihak ke-Tiga' 
                                 else 'Sudah Selesai' end as status,tanggal(tgl_selesai,0) ||' '|| waktu_selesai as tgl_selesai,id_ipsrs
			from rs80808 where nomor like '%".$_GET["search"]."%' 
            group by tanggal, waktu, nomor, jns_kegiatan,catatan,catatan_jns, pelapor,pekerja ,id_ruang,status,id_ipsrs,tgl_selesai,waktu_selesai	
			order by tanggal, waktu,status ";
        }elseif ($_GET["tanggal1D"]){
            $sql =" select tanggal(tanggal,0) || ' ' || waktu as tanggal, waktu, nomor,id_ruang, 
			case when jns_kegiatan = 'E' then 'Elektomedik' 
				 when jns_kegiatan = 'S' then 'Sipil' end as jns_kegiatan,catatan_jns,catatan, pelapor,pekerja ,
			case when status='0' then 'Belum Dikerjakan'
				 when status='1' then 'Sudah Selesai Tanpa Suku Cadang'
                                 when status='2' then 'Sudah Selesai menggunakan Suku Cadang' 
                                 when status='3' then 'Diusulkan Membuat RAB (SWAKELOLA)' 
                                 else 'Diusulkan Kepada Pihak ke-Tiga' end as status,tanggal(tgl_selesai,0)  ||' '|| waktu_selesai as tgl_selesai,id_ipsrs
			from rs80808 where status='0' and (tanggal between '$ts_check_in1' and '$ts_check_in2') 
            group by tanggal, waktu, nomor, jns_kegiatan,catatan,catatan_jns, pelapor,pekerja ,id_ruang,status,id_ipsrs,tgl_selesai,waktu_selesai	
			order by tanggal, waktu,status ";
        }else{
		$sql =" select tanggal(tanggal,0) || ' ' || waktu as tanggal, waktu, nomor,id_ruang, 
			case when jns_kegiatan = 'E' then 'Elektomedik' 
				 when jns_kegiatan = 'S' then 'Sipil' end as jns_kegiatan,catatan_jns,catatan, pelapor,pekerja ,
			case when status='0' then 'Belum Dikerjakan'
				 when status='1' then 'Tidak Selesai'
                                 when status='2' then 'Dialihkan pada Pihak ke-Tiga' 
                                 else 'Sudah Selesai' end as status,tanggal(tgl_selesai,0) ||' '|| waktu_selesai  as tgl_selesai,id_ipsrs
			from rs80808 where status='0' 
            group by tanggal, waktu, nomor, jns_kegiatan,catatan,catatan_jns, pelapor,pekerja ,id_ruang,status,id_ipsrs,tgl_selesai,waktu_selesai	
			order by tanggal, waktu,status ";
		}
			

		@$r1 = pg_query($con,$sql);
                @$n1 = pg_num_rows($r1);

		$max_row= 9999999999 ;
		$mulai = $HTTP_GET_VARS["rec"] ;
		if (!$mulai){$mulai=1;}

			
		?>

<br>
  <table CLASS=TBL_BORDER width="100%" border="0">

    <tr>
      <td class="TBL_HEAD" width="2%"><div align="center">NO. </div></td>
      <td class="TBL_HEAD" ><div align="center">NOMOR</div></td>
      <td class="TBL_HEAD" ><div align="center">NAMA RUANG</div></td>
      <td class="TBL_HEAD" ><div align="center">TANGGAL<br>LAPOR</div></td>
      <td class="TBL_HEAD" ><div align="center">JENIS PEKERJAAN</div></td>
	  <td class="TBL_HEAD" ><div align="center">NAMA ALAT/NAMA KELAS</div></td>
      <td class="TBL_HEAD" ><div align="center">PERMASALAHAN</div></td>
      <td class="TBL_HEAD" ><div align="center">PELAPOR</div></td>
	  <td class="TBL_HEAD" ><div align="center">PEKERJA</div></td>
	  <td class="TBL_HEAD" ><div align="center">STATUS</div></td>
      <td class="TBL_HEAD" ><div align="center">TANGGAL<br>SELESAI</div></td>
	  <? if (!$GLOBALS['print']){ ?>
          <td class="TBL_HEAD" width="4%"><div align="center">EDIT<br>STATUS</div></td>
	  <td class="TBL_HEAD" width="4%"><div align="center">EDIT</div></td>
	  <td class="TBL_HEAD" width="4%"><div align="center">HAPUS</div></td>
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
                        <td class="TBL_BODY" align="left"><?=$row1["nomor"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["id_ruang"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["tanggal"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["jns_kegiatan"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["catatan_jns"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["catatan"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["pelapor"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["pekerja"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["status"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["tgl_selesai"] ?> </td>
                        <? if (!$GLOBALS['print']){ ?>
                        <td class="TBL_BODY" align="center"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=status&id=".$row1["id_ipsrs"]."'>".
                            icon("ok","Edit Status pekerjaan")."</A>"; ?> </td>
                        <td class="TBL_BODY" align="center"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=edit&id=".$row1["id_ipsrs"]."'>".
                            icon("edit","Edit & Tambah Barang")."</A>"; ?> </td>
                        <td class="TBL_BODY" align="center"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=hapus1&id=".$row1['id_ipsrs']."'>".
                            icon("delete","Hapus")."</A>"; ?> </td>
                        <td class="TBL_BODY" align="center"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=view&id=".$row1['id_ipsrs']."'>".
                            icon("view","Lihat")."</A>"; ?> </td>
                        <? } ?>
                </tr>

                <?;$j++;
        }
        $i++;
}
?>
  </table>
    <br>
  <br>
  <table>
		<tr>
  <td align="center"><b><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=new'>".
                        icon("new","Tambah")." Tambah Data </A>"; ?>  </b></td>
		 </tr>
</table>
<? }
?>
