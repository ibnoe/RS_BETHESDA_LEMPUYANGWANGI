<?php
/* - edited 100210 -
		- menghapus fungsi trim() dan mengganti type data entitas f.id menjadi character varying
	*/
    	if ($_GET["act"] == "detail_klinik") {
            $sql = "select a.*,b.nama,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg,f.layanan,a.id_poli 
                from c_visit a 
                left join rs00017 b on a.id_dokter = B.ID 
                left join rsv0002 c on a.no_reg=c.id 
                left join rs00006 d on d.id = a.no_reg
                left join rs00008 e on e.no_reg = a.no_reg
                left join rs00034 f on f.id::text = e.item_id::text
                where a.no_reg='{$_GET['rg']}' and a.oid='{$_GET['oid']}'";
                                    
                $r = pg_query($con,$sql);
                $n = pg_num_rows($r);
                if($n > 0) $d = pg_fetch_array($r);
                pg_free_result($r);
                    //echo $sql;exit;			
                $_GET['id'] = $_GET['rg'] ;	
	 			
			//echo"<div class=box>";
			echo "<table width='100%' border='0'><tr><td colspan='2'>";
			//echo"<div class=form_subtitle>PEMERIKSAAN PASIEN</div>";
			echo "</td></tr>";
    		echo "<tr><td>";
    		$f = new ReadOnlyForm();
    		$poli=$_GET["polinya"];
    		$f->text("Poli","<b>".$poli);
    		if ($poli == $setting_poli["igd"]) {
    			include(detail_igd);
    		}
			elseif ($poli == $setting_poli["umum"]){
    			include(detail_umum);
    		}
			elseif ($poli == $setting_poli["mata"]){
    			include(detail_mata);
			}
			elseif ($poli == $setting_poli["gizi"]){
    			include(detail_gizi);
    		}
			elseif ($poli == $setting_poli["peny_dalam"]){
    			include(detail_peny_dalam);
    		}
    		elseif ($poli == $setting_poli["anak"]){
    			include(detail_anak);
    		}
    		elseif ($poli == $setting_poli["gigi"]){
    			include(detail_gigi);
    		}
    		elseif ($poli == $setting_poli["tht"]){
    			include(detail_tht);
    		}
    		elseif ($poli == $setting_poli["bedah"]){
    			include(detail_bedah);
    		}
    		elseif ($poli == $setting_poli["kulit_kelamin"]){
    			include(detail_kulit_kelamin);
    		}
    		elseif ($poli == $setting_poli["akupunktur"]){
    			include(detail_akupunktur);
    		}
    		elseif ($poli == $setting_poli["jantung"]){
    			include(detail_jantung);
    		}
    		elseif ($poli == $setting_poli["paru"]){
    			include(detail_paru);
    		}
    		elseif ($poli == $setting_poli["kebidanan_obstetri"]){
    			include(detail_obstetri);
    		}
    		elseif ($poli == $setting_poli["kebidanan_ginekologi"]){
    			include(detail_ginekologi);
    		}
    		elseif ($poli == $setting_poli["psikiatri"]){
    			include(detail_psikiatri);
    		}
    		elseif ($poli == $setting_poli["laboratorium"]){
    			include(detail_laboratorium);
    		}
                elseif ($poli == $setting_poli["operasi"]){
    			include(detail_operasi);
    		}
    		elseif ($poli == $setting_poli["saraf"]){
    			include(detail_saraf);
    		}
    		elseif ($poli == $setting_poli["radiologi"]){
    			include(detail_radiologi);
    		}
                elseif ($poli == "A01"){
    			include(detail_resume_anak);
    		}
                elseif ($poli == "A01"){
    			include(detail_resume_anak);
    		}
                elseif ($poli == "A02"){
    			include(detail_resume_kebidanan);
    		}
                elseif ($poli == "A03"){
    			include(detail_resume_bayi);
    		}
                elseif ($poli == "B01"){
    			include(detail_grafik_suhu);
    		}
                elseif ($poli == "B02"){
    			include(detail_grafik_ibu);
    		}
                elseif ($poli == "B03"){
    			include(detail_grafik_bayi);
    		}
                elseif ($poli == "C03"){
    			include(detail_ringkasan_masuk_keluar);
    		}
                elseif ($poli == "D04"){
    			include(detail_dokumen_surat_pengantar);
    		}
                elseif ($poli == "E05"){
    			include(detail_riwayat_penyakit);
    		}
                elseif ($poli == "F01"){
    			include(detail_catatan_kebidanan);
    		}
                elseif ($poli == "F02"){
    			include(detail_catatan_bayi_baru);
    		}
                elseif ($poli == "F03"){
    			include(detail_catatan_harian);
    		}
                elseif ($poli == "G02"){
    			include(detail_catatan_laporan_pembedahan);
    		}
                elseif ($poli == "K02"){
    			include(detail_hasil_radiologi);
    		}
                elseif ($poli == "J10"){
    			include(detail_lembar_konsultasi);
    		}
                elseif ($poli == "G03"){
    			include(detail_laporan_pembedahan);
    		}
                elseif ($poli == "I02"){
    			include(detail_pengawasan_pasien_khusus_anak);
    		}
                elseif ($poli == "F04"){
    			include(detail_catatan_perkembangan_bayi);
                }
                elseif ($poli == "K03"){
    			include(detail_hasil_ekg);
                }
                 elseif ($poli == "I03"){
    			include(detail_catatan_obstetri);
                }
                 elseif ($poli == "H03"){
    			include(detail_pemakaian_alat_tindakan_keperawatan);
                }
                elseif ($poli == "I01"){
    			include(detail_asuhan_keperawatan);
                }
                elseif ($poli == "H03"){
    			include(detail_pengawasan_khusus_pasien_dewasa);
                }
                elseif ($poli == "K01"){
    			include(detail_hasil_laboratorium_patologi);
                }
                elseif ($poli == "K04"){
    			include(detail_hasil_usg);
                }elseif ($poli == "H02"){
       			include(detail_catatan_proses_keperawatan);
                }
				elseif ($poli == "205"){
       			include(detail_fisioterapi);
				}
    		else{
                    
    			include(detail_laboratorium);
    		}
    		
			}else {
				echo"<div align=center class=form_subtitle1>RIWAYAT PENYAKIT PASIEN</div>";
		//detail riwayat
		echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'  colspan=2>";
		
		//$f = new Form($SC, "GET");
                            $sql =  "SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,C.TDESC,D.NAMA,A.ID_POLI::text,a.oid  
                                        FROM C_VISIT A 
                                        LEFT JOIN RS00006 B ON A.NO_REG=B.ID 
                                        LEFT JOIN RS00001 C ON A.ID_POLI = C.TC_POLI AND C.TT='LYN'
                                        LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID 
                                        LEFT JOIN RS00001 E ON A.ID_KONSUL = E.TC AND E.TT='LYN'
                                        WHERE  A.user_id != '' and B.MR_NO = '".$_GET["mr"]."' AND A.ID_POLI != 100
                                        GROUP BY A.NO_REG,A.TANGGAL_REG,C.TDESC,D.NAMA,A.ID_POLI,a.oid
                                            union 
                                            SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,'RAWAT INAP - '||C.TDESC,D.NAMA,A.ID_RI,a.oid
                                            FROM C_VISIT_RI A 
                                            LEFT JOIN RS00006 B ON A.NO_REG=B.ID 
                                            LEFT JOIN RS00001 C ON A.ID_RI::text = C.TC::text AND C.TT='LRI'
                                            LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID 
                                            WHERE B.MR_NO = '".$_GET["mr"]."' AND A.ID_RI::text != 100::text
                                            GROUP BY A.NO_REG,A.TANGGAL_REG,C.TDESC,D.NAMA,A.ID_RI,a.oid
                                                union 
                                                SELECT A.NO_REG,TO_CHAR(A.TANGGAL_REG,'DD MON YYYY')AS TANGGAL,TO_CHAR(A.TANGGAL_REG,'HH:MM:SS') AS WAKTU,'PELAYANAN OPERASI',D.NAMA,'209',a.oid
                                                FROM C_VISIT_OPERASI A 
                                                LEFT JOIN RS00006 B ON A.NO_REG=B.ID 
                                                LEFT JOIN RS00017 D ON A.ID_DOKTER = D.ID 
                                                WHERE B.MR_NO = '".$_GET["mr"]."'
                                                GROUP BY A.NO_REG,A.TANGGAL_REG,D.NAMA,a.oid
                                        ";
					
                            $t = new PgTable($con, "100%");
			    $t->SQL = $sql ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
                            $t->ColHidden[6]= true;
                            $t->ColHidden[1]= true;
			    $t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array("TANGGAL PEMERIKSAAN","","WAKTU KUNJUNGAN","KLINIK","DOKTER PEMERIKSA","DETAIL");
                            $t->ColAlign = array("center","center","center","left","left","left","center","center");
                            $t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=riwayat_klinik&act=detail_klinik&polinya=<#5#>&mr=".$_GET["mr"]."&rg=<#0#>&oid=<#6#>'>".icon("view","View")."</A>";	
                            $t->execute();
                            
                            echo"<br>";
                            echo"</div>";
                            echo "</td></tr></table></div>";
    	
			}
?>