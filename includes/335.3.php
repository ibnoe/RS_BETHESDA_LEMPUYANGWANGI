<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<script language="JavaScript">
    function reply_click(clicked_id)
    {
        var cek=clicked_id;
        var penj;

        penj=cek.substring(11);

        if(document.getElementById(cek).checked){
            document.getElementById("penjamin_"+penj).style.visibility ="visible";
            document.getElementById("submit_"+penj).style.visibility ="visible";
        }else{
            document.getElementById("penjamin_"+penj).style.visibility ="hidden";
            document.getElementById("submit_"+penj).style.visibility ="hidden";
        }
    }
    function validate(evt) {
        var charCode = ( evt.which ) ? evt.which : event.keyCode;
        if ( charCode > 31 && (charCode < 48 || charCode > 57) ) return false;
        return true;
    }

	function editLayananNonPaket(id_rs08){
		sWin = window.open('popup/edit_layanan_non_paket.php?id='+id_rs08,'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');
        sWin.focus();
		}
	function editLayananPaket(id_rs08){

		sWin = window.open('popup/edit_layanan_paket.php?id='+id_rs08,'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');

        sWin.focus();

		}
	function editAkomodasiRI(id_rs08){
		sWin = window.open('popup/edit_akomodasi_ri.php?id='+id_rs08,'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');
        sWin.focus();
		}
