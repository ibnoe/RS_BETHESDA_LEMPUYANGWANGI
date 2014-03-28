<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.autocomplete.css">
<br>  
<h2>POSTING JURNAL</h2>

<?php
	$f = new Form("actions/coak.insert.php", "POST", "NAME=Form1 id='Form1'");
	$f->hidden("act","new");
	$f->hidden("rg",$_GET["rg"]);
	$f->hidden("PID",$PID);
	$f->hidden("SC",$SC);
?>

<table id="list-pasien" width="15%">
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="10"<?=$font ?>>No</td>
            <td align="CENTER" class="TBL_HEAD" width="40"<?=$font ?>>Kode Akun</td>
            <td align="CENTER" class="TBL_HEAD" width="50"<?=$font ?>>Nama Akun</td>
            <!-- <td align="CENTER" class="TBL_HEAD" width="20"<?//=$font ?>>Normal Balance</td> -->
			<!-- <td align="CENTER" class="TBL_HEAD" width="20"<?//=$font ?>>Nominal</td> -->
            <td align="CENTER" class="TBL_HEAD" width="20"<?=$font ?>>Debet</td>
            <td align="CENTER" class="TBL_HEAD" width="20"<?=$font ?>>Kredit</td>
        </tr>
    </thead>

    <tbody>
