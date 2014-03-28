<?php
// Data Penerimaan Penerimaan
 if (!$GLOBALS['print']){
		title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > Penerimaan Falmakes");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Penerimaan Falmakes");
    }
	
	$ext = "OnChange = 'Form1.submit();'";
	//echo $_SESSION["gr"];
	/*if ($_SESSION["gr"]=="KEUANGAN"){
	$sr= "a.po_status=1 ";
	}elseif ($_SESSION["gr"]=="GUDANG"){
		$sr= "a.po_status in ('0')";
	}else{
		$sr= "a.po_status in ('0')";
	} */
	
	if(!$GLOBALS['print']){
		echo "<br /><br />";
		echo "<DIV ALIGN=RIGHT>";
        echo "<TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID >";
        echo "<TD >Pencarian PO ID / Supplier : <INPUT TYPE=TEXT NAME=search VALUE='" . $_GET["search"] . "'></TD>";
        echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

        echo "</TR></FORM></TABLE>";
        echo "</DIV>";
		echo "<br />";
	}
	echo "<br/>";
	if($_GET['search']){
		$sql_add = "and (upper(a.po_id) like '%".strtoupper($_GET[search])."%' or upper(b.nama) like '%".strtoupper($_GET[search])."%' )";
	}else{
		$sql_add = "and date_part('month',po_tanggal)='".date('m')."' ";
	}
	$SQL = "select a.no_faktur,a.po_id,tanggal(a.po_tanggal,0) as tanggal,
				b.nama,a.po_personal,
			case when a.po_status=0 then 'Barang Belum Terima' else 'Sudah Terima' end as po_status,
			tanggal(a.jatuh_tempo,0) as jatuh_tempo, 
			((select count(ee.item_id) as jumlah from c_po_item ee where po_id=a.po_id) - 
			(select count(ff.item_id) as jumlah from c_po_item_terima ff where ff.po_id=a.po_id)) AS jumlah
			from c_po a,rs00028 b where a.supp_id::text=b.id $sql_add
			group by a.no_faktur,a.po_id,b.nama,a.po_tanggal,a.po_personal,a.po_status,a.jatuh_tempo
			order by a.po_tanggal desc";
			@$r1 = pg_query($con,$SQL);
			@$n1 = pg_num_rows($r1);
	//echo $SQL;
   			$max_row= $n1 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">PO ID</td>
				<td class="TBL_HEAD"align="center">TANGGAL</td>
				<td class="TBL_HEAD"align="center">ESTIMASI TERIMA <br> (HARI)</td>
				<td class="TBL_HEAD"align="center">NAMA SUPPLIER</td>	
				<td class="TBL_HEAD"align="center">PENANGGUNG JAWAB</td>
				<td class="TBL_HEAD"align="center">STATUS</td>
				<!--td class="TBL_HEAD"align="center">JATUH TEMPO</td-->
				<td class="TBL_HEAD"align="center">SISA BARANG <BR> BELUM TERIMA</td>
				<?php if($_SESSION["uid"]=="keuangan"){?>
				<!--td width="10%" align="center" class="TBL_HEAD">INPUT FAKTUR</td-->
				<?} elseif($_SESSION["uid"]=="gudang"){?>
				<td width="10%" align="center" class="TBL_HEAD">VIEW DETAIL</td>
				<?}else{?>
				<td width="10%" align="center" class="TBL_HEAD">INPUT BONUS</td>
				<?php if ($_SESSION["gr"]=="RSBL-KEUANGAN"){?>
				<td width="10%" align="center" class="TBL_HEAD">PEMBAYARAN</td>
				<?php }ELSE if ($_SESSION["uid"]=="root"){?>
				<td width="10%" align="center" class="TBL_HEAD">PEMBAYARAN</td>
				<td width="10%" align="center" class="TBL_HEAD">VIEW DETAIL</td>
				
				<?}ELSE {?>
				<td width="10%" align="center" class="TBL_HEAD">VIEW DETAIL</td>
				
				<?}}?>
			</tr>
			
	
		<?	
			$jml_tagihan= 0;
			$jml_dokter= 0;
			$jml_rs= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i; 	
						$jumlah1=getFromTable("select count(item_id) as jumlah from c_po_item_terima where po_id='".$row1["po_id"]."'");
						$jumlah2=getFromTable("select count(item_id) as jumlah from c_po_item where po_id='".$row1["po_id"]."'");
						$status=$jumlah2-$jumlah1;
						$hari=getFromTable("select d.tanggal_terima-a.po_tanggal as hari from c_po a, c_po_item_terima d where a.po_id=d.po_id and a.po_id='".$row1["po_id"]."'");
						$jam=getFromTable("select EXTRACT(HOUR FROM (d.jam_terima::time-a.po_jam::time)) as jam as hari from c_po a, c_po_item_terima d where a.po_id=d.po_id and a.po_id='".$row1["po_id"]."'");
						
						//if ($status=='0'){
						//}
						///else{
					?>
					
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BODY" align="center"><?=$no ?> </td>
			        	<td class="TBL_BODY" align="center"><?=$row1["po_id"] ?> </td>
						<td align="center" class="TBL_BODY"><?=$row1["tanggal"] ?>  </td>
						<td align="center" class="TBL_BODY"><? echo $hari; ?> Hari <? echo $jam ?> Jam</td>
						<td align="left" class="TBL_BODY"><?=$row1["nama"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["po_personal"] ?></td>
						<?php
						$jumlah1=getFromTable("select count(item_id) as jumlah from c_po_item_terima where po_id='".$row1["po_id"]."'");
						$jumlah2=getFromTable("select count(item_id) as jumlah from c_po_item where po_id='".$row1["po_id"]."'");
						$status=$jumlah2-$jumlah1;
						
						if ($status=='0'){
						?>
						<td align="center" class="TBL_BODY">Lengkap</td>
						<?php
						}else if ($status==$jumlah2){
						?>
						<td align="center" class="TBL_BODY">Belum Diterima</td>
						<?php
						}else{
						?><td align="center" class="TBL_BODY">Belum Lengkap</td>
						<?php
						}
						?>
						<!--td align="center" class="TBL_BODY"><?=$row1["jatuh_tempo"] ?></td-->
						<td align="center" class="TBL_BODY"><?=$row1["jumlah"] ?></td>
						
						<?php if ($_SESSION["uid"]=="keuangan"){?>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=edit1&poid=".$row1["po_id"]."'>".
                        icon("edit","Input Faktur")."</A>&nbsp;&nbsp;";?></td>						
						<?}elseif($_SESSION["uid"]=="gudang"){?>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=view&poid=".$row1["po_id"]."'>".icon("view","Proses Barang")."</A>";?></td>
						<?}else{?>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=bonus&poid=".$row1["po_id"]."'>".
                        icon("edit","Input Bonus")."</A>&nbsp;&nbsp;";?></td>
						<?php if ($_SESSION["gr"]=="RSBL-KEUANGAN"){?>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=edit1&poid=".$row1["po_id"]."'>".
                        icon("edit","Input Faktur")."</A>&nbsp;&nbsp;";?></td>
						<?php }else if ($_SESSION["uid"]=="root"){?>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=edit1&poid=".$row1["po_id"]."'>".
                        icon("edit","Input Faktur")."</A>&nbsp;&nbsp;";?></td>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=view&poid=".$row1["po_id"]."'>".icon("view","Proses Barang")."</A>";?></td>
						<?}else{?>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=view&poid=".$row1["po_id"]."'>".icon("view","Proses Barang")."</A>";?></td>
						<?}
						}?>
						
					</tr>	

					<?;$j++;					
				//}
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			
			}
			
			?>

</TABLE>
<?
?>
