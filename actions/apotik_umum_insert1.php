<?php
session_start();
require_once("../lib/dbconn.php");
$PID1	    = "apotik_umum_SWD";
$noReg      = $_POST["no_reg"];
$nama       = $_POST["nama"];
$alamat     = $_POST["alamat"];
$resep      = 'TIDAK';
$dokter     = $_POST["dokter"];
$praktek    = $_POST["praktek"];
$obatId     = $_POST["obat_id"];
$obatNama   = $_POST["obat_nama"];
$qty        = floatval($_POST["qty"]);
$harga      = floatval($_POST["harga"]);
$jumlah     = floatval($_POST["jumlah"]);
$tokit = pg_query("select nextval('rs00008_seq_group')");

if($_POST["resep"] != ''){
    $resep = 'YA';
}

if(isset($obatId)){
    $sqlStok = pg_query($con, "SELECT qty_ri FROM rs00016a WHERE obat_id = ".$obatId);
    $rowStok = pg_fetch_array($sqlStok);
    $stok    = $rowStok['qty_ri'];
}

if($_POST["del"] == 'true' ){
    pg_query($con, "DELETE FROM apotik_umum WHERE id = ".$_POST['id']);

    pg_query($con, "UPDATE rs00016a SET qty_ri = ".($stok+$qty)."  WHERE obat_id = ".$obatId);
	
	//--- wildan ST. delete from rs00008
	pg_query($con, "DELETE FROM rs00008 WHERE id_transaksi = '".$_POST['id']."'");
	//---
}

