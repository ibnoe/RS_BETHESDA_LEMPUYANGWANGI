<? 
// Rizki, NOV 08 14:09:04 WIB 2012
$PID = "9999";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("<img src='icon/informasi-2.gif' align='absmiddle' >  Tabel Master: ICD-9 CM ");
if(strlen($_GET["e"]) > 0)
	{
		//Form Input
		echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
		if($_GET["e"] == "new") 
			{
			
				$f = new Form("actions/9999.insert.php");
				// UKI, NOV 08 14:09:04 WIB 2012 RENAME TITLE
				title("Tambah Data ICD-9 CM");
				echo "<BR />";
				$f->text("f_description_code","Kode ICD-9 CM",12,12,"","");
				$f->text("f_description","Nama ICD-9 CM",50,250,"");
				
			}
		else
			{
			
					$r2 = pg_query($con,
						"select * ".
						"from rs000199 ".
						"where description_code='".$_GET["e"]."'");
					$d2 = pg_fetch_object($r2);
					pg_free_result($r2);
					
					$f = new Form("actions/9999.update.php");
					$f->subtitle("Edit Data ICD-9 CM");
					
					echo "<BR />";
					
					$f->hidden("description_code",$_GET["e"]);
					$f->text("f_diagnosis_code","Kode ICD-9 CM",6,6,$_GET["e"],"DISABLED");
					$f->text("f_description","Nama ICD-9 CM",50,250,$d2->description);
					
			}

				$f->submit(" Simpan ");
				$f->execute();
				
				echo "<br />";
				
					if(strlen($_GET["err"]) > 0) {
						errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
					}
			
	}
else
	{
		//Search And Table
		echo "<BR />";
		echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
		echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
		echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
		echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";
		echo "</TR></FORM></TABLE></DIV>";
		
		$t = new PgTable($con, "100%");
		$t->SQL = "select description_code, description, category, description_code as dummy ".
				  "from rsv_icd9_cm ".
				  "where ".
				  "(upper(description_code) LIKE '%".strtoupper($_GET["search"])."%' ".
				  "OR upper(description) LIKE '%".strtoupper($_GET["search"])."%' ".
				  "OR upper(category) LIKE '%".strtoupper($_GET["search"])."%') ";
			
		$t->setlocale("id_ID");
		$t->ShowRowNumber = true;
		$t->RowsPerPage = $ROWS_PER_PAGE;
		$t->ColAlign[0] = "CENTER";
		$t->ColAlign[3] = "CENTER";
		$t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#3#>&acticd=edd'>".icon("edit","Edit")."</A>";
		$t->ColHeader = array("KODE DIAGNOSA", "DIAGNOSA", "KATEGORI","E d i t");
		$t->execute();
		
		echo "<BR /><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
			 "HREF='index2.php?p=$PID&e=new&acticd=add'>Tambah Data ICD-9 CM Baru </A></DIV>";
	}
?>