</script>
<form name="form1" method="post" action="<?php echo $PHP_SELF."?p=335&t1=&kas=".$_GET['kas']."&rg=".$_GET['rg']."&sub=3"; ?>">
    <table width="100%">
        <tr>
            <td align="center" class="TBL_HEAD" width="16%">TANGGAL</td>
            <td align="center" class="TBL_HEAD">DESCRIPTION</td>
            <td align="center" class="TBL_HEAD">DOKTER</td>
            <td align="center" class="TBL_HEAD" width="7%">JUMLAH</td>
            <td align="center" class="TBL_HEAD" width="5%">TAGIHAN</td>
            <td align="center" class="TBL_HEAD" width="5%">DISKON (%)</td>
            <td align="center" class="TBL_HEAD" width="5%">DISKON (Rp.)</td>
            <td align="center" class="TBL_HEAD" width="12%" colspan="2">PENJAMIN</td>
            <td align="center" class="TBL_HEAD" width="10%">SELISIH</td>
            <td align="center" class="TBL_HEAD" width="5%">EDIT</td>
			<td align="center" class="TBL_HEAD" width="5%">HAPUS</td>
        </tr>
        <?
        $totalTagihan = 0;
        $totalPenjamin = 0;
        $z = 0;
        $rec = getFromTable("select count(id) from rs00008 " .
                "where trans_type = 'LTM' and to_number(no_reg,'999999999999') = $reg and referensi = 'P'");

        if ($rec > 0) {
            $sqla = "select distinct a.is_bayar,a.id as rs00008id,to_char(a.tanggal_trans,'dd-mm-yyyy') as tanggal_trans, a.waktu_entry as waktu_entry, a.no_reg,b.id, 
upper(b.description) as description, a.qty, a.tagihan, persen, diskon, c.nama
from rs00008 a
left join rs99996 b on to_number(a.item_id,'9999999')=b.id
left join rs00017 c on a.no_kwitansi::numeric = c.id::numeric
where a.referensi ='P' and no_reg='$reg' order by tanggal_trans, waktu_entry, description  ";


            @$r1 = pg_query($con,
                    $sqla);
            @$n1 = pg_num_rows($r1);

            $max_row1 = 200;
            $mulai1 = $HTTP_GET_VARS["rec"];
            if (!$mulai1) {
                $mulai1 = 1;
            }
            ?>
            <tr>
                <td bgcolor="#8ADFD3" align="center">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="left"><b><u>RINCIAN PAKET LAYANAN</u></b></td>
                <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
                <td bgcolor="#8ADFD3" align="center">&nbsp;</td>
		<td bgcolor="#8ADFD3" align="center">&nbsp;</td>
            </tr>
            <?
            // Line 1 Grup layanan paket
            $row1 = 0;
            $tagihan1 = 0;
            $i = 1;
            $j = 1;
            $last_id = 1;
            $index_total1 = 0;
            $total1 = 0;
            while (@$row1 = pg_fetch_array($r1)) {
                if (($j <= $max_row1) AND ($i >= $mulai1)) {
                    $no = $i;
                    $z++;
                    $index_total1++;
                    
		    //var_dump($id_rs00008); die;
		    $tglTrans = $row1["tanggal_trans"];
                    $arrTglTrans = explode('-',$tglTrans);
		    $id_rs00008 = $row1['rs00008id'];
                    $row_penjamin1 = pg_query($con,
                            "select dibayar_penjamin from rs00008 where id ='$id_rs00008'");
                    $rw_penjamin1 = pg_fetch_array($row_penjamin1);
		    $tagihan1 = $row1['tagihan'];
		    $penjamin = $rw_penjamin1['dibayar_penjamin'];
                    $totalTagihan = $totalTagihan + $row1['tagihan'];
                    $totalPenjamin = $totalPenjamin + $rw_penjamin1['dibayar_penjamin']; 
//Perbaikan bug penjamin paket layanan
  if (isset($_POST['add_penjamin_' . $z])) {
					if($_POST["penjamin_persen_" . $z]!='0'){
					$tagihan=getFromTable("select tagihan from rs00008 where id='".$_POST["rs00008_id_" . $z]."' ");
					$_POST["penjamin_" . $z]=$tagihan*($_POST["penjamin_persen_" . $z]/100);
                    pg_query($con,
                            "UPDATE  rs00008 SET dibayar_penjamin = " . $_POST["penjamin_" . $z] . " WHERE id = " . $_POST["rs00008_id_" . $z]);
                    echo "<script>window.location='index2.php?p=335&t1=&kas=" . $_GET['kas'] . "&rg=" . $_GET['rg'] . "&sub=3'</script>";
					}else{
					pg_query($con,
                            "UPDATE  rs00008 SET dibayar_penjamin = " . $_POST["penjamin_" . $z] . " WHERE id = " . $_POST["rs00008_id_" . $z]);
                    echo "<script>window.location='index2.php?p=335&t1=&kas=" . $_GET['kas'] . "&rg=" . $_GET['rg'] . "&sub=3'</script>";
					
					}
				}
                    ?>

                    <tr>
                        <td class="TBL_BODY" align="center"><b><?php echo $arrTglTrans[0] . ' ' . bulan($arrTglTrans[1]) . ' ' . $arrTglTrans[2] ?> &nbsp;&nbsp; <?= date("H:i:s", strtotime($row1["waktu_entry"])) ?></b></td>
                        <td class="TBL_BODY" align="left"><b>PAKET LAYANAN <?= $row1["description"] ?></b></td>
                        <td class="TBL_BODY" align="left"><b><?= $row1["nama"] ?></b></td>
                        <td class="TBL_BODY" align="left"><b><?= $row1["qty"] ?></b></td>
                        <td class="TBL_BODY" align="right" height="20"><b><span id="nilai_tagihan_<?php echo $z ?>"><?= number_format($tagihan1, 0, ",", ".") ?></span></b></td>
                        <td class="TBL_BODY" align="right" height="20"><?= $row1['persen']?></td>
                        <td class="TBL_BODY" align="right" height="20"><?= $row1['diskon']?></td>
			<td class="TBL_BODY" align="right">
                        <input type="checkbox" onClick="showBoxPenjamin(<?php echo $z ?>)" name="ck_penjamin_<?php echo $z ?>" id="ck_penjamin_<?php echo $z ?>" value="<?php echo $id_rs00008 ?>" />
			</td>
			<td class="TBL_BODY" align="right">
                        <span id="nilai_penjamin_<?php echo $z; ?>" style="font-weight: bold; "> <?php echo number_format($penjamin, 0, ",", ".") ?></span>
                        <span id="box_penjamin_<?php echo $z; ?>" style="display:none;">
                            <input type="hidden" name="rs00008_id_<?php echo $z ?>" value="<?php echo $id_rs00008 ?>"/> 
                            <input type="text" maxlength="2" name="penjamin_persen_<?php echo $z ?>" id="penjamin_persen_<?php echo $z ?>" value="<?php echo number_format((($penjamin/$tagihan) * 100), 0, ",", "") ?>"  style="text-align:right" size="2"  />%
                            <input type="text" name="penjamin_<?php echo $z ?>" id="penjamin_<?php echo $z ?>" value="<?php echo number_format($penjamin, 0, ",", "") ?>" style="text-align:right" size="12" /> 
                            <input type="submit" name="add_penjamin_<?php echo $z ?>" id="add_penjamin_<?php echo $z ?>" value="OK" /> 
                        </span>
                    </td>

                        <?php
                        if (isset($_POST['edit_' . $z])) {
                            $sqle = "delete from rs00005_penjamin where id_rs00008 ='$id_rs00008'";
                            $tr = pg_query($con,
                                    $sqle);
                            $jumlah_bayar1[$index_total1] = $tagihan1;
                            echo $z;
                            echo"' id='ck_penjamin";
                            echo $z;
                            echo "' onClick='reply_click(this.id)'></td>";
                            echo "<td class='TBL_BODY'  align='left'><input type='text' name='penjamin_";
                            echo $z;
                            echo"' id='penjamin_";
                            echo $z;
                            echo "' onkeypress='return validate(event);' style='visibility:hidden'>";
                            echo "<input type='submit' name='submit_";
                            echo $z;
                            echo"' id='submit_";
                            echo $z;
                            echo "' value='OK' style='visibility:hidden'>";
                            echo "</td>";
                            ?><td class="TBL_BODY" align="right"><b><?= number_format($jumlah_bayar1[$index_total1],
                        2,
                        ",",
                        ".") ?></b></td>
                            <td class="TBL_BODY" align="right">&nbsp;</td>
                            <?
                        } elseif ($_POST['submit_' . $z] || $rw_penjamin1['dibayar_penjamin'] != 0) {
                            if ($_POST['submit_' . $z]) {
                                $penjamin1 = $_POST['penjamin_' . $z];
                                $sqle = "update rs00008 set dibayar_penjamin ='$penjamin1' where id = '$id_rs00008'";
                                $tr = pg_query($con,
                                        $sqle);
                            } else {
                                $penjamin1 = $rw_penjamin1['dibayar_penjamin'];
                            }
                         
                            $jumlah_bayar1[$index_total1] = $tagihan1 - $penjamin1;
                            ?><td class="TBL_BODY" align="right"><b><?= number_format($jumlah_bayar1[$index_total1],
                        2,
                        ",",
                        ".") ?></b></td>
                <?
            } else {
                $jumlah_bayar1[$index_total1] = $tagihan1;
                ?><td class="TBL_BODY" align="right"><b><?= number_format($jumlah_bayar1[$index_total1],
                        2,
                        ",",
                        ".") ?></b></td>
                <?
            }
            ?>
						<td class="TBL_BODY" align="center"><a href="#" onclick="editLayananPaket(<?=$row1['rs00008id']?>)">EDIT</a></td>
						<td class="TBL_BODY" align="center"><a href="./actions/hapus_tagihan_kasir.php?id=<?php echo $row1['rs00008id'];?>&kas=<?php echo $_GET['kas'];?>&rg=<?php echo $_GET['rg'];?>" onclick="return confirm('HAPUS <?php echo $row1['description'];?> ?')" >HAPUS</a></td>
                    </tr>
            <?
            $total1 = array_sum($jumlah_bayar1);
            // line 2 Rincian oaket Layanan
            $sqlb = "select a.id as id_lay, f.id,z.preset_id, a.layanan, 
				z.qty ||' '|| g.tdesc as qty, f.tagihan,  f.tanggal_trans, f.trans_group, f.waktu_entry as waktu_entry, 
				from rs00034 a 
				left join rs99997 z on z.item_id=a.id and z.trans_type='LYN'
				left join rs00008 f on to_number(f.item_id,'999999999999') = z.preset_id and f.trans_type = 'LTM' and f.referensi='P'
				left join rs00001 g on a.satuan_id = g.tc and g.tt = 'SAT' 
				where z.preset_id = $row1[id] and f.no_reg='$reg' 
				order by  a.id ";
            ?>
                    <tr>
                        <td class="TBL_BODY" align="center">&nbsp;</td>
                        <td class="TBL_BODY" align="left">&nbsp;&nbsp;&nbsp;&nbsp; RINCIAN LAYANAN <?= $row1["description"] ?></td>
                        <td class="TBL_BODY" align="center">&nbsp;</td>
                        <td class="TBL_BODY" align="right">&nbsp;</td>
                        <td class="TBL_BODY" align="left">&nbsp;</td>
                        <td class="TBL_BODY" align="right">&nbsp;</td>
                        <td class="TBL_BODY" align="center">&nbsp;</td>
                        <td class="TBL_BODY" align="left">&nbsp;</td>
                        <td class="TBL_BODY" >&nbsp;</td>
                        <td class="TBL_BODY" >&nbsp;</td>
                        <td class="TBL_BODY" >&nbsp;</td>
                        <td class="TBL_BODY" >&nbsp;</td>
                    </tr>
                    <?
                    @$r2 = pg_query($con,
                            $sqlb);
                    @$n2 = pg_num_rows($r2);

                    $max_row2 = 200;
                    $mulai2 = $HTTP_GET_VARS["rec"];
                    if (!$mulai2) {
                        $mulai2 = 1;
                    }

                    $row2 = 0;
                    $i2 = 1;
                    $j2 = 1;
                    $last_id2 = 1;
                    while (@$row2 = pg_fetch_array($r2)) {
                        if (($j2 <= $max_row2) AND ($i2 >= $mulai2)) {
                            $no2 = $i2;
                            ?>
                            <tr>
                                <td class="TBL_BODY" align="center"><? ?></td>
                                <td class="TBL_BODY" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <?= $row2["layanan"] ?></td>
                                <td class="TBL_BODY" align="right">&nbsp;</td>
                                <td class="TBL_BODY" align="left"><?= $row2["qty"] ?></td>
                                <td class="TBL_BODY" align="right">&nbsp;</td>
                                <td class="TBL_BODY" align="right">&nbsp;</td>
                                <td class="TBL_BODY" align="right">&nbsp;</td>
                                <td class="TBL_BODY" align="right">&nbsp;</td>					
                                <td class="TBL_BODY"  align="right">&nbsp;</td>
                                <td class="TBL_BODY"  align="right">&nbsp;</td>
				<td class="TBL_BODY"  align="right">&nbsp;</td>
                            </tr>
                    <?
                    ;
                    $j2++;
                }

                $i2++;
            }
            // Batas Untuk Line 2
            // line 2 Rincian paket obat
            $sqlc = "select z.item_id,z.preset_id,to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans, a.waktu_entry as waktu_entry,  
				b.obat, z.qty ||' '|| c.tdesc as qty, sum(tagihan) as tagihan, a.pembayaran
				from rs00008 a
				left join rs99997 z on z.preset_id=to_number(a.item_id,'999999999999') and z.trans_type='OBI'
				left join rs00015 b on z.item_id = b.id  
				left join rs00001 c on b.satuan_id = c.tc and c.tt = 'SAT' 
				left join rs00001 d on b.kategori_id = d.tc and d.tt = 'GOB' 
				where to_number(a.no_reg,'999999999999')= $reg  and a.referensi = 'P' and z.preset_id = $row1[id]
				group by  z.preset_id,z.item_id,d.tdesc, a.tanggal_trans, a.id, b.obat, z.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form ";

            @$r3 = pg_query($con,
                    $sqlc);
            @$n3 = pg_num_rows($r3);
            ?>
                    <tr>
                        <td class="TBL_BODY" align="center">&nbsp;</td>
                        <td class="TBL_BODY" align="left">&nbsp;&nbsp;&nbsp;&nbsp;  RINCIAN OBAT <?= $row1["description"] ?></td>
                        <td class="TBL_BODY" align="left">&nbsp;</td>
                        <td class="TBL_BODY" align="left">&nbsp;</td>
                        <td class="TBL_BODY" align="right">&nbsp;</td>
                        <td class="TBL_BODY"  align="center">&nbsp;</td>
                        <td class="TBL_BODY" align="left">&nbsp;</td>
                        <td class="TBL_BODY" align="right">&nbsp;</td>
                        <td class="TBL_BODY" align="right">&nbsp;</td>
                        <td class="TBL_BODY" align="right">&nbsp;</td>
                        <td class="TBL_BODY" align="right">&nbsp;</td>
			<td class="TBL_BODY" align="right">&nbsp;</td>
                    </tr>
                    <?
                    $max_row3 = 200;
                    $mulai3 = $HTTP_GET_VARS["rec"];
                    if (!$mulai3) {
                        $mulai3 = 1;
                    }

                    $row3 = 0;
                    $i3 = 1;
                    $j3 = 1;
                    $last_id3 = 1;
                    while (@$row3 = pg_fetch_array($r3)) {
                        if (($j3 <= $max_row3) AND ($i3 >= $mulai3)) {
                            $no3 = $i3;
                            ?>
                            <tr>
                                <td class="TBL_BODY" align="center"><? ?></td>
                                <td class="TBL_BODY" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <?= $row3["obat"] ?></td>
                                <td class="TBL_BODY" align="right">&nbsp;</td>
                                <td class="TBL_BODY" align="left"><?= $row3["qty"] ?></td>
                                <td class="TBL_BODY" align="right">&nbsp;</td>
                                <td class="TBL_BODY" align="left">&nbsp;</td>
                                <td class="TBL_BODY" align="right">&nbsp;</td>
                                <td class="TBL_BODY"  align="center">&nbsp;</td>
                                <td class="TBL_BODY" align="left">&nbsp;</td>
                                <td class="TBL_BODY" align="left">&nbsp;</td>
                                <td class="TBL_BODY"  align="right">&nbsp;</td>
                                <td class="TBL_BODY"  align="right">&nbsp;</td>
                                <td class="TBL_BODY"  align="right">&nbsp;</td>
				<td class="TBL_BODY"  align="right">&nbsp;</td>
                            </tr>
                            <?php
                            $j3++;
                        }
                        $i3++;
                    }
                    // Batas Untuk Line 3
                    $tagihan = $tagihan + $row1["tagihan"];
                    $j++;
                }
                $i++;
            }
            // Batas Untuk Line 1
        }
        // Akomodasi rawat Inap
        $tgl_masuk = getFromTable("select min(b.ts_check_in) from rsv_akomodasi_inap b where b.no_reg = '$reg'");
        $tgl_pos = getFromTable("select max(d.ts_calc_stop::date) from rsv_akomodasi_inap d where d.no_reg='$reg'");
        /**
        $sqlAkomodasi = pg_query($con,
                "SELECT rsv_akomodasi_inap.qty,rs00008.persen, rs00008.diskon, rsv_akomodasi_inap.bangsal, rsv_akomodasi_inap.bed, rsv_akomodasi_inap.harga_satuan, DATE(rsv_akomodasi_inap.ts_calc_stop) as tgl_keluar, tanggal(rs00008.tanggal_trans,3) AS tgl_trans, rs00008.id AS id_rs00008, rs00008.tagihan , rs00008.dibayar_penjamin  FROM rsv_akomodasi_inap 
		JOIN rs00008 ON rsv_akomodasi_inap.no_reg = rs00008.no_reg
						AND rs00008.qty >= 0.5
						AND rsv_akomodasi_inap.harga_satuan = rs00008.harga
                             WHERE rs00008.qty <> 0 AND rsv_akomodasi_inap.no_reg = '" . $_GET["rg"] . "' 
                             ORDER BY tgl_keluar DESC");
                             */ 
                             
        $rowsAkomodasi = pg_query("SELECT id,tanggal(tanggal_trans,3) AS tgl_trans, tanggal_entry, waktu_entry as waktu_entry, klasifikasi_tarif, bangsal, ruangan,bed, no_reg,nama, qty, diskon, persen, harga, tagihan, 
        dibayar_penjamin FROM rsv_akomodasi_inap_kasir WHERE no_reg='".$_GET['rg']."' ORDER BY tanggal_trans");
        if (pg_num_rows($rowsAkomodasi) > 0) {
            ?>
            <tr>
                <td bgcolor="#8ADFD3"  align="left">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="left"><b><u>TAGIHAN SEWA KAMAR <?= $tgl_masuk ?> s/d <? echo $tgl_pos; ?></u></b></td>
                <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
                <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
		<td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
            </tr>
            <?
            while ($row7 = pg_fetch_array($rowsAkomodasi)) {
                $z++;
                $id_rs00008 = $row7["id_rs00008"];
                $lamaInap = (double) $row7["qty"];
                //$tagihan = $row7["qty"] * $row7["harga_satuan"];
                $penjamin = $row7["dibayar_penjamin"];
                $tglKeluar = $row7["tgl_keluar"];
                $arrTglKeluar = explode('-',
                        $tglKeluar);

                $totalTagihan = $totalTagihan + $row7['tagihan'];
                $totalPenjamin = $totalPenjamin + $row7['dibayar_penjamin'];

                if (isset($_POST['add_penjamin_' . $z])) {
					if($_POST["penjamin_persen_" . $z]!='0'){
					$tagihan=getFromTable("select tagihan from rs00008 where id='".$_POST["rs00008_id_" . $z]."' ");
					$_POST["penjamin_" . $z]=$tagihan*($_POST["penjamin_persen_" . $z]/100);
                    pg_query($con,
                            "UPDATE  rs00008 SET dibayar_penjamin = " . $_POST["penjamin_" . $z] . " WHERE id = " . $_POST["rs00008_id_" . $z]);
                    echo "<script>window.location='index2.php?p=335&t1=&kas=" . $_GET['kas'] . "&rg=" . $_GET['rg'] . "&sub=3'</script>";
					}else{
					pg_query($con,
                            "UPDATE  rs00008 SET dibayar_penjamin = " . $_POST["penjamin_" . $z] . " WHERE id = " . $_POST["rs00008_id_" . $z]);
                    echo "<script>window.location='index2.php?p=335&t1=&kas=" . $_GET['kas'] . "&rg=" . $_GET['rg'] . "&sub=3'</script>";
					
					}
				}
                if ($lamaInap > 0) {
                    ?>
                    <tr>
                        <td class="TBL_BODY" align="center"><b><?php echo $row7["tgl_trans"] ?>&nbsp;<?php echo date("H:i:s", strtotime($row7["waktu_entry"])) ?></b></td>
                        <td class="TBL_BODY" align="left"><?php echo $row7["ruangan"] . ' / ' . $row7["bed"] .' / '. $row7["klasifikasi_tarif"];?></td>
                        <td class="TBL_BODY" align="center">&nbsp;</td>
                        <td class="TBL_BODY" align="left"><?= $row7["qty"] ?> HARI</td>
                        <td class="TBL_BODY" align="right"><b><span id="nilai_tagihan_<?php echo $z ?>"><?= number_format($row7["tagihan"]) ?></span></b></td>                        
                        <td class="TBL_BODY"  align="right"><?= number_format($row7["diskon"]) ?></td>
                        <td class="TBL_BODY"  align="right"><?= number_format($row7["persen"]) ?></td>
                        <td class="TBL_BODY" align="right">
                            <input type="checkbox" onClick="showBoxPenjamin(<?php echo $z ?>)" name="ck_penjamin_<?php echo $z ?>" id="ck_penjamin_<?php echo $z ?>" value="<?php echo $id_rs00008 ?>" />
                        </td>
                        <td class="TBL_BODY" align="right">
                            <span id="nilai_penjamin_<?php echo $z; ?>" style="font-weight: bold; "> <?php echo $penjamin ?></span>
                            <span id="box_penjamin_<?php echo $z; ?>" style="display:none;">
                                <input type="hidden" name="rs00008_id_<?php echo $z ?>" value="<?=$row7['id']?>"/> 
                                <input type="text" maxlength="2" name="penjamin_persen_<?php echo $z ?>" id="penjamin_persen_<?php echo $z ?>" value="<?php echo number_format((($penjamin/$row7["tagihan"]) * 100), 0, ",", "") ?>"  style="text-align:right" size="2"  />%
								<input type="text" name="penjamin_<?php echo $z ?>" id="penjamin_<?php echo $z ?>" value="<?php echo $penjamin ?>" style="text-align:right" size="12" /> 
                                <input type="submit" name="add_penjamin_<?php echo $z ?>" id="add_penjamin_<?php echo $z ?>" value="OK" /> 
                            </span>
                        </td>
                        <td class="TBL_BODY" align="right"><b><?= number_format(($row7["tagihan"] - $penjamin),
                    2,
                    ",",
                    ".") ?></b></td>
                    <td class="TBL_BODY" align="center"><a href="#" onclick="editAkomodasiRI(<?=$row7['id']?>)">EDIT</a></td>
		    <td class="TBL_BODY" align="center"><a href="./actions/hapus_tagihan_kasir.php?ref=sewa_kamar&id=<?php echo $row7['id'];?>&kas=<?php echo $_GET['kas'];?>&rg=<?php echo $_GET['rg'];?>" onclick="return confirm('HAPUS AKOMODASI <?php echo $row7["bangsal"] . ' - ' . $row7["bed"] ?> DENGAN TAGIHAN <?php echo ($row7["tagihan"] - $penjamin);?> ?')" >HAPUS</a></td>
                    </tr>

            <?
        }
    }
    $tagihan7 = $row7["qty"] * $row7["harga_satuan"];
}
// Rincian Layanan Non Paket
$sqle = "select f.id,f.item_id, a.layanan, 
        f.qty ||' '|| g.tdesc as qty, f.tagihan, to_char(f.tanggal_trans,'dd-mm-yyyy') as tanggal_trans, f.waktu_entry as waktu_entry, f.trans_group,
        f.is_bayar, h.nama, f.diskon, f.persen
        from rs00034 a 
        left join rs00008 f on to_number(f.item_id,'999999999999') =
        a.id and f.trans_type = 'LTM' and f.referensi != 'P'
        left join rs00001 g on a.satuan_id = g.tc and g.tt = 'SAT' 
        left join rs00017 h on f.no_kwitansi::numeric = h.id::numeric
        where f.no_reg = '$reg' 
        order by  f.tanggal_trans, f.waktu_entry desc ";

@$r4 = pg_query($con,
        $sqle);
@$n4 = pg_num_rows($r4);

$max_row4 = 200;
$mulai4 = $HTTP_GET_VARS["rec"];
if (!$mulai4) {
    $mulai4 = 1;
}
?>
        <tr>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="left"><b><u>RINCIAN LAYANAN NON PAKET</u></b></td>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
	    <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
        </tr>
        <?
        $row4 = 0;
        $i4 = 1;
        $j4 = 1;
        $last_id4 = 1;
        $index_total4 = 0;
        while (@$row4 = pg_fetch_array($r4)) {
            if (($j4 <= $max_row4) AND ($i4 >= $mulai4)) {
                $no4 = $i4;
                $z++;
                $index_total4++;
                $id_rs00008 = $row4['id'];
                $row_penjamin4 = pg_query($con,
                        "select dibayar_penjamin from rs00008 where id ='$id_rs00008'");
                $rw_penjamin4 = pg_fetch_array($row_penjamin4);
                $tglTrans = $row4["tanggal_trans"];
                $arrTglTrans = explode('-',
                        $tglTrans);
                $tagihan = $row4['tagihan'];
                $penjamin = $rw_penjamin4['dibayar_penjamin'];

                $totalTagihan = $totalTagihan + $row4['tagihan'];
                $totalPenjamin = $totalPenjamin + $rw_penjamin4['dibayar_penjamin'];//penjamin

                
				if (isset($_POST['add_penjamin_' . $z])) {
					if($_POST["penjamin_persen_" . $z]!='0'){
					$tagihan=getFromTable("select tagihan from rs00008 where id='".$_POST["rs00008_id_" . $z]."' ");
					$_POST["penjamin_" . $z]=$tagihan*($_POST["penjamin_persen_" . $z]/100);
                    pg_query($con,
                            "UPDATE  rs00008 SET dibayar_penjamin = " . $_POST["penjamin_" . $z] . " WHERE id = " . $_POST["rs00008_id_" . $z]);
                    echo "<script>window.location='index2.php?p=335&t1=&kas=" . $_GET['kas'] . "&rg=" . $_GET['rg'] . "&sub=3'</script>";
					}else{
					pg_query($con,
                            "UPDATE  rs00008 SET dibayar_penjamin = " . $_POST["penjamin_" . $z] . " WHERE id = " . $_POST["rs00008_id_" . $z]);
                    echo "<script>window.location='index2.php?p=335&t1=&kas=" . $_GET['kas'] . "&rg=" . $_GET['rg'] . "&sub=3'</script>";
					
					}
				}
                ?>
                <tr>
                    <td class="TBL_BODY" align="center" height="20"><b><?php echo $arrTglTrans[0] . ' ' . bulan($arrTglTrans[1]) . ' ' . $arrTglTrans[2] ?> &nbsp;&nbsp; <?= date("H:i:s", strtotime($row4["waktu_entry"])) ?></b></td>
                    <td class="TBL_BODY" align="left" height="20"><?= $row4["layanan"] ?></td>
                    <td class="TBL_BODY" align="left" height="20"><?= $row4["nama"] ?></td>
                    <td class="TBL_BODY" align="left" height="20"><?= $row4["qty"] ?></td>
                    <td class="TBL_BODY" align="right" height="20"><b><span id="nilai_tagihan_<?php echo $z ?>"><?= number_format($tagihan, 0, ",", ".") ?></span></b></td>
                    <td class="TBL_BODY"  align="right"><?= $row4["persen"] ?></td>
                    <td class="TBL_BODY"  align="right"><?= $row4["diskon"] ?></td>
                    <td class="TBL_BODY" align="right">
					
                        <input type="checkbox" onClick="showBoxPenjamin(<?php echo $z ?>)" name="ck_penjamin_<?php echo $z ?>" id="ck_penjamin_<?php echo $z ?>" value="<?php echo $id_rs00008 ?>" />
                    </td>
                    <td class="TBL_BODY" align="right">
                        <span id="nilai_penjamin_<?php echo $z; ?>" style="font-weight: bold; "> <?php echo number_format($penjamin, 0, ",", ".") ?></span>
                        <span id="box_penjamin_<?php echo $z; ?>" style="display:none;">
                            <input type="hidden" name="rs00008_id_<?php echo $z ?>" value="<?php echo $id_rs00008 ?>"/> 
                            <input type="text" maxlength="2" name="penjamin_persen_<?php echo $z ?>" id="penjamin_persen_<?php echo $z ?>" value="<?php echo number_format((($penjamin/$tagihan) * 100), 0, ",", "") ?>"  style="text-align:right" size="2"  />%
                            <input type="text" name="penjamin_<?php echo $z ?>" id="penjamin_<?php echo $z ?>" value="<?php echo number_format($penjamin, 0, ",", "") ?>" style="text-align:right" size="12" /> 
                            <input type="submit" name="add_penjamin_<?php echo $z ?>" id="add_penjamin_<?php echo $z ?>" value="OK" /> 
                        </span>
                    </td>
                    <td class="TBL_BODY" align="right"><b><?= number_format(($tagihan - $penjamin),
                2,
                ",",
                ".") ?></b></td>
					<td class="TBL_BODY" align="center"><a href="#" onclick="editLayananNonPaket(<?=$row4['id']?>)">EDIT</a></td>
					<td class="TBL_BODY" align="center"><a href="./actions/hapus_tagihan_kasir.php?id=<?php echo $row4['id'];?>&kas=<?php echo $_GET['kas'];?>&rg=<?php echo $_GET['rg'];?>" onclick="return confirm('HAPUS LAYANAN <?php echo $row4["layanan"] ?> DENGAN TAGIHAN <?php echo ($tagihan - $penjamin);?> ?')" >HAPUS</a></td>
                </tr>
        <?
        ;
        $j4++;
    }

    $i4++;
}
?>
        <?
        //Batas Layanan Non Paket
// Pembelian BHP
        $sqlfa = "select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans,  a.waktu_entry as waktu_entry,
		obat, qty ||' '|| c.tdesc as qty, sum(tagihan) as tagihan, pembayaran, trans_group, d.tdesc as kategori, a.trans_form , a.persen, a.diskon
		from rs00008 a, rs00015 b, rs00001 c, rs00001 d 
		where to_number(a.item_id,'999999999999') = b.id  
		and b.satuan_id = c.tc and a.trans_type = 'BHP' 
		and c.tt = 'SAT' 
		and b.kategori_id = d.tc and d.tt = 'GOB' 
		and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'
		group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form ";
        @$r5a = pg_query($con,
                $sqlfa);
        @$n5a = pg_num_rows($r5a);

        $max_row5a = 200;
        $mulai5a = $HTTP_GET_VARS["rec"];
        if (!$mulai5a) {
            $mulai5a = 1;
        }
        ?>
        <tr>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="left"><b><u>RINCIAN BHP RUANGAN</u></b></td>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
	    <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
	    <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
	    <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
	    <td bgcolor="#8ADFD3"  align="right">&nbsp;</td>
        </tr>
<?
$row5a = 0;
$tagihan5a = 0;
$i5a = 1;
$j5a = 1;
$last_id5a = 1;
$index_total5a = 0;
while (@$row5a = pg_fetch_array($r5a)) {
    if (($j5a <= $max_row5a) AND ($i5a >= $mulai5a)) {
        $no5 = $i5a;
        $z++;
        $index_total5a++;
        $id_rs00008 = $row5a['id'];
        $row_penjamin5a = pg_query($con,
                "select dibayar_penjamin from rs00008 where id ='$id_rs00008'");
        $rw_penjamin5a = pg_fetch_array($row_penjamin5a);
        $tglTrans = $row5a["tanggal_trans"];
        $arrTglTrans = explode('-',$tglTrans);
        $tagihan = $row5a['tagihan'];
        $penjamin = $rw_penjamin5a['dibayar_penjamin'];

        $totalTagihan = $totalTagihan + $tagihan;
        $totalPenjamin = $totalPenjamin + $penjamin;
        if (isset($_POST['add_penjamin_' . $z])) {
					if($_POST["penjamin_persen_" . $z]!='0'){
					$tagihan=getFromTable("select tagihan from rs00008 where id='".$_POST["rs00008_id_" . $z]."' ");
					$_POST["penjamin_" . $z]=$tagihan*($_POST["penjamin_persen_" . $z]/100);
                    pg_query($con,
                            "UPDATE  rs00008 SET dibayar_penjamin = " . $_POST["penjamin_" . $z] . " WHERE id = " . $_POST["rs00008_id_" . $z]);
                    echo "<script>window.location='index2.php?p=335&t1=&kas=" . $_GET['kas'] . "&rg=" . $_GET['rg'] . "&sub=3'</script>";
					}else{
					pg_query($con,
                            "UPDATE  rs00008 SET dibayar_penjamin = " . $_POST["penjamin_" . $z] . " WHERE id = " . $_POST["rs00008_id_" . $z]);
                    echo "<script>window.location='index2.php?p=335&t1=&kas=" . $_GET['kas'] . "&rg=" . $_GET['rg'] . "&sub=3'</script>";
					
					}
				}
        ?>
                <tr>
                    <td class="TBL_BODY" align="center" height="20"><b><?php echo $arrTglTrans[0] . ' ' . bulan($arrTglTrans[1]) . ' ' . $arrTglTrans[2] ?> &nbsp;&nbsp; <?= date("H:i:s", strtotime($row5a["waktu_entry"])) ?></b></td>
                    <td class="TBL_BODY" align="left" height="20"><?= $row5a["obat"] ?></td>
                    <td class="TBL_BODY" align="center" height="20">&nbsp;</td>
                    <td class="TBL_BODY" align="left" height="20"><?= $row5a["qty"] ?></td>
                    <td class="TBL_BODY" align="right" height="20"><b><span id="nilai_tagihan_<?php echo $z ?>"><?= number_format($tagihan, 0, "", ".") ?></span></b></td>
                    <td class="TBL_BODY"  align="right"><?= number_format($row5a["persen"],2, ",",".") ?></td>
                    <td class="TBL_BODY"  align="right"><?= number_format($row5a["diskon"],2, ",",".") ?></td>
                    <td class="TBL_BODY" align="right">
                        <input type="checkbox" onClick="showBoxPenjamin(<?php echo $z ?>)" name="ck_penjamin_<?php echo $z ?>" id="ck_penjamin_<?php echo $z ?>" value="<?php echo $id_rs00008 ?>" />
                    </td>
                    <td class="TBL_BODY" align="right">
                        <span id="nilai_penjamin_<?php echo $z; ?>" style="font-weight: bold; "><?php echo number_format($penjamin, 0, '','.') ?></span>
                        <span id="box_penjamin_<?php echo $z; ?>" style="display:none;">
                            <input type="hidden" name="rs00008_id_<?php echo $z ?>" value="<?php echo $id_rs00008 ?>"/> 
                            <input type="text" maxlength="2" name="penjamin_persen_<?php echo $z ?>" id="penjamin_persen_<?php echo $z ?>" value="<?php echo number_format((($penjamin/$tagihan) * 100), 0, ",", "") ?>"  style="text-align:right" size="2"  />%
                            <input type="text" name="penjamin_<?php echo $z ?>" id="penjamin_<?php echo $z ?>" value="<?php echo number_format($penjamin,0,'','') ?>" style="text-align:right" size="12" /> 
                            <input type="submit" name="add_penjamin_<?php echo $z ?>" id="add_penjamin_<?php echo $z ?>" value="OK" /> 
                        </span>
                    </td>
                    <td class="TBL_BODY" align="right"><b><?= number_format(($tagihan - $penjamin),
                2,
                ",",
                ".") ?></b></td>
					<td class="TBL_BODY">&nbsp;</td>
					<td class="TBL_BODY">&nbsp;</td>
                </tr>
        <?
        $tagihan5a = $tagihan5a + $row5a["tagihan"];

        ;
        $j5a++;
    }

    $i5a++;
}
?>
			
        <tr>
            <td bgcolor="#8ADFD3"  align="center">&nbsp;</td>
            <td bgcolor="#8ADFD3"  align="left" colspan="11"><b><u>RINCIAN OBAT APOTEK</u></b></td>
        </tr>
<?php
$rowsPemakaianObat = pg_query($con,
        "SELECT id, tanggal_entry, waktu_entry as waktu_entry, item_id, qty, tagihan, referensi, dibayar_penjamin, persen, diskon  
                             FROM rs00008 
                             WHERE trans_type = 'OB1' AND rs00008.no_reg = '" . $_GET["rg"] . "' ORDER BY tanggal_entry, waktu_entry ASC");
$rowsPemakaianRacikan = pg_query($con,
        "SELECT id, tanggal_entry, waktu_entry as waktu_entry, item_id, qty, tagihan, referensi, dibayar_penjamin , persen, diskon 
                             FROM rs00008 
                             WHERE trans_type = 'RCK' AND rs00008.no_reg = '" . $_GET["rg"] . "' ORDER BY tanggal_entry, waktu_entry ASC");
$rowsObatReturn = pg_query($con,
        "SELECT id, tanggal_entry, waktu_entry as waktu_entry, item_id, qty_return as qty, tagihan, referensi, dibayar_penjamin 
                             FROM rs00008_return 
                             WHERE (trans_type = 'RCK' OR trans_type = 'OB1') AND no_reg = '" . $_GET["rg"] . "' ORDER BY tanggal_entry, waktu_entry ASC");

if (pg_num_rows($rowsPemakaianObat) > 0) {
    echo '<tr><td bgcolor="#8ADFD3">&nbsp;</td>';
    echo '<td bgcolor="#8ADFD3" colspan="11"  align="left"><b><u>OBAT RESEP</u></b></td></tr>';
    while ($row = pg_fetch_array($rowsPemakaianObat)) {

        $sqlObat = pg_query($con,
                "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                                        FROM rs00015 
                                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = " . $row["item_id"]);
        $obat = pg_fetch_array($sqlObat);

        $totalTagihan = $totalTagihan + $row["tagihan"];
        $totalPenjamin = $totalPenjamin + $row['dibayar_penjamin'];
        ?>
                <tr>
                    <td class="TBL_BODY" align="left">&nbsp;<b><?= tanggal($row["tanggal_entry"]) ?> &nbsp;&nbsp; <?= date("H:i:s", strtotime($row["waktu_entry"])) ?></b></td>
                    <td class="TBL_BODY" align="left">&nbsp;<?= $obat["obat"] ?></td>
                    <td class="TBL_BODY" align="left">&nbsp; </td>
                    <td class="TBL_BODY" align="left"><?= $row["qty"] ?> <?= $obat["satuan"] ?></td>
                    <td class="TBL_BODY" align="right" style="font-weight: bold;"><?= number_format($row["tagihan"],'0', '', '.') ?></td>
                    <td class="TBL_BODY" align="right"><?= number_format($row["persen"],'0', ",",".") ?></td>
                    <td class="TBL_BODY" align="right"><?= number_format($row["diskon"],'0', ",",".") ?></td>
                    <td class="TBL_BODY" align="left">&nbsp; </td>
                    <td class="TBL_BODY" align="right" style="font-weight: bold;"><?= number_format($row["dibayar_penjamin"],'0', '', '.') ?></td>
                    <td class="TBL_BODY" align="right" style="font-weight: bold;"><?= number_format($row["tagihan"] - $row["dibayar_penjamin"],'0', '', '.') ?></td>
                    <td class="TBL_BODY"  align="right">&nbsp;</td>
		    <td class="TBL_BODY"  align="right">&nbsp;</td>
                </tr>
        <?php
    }
}
?>

<?php
if (pg_num_rows($rowsPemakaianRacikan) > 0) {
    echo '<tr><td bgcolor="#8ADFD3">&nbsp;</td>';
    echo '<td bgcolor="#8ADFD3" colspan="11"  align="left"><b><u>OBAT RACIKAN</u></b></td></tr>';
    while ($rowRacikan = pg_fetch_array($rowsPemakaianRacikan)) {

        $sqlObatR = pg_query($con,
                "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                                        FROM rs00015 
                                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = " . $rowRacikan["item_id"]);
        $obatR = pg_fetch_array($sqlObatR);

        $totalTagihan = $totalTagihan + $rowRacikan["tagihan"];
        $totalPenjamin = $totalPenjamin + $rowRacikan['dibayar_penjamin'];
        ?>
                <tr>
                    <td class="TBL_BODY" align="left">&nbsp; <b><?= tanggal($rowRacikan["tanggal_entry"]) ?> &nbsp;&nbsp; <?= date("H:i:s", strtotime($rowRacikan["waktu_entry"])) ?></b></td>
                    <td class="TBL_BODY" align="left">&nbsp; <?= $obatR["obat"] ?></td>
                    <td class="TBL_BODY" align="left">&nbsp;</td>
                    <td class="TBL_BODY" align="left"><?= $rowRacikan["qty"] ?> <?= $obatR["satuan"] ?></td>
                    <td class="TBL_BODY" align="right" style="font-weight: bold;"><?= number_format($rowRacikan["tagihan"],'0', '', '.') ?></td>
                    <td class="TBL_BODY" align="right"><?= $rowRacikan["persen"] ?></td>
                    <td class="TBL_BODY" align="right"><?= $rowRacikan["diskon"] ?></td>
                    <td class="TBL_BODY" align="right">&nbsp;</td>
                    <td class="TBL_BODY" align="right" style="font-weight: bold;"><?= number_format($rowRacikan["dibayar_penjamin"],'0', '', '.') ?></td>
                    <td class="TBL_BODY" align="right" style="font-weight: bold;"><?= number_format($rowRacikan["tagihan"] - $rowRacikan["dibayar_penjamin"],'0', '', '.') ?></td>
                    <td class="TBL_BODY" align="right">&nbsp;</td>
                    <td class="TBL_BODY" align="right">&nbsp;</td>
                </tr>
        <?php
    }
}
?>

        <tr>
            <td class="TBL_BODY" align="right" colspan="12">&nbsp;</td>
        </tr>
        <tr>
            <td class="TBL_BODY" align="left" bgcolor="#8ADFD3">&nbsp; </td>
            <td class="TBL_BODY" align="left" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;" colspan="3">TOTAL TRANSAKSI</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3">&nbsp;</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3">
			
			<?php //Penjamin Keseluruhan 
			 if (isset($_POST['add_penjamin_'])) {
			 /**
			 echo "<pre>";
			 $tagihan=getFromTable("select sum(tagihan) from rs00008 where no_reg='".$_POST["rs00008_id_"]."' ");
			 echo $tagihan;
			 echo "<br>";echo "<br>";
			 echo "UPDATE  rs00008 SET dibayar_penjamin = (".$_POST["penjamin_persen_"]."/100)*tagihan WHERE no_reg = '" . $_POST["rs00008_id_"]."' and tagihan !='0.00'";
			 echo "<br>";echo "<br>";
			 $tagihan=getFromTable("select sum(tagihan) from rs00008 where no_reg='".$_POST["rs00008_id_"]."' ");
					$persen=($_POST["penjamin_"]/$tagihan)*100;
			 echo "UPDATE  rs00008 SET dibayar_penjamin = (".$persen."/100)*tagihan WHERE no_reg = " . $_POST["rs00008_id_"]." and tagihan !='0.00'";
			 echo "<br>";echo "<br>";
			 var_dump($_POST);
			 die;
			 **/
					if($_POST["penjamin_persen_"]!='0'){
					//$tagihan=getFromTable("select sum(tagihan) from rs00008 where no_reg='".$_POST["rs00008_id_"]."' ");
					//$_POST["penjamin_"]=$tagihan*($_POST["penjamin_persen_"]/100);
                    $persen=$_POST["penjamin_persen_"]/100;
                    pg_query($con,"UPDATE  rs00008 SET dibayar_penjamin = ".$persen."*tagihan WHERE no_reg = '" . $_POST["rs00008_id_"]."' and tagihan !='0.00'");
					//echo  "UPDATE  rs00008 SET dibayar_penjamin = $persen*tagihan WHERE no_reg = '" . $_POST["rs00008_id_"]."' and tagihan !='0.00'";
                    //die;
					echo "<script>window.location='index2.php?p=335&t1=&kas=" . $_GET['kas'] . "&rg=" . $_GET['rg'] . "&sub=3'</script>";
					}else{
					$tagihan=getFromTable("select sum(tagihan) from rs00008 where no_reg='".$_POST["rs00008_id_"]."' ");
					$persen=($_POST["penjamin_"]/$tagihan)*100;
					pg_query($con,"UPDATE  rs00008 SET dibayar_penjamin = (".$persen."/100)*tagihan WHERE no_reg = '" . $_POST["rs00008_id_"]."' and tagihan !='0.00'");
					//die;
                    echo "<script>window.location='index2.php?p=335&t1=&kas=" . $_GET['kas'] . "&rg=" . $_GET['rg'] . "&sub=3'</script>";
					
					}
				}
        ?>
			 <input type="checkbox" onClick="showBoxPenjaminTotal()" name="ck_penjamin_" id="ck_penjamin_" value="<?php echo $_GET["rg"] ?>" />
                    </td>
                    <td class="TBL_BODY" align="right">
            <span id="nilai_penjamin_" style="font-weight: bold; "> <?php echo number_format($totalTagihan, 0, ",", ".") ?></span>
            <span id="box_penjamin_" style="display:none;">
            <input type="hidden" name="rs00008_id_" value="<?php echo $_GET["rg"] ?>"/> 
                            <input type="text" maxlength="2" name="penjamin_persen_" id="penjamin_persen_" value="<?php echo number_format((($totalPenjamin/$totalTagihan) * 100), 0, ",", "") ?>"  style="text-align:right" size="2"  />%
                            <input type="text" name="penjamin_" id="penjamin_" value="<?php echo number_format($totalTagihan, 0, ",", "") ?>" style="text-align:right" size="12" /> 
                            <input type="submit" name="add_penjamin_" id="add_penjamin_" value="OK" /> 
                        </span>
						</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;"><?php echo number_format($totalTagihan,'0', '', '.') ?></td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3">&nbsp;</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;"><?php echo number_format($totalPenjamin,'0', '', '.') ?></td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;"><?php echo number_format($totalTagihan - $totalPenjamin,'0', '', '.') ?></td>
            <td class="TBL_BODY" align="left" bgcolor="#8ADFD3">&nbsp; </td>
	    <td class="TBL_BODY" align="left" bgcolor="#8ADFD3">&nbsp; </td>
        </tr>
        <tr>
            <td class="TBL_BODY" align="right" colspan="12">&nbsp;</td>
        </tr>
        
<?php
if (pg_num_rows($rowsObatReturn) > 0) {
    echo '<tr><td bgcolor="#8ADFD3">&nbsp;</td>';
    echo '<td bgcolor="#8ADFD3" colspan="11"  align="left"><b><u>OBAT RETURN</u></b></td></tr>';
    while ($rowReturn = pg_fetch_array($rowsObatReturn)) {

        $sqlObatR = pg_query($con,
                "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                                        FROM rs00015 
                                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = " . $rowReturn["item_id"]);
        $obatR = pg_fetch_array($sqlObatR);
        
        $totalTagihanReturn = $totalTagihanReturn + $rowReturn["tagihan"];
        $totalPenjaminReturn = $totalPenjaminReturn + $rowReturn['dibayar_penjamin'];
        ?>
                <tr>
                    <td class="TBL_BODY" align="left">&nbsp; <b><?= tanggal($rowReturn["tanggal_entry"]) ?> &nbsp;&nbsp; <?= date("H:i:s", strtotime($rowReturn["waktu_entry"])) ?></b></td>
                    <td class="TBL_BODY" align="left">&nbsp; <?= $obatR["obat"] ?></td>
                    <td class="TBL_BODY" align="left">&nbsp;</td>
                    <td class="TBL_BODY" align="left"><?= $rowReturn["qty"] ?> <?= $obatR["satuan"] ?></td>
                    <td class="TBL_BODY" align="right" style="font-weight: bold;"><?= number_format($rowReturn["tagihan"],'0', '', '.') ?></td>
                    <td class="TBL_BODY" align="right">&nbsp;</td>
                    <td class="TBL_BODY" align="right">&nbsp;</td>
                    <td class="TBL_BODY" align="right">&nbsp;</td>
                    <td class="TBL_BODY" align="right" style="font-weight: bold;"><?= number_format($rowReturn["dibayar_penjamin"],'0', '', '.') ?></td>
                    <td class="TBL_BODY" align="right" style="font-weight: bold;"><?= number_format($rowReturn["tagihan"] - $rowReturn["dibayar_penjamin"],'0', '', '.') ?></td>
                    <td class="TBL_BODY" align="left" >&nbsp; </td>
		    <td class="TBL_BODY" align="left" >&nbsp; </td>
                </tr>
        <?php
    }
?>
        <tr>
            <td class="TBL_BODY" align="left"  bgcolor="#8ADFD3">&nbsp; </td>
            <td class="TBL_BODY" align="left"  bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;" colspan="3">TOTAL RETURN</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;"><?php echo number_format($totalTagihanReturn,'0', '', '.') ?></td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3">&nbsp;</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3">&nbsp;</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3">&nbsp;</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;"><?php echo number_format($totalPenjaminReturn,'0', '', '.') ?></td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;"><?php echo number_format($totalTagihanReturn - $totalPenjaminReturn,'0', '', '.') ?></td>
            <td class="TBL_BODY" align="left"  bgcolor="#8ADFD3">&nbsp; </td>
	    <td class="TBL_BODY" align="left"  bgcolor="#8ADFD3">&nbsp; </td>
        </tr>
<?php        
}
?>
        <tr>
            <td class="TBL_BODY" align="right" colspan="13">&nbsp;</td>
        </tr>
        <tr>
            <td class="TBL_BODY" align="left" bgcolor="#8ADFD3">&nbsp; </td>
            <td class="TBL_BODY" align="left" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;" colspan="3">GRAND TOTAL</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3">&nbsp;</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3">&nbsp;</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;"><?php echo number_format($totalTagihan-$totalTagihanReturn,'0', '', '.') ?></td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3">&nbsp;</td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;"><?php echo number_format($totalPenjamin-$totalPenjaminReturn,'0', '', '.') ?></td>
            <td class="TBL_BODY" align="right" bgcolor="#8ADFD3" style="font-size: 14px;font-weight: bold;"><?php echo number_format(($totalTagihan-$totalTagihanReturn) - ($totalPenjamin-$totalPenjaminReturn),'0', '', '.') ?></td>
            <td class="TBL_BODY" align="left" bgcolor="#8ADFD3">&nbsp; </td>
	    <td class="TBL_BODY" align="left" bgcolor="#8ADFD3">&nbsp; </td>
        </tr>
                </table>
</form>
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
<script language='JavaScript'>
    
    function cetakcopyresep(tag) {
        sWin = window.open('includes/cetak.copy_resep.php?rg=' + tag+'&kas=ri', 'xWin', 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');
        sWin.focus();
    }

    function showBoxPenjamin(i){
        var isChecked = $('#ck_penjamin_'+i).is(':checked');
        if( isChecked ==  true){
            $('#nilai_penjamin_'+i).hide();
            $('#box_penjamin_'+i).show();
			
            var nilaiPenjamin = $('#nilai_penjamin_'+i).text().replace('.', '');
            var nilaiTagihan = $('#nilai_tagihan_'+i).text().replace('.', '');
            var nilaiTagihan2 = ((nilaiTagihan/nilaiPenjamin) * 100);
            
            if(parseInt(nilaiPenjamin) == false){
                nilaiPenjamin = 0;
            }
            
            nilaiPenjaminInt = parseFloat(nilaiPenjamin);
            nilaiTagihanInt  = parseFloat(nilaiTagihan);
            nilaiTagihan2Int  = parseFloat(nilaiTagihan2);
            
            if( parseInt(nilaiPenjamin) == 0){
                $('#penjamin_'+i).val(nilaiTagihan.replace('.', ''));
                $('#penjamin_persen_'+i).val(nilaiTagihan2.replace('.', ''));
            }            
        }else{
            $('#box_penjamin_persen'+i).hide();
            $('#box_penjamin_'+i).hide();
            $('#nilai_penjamin_'+i).show();
        }
    }
    function showBoxPenjaminTotal(){
        var isChecked = $('#ck_penjamin_').is(':checked');
        if( isChecked ==  true){
            $('#nilai_penjamin_').hide();
            $('#box_penjamin_').show();
			
            var nilaiPenjamin = $('#nilai_penjamin_').text().replace('.', '');
            var nilaiTagihan = $('#nilai_tagihan_').text().replace('.', '');
            var nilaiTagihan2 = ((nilaiTagihan/nilaiPenjamin) * 100);
            
            if(parseInt(nilaiPenjamin) == false){
                nilaiPenjamin = 0;
            }
            
            nilaiPenjaminInt = parseFloat(nilaiPenjamin);
            nilaiTagihanInt  = parseFloat(nilaiTagihan);
            nilaiTagihan2Int  = parseFloat(nilaiTagihan2);
            
            if( parseInt(nilaiPenjamin) == 0){
                $('#penjamin_').val(nilaiTagihan.replace('.', ''));
                $('#penjamin_persen_').val(nilaiTagihan2.replace('.', ''));
            }            
        }else{
            $('#box_penjamin_persen').hide();
            $('#box_penjamin_').hide();
            $('#nilai_penjamin_').show();
        }
    }
</script>

<?php

function tanggal($tanggal) {
    $arrTanggal = explode('-',
            $tanggal);

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
