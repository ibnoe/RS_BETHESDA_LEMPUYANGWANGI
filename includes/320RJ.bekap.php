<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.autocomplete.css">
<?php
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$PID = "320RJ";
$SC = $_SERVER["SCRIPT_NAME"];

// ----------------- Start Form Pencarian Pasien -------------------------------
$f = new Form($SC, "GET", "NAME=Form2");
$f->hidden("p", $PID);
$f->hidden("tt", $_GET["tt"]);
$f->search("search",  "Pencarian Nama atau No.MR", 20, 20, $_GET["search"], "icon/ico_find.gif", "Cari", "OnChange='Form2.submit();'");

echo "<TABLE  width='100%'>";
echo " <tr>";
echo "  <td ALIGN=RIGHT>";
        $f->execute();
        if ($msg)
            errmsg("Error:", $msg);
echo "  </td>";
echo "</tr>";
echo "</table>";
echo "<p/>";
// ----------------- End Form Pencarian Pasien ---------------------------------
?>
<table>
    <tr>
        <td><?title("<img src='icon/apotek1-icon.png' align='absmiddle' >Layanan Apotek Klinik ". $ket);?></td>
		<td><?title("<img src='icon/apotek-icon.png' align='absmiddle' ><A CLASS=SUB_MENU  HREF='index2.php?p=apotik_umum&tt=".$_GET["tt"]."'>APOTEK UMUM</A>"); ?></td>
    </tr>
</table>
<?php
// ----------------- Start Result Search Pasien --------------------------------
if ($_GET["search"]) {
    $SQLSTR = "select d.mr_no, a.id, TO_CHAR(a.tanggal_reg,'dd-mm-yyyy') as tanggal_reg, d.nama,
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
    
    $SQLWHERE = "where  ((upper(d.nama) LIKE '%".strtoupper($_GET["search"])."%') OR 
                 d.mr_no LIKE '%".$_GET["search"]."%'  or 
                 a.id LIKE '%".$_GET["search"]."%' ) ";
    
    // Cek dulu, jika data yang ditampilkan 1 record, langsung aja munculkan form input obatnya
    $cekResultSearch = pg_query($con,$SQLSTR . $SQLWHERE);
    $numRowsResultSearch = pg_num_rows($cekResultSearch);
    
    if($numRowsResultSearch == 1){
        $resultSearch = pg_fetch_object($cekResultSearch);
        echo '<script>';
        echo 'window.location.href = "index2.php?p='.$PID.'&rg='.$resultSearch->id.'"; ';
        echo '</script>';
    }
    
    $t = new PgTable($con, "100%");
    $t = new PgTable($con, "100%");
    $t->SQL = "$SQLSTR $SQLWHERE  group by d.mr_no,a.id, a.tanggal_reg, d.nama,a.rawat_inap,a.rujukan,a.status_apotek,a.poli,b.tdesc order by a.id desc ";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "LEFT";
    $t->ColAlign[4] = "LEFT";
    $t->ColAlign[5] = "CENTER";
    $t->ColAlign[6] = "CENTER";
    $t->ColAlign[9] = "CENTER";
    $t->RowsPerPage = 20;
    $t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tt=".$_GET["tt"]."&rg=<#1#>$tambah&sub=obat'><#1#></A>";
    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL<br>REGISTRASI","NAMA PASIEN","P O L I","LOKET","TIPE PASIEN","KEDATANGAN","STATUS");
    $t->execute();
}
// ----------------- End Result Search Pasien ----------------------------------

