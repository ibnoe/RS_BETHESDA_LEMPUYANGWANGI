<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004
   // sfdn, 24-12-2006	

$PID = "sms_outbox";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

// 24-12-2006
    if ($_SESSION[uid] == "kasir2") {
       $what = "RAWAT INAP";
       $sqlayanan = "NOT LIKE '%IGD%'";	
    } elseif ($_SESSION[uid] == "kasir1") {
       $what = "RAWAT JALAN";
       $sqlayanan = "NOT LIKE '%IGD%'";
    } else {
       $what = "IGD";
       $sqlayanan = "LIKE '%IGD%'";
    
  echo "<table>";
    echo "<tr>";
    echo "<td><b><font size=4>Kirim SMS</size></b></td>";
    echo "</tr>";
    echo "</table>";


// ---- end ----
  }
?>
<SCRIPT language=javascript>
		function jumlahKata(form) {
		with (form) {
			sisa.value = 160-pesan.value.length;
			if (parseInt(sisa.value)<0) {
				sisa.value = '0';
			}
		pesan.value = pesan.value.substr(0,160);
		}
		return;
	}

	</SCRIPT>
<form name ="fp" action=<?php echo "pengiriman berhasil"; ?> method=POST>
    <table align="center" width=75% cellspacing="0" cellpadding="0">
	<tr>
	<td width=0%>&nbsp;</td>
        <td width=25%>Nomor Tujuan</td>
        <td width=4%><div align="center">:</div></td>
	<td>
	<select name="groupid">
            <?php
              //$SQL = "select tc, tdesc from rs00001 where tt='PEG' order by tc";
              $SQL = "select * from pbk_groups order by id asc";
			  $r1 = pg_query($con,$SQL);
               while ($data = pg_fetch_array($r1))
                {
            ?>
               <option value="<?php echo $data[id];?>"><?php echo $data[name];?></option>
            <?php
                }
            ?>
        </select>
						</td>
					</tr>
	<tr>
		<td width=0%>&nbsp;</td>
		<td width=25%>Pesan </td>
		<td width=4%><div align="center">:</div></td>
		<td width=88% height=25%><font color="#000066"><TEXTAREA onkeyup=jumlahKata(document.fp); class=textcontent name=pesan rows=8 cols=75></TEXTAREA>Panjang maksimum: 160 karakter<BR>Sisa karakter:<INPUT
            size=3 name=sisa class=inputcontent></font>
	</tr>
	<tr>
	<td><input type=submit value=Kirim></td>
	<td><input type=reset value=Reset></td>
	</tr>
</table>

</form>