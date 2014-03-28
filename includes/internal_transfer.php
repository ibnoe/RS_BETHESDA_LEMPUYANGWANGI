<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 09-05-2004

$PID = "internal_transfer";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$tgl_sekarang = date("d-m-Y", time());
$tglhariini = date("Y-m-d", time());

if ($_GET["httpHeader"] == "1") {
    if (isset($_GET["nomor_invoice"])) {
        $_SESSION["ob4"]["nomor-invoice"] = $_GET["nomor_invoice"];
    }if (isset($_GET["ket_depo"])) {
        $_SESSION["ob4"]["ket-depo"] = $_GET["ket_depo"];
    }
    
    
    if (strlen($_GET["ob4_id"]) > 0 && $_GET["jumlah_obat"] > 0) {
        if (is_array($_SESSION["ob4"]["obat"])) {
            $cnt = count($_SESSION["ob4"]["obat"]);
        } else {
            $cnt = 0;
        }
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_GET["ob4_id"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
        
        $_SESSION["ob4"]["obat"][$cnt]["id"]     = $d1->id;
		$_SESSION["ob4"]["obat"][$cnt]["batch"]  = $d1->batch;
        $_SESSION["ob4"]["obat"][$cnt]["obat"]   = $d1->obat;
        $_SESSION["ob4"]["obat"][$cnt]["satuan"] = $d1->satuan;
        $_SESSION["ob4"]["obat"][$cnt]["keterangan"]  = $_GET["ob4_keterangan"];              
        $_SESSION["ob4"]["obat"][$cnt]["jumlah_pakai"] = $_GET["jumlah_obat"];
        unset($_SESSION["SELECT_OBAT"]);
        
    }
    if (isset($_GET["del"])) {
        $temp = $_SESSION["ob4"]["obat"];
        unset($_SESSION["ob4"]["obat"]);
        $cnt = 0;
        foreach ($temp as $k => $v) {
            if ($k != $_GET["del"]) {
                $_SESSION["ob4"]["obat"][$cnt] = $v;
                $cnt++;
            }
        }
    }
    if (isset($_GET["editrow"])) {
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["jumlah_pakai"] = $_GET["editjumlah_pakai"];
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["keterangan"] = $_GET["editketerangan"];

    }
    header("Location: $SC?p=$PID&action=new&poli_asal=".$_GET["poli_asal"]."&poli_tujuan=".$_GET["poli_tujuan"]."");
    exit;
}


if ($_GET["action"] == "new") {
title("<img src='icon/apotik-2.gif' align='absmiddle' >  TRANSAKSI BARANG RUANGAN (EDIT)");
echo "<br>";
echo "<form action=$SC>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
$ext = "onchange='javascript:Form1.submit()'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	
	
    $f->selectSQL("poli_asal", "Dari ",
						"select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc not in ('000') order by tdesc "
						,$_GET["poli_asal"],$ext);
    $f->selectSQL("poli_tujuan", "Tujuan ",
						"select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc not in ('000','".$_GET["poli_asal"]."') order by tdesc "
						,$_GET["poli_tujuan"],$ext);
	$f->submit("OK",$ext);					
    $f->execute();
    echo "<br>";
        ?>
        <SCRIPT language="JavaScript">
            document.Form1.poli_tujuan.selectedIndex = -1;
            function setPoli( v )
            {
                document.Form1.poli_tujuan.disabled = v == "N";
                document.Form1.poli_tujuan.selectedIndex = document.Form1.poli_tujuan.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.poli_tujuan.selectedIndex : -1;
            }
			
			document.Form1.poli_asal.selectedIndex = -1;
            function setPoli( v )
            {
                document.Form1.poli_asal.disabled = v == "N";
                document.Form1.poli_asal.selectedIndex = document.Form1.poli_asal.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.poli_asal.selectedIndex : -1;
            }
        </SCRIPT>
        <?php
$depo=$_GET["poli_tujuan"];
if (isset($depo)==$_GET["poli_tujuan"]) {

    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }
		if ($_GET["poli_asal"]=="003") {
           $q=  getFromTable("select gudang from rs00016a where obat_id=$d1->id"); 
		   }elseif($_GET["poli_asal"]=="020") {
           $q=  getFromTable("select qty_ri from rs00016a where obat_id=$d1->id"); 
		   }else{
		   $q=  getFromTable("select qty_".$_GET["poli_asal"]." from rs00016a where obat_id=$d1->id"); 
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
		
    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("BATCH ID","KODE", "NAMA OBAT","SATUAN","JUMLAH", "KETERANGAN",""));
    if (is_array($_SESSION["ob4"]["obat"])) {
        $total = 0.00;
        foreach($_SESSION["ob4"]["obat"] as $k => $o ) {
            if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
                echo "<form action=$SC onSubmit='return validasi2()' name=formx>";
                echo "<input type=hidden name=p value=$PID>";
                echo "<input type=hidden name=editrow value=$k>";
                echo "<input type=hidden name=httpHeader value=1>";
                echo "<input type=hidden name=poli_tujuan value=".$_GET[poli_tujuan].">";
				echo "<input type=hidden name=poli_asal value=".$_GET[poli_asal].">";
                
                $t->printRow(
                    Array( str_pad($o["batch"],6,"0",STR_PAD_LEFT),
						$o["id"],
                        $o["obat"],
                        $o["satuan"],
                        $x_jumlah,
                        "<input type=text size=40 maxlength=50 name=editketerangan value='".$o["keterangan"]."' style='text-align:left'>",
                        //"<input type=text size=10 maxlength=15 name=edittglPakai value='".$o["tglPakai"]."' style='text-align:right'>",
                        "<input type=submit value='Update'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}\"'>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER")
                    );
                echo "</form>";
            } else {
                $t->printRow(
                    Array( str_pad($o["batch"],6,"0",STR_PAD_LEFT),
                        $o["id"],
						$o["obat"],
                        $o["satuan"],
                        $o["jumlah_pakai"],
                        $o["keterangan"],
                        //$o["tglPakai"],
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}'>".icon("del-left")."</a>"),
                        //" &nbsp; " ,
                        //"<a href='$SC?p=$PID&edit=$k&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}'>".icon("edit")."</a>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER")
                    );
            }
            $total += $o["total"];
        }
    }

    if (strlen($_GET["edit"]) == "0") {
        
		echo "<form action=$SC name=formx>";
        echo "<input type=hidden name=p value=$PID>";
        echo "<input type=hidden name=httpHeader value=1>";
        echo "<input type=hidden name=poli_asal value=".$_GET[poli_asal].">";
		echo "<input type=hidden name=poli_tujuan value=".$_GET[poli_tujuan].">";
		if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }
		
		   if ($_GET["poli_asal"]=="003") {
           $q=  getFromTable("select gudang from rs00016a where obat_id=$d1->id"); 
		   }elseif($_GET["poli_asal"]=="020") {
           $q=  getFromTable("select qty_ri from rs00016a where obat_id=$d1->id"); 
		   }else{
		   $q=  getFromTable("select qty_".$_GET["poli_asal"]." from rs00016a where obat_id=".$_SESSION["SELECT_OBAT"].""); 
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
		
        $t->printRow(
            Array( 
            	"<input type=text size=20 maxlength=20 name=ob4_id1 style='text-align:center' value=$d1->batch>"."&nbsp;<a href='javascript:selectObat()'><input type=hidden size=20 name=ob4_id value=$d1->id>".icon("view")."</a>",
				$d1->id,
				$d1->obat,
                $d1->satuan,
				$x_jumlah,
                //$x_jumlah,
                "<input type=text size=40 maxlength=50 name=ob4_keterangan value='".$_SESSION["ob4"]["obat"]["keterangan"]."' style='text-align:left'>",
                //"<input type=text size=7 maxlength=10 name=ob4_tglPakai value='".$tgl_sekarang."' style='text-align:right'>",
                "<input type=submit value=OK>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER")
            );
        echo "</FORM>";
     
    }
    $t->printTableClose();



    if (is_array($_SESSION["ob4"]["obat"])) {
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/internal_transfer.insert.php' method=POST name=Form10>";
        echo "<input type=hidden name=poli_tujuan value=".$_GET[poli_tujuan].">";
		echo "<input type=hidden name=poli_asal value=".$_GET[poli_asal].">";
        echo "<input type=submit value=' &nbsp; Simpan &nbsp; '>";
        echo "</form>";
        echo "</div>";
    }

	
    echo "\n<script language='JavaScript'>\n";
    echo "function selectObat() {\n";
	
	if ($_GET["poli_asal"]=="003") {
           echo "    sWin = window.open('popup/obat_gudang.php?mOBT=002', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n";
		   }elseif ($_GET["poli_asal"]=="020") {
           echo "    sWin = window.open('popup/obat.php?mOBT=002', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n";
		   }else{
           echo "    sWin = window.open('popup/obat001.php?mOBT=002&asal=".$_GET["poli_asal"]."&tujuan=".$_GET["poli_tujuan"]."', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n"; 
		   }
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
}
} else if($_GET["action"] == "view") {
echo "<table>";
echo "<tr>";
echo "<td>".title("<img src='icon/apotik-2.gif' align='absmiddle' >  RINCIAN OBAT BARANG RUANGAN ")."</td>";
echo "<td>".title_print("")."</td>";
echo "</tr>";
echo "</table>";
	
	$cek_status_ver=getFromTable("select status from internal_transfer_m where kode_transaksi=".$_GET["f"]." ");
    
    $tanggal = getFromTable(
               "select to_char(tanggal_trans,'DD Mon YYYY') from internal_transfer_m ".
               "where kode_transaksi='".$_GET["f"]."' ");

	$qty_asal = getFromTable(
               "select 'qty_'||poli_tujuan from internal_transfer_m ".
               "where kode_transaksi='".$_GET["f"]."' ");
	
    $f = new Form("");

echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NO. TRANSAKSI </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["f"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> ASAL </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["e"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> TUJUAN </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["g"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> TANGGAL TRANSAKSI</td>";
		echo "<td bgcolor='B0C4DE'><b>: $tanggal </td>";
	echo "</tr>";
	
echo "</table>";

    $f->execute();
    
    if (!$GLOBALS['print']){
    	//echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    } else {
    	"";
    }

    echo "<br>";
	
    $t = new PgTable($con, "100%"); 
	if($cek_status_ver == 0){
   	$t->SQL = "select a.obat,b.batch_id,b.jumlah||' '||d.tdesc as jumlah, c.$qty_asal||' '||d.tdesc as stok_ruang, 
	b.keterangan,nm_user,case when b.status='0' then 'Belum di Konfirm' else 'Sudah di Konfirm' end ,verifikator,b.oid as dummy 
	from internal_transfer_d b, rs00015 a, rs00016a c, rs00001 d 
	where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id and c.obat_id::text=b.item_id and a.satuan_id=d.tc AND d.tt='SAT' ";
    $t->ColHeader = array("NAMA OBAT", "BATCH ID","QTY<br>PERMINTAAN","QTY<br>RUANGAN","KETERANGAN","PEMINTA","SATUS","VERIFIKATOR","EDIT");
	$t->ColAlign = array("LEFT","LEFT","CENTER","CENTER","LEFT","LEFT","LEFT","LEFT","CENTER");
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 9999;
	$t->ColFormatHtml[8] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=edit&f=".$_GET[f]."&e=".$_GET[e]."&g=".$_GET[g]."&id_obt=<#8#>'>".icon("edit","Edit")."</A>
						&nbsp;<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=verifikasi&f=".$_GET[f]."&e=".$_GET[e]."&g=".$_GET[g]."&id_obt=<#8#>'>".icon("ok","Verifikasi")."</A>";
	}else{
	$t->SQL = "select a.obat,b.batch_id,b.jumlah||' '||d.tdesc as jumlah,  
	b.keterangan,nm_user,case when b.status='0' then 'Belum di Konfirm' else 'Sudah di Konfirm' end ,verifikator
	from internal_transfer_d b, rs00015 a, rs00016a c, rs00001 d 
	where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id and c.obat_id::text=b.item_id and a.satuan_id=d.tc AND d.tt='SAT' ";
    $t->ColHeader = array("NAMA OBAT", "BATCH ID","QTY KIRIM","KETERANGAN","PEMINTA","SATUS","VERIFIKATOR");
	$t->ColAlign = array("LEFT","LEFT","CENTER","CENTER","LEFT","LEFT","LEFT","LEFT","CENTER");
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 9999;
	}
    $t->execute();
}else if($_GET["action"] == "verifikasi") {
	$qty_asal = getFromTable(
               "select 'qty_'||poli_asal from internal_transfer_m ".
               "where kode_transaksi='".$_GET["f"]."' ");
	$qty_tujuan = getFromTable(
               "select 'qty_'||poli_tujuan from internal_transfer_m ".
               "where kode_transaksi='".$_GET["f"]."' ");

if($qty_asal=="qty_003"){
$qty_asal1= "gudang";
}elseif($qty_asal=="qty_020"){
$qty_asal1= "qty_ri";
}else{
$qty_asal1= $qty_asal;
}

if($qty_tujuan=="qty_003"){
$qty_tujuan1= "gudang";
}elseif($qty_tujuan=="qty_020"){
$qty_tujuan1= "qty_ri";
}else{
$qty_tujuan1= $qty_tujuan;
}
	$r2 = pg_query($con,"select b.item_id, a.obat,b.batch_id,b.jumlah as qty,b.jumlah||' '||d.tdesc as jumlah, c.$qty_asal1||' '||d.tdesc as stok_asal,c.$qty_tujuan1||' '||d.tdesc as stok_tujuan, 
					b.keterangan,nm_user,d.oid as dummy 
					from internal_transfer_d b, rs00015 a, rs00016a c, rs00001 d 
					where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id and c.obat_id::text=b.item_id and b.oid='".$_GET[id_obt]."'
					and a.satuan_id=d.tc AND d.tt='SAT' ");
	$d2 = pg_fetch_object($r2);
    pg_free_result($r2);
	

			   
$f = new Form("actions/internal_transfer.insert.php", "POST","NAME=Form1");
$f->PgConn = $con;
$f->hidden("action",$_GET[action]);
$f->hidden("f",$_GET[f]);
$f->hidden("e",$_GET[e]);
$f->hidden("g",$_GET[g]);
$f->hidden("qty_asal",$qty_asal1);
$f->hidden("qty_tujuan",$qty_tujuan1);
$f->hidden("id_obt",$_GET[id_obt]);
$f->hidden("id",$d2->item_id);
$f->hidden("qty",$d2->qty);
$f->subtitle("<font color='red'>Apakah yakin mau di Verikasi?</font>");
$f->submitAndCancel("Simpan","","Batal","index2.php?p=$PID","");
$f->execute();

}else if($_GET["action"] == "edit") {
echo "<table>";
echo "<tr>";
echo "<td>".title("<img src='icon/apotik-2.gif' align='absmiddle' >  EDIT JUMLAH OBAT BARANG RUANGAN ")."</td>";
echo "<td>".title_print("")."</td>";
echo "</tr>";
echo "</table>";

    
    $tanggal = getFromTable(
               "select to_char(tanggal_trans,'DD Mon YYYY') from internal_transfer_m ".
               "where kode_transaksi='".$_GET["f"]."' ");

	$qty_asal = getFromTable(
               "select 'qty_'||poli_asal from internal_transfer_m ".
               "where kode_transaksi='".$_GET["f"]."' ");
	$qty_tujuan = getFromTable(
               "select 'qty_'||poli_tujuan from internal_transfer_m ".
               "where kode_transaksi='".$_GET["f"]."' ");
	
    $f = new Form("");

echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NO. TRANSAKSI </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["f"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> ASAL </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["e"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> TUJUAN </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["g"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> TANGGAL TRANSAKSI</td>";
		echo "<td bgcolor='B0C4DE'><b>: $tanggal </td>";
	echo "</tr>";
	
echo "</table><br><br>";

if($qty_asal=="qty_003"){
$qty_asal1= "gudang";
}elseif($qty_asal=="qty_020"){
$qty_asal1= "qty_ri";
}else{
$qty_asal1= $qty_asal;
}

if($qty_tujuan=="qty_003"){
$qty_tujuan1= "gudang";
}elseif($qty_tujuan=="qty_020"){
$qty_tujuan1= "qty_ri";
}else{
$qty_tujuan1= $qty_tujuan;
}



$r2 = pg_query($con,"select b.item_id, a.obat,b.batch_id,b.jumlah as qty,b.jumlah||' '||d.tdesc as jumlah, c.$qty_asal1||' '||d.tdesc as stok_asal,c.$qty_tujuan1||' '||d.tdesc as stok_tujuan, 
					b.keterangan,nm_user,d.oid as dummy 
					from internal_transfer_d b, rs00015 a, rs00016a c, rs00001 d 
					where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id and c.obat_id::text=b.item_id and b.oid='".$_GET[id_obt]."'
					and a.satuan_id=d.tc AND d.tt='SAT' ");
$d2 = pg_fetch_object($r2);
    pg_free_result($r2);

			
$f = new Form("actions/internal_transfer.insert.php", "POST","NAME=Form1");
$f->PgConn = $con;
$f->hidden("action",$_GET[action]);
$f->hidden("f",$_GET[f]);
$f->hidden("e",$_GET[e]);
$f->hidden("g",$_GET[g]);
$f->hidden("qty_asal",$qty_asal1);
$f->hidden("qty_tujuan",$qty_tujuan1);
$f->hidden("id_obt",$_GET[id_obt]);
$f->hidden("id",$d2->item_id);
$f->text("item_id","Obat ID",20,20,$d2->item_id,"readonly");
$f->text("obat","Nama Obat",50,50,$d2->obat,"readonly");
$f->text("qty_asal1","Stok Obat $_GET[e] ",20,20,$d2->stok_asal,"readonly");
$f->text("qty_ruang","Stok Obat $_GET[g]",20,20,$d2->stok_tujuan,"readonly");
$f->text("minta","Jumlah Permintaan",20,20,$d2->jumlah,"readonly");
$f->text("qty","Jumlah diberikan",20,20,$d2->qty,"");
$f->submit("Simpan");
$f->execute();

}else {
title("<img src='icon/apotik-2.gif' align='absmiddle' >  TRANSAKSI BARANG RUANGAN");
echo "<br>"; 

	//================ Tambah Tanggal, Agung Sunandar 13:20 07/08/2012
	
	$wkthariini = date("H:i:s", time());
	
	//echo $wkthariini;
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
 	if (!$GLOBALS['print']){
	    if (!isset($_GET['tanggal1D'])) {
			$tanggal1D = date("d", time());
			$tanggal1M = date("m", time());
			$tanggal1Y = date("Y", time());
			$tanggal2D = date("d", time());
			$tanggal2M = date("m", time());
			$tanggal2Y = date("Y", time());
	
			$ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
			$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
			$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
			$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
			
	    } else {
		    
	    $tgl_sakjane = $_GET[tanggal2D] + 1;
		
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
		
	    }
		$f->selectArray("status","Status",Array(""=>"Semua Status","0"=>"Belum di Konfirmasi","1"=>"Sudah di Konformasi"),$_GET[status],"");
    	$f->submit ("TAMPILKAN");
    	$f->execute();
	} else { 
		if (!isset($_GET['tanggal1D'])) {
			$tanggal1D = date("d", time());
			$tanggal1M = date("m", time());
			$tanggal1Y = date("Y", time());
			$tanggal2D = date("d", time());
			$tanggal2M = date("m", time());
			$tanggal2Y = date("Y", time());
	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
			
	    } else {
		    
	    $tgl_sakjane = $_GET[tanggal2D] + 1;	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
		
	    }
	    $f->selectArray("status","Status",Array(""=>"Semua Status","0"=>"Belum di Konfirmasi","1"=>"Sudah di Konformasi"),$_GET[status],"disabled");  
    	$f->execute();
	}
	title_print("");
	title_excel("$PID&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y]."&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y]."&status=".$_GET[status]."&tblstart=".$_GET[tblstart]."");
    echo "<br>";
	//======================== Akhir tanggal
	$f = new Form($SC, "GET", "NAME=Form2");
	$f->hidden("p",$PID);
    $f->PgConn = $con;
	
    $t = new PgTable($con, "100%");
   	$t->SQL = "select tanggal(a.tanggal_trans,0),c.tdesc as poli_asal, b.tdesc,case when a.status='0' then 'Belum di Konfirmasi' else 'Sudah di Konfirmasi' end, a.kode_transaksi 
			   From internal_transfer_m a, rs00001 b, rs00001 c 
			   where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and c.tt='GDP' and b.tc=a.poli_tujuan and c.tc=a.poli_asal and b.tt='GDP' 
			   and a.status like '%".$_GET[status]."%' $cond
			   Group by a.tanggal_trans,c.tdesc ,b.tdesc,a.kode_transaksi,a.status "; 

    if (!isset($_GET[sort])){
           $_GET[sort] = "a.tanggal_trans";
           $_GET[order] = "asc";
	}

    
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[4] = "CENTER";
	
	if (!$GLOBALS['print']){
		$t->ColHeader = array("TANGGAL","RUANGAN ASAL","RUANGAN TUJUAN","STATUS","VIEW");
		$t->RowsPerPage = 20;
		$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=view&f=<#4#>&e=<#1#>&g=<#2#>'>".icon("view","View")."</A>";
		}else{
			$t->ColHeader = array("TANGGAL","RUANGAN ASAL","RUANGAN TUJUAN","STATUS");
			$t->RowsPerPage = 99999;
			$t->DisableNavButton = true;
			$t->DisableScrollBar = true;
			}
			
    $t->execute();

}

?>
