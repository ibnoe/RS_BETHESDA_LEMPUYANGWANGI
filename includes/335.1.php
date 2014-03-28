<?php // Nugraha, Sat May  1 09:58:26 WIT 2004
      // sfdn, 01-06-2004

echo "<hr noshade size=1>";
title("Identitas Lengkap");
echo "<br>";

$r = pg_query($con, 
        "SELECT a.id, to_char(a.tanggal_reg,'DD-Mon-YYYY') AS tanggal_reg, a.waktu_reg, ".
        "    a.mr_no, e.nama, e.nama_keluarga, e.keluarga_dekat, to_char(e.tgl_lahir,'DD-Mon-YYYY') AS tgl_lahir, ".
        "    e.tmp_lahir, e.no_ktp, e.kesatuan, e.nama_ibu, e.nama_ayah, e.jenis_kelamin, f.tdesc AS agama, ".
        "    e.kota_keluarga, e.pos_keluarga, e.tlp_keluarga, ".
        "    e.alm_keluarga, e.pekerjaan, e.alm_tetap, e.pangkat_gol, e.kota_tetap, e.pos_tetap, e.tlp_tetap, ".
        "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
        "    c.tdesc AS penjamin, a.no_jaminan, a.rujukan, a.rujukan_rs_id, ".
        "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
        "    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, ".
        "        CASE ".
        "            WHEN a.rawat_inap = 'Y' THEN 'Rawat Inap'  ".
        "            WHEN a.rawat_inap = 'N' THEN 'Rawat Jalan' ".
        "            ELSE 'IGD' ".
        "        END AS rawatan, ".
        "        age(a.tanggal_reg , e.tgl_lahir ) AS umur ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        //"WHERE a.id = lpad('$reg',10,'0')");
		"WHERE a.id = '$reg'");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);
echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='50%'>";
$umure = umur($d->umur);
$umure = explode(" ",$umure);
$umur = $umure[0]." tahun";
$f = new ReadOnlyForm();
$f->text("Nama", $d->nama);
$f->text("Jenis Kelamin", $d->jenis_kelamin == "L" ? "Laki-Laki" : "Perempuan");
$f->text("Tempat Lahir", $d->tmp_lahir);
$f->text("Tanggal Lahir", $d->tgl_lahir);
$f->text("Umur", $d->umur);
$f->text("Agama", $d->agama);
$f->text("Alamat", $d->alm_tetap);
$f->text("Kota", $d->kota_tetap);
$f->text("Kode Pos", $d->pos_tetap);
$f->text("Telepon", $d->tlp_tetap);    
$f->text("Nomor KTP/SIM/KTA", $d->no_ktp	);
$f->text("Pangkat/Golongan/NRP/NIP ", $d->pangkat_gol);
$f->text("Kesatuan/Instansi ", $d->kesatuan	);
//$f->selectSQL("f_agama_id", "Agama","select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'","");
//$f->selectArray("f_status_nikah", "Status Pernikahan", Array("1" => "Blm Menikah", "2" => "Menikah", "3" => "Janda", "4" => "Duda"),"");
//$f->selectArray("f_gol_darah", "Golongan Darah", Array("-" => "-","A" => "A", "B" => "B", "AB" => "AB", "O" => "O"),"");  
//$f->selectArray("f_resus_faktor", "Resus Faktor", Array("0" => "-","1" => "Negatif", "2" => "Positif"),"");
$f->execute();

echo "</td><td align=left valign=top width='50%'>";
$f = new ReadOnlyForm();
$f->text("Nama Keluarga ", $d->nama_keluarga);
$f->text("Nama Ibu ", $d->nama_ibu);
$f->text("Nama Ayah ", $d->nama_ayah);
$f->text("Pekerjaan ", $d->pekerjaan);
//$f->subtitle("Alamat Tetap");
$f->text("Alamat ", $d->alm_tetap);
$f->text("Kota ", $d->kota_tetap);
$f->text("Kode Pos ", $d->pos_tetap);
$f->text("Telepon" , $d->tlp_tetap);
//$f->subtitle("Keluarga Dekat");
$f->text("Keluarga/Kerabat Dekat " , $d->keluarga_dekat);
$f->text("Alamat" , $d->alm_keluarga);
$f->text("Kota " , $d->kota_keluarga);
$f->text("Kode Pos " , $d->pos_keluarga);
$f->text("Telepon " , $d->tlp_keluarga);
$f->execute();
echo "</td></tr></table>";
?>
