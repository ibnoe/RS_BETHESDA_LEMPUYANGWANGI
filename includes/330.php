<?php // Nugraha, Thu Apr 22 19:51:19 WIT 2004
      // sfdn, 10-05-2004
      
$PID = "330";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

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

title("Informasi Pasien");
echo "<br>";

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
		"	case when a.rujukan ='Y' then 'Rujukan' ".
		"		 when a.rujukan = 'U' then 'Unit Lain ' else 'Non-Rujukan' end as datang ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "WHERE a.id = lpad($reg,10,'0')");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    $r = new Form("");
    $r->subtitle("Data Medis Terakhir");
    $r->execute();
	$akhir = getFromTable("select to_char(CURRENT_TIMESTAMP,'DD MONTH YYYY HH24:MI:SS')");
    echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Nama", $d->nama);
    $f->text("Nomor Registrasi", formatRegNo($d->id). " - " . getFromTable("select count(mr_no) from rs00006 where mr_no = '$d->mr_no'"));
    $f->text("Nomor MR", $d->mr_no);
    $f->text("Pasien Dari", $d->rawatan );
    $f->text("RS Perujuk", $d->rujukan_rs);
    $f->text("Dokter Perujuk", $d->rujukan_dokter);
    $f->text("Jenis Kedatangan", $d->datang);
    $f->execute();
    echo "</td><td align=center valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Tanggal Reg.", $d->tanggal_reg);
    $f->text("Waktu Registrasi", substr($d->waktu_reg,0,8));
    $f->text("Penanggung", $d->penanggung);
    $f->text("Penjamin", $d->penjamin);
    $f->text("No. Jaminan", $d->no_jaminan);
    $f->text("Tipe Pasien", $d->tipe_desc);
	$f->text("Sampai Dgn.Tgl",$akhir);
    $f->execute();

    echo "</td><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    echo "<table border=0 width='100%'>";
    echo "<tr><td class=TBL_BODY>Diagnosa Sementara:</td></tr>";
    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
    echo "</table>";
    $f->execute();
    echo "</td></tr></table>";
    echo "<form name=Form3>";
    echo "<input name=b1 type=button value='Identitas' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=1\";'".($_GET["sub"] == "1" || $_GET["sub"] == "" ? " DISABLED" : "").">&nbsp;";
    echo "<input name=b2 type=button value='Tindakan Medis' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=2\";'".($_GET["sub"] == "2" ? " DISABLED" : "").">&nbsp;";
    echo "<input name=b2 type=button value='Rincian Tagihan' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=3\";'".($_GET["sub"] == "3" ? " DISABLED" : "").">&nbsp;";
    echo "</form>";

    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
    if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");

} else {
    echo "<DIV class=BOX>";
    $f = new Form($SC, "GET");
    $f->hidden("p", $PID);
    $f->text("rg","Masukkan Nomor Registrasi",10,10,$_GET["rg"]);
    $f->submit(" Transaksi ");
    $f->execute();
    if ($msg) errmsg("Error:", $msg);
    echo "</DIV>";
}


?>
