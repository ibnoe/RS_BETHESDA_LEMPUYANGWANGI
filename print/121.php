<?php // Nugraha, Tue Mar 30 05:10:23 WIT 2004
	  // sfdn, 02-06-2004
	  // sfdn, 06-06-2004
	  // sfdn, 09-06-2004
	  // sfdn, 11-06-2004

$PID = "121";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/functions.php");
require_once("lib/class.PgTrans.php");
require_once("lib/phpbarcode/barcode.php");
//require '../../../sysconfig.inc.php';
//require SIMBIO_BASE_DIR.'simbio_GUI/table/simbio_table.inc.php';
//require SIMBIO_BASE_DIR.'simbio_GUI/form_maker/simbio_form_table_AJAX.inc.php';
//require SIMBIO_BASE_DIR.'simbio_GUI/paging/simbio_paging_ajax.inc.php';
//require SIMBIO_BASE_DIR.'simbio_DB/datagrid/simbio_dbgrid.inc.php';
//require SIMBIO_BASE_DIR.'simbio_DB/simbio_dbop.inc.php';

$reg_count = getFromTable("select count(mr_no) from rs00006 where mr_no = (select mr_no from rs00006 where id = '".$_GET["id"]."') and id <= '".$_GET["id"]."'");
//echo $_GET["id"];
$r = pg_query($con,"select a.id, a.mr_no, b.nama, b.tgl_lahir, b.jenis_kelamin, ".
				   "a.tanggal_reg, a.waktu_reg, a.tipe, a.rujukan, a.rawat_inap, b.umur, b.alm_tetap, a.poli ".
				   "from rs00006 as a, rs00002 as b ". //"left join rs00034 c on a.poli = c.id  ".
				   "where a.mr_no = b.mr_no ".
				   "and a.id = '".$_GET["id"]."'");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);
// tambahan sfdn 09-06-2004
$baru  = "Y";
$loket = "RJN";
if ($reg_count > 1 ) $baru = "T";
if ($d->rawat_inap == "N" ) {
	$loket = "IGD";
}elseif ($d->rawat_inap == "I"){
$loket = "RIN";
}
$poly=$d->poli;
// akhir tambahan, 31-05-2004
if ($loket == "RJN" OR $loket == "IGD")  {
    $tr = new PgTrans;
	$tr->PgConn = $con;
	
	$tr->addSQL("insert into rs00008 (id,trans_type, is_inout, qty , no_reg, is_baru, ".
    			"tanggal_trans, datang_id, trans_group ".
    			") values (" .
            	"nextval('rs00008_seq'),'$loket', 'I', 1, '$d->id', ".
            	"'$baru','$d->tanggal_reg','$d->rujukan',nextval('rs00008_seq_group'))");
	//$tr->add("update rs00006 set id = $no_reg
            	// update kode poli IGD di table rs00006
    	if ($loket == "IGD" and $poly != '208') {
           $tr->addSQL("update rs00006 set poli=100 where id = '".$_GET["id"]."'");
	    $d->poli = 100;
		
	}
//$tr->showSQL();
$tr->execute();
}

//$namapoli = getFromTable("select layanan from rs00034 where id = $d->poli");
$namapoli = getFromTable("select tdesc from rs00001 where tc = '$d->poli'");
$noUrut = getFromTable("select count(id) from rs00006 where poli = $d->poli and tanggal_reg = '$d->tanggal_reg'");

        //if (!empty($itemID)) {
            $card_text = ($_GET["id"]);
            //echo 'new Ajax.Request(\'lib/phpbarcode/barcode.php?code='.$card_text.'&encoding='.$sysconf['barcode_encoding'].'&scale='.$size.'&mode=png\', { method: \'get\', onFailure: function(sendAlert) { alert(\'Error creating card!\'); } });'."\n";
            // add to sessions
            //$_SESSION['card'][$itemID] = $itemID;
            //$print_count++;
        //}

?>
<table width="300" cellpadding="0" cellspacing="0" border="1">
<tr><td>

<TABLE border="0" cellpadding="3" cellspacing="0" width="300">
<TR>
    <TD colspan=2 align=center><BIG><BIG><B><? //=$RS_NAME?></B></BIG></BIG></TD>
</TR>
<TR>
    <TD colspan=2 align=center><BIG><B>KARTU REGISTRASI</B></BIG><br><HR noshade="true" size="1"></TD>
</TR>

<tr>
    <td colspan=2 align=right><BIG><b>NO. REGISTRASI:&nbsp;&nbsp;&nbsp;&nbsp;</b></BIG><br><BIG><BIG><BIG><B><?=formatRegNo($_GET["id"]);?>&nbsp;&nbsp;&nbsp;</B></BIG></BIG></BIG></td>
</tr>
<tr>
    <td colspan=2>

    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><B>NO. RM</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $d->mr_no;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><B>NAMA</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $d->nama;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><B>ALAMAT</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $d->alm_tetap;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td valign=top><B>PASIEN</B></td>
        <td valign=top><B>&nbsp;:&nbsp;</B></td>
        <td><B><?php echo $namapoli;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td valign=top><B>No Urut</B></td>
        <td valign=top><B>&nbsp;:&nbsp;</B></td>
        <td><B><? echo $noUrut;?></B></td>
    </tr>

    </table>

    </td>
</tr>
<tr>
    <td><br></td>
</tr>
<tr>
    <td align=left>&nbsp;&nbsp;&nbsp;&nbsp;KUNJUNGAN: <? echo $reg_count;?></td>
    <td align="right"><?=date("d/m/Y",pgsql2mktime($d->tanggal_reg))." ".substr($d->waktu_reg,0,8)?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
</TABLE>
</td></tr>
</table>

<BR>
<?
echo "\n<script language='JavaScript'>\n";
echo "function cetakaja(tag) {\n";
echo "    sWin = window.open('includes/cetak.121.php?rg=' + tag, 'xWin',".
     " 'top=0,left=0,width=500,height=300,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

?>
<br>
<div align="left">
<a href="javascript: cetakaja('<?echo $_GET[id];?>')" ><img src="images/cetak.gif" border="0"></a>
</div>
