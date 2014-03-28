<?php // Nugraha, Sat May  1 09:58:26 WIT 2004
      // sfdn, 01-06-2004
      // sfdn, 24-12-2006
      // sfdn, 25-12-2006


session_start();

$jns_kasir = array(
	"rj"=>"RAWAT JALAN", 
	"ri"=>"RAWAT INAP",
	"igd"=>"IGD",
) ;
$kasirnya = $_GET["kas"] ;

$PID = "888";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$r = pg_query($con, "
select b.mr_no, a.no_reg, upper(b.nama)as nama, 
to_char(a.ts_check_in,'DD MON YYYY HH24:MI:SS') as tgl_masuk,g.tdesc, 
b.alm_tetap,f.bangsal || ' / ' || e.bangsal|| ' / ' || d.bangsal as bangsal, 
case when c.status = 'P' then 'Sudah Keluar' else 'Masih Dirawat' end as status 
from rs00010 as a 
join rs00006 as c on a.no_reg = c.id 
join rs00002 as b on c.mr_no = b.mr_no 
join rs00012 as d on a.bangsal_id = d.id 
join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' 
join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' 
left join rs00001 g on g.tc = b.tipe_pasien and g.tt='JEP' 
where a.ts_calc_stop is null and a.no_reg = '".$_GET[rg]."'
");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&kas=ri'>".icon("back","Kembali")."</a></DIV>";
    
  
    /* if($n = 0) {
    title("Input Deposit Pasien Rawat Inap");
    echo "<BR>";
        $f = new Form("actions/888.update.php", "POST");
        $f->hidden("id",$d->id);
		$f->hidden("unit_medis_id",$_GET[unit_medis_id]);
		$f->hidden("f_unit_medis_id", $d->unit_medis_id);
        $f->hidden("f_tipe_medis", $d->tipe_medis);
		$f->hidden("f_tipe_pasien",$d->tipe_pasien);
		
        $f->text("id","KODE",3,3,$d->id,"DISABLED");
		$f->selectArray("f_is_person",
                    "PEGAWAI",
                     Array("Y" => "Ya", "N" => "Tidak"),
                     $d->is_person);
		$f->text("f_prosen","Prosentase",5,5,$d->prosen);
	
		$f->submit(" Simpan ", "onClick='Form1.method=\"POST\";Form1.action=\"actions/888.update.php\";'");
		$f->execute();
    } else { */
    title("Input Deposit Pasien Rawat Inap");    
    echo "<BR>";

        $f = new Form($SC, "GET", "NAME=Form1");
        $f->hidden("id","new");
        $f->text("id","KODE",12,12,"&lt;OTOMATIS&gt;","DISABLED");
		$f->PgConn = $con;
   
        echo "<br>";
        $f->hidden("p", $PID);
		$f->hidden("e","new");
		$f->text("mr_no","No.MR",12,12,$d->mr_no,"readonly");
		$f->text("no_reg","No.Reg",12,12,$d->no_reg,"readonly");
		$f->text("nama","Nama Pasien",30,30,$d->nama,"readonly");
		$f->text("tgl_masuk","Tanggal Masuk",30,30,$d->tgl_masuk,"readonly");
		$f->text("bangsal","Bangsal",50,50,$d->bangsal,"readonly");
		$f->text("kasir","Kasir",50,50,$jns_kasir[$kasirnya],"readonly");
        $f->selectSQL("mCAB", "Cara Pembayaran",
        "select '' as tc, '' as tdesc union ".
        "select a.tc , a.tdesc ".
        "from rs00001 a ".
        "where a.tt='CAB' and  a.tc !='000' order by tdesc asc ", ($_GET["mCAB"]) ? $_GET[mCAB] : "001","OnChange=\"setNoKartu(this.value);\"");

		if ($_GET["e"]) {

		$r = pg_query($con, "select * from rs00044 where no_reg = '".$_GET["e"]."'");
        	$d = pg_fetch_object($r);
        	pg_free_result($r);
		
		$f->text("no_kartu","No. Kartu",50,50,$d->no_kartu," ");
		$f->text("pembayar","Nama Pembayar",50,50,$d->pembayar);
		$f->text("jumlah","Jumlah Deposit",50,50,$d->jumlah);
		$f->submit(" Simpan ", "onClick='Form1.method=\"POST\";Form1.action=\"actions/888.update.php\";'");
		}else{
		$f->text("no_kartu","No. Kartu",50,50,"0"," ");
		$f->text("pembayar","Nama Pembayar",50,50," ");
		$f->text("jumlah","Jumlah Deposit",50,50,"");
		$f->submit(" Simpan ", "onClick='Form1.method=\"POST\";Form1.action=\"actions/888.insert.php\";'");
		}
		$f->execute();
//    }    
?>
<SCRIPT language="JavaScript">

    function setNoKartu( v )
     {
			if (v == "001") {
				document.Form1.no_kartu.disabled = v == "001";
			}
			else{
				document.Form1.no_kartu.value = "0";
				document.Form1.no_kartu.disabled = v == "001";
				} 
		}
</SCRIPT>
