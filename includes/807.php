<?php

// Agung Sunanda 23:21 06/07/2012 MEnambahkan stok awal pada aplikasi

$PID = "807";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if ($_GET[f]) {

    echo "<div align=right><a href='$SC?p=$PID&mOBT=" . $_GET["o"] . "'>" . icon("back",
            "Kembali") . "</a></div>";

    $r2 = pg_query($con,
            "select * " .
            "from rs00015 " .
            "where id='" . $_GET["e"] . "'");
    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $r3 = pg_query($con,
            "select harga " .
            "from rs00016 " .
            "where obat_id = '" . $_GET["e"] . "' and id = (select max(id) from rs00016 where obat_id = '" . $_GET["e"] . "')");
    $d3 = pg_fetch_object($r3);
    pg_free_result($r3);


// Untuk menampilkan data obat
    $f = new Form("actions/998.1.update.php?configtype=1", "POST");
    $f->hidden("id",
            $_GET["id"]);
    $f->text("kode_obat",
            "Kode Obat",
            10,
            10,
            $d2->id,
            "readonly");
    $f->text("nama_obat",
            "Nama Obat",
            50,
            100,
            $d2->obat,
            "readonly");
    $f->text("satuan",
            "Satuan",
            20,
            40,
            $harga,
            "readonly");

    $f->submit(" Simpan ");
    $f->execute();
} elseif (strlen($_GET["e"]) > 0) {
    echo "<div align=right><a href='" .
    "$SC?p=$PID&mOBT=" . $_GET["o"] .
    "'>" . icon("back",
            "Kembali") . "</a></div>";

    if ($_GET["e"] == "new") {
        $f = new Form("actions/807.insert.php");
        title("Data Baru");
        echo "<BR>";
        $f->text("id",
                "ID",
                12,
                12,
                "<OTOMATIS>",
                "DISABLED");
        $harga = 0;
    } else {
        $r2 = pg_query($con,
                "select * " .
                "from rs00015 " .
                "where id='" . $_GET["e"] . "'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);

        $r3 = pg_query($con,
                "select harga,harga_beli " .
                "from rs00016 " .
                "where obat_id = '" . $_GET["e"] . "' and id = (select max(id) from rs00016 where obat_id = '" . $_GET["e"] . "')");
        $d3 = pg_fetch_object($r3);
        pg_free_result($r3);

        $sql = "select a.tc, a.tdesc
			from rs00001 a
			where a.tt='GDP' and a.tc!='000'
			order by a.tc";
        @$r1 = pg_query($con,
                $sql);
        @$n1 = pg_num_rows($r1);

        $max_row = 30;
        $mulai = $HTTP_GET_VARS["rec"];
        if (!$mulai) {
            $mulai = 1;
        }


        $f = new Form("actions/807.update.php?search=" . $_GET[search] . "&sort=" . $_GET[sort] . "&order=" . $_GET[order] . "&tblstart=" . $_GET[tblstart]);
        title("Editing Data");
        echo "<BR>";

        $f->hidden("id",
                $_GET["e"]);
        $f->text("id",
                "ID",
                10,
                4,
                $_GET["e"],
                "DISABLED");
        $harga = $d3->harga;
    }    

    $f->PgConn = $con;
    $f->hidden("f_kategori_stock_id",
            $_GET["s"]);
    if($_GET["e"] == "new"){
    //$f->hidden("f_kategori_id",
    //        $_GET["o"]);
    $f->selectSQL("f_kategori_id","Kategori Obat","SELECT tc,tdesc FROM rs00001 WHERE tc!='000' AND tt='GOB' ORDER BY tdesc ASC",
	$_GET["o"]);
         }
    else{
	$f->selectSQL("f_kategori_id","Kategori Obat","SELECT tc,tdesc FROM rs00001 WHERE tc!='000' AND tt='GOB' ORDER BY tdesc ASC",
	getFromTable("SELECT kategori_id FROM rs00015 WHERE id=".$_GET['e']));	
		}
    $f->hidden("mOBT",
            $_GET["o"]);

	$r4 = pg_query($con,
                "select * " .
                "from margin_apotik " .
                "where kategori_id = '" . $_GET["o"] . "'");
        $d4 = pg_fetch_object($r4);
        pg_free_result($r4);

    $jd1 = "Obat";
    if ($_GET["o"] == "005") {
        $jd1 = "Nama Bahan Makanan";
    } elseif ($_GET["o"] == "004") {
        $jd1 = "Nama Barang Habis Pakai";
    }elseif ($_GET["o"] == "021") {
        $jd1 = "Nama Barang";
    }
    $f->text("f_obat",
            $jd1,
            40,
            50,
            $d2->obat, 'required');
    if ($_GET["o"] == "001" OR $_GET["o"] == "002" OR $_GET["o"] == "003") {
        $f->text("f_generik",
                "Generik",
                40,
                50,
                $d2->generik);
    }
    $SQL =
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc from rs00001 where tt = 'SAT' and tc!='000' order by tdesc ASC";

    $f->selectSQL("f_satuan_id",
            "Satuan Jual",
            $SQL,
            $d2->satuan_id);


    $f->text("harga",
            "Harga Jual",
            12,
            12,
            $harga,
            "style='text-align:right' required");
/*
    $f->hidden("f_max_stok",
            "Stok Maksimal",
            12,
            12,'0',
            $d2->max_stok,
            "style='text-align:right'");
			
    $f->hidden("f_min_stok",
            "Stok Minimal",
            12,
            12,'0',
            $d2->min_stok,
            "style='text-align:right'");
*/
   $SQL2 =
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc from rs00001 where tt = 'GNR' and tc!='000' order by tdesc ASC";

    $f->selectSQL("f_jenis_id",
            "Jenis Obat",
            $SQL2,
            $d2->jenis_id);

    $SQL3 =
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc from rs00001 where tt = 'FRM' and tc!='000' order by tdesc ASC";

    $f->selectSQL("f_tipe_id",
            "Tipe Obat",
            $SQL3,
            $d2->tipe_id);
	
	$SQL4 =
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc from rs00001 where tt = 'ATB' and tc!='000' order by tdesc ASC";

    $f->selectSQL("f_antibiotik_id",
            "Anti Biotik",
            $SQL4,
            $d2->antibiotik_id);
			
	$SQL5 =
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc from rs00001 where tt = 'KRJ' and tc!='000' order by tdesc ASC";

    $f->selectSQL("f_kerjasama_id",
            "Kerjasama",
            $SQL5,
            $d2->kerjasama_id);
			
	$SQL6 =
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc from rs00001 where tt = 'PPL' and tc!='000' order by tdesc ASC";

    $f->selectSQL("f_prinsiple_id",
            "Prinsiple",
            $SQL6,
            $d2->prinsiple_id);

    $f->text("harga_beli",
            "Harga Beli",
            12,
            12,
            $d3->harga_beli,
            "style='text-align:right' required");

    /*$f->text("harga_car_drs",
            "Harga Jual Car drS",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_car_rsrj",
            "Harga Jual Car RS",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_car_rsri",
            "Harga Jual Inhealth drS",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_inhealth_drs",
            "Harga Jual Inhealth RS",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_inhealth_rs",
            "Harga Jual Jamkesmas RI",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_jam_ri",
            "Harga Jual Jamkesmas RJ",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_jam_rj",
            "Harga Jual Jamkesmas RJ",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_kry_kelinti",
            "Harga Jual Umum RI",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_kry_kelbesar",
            "Harga Jual Umum RJ",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_tanggungan_rs",
            "Harga Jual Tanggungan RJ",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_kry_kelgratisri",
            "Harga Jual Karyawan Kel. Inti",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_kry_kelrespoli",
            "Harga Jual Karyawan Kel. Besar",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_kry_kel",
            "Harga Jual Karyawan Kel. Gratis",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_umum_rj",
            "Harga Jual Penjualan Bebas",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_umum_ri",
            "Harga Jual Nempil",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_umum_ikutrekening",
            "Harga Jual Nempil Apotik Kurnia",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_gratis_rj",
            "Harga Jual Nempil Apotik Kurnia",
            12,
            12,
            $harga,
            "style='text-align:right'");
	
    $f->text("harga_gratis_ri",
            "Harga Jual Nempil Apotik Kurnia",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_pen_bebas",
            "Harga Jual Nempil Apotik Kurnia",
            12,
            12,
            $harga,
            "style='text-align:right'");

    $f->text("harga_nempil",
            "Harga Jual Nempil Apotik Kurnia",
            12,
            12,
            $harga,
            "style='text-align:right'");
    
    $f->text("harga_nempil_apt",
            "Harga Jual Nempil Apotik Kurnia",
            12,
            12,
            $harga,
            "style='text-align:right'");*/

    //****** Obat Baru ******//

    //Agung Sunandar, untuk menambahakan data stok dan pengimputan stok awal
    //Agung SUnandar 0:00 07/07/2012 mengganti sesuai dengan gudang depo
    if ($_GET["e"] != "new") {
        $f->selectArray("f_status",
                "Status",
                Array("1" => "Aktif", "0" => "Tidak Aktif"),
                $d2->status);
    }
    if ($_GET["e"] != "new") {
        $f->title("Data Stok Barang");
        //$f->title1("STOK GUDANG");
        $f->hidden("stok",
                "stok");

        $tot1 = 0;
        $totulang = 0;
        $row1 = 0;
        $i = 1;
        $j = 1;
        $last_id = 1;
	$f->text("qty_ri",
                  "FARMASI",
                  12,
                  12,
                            getFromTable("select qty_ri from rs00016a where obat_id = '" . $_GET[e] . "'"),
                            $ext);
							
		/*					
        while (@$row1 = pg_fetch_array($r1)) {
            if (($j <= $max_row) AND ($i >= $mulai)) {
                $no = $i;

                if ($row1["tc"] == '003') {
                    $gudang = getFromTable("select gudang from rs00016a where obat_id = '" . $_GET[e] . "'");
                    if ($gudang > 0) {
                        $ext = "";
                    } else {
                        $ext = " ";
                    }
                    $f->text("gudang",
                            $row1["tdesc"],
                            12,
                            12,
                            $gudang,
                            $ext);
                } elseif ($row1["tc"] == '020') {
				*/
                   /** $gudang = getFromTable("select qty_ri from rs00016a where obat_id = '" . $_GET[e] . "'");
                    if ($gudang > 0) {
                        $ext = "";
                    } else {
                        $ext = " ";
                    }
                    $f->text("qty_ri",
                            $row1["tdesc"],
                            12,
                            12,
                            $gudang,
                            $ext);*/
		
/*            
			} else {
                    $gudang = getFromTable("select qty_$row1[tc] from rs00016a where obat_id = '" . $_GET[e] . "'");
                    if ($gudang > 0) {
                        $ext = "";
                    } else {
                        $ext = " ";
                    }
                    //if(){
                    $f->text("x_qty_$row1[tc]",
                            $row1["tdesc"],
                            12,
                            12,
                            $gudang,
                            $ext);
                    //}
                }
                $j++;
            }
            $i++;
        } */
    }

    $f->submit(" Simpan ");
    $f->execute();

    echo "<br>";

    if (strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan",
                stripslashes($_GET["err"]));
    }
} else {
    title("<img src='icon/informasi-2.gif' align='absmiddle' >  MASTER DATA BARANG INVENTORY");
    if (isset($_GET["e"])) {
        $ext = "DISABLED";
    } else {
        $ext = "OnChange = 'Form1.submit();'";
    }
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p",
            $PID);

    $q = pg_query("select kategori_stock_id from rs00015 where kategori_id = '" . $_GET['mOBT'] . "'");
    $qr = pg_fetch_object($q);
    $f->hidden("stock_id",
            $qr->kategori_stock_id);

    $f->selectSQL("mOBT",
            "Kategori Inventory",
            //"select '' as tc, '' as tdesc union " . 
            //"select tc, tdesc ".
            "select tc, case when tc = '000' then '-' else tdesc end " .
            "from rs00001 " .
            "where tt = 'GOB' " .
            //"and tc != '000' ".
            "order by tdesc ASC",
            $_GET["mOBT"],
            $ext);
    $f->execute();

    //if ($_GET["mOBT"]) {