if($_POST["id"] != '' ){
    pg_query($con, "UPDATE apotik_umum SET 
                obat_id = ".$_POST['obat_id'].", 
                banyaknya = ".$_POST['qty'].", 
                harga = ".$_POST['harga'].", 
                jumlah = ".$_POST['jumlah']." 
                WHERE id = ".$_POST['id']."    
                ");
    
    pg_query($con, "UPDATE rs00016a SET qty_ri = ".(($stok+$_POST["qty_awal"]) -$qty)."  WHERE obat_id = ".$obatId);

	//--- wildan ST. update from rs00008
	pg_query($con, "UPDATE rs00008 SET 
                item_id = ".$_POST['obat_id'].", 
                qty = ".$_POST['qty'].", 
                harga = ".$_POST['harga'].", 
                tagihan = ".$_POST['jumlah']." 
                WHERE id_transaksi = '".$_POST['id']."'
                ");
	//---    
}

if($_POST['no_reg'] != '' && $_POST["id"] == ''){
    pg_query($con, "INSERT INTO apotik_umum (id, no_reg, nama, alamat, resep, dokter, praktek, obat_id, obat_nama,banyaknya, harga, jumlah, tanggal_entry, waktu_entry, nama_relasi) 
                            values(
                            nextval('apotik_umum_seq'), '".$noReg."', '".$nama."', '".$alamat."', '".$resep."', '".$dokter."', '".$praktek."', ".$obatId.", '".$obatNama."', ".$qty.", ".$harga.", ".$jumlah.", CURRENT_DATE, CURRENT_TIME,'".$_POST["nama_relasi"]."')" );
    pg_query($con, "UPDATE rs00016a SET qty_ri = ".($stok-$qty)."  WHERE obat_id = ".$obatId);
	
	//--- wildan ST. insert to rs00008
    $idApotikUmum = getFromTable("select id from apotik_umum order by id DESC");
    $namaRelasi = getFromTable("select item_id from rs00008 where no_reg='$noReg' and trans_type='OBM'");
    
    if($namaRelasi == '022') {
    	$tuslah = 1250;
    } else {
    	$tuslah = 0;
    }
    
    pg_query($con, "insert into rs00008 (
	                id, trans_type, trans_form, trans_group, tanggal_trans,
	                tanggal_entry, waktu_entry, no_reg, item_id, referensi,
	                qty, harga, tagihan, pembayaran, user_id, id_transaksi
	            	) values (
	                nextval('rs00008_seq'), 'OB2', '$PID1', currval('rs00008_seq_group'), CURRENT_DATE,
	                CURRENT_DATE, CURRENT_TIME, '".$noReg."', '".$obatId."', '".$tuslah."',
	                '".$qty."', '".$harga."', '".$jumlah."', 0, '".$_SESSION["uid"]."', '".$idApotikUmum."')"
              );
  	//---
}

$sql = pg_query($con, "SELECT MAX(no_reg) AS max_no_reg FROM apotik_umum");
$row = pg_fetch_array($sql);
$maxNoReg    = $row['max_no_reg'];
$lastNo      = substr($maxNoReg, 1, 4);
$nextNo      = substr('000'.($lastNo+1),-4);
$newNoReg    = 'A'.$nextNo.date('m').date('y');

if ($_POST['act'] == "new2") {

	$noReg      = $_POST["no_reg"];
	$SQLa="insert into rs00008 (" .
                "id,            trans_type,  trans_form, trans_group, tanggal_trans, " .
                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
                "qty,           harga,       tagihan,    pembayaran,user_id ".
            ") values (".
                "nextval('rs00008_seq'), 'OBM', '$PID1', currval('rs00008_seq_group'), CURRENT_DATE, " .
                "CURRENT_DATE, CURRENT_TIME, '".$newNoReg."', '".$_POST["nama_relasi"]."', 0, " .
                "0,0,0,0,'".$_SESSION["uid"]."')";

	pg_query($con, $SQLa);

			header("Location: ../index2.php?p=apotik_umum&tt=".$_POST[tt]."&rg=".$newNoReg."&sub=obat&nama_relasi=".$_POST[nama_relasi]);
			exit;
	}
?>
<h2>Daftar Pembelian Obat</h2>
<table>
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="300">Nama Obat</td>
            <td align="CENTER" class="TBL_HEAD" width="50">Resep</td>
            <td align="CENTER" class="TBL_HEAD" width="50">Jumlah</td>
            <td align="CENTER" class="TBL_HEAD" width="80">Harga Satuan</td>
            <td align="CENTER" class="TBL_HEAD" width="80">Harga Total</td>
            <td align="CENTER" class="TBL_HEAD" width="120"></td>
        </tr>
    </thead>
    <tbody>
        <?php
        $sqlPembelian = pg_query($con, "SELECT id, obat_id, obat_nama, resep, banyaknya, harga, jumlah FROM apotik_umum  WHERE no_reg = '".$_GET['no_reg']."' ");
        $i=0;
        $total = 0;
        while($rowPembelian=pg_fetch_array($sqlPembelian)){
            $i++;
            $total = $total + $rowPembelian['jumlah'];
        ?>
        <tr>
            <td>
                <input type="hidden" id="id" value="<?php echo $rowPembelian['id']?>">
                <input type="hidden" id="obat_id_<?php echo $rowPembelian['id'] ?>" value="<?php echo $rowPembelian['obat_id']?>">
                <input type="hidden" id="obat_nama_<?php echo $rowPembelian['id'] ?>" value="<?php echo rtrim($rowPembelian['obat_nama'])?>">
                <input type="hidden" id="qty_<?php echo $rowPembelian['id'] ?>" value="<?php echo $rowPembelian['banyaknya']?>">
                <input type="hidden" id="harga_<?php echo $rowPembelian['id'] ?>" value="<?php echo $rowPembelian['harga']?>">
                <input type="hidden" id="jumlah_<?php echo $rowPembelian['id'] ?>" value="<?php echo $rowPembelian['jumlah']?>">
                <?php echo $rowPembelian['obat_nama']?>
            </td>
            <td align="right"><?php echo $rowPembelian['resep']?></td>
            <td align="right"><?php echo $rowPembelian['banyaknya']?></td>
            <td align="right"><?php echo $rowPembelian['harga']?></td>
            <td align="right"><?php echo $rowPembelian['jumlah']?></td>
            <td align="center"><a href="#" onClick="edit_data_obat(<?php echo $rowPembelian['id']?>)">[ Edit ]</a> &nbsp; <a href="#" onClick="delete_data_obat(<?php echo $rowPembelian['id']?>)">[ Delete ]</a></td>
        </tr>
        <?php } ?>
        <tr>
            <td>&nbsp;</td>
            <td align="right" colspan="3"><b>TOTAL</b></td>
            <td align="right"><b><?php echo $total?></b></td>
            <td align="center">&nbsp;</td>
        </tr>
    </tbody>
</table>
<table>
	<tr>
	<td class="TBL_BODY" colspan="" align="right">Cetak Rincian Obat</td>
	<td class="TBL_BODY" align="center" width='15%'><a href="javascript: cetakRincianObat('<? echo $_GET['no_reg']; ?>')" ><img src="images/cetak.gif" border="0"></a></td>
	</tr>	
</table>
