<div align='center'>
	<table border='0' width='100%' id='table1'>
	<tr>
	<td>
	<?php
	$PID = "laporan2_rl4";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;
require_once("../lib/class.PgTable.php");
require_once("../lib/class.BaseTable.php");
require_once("../lib/class.TabBar.php");

require_once("../lib/visit_setting.php");
require_once("../lib/setting.php"); 

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");



//subtitle_print("DATA KETENAGAAN RUMAH SAKIT");/
//subtitle_print("Triwulan : ".$set_triwulan);
//subtitle_rs("FORMULIR RL4");
//subtitle_rs($set_header[0]." ".$set_header[1]);
//subtitle_rs("No. Kode RS : ".$set_kode_rs); 	  
		
	if(!$GLOBALS['print'])
	{
	 	title_print("");
		title_excel("laporan2_rl4");
        edit_laporan("input_rl4");
	}
	else 
	{
	
			
	}

	?>
	
	</td>
	</tr>
	<tr><td>
	<div align='center'>
				<table border='0' width='100%' id='table2'>
					<tr>
						<td colspan='3'>
						<p align='center'><b><font size="4">DATA KEADAAN KETENAGAAN RUMAH SAKIT</font></b></td>
					</tr>
					<tr>
						<td colspan='3'>
						<p align='center'><b><font size="4">(PER 1 JANUARI / 31 JUNI 2009)</font></b></td>
					</tr>
					<tr>
						<td width='444'><b>Nama Rumah Sakit : RSJ Prof. HB. Saanin Padang</b></td>
						<td width='602'>&nbsp;</td>
						<td width='260'>
						<p align='right'>Formulir RL4 Halaman 1</td>
					</tr>
					<tr>
						<td width='444'><b>A. JUMLAH TENAGA KESEHATAN MENURUT JENIS</b></td>
						<td width='602'>&nbsp;</td>
						<td width='260'>
						<p align='right'>
						<div align='center'>
							<table border='1' width='260' style='border-collapse: collapse'>
								<tr>
									<td width='100' align='center'>No. Kode RS : </td>
									<td width='20' align='center'>1</td>
									<td width='20' align='center'>3</td>
									<td width='20' align='center'>7</td>
									<td width='20' align='center'>1</td>
									<td width='20' align='center'>3</td>
									<td width='20' align='center'>1</td>
									<td width='20' align='center'>6</td>
								</tr>
							</table>
						</div>

						</td>
					</tr>
					</table>
			</div>
			</td>
		</tr>
		<tr>
		<td>
		<?php


		
						$SQLpendidikan = "select * from kualifikasi_pendidikan order by id_kualifikasi_pendidikan asc;";
						$execpendidikan = pg_query($con,$SQLpendidikan);
						while ($tampilpendd = pg_fetch_array($execpendidikan))
						{
						// echo $tampilpendd[nama_kualifikasi_pendidikan]."<br>";
						
						
						?>
		
		
		<div align='center'>
		<table border='0' width='100%'>
		<tr><td>
			<div align='center'>
				<table border='0' width='100%'>
					<tr>
						<td>
						
						
						
						
						<b><?php echo $tampilpendd[id_kualifikasi_pendidikan].". ".$tampilpendd[nama_kualifikasi_pendidikan] ?></b></td>
					</tr>
					<tr>
						<td>
						<div align='center'>
							<table border='1'  style='border-collapse: collapse' id='table1' width='100%'>
								
								<?php
echo $tampilpendd[header];
  
						$SQL1 = "select 
									c.keterangan as identifikasi, *
								from 
									kualifikasi_ketenagaan as a,
									kualifikasi_pendidikan as b,
									tingkatan_pendidikan as c
								where
									a.id_kualifikasi_ketenagaan = b.id_kualifikasi_ketenagaan and
									b.id_kualifikasi_pendidikan = c.id_kualifikasi_pendidikan and
									b.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan]." order by id_tingkatan_pendidikan asc;";
									
   						$r1 = pg_query($con,$SQL1);
						$n1 = pg_num_rows($r1);
						while ($row1 = pg_fetch_array($r1))
						{
								
								echo "<tr>
									<td align='center'>".$row1[no_urut]."</td>
									<td>".$row1[nama_tingkatan]."</td>";
									
for($x=1; $x <=9; $x++)
{
echo "<td align='center'>";
									
						if($row1[identifikasi] != "total")
						{
						$SQLs3 = "select 
			count(nip) as jumlah
		from 	
			kualifikasi_ketenagaan as h,	
			kualifikasi_pendidikan as i, 
			tingkatan_pendidikan as j, 
			rs00017 a
			left outer join rs00027 d ON a.rs00027_id = d.id 
			left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM' 
			left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD' 
			left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
			left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
			left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
		where
			a.id_waktu_kerja = '001' and 
			a.statuspegawai_id = '00".$x."' and
			h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
			i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
			a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
			a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
			a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
			j.id_tingkatan_pendidikan = ".$row1[id_tingkatan_pendidikan]." and
			i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";
									
   						$rr3 = pg_query($con,$SQLs3);
						$rows3 = pg_fetch_array($rr3);
						}
						else
						{
$SQLs3 = "select 
			count(nip) as jumlah
		from 	
			kualifikasi_ketenagaan as h,	
			kualifikasi_pendidikan as i, 
			tingkatan_pendidikan as j, 
			rs00017 a
			left outer join rs00027 d ON a.rs00027_id = d.id 
			left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM' 
			left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD' 
			left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
			left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
			left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
		where
			a.id_waktu_kerja = '001' and 
			a.statuspegawai_id = '00".$x."' and
			h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
			i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
			a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
			a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
			a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
			i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";
									
   						$rr3 = pg_query($con,$SQLs3);
						$rows3 = pg_fetch_array($rr3);
						}
						
						if($rows3[jumlah] <> 0)
						{
						echo $rows3[jumlah];
						}
						else
						{
						echo "-";
						}					
									
									echo "</td>";
									}
									
									
									
									echo "<td align='center'>";
if($row1[identifikasi] != "total")
{
$SQLsubtotalpurna = "select
count(nip) as jumlah
from
kualifikasi_ketenagaan as h,
kualifikasi_pendidikan as i,
tingkatan_pendidikan as j,
rs00017 a
left outer join rs00027 d ON a.rs00027_id = d.id
left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM'
left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD'
left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
where
a.id_waktu_kerja = '001' and
h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
j.id_tingkatan_pendidikan = ".$row1[id_tingkatan_pendidikan]." and
i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";

$rrr = pg_query($con,$SQLsubtotalpurna);
$rowssubtotal = pg_fetch_array($rrr);

}
else
{
$SQLsubtotalpurna = "select
count(nip) as jumlah
from
kualifikasi_ketenagaan as h,
kualifikasi_pendidikan as i,
tingkatan_pendidikan as j,
rs00017 a
left outer join rs00027 d ON a.rs00027_id = d.id
left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM'
left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD'
left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
where
a.id_waktu_kerja = '001' and
h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";

$rrr = pg_query($con,$SQLsubtotalpurna);
$rowssubtotal = pg_fetch_array($rrr);

}

if($rowssubtotal[jumlah] <> 0)
{
echo $rowssubtotal[jumlah];
}
else
{
echo "-";
}

									
									echo "</td>";
									
									for($y=1;$y<=8;$y++)
									{
									echo "<td align='center'>";

if($row1[identifikasi] != "total")
{
$SQLs4 = "select
count(nip) as jumlah
from
kualifikasi_ketenagaan as h,
kualifikasi_pendidikan as i,
tingkatan_pendidikan as j,
rs00017 a
left outer join rs00027 d ON a.rs00027_id = d.id
left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM'
left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD'
left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
where
a.id_waktu_kerja = '002' and
a.statuspegawai_id = '00".$y."' and
h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
j.id_tingkatan_pendidikan = ".$row1[id_tingkatan_pendidikan]." and
i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";

$rr4 = pg_query($con,$SQLs4);
$rows4 = pg_fetch_array($rr4);
}
else
{
$SQLs4 = "select
count(nip) as jumlah
from
kualifikasi_ketenagaan as h,
kualifikasi_pendidikan as i,
tingkatan_pendidikan as j,
rs00017 a
left outer join rs00027 d ON a.rs00027_id = d.id
left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM'
left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD'
left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
where
a.id_waktu_kerja = '002' and
a.statuspegawai_id = '00".$y."' and
h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";

$rr4 = pg_query($con,$SQLs4);
$rows4 = pg_fetch_array($rr4);

}

if($rows4[jumlah] <> 0)
{
echo $rows4[jumlah];
}
else
{
echo "-";
}


									echo "</td>";
									}
									
echo "<td align='center'>";

if($row1[identifikasi] !="total")
{
$SQLsubtotalparuh = "select
count(nip) as jumlah
from
kualifikasi_ketenagaan as h,
kualifikasi_pendidikan as i,
tingkatan_pendidikan as j,
rs00017 a
left outer join rs00027 d ON a.rs00027_id = d.id
left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM'
left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD'
left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
where
a.id_waktu_kerja = '002' and
h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
j.id_tingkatan_pendidikan = ".$row1[id_tingkatan_pendidikan]." and
i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";

$rrr2 = pg_query($con,$SQLsubtotalparuh);
$rowssubtotal2 = pg_fetch_array($rrr2);
}
else
{
$SQLsubtotalparuh = "select
count(nip) as jumlah
from
kualifikasi_ketenagaan as h,
kualifikasi_pendidikan as i,
tingkatan_pendidikan as j,
rs00017 a
left outer join rs00027 d ON a.rs00027_id = d.id
left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM'
left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD'
left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
where
a.id_waktu_kerja = '002' and
h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";

$rrr2 = pg_query($con,$SQLsubtotalparuh);
$rowssubtotal2 = pg_fetch_array($rrr2);
}

if($rowssubtotal2[jumlah] <> 0)
{
echo $rowssubtotal2[jumlah];
}
else
{
echo "-";
}

echo "</td>
<td align='center'>";
///// awal honorer
if($row1[identifikasi] != "total")
{
$SQLhonorer = "		select
					count(nip) as jumlah
					from
					kualifikasi_ketenagaan as h,
					kualifikasi_pendidikan as i,
					tingkatan_pendidikan as j,
					rs00017 a
					left outer join rs00027 d ON a.rs00027_id = d.id
					left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM'
					left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD'
					left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
					left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
					left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
					where
					a.statuspegawai_id = '010' and
					h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
					i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
					a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
					a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
					a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
					j.id_tingkatan_pendidikan = ".$row1[id_tingkatan_pendidikan]." and
					i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";

$rrrhonorer = pg_query($con,$SQLhonorer);
$rowshonorer = pg_fetch_array($rrrhonorer);
}
else
{
$SQLhonorer = "		select
					count(nip) as jumlah
					from
					kualifikasi_ketenagaan as h,
					kualifikasi_pendidikan as i,
					tingkatan_pendidikan as j,
					rs00017 a
					left outer join rs00027 d ON a.rs00027_id = d.id
					left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM'
					left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD'
					left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
					left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
					left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
					where
					a.statuspegawai_id = '010' and
					h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
					i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
					a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
					a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
					a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
					i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";

$rrrhonorer = pg_query($con,$SQLhonorer);
$rowshonorer = pg_fetch_array($rrrhonorer);
}

if($rowshonorer[jumlah] <> 0)
{
echo $rowshonorer[jumlah];
}
else
{
echo "-";
}
///// akhir honorer
echo "</td>
<td align='center'>";

if($row1[identifikasi] != "total")
{
$SQLtotal = "		select
					count(nip) as jumlah
					from
					kualifikasi_ketenagaan as h,
					kualifikasi_pendidikan as i,
					tingkatan_pendidikan as j,
					rs00017 a
					left outer join rs00027 d ON a.rs00027_id = d.id
					left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM'
					left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD'
					left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
					left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
					left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
					where
					h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
					i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
					a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
					a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
					a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
					j.id_tingkatan_pendidikan = ".$row1[id_tingkatan_pendidikan]." and
					i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";

$rrrtotal = pg_query($con,$SQLtotal);
$rowstotal = pg_fetch_array($rrrtotal);
}
else
{
$SQLtotal = "		select
					count(nip) as jumlah
					from
					kualifikasi_ketenagaan as h,
					kualifikasi_pendidikan as i,
					tingkatan_pendidikan as j,
					rs00017 a
					left outer join rs00027 d ON a.rs00027_id = d.id
					left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM'
					left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD'
					left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP'
					left outer join rs00001 f ON a.id_waktu_kerja = f.tc and f.tt='WKK'
					left outer join rs00001 g ON a.statuspegawai_id = g.tc and g.tt='STP'
					where
					h.id_kualifikasi_ketenagaan = i.id_kualifikasi_ketenagaan and
					i.id_kualifikasi_pendidikan = j.id_kualifikasi_pendidikan and
					a.id_kualifikasi_ketenagaan = h.id_kualifikasi_ketenagaan and
					a.id_kualifikasi_pendidikan = i.id_kualifikasi_pendidikan and
					a.id_tingkatan_pendidikan = j.id_tingkatan_pendidikan and
					i.id_kualifikasi_pendidikan = ".$tampilpendd[id_kualifikasi_pendidikan].";";

$rrrtotal = pg_query($con,$SQLtotal);
$rowstotal = pg_fetch_array($rrrtotal);
}

if($rowstotal[jumlah] <> 0)
{
echo $rowstotal[jumlah];
}
else
{
echo "-";
}

echo "</td>
								</tr>";
								
								}
								?>
								
								
								</table>
						</div>
						</td>
					</tr>
				</table>
			</div>
			</td>
		</tr>
		<tr>
			<td>
			<?php  echo $tampilpendd[note] ?>
			</td>
		</tr>
		<tr>
			
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		</table></div>
		
		
		<?php
		}
		
		?>
		
		
		
		</td>
		</tr>
		<tr>
			<td>
			<div align="center">
				<table border="0" width="100%">
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td width="371">
						<div align="center">
							<table border="0" width="100%" id="table75">
								<tr>
									<td colspan="3">
									............................................................................................</td>
								</tr>
								<tr>
									<td width="27%">Direktur RS</td>
									<td width="3%">:</td>
									<td width="67%">RSJ Prof. HB Saanin Padang</td>
								</tr>
								<tr>
									<td width="27%">Tanda Tangan </td>
									<td width="3%">:</td>
									<td width="67%">
									...............................................................</td>
								</tr>
								<tr>
									<td width="27%">Nama Terang</td>
									<td width="3%">:</td>
									<td width="67%">Dr. Kurniawan S, Sp.KJ</td>
								</tr>
							</table>
						</div>
						</td>
					</tr>
				</table>
			</div>
		</td></tr>
<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>
</div>