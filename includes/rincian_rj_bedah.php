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
                
                <td class="TBL_HEAD" colspan="8"><div align="center">TINDAKAN MEDIK</div></td>
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
				$sql4a = "select to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,tgl_keluar from rsv_layanan_bedah2 where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by tgl_keluar order by tgl_keluar asc";
				@$r4a = pg_query($con,$sql4a);
				@$n4a = pg_num_rows($r4a);

				$max_row4a= 9999999 ;
				$mulai4a = $HTTP_GET_VARS["rec"] ;
				if (!$mulai4a){$mulai4a=1;}
				
				
				$i4a= 1 ;
				$j4a= 1 ;
				$last_id4a=1;
				while (@$row4a = pg_fetch_array($r4a)){
					  if (($j4a<=$max_row4a) AND ($i4a >= $mulai4a)){

						 $no4a=$i4a;

			$sql4 = "select poli,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_kwitansi, nama,mr_no,
                    karcis_umum, radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,rj_igd_bedah, smf_bedah_1,smf_bedah_2,smf_bedah_3,smf_bedah_utama,
                    smf_bedah_vip,smf_bedah_lain, jumlah, (karcis_umum + jumlah + jumlah_penunjang) as total
                    from rsv_layanan_bedah2
                    where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,poli,tgl_keluar, nama,mr_no, karcis_umum, rj_igd_bedah, smf_bedah_1,smf_bedah_2,smf_bedah_3,smf_bedah_utama,smf_bedah_vip,smf_bedah_lain,jumlah
                    order by tgl_keluar asc";


			@$r4 = pg_query($con,$sql4);
            @$n4 = pg_num_rows($r4);

            $max_row4= 9999999 ;
            $mulai4 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai4){$mulai4=1;}
				
							$row4=0;
							$i4= 1 ;
							$j4= 1 ;
							$last_id4=1;
							while (@$row4 = pg_fetch_array($r4)){
								  if (($j4<=$max_row4) AND ($i4 >= $mulai4)){

									 $no4=$i4;
								  if($row4["poli"]==$row1["poli"] and $row4["tgl_lunas"]==$row4a["tgl_lunas"]) {
								  if($row4["poli"]=="107"){
								  ?>
            <tr>
                <td class="TBL_BODY" align="center"><?=$no4 ?></td>
                <td class="TBL_BODY" align="center"><?=$row4["tgl_lunas"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row4["tgl_kwitansi"] ?></td>
                <td class="TBL_BODY" align="left"><?=$row4["nama"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row4["mr_no"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["karcis_umum"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["karcis_umum"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row4["rj_igd_bedah"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["smf_bedah_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["smf_bedah_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["smf_bedah_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["smf_bedah_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["smf_bedah_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["smf_bedah_lain"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["jumlah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row4["radiologi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["lab_klinik"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["lab_pa"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["rehab"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["instalasi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row4["jumlah_penunjang"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row4["total"],2,",",".") ?></td>
            </tr>
			
			
			<?
			}
			$karcis_1=$karcis_1 + $row4["karcis_umum"];
			$karcis_total=$karcis_total + $row4["karcis_umum"];
			
            $rj_igd=$rj_igd + $row4["rj_igd_bedah"];
			$smf_1=$smf_1 + $row4["smf_bedah_1"];
            $smf_2=$smf_2 + $row4["smf_bedah_2"];
            $smf_3=$smf_3 + $row4["smf_bedah_3"];
            $smf_4=$smf_4 + $row4["smf_bedah_utama"];
            $smf_5=$smf_5 + $row4["smf_bedah_vip"];
			$smf_6=$smf_6 + $row4["smf_bedah_lain"];
            $smf_total=$smf_total + $row4["jumlah"];
            $penunjang_1=$penunjang_1 + $row4["radiologi"];
            $penunjang_2=$penunjang_2 + $row4["lab_klinik"];
            $penunjang_3=$penunjang_3 + $row4["lab_pa"];
            $penunjang_4=$penunjang_4 + $row4["rehab"];
            $penunjang_5=$penunjang_5 + $row4["instalasi"];
            $penunjang_total=$penunjang_total + $row4["jumlah_penunjang"];
            $total=$total + $row4["total"];
			

				;$j4++;}

          $i4++;}
        }	
		
				  $karcis_umum = getFromTable("select sum(karcis_umum) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $smf_1_ = getFromTable("select sum(smf_bedah_1) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $smf_2_ = getFromTable("select sum(smf_bedah_2) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $smf_3_ = getFromTable("select sum(smf_bedah_3) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $smf_4_ = getFromTable("select sum(smf_bedah_utama) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $smf_5_ = getFromTable("select sum(smf_bedah_vip) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $smf_6_ = getFromTable("select sum(smf_bedah_lain) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $smf_total_ = getFromTable("select sum(jumlah) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $penunjang_1_ = getFromTable("select sum(radiologi) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $penunjang_2_ = getFromTable("select sum(lab_klinik) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $penunjang_3_ = getFromTable("select sum(lab_pa) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $penunjang_4_ = getFromTable("select sum(rehab) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $penunjang_5_ = getFromTable("select sum(instalasi) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $penunjang_total_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'");
				  $total_ = getFromTable("select sum(karcis_umum + jumlah + jumlah_penunjang) from rsv_layanan_bedah2 where tgl_keluar='".$row4a["tgl_keluar"]."'"); 
			
			if ($_GET["mRAWAT"]=="107") {	  
			?>
			 <tr>
				<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row4a["tgl_keluar"] ?> </td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>

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
			
			;$j4a++;}

          $i4a++;
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

                <td class="TBL_FOOT" align="right"><?=number_format($rj_igd,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_1,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_2,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_3,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_4,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_5,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_6,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_total,2,",",".") ?></td>>

                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_1,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_2,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_3,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_4,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_5,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_total,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($total,2,",",".") ?></td>
            </tr>
       
	  
</TABLE>