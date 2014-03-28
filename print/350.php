<?php 

$PID = "350";
session_start();
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/functions.php");
require_once("lib/class.PgTrans.php");
require_once("lib/terbilang.php");

if($_GET["no_faktur"]){
$_GET["po_id"]=getFromTable("select po_id from c_po_item_terima where no_faktur='".$_GET["no_faktur"]."'");
}
//$no_faktur = getFromTable("select tdesc from rs00001 where tc = '$d->poli'");
$supplier = getFromTable("select a.nama from rs00028 a, c_po b where  b.po_id='".$_GET["po_id"]."' and a.id::numeric= b.supp_id");

$apotek= $_SESSION[nama_usr]; //getFromTable("select gr_ket from rs_grup_user where gr_id='".$_SESSION["gr"]."'");

$id_sup=getFromTable("select supp_id from c_po  where po_id='".$_GET["po_id"]."'");
$ppn=getFromTable("select ppn from c_po  where po_id='".$_GET["po_id"]."'");
$disc1=getFromTable("select disc1 from c_po  where po_id='".$_GET["po_id"]."'");
$disc2=getFromTable("select disc2 from c_po  where po_id='".$_GET["po_id"]."'");

$alamat=getFromTable("select alamat_jln1||', '||alamat_kota from rs00028  where id::numeric=$id_sup");
$npwp = getFromTable("select npwp from rs00028 where id::numeric=$id_sup");
$tgl_po = getFromTable("select to_char(po_tanggal,'dd Mon yyyy') from c_po where  po_id='".$_GET["po_id"]."'");
$tgl_sekarang=date("d F Y");

	$SQL = "select a.obat,c.tdesc,b.harga_beli,b.item_qty,b.jumlah_harga,b.qty_terima from rs00015 a, c_po_item b, rs00001 c
where b.po_id='".$_GET["po_id"]."' and a.id::text=b.item_id and b.satuan1=c.tc and c.tt='SAT'";
	
			@$r1 = pg_query($con,$SQL);
			@$n1 = pg_num_rows($r1);
	
	
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<table width="900" cellpadding="0" cellspacing="0" border="1">
<tr><td>

<TABLE border="0" cellpadding="3" cellspacing="0" width="900">
<TR>
    <TD colspan=2 align=center><BIG><BIG><B><? //=$RS_NAME?></B></BIG></BIG></TD>
</TR>
<TR>
    <TD colspan=2 align=center><BIG><B>Surat Pesanan Perbekalan Farmasi <?//echo $apotek; ?></B></BIG><br><HR noshade="true" size="1"></TD>
</TR>

<tr>
    <td colspan=2 align=center><BIG><b>&nbsp;</b></BIG><br><BIG><BIG><BIG><B>Instalasi Farmasi<br><? echo $set_header[0]?></br></B></BIG></BIG></BIG> <br>Jl. Hayam Wuruk No. 6 Bausasran – Danurejan – Yogyakarta 55211
Telp. (0274) 588002 Fax. (0274) 547253
</br></td>
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
        <td><B>NAMA SUPPLIER</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $supplier;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><B>ALAMAT</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $alamat;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><B>NPWP</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $npwp;?></B></td>
    </tr>
    </table>

    </td>
</tr>
<tr>
<td><br></td>
</tr>
</table>
<TABLE ALIGN="center" CLASS='TBL_BORDER' WIDTH='100%' BORDER='1' CELLSPACING='1' CELLPADDING='2'>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD" align="center">NAMA BARANG</td>
                <td class="TBL_HEAD" align="center">SATUAN</td>
				<td class="TBL_HEAD" align="center">QTY PESANAN</td>	
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
						<td align="center" class="TBL_BODY"><?=$row1["item_qty"] ?></td>
						
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
        <!--table>
            <tr>
                <td>Terbilang</td>
                <td>:</td>
                <td><i><?php $y=terbilang($total);
						echo strtoupper($y);?> RUPIAH</i></td>
            </tr>
        </table-->
<table ALIGN="right" border="0" width="100%">
<tr>
    <td align=center>&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td align=center width="25%"><?php echo $client_city;?>, <?php echo $tgl_sekarang;?></td>
</tr>
<tr>
    <td align=center>Kepala Instalasi Farmasi</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%" align=center>Petugas Pembelian <?//echo $apotek; ?></td>
    <!--td width="25%" align=center>Bag. Apotek <?//echo $apotek; ?></td-->
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
    <td align=center>( Agung Suprihatin,S.Si.,Apt )</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td align=center><b>(&nbsp;&nbsp;&nbsp;<?php echo $_SESSION[uid];?>&nbsp;&nbsp;&nbsp;)</b></td>
</tr><tr>
    <td width="25%" align=center>&nbsp;&nbsp;19701111/SIPA-3471/2012/2012</td>
    <td width="25%" align=center>&nbsp;&nbsp; </td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
	
</TABLE>

</td></tr>
</table>

<BR>
<?
/*
echo "\n<script language='JavaScript'>\n";
echo "function cetakaja(tag) {\n";
echo "    sWin = window.open('includes/cetak.350.php?po_id=".$_GET["po_id"]."&jumlah=".$_GET["jumlah"]."' + tag, 'xWin',".
     " 'top=0,left=0,width=500,height=300,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";
*/
?>
<br>
<? title_print2("");?>
