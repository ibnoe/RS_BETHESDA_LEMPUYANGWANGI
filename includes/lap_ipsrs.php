<?
$PID = "lap_ipsrs";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("startup.php");

if (!$GLOBALS['print']){
title_print("<img src='icon/informasi-2.gif' align='absmiddle' > LAPORAN INSTALASI PEMELIHARAAN SARANA");
    title_excel("lap_ipsrs&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y]."&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y]."&jns=".$_GET[jns]."");

}
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);


 	if (!$GLOBALS['print']){
	    if (!isset($_GET['tanggal1D'])) {
			$tanggal1D = date("d", time());
			$tanggal1M = date("m", time());
			$tanggal1Y = date("Y", time());
			$tanggal2D = date("d", time());
			$tanggal2M = date("m", time());
			$tanggal2Y = date("Y", time());
	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
			
	    } else {
		    
	    $tgl_sakjane = $_GET[tanggal2D] + 1;	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
	
	    }
            $f->selectArray("jns", "Status Akhir",Array("%"=>"Semuanya","1" => "Sudah Selesai Tanpa Suku Cadang", 
                                                     "2" => "Sudah Selesai menggunakan Suku Cadang", 
                                                     "3" => "Diusulkan Membuat RAB (SWAKELOLA)",
                                                     "4" => "Diusulkan Kepada Pihak ke-Tiga",
													 "5" => "Pemeliharaan Rutin di Ruangan jam 09.00 s/d 11.00",
													 "6" => "Pemeliharaan Berkala Mingguan Jum'at & Sabtu jam 09.00 s/d 11.00",
													 "7" => "Pengecekan & Pemasangan Oksigen",
													 "8" => "Pengecekan & Pemasangan gas Medis"),$_GET[jns]);
		$f->selectArray("jns_kegiatan", "Jenis Kegiatan",Array("%" => "Semua","E" => "Elektromedik", "S" => "Sipil"),$_GET[jns_kegiatan]);
    	$f->submit ("TAMPILKAN");
    	$f->execute();
	} else { 
        if (!isset($_GET['tanggal1D'])) {
            $tanggal1D = date("d", time());
            $tanggal1M = date("m", time());
            $tanggal1Y = date("Y", time());
            $tanggal2D = date("d", time());
            $tanggal2M = date("m", time());
            $tanggal2Y = date("Y", time());
	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
			
	    } else {
		    
	    $tgl_sakjane = $_GET[tanggal2D] + 1;	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
	
	    }
	    $f->selectArray("jns", "Status Akhir",Array("%"=>"Semuanya",
                                                     "1" => "Sudah Selesai Tanpa Suku Cadang", 
                                                     "2" => "Sudah Selesai menggunakan Suku Cadang", 
                                                     "3" => "Diusulkan Membuat RAB (SWAKELOLA)",
                                                     "4" => "Diusulkan Kepada Pihak ke-Tiga",
													 "5" => "Pemeliharaan Rutin di Ruangan jam 09.00 s/d 11.00",
													 "6" => "Pemeliharaan Berkala Mingguan Jum'at & Sabtu jam 09.00 s/d 11.00",
													 "7" => "Pengecekan & Pemasangan Oksigen",
													 "8" => "Pengecekan & Pemasangan gas Medis"),$_GET[jns],"disabled");   
		$f->selectArray("jns_kegiatan", "Jenis Kegiatan",Array("%" => "Semua","E" => "Elektromedik", "S" => "Sipil"),$_GET[jns_kegiatan]);
    	$f->execute();
	} 

    echo "<br>";
	$sql =" select tanggal(tanggal,0) || ' ' || to_char(waktu,'hh:mm:ss') as tanggal, to_char(waktu,'hh:mm:ss') as waktu, nomor,id_ruang, 
			case when jns_kegiatan = 'E' then 'Elektomedik' 
				 when jns_kegiatan = 'S' then 'Sipil' end as jns_kegiatan,catatan_jns,catatan, pelapor,pekerja ,
			case when status='1' then 'Sudah Selesai Tanpa Suku Cadang'
                 when status='2' then 'Sudah Selesai menggunakan Suku Cadang' 
				 when status='3' then 'Diusulkan Membuat RAB (SWAKELOLA)' 
				 when status='4' then 'Diusulkan Kepada Pihak ke-Tiga'
				 when status='5' then 'Pemeliharaan Rutin di Ruangan jam 09.00 s/d 11.00'
				 when status='6' then 'Pemeliharaan Berkala Mingguan Jumat & Sabtu jam 09.00 s/d 11.00'
				 when status='7' then 'Pengecekan & Pemasangan Oksigen'
				 else 'Pengecekan & Pemasangan Gas Medis'  end as status, tanggal(tgl_selesai,0)||' '|| waktu_selesai as tgl_selesai,id_ipsrs,catatan_hasil
			from rs80808 
			where jns_kegiatan like '%".$_GET[jns_kegiatan]."%'and status!='0' and (tgl_selesai between '$ts_check_in1' and '$ts_check_in2') and status::text like '%".$_GET[jns]."%'
			group by tanggal, waktu, nomor, jns_kegiatan,catatan,catatan_jns,waktu_selesai, pelapor,pekerja ,id_ruang,status,id_ipsrs,tgl_selesai,catatan_hasil	
			order by tgl_selesai, waktu,status ";

		@$r1 = pg_query($con,$sql);
        @$n1 = pg_num_rows($r1);

		$max_row= 9999999999 ;
		$mulai = $HTTP_GET_VARS["rec"] ;
		if (!$mulai){$mulai=1;}

			
		?>

<br>
  <table CLASS=TBL_BORDER width="100%" border="0">

    <tr>
      <td class="TBL_HEAD" width="4%"><div align="center">NO. </div></td>
      <td class="TBL_HEAD" ><div align="center">NOMOR</div></td>
      <td class="TBL_HEAD" ><div align="center">NAMA RUANG</div></td>
      <td class="TBL_HEAD" ><div align="center">TANGGAL<br>MELAPOR</div></td>
      <td class="TBL_HEAD" ><div align="center">TANGGAL<br>SELESAI</div></td>
      <td class="TBL_HEAD" ><div align="center">JENIS PEKERJAAN</div></td>
      <td class="TBL_HEAD" ><div align="center">NAMA <br>ALAT/KELAS</div></td>
      <td class="TBL_HEAD" ><div align="center">CATATAN<br>HASIL PEKERJAAN</div></td>
      <td class="TBL_HEAD" ><div align="center">PELAPOR</div></td>
      <td class="TBL_HEAD" ><div align="center">PEKERJA</div></td>
      <td class="TBL_HEAD" ><div align="center">STATUS</div></td>
      
    </tr>
    <?
    $jumlah= 0;
    $row1=0;
    $i= 1 ;
    $j= 1 ;
    $last_id=1;
    while (@$row1 = pg_fetch_array($r1)){
        if (($j<=$max_row) AND ($i >= $mulai)){
                $no=$i
                ?>
                <tr valign="top" class="<? ?>" >
                        <td class="TBL_BODY" align="center"><?=$no ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["nomor"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["id_ruang"] ?> </td>
			<td class="TBL_BODY" align="left"><?=$row1["tanggal"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["tgl_selesai"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["jns_kegiatan"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["catatan_jns"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["catatan_hasil"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["pelapor"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["pekerja"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["status"] ?> </td>
                        
                        
                </tr>

                <?;$j++;
        }
        $i++;
}
?>
  </table>

