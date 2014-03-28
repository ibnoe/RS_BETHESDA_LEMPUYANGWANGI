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
                
                <td class="TBL_HEAD" colspan="7"><div align="center">TINDAKAN MEDIK BEDAH MATA</div></td>
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

                <td class="TBL_HEAD"><div align="center">BEDAH TANPA&nbsp;ANASTESI</div></td>
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
				$sql8a = "select to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,tgl_keluar from rsv_layanan_mata2 where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by tgl_keluar order by tgl_keluar asc";
				@$r8a = pg_query($con,$sql8a);
				@$n8a = pg_num_rows($r8a);

				$max_row8a= 9999999 ;
				$mulai8a = $HTTP_GET_VARS["rec"] ;
				if (!$mulai8a){$mulai8a=1;}
				
				
				$i8a= 1 ;
				$j8a= 1 ;
				$last_id8a=1;
				while (@$row8a = pg_fetch_array($r8a)){
					  if (($j8a<=$max_row8a) AND ($i8a >= $mulai8a)){

						 $no8a=$i8a;

			$sql8 = "select poli,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_kwitansi, nama,mr_no,
                    karcis_umum, radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,bedah_non_anastesi,bedah_mata_1,bedah_mata_2,bedah_mata_3,bedah_mata_utama,
                    bedah_mata_vip,total_bedah,rj_igd_mata, smf_mata_1,smf_mata_2,smf_mata_3,smf_mata_utama,
                    smf_mata_vip,smf_mata_lain, jumlah, (karcis_umum + jumlah + total_bedah + jumlah_penunjang) as total
                    from rsv_layanan_mata2
                    where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,bedah_non_anastesi,total_bedah,poli,tgl_keluar, nama,mr_no, karcis_umum, bedah_mata_1,bedah_mata_2,bedah_mata_3,bedah_mata_utama,
                    bedah_mata_vip,rj_igd_mata, smf_mata_1,smf_mata_2,smf_mata_3,smf_mata_utama,smf_mata_vip,smf_mata_lain,jumlah
                    order by tgl_keluar asc";


			@$r8 = pg_query($con,$sql8);
            @$n8 = pg_num_rows($r8);

            $max_row8= 9999999 ;
            $mulai8 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai8){$mulai8=1;}
				
							$row8=0;
							$i8= 1 ;
							$j8= 1 ;
							$last_id8=1;
							while (@$row8 = pg_fetch_array($r8)){
								  if (($j8<=$max_row8) AND ($i8 >= $mulai8)){

									 $no8=$i8;
								  if($row8["poli"]==$row1["poli"] and $row8["tgl_lunas"]==$row8a["tgl_lunas"]) {
								  if($row8["poli"]=="102"){
								  ?>
            <tr>
                <td class="TBL_BODY" align="center"><?=$no8 ?></td>
                <td class="TBL_BODY" align="center"><?=$row8["tgl_lunas"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row8["tgl_kwitansi"] ?></td>
                <td class="TBL_BODY" align="left"><?=$row8["nama"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row8["mr_no"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["karcis_umum"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["karcis_umum"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row8["bedah_non_anastesi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["bedah_mata_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["bedah_mata_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["bedah_mata_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["bedah_mata_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["bedah_mata_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["total_bedah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row8["rj_igd_mata"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["smf_mata_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["smf_mata_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["smf_mata_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["smf_mata_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["smf_mata_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["smf_mata_lain"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["jumlah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row8["radiologi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["lab_klinik"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["lab_pa"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["rehab"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["instalasi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row8["jumlah_penunjang"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row8["total"],2,",",".") ?></td>
            </tr>
			
			
			<?
			}
			$karcis_1=$karcis_1 + $row8["karcis_umum"];
			$karcis_total=$karcis_total + $row8["karcis_umum"];
			
			$bedah_non=$bedah_non + $row8["bedah_non_anastesi"];
			$bedah_1=$bedah_1 + $row8["bedah_mata_1"];
            $bedah_2=$bedah_2 + $row8["bedah_mata_2"];
            $bedah_3=$bedah_3 + $row8["bedah_mata_3"];
            $bedah_4=$bedah_4 + $row8["bedah_mata_utama"];
            $bedah_5=$bedah_5 + $row8["bedah_mata_vip"];
            $bedah_total=$bedah_total + $row8["total_bedah"];

            $rj_igd=$rj_igd + $row8["rj_igd_mata"];
			$smf_1=$smf_1 + $row8["smf_mata_1"];
            $smf_2=$smf_2 + $row8["smf_mata_2"];
            $smf_3=$smf_3 + $row8["smf_mata_3"];
            $smf_4=$smf_4 + $row8["smf_mata_utama"];
            $smf_5=$smf_5 + $row8["smf_mata_vip"];
			$smf_6=$smf_6 + $row8["smf_mata_lain"];
            $smf_total=$smf_total + $row8["jumlah"];
			
            $penunjang_1=$penunjang_1 + $row8["radiologi"];
            $penunjang_2=$penunjang_2 + $row8["lab_klinik"];
            $penunjang_3=$penunjang_3 + $row8["lab_pa"];
            $penunjang_4=$penunjang_4 + $row8["rehab"];
            $penunjang_5=$penunjang_5 + $row8["instalasi"];
            $penunjang_total=$penunjang_total + $row8["jumlah_penunjang"];
            $total=$total + $row8["total"];
			

				;$j8++;}

          $i8++;}
        }	
		
				  $karcis_umum = getFromTable("select sum(karcis_umum) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $bedah_non_ = getFromTable("select sum(bedah_non_anastesi) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $bedah_1_ = getFromTable("select sum(bedah_mata_1) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $bedah_2_ = getFromTable("select sum(bedah_mata_2) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $bedah_3_ = getFromTable("select sum(bedah_mata_3) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $bedah_4_ = getFromTable("select sum(bedah_mata_utama) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $bedah_5_ = getFromTable("select sum(bedah_mata_vip) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $bedah_total_ = getFromTable("select sum(total_bedah) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $rj_igd_ = getFromTable("select sum(rj_igd_mata) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $smf_1_ = getFromTable("select sum(smf_mata_1) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $smf_2_ = getFromTable("select sum(smf_mata_2) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $smf_3_ = getFromTable("select sum(smf_mata_3) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $smf_4_ = getFromTable("select sum(smf_mata_utama) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $smf_5_ = getFromTable("select sum(smf_mata_vip) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $smf_6_ = getFromTable("select sum(smf_mata_lain) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $smf_total_ = getFromTable("select sum(jumlah) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $penunjang_1_ = getFromTable("select sum(radiologi) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $penunjang_2_ = getFromTable("select sum(lab_klinik) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $penunjang_3_ = getFromTable("select sum(lab_pa) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $penunjang_4_ = getFromTable("select sum(rehab) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $penunjang_5_ = getFromTable("select sum(instalasi) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $penunjang_total_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'");
				  $total_ = getFromTable("select sum(karcis_umum + jumlah + total_bedah + jumlah_penunjang) from rsv_layanan_mata2 where tgl_keluar='".$row8a["tgl_keluar"]."'"); 
			
			if ($_GET["mRAWAT"]=="102") {	  
			?>
			 <tr>
				<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row8a["tgl_keluar"] ?> </td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
				
				<td class="TBL_FOOT" align="right"><?=number_format($bedah_non_,2,",",".") ?></td>
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
			;$j8a++;}

          $i8a++;
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
				
				<td class="TBL_FOOT" align="right"><?=number_format($bedah_non,2,",",".") ?></td>
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