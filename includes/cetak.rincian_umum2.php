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
<LINK rel='styleSheet' type='text/css' href='../invoice.css'>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>

</HEAD>

<BODY TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 />

<?


$reg = $_GET["rg"];

$rt = pg_query($con,
        "select id as code, nama, alm_tetap, diagnosa_sementara, kota_tetap from rsv_pasien2 where id::text= '$reg'  ");     

    $nt = pg_num_rows($rt);
    $dt = pg_fetch_object($rt);
    pg_free_result($rt);

if ($reg > 0) {
    if (getFromTable("select id as id ".
                     "from rsv_pasien2 ".
                     "where id::text = '$reg' ".
                     " ") ==0) {
                     //"and status = 'A'") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}


//include("335.inc_.php");
?>
<?
$tgl_sekarang = date("d M Y", time());
?>

    
					<table align=center >
					<tr>
                            <td align="center" colspan="4"><font size=2 ><b><font size="2"  face="Arial">PERINCIAN TRANSAKSI FARMASI</b></u></font></td>
                    </tr>
					</table>
                        <table border ="0" align=left cellpadding="0" cellspacing="0" >
							<tr>
								<td><font size=2 ><font size="2"  face="Arial">Tanggal</font></td>
								<td><font size=2 ><font size="2"  face="Arial">: <?echo $tgl_sekarang;?></font></td>
								<td>&nbsp;&nbsp;&nbsp;</td>
								<td><font size=2 ><font size="2"  face="Arial">Dokter : <?echo $dt->diagnosa_sementara;?></font></td>
							</tr>
							<tr>
								<td><font size=2 ><font size="2"  face="Arial">No.Reg</font></td>
								<td><font size=2 ><font size="2"  face="Arial">: <?echo $dt->code;?></font></td>
							</tr>
							<tr>
								<td><font size=2 ><font size="2"  face="Arial">Nama</font></td>
								<td><font size=2 ><font size="2"  face="Arial">: <?echo $dt->nama;?></font></td>
							</tr>
							<tr>
								<td><font size=2 ><font size="2"  face="Arial">Alamat</font></td>
								<td><font size=2 ><font size="2"  face="Arial">: <?echo $dt->alm_tetap;?>, <?echo $dt->kota_tetap;?></font></td>
							</tr>
							
							</table>
					
               
    
    <table style="width: 100% !important;" cellpadding="0" cellspacing="0">
	<tr>
	   <td>---------------------------------------------------------------------</td>
	</tr>
        
    </table>

	

<?
//include("335.inc_2.php");

  // title("Pembayaran");

    if ($_GET["kas"] == "igd") {
       $loket = "IGD";
       $kasir = "IGD";
       $lyn = "layanan = '100'";
   
           
       
    } elseif ($_GET["kas"] == "rj") {
       $loket = "RJL";
       $kasir = "RJL";
       $lyn = "layanan not in ('100','99996','99997','12651','13111')";

    } else {
       $loket = "RIN";
       $kasir = "RIN";
       $lyn = "(layanan not in ('99996','99997','12651','13111'))";
       $d->poli = 0;
    }




$tgl_skrg=date('d-m-Y',time());

$sql="SELECT a.obat as nama,b.harga,b.qty, (b.tagihan-(b.harga*b.qty)) as jasa, b.tagihan, b.dosis   
	from rs00015 a, rs00008 b 
	where a.id::text=b.item_id and b.no_reg='".$_GET["rg"]."' and trans_type='OB1' and trans_form in ('320RJ_IGD','320RJ_SWD','320RJ_CDM','320RJ_ASK')";

$sql2 = "SELECT 'Racikan Obat' as nama,sum(b.harga) as harga, '' as qty , sum((b.tagihan-(b.harga*b.qty))) as jasa, sum(b.tagihan) as tagihan
		from rs00015 a, rs00008 b 
		where a.id::text=b.item_id and b.no_reg='".$_GET["rg"]."' and trans_type='RCK' and trans_form in ('320RJ_IGD','320RJ_SWD','320RJ_CDM','320RJ_ASK') ";
		
@$r1 = pg_query($con,$sql);
@$n1 = pg_num_rows($r1);

@$r2 = pg_query($con,$sql2);
@$n2 = pg_num_rows($r2);

	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
	
	if ($_GET[tt] == "igd") {
      $loket = "IGD";
	  $PID1 = "320RJ_IGD";
   } elseif ($_GET[tt] == "swd") {
      $loket = "SWADAYA";
	  $PID1 = "320RJ_SWD";
   } elseif ($_GET[tt] == "cdm") {
      $loket = "CINDUO MATO";
	  $PID1 = "320RJ_CDM";
   } else {
      $loket = "AKSES";
	  $PID1 = "320RJ_ASK";
   }
   
   
   //========== cek bayar/blm
	$blm_byr=getFromTable("select sum(jumlah) from rs00005 where is_obat='Y' and kasir in ('BYG','BYC','BYS','BYA') and is_bayar='N' and reg='".$_GET["rg"]."'");
	$sdh_byr=getFromTable("select sum(jumlah) from rs00005 where is_obat='Y' and kasir in ('BYG','BYC','BYS','BYA') and is_bayar='Y' and reg='".$_GET["rg"]."'");
	$pot_byr=getFromTable("select sum(jumlah) from rs00005 where is_obat='Y' and kasir in ('POT') and is_bayar='Y' and reg='".$_GET["rg"]."'");
	$sisa_tgh=$blm_byr - ($sdh_byr + $pot_byr);
	//=========================
		
		
?>



<table width="100%" BORDER="0" CLASS="items" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" class="TBL_HEAD"><font size="2"  face="Arial">NAMA OBAT</td>
		<td align="center" class="TBL_HEAD" width="20%"><font size="2"  face="Arial">JUMLAH</td>
		<td align="center" class="TBL_HEAD" width="20%"><font size="2"  face="Arial">HARGA</td>
	</tr>

<?
		///Batas Pembelian Obat

                // Pembelian Obat
		$rec3 = getFromTable ("select count(id) from rs00008 ".
				     "where trans_type = 'OB1' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
		
		
		if ($rec3 > 0){
		$sqlf= "select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans,  
		obat, qty , sum(tagihan) as tagihan, pembayaran, trans_group, d.tdesc as kategori, a.trans_form 
		from rs00008 a, rs00015 b, rs00001 c, rs00001 d 
		where to_number(a.item_id,'999999999999') = b.id  
		and b.satuan_id = c.tc and a.trans_type = 'OB1' 
		and c.tt = 'SAT' 
		and b.kategori_id = d.tc and d.tt = 'GOB' 
		and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'
		group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form ";
		@$r5 = pg_query($con,$sqlf);
		@$n5 = pg_num_rows($r5);
		
		$max_row5= 200 ;
		$mulai5 = $HTTP_GET_VARS["rec"] ;
		if (!$mulai5){$mulai5=1;}
		
		
		
		$row5=0;
		$tagihan5=0;
		$i5= 1 ;
		$j5= 1 ;
		$last_id5=1;
		while (@$row5 = pg_fetch_array($r5)){
			  if (($j5<=$max_row5) AND ($i5 >= $mulai5)){
			  $no5=$i5;
		?>
		<tr>
			
			<td class="TBL_BODY" align="left"><font size="2"  face="Arial"><?=$row5["obat"] ?></td>
			<td class="TBL_BODY" align="center"><font size="2"  face="Arial"><?=$row5["qty"] ?></td>
			<td class="TBL_BODY" align="right"><font size="2"  face="Arial"><?=number_format($row5["tagihan"],2,",",".") ?></b></td>
		</tr>
		<?
		$tagihan5=$tagihan5+$row5["tagihan"];		 
					 
             ;$j5++;}
			 
          $i5++;}
		 	
		
		}
		
		
		///Batas Pembelian Obat



// Pembelian Obat Racikan
		$rec4 = getFromTable ("select count(id) from rs00008 ".
				     "where trans_type = 'RCK' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
		
		
		if ($rec4 > 0){
		$sqlf= "select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans,  
		obat, qty ||' '|| c.tdesc as qty, sum(tagihan) as tagihan, pembayaran, trans_group, d.tdesc as kategori, a.trans_form 
		from rs00008 a, rs00015 b, rs00001 c, rs00001 d 
		where to_number(a.item_id,'999999999999') = b.id  
		and b.satuan_id = c.tc and a.trans_type = 'RCK' 
		and c.tt = 'SAT' 
		and b.kategori_id = d.tc and d.tt = 'GOB' 
		and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'
		group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form ";
		@$r6 = pg_query($con,$sqlf);
		@$n6 = pg_num_rows($r6);
		
		$max_row6= 200 ;
		$mulai6 = $HTTP_GET_VARS["rec"] ;
		if (!$mulai6){$mulai6=1;}
		
		?>
		<tr>
		<td bgcolor="#8ADFD3" align="center">&nbsp;</td>
		<td bgcolor="#8ADFD3" align="left"><u><font size="2"  face="Arial">RINCIAN  OBAT RACIKAN</u></td>
		<td bgcolor="#8ADFD3" align="center">&nbsp;</td>
		<td bgcolor="#8ADFD3" align="right">&nbsp;</td>
	</tr>
		<?
		
		$row6=0;
		$tagihan6=0;
		$i6= 1 ;
		$j6= 1 ;
		$last_id6=1;
		while (@$row6 = pg_fetch_array($r6)){
			  if (($j6<=$max_row6) AND ($i6 >= $mulai6)){
			  $no6=$i6;
		?>
		<tr>
			<td class="TBL_BODY" align="center"><b><font size="2"  face="Arial"><?=$row6["tanggal_trans"] ?></b></td>
			<td class="TBL_BODY" align="left"><font size="2"  face="Arial"><?=$row6["obat"] ?></td>
			<td class="TBL_BODY" align="left"><font size="2"  face="Arial"><?=$row6["qty"] ?></td>
			<td class="TBL_BODY" align="right"><b><font size="2"  face="Arial"><?=number_format($row6["tagihan"],2,",",".") ?></b></td>
		</tr>
		<?
		$tagihan6=$tagihan6+$row6["tagihan"];		 
					 
             ;$j6++;}
			 
          $i6++;}
		
		
		}
		
//Batas Pembelian Obat Racikan

?>
<tr>

</tr>
	<tr>
	  	<td class="TBL_HEAD" align="right" colspan="3"><b><font size="2"  face="Arial">TOTAL: <?=number_format($tagihan6 + $tagihan5,2,",",".") ?></font></b></td>
	</tr>
</table>




<table border="0" align="right" width="50%">
  
    <td align="center" class="TITLE_SIM3"></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="right" class="TITLE_SIM3"><font size="2"  face="Arial"><? echo $_SESSION["nama_usr"];?></font></td>
</tr>
</table>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</html>