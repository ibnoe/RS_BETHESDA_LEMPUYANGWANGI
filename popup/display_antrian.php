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
background: rgb(183,222,237); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(183,222,237,1) 0%, rgba(113,206,239,1) 50%, rgba(33,180,226,1) 51%, rgba(183,222,237,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(183,222,237,1)), color-stop(50%,rgba(113,206,239,1)), color-stop(51%,rgba(33,180,226,1)), color-stop(100%,rgba(183,222,237,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(183,222,237,1) 0%,rgba(113,206,239,1) 50%,rgba(33,180,226,1) 51%,rgba(183,222,237,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(183,222,237,1) 0%,rgba(113,206,239,1) 50%,rgba(33,180,226,1) 51%,rgba(183,222,237,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(183,222,237,1) 0%,rgba(113,206,239,1) 50%,rgba(33,180,226,1) 51%,rgba(183,222,237,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(183,222,237,1) 0%,rgba(113,206,239,1) 50%,rgba(33,180,226,1) 51%,rgba(183,222,237,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b7deed', endColorstr='#b7deed',GradientType=0 ); /* IE6-9 */

	
}
#content{
float:left;
width:100%;
}
#footer{
width:100%;
text-align:center;
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
margin:5px;
margin-top:20px;
}

.ant-content{	
border-spacing:0;
border-collapse:collapse;
font-size:10pt;
height:460px;
}

.ant-content tbody{
display:block;
font-family:Arial;
height:460px;
overflow:hidden;
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
font-size:25pt;
color:#ffffff;
padding:10px;
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
font-size:20pt;
padding:10px;
}

.ant-wrapper, .ant-header, .ant-content, .ant-content tbody{
width:1350px;
}

.no{
font-size:18pt;
width:50px;
}
.alamat{
font-size:18pt;
width:450px;
}
.nama{
font-size:18pt;
width:450px;
}
.no_reg{
font-size:18pt;
width:450px;
}
.ant-content tbody > tr > td{
border-spacing:0;
border-collapse:collapse;
padding:10px;
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
font-size:23pt;
font-weight:bold;
padding:10px 10px 10px 10px;
}

.ant-close{
color:#666666;
font-family:Arial;
padding:5px;
visibility:hidden;
}
</style>
<script type="text/javascript" src="../plugin/jquery-1.8.2.js"></script>
<script type="text/javascript" src="../plugin/screenfull.js"></script>
<script type="text/javascript">


id_pasien = Array();
var tgl_skrg = "<?php echo date('j-n-Y');?>";
$.fn.infiniteScrollUp=function(n,x){
		var self=this,kids=self.children()
		kids.slice(x).hide()
		setInterval(function(){
			kids.filter(':hidden').eq(0).fadeIn()
			kids.eq(0).fadeOut(function(){
				$(this).appendTo(self)
				kids=self.children()
			})
		},n)
		return this
	}
$(function() {
	if(screenfull)
{
	screenfull.request($('div')[0]);
} else {
	alert('No');
}
	clear();
	<?php
	$result = pg_query("SELECT tc, tdesc FROM rs00001 WHERE tt = 'LYN' AND tc NOT IN('000','110','201','202','206','207','208','209','210')");
	while($row = pg_fetch_array($result)){
	?>
	createTable(<?php echo $row['tc'] ?>,'<?php echo $row['tdesc'] ?>');	
	update(<?php echo $row['tc'] ?>, 'ant-<?php echo $row['tc'] ?>');	
	$('#ant-<?php echo $row['tc'] ?> tbody').infiniteScrollUp(4000,0);
	$("#ant-wrapper-<?php echo $row['tc']?>").mouseover(function() { $("#ant-close-<?php echo $row['tc']?>").css('visibility','visible'); });
	$("#ant-wrapper-<?php echo $row['tc']?>").mouseout(function() { $("#ant-close-<?php echo $row['tc']?>").css('visibility','hidden'); });
	<?php
	}
	
	?>$('#content').infiniteScrollUp(7000,20);
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
	 },1000);
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
				$('#'+id_table+' tbody').append('<tr class="'+cls+'" id="'+poli+'-'+data[i].id+'"><td align="right" class="no">'+(i+1)+'</td>'+
				  '<td class="no_reg">'+data[i].id+'</td><td class="nama">'+data[i].nama+'</td><td class="nama">'+data[i].alamat+'</td></tr>');
			}
		id_pasien.push(poli+'-'+data[i].id);
		}
		if(data.length==0){
		  $('#ant-wrapper-'+poli).remove();
		}
		$('#jml_pasien-'+poli).html(data.length);
	  });
}

function createTable(id, header){
$('#content').append('<table class="ant-wrapper" id="ant-wrapper-'+id+'">'+
'<tr>'+
   '<td>'+
     '<table cellspacing="0" cellpadding="0" class="ant-header">'+
	'<tr><th align="center" colspan="4">'+header+'</th></tr>'+
	'<tr><td align="center" class="no">No.</td><td align="center" class="no_reg">No. Reg</td><td align="center" class="nama">Nama</td><td align="center" class="nama">Alamat</td></tr>'+
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
'<tr><td><a class="ant-close" id="ant-close-'+id+'" onclick=tutup("ant-wrapper-'+id+'")>X</a></td></tr>'+
'</table>');
}
</script>
<body>
<table width="100%">
  <tr>
      <td rowspan="3" align="right" width="15%"><img src="../<?=$set_client_logo?>" height="80%" width="80%""/></td>
	  <td align="center" width="65%"><font face="Arial" size="12"><b><?=$set_header[0];?></b></font></td>
	  <td rowspan="3" align="left" width="20%"><img src="../images/logo.png" height="100%" width="100%" /></td>
  </tr>
  <tr><td align="center"><font face="Verdana" size="6" style="text-align:center;"><?=$set_header[2];?></font></td></tr>
  <tr><td align="center"><font face="Verdana" size="6" style="text-align:center;"><?=$set_header[3];?></font></td></tr>
</table>
<div id="content"></div>
<div id="footer"><a>Copyright &copy; 2012 by<b>&nbsp;<i>One Medic</i></b> - All Right Reserved</a></div>
</body>
