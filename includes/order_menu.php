<? // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006
//session_start();
//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "daftarri"  || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "order_menu";
$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);
		
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  INSTALASI GIZI");
title_print("");

if (isset($_GET["new"])){

	
	$f = new Form("$SC", "GET", "name='Form2'");
    $f->PgConn = $con;
	$f->hidden("p",$PID);
	$f->hidden("new","new");
	$f->selectArray("pasien", "Jenis Pasien Klinik",
    Array("I" => "Rawat Inap", "J" => "Konsul Rawat Jalan"),
    $_GET["pasien"],"onChange=\"Form2.submit();\"");
	$f->execute();
if ($_GET["pasien"]=="I"){	

	echo "\n<script language='JavaScript'>\n";
	echo "function selectPasien() {\n";
	echo "    sWin = window.open('popup/bed_menu.php', 'xWin', 	'width=600,height=400,menubar=no,scrollbars=yes');\n";
	echo "    sWin.focus();\n";
	echo "}\n";
	echo "</script>\n";
	
if ($_SESSION["SELECT_PASIEN"]) {
		//pilih no reg pasien
        $r1 = pg_query($con, "select d.no_reg as no_reg, e.mr_no as no_mr, d.bangsal_id as bangsal, f.nama,c.bangsal || ' / ' || b.bangsal || ' / ' || a.bangsal as bangsal, d.bangsal_id as id_bangsal from rs00010 d, rs00006 e, rs00002 f,rs00012 as a join rs00012 as b on b.hierarchy = substr(a.hierarchy,1,6) || '000000000' join rs00012 as c on c.hierarchy = substr(a.hierarchy,1,3) || '000000000000' where d.id = '".$_SESSION["SELECT_PASIEN"]."' and e.id=d.no_reg and f.mr_no=e.mr_no and a.id=d.bangsal_id");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
		
		
    }
echo "<form ename='paket' action='actions/order_menu_insert.php' >
	<table>
	<tr>
			<td><input type='hidden' size=10 name='pasien' value='".$_GET["pasien"]."'></td>
	</tr>
	<tr>
			<td>Tanggal Pencatan</td>
			<td>: </td>
			<td><input type='text' size=10 name='tanggal_menu' value='".date("Y-m-d", time())."'> (format tanggal YYYY-MM-DD)</td>
		</tr>
		<tr>
			<td>No Registrasi</td>
			<td>: </td>
			<td><input type='text' size=26 name='no_reg' value='".$d1->no_reg."' readonly>&nbsp;<input type=button value='&nbsp; ... &nbsp;' OnClick='selectPasien()'></td>
		</tr>
		<tr>
			<td>No MR</td>
			<td>: </td>
			<td><input type='text' size=26 name='no_mr' value='".$d1->no_mr."' readonly> </td>
		</tr>
		<tr>
			<td>Nama Pasien</td>
			<td>: </td>
			<td><input type='text' size=26 name='nm_pasien' value='".$d1->nama."' readonly> </td>
		</tr>
		<tr>
			<td>Ruangan</td>
			<td>: </td>
			<td><input type='text' size=50 name='ruangan' value='".$d1->bangsal."' readonly> 
			<input type='hidden' name='id_bangsal' value='".$d1->id_bangsal."' readonly></td>
		</tr>
		<tr>
			<td>Makan Pagi</td>
			<td>: </td>
			<td><textarea name='pagi'></textarea>
			</td>
		</tr>
		
		<tr>
			<td>Makan Siang</td>
			<td>: </td>
			<td> <textarea name='siang'></textarea>
			</td>
		</tr>
		<tr>
			<td>Makan Malam</td>
			<td>: </td>
			<td> <textarea name='malam'></textarea>
			</td>
		</tr>
		<tr>
			<td>Snack 1</td>
			<td>: </td>
			<td><textarea name='snack1'></textarea>
			</td>
		</tr>
		<tr>
			<td>Snack 2</td>
			<td>: </td>
			<td> <textarea name='snack2'></textarea>
			</td>
		</tr>
		
		<tr>
			<td><input type='submit' value='Simpan'> </td>
			<td><input type='reset' value='Batal'> </td>
		</tr>
	</table>
</form> ";
}else{
	echo "\n<script language='JavaScript'>\n";
	echo "function selectPasien() {\n";
	echo "    sWin = window.open('popup/bed_menu1.php', 'xWin', 	'width=600,height=400,menubar=no,scrollbars=yes');\n";
	echo "    sWin.focus();\n";
	echo "}\n";
	echo "</script>\n";
	
	
	if ($_SESSION["SELECT_PASIEN"]) {
		//pilih no reg pasien
        $r1 = pg_query($con, "select distinct a.id as no_reg, a.mr_no,b.id_poli, upper(a.nama)as nama, c.tdesc
				from rsv_pasien4 a 
				left join c_visit b on b.no_reg = a.id
				left join rs00001 c on c.tc_poli = b.id_poli and c.tt='LYN'
				WHERE b.id_konsul='111' and a.id = '".$_SESSION["SELECT_PASIEN"]."'
				 group by a.id, a.mr_no,a.id,a.nama,c.tdesc,b.id_poli ");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
		
		
    }
echo "<form ename='paket' action='actions/order_menu_insert.php' >
	<table>
	<tr>
			<td><input type='hidden' size=10 name='pasien' value='".$_GET["pasien"]."'></td>
	</tr>
	<tr>
			<td>Tanggal Pencatan</td>
			<td>: </td>
			<td><input type='text' size=10 name='tanggal_menu' value='".date("Y-m-d", time())."'> (format tanggal YYYY-MM-DD)</td>
		</tr>
		<tr>
			<td>No Registrasi</td>
			<td>: </td>
			<td><input type='text' size=26 name='no_reg' value='".$d1->no_reg."' readonly>&nbsp;<input type=button value='&nbsp; ... &nbsp;' OnClick='selectPasien()'></td>
		</tr>
		<tr>
			<td>No MR</td>
			<td>: </td>
			<td><input type='text' size=26 name='no_mr' value='".$d1->mr_no."' readonly> </td>
		</tr>
		<tr>
			<td>Nama Pasien</td>
			<td>: </td>
			<td><input type='text' size=26 name='nm_pasien' value='".$d1->nama."' readonly> </td>
		</tr>
		<tr>
			<td>Ruangan</td>
			<td>: </td>
			<td><input type='text' size=50 name='ruangan' value='".$d1->tdesc."' readonly> 
			<input type='hidden' name='id_bangsal' value='".$d1->id_poli."' readonly></td>
		</tr>
		<tr>
			<td>Catatan Diet</td>
			<td>: </td>
			<td><textarea name='pagi'></textarea>
			</td>
		</tr>
		
		<tr>
			<td><input type='submit' value='Simpan'> </td>
			<td><input type='reset' value='Batal'> </td>
		</tr>
	</table>
</form> ";
}

}
else if(isset($_GET["e"])) {
if ($_GET["pasien"] == "I") {
if ($_GET["e"] != "new") {
        $r = pg_query($con, "select to_char(d.tgl,'DD MON YYYY') as tgl, d.no_mr as no_mr ,e.nama as nama, c.bangsal || ' / ' || b.bangsal || ' / ' || a.bangsal as bangsal, d.pagi as pagi, d.siang as siang, d.malam as malam, d.snack_1 as snack1 , d.snack_2 as snack2, d.id,d.dummy from menu_pasien d , rs00002 e, rs00012 as a join rs00012 as b on b.hierarchy = substr(a.hierarchy,1,6) || '000000000' join rs00012 as c on c.hierarchy = substr(a.hierarchy,1,3) || '000000000000' where d.id='".$_GET['e']."' and a.id = d.id_bangsal and d.no_mr=e.mr_no");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	
	
	echo "<form ename='paket' action='actions/order_menu.update.php' >
	<table>
	<tr>
			<td>Tanggal Pencatan</td>
			<td>: </td>
			<td><input type='text' size=10 name='tanggal_menu' value='".$d->tgl."' readonly> (format tanggal YYYY-MM-DD)</td>
		</tr>
		
		<tr>
			<td>No MR</td>
			<td>: </td>
			<td><input type='text' size=26 name='no_mr' value='".$d->no_mr."' readonly> </td>
			<input type='hidden' size=26 name='id' value='".$d->id."'>
		</tr>
		<tr>
			<td>Nama Pasien</td>
			<td>: </td>
			<td><input type='text' size=26 name='nm_pasien' value='".$d->nama."' readonly> </td>
		</tr>
		<tr>
			<td>Ruangan</td>
			<td>: </td>
			<td><input type='text' size=50 name='ruangan' value='".$d->bangsal."' readonly>
		</tr>
		<tr>
			<td>Makan Pagi</td>
			<td>: </td>
			<td><textarea name='pagi'>".$d->pagi."</textarea>
			</td>
		</tr>
		
		<tr>
			<td>Makan Siang</td>
			<td>: </td>
			<td> <textarea name='siang'>".$d->siang."</textarea>
			</td>
		</tr>
		<tr>
			<td>Makan Malam</td>
			<td>: </td>
			<td> <textarea name='malam'>".$d->malam."</textarea>
			</td>
		</tr>
		<tr>
			<td>Snack 1</td>
			<td>: </td>
			<td><textarea name='snack1'>".$d->snack_1."</textarea>
			</td>
		</tr>
		<tr>
			<td>Snack 2</td>
			<td>: </td>
			<td> <textarea name='snack2'>".$d->snack_2."</textarea>
			</td>
		</tr>
		
		<tr>
			<td><input type='submit' value='Simpan'> </td>
			<td><input type='reset' value='Batal'> </td>
		</tr>
	</table>
</form> ";
}else{
if ($_GET["e"] != "new") {
        $r = pg_query($con, "select to_char(d.tgl,'DD MON YYYY') as tgl, d.no_mr as no_mr ,e.nama as nama, 
			c.tdesc as poli, d.pagi as pagi, d.id
			from menu_pasien d , rs00002 e, rs00001 c  
			where c.tc = d.id_bangsal::text and c.tt='LYN' and d.no_mr=e.mr_no and d.jns_pasien='J' and d.id::text ='".$_GET["e"]."' ");
        $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	
	
	echo "<form ename='paket' action='actions/order_menu.update.php' >
	<table>
	<tr>
			<td>Tanggal Pencatan</td>
			<td>: </td>
			<td><input type='text' size=10 name='tanggal_menu' value='".$d->tgl."' readonly> (format tanggal YYYY-MM-DD)</td>
		</tr>
		
		<tr>
			<td>No MR</td>
			<td>: </td>
			<td><input type='text' size=26 name='no_mr' value='".$d->no_mr."' readonly> </td>
			<input type='hidden' size=26 name='id' value='".$d->id."'>
		</tr>
		<tr>
			<td>Nama Pasien</td>
			<td>: </td>
			<td><input type='text' size=26 name='nm_pasien' value='".$d->nama."' readonly> </td>
		</tr>
		<tr>
			<td>Poli Asal</td>
			<td>: </td>
			<td><input type='text' size=50 name='ruangan' value='".$d->poli."' readonly>
		</tr>
		<tr>
			<td>Catatan Diet</td>
			<td>: </td>
			<td><textarea name='pagi'>".$d->pagi."</textarea>
			</td>
		</tr>
		
		<tr>
			<td><input type='submit' value='Simpan'> </td>
			<td><input type='reset' value='Batal'> </td>
		</tr>
	</table>
</form> ";
}
}
else if(isset($_GET["n"])) {
if ($_GET["n"] != "new") {
        $r = pg_query($con, "SELECT id,no_mr, to_char(tgl,'DD MON YYYY') as tgl FROM menu_pasien where id='".$_GET['n']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
		
		
    }
	
echo"<form action='actions/order_menu.delete.php'>
			<table>
			<h1>Anda yakin menghapus data ini?</h1>
				<tr>	
					<td>No MR</td><td>:</td>
					<td><input type='text' name='id' value='".$d->no_mr."' readonly></td>
					<td><input type='hidden' name='id' value='".$d->id."' readonly></td>
				</tr>
				<tr>
					<td>Tanggal Pencatan</td><td>:</td>
					<td><input type='text' name='jenis_linen' value='".$d->tgl."' readonly></td>
				</tr>
				<tr>
					<input type='submit' value=YA></td>
					
				</tr>
			</table>
		</form>";
}


//tambah cetak label..
//file terkait includes/cetak_menu.php
else if(isset($_GET["c"])) {
if ($_GET["c"] != "new") {
        if ($_GET["pasien"]=="I"){
		$r = pg_query($con, "select to_char(d.tgl,'DD MON YYYY') as tgl, d.no_mr as no_mr ,e.nama as nama, c.bangsal || ' / ' || b.bangsal || ' / ' || a.bangsal as bangsal, d.pagi as pagi, d.siang as siang, d.malam as malam, d.snack_1 as snack1 , d.snack_2 as snack2, d.id,d.dummy from menu_pasien d , rs00002 e, rs00012 as a join rs00012 as b on b.hierarchy = substr(a.hierarchy,1,6) || '000000000' join rs00012 as c on c.hierarchy = substr(a.hierarchy,1,3) || '000000000000' where d.id='".$_GET['c']."' and a.id = d.id_bangsal and d.no_mr=e.mr_no");
		}else{
		$r = pg_query($con, "select to_char(d.tgl,'DD MON YYYY') as tgl, d.no_mr as no_mr ,e.nama as nama, 
			c.tdesc as bangsal, d.pagi as pagi, d.id
			from menu_pasien d , rs00002 e, rs00001 c  
			where c.tc = d.id_bangsal::text and c.tt='LYN' and d.no_mr=e.mr_no and d.jns_pasien='J' and d.id::text ='".$_GET["c"]."'");
		}
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	
	
	
	echo "
	<table>
	<form name=cetak action=includes/cetak_menu.php>
		<tr>
			<td>Tanggal Pencatatv</td>
			<td>: </td>
			<td><input type='text' size=26 name='tgl' value='".$d->tgl."' readonly> </td>
		</tr>
		<tr>
			<td>No MR</td>
			<td>: </td>
			<td><input type='text' size=26 name='no_mr' value='".$d->no_mr."' readonly> </td>
			<input type='hidden' size=26 name='id' value='".$d->id."'>
		</tr>
		<tr>
			<td>Nama Pasien</td>
			<td>: </td>
			<td><input type='text' size=26 name='nm_pasien' value='".$d->nama."' readonly> </td>
		</tr>
		<tr>
			<td>Ruangan/Poli Asal</td>
			<td>: </td>
			<td><input type='text' size=50 name='ruangan' value='".$d->bangsal."' readonly>
		</tr>
		";
		if ($_GET["pasien"]=="I"){
		echo "<tr>
			<td>Jam Makan</td>
			<td>: </td>
			<td>
			<select name=waktu >
				<option value='pagi'>Pagi</option>
				<option value='siang'>Siang</option>
				<option value='malam'>Malam</option>
				<option value='snack_1'>Snack 1</option>
				<option value='snack_2'>Snack 2</option>
			</select>
			</td>
		</tr>
		";
		}else{
		echo "<tr>
			<td>Catatan Diet</td>
			<td>: </td>
			<td><input type='text' size=50 name='pagi' value='".$d->pagi."' readonly>
			<input type='hidden' size=26 name='waktu' value=pagi>
		</tr>
		";
		}
		echo "
		<tr>
		<td>
			<input type='submit' value='&nbsp; cetak &nbsp;'></td>
		</tr>
		</form>
		</table>
		
	";
}

else{

title_excel("order_menu&pasien1=".$_GET[pasien1]."&pasien=".$_GET[pasien]."&e=".$_GET[e]." ");
	$f = new Form("$SC", "GET", "name='Form2'");
    $f->PgConn = $con;
	$f->hidden("p",$PID);
	// $f->hidden("new","new");
	$f->selectArray("pasien1", "Jenis Pasien Klinik",
    Array("I" => "Rawat Inap", "J" => "Konsul Rawat Jalan"),
    $_GET["pasien1"],"onChange=\"Form2.submit();\"");
	$f->execute();
	
	if ($_GET["pasien1"]=="I"){
	$t = new PgTable($con, "100%");

	$t->SQL = "select to_char(d.tgl,'DD MON YYYY') as tgl, d.no_mr as no_mr ,e.nama as nama, 
			c.bangsal || ' / ' || b.bangsal || ' / ' || a.bangsal as bangsal, d.pagi as pagi, d.siang as siang, 
			d.malam as malam, d.snack_1 as snack1 , d.snack_2 as snack2, d.id,d.dummy 
			from menu_pasien d , rs00002 e, rs00012 as a 
			join rs00012 as b on b.hierarchy = substr(a.hierarchy,1,6) || '000000000' 
			join rs00012 as c on c.hierarchy = substr(a.hierarchy,1,3) || '000000000000' 
			where a.id = d.id_bangsal and d.no_mr=e.mr_no and jns_pasien='I' ";
		if (!isset($_GET[sort])) {
           $_GET[sort] = "tgl";
           $_GET[order] = "asc";
	}
  	$t->ColHeader = array("Tanggal Pencatatan","No MR","Nama Pasien","Ruangan","Menu Pagi","Menu Siang","Menu Malam","Snack1","Snack2","Edit/Hapus","Cetak");
    $t->ShowRowNumber = true;
   	$t->ColAlign[0] = "CENTER";
	$t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[10] = "CENTER";
	$t->ColFormatHtml[9] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#9#>&pasien=I'>".icon("edit","Edit")."</A> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
    						"<A CLASS=TBL_HREF HREF='$SC?p=$PID&n=<#9#>&pasien=I'>".icon("delete","Hapus")."</A>".
            "</nobr>"; 
	$t->ColFormatHtml[10] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&c=<#9#>&pasien=I'>".icon("print","Cetak")."</A></nobr>"; 
    $t->ColAlign[10] = "CENTER";
	
	$t->ColAlign[11] = "CENTER";
	$t->execute();
 }else{
 $t = new PgTable($con, "100%");

	$t->SQL = "select to_char(d.tgl,'DD MON YYYY') as tgl, d.no_mr as no_mr ,e.nama as nama, 
			c.tdesc as poli, d.pagi as pagi, d.id,d.dummy 
			from menu_pasien d , rs00002 e, rs00001 c  
			where c.tc = d.id_bangsal::text and c.tt='LYN' and d.no_mr=e.mr_no and d.jns_pasien='J' ";
		if (!isset($_GET[sort])) {
           $_GET[sort] = "tgl";
           $_GET[order] = "asc";
	}
  	$t->ColHeader = array("Tanggal Pencatatan","No MR","Nama Pasien","Ruangan","Catatan Diet","Edit/Hapus","Cetak");
    $t->ShowRowNumber = true;
   	$t->ColAlign[0] = "CENTER";
	$t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[6] = "CENTER";
	$t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#5#>&pasien=J'>".icon("edit","Edit")."</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
    						"<A CLASS=TBL_HREF HREF='$SC?p=$PID&n=<#5#>&pasien=J'>".icon("delete","Hapus")."</A>".
            "</nobr>"; 
	$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&c=<#5#>&pasien=J'>".icon("print","Cetak")."</A></nobr>"; 
    $t->ColAlign[6] = "CENTER";
	
	$t->ColAlign[5] = "CENTER";
	$t->execute();
 }
 
 echo "<h1><A HREF='$SC?p=$PID&new'>Order Menu Baru</h1>";
}

?> 
