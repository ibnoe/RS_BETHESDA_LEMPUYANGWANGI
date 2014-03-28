<?php

	$f = new Form("actions/p_pelayanan.insert.php", "POST", "NAME=Form2");
	$f->hidden("act","new2");
	$f->hidden("p",$PID);
	$f->hidden("f_no_reg",$d->id);
	$f->hidden("list","konsultasi");
	$f->hidden("mr",$_GET["mr"]);
	$f->hidden("f_id_poli",$_GET["poli"]);
	$f->hidden("f_tanggal_reg",$d2["tanggal_reg"]);
	$f->hidden("f_user_id",$_SESSION[uid]);
	$f->hidden("konsultasi",$_GET["konsultasi"]);

	echo"<br>";
	
	$konsul = getFromTable("select id_konsul from c_visit where no_reg='".$_GET["rg"]."' and id_poli='".$_GET["poli"]."'");
	$f->PgConn=$con;
	$f->selectSQL("konsultasi","Unit Yang Dituju", "select tc,tdesc from rs00001 where tt='LYN' and tc not in ('000','100','201','202','206','207','208') order by tdesc",$konsul,$ext);
	$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
	$f->execute();
	
	echo "<b>Pasien di Konsul ke Poli:</b><br>";
	
	$t = new PgTable($con, "100%");
	$t->SQL = "select a.tanggal_reg, b.tdesc, a.oid from c_visit a left join rs00001 b on b.tc=a.id_konsul and b.tt='LYN'  where  a.no_reg='".$_GET[rg]."' and a.id_poli='".$_GET["poli"]."' and a.id_konsul != '' ";
	$t->setlocale("id_ID");
	$t->ShowRowNumber = true;
	$t->ColAlign = array("CENTER","LEFT","CENTER","CENTER","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
	$t->RowsPerPage = $ROWS_PER_PAGE;
	$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='actions/p_umum.delete.php?p=$PID&oid=<#1#>&tbl=konsultasi&mr=".$_GET[mr]."&rg=".$_GET[rg]."&f_id_poli=".$_GET["poli"]."'>". icon("delete","Edit Status pekerjaan")."</A>";
	$t->ColHeader = array("TANGGAL KONSUL", "KONSUL KE", "HAPUS");
	//$t->ColRowSpan[2] = 2;
	$t->execute();
	echo"<br><font color=black>&nbsp;* Catatan : Hasil Pemeriksaan Pasien harus diisi minimal Dokter Pemeriksa</font><br>";
?>