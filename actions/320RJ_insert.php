<?php
session_start();
require_once("../lib/dbconn.php");

// cek nilai post is_racikan, jika nilainya = 1, nilai trans_type = 'RCK'
if( (int)$_POST["is_racikan"] == 1){
	$transType = 'RCK';
}else if((int)$_POST["is_racikan"] == 0){
    $transType = 'OB1';
}
else if((int)$_POST["is_racikan"] == 2){
    $transType = 'BHP';
}
// Ambil nilai jasa sesuai dengan kategori obatnya


$noReg		= $_GET["rg"];
$obatId		= $_POST["obat_id"];
$kategoriId	= $_POST["kategori_id"];
$qty		= floatval($_POST["qty"]);
$harga		= floatval($_POST["harga"]);
//$jasa		= floatval($_POST['jasa']);
$qty_apo = floatval($_POST["qty_awal"])-floatval($_POST["qty"]);

//jasa tuslah
$isRacikan	= (int)$_POST["is_racikan"];
if( $isRacikan == 0){
	$jasa		= floatval($_POST['jasa']);
} else if( $isRacikan == 1 && $kategoriId == '028'){
	$jasa		= (floatval($_POST['jasa'])-1000)+1500;
} else if( $isRacikan == 1 && $qty >= 1 && $qty <= 20){
	$jasa		= (floatval($_POST['jasa'])-1000)+3000;
} else if( $isRacikan == 1 && $qty >= 21 && $qty <= 30){
	$jasa		= (floatval($_POST['jasa'])-1000)+3500;
} else if( $isRacikan == 1 && $qty >= 31 && $qty <= 60){
	$jasa		= (floatval($_POST['jasa'])-1000)+4500;
} else if( $isRacikan == 1 && $qty >= 60){
	$jasa		= (floatval($_POST['jasa'])-1000)+5500;
} else if( $isRacikan == 2){
	$jasa		= floatval($_POST['jasa']);
} else {
	$jasa		= floatval($_POST['jasa']);
}

$tagihan	= floatval($_POST['jumlah']);
$penjamin	= floatval($_POST["penjamin"]);

    $sqlRi = pg_query($con, "SELECT rawat_inap FROM rs00006 WHERE id = '".$noReg."'");
    $rowRi = pg_fetch_array($sqlRi);
    $Ri    = $rowRi['rawat_inap'];
    
    $Tr = getFromTable("select nmr_transaksi from rs00008 where no_reg = '".$noReg."' and trans_type='OBM'");
    $Trn = getFromTable("select jenis_transaksi from rs00008 where no_reg = '".$noReg."' and trans_type='OBM'");
//var_dump($Trn);

if(!empty($_GET["delreturn"])){
$sqlStok = pg_query($con, "SELECT qty_ri FROM rs00016a WHERE obat_id = ".$_POST["obat_id"]);
    $rowStok = pg_fetch_array($sqlStok);
    $stok    = $rowStok['qty_ri'];
    pg_query($con, "UPDATE rs00016a SET qty_ri = ".($stok-$_POST["qty"])."  WHERE obat_id = ".$_POST["obat_id"]);
    pg_query($con, "DELETE FROM rs00008_return WHERE id = ".$_POST["rs00008_return_id"] );
	// delete juga di buku_besar
    pg_query($con, "DELETE FROM buku_besar WHERE kode_transaksi = '" . $_POST["rs00008_return_id"]."' and trans_form='rs00008_return'");
	 $obat=getFromTable("select obat from rs00015 where id = '".$_POST["obat_id"]."'");
	$uid=$_SESSION["uid"] ;
	$nama_usr=$_SESSION["nama_usr"] ;
		//========= Trendy Dwi A G 08/01/2014 hystory user
	    pg_query("insert into history_user " .
		            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
		            "values".
		            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Retur Data Obat', ".
		            "'Apotik -> Pelayanan Farmasi -> Apotik Klinik', ".
					"'Data Retur Obat Pasien dengan No.Reg $noReg telah Dihapus pada Obat $obat ', ".
		            "'$uid','$nama_usr')");
		//=========
}
if(!empty($_GET["del"])){
    $sqlStok = pg_query($con, "SELECT qty_ri FROM rs00016a WHERE obat_id = ".$_POST["obat_id"]);
    $rowStok = pg_fetch_array($sqlStok);
    $stok    = $rowStok['qty_ri'];
    pg_query($con, "DELETE FROM rs00008 WHERE id = ".$_POST["rs00008_id"] );
    pg_query($con, "UPDATE rs00016a SET qty_ri = ".($stok+$_POST["qty"])."  WHERE obat_id = ".$_POST["obat_id"]);
    $obat=getFromTable("select obat from rs00015 where id = '".$_POST["obat_id"]."'");
	$uid=$_SESSION["uid"] ;
	$nama_usr=$_SESSION["nama_usr"] ;
		//========= Trendy Dwi A G 08/01/2014 hystory user
	    pg_query("insert into history_user " .
		            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
		            "values".
		            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Delete Data Obat', ".
		            "'Apotik -> Pelayanan Farmasi -> Apotik Klinik', ".
					"'Data Obat Pasien dengan No.Reg $noReg telah Dihapus pada Obat $obat ', ".
		            "'$uid','$nama_usr')");
		//=========
 
	
    // ---- update juga rs00005 untuk kasir
    updateTagihanUntukKasir($con,$_GET["rg"]);
	// delete juga di buku_besar
    pg_query($con, "DELETE FROM buku_besar WHERE kode_transaksi = '" . $_POST["rs00008_id"]."' and trans_form='rs00008'");
	
}

