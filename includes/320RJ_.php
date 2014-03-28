<?php // Agung S. Menambahkan Print Untuk Apotek

$PID = "320RJ";
$SC = $_SERVER["SCRIPT_NAME"];

session_start();
$title_layanan = "";
//echo $_SESSION[gr];
if (!empty($_SESSION[gr])) {

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
//require_once("lib/class.PgTable2.php");
require_once("lib/functions.php");

if (isset($_GET["del-obat"])) {
    $temp = $_SESSION["obat"];
    unset($_SESSION["obat"]);
    foreach ($temp as $k => $v) {
        if ($k != $_GET["del-obat"]) $_SESSION["obat"][count($_SESSION["obat"])] = $v;
    }
    header("Location: $SC?p=".$_GET["p"]."&tt=".$_GET["tt"]."&rg=".$_GET["rg"]."&sub=obat");
    exit;
} elseif (isset($_GET["del-racikan"])) {
    $temp = $_SESSION["racikan"];
    unset($_SESSION["racikan"]);
    foreach ($temp as $k => $v) {
        if ($k != $_GET["del-racikan"]) $_SESSION["racikan"][count($_SESSION["racikan"])] = $v;
    }
    header("Location: $SC?p=".$_GET["p"]."&tt=".$_GET["tt"]."&rg=".$_GET["rg"]."&sub=racikan");
    exit;

} elseif (isset($_GET["obat"])) {
    $r = pg_query($con,"select * from rsv0004 where id = '".$_GET["obat"]."'");
    $d = pg_fetch_object($r);
    pg_free_result($r);

	$kategori = getFromTable("select kategori_id from rsv0004 where id = '".$_GET["obat"]."'");
    $ppn = getFromTable("select comment from rs00001 where tc='$kategori' and tt='GOB'");
    $r2 = pg_query($con,"select comment from rs00001 where tc='$kategori' and tt='GOB'");
    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    if (is_array($_SESSION["obat"])) {
        $cnt = count($_SESSION["obat"]);
    } else {
        $cnt = 0;
    }
    if (!empty($d->obat)) {
    //$_SESSION["obat"][$cnt]["batch"]  = $_GET["obat"];
	$_SESSION["obat"][$cnt]["id"]     = $_GET["obat"];
    $_SESSION["obat"][$cnt]["desc"]   = $d->obat;
    $_SESSION["obat"][$cnt]["dosis"]  = $_GET["dosis"];
	$_SESSION["obat"][$cnt]["ket_racikan"]  = $d->satuan;
	$_SESSION["obat"][$cnt]["jumlah"] = $_GET["jumlah_obat"];
    $_SESSION["obat"][$cnt]["harga"]  = $d->harga;
	$_SESSION["obat"][$cnt]["ppn"]    = $d2->comment;
    $_SESSION["obat"][$cnt]["total"]  = ($d->harga * $_GET["jumlah_obat"])+$d2->comment;
    unset($_SESSION["SELECT_OBAT"]);
    }
    header("Location: $SC?p=".$_GET["p"]."&tt=".$_GET["tt"]."&rg=".$_GET["rg"]."&sub=obat");
    exit;
    
} elseif (isset($_GET["batch"])) {

    $r1 = pg_query($con,"select * from rsv0004 where id = '".$_GET["batch"]."'");
    $d1 = pg_fetch_object($r1);
    pg_free_result($r1);

	$kategori = getFromTable("select kategori_id from rsv0004 where id = '".$_GET["batch"]."'");
    $ppn = getFromTable("select tc_poli from rs00001 where tc='$kategori' and tt='GOB'");
    $r2 = pg_query($con,"select tc_poli from rs00001 where tc='$kategori' and tt='GOB'");
    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    if (is_array($_SESSION["racikan"])) {
        $cnt = count($_SESSION["racikan"]);
    } else {
        $cnt = 0;
    }
	
    if (!empty($d1->obat)) {
        $_SESSION["racikan"][$cnt]["batch1"]  = $_GET["batch"];
		$_SESSION["racikan"][$cnt]["id1"]     = $d1->id;
        $_SESSION["racikan"][$cnt]["desc1"]   = $d1->obat;
		$_SESSION["racikan"][$cnt]["jumlah1"] = $_GET["jumlah_obat2"];
        $_SESSION["racikan"][$cnt]["harga1"]  = $d1->harga;
        $_SESSION["racikan"][$cnt]["ppn1"]    = $d2->tc_poli;
        $_SESSION["racikan"][$cnt]["total1"]  = ($d1->harga * $_GET["jumlah_obat2"])+$d2->tc_poli;
        unset($_SESSION["SELECT_OBAT2"]);
    }
    header("Location: $SC?p=".$_GET["p"]."&tt=".$_GET["tt"]."&rg=".$_GET["rg"]."&sub=racikan");
    exit;

} 



echo "<br>";
if ($_GET["tt"]=="igd"){
$ket="IGD";
}elseif ($_GET["tt"]=="swd"){
$ket="Swadana";
}elseif ($_GET["tt"]=="cdm"){
$ket="Cinduo Mato";
}elseif ($_GET["tt"]=="ask"){
$ket="Askes";
}

?>
<table>
    <tr>
        <td><?title("<img src='icon/apotek1-icon.png' align='absmiddle' >Layanan Apotek Klinik ". $ket);?></td>
		<td><?title("<img src='icon/apotek-icon.png' align='absmiddle' ><A CLASS=SUB_MENU  HREF='index2.php?p=apotik_umum&tt=".$_GET["tt"]."'>APOTEK UMUM</A>"); ?></td>
    </tr>
</table>
<?


	
unset($_GET["layanan"]);

$reg = $_GET["rg"];
$reg2 = $_GET["rg"];


if ($reg > 0) {
    $r = pg_query($con,
        "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, ".
        "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, ".
        "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, ".
        "    e.alm_tetap, e.kota_tetap, e.pos_tetap, e.tlp_tetap, ".
        "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
        "    c.tdesc AS penjamin, a.no_jaminan, a.rujukan, a.rujukan_rs_id, ".
        "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
        "    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, ".
        "        CASE ".
        "            WHEN a.rawat_inap = 'I' THEN 'Rawat Inap'  ".
        "            WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan' ".
        "            ELSE 'IGD' ".
        "        END AS rawatan, ".
        "        age(a.tanggal_reg , e.tgl_lahir ) AS umur, ".
	"	case when a.rujukan = 'Y' then 'Rujukan' ".
	"	     when a.rujukan ='U' then 'Unit Lain'  else 'Non-Rujukan' ".
        "       end as datang,  ".
        "   i.tdesc as  poli ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc and h.tt = 'JDP' ".
	"   left join rs00001 i on i.tc_poli = a.poli ".
        //"WHERE a.id = lpad('$reg',10,'0')");
		"WHERE a.id = '$reg'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    $rawatan = $d->rawatan;

    // ambil bangsal
    $id_max = getFromTable("select max(id) from rs00010 where no_reg = '".$_GET["rg"]."'");
    if (!empty($id_max)) {
    $bangsal = getFromTable("select c.bangsal || ' / ' || e.tdesc ".
                       "from rs00010 as a ".
                       "    join rs00012 as b on a.bangsal_id = b.id ".
                       "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
                       //"    join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
                       "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
                       "where a.id = '$id_max'");
    }
    $umure = umur($d->umur);
    $umure = explode(" ",$umure);
    $umur = $umure[0]." thn";


    echo "<table  width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("No Reg.", formatRegNo($d->id));
    $f->text("No MR", $d->mr_no);
    $f->text("Nama", $d->nama);

    $f->text("Pasien Dari",$d->rawatan);
    if ($rawatan == "Rawat Jalan") {
       $f->text("Poli",$d->poli);
    } else {
       $f->text("Bangsal",$bangsal);
    }

    $f->text("Kedatangan",$d->datang);

    $f->execute();
    echo "</td><td align=center valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Alamat", "$d->alm_tetap $d->kota_tetap $d->pos_tetap");
    $f->text("Telepon", $d->tlp_tetap);
    $f->text("Tanggal", date("d F Y"));
    $f->text("<nobr>Tipe Pasien</nobr>", $d->tipe_desc);
    $f->text("Umur", $umur);
    $f->execute();
    echo "</td><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    echo "<table  width='100%'>";
	
    echo "<tr><td class=TBL_BODY>Diagnosa Sementara:</td></tr>";
    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
	 if(!$GLOBALS['print']){
	echo " <DIV ALIGN=RIGHT><img src=\"icon/back.gif\" align=absmiddle ><A CLASS=SUB_MENU HREF='index2.php".
            "?p=$PID&tt=".$_GET["tt"]."'>".
            "  Kembali  </A></DIV>";
    }else{}
	
    echo "</table>";
    $f->execute();
    echo "</td></tr></table>  " ;

    echo "<form name=Form3>";
	echo "<input name=b3 type=button value='Resep / Obat'      onClick='window.location=\"$SC?p=$PID&tt=".$_GET["tt"]."&rg=".$_GET["rg"]."&sub=obat\";'>&nbsp;";
	echo "<input name=b10 type=button value='Layanan / Racikan' onClick='window.location=\"$SC?p=$PID&tt=".$_GET["tt"]."&rg=".$_GET["rg"]."&sub=racikan\";'>&nbsp;";
	echo "<input name=b11 type=button value='Pembayaran Obat' onClick='window.location=\"$SC?p=$PID&tt=".$_GET["tt"]."&rg=".$_GET["rg"]."&sub=bayar\";'>&nbsp;";
    echo "</form>";

    $total = 0.00;

    echo "<div>";

    if ($_GET["sub"] == "bayar") {
title("Pembayaran Obat");
echo "<script language='JavaScript'>\n";
        echo "document.Form3.b11.disabled = true;\n";
        echo "</script>\n";
		
	
    echo "<hr>";
	
	
echo "\n<script language='JavaScript'>\n";
echo "function hitung1() {\n";
echo "  var jml,potongan ;   \n";
echo "  potongan = Math.round(document.Form1.keringanan.value) + Math.round(document.Form1.askes.value)  ;  \n";
echo "  jml = Math.round(document.Form1.tmp_tagihan.value) - potongan ;    ; \n";
echo "  document.Form1.bayar.value =  Math.round(jml);     \n";
echo "  document.Form1.hrg.value =  Math.round(jml);     \n";
echo "  document.Form1.hrg1.value =  Math.round(jml);     \n";
echo "  document.Form1.sisa.value = Math.round(document.Form1.tmp_tagihan.value) - (Math.round(document.Form1.bayar.value) + potongan) ;     \n";
echo "  \n";
echo "}\n";

echo "function hitung2() {\n";
echo "       var jml,potongan ;   \n";
echo "       potongan = Math.round(document.Form1.keringanan.value) + Math.round(document.Form1.askes.value)  ;  \n";
echo "       jml = Math.round(document.Form1.tmp_tagihan.value) - potongan ;    ; \n";
echo "       document.Form1.sisa.value = Math.round(document.Form1.tmp_tagihan.value) - (Math.round(document.Form1.bayar.value) + potongan) ;     \n";
echo "        \n";
echo "}\n";
echo "</script>\n";

echo "\n<script language='JavaScript'>\n";
echo "function cetakkwitansi(tag) {\n";
echo "    sWin = window.open('includes/cetak.rincian_apotek.php?rg='+tag+'&kas=".$_GET["tt"]."', 'xWin',".
     " 'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

	$sql="select b.obat, a.qty, a.harga, a.referensi, a.tagihan from rs00008 a 
	left join rsv0004 b on b.id::text=a.item_id::text
	where a.no_reg='$d->id' and a.trans_form not in ('320') and trans_type in ('OB1')
	group by a.item_id, a.qty, a.harga, a.referensi, a.tagihan,b.obat ";
	
	$sqlrck=getFromTable("select sum(a.tagihan) as tagihan from rs00008 a 
	left join rsv0004 b on b.id::text=a.item_id::text
	where a.no_reg='$d->id' and a.trans_form not in ('320') and trans_type in ('RCK') ");
	
	$r1 = pg_query($con,$sql);
    $n1 = pg_num_rows($r1);
					    
	$max_row= 30 ;
	$mulai = $HTTP_GET_VARS["rec"] ;	
	if (!$mulai){$mulai=1;}  
	
?>

<table width='75%'>
	<tr>
		<td class="TBL_HEAD" width='5%'><center>NO.</center></td>
		<td class="TBL_HEAD" ><center>NAMA OBAT</center></td>
		<td class="TBL_HEAD" width='8%'><center>QTY</center></td>
		<td class="TBL_HEAD" width='15%'><center>HARGA</center></td>
		<td class="TBL_HEAD" width='15%'><center>R OBAT/PPn</center></td>
		<td class="TBL_HEAD" width='15%'><center>TAGIHAN</center></td>
	</tr>
	 <?
    $i=0;
	$total=0;
	while($row1=pg_fetch_array($r1)){
	$i=$i+1;
	?>
	<tr>
		<td class="TBL_BODY" align="center"><?=$i?></td>
		<td class="TBL_BODY" align="left"><?=$row1["obat"]?></td>
		<td class="TBL_BODY" align="center"><?=$row1["qty"]?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row1["harga"])?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row1["referensi"])?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row1["tagihan"])?></td>
	</tr>
	<? $total=$total+$row1["tagihan"]+$sqlrck; $no=$i+1;} ?>
	<tr>
		<td class="TBL_BODY" align="center"><?=$no?></td>
		<td class="TBL_BODY" colspan="4" align="left">Obat Racikan</td>
		<td class="TBL_BODY" align="right" width='15%'><?=number_format($sqlrck)?></td>
	</tr>
	<tr>
		<td class="TBL_HEAD" colspan="5" align="right">T O T A L</td>
		<td class="TBL_HEAD" align="right" width='15%'><?=number_format($total)?>&nbsp;&nbsp;</td>
	</tr>
	
	<FORM ACTION='actions/apotek_bayar1.php' NAME='Form1' method='GET'>
	<INPUT TYPE='HIDDEN' NAME='p'  VALUE='<?=$PID?>'>
	<INPUT TYPE='HIDDEN' NAME='tt' VALUE='<?=$_GET["tt"]?>'>
	<INPUT TYPE='HIDDEN' NAME='rg' VALUE='<?=$_GET["rg"]?>'>
	<INPUT TYPE='HIDDEN' NAME='sub' VALUE='<?=$_GET["sub"]?>'>
	<?$cek=getFromTable("select count(reg) from rs00005 where (kasir='BYG' or kasir='BYC' or kasir='BYS' or kasir='BYA') and reg='".$_GET["rg"]."'");    if ($cek==0) {?>
	<tr>
		<td class="TBL_BODY" colspan="5" align="right"><b>P O T O N G A N</b></td>
		<td class="TBL_HEAD" align="right" width='15%'><INPUT NAME='keringanan' TYPE=TEXTAREA SIZE=18 MAXLENGTH=20 VALUE='0' STYLE='text-align:right' onchange='hitung1()'></td>
	</tr>
	<tr>
		<td class="TBL_BODY" colspan="5" align="right"><b>HARGA YANG HARUS DIBAYAR</b></td>
		<td class="TBL_HEAD" align="right" width='15%'><INPUT NAME='hrg' TYPE='HIDDEN' VALUE='<?=$total?>'><INPUT NAME='hrg1' TYPE=TEXTAREA SIZE=18 MAXLENGTH=20 VALUE='0' STYLE='text-align:right' disabled></td>
	</tr>
	<tr>
		<td class="TBL_BODY" colspan="5" align="right"><b>UANG PEMBAYARAN</b></td>
		<td class="TBL_HEAD" align="right" width='15%'>	<INPUT TYPE='HIDDEN' NAME='askes' VALUE='0'>
														<INPUT TYPE='HIDDEN' NAME='tmp_tagihan' VALUE='<?=$total?>'>
														<INPUT NAME='bayar' TYPE=TEXTAREA SIZE=18 MAXLENGTH=20 VALUE='<?=$total?>' STYLE='text-align:right' onchange='hitung2()'></td>
	</tr>
	<tr>
		<td class="TBL_BODY" colspan="5" align="right"><b>UANG KEMBALIAN</b></td>
		<td class="TBL_HEAD" align="right" width='15%'><INPUT NAME='sisa' TYPE=TEXTAREA SIZE=18 MAXLENGTH=20 VALUE='0' STYLE='text-align:right' disabled></td>
	</tr>
	<tr>
	<td class="TBL_BODY" colspan="5" align="right">&nbsp;</td>
	<td class="TBL_HEAD" align="center" width='15%'><input type=button value='Bayar Obat' onClick='document.Form1.submit()'>&nbsp;&nbsp;</td>
	</tr>
	<?}else{?>
	<tr>
	<td class="TBL_BODY" colspan="5" align="right">&nbsp;</td>
	<td class="TBL_BODY" align="center" width='15%'><a href="javascript: cetakkwitansi(<? echo (int) $_GET[rg];?>)" ><img src="images/cetak.gif" border="0"></a></td>
	</tr>
	<?}?>
	</FORM>
</table>

<?


    } elseif ($_GET["sub"] == "racikan") { // -------- RACIKAN
       title("Layanan / Racikan");
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b10.disabled = true;\n";
        echo "</script>\n";

        if ($_SESSION["SELECT_OBAT2"]) {
			$namaObat2 = getFromTable("select obat from rsv0004 where id = '".$_SESSION["SELECT_OBAT2"]."'");
			$idObat2 = getFromTable("select id from rsv0004 where id = '".$_SESSION["SELECT_OBAT2"]."'");
           
			$hargaObat2 = getFromTable("select harga from rsv0004 where id = '".$_SESSION["SELECT_OBAT2"]."'");
			$kategori2 = getFromTable("select kategori_id from rsv0004 where id = '".$_SESSION["SELECT_OBAT2"]."'");
			$ppn2 = getFromTable("select tc_poli from rs00001 where tc='$kategori2' and tt='GOB'");
			
		   if ($_GET["tt"]=="igd") {
           $q=  getFromTable("select qty_jantung from rs00016a where obat_id='$idObat2'"); 
		   }elseif ($_GET["tt"]=="swd") {
           $q=  getFromTable("select qty_interne from rs00016a where obat_id='$idObat2'"); 
		   }elseif ($_GET["tt"]=="cdm") {
           $q=  getFromTable("select qty_jiwa from rs00016a where obat_id='$idObat2'"); 
		   }elseif ($_GET["tt"]=="ask") {
           $q=  getFromTable("select qty_kebid from rs00016a where obat_id='$idObat2'"); 
		   }elseif ($_SESSION["gr"]=="APOTEK5") {
           $q=  getFromTable("select qty_apotek5 from rs00016a where obat_id='$idObat2'"); 
		   }elseif ($_SESSION["gr"]=="APOTEK6") {
           $q=  getFromTable("select qty_apotek6 from rs00016a where obat_id='$idObat2'"); 
		   }elseif ($_SESSION["gr"]=="APOTEK7") {
           $q=  getFromTable("select qty_apotek7 from rs00016a where obat_id='$idObat2'"); 
		   }
			
        }


        $x_jumlah2 = " <SELECT name='jumlah_obat2'>\n";
          for ($i='1'; $i<=$q; $i++){
                 if ($i=='1') {
             $x_jumlah2  .= "<option value=$i selected>$i</option>\n";

          }
            else {
            $x_jumlah2  .= " <option value=$i>$i</option>\n";
          }
          }
        "</SELECT>\n";

        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=tt VALUE='".$_GET["tt"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("Batch Id", "Nama Obat", "Jumlah", "% Jasa racikan",  "Harga Satuan", "Harga Total", ""));


        if (is_array($_SESSION["racikan"])) {


            foreach($_SESSION["racikan"] as $k => $l) {
                $t->printRow(
                    Array( $l["batch1"],$l["desc1"],$l["jumlah1"],$l["ppn1"], number_format($l["harga1"],2), number_format($l["total1"],2),
                    "<A HREF='$SC?p=$PID&tt=".$_GET["tt"]."&rg=".$_GET["rg"]."&del-racikan=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "LEFT", "LEFT", "RIGHT", "RIGHT", "CENTER","RIGHT", "CENTER")
                );
            }
        }
        
	
        $t->printRow(
            Array("<INPUT OnKeyPress='refreshSubmit2()' NAME=batch STYLE='text-align:center'  VALUE='".$_SESSION["SELECT_OBAT2"]."'>&nbsp;<A HREF='javascript:selectObat1()'>
            <IMG SRC='images/icon-view.png'></A>", $namaObat2,
            $x_jumlah2, $ppn2, number_format($hargaObat2,2), "",
            "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "LEFT", "center", "right", "CENTER","RIGHT", "CENTER")
        );
        
        $t->printTableClose(); 
   echo "</FORM>";
        
        echo "\n<script language='JavaScript'>\n";
        echo "function selectObat1() {\n";
		if ($_GET["tt"]=="igd") {
        echo "    sWin = window.open('popup/obat_racikanigd.php?mOBT=002', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_GET["tt"]=="swd") {
        echo "    sWin = window.open('popup/obat_racikanswd.php?mOBT=002', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_GET["tt"]=="cdm") {
        echo "    sWin = window.open('popup/obat_racikancdm.php?mOBT=002', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_GET["tt"]=="ask") {
        echo "    sWin = window.open('popup/obat_racikanask.php?mOBT=002', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_SESSION["gr"]=="APOTEK5") {
        echo "    sWin = window.open('popup/obat5.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_SESSION["gr"]=="APOTEK6") {
        echo "    sWin = window.open('popup/obat6.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_SESSION["gr"]=="APOTEK7") {
        echo "    sWin = window.open('popup/obat7.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}
		echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";
        
    } elseif ($_GET["sub"] == "obat") { // OBAT

        title("Resep / Obat");
        echo "<script language='JavaScript'>\n";
        echo "document.Form3.b3.disabled = true;\n";
        echo "</script>\n";

        if ($_SESSION["SELECT_OBAT"]) {
		   
		   $namaObat = getFromTable("select obat from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
           $idObat = getFromTable("select id from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
           $hargaObat = getFromTable("select harga from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
           $satuan = getFromTable("select satuan from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
		   
		   $kategori = getFromTable("select kategori_id from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
		   $ppn = getFromTable("select comment from rs00001 where tc='$kategori' and tt='GOB'");
			
		   if ($_GET["tt"]=="igd") {
           $q=  getFromTable("select qty_jantung from rs00016a where obat_id='$idObat'"); 
		   }elseif ($_GET["tt"]=="swd") {
           $q=  getFromTable("select qty_interne from rs00016a where obat_id='$idObat'"); 
		   }elseif ($_GET["tt"]=="cdm") {
           $q=  getFromTable("select qty_jiwa from rs00016a where obat_id='$idObat'"); 
		   }elseif ($_GET["tt"]=="ask") {
           $q=  getFromTable("select qty_kebid from rs00016a where obat_id='$idObat'"); 
		   }elseif ($_SESSION["gr"]=="APOTEK5") {
           $q=  getFromTable("select qty_apotek5 from rs00016a where obat_id='$idObat'"); 
		   }elseif ($_SESSION["gr"]=="APOTEK6") {
           $q=  getFromTable("select qty_apotek6 from rs00016a where obat_id='$idObat'"); 
		   }elseif ($_SESSION["gr"]=="APOTEK7") {
           $q=  getFromTable("select qty_apotek7 from rs00016a where obat_id='$idObat'"); 
		   }
		   
        }

        
        $x_jumlah = " <SELECT name='jumlah_obat'>\n";
          for ($i='1'; $i<=$q; $i++){
		  
                 if ($i=='1') {
             $x_jumlah  .= "<option value=$i selected>$i</option>\n";

          }
            else {
            $x_jumlah  .= " <option value=$i>$i</option>\n";
          }
          }
        "</SELECT>\n";

        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE='$PID'>";
		echo "<INPUT TYPE=HIDDEN NAME=tt VALUE='".$_GET["tt"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";
        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("Obat Id", "Nama Obat", "Cara Pemakaian","Satuan", "Jumlah", "R / Jasa Resep", "Harga Satuan", "Harga Total", ""));


        if (is_array($_SESSION["obat"])) {


            foreach($_SESSION["obat"] as $k => $l) {
                $t->printRow(
                    Array( $l["id"],$l["desc"],$l["dosis"],$l["ket_racikan"] , $l["jumlah"], number_format($l["ppn"],2),number_format($l["harga"],2), number_format($l["total"],2),
                    "<A HREF='$SC?p=$PID&tt=".$_GET["tt"]."&rg=".$_GET["rg"]."&del-obat=$k&httpHeader=1'>".icon("del-left")."</A>"),
                    Array("CENTER", "LEFT", "LEFT", "LEFT", "RIGHT", "RIGHT", "CENTER","RIGHT","RIGHT", "CENTER")
                );
            }
        }
        
	
        $t->printRow(
            Array("<INPUT OnKeyPress='refreshSubmit2()' NAME=obat STYLE='text-align:center'  VALUE='".$_SESSION["SELECT_OBAT"]."'>&nbsp;<A HREF='javascript:selectObat()'>
            <IMG  SRC='images/icon-view.png'></A>", $namaObat,
            "<INPUT VALUE='".(isset($_GET["dosis"]) ? $_GET["dosis"] : "3 x 1")."'NAME=dosis OnKeyPress='refreshSubmit2()' TYPE=TEXTAREA SIZE=20 MAXLENGTH=20 VALUE='0' STYLE='text-align:left'>", //penambahan dosis
            $satuan, //penambahan ket.racikan
            $x_jumlah,$ppn,number_format($hargaObat,2), "",
            "<INPUT NAME='submitButton' TYPE=SUBMIT VALUE='OK'>"),
            Array("CENTER", "LEFT", "LEFT", "RIGHT", "RIGHT", "CENTER","RIGHT","RIGHT","CENTER")
        );
	// --- eof 27-12-2006 ---
        $t->printTableClose(); 

        echo "</FORM>";
        echo "\n<script language='JavaScript'>\n";
        echo "function selectObat() {\n";
		if ($_GET["tt"]=="igd") {
        echo "    sWin = window.open('popup/obat_apotek_igd.php?mOBT=002', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_GET["tt"]=="swd") {
        echo "    sWin = window.open('popup/obat_apotek_swd.php?mOBT=002', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_GET["tt"]=="cdm") {
        echo "    sWin = window.open('popup/obat_apotek_cdm.php?mOBT=002', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_GET["tt"]=="ask") {
        echo "    sWin = window.open('popup/obat_apotek_ask.php?mOBT=002', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_SESSION["gr"]=="APOTEK5") {
        echo "    sWin = window.open('popup/obat5.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_SESSION["gr"]=="APOTEK6") {
        echo "    sWin = window.open('popup/obat6.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}elseif ($_SESSION["gr"]=="APOTEK7") {
        echo "    sWin = window.open('popup/obat7.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
		}
		echo "    sWin.focus();\n";
        echo "}\n";
        echo "</script>\n";

    } elseif ($_GET["sub"] == "retur") { // -------- RETUR

        title("Retur Obat");
        echo "<br>";

        if ($_GET[sub2] == 1) {

$q = @pg_query(
    "select TO_CHAR(a.tanggal_trans,'dd-mm-yyyy') as tanggal_trans, b.obat, c.tdesc as satuan, a.item_id, a.harga, a.qty, d.qty as retur, (a.qty*a.harga) as jumlah, a.no_reg as dummy ".
    "from rs00008 a ".
    "     left join rs00015 b on a.item_id = b.id ".
    "     left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
    "     left join rs00008 d on d.referensi = a.id and d.trans_type = 'RET' ".
    "where a.id = '$_GET[id]'");


$qr = @pg_fetch_object($q);
$sisa = $qr->qty - $qr->retur;

    $f = new ReadOnlyForm();
    $f->title("Data Obat");
    $f->text("Obat ID",$qr->item_id);
    $f->text("Nama Obat",$qr->obat);
    $f->text("Satuan",$qr->satuan);
    $f->text("Harga",number_format($qr->harga,2,',','.'));
    $f->text("Jumlah",$sisa);
    //$f->text("Total",$qr->qty_rj);
    //$f->text("Stok Apotek R/I",$qr->qty_ri);
    $f->execute();

    //$totalret = $qr->qty*$qr->harga;

    echo "<br>";
    if ($sisa > 0) {
    $f = new Form("actions/123retur.insert.php", "POST", "NAME=Form1");
    //$f->PgConn = $con;
    $f->hidden("retur_id",$_GET[id]);
    $f->hidden("rg",$_GET[rg]);
    $f->hidden("sub",$_GET[sub]);
    $f->hidden("rawatan",$rawatan);
    $f->hidden("harga",$qr->harga);

    $f->hidden("sisa",$sisa);
    $f->hidden("id",$qr->item_id);


    $f->text("retur","Retur",10,"","","");

    $f->submit(" Simpan ");
    $f->execute();
    }



        } else {


if (empty($_GET[sort])) {
   $_GET[sort] = "tanggal_trans";
}

$t = new PgTable($con, "100%");

$t->SQL =
//    "select a.tanggal_trans, b.obat, c.tdesc as satuan, a.harga, a.qty, sum(d.qty) as retur, (a.qty*a.harga) as jumlah, a.id as dummy ".
    "select TO_CHAR(a.tanggal_trans,'dd-mm-yyyy') as tanggal_trans, b.obat, c.tdesc as satuan, a.harga, a.qty, sum(d.qty) as retur, a.id as dummy ".
    "from rs00008 a ".
  //  "     left join rs00015 b on a.item_id = b.id ".
  "     left join rs00015 b on a.item_id = b.id_obat ".
    "     left join rs00001 c on b.satuan_id = c.tc and c.tt='SAT' ".
 //   "     left join rs00008 d on d.referensi = a.id and d.trans_type = 'RET' ".
   "     left join rs00008 d on d.referensi = a.id_transaksi and d.trans_type = 'RET' ".
    "where a.trans_type='OB1' and a.no_reg= '$_GET[rg]' ".
    "group by a.tanggal_trans, b.obat, c.tdesc, a.harga, a.qty, a.id";

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
$t->RowsPerPage = 50;
$t->ColAlign[3] = "RIGHT";
$t->ColAlign[4] = "RIGHT";
$t->ColAlign[5] = "RIGHT";
$t->ColAlign[6] = "CENTER";
//$t->ColAlign[7] = "CENTER";

$t->ColFormatNumber[3] = 2;
$t->ColFormatNumber[4] = 0;
$t->ColFormatNumber[5] = 0;
//$t->ColFormatNumber[6] = 0;



//$t->ColFormatMoney[2] = "%!+#2n";

//$t->ColHeader = array("NAMA OBAT", "SATUAN", "HARGA", "AWAL","TERIMA","KELUAR","AKHIR");
$t->ColHeader = array("TANGGAL", "NAMA OBAT", "SATUAN", "HARGA", "JUMLAH","RETUR","");
$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&rg=".$_GET[rg]."&sub=retur&id=<#6#>&sub2=1'>".icon("view","View")."</A>";
$t->execute();
        }   // end of sub2 = 1

    } 

    echo "</div>";

    if ($_GET["sub"] != "bayar") {

	echo "<table width='100%'><tr>";
	echo "<td align=right valign=top>";
        if ($_GET[sub] != "retur") {
        echo "<form name='Form9' action='actions/320RJ.insert.php' method=POST>";
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=sub VALUE='".$_GET["sub"]."'>";
        echo "<input type=hidden name=tt value='".$_GET["tt"]."'>";
        echo "<input type=button value='Simpan' onClick='document.Form9.submit()'>&nbsp;";
        echo "</form>";
        }
        echo "</td></tr>";

        if (empty($_GET["sub"])) {

        if ($_SESSION[gr] == "laborat"  || $_SESSION[gr] == "root" || $_SESSION[uid] == "apotek") {

        echo "<form name='Form10' action='actions/998.1.load.php' method=POST>";
        echo "<tr><td>";
	// ------------ paket laboratorium
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";

        echo "</form>";

        } // end of $_SESSION[gr] == laborat || root


        if ($_SESSION[gr] == "radiologi" || $_SESSION[gr] == "root" || $_SESSION[uid] == "apotek") {

        echo "<form name='Form10' action='actions/998.1.load.php' method=POST>";
        echo "<tr><td>";
	// ------------ paket radiologi
        echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";


        } 

        }

        echo "</table>";


    }

    echo "\n<script language='JavaScript'>\n";
    echo "function selectLayanan() {\n";
    echo "    sWin = window.open('popup/layanan.php', 'xWin', 'top=0,left=0,width=600,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    
    //echo "\n<script language='JavaScript'>\n";
        echo "function selectPegawai(tag) {\n";
        echo "    sWin = window.open('popup/pegawai.php?tag=' + tag, 'xWin',".
             " 'top=0,left=0,width=500,height=400,menubar=no,scrollbars=yes');\n";
        echo "    sWin.focus();\n";
        echo "}\n";
        //echo "
        

    if (empty($_GET[sub])) {
    echo "function refreshSubmit() {\n";
    echo "    document.Form8.submitButton.disabled = Number(document.Form8.layanan.value) == 0 || Number(document.Form8.jumlah.value == 0);\n";
    echo "}\n";
    echo "refreshSubmit();\n";

    }

   
    echo "</script>\n";
if ($_GET[sub] !="bayar") {
  include("rincianobat.php");
echo "<br><br>";
  include("rincianapotik.php");
}


} else {
    echo "<DIV>";
/* Rawat Jalan */
//awalnya [gr]
    //if ($_SESSION[uid] == "apotikri" || $_SESSION[uid] == "apotikrj" || $_SESSION[gr] == "daftar" || $_SESSION[uid] == "daftarri" || $_SESSION[uid] == "root"|| $_SESSION[uid] == "apotek") {

	    $ext = "OnChange = 'Form1.submit();'";
	    $f = new Form($SC, "GET", "NAME=Form1");
	    $f->PgConn = $con;
	    $f->hidden("p", $PID);
	    echo "<br>";
		echo "<TABLE  width='100%'><tr><td align='left'>";
/*
	    $f->selectSQL("mPOLI", "P O L I",
			        "select '' as tc, '' as tdesc union ".
				"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' order by tdesc "
				, $_GET["mPOLI"],$ext);
	
		$f->execute();
*/
    //} 		
		echo "</td><td ALIGN=RIGHT>";
			$f = new Form($SC, "GET","NAME=Form2");
		    $f->hidden("p", $PID);
			$f->hidden("tt", $_GET["tt"]);
		    if (!$GLOBALS['print']){
		    	$f->search("search","Pencarian Nama atau No.MR",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
			}else { 
			   	$f->search("search","Pencarian Nama atau No.MR",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
			}
		    $f->execute();
	    	if ($msg) errmsg("Error:", $msg);
			//---------------------
		echo "</td></tr></table>";
                echo "<br>";
	$SQLSTR =
        "select d.mr_no, a.id, TO_CHAR(a.tanggal_reg,'dd-mm-yyyy') as tanggal_reg, d.nama,
		(SELECT x.tdesc FROM rs00001 x WHERE x.tt = 'LYN' AND x. tc_poli=a.poli) as layanan,
		case when a.rawat_inap='Y' then 'RAWAT JALAN'
		when a.rawat_inap='I' then 'RAWAT INAP ' else 'IGD' end as rawatan,
		b.tdesc as pasien,
		case when a.rujukan='N' then 'Non-Rujukan'
		when a.rujukan='U' then 'Unit Lain' else 'Rujukan' end as datang,
		case when a.status_apotek='0' then 'Resep'
		when a.status_apotek='1' then 'Tunggu'
		when a.status_apotek='2' then 'Bayar Kasir'
		when a.status_apotek='3' then 'Tunggu Obat'
		when a.status_apotek='4' then 'Ambil Obat' else 'Selesai' end as status
		from  rs00006 a
		left join rs00001 b ON a.tipe = b.tc and b.tt='JEP'
		left join rs00002 d ON a.mr_no = d.mr_no ";


        $tglhariini = date("Y-m-d", time());
/* 	if (strlen($_GET["mPOLI"]) > 0 ) {
		$SQLWHERE =
			"where  a.poli ='".$_GET["mPOLI"]."' and ".
			"	(upper(d.nama) LIKE '%".strtoupper($_GET["search"])."%') ";
	} else {
		$SQLWHERE =
			"where  ". 
		 	"	 (upper(d.nama) LIKE '%".strtoupper($_GET["search"])."%' ) and a.is_bayar='N' and a.status_apotek not in ('1') ";
	} */

	if ($_GET["search"]) {
		$SQLWHERE =
			"where  ((upper(d.nama) LIKE '%".strtoupper($_GET["search"])."%') OR ".
                        "	d.mr_no LIKE '%".$_GET["search"]."%'  or ".
                        "   a.id LIKE '%".$_GET["search"]."%' ) ";

	}

         if ($_SESSION[gr] == "igd") {
           $SQLWHERE2 = "and a.rawat_inap='N' ";
           $title_layanan = "IGD" ;
        } elseif ($_SESSION[gr] == "rj") {
           $SQLWHERE2 = "and a.rawat_inap='Y' ";
            $title_layanan = "RAWAT JALAN " ;
        } elseif ($_SESSION[gr] == "ri") {
           $SQLWHERE2 = "and a.rawat_inap='I' ";
           $title_layanan = "RAWAT INAP " ;
        } else {
           $SQLWHERE2 = " ";
        }
 

        $SQLWHERE4 = "AND (a.rawat_inap = 'Y' or a.rawat_inap = 'N')" ;


/* 	if (!isset($_GET[sort])) {

           $_GET[sort] = "a.id";
           $_GET[order] = "asc";
	} */

title($title_layanan);
	if ($_GET["search"]) {
    $t = new PgTable($con, "100%");
	
    $t->SQL = "$SQLSTR $SQLWHERE  group by d.mr_no,a.id, a.tanggal_reg, d.nama,a.rawat_inap,a.rujukan,a.status_apotek,a.poli,b.tdesc ";
    
	$t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "LEFt";
    $t->ColAlign[4] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->ColAlign[6] = "CENTER";
    $t->ColAlign[9] = "CENTER";

    $t->RowsPerPage = 20;
	$t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tt=".$_GET["tt"]."&rg=<#1#>$tambah&sub=obat'><#1#></A>";
    //$t->ColFormatHtml[9] = "<A CLASS=TBL_HREF HREF='#' onclick = 'selesai1(<#9#>)'>".icon("ok","Selesai Input")."</A>";
    $t->ColFormatMoney[2] = "%!+#2n";
	
    $t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL<br>REGISTRASI","NAMA PASIEN","P O L I","LOKET","TIPE PASIEN","KEDATANGAN","STATUS");
	//$t->ShowSQL = true;
    $t->execute();
}

    echo "</DIV>";
}


}  

/* echo "
<script type='text/javascript'>
function selesai(reg)
{
var sip = 'actions/apotek.insert.php?p=$PID&id=' + reg;
var stay= confirm('Apakah Anda yakin sudah selesai input?')
if (!stay) {
window.location='$SC?p=$PID';
}else{
window.location=sip;
}
}
</script>";

echo "
<script type='text/javascript'>
function selesai1(reg)
{
var sip = 'actions/apotek.insert.php?p=$PID&id=' + reg;
var stay= confirm('Apakah anda yakin akan ubah status?')
if (!stay) {
window.location='$SC?p=$PID';
}else{
window.location=sip;
}
}
</script>"; */
?>
