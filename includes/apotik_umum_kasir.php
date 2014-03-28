<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery-ui.custom.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.autocomplete.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.tabs.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.dataTables.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.dataTables_themeroller.css">
<script src="plugin/ui/jquery.ui.core.js"></script>
<script src="plugin/ui/jquery.ui.tabs.js"></script>
<script src="plugin/jquery.dataTables.js"></script>
<script src="plugin/jquery.form.js"></script>
<?php 	
session_start();
require_once("lib/dbconn.php");
$PID = "apotik_umum_kasir";
$SC = $_SERVER["SCRIPT_NAME"];
?>
<p/>
<p/>
<h2>Daftar Penjualan Obat - Apotek Umum</h2>
</table>
<br/>
			<table>
                <tr>
                    <td>Pilih waktu yang akan ditampilkan </td>
                    <td>: 
                        <select name="range_date" id="range_date" onChange="fltData(this.value)">
                            <option value="<?php echo date('Y-m-d')?>" <?php if((string)date('Y-m-d') == $_GET['start_date']){ echo 'selected="selected"'; } ?> >Hari Ini</option>
                            <option value="<?php  echo date("Y-m-d", strtotime("-1 day") ) ?>" <?php if((string)date('Y-m-d', strtotime("-1 day")) == $_GET['start_date']){ echo 'selected="selected"'; } ?>>Kemarin</option>
                            <option value="<?php  echo date("Y-m-d", strtotime("-7 day") ) ?>" <?php if((string)date('Y-m-d', strtotime("-7 day")) == $_GET['start_date']){ echo 'selected="selected"'; } ?>>1 Minggu</option>
                            <option value="<?php  echo date("Y-m-d", strtotime("-30 day") ) ?>" <?php if((string)date('Y-m-d', strtotime("-30 day")) == $_GET['start_date']){ echo 'selected="selected"'; } ?>>1 Bulan</option>
                        	<option value="<?php  echo date("Y-m-d", strtotime("-365 day") ) ?>" <?php if((string)date('Y-m-d', strtotime("-365 day")) == $_GET['start_date']){ echo 'selected="selected"'; } ?>>1 Tahun</option>
                        </select>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" border="0" class="" id="daftar_penjulan_obat">
            <thead>
                <tr>
                    <th>no</th>
                    <th>Tanggal</th>
                    <th>No. Registrasi</th>
                    <th>Resep</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Dokter</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $flt = date('Y-m-d');
                if(!empty($_GET['start_date']) ){
                    $flt = $_GET['start_date'];   
                }
                $rows      = pg_query($con, "SELECT DISTINCT ON (no_reg) tanggal_entry, no_reg, nama, alamat, resep, dokter FROM apotik_umum WHERE tanggal_entry >= '".$flt."' ORDER BY no_reg DESC");
                $i=0;
                while($row=pg_fetch_array($rows)){
                    $i++;
                ?>
                        <tr class="odd gradeC">
                            <td><?php echo $i ?></td>
                            <td><?php echo $row["tanggal_entry"] ?></td>
                            <td><?php echo $row["no_reg"] ?></td>
                            <td><?php echo $row["resep"] ?></td>
                            <td><?php echo $row["nama"] ?></td>
                            <td><?php echo $row["alamat"] ?></td>
                            <td><?php echo $row["dokter"] ?></td>
                            <td><a href="<?php echo $SC.'?p='.$PID.'&no_reg='.$row["no_reg"]?>">edit</a> | <a href="#" onClick="cetakPembelian('<?php echo $row['no_reg']?>')"> cetak</a></td>
                        </tr>
                        <?php
                }
                ?>                    
            </tbody>
        </table> 


<script>
$('#daftar_penjulan_obat').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aoColumns": [
                            { "bSortable": false },
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null
                        ]
});

function cetakPembelian(no_reg){
    window.open('includes/cetak.apotik_umum.php?no_reg='+no_reg, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
}

function fltData(str){
    window.location = 'index2.php?p=apotik_umum_kasir&start_date='+str;
}
</script>