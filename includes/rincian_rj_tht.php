<? // Agung Sunandar , Menampilkan lap. Buku Besar Klinik


$PID = "rincian_rj";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

		
?>
<table width="100%">
	<tr>
		<td align="center" class="TBL_JUDUL">RSUD dr. ACHMAD MOCHTAR BUKITTINGGI</td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL">RINCIAN PENERIMAAN RAWAT JALAN</td>
	</tr>
</table>

<br>
<br>
<TABLE BORDER="0" CLASS="TBL_BORDER">
    <?
    $row1=0;
	$i= 1 ;
	$j= 1 ;
	$last_id=1;
	while (@$row1 = pg_fetch_array($r1)){
              if (($j<=$max_row1) AND ($i >= $mulai1)){
              $no=$i;
	?>

            <tr>
                <td  class="TBL_HEAD8" colspan="45" align="left"><b><?=$row1["poli"] ?> - <?=$row1["tdesc"] ?></b></td>
            </tr>
              <tr>
                <td class="TBL_HEAD" rowspan="2"><div align="center">NO</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TGL. LUNAS</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TGL. KWITANSI </div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">&nbsp;&nbsp;&nbsp;&nbsp;NAMA&nbsp;&nbsp;&nbsp;&nbsp;PASIEN&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">NO. MR </div></td>
                <td class="TBL_HEAD" colspan="4"><div align="center">KARCIS</div></td>
                <td class="TBL_HEAD" colspan="3"><div align="center">KONSUL</div></td>
                
                <td class="TBL_HEAD" colspan="6"><div align="center">TINDAKAN MEDIK BEDAH</div></td>
                <td class="TBL_HEAD" colspan="8"><div align="center">TINDAKAN MEDIK NON BEDAH</div></td>
                <td class="TBL_HEAD" colspan="6"><div align="center">PENUNJANG</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TOTAL</div></td>
                
              </tr>

              <tr>
                <td class="TBL_HEAD" ><div align="center">KARCIS UMUM </div></td>
                <td class="TBL_HEAD"><div align="center">KARCIS SPESIALIS </div></td>
                <td class="TBL_HEAD"><div align="center">STATUS (MR) </div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
                <td class="TBL_HEAD"><div align="center">DOKTER SPESIALIS </div></td>
                <td class="TBL_HEAD"><div align="center">DOKTER UMUM </div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>

				<td class="TBL_HEAD"><div align="center">BEDAH KELAS&nbsp;I</div></td>
                <td class="TBL_HEAD"><div align="center">BEDAH KELAS&nbsp;II</div></td>
                <td class="TBL_HEAD"><div align="center">BEDAH KELAS&nbsp;III</div></td>
                <td class="TBL_HEAD"><div align="center">BEDAH KELAS&nbsp;UTAMA</div></td>
                <td class="TBL_HEAD"><div align="center">BEDAH KELAS&nbsp;VIP</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
                <td class="TBL_HEAD"><div align="center">PELAYANAN RAWAT&nbsp;JALAN</div></td>
                <td class="TBL_HEAD"><div align="center">SMF KELAS&nbsp;I</div></td>
                <td class="TBL_HEAD"><div align="center">SMF KELAS&nbsp;II</div></td>
                <td class="TBL_HEAD"><div align="center">SMF KELAS&nbsp;III</div></td>
                <td class="TBL_HEAD"><div align="center">SMF KELAS&nbsp;UTAMA</div></td>
                <td class="TBL_HEAD"><div align="center">SMF KELAS&nbsp;VIP</div></td>
                <td class="TBL_HEAD"><div align="center">PELAYANAN LAIN-LAIN</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
                <td class="TBL_HEAD"><div align="center">RADIOLOGI</div></td>
                <td class="TBL_HEAD"><div align="center">LAB. KLINIK</div></td>
                <td class="TBL_HEAD"><div align="center">LAB.&nbsp;PA</div></td>
                <td class="TBL_HEAD"><div align="center">REHABILITASI MEDIK</div></td>
                <td class="TBL_HEAD"><div align="center">INSTALASI</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
                </tr>

		<?
				$sql7a = "select to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,tgl_keluar from rsv_layanan_tht2 where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by tgl_keluar order by tgl_keluar asc";
				@$r7a = pg_query($con,$sql7a);
				@$n7a = pg_num_rows($r7a);

				$max_row7a= 9999999 ;
				$mulai7a = $HTTP_GET_VARS["rec"] ;
				if (!$mulai7a){$mulai7a=1;}
				
				
				$i7a= 1 ;
				$j7a= 1 ;
				$last_id7a=1;
				while (@$row7a = pg_fetch_array($r7a)){
					  if (($j7a<=$max_row7a) AND ($i7a >= $mulai7a)){

						 $no7a=$i7a;
		?>
         <?	
			$sql7 = "select poli,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_kwitansi, nama,mr_no,
                    karcis_umum, radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,bedah_tht_1,bedah_tht_2,bedah_tht_3,bedah_tht_utama,
                    bedah_tht_vip,total_bedah,rj_igd_tht, smf_tht_1,smf_tht_2,smf_tht_3,smf_tht_utama,
                    smf_tht_vip,smf_tht_lain, jumlah, (karcis_umum + jumlah + total_bedah + jumlah_penunjang) as total
                    from rsv_layanan_tht2
                    where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,total_bedah,poli,tgl_keluar, nama,mr_no, karcis_umum, bedah_tht_1,bedah_tht_2,bedah_tht_3,bedah_tht_utama,
                    bedah_tht_vip,rj_igd_tht, smf_tht_1,smf_tht_2,smf_tht_3,smf_tht_utama,smf_tht_vip,smf_tht_lain,jumlah
                    order by tgl_keluar asc";

			@$r7 = pg_query($con,$sql7);
            @$n7 = pg_num_rows($r7);

            $max_row7= 9999999 ;
            $mulai7 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai7){$mulai7=1;}
				
							$row7=0;
							$i7= 1 ;
							$j7= 1 ;
							$last_id7=1;
							while (@$row7 = pg_fetch_array($r7)){
								  if (($j7<=$max_row7) AND ($i7 >= $mulai7)){

									 $no7=$i7;
								  if($row7["poli"]==$row1["poli"] and $row7["tgl_lunas"]==$row7a["tgl_lunas"]) {
								  if($row7["poli"]=="106"){
								  ?>
            <tr>
                <td class="TBL_BODY" align="center"><?=$no7 ?></td>
                <td class="TBL_BODY" align="center"><?=$row7["tgl_lunas"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row7["tgl_kwitansi"] ?></td>
                <td class="TBL_BODY" align="left"><?=$row7["nama"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row7["mr_no"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["karcis_umum"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["karcis_umum"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row7["bedah_tht_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["bedah_tht_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["bedah_tht_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["bedah_tht_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["bedah_tht_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["total_bedah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row7["rj_igd_tht"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["smf_tht_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["smf_tht_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["smf_tht_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["smf_tht_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["smf_tht_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["smf_tht_lain"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["jumlah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row7["radiologi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["lab_klinik"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["lab_pa"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["rehab"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["instalasi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row7["jumlah_penunjang"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row7["total"],2,",",".") ?></td>
            </tr>
			
			
			<?
			}
			$karcis_1=$karcis_1 + $row7["karcis_umum"];
			$karcis_total=$karcis_total + $row7["karcis_umum"];
			
            $bedah_1=$bedah_1 + $row7["bedah_tht_1"];
            $bedah_2=$bedah_2 + $row7["bedah_tht_2"];
            $bedah_3=$bedah_3 + $row7["bedah_tht_3"];
            $bedah_4=$bedah_4 + $row7["bedah_tht_utama"];
            $bedah_5=$bedah_5 + $row7["bedah_tht_vip"];
            $bedah_total=$bedah_total + $row7["total_bedah"];
            $rj_igd=$rj_igd + $row7["rj_igd_tht"];
            $smf_1=$smf_1 + $row7["smf_tht_1"];
            $smf_2=$smf_2 + $row7["smf_tht_2"];
            $smf_3=$smf_3 + $row7["smf_tht_3"];
            $smf_4=$smf_4 + $row7["smf_tht_utama"];
            $smf_5=$smf_5 + $row7["smf_tht_vip"];
			$smf_6=$smf_6 + $row7["smf_tht_lain"];
            $smf_total=$smf_total + $row7["jumlah"];
            $penunjang_1=$penunjang_1 + $row7["radiologi"];
            $penunjang_2=$penunjang_2 + $row7["lab_klinik"];
            $penunjang_3=$penunjang_3 + $row7["lab_pa"];
            $penunjang_4=$penunjang_4 + $row7["rehab"];
            $penunjang_5=$penunjang_5 + $row7["instalasi"];
            $penunjang_total=$penunjang_total + $row7["jumlah_penunjang"];
            $total=$total + $row7["total"];
			

				;$j7++;}

          $i7++;}
        }	
		
				  $karcis_umum = getFromTable("select sum(karcis_umum) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $bedah_1_ = getFromTable("select sum(bedah_tht_1) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $bedah_2_ = getFromTable("select sum(bedah_tht_2) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $bedah_3_ = getFromTable("select sum(bedah_tht_3) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $bedah_4_ = getFromTable("select sum(bedah_tht_utama) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $bedah_5_ = getFromTable("select sum(bedah_tht_vip) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $bedah_total_ = getFromTable("select sum(total_bedah) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $rj_igd_ = getFromTable("select sum(rj_igd_tht) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $smf_1_ = getFromTable("select sum(smf_tht_1) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $smf_2_ = getFromTable("select sum(smf_tht_2) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $smf_3_ = getFromTable("select sum(smf_tht_3) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $smf_4_ = getFromTable("select sum(smf_tht_utama) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $smf_5_ = getFromTable("select sum(smf_tht_vip) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $smf_6_ = getFromTable("select sum(smf_tht_lain) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $smf_total_ = getFromTable("select sum(jumlah) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $penunjang_1_ = getFromTable("select sum(radiologi) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $penunjang_2_ = getFromTable("select sum(lab_klinik) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $penunjang_3_ = getFromTable("select sum(lab_pa) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $penunjang_4_ = getFromTable("select sum(rehab) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $penunjang_5_ = getFromTable("select sum(instalasi) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $penunjang_total_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'");
				  $total_ = getFromTable("select sum(karcis_umum + jumlah + total_bedah + jumlah_penunjang) from rsv_layanan_tht2 where tgl_keluar='".$row7a["tgl_keluar"]."'"); 
			if ($_GET["mRAWAT"]=="106"){	  
			?>
			 <tr>
				<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row7a["tgl_keluar"] ?> </td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($bedah_1_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($bedah_2_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($bedah_3_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($bedah_4_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($bedah_5_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($bedah_total_,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($rj_igd_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_1_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_2_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_3_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_4_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_5_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_6_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_total_,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_1_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_2_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_3_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_4_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_5_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_total_,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($total_,2,",",".") ?></td>
			</tr>
			<?	
			}
			
			;$j7a++;}

          $i7a++;
		  }
        				
			?>
         
            <?;$j++;}
        $i++;
        }
        ?>

			<tr>
                <td colspan="5" class="TBL_FOOT" align="center">TOTAL</td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_1,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_total,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($bedah_1,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($bedah_2,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($bedah_3,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($bedah_4,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($bedah_5,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($bedah_total,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($rj_igd,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_1,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_2,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_3,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_4,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_5,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_6,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_total,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_1,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_2,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_3,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_4,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_5,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_total,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($total,2,",",".") ?></td>
            </tr>

</TABLE>