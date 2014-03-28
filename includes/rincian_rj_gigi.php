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
                
                <td class="TBL_HEAD" colspan="9"><div align="center">TINDAKAN MEDIK</div></td>
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

                <td class="TBL_HEAD"><div align="center">KONSERVASI</div></td>
                <td class="TBL_HEAD"><div align="center">ORAL SURGERY</div></td>
                <td class="TBL_HEAD"><div align="center">ORTHODONTY</div></td>
                <td class="TBL_HEAD"><div align="center">PAEDONTY (GIGI ANAK)</div></td>
                <td class="TBL_HEAD"><div align="center">PERIODONTOLOGY</div></td>
                <td class="TBL_HEAD"><div align="center">PROSTODONTY</div></td>
                <td class="TBL_HEAD"><div align="center">TINDAKAN MEDIK</div></td>
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
				$sql6a = "select to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,tgl_keluar from rsv_layanan_gigi2 where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by tgl_keluar order by tgl_keluar asc";
				@$r6a = pg_query($con,$sql6a);
				@$n6a = pg_num_rows($r6a);

				$max_row6a= 9999999 ;
				$mulai6a = $HTTP_GET_VARS["rec"] ;
				if (!$mulai6a){$mulai6a=1;}
				
				
				$i6a= 1 ;
				$j6a= 1 ;
				$last_id6a=1;
				while (@$row6a = pg_fetch_array($r6a)){
					  if (($j6a<=$max_row6a) AND ($i6a >= $mulai6a)){

						 $no6a=$i6a;

			$sql6 = "select poli,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_kwitansi, nama,mr_no,
                    karcis_umum, radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,konservasi, oral_surgery,orthodonty,paedonty,periodontologi,
                    prostodonty,tindakan_medik,smf_gigi_lain, jumlah, (karcis_umum + jumlah + jumlah_penunjang) as total
                    from rsv_layanan_gigi2
                    where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,poli,tgl_keluar, nama,mr_no, karcis_umum, konservasi, oral_surgery,orthodonty,paedonty,periodontologi,
                    prostodonty,tindakan_medik,smf_gigi_lain, jumlah
                    order by tgl_keluar asc";


			@$r6 = pg_query($con,$sql6);
            @$n6 = pg_num_rows($r6);

            $max_row6= 9999999 ;
            $mulai6 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai6){$mulai6=1;}
				
							$row6=0;
							$i6= 1 ;
							$j6= 1 ;
							$last_id6=1;
							while (@$row6 = pg_fetch_array($r6)){
								  if (($j6<=$max_row6) AND ($i6 >= $mulai6)){

									 $no6=$i6;
								  if($row6["poli"]==$row1["poli"] and $row6["tgl_lunas"]==$row6a["tgl_lunas"]) {
								  if($row6["poli"]=="105"){
								  ?>
            <tr>
                <td class="TBL_BODY" align="center"><?=$no6 ?></td>
                <td class="TBL_BODY" align="center"><?=$row6["tgl_lunas"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row6["tgl_kwitansi"] ?></td>
                <td class="TBL_BODY" align="left"><?=$row6["nama"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row6["mr_no"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["karcis_umum"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["karcis_umum"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row6["konservasi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["oral_surgery"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["orthodonty"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["paedonty"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["periodontologi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["prostodonty"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["tindakan_medik"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["smf_gigi_lain"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["jumlah"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row6["radiologi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["lab_klinik"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["lab_pa"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["rehab"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["instalasi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row6["jumlah_penunjang"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row6["total"],2,",",".") ?></td>
            </tr>
			
			
			<?
			}
			$karcis_1=$karcis_1 + $row6["karcis_umum"];
			$karcis_total=$karcis_total + $row6["karcis_umum"];
			
			$konservasi=$konservasi + $row6["konservasi"];
			$oral_surgery=$oral_surgery + $row6["oral_surgery"];
            $orthodonty=$orthodonty + $row6["orthodonty"];
            $paedonty=$paedonty + $row6["paedonty"];
            $periodontologi=$periodontologi + $row6["periodontologi"];
            $prostodonty=$prostodonty + $row6["prostodonty"];
            $tindakan_medik=$tindakan_medik + $row6["tindakan_medik"];
			$smf_gigi_lain=$smf_gigi_lain + $row6["smf_gigi_lain"];
            $smf_total=$smf_total + $row6["jumlah"];
			
            $penunjang_1=$penunjang_1 + $row6["radiologi"];
            $penunjang_2=$penunjang_2 + $row6["lab_klinik"];
            $penunjang_3=$penunjang_3 + $row6["lab_pa"];
            $penunjang_4=$penunjang_4 + $row6["rehab"];
            $penunjang_5=$penunjang_5 + $row6["instalasi"];
            $penunjang_total=$penunjang_total + $row6["jumlah_penunjang"];
            $total=$total + $row6["total"];
			

				;$j6++;}

          $i6++;}
        }	
		
				  $karcis_umum = getFromTable("select sum(karcis_umum) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $konservasi_ = getFromTable("select sum(konservasi) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $oral_surgery_ = getFromTable("select sum(oral_surgery) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $orthodonty_ = getFromTable("select sum(orthodonty) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $paedonty_ = getFromTable("select sum(paedonty) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $periodontologi_ = getFromTable("select sum(periodontologi) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $prostodonty_ = getFromTable("select sum(prostodonty) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $tindakan_medik_ = getFromTable("select sum(tindakan_medik) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $smf_gigi_lain_ = getFromTable("select sum(smf_gigi_lain) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $smf_total_ = getFromTable("select sum(jumlah) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $penunjang_1_ = getFromTable("select sum(radiologi) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $penunjang_2_ = getFromTable("select sum(lab_klinik) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $penunjang_3_ = getFromTable("select sum(lab_pa) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $penunjang_4_ = getFromTable("select sum(rehab) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $penunjang_5_ = getFromTable("select sum(instalasi) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $penunjang_total_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'");
				  $total_ = getFromTable("select sum(karcis_umum + jumlah + jumlah_penunjang) from rsv_layanan_gigi2 where tgl_keluar='".$row6a["tgl_keluar"]."'"); 
			
			if ($_GET["mRAWAT"]=="105") {	  
			?>
			 <tr>
				<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row6a["tgl_keluar"] ?> </td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
				
				<td class="TBL_FOOT" align="right"><?=number_format($konservasi_,2,",",".") ?></td>
				<td class="TBL_FOOT" align="right"><?=number_format($oral_surgery_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($orthodonty_,2,",",".") ?></td>
				<td class="TBL_FOOT" align="right"><?=number_format($paedonty_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($periodontologi_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($prostodonty_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($tindakan_medik_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_gigi_lain_,2,",",".") ?></td>
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
			
			;$j6a++;}

          $i6a++;
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
				
				<td class="TBL_FOOT" align="right"><?=number_format($konservasi,2,",",".") ?></td>
				<td class="TBL_FOOT" align="right"><?=number_format($oral_surgery,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($orthodonty,2,",",".") ?></td>
				<td class="TBL_FOOT" align="right"><?=number_format($paedonty,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($periodontologi,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($prostodonty,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($tindakan_medik,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($smf_gigi_lain,2,",",".") ?></td>
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