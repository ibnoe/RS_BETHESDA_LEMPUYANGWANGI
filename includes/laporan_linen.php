
 <?	
  
 $PID = "laporan_linen";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	 
require_once("lib/setting.php"); 
echo "<b><big><center>LAPORAN JUMLAH CUCIAN</center></big></b>";


$f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);


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

	$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
    }

    $f->submit ("TAMPILKAN");
    $f->execute();
	

//$bulan=datetime($ts_check_in1,('dd mm yyyy'));
//subtitle_print("Bulan : $bulan");
//subtitle_print($set_header[0]." ".$set_header[1]);

		
	if(!$GLOBALS['print']){
        title_print(""); 
	title_excel("laporan_linen&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."");
		
		
	}else {
		
	}

$SQL = "select nama_jenis,id from jenislinen group by nama_jenis,id order by id";
   
                        $r1 = pg_query($con,$SQL);
                        $n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}    
	
 

?>

<table CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>
  <tr>
    <td class="TBL_HEAD" align="center" >No.</td>
	<td class="TBL_HEAD" align="center" width=25% >Ruangan</td>
   <?
    $i=0;
	while($row1=pg_fetch_array($r1)){
	$array_jenis[$i]=$row1["id"];
	$jumlahbawah[$i]=0;
	$i=$i+1;
	
	echo" <td class='TBL_HEAD' width=10% align='center' >".$row1["nama_jenis"]."</td>";
	
	}
	echo "<td class='TBL_HEAD' align=CENTER >JUMLAH</td>";
	echo "  </tr>";
	$SQL2 = "select nama_ruang,id from ruang_linen group by nama_ruang,id order by id";
	$r2 = pg_query($con,$SQL2);
	$j=1;
	$total=0;
	while($row2=pg_fetch_array($r2)){   
	$i=0;
	$jumlahpinggir=0;
		echo "<tr>";
			echo"<td class='TBL_BODY'>$j</td>";
			echo"<td class='TBL_BODY'>".$row2["nama_ruang"]."</td>";
			while ($i<$n1){
		
				$jumlah=getFromTable ("select sum(a.jumlah)
					from laundry_item a, jenislinen b ,laundry_c c , ruang_linen d
					where a.id_linen=b.id and a.id_laundry=c.id and d.id=c.id_ruang 
					and (c.tanggal between '$ts_check_in1' and '$ts_check_in2') and c.id_ruang=".$row2["id"]." and a.id_linen=".$array_jenis[$i]." ");
					echo"<td class='TBL_BODY'>$jumlah</td>";
					$jumlahpinggir=$jumlah+$jumlahpinggir;
					$jumlahbawah[$i]=$jumlah+$jumlahbawah[$i];$i=$i+1;
			}
			echo"<td class='TBL_BODY'>$jumlahpinggir</td>";
			$total=$jumlahpinggir+$total;
		echo "</tr>";
		
		$j=$j+1;
	}
	echo "<tr>";
		echo"<td class='TBL_FOOT'>&nbsp;</td>";
		echo "<td class='TBL_FOOT'>JUMLAH</td>";
		$i=0;
		while ($i<$n1){
			echo"<td class='TBL_FOOT'>$jumlahbawah[$i]</td>";
			$i=$i+1;
		}echo"<td class='TBL_FOOT'>$total</td>";
	echo "</tr>";
	
	
	?>
    

  
  
  

  	
  
  
  
</table>
<p>&nbsp;</p>	
