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

$r = pg_query($con, "select a.mr_no as mr_no, a.id as no_reg, upper(a.nama)as nama, 
		a.tgl_reg as tgl_reg, a.asal as poli, a.pasien as tipe_pasien,
		case when a.sisa < 0 then a.sisa * -1 else a.sisa end, 
		to_char((select sum(z.jumlah) from rs00044 z where z.no_reg = a.id),'999,999,999.99') as deposit, a.id as no_reg, a.id as no_reg
		from rsv0012 a 
		where a.statusbayar = 'BELUM LUNAS' and a.id = '".$_GET[rg]."'");
					
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&kas=rj'>".icon("back","Kembali")."</a></DIV>";
    
  
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
    title("Input Deposit Pasien Rawat Jalan");    
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
		$f->text("tgl_masuk","Tanggal Masuk",30,30,$d->tgl_reg,"readonly");
		$f->text("bangsal","Poli",50,50,$d->poli,"readonly");
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
