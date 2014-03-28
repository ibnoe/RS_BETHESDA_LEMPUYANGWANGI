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
                
                <td class="TBL_HEAD" rowspan="2"><div align="center">TINDAKAN MEDIK</div></td>
                <td class="TBL_HEAD" colspan="3"><div align="center">KONSULTASI</div></td>
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

                <td class="TBL_HEAD"><div align="center">DR.&nbsp;GIZI</div></td>
                <td class="TBL_HEAD"><div align="center">AHLI&nbsp;GIZI</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
                <td class="TBL_HEAD"><div align="center">RADIOLOGI</div></td>
                <td class="TBL_HEAD"><div align="center">LAB. KLINIK</div></td>
                <td class="TBL_HEAD"><div align="center">LAB.&nbsp;PA</div></td>
                <td class="TBL_HEAD"><div align="center">REHABILITASI MEDIK</div></td>
                <td class="TBL_HEAD"><div align="center">INSTALASI</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
            </tr>


        <?
				$sql14a = "select to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,tgl_keluar from rsv_layanan_gizi2 where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by tgl_keluar order by tgl_keluar asc";
				@$r14a = pg_query($con,$sql14a);
				@$n14a = pg_num_rows($r14a);

				$max_row14a= 9999999 ;
				$mulai14a = $HTTP_GET_VARS["rec"] ;
				if (!$mulai14a){$mulai14a=1;}
				
				
				$i14a= 1 ;
				$j14a= 1 ;
				$last_id14a=1;
				while (@$row14a = pg_fetch_array($r14a)){
					  if (($j14a<=$max_row14a) AND ($i14a >= $mulai14a)){

						 $no14a=$i14a;

			$sql14 = "select poli,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_kwitansi, nama,mr_no,
                    karcis_umum, radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,rj_igd_gizi,konsul_dr_gizi,konsul_ahli_gizi,jumlah_konsul, (karcis_umum + rj_igd_gizi +  jumlah_penunjang + jumlah_konsul) as total
                    from rsv_layanan_gizi2
                    where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2') group by radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,poli,tgl_keluar, nama,mr_no, karcis_umum, rj_igd_gizi,konsul_dr_gizi,konsul_ahli_gizi,jumlah_konsul
                    order by tgl_keluar asc";


			@$r14 = pg_query($con,$sql14);
            @$n14 = pg_num_rows($r14);

            $max_row14= 9999999 ;
            $mulai14 = $HTTP_GET_VARS["rec"] ;
            if (!$mulai14){$mulai14=1;}
				
							$row14=0;
							$i14= 1 ;
							$j14= 1 ;
							$last_id14=1;
							while (@$row14 = pg_fetch_array($r14)){
								  if (($j14<=$max_row14) AND ($i14 >= $mulai14)){

									 $no14=$i14;
								  if($row14["poli"]==$row1["poli"] and $row14["tgl_lunas"]==$row14a["tgl_lunas"]) {
								  if($row14["poli"]=="111"){
								  ?>
            <tr>
                <td class="TBL_BODY" align="center"><?=$no14 ?></td>
                <td class="TBL_BODY" align="center"><?=$row14["tgl_lunas"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row14["tgl_kwitansi"] ?></td>
                <td class="TBL_BODY" align="left"><?=$row14["nama"] ?></td>
                <td class="TBL_BODY" align="center"><?=$row14["mr_no"] ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row14["karcis_umum"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row14["karcis_umum"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>


                <td class="TBL_BODY" align="right"><?=number_format($row14["rj_igd_gizi"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row14["konsul_dr_gizi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row14["konsul_ahli_gizi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row14["jumlah_konsul"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row14["radiologi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row14["lab_klinik"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row14["lab_pa"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row14["rehab"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row14["instalasi"],2,",",".") ?></td>
                <td class="TBL_BODY" align="right"><?=number_format($row14["jumlah_penunjang"],2,",",".") ?></td>

                <td class="TBL_BODY" align="right"><?=number_format($row14["total"],2,",",".") ?></td>
            </tr>
			
			
			<?
			}
			$karcis_1=$karcis_1 + $row14["karcis_umum"];
			$karcis_total=$karcis_total + $row14["karcis_umum"];
			
			$rj_igd_gizi=$rj_igd_gizi + $row14["rj_igd_gizi"];
			$konsul_dr_gizi=$konsul_dr_gizi + $row14["konsul_dr_gizi"];
			$konsul_ahli_gizi=$konsul_ahli_gizi + $row14["konsul_ahli_gizi"];
            $jumlah_konsul=$jumlah_konsul + $row14["jumlah_konsul"];
			
            $penunjang_1=$penunjang_1 + $row14["radiologi"];
            $penunjang_2=$penunjang_2 + $row14["lab_klinik"];
            $penunjang_3=$penunjang_3 + $row14["lab_pa"];
            $penunjang_4=$penunjang_4 + $row14["rehab"];
            $penunjang_5=$penunjang_5 + $row14["instalasi"];
            $penunjang_total=$penunjang_total + $row14["jumlah_penunjang"];
            $total=$total + $row14["total"];
			

				;$j14++;}

          $i14++;}
        }	
		
				  $karcis_umum = getFromTable("select sum(karcis_umum) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $rj_igd_gizi_ = getFromTable("select sum(rj_igd_gizi) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $konsul_dr_gizi_ = getFromTable("select sum(konsul_dr_gizi) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $konsul_ahli_gizi_ = getFromTable("select sum(konsul_ahli_gizi) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $jumlah_konsul_ = getFromTable("select sum(jumlah_konsul) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $penunjang_1_ = getFromTable("select sum(radiologi) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $penunjang_2_ = getFromTable("select sum(lab_klinik) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $penunjang_3_ = getFromTable("select sum(lab_pa) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $penunjang_4_ = getFromTable("select sum(rehab) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $penunjang_5_ = getFromTable("select sum(instalasi) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $penunjang_total_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'");
				  $total_ = getFromTable("select sum(karcis_umum + rj_igd_gizi +  jumlah_penunjang + jumlah_konsul) from rsv_layanan_gizi2 where tgl_keluar='".$row14a["tgl_keluar"]."'"); 
			
			if ($_GET["mRAWAT"]=="111") {	  
			?>
			 <tr>
				<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row14a["tgl_keluar"] ?> </td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
				
				<td class="TBL_FOOT" align="right"><?=number_format($rj_igd_gizi_,2,",",".") ?></td>
				<td class="TBL_FOOT" align="right"><?=number_format($konsul_dr_gizi_,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($konsul_ahli_gizi_,2,",",".") ?></td>
				<td class="TBL_FOOT" align="right"><?=number_format($jumlah_konsul_,2,",",".") ?></td>
                
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
			
			;$j14a++;}

          $i14a++;
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
				
				<td class="TBL_FOOT" align="right"><?=number_format($rj_igd_gizi,2,",",".") ?></td>
				<td class="TBL_FOOT" align="right"><?=number_format($konsul_dr_gizi,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($konsul_ahli_gizi,2,",",".") ?></td>
				<td class="TBL_FOOT" align="right"><?=number_format($jumlah_konsul,2,",",".") ?></td>
				
				<td class="TBL_FOOT" align="right"><?=number_format($penunjang_1,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_2,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_3,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_4,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_5,2,",",".") ?></td>
                <td class="TBL_FOOT" align="right"><?=number_format($penunjang_total,2,",",".") ?></td>

                <td class="TBL_FOOT" align="right"><?=number_format($total,2,",",".") ?></td>
            </tr>
</TABLE>