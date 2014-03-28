<?php // Nugraha, Sun Apr 18 18:58:42 WIT 2004
      // sfdn, 22-04-2004: hanya merubah beberapa title
      // sfdn, 23-04-2004: tambah harga obat
      // sfdn, 30-04-2004
      // sfdn, 09-05-2004
      // sfdn, 18-05-2004: age
      // sfdn, 02-06-2004
      // Nugraha, Sun Jun  6 18:14:41 WIT 2004 : Paket Transaksi
	  // sfdn, 06-06-2004 : fungsi umur
	  // sfdn, 08-06-2004
	  // sfdn, 10-06-2004

if ($_SESSION[uid] == "kasir1" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {



$PID = "325";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
title("Pembelian Karcis");
echo "<br>";

unset($_GET["layanan"]);
//unset($_SESSION["layanan"]);
// unset($_GET["jumlah"]);
$reg = $_GET["rg"];
if ($reg > 0) {
    if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where to_number(id,'9999999999') = $reg ".
                     " ") == 0) {
		     //"and status = 'A'") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}

/*
    if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where to_number(id,'9999999999') = $reg ")  {
                     "and status_akhir_pasien != '-'") > 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak dpt. dipergunakan. Pasien tlh. Dilayani";
    }
*/

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
		"	case when a.rujukan = 'Y' then 'Rujukan' else 'Non-Rujukan' end as datang ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc and h.tt = 'JDP' ".
        "WHERE a.id = lpad($reg,10,'0')");

    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Nomor Registrasi", formatRegNo($d->id));
    $f->text("Nomor MR", $d->mr_no);
    $f->text("Nama", $d->nama);
    /*$f->text("Pasien Dari", $d->rawat_inap == "Y" ? "Rawat Inap" : "Rawat Jalan" );*/
    // diganti oleh sfdn
    $f->text("Pasien Dari",$d->rawatan);
    $f->text("Jenis Kedatangan",$d->datang);
    // akhir ganti
    $f->execute();
    echo "</td><td align=center valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Alamat", "$d->alm_tetap $d->kota_tetap $d->pos_tetap");
    $f->text("Telepon", $d->tlp_tetap);
    $f->text("Tanggal", date("d F Y"));
    $f->text("Tipe Pasien", $d->tipe_desc);
    $f->text("Umur", umur($d->umur));
    $f->execute();
    echo "</td><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    echo "<table border=0 width='100%'>";
    echo "<tr><td class=TBL_BODY>Diagnosa Sementara:</td></tr>";
    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
    echo "</table>";
    $f->execute();
    echo "</td></tr></table>";
    $total = 0.00;
    if (is_array($_SESSION["layanan"])) {
        foreach($_SESSION["layanan"] as $k => $l) {
            $total += $l["total"];

        }
    }
    echo "<div class=box>";

    if ($_GET["sub"] == "byr") {

        //echo "<br><br>";
        $kodepoli = getFromTable("select poli from rs00006 where id = lpad('".$_GET["rg"]."',10,'0')");
	$r2 = pg_query($con, "select * from rs99996 where trans_type = 'LYN' and poli = $kodepoli order by description");
        $d2 = pg_fetch_object($r2);

        title($d2->description);
        title("Total Tagihan: Rp. ".number_format($total,2));
        $f = new Form("actions/325.insert.php");
        $f->hidden("rg",$_GET["rg"]);
        $f->hidden("rawatan",$d->rawatan);
        $f->hidden("byr",$total);
        //$f->text("byr","Jumlah Pembayaran",15,15,$total,"STYLE='text-align:right'");
        $f->submit(" Simpan &amp; Bayar ");
        $f->execute();
    } else {
	echo "<br>";
        title("Harga Karcis");

	//echo "<script language='JavaScript'>\n";
        //echo "document.Form3.b1.disabled = true;\n";
        //echo "</script>\n";

        echo "<FORM ACTION='$SC' NAME=Form8>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
        echo "<INPUT TYPE=HIDDEN NAME=rg VALUE='".$_GET["rg"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=httpHeader VALUE='1'>";

        $t = new BaseTable("100%");
        $t->printTableOpen();
        $t->printTableHeader(Array("KODE", "Layanan", "Jumlah", "Satuan",
            "Harga Satuan", "Harga Total", ""));

        if (is_array($_SESSION["layanan"])) {
            $total = 0.00;
            foreach($_SESSION["layanan"] as $k => $l) {
                $t->printRow(
                    Array($l["id"], $l["nama"], $l["jumlah"], $l["satuan"],
                        number_format($l["harga"],2), number_format($l["total"],2),
                        "<A HREF='$SC?p=$PID&regno=".$_GET["rg"]."'></A>"),
                    Array("CENTER", "LEFT", "RIGHT", "LEFT", "RIGHT", "RIGHT", "CENTER")
                );
                $total += $l["total"];

            }
        }
        $t->printRow(
            Array("", "", "", "", "", number_format($total,2),""),
            Array("RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT", "RIGHT")
        );

        $t->printTableClose();
        echo "</FORM>";
        echo "</div>";
    }
	if ($_GET["sub"] != "byr") {
		echo "<table border=0 width='100%'><td>";
		$kodepoli = getFromTable("select poli from rs00006 where id = lpad('".$_GET["rg"]."',10,'0')");
		$r2 = pg_query($con, "select * from rs99996 where trans_type = 'LYN' and poli = $kodepoli order by description");
		$enableOK = "Y";
                $d2 = pg_fetch_object($r2);
                //if (getFromTable("select rawat_inap from rs00006 where id = lpad('".$_GET["rg"]."',10,'0')") == "I") {
		//	$r2 = pg_query($con, "select * from rs99996 where trans_type = '' order by description");
		//	$enableOK = "N";
		//	echo "Masuk ke sini";
		//}
		title($d2->description);
                echo "<form name='Form10' action='actions/998.325.load.php' method=POST>";
		echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
                echo "<input type=hidden name=preset value='$d2->id'>";

                /*
		echo "<select name=preset>";
                while ($d2 = pg_fetch_object($r2)) {
			if ($d2->id == $_SESSION["LAST_PRESET"]) {
				echo "<option selected value='$d2->id'>$d2->description</option>'";
			} else {
				echo "<option value='$d2->id'>$d2->description</option>'";
			}
		}
                pg_free_result($r2);
		echo "</select>";
		if ($enableOK == "Y") {
			echo "<input type='submit' value='OK'>";
		}
		*/

		echo "</form></td>";
		if (!isset($_GET[ok])) {
		   echo "<script language=javascript>\n";
		   echo "<!--\n";
		   echo "Form10.submit();\n";
		   echo "-->\n";
		   echo "</script>\n";


		}

                echo "<td align=right>";
		echo "<form name='Form9' action='actions/325.insert.php' method=POST>";
		echo "<input type=hidden name=rg value='".$_GET["rg"]."'>";
		echo "<input type=hidden name=rawatan value='".$d->rawatan."'>";

                //echo "<input type=button value='Simpan' onClick='window.location=\"$SC?p=325&rg=".$_GET["rg"]."&sub=sim\"'>&nbsp;";
		if ($total > 0) {
                   if ($d->rawatan != "Rawat Jalan") {
                      echo "<input type=button value='Simpan' onClick='Form9.submit()'>&nbsp;";
                   }
                   if ($d->rawatan != "Rawat Inap") {
	              echo "<input type=button value='Simpan &amp; Bayar' onClick='window.location=\"$SC?p=325&rg=".$_GET["rg"]."&sub=byr\"'>&nbsp;";
	           }
		}
		echo "</form>";
		echo "</td></tr></table>";
	}
} else {
    echo "<DIV class=BOX>";
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    echo "<br>";
    $f->selectSQL("mPOLI", "P O L I",
        "select '' as tc, '' as tdesc union ".
		"select ltrim(rtrim(to_char(id,'999999999'))) as tc,layanan as tdesc ".
		"from rs00034  ".
		"where substr(hierarchy,1,3)='002' and ".
   		"	substr(hierarchy,4,3) NOT IN ('000') and ".
   		"	is_group ='Y' and substr(hierarchy,1,6) ".
		"		NOT IN ('002087','002000','002086','002084')", $_GET["mPOLI"],$ext);
    $f->execute();
	echo "<br>";
	$SQLSTR =
        "select a.id, a.tanggal_reg, d.nama, ".
		"	case when a.rawat_inap='Y' then (select x.layanan from rs00034 x where x.id = a.poli ) ".
		"		else (select x.layanan from rs00034 x where x.id=10) end as layanan, ".
        "   case when a.rawat_inap='Y' then 'RAWAT JALAN' else 'IGD' end as rawatan, ".
        "   b.tdesc as pasien, ".
		"	case when rujukan='N' then 'Non-Rujukan' else 'Rujukan' end as datang  ".
        "from rs00006 a  ".
        "   left join rs00001 b ON a.tipe = b.tc and b.tt='JEP' ".
        "   left join rs00002 d ON a.mr_no = d.mr_no ";
	if (strlen($_GET["mPOLI"]) > 0) {
		$SQLWHERE =
			"where poli ='".$_GET["mPOLI"]."' and is_karcis='N'";
	} else {
		$SQLWHERE = " where is_karcis='N'";
	}

    $SQLWHERE2 = " and a.poli not in (11095,12651,12652,12653) ";

    if ($_SESSION[uid] == "igd") {
       $SQLWHERE3 = " and a.rawat_inap = 'N' ";
    } elseif ($_SESSION[uid] == "kasir1")  {
       $SQLWHERE3 = " and a.rawat_inap = 'Y' ";
    } else {
       $SQLWHERE3 = "";
    }

    if (!isset($_GET[sort])) {

           $_GET[sort] = "id";
           $_GET[order] = "desc";
    }


    $t = new PgTable($con, "100%");
    $t->SQL = "$SQLSTR $SQLWHERE $SQLWHERE2 $SQLWHERE3";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[4] = "CENTER";
	$t->ColAlign[5] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&rg=<#0#>'><#0#></A>";
    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColHeader = array("NO.REG","TANGGAL  REGISTRASI","NAMA PASIEN","P O L I","LOKET","TIPE PASIEN","KEDATANGAN");
    $t->execute();
/*
    $f = new Form($SC, "GET");
    $f->hidden("p", $PID);
    $f->text("rg","Masukkan Nomor Registrasi",10,10,$_GET["rg"]);
    $f->submit(" Transaksi ");
    $f->execute();
    if ($msg) errmsg("Error:", $msg);
*/

    echo "</DIV>";
}


} // end of $_SESSION[uid]
?>
