<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.autocomplete.css">
<?php
		
		/* Untuk Layanan Paket             */
        /* Agung Sunandar 16:53 26/06/2012 */
		
		if($_GET[p]=="p_ginekologi"){
		$PID = "p_ginekologi";
		}elseif($_GET[p]=="p_obsteteri"){
		$PID = "p_obsteteri";
		}else{
		$PID = $PID;
		}

        echo"<hr noshade size='2'>";
        echo "<form name=Form88>";
        echo "<input name=b3 type=button value='Layanan Non Paket'      onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&poli=".$_GET["poli"]."&sub=".$_GET["sub"]."&mr=$mr&list=layanan&sub2=nonpaket\";'>&nbsp;";
        echo "<input name=b10 type=button value='Layanan Paket' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&poli=".$_GET["poli"]."&sub=".$_GET["sub"]."&mr=$mr&list=layanan&sub2=paket\";'>&nbsp;";
        
        // Tambahan BHP 
        echo "<input name=b13 type=button value='Tambah Barang Habis Pakai' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&poli=".$_GET["poli"]."&sub=".$_GET["sub"]."&mr=$mr&list=layanan&sub2=bhp\";'>&nbsp;";
        // ==============================================  
        
        echo "</form>";


        if ($_GET[sub2]=="nonpaket"){
        echo"<div align=center class=form_subtitle1>LAYANAN DAN TINDAKAN MEDIS (NON PAKET)</div>";
        }elseif ($_GET[sub2]=="paket"){
        echo"<div align=center class=form_subtitle1>LAYANAN DAN TINDAKAN MEDIS (PAKET)</div>";
        }else{
        echo"<div align=center class=form_subtitle1>TAMBAH BARANG HABIS PAKAI</div>";
        }
		
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b1.disabled = true;\n";
        echo "document.Form3.b2.disabled = false;\n";
        echo "document.Form3.b4.disabled = false;\n";
        echo "</script>\n";
		
		
		
        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=list VALUE='layanan'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=poli VALUE='".$_GET["mPOLI"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub2 VALUE='".$_GET["sub2"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        
		if($_GET[sub2]=="nonpaket"){
		
		echo "<script language='JavaScript'>\n";
                echo "document.Form88.b3.disabled = true;\n";
                echo "</script>\n";
		
		
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "LAYANAN", "YANG MELAKUKAN TINDAKAN", "JUMLAH", "SATUAN","HARGA SATUAN", "(%) DISCOUNT", "(Rp.) DISCOUNT", "CITO","HARGA TOTAL", ""));
            
