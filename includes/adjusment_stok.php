<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 09-05-2004

$PID = "adjusment_stok";
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
    
    
    if (strlen($_GET["ob4_id"]) > 0 && $_GET["jumlah_obat"] >= 0) {
        
				
		if (is_array($_SESSION["ob4"]["obat"])) {
			$cnt = count($_SESSION["ob4"]["obat"]);
		} else {
			$cnt = 0;
		}
		$r1 = pg_query($con, "select * from rsv0004 where id = '".$_GET["ob4_id"]."'");
		$d1 = pg_fetch_object($r1);
		pg_free_result($r1);
		
		$r3 = pg_query($con, "select a.kode_trans,  b.tdesc as satuan1, a.jumlah2,a.jumlah1, c.tdesc as satuan2 
			from rs00016d a, rs00001 b, rs00001 c 
			where a.satuan1=b.tc and b.tt='SAT' and a.satuan2=c.tc and c.tt='SAT' and a.kode_trans='".$_GET[kode_kon]."'");
        $d3 = pg_fetch_object($r3);
        pg_free_result($r3);
		
		$_SESSION["ob4"]["obat"][$cnt]["id"]     = $d1->id;
		$_SESSION["ob4"]["obat"][$cnt]["batch"]  = $d1->batch;
		$_SESSION["ob4"]["obat"][$cnt]["obat"]   = $d1->obat;
		$_SESSION["ob4"]["obat"][$cnt]["satuan"] = $d1->satuan;
		$_SESSION["ob4"]["obat"][$cnt]["harga_beli"] = $d1->harga_beli ;
		/*$_SESSION["ob4"]["obat"][$cnt]["harga_jual"] = $d1->harga +($d1->harga*0.1) ;
		$_SESSION["ob4"]["obat"][$cnt]["total_harga"] = ($d1->harga_beli +($d1->harga_beli*0.1)) * $_GET["jumlah_obat"] ;
		$_SESSION["ob4"]["obat"][$cnt]["total_harga_jual"] = ($d1->harga +($d1->harga*0.1)) * $_GET["jumlah_obat"] ;
		*/
		
		$_SESSION["ob4"]["obat"][$cnt]["jml_minta"] = $_GET["jumlah_obat"];		
		$_SESSION["ob4"]["obat"][$cnt]["kode_trans"] = $_GET["kode_trans"];

	
		$_SESSION["ob4"]["obat"][$cnt]["jml_depo"] = $_GET["jml_depo"];
		$_SESSION["ob4"]["obat"][$cnt]["ket_kon"] = 1 ." ". $_GET["sat_kirim"] ." = ". $_GET["jml_isi"] ." ". $_GET["sat_jual"];
		
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
        $_SESSION["ob4"]["obat"][$_GET["editrow"]]["jml_minta"] = $_GET["editjumlah_pakai"];
        

    }
    header("Location: $SC?p=$PID&action=new&poli_asal=".$_GET["poli_asal"]."&poli_tujuan=".$_GET["poli_tujuan"]."");
    exit;
}