if(!empty($_POST["obat_id"]) && ($_GET["del"] == false)){
// ---------------------- Start Insert/Update Pemakaian Obat  ------------------
   // select stok 
   $sqlStok = pg_query($con, "SELECT qty_ri FROM rs00016a WHERE obat_id = ".$_POST["obat_id"]);
   $rowStok = pg_fetch_array($sqlStok);
   $stok    = $rowStok['qty_ri'];
   
   $mr=getFromTable("select mr_no from rs00006 where id = '".$noReg."'");
		$nama=getFromTable("select nama from rs00002 where mr_no = '".$mr."'");
		$bangsal=getFromTable("select bangsal_id from rs00010 where no_reg = '".$noReg."'");
		
		if ($bangsal!="" || $bangsal!=NULL)
		{
		$status = "RI";
		}else{
		$status = "RJ";
		}
   
   if($_POST["rs00008_id"] > 0){
       
        if($_POST['is_return'] == 1){
             pg_query($con, "INSERT INTO rs00008_return (id, trans_type, trans_form, trans_group, tanggal_trans, 
                    tanggal_entry, waktu_entry, no_reg, item_id, referensi,qty_awal, qty_return, harga, tagihan, dibayar_penjamin,user_id) 
                    values(
                    nextval('rs00008_return_seq'), '".$transType."', '320RJ_SWD', 0, CURRENT_DATE, CURRENT_DATE, CURRENT_TIME, 
                    '".$noReg."', '".$obatId."', '".$jasa."',
                    ".$_POST["qty_awal"].", ".$qty.", ".$harga.", ".(int)$tagihan.", ".$penjamin.",'".$_SESSION["uid"]."')" );
             
			$qty		= floatval($_POST["qty_awal"]);
			$penjamin	= floatval($_POST["penjamin_awal"]);
			$harga		= floatval($_POST["harga_awal"]);
			$tagihan	= floatval($_POST["jumlah_awal"]);
			pg_query($con, "UPDATE rs00016a SET qty_ri = qty_ri + " . $_POST['qty'] . "  WHERE obat_id = " . $_POST["obat_id"]);
	   }else{
	   	     pg_query($con, "UPDATE rs00016a SET qty_ri = qty_ri + " . $qty_apo . "  WHERE obat_id = " . $_POST["obat_id"]);
			pg_query($con,"update buku_besar SET qty= '".$qty."' where kode_transaksi = ".$_POST["rs00008_id"]);
			 $obat=getFromTable("select obat from rs00015 where id = '".$obatId."'");
	$uid=$_SESSION["uid"] ;
	$nama_usr=$_SESSION["nama_usr"] ;
		//========= Trendy Dwi A G 08/01/2014 hystory user
	    pg_query("insert into history_user " .
		            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
		            "values".
		            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Edit Data Obat', ".
		            "'Apotik -> Pelayanan Farmasi -> Apotik Klinik', ".
					"'Data Obat Pasien dengan No.Reg $noReg telah Diubah pada Obat $obat ', ".
		            "'$uid','$nama_usr')");
		//=========
	  
	   }
        pg_query($con,"update buku_besar SET qty= '".$qty."' where kode_transaksi = ".$_POST["rs00008_id"]);
	  
        pg_query($con, "UPDATE  rs00008 SET trans_type = '".$transType."', waktu_entry = CURRENT_TIME, item_id = '".$_POST["obat_id"]."', referensi = '".$_POST["jasa"]."', 
                        qty = ".$qty.", harga = ".$harga.", tagihan = ".(int)$tagihan.", 
                            dibayar_penjamin = ".$penjamin." WHERE id = ".$_POST["rs00008_id"] );
        //pg_query($con, "UPDATE rs00016a SET qty_ri = ".($stok+$_POST["qty"])."  WHERE obat_id = ".$_POST["obat_id"]);
        
        // ---- update juga rs00005 untuk kasir
        updateTagihanUntukKasir($con,$_GET["rg"]);
		
		// ---- insert ke buku besar
			$rs00008_return_seq = "currval('rs00008_return_seq')";
			//$netto=getFormTable("select harga from rs00016 where obat_id='$obatId'");
			$obatObjects = pg_query($con, "select harga from rs00016 where obat_id='$obatId'");
			$netto = pg_fetch_object($obatObjects);
			$harga2=getFromTable("select harga from buku_besar where item_id = '".$obatId."' and trans_form='c_po_item_terima' order by id Desc");
	$harga2=getFromTable("select harga from buku_besar where item_id = '".$obatId."' and trans_form='c_po_item_terima' order by id Desc");
		if ($harga2!='') {
				pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,ket,harga) 
									values (CURRENT_DATE,CURRENT_TIME,'$obatId','ORT','020',$qty,$rs00008_return_seq,'rs00008_return','Retur $status / $nama','".$harga2."')");
	 $obat=getFromTable("select obat from rs00015 where id = '".$obatId."'");
	$uid=$_SESSION["uid"] ;
	$nama_usr=$_SESSION["nama_usr"] ;
		//========= Trendy Dwi A G 08/01/2014 hystory user
	    pg_query("insert into history_user " .
		            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
		            "values".
		            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Retur Data Obat', ".
		            "'Apotik -> Pelayanan Farmasi -> Apotik Klinik', ".
					"'Data Retur Obat Pasien dengan No.Reg $noReg telah Ditambahkan pada Obat $obat ', ".
		            "'$uid','$nama_usr')");
		//=========
	}else{
		$harga3=getFromTable("select harga_beli from rs000016 where obat_id = '".$obatId."'");
		pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,ket,harga) 
									values (CURRENT_DATE,CURRENT_TIME,'$obatId','ORT','020',$qty,$rs00008_return_seq,'rs00008_return','Retur $status / $nama','".$harga3."')");
}
   }else{
		$cek_reg=getFromTable("select count(no_reg) from rs00008 where trans_type in ('OB1','OB2','BHP','RCK') and no_reg ='".$_GET["rg"]."' ");
		if ($cek_reg =='0'){
		$cek_status=getFromTable(" SELECT rawat_inap FROM rs00008 where no_reg '".$_GET["rg"]."' ");
		if ($status=='RI'){
		$nominal=getFromTable("select comment from rs00001 where tt='ADA' AND tc='002'");
		pg_query($con, "INSERT INTO rs00008 (id, trans_type, trans_form, trans_group, tanggal_trans, 
                    tanggal_entry, waktu_entry, no_reg, item_id, referensi,qty, harga, tagihan, dibayar_penjamin,user_id,rawat_inap,nmr_transaksi,jenis_transaksi) 
                    values(
                    nextval('rs00008_seq'), 'ADA', '320RJ_SWD', nextval('rs00008_seq_group'), CURRENT_DATE, CURRENT_DATE, CURRENT_TIME, 
                    '".$noReg."', '002', '0',
                    1, '".$nominal."', '".$nominal."', '0','".$_SESSION["uid"]."','".$Ri."','".$Tr."','".$Trn."')" );
   
		}else{
		$nominal=getFromTable("select comment from rs00001 where tt='ADA' AND tc='001'");
		
		pg_query($con, "INSERT INTO rs00008 (id, trans_type, trans_form, trans_group, tanggal_trans, 
                    tanggal_entry, waktu_entry, no_reg, item_id, referensi,qty, harga, tagihan, dibayar_penjamin,user_id,rawat_inap,nmr_transaksi,jenis_transaksi) 
                    values(
                    nextval('rs00008_seq'), 'ADA', '320RJ_SWD', nextval('rs00008_seq_group'), CURRENT_DATE, CURRENT_DATE, CURRENT_TIME, 
                    '".$noReg."', '001', '0',
                    1, ".$nominal.", ".$nominal.", '0','".$_SESSION["uid"]."','".$Ri."','".$Tr."','".$Trn."')" );
   
		}
		}
		
		$mr=getFromTable("SELECT mr_no from rs00006 where id='".$_GET["rg"]."'");
		$kunjungan=getFromTable("SELECT count(mr_no) from rs00006 where mr_no='".$mr."'");
		
		if ($kunjungan>20){
		$diskon=($tagihan*(5/100));
		pg_query($con, "INSERT INTO rs00008 (id, trans_type, trans_form, trans_group, tanggal_trans, 
                    tanggal_entry, waktu_entry, no_reg, item_id, referensi,qty, harga, tagihan, dibayar_penjamin,user_id,rawat_inap,nmr_transaksi,jenis_transaksi,diskon) 
                    values(
                    nextval('rs00008_seq'), '".$transType."', '320RJ_SWD', nextval('rs00008_seq_group'), CURRENT_DATE, CURRENT_DATE, CURRENT_TIME, 
                    '".$noReg."', '".$obatId."', '1000',
                    ".$qty.", ".$harga.", ".(int)$tagihan.", ".$penjamin.",'".$_SESSION["uid"]."','".$Ri."','".$Tr."','".$Trn."','".$diskon."')" );
		}else{
		pg_query($con, "INSERT INTO rs00008 (id, trans_type, trans_form, trans_group, tanggal_trans, 
                    tanggal_entry, waktu_entry, no_reg, item_id, referensi,qty, harga, tagihan, dibayar_penjamin,user_id,rawat_inap,nmr_transaksi,jenis_transaksi) 
                    values(
                    nextval('rs00008_seq'), '".$transType."', '320RJ_SWD', nextval('rs00008_seq_group'), CURRENT_DATE, CURRENT_DATE, CURRENT_TIME, 
                    '".$noReg."', '".$obatId."', '1000',
                    ".$qty.", ".$harga.", ".(int)$tagihan.", ".$penjamin.",'".$_SESSION["uid"]."','".$Ri."','".$Tr."','".$Trn."')" );
		
		}
        pg_query($con, "UPDATE rs00016a SET qty_ri = ".($stok-$qty)."  WHERE obat_id = ".$obatId);
        $rs00008_seq = "currval('rs00008_seq')";
		$harga2=getFromTable("select harga from buku_besar where item_id = '".$obatId."' and trans_form='c_po_item_terima' order by id Desc");
		
		 $obat=getFromTable("select obat from rs00015 where id = '".$obatId."'");
		$uid=$_SESSION["uid"] ;
		$nama_usr=$_SESSION["nama_usr"] ;
		//========= Trendy Dwi A G 08/01/2014 hystory user
	    pg_query("insert into history_user " .
		            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
		            "values".
		            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Tambah Data Obat', ".
		            "'Apotik -> Pelayanan Farmasi -> Apotik Klinik', ".
					"'Data Obat Pasien dengan No.Reg $noReg telah Ditambahkan dengan Nama Obat $obat ', ".
		            "'$uid','$nama_usr')");
		//=========
		
		if ($harga2!='') {
		pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,ket,harga) 
									values (CURRENT_DATE,CURRENT_TIME,'$obatId','OBK','020',$qty,$rs00008_seq,'rs00008','$status / $nama','".$harga2."')");
        }else{
		$harga3=getFromTable("select harga_beli from rs000016 where obat_id = '".$obatId."'");
		pg_query($con,"INSERT INTO buku_besar (tanggal_entry,waktu_entry,item_id,trans_type,id_depo,qty,kode_transaksi,trans_form,ket,harga) 
									values (CURRENT_DATE,CURRENT_TIME,'$obatId','OBK','020',$qty,$rs00008_seq,'rs00008','$status / $nama','".$harga3."')");
		}/// ---- update juga rs00005 untuk kasir
        updateTagihanUntukKasir($con,$noReg);
   }