<?php
	include ('CoaAk.txt');
		include ('CoaAk.php');


	$rowsData = pg_query($con,"select a.kode, a.nama, a.akun_type from akun_master a 
								where a.kode IN ('110101','110402','110401','210801','410107', ".
								"'410106','410103','410101','410108','410102','210504','420188','410109','410105')"); 
	if(!empty($rowsData)){
		$i=0;
		$qty=0;
		while($rows=pg_fetch_array($rowsData)){
			$i++;
			
			//if ($PendKamOp != '0') {
			
			?>
		<tr>
			<td style="text-align: right;" <?=$font ?>><?php echo $i;?>&nbsp;</td>
			<?php
			$f->hidden("i",$i);
			?>
			<td style="text-align: center;" <?=$font ?>><?php echo $rows['kode'];?>&nbsp;</td>
			<?php
			$f->hidden("kode_".$i."",$rows['kode']);
			?>
			<td style="text-align: left;" <?=$font ?>><?php echo $rows['nama'];?>&nbsp;</td>
			<?php
			$f->hidden("nama_".$i."",$rows['nama']);
			?>
			<!-- <td style="text-align: left;" <?=$font ?>><?php echo $rows['akun_type'];?>&nbsp;</td> -->
			<?php
			$f->hidden("akun_type_".$i."",$rows['akun_type']);
			
			//Tipe Akun
			$posting= getFromTable("select is_coa from rs00008 where no_reg='".$_GET["rg"]."'");
			/*
			if ($posting == 0){
			?>
			<td style="text-align: left;" <?=$font ?>><?php echo $rows['akun_type'];?>&nbsp;</td>
			<?php
			$f->hidden("akun_type_".$i."",$rows['akun_type']);
			}else{
			
			if ($rows['akun_type'] = '110101' ){
			$akun="Kredit";
			?>
			<td style="text-align: left;" <?=$font ?>><?php echo $akun;?>&nbsp;</td>
			<?php
			$f->hidden("akun_type_".$i."",$rows['akun_type']);
			}else if $rows['akun_type'] = '110402'{
			$akun="Debet";
			?>
			<td style="text-align: left;" <?=$font ?>><?php echo $akun;?>&nbsp;</td>
			<?php
			$f->hidden("akun_type_".$i."",$rows['akun_type']);
			}
			}
			*/
			
			//Nominal
			$posting= getFromTable("select is_coa from rs00008 where no_reg='".$_GET["rg"]."'");
			
			if ($posting == 0){
			if ($rows['kode'] == '110101'){  //Pendapatan Kas Besar DB
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $kasbesar;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Debet') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $kasbesar?$kasbesar:'0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$kasbesar);
			}
			
			
			if ($rows['kode'] == '110402'){  //Piutang Pasien Pulang DB
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $piutangPasPul;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Debet') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $piutangPasPul?$piutangPasPul:'0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$piutangPasPul);
			}
			
			
			}else{
			
			
			if ($rows['kode'] == '110101'){ //Split Piutang pasien pulang dan Kas Besar DB
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $piutangPasPul;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Debet') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $piutangPasPul?$piutangPasPul:'0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$piutangPasPul);
			}
			
			
			if ($rows['kode'] == '110402'){ //Split Piutang pasien pulang dan Kas Besar DB
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $kasbesar;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Debet') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $kasbesar?$kasbesar:'0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$kasbesar);
			}
			}
			
			if ($rows['kode'] == '210801'){ //Pendapatan Diterima dimuka CR
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $InputDeposit2;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $InputDeposit2?$InputDeposit2:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$InputDeposit2);
			}
			
			
			if ($rows['kode'] == '410101'){ //Pendapatan IGD CR
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $igd;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $igd?$igd:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$igd);
			}
			
			
			if ($rows['kode'] == '110401'){
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $piutangRanap;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $piutangRanap?$piutangRanap:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$piutangRanap);
			}
			
			
			if ($rows['kode'] == '410106'){
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $farmasi;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $farmasi?$farmasi:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$farmasi);
			}
			
			
			if ($rows['kode'] == '410105'){
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $PendKamOp;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $PendKamOp?$PendKamOp:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$PendKamOp);
			}
			
			
			if ($rows['kode'] == '410107'){
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $lab;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $lab?$lab:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$lab);
			}
			
			
			if ($rows['kode'] == '410102'){
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $PenRajal;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $PenRajal?$PenRajal:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$PenRajal);
			}
			
			
			if ($rows['kode'] == '410103'){
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $ranap;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $ranap?$ranap:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$ranap);
			}
			
			
			if ($rows['kode'] == '210504'){
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $konsul;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $konsul?$konsul:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$konsul);
			}
			
			
			if ($rows['kode'] == '420188'){
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $admin;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $admin?$admin:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$admin);
			}
			
			
			if ($rows['kode'] == '410108'){
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $radiologi;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $radiologi?$radiologi:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$radiologi);
			}
			
			
			if ($rows['kode'] == '410109'){
			?>
			<!-- <td style="text-align: right;" <?//=$font ?>><?php //echo $fisio;?>&nbsp;</td> -->
						<?php if ($rows['akun_type']='Kredit') {?>
						<td id="val_debet_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo '0';?>&nbsp;</td>
						<td id="val_kredit_<?php echo $i ?>" style="text-align: right;" <?=$font ?>><?php echo $fisio?$fisio:'0';?>&nbsp;</td>
						<?php } ?>
			<?php
			$f->hidden("jumlah_".$i."",$fisio);
			}
			
			$totDebet =+ $i;
			$toKredit =+ $i;
			
			?>
		</tr>
	<?php
		}
		
	//}
	}
	?>
		<tr>
			<td colspan="3" class="TBL_HEAD" align="right">J U M L A H</td>
			<td class="TBL_HEAD" align="right" id="jumlah_debet"></td>
			<td class="TBL_HEAD" align="right" id="jumlah_kredit"></td>
		</tr>	
    </tbody> 
</table>
<br>
<br>
<br>
<?php
	$f->submitAndCancel("Posting",$ext,"Batal","window.history.back()",$ext);
	$f->execute();
?>

<script language="JavaScript" type="text/JavaScript">
	totalDebet		= 0;
    totalKredit		= 0;
	
	for(i=1;i<=<?php echo $i ?>;i++){
		
		debetTmp = $('#val_debet_'+i).text();
        debet = parseInt(debetTmp.replace('.',''));
        totalDebet = totalDebet+debet;
		
		kreditTmp = $('#val_kredit_'+i).text();
        kredit = parseInt(kreditTmp.replace('.',''));
        totalKredit = totalKredit+kredit;
		
	}
	
	$('#jumlah_debet').text(totalDebet);
	$('#jumlah_kredit').text(totalKredit);
	

</script>