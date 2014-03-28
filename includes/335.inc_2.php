<?php
//Merubah tampilan sesuai dengan request di RSUD Prof. Dr. Moh. ALi Hanafiah SM

    $t = new Form("");
    //$t->subtitle("Data Medis Terakhir");
    $t->execute();

    $r = pg_query($con,
        "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, ".
        "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, ".
        "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, ".
        "    e.alm_tetap, e.kota_tetap, e.umur, e.pos_tetap, e.tlp_tetap, ".
        "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
        "    c.tdesc AS penjamin, a.no_jaminan,a.no_asuransi ,a.rujukan, a.rujukan_rs_id, ".
        "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
        "    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, ".
        "        CASE ".
        "            WHEN a.rawat_inap = 'I' THEN 'Rawat Inap' ".
        "            WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan' ".
        "            ELSE 'IGD' ".
        "        END AS rawat, ".
        "        age(a.tanggal_reg , e.tgl_lahir ) AS umur, ".
	"	case when a.rujukan = 'Y' then 'Rujukan' else 'Non-Rujukan' end as datang ".
	"    , i.tdesc as poli,e.pangkat_gol,e.nrp_nip,e.kesatuan ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc AND h.tt = 'JDP' ".
        "   left join rs00001 i on i.tc_poli = a.poli ".
        "WHERE a.id = '$reg'  ");
		//"WHERE a.id = '$reg'");
     

    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='100%'>";
    $alamat = $d->alm_tetap." ".$d->kota_tetap;

    // ambil bangsal
    $id_min = getFromTable("select min(id) from rs00010 where no_reg = '".$_GET["rg"]."'");
    $id_max = getFromTable("select max(id) from rs00010 where no_reg = lpad(".$_GET["rg"].",10,'0')");
    if (!empty($id_max)) {
    $bangsal = getFromTable("select c.bangsal || ' / ' || e.tdesc ".
                       "from rs00010 as a ".
                       "    join rs00012 as b on a.bangsal_id = b.id ".
                       "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
                       "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
                       "where a.id = '$id_max'");
    }
$bangsal = getFromTable("select d.bangsal || ' / ' || c.bangsal || ' / ' || e.tdesc || ' / ' || b.bangsal ".
                       "from rs00010 as a ".
                       "    join rs00012 as b on a.bangsal_id = b.id ".
                       "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
                       "    join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
                       "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
                       "where a.id = '$id_min'");


    $f = new ReadOnlyForm();
    echo "<hr>";
    ?>
<table border="0" width="100%">
    <tr>
        <td class="TITLE_SIM3" width="20%" align="left"><b>Alamat</b></td>
        <td class="TITLE_SIM3" width="35%" align="left"><b>: <?echo $alamat;?></b></td>
        
        <td class="TITLE_SIM3" width="15%" align="right"><b>Pasien Dari</b></td>
        <td class="TITLE_SIM3" width="35%" align="left"><b>: <?echo $d->rawat;?></b></td>
    </tr>
    <tr>
        <td class="TITLE_SIM3" align="left"><b>No. MR / No. Reg</b></td>
        <td class="TITLE_SIM3"  align="left"><b>: <?echo $d->mr_no;?> / <?echo formatRegNo($d->id). " - " . getFromTable("select count(mr_no) from rs00006 where mr_no = '$d->mr_no'");?></b></td>
    

       <?
     if ($d->rawat == "Rawat Jalan") {
       ?>
       
            <td class="TITLE_SIM3" width="25%" align="right"><b>Poli</b></td>
            <td class="TITLE_SIM3" width="25%" align="left"><b>: <?echo $d->poli;?></b></td>
         
       <?
    } else {
       ?>
        
            <td class="TITLE_SIM3" align="right"><b>Bangsal</b></td>
            <td class="TITLE_SIM3" align="left"><b>: <?echo $bangsal;?></b></td>
       
        <?
    }
    
    ?>
    </tr>
    <tr>
        <td class="TITLE_SIM3" width="20%" align="left"><b>Tipe Pasien</b></td>
        <td class="TITLE_SIM3" width="35%" align="left"><b>: <?echo $d->tipe_desc;?></b></td>
        
        <td class="TITLE_SIM3" width="15%" align="right"><b>&nbsp;</b></td>
        <td class="TITLE_SIM3" width="35%" align="left"><b>&nbsp;</b></td>
    </tr>
</table>
    <?
	$f->execute();
    echo "</td><td align=center valign=top width='33%'>";
    echo "</td><td valign=top width='33%'>";
    echo "</td></tr></table>";
    echo "<hr>";
	?>