// ---------------------- End Insert/Update Pemakaian Obat  --------------------

}

$rowsPemakaianObat      = pg_query($con, "SELECT id, tanggal_entry,nmr_transaksi, waktu_entry, item_id, qty, tagihan::integer, referensi::integer, dibayar_penjamin::integer, trans_type, diskon  
                             FROM rs00008 
                             WHERE trans_type = 'OB1' AND rs00008.no_reg = '".$_GET["rg"]."' order by id");
$rowsPemakaianRacikan   = pg_query($con, "SELECT id, tanggal_entry,nmr_transaksi, waktu_entry, item_id, qty, tagihan::integer, referensi::integer, dibayar_penjamin::integer, trans_type, diskon  
                             FROM rs00008 
                             WHERE trans_type = 'RCK' AND rs00008.no_reg = '".$_GET["rg"]."' order by id");
$rowsPemakaianBHP      = pg_query($con, "SELECT id, tanggal_entry,nmr_transaksi, waktu_entry, item_id, qty, tagihan::integer,COALESCE(0, tagihan::integer) AS referensi, dibayar_penjamin::integer, trans_type, diskon  
                             FROM rs00008 
                             WHERE trans_type = 'BHP' AND rs00008.no_reg = '".$_GET["rg"]."' order by id");
$rowsAdministrasi      = pg_query($con, "SELECT id, tanggal_entry,nmr_transaksi, waktu_entry, item_id, qty, tagihan::integer,COALESCE(0, tagihan::integer) AS referensi, dibayar_penjamin::integer, trans_type, diskon  
                             FROM rs00008 
                             WHERE trans_type = 'ADA' AND rs00008.no_reg = '".$_GET["rg"]."' order by id");

?>
<!-- ---------------------- Start Buat tabel hasil input obat -------------------->
<table width='100%'>
	<tr>
		<td class="TBL_HEAD" width='3%'><center>No.</center></td>
		<td class="TBL_HEAD" width='12%' ><center>Tanggal</center></td>
		<td class="TBL_HEAD"><center>Obat</center></td>
		<td class="TBL_HEAD" width='8%'><center>Jumlah</center></td>
		<td class="TBL_HEAD" width='8%'><center>Tagihan</center></td>
		<td class="TBL_HEAD" width='8%'><center>Pejamin</center></td>
		<td class="TBL_HEAD" width='8%'><center>Selisih</center></td>
		<td class="TBL_HEAD" width='8%'><center>Diskon</center></td>
		<td class="TBL_HEAD" width='5%'><center>Cetak <input type="checkbox" id="check_all_obat"></center></center></td>
		<td class="TBL_HEAD" width='18%'>&nbsp;</td>
	</tr>	
        <tr>
		<td class="TBL_BODY" colspan="10"><span style="font-weight: bold;">Administrasi</span></td>
	</tr>
<?php
        $iData          = 0;
        $iObat          = 0;
        $total          = 0;
        $totalPenjamin  = 0;
        $totalSelisih   = 0;
        $totalDiskon   = 0;
        while($row=pg_fetch_array($rowsAdministrasi)){
            $iData++;
            $iObat++;
            $total          = $total + $row["tagihan"];
            $totalPenjamin  = $totalPenjamin + $row["dibayar_penjamin"] ;
            $totalSelisih   = $totalSelisih + ($row["tagihan"]-$row["dibayar_penjamin"]);
            $totalDiskon   	= $totalDiskon + $row["diskon"];
            $sqlObat 		= pg_query($con, "SELECT tdesc from rs00001 where tt='ADA' AND tc = '". $row["item_id"]."'" );
            $obat 			= pg_fetch_array($sqlObat);
            $arrWaktuEntry 	= explode('.', $row["waktu_entry"]);
            $arrJamEntry 	= explode(':', $arrWaktuEntry[0]);
//start pemisah tanggal
if ($oldTgl != $row['tanggal_entry']) {
	echo '<tr>';
		echo '<td bgcolor="#D3D3D3" colspan="10"><b><marquee behavior="alternate"><font size=2>';
			echo 'Transaksi Farmasi Tanggal : '.tanggal($row["tanggal_entry"]);
		echo '</font></marquee></b></td>';
	echo '</tr>';
}

if ($oldReg != $row['nmr_transaksi']) {
	echo '<tr>';
		echo '<td bgcolor="#D3D3D3" colspan="10"><b><font size=2> <CENTER>';
			echo 'No Resep : '.$row["nmr_transaksi"];
		echo ' </CENTER></font></b></td>';
	echo '</tr>';
}

?>
	<tr>
		<td class="TBL_BODY" align="right"><?=$iObat?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
		<td class="TBL_BODY" align="left">
                    <input type="hidden" id="obat_id_<?php echo $row["id"]?>" value="<?=$obat["id"]?>" />
                    <input type="hidden" id="harga_<?php echo $row["id"]?>" value="<?=$harga?>" />
		    <input type="hidden" id="hargareturn_<?php echo $row["id"]?>" value="<?=$obat["harga"]?>" />
                    <input type="hidden" id="jasa_<?php echo $row["id"]?>" value="<?=$row["referensi"]?>" />
		    <input type="hidden" id="jasareturn_<?php echo $row["id"]?>" value="<?=$jasareturn?>" />
                    <input type="hidden" id="tagihan_<?php echo $row["id"]?>" value="<?=$row["tagihan"]?>" />
                    <input type="hidden" id="tipe_<?php echo $row["id"]?>" value="<?=$row["trans_type"]?>" />
                    <span id="obat_nama_<?php echo $row["id"]?>"><?=$obat["tdesc"]?></span>
                </td>
		<td class="TBL_BODY" align="right">
                    <span id="qty_<?php echo $row["id"]?>"><?=$row["qty"]?></span>
                    <span id="satuan_<?php echo $row["id"]?>"><?=$obat["satuan"]?></span>
                </td>
		<td class="TBL_BODY" align="right"><?=number_format($row["tagihan"],'0','','.')?></td>
		<td class="TBL_BODY" align="right"><span id="penjamin_<?php echo $row["id"]?>"><?=number_format($row["dibayar_penjamin"],'0','','.')?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $row["id"]?>"><?=number_format($row["tagihan"]-$row["dibayar_penjamin"],'0','','.')?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $row["id"]?>"><?=number_format($row["diskon"],'0','','.')?></span></td>
                <td class="TBL_BODY" align="center"><input type="checkbox" class="check_obat" name="cetak_<?php echo $iData ?>" id="cetak_<?php echo $iData ?>" value="<?php echo $row["id"]?>"></td>
                <td class="TBL_BODY" align="center">
                    <a href="#" onClick="return_data_obat('<?php echo $row["id"]?>')">Return</a> &nbsp; | &nbsp;
                    <a href="#" onClick="edit_data_obat('<?php echo $row["id"]?>')">edit</a> &nbsp; | &nbsp;
                <a href="#" onClick="delete_data_obat('<?php echo $row["id"]?>')">delete</a></td>
	</tr>
<?php
//end pemisah tanggal
$oldReg	= $row['nmr_transaksi'];
$oldTgl	= $row['tanggal_entry'];
        }
        
?>   
	<tr>
		<td class="TBL_BODY" colspan="10"><span style="font-weight: bold;">Obat Resep</span></td>
	</tr>
<?php
        
        while($row=pg_fetch_array($rowsPemakaianObat)){
            $iData++;
            $iObat++;
            $total          = $total + $row["tagihan"];
            $totalPenjamin  = $totalPenjamin + $row["dibayar_penjamin"];
            $totalSelisih   = $totalSelisih + ($row["tagihan"]-$row["dibayar_penjamin"]);
            $totalDiskon   = $totalDiskon + $row["diskon"];
			
            $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga::integer,rs00016.harga_car_drs,
rs00016.harga_car_rsrj,rs00016.harga_car_rsri,rs00016.harga_inhealth_drs,rs00016.harga_inhealth_rs,
rs00016.harga_jam_ri,rs00016.harga_jam_rj,rs00016.harga_kry_kelinti,rs00016.harga_kry_kelbesar,
rs00016.harga_kry_kelgratisri,rs00016.harga_kry_kelrespoli,rs00016.harga_kry_kel,
rs00016.harga_ranap  ,
  rs00016.harga_khusus_i  ,
  rs00016.harga_khusus_ii  ,
  rs00016.harga_pamper  ,
  rs00016.harga_kel_kry  ,
  rs00016.harga_obat_rajal  ,
  rs00016.harga_obat_tjp  ,
  rs00016.harga_bpjs  ,
 rs00016.harga_aswas  ,
 rs00016.harga_vaksin  ,
  rs00016.harga_alkes_bhp  ,
  rs00016.harga_nempil_rajal  ,
  rs00016.harga_langganan  ,
  rs00016.harga_hv  ,
  rs00016.harga_klinik  ,
rs00016.harga_kry_kelgratisrj,rs00016.harga_umum_ri,rs00016.harga_umum_rj::integer,
rs00016.harga_umum_ikutrekening,rs00016.harga_gratis_rj,rs00016.harga_gratis_ri,
rs00016.harga_pen_bebas,rs00016.harga_nempil,rs00016.harga_nempil_apt 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] ." order by rs00015.id ASC" );
            $obat = pg_fetch_array($sqlObat);
            $arrWaktuEntry = explode('.', $row["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
$margin = getFromTable("select item_id from rs00008 where no_reg='$noReg' and trans_type='OBM'");

if($margin=='001'){$harga = (int)$obat["harga_car_drs"];}
		else if($margin=='002'){
				   $harga = (int)$obat["harga_car_rsrj"];}
		else if($margin=='003'){
				   $harga = (int)$obat["harga_car_rsri"];}
		else if($margin=='004'){
				   $harga = (int)$obat["harga_inhealth_drs"];}
		else if($margin=='005'){
				   $harga = (int)$obat["harga_inhealth_rs"];}
		else if($margin=='006'){
				   $harga = (int)$obat["harga_jam_ri"];}
		else if($margin=='007'){
			           $harga = (int)$obat["harga_jam_rj"];}
		else if($margin=='008'){
				   $harga = (int)$obat["harga_kry_kelinti"];}
		else if($margin=='009'){
				   $harga = (int)$obat["harga_kry_kelbesar"];}
		else if($margin=='010'){
				   $harga = (int)$obat["harga_kry_kelgratisri"];}
		else if($margin=='011'){
				   $harga = (int)$obat["harga_kry_kelrespoli"];}
		else if($margin=='012'){
				   $harga = (int)$obat["harga_kry_kel"];}
		else if($margin=='013'){
				   $harga = (int)$obat["harga_kry_kelgratisrj"];}
		else if($margin=='014'){
				   $harga = (int)$obat["harga_umum_ri"];}
		else if($margin=='015'){
				   $harga = (int)$obat["harga_umum_rj"];}
		else if($margin=='016'){
				   $harga = (int)$obat["harga_umum_ikutrekening"];}
		else if($margin=='017'){
				   $harga = (int)$obat["harga_gratis_rj"];}
		else if($margin=='018'){
				   $harga = (int)$obat["harga_gratis_ri"];}
		else if($margin=='019'){
				   $harga = (int)$obat["harga_pen_bebas"];}
		else if($margin=='020'){
				   $harga = (int)$obat["harga_nempil"];}
		else if($margin=='021'){
				   $harga = (int)$obat["harga_nempil_apt"];}
		else if($margin=='022'){
				   $harga = (int)$obatR["harga_ranap"];}
		else if($margin=='023'){
				   $harga = (int)$obatR["harga_khusus_i"];}
		else if($margin=='024'){
				   $harga = (int)$obatR["harga_khusus_ii"];}
		else if($margin=='025'){
				   $harga = (int)$obatR["harga_pamper"];}
		else if($margin=='026'){
				   $harga = (int)$obatR["harga_kel_kry"];}
		else if($margin=='027'){
				   $harga = (int)$obatR["harga_obat_rajal"];}
		else if($margin=='028'){
				   $harga = (int)$obatR["harga_obat_tjp"];}
		else if($margin=='029'){
				   $harga = (int)$obatR["harga_bpjs"];}
		else if($margin=='030'){
				   $harga = (int)$obatR["harga_aswas"];}
		else if($margin=='031'){
				   $harga = (int)$obatR["harga_vaksin"];}
		else if($margin=='032'){
				   $harga = (int)$obatR["harga_alkes_bhp"];}
		else if($margin=='033'){
				   $harga = (int)$obatR["harga_nempil_rajal"];}
		else if($margin=='034'){
				   $harga = (int)$obatR["harga_langganan"];}
		else if($margin=='035'){
				   $harga = (int)$obatR["harga_klinik"];}
		else{
				   $harga = (int)$obat["harga_umum_rj"];}
		$jasareturn=0;
//start pemisah tanggal
if ($oldTgl != $row['tanggal_entry']) {
	echo '<tr>';
		echo '<td bgcolor="#D3D3D3" colspan="10"><b><marquee behavior="alternate"><font size=2>';
			echo 'Transaksi Farmasi Tanggal : '.tanggal($row["tanggal_entry"]);
		echo '</font></marquee></b></td>';
	echo '</tr>';
}

if ($oldReg != $row['nmr_transaksi']) {
	echo '<tr>';
		echo '<td bgcolor="#D3D3D3" colspan="10"><b><font size=2> <CENTER>';
			echo 'No Resep : '.$row["nmr_transaksi"];
		echo ' </CENTER></font></b></td>';
	echo '</tr>';
}

?>
	<tr>
		<td class="TBL_BODY" align="right"><?=$iObat?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
		<td class="TBL_BODY" align="left">
                    <input type="hidden" id="obat_id_<?php echo $row["id"]?>" value="<?=$obat["id"]?>" />
                    <input type="hidden" id="harga_<?php echo $row["id"]?>" value="<?=$harga?>" />
		    <input type="hidden" id="hargareturn_<?php echo $row["id"]?>" value="<?=$obat["harga"]?>" />
                    <input type="hidden" id="jasa_<?php echo $row["id"]?>" value="<?=$row["referensi"]?>" />
		    <input type="hidden" id="jasareturn_<?php echo $row["id"]?>" value="<?=$jasareturn?>" />
                    <input type="hidden" id="tagihan_<?php echo $row["id"]?>" value="<?=$row["tagihan"]?>" />
                    <input type="hidden" id="tipe_<?php echo $row["id"]?>" value="<?=$row["trans_type"]?>" />
                    <span id="obat_nama_<?php echo $row["id"]?>"><?=$obat["obat"]?></span>
                </td>
		<td class="TBL_BODY" align="right">
                    <span id="qty_<?php echo $row["id"]?>"><?=$row["qty"]?></span>
                    <span id="satuan_<?php echo $row["id"]?>"><?=$obat["satuan"]?></span>
                </td>
		<td class="TBL_BODY" align="right"><?=number_format($row["tagihan"],'0','','.')?></td>
		<td class="TBL_BODY" align="right"><span id="penjamin_<?php echo $row["id"]?>"><?=number_format($row["dibayar_penjamin"],'0','','.')?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $row["id"]?>"><?=number_format($row["tagihan"]-$row["dibayar_penjamin"],'0','','.')?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $row["id"]?>"><?=number_format($row["diskon"],'0','','.')?></span></td>
                <td class="TBL_BODY" align="center"><input type="checkbox" class="check_obat" name="cetak_<?php echo $iData ?>" id="cetak_<?php echo $iData ?>" value="<?php echo $row["id"]?>"></td>
                <td class="TBL_BODY" align="center">
                    <a href="#" onClick="return_data_obat('<?php echo $row["id"]?>')">Return</a> &nbsp; | &nbsp;
                    <a href="#" onClick="edit_data_obat('<?php echo $row["id"]?>')">edit</a> &nbsp; | &nbsp;
                <a href="#" onClick="delete_data_obat('<?php echo $row["id"]?>')">delete</a></td>
	</tr>
<?php
//end pemisah tanggal
$oldReg	= $row['nmr_transaksi'];
$oldTgl	= $row['tanggal_entry'];
        }
        
?>
    <tr>
		<td class="TBL_BODY" colspan="10"><span style="font-weight: bold;">Obat Racikan</span></td>
	</tr>
    <?php
        $iRacikan         = 0;
        while($rowRacikan = pg_fetch_array($rowsPemakaianRacikan)){
            $iRacikan++;
            $iData++;
            $total          = $total + $rowRacikan["tagihan"];
            $totalPenjamin  = $totalPenjamin + $rowRacikan["dibayar_penjamin"];
            $totalSelisih   = $totalSelisih + ($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]);
            $totalDiskon   = $totalDiskon + $row["diskon"];
			
            $sqlObatR = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga::integer,rs00016.harga_car_drs,
rs00016.harga_car_rsrj,rs00016.harga_car_rsri,rs00016.harga_inhealth_drs,rs00016.harga_inhealth_rs,
rs00016.harga_jam_ri,rs00016.harga_jam_rj,rs00016.harga_kry_kelinti,rs00016.harga_kry_kelbesar,
rs00016.harga_kry_kelgratisri,rs00016.harga_kry_kelrespoli,rs00016.harga_kry_kel,
rs00016.harga_kry_kelgratisrj,rs00016.harga_umum_ri,rs00016.harga_umum_rj::integer,
rs00016.harga_umum_ikutrekening,rs00016.harga_gratis_rj,rs00016.harga_gratis_ri,
rs00016.harga_ranap  ,
  rs00016.harga_khusus_i  ,
  rs00016.harga_khusus_ii  ,
  rs00016.harga_pamper  ,
  rs00016.harga_kel_kry  ,
  rs00016.harga_obat_rajal  ,
  rs00016.harga_obat_tjp  ,
  rs00016.harga_bpjs  ,
 rs00016.harga_aswas  ,
 rs00016.harga_vaksin  ,
  rs00016.harga_alkes_bhp  ,
  rs00016.harga_nempil_rajal  ,
  rs00016.harga_langganan  ,
  rs00016.harga_hv  ,
  rs00016.harga_klinik  ,
rs00016.harga_pen_bebas,rs00016.harga_nempil,rs00016.harga_nempil_apt 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $rowRacikan["item_id"] ."order by rs00015.id ASC");
            $obatR = pg_fetch_array($sqlObatR);
            $arrWaktuEntry2 = explode('.', $rowRacikan["waktu_entry"]);
            $arrJamEntry2 = explode(':', $arrWaktuEntry2[0]);
$margin = getFromTable("select item_id from rs00008 where no_reg='$noReg' and trans_type='OBM'");

if($margin=='001'){$harga = (int)$obatR["harga_car_drs"];}
		else if($margin=='002'){
				   $hargaR = (int)$obatR["harga_car_rsrj"];}
		else if($margin=='003'){
				   $hargaR = (int)$obatR["harga_car_rsri"];}
		else if($margin=='004'){
				   $hargaR = (int)$obatR["harga_hv"];}
		else if($margin=='005'){
				   $hargaR = (int)$obatR["harga_inhealth_rs"];}
		else if($margin=='006'){
				   $hargaR = (int)$obatR["harga_jam_ri"];}
		else if($margin=='007'){
			           $hargaR = (int)$obatR["harga_jam_rj"];}
		else if($margin=='008'){
				   $hargaR = (int)$obatR["harga_kry_kelinti"];}
		else if($margin=='009'){
				   $hargaR = (int)$obatR["harga_kry_kelbesar"];}
		else if($margin=='010'){
				   $hargaR = (int)$obatR["harga_kry_kelgratisri"];}
		else if($margin=='011'){
				   $hargaR = (int)$obatR["harga_kry_kelrespoli"];}
		else if($margin=='012'){
				   $hargaR = (int)$obatR["harga_kry_kel"];}
		else if($margin=='013'){
				   $hargaR = (int)$obatR["harga_kry_kelgratisrj"];}
		else if($margin=='014'){
				   $hargaR = (int)$obatR["harga_umum_ri"];}
		else if($margin=='015'){
				   $hargaR = (int)$obatR["harga_umum_rj"];}
		else if($margin=='016'){
				   $hargaR = (int)$obatR["harga_umum_ikutrekening"];}
		else if($margin=='017'){
				   $hargaR = (int)$obatR["harga_gratis_rj"];}
		else if($margin=='018'){
				   $hargaR = (int)$obatR["harga_gratis_ri"];}
		else if($margin=='019'){
				   $hargaR = (int)$obatR["harga_pen_bebas"];}
		else if($margin=='020'){
				   $hargaR = (int)$obatR["harga_nempil"];}
		else if($margin=='021'){
				   $hargaR = (int)$obatR["harga_nempil_apt"];}
		else if($margin=='022'){
				   $hargaR = (int)$obatR["harga_ranap"];}
		else if($margin=='023'){
				   $hargaR = (int)$obatR["harga_khusus_i"];}
		else if($margin=='024'){
				   $hargaR = (int)$obatR["harga_khusus_ii"];}
		else if($margin=='025'){
				   $hargaR = (int)$obatR["harga_pamper"];}
		else if($margin=='026'){
				   $hargaR = (int)$obatR["harga_kel_kry"];}
		else if($margin=='027'){
				   $hargaR = (int)$obatR["harga_obat_rajal"];}
		else if($margin=='028'){
				   $hargaR = (int)$obatR["harga_obat_tjp"];}
		else if($margin=='029'){
				   $hargaR = (int)$obatR["harga_bpjs"];}
		else if($margin=='030'){
				   $hargaR = (int)$obatR["harga_aswas"];}
		else if($margin=='031'){
				   $hargaR = (int)$obatR["harga_vaksin"];}
		else if($margin=='032'){
				   $hargaR = (int)$obatR["harga_alkes_bhp"];}
		else if($margin=='033'){
				   $hargaR = (int)$obatR["harga_nempil_rajal"];}
		else if($margin=='034'){
				   $hargaR = (int)$obatR["harga_langganan"];}
		else if($margin=='035'){
				   $hargaR = (int)$obatR["harga_klinik"];}
		else{
				   $hargaR = (int)$obatR["harga_umum_rj"];}  
		$jasareturn=0; 
//start pemisah tanggal
if ($oldTgl != $row['tanggal_entry']) {
	echo '<tr>';
		echo '<td bgcolor="#D3D3D3" colspan="10"><b><marquee behavior="alternate"><font size=2>';
			echo 'Transaksi Farmasi Tanggal : '.tanggal($rowRacikan['tanggal_entry']);
		echo '</font></marquee></b></td>';
	echo '</tr>';
}
if ($oldReg != $rowRacikan['nmr_transaksi']) {
	echo '<tr>';
		echo '<td bgcolor="#D3D3D3" colspan="10"><b><font size=2> <CENTER>';
			echo 'No Resep : '.$rowRacikan["nmr_transaksi"];
		echo ' </CENTER></font></b></td>';
	echo '</tr>';
}         
?>
	<tr>
            
		<td class="TBL_BODY" align="right"><?=$iRacikan?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($rowRacikan["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry2[0].':'.$arrJamEntry2[1]?></td>
		<td class="TBL_BODY" align="left">
			<input type="hidden" id="obat_id_<?php echo $rowRacikan["id"]?>" value="<?=$obatR["id"]?>" />
			<input type="hidden" id="harga_<?php echo $rowRacikan["id"]?>" value="<?=$hargaR?>" />
			<input type="hidden" id="hargareturn_<?php echo $rowRacikan["id"]?>" value="<?=$obatR["harga"]?>" />
			<input type="hidden" id="jasa_<?php echo $rowRacikan["id"]?>" value="<?=$rowRacikan["referensi"]?>" />
			<input type="hidden" id="jasareturn_<?php echo $rowRacikan["id"]?>" value="<?=$jasareturn?>" />
			<input type="hidden" id="tagihan_<?php echo $rowRacikan["id"]?>" value="<?=$rowRacikan["tagihan"]?>" />
			<input type="hidden" id="tipe_<?php echo $row["id"]?>" value="<?=$row["trans_type"]?>" />
			<span id="obat_nama_<?php echo $rowRacikan["id"]?>"><?=$obatR["obat"]?></span>
        </td>
		<td class="TBL_BODY" align="right">
                    <span id="qty_<?php echo $rowRacikan["id"]?>"><?=$rowRacikan["qty"]?></span>
                    <span id="satuan_<?php echo $rowRacikan["id"]?>"><?=$obatR["satuan"]?></span>
                </td>
		<td class="TBL_BODY" align="right"><?=number_format($rowRacikan["tagihan"],'0','','.')?></td>
		<td class="TBL_BODY" align="right"><span id="penjamin_<?php echo $rowRacikan["id"]?>"><?=number_format($rowRacikan["dibayar_penjamin"],'0','','.')?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $rowRacikan["id"]?>"><?=number_format($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"],'0','','.')?></span></td>
                <td class="TBL_BODY" align="center"><input type="checkbox" class="check_obat" name="cetak_<?php echo $iData ?>" id="cetak_<?php echo $iData ?>" value="<?php echo $rowRacikan["id"]?>"></td>
                <td class="TBL_BODY" align="center">
                    <a href="#" onClick="return_data_obat('<?php echo $rowRacikan["id"]?>')">Return</a> &nbsp; | &nbsp;
                    <a href="#" onClick="edit_data_obat('<?php echo $rowRacikan["id"]?>')">edit</a> &nbsp; | &nbsp;
                <a href="#" onClick="delete_data_obat('<?php echo $rowRacikan["id"]?>')">delete</a></td>
	</tr>
<?php
//end pemisah tanggal
$oldReg	= $rowRacikan['nmr_transaksi'];
$oldTgl	= $row['tanggal_entry'];
        }
?> 
<!-- BHP -->
<tr>
	<td class="TBL_BODY" colspan="10"><span style="font-weight: bold;">BHP</span></td>
</tr> 
<?php
$oldTgl	 = null;
$iObat = 0;
        while($row=pg_fetch_array($rowsPemakaianBHP)){
            $iData++;
            $iObat++;
            $total          = $total + $row["tagihan"];
            $totalPenjamin  = $totalPenjamin + $row["dibayar_penjamin"];
            $totalSelisih   = $totalSelisih + ($row["tagihan"]-$row["dibayar_penjamin"]);
            $totalDiskon   = $totalDiskon + $row["diskon"];
			
            $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga::integer,rs00016.harga_car_drs,
rs00016.harga_car_rsrj,rs00016.harga_car_rsri,rs00016.harga_inhealth_drs,rs00016.harga_inhealth_rs,
rs00016.harga_jam_ri,rs00016.harga_jam_rj,rs00016.harga_kry_kelinti,rs00016.harga_kry_kelbesar,
rs00016.harga_kry_kelgratisri,rs00016.harga_kry_kelrespoli,rs00016.harga_kry_kel,
rs00016.harga_kry_kelgratisrj,rs00016.harga_umum_ri,rs00016.harga_umum_rj::integer,
rs00016.harga_umum_ikutrekening,rs00016.harga_gratis_rj,rs00016.harga_gratis_ri,
rs00016.harga_ranap  ,
  rs00016.harga_khusus_i  ,
  rs00016.harga_khusus_ii  ,
  rs00016.harga_pamper  ,
  rs00016.harga_kel_kry  ,
  rs00016.harga_obat_rajal  ,
  rs00016.harga_obat_tjp  ,
  rs00016.harga_bpjs  ,
 rs00016.harga_aswas  ,
 rs00016.harga_vaksin  ,
  rs00016.harga_alkes_bhp  ,
  rs00016.harga_nempil_rajal  ,
  rs00016.harga_langganan  ,
  rs00016.harga_hv  ,
  rs00016.harga_klinik  ,
rs00016.harga_pen_bebas,rs00016.harga_nempil,rs00016.harga_nempil_apt 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] ."order by rs00015.id ASC");
            $obat = pg_fetch_array($sqlObat);
            $arrWaktuEntry = explode('.', $row["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
$margin = getFromTable("select item_id from rs00008 where no_reg='$noReg' and trans_type='BHP'");

if($margin=='001'){$harga = (int)$obat["harga_car_drs"];}
		else if($margin=='002'){
				   $harga = (int)$obat["harga_car_rsrj"];}
		else if($margin=='003'){
				   $harga = (int)$obat["harga_car_rsri"];}
		else if($margin=='004'){
				   //$harga = (int)$obat["harga_inhealth_drs"];
				  $harga = (int)$obat["harga_umum_rj"];
		}
		else if($margin=='005'){
				   //$harga = (int)$obat["harga_inhealth_rs"];
				   $harga = (int)$obat["harga_umum_ri"];
		}
		else if($margin=='006'){
				   $harga = (int)$obat["harga_jam_ri"];}
		else if($margin=='007'){
			           $harga = (int)$obat["harga_jam_rj"];
		}
		else if($margin=='008'){
				   $harga = (int)$obat["harga_kry_kelinti"];}
		else if($margin=='009'){
				   $harga = (int)$obat["harga_kry_kelbesar"];}
		else if($margin=='010'){
				   $harga = (int)$obat["harga_kry_kelgratisri"];}
		else if($margin=='011'){
				   $harga = (int)$obat["harga_kry_kelrespoli"];}
		else if($margin=='012'){
				   $harga = (int)$obat["harga_kry_kel"];}
		else if($margin=='013'){
				   $harga = (int)$obat["harga_kry_kelgratisrj"];}
		else if($margin=='014'){
				   $harga = (int)$obat["harga_umum_ri"];}
		else if($margin=='015'){
				   $harga = (int)$obat["harga_umum_rj"];}
		else if($margin=='016'){
				   $harga = (int)$obat["harga_umum_ikutrekening"];}
		else if($margin=='017'){
				   $harga = (int)$obat["harga_gratis_rj"];}
		else if($margin=='018'){
				   $harga = (int)$obat["harga_gratis_ri"];}
		else if($margin=='019'){
				   $harga = (int)$obat["harga_pen_bebas"];}
		else if($margin=='020'){
				   $harga = (int)$obat["harga_nempil"];}
		else if($margin=='020'){
				   $harga = (int)$obat["harga_nempil_apt"];}
		else if($margin=='022'){
				   $harga = (int)$obatR["harga_ranap"];}
		else if($margin=='023'){
				   $harga = (int)$obatR["harga_khusus_i"];}
		else if($margin=='024'){
				   $harga = (int)$obatR["harga_khusus_ii"];}
		else if($margin=='025'){
				   $harga = (int)$obatR["harga_pamper"];}
		else if($margin=='026'){
				   $harga = (int)$obatR["harga_kel_kry"];}
		else if($margin=='027'){
				   $harga = (int)$obatR["harga_obat_rajal"];}
		else if($margin=='028'){
				   $harga = (int)$obatR["harga_obat_tjp"];}
		else if($margin=='029'){
				   $harga = (int)$obatR["harga_bpjs"];}
		else if($margin=='030'){
				   $harga = (int)$obatR["harga_aswas"];}
		else if($margin=='031'){
				   $harga = (int)$obatR["harga_vaksin"];}
		else if($margin=='032'){
				   $harga = (int)$obatR["harga_alkes_bhp"];}
		else if($margin=='033'){
				   $harga = (int)$obatR["harga_nempil_rajal"];}
		else if($margin=='034'){
				   $harga = (int)$obatR["harga_langganan"];}
		else if($margin=='035'){
				   $harga = (int)$obatR["harga_langganan"];}
		else{
				   $harga = (int)$obat["harga_umum_rj"];}
		$jasareturn=0;
//start pemisah tanggal
if ($oldTgl != $row['tanggal_entry']) {
	echo '<tr>';
		echo '<td bgcolor="#D3D3D3" colspan="10"><b><marquee behavior="alternate"><font size=2>';
			echo 'Transaksi Farmasi Tanggal : '.tanggal($row["tanggal_entry"]);
		echo '</font></marquee></b></td>';
	echo '</tr>';
}
if ($oldReg != $row['nmr_transaksi']) {
	echo '<tr>';
		echo '<td bgcolor="#D3D3D3" colspan="10"><b><font size=2> <CENTER>';
			echo 'No Resep : '.$row["nmr_transaksi"];
		echo ' </CENTER></font></b></td>';
	echo '</tr>';
}
?>
	<tr>
		<td class="TBL_BODY" align="right"><?=$iObat?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
		<td class="TBL_BODY" align="left">
            <input type="hidden" id="obat_id_<?php echo $row["id"]?>" value="<?=$obat["id"]?>" />
            <input type="hidden" id="harga_<?php echo $row["id"]?>" value="<?=$harga?>" />
		    <input type="hidden" id="hargareturn_<?php echo $row["id"]?>" value="<?=$obat["harga"]?>" />
			<input type="hidden" id="jasa_<?php echo $row["id"]?>" value="<?=$row["referensi"]?>" />
		    <input type="hidden" id="jasareturn_<?php echo $row["id"]?>" value="<?=$jasareturn?>" />
            <input type="hidden" id="tagihan_<?php echo $row["id"]?>" value="<?=$row["tagihan"]?>" />
            <input type="hidden" id="tipe_<?php echo $row["id"]?>" value="<?=$row["trans_type"]?>" />
            <span id="obat_nama_<?php echo $row["id"]?>"><?=$obat["obat"]?></span>
        </td>
		<td class="TBL_BODY" align="right">
                    <span id="qty_<?php echo $row["id"]?>"><?=$row["qty"]?></span>
                    <span id="satuan_<?php echo $row["id"]?>"><?=$obat["satuan"]?></span>
        </td>
		<td class="TBL_BODY" align="right"><?=number_format($row["tagihan"],'0','','.')?></td>
		<td class="TBL_BODY" align="right"><span id="penjamin_<?php echo $row["id"]?>"><?=number_format($row["dibayar_penjamin"],'0','','.')?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $row["id"]?>"><?=number_format($row["tagihan"]-$row["dibayar_penjamin"],'0','','.')?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $row["id"]?>"><?=number_format($row["diskon"],'0','','.')?></span></td>
                <td class="TBL_BODY" align="center"><input type="checkbox" class="check_obat" name="cetak_<?php echo $iData ?>" id="cetak_<?php echo $iData ?>" value="<?php echo $row["id"]?>"></td>
                <td class="TBL_BODY" align="center">
                    <a href="#" onClick="return_data_obat('<?php echo $row["id"]?>')">Return</a> &nbsp; | &nbsp;
                    <a href="#" onClick="edit_data_obat('<?php echo $row["id"]?>')">edit</a> &nbsp; | &nbsp;
                <a href="#" onClick="delete_data_obat('<?php echo $row["id"]?>')">delete</a></td>
	</tr>
<?php
//end pemisah tanggal
$oldReg	= $row['nmr_transaksi'];
$oldTgl	= $row['tanggal_entry'];
        }
        echo '<input type="hidden" name="max_i" id="max_i" value="'.$iData.'">';
		
        ?>      
	<tr>
		<td class="TBL_HEAD" colspan="4" align="right"> T O T A L </td>
		<td class="TBL_HEAD" align="right" ><?=number_format($total,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalPenjamin,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalSelisih,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalSelisih-$totalDiskon,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >&nbsp;&nbsp;</td>
	</tr>
	<?PHP
	//DISKON SETELAH KUNJUNGAN KE 50
		//var_dump($_GET);
		$mr=getFromTable("SELECT mr_no from rs00006 where id='".$_GET["rg"]."'");
		$kunjungan=getFromTable("SELECT count(mr_no) from rs00006 where mr_no='".$mr."'");
		
		if ($kunjungan>21){
		$diskon=($total*(5/100));
		$sisa=$total-$diskon;
		$sisa3=$totalSelisih-$diskon;
		?>
		<tr>
		<td class="TBL_HEAD" colspan="4" align="right"> D I S K O N (5%)</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($diskon,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalPenjamin,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalSelisih-$totalDiskon,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >Kunjungan <br> ke <?=$kunjungan;?> &nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >&nbsp;&nbsp;</td>
		</tr>
		<tr>
		<td class="TBL_HEAD" colspan="4" align="right"> T O T A L &nbsp;&nbsp;&nbsp; B A Y A R </td>
		<td class="TBL_HEAD" align="right" ><?=number_format($sisa,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalPenjamin,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($sisa3,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >Kunjungan <br> ke <?=$kunjungan;?> &nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >&nbsp;&nbsp;</td>
		</tr>
		<?PHP
		}
	?>
	
</table>
<table  width='100%%'>
    <tr>
        <td class="TBL_BODY" align="center" width='33%'><b>Cetak Rawat Jalan</b>&nbsp;&nbsp;<a href="javascript: cetakkwitansi1(<? echo (int) $_GET["rg"];?>)" ><img src="images/cetak.gif" border="0"></a></td>
	<td class="TBL_BODY" align="center" width='33%'><b>Cetak Copy Resep</b>&nbsp;&nbsp;<a href="javascript: cetakTransaksicopyresep(<? echo (int) $_GET["rg"];?>)" ><img src="images/cetak.gif" border="0"></a></td>
        <td class="TBL_BODY" align="center" width='33%'><b>Cetak Rawat Inap</b>&nbsp;&nbsp;<a href="javascript: cetakTransaksi(<? echo (int) $_GET["rg"];?>)" ><img src="images/cetak.gif" border="0"></a></td>
    </tr>	
</table>
<?php
$rowsReturn = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty_return, tagihan, referensi, dibayar_penjamin, trans_type  
                             FROM rs00008_return 
                             WHERE trans_type IN ('OB1','BHP','RCK') AND rs00008_return.no_reg = '".$_GET["rg"]."' ");
?>
<div align="LEFT" class="FORM_TITLE"><b>Return</b></div>
<table width='100%'>
	<tr>
		<td class="TBL_HEAD" width='3%'><center>No.</center></td>
		<td class="TBL_HEAD" width='12%' ><center>Tanggal</center></td>
		<td class="TBL_HEAD"><center>Obat</center></td>
		<td class="TBL_HEAD" width='7%'><center>Jumlah</center></td>
		<td class="TBL_HEAD" width='7%'><center>Tagihan</center></td>
		<td class="TBL_HEAD" width='7%'><center>Penjamin</center></td>
		<td class="TBL_HEAD" width='7%'><center>Selisih</center></td>
		<td class="TBL_HEAD" width='7%'><center>Cetak <input type="checkbox" id="check_all_return"></center></td>
		<td class="TBL_HEAD" width='18%'>&nbsp;</td>
	</tr>
<?php
        $iData          = 0;
        while($row=pg_fetch_array($rowsReturn)){
            $iData++;
            
            $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
            $arrWaktuEntry = explode('.', $row["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
?>
	<tr>
            <td class="TBL_BODY" align="right"><?=$iData?></td>
            <td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
            <td class="TBL_BODY" align="left"><?=$obat["obat"]?></td>
            <td class="TBL_BODY" align="right"><?=$row["qty_return"]?></td>
            <td class="TBL_BODY" align="right"><?=number_format($row["tagihan"],'0','','.') ?></td>
            <td class="TBL_BODY" align="right"><?=number_format($row["dibayar_penjamin"],'0','','.')?></td>
            <td class="TBL_BODY" align="right"><?=number_format($row["tagihan"]-$row["dibayar_penjamin"],'0','','.')?></td>
            <td class="TBL_BODY" align="center"><input type="checkbox" class="check_return" name="cetak_return_<?php echo $iData ?>" id="cetak_return_<?php echo $iData ?>" value="<?php echo $row["id"]?>"></td>
            <td class="TBL_BODY" align="center">
            <a href="#" onClick="delete_data_obat_return('<?php echo $row["id"]?>')">delete</a></td>
	</tr>
<?php
        }
        echo '<input type="hidden" name="max_i_return" id="max_i_return" value="'.$iData.'">';
?>        
</table>
<table  width='100%%'>
    <tr>
        <td class="TBL_BODY" align="center" width='34%'><b>Cetak Return Farmasi</b>&nbsp;&nbsp;<a href="javascript: cetakReturn(<? echo (int) $_GET["rg"];?>)" ><img src="images/cetak.gif" border="0"></a></td>
    </tr>	
</table>
<!-- ---------------------- End Buat tabel hasil input obat -------------------- -->
<table width="100%">
        <tbody>
            <tr>
                <td align="center" width="20%"> Cetak Copy Resep</td>
                <td align="left" > &nbsp;</td>
        </tr>
        <tr>
                <td align="center"> <a href="javascript: cetakcopyresep('<?php echo $_GET["rg"] ?>')"><img border="0" src="images/cetak.gif"></a></td>
                <td align="left" > &nbsp;</td>
        </tr>
    </tbody>
</table>
<script>
    
    function cetakcopyresep(tag) {
        sWin = window.open('includes/cetak.copy_resep.php?rg=' + tag+'&kas=ri', 'xWin', 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');
        sWin.focus();
    }

    $('#check_all_return').click(function(){
        if($('#check_all_return').is(':checked')){
            $('.check_return').attr("checked",true);
        }else{
            $('.check_return').attr("checked",false);
        }        
    })
    $('#check_all_obat').click(function(){
        if($('#check_all_obat').is(':checked')){
            $('.check_obat').attr("checked",true);
        }else{
            $('.check_obat').attr("checked",false);
        }        
    })
    
    function cetakReturn() {
        maxIReturn = $('#max_i_return').val();
        selectedToPrint = '&max_return='+maxIReturn;
        for(iReturn=0;iReturn<=maxIReturn;iReturn++){
            if($('#cetak_return_'+iReturn).is(':checked') == true){
                obatReturnSelected = $('#cetak_return_'+iReturn).val();
                selectedToPrint = selectedToPrint+'&obat_id_'+iReturn+'='+obatReturnSelected;
            }
        }
        window.open('includes/cetak.rincian_obat_return.php?rg=<?php echo $_GET['rg']?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    }
    
	function cetakTransaksicopyresep() {
        maxI = $('#max_i').val();
        selectedToPrint = '&max_cetak='+maxI;
        for(i=0;i<=maxI;i++){
            if($('#cetak_'+i).is(':checked') == true){
                obatSelected = $('#cetak_'+i).val();
                selectedToPrint = selectedToPrint+'&cetak_'+i+'='+obatSelected;
            }
        }
        window.open('includes/cetak.rincian_obat_selectedcopyresep.php?rg=<?php echo $_GET['rg']?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');

    }

    function cetakTransaksi() {
        maxI = $('#max_i').val();
        selectedToPrint = '&max_cetak='+maxI;
        for(i=0;i<=maxI;i++){
            if($('#cetak_'+i).is(':checked') == true){
                obatSelected = $('#cetak_'+i).val();
                selectedToPrint = selectedToPrint+'&cetak['+i+']='+obatSelected;
            }
        }
        window.open('includes/cetak.rincian_obat_selected.php?rg=<?php echo $_GET['rg']?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    }
</script>
<?php
function tanggal($tanggal) {
        $arrTanggal = explode('-', $tanggal);

        $hari = $arrTanggal[2];
        $bulan = $arrTanggal[1];
        $tahun = $arrTanggal[0];

        $result = $hari . ' ' . bulan($bulan) . ' ' . $tahun;

        return $result;
    }

function bulan($params) {
    switch ($params) {
        case 1:
            $bln = "Jan";
            break;
        case 2:
            $bln = "Peb";
            break;
        case 3:
            $bln = "Mar";
            break;
        case 4:
            $bln = "Apr";
            break;
        case 5:
            $bln = "Mei";
            break;
        case 6:
            $bln = "Jun";
            break;
        case 7:
            $bln = "Jul";
            break;
        case 8:
            $bln = "Agu";
            break;
        case 9:
            $bln = "Sep";
            break;
        case 10:
            $bln = "Okt";
            break;
        case 11:
            $bln = "Nop";
            break;
        case 12:
            $bln = "Des";
            break;
            break;
    }
    return $bln;
}

function updateTagihanUntukKasir($con, $rg){
            // ---- insert juga rs00005 untuk kasir
        $sqlTotalBiayaObat      = pg_query($con, "SELECT SUM(tagihan) as jumlah_tagihan, SUM(dibayar_penjamin) as jumlah_penjamin  
                            FROM rs00008 
                            WHERE no_reg = '".$rg."' ");
        $totalBiayaObat = pg_fetch_array($sqlTotalBiayaObat);

        if($totalBiayaObat['jumlah_tagihan'] > 0){
            $totalBiayaObatVal = $totalBiayaObat['jumlah_tagihan'];
        }else{
            $totalBiayaObatVal = 0;
        }
        
        if($totalBiayaObat['jumlah_penjamin'] > 0){
            $totalBiayaObatValPenjamin = $totalBiayaObat['jumlah_penjamin'];
        }else{
            $totalBiayaObatValPenjamin = 0;
        }
        
        // cek dulu di tabel rs00005 klo datanya udah ada di update, klo g ada di insert aja cuy
        $sqlCek = pg_query($con, "SELECT jumlah FROM  rs00005 WHERE reg = '".$rg."' AND kasir = 'RJL' AND layanan = '320RJ_SWD' ");
        
        if(pg_num_rows($sqlCek) > 0){
            pg_query($con, "UPDATE  rs00005  SET  jumlah = ".((int)$totalBiayaObatVal-(int)$totalBiayaObatValPenjamin)." 
                WHERE reg = '".$rg."' AND kasir = 'RJL' AND layanan = '320RJ_SWD' ");
        }else{
            pg_query($con, "INSERT INTO rs00005 VALUES( nextval('kasir_seq'), '".$rg."', ".
        "CURRENT_DATE, 'RJL', 'Y', 'N', '320RJ_SWD', ".((int)$totalBiayaObatVal-(int)$totalBiayaObatValPenjamin).", 'N')");
        }
}
?>
