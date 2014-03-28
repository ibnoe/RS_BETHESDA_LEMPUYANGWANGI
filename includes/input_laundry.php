<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 09-05-2004

$PID = "input_laundry";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
session_start();



if (isset($_GET["del-laundry"])) {
	$id=$_GET["id"];
		$temp = $_SESSION["laundry"];
    unset($_SESSION["laundry"]);
    foreach ($temp as $k => $v) {
        if ($k != $_GET["laundry"]) $_SESSION["laundry"][count($_SESSION["laundry"])] = $v;
    }
    header("Location: $SC?p=input_laundry&action=tambah2&id=$id");
    exit;
	
	
	
}

elseif (isset($_GET["laundry"])) {
$id=$_GET["id"];
    $r = pg_query($con,"select * from jenislinen where id = '".$_GET["laundry"]."'");
    $d = pg_fetch_object($r);
    pg_free_result($r);
    if (is_array($_SESSION["laundry"])) {
        $cnt = count($_SESSION["laundry"]);
    } else {
        $cnt = 0;
    }
    if (!empty($d->laundry)) {
        $_SESSION["laundry"][$cnt]["no_id"]  = $d->id;
        $_SESSION["laundry"][$cnt]["nama"]   = $d->nama_jenis;
       
	$_SESSION["laundry"][$cnt]["jumlah"] = $_GET["jumlah_laundry"];
       
        unset($_SESSION["SELECT_laundry"]);
    }
    header("Location: $SC?p=input_laundry&action=tambah2&id=$id");
    exit;
    
}



if($_GET["action"]=="tambah"){
title("<img src='icon/keuangan.gif' align='absmiddle' > Laundry");
$f =new Form("actions/input_laundry.insert.php", "POST", "NAME=Form1");
 $f->PgConn = $con;
$f->text("no_laundry","NO Laundry",10,"10","OTOMATIS","disabled");
$f->selectSQL("f_id_ruang", "Ruang","select id, nama_ruang from ruang_linen","", "");
$f->hidden("action","tambah");
$f->text("f_petugas","Petugas",25,"50","","");
$f->calendar1("f_tanggal","Tanggal Masuk",15,15,$tglhariini,"Form1","icon/calendar.gif","Pilih Tanggal","");
$f->submit("Simpan");
$f->execute();

}
elseif($_GET["action"]=="tambah2"){
	$id=$_GET["id"];
	$ruangan= getFromTable("select a.nama_ruang from ruang_linen a,laundry_c b where a.id=b.id_ruang and b.id=$id");
	$petugas= getFromTable("select petugas from laundry_c where id=$id");
	$tanggal= getFromTable("select to_char(tanggal,'dd-mm-yyyy') from laundry_c where id=$id");
	$f=new ReadOnlyForm();
	$f->title("Data Laundry");
	$f->text("No Laundry",$id);
	$f->text("Ruangan",$ruangan);
	$f->text("Petugas",$petugas);
	$f->text("Tanggal",$tanggal);
	$f->execute();

	$i=1;
	$SQL="select * from jenislinen";
	@$r1 = pg_query($con,$SQL);
	@$n1 = pg_num_rows($r1);
	
	
	$f =new Form("actions/input_laundry.insert.php", "POST", "NAME=Form1");
	$f->hidden("action","tambah2");
	while (@$row1 = pg_fetch_array($r1)){
	$nama="jumlah_linen".$i;
	$id_jenis="id_linen".$i;
	$f->text($nama,$row1["nama_jenis"],10,10,"","");
	$f->hidden($id_jenis,$row1["id"]);
	$i=$i+1;
	}
		$f->hidden("jumlah",$i-1);
		$f->hidden("id_laundry",$_GET["id"]);
	$f->submit("SIMPAN");
	$f->execute();
	
}


elseif($_GET["action"]=="view"){
	title_excel("input_laundry");
	title_print("");
	$f=new ReadOnlyForm();
	$id=$_GET["id"];
	$ruangan= getFromTable("select a.nama_ruang from ruang_linen a,laundry_c b where a.id=b.id_ruang and b.id=$id ");
	$petugas= getFromTable("select petugas from laundry_c where id=$id");
	$tanggal= getFromTable("select to_char(tanggal,'dd-mm-yyyy') from laundry_c where id=$id");

	$f->title("Data Laundry");
	$f->text("No Laundry",$id);
	$f->text("Ruangan",$ruangan);
	$f->text("Petugas",$petugas);
	$f->text("Tanggal",$tanggal);
	$f->execute();
	
	
	$t = new PgTable($con, "100%");
        $t->SQL = "select b.nama_jenis, a.jumlah from jenislinen b, laundry_item a where b.id=a.id_linen and a.id_laundry=$id group by b.nama_jenis, a.jumlah";
                	
		
        $t->ColHeader = array("NAMA","JUMLAH");
        $t->ShowRowNumber = true;
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[2] = "CENTER";
        
	 
		// --- eof 27-12-2006
        $t->execute();
		$f =new Form("actions/input_laundry.insert.php", "POST", "NAME=Form1");
		$status= getFromTable("select status from laundry_c where id=$id");
		if($status==1){
			$tulisan ="SELESAI DICUCI";
			$update=2;
		}
		elseif($status==2){
			$tulisan="KEMBALIKAN LAUNDRY";
			$update=3;
		}
		else{
			$tulisan="KELUARKAN DARI DAFTAR";
			$update=4;
		}
		$f->hidden("action","selesai");
		$f->hidden("id_laundry",$id);
		$f->hidden("update",$update);
		$f->submit($tulisan);
		$f->execute();


}

else{
if (!$GLOBALS['print']){
		title_print("<img src='icon/keuangan-2.gif' align='absmiddle' >Laundry");
		title_excel("input_laundry");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laundry");
		title_excel("p=input_laundry&action=view&id=2891");
    }
	
	
	$SQL = "select a.id,to_char(a.tanggal,'dd MON YYYY') as tanggal,a.petugas,
	b.nama_ruang,
	case 
		when a.status='1' then 'Sedang Dicuci' 
		when a.status='2' then 'Sudah Selesai'
		else 'Sudah Dikembalikan' end as status from laundry_c a,ruang_linen b 
		where a.id_ruang=b.id  and  a.status not like '4'
		group by a.id,a.tanggal,a.petugas,a.status,b.nama_ruang ";
	
			@$r1 = pg_query($con,$SQL);
			@$n1 = pg_num_rows($r1);
	
	
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">NO FAKTUR</td>
				<td class="TBL_HEAD"align="center">TANGGAL</td>
				<td class="TBL_HEAD"align="center">NAMA RUANGAN</td>	
				<td class="TBL_HEAD"align="center">PETUGAS</td>
				<td class="TBL_HEAD"align="center">STATUS</td>
				<td width="5%" align="center" class="TBL_HEAD">VIEW DETAIL</td>
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
			        	<td class="TBL_BODY" align="center"><?=$row1["id"] ?> </td>
                                        <td align="center" class="TBL_BODY"><?=$row1["tanggal"] ?></td>
                                        <td align="left" class="TBL_BODY"><?=$row1["nama_ruang"] ?></td>
                                        <td align="left" class="TBL_BODY"><?=$row1["petugas"] ?></td>
                                        <td align="center" class="TBL_BODY"><?=$row1["status"] ?></td>
                                        <td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=view&id=".$row1['id']."'>".
                        icon("View","Lihat")."</A>&nbsp;&nbsp;";?></td>
						</tr>	

					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>

</table>
<?
echo "<br><br>";
echo "<a href=$SC?p=$PID&action=tambah>Tambah Data</a>";

 
    
       
        

}
?>
