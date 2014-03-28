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
                <td class="TBL_HEAD" colspan="6"><div align="center">PERSALINAN PER-VAGINAM</div></td>
                <td class="TBL_HEAD" colspan="6"><div align="center">PERSALINAN PER-ABDOMINAL</div></td>
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
                <td class="TBL_HEAD"><div align="center">KELAS&nbsp;I</div></td>
                <td class="TBL_HEAD"><div align="center">KELAS&nbsp;II</div></td>
                <td class="TBL_HEAD"><div align="center">KELAS&nbsp;III</div></td>
                <td class="TBL_HEAD"><div align="center">KELAS&nbsp;UTAMA</div></td>
                <td class="TBL_HEAD"><div align="center">KELAS&nbsp;VIP</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
                <td class="TBL_HEAD"><div align="center">KELAS&nbsp;I</div></td>
                <td class="TBL_HEAD"><div align="center">KELAS&nbsp;II</div></td>
                <td class="TBL_HEAD"><div align="center">KELAS&nbsp;III</div></td>
                <td class="TBL_HEAD"><div align="center">KELAS&nbsp;UTAMA</div></td>
                <td class="TBL_HEAD"><div align="center">KELAS&nbsp;VIP</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
                <td class="TBL_HEAD"><div align="center">RADIOLOGI</div></td>
                <td class="TBL_HEAD"><div align="center">LAB. KLINIK</div></td>
                <td class="TBL_HEAD"><div align="center">LAB.&nbsp;PA</div></td>
                <td class="TBL_HEAD"><div align="center">REHABILITASI MEDIK</div></td>
                <td class="TBL_HEAD"><div align="center">INSTALASI</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
                

                </tr>


         <?
				$sql10a = "select to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,tgl_keluar from rsv_layanan_bidan_o2 where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by tgl_keluar order by tgl_keluar asc";
				@$r10a = pg_query($con,$sql10a);
				@$n10a = pg_num_rows($r10a);

				$max_row10a= 9999999 ;
				$mulai10a = $HTTP_GET_VARS["rec"] ;
				if (!$mulai10a){$mulai10a=1;}
				
				
				$i10a= 1 ;
				$j10a= 1 ;
				$last_id10a=1;
				while (@$row10a = pg_fetch_array($r10a)){
					  if (($j10a<=$max_row10a) AND ($i10a >= $mulai10a)){

						 $no10a=$i10a;

			$sql10 = "select poli,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_kwitansi, nama,mr_no,
                    karcis_umum, radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,lahir_bidan_o_1,lahir_bidan_o_2,lahir_bidan_o_3,lahir_bidan_o_utama,
                    lahir_bidan_o_vip,total_lahir,
                    lahir1_bidan_o_1,lahir1_bidan_o_2,lahir1_bidan_o_3,lahir1_bidan_o_utama,
                    lahir1_bidan_o_vip,total_lahir1,
                    bedah_bidan_o_1,bedah_bidan_o_2,bedah_bidan_o_3,bedah_bidan_o_utama,
                    bedah_bidan_o_vip,total_bedah,rj_igd_bidan_o, smf_bidan_o_1,smf_bidan_o_2,smf_bidan_o_3,smf_bidan_o_utama,
                    smf_bidan_o_vip,smf_bidan_o_lain, jumlah, (jumlah_penunjang + karcis_umum + jumlah + total_bedah + total_lahir + total_lahir1) as total
                    from rsv_layanan_bidan_o2
                    where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,lahir_bidan_o_1,lahir_bidan_o_2,lahir_bidan_o_3,lahir_bidan_o_utama,
                    lahir_bidan_o_vip,total_lahir,
                    lahir1_bidan_o_1,lahir1_bidan_o_2,lahir1_bidan_o_3,lahir1_bidan_o_utama,
                    lahir1_bidan_o_vip,total_lahir1,total_bedah,poli,tgl_keluar, nama,mr_no, karcis_umum, bedah_bidan_o_1,bedah_bidan_o_2,bedah_bidan_o_3,bedah_bidan_o_utama,
                    bedah_bidan_o_vip,rj_igd_bidan_o, smf_bidan_o_1,smf_bidan_o_2,smf_bidan_o_3,smf_bidan_o_utama,smf_bidan_o_vip,smf_bidan_o_lain,jumlah
                    order by tgl_keluar asc";


			@$r10 = pg_query($con,$sql10);
            @$n10 = pg_num_rows($r10);

            $max_row10= 9999999 ;
            $mulai10 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai10){$mulai10=1;}
				
							$row10=0;
							$i10= 1 ;
							$j10= 1 ;
							$last_id10=1;
							while (@$row10 = pg_fetch_array($r10)){
								  if (($j10<=$max_row10) AND ($i10 >= $mulai10)){

									 $no10=$i10;
								  if($row10["poli"]==$row1["poli"] and $row10["tgl_lunas"]==$row10a["tgl_lunas"]) {
								  if($row10["poli"]=="114"){
								  ?>
            <tr>
                <td class="TBL_BODY" align="center"><?=$no10 ?></td>
                <td class="TBL_BODY" align="center"><?=$row10["tgl_lunas"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row10["tgl_kwitansi"] ?></td>
                <td class="TBL_BODY" align="left"><?=$row10["nama"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row10["mr_no"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["karcis_umum"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["karcis_umum"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row10["bedah_bidan_o_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["bedah_bidan_o_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["bedah_bidan_o_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["bedah_bidan_o_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["bedah_bidan_o_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["total_bedah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row10["rj_igd_bidan_o"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["smf_bidan_o_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["smf_bidan_o_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["smf_bidan_o_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["smf_bidan_o_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["smf_bidan_o_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["smf_bidan_o_lain"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["jumlah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row10["lahir_bidan_o_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["lahir_bidan_o_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["lahir_bidan_o_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["lahir_bidan_o_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["lahir_bidan_o_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["total_lahir"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row10["lahir1_bidan_o_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["lahir1_bidan_o_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["lahir1_bidan_o_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["lahir1_bidan_o_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["lahir1_bidan_o_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["total_lahir1"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row10["radiologi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["lab_klinik"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["lab_pa"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["rehab"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["instalasi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row10["jumlah_penunjang"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row10["total"],2,",",".") ?></td>
            </tr>
			
			
			<?
			}
			$karcis_1=$karcis_1 + $row10["karcis_umum"];
			$karcis_total=$karcis_total + $row10["karcis_umum"];
			$bedah_1 = $bedah_1 + $row10["bedah_bidan_o_1"];
			$bedah_2 = $bedah_2 + $row10["bedah_bidan_o_2"];
			$bedah_3 = $bedah_3 + $row10["bedah_bidan_o_3"];
			$bedah_4 = $bedah_4 + $row10["bedah_bidan_o_utama"];
			$bedah_5 = $bedah_5 + $row10["bedah_bidan_o_vip"];
			$bedah_total = $bedah_total + $row10["total_bedah"];
			$lahir_bidan_o_1 = $lahir_bidan_o_1 + $row10["lahir_bidan_o_1"];
			$lahir_bidan_o_2 = $lahir_bidan_o_2 + $row10["lahir_bidan_o_2"];
			$lahir_bidan_o_3 = $lahir_bidan_o_3 + $row10["lahir_bidan_o_3"];
			$lahir_bidan_o_4 = $lahir_bidan_o_4 + $row10["lahir_bidan_o_utama"];
			$lahir_bidan_o_5 = $lahir_bidan_o_5 + $row10["lahir_bidan_o_vip"];
			$lahir_total = $lahir_total + $row10["total_lahir"];
			$lahir1_bidan_o_1 = $lahir1_bidan_o_1 + $row10["lahir1_bidan_o_1"];
			$lahir1_bidan_o_2 = $lahir1_bidan_o_2 + $row10["lahir1_bidan_o_2"];
			$lahir1_bidan_o_3 = $lahir1_bidan_o_3 + $row10["lahir1_bidan_o_3"];
			$lahir1_bidan_o_4 = $lahir1_bidan_o_4 + $row10["lahir1_bidan_o_utama"];
			$lahir1_bidan_o_5 = $lahir1_bidan_o_5 + $row10["lahir1_bidan_o_vip"];
			$lahir_total1 = $lahir_total1 + $row10["total_lahir1"];
            $rj_igd=$rj_igd + $row10["rj_igd_bidan_o"];
			$smf_1=$smf_1 + $row10["smf_bidan_o_1"];
            $smf_2=$smf_2 + $row10["smf_bidan_o_2"];
            $smf_3=$smf_3 + $row10["smf_bidan_o_3"];
            $smf_4=$smf_4 + $row10["smf_bidan_o_utama"];
            $smf_5=$smf_5 + $row10["smf_bidan_o_vip"];
			$smf_6=$smf_6 + $row10["smf_bidan_o_lain"];
            $smf_total=$smf_total + $row10["jumlah"];
			
            $penunjang_1=$penunjang_1 + $row10["radiologi"];
            $penunjang_2=$penunjang_2 + $row10["lab_klinik"];
            $penunjang_3=$penunjang_3 + $row10["lab_pa"];
            $penunjang_4=$penunjang_4 + $row10["rehab"];
            $penunjang_5=$penunjang_5 + $row10["instalasi"];
            $penunjang_total=$penunjang_total + $row10["jumlah_penunjang"];
            $total=$total + $row10["total"];
			

				;$j10++;}

          $i10++;}
        }	
		
				  $karcis_umum = getFromTable("select sum(karcis_umum) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $bedah_1_ = getFromTable("select sum(bedah_bidan_o_1) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $bedah_2_ = getFromTable("select sum(bedah_bidan_o_2) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $bedah_3_ = getFromTable("select sum(bedah_bidan_o_3) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $bedah_4_ = getFromTable("select sum(bedah_bidan_o_utama) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $bedah_5_ = getFromTable("select sum(bedah_bidan_o_vip) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $bedah_total_ = getFromTable("select sum(total_bedah) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir_bidan_o_1_ = getFromTable("select sum(lahir_bidan_o_1) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir_bidan_o_2_ = getFromTable("select sum(lahir_bidan_o_2) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir_bidan_o_3_ = getFromTable("select sum(lahir_bidan_o_3) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir_bidan_o_4_ = getFromTable("select sum(lahir_bidan_o_utama) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir_bidan_o_5_ = getFromTable("select sum(lahir_bidan_o_vip) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir_total_ = getFromTable("select sum(total_lahir) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir1_bidan_o_1_ = getFromTable("select sum(lahir1_bidan_o_1) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir1_bidan_o_2_ = getFromTable("select sum(lahir1_bidan_o_2) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir1_bidan_o_3_ = getFromTable("select sum(lahir1_bidan_o_3) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir1_bidan_o_4_ = getFromTable("select sum(lahir1_bidan_o_utama) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir1_bidan_o_5_ = getFromTable("select sum(lahir1_bidan_o_vip) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $lahir_total1_ = getFromTable("select sum(total_lahir1) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $rj_igd_ = getFromTable("select sum(rj_igd_bidan_o) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $smf_1_ = getFromTable("select sum(smf_bidan_o_1) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $smf_2_ = getFromTable("select sum(smf_bidan_o_2) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $smf_3_ = getFromTable("select sum(smf_bidan_o_3) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $smf_4_ = getFromTable("select sum(smf_bidan_o_utama) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $smf_5_ = getFromTable("select sum(smf_bidan_o_vip) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $smf_6_ = getFromTable("select sum(smf_bidan_o_lain) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $smf_total_ = getFromTable("select sum(jumlah) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $penunjang_1_ = getFromTable("select sum(radiologi) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $penunjang_2_ = getFromTable("select sum(lab_klinik) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $penunjang_3_ = getFromTable("select sum(lab_pa) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $penunjang_4_ = getFromTable("select sum(rehab) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $penunjang_5_ = getFromTable("select sum(instalasi) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $penunjang_total_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'");
				  $total_ = getFromTable("select sum(jumlah_penunjang + karcis_umum + jumlah + total_bedah + total_lahir + total_lahir1) from rsv_layanan_bidan_o2 where tgl_keluar='".$row10a["tgl_keluar"]."'"); 
			
			if ($_GET["mRAWAT"]=="114") {	  
			?>
			 <tr>
				<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row10a["tgl_keluar"] ?> </td>
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
				
				<td class="TBL_FOOT" align="right"><?=number_format($lahir_bidan_o_1_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_bidan_o_2_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_bidan_o_3_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_bidan_o_4_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_bidan_o_5_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_total_,2,",",".") ?></td>
				
				<td class="TBL_FOOT" align="right"><?=number_format($lahir1_bidan_o_1_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir1_bidan_o_2_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir1_bidan_o_3_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir1_bidan_o_4_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir1_bidan_o_5_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_total1_,2,",",".") ?></td>

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
			
			;$j10a++;}

          $i10a++;
		  }
        				
			?>
         
            <?;$j++;}
        $i++;
        }
        ?>

                    
		<?if ($_GET["mRAWAT"]=="114") {?>
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
				
				<td class="TBL_FOOT" align="right"><?=number_format($lahir_bidan_o_1,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_bidan_o_2,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_bidan_o_3,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_bidan_o_4,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_bidan_o_5,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_total,2,",",".") ?></td>
				
				<td class="TBL_FOOT" align="right"><?=number_format($lahir1_bidan_o_1,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir1_bidan_o_2,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir1_bidan_o_3,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir1_bidan_o_4,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir1_bidan_o_5,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($lahir_total1,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_1,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_2,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_3,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_4,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_5,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_total,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($total,2,",",".") ?></td>
            </tr>
		<?}?>
       
	  
</TABLE>