//            echo '<pre>';
//            var_dump($_SESSION);
//            echo '</pre>';
            
        if (is_array($_SESSION["layanan"])) {
            $total = 0.00;
            foreach($_SESSION["layanan"] as $k => $l) {

                $q = pg_query("SELECT B.TDESC AS KELAS_TARIF, SUBSTR(A.HIERARCHY,1,6) AS HIE FROM RS00034 A ".
                        "LEFT JOIN RS00001 B ON A.KLASIFIKASI_TARIF_ID = B.TC AND B.TT = 'KTR' ".
                        "WHERE A.ID = $l[id]");
                $qr = pg_fetch_object($q);

                if ($qr->hie == "003002") {
                   $tambahan = " - ".$qr->kelas_tarif;

                }

                $t->printRow2(
                    Array($l["id"], $l["nama"].$tambahan, $l["dokter"], $l["jumlah"], $l["satuan"],
                        number_format($l["harga"],2), $l["persen"]."%", number_format($l["diskon"],2), $l["ciko"],number_format($l["total"],2),
                        "<A HREF='$SC?p=$PID&list=layanan&rg=".$_GET["rg"]."&mr=".$_GET["mr"]."&del=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "CENTER","RIGHT", "LEFT", "RIGHT", "CENTER", "RIGHT", "RIGHT", "RIGHT", "RIGHT")
                );
                $total += $l["total"];
            }
        }
        if (isset($_SESSION["SELECT_LAYANAN"])) {
            $r = pg_query($con,"select * from rsv0034 where id = '" . $_SESSION["SELECT_LAYANAN"] . "'");
            $d = pg_fetch_object($r);
            pg_free_result($r);
			$ext = "  ";
        }else{
			$ext = " disabled ";
		}
		// sfdn, 27-12-2006 -> pembetulan directory gambar = ../simrs/images/*.png
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_LAYANAN"].
			"'>&nbsp;<A HREF='javascript:selectLayanan()'><IMG BORDER=0 SRC='images/icon-view.png'></A>",
			$d->layanan . " - " . $d->klasifikasi_tarif, "<INPUT OnKeyPress='refreshSubmit()' NAME=dokter STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=12 VALUE='"
                        .$_SESSION["SELECT_EMP"]."'>&nbsp;<A HREF='javascript:selectPegawai()'><IMG BORDER=0 SRC='images/icon-view.png'></a>", "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1").
			"'NAME=jumlah TYPE=TEXT SIZE=2 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", $d->satuan, 
			"<INPUT NAME=harga ID=harga STYLE='text-align:center' disabled TYPE=TEXT SIZE=7 MAXLENGTH=10 VALUE='".$d->harga."'>",  
			"<INPUT NAME=persen ID=persen STYLE='text-align:center' TYPE=TEXT SIZE=3 MAXLENGTH=3 VALUE='0'>", 
			"<INPUT NAME=diskon ID=diskon STYLE='text-align:center' TYPE=TEXT SIZE=7 MAXLENGTH=10 VALUE='0'>",
			"<input type=checkbox  name='ciko' value='YA' >", "", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK' $ext >"),
            Array("CENTER", "LEFT", "CENTER","CENTER", "LEFT", "RIGHT", "CENTER", "CENTER", "RIGHT", "RIGHT", "RIGHT")
        );
		// --- eof 27-12-2006 ---
        $t->printRow2(
            Array("", "", "", "", "", "", "","", "", "<b>".number_format($total,2)."</b>",""),
            Array("RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT")
        );
        $t->printTableClose();
        echo "</FORM>";
        
		}elseif($_GET[sub2]=="paket"){
		/* Untuk Layanan Paket             */
		/* Agung Sunandar 16:53 26/06/2012 */
		echo "<script language='JavaScript'>\n";
        echo "document.Form88.b10.disabled = true;\n";
        echo "</script>\n";
		
		$t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "LAYANAN", "YANG MELAKUKAN TINDAKAN", "JUMLAH", "SATUAN",
            "HARGA SATUAN", "(%) DISCOUNT", "(Rp.) DISCOUNT", "HARGA TOTAL", ""));
            
        if (is_array($_SESSION["layanan2"])) {
            $total = 0.00;
            foreach($_SESSION["layanan2"] as $k => $l) {

                $t->printRow2(
                    Array($l["id"], $l["nama"], $l["dokter"], $l["jumlah"], $l["satuan"],
                        number_format($l["harga"],2), $l["persen"]."%", number_format($l["diskon"],2), number_format($l["total"],2),
                        "<A HREF='$SC?p=$PID&list=layanan&rg=".$_GET["rg"]."&mr=".$_GET["mr"]."&sub=".$_GET["sub"]."&sub2=".$_GET["sub2"]."&del1=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "CENTER","RIGHT", "LEFT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "CENTER")
                );
                $total += $l["total"];
            }
        }
        
        if (isset($_SESSION["SELECT_LAYANAN2"])) {
            $r = pg_query($con,"select * from rs99996 where id = '" . $_SESSION["SELECT_LAYANAN2"] . "'");
            $d = pg_fetch_object($r);
            pg_free_result($r);
            $hrg = ($d->harga_paket) ;	
            $ext = " ";
        }else{
            $ext = " disabled ";
        }
		
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit()' NAME=layanan2 STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=10 VALUE='".$_SESSION["SELECT_LAYANAN2"].
			"'>&nbsp;<A HREF='javascript:selectLayanan2()'><IMG BORDER=0 SRC='images/icon-view.png'></A>",
			$d->description , "<INPUT OnKeyPress='refreshSubmit()' NAME=dokter2 STYLE='text-align:center' TYPE=TEXT SIZE=5 MAXLENGTH=12 VALUE='"
            .$_SESSION["SELECT_EMP2"]."'>&nbsp;<A HREF='javascript:selectPegawai2()'><IMG BORDER=0 SRC='images/icon-view.png'></a>", "<INPUT VALUE='".(isset($_GET["jumlah"]) ? $_GET["jumlah"] : "1").
			"'NAME=jumlah OnKeyPress='refreshSubmit()' TYPE=TEXT SIZE=2 MAXLENGTH=10 VALUE='1' STYLE='text-align:right'>", "KALI", 
			"<INPUT NAME=harga ID=harga STYLE='text-align:center' disabled TYPE=TEXT SIZE=7 MAXLENGTH=10 VALUE='".$hrg."'>",  
			"<INPUT NAME=persen ID=persen STYLE='text-align:center' TYPE=TEXT SIZE=3 MAXLENGTH=3 VALUE='0'>", 
			"<INPUT NAME=diskon ID=diskon STYLE='text-align:center' TYPE=TEXT SIZE=7 MAXLENGTH=10 VALUE='0'>",
			"", "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK' $ext >"),
            Array("CENTER", "LEFT", "CENTER","CENTER", "LEFT", "RIGHT", "RIGHT", "RIGHT", "LEFT", "CENTER")
        );
		// untuk warning pada stok obat dalam paket yang nol  Agung SUnandar 9:02 16/07/2012
			
			$cek_qty= getFromTable("select 'qty_'||tc from rs00001 where tt='GDP' and tc_poli='".$_GET["poli"]."' ") ;
			
			
			$SQL = "select a.*, b.obat,b.satuan,c.$cek_qty as qty_ruang  from rs99997 a
					left join rsv0004 b on b.id=a.item_id 
					left join rs00016a c on c.obat_id=a.item_id
					where a.preset_id = '".$d->id."' and a.trans_type='OBI' " ;
            @$r1 = pg_query($con,$SQL);
			@$n1 = pg_num_rows($r1);
			
			$SQL2 = "select a.*, b.layanan, c.tdesc as satuan  from rs99997 a
					left join rs00034 b on b.id=a.item_id 
					left join rs00001 c on c.tt='SAT' and c.tc=b.satuan_id
					where a.preset_id = '".$d->id."' and a.trans_type='LYN' " ;
            @$r2 = pg_query($con,$SQL2);
			@$n2 = pg_num_rows($r2);
			
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
			
			$row2=0;
			$i2= 1 ;
			$j2= 1 ;
			$last_id=1;		
				
			while (@$row2 = pg_fetch_array($r2)){
			$wrna= " color='black' "; 
				if (($j2<=$max_row) AND ($i2 >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i2
					
					?>
					
					<tr>	
						<td class="TBL_BODY"><font <?=$wrna?>><b>Tindakan Medis</b></font></td>
						<td class="TBL_BODY"><font <?=$wrna?>><b><?=$row2["layanan"]?></b></font></td>
						
						<td class="TBL_BODY"><font <?=$wrna?>><b>&nbsp;</b></font></td>
						<td class="TBL_BODY"><font <?=$wrna?>><b><?=$row2["qty"]?></b></font></td>
						<td class="TBL_BODY"><font <?=$wrna?>><b><?=$row2["satuan"]?></b></font></td>
						<td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
						<td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
						<td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
						<td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
						<td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
					</tr>
										
					<?
				;$j++;					
				}
				$i++;	
			}
			
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;		
				
			while (@$row1 = pg_fetch_array($r1)){
			 IF ($row1["qty_ruang"] <= 0) {
					$wrna= " color='red' ";
					}else{$wrna= " color='black' ";} 
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i
					
					?>
					
					<tr>	
						<td class="TBL_BODY"><font <?=$wrna?>><b>Obat/BHP</b></font></td>
						<td class="TBL_BODY"><font <?=$wrna?>><b><?=$row1["obat"]?></b></font></td>
						
						<td class="TBL_BODY"><font <?=$wrna?>><b>Stok Ruangan <?=$row1["qty_ruang"]?></b></font></td>
						<td class="TBL_BODY"><font <?=$wrna?>><b><?=$row1["qty"]?></b></font></td>
						<td class="TBL_BODY"><font <?=$wrna?>><b><?=$row1["satuan"]?></b></font></td>
						<td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
						<td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
						<td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
						<td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
						<td class="TBL_BODY"><font color="red"><b>&nbsp;</b></font></td>
					</tr>
										
					<?
				;$j++;					
				}
				$i++;	
			}
			
			
			// end dr warning Obat
        $t->printRow2(
            Array("", "", "", "", "", "", "", "", number_format($total,2),""),
            Array("RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT")
        );
        $t->printTableClose();
        echo "</FORM>";
		
		
        } else {
                /* Untuk Tambah BHP                */
		/* Agung Sunandar 16:53 26/06/2012 */
		echo "<script language='JavaScript'>\n";
                echo "document.Form88.b13.disabled = true;\n";
                echo "</script>\n";
          // Tambahan BHP       
           if ($_SESSION["SELECT_OBAT2"]) {
           $namaObat = getFromTable("select obat from rsv0004 where id = '".$_SESSION["SELECT_OBAT2"]."'");
           $hargaObat = getFromTable("select harga from rsv0004 where id = '".$_SESSION["SELECT_OBAT2"]."'");
           $satuan = getFromTable("select satuan from rsv0004 where id = '".$_SESSION["SELECT_OBAT2"]."'");
           $ext = "  ";
            }else{
                $ext = " disabled ";
            }
		
		$x_racikan = " <SELECT NAME='is_racikan'>\n";
        $x_racikan .= "<OPTION VALUE=N>N</OPTION>\n";
        $x_racikan .= "<OPTION VALUE=Y>Y</OPTION>\n";
        "</SELECT></TD>\n";
		
        $cek_qty= getFromTable("select 'qty_'||tc from rs00001 where tt='GDP' and tc_poli='".$_GET["poli"]."' ") ;
        $qty= getFromTable("select tc from rs00001 where tt='GDP' and tc_poli='".$_GET["poli"]."' ") ;
        //echo $cek_qty;
        //$q=  getFromTable("select $cek_qty from rs00016a where obat_id='".$_SESSION["SELECT_OBAT2"]."'"); 
		$q=  getFromTable("select qty_ri from rs00016a where obat_id='".$_SESSION["SELECT_OBAT2"]."'"); 
        $x_jumlah2 = " <SELECT name='jumlah_obat'>\n";
          for ($i='1'; $i<=$q; $i++){
                 if ($i=='1') {
             $x_jumlah2  .= "<option value=$i selected>$i</option>\n";

          }
            else {
            $x_jumlah2  .= " <option value=$i>$i</option>\n";
          }
          }
        "</SELECT>\n";
        
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "Nama Obat/BHP", "Satuan", "Jumlah", "Harga Satuan", "(%) Discount", "(Rp.) Discount", "Harga Total", ""));


        if (is_array($_SESSION["obat2"])) {


            foreach($_SESSION["obat2"] as $k => $l) {
                $t->printRow2(
                    Array($l["id"], $l["desc"],$l["satuan"], $l["jumlah"], number_format($l["harga"],2), 
                    $l["persen"]."%", number_format($l["diskon"],2), number_format($l["total"],2),
                    "<A HREF='$SC?p=$PID&list=resepobat&rg=".$_GET["rg"]."&mr=".$_GET["mr"]."&del-obat2=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "CENTER", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "CENTER")
                );
            }



        }
        
	// sfdn, 27-12-2006 -> pembetulan directory icon = ../simrs/images/*.png
        $t->printRow2(
            Array("<INPUT OnKeyPress='refreshSubmit2()' NAME=obat2 STYLE='text-align:center' TYPE=TEXT SIZE=5
            MAXLENGTH=10 VALUE='".$_SESSION["SELECT_OBAT2"]."'>&nbsp;<A HREF='javascript:selectObat()'>
            <IMG BORDER=0 SRC='images/icon-view.png'></A>", $namaObat,
            $satuan,//penambahan dosis
            $x_jumlah2,
            //number_format($hargaObat,2),"", 
            "<INPUT NAME=harga ID=harga STYLE='text-align:center' disabled TYPE=TEXT SIZE=7 MAXLENGTH=10 VALUE='".$hargaObat."'>",  
			"<INPUT NAME=persen ID=persen STYLE='text-align:center' TYPE=TEXT SIZE=3 MAXLENGTH=3 VALUE='0'>", 
			"<INPUT NAME=diskon ID=diskon STYLE='text-align:center' TYPE=TEXT SIZE=7 MAXLENGTH=10 VALUE='0'>","", 
            "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK' $ext>"),
            Array("CENTER", "LEFT", "CENTER","LEFT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "CENTER", "CENTER")
        );
	// --- eof 27-12-2006 ---
        $t->printTableClose();
        echo "</FORM>";
        
        echo "\n<script language='JavaScript'>\n";
        echo "function selectObat() {\n";
        echo "    sWin = window.open('popup/obat004.php?mOBT=061&gudang=".$qty."', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
        
                }
	// ===============================================	
		
        echo "<form name='Form10' action='actions/p_pelayanan.insert.php' method=POST>";
        echo "<input type=hidden name=p value='".$_GET["p"]."'>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub2 VALUE='".$_GET["sub2"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mr VALUE='".$_GET["mr"]."'>";
        echo "<input type=hidden name=rawatan value='".$rawatan."'>";
        echo "<INPUT TYPE=HIDDEN NAME=poli VALUE='".$_GET["mPOLI"]."'>";
        echo "<input type=hidden name=list value='layanan'>";
		echo "<input type=hidden name=sub2 value='".$_GET["sub2"]."'>";
        echo "<br><div align=right><input type=button value='Simpan' onClick='document.Form10.submit()'>&nbsp;";
        echo "</form><br>";
        echo "<br>";
		
		
?>

<script type="text/javascript">
    $(function() {
		$("#persen").keyup( function(){
            var hargaSatuan1 = parseFloat($('#harga').val());
            var hargaPersen = parseFloat($('#persen').val());
            
            if(hargaPersen > 100){
                alert('diskon tidak boleh lebih dari harga satuan !');
                $('#persen').val(0);
                $('#diskon').val(0);
                return false;
            } else {
            	jumlahHargaPersen = (hargaPersen/100)*hargaSatuan1;
            	$('#diskon').val(jumlahHargaPersen);
            }
        });
        
        $("#diskon").keyup( function(){
        	var hargaSatuan2 = parseFloat($('#harga').val());
            var hargaDiskon = parseFloat($('#diskon').val());
            
            if(hargaDiskon > hargaSatuan2){
                alert('diskon tidak boleh lebih dari harga satuan !');
                $('#persen').val(0);
                $('#diskon').val(0);
                return false;
            } else {
            	jumlahHargaDiskon = (hargaDiskon/hargaSatuan2)*100 ;
            	$('#persen').val(jumlahHargaDiskon);
            } 
        });
    });
</script>
