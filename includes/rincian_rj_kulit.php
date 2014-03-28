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
                <td class="TBL_HEAD" rowspan="2"><div align="center">TINDAKAN&nbsp;MEDIK NON&nbsp;BEDAH</div></td>
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
                <td class="TBL_HEAD"><div align="center">RADIOLOGI</div></td>
                <td class="TBL_HEAD"><div align="center">LAB. KLINIK</div></td>
                <td class="TBL_HEAD"><div align="center">LAB.&nbsp;PA</div></td>
                <td class="TBL_HEAD"><div align="center">REHABILITASI MEDIK</div></td>
                <td class="TBL_HEAD"><div align="center">INSTALASI</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
                </tr>


         <?
				$sql12a = "select to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,tgl_keluar from rsv_layanan_kulit2 where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by tgl_keluar order by tgl_keluar asc";
				@$r12a = pg_query($con,$sql12a);
				@$n12a = pg_num_rows($r12a);

				$max_row12a= 9999999 ;
				$mulai12a = $HTTP_GET_VARS["rec"] ;
				if (!$mulai12a){$mulai12a=1;}
				
				
				$i12a= 1 ;
				$j12a= 1 ;
				$last_id12a=1;
				while (@$row12a = pg_fetch_array($r12a)){
					  if (($j12a<=$max_row12a) AND ($i12a >= $mulai12a)){

						 $no12a=$i12a;

			$sql12 = "select poli,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_kwitansi, nama,mr_no,
                    karcis_umum, radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,bedah_kulit_1,bedah_kulit_2,bedah_kulit_3,bedah_kulit_utama,
                    bedah_kulit_vip,total_bedah,rj_igd_kulit, (karcis_umum + rj_igd_kulit + total_bedah + jumlah_penunjang) as total
                    from rsv_layanan_kulit2
                    where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,total_bedah,poli,tgl_keluar, nama,mr_no, karcis_umum, bedah_kulit_1,bedah_kulit_2,bedah_kulit_3,bedah_kulit_utama,
                    bedah_kulit_vip,rj_igd_kulit
                    order by tgl_keluar asc";

			@$r12 = pg_query($con,$sql12);
            @$n12 = pg_num_rows($r12);

            $max_row12= 9999999 ;
            $mulai12 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai12){$mulai12=1;}
				
							$row12=0;
							$i12= 1 ;
							$j12= 1 ;
							$last_id12=1;
							while (@$row12 = pg_fetch_array($r12)){
								  if (($j12<=$max_row12) AND ($i12 >= $mulai12)){

									 $no12=$i12;
								  if($row12["poli"]==$row1["poli"] and $row12["tgl_lunas"]==$row12a["tgl_lunas"]) {
								  if($row12["poli"]=="109"){
								  ?>
            <tr>
                <td class="TBL_BODY" align="center"><?=$no12 ?></td>
                <td class="TBL_BODY" align="center"><?=$row12["tgl_lunas"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row12["tgl_kwitansi"] ?></td>
                <td class="TBL_BODY" align="left"><?=$row12["nama"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row12["mr_no"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["karcis_umum"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["karcis_umum"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row12["bedah_kulit_1"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["bedah_kulit_2"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["bedah_kulit_3"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["bedah_kulit_utama"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["bedah_kulit_vip"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["total_bedah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row12["rj_igd_kulit"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row12["radiologi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["lab_klinik"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["lab_pa"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["rehab"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["instalasi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row12["jumlah_penunjang"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row12["total"],2,",",".") ?></td>
            </tr>
			
			
			<?
			}
			$karcis_1=$karcis_1 + $row12["karcis_umum"];
			$karcis_total=$karcis_total + $row12["karcis_umum"];
			
            $bedah_1=$bedah_1 + $row12["bedah_kulit_1"];
            $bedah_2=$bedah_2 + $row12["bedah_kulit_2"];
            $bedah_3=$bedah_3 + $row12["bedah_kulit_3"];
            $bedah_4=$bedah_4 + $row12["bedah_kulit_utama"];
            $bedah_5=$bedah_5 + $row12["bedah_kulit_vip"];
            $bedah_total=$bedah_total + $row12["total_bedah"];
            $rj_igd=$rj_igd + $row12["rj_igd_kulit"];
            $penunjang_1=$penunjang_1 + $row12["radiologi"];
            $penunjang_2=$penunjang_2 + $row12["lab_klinik"];
            $penunjang_3=$penunjang_3 + $row12["lab_pa"];
            $penunjang_4=$penunjang_4 + $row12["rehab"];
            $penunjang_5=$penunjang_5 + $row12["instalasi"];
            $penunjang_total=$penunjang_total + $row12["jumlah_penunjang"];
            $total=$total + $row12["total"];
			

				;$j12++;}

          $i12++;}
        }	
		
				  $karcis_umum = getFromTable("select sum(karcis_umum) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $bedah_1_ = getFromTable("select sum(bedah_kulit_1) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $bedah_2_ = getFromTable("select sum(bedah_kulit_2) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $bedah_3_ = getFromTable("select sum(bedah_kulit_3) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $bedah_4_ = getFromTable("select sum(bedah_kulit_utama) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $bedah_5_ = getFromTable("select sum(bedah_kulit_vip) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $bedah_total_ = getFromTable("select sum(total_bedah) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $rj_igd_ = getFromTable("select sum(rj_igd_kulit) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $penunjang_1_ = getFromTable("select sum(radiologi) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $penunjang_2_ = getFromTable("select sum(lab_klinik) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $penunjang_3_ = getFromTable("select sum(lab_pa) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $penunjang_4_ = getFromTable("select sum(rehab) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $penunjang_5_ = getFromTable("select sum(instalasi) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $penunjang_total_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'");
				  $total_ = getFromTable("select sum(karcis_umum + rj_igd_kulit + total_bedah + jumlah_penunjang) from rsv_layanan_kulit2 where tgl_keluar='".$row12a["tgl_keluar"]."'"); 
			
			if ($_GET["mRAWAT"]=='109') {	  
			?>
			 <tr>
				<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row12a["tgl_keluar"] ?> </td>
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
			
			;$j12a++;}

          $i12a++;
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