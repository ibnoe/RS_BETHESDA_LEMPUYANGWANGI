<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.autocomplete.css">
<table>
    <tr>
        <td  style="font-size: 16px; font-weight: bold;">&nbsp; <img src='icon/apotek-icon.png' width='48' align='absmiddle' >Penggunaan Barang Habis Pakai</td>
    </tr>
</table>
<?php 	
session_start();
require_once("lib/dbconn.php");
$rowPasien = pg_query($con,
            "select a.id, a.nama,a.alm_tetap,a.tipe_desc, e.bangsal from rsv_pasien2 a 
				join rs00010 as b on a.id = b.no_reg join rs00012 as c on b.bangsal_id = c.id 
				join rs00012 as d on d.hierarchy = substr(c.hierarchy,1,6) || '000000000' 
				join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,3) || '000000000000' 
				join rs00010 as f on f.no_reg = a.id
				where a.id = '".(string)$_GET['rg']."'");
$pasien = pg_fetch_object($rowPasien);
$rowDokterDPJP = pg_query($con,
            "SELECT(C.NAMA)AS nama FROM C_VISIT_RI A 
                LEFT JOIN RS00017 C ON A.VIS_1::text = C.ID::text
                LEFT JOIN RS00017 D ON A.VIS_2::text = D.ID::text
                LEFT JOIN RS00017 E ON A.id_dokter::text = E.ID::text
                WHERE A.NO_REG='".(string)$_GET['rg']."'::text");
$dokterDPJP = pg_fetch_object($rowDokterDPJP);

$bangsal = $pasien->bangsal;
switch ($bangsal) {
    case "MULTAZAM":
        $fieldStock =  "qty_009";
        break;
    case "MARWAH":
        $fieldStock =  "qty_007";
        break;
    case "AROFAH":
        $fieldStock =  "qty_004";
        break;
    case "MINA":
        $fieldStock =  "qty_008";
        break;
    case "SHOFA BAYI":
        $fieldStock =  "qty_011";
        break;
    case "SHOFA PERSALINAN":
        $fieldStock =  "qty_012";
        break;
}

?>
<p/>
<p/>
<form method="post" action="#" id="form-bhp" style="padding-left: 10px;">
<table>
    <tr>
        <td style="font-size: 11px;">No. Registrasi</td>
        <td style="font-size: 11px;">: <b><?php echo $pasien->id ?></b></td>
        <td style="font-size: 11px;" width="30%">&nbsp;</td>
        <td style="font-size: 11px;">Tipe Pasien</td>
        <td style="font-size: 11px">: <b><?php echo $pasien->tipe_desc ?></b></td>
    </tr>
    <tr>
        <td style="font-size: 11px;">Nama</td>
        <td style="font-size: 11px;">: <b><?php echo $pasien->nama ?></b></td>
        <td style="font-size: 11px;" width="30%">&nbsp;</td>
        <td style="font-size: 11px;">Ruangan</td>
        <td style="font-size: 11px">: <b><?php echo $pasien->bangsal ?></b></td>
    </tr>
    <tr>
        <td style="font-size: 11px;">Alamat</td>
        <td style="font-size: 11px;">: <b><?php echo $pasien->alm_tetap ?></b></td>
        <td style="font-size: 11px;" width="30%">&nbsp;</td>
        <td style="font-size: 11px;">Dokter DPJP</td>
        <td style="font-size: 11px">: <b><?php echo $dokterDPJP->nama ?></b></td>
    </tr>
</table>
<table id="list-obat">
<thead>
    <tr>
        <td align="CENTER" class="TBL_HEAD" width="300">Nama Obat</td>
        <td align="CENTER" class="TBL_HEAD" width="80">Qty</td>
        <td align="CENTER" class="TBL_HEAD" width="80">Harga Satuan</td>
        <td align="CENTER" class="TBL_HEAD" width="80">Harga Total</td>
        <td align="CENTER" class="TBL_HEAD" width="50">Penjamin(%)</td>
        <td align="CENTER" class="TBL_HEAD" width="50">Penjamin(Rp.)</td>
        <td align="CENTER" class="TBL_HEAD" width="50">Selisih</td>
        <td align="CENTER" class="TBL_HEAD" width=""></td>
    </tr>
</thead>
<tbody>
                <tr>
                    <td>
                        <input type="hidden" name="rs00008_id" id="rs00008_id" value="">
                        <input type="hidden" name="is_return" id="is_return" value="0">
                        <input type="hidden" name="obat_id" id="obat_id" value="">
                        <input type="hidden" name="qty_awal" id="qty_awal" value="" >
                        <input type="hidden" name="harga_awal" id="harga_awal" value="" >
                        <input type="hidden" name="jumlah_awal" id="jumlah_awal" value="" >
                        <input type="hidden" name="penjamin_awal" id="penjamin_awal" value="" >
                        <input type="text" name="obat_nama" id="obat_nama" size="40" value="">
                    </td>
                    <td><input type="text" name="qty" id="qty" size="2" value="" style="text-align: right;"> (<span id="stok"></span>)</td>
                    <td><input type="text" name="harga" id="harga" size="10" value="" style="text-align: right;"></td>
                    <td><input type="text" name="jumlah" id="jumlah" size="10" value="" style="text-align: right;"></td>
                    <td><input type="checkbox" id="is_penjamin" ><input type="text" name="penjamin_persen" id="penjamin_persen" size="3" value="0" style="text-align: right;"></td>
                    <td>
                        <input type="text" name="penjamin" id="penjamin" size="10" value="0" style="text-align: right;">
                    </td>
                    <td><input type="text" name="selisih" id="selisih" size="10" value="0" style="text-align: right;"></td>
                    <td><input type="button" id="save-obat" value=" OK " /><input type="button" id="reset-obat" value=" Reset " /></td>
                </tr>
</table>
<input type="hidden" name="max_obat"  id="max_obat" value="0">
</form>
<div id="list_pemakaian_bhp"  style="padding-left: 10px;"></div>
<?php
 
$result = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00016.harga, rs00016a.".$fieldStock." AS stok
    FROM rs00015 
    INNER JOIN rs00001 ON rs00015.kategori_id = rs00001.tc 
    INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id
    INNER JOIN rs00016a ON rs00015.id = rs00016a.obat_id 
    WHERE rs00001.tt = 'GOB'
    ORDER BY rs00015.obat ASC");
?>
<script>
    $(function() {
		
        $('#list_pemakaian_bhp').load('actions/apotik_bhp_rwi_insert.php?rg=<?php echo $_GET['rg'] ?>&fs=<?php echo $fieldStock?>');
        
        var data = [
            <?php 
            while ($row = pg_fetch_array($result))
            {
                $id = $row["id"];
                $harga = (int)$row["harga"];
                $stok = $row["stok"];
                $obat = str_replace("'","/",$row["obat"]);
                
                echo "{";
                echo "id: ".$id .", ";
                echo "value: '".$obat ."', ";
                echo "harga: '".$harga ."',";
                echo "stok: '".$stok ."'";
                echo "},";
            }
            ?>
                        ""
        ];

        $('#is_penjamin').click(function(){
            valJumlah = $('#jumlah').val();
            $('#penjamin_persen').val('100');
            $('#penjamin').val(valJumlah);
            $('#selisih').val(0);
        });


        $( "#obat_nama" ).autocomplete({
            source: data,
            messages: {
			noResults: "",
			results: function( amount ) {
				
			}
		},
            minLength: 3,
            select: function (event, ui) {
                $('#obat_id').val('');
                $('#qty').val('');
                $("#harga").val('');
                $("#penjamin").val('');
                $("#selisih").val('');
                $("#stok").empty();
                var obatId = ui.item.id;
                var obatNama = ui.item.value;
                var obatSatuan = ui.item.satuan;
                var obatHarga = ui.item.harga;
                var obatStok = ui.item.stok;
                
                if(parseInt(obatStok) < 1){
                    alert('stok kosong !');
                    return false;
                }
                
                $('#obat_nama').val('obatNama');
                $('#obat_id').val(obatId);
                $("#harga").val(obatHarga);
                $("#stok").html(obatStok);
                $('#penjamin').val(0);
                $('#is_penjamin').attr('checked', false);
            }
        });
        
        $("#qty").keyup( function(){
            var obatQty = parseFloat($('#qty').val());
            var obatHarga = parseFloat($('#harga').val());   
            var obatPenjamin = parseFloat($('#penjamin').val());   
            jumlah = obatQty*obatHarga;
            selisih = jumlah-obatPenjamin;
            $('#jumlah').val(jumlah);
            $('#selisih').val(selisih);
        });
        
        $("#penjamin_persen").keyup( function(){
            var obatQty = parseFloat($('#qty').val());
            var obatHarga = parseFloat($('#harga').val());   
            var obatPenjaminPersen = parseFloat($('#penjamin_persen').val());   
            
            
            jumlah = obatQty*obatHarga;
            obatPenjamin = (obatPenjaminPersen*jumlah)/100;
            $('#penjamin').val(obatPenjamin);
            
            selisih = jumlah-obatPenjamin;
            $('#jumlah').val(jumlah);
            $('#selisih').val(selisih);
        });

        $("#penjamin").keyup( function(){
            var obatQty = $('#qty').val();
            var obatHarga = $('#harga').val();   
            var obatPenjamin = $('#penjamin').val();   
            jumlah = (parseFloat(obatQty)*parseFloat(obatHarga));
            selisih = (parseFloat(obatQty)*parseFloat(obatHarga))-parseFloat(obatPenjamin);
            $('#jumlah').val(jumlah);
            $('#selisih').val(selisih);
        });
                
        
        $('#save-obat').click(function(){
            valrs00008Id	= $('#rs00008_id').val();
            valRg		= $('#rg').val();
            valObatId		= $('#obat_id').val();
            valQty		= $('#qty').val();
            valQtyAwal		= $('#qty_awal').val();
            valHarga		= $('#harga').val();
            valJumlah           = $('#jumlah').val();
            valHargaAwal       = $('#harga_awal').val();
            valJumlahAwal       = $('#jumlah_awal').val();
            valPenjamin		= $('#penjamin').val();
            valPenjaminAwal	= $('#penjamin_awal').val();
            valIsReturn		= $('#is_return').val();
            
            if(valQty <= 0){
                alert('Jumlah obat harus diisi !');
                return false;
            }
            
            $.post('actions/apotik_bhp_rwi_insert.php?rg=<?php echo $_GET['rg']?>&fs=<?php echo $fieldStock?>',
                        {
                            rg: valRg,
                            obat_id: valObatId,
                            qty: valQty,
                            qty_awal: valQtyAwal,
                            harga: valHarga,
                            jumlah: valJumlah,
                            harga_awal: valHargaAwal,
                            jumlah_awal: valJumlahAwal,
                            penjamin: valPenjamin,
                            penjamin_awal: valPenjaminAwal,
                            rs00008_id: valrs00008Id,
                            is_return: valIsReturn
                            
                        }
                    ).success(function(data){ 
                                        $('#rs00008_id').val('');
                                        $('#is_return').val('');
                                        $('#obat_id').val('');
                                        $('#obat_nama').val('');
                                        $("#qty").val('');
                                        $("#qty_awal").val('');
                                        $("#harga").val('');
                                        $("#jumlah").val(0);
                                        $("#penjamin").val(0);
                                        $("#penjamin_persen").val(0);
                                        $("#penjamin_awal").val(0);
                                        $("#selisih").val(0);
                                        $('#list_pemakaian_bhp').empty();
                                        $('#list_pemakaian_bhp').html(data);
                                        $('#is_penjamin').attr('checked', false);
                                        $('#save-obat').val('OK');
                                     });            
        })

    });
    
    $('#reset-obat').click(function(){
        $('#rs00008_id').val('');
        $('#is_return').val('');
        $('#obat_id').val('');
        $('#obat_nama').val('');
        $("#qty").val('');
        $("#qty_awal").val('');
        $("#stok").text('');
        $("#harga").val('');
        $("#jumlah").val(0);
        $("#penjamin").val(0);
        $("#penjamin_persen").val(0);
        $("#penjamin_awal").val(0);
        $("#selisih").val(0);
        $('#is_penjamin').attr('checked', false);
    });
    
    function edit_data_obat(id){
        var obatId = $('#obat_id_'+id).val();
        var obatNama = $('#obat_nama_'+id).text();
        var qty = $('#qty_'+id).text();
        var qtyAwal = $('#qty_'+id).text();
        var harga = parseFloat($('#harga_'+id).val().replace('.', ''));
        var penjamin = parseFloat($('#penjamin_'+id).text().replace('.', ''));
        var selisih = parseFloat($('#selisih_'+id).text().replace('.', ''));
        var tipe = $('#tipe_'+id).val();
        
        $('#save-obat').val('Update');
        
        jumlah = (parseFloat(qty)*parseFloat(harga));
   
        $('#rs00008_id').val( id );
        $('#obat_id').val( obatId );
        $('#obat_nama').val( obatNama );
        $('#qty').val( parseFloat(qty) );
        $('#qty_awal').val( parseFloat(qty) );
        $('#harga').val( harga );
        $('#penjamin').val( penjamin );
        $('#jumlah').val(jumlah);
        $('#selisih').val(selisih);

    }
    function return_data_obat(id){
        var obatId = $('#obat_id_'+id).val();
        var obatNama = $('#obat_nama_'+id).text();
        var qty = $('#qty_'+id).text();
        var harga = parseFloat($('#harga_'+id).val().replace('.', ''));
        var penjamin = parseFloat($('#penjamin_'+id).text().replace('.', ''));
        var tagihan = parseFloat($('#tagihan_'+id).val().replace('.', ''));
        var selisih = parseFloat($('#selisih_'+id).text().replace('.', ''));
        var tipe = $('#tipe_'+id).val();
        
        $('#save-obat').val('Return');
           
        $('#rs00008_id').val( id );
        $('#is_return').val(1);
        $('#obat_id').val( obatId );
        $('#obat_nama').val( obatNama );
        $('#qty').val( parseFloat(qty) );
        $('#qty_awal').val( parseFloat(qty) );
        $('#harga').val( harga );
        $('#harga_awal').val(tagihan);
        $('#penjamin').val(penjamin);
        $('#penjamin_awal').val( penjamin );
        $('#jumlah').val(tagihan);
        $('#jumlah_awal').val(tagihan);
        $('#selisih').val(selisih);

		$('#is_penjamin').attr('checked', false);

    }
    
    function delete_data_obat(id){
         var valRg   = $('#rg').val();
         var obatId  = $('#obat_id_'+id).val();
         var qty     = $('#qty_'+id).text();
         
         $.post('actions/apotik_bhp_rwi_insert.php?rg=<?php echo $_GET['rg']?>&fs=<?php echo $fieldStock?>&del=true',
                        {
                            obat_id: obatId,
                            qty: qty,
                            rs00008_id: id
                        }
                    ).success(function(data){ 
                                        $('#list_pemakaian_bhp').empty();
                                        $('#list_pemakaian_bhp').html(data);
										$('#list_pemakaian_bhp').load('actions/apotik_bhp_rwi_insert.php?rg=<?php echo $_GET["rg"]?>&fs=<?php echo $fieldStock?>');
                                     });   
    }
    function delete_data_obat_return(id){
         var valRg   = $('#rg').val();
         var obatId  = $('#obat_id_'+id).val();
         var qty     = $('#qty_'+id).text();
         
         $.post('actions/apotik_bhp_rwi_insert.php?rg='+valRg+'&fs=<?php echo $fieldStock?>&delreturn=true',
                        {
                            rs00008_return_id: id
                        }
                    ).success(function(data){ 
                                        $('#list_pemakaian_bhp').empty();
                                        $('#list_pemakaian_bhp').html(data);
					$('#list_pemakaian_bhp').load('actions/apotik_bhp_rwi_insert.php?rg=<?php echo $_GET["rg"]?>&fs=<?php echo $fieldStock?>');
                                     });   
    }
    
    function cetakkwitansi1(tag) {
        sWin = window.open('includes/cetak.rincian_obat.php?rg=<?php echo $_GET['rg']?>&kas=<?php echo $_GET['rg']?>', 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
        sWin.focus();
    }
    
    function cetakTransaksi() {
        $('#list_obat_created').submit();
    }
    function cetakReturn() {
        window.open('includes/cetak.rincian_obat_return.php?rg=<?php echo $_GET['rg']?>&kas=<?php echo $_GET['rg']?>', 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    }
 </script>