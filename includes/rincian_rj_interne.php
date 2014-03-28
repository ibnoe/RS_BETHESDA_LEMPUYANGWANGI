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
                
                <td class="TBL_HEAD" colspan="6"><div align="center">TINDAKAN&nbsp;MEDIK BEDAH</div></td>
                <td class="TBL_HEAD" colspan="3"><div align="center">TINDAKAN&nbsp;MEDIK NON&nbsp;BEDAH</div></td>
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
                <td class="TBL_HEAD"><div align="center">PELAYANAN&nbsp;RAWAT JALAN&nbsp;INTERNE </div></td>
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
				$sql3a = "select to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,tgl_keluar from rsv_layanan_interne2 where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by tgl_keluar order by tgl_keluar asc";
				@$r3a = pg_query($con,$sql3a);
				@$n3a = pg_num_rows($r3a);

				$max_row3a= 9999999 ;
				$mulai3a = $HTTP_GET_VARS["rec"] ;
				if (!$mulai3a){$mulai3a=1;}
				
				
				$i3a= 1 ;
				$j3a= 1 ;
				$last_id3a=1;
				while (@$row3a = pg_fetch_array($r3a)){
					  if (($j3a<=$max_row3a) AND ($i3a >= $mulai3a)){

						 $no3a=$i3a;

			$sql3 = "select poli,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_kwitansi, nama,mr_no,
                    karcis_umum, bedah_interne_1,bedah_interne_2,bedah_interne_3,bedah_interne_utama,bedah_interne_vip,total_bedah,radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,rj_igd_interne, smf_interne_lain, jumlah, (karcis_umum + jumlah + total_bedah + jumlah_penunjang) as total
                    from rsv_layanan_interne2
                    where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by bedah_interne_1,bedah_interne_2,bedah_interne_3,bedah_interne_utama,bedah_interne_vip,total_bedah,radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,poli,tgl_keluar, nama,mr_no, karcis_umum, rj_igd_interne ,smf_interne_lain,jumlah
                    order by tgl_keluar asc";

			@$r3 = pg_query($con,$sql3);
            @$n3 = pg_num_rows($r3);

            $max_row3= 9999999 ;
            $mulai3 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai3){$mulai3=1;}
				
							$row3=0;
							$i3= 1 ;
							$j3= 1 ;
							$last_id3=1;
							while (@$row3 = pg_fetch_array($r3)){
								  if (($j3<=$max_row3) AND ($i3 >= $mulai3)){

									 $no3=$i3;
								  if($row3["poli"]==$row1["poli"] and $row3["tgl_lunas"]==$row3a["tgl_lunas"]) {
								  if($row3["poli"]=="103"){
								  ?>
            <tr>
                <td class="TBL_BODY" align="center"><?=$no3 ?></td>
                <td class="TBL_BODY" align="center"><?=$row3["tgl_lunas"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row3["tgl_kwitansi"] ?></td>
                <td class="TBL_BODY" align="left"><?=$row3["nama"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row3["mr_no"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["karcis_umum"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["karcis_umum"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row3["bedah_interne_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["bedah_interne_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["bedah_interne_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["bedah_interne_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["bedah_interne_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["total_bedah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row3["rj_igd_interne"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["smf_interne_lain"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["jumlah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row3["radiologi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["lab_klinik"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["lab_pa"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["rehab"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["instalasi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row3["jumlah_penunjang"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row3["total"],2,",",".") ?></td>
            </tr>
			
			
			<?
			}
			$karcis_1=$karcis_1 + $row3["karcis_umum"];
			$karcis_total=$karcis_total + $row3["karcis_umum"];
			
            $bedah_1=$bedah_1 + $row3["bedah_interne_1"];
            $bedah_2=$bedah_2 + $row3["bedah_interne_2"];
            $bedah_3=$bedah_3 + $row3["bedah_interne_3"];
            $bedah_4=$bedah_4 + $row3["bedah_interne_utama"];
            $bedah_5=$bedah_5 + $row3["bedah_interne_vip"];
            $bedah_total=$bedah_total + $row3["total_bedah"];
            $rj_igd=$rj_igd + $row3["rj_igd_interne"];
			$smf_6=$smf_6 + $row3["smf_interne_lain"];
            $smf_total=$smf_total + $row3["jumlah"];
            $penunjang_1=$penunjang_1 + $row3["radiologi"];
            $penunjang_2=$penunjang_2 + $row3["lab_klinik"];
            $penunjang_3=$penunjang_3 + $row3["lab_pa"];
            $penunjang_4=$penunjang_4 + $row3["rehab"];
            $penunjang_5=$penunjang_5 + $row3["instalasi"];
            $penunjang_total=$penunjang_total + $row3["jumlah_penunjang"];
            $total=$total + $row3["total"];
			

				;$j3++;}

          $i3++;}
        }	
		
				  $karcis_umum = getFromTable("select sum(karcis_umum) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $bedah_1_ = getFromTable("select sum(bedah_interne_1) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $bedah_2_ = getFromTable("select sum(bedah_interne_2) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $bedah_3_ = getFromTable("select sum(bedah_interne_3) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $bedah_4_ = getFromTable("select sum(bedah_interne_utama) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $bedah_5_ = getFromTable("select sum(bedah_interne_vip) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $bedah_total_ = getFromTable("select sum(total_bedah) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $rj_igd_ = getFromTable("select sum(rj_igd_interne) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $penunjang_1_ = getFromTable("select sum(radiologi) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $smf_6_ = getFromTable("select sum(smf_interne_lain) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $smf_total_ = getFromTable("select sum(jumlah) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $penunjang_2_ = getFromTable("select sum(lab_klinik) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $penunjang_3_ = getFromTable("select sum(lab_pa) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $penunjang_4_ = getFromTable("select sum(rehab) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $penunjang_5_ = getFromTable("select sum(instalasi) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $penunjang_total_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'");
				  $total_ = getFromTable("select sum(karcis_umum + jumlah + total_bedah + jumlah_penunjang) from rsv_layanan_interne2 where tgl_keluar='".$row3a["tgl_keluar"]."'"); 
			
			if ($_GET["mRAWAT"]=='103') {	  
			?>
			 <tr>
				<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row3a["tgl_keluar"] ?> </td>
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
			
			;$j3a++;}

          $i3a++;
		  }?>
         
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