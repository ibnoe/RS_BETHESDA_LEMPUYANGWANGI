<?php
$PID = 'lap_pengeluaran_obat';
/**
 * Gema Perbangsa
 * Juli 2013
 */
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$SC = $_SERVER["SCRIPT_NAME"];
title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > LAPORAN PENGELUARAN OBAT");
title_excel($PID.'&tanggal1D='.$_GET['tanggal1D'].'&tanggal1M='.$_GET['tanggal1M'].'&tanggal1Y='.$_GET['tanggal1Y'].
'&tanggal2D='.$_GET['tanggal2D'].'&tanggal2M='.$_GET['tanggal2M'].'&tanggal2Y='.$_GET['tanggal2Y'].
'&mObat='.$_GET['mObat'].'&tipe_obat='.$_GET['tipe_obat'].'&obat='.$_GET['obat'].'&print=1');
if($_GET['print']!=1){
	$f = new Form($SC, "GET", "NAME=Form1");
	$f->PgConn = $con;
	$f->hidden("p", $PID);
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
	$f->selectSQL("mObat", "Kategori Obat", "select '' as tc, '' as tdesc union select tc, tdesc from rs00001  
											 where tt='GOB' and tc != '000' ORDER BY tdesc", $_GET["mObat"], null);
	$f->selectArray("tipe_obat", "Tipe Obat", array("","OB1"=>"OBAT", "RCK"=>"RACIKAN","BHP"=>"BHP"),$_GET['tipe_obat']);
	$f->text("obat","Obat",null, null, $_GET['obat'],null);
	$f->submit ("TAMPILKAN");    
	$f->execute();
}		
else{
	 $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
     $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	?>
	<table>
		<tr>
			<td>Dari Tanggal</td><td>:</td><td><?php echo $_GET["tanggal1M"].'-'.$_GET["tanggal1D"].'-'.$_GET["tanggal1Y"]; ?></td>
		</tr>
		<tr>
			<td>s / d Tanggal</td><td>:</td><td><?php echo $_GET["tanggal2M"].'-'.$_GET["tanggal2D"].'-'.$_GET["tanggal2Y"];?></td>
		</tr>
		<tr>
			<td>Kategori Obat</td><td>:</td><td><?php echo (empty($_GET['mObat'])) ? 'Semua Kategori' : getFromTable("SELECT tdesc FROM rs00001 WHERE tc='".$_GET['mObat']."' AND tt='GOB'");?></td>
		</tr>
		<tr>
			<td>Tipe Obat</td><td>:</td><td><?php if($_GET['tipe_obat']=='OB1'){ echo 'OBAT';} 
												  else if($_GET['tipe_obat']=='RCK'){ echo 'RACIKAN';} 
												  else if($_GET['tipe_obat']=='BHP'){ echo 'BHP';}  
												  else{ echo 'Semua Tipe Obat';} ?></td>
		</tr>
		<?php if(!empty($_GET['obat'])){?>
		<tr>
			<td>Obat</td><td>:</td><td><?php echo $_GET['obat'];?></td>
		</tr>
		<?php } ?>
	</table>
	<?php
	}    
$t = new PgTable($con, "100%");
$t->setlocale("id_ID");
$t->ColHeader= array("KATEGORI", "OBAT", "JUMLAH");
$t->RowsPerPage = ($_GET['print']==1) ? 10000 : 100;
$t->ShowRowNumber = ($_GET['print']==1) ? false : true;
$t->DisableNavButton = ($_GET['print']==1) ? true : false;
$t->DisableScrollBar = ($_GET['print']==1) ? true : false;
$t->DisableStatusBar = ($_GET['print']==1) ? true : false;
$COND = null;
if(!empty($_GET['obat'])){
	$COND .= " AND b.obat ILIKE '%".$_GET['obat']."%' ";
	}
if (!empty($_GET['mObat'])){
	$COND .= " AND b.kategori_id = '".$_GET['mObat']."' ";
	}
if(!empty($_GET['tipe_obat'])){
	$COND .= "AND a.trans_type = '".$_GET['tipe_obat']."'";
	$SUB_COND = "'".$_GET['tipe_obat']."'";
	}
else{
	$COND .= "AND a.trans_type IN ('OB1', 'RCK','OB2')";
	$SUB_COND = "'OB1', 'RCK','OB2'";
	}		
$SQL = "SELECT DISTINCT c.tdesc AS kategori,b.obat, 
(SELECT SUM(qty) FROM rs00008 WHERE tanggal_trans BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."' AND trans_type IN (".$SUB_COND.")
AND item_id = a.item_id) || ' ' || d.tdesc AS jumlah FROM rs00008 a 
JOIN rs00015 b ON a.item_id = b.id_obat
JOIN rs00001 c ON c.tt = 'GOB' AND c.tc = b.kategori_id 
JOIN rs00001 d ON d.tt = 'SAT' AND d.tc = b.satuan_id 
WHERE a.tanggal_trans BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."'".$COND;
$t->SQL=$SQL;

$t->execute();

?>