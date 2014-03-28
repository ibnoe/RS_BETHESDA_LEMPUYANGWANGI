<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004
   // sfdn, 24-12-2006	

$PID = "sms_index_pbk";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from pbk
            where number='".$_GET['e']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
	echo"<form action='actions/sms_index_pbk.update.php'>
			<table>
				<tr>
					<td>Nomor Telepon</td><td>:</td>	
					<td><input type='text' name='number' value='".$d->number."'></td>
					<input type='hidden' name='number_hidden' value='".$d->number."'>
				</tr>
				<tr>
					<td>Nama</td><td>:</td>
					<td><input type='text' name='name' value='".$d->name."'></td>
				</tr>
				<tr>
				<td>Groups</td><td>:</td>
				<td>
	<select name='groupid'>";
			 
              $SQL = 'select * from pbk_groups order by id asc';
			  $r1 = pg_query($con,$SQL);
               while ($data = pg_fetch_array($r1))
                {
            
               echo "<option value='$data[id]'>$data[name]</option>";
            
                }
     echo "</select>
						</td>
					</tr>
					<tr>
					<td><input type='submit' value=Simpan> &nbsp; &nbsp;
					<input type='reset' value=Batal></td>
			</table>
		</form>";
}

else if(isset($_GET["n"])) {
if ($_GET["n"] != "new") {
        $r = pg_query($con, "SELECT * FROM pbk where number='".$_GET['n']."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
	echo"<form action='actions/sms_index_pbk.delete.php'>
			<table>
			<h1>Anda yakin menghapus data ini?</h1>
				<tr>	
					<td>Nomor Telepon</td><td>:</td>
					<td><input type='text' name='number' value='".$d->number."' readonly></td>
				</tr>
				<tr>
					<td>Nama</td><td>:</td>
					<td><input type='text' name='name' value='".$d->name."' readonly></td>
				</tr>
				<tr>
					<td><input type='hidden' name='number_hidden' value='".$d->number."'>
					<input type='submit' value=YA></td>
					
				</tr>
			</table>
		</form>";
	
}
// 24-12-2006
    else if($_SESSION[uid] == "kasir2") {
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
   echo "<td><img src='icon/cellphone_icon.gif' align='absmiddle' ><b><font size=4>Buku Telephone</size></b></td>";
    echo "</tr>";
    echo "</table>";

    if(!$GLOBALS['print']){
        sms_sentitems("sms_pbk_add");
        sms_outbox("sms_pbk_group_add");
        }else {
	}

        $t = new PgTable($con, "100%");

$t->SQL = "SELECT p.name,p.number,pg.name,p.dummy FROM pbk p,pbk_groups pg
            where p.groupid = pg.id";

        if (!isset($_GET[sort])) {
           $_GET[sort] = "pg.name";
           $_GET[order] = "asc";
	}
  $t->ColHeader = array("Nama","No. Handphone","Group","Edit");
    $t->ShowRowNumber = true;
   	$t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
	$t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#1#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='$SC?p=$PID&n=<#1#>'>".icon("delete","Hapus")."</A>".
            "</nobr>"; 
    $t->ColAlign[10] = "CENTER";
	
$t->ColAlign[11] = "CENTER";
 $t->execute();
// ---- end ----
  }
?>