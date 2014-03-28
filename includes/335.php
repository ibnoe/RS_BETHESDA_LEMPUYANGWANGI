<?php

$_GET['search'] = trim($_GET['search']);
$jns_kasir = array(
    "rj" => "RAWAT JALAN",
    "ri" => "RAWAT INAP",
    "igd" => "IGD",
        );
$kasirnya = $_GET["kas"];


if ($kasirnya) {
    /* End of By YGR */

    $PID = "335";
    $SC = $_SERVER["SCRIPT_NAME"];

    require_once("lib/dbconn.php");
    require_once("lib/form.php");
    require_once("lib/class.PgTable.php");
    require_once("lib/functions.php");


    $reg = $_GET["rg"];
    $id_reg = getfromtable("select to_number(id,'9999999999') as id FROM rs00006 where to_number(id,'9999999999') = $reg");
    if ($reg > 0) {
        if ($id_reg == 0)
        {
            $reg = 0;
            $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
        }
    }

    echo "</br>";
    echo "<table width='100%'>";
    echo "<tr>";
    echo "<td>";
    title("<img src='icon/kasir-2.gif' align='absmiddle' > KASIR " . $jns_kasir[$kasirnya]);
    echo "</td>";
	
	if ($kasirnya == 'ri') {
    echo "<td>";
    title("<img src='icon/rawat-jalan-2_asli.gif' align='absmiddle' > <A CLASS=SUB_MENU HREF='index2.php?p=888&kas=ri'><font color='black'>INPUT DEPOSIT RAWAT INAP </font></A>");
    echo "</td>";
    } else if ($kasirnya == 'rj' || $kasirnya == 'igd') {
	echo "<td>";
    title("<img src='icon/rawat-jalan-2_asli.gif' align='absmiddle' > <A CLASS=SUB_MENU HREF='index2.php?p=888&kas=rj'><font color='black'>INPUT DEPOSIT RAWAT JALAN </font></A>");
    echo "</td>";
	}
	
	echo "<td>";
    title("<img src='icon/apotek1-icon.png' align='absmiddle' > <A CLASS=SUB_MENU  HREF='index2.php?p=apotik_umum_kasir'><font color='black'>APOTEK UMUM</font></A>");
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "</br>";

    if ($reg) {

        echo "<DIV ALIGN=RIGHT OnClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&kas=$kasirnya\"'>" . icon("back",
                "Kembali") . "</a></DIV>";

        include("335.inc.php");

        echo "<form name=Form3>";
        echo "<input name=b2 type=button value='Rincian Layanan/Tindakan Medis'  onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&kas=$kasirnya&rg=" . $_GET["rg"] . "&sub=3\";'" . ($_GET["sub"] == "3"
                ? " DISABLED"
                : "") . ">&nbsp;";
        echo "<input name=b2 type=button value='Pembayaran'       onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&kas=$kasirnya&rg=" . $_GET["rg"] . "&sub=4\";'" . ($_GET["sub"] == "4"
                ? " DISABLED"
                : "") . ">&nbsp;";
        echo "</form>";

        $sub = isset($_GET["sub"])
                ? $_GET["sub"]
                : "4";
        if (file_exists("includes/$PID.$sub.php"))
            include_once("includes/$PID.$sub.php");
    } else {
        $f = new Form($SC, "GET", "NAME=Form1");
        $f->PgConn = $con;
        $f->hidden("p",
                $PID);
        $f->hidden("kas",
                $kasirnya);

        echo "<DIV ALIGN=RIGHT>";
        echo "<TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID >";
        echo "<INPUT TYPE=HIDDEN NAME=kas VALUE='$kasirnya' >";
        echo "<TD >Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='" . $_GET["search"] . "'></TD>";
        echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

        echo "</TR></FORM></TABLE>";
        echo "</DIV>";

        $SQLSTR = "select a.id, upper(a.nama)as nama, a.mr_no, a.tgl_reg, a.pasien,  a.asal, a.statusbayar, a.tagih, a.bayar,
		case when a.sisa < 0 then a.sisa * -1 else a.sisa end  from rsv0012 a ";

        $SQLSTR .=" left join rs00006 b on a.id = b.id  ";
        $SQLSTR .="  left join rs00001 c on b.status_akhir_pasien  = c.tc and c.tt = 'SAP' ";


        $what = $jns_kasir[$kasirnya];
        $SQLWHERE = "where a.rawat = '$what'  ";
        if ($kasirnya == "rj") {
            $SQLWHERE1 .= " AND upper(a.pasien) NOT LIKE('%DINAS%')";
        }

        $SQLWHERE2 = " and (upper(a.nama) LIKE '%" . strtoupper($_GET[search]) . "%' or " .
                " a.mr_no like '%" . $_GET[search] . "%' or a.id like '%" . $_GET[search] . "%') ";
        if ($_GET[search]) {
            $SQLWHERE3 = " and (a.statusbayar like '%%')";
        } else {
            $SQLWHERE3 = " and (a.statusbayar like '%BELUM%')";
        }
        $SQLWHERE4 = " and (a.pasien like '%%')";
        $ORDERBY = " ORDER BY a.tgl_reg DESC";
	/**
        if (!isset($_GET[sort])) {
            $_GET[sort] = "a.tanggal_reg";
            $_GET[order] = "asc";
        }
	*/
        echo "<br>";
        $t = new PgTable($con, "100%");
        $t->SQL = "$SQLSTR $SQLWHERE $SQLWHERE1 $SQLWHERE2 $SQLWHERE3 $SQLWHERE4";
	$t->DefaultSort = "b.tanggal_reg";
        $t->DefaultOrder = "DESC";
        $t->ColHeader = array("NO.REG", "N A M A", "NO. MR", "TGL. REGISTRASI", "TIPE PASIEN", "POLIKLINIK", "STATUS BAYAR", "TAGIHAN", "BAYAR", "SISA");
        $t->ShowRowNumber = true;
        $t->setlocale("id_ID");
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[2] = "CENTER";
        $t->ColAlign[3] = "CENTER";
        $t->ColAlign[4] = "CENTER";
        $t->ColAlign[5] = "CENTER";
        $t->ColAlign[6] = "CENTER";
        $t->ColAlign[7] = "CENTER";
        $t->ColAlign[8] = "CENTER";
        $t->RowsPerPage = 20;
        $t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID" . "&rg=<#0#>&sub=4" . "&t1=$ts_check_in1" . "&t2=$ts_check_in2" .
                "&kas=$kasirnya" . /* By Yudha */
                "'><#1#></A>";
        if (($_SESSION["gr"] == "root") or ($_SESSION["gr"] == "finance")) {

            $t->ColFormatHtml[10] = "<A CLASS=TBL_HREF HREF='actions/hapus_pembayaran.php?" . "&rg=<#0#>&kasir=klinik&kas=" . $_GET[kas] . "" . /* By Yudha */
                    "'>HAPUS PEMBAYARAN</A>";
        }
        $t->execute();

        echo "<br>";
		echo "<a href='actions/hapus_pembayaran.php?" . "&rg=&kasir=klinik&kas=" . $_GET[kas] . "'>[ REVISI PEMBAYARAN ]</a>";


        $SQLSTR1 = "select a.id, a.nama, a.mr_no, a.tgl_reg, a.pasien, c.tdesc, a.asal, a.statusbayar, a.tagih, a.bayar,a.sisa from rsv0012 a 
			left join rs00006 b on a.id = b.id  
			left join rs00001 c on b.status_akhir_pasien  = c.tc and c.tt = 'SAP' 
			where a.rawat = '$what'	and (a.tagih = 0) and (a.pasien like '%%') ORDER BY a.id DESC";

        @$r1 = pg_query($con,
                $SQLSTR1);
        @$n1 = pg_num_rows($r1);

        $max_row = 30;
        $mulai = $HTTP_GET_VARS["rec"];
        if (!$mulai) {
            $mulai = 1;
        }

        $cek = getFromTable("select count(id) from rsv0012 where rawat='$what' and tagih = 0 ");
        if ($cek > 0) {
            ?>
            <br>
            <br>
            <table border="0" width="100%">
                <tr>
                    <td align="center"><blink><font FACE="georgia" color="Red"><b>MASIH TERDAPAT PASIEN YANG BELUM DIINPUTKAN LAYANAN TINDAKAN</b></font></blink></td>
            </tr>
            <tr>
                <td align="center"><marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="2" direction="up" width="100%" height="200" align="center">
                <table width="100%">
                    <tr>
                        <td class="TBL_HEAD" width="4%">NO</td>
                        <td class="TBL_HEAD" >NO.REG</td>
                        <td class="TBL_HEAD" >NO.MR</td>
                        <td class="TBL_HEAD" >TANGGAL REG</td>
                        <td class="TBL_HEAD" >NAMA PASIEN</td>
                        <td class="TBL_HEAD" >POLI KLINIK</td>
                    </tr>
            <?
            $tot1 = 0;
            $totulang = 0;
            $row1 = 0;
            $i = 1;
            $j = 1;
            $last_id = 1;
            while (@$row1 = pg_fetch_array($r1)) {
                if (($j <= $max_row) AND ($i >= $mulai)) {
                    $no = $i
                    ?>		
                            <tr valign="top" class="<? ?>" > 
                                <td class="TBL_BODY" align="center"><?= $no ?> </td>
                                <td class="TBL_BODY" align="left"><?= $row1["id"] ?> </td>
                                <td class="TBL_BODY" align="left"><?= $row1["mr_no"] ?> </td>
                                <td class="TBL_BODY" align="left"><?= $row1["tgl_reg"] ?> </td>
                                <td class="TBL_BODY" align="left"><?= $row1["nama"] ?> </td>
                                <td class="TBL_BODY" align="left"><?= $row1["asal"] ?> </td>
                            </tr>	

                    <?
                    ;
                    $j++;
                }
                $i++;
            }
            ?>
                </table>
            </marquee></td>
            </tr>
            </table>
            <?
        }
    }
}
?>
