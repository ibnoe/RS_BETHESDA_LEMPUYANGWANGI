<?php // Nugraha, Fri Apr 30 14:43:48 WIT 2004

$PID = "610";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

if (empty($_GET[u]) && empty($_GET[s]) && empty($_GET[b]) && empty($_GET[r])
   && empty($_GET[mPEG]) && empty($_GET[mJAB])) {

   header("Location: /index2.php?p=$PID&mUNSUR=".$_GET[u]."&mSUBUNSUR=".$_GET[s]."&mBIDANG=".$_GET[b]
          ."&mRINCIAN=".$_GET[r]."&mPEG=".$_GET[mPEG]."&mJAB=".$_GET[mJAB]);
   exit();
} else {

   $q = pg_query("select a.nip,a.nama, e.tdesc as agama, to_char(tanggal_lahir,'DD MON YYYY') as lahir, ".
                "b.tdesc as jabatan, c.tdesc as golongan, d.nama_jenjang_pangkat, d.jjd_id ".
                ", a.id as dummy ".
	        "from rs00017 a ".
	            "left outer join rs00027 d ON a.rs00027_id = d.id  ".
	            "left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM' ".
	            "left outer join rs00001 b ON d.jjd_id = b.tc and b.tt='JJD' ".
	            "left outer join rs00001 c ON d.gol_ruang_id = c.tc and c.tt='GRP' ".
		"where a.jabatan_medis_fungsional_id='".$_GET["mJAB"]."' ");
   $qr = pg_fetch_object($q);


   $utama = getFromTable("select akkm_utama from rs00029 where rs00017_id = ".$_GET[e]);
   $penunjang = getFromTable("select akkm_penunjang from rs00029 where rs00017_id = ".$_GET[e]);

   if (empty($utama)) {
      $utama = 0;
   }
   if (empty($penunjang)) {
      $penunjang = 0;
   }

   $kredit = getFromTable("select kredit from rs00026 where id_rincian='".$_GET[r]."' and jjd_id=$qr->jjd_id");

   $rs00025_id = $_GET[r];
   $rs00027_id = getFromTable(
            "select rs00027_id ".
	    "from rs00017  ".
	    "where id='".$_GET["e"]."' ");
   $rs00017_id = $_GET[e];



   if ($_GET[u] == "001") {
      $utama = $utama + $kredit;
   } else {
      $penunjang = $penunjang + $kredit;
   }

   // insert into table 29

   $SQL = "insert into rs00029 (id, akkm_utama, akkm_penunjang, rs00027_id, rs00017_id) ".
          "values (nextval('rs00029_seq'),$utama,$penunjang,$rs00027_id,$rs00017_id) ";


   // insert into table 30


   $SQL2 = "insert into rs00030 (id, rs00025_id, rs00027_id, rs00017_id) ".
          "values (nextval('rs00030_seq'),$rs00025_id,$rs00027_id,$rs00017_id) ";

}




pg_query($con, $SQL);
pg_query($con, $SQL2);

header("Location: /index2.php?p=$PID&mUNSUR=".$_GET[u]."&mSUBUNSUR=".$_GET[s]."&mBIDANG=".$_GET[b]
          ."&mRINCIAN=".$_GET[r]."&mPEG=".$_GET[mPEG]."&mJAB=".$_GET[mJAB]."&e=".$_GET[e]);

exit;

?>
