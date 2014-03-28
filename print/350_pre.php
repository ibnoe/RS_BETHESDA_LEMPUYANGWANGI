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

$nama=getFromTable("select po_personal from c_po where po_id='".$_GET["po_id"]."'");
$jabatan=getFromTable("select jabatan from rs00017 where nama='".$nama."'");
$alamat=getFromTable("select alamat from rs00017 where nama='".$nama."'");

$id_sup=getFromTable("select supp_id from c_po  where po_id='".$_GET["po_id"]."'");
$ppn=getFromTable("select ppn from c_po  where po_id='".$_GET["po_id"]."'");
$disc1=getFromTable("select disc1 from c_po  where po_id='".$_GET["po_id"]."'");
$disc2=getFromTable("select disc2 from c_po  where po_id='".$_GET["po_id"]."'");

$alamat_supp=getFromTable("select alamat_jln1||', '||alamat_kota from rs00028  where id::numeric=$id_sup");
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
    <TD colspan=2 align=center><BIG><B>SURAT PESANAN  <br> OBAT MENGANDUNG PREKUSOR FARMASI <?//echo $apotek; ?></B></BIG></TD>
</TR>
<tr>
        <td colspan=2 align=CENTER><B>Nomor SP : <?echo $_GET["po_id"];?></B><br><HR noshade="true" size="1"></td>
    </tr>
<TR>
<tr>
    <td colspan=2>
<br>
    <table cellpadding="0" cellspacing="0" border="0">
    
	<tr>
        <td colspan=4>Yang bertandatangan di bawah ini :</td>
		
    </tr>
	<tr>
        <td>&nbsp;&nbsp;</td>
    </tr>
	<tr>
        <td>&nbsp;&nbsp;</td>
        <td>Nama</td>
        <td>&nbsp;:&nbsp;</td>
        <td><?echo $nama;?></td>
    </tr>
	<tr>
        <td>&nbsp;&nbsp;</td>
        <td> Jabatan  </td>
        <td> &nbsp;:&nbsp; </td>
        <td> <?echo $jabatan;?> </td>
    </tr>
	<tr>
        <td>&nbsp;&nbsp;</td>
        <td> Nomor SIPA  </td>
        <td> &nbsp;:&nbsp; </td>
        <td> 19701111/SIPA-3471/2012/2012 </td>
    </tr>
	<tr>
        <td>&nbsp;&nbsp;</td>
    </tr>
	<tr>
        <td colspan=4>Mengajukan Pesanan obat mengandung Prekusor Farmasi yang dipesan kepada :</td>
    </tr>
	<tr>
        <td>&nbsp;&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;</td>
        <td> NAMA SUPPLIER </td>
        <td> &nbsp;:&nbsp; </td>
        <td> <?echo $supplier;?> </td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td> ALAMAT </td>
        <td> &nbsp;:&nbsp; </td>
        <td> <?echo $alamat_supp;?> </td>
    </tr>
	<tr>
        <td>&nbsp;&nbsp;</td>
    </tr>
    </table>

    </td>
</tr>
<tr>
        <td colspan=4>Jenis Obat mengandung Prekusor Farmasi yang dipesan adalah :</td>
    </tr>
</table>
<TABLE ALIGN="center" CLASS='TBL_BORDER' WIDTH='100%' BORDER='1' CELLSPACING='1' CELLPADDING='2'>
<tr >     	
				<td  width="4%" align="center">NO</td>
				<td  align="center">NAMA OBAT</td>
				<td  align="center">ZAT AKTIF</td>
				<td  align="center">BENTUK DAN <br> KEKUATAN SEDIAAN</td>
                <td  align="center">SATUAN</td>
				<td  align="center" colspan=2>QTY PESANAN</td>
				<td  align="center" width='300'>KETERANGAN</td>
			</tr>
		<?	
			$jml_tagihan= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					
					$no=$i 	
					?>		
				 	<tr valign="top"  >  
						<td  align="center"> <?=$no ?> </td>
						<td  align="left"><?=$row1["obat"] ?> </td>
						<td  align="left">&nbsp; <br>&nbsp; </td>
						<td  align="left">&nbsp; <br>&nbsp; </td>
						<td  align="left"><?=$row1["tdesc"] ?> </td>
						<td align="center" ><?=$row1["item_qty"] ?></td>
						<td align="center" >( <?=terbilang($row1["item_qty"]) ?> )</td>
						<td align="center" >&nbsp; <br>&nbsp;</td>
						
					</tr>	
					<?
					;$j++;					
				}
				$i++;	
			} 
			
			if ($_GET["e"] != "lap"){
			$col=6;
			}else{$col=5;}
			?>
                                        
</table>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
        <td>&nbsp;&nbsp;</td>
</tr>
<tr>
        <td colspan=4>Obat Mengandung Prekusor tersebut akan digunakan untuk memenuhi Kebutuhan</td>

</tr>
<tr>
        <td>&nbsp;&nbsp;</td>
</tr>
<tr>
        <td> Nama </td>
        <td> &nbsp;:&nbsp; </td>
        <td> Instalasi Farmasi <? echo $set_header[0]?> </td>
</tr>
<tr>
        <td> Alamat Lengkap</td>
        <td> &nbsp;:&nbsp; </td>
        <td><? echo $set_header[2]?> </td>
</tr>
<tr>
        <td> Surat Ijin RS </td>
        <td> &nbsp;:&nbsp; </td>
        <td><? echo $set_header[4]?> </td>
</tr>
<tr>
        <td>&nbsp;&nbsp;</td>
</tr>
</table>
<table ALIGN="right" border="0" width="100%">
<tr>
    <td align=center>&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td align=center width="25%"><?php echo $client_city;?>, <?php echo $tgl_sekarang;?></td>
</tr>
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
</tr>
<tr>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td align=center><u>( Agung Suprihatin,S.Si.,Apt )</u></td>
</tr>
<tr>
    <td width="25%" align=center>&nbsp;&nbsp; </td>
    <td width="25%">&nbsp;</td>
    <td width="25%" align=center>&nbsp;&nbsp;19701111/SIPA-3471/2012/2012</td>
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