if (!$_GET["mOBTs"]) {
//        $jdl = "Data Obat Baru";
//        if ($_GET["mOBT"] == "005") {
//            $jdl = "Data Bahan Inst.Gizi";
//        } elseif ($_GET["mOBT"] == "004") {
//            $jdl = "Data BHP";
//        }
        // search box
        echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
        echo "<INPUT TYPE=HIDDEN NAME=mOBT VALUE='" . $_GET["mOBT"] . "'>";
        echo "<TD WIDTH=1>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='" . $_GET["search"] . "'></TD>";
        echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

        echo "</TR></FORM></TABLE></DIV>";

        if ($_GET[search] == '') {
            $stat = " and a.status='1' ";
        } else {
            $stat = " and (a.status='1' or a.status='0') ";
        }

        $t = new PgTable($con, "100%");
	
	/**
	if($_GET['mOBT'] && ($_GET['search']=='') && ($_GET['mOBT'] != '000')){
	    $kategori = " a.kategori_id = '" . $_GET["mOBT"] . "' and ";
	}
	else if(($_GET['search']!='') && ($_GET['mOBT'] != '000') && ($_GET['mOBT'] != '')){
		$kategori = " a.kategori_id = '" . $_GET["mOBT"] . "' and ";
	}
	
        $t->SQL =
                "select d.tdesc, a.obat, c.tdesc as satuan, tanggal(b.tanggal_entry,3) as tanggal_entry_str, " .
                "b.harga, e.qty_ri, case when a.status = '0' then 'Tidak Aktif' else 'Aktif' end,a.id as dummy,a.id as dummy " .
                "from rs00015 a, rs00016 b, rs00001 c, rs00001 d, rs00016a e " .
                "where "  .$kategori.
               // "a.kategori_id = '" . $_GET["mOBT"] . "' and " .
                "a.id = b.obat_id and a.satuan_id = c.tc and c.tt='SAT' $stat and a.kategori_id = d.tc AND d.tt='GOB' AND" .
                "(upper(obat) ILIKE '%" . $_GET["search"] . "%') AND a.id = e.obat_id";
	*/
	
	
	if($_GET['mOBT'] && ($_GET['search']=='') && ($_GET['mOBT'] != '000')){
	    $kategori = " a.kategori_id = '" . $_GET["mOBT"] . "' and ";
	}
	else if(($_GET['search']!='') && ($_GET['mOBT'] != '000') && ($_GET['mOBT'] != '')){
		$_GET['mOBT'] = '';
	}

	$t->SQL = "select d.tdesc, a.obat, c.tdesc as satuan, tanggal(b.tanggal_entry,3) as tanggal_entry_str, b.harga, e.qty_ri, 
	    case when a.status = '0' then 'Tidak Aktif' else 'Aktif' end,a.id as dummy,a.id as dummy from 
	    rs00015 a JOIN  rs00016 b ON a.id = b.obat_id 
	    LEFT OUTER JOIN rs00001 c ON a.satuan_id = c.tc AND c.tt='SAT' 
	    LEFT OUTER JOIN rs00001 d ON a.kategori_id = d.tc AND d.tt='GOB'
	    LEFT OUTER JOIN rs00016a e ON a.id = e.obat_id WHERE $kategori
            (upper(obat) ILIKE '%" . $_GET["search"] . "%')";
		
        $t->setlocale("id_ID");
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColAlign[3] = "CENTER";
        $t->ColAlign[4] = "RIGHT";
        $t->ColAlign[6] = "CENTER";
        $t->ColAlign[7] = "CENTER";
        $t->ColAlign[8] = "CENTER";
        //$t->ColFormatMoney[3] = "%!+#2n";
        $t->ColFormatNumber[4] = 2;
        $t->ColFormatHtml[7] = "<nobr><A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#7#>&o=" . $_GET["mOBT"] .
                "&search=" . $_GET[search] . "&sort=" . $_GET[sort] . "&order=" . $_GET[order] . "&tblstart=" . $_GET[tblstart] . "'>" . icon("edit",
                        "Edit") . "</A>";
        $t->ColFormatHtml[8] = "<nobr><A CLASS=TBL_HREF HREF='$SC?p=koponsepersipi&e=<#8#>&o=" . $_GET["mOBT"] . "&f=kon'>" . icon("conversion",
                        "Tambah Satuan Konversi") . "</A>";

        /*         * ***** tokit: jangan diberi fasilitas delete, ini berhubungan dengan inventori. Bahaya!!!	

          "&nbsp;<A CLASS=TBL_HREF HREF='807.delete.php?p=$PID&e=<#5#>&o=".$_GET["mOBT"].
          "&search=".$_GET[search]."&sort=".$_GET[sort]."&order=".$_GET[order]."&tblstart=".$_GET[tblstart]."'>".icon("delete","Hapus")."</A></nobr>";

         * ****** */

        $t->ColHeader = array("KATEGORI","ITEM", "SATUAN", "TANGGAL UPDATE", "HARGA JUAL (HNA)","STOK<br/>APOTEK", "STATUS", "E D I T", "TAMBAH<br>SATUAN", "TAMBAH<br>KONVERSI");

        $t->execute();
        
        if($_GET["mOBT"] == "004"){
            echo "&nbsp;&nbsp;&nbsp;<img src=\"icon/inventory.gif\" align=absmiddle ><A CLASS=SUB_MENU " .
        "HREF='index2.php?p=$PID&e=new&o=" . $_GET["mOBT"] . "&s=" . $qr->kategori_stock_id . "'><font color='black'> Tambah BHP </font></A></DIV>";
        }else if($_GET["mOBT"] == "021"){
            echo "&nbsp;&nbsp;&nbsp;<img src=\"icon/inventory.gif\" align=absmiddle ><A CLASS=SUB_MENU " .
        "HREF='index2.php?p=$PID&e=new&o=" . $_GET["mOBT"] . "&s=" . $qr->kategori_stock_id . "'><font color='black'> Tambah Bahan Paket </font></A></DIV>";
        }else{
	echo "<BR><DIV ALIGN=LEFT><img src=\"icon/inventory.gif\" align=absmiddle ><A CLASS=SUB_MENU " .
        "HREF='index2.php?p=$PID&e=new&o=" . $_GET["mOBT"] . "&s=" . $qr->kategori_stock_id . "'><font color='black'> Tambah Obat Baru </font></A>";	
	}
    }
	
}
?>
