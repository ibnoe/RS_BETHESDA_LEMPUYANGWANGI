<?php
	// sfdn, 24-12-2006
session_start();

require_once("../lib/setting.php");
require_once("../lib/terbilang.php");

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

$ROWS_PER_PAGE     = 999999;
//$RS_NAME           = $set_header[0]."<br>".$set_header[1];
//$RS_ALAMAT         = $set_header[2]."<br>".$set_header[3].$set_header[4];

?>

<HTML>


<HEAD>
<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
<LINK rel='styleSheet' type='text/css' href='../cetak.css'>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>

</HEAD>

<BODY TOPMARGIN=1 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0 />

<?
echo "<hr>";
titlecashier2('INSTALAS PEMELIHARAAN SARAN RUMAH SAKIT');
titlecashier4('RSUD Dr. ACHMAD MOCHTAR BUKITTINGGI');
titlecashier1('Jl. Dr. A. Rivai - Bukittinggi');
titlecashier1('Telp. Hunting (0752) 21720 - 21492 - 21831 - 21322');
echo "<hr>";
//echo "<br>";

$r2 = pg_query($con,
            "select a.*,tanggal(a.tanggal,0) as tanggal1,tanggal(a.tgl_selesai,0) as tanggal2 ".
            "from rs80808 a ".
            "where a.id_ipsrs = '".$_GET["id"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);

echo "<br>";
titlecashier2('FORM DATA INPUT LAPORAN KERUSAKAN');
titlecashier1("No. : ".$d2->nomor);
echo "<br>";


	if ($d2->status == "0"){
	$status="Belum Dikerjakan";
	}elseif ($d2->status == "1"){
	$status="Sudah Selesai Tanpa Suku Cadang";
        }elseif ($d2->status == "2"){
	$status="Sudah Selesai menggunakan Suku Cadang";
        }elseif ($d2->status == "3"){
	$status="Diusulkan Membuat RAB (SWAKELOLA)";
	}else{$status="Diusulkan Kepada Pihak ke-Tiga";}
	
	if ($d2->jns_kegiatan == "E"){
	$status1="Elektomedik";
	}elseif ($d2->jns_kegiatan == "S"){
	$status1="Sipil";
	}
	
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
        $f->text("f_id_ruang","Ruangan",35,200,$d2->id_ruang,"readonly");
        $f->text("f_tanggal","Tanggal",15,15,$d2->tanggal,"readonly");
		if ($d2->waktu == null){
        $f->text("f_waktu","Waktu",15,15,date("H:i:s"),"readonly");
		}else{
		$f->text("f_waktu","Waktu",15,15,$d2->waktu,"readonly");
		}
        $f->text("f_nomor","Nomor",20,50,$d2->nomor,"readonly");
        $f->text("f_jns_kegiatan", "Jenis Kegiatan",20,50,$status1,"readonly");
        $f->textarea("f_catatan_jns","Nama Alat/Nama Kelas",4,40,$d2->catatan_jns,"readonly");
        $f->textarea("f_catatan","Permasalahan",4,40,$d2->catatan,"readonly");
        $f->text("f_pelapor","Nama Pelapor",30,100,$d2->pelapor,"readonly");
        $f->text("f_pekerja","Nama Pelaksana",30,100,$d2->pekerja,"readonly");
        $f->execute();
		
$tgl_sekarang = date("d M Y", time());
echo "<br>";

	$jml=getFromTable("select count(id_ipsrs) from rs80888  where id_ipsrs='".$_GET["id"]."' ");
	
	if ($jml > 0){
	echo "<table width='100%'><tr><td width='100%'>";
	titlecashier1('DATA SUKU CADANG YANG DIPERLUKAN');
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

  <table width="100%" border="1">

    <tr>
      <td class="TBL_HEAD" width="1%"><div align="center">NO. </div></td>
      <td class="TBL_HEAD" width="10%"><div align="center">NAMA SUKU CADANG</div></td>
	  <td class="TBL_HEAD" width="2%"><div align="center">JUMLAH</div></td>
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
<? } ?>
<br>
<table border="0" align="right" width="100%">
  <tr>
        <td align="center" class="TITLE_SIM3" width="50%"><b>Yang Menerima Laporan</b></td>
		<td align="center" class="TITLE_SIM3" width="50%"><b>Bukittinggi, <?echo $tgl_sekarang;?></b></td>
      
  </tr>
  <tr>
	<td align="center" class="TITLE_SIM3"><b>Adm. IPS RSAM</b></td>
    <td align="center" class="TITLE_SIM3"><b>Ka. Bagian/Ka. Instalasi/Ka. Ruangan</b></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
	<td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
	<td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
	<td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
	<td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
	<td align="center" class="TITLE_SIM3"><b>( .................................................. )</b></td>
    <td align="center" class="TITLE_SIM3"><b>( .................................................. )</b></td>
</tr>
</table>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</html>
