<?php
session_start();
$PID = "import";

// menggunakan class phpExcelReader dan database connection
require_once("../lib/dbconn.php");
require_once("../lib/excel_reader2.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

// membaca file excel yang diupload
$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);

// membaca jumlah baris dari data excel
$baris = $data->rowcount($sheet_index=0);

// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
$sukses = 0;
$gagal = 0;

// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
for ($i=2; $i<=$baris; $i++)
{
  // membaca data Kolom yang di sediakan oleh Excel
  //
  $USERID = $data->val($i, 1); 
  $CHECKTIME = $data->val($i, 2); 
  $CHECKTYPE = $data->val($i, 3); 
  $VERIFYCODE = $data->val($i, 4); 
  $SENSORID = $data->val($i, 5); 
  $WORKCODE = $data->val($i, 6); 

  
  $query = "INSERT INTO checkinout (userid,  checktime,  checktype, verifycode,   sencoreid,   workcode)
		VALUES ($USERID,'$CHECKTIME','$CHECKTYPE','$VERIFYCODE','$SENSORID','$WORKCODE')";
  $hasil = pg_query($query) or die($query);

  // jika proses insert data sukses, maka counter $sukses bertambah
  // jika gagal, maka counter $gagal yang bertambah
  if ($hasil) $sukses++;
  else $gagal++;
  
}

if ($sukses!=0) {
    $_SESSION["dialog"]["title"] = "Jumlah data yang sukses di import '$sukses'";
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    header("Location: ../index2.php?p=dialog");
    exit;
} else {
    echo "Jumlah data yang gagal di import '$gagal'";
}
?>
