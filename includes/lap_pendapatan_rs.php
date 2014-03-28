<?php
/**
 * Wildan ST.
 * 18 Feb 2014 
 */
session_start();
require_once('startup.php');
require_once('lib/form.php');

$PID = 'lap_pendapatan_rs';
$SC  =$_SERVER['script_name'];

if(!empty($_GET['view'])){

	$t = new PgTable($con, "100%");
	$t->RowsPerPage =150;
	$t->ShowRowNumber =true;
	$t->setlocale("id_ID");	
	$q_tgl_trans = " AND tanggal_trans BETWEEN '".$_GET['tgl1']."' AND '".$_GET['tgl2']."' ";
	if(!empty($_GET['mPASIEN'])){
	$q_adm_tipe_pasien = " AND b.tipe = '".$_GET['mPASIEN']."'";
		if(!empty($_GET['mUNIT'])){
			$q_tipe_pasien .=" AND b.rawat_inap = '".$_GET['mUNIT']."'";
		}
	}
	else if(!empty($_GET['mUNIT'])){
			$q_adm_tipe_pasien .=" AND b.rawat_inap = '".$_GET['mUNIT']."'";
		}	
	$view = base64_decode($_GET['view']);
	if($view=='adm_rawat_inap'){
		title('<img src=\'icon/keuangan-2.gif\' align=\'absmiddle\'>Laporan Pendapatan RS - Administrasi Rawat Inap');
		$stat_ri = 'I';
		}
	else if($view=='adm_igd'){
		title('<img src=\'icon/keuangan-2.gif\' align=\'absmiddle\'>Laporan Pendapatan RS - Administrasi IGD');
		$stat_ri = 'N';
	}
	else if($view=='adm_rawat_jalan'){
		title('<img src=\'icon/keuangan-2.gif\' align=\'absmiddle\'>Laporan Pendapatan RS - Administrasi Rawat Jalan');
		$stat_ri = 'Y';
		}
	
	if(($view=='adm_rawat_inap')||($view=='adm_igd')||($view=='adm_rawat_jalan')){
	title_print();		
	title_excel(str_replace('p=','',$_SERVER['QUERY_STRING']));
	$t->ColAlign = array("CENTER","LEFT","CENTER","LEFT","CENTER", "CENTER");
	$t->ColHeader = array("TANGGAL<br/>TRANSAKSI","NO. REG","NAMA","TIPE<br/>PASIEN","LAYANAN","HARGA","DISKON","DIBAYAR PENJAMIN","JUMLAH");		
		$SQL = "SELECT tanggal(a.tanggal_entry,3), a.no_reg,d.nama, e.tdesc,c.layanan, a.harga, a.diskon, COALESCE(0,a.dibayar_penjamin),(a.tagihan-COALESCE(0,a.dibayar_penjamin)) AS total_tagihan FROM rs00008 a 
		JOIN rs00006 b ON a.no_reg = b.id AND b.rawat_inap = '".$stat_ri."' ".$q_adm_tipe_pasien." 
		JOIN rs00034 c ON a.item_id::numeric = c.id 
		LEFT JOIN rs00002 d ON d.mr_no = b.mr_no 
		JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
		WHERE a.trans_type = 'LTM' AND a.is_bayar = 'Y' ".$q_tgl_trans;
		$t->ColFooter[3] = "TOTAL";
		$t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","RIGHT","RIGHT");
		$jm = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin, SUM(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a 
		JOIN rs00006 b ON a.no_reg = b.id AND b.rawat_inap = '".$stat_ri."' ".$q_adm_tipe_pasien." 
		JOIN rs00034 c ON a.item_id::numeric = c.id 
		LEFT JOIN rs00002 d ON d.mr_no = b.mr_no 
		JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
		WHERE a.trans_type = 'LTM' AND a.is_bayar = 'Y' ".$q_tgl_trans));
		$t->ColFooter[7]=number_format($jm['total_dibayar_penjamin'],2);
		$t->ColFooter[8]=number_format($jm['total_tagihan'],2);
		}
		
	if($view=='akm_rawat_inap'){
		title_print();		
		title_excel(str_replace('p=','',$_SERVER['QUERY_STRING']));
		$t->ColAlign = array("CENTER","LEFT","CENTER", "CENTER");
		$ktr = (empty($_GET['ktr'])) ? null : " AND i.tdesc = '".base64_decode($_GET['ktr'])."'";
		$table_row = (empty($_GET['ktr'])) ? null : "<td>Tarif Kelas</td><td>:</td><td>".base64_decode($_GET['ktr'])."</td>";
		$t->ColHeader = array("TANGGAL<br/>TRANSAKSI","NO. REG","NAMA","TIPE<br/>PASIEN","BANGSAL","HARGA","DISKON","DIBAYAR PENJAMIN","JUMLAH");		
		$SQL = "SELECT tanggal(a.tanggal_entry,3),a.no_reg,d.nama, e.tdesc, h.bangsal || ' / ' || g.bangsal || '/' || i.tdesc ||  '/' || f.bangsal as bangsal,
		a.harga, a.diskon, COALESCE(a.dibayar_penjamin,0),(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a 
		JOIN rs00006 b ON a.no_reg = b.id AND b.rawat_inap = 'I' ".$q_adm_tipe_pasien." 
		JOIN rs00002 d ON d.mr_no = b.mr_no 
		JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
		JOIN rs00012 f ON a.bangsal_id = f.id
		JOIN rs00012 as g on g.hierarchy = substr(f.hierarchy,1,6) || '000000000'
		JOIN rs00012 as h on h.hierarchy = substr(f.hierarchy,1,3) || '000000000000'
		join rs00001 i on i.tc = g.klasifikasi_tarif_id and i.tt='KTR' ".$ktr."
		WHERE a.trans_type = 'POS' AND a.is_bayar = 'Y' ".$q_tgl_trans;

		$t->ColFooter[3] = "TOTAL";
		$t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","RIGHT","RIGHT");
		$jml = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin, SUM(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a 
		JOIN rs00006 b ON a.no_reg = b.id AND b.rawat_inap = 'I' ".$q_adm_tipe_pasien." 
		LEFT JOIN rs00002 d ON d.mr_no = b.mr_no 
		JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
		JOIN rs00012 f ON a.bangsal_id = f.id
		JOIN rs00012 as g on g.hierarchy = substr(f.hierarchy,1,6) || '000000000'
		JOIN rs00012 as h on h.hierarchy = substr(f.hierarchy,1,3) || '000000000000'
		join rs00001 i on i.tc = g.klasifikasi_tarif_id and i.tt='KTR' ".$ktr."
		WHERE a.trans_type = 'POS' AND a.is_bayar = 'Y' ".$q_tgl_trans));
		$t->ColFooter[7]=number_format($jml['total_dibayar_penjamin'],2);
		$t->ColFooter[8]=number_format($jml['total_tagihan'],2);

		}
	else if($view=='penunjang'||$view=='poliklinik'||$view=='pelayanan'){
		title('<img src=\'icon/keuangan-2.gif\' align=\'absmiddle\'>Laporan Pendapatan RS - Poliklinik & IGD');
		title_print();
		title_excel(str_replace('p=','',$_SERVER['QUERY_STRING']));
		$t->ColAlign = array("CENTER","LEFT","CENTER", "LEFT", "CENTER");
		$t->ColHeader = array("TANGGAL<br/>TRANSAKSI","NO. REG","NAMA","TIPE<br/>PASIEN","LAYANAN","HARGA","DISKON","DIBAYAR PENJAMIN","JUMLAH");
		$SQL="SELECT tanggal(a.tanggal_trans,3), a.no_reg, d.nama,e.tdesc, CASE WHEN 
referensi = 'P' THEN (SELECT description FROM rs99996 WHERE id = a.item_id::numeric) 
ELSE 
c.layanan END AS layanan, a.harga, a.diskon, COALESCE(a.dibayar_penjamin,0),
		(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a JOIN rs00034 c ON a.item_id::numeric = c.id 
		JOIN rs00006 b ON a.no_reg = b.id ".$q_adm_tipe_pasien." 
		LEFT JOIN rs00002 d ON d.mr_no = b.mr_no 
		JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
		WHERE a.trans_type = 'LTM' AND a.is_bayar = 'Y' AND a.trans_form='".$_GET['trans_form']."' ".$q_tgl_trans;

		$t->ColFooter[4] = "TOTAL";
		$t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","RIGHT","RIGHT");
		$jml = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin,SUM(a.tagihan - COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a JOIN rs00034 c ON a.item_id::numeric = c.id 
		JOIN rs00006 b ON a.no_reg = b.id ".$q_adm_tipe_pasien." 
		LEFT JOIN rs00002 d ON d.mr_no = b.mr_no 
		JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
		WHERE a.trans_type = 'LTM' AND a.is_bayar = 'Y' AND a.trans_form='".$_GET['trans_form']."' ".$q_tgl_trans));

		$t->ColFooter[7]=number_format($jml['total_dibayar_penjamin'],2);
		$t->ColFooter[8]=number_format($jml['total_tagihan'],2);
		}
	else if($view=='apotek'){
		title('<img src=\'icon/keuangan-2.gif\' align=\'absmiddle\'>Laporan Pendapatan RS - Apotek');
		title_print();
		title_excel(str_replace('p=','',$_SERVER['QUERY_STRING']));
		$t->ColHeader = array("TANGGAL<br/>TRANSAKSI","NO. REG","NAMA","TIPE<br/>PASIEN","OBAT","HARGA","DISKON","DIBAYAR PENJAMIN","JUMLAH");
		$SQL = "SELECT tanggal(a.tanggal_trans,3),a.no_reg, d.nama,e.tdesc, c.obat, a.harga, a.diskon, COALESCE(a.dibayar_penjamin,0),
		(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a 
		JOIN rs00015 c ON c.id::character varying =  a.item_id
		JOIN rs00006 b ON a.no_reg = b.id ".$q_adm_tipe_pasien." 
		LEFT JOIN rs00002 d ON d.mr_no = b.mr_no 
		JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
		WHERE a.trans_type IN ('OB1','OBM','BHP', 'RCK') AND a.is_bayar = 'Y' ".$q_tgl_trans;
		$t->ColFooter[4] = "TOTAL";
		$t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","RIGHT","RIGHT");
		$jml = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin,SUM(a.tagihan - COALESCE(a.dibayar_penjamin,0)) AS 
		total_tagihan FROM rs00008 a 
		JOIN rs00015 c ON c.id::character varying =  a.item_id
		JOIN rs00006 b ON a.no_reg = b.id ".$q_adm_tipe_pasien." 
		LEFT JOIN rs00002 d ON d.mr_no = b.mr_no 
		JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
		WHERE a.trans_type IN ('OB1','OBM','BHP', 'RCK') AND a.is_bayar = 'Y' ".$q_tgl_trans));
		$t->ColFooter[7]=number_format($jml['total_dibayar_penjamin'],2);
		$t->ColFooter[8]=number_format($jml['total_tagihan'],2);
		}
	else if($view=='apotek_umum'){
		title('<img src=\'icon/keuangan-2.gif\' align=\'absmiddle\'>Laporan Pendapatan RS - Apotek Umum');
		title_print();
		title_excel(str_replace('p=','',$_SERVER['QUERY_STRING']));
		$t->ColAlign = array("CENTER","CENTER","LEFT", "LEFT", "RIGHT");
		$t->ColHeader = array("TANGGAL<br/>TRANSAKSI","NO. REG","NAMA","OBAT","JUMLAH");
		$SQL = "SELECT tanggal(tanggal_entry,3), no_reg, nama, obat_nama, jumlah FROM apotik_umum WHERE 
		tanggal_entry BETWEEN '".$_GET['tgl1']."' AND '".$_GET['tgl2']."' ";
		$t->ColFooter[3] = "TOTAL";
		$t->ColFooter[4]=number_format(getFromTable("SELECT SUM(jumlah) FROM apotik_umum
		WHERE tanggal_entry BETWEEN '".$_GET['tgl1']."' AND '".$_GET['tgl2']."'"),2);
		}
	if($GLOBALS['print']){
		$t->ShowRowNumber = false;
		$t->RowsPerPage = pg_num_rows(pg_query($SQL));
		$t->DisableStatusBar = true;
	}
	$t->SQL = $SQL;
	
		?>
	<table>
			<tr>
				<td>Dari Tanggal</td><td>:</td><td><?php echo tanggal_format($_GET['tgl1'],'d-m-Y');?></td>
			</tr>
			<tr>
				<td>s/d Tanggal</td><td>:</td><td><?php echo tanggal_format($_GET['tgl2'],'d-m-Y');?></td>
			</tr>
			<tr>
				<td>Tipe Pasien</td><td>:</td><td><?php echo empty($_GET['mPASIEN']) ? 'Semua' : getFromTable("SELECT tdesc FROM rs00001 WHERE tt='JEP' AND tc='".$_GET['mPASIEN']."'");?></td>
			</tr>
			<?php			
			if(($_GET['mUNIT']=='I')&&(!empty($_GET['mUNIT']))){
				?>
				<tr>
					<td>Rawatan</td><td>:</td><td>Rawat Inap</td>
				</tr>
				<?php
				}
			else if(($_GET['mUNIT']=='Y')&&(!empty($_GET['mUNIT']))){
				?>
				<tr>
					<td>Rawatan</td><td>:</td><td>Rawat Jalan</td>
				</tr>
				<?php
				}
			else if(($_GET['mUNIT']=='N')&&(!empty($_GET['mUNIT']))){
				?>
				<tr>
					<td>Rawatan</td><td>:</td><td>IGD</td>
				</tr>
				<?php
				}
			else if(isset($table_row)){
				echo $table_row;
			}
			?>
		</table>
	<?php
	$t->execute();
	}
else{	
title('<img src=\'icon/keuangan-2.gif\' align=\'absmiddle\'>Laporan Pendapatan RS');
title_print();	
title_excel(str_replace('p=','',$_SERVER['QUERY_STRING']));

$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
$ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
$total = 0;
if(!$GLOBALS['print']){
	$cls_thead = 'class="TBL_HEAD"';
	$cls_tbody = 'class="TBL_BODY"';
    $f = new Form($SC, "get", "name='form1'");
    $f->PgConn = $con;
    $f->hidden("p", $PID);	
    $f->selectArray("mUNIT", "Rawatan",
        Array(""=>"--Semua--", "Y" => "Rawat Jalan", "I" => "Rawat Inap", "N" => "IGD"), $_GET["mUNIT"],$ext);
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
        $f->selectDate("tanggal2", "s/d Tanggal", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
    } else {
        $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
        $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
        $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
        $f->selectDate("tanggal2", "s/d Tanggal", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");        
    }
    $f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '--Semua--' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='JEP' and tc != '000' order by tdesc", $_GET["mPASIEN"],
        $ext);    
    $f->submit ("TAMPILKAN");
    $f->execute();
} else {	
	$cls_thead = 'style="background-color:#cccccc;font-size:0.88em;"';
	$cls_tbody = 'style="font-size:0.88em;"';
	if ($_GET["mUNIT"] == "Y") {
		$unit = "Rawat Jalan";
	} elseif  ($_GET["mUNIT"] == "N"){
		$unit = "IGD";
	} elseif ($_GET["mUNIT"] == "I"){
		$unit = "Rawat Inap";
	} else {
		$unit = "Semua";
	}
?>
<table>
	<tr>
		<td bgcolor='WHITE'><font size='0.9em'><b> Periode </td>
		<td bgcolor='WHITE'><font size='0.9em'><b>: <?php echo tanggal_format($ts_check_in1,'d-m-Y')?> s/d <?php echo tanggal_format($ts_check_in2,'d-m-Y')?> </td>
	</tr>
	<tr>
		<td bgcolor='WHITE'><font size='0.9em'><b> Rawatan</td>
		<td bgcolor='WHITE'><font size='0.9em'><b>: <?php echo $unit?></td>
	</tr>
	<tr>
		<td bgcolor='WHITE'><font size='0.9em'><b> Tipe Pasien</td>
		<td bgcolor='WHITE'><font size='0.9em'><b>: <?php
		$tipe_pasien = getFromTable("SELECT tdesc FROM rs00001 WHERE tt='JEP' AND tc='".$_GET['mPASIEN']."'");
		echo ($tipe_pasien==null) ? 'Semua' : $tipe_pasien;
		?>		
		</td>
	</tr>
</table>
    <?php
}
$q_tgl_trans = " AND tanggal_trans BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."' ";
if(!empty($_GET['mPASIEN'])){
	$q_adm_tipe_pasien = " AND b.tipe = '".$_GET['mPASIEN']."'";
	$q_tipe_pasien = " JOIN rs00006 b ON a.no_reg = b.id AND b.tipe = '".$_GET['mPASIEN']."'";
	if(!empty($_GET['mUNIT'])){
		$q_tipe_pasien .=" AND b.rawat_inap = '".$_GET['mUNIT']."'";
		}
	}
else if(!empty($_GET['mUNIT'])){
		$q_tipe_pasien .=" JOIN rs00006 b ON a.no_reg = b.id AND b.rawat_inap = '".$_GET['mUNIT']."'";
		}		
?>
<!-- Laporan Pendapatan RS : Pendaftaran, Poli, Penunjang (Laboratorium), Apotik -->
<table width="100%">
	<tr>
		<td align="center" <?php echo $cls_thead?> colspan="4">ADMINISTRASI</td>		
	</tr>
	<tr>
		<td align="center" <?php echo $cls_thead?>>&nbsp;</td>
		<td align="center" <?php echo $cls_thead?>>DIBAYAR PENJAMIN</td>		
		<td align="center" <?php echo $cls_thead?>>TAGIHAN</td>		
		<td align="center" <?php echo $cls_thead?>>&nbsp;</td>
	</tr>
<?php if(($_GET['mUNIT']=='Y') || (empty($_GET['mUNIT']))){
	$rawat_jalan = pg_fetch_array(pg_query("SELECT SUM(a.dibayar_penjamin) AS total_dibayar_penjamin, SUM(a.tagihan-COALESCE(0,a.dibayar_penjamin)) AS total_tagihan FROM rs00008 a JOIN rs00006 b ON a.no_reg = b.id AND b.rawat_inap = 'Y' ".$q_adm_tipe_pasien." JOIN rs00034 c ON a.item_id::numeric = c.id WHERE a.trans_type = 'LTM' ".$q_tgl_trans));
	$total += $rawat_jalan['total_tagihan'];
	$total_dibayar_penjamin += $rawat_jalan['total_dibayar_penjamin'];
	?>	
	<tr>
	
	    <td align="left" <?php echo $cls_tbody?>>&minus; RAWAT JALAN</td>
	    <td align="right" <?php echo $cls_tbody?> ><?php echo number_format($rawat_jalan['total_dibayar_penjamin'],2)?></td>
	    <td align="right" <?php echo $cls_tbody?> ><?php echo number_format($rawat_jalan['total_tagihan'],2)?></td>
<?php if(!$GLOBALS['print']){ ?>
	    <td align="center" <?php echo $cls_tbody?>><a href="<?php echo $SC.'index2.php?p='.$PID.'&view='.base64_encode('adm_rawat_jalan').'&tgl1='.$ts_check_in1.'&tgl2='.$ts_check_in2.'&mPASIEN='.$_GET['mPASIEN']?>"><?php echo ($GLOBALS['print']) ? '&nbsp;' : icon('view','View');?></a></td>
<?php } ?>
	</tr>
<?php } if(($_GET['mUNIT']=='I') || (empty($_GET['mUNIT']))){
	$rawat_inap = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin, SUM(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a JOIN rs00006 b ON a.no_reg = b.id AND b.rawat_inap = 'I' ".$q_adm_tipe_pasien." JOIN rs00034 c ON a.item_id::numeric = c.id WHERE a.trans_type = 'LTM' AND a.is_bayar = 'Y' ".$q_tgl_trans));
	$total += $rawat_inap['total_tagihan'];
	$total_dibayar_penjamin += $rawat_inap['total_dibayar_penjamin'];
	?>	
	<tr>
		<td align="left" <?php echo $cls_tbody?>>&minus; RAWAT INAP</td>
		<td align="right" <?php echo $cls_tbody?>><?php echo number_format($rawat_inap['total_dibayar_penjamin'],2)?></td>
		<td align="right" <?php echo $cls_tbody?>><?php echo number_format($rawat_inap['total_tagihan'],2)?></td>
		<?php if(!$GLOBALS['print']){ ?>
		<td align="center" <?php echo $cls_tbody?>><a href="<?php echo $SC.'index2.php?p='.$PID.'&view='.base64_encode('adm_rawat_inap').'&tgl1='.$ts_check_in1.'&tgl2='.$ts_check_in2.'&mPASIEN='.$_GET['mPASIEN']?>"><?php echo ($GLOBALS['print']) ? '&nbsp;' : icon('view','View');?></a></td>
		<?php } ?>
	</tr>
<?php } if(($_GET['mUNIT']=='N') || (empty($_GET['mUNIT']))){
	$igd = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin, SUM(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a JOIN rs00006 b ON a.no_reg = b.id AND b.rawat_inap = 'N' ".$q_adm_tipe_pasien." JOIN rs00034 c ON a.item_id::numeric = c.id WHERE a.trans_type = 'LTM' AND a.is_bayar = 'Y' ".$q_tgl_trans));
	$total += $igd['total_tagihan'];
	$total_dibayar_penjamin += $igd['total_dibayar_penjamin'];
	?>		
	<tr>
		<td align="left" <?php echo $cls_tbody?>>&minus; IGD</td>
		<td align="right" <?php echo $cls_tbody?>><?php echo number_format($igd['total_dibayar_penjamin'],2)?></td>
		<td align="right" <?php echo $cls_tbody?>><?php echo number_format($igd['total_tagihan'],2)?></td>
		<?php if(!$GLOBALS['print']){ ?>
		<td align="center" <?php echo $cls_tbody?>><a href="<?php echo $SC.'index2.php?p='.$PID.'&view='.base64_encode('adm_igd').'&tgl1='.$ts_check_in1.'&tgl2='.$ts_check_in2.'&mPASIEN='.$_GET['mPASIEN']?>"><?php echo ($GLOBALS['print']) ? '&nbsp;' : icon('view','View');?></a></td>
		<?php } ?>
	</tr>
<?php } if(($_GET['mUNIT']=='I') || (empty($_GET['mUNIT']))){?>
	<tr>
		<td align="center" <?php echo $cls_thead?> colspan="4">RAWAT INAP</td>
	</tr>
	<tr>
		<td align="center" <?php echo $cls_thead?>>&nbsp;</td>
		<td align="center" <?php echo $cls_thead?>>DIBAYAR PENJAMIN</td>		
		<td align="center" <?php echo $cls_thead?>>TAGIHAN</td>		
		<td align="center" <?php echo $cls_thead?>>&nbsp;</td>
	</tr>
	<tr>
		<td align="left" <?php echo $cls_tbody?>>&minus; SEWA KAMAR</td>
		<td align="right" <?php echo $cls_tbody?>>
		<?php
		$akomodasi = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin, SUM(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a ".$q_tipe_pasien." WHERE a.trans_type = 'POS' AND a.is_bayar = 'Y' ".$q_tgl_trans));
		$total += $akomodasi['total_tagihan'];
		$total += $akomodasi['total_dibayar_penjamin'];
		echo number_format($akomodasi['total_dibayar_penjamin'], 2);
		?>
		</td>
		<td align="right" <?php echo $cls_tbody?>>
		<?php echo number_format($akomodasi['total_tagihan'], 2);?>
		</td>
		<?php if(!$GLOBALS['print']){ ?>
		<td align="center" <?php echo $cls_tbody?>><a href="<?php echo $SC.'index2.php?p='.$PID.'&view='.base64_encode('akm_rawat_inap').'&tgl1='.$ts_check_in1.'&tgl2='.$ts_check_in2.'&mPASIEN='.$_GET['mPASIEN']?>"><?php echo ($GLOBALS['print']) ? '&nbsp;' : icon('view','View');?></a></td>
		<?php } ?>
	</tr>
	
	<!-- start Row Kamar-->
	<?php
		$sqlKamar = "SELECT 
			i.tdesc AS kelas,
			SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin, 
			SUM(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan 
		FROM rs00008 a 
			JOIN rs00006 b ON a.no_reg = b.id AND b.rawat_inap = 'I' 
			JOIN rs00002 d ON d.mr_no = b.mr_no 
			JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
			JOIN rs00012 f ON a.bangsal_id = f.id
			JOIN rs00012 as g on g.hierarchy = substr(f.hierarchy,1,6) || '000000000'
			JOIN rs00012 as h on h.hierarchy = substr(f.hierarchy,1,3) || '000000000000'
			join rs00001 i on i.tc = g.klasifikasi_tarif_id and i.tt='KTR'
		WHERE 
			a.trans_type = 'POS' AND a.is_bayar = 'Y' ".$q_tgl_trans."
		GROUP BY i.tdesc
		ORDER BY i.tdesc ASC";
		
		@$rk = pg_query($con, $sqlKamar);
		@$nk = pg_num_rows($rk);
		while (@$rowKamar = pg_fetch_array($rk)) {
			?>
			<tr>
				<td colspan="1" align="left" <?php echo $cls_tbody?>>&nbsp;&nbsp;&nbsp;&minus; <?php echo $rowKamar["kelas"];?></td>
				<td colspan="1" align="right" <?php echo $cls_tbody?>><?php echo number_format($rowKamar["total_dibayar_penjamin"], 2);?></td>
				<td colspan="1" align="right" <?php echo $cls_tbody?>><?php echo number_format($rowKamar["total_tagihan"], 2);?></td>
				<td colspan="1" align="center" <?php echo $cls_tbody?>><a href="<?php echo $SC.'index2.php?p='.$PID.'&view='.base64_encode('akm_rawat_inap').'&ktr='.base64_encode($rowKamar['kelas']).'&tgl1='.$ts_check_in1.'&tgl2='.$ts_check_in2.'&mPASIEN='.$_GET['mPASIEN']?>"><?php echo ($GLOBALS['print']) ? '&nbsp;' : icon('view','View');?></a></td>
			</tr>
			<?php
		}
	?>
	<!-- end Row Kamar-->
	
	<tr>
		<td align="left" <?php echo $cls_tbody?>>&minus; PELAYANAN</td>
		<td align="right" <?php echo $cls_tbody?>>
		<?php
		$pelayanan = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin, SUM(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a ".$q_tipe_pasien." JOIN rs00034 c ON a.item_id::numeric = c.id WHERE a.trans_type = 'LTM' AND a.is_bayar = 'Y' AND a.trans_form='p_riwayat_penyakit' ".$q_tgl_trans));
		$total += $pelayanan['total_tagihan'];
		$total += $pelayanan['total_dibayar_penjamin'];
		echo number_format($pelayanan['total_dibayar_penjamin'], 2);
		?>
		</td>
		<td align="right" <?php echo $cls_tbody?>><?php echo number_format($pelayanan['total_tagihan'], 2);?></td>
		<?php if(!$GLOBALS['print']){ ?>
		<td align="center" <?php echo $cls_tbody?>><a href="<?php echo $SC.'index2.php?p='.$PID.'&view='.base64_encode('pelayanan').'&tgl1='.$ts_check_in1.'&tgl2='.$ts_check_in2.'&mPASIEN='.$_GET['mPASIEN'].'&trans_form=p_riwayat_penyakit&mUNIT='.$_GET['mUNIT']?>"><?php echo ($GLOBALS['print']) ? '&nbsp;' : icon('view','View');?></a></td>
		<?php } ?>
	</tr>
<?php } ?>		
	<tr>
		<td align="center" <?php echo $cls_thead?> colspan="4">LAYANAN POLIKLINIK &amp; IGD</td>
	</tr>
	<tr>
		<td align="center" <?php echo $cls_thead?>>&nbsp;</td>
		<td align="center" <?php echo $cls_thead?>>DIBAYAR PENJAMIN</td>		
		<td align="center" <?php echo $cls_thead?>>TAGIHAN</td>		
		<td align="center" <?php echo $cls_thead?>>&nbsp;</td>
	</tr>
	<?php
	$poli_query = pg_query("SELECT tc, tdesc, comment FROM rs00001 WHERE tt='LYN' AND tc NOT IN('201',
'202','203','204','205','206','207','208','209','210','000','110') ORDER BY tc");
	while($poli = pg_fetch_array($poli_query)){
		$poliklinik = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin, SUM(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a ".$q_tipe_pasien." JOIN rs00034 c ON a.item_id::numeric = c.id WHERE a.trans_type = 'LTM' AND a.is_bayar = 'Y' AND a.trans_form='".$poli['comment']."' ".$q_tgl_trans));
		$total += $poliklinik['total_tagihan'];
		$total_dibayar_penjamin += $poliklinik['total_dibayar_penjamin'];
		?>
	<tr>
		<td align="left" <?php echo $cls_tbody?>>&minus; <?php echo $poli['tdesc']?></td>
		<td <?php echo $cls_tbody?> align="right"><?php echo number_format($poliklinik['total_dibayar_penjamin'],2)?></td>
		<td <?php echo $cls_tbody?> align="right"><?php echo number_format($poliklinik['total_tagihan'],2)?></td>
		<?php if(!$GLOBALS['print']){ ?>
		<td align="center" <?php echo $cls_tbody?>><a href="<?php echo $SC.'index2.php?p='.$PID.'&view='.base64_encode('poliklinik').'&tgl1='.$ts_check_in1.'&tgl2='.$ts_check_in2.'&mPASIEN='.$_GET['mPASIEN'].'&trans_form='.$poli['comment'].'&mUNIT='.$_GET['mUNIT']?>"><?php echo ($GLOBALS['print']) ? '&nbsp;' : icon('view','View');?></a></td></tr>	
		<?php } ?>
	<?php
	}
	?>
	<tr>
		<td align="center" <?php echo $cls_thead?> colspan="4">PENUNJANG</td>
	</tr>
		<tr>
		<td align="center" <?php echo $cls_thead?>>&nbsp;</td>
		<td align="center" <?php echo $cls_thead?>>DIBAYAR PENJAMIN</td>		
		<td align="center" <?php echo $cls_thead?>>TAGIHAN</td>		
		<td align="center" <?php echo $cls_thead?>>&nbsp;</td>
	</tr>
		<?php
	$poli_query = pg_query("SELECT tc, tdesc, comment FROM rs00001 WHERE tt='LYN' AND tc IN('203','204','205') ORDER BY tc");
	while($poli = pg_fetch_array($poli_query)){
		$penunjang = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin, SUM(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a ".$q_tipe_pasien." JOIN rs00034 c ON a.item_id::numeric = c.id WHERE a.trans_type = 'LTM' AND a.is_bayar = 'Y' AND a.trans_form='".$poli['comment']."' ".$q_tgl_trans));
		$total_dibayar_penjamin += $penunjang['total_dibayar_penjamin'];
		$total += $penunjang['total_tagihan'];
		?>
	<tr><td align="left" <?php echo $cls_tbody?>>&minus; <?php echo $poli['tdesc']?></td>
		<td <?php echo $cls_tbody?> align="right"><?php echo number_format($penunjang['total_dibayar_penjamin'], 2);?></td>
		<td <?php echo $cls_tbody?> align="right"><?php echo number_format($penunjang['total_tagihan'], 2);?></td>
		<?php if(!$GLOBALS['print']){ ?>
		<td align="center" <?php echo $cls_tbody?>><a href="<?php echo $SC.'index2.php?p='.$PID.'&view='.base64_encode('penunjang').'&tgl1='.$ts_check_in1.'&tgl2='.$ts_check_in2.'&mPASIEN='.$_GET['mPASIEN'].'&trans_form='.$poli['comment'].'&mUNIT='.$_GET['mUNIT']?>"><?php echo ($GLOBALS['print']) ? '&nbsp;' : icon('view','View');?></a></td></tr>	
		<?php } ?>
	<?php
	}
	?>
	<tr>
		<td align="center" <?php echo $cls_thead?> colspan="4">APOTEK</td>
	</tr>
		<tr>
		<td align="center" <?php echo $cls_thead?>>&nbsp;</td>
		<td align="center" <?php echo $cls_thead?>>DIBAYAR PENJAMIN</td>		
		<td align="center" <?php echo $cls_thead?>>TAGIHAN</td>		
		<td align="center" <?php echo $cls_thead?>>&nbsp;</td>
	</tr>
	<tr>
		<td align="left" <?php echo $cls_tbody?>>&minus; APOTEK KLINIK</td>
		<td <?php echo $cls_tbody?> align="right">
		<?php
		$apotik_klinik = pg_fetch_array(pg_query("SELECT SUM(COALESCE(a.dibayar_penjamin,0)) AS total_dibayar_penjamin, SUM(a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS total_tagihan FROM rs00008 a ".$q_tipe_pasien." WHERE a.trans_type IN ('OB1','OBM','BHP', 'RCK') AND a.is_bayar = 'Y' ".$q_tgl_trans));
		$total_dibayar_penjamin += $apotik_klinik['total_dibayar_penjamin'];
		$total += $apotik_klinik['total_tagihan'];
		echo number_format($apotik_klinik['total_dibayar_penjamin'],2);		
		?>
		</td>
		<td <?php echo $cls_tbody?> align="right"><?php echo number_format($apotik_klinik['total_tagihan'],2);?></td>
		<?php if(!$GLOBALS['print']){ ?>
		<td align="center" <?php echo $cls_tbody?>><a href="<?php echo $SC.'index2.php?p='.$PID.'&view='.base64_encode('apotek').'&tgl1='.$ts_check_in1.'&tgl2='.$ts_check_in2.'&mPASIEN='.$_GET['mPASIEN'].'&mUNIT='.$_GET['mUNIT'];?>"><?php echo ($GLOBALS['print']) ? '&nbsp;' : icon('view','View');?></a></td>
		<?php } ?>
	</tr>
	<tr>
		<td align="left" <?php echo $cls_tbody?>>&minus; APOTEK UMUM</td>
		<td <?php echo $cls_tbody?>>&nbsp;</td>
		<td <?php echo $cls_tbody?> align="right">
		<?php
		$apotik_umum = getFromTable("SELECT SUM(jumlah) FROM apotik_umum WHERE tanggal_entry BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."'");
		$total += $apotik_umum;
		echo number_format($apotik_umum,2);		
		?></td>
		<?php if(!$GLOBALS['print']){ ?>
		<td align="center" <?php echo $cls_tbody?>><a href="<?php echo $SC.'index2.php?p='.$PID.'&view='.base64_encode('apotek_umum').'&tgl1='.$ts_check_in1.'&tgl2='.$ts_check_in2.'&mPASIEN='.$_GET['mPASIEN'].'&mUNIT='.$_GET['mUNIT'];?>"><?php echo ($GLOBALS['print']) ? '&nbsp;' : icon('view','View');?></a></td>
		<?php } ?>
	</tr>
	<tr>
	<td align="center" <?php echo $cls_thead?>><b>TOTAL</b></td>
	<td align="right" <?php echo $cls_thead?>><b><?php echo number_format($total_dibayar_penjamin,2)?></b></td>
	<td align="right" <?php echo $cls_thead?>><b><?php echo number_format($total,2)?></b></td>
	<td align="left" <?php echo $cls_thead?>>&nbsp;</td>
	</tr>	
</table>
<?php }?>
