<?php

include '../lib/dbconn.php';
include '../lib/class.PgTrans.php';
$tr = new PgTrans();
$tr->PgConn = $con;
if(!empty($_POST['idOp1'])){
	if(getFromTable("SELECT count(id) FROM rs00008_op2 WHERE id_rs08 = ".$_POST['idrs08']." AND status_penindak = 'OP1'") > 0){
	$OP1 = "UPDATE rs00008_op2 SET id_rs17 = ".$_POST['idOp1'].", terima = ".$_POST['totalTerimaOperasi1'].", diskon = ".$_POST['diskonNominalOperasi1'].", 
		persen = ".$_POST['diskonPersenOperasi1']." WHERE id_rs08 = ".$_POST['idrs08']." AND status_penindak = 'OP1'";	
	}
else{
	/** INSERT DOKTER OPERATOR 1 */
	$OP1 = "INSERT INTO rs00008_op2(id_rs08, id_rs17, terima, diskon, persen, status_penindak) 
			VALUES(".$_POST['idrs08'].",".$_POST['idOp1'].",".$_POST['totalTerimaOperasi1'].",".$_POST['diskonNominalOperasi1'].",".$_POST['diskonPersenOperasi1'].",'OP1' )";
}
	$tr->addSQL($OP1);
}

if(!empty($_POST['idOp2'])){
	if(getFromTable("SELECT count(id) FROM rs00008_op2 WHERE id_rs08 = ".$_POST['idrs08']." AND status_penindak = 'OP2'") > 0){
	$OP2 = "UPDATE rs00008_op2 SET id_rs17 = ".$_POST['idOp2'].", terima = ".$_POST['totalTerimaOperasi2'].", diskon = ".$_POST['diskonNominalOperasi2'].", 
		persen = ".$_POST['diskonPersenOperasi2']." WHERE id_rs08 = ".$_POST['idrs08']." AND status_penindak = 'OP2'";	
	}
	else{
	/** INSERT DOKTER OPERATOR 2 */
	$OP2 = "INSERT INTO rs00008_op2(id_rs08, id_rs17, terima, diskon, persen, status_penindak) 
		VALUES(".$_POST['idrs08'].",".$_POST['idOp2'].",".$_POST['totalTerimaOperasi2'].",".$_POST['diskonNominalOperasi2'].",".$_POST['diskonPersenOperasi2'].",'OP2' )";
	}
$tr->addSQL($OP2);
}

if(!empty($_POST['idAsistenOp1'])){
	if(getFromTable("SELECT count(id) FROM rs00008_op2 WHERE id_rs08 = ".$_POST['idrs08']." AND status_penindak = 'AST1'") > 0){
	$AST1 = "UPDATE rs00008_op2 SET id_rs17 = ".$_POST['idAsistenOp1'].", terima = ".$_POST['terimaAsistenOperasi1'].", diskon = ".$_POST['diskonNominalAsistenOperasi1'].", 
		persen = ".$_POST['diskonPersenAsistenOperasi1']." WHERE id_rs08 = ".$_POST['idrs08']." AND status_penindak = 'AST1'";	
	}
	else{
	/** INSERT ASISTEN 1 */
	$AST1 = "INSERT INTO rs00008_op2(id_rs08, id_rs17, terima, diskon, persen, status_penindak) 
		VALUES(".$_POST['idrs08'].",".$_POST['idAsistenOp1'].",".$_POST['terimaAsistenOperasi1'].",".$_POST['diskonNominalAsistenOperasi1'].",".$_POST['diskonPersenAsistenOperasi1'].",'AST1' )";
	}
$tr->addSQL($AST1);
}

if(!empty($_POST['idAsistenOp2'])){
	if(getFromTable("SELECT count(id) FROM rs00008_op2 WHERE id_rs08 = ".$_POST['idrs08']." AND status_penindak = 'AST2'") > 0){
	$AST2 = "UPDATE rs00008_op2 SET id_rs17 = ".$_POST['idAsistenOp2'].", terima = ".$_POST['terimaAsistenOperasi2'].", diskon = ".$_POST['diskonNominalAsistenOperasi2'].", 
		persen = ".$_POST['diskonPersenAsistenOperasi2']." WHERE id_rs08 = ".$_POST['idrs08']." AND status_penindak = 'AST2'";		
	}
	else{
	/** INSERT ASISTEN 2 */
	$AST2 = "INSERT INTO rs00008_op2(id_rs08, id_rs17, terima, diskon, persen, status_penindak) 
		VALUES(".$_POST['idrs08'].",".$_POST['idAsistenOp2'].",".$_POST['terimaAsistenOperasi2'].",".$_POST['diskonNominalAsistenOperasi2'].",".$_POST['diskonPersenAsistenOperasi2'].",'AST2' )";
	}
$tr->addSQL($AST2);
}

if(!empty($_POST['idAsistenOp3'])){
	if(getFromTable("SELECT count(id) FROM rs00008_op2 WHERE id_rs08 = ".$_POST['idrs08']." AND status_penindak = 'AST3'") > 0){
	$AST3 = "UPDATE rs00008_op2 SET id_rs17 = ".$_POST['idAsistenOp3'].", terima = ".$_POST['terimaAsistenOperasi3'].", diskon = ".$_POST['diskonNominalAsistenOperasi3'].", 
		persen = ".$_POST['diskonPersenAsistenOperasi3']." WHERE id_rs08 = ".$_POST['idrs08']." AND status_penindak = 'AST3'";			
	}
	else{
	$AST3 = "INSERT INTO rs00008_op2(id_rs08, id_rs17, terima, diskon, persen, status_penindak) 
		VALUES(".$_POST['idrs08'].",".$_POST['idAsistenOp3'].",".$_POST['terimaAsistenOperasi3'].",".$_POST['diskonNominalAsistenOperasi3'].",".$_POST['diskonPersenAsistenOperasi3'].",'AST3' )";
	$tr->addSQL($AST3);
	}
}
if($tr->execute()){
header('Location:../index2.php?p=lap_jasmed_operasi&input='.$_POST['idrs08']);
}
else{
$tr->showSQL();		
}
