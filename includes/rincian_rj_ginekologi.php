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
				$sql11a = "select to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,tgl_keluar from rsv_layanan_bidan_g2 where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by tgl_keluar order by tgl_keluar asc";
				@$r11a = pg_query($con,$sql11a);
				@$n11a = pg_num_rows($r11a);

				$max_row11a= 9999999 ;
				$mulai11a = $HTTP_GET_VARS["rec"] ;
				if (!$mulai11a){$mulai11a=1;}
				
				
				$i11a= 1 ;
				$j11a= 1 ;
				$last_id11a=1;
				while (@$row11a = pg_fetch_array($r11a)){
					  if (($j11a<=$max_row11a) AND ($i11a >= $mulai11a)){

						 $no11a=$i11a;

			$sql11 = "select poli,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_kwitansi, nama,mr_no,
                    karcis_umum, radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,bedah_bidan_g_1,bedah_bidan_g_2,bedah_bidan_g_3,bedah_bidan_g_utama,
                    bedah_bidan_g_vip,total_bedah,rj_igd_bidan_g, smf_bidan_g_1,smf_bidan_g_2,smf_bidan_g_3,smf_bidan_g_utama,
                    smf_bidan_g_vip,smf_bidan_g_lain, jumlah, (karcis_umum + jumlah + total_bedah + jumlah_penunjang) as total
                    from rsv_layanan_bidan_g2
                    where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,total_bedah,poli,tgl_keluar, nama,mr_no, karcis_umum, bedah_bidan_g_1,bedah_bidan_g_2,bedah_bidan_g_3,bedah_bidan_g_utama,
                    bedah_bidan_g_vip,rj_igd_bidan_g, smf_bidan_g_1,smf_bidan_g_2,smf_bidan_g_3,smf_bidan_g_utama,smf_bidan_g_vip,smf_bidan_g_lain,jumlah
                    order by tgl_keluar asc";


			@$r11 = pg_query($con,$sql11);
            @$n11 = pg_num_rows($r11);

            $max_row11= 9999999 ;
            $mulai11 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai11){$mulai11=1;}
				
							$row11=0;
							$i11= 1 ;
							$j11= 1 ;
							$last_id11=1;
							while (@$row11 = pg_fetch_array($r11)){
								  if (($j11<=$max_row11) AND ($i11 >= $mulai11)){

									 $no11=$i11;
								  if($row11["poli"]==$row1["poli"] and $row11["tgl_lunas"]==$row11a["tgl_lunas"]) {
								  if($row11["poli"]=="115"){
								  ?>
            <tr>
                <td class="TBL_BODY" align="center"><?=$no11 ?></td>
                <td class="TBL_BODY" align="center"><?=$row11["tgl_lunas"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row11["tgl_kwitansi"] ?></td>
                <td class="TBL_BODY" align="left"><?=$row11["nama"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row11["mr_no"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["karcis_umum"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["karcis_umum"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row11["bedah_bidan_g_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["bedah_bidan_g_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["bedah_bidan_g_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["bedah_bidan_g_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["bedah_bidan_g_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["total_bedah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row11["rj_igd_bidan_g"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["smf_bidan_g_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["smf_bidan_g_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["smf_bidan_g_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["smf_bidan_g_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["smf_bidan_g_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["smf_bidan_g_lain"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["jumlah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row11["radiologi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["lab_klinik"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["lab_pa"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["rehab"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["instalasi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row11["jumlah_penunjang"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row11["total"],2,",",".") ?></td>
            </tr>
			
			
			<?
			}
			$karcis_1=$karcis_1 + $row11["karcis_umum"];
			$karcis_total=$karcis_total + $row11["karcis_umum"];
			$bedah_1 = $bedah_1 + $row11["bedah_bidan_g_1"];
			$bedah_2 = $bedah_2 + $row11["bedah_bidan_g_2"];
			$bedah_3 = $bedah_3 + $row11["bedah_bidan_g_3"];
			$bedah_4 = $bedah_4 + $row11["bedah_bidan_g_utama"];
			$bedah_5 = $bedah_5 + $row11["bedah_bidan_g_vip"];
			$bedah_total = $bedah_total + $row11["total_bedah"];
            $rj_igd=$rj_igd + $row11["rj_igd_bidan_g"];
			$smf_1=$smf_1 + $row11["smf_bidan_g_1"];
            $smf_2=$smf_2 + $row11["smf_bidan_g_2"];
            $smf_3=$smf_3 + $row11["smf_bidan_g_3"];
            $smf_4=$smf_4 + $row11["smf_bidan_g_utama"];
            $smf_5=$smf_5 + $row11["smf_bidan_g_vip"];
			$smf_6=$smf_6 + $row11["smf_bidan_g_lain"];
            $smf_total=$smf_total + $row11["jumlah"];
			
            $penunjang_1=$penunjang_1 + $row11["radiologi"];
            $penunjang_2=$penunjang_2 + $row11["lab_klinik"];
            $penunjang_3=$penunjang_3 + $row11["lab_pa"];
            $penunjang_4=$penunjang_4 + $row11["rehab"];
            $penunjang_5=$penunjang_5 + $row11["instalasi"];
            $penunjang_total=$penunjang_total + $row11["jumlah_penunjang"];
            $total=$total + $row11["total"];
			

				;$j11++;}

          $i11++;}
        }	
		
				  $karcis_umum = getFromTable("select sum(karcis_umum) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $bedah_1_ = getFromTable("select sum(bedah_bidan_g_1) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $bedah_2_ = getFromTable("select sum(bedah_bidan_g_2) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $bedah_3_ = getFromTable("select sum(bedah_bidan_g_3) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $bedah_4_ = getFromTable("select sum(bedah_bidan_g_utama) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $bedah_5_ = getFromTable("select sum(bedah_bidan_g_vip) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $bedah_total_ = getFromTable("select sum(total_bedah) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $rj_igd_ = getFromTable("select sum(rj_igd_bidan_g) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $smf_1_ = getFromTable("select sum(smf_bidan_g_1) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $smf_2_ = getFromTable("select sum(smf_bidan_g_2) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $smf_3_ = getFromTable("select sum(smf_bidan_g_3) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $smf_4_ = getFromTable("select sum(smf_bidan_g_utama) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $smf_5_ = getFromTable("select sum(smf_bidan_g_vip) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $smf_6_ = getFromTable("select sum(smf_bidan_g_lain) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $smf_total_ = getFromTable("select sum(jumlah) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $penunjang_1_ = getFromTable("select sum(radiologi) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $penunjang_2_ = getFromTable("select sum(lab_klinik) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $penunjang_3_ = getFromTable("select sum(lab_pa) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $penunjang_4_ = getFromTable("select sum(rehab) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $penunjang_5_ = getFromTable("select sum(instalasi) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $penunjang_total_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'");
				  $total_ = getFromTable("select sum(karcis_umum + jumlah + total_bedah + jumlah_penunjang) from rsv_layanan_bidan_g2 where tgl_keluar='".$row11a["tgl_keluar"]."'"); 
			
			if ($_GET["mRAWAT"]=="115") {	  
			?>
			 <tr>
				<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row11a["tgl_keluar"] ?> </td>
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
			
			;$j11a++;}

          $i11a++;
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