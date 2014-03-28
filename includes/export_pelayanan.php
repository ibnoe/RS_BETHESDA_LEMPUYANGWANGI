 <?	
  
 $PID = "export_pelayanan";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 	  
		
	if(!$GLOBALS['print']){
		title_excel("export_pelayanan");	
	}else {
		
	}


	
$SQL = "select tc as id, tt as hierarchy, tdesc as is_group
    from rs00001
where tt='LYN' ";

$SQLc = "select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034 b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,9 ) = '032001001') as harga_atas,
(select c.harga from rs00034 c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,9) = '032001001') as harga_bawah,
(select d.harga from rs00034 d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,9) = '032001001') + (select e.harga from rs00034 e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,9) = '032001001') as total,rs00021_id,id_rincian
from rs00034 a
where substring(a.hierarchy,1,9) = '032001001' and is_group='N' and a.sumber_pendapatan_id='006'
order by a.layanan";

$SQLa = "select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,12 ) = '033002002003') as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002002003') as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002002003') + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002002003') as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002002003' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002002001' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002002001' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002002001' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002002001' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002002001' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002002002' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002002002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002002002' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002002002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002002002' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002002004' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002002004' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002002004' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002002004' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002002004' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002002005' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002002005' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002002005' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002002005' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002002005' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
--======================================033002003
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002003005' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002003005' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002003005' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002003005' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002003005' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,12 ) = '033002003003') as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002003003') as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002003003') + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002003003') as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002003003' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002003001' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002003001' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002003001' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002003001' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002003001' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002003002' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002003002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002003002' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002003002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002003002' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002003004' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002003004' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002003004' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002003004' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002003004' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
--=========================================================033002004
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002004005' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002004005' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002004005' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002004005' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002004005' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002004004' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002004004' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002004004' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002004004' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002004004' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002004003' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002004003' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002004003' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002004003' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002004003' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002004002' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002004002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002004002' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002004002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002004002' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002004001' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002004001' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002004001' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002004001' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002004001' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
--=======================================================================
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002005005' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002005005' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002005005' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002005005' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002005005' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002005004' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002005004' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002005004' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002005004' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002005004' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002005003' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002005003' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002005003' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002005003' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002005003' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002005002' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002005002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002005002' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002005002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002005002' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
union
select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034b b where b.sumber_pendapatan_id='006' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id and b.layanan=a.layanan and substring(b.hierarchy,1,12) = '033002005001' and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034b c where c.sumber_pendapatan_id='002' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id and c.layanan=a.layanan and substring(c.hierarchy,1,12) = '033002005001' and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034b d where d.sumber_pendapatan_id='006' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id and d.layanan=a.layanan and substring(d.hierarchy,1,12) = '033002005001' and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034b e where e.sumber_pendapatan_id='002' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id and e.layanan=a.layanan and substring(e.hierarchy,1,12) = '033002005001' and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034b a
where substring(a.hierarchy,1,12) = '033002005001' and is_group='N' and a.sumber_pendapatan_id='006'
group by a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga, harga_atas,harga_bawah,total,rs00021_id,id_rincian
order by hierarchy ";

$SQLD = "select a.id, a.hierarchy,a.is_group,a.klasifikasi_tarif_id,a.satuan_id,a.sumber_pendapatan_id,a.golongan_tindakan_id,a.tipe_pasien_id,
a.layanan,a.harga,
(select b.harga from rs00034 b where b.sumber_pendapatan_id='006' and b.layanan=a.layanan and substring(b.hierarchy,1,6) = '041001' and b.golongan_tindakan_id=a.golongan_tindakan_id and b.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_atas,
(select c.harga from rs00034 c where c.sumber_pendapatan_id='002' and c.layanan=a.layanan and substring(c.hierarchy,1,6) = '041001' and c.golongan_tindakan_id=a.golongan_tindakan_id and c.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as harga_bawah,
(select d.harga from rs00034 d where d.sumber_pendapatan_id='006' and d.layanan=a.layanan and substring(d.hierarchy,1,6) = '041001' and d.golongan_tindakan_id=a.golongan_tindakan_id and d.klasifikasi_tarif_id=a.klasifikasi_tarif_id) + (select e.harga from rs00034 e where e.sumber_pendapatan_id='002' and e.layanan=a.layanan and substring(e.hierarchy,1,6) = '041001' and e.golongan_tindakan_id=a.golongan_tindakan_id and e.klasifikasi_tarif_id=a.klasifikasi_tarif_id) as total,rs00021_id,id_rincian
from rs00034 a
where substring(a.hierarchy,1,6) = '041001' and is_group='N' and a.sumber_pendapatan_id='002'
order by a.layanan";

$SQLa = "SELECT * from rs00034 a
where substring(a.hierarchy,1,6) in  ('031002','031003','031004','031005') and is_group='N'
order by a.layanan";

        $r1 = pg_query($con,$SQL);
        $n1 = pg_num_rows($r1);
					    
        $max_row= 9999999 ;
        $mulai = $HTTP_GET_VARS["rec"] ;	
        if (!$mulai){$mulai=1;}   

?>
<table align="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" WIDTH='10%' align="center">id</td>
        <td class="TBL_HEAD" WIDTH='20%' align="center">hierarchy</td>
        <td class="TBL_HEAD" WIDTH='5%' align="center">is_group</td>
        <td class="TBL_HEAD" WIDTH='5%' align="center">klasifikasi_tarif_id</td>
        <td class="TBL_HEAD" WIDTH='5%' align="center">satuan_id</td>
        <td class="TBL_HEAD" WIDTH='5%' align="center">sumber_pendapatan_id</td>
		<td class="TBL_HEAD" WIDTH='5%' align="center">golongan_tindakan_id</td>
        <td class="TBL_HEAD" WIDTH='5%' align="center">tipe_pasien_id</td>
        <td class="TBL_HEAD" WIDTH='70%' align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;layanan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td class="TBL_HEAD" WIDTH='10%' align="center">harga_atas</td>
        <td class="TBL_HEAD" WIDTH='10%' align="center">harga_bawah</td>
		<td class="TBL_HEAD" WIDTH='10%' align="center">total</td>
        <td class="TBL_HEAD" WIDTH='10%' align="center">rs00021_id</td>
        <td class="TBL_HEAD" WIDTH='10%' align="center">id_rincian</td>
    </tr>
		<?				
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while ($row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
    <tr>
      <td class="TBL_BODY" align="left"><?=$row1["id"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["hierarchy"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["is_group"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["klasifikasi_tarif_id"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["satuan_id"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["sumber_pendapatan_id"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["golongan_tindakan_id"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["tipe_pasien_id"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["layanan"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["harga_atas"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["harga_bawah"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["total"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["rs00021_id"] ?></td>
	  <td class="TBL_BODY" align="left"><?=$row1["id_rincian"] ?></td>
    </tr>
			
	<?;$j++;					
				}
				$i++;	
			} 
			?>

</table>
<p>&nbsp;</p>