<?
//require_once("startup.php");
require_once("../lib/visit_setting.php");
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/setting.php"); 
//Untuk menambahkan link file PDF
require_once("fpdf/fpdf.php");	

//include ("cetak.invoice");

//perintah untuk query dari database
$query ="SELECT mr_no,nama,alm_tetap,tanggal(tgl_lahir,0) FROM rs00002 ";
$db_query = pg_query($query) or die("Query gagal");

//Variabel untuk iterasi
$i = 0;

//Mengambil nilai dari query database
while($data=pg_fetch_row($db_query))
{
$cell[$i][0] = $data[0];
$cell[$i][1] = $data[1];
$cell[$i][2] = $data[2];
$cell[$i][3] = $data[3];
$i++;
}



//memulai pengaturan output PDF
class PDF extends FPDF
{
//untuk pengaturan header halaman
function Header()
{
//Pengaturan Font Header
$this->SetFont('Arial','B',12); //jenis font : Times New Romans, Bold, ukuran 14
//$this->Image('../images/icon.png',10,6,30);
//untuk warna background Header
$this->SetFillColor(255,255,255);

//untuk warna text
$this->SetTextColor(0,0,0);

//Menampilkan tulisan di halaman
$this->Cell(19,1,'Data Pribadi','TBLR',0,'C',1); //TBLR (untuk garis)=> B = Bottom,
// L = Left, R = Right
//untuk garis, C = center
}
}


//pengaturan ukuran kertas P = Portrait
$pdf = new PDF('P','cm','A4');
$pdf->Open();
$pdf->AddPage();
//Ln() = untuk pindah baris
$pdf->Ln();
$pdf->SetFont('Times','B',12);

$pdf->Cell(1,1,'No','LRTB',0,'C');
$pdf->Cell(3,1,'NO.MR','LRTB',0,'C');
$pdf->Cell(4,1,'NAMA','LRTB',0,'C');
$pdf->Cell(5,1,'ALAMAT','LRTB',0,'C');
$pdf->Cell(6,1,'TGL.LAHIR','LRTB',0,'C');
$pdf->Ln();

$pdf->SetFont('Arial','',10);
for($j=0;$j<$i;$j++)
{
//menampilkan data dari hasil query database
$pdf->Cell(1,1,$j+1,'LBTR',0,'C');
$pdf->Cell(3,1,$cell[$j][0],'LBTR',0,'C');
$pdf->Cell(4,1,$cell[$j][1],'LBTR',0,'C');
$pdf->Cell(5,1,$cell[$j][2],'LBTR',0,'L');
$pdf->Cell(6,1,$cell[$j][3],'LBTR',0,'L');
$pdf->Ln();
}


//menampilkan output berupa halaman PDF
$pdf->Output();
?>