// ----------------- Start Form Input Pelayanan Obat Pasien --------------------
$reg = $_GET['rg'];
$sub = $_GET['sub'];
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
	"WHERE a.id = '$reg'");
    
    $n = pg_num_rows($r);
    
    if($n > 0)
        $d = pg_fetch_object($r);
        pg_free_result($r);
        $rawatan = $d->rawatan;
        
        // ambil bangsal
        $id_max = getFromTable("select max(id) from rs00010 where no_reg = '".$_GET["rg"]."'");
        if (!empty($id_max)) {
        $bangsal = getFromTable("select c.bangsal || ' / ' || e.tdesc ".
                        "from rs00010 as a ".
                        "    join rs00012 as b on a.bangsal_id = b.id ".
                        "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
                        "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
                        "where a.id = '$id_max'");
        }
        $umure = umur($d->umur);
        $umure = explode(" ",$umure);
        $umur = $umure[0]." thn";

        $f = new ReadOnlyForm();
        echo "<table  width='100%' cellspacing=0 cellpadding=0>";
        echo " <tr>";
        echo "  <td valign=top width='33%'>";
        
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
        echo "  </td>";
        echo "  <td align=center valign=top width='33%'>";
        $f = new ReadOnlyForm();
        $f->text("Alamat", "$d->alm_tetap $d->kota_tetap $d->pos_tetap");
        $f->text("Telepon", $d->tlp_tetap);
        $f->text("Tanggal", date("d F Y"));
        $f->text("<nobr>Tipe Pasien</nobr>", $d->tipe_desc);
        $f->text("Umur", $umur);
        $f->execute();
        echo "  </td>";
        echo "  <td valign=top width='33%'>";
        $f = new ReadOnlyForm();
        echo "    <table  width='100%'>";
        echo "      <tr><td class=TBL_BODY>Diagnosa Sementara:</td></tr>";
        echo "      <tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
        echo "    </table>";
        $f->execute();
        echo "  </td>";
        echo " </tr>";
        echo "</table>";
	//Tambahan Relasi Apotik

	$rawatinap=$d->rawat_inap;
	if ($rawatinap == 'I') {
            $rwt='I';
        } else {
            $rwt='Y';
        }

	$SQL12 = "SELECT a.id, b.tc from rs00008 a 
    		  left join rs00001 b on a.item_id = b.tc and tt='RAP'
    		  where a.trans_type='OBM' and a.no_reg='$reg'"; 
	$r = pg_query($con,$SQL12);
	$n = pg_num_rows($r);		    	
	if($n > 0) $d2 = pg_fetch_array($r);
	pg_free_result($r);

	//Tambahan Dokter Peresep
	//start sql nama dokter
	$rr = pg_query($con, "SELECT b.id, b.nama
			FROM rs00008 a
			JOIN rs00017 b ON b.id = a.no_kwitansi
			WHERE a.no_reg = '$reg'");
	
	$nr = pg_num_rows($rr);
	$dr = pg_fetch_object($rr);
	//---------------------------------
	$rr2 = pg_query($con, "SELECT b.id, b.nama
			FROM rs00006 a
			JOIN rs00017 b ON b.nama = a.diagnosa_sementara
			WHERE a.id = '$reg'");
	
	$nr2 = pg_num_rows($rr2);
	$dr2 = pg_fetch_object($rr2);
		
	if($nr > 0){
			$nama_dokter = $dr->id;
	} else {
			$nama_dokter = $dr2->id;
	}
	//end sql nama dokter	
	
	if($n > 0){
						echo "<div align=right><input type=button value=' Edit ' OnClick=\"window.location = './index2.php?p=320RJ&tt=".$_GET[tt]."&rg=".$_GET[rg]."&sub=obat&nama_relasi=".$_GET[nama_relasi]."&jenis_transaksi=".$_GET[jenis_transaksi]."&nama_dokter=".$_GET[nama_dokter]."&id=".$d2->id."&act=edit';\">\n";
					}else {
						$ext = "";
					
   }

	if ($_GET['act'] == "edit") {
					
						echo "<font color='#000000' size='2'> >>Edit Relasi Apotik</font>";


						$f = new Form("actions/320RJ.insert.php", "POST", "NAME=Form2");
						$f->hidden("act","edit");
						$f->hidden("no_reg",$d2["no_reg"]);
					    	$f->hidden("tanggal_reg",$d2["tanggal_reg"]);
						$f->hidden("sub","obat");
						$f->hidden("nama_relasi",$_GET[nama_relasi]);
						$f->hidden("jenis_transaksi",$_GET[jenis_transaksi]);
						$f->hidden("nama_dokter",$_GET[nama_dokter]);
					    	$f->hidden("user_id",$_SESSION[uid]);
						$f->hidden("id",$d2["id"]);
					    
					  
				}else {
					if($n > 0){
						$ext= "disabled";
					}else {
						$ext = "";
					}
				//---------------------------------------------------------------------------------	
					/*$f = new Form("actions/320RJ.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","edit");
					$f->hidden("f_no_reg",$d2->no_reg);
					$f->hidden("sub","obat");
				        $f->hidden("f_user_id",$_SESSION[uid]);
					$f->hidden("tt", $_GET["tt"]);*/
			}

	$f = new Form("actions/320RJ.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new2");
					$f->hidden("rg",$_GET['rg']);
				    	$f->hidden("tanggal_reg",$d2["tanggal_reg"]);
				    	$f->hidden("user_id",$_SESSION[uid]);
					$f->hidden("tt", $_GET["tt"]);
					echo"<br><div align=right>";
					$maxid = getFromTable("select max(id) from rs00008 where no_reg='$reg' and trans_type='OBM'");
					//var_dump($maxid);
					$margin = getFromTable("select item_id from rs00008 where no_reg='$reg' and trans_type='OBM' and id='$maxid'");
					$margint = getFromTable("select jenis_transaksi from rs00008 where no_reg='$reg' and trans_type='OBM'");
				    	$f->PgConn=$con;
					$f->title1("<U>Pilih Relasi Apotik</U>","LEFT");
					$f->selectSQL("nama_relasi", "<I><B><font color='orange'><font size='2'>Nama Relasi</B>", "select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'RAP' and tc != '000' and tc_tipe='".$d->tipe."' and comment='".$rwt."'",$margin ,$ext,$_GET["nama_relasi"],$ext);
					$f->selectSQL("jenis_transaksi", "<I><B><font color='orange'><font size='2'>Jenis Transaksi</B>", "select tc, tdesc from rs00001 where tt = 'TRS' and tc != '000'",$margint ,$ext,$_GET["jenis_transaksi"],$ext);
					$f->selectSQL("nama_dokter", "<I><B><font color='orange'><font size='2'>Nama Dokter</B>", "select id, nama from rs00017 WHERE pangkat LIKE '%DOKTER%' Order By nama Asc ;", $nama_dokter, $ext);
				    	$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
				    	$f->execute();
	
if($n > 0){
        echo '<form action="actions/320RJ_insert.php" >';
        echo '<input type="hidden" name="rg" id="rg" value="'.$_GET['rg'].'" />';
            title("Resep / Obat");
            $t = new BaseTable("100%");
            $t->printTableOpen();
            $t->printTableHeader(Array("Nama Obat", "Jumlah", "Racikan", "Harga Satuan", "Harga Total", "Penjamin (%)","Penjamin (Rp)", "Selisih", ""));

        ?>
            <tbody>
                <tr>
                    <td>
                        <input type="hidden" name="rs00008_id" id="rs00008_id" value="">
                        <input type="hidden" name="is_return" id="is_return" value="0">
                        <input type="hidden" name="obat_id" id="obat_id" value="">
                        <input type="hidden" name="jasa" id="jasa" value="">
                        <input type="hidden" name="qty_awal" id="qty_awal" value="" >
                        <input type="hidden" name="harga_awal" id="harga_awal" value="" >
                        <input type="hidden" name="jumlah_awal" id="jumlah_awal" value="" >
                        <input type="hidden" name="penjamin_awal" id="penjamin_awal" value="" >
                        <input type="text" name="obat_nama" id="obat_nama" size="40" value="">
                    </td>
                    <td><input type="text" name="qty" id="qty" size="3" value="" style="text-align: right;"> (<span id="stok"></span>)</td>
                    <td  style="text-align: center;">
						<select name="is_racikan" id="is_racikan">
							<option value="0">Resep</option>
							<option value="1">Racikan</option>
						</select>
					</td>
                    <td><input type="text" name="harga" id="harga" size="10" value="" style="text-align: right;" disabled></td>
		    <!--<td><input type="text" name="harga_car_drs" id="harga_car_drs" size="10" value="" style="text-align: right;"></td>-->
                    <td><input type="text" name="jumlah" id="jumlah" size="10" value="" style="text-align: right;" disabled></td>
                    <td><input type="checkbox" id="is_penjamin" ><input type="text" name="penjamin_persen" id="penjamin_persen" size="3" value="0" style="text-align: right;"></td>
                    <td>
                        <input type="text" name="penjamin" id="penjamin" size="10" value="0" style="text-align: right;">
                    </td>
                    <td><input type="text" name="selisih" id="selisih" size="10" value="0" style="text-align: right;" disabled></td>
                    <td><input type="button" id="save-obat" value=" OK " /></td>
                </tr>
            </tbody>
<?php
            $t->printTableClose(); 
        echo '</form>';
        
        echo '<form id="list_obat_created" method="GET" action="includes/cetak.rincian_obat_selected.php" target="_blank">';
        echo '<input type="hidden" name="rg" value="'.$_GET['rg'].'" /> ';
        echo '<div id=list_pemakaian_obat></div>';
        echo '</form>';
	echo '<form id="list_obat_created" method="GET" action="includes/cetak.rincian_obat_selectedcopyresep.php" target="_blank">';
        echo '<input type="hidden" name="rg" value="'.$_GET['rg'].'" /> ';
        echo '<div id=list_pemakaian_obat></div>';
        echo '</form>';
           
}

// ----------------- End Form Input Pelayanan Obat Pasien ----------------------

//var_dump($relasi); die;
if(empty($reg))
echo "<b>Note: Masukan No.REG atau Nama untuk memilih Pasien</b>";

$result = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00016.harga,rs00016.harga_car_drs,
rs00016.harga_car_rsrj,rs00016.harga_car_rsri,rs00016.harga_inhealth_drs,rs00016.harga_inhealth_rs,
rs00016.harga_jam_ri,rs00016.harga_jam_rj,rs00016.harga_kry_kelinti,rs00016.harga_kry_kelbesar,
rs00016.harga_kry_kelgratisri,rs00016.harga_kry_kelrespoli,rs00016.harga_kry_kel,
rs00016.harga_kry_kelgratisrj,rs00016.harga_umum_ri,rs00016.harga_umum_rj,
rs00016.harga_umum_ikutrekening,rs00016.harga_gratis_rj,rs00016.harga_gratis_ri,
rs00016.harga_pen_bebas,rs00016.harga_nempil,rs00016.harga_nempil_apt,margin_apotik.tuslah_car_drs,
margin_apotik.tuslah_car_rsrj,margin_apotik.tuslah_car_rsri,margin_apotik.tuslah_inhealth_drs,
margin_apotik.tuslah_inhealth_rs,margin_apotik.tuslah_jam_ri,margin_apotik.tuslah_jam_rj,
margin_apotik.tuslah_kry_kelinti,margin_apotik.tuslah_kry_kelbesar,margin_apotik.tuslah_kry_kelgratisri,
margin_apotik.tuslah_kry_kelrespoli,margin_apotik.tuslah_kry_kel,margin_apotik.tuslah_kry_kelgratisrj,
margin_apotik.tuslah_umum_ri,margin_apotik.tuslah_umum_rj,margin_apotik.tuslah_umum_ikutrekening,
margin_apotik.tuslah_gratis_rj,margin_apotik.tuslah_gratis_ri,margin_apotik.tuslah_pen_bebas,
margin_apotik.tuslah_nempil,margin_apotik.tuslah_nempil_apt,
rs00001.comment AS jasa, rs00016a.qty_ri AS stok
    FROM rs00015 
    INNER JOIN rs00001 ON rs00015.kategori_id = rs00001.tc 
    INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id
    INNER JOIN rs00016a ON rs00015.id = rs00016a.obat_id
    INNER JOIN margin_apotik on rs00015.kategori_id = margin_apotik.kategori_id 
    WHERE rs00001.tt = 'GOB' and rs00015.kategori_id = margin_apotik.kategori_id and status='1'
    ORDER BY rs00015.obat ASC");

?>
<script>
    $(function() {
		
        var rg = $('#rg').val();
        $('#list_pemakaian_obat').load('actions/320RJ_insert.php?rg='+rg);
        
        var data = [
            <?php 
            while ($row = pg_fetch_array($result))
            {
                $id = $row["id"];
                if($margin=='001'){$harga = (int)$row["harga_car_drs"];
				   $jasa = (int)$row["tuslah_car_drs"];}
		else if($margin=='002'){
				   $harga = (int)$row["harga_car_rsrj"];
				   $jasa = (int)$row["tuslah_car_rsrj"];}
		else if($margin=='003'){
				   $harga = (int)$row["harga_car_rsri"];
                                   $jasa = (int)$row["tuslah_car_rsri"];}
		else if($margin=='004'){
				   $harga = (int)$row["harga_inhealth_drs"];
				   $jasa = (int)$row["tuslah_inhealth_drs"];}
		else if($margin=='005'){
				   $harga = (int)$row["harga_inhealth_rs"];
                                   $jasa = (int)$row["tuslah_inhealth_rs"];}
		else if($margin=='006'){
				   $harga = (int)$row["harga_jam_ri"];
				   $jasa = (int)$row["tuslah_jam_ri"];}
		else if($margin=='007'){
			           $harga = (int)$row["harga_jam_rj"];
                                   $jasa = (int)$row["tuslah_jam_rj"];}
		else if($margin=='008'){
				   $harga = (int)$row["harga_kry_kelinti"];
                                   $jasa = (int)$row["tuslah_kry_kelinti"];}
		else if($margin=='009'){
				   $harga = (int)$row["harga_kry_kelbesar"];
				   $jasa = (int)$row["tuslah_kry_kelbesar"];}
		else if($margin=='010'){
				   $harga = (int)$row["harga_kry_kelgratisri"];
				   $jasa = (int)$row["tuslah_kry_kelgratisri"];}
		else if($margin=='011'){
				   $harga = (int)$row["harga_kry_kelrespoli"];
				   $jasa = (int)$row["tuslah_kry_kelrespoli"];}
		else if($margin=='012'){
				   $harga = (int)$row["harga_kry_kel"];
				   $jasa = (int)$row["tuslah_kry_kel"];}
		else if($margin=='013'){
				   $harga = (int)$row["harga_kry_kelgratisrj"];
				   $jasa = (int)$row["tuslah_kry_kelgratisrj"];}
		else if($margin=='014'){
				   $harga = (int)$row["harga_umum_ri"];
				   $jasa = (int)$row["tuslah_umum_ri"];}
		else if($margin=='015'){
				   $harga = (int)$row["harga_umum_rj"];
				   $jasa = (int)$row["tuslah_umum_rj"];}
		else if($margin=='016'){
				   $harga = (int)$row["harga_umum_ikutrekening"];
				   $jasa = (int)$row["tuslah_umum_ikutrekening"];}
		else if($margin=='017'){
				   $harga = (int)$row["harga_gratis_rj"];
				   $jasa = (int)$row["tuslah_gratis_rj"];}
		else if($margin=='018'){
				   $harga = (int)$row["harga_gratis_ri"];
				   $jasa = (int)$row["tuslah_gratis_ri"];}
		else if($margin=='019'){
				   $harga = (int)$row["harga_pen_bebas"];
				   $jasa = (int)$row["tuslah_pen_bebas"];}
		else if($margin=='020'){
				   $harga = (int)$row["harga_nempil"];
				   $jasa = (int)$row["tuslah_nempil"];}
		else{
				   $harga = (int)$row["harga_nempil_apt"];
				   $jasa = (int)$row["tuslah_nempil_apt"];}
                $stok = $row["stok"];
                $obat = str_replace("'","/",$row["obat"]);
                
                echo "{";
                echo "id: ".$id .", ";
                echo "value: '".$obat ."', ";
                echo "jasa: '".$jasa ."', ";
                echo "harga: '".$harga ."',";
                echo "stok: '".$stok ."'";
                echo "},";
            }
            ?>
                        ""
        ];

        $('#is_penjamin').click(function(){
            valJumlah = $('#jumlah').val();
            $('#penjamin_persen').val('100');
            $('#penjamin').val(valJumlah);
            $('#selisih').val(0);
        });


        $( "#obat_nama" ).autocomplete({
            source: data,
            messages: {
			noResults: "",
			results: function( amount ) {
				
			}
		},
            minLength: 3,
            select: function (event, ui) {
                $('#obat_id').val('');
                $('#qty').val('');
                $("#jasa").val('');
                $("#harga").val('');
                $("#penjamin").val('');
                $("#selisih").val('');
                $("#stok").empty();
                var obatId = ui.item.id;
                var obatNama = ui.item.value;
                var obatSatuan = ui.item.satuan;
                var obatJasa = ui.item.jasa;
                var obatHarga = ui.item.harga;
                var obatStok = ui.item.stok;
                
                if(parseInt(obatStok) < 1){
                    alert('stok kosong !');
                    return false;
                }
                
                $('#obat_nama').val('obatNama');
                $('#obat_id').val(obatId);
                $("#jasa").val(parseInt(obatJasa));
                $("#harga").val(obatHarga);
                $("#stok").html(obatStok);
                $('#penjamin').val(0);
                $('#is_penjamin').attr('checked', false);
                $('#is_racikan').attr('checked', false);                
            }
        });
        
        $("#qty").keyup( function(){
            var obatQty = parseFloat($('#qty').val());
            var obatJasa = parseFloat($('#jasa').val());
            var obatHarga = parseFloat($('#harga').val());   
            var obatPenjamin = parseFloat($('#penjamin').val());   
            jumlah = obatQty*obatHarga+obatJasa;
            selisih = jumlah-obatPenjamin;
            $('#jumlah').val(jumlah);
            $('#selisih').val(selisih);
        });
        
        $("#penjamin_persen").keyup( function(){
            var obatQty = parseFloat($('#qty').val());
            var obatJasa = parseFloat($('#jasa').val());
            var obatHarga = parseFloat($('#harga').val());   
            var obatPenjaminPersen = parseFloat($('#penjamin_persen').val());   
            
            jumlah = obatQty*obatHarga+obatJasa;
            obatPenjamin = (obatPenjaminPersen*jumlah)/100;
            $('#penjamin').val(obatPenjamin);
            
            selisih = jumlah-obatPenjamin;
            $('#jumlah').val(jumlah);
            $('#selisih').val(selisih);
        });

        $("#penjamin").keyup( function(){
            var obatQty = $('#qty').val();
            var obatJasa = $('#jasa').val();
            var obatHarga = $('#harga').val();   
            var obatPenjamin = $('#penjamin').val();   
            jumlah = (parseFloat(obatQty)*parseFloat(obatHarga))+parseFloat(obatJasa);
            selisih = (parseFloat(obatQty)*parseFloat(obatHarga))+parseFloat(obatJasa)-parseFloat(obatPenjamin);
            $('#jumlah').val(jumlah);
            $('#selisih').val(selisih);
        });
                
        
        $('#save-obat').click(function(){
            valrs00008Id	= $('#rs00008_id').val();
            valRg		= $('#rg').val();
            valObatId		= $('#obat_id').val();
            valIsRacikan	= $('#is_racikan').val();
            valQty		= $('#qty').val();
            valQtyAwal		= $('#qty_awal').val();
            valHarga		= $('#harga').val();
            valJasa             = $('#jasa').val();
            valJumlah           = $('#jumlah').val();
            valHargaAwal        = $('#harga_awal').val();
            valJumlahAwal       = $('#jumlah_awal').val();
            valPenjamin		= $('#penjamin').val();
            valPenjaminAwal	= $('#penjamin_awal').val();
            valIsReturn		= $('#is_return').val();
            
            if(valQty <= 0){
                alert('Jumlah obat harus diisi !');
                return false;
            }
            
            $.post('actions/320RJ_insert.php?rg='+valRg,
                        {
                            rg: valRg,
                            obat_id: valObatId,
                            is_racikan: valIsRacikan,
                            qty: valQty,
                            qty_awal: valQtyAwal,
                            harga: valHarga,
                            jasa: valJasa,
                            jumlah: valJumlah,
                            harga_awal: valHargaAwal,
                            jumlah_awal: valJumlahAwal,
                            penjamin: valPenjamin,
                            penjamin_awal: valPenjaminAwal,
                            rs00008_id: valrs00008Id,
                            is_return: valIsReturn
                            
                        }
                    ).success(function(data){ 
                                        $('#rs00008_id').val('');
                                        $('#is_return').val('');
                                        $('#obat_id').val('');
                                        $('#obat_nama').val('');
                                        $("#qty").val('');
                                        $("#qty_awal").val('');
                                        $("#is_racikan").val(0);
                                        $("#harga").val('');
                                        $("#jasa").val(0);
                                        $("#jumlah").val(0);
                                        $("#penjamin").val(0);
                                        $("#penjamin_persen").val(0);
                                        $("#penjamin_awal").val(0);
                                        $("#selisih").val(0);
                                        $('#list_pemakaian_obat').empty();
                                        $('#list_pemakaian_obat').html(data);
                                        $('#is_penjamin').attr('checked', false);
					$('#is_racikan').attr('checked', false);
                                        $('#save-obat').val('OK');
                                     });            
        })

    });
    
    function edit_data_obat(id){
        var obatId = $('#obat_id_'+id).val();
        var obatNama = $('#obat_nama_'+id).text();
        var qty = $('#qty_'+id).text();
        var qtyAwal = $('#qty_'+id).text();
        var harga = parseFloat($('#harga_'+id).val().replace('.', ''));//harga
        var penjamin = parseFloat($('#penjamin_'+id).text().replace('.', ''));
        var jasa = parseFloat($('#jasa_'+id).val().replace('.', ''));
        var selisih = parseFloat($('#selisih_'+id).text().replace('.', ''));
        var tipe = $('#tipe_'+id).val();
        
        $('#save-obat').val('Update');
        
        jumlah = (parseFloat(qty)*parseFloat(harga))+parseFloat(jasa);
   
        $('#rs00008_id').val( id );
        $('#obat_id').val( obatId );
        $('#obat_nama').val( obatNama );
        $('#qty').val( parseFloat(qty) );
        $('#qty_awal').val( parseFloat(qty) );
	$('#harga').val( harga );
        $('#jasa').val( jasa );
        $('#penjamin').val( penjamin );
        $('#jumlah').val(jumlah);
        $('#selisih').val(selisih);

		if(tipe == 'RCK'){
			$('#is_racikan').val(1);
		}else{
			$('#is_racikan').val(0);
		}
    }

    function return_data_obat(id){
        var obatId = $('#obat_id_'+id).val();
        var obatNama = $('#obat_nama_'+id).text();
        var qty = $('#qty_'+id).text();
        var harga = parseFloat($('#hargareturn_'+id).val().replace('.', ''));
        var penjamin = parseFloat($('#penjamin_'+id).text().replace('.', ''));
        var jasa = parseFloat($('#jasareturn_'+id).val().replace('.', ''));
        var tagihan = parseFloat($('#tagihan_'+id).val().replace('.', ''));
        var selisih = parseFloat($('#selisih_'+id).text().replace('.', ''));
        var tipe = $('#tipe_'+id).val();
        
        $('#save-obat').val('Return');
           
        $('#rs00008_id').val( id );
        $('#is_return').val(1);
        $('#obat_id').val( obatId );
        $('#obat_nama').val( obatNama );
        $('#qty').val( parseFloat(qty) );
        $('#qty_awal').val( parseFloat(qty) );
        $('#harga').val( harga );
        $('#harga_awal').val(tagihan);
        $('#jasa').val( jasa );
//        $('#penjamin').val(penjamin);
        $('#penjamin').val(0);
        $('#penjamin_awal').val( penjamin );
        $('#jumlah').val(tagihan);
        $('#jumlah_awal').val(tagihan);
        $('#selisih').val(selisih);

		$('#is_penjamin').attr('checked', false);

		if(tipe == 'RCK'){
			$('#is_racikan').val(1);
		}else{
			$('#is_racikan').val(0);
		}
    }
    
    function delete_data_obat(id){
         var valRg   = $('#rg').val();
         var obatId  = $('#obat_id_'+id).val();
         var qty     = $('#qty_'+id).text();
         
         $.post('actions/320RJ_insert.php?rg='+valRg+'&del=true',
                        {
                            obat_id: obatId,
                            qty: qty,
                            rs00008_id: id
                        }
                    ).success(function(data){ 
                                        $('#list_pemakaian_obat').empty();
                                        $('#list_pemakaian_obat').html(data);
										$('#list_pemakaian_obat').load('actions/320RJ_insert.php?rg=<?php echo $_GET["rg"]?>');
                                     });   
    }
    function delete_data_obat_return(id){
         var valRg   = $('#rg').val();
         var obatId  = $('#obat_id_'+id).val();
         var qty     = $('#qty_'+id).text();
         
         $.post('actions/320RJ_insert.php?rg='+valRg+'&delreturn=true',
                        {
                            rs00008_return_id: id
                        }
                    ).success(function(data){ 
                                        $('#list_pemakaian_obat').empty();
                                        $('#list_pemakaian_obat').html(data);
					$('#list_pemakaian_obat').load('actions/320RJ_insert.php?rg=<?php echo $_GET["rg"]?>');
                                     });   
    }
    
    function cetakkwitansi1(tag) {
        sWin = window.open('includes/cetak.rincian_obat_apotek.php?rg=<?php echo $_GET['rg']?>&kas=<?php echo $_GET['rg']?>', 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
        sWin.focus();
    }
</script>
<?
}
