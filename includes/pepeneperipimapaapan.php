<?php
// Data Penerimaan Penerimaan
 if (!$GLOBALS['print']){
		title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > Penerimaan Perbekalan Farmasi");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Penerimaan Perbekalan Farmasi");
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
		$sql_add = "and (upper(a.po_id) like '%".strtoupper($_GET[search])."%' or upper(b.nama) like '%".strtoupper($_GET[search])."%' ) and po_status in('0','1')";
	}else{
		$sql_add = "and po_status in('0','1') and date_part('month',po_tanggal)='".date('m')."' ";
	}
	$SQL = "select a.no_faktur,a.po_id,tanggal(a.po_tanggal,0) as tanggal,b.nama,a.po_personal,
			case when a.po_status=0 then 'Barang Belum Terima' else 'Sudah Terima' end as po_status,
			tanggal(a.jatuh_tempo,0) as jatuh_tempo 
			from c_po a,rs00028 b where a.supp_id::text=b.id $sql_add
			group by a.no_faktur,a.po_id,b.nama,a.po_tanggal,a.po_personal,a.po_status,a.jatuh_tempo order by a.po_tanggal desc";
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
				<td class="TBL_HEAD"align="center">NAMA SUPPLIER</td>	
				<td class="TBL_HEAD"align="center">PENANGGUNG JAWAB</td>
				<td class="TBL_HEAD"align="center">STATUS</td>
				<td class="TBL_HEAD"align="center">JATUH TEMPO</td>
				<?php if($_SESSION["uid"]=="keuangan"){?>
				<td width="10%" align="center" class="TBL_HEAD">INPUT FAKTUR</td>
				<?} elseif($_SESSION["uid"]=="gudang"){?>
				<td width="10%" align="center" class="TBL_HEAD">VIEW DETAIL</td>
				<?}else{?>
				<td width="10%" align="center" class="TBL_HEAD">INPUT BONUS</td>
				<td width="10%" align="center" class="TBL_HEAD">INPUT FAKTUR</td>
				<td width="10%" align="center" class="TBL_HEAD">VIEW DETAIL</td>
				<?}?>
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
					$no=$i 	
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BODY" align="center"><?=$no ?> </td>
			        	<td class="TBL_BODY" align="center"><?=$row1["po_id"] ?> </td>
						<td align="center" class="TBL_BODY"><?=$row1["tanggal"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["nama"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["po_personal"] ?></td>
						<td align="center" class="TBL_BODY"><?=$row1["po_status"] ?></td>
						<td align="center" class="TBL_BODY"><?=$row1["jatuh_tempo"] ?></td>
						
						<?php if ($_SESSION["uid"]=="keuangan"){?>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=edit1&poid=".$row1["po_id"]."'>".
                        icon("edit","Input Faktur")."</A>&nbsp;&nbsp;";?></td>						
						<?}elseif($_SESSION["uid"]=="gudang"){?>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=view&poid=".$row1["po_id"]."'>".icon("view","Proses Barang")."</A>";?></td>
						<?}else{?>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=bonus&poid=".$row1["po_id"]."'>".
                        icon("edit","Input Bonus")."</A>&nbsp;&nbsp;";?></td>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=edit1&poid=".$row1["po_id"]."'>".
                        icon("edit","Input Faktur")."</A>&nbsp;&nbsp;";?></td>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=view&poid=".$row1["po_id"]."'>".icon("view","Proses Barang")."</A>";?></td>
						<?}?>
						
					</tr>	

					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>

</TABLE>
<?
?>
