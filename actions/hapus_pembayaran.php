<?php
session_start();
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
?>

<HTML>

    <HEAD>
        <TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
        <LINK rel='styleSheet' type='text/css' href='../invoice1.css'>
        <SCRIPT LANGUAGE="JavaScript">
            <!-- Begin
            function printWindow() {
                bV = parseInt(navigator.appVersion);
                if (bV >= 4) window.print();
            }
            //  End -->
        </script>


    </HEAD>

    <BODY TOPMARGIN=1 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0 >

<?
if ($_POST[action] == 'batal') {//awal proses
    pg_query("select nextval('kasir_seq')");
    $kasir = $_POST["kasir"];

    if ($kasir == "umum") {


        $bayar = getFromTable("select jumlah from rs00005 where kasir='BYU' and reg ='" . $_POST["rg"] . "'");
        $carabayar = getFromTable("select bayar from rs00005 where kasir='BYU' and reg ='" . $_POST["rg"] . "'");
        $nmkasir = getFromTable("select nama_kasir from rs00005 where kasir='BYU' and reg ='" . $_POST["rg"] . "'");
        $tanggal = getFromTable("select tgl_entry from rs00005 where kasir='BYU' and reg ='" . $_POST["rg"] . "'");
        $jam_trx = getFromTable("select waktu_bayar from rs00005 where kasir='BYU' and reg ='" . $_POST["rg"] . "'");

        $total = 0 - $bayar;
        pg_query("INSERT INTO rs00005 VALUES( currval('kasir_seq'), '" . $_POST["rg"] . "', " .
                "'$tanggal', 'BYU', 'Y', 'N', 0, $total, 'N','$carabayar','" . $nmkasir . "','','$jam_trx')");

        pg_query("UPDATE rs00005 set jumlah=0 where kasir ='POT' and reg ='" . $_POST["rg"] . "' ");
        pg_query("UPDATE rs00005 set is_bayar='N' where reg ='" . $_POST["rg"] . "' ");
        pg_query("UPDATE rs00008 set is_bayar='N' where no_reg ='" . $_POST["rg"] . "' ");

        $mr_no = getFromTable("select mr_no from rs00006 where id ='" . $_POST["rg"] . "'");

        $no_faktur = $_POST["rg"] . '-' . $mr_no;
        $id = getFromTable("select id from jurnal_umum_m where no_faktur ='$no_faktur'");

        if ($id != "") {
            pg_query("delete from jurnal_umum where id=$id ");
            pg_query("delete from jurnal_umum_m where  no_faktur ='$no_faktur'");
        }
    } else {
        pg_query("DELETE FROM rs00005 WHERE reg = '".$_POST["rg"]."' AND (kasir = 'BYR' OR kasir = 'BYD' OR kasir = 'BYI') ");
        pg_query("UPDATE rs00005 SET jumlah = 0 WHERE reg = '".$_POST["rg"]."' AND kasir = 'ASK' ");
        pg_query("UPDATE rs00005 SET jumlah = 0 WHERE reg = '".$_POST["rg"]."' AND kasir = 'POT' ");
        pg_query("UPDATE rs00008 SET is_bayar = 'N' WHERE no_reg = '".$_POST["rg"]."' ");
		
		//delete Deposite
		pg_query("DELETE FROM rs00044 WHERE no_reg = '".$_POST["rg"]."'");
    }
    $PID2 = "KASIR";
    $SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) " .
            "values" .
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'$PID2','Kasir','Pembatalan Pembayaran dengan no " . $_POST["rg"] . ", sejumlah jumlah :" . $bayar . ", alasan: " . $_POST["alasan"] . "','" . $_SESSION["uid"] . "','" . $_SESSION["nama_usr"] . "')";
    pg_query($con,
            $SQL2);

    echo "<script language='JavaScript'>document.location='../index2.php?p=335&rg=".$_POST['rg']."&kas=" . $_POST['kas'] . "'</script>";
}//akhir proses
else {
    echo "<div class='wrapper'>";
    if ($_GET[rg] > 0) {

        $cek = getFromTable("select count(id) from rsv0012 where id='" . $_GET[rg] . "'");
        if ($cek == 0) {
            echo "<blink><font color=red></b>No. Reg tidak ditemukan</b></font></blink><br><br>";
            echo "<a href='javascript:history.back()'>Kembali</a>";
        } else {
            $cek_nm = getFromTable("select nama from rsv0012 where id='" . $_GET[rg] . "'");
            $cek_jml = getFromTable("select bayar from rsv0012 where id='" . $_GET[rg] . "'");

            $f = new Form("hapus_pembayaran.php", "POST", "name=form1");
            $f->hidden("rg",
                    $_GET["rg"]);
            $f->hidden("kasir",
                    $_GET["kasir"]);
            $f->hidden("kas",
                    $_GET["kas"]);
            $f->hidden("action",
                    "batal");
            $f->text("rg1",
                    "No. Reg",
                    10,
                    20,
                    $_GET["rg"],
                    "readonly");
            $f->text("nama",
                    "Nama Pasien",
                    20,
                    20,
                    $cek_nm,
                    "readonly");
            $f->text("bayar",
                    "Jumlah Bayar",
                    20,
                    20,
                    "Rp. " . number_format($cek_jml,
                            2,
                            ",",
                            "."),
                    "readonly");
            $f->text("alasan",
                    "Ket. Pembatalan",
                    50,
                    50,
                    "");
            if ($cek_jml > 0) {
                $f->submit("SIMPAN");
            } else {
                ?> <a href="../index2.php?p=335&kas=<?php echo $_GET[kas] ?>">Kembali</a> <?php
            }
            $f->execute();
        }
    } else {


        echo "<form action=hapus_pembayaran.php name=form1 onSubmit='return checkinput()'>";
        echo "<input type=hidden name=kasir value=" . $_GET["kasir"] . ">";
        echo "<input type=hidden name=kas value=" . $_GET["kas"] . ">";
        echo "<input type=hidden name=action value=batal>";
        echo "<table border=0>";
        echo "<tr><td class=FORM>Nomor Registrasi</td><td class=FORM>:</td>";
        echo "    <td class=FORM colspan=2><input type=TEXT name=rg id=rg size=15 maxlength=20 value='" . $_SESSION["rg"] . "'></td></tr>";
        echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
        echo "    <td class=FORM colspan=2><input type=SUBMIT value='Cek' ></td></tr>";
        echo "</tr></table>";
        echo "</form>";
    }
    echo "</div>";
}
?>
    </body>
</html>