<?php
/**
 * Gema Perbangsa
 * 24 Agustus 2013
 */ 
include '../lib/setting.php';
include '../lib/dbconn.php';
ini_set('display_errors',1);
?>
<title><?=$set_header[0];?> - ANTRIAN PASIEN</title>
<style type="text/css">	 
body{
background-color:#BDFF84;	
}
#content{
float:left;
width:100%;
}
.even{
 background-color: #C8FFA9;
}
 .odd{
 background-color: #ECFFAE;
}

.ant-wrapper{
background-color:#FEFFD8;	
border : 1px solid #8CF535;	
float:left;
margin:10px;
width:300px;
}

.ant-content{	
border-spacing:0;
border-collapse:collapse;
}

.ant-content tbody{
display:block;
font-family:Arial;
height:250px;
overflow:hidden;
}

.ant-close{
color:#666666;
font-family:Arial;
padding:5px;
visibility:hidden;
}

.ant-header tbody > tr > td{
background: rgb(229,230,150); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(229,230,150,1) 0%, rgba(209,211,96,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(229,230,150,1)), color-stop(100%,rgba(209,211,96,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(229,230,150,1) 0%,rgba(209,211,96,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(229,230,150,1) 0%,rgba(209,211,96,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(229,230,150,1) 0%,rgba(209,211,96,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(229,230,150,1) 0%,rgba(209,211,96,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e5e696', endColorstr='#d1d360',GradientType=0 ); /* IE6-9 */

border-spacing:0;
border-collapse:collapse;
font-family:Arial;
padding:10px;
width:150px;
}

.ant-header tbody > tr > th{
background: rgb(80,191,43); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(80,191,43,1) 0%, rgba(96,153,0,1) 50%, rgba(37,132,0,1) 51%, rgba(80,191,43,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(80,191,43,1)), color-stop(50%,rgba(96,153,0,1)), color-stop(51%,rgba(37,132,0,1)), color-stop(100%,rgba(80,191,43,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(80,191,43,1) 0%,rgba(96,153,0,1) 50%,rgba(37,132,0,1) 51%,rgba(80,191,43,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(80,191,43,1) 0%,rgba(96,153,0,1) 50%,rgba(37,132,0,1) 51%,rgba(80,191,43,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(80,191,43,1) 0%,rgba(96,153,0,1) 50%,rgba(37,132,0,1) 51%,rgba(80,191,43,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(80,191,43,1) 0%,rgba(96,153,0,1) 50%,rgba(37,132,0,1) 51%,rgba(80,191,43,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#50bf2b', endColorstr='#50bf2b',GradientType=0 ); /* IE6-9 */

border-spacing:0;
border-collapse:collapse;
font-family:Arial;
font-weight:bold;
color:#ffffff;
padding:10px;
width:150px;
}

.ant-content tbody > tr > td{
border-spacing:0;
border-collapse:collapse;
padding:10px;
width:150px;
}

.ant-footer{
bbackground: rgb(229,230,150); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(229,230,150,1) 0%, rgba(209,211,96,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(229,230,150,1)), color-stop(100%,rgba(209,211,96,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(229,230,150,1) 0%,rgba(209,211,96,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(229,230,150,1) 0%,rgba(209,211,96,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(229,230,150,1) 0%,rgba(209,211,96,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(229,230,150,1) 0%,rgba(209,211,96,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e5e696', endColorstr='#d1d360',GradientType=0 ); /* IE6-9 */

font-family:Arial;
padding:20px 10px 20px 10px;
width:100%;
}
</style>
<script type="text/javascript" src="../plugin/jquery-1.8.2.js"></script>
<script type="text/javascript">
id_pasien = Array();
var tgl_skrg = "<?php echo date('j-n-Y');?>";
$.fn.infiniteScrollUp=function(){
		var self=this,kids=self.children()
		kids.slice(20).hide()
		setInterval(function(){
			kids.filter(':hidden').eq(0).fadeIn()
			kids.eq(0).fadeOut(function(){
				$(this).appendTo(self)
				kids=self.children()
			})
		},2000)
		return this
	}
$(function() {
	clear();
	<?php
	$result = pg_query("SELECT tc, tdesc FROM rs00001 WHERE tt = 'LYN' AND tc NOT IN('000','110','201','202','206','207','208','209','210')");
	while($row = pg_fetch_array($result)){
	?>
	createTable(<?php echo $row['tc'] ?>,'<?php echo $row['tdesc'] ?>');	
	update(<?php echo $row['tc'] ?>, 'ant-<?php echo $row['tc'] ?>');	
	$('#ant-<?php echo $row['tc'] ?> tbody').infiniteScrollUp();
	$("#ant-wrapper-<?php echo $row['tc']?>").mouseover(function() { $("#ant-close-<?php echo $row['tc']?>").css('visibility','visible'); });
	$("#ant-wrapper-<?php echo $row['tc']?>").mouseout(function() { $("#ant-close-<?php echo $row['tc']?>").css('visibility','hidden'); });
	<?php
	}
	?>
});

function tutup(id){
	$('#'+id).remove();
}
function clear(){
	setTimeout(function(){
		clear();
	 },36000);
	var d = new Date();
	var tgl = d.getDate()+"-"+(d.getMonth()+1)+"-"+d.getFullYear();
	if(tgl_skrg!=tgl){
		location.reload();
	}
}
function update(poli, id_table) {
	var cls;
	 setTimeout(function(){
		update(poli, id_table);
	 },4000);
 $.ajax({
  type: "POST",
  url: "get_pasien_poli.php",
  dataType : 'json',
  data: { poli : poli }
	}).fail(function( msg ) {
  		alert( "Gagal Mengambil Data !!!!" );
	}).done(function (data){
		for(i=0;i<data.length;i++){
			if(id_pasien.indexOf(poli+'-'+data[i].id)==-1){
				cls = (i%2==0) ? cls = "odd": cls = "even";	    
				$('#'+id_table+' tbody').append('<tr class="'+cls+'" id="'+poli+'-'+data[i].id+'"><td align="right">'+(i+1)+'</td><td>'+data[i].id+'</td><td>'+data[i].nama+'</td></tr>');
			}
		id_pasien.push(poli+'-'+data[i].id);
		}
		$('#jml_pasien-'+poli).html(data.length);
	  });
}

function createTable(id, header){
$('#content').append('<table class="ant-wrapper" id="ant-wrapper-'+id+'">'+
'<tr>'+
   '<td>'+
     '<table cellspacing="0" cellpadding="0" class="ant-header">'+
	'<tr><th align="center" colspan="3">'+header+'</th></tr>'+
	'<tr><td align="center">No.</td><td align="center">No. Reg</td><td align="center">Nama</td></tr>'+
     '</table>'+
   '</td>'+
'</tr>'+
'<tr>'+
   '<td>'+
     '<table cellspacing="0" cellpadding="0" class="ant-content" id="ant-'+id+'">'+
	'<tbody>'+
	 '<tr><td></td></tr>'+
	 '</tbody>'+
     '</table>'+
   '</td>'+
'</tr>'+
'<tr>'+
   '<td>'+
     '<table class="ant-footer" cellspacing="0" cellpadding="0" width="100%">'+
	'<tr><td align="center">JUMLAH PASIEN : <span id="jml_pasien-'+id+'"></span></td></tr>'+
     '</table>'+
   '</td>'+
'</tr>'+
'<tr>'+
'<tr><td><a class="ant-close" id="ant-close-'+id+'" onclick=tutup("ant-wrapper-'+id+'")>tutup</a></td></tr>'+
'</table>');
}
</script>
<body>
<table width="100%">
  <tr>
      <td rowspan="3" align="right" width="25%"><img src="../<?=$set_client_logo?>" height="150" width="150"/></td>
	  <td align="left" width="75%"><font face="Arial" size="8"><b><?=$set_header[0];?></b></font></td>
  </tr>
  <tr><td align="left"><font face="Verdana" size="5" style="margin-left:35px;"><?=$set_header[2];?></font></td></tr>
  <tr><td align="left"><font face="Verdana" size="5" style="margin-left:95px;"><?=$set_header[3];?></font></td></tr>
</table>
<div id="content"></div>
</body>
