<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 09-05-2004

$PID = "retur_gudang_minta";
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
		
		$r3 = pg_query($con, "select a.kode_trans,  b.tdesc as satuan1, a.jumlah2,a.jumlah1, c.tdesc as satuan2 
			from rs00016d a, rs00001 b, rs00001 c 
			where a.satuan1=b.tc and b.tt='SAT' and a.satuan2=c.tc and c.tt='SAT' and a.kode_trans='".$_GET[kode_kon]."'");
        $d3 = pg_fetch_object($r3);
        pg_free_result($r3);
		
		$_SESSION["ob4"]["obat"][$cnt]["id"]     = $d1->id;
		$_SESSION["ob4"]["obat"][$cnt]["batch"]  = $d1->batch;
		$_SESSION["ob4"]["obat"][$cnt]["obat"]   = $d1->obat;
		$_SESSION["ob4"]["obat"][$cnt]["satuan"] = $d1->satuan;
		
		
		$_SESSION["ob4"]["obat"][$cnt]["jml_minta"] = $_GET["jumlah_obat"];		
		$_SESSION["ob4"]["obat"][$cnt]["kode_trans"] = $_GET["kode_trans"];
		$_SESSION["ob4"]["obat"][$cnt]["ket"] = $_GET["ket"];

	
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
title("<img src='icon/apotik-2.gif' align='absmiddle' >  RETURN GUDANG");
echo "<br>"; 
echo "<form action=$SC>";
echo "<input type=hidden name=p value=$PID>";
echo "<input type=hidden name=httpHeader value=1>";
$ext = "onchange='javascript:Form1.submit()'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	
	
     $f->selectSQL("poli_asal", "DARI ",
						"select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc not in ('000','003','005','008','009') order by tdesc "
						,$_GET["poli_asal"],$ext);
    /*$f->selectSQL("poli_tujuan", "KE",
						//"select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc  in ('003') order by tdesc "
						,$_GET["poli_tujuan"],$ext); */
	$f->hidden("poli_tujuan","003");
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
$depo=$_GET["poli_asal"];
if (isset($depo)==$_GET["poli_asal"]) {

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
        
        "</SELECT>\n";
		$nama_depo = getFromTable("select tdesc from rs00001 where tt='GDP' and tc='".$_GET["poli_asal"]."'");
    $t = new BaseTable("100%");
    $t->printTableOpen();
    $t->printTableHeader(Array("NO","BATCH ID","KODE", "NAMA OBAT","SATUAN JUAL","JUMLAH $nama_depo","JUMLAH<br>RETURN","KETERANGAN",""));
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
						"",
                        $x_jumlah,
                        
                        //"<input type=text size=10 maxlength=15 name=edittglPakai value='".$o["tglPakai"]."' style='text-align:right'>",
                        "<input type=submit value='Update'>".
                        " &nbsp; " .
                        "<input type=button value='Batal' onClick='window.location=\"$SC?p=$PID&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}\"'>" ),
                    Array( "CENTER","CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "CENTER",
                        "LEFT",
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
                        $o["ket"],
                      
                        "<a href='$SC?p=$PID&httpHeader=1&del=$k&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}'>".icon("del-left")."</a>"),
                        //" &nbsp; " ,
                        //"<a href='$SC?p=$PID&edit=$k&action=new&poli_asal={$_GET["poli_asal"]}&poli_tujuan={$_GET["poli_tujuan"]}'>".icon("edit")."</a>" ),
                    Array( "CENTER","CENTER",
                        "LEFT",
                        "left",
                        "CENTER",
                        "CENTER",
                        "LEFT",
                        "CENTER",
                        "LEFT",
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
        
		
			if ($_GET["poli_asal"]=="003") {
           $q1=  getFromTable("select gudang from rs00016a where obat_id=$d1->id"); 
		   }elseif($_GET["poli_asal"]=="020") {
           $q1=  getFromTable("select qty_ri from rs00016a where obat_id=$d1->id"); 
		   }else{
		   $q1=  getFromTable("select qty_".$_GET["poli_asal"]." from rs00016a where obat_id=".$_SESSION["SELECT_OBAT"].""); 
		   }
		  // echo $q1;
		   @$q = $q1;// / $d2->jumlah1;
		   $x_jumlah = " <SELECT name='jumlah_obat'>\n";
          for ($i=1; $i<=$q; $i++){
		  
                 if ($i==1) {
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
               
				//"<input type=text size=5 maxlength=10 name=jumlah_obat value='0' style='text-align:right'> ".$d2->satuan1,
				$x_jumlah,
				"<input type=text name=ket> ",
                
                //"<input type=text size=7 maxlength=10 name=ob4_tglPakai value='".$tgl_sekarang."' style='text-align:right'>",
                "<input type=submit value=OK $ext>" ),
                    Array( "CENTER",
                        "LEFT",
                        "CENTER",
                        "CENTER",
                        "CENTER",
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
        echo "<form action='actions/retur_gudang_minta.insert.php' method=POST name=Form10>";
        echo "<input type=hidden name=poli_tujuan value=".$_GET[poli_tujuan].">";
		echo "<input type=hidden name=poli_asal value=".$_GET[poli_asal].">";
        echo "<input type=submit value=' &nbsp; Simpan &nbsp; '>";
        echo "</form>";
        echo "</div>";
    }

    echo "\n<script language='JavaScript'>\n";
    echo "function selectObat() {\n";
    echo "    sWin = window.open('popup/retur_obat.php?mOBT=002&asal=".$_GET[poli_asal]."&tujuan=".$_GET[poli_tujuan]."', 'xWin', 'width=500,height=400,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
}
} else if($_GET["action"] == "view") {
echo "<table>";
echo "<tr>";
if(!$GLOBALS['print']){
$bgcolor='B0C4DE';
$border='BORDER=0';
echo "<td>".title("<img src='icon/apotik-2.gif' align='absmiddle' >  RINCIAN OBAT PERMINTAAN INTERNAL TRANSFER BANGSAL ")."</td>";
}else{
$border='BORDER=1';
$bgcolor='FFFFFF';
echo "<td>".title("RINCIAN OBAT PERMINTAAN INTERNAL TRANSFER")."</td>";
}
echo "<td>".title_print("")."</td>";
echo "</tr>";
echo "</table>";

    
    $tanggal = getFromTable(
               "select to_char(tanggal_trans,'DD Mon YYYY') from retur_gudang_m ".
               "where kode_transaksi='".$_GET["f"]."' ");


    $f = new Form("");

echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b> NO. TRANSAKSI </td>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b>: ".$_GET["f"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b> ASAL </td>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b>: ".$_GET["e"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b> TUJUAN </td>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b>: ".$_GET["g"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='$bgcolor' style='font-size:11px'><b> TANGGAL TRANSAKSI</td>";
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
	if(!$GLOBALS['print']){
   	$t->SQL = "select a.obat,b.batch_id,b.jumlah,b.keterangan,nm_user from retur_gudang_d b, rs00015 a where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id";
   	$t->ColHeader = array("NAMA OBAT", "BATCH ID","QTY","KETERANGAN","USER ENTRY");
	}else{
	$t->SQL = "select a.obat,b.batch_id,b.jumlah,b.keterangan from retur_gudang_d b, rs00015 a where b.kode_transaksi='".$_GET["f"]."' and a.id::text=b.item_id";
    $t->ColHeader = array("NAMA OBAT", "BATCH ID","QTY","KETERANGAN");
	}
	$t->ColAlign = array("LEFT","LEFT","CENTER","LEFT","LEFT");
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
	//if (!$GLOBALS['print']){
			$t->RowsPerPage = 99999;
			$t->DisableNavButton = true;
			$t->DisableScrollBar = true;
	//		}
   // $t->RowsPerPage = $ROWS_PER_PAGE;
	//$t->ColFooter [5]=  number_format($d2->jml_tagihan,2,',','.');
    $t->execute();
}else {
title("<img src='icon/apotik-2.gif' align='absmiddle' >  RETURN GUDANG");
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
	echo "<div class='wrapper'>";
	$f = new Form($SC, "GET", "NAME=Form2");
	$f->hidden("p",$PID);
    $f->PgConn = $con;
	
	$ext = "onchange='javascript:Form2.submit()'";        
	
    $t = new PgTable($con, "100%",$border);
   	$t->SQL = "select tanggal(a.tanggal_trans,0),c.tdesc as poli_asal, b.tdesc,case when a.status='0' then 'Belum di Konfirmasi' else 'Sudah di Konfirmasi' end, a.kode_transaksi 
			   From retur_gudang_m a, rs00001 b, rs00001 c 
			   where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and c.tt='GDP' and b.tc=a.poli_tujuan and c.tc=a.poli_asal and b.tt='GDP' and a.status like '%".$_GET[status]."%'
			   Group by a.tanggal_trans,c.tdesc ,b.tdesc,a.kode_transaksi,a.status "; 

        if (!isset($_GET[sort])) {
           $_GET[sort] = "tanggal_trans";
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
echo "</div>";	
    echo "<br>";
	if (!$GLOBALS['print']){
	   echo "<div align=right>";
	   echo "<img src='icon/apotik.gif' align='absmiddle'> <A HREF='$SC?p=$PID&action=new&poli_asal=&poli_tujuan='>Tambah Retur Obat</A>";
	   echo "</div>";	
	}
}
if($GLOBALS['print']){
echo " <div style='float:right;margin-right:40px;margin-top:100px;'>( $_SESSION[nama_usr] )</div>";
}
?>
