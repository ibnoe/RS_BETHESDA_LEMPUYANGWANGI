<?php 

$PID = "terima_gudang";
session_start();
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/functions.php");
require_once("lib/class.PgTrans.php");
require_once("lib/terbilang.php");


//$no_faktur = getFromTable("select tdesc from rs00001 where tc = '$d->poli'");
$pengirim = getFromTable("select pengirim from c_po_gudang where id_po_gd='".$_GET["po_id"]."' ");
$penerima = getFromTable("select penerima from c_po_gudang where id_po_gd='".$_GET["po_id"]."' ");

$apotek=getFromTable("select gr_ket from rs_grup_user where gr_id='".$_SESSION["gr"]."'");

$id_sup=getFromTable("select supp_id from c_po  where po_id='".$_GET["po_id"]."'");
$ppn=getFromTable("select ppn from c_po  where po_id='".$_GET["po_id"]."'");
$disc1=getFromTable("select disc1 from c_po  where po_id='".$_GET["po_id"]."'");
$disc2=getFromTable("select disc2 from c_po  where po_id='".$_GET["po_id"]."'");

$alamat=getFromTable("select alamat_jln1||', '||alamat_kota from rs00028  where id::numeric=$id_sup");
$npwp = getFromTable("select npwp from rs00028 where id::numeric=$id_sup");
$tgl_po = getFromTable("select to_char(tanggal,'dd Mon yyyy') from c_po_gudang where  id_po_gd='".$_GET["po_id"]."'");
$tgl_sekarang=date("d F Y");

$SQL = "select a.obat,c.tdesc,b.jumlah from rs00015 a, c_po_item_gd b, rs00001 c
where b.id_po_gd='".$_GET["po_id"]."' and a.id=b.item_id and b.satuan=c.tc and c.tt='SAT'";
			@$r1 = pg_query($con,$SQL);
			@$n1 = pg_num_rows($r1);
	
	
   			$max_row= 200 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<table width="800" cellpadding="0" cellspacing="0" border="1">
<tr><td>

<TABLE border="0" cellpadding="3" cellspacing="0" width="800">
<TR>
    <TD colspan=2 align=center><BIG><BIG><B><? //=$RS_NAME?></B></BIG></BIG></TD>
</TR>
<TR>
    <TD colspan=2 align=center><BIG><B>Surat Penerimaan Gudang</B></BIG><br><HR noshade="true" size="1"></TD>
</TR>

<tr>
    <td colspan=2 align=center><BIG><b>&nbsp;</b></BIG><br><BIG><BIG><BIG><B><? echo $set_header[0]?></B></BIG></BIG></BIG></td>
</tr>
<tr>
    <td colspan=2>
<br>
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>&nbsp;&nbsp;&nbsp;</td>
        <td><B>NO. PO</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $_GET["po_id"];?></B></td>
    </tr>
	<tr>
        <td>&nbsp;&nbsp;&nbsp;</td>
        <td><B>TANGGAL. PO</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $tgl_po;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><B>PENGIRIM</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $pengirim;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><B>PENERIMA</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $penerima;?></B></td>
    </tr>
    </table>

    </td>
</tr>
<tr>
<td><br></td>
</tr>
</table>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">NAMA BARANG</td>
                <td class="TBL_HEAD"align="center">SATUAN</td>
				<td class="TBL_HEAD"align="center">QTY PESANAN</td>	
			</tr>
			
	
		<?	
			$jml_tagihan= 0;
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
						<td class="TBL_BODY" align="center"><?=$no ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["obat"] ?> </td>
						<td class="TBL_BODY" align="left"><?=$row1["tdesc"] ?> </td>
						<td align="center" class="TBL_BODY"><?=$row1["jumlah"] ?></td>
						
					</tr>	
					<?
					;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			
			if ($_GET["e"] != "lap"){
			$col=6;
			}else{$col=5;}
			?>
			
					<!--tr class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="right" colspan="<?=$col?>" height="25" valign="middle"> SUB TOTAL </td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($jml_tagihan,2,",",".") ?></td>
					</tr>
					<? $total=($jml_tagihan+(($jml_tagihan*$ppn)/100))+($disc1+$disc2); $ppn1=($jml_tagihan*$ppn)/100;?>
					<tr class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="right" colspan="<?=$col?>" height="25" valign="middle"> PPN <?=$ppn ?> %</td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($ppn1,2,",",".") ?></td>
					</tr>
					<tr class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="right" colspan="<?=$col?>" height="25" valign="middle"> Discount 1 </td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($disc1,2,",",".") ?></td>
					</tr>
					<tr class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="right" colspan="<?=$col?>" height="25" valign="middle"> Discount 2 </td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($disc2,2,",",".") ?></td>
					</tr>
					<tr class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="right" colspan="<?=$col?>" height="25" valign="middle"> TOTAL </td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($total,2,",",".") ?></td>
					</tr-->
                                        
</table>
<table ALIGN="right" border="0" width="100%">
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
<tr>
    <td align=center>Pengirim,</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td align=center>Cikarang, <?echo $tgl_sekarang;?></td>
</tr>
<tr>
    <td align=center>&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%" align=center>Penerima, </td>
</tr>
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
<tr>
    <td align=center><b>(_______________________)</b></td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td align=center><b>(_______________________)</b></td>
</tr>
	
</TABLE>

</td></tr>
</table>

<BR>
<?
echo "\n<script language='JavaScript'>\n";
echo "function cetakaja(tag) {\n";
echo "    sWin = window.open('includes/cetak.350.php?po_id=".$_GET["po_id"]."&jumlah=".$_GET["jumlah"]."' + tag, 'xWin',".
     " 'top=0,left=0,width=500,height=300,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

?>
<br>
<? title_print("");?>
