<html>
<head>
<script>

var limit="5:00"

if (document.images){
var parselimit=limit.split(":")
parselimit=parselimit[0]*60+parselimit[1]*1
}
function beginrefresh(){
if (!document.images)
return
if (parselimit==1)
window.location.reload()
else{ 
parselimit-=1
curmin=Math.floor(parselimit/60)
cursec=parselimit%60
if (curmin!=0)
curtime=curmin+" minutes and "+cursec+" seconds left until page refresh!"
else
curtime=cursec+" seconds left until page refresh!"
window.status=curtime
setTimeout("beginrefresh()",1000)
}
}

window.onload=beginrefresh
//-->
</script>
<?php
require_once("lib/dbconn.php");
//hierarchy
$rowsBangsal   = pg_query($con, "SELECT id, LEFT(hierarchy,3) AS kode, bangsal FROM rs00012 WHERE RIGHT(hierarchy,12) = '000000000000' ORDER BY LEFT(hierarchy,3) ASC" );
$rowsBangsal2   = pg_query($con, "SELECT id, bangsal, hierarchy FROM rs00012 WHERE RIGHT(hierarchy,12) = '000000000000'");
?>
</head>
<body leftmargin="0" topmargin="0">
<table border="0">
	<tr>
<?php
        $arrBangsal = array();
	while($rowBangsal=pg_fetch_array($rowsBangsal)){
            $arrBangsal[$rowBangsal['kode']] = $rowBangsal['bangsal'];
            
            echo '<td valign="top" style="height:1150;background-color:#000;color:#09ED11;font-family:arial;font-size:22px;font-weight:bold;text-align:center;">';
            echo $rowBangsal['bangsal'].'<hr/>';
                // get data kelas
                $rowsKelas   = pg_query($con, "SELECT id, LEFT(hierarchy,6) AS kode, bangsal as kelas FROM rs00012 WHERE LEFT(hierarchy,6)LIKE '".$rowBangsal['kode']."%' AND RIGHT(hierarchy,9) = '000000000'  ORDER BY LEFT(hierarchy,6) ASC" );
				if(($rowBangsal['kode']== '002') || ($rowBangsal['kode']== '003') || ($rowBangsal['kode']== '004')){
                echo '<MARQUEE DIRECTION="up" height="1000"scrollamount="3">';
				}
                echo '<table>';
                while($rowKelas=pg_fetch_array($rowsKelas)){
                    if($rowBangsal['kode'].'000' != $rowKelas['kode']){
                        echo '<tr>';
                        echo '<td style="color:#fff;font-size:16px;font-weight:bold;">&nbsp;&nbsp;';
						$kelasTmp = $rowKelas['kelas'];
						$kelas = str_replace("Kls - Utama - ","",$kelasTmp);
						$kelas = str_replace("Kls - 1 - ","",$kelas);
						$kelas = str_replace("Kls - 2 - ","",$kelas);
						$kelas = str_replace("Kls - 3 - ","",$kelas);
						$kelas = str_replace("Km.","Kamar ",$kelas);
                        echo $kelas;
                        // get data bed
                            echo '<table>';
                            $rowsBed   = pg_query($con, "SELECT id, LEFT(hierarchy,9) AS kode, bangsal as bed FROM rs00012 WHERE LEFT(hierarchy,9)LIKE '".$rowKelas['kode']."%' AND RIGHT(hierarchy,6) = '000000'  ORDER BY LEFT(hierarchy,9) ASC" );
                            while($rowBed=pg_fetch_array($rowsBed)){
                                if($rowKelas['kode'].'000' != $rowBed['kode']){
                                    // get pasien on bed
                                    $rowPasien   = pg_query($con, "select DISTINCT upper(b.nama)as nama

from rs00010 as a 
join rs00006 as c on a.no_reg = c.id 
join rs00002 as b on c.mr_no = b.mr_no 
join rs00012 as d on a.bangsal_id = d.id 
join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' 
join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' 
left join rs00001 g on g.tc = b.tipe_pasien and g.tt='JEP' 
where 
a.ts_calc_stop is null AND
d.id = ".$rowBed['id']);

								  //if(pg_num_rows($rowPasien) > 0){
                                    echo '<tr>';
                                    echo '<td  style="color:#FFF;font-size:14px;font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;';
                                    echo $rowBed['bed'].': ';
                                        while($pasien=  pg_fetch_array($rowPasien)){
                                            echo '<span style="color:#09ED11;font-size:14px;font-weight:bold;">'.$pasien[0].'</span><br/>';
                                        }
                                    echo '</td>';
                                    echo '</tr>';
								  //}
                                }
                            }
                            echo '</table>';
							echo '</marquee>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
				echo '</MARQUEE>';
                echo '</table>';
            echo '</td>';
                
	}
?>
	</tr>
</table>
</body>
</html>