if ($_GET["action"] == "new") { 
title("<img src='icon/apotik-2.gif' align='absmiddle' >  STOCK ADJUSMENT");
echo "<br>"; 
echo "<form action=$SC>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
$ext = "onchange='javascript:Form1.submit()'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	
	/* $f->selectSQL("poli_asal", "Dari ",
					//	"select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc in ('003') order by tdesc "
						,$_GET["poli_asal"],$ext);
		*/				
	 $f->hidden("poli_asal","003");

     $f->selectSQL("poli_tujuan", "DEPO ",
						"select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc not in ('000','005','008','009') order by tdesc "
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
if ($_GET["poli_tujuan"]) {

    if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }
	
	
	
		if ($_GET["poli_tujuan"]=="003") {
           $q=  getFromTable("select gudang from rs00016a where obat_id=$d1->id"); 
		   }elseif($_GET["poli_tujuan"]=="020") {
           $q=  getFromTable("select qty_ri from rs00016a where obat_id=$d1->id"); 
		   }else{
		   $q=  getFromTable("select qty_".$_GET["poli_tujuan"]." from rs00016a where obat_id=$d1->id"); 
		   }
		   
		   $x_jumlah = " <SELECT name='jumlah_obat'>\n";
        
        "</SELECT>\n";
		$g_depo = getFromTable("select tdesc from rs00001 where tc='$_GET[poli_tujuan]' and tt='GDP'");
    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("NO","BATCH ID","KODE", "NAMA OBAT","SATUAN","STOK $g_depo","STOK REAL",""));
    if (is_array($_SESSION["ob4"]["obat"])) {
        $total = 0.00;
		$i=1;
        foreach($_SESSION["ob4"]["obat"] as $k => $o ) {
            if ($k == $_GET["edit"] && strlen($_GET["edit"]) > 0) {
                echo "<form action=$SC onSubmit='return validasi2()' name=formx>";
                echo "<input type=hidden name=p value=$PID>";
                echo "<input type=hidden name=editrow value=$k>";
                echo "<input type=hidden name=httpHeader value=1>";
                echo "<input type=hidden name=poli_tujuan value=".$_GET[poli_tujuan].">";
				echo "<input type=hidden name=poli_asal value=".$_GET[poli_asal].">";
                
                $t->printRow(
                    Array($i, str_pad($o["batch"],6,"0",STR_PAD_LEFT),
						$o["id"],
                        $o["obat"],
                        $o["satuan"],
                        $o["harga_beli"],
                        $o["harga_jual"],
						"",
                        $x_jumlah,
                        "",
                        "",
                        //"<input type=text size=10 maxlength=15 name=edittglPakai value='".$o["tglPakai"]."' style='text-align:right'>",
                        "<input type=submit value='Update'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}\"'>" ),
                    Array( "CENTER","CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "CENTER")
                    );
                echo "</form>";
            } else {
                $t->printRow(
                    Array($i, str_pad($o["batch"],6,"0",STR_PAD_LEFT),
                        $o["id"],
						$o["obat"],
                        $o["satuan"],
					                          
						$o["jml_depo"]." ".$o["satuan"],                        
                        $o["jml_minta"]." ".$o["satuan"],
                       
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}'>".icon("del-left")."</a>"),
                        //" &nbsp; " ,
                        //"<a href='$SC?p=$PID&edit=$k&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}'>".icon("edit")."</a>" ),
                    Array( "CENTER","CENTER",
                        "LEFT",
                        "left",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "LEFT",
                        "LEFT",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "CENTER",
						"LEFT",
                        "CENTER")
                    );
            }
            $total += $o["total"];
			$i++;
        }
    }

    if (strlen($_GET["edit"]) == "0") {
        
		
			if ($_GET["poli_tujuan"]=="003") {
           $q1=  getFromTable("select gudang from rs00016a where obat_id=$d1->id"); 
		   }elseif($_GET["poli_tujuan"]=="020") {
           $q1=  getFromTable("select qty_ri from rs00016a where obat_id=$d1->id"); 
		   }else{
		   $q1=  getFromTable("select qty_".$_GET["poli_tujuan"]." from rs00016a where obat_id=".$_SESSION["SELECT_OBAT"].""); 
		   }
		   @$q = $q1 / $d2->jumlah1;
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
		
		echo "<form action=$SC name=formx>";
        echo "<input type=hidden name=p value=$PID>";
        echo "<input type=hidden name=httpHeader value=1>";
        echo "<input type=hidden name=poli_asal value=".$_GET[poli_asal].">";
		echo "<input type=hidden name=poli_tujuan value=".$_GET[poli_tujuan].">";
		


		echo "<input type=hidden name=sat_jual value=".$d2->satuan1.">";
		echo "<input type=hidden name=jml_isi value=".$jml_minta.">";
		
		echo "<input type=hidden name=jml_depo value=".$q1.">";
		
		if ($_SESSION["SELECT_OBAT"]) {
        $r1 = pg_query($con, "select * from rsv0004 where id = '".$_SESSION["SELECT_OBAT"]."'");
        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    }
		

		
        $t->printRow(
            Array( 
            	"","<input type=text size=20 maxlength=20 name=ob4_id1 style='text-align:center' value=$d1->batch>"."&nbsp;<a href='javascript:selectObat()'><input type=hidden size=20 name=ob4_id value=$d1->id>".icon("view")."</a>",
				$d1->id,
				$d1->obat,
                $d1->satuan,	
                
				$q1." ".$d2->satuan1,
				"<input type=text size=5 maxlength=10 name=jumlah_obat value='0' style='text-align:right'> ".$d2->satuan1,
                //"<input type=text size=7 maxlength=10 name=ob4_tglPakai value='".$tgl_sekarang."' style='text-align:right'>",
                "<input type=submit value=OK $ext>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "CENTER")
            );
        echo "</FORM>";
     
    }
    $t->printTableClose();



    if (is_array($_SESSION["ob4"]["obat"])) {
        echo "<br>";
        echo "<div align=right>";
        echo "<form action='actions/adjusment_stok.insert.php' method=POST name=Form10>";
		if($_GET["poli_tujuan"]=='020'){
			$qty_gd = 'qty_ri';
		}else if($_GET["poli_tujuan"]=='003'){
			$qty_gd = 'gudang';
		}else{
			$qty_gd = 'qty_'.$_GET["poli_tujuan"];
		}
        echo "<input type=hidden name=poli_tujuan value=".$_GET[poli_tujuan].">";
		echo "<input type=hidden name=poli_asal value=".$_GET[poli_asal].">";
		echo "<input type=hidden name=stok_poli value='$qty_gd'>";
        echo "<input type=submit value=' &nbsp; Simpan &nbsp; '>";
        echo "</form>";
        echo "</div>";
    }

    echo "\n<script language='JavaScript'>\n";
    echo "function selectObat() {\n";

    echo "    sWin = window.open('popup/obat_adjusment.php?poli_tujuan=".$_GET[poli_tujuan]."', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n"; 
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
}
} else if($_GET["action"] == "view" && substr($_GET["stat"],0,5)=="Sudah") {
echo "<table>";
echo "<tr>";
if(!$GLOBALS['print']){
$bgcolor='B0C4DE';
$border='BORDER=0';
echo "<td>".title("<img src='icon/apotik-2.gif' align='absmiddle' >  RINCIAN OBAT STOCK ADJUSMENT ")."</td>";
}else{
$border='BORDER=1';
$bgcolor='FFFFFF';
echo "<td>".title("RINCIAN OBAT STOCK ADJUSMENT")."</td>";
}
echo "<td>".title_print("")."</td>";
echo "</tr>";
echo "</table>";

    
    $tanggal = getFromTable(
               "select to_char(tanggal_trans,'DD Mon YYYY') from stok_adjusment ".
               "where kode_transaksi='".$_GET["f"]."' ");


    $f = new Form("");

echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b> NO. TRANSAKSI </td>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b>: ".$_GET["f"]." </td>";
	echo "</tr>";
	/*echo "<tr>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b> DARI </td>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b>: ".$_GET["e"]." </td>";
	echo "</tr>";*/
	echo "<tr>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b> DEPO </td>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b>: ".$_GET["g"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b> TANGGAL ADJUSMENT</td>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b>: $tanggal </td>";
	echo "</tr>";
	
echo "</table>";

    $f->execute();
    
    if (!$GLOBALS['print']){
    	//echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    } else {
    	"";
    }

    echo "<br>";
    $t = new PgTable($con, "100%",$border);   
	
	  $r2 = pg_query($con, "select sum(b.stok_asal) as stok_asal ,sum(b.selisih_stok) as selisih,sum(b.stok_real) as stok_real,sum(hna) as hna,sum(b.stok_real*hna) as jml_saldo from stok_adjusment c,stok_adjusment_item b, rs00015 a where b.kode_transaksi='".$_GET["f"]."' and c.kode_transaksi=b.kode_transaksi and a.id::text=b.item_id");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
	
	if(!$GLOBALS['print']){
   	$t->SQL = "select to_char(waktu_ver,'DD Mon YYYY'),a.obat,b.batch_id,b.stok_asal,b.selisih_stok,b.stok_real,hna,(b.stok_real*hna) as total,c.nm_user as user from stok_adjusment c,stok_adjusment_item b, rs00015 a where b.kode_transaksi='".$_GET["f"]."' and c.kode_transaksi=b.kode_transaksi and a.id::text=b.item_id";
   	$t->ColHeader = array("TANGGAL","NAMA OBAT", "BATCH ID","STOK ASAL","SELISIH","STOK REAL","HNA","TOTAL","USER ENTRY");
	}else{
	$t->SQL = "select to_char(waktu_ver,'DD Mon YYYY'),a.obat,b.batch_id,b.stok_asal,b.selisih_stok,b.stok_real,hna,(b.stok_real*hna) as total,c.nm_user as user from stok_adjusment c,stok_adjusment_item b, rs00015 a where b.kode_transaksi='".$_GET["f"]."' and c.kode_transaksi=b.kode_transaksi and a.id::text=b.item_id";
    $t->ColHeader = array("TANGGAL VERIFIKASI","NAMA OBAT", "BATCH ID","STOK ASAL","SELISIH","STOK REAL","HNA","TOTAL","USER ENTRY");
	}
	$t->ColAlign = array("LEFT","CENTER","CENTER","RIGHT","RIGHT","CENTER");
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
	
	$t->ColFooter[2] =  "TOTAL ";
	$t->ColFooter[3] =  number_format($d2->stok_asal,0,',','.');
	$t->ColFooter[4] =  number_format($d2->selisih,0,',','.');
	$t->ColFooter[5] =  number_format($d2->stok_real,0,',','.');
	//$t->ColFooter[6] =  number_format($d2->hna,0,',','.');
	$t->ColFooter[7] =  number_format($d2->jml_saldo,2,',','.');
	//if (!$GLOBALS['print']){
			$t->RowsPerPage = 20;
			$t->DisableNavButton = false;
			$t->DisableStatusBar = false;
			$t->DisableScrollBar = false;
	//		}
	if($GLOBALS['print']){
			$t->DisableStatusBar = true;
			$t->DisableNavButton = true;
			$t->DisableScrollBar = true;
			
	}
   // $t->RowsPerPage = $ROWS_PER_PAGE;
	//$t->ColFooter [5]=  number_format($d2->jml_tagihan,2,',','.');
    $t->execute();
} else if($_GET["action"] == "view") {
echo "<table>";
echo "<tr>";
echo "<td>".title("<img src='icon/apotik-2.gif' align='absmiddle' >  RINCIAN OBAT STOCK ADJUSMENT ")."</td>";
echo "<td>".title_print("")."</td>";
echo "</tr>";
echo "</table>";
	
	$cek_status_ver=getFromTable("select status from stok_adjusment where kode_transaksi=".$_GET["f"]." ");
    
    $tanggal = getFromTable(
               "select to_char(tanggal_trans,'DD Mon YYYY') from stok_adjusment ".
               "where kode_transaksi='".$_GET["f"]."' ");

/*	$qty_asal = getFromTable(
               "select 'qty_'||poli_asal from stok_adjusment ".
               "where kode_transaksi='".$_GET["f"]."' "); */
	$qty_tujuan = getFromTable(
               "select 'qty_'||stok_poli from stok_adjusment ".
               "where kode_transaksi='".$_GET["f"]."' ");
	
/*if($qty_asal=="qty_003"){
$qty_asal1= "gudang";
}elseif($qty_asal=="qty_020"){
$qty_asal1= "qty_ri";
}else{
$qty_asal1= $qty_asal;
} */
//echo $qty_asal1;

    $f = new Form("");

echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NO. TRANSAKSI </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["f"]." </td>";
	echo "</tr>";
	/*echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> DARI </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["e"]." </td>";
	echo "</tr>";*/
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> DEPO </td>";
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
		$border='border=1';
    }

    echo "<br>";

    $t = new PgTable($con, "100%",$border); 
	if($cek_status_ver == 0){
		if($_GET['act']=='ver' && !$GLOBALS['print']){
		$field_add = ', verifikator , b.oid as dummy';
		}else if($_GET['act']=='ver' && $GLOBALS['print']){
		$field_add = ', verifikator ';
		}else{
		$field_add ='';
		}
   	$t->SQL = "select a.obat,b.batch_id,b.stok_real||' '||d.tdesc as jumlah, b.stok_asal ||' '||d.tdesc as stok_ruang,case when b.status='0' then 'Belum di Konfirm' else 'Sudah di Konfirm' end ,selisih_stok, hna ,total $field_add 
	from stok_adjusment_item b, rs00015 a, rs00016a c, rs00001 d 
	where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id and c.obat_id::text=b.item_id and a.satuan_id=d.tc AND d.tt='SAT' ";
	$t->ColHeader = array("NAMA OBAT", "BATCH ID","STOK REAL","STOK ASAL","STATUS","SELISIH","HNA","TOTAL","VERIFIKATOR","");
	$t->ColAlign = array("LEFT","LEFT","CENTER","CENTER","LEFT","LEFT","CENTER","CENTER","CENTER");
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 9999;
	if($_GET['act']=='ver' && !$GLOBALS['print']){
	$t->ColFormatHtml[9] = //"<A CLASS=TBL_HREF HREF='$SC?p=$PID&action=edit&f=".$_GET[f]."&e=".$_GET[e]."&g=".$_GET[g]."&id_obt=<#6#>'>".icon("edit","Edit")."</A>
						"&nbsp;<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=$_GET[act]&action=verifikasi&f=".$_GET[f]."&e=".$_GET[e]."&g=".$_GET[g]."&id_obt=<#9#>'>".icon("ok","Verifikasi")."</A>";
	}
	}else{
	$t->SQL = "select a.obat,b.batch_id,b.stok_real||' '||d.tdesc as jumlah,  
	nm_user,case when b.status='0' then 'Belum di Konfirm' else 'Sudah di Konfirm' end ,verifikator , selisih_stok, hna ,total
	from stok_adjusment_item b, rs00015 a, rs00016a c, rs00001 d 
	where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id and c.obat_id::text=b.item_id and a.satuan_id=d.tc AND d.tt='SAT' ";
    $t->ColHeader = array("NAMA OBAT", "BATCH ID","QTY KIRIM","PEMINTA","STATUS","VERIFIKATOR","SELISIH","HNA","TOTAL");
	$t->ColAlign = array("LEFT","LEFT","CENTER","CENTER","LEFT","LEFT","CENTER","CENTER","CENTER");
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 9999;
	}
	if($GLOBALS['print']){
			$t->DisableNavButton = true;
			$t->DisableStatusBar = true;
			$t->DisableScrollBar = true;
	}
    $t->execute();
}else if($_GET["action"] == "verifikasi") {
	$qty_tujuan = getFromTable(
               "select 'qty_'||stok_poli from stok_adjusment ".
               "where kode_transaksi='".$_GET["f"]."' ");
	/*$qty_asal = getFromTable(
               "select 'qty_'||poli_asal from stok_adjusment ".
               "where kode_transaksi='".$_GET["f"]."' ");
if($qty_asal=="qty_003"){
$qty_asal1= "gudang";
}elseif($qty_asal=="qty_020"){
$qty_asal1= "qty_ri";
}else{
$qty_asal1= $qty_asal;
} */

if($qty_tujuan=="qty_003"){
$qty_tujuan1= "gudang";
}elseif($qty_tujuan=="qty_020"){
$qty_tujuan1= "qty_ri";
}else{
$qty_tujuan1= $qty_tujuan;
}



	$r2 = pg_query($con,"select b.item_id, a.obat,b.batch_id,b.stok_real as qty,b.stok_real||' '||d.tdesc as jumlah, b.stok_asal||' '||d.tdesc as stok_asal,c.$qty_tujuan1||' '||d.tdesc as stok_tujuan, 
					b.keterangan,nm_user,d.oid as dummy 
					from stok_adjusment_item b, rs00015 a, rs00016a c, rs00001 d 
					where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id and c.obat_id::text=b.item_id and b.oid='".$_GET[id_obt]."'
					and a.satuan_id=d.tc AND d.tt='SAT' ");
	$d2 = pg_fetch_object($r2);
    pg_free_result($r2);
	

			   
$f = new Form("actions/adjusment_stok_item.insert.php", "POST","NAME=Form1");
$f->PgConn = $con;
$f->hidden("action",$_GET[action]);
$f->hidden("f",$_GET[f]);
$f->hidden("e",$_GET[e]);
$f->hidden("g",$_GET[g]);
$f->hidden("act",$_GET[act]);
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
echo "<td>".title("<img src='icon/apotik-2.gif' align='absmiddle' >  EDIT JUMLAH OBAT STOCK ADJUSMENT ")."</td>";
echo "<td>".title_print("")."</td>";
echo "</tr>";
echo "</table>";

    
    $tanggal = getFromTable(
               "select to_char(tanggal_trans,'DD Mon YYYY') from stok_adjusment ".
               "where kode_transaksi='".$_GET["f"]."' ");

	$qty_tujuan = getFromTable(
               "select 'qty_'||poli_tujuan from stok_adjusment ".
               "where kode_transaksi='".$_GET["f"]."' ");
	$qty_asal = getFromTable(
               "select 'qty_'||poli_asal from stok_adjusment ".
               "where kode_transaksi='".$_GET["f"]."' ");
	
    $f = new Form("");

echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NO. TRANSAKSI </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["f"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> DARI </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["e"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> KE </td>";
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
					from stok_adjusment_item b, rs00015 a, rs00016a c, rs00001 d 
					where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id and c.obat_id::text=b.item_id and b.oid='".$_GET[id_obt]."'
					and a.satuan_id=d.tc AND d.tt='SAT' ");
$d2 = pg_fetch_object($r2);
    pg_free_result($r2);

			
$f = new Form("actions/adjusment_stok_item.insert.php", "POST","NAME=Form1");
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
if(!$GLOBALS['print']){
title("<img src='icon/apotik-2.gif' align='absmiddle' > STOCK ADJUSMENT");
}else{
title("STOCK ADJUSMENT");
}
echo "<br>"; 

	//================ Tambah Tanggal, AGung SUnandar 13:20 07/08/2012
	
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
		//$f->selectArray("status","Status",Array(""=>"Semua Status","0"=>"Belum di Konfirmasi","1"=>"Sudah di Konformasi"),$_GET[status],"");
    	$f->hidden("act",$_GET['act']);
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
	    $ts_check_in1 = date("d M Y", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("d M Y", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	 //   $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
	  //  $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
		if($_GET["status"]==""){
			$status="Semua Status";
		}else if($_GET["status"]=="0"){
			$status="Belum di Konfirmasi";
		}else{
			$status="Sudah di Konformasi";
		}
		echo "Tanggal : ".$ts_check_in1;
		echo " s/d : ".$ts_check_in2."<br/>";	
		echo " Status : ".$status;
		$border='border=1';
	    }
	  //  $f->selectArray("status","Status",Array(""=>"Semua Status","0"=>"Belum di Konfirmasi","1"=>"Sudah di Konformasi"),$_GET[status],"disabled");   
    	$f->execute();
	}
	title_print("");
	title_excel("$PID&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y]."&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y]."&status=".$_GET[status]."&tblstart=".$_GET[tblstart]."");
    echo "<br>";
	//======================== Akhir tanggal
	echo "<div class='wrapper'>";
	$f = new Form($SC, "GET", "NAME=Form2");
	$f->hidden("p",$PID);
    $f->PgConn = $con;
	
	$ext = "onchange='javascript:Form2.submit()'";        
    $t = new PgTable($con, "100%",$border);
		if (!$GLOBALS['print']){

   	$t->SQL = "select a.kode_transaksi,tanggal(a.tanggal_trans,0), b.tdesc,case when a.status='0' then 'Belum di Konfirmasi' else 'Sudah di Konfirmasi' end, a.kode_transaksi
			   From stok_adjusment a, rs00001 b
			   where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and b.tc=a.stok_poli and b.tt='GDP' and a.status like '%$_GET[status]%'
			   Group by a.tanggal_trans ,b.tdesc,a.kode_transaksi,a.status ";
}else{
	$t->SQL = "select a.kode_transaksi,tanggal(a.tanggal_trans,0), b.tdesc ,case when a.status='0' then 'Belum di Konfirmasi' else 'Sudah di Konfirmasi' end
			   From stok_adjusment a, rs00001 b
			   where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and b.tc=a.stok_poli and b.tt='GDP' and a.status like '%$_GET[status]%'
			   Group by a.tanggal_trans ,b.tdesc,a.kode_transaksi,a.status "; 
}
        if (!isset($_GET[sort])) {
           $_GET[sort] = "tanggal_trans";
           $_GET[order] = "asc";
	}

    
	$t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[5] = "CENTER";
	
	if (!$GLOBALS['print']){
		$t->ColHeader = array("KODE TRANSAKSI","TANGGAL","DEPO","STATUS","VIEW");
		$t->RowsPerPage = 20;
		$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=$_GET[act]&action=view&f=<#4#>&e=<#1#>&g=<#2#>&stat=<#3#>'>".icon("view","View")."</A>";
		$t->DisableNavButton = false;
		$t->DisableStatusBar = false;
		$t->DisableScrollBar = false;
		}else{
			$t->ColHeader = array("KODE TRANSAKSI","TANGGAL","DEPO","STATUS");
			$t->RowsPerPage = 99999;
			$t->DisableNavButton = true;
			$t->DisableStatusBar = true;
			$t->DisableScrollBar = true;
			}
			
    $t->execute();
echo "</div>";	
    echo "<br>";
	if (!$GLOBALS['print']){
	   echo "<div align=right>";
	   if($_GET['act']!='ver'){
	   echo "<img src='icon/apotik.gif' align='absmiddle'> <A HREF='$SC?p=$PID&action=new'>Tambah Adjusment</A>";
	   }
	   echo "</div>";	
	}
}

if($GLOBALS['print']){
echo " <div style='float:right;margin-right:40px;margin-top:100px;'>( $_SESSION[nama_usr] )</div>";
}
?>
