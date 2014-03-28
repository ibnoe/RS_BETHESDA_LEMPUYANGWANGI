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
margin-bottom:50px;
}

.ant-content{	
border-spacing:0;
border-collapse:collapse;
font-size:10pt;
height:530px;
}

.ant-content tbody{
display:block;
font-family:Arial;
height:530px;
overflow:hidden;
}

.ant-close{
color:#666666;
font-family:Arial;
padding:5px;
visibility:hidden;
width:100%;
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
font-size:18pt;
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
font-size:18pt;
padding:10px;
}

.ant-wrapper, .ant-header, .ant-content, .ant-content tbody{
width:1400px;
}

.no{
font-size:18pt;
width:50px;
}
.no_reg{
font-size:18pt;
text-align:center;
width:150px;
}
.alamat{
font-size:18pt;
width:450px;
}
.nama{
font-size:18pt;
width:400px;
}
.poli{
font-size:18pt;
width:350px;
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
font-size:12pt;
padding:20px 10px 20px 10px;
width:100%;
}
</style>
<script type="text/javascript" src="../plugin/jquery-1.8.2.js"></script>
<script type="text/javascript">
id_pasien = Array();
id_poli = Array();
j=1;
var tgl_skrg = "<?php echo date('j-n-Y');?>";
$.fn.infiniteScrollUp=function(n){
		var self=this,kids=self.children()
		kids.slice(5).hide()
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
	clear();
	<?php
	//$result = pg_query("SELECT id, bangsal FROM rs00012 WHERE is_group = 'Y' AND klasifikasi_tarif_id = '-' AND bangsal NOT IN('IGD') LIMIT 1 OFFSET 0");
	//while($row = pg_fetch_array($result)){
	?>
	createTable('rj','rj');	
	update('rj', 'ant-rj');	
	$('#ant-rj tbody').infiniteScrollUp(6000);
	<?php
	//}
	
	?>//$('#content').infiniteScrollUp(7000);
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
function update(bangsal, id_table) {	
	var cls;
	 setTimeout(function(){
		update(bangsal, id_table);
	 },4000);
 $.ajax({
  type: "POST",
  url: "get_pasien_rj.php",
  dataType : 'json',
  data: { bangsal : bangsal }
	}).fail(function( msg ) {
  		//alert( "Gagal Mengambil Data !!!!" );
	}).done(function (data){
		if(($("#ant-wrapper-rj").length < 1) && (data.length>0)){
		createTable('rj','rj');	
		$('#ant-rj tbody').infiniteScrollUp(6000);
		}
		for(i=0;i<data.length;i++){
			if(id_pasien.indexOf(bangsal+'-'+data[i].id)==-1){
				
				cls = (j%2==0) ? cls = "odd": cls = "even";	  
				var exist = $('.'+bangsal+'-'+data[i].id_poli).length;   
				if(exist==0){  
				
					if(id_poli.length<1){
						$('#'+id_table+' tr:last').after('<tr class="'+cls+' '+bangsal+'-'+data[i].id_poli+'" id="'+bangsal+'-'+data[i].id+'"><td align="right" class="no_reg"><b>'+data[i].id+'</b></td><td class="nama"><b>'+data[i].nama+'</b></td><td align="center" class="poli"><b>'+data[i].poli+'</b></td><td class="alamat"><b>'+data[i].alamat+'</b></td></tr>');
					}
					else{
						$('.'+id_poli[id_poli.length-1]+' :last').after('<tr class="'+cls+' '+bangsal+'-'+data[i].id_poli+'" id="'+bangsal+'-'+data[i].id+'"><td align="right" class="no_reg"><b>'+data[i].id+'</b></td><td class="nama"><b>'+data[i].nama+'</b></td><td align="center" class="poli"><b>'+data[i].poli+'</b></td><td class="alamat"><b>'+data[i].alamat+'</b></td></tr>');
					}
				}
				else{
					$('.'+bangsal+'-'+data[i].id_poli+' :last').after('<tr class="'+cls+' '+bangsal+'-'+data[i].id_poli+'" id="'+bangsal+'-'+data[i].id+'"><td align="right" class="no_reg"><b>'+data[i].id+'</b></td><td class="nama"><b>'+data[i].nama+'</b></td><td align="center" class="poli"><b>'+data[i].poli+'</b></td><td class="alamat"><b>'+data[i].alamat+'</b></td></tr>');
				}
				j++;
			}
		id_poli.push(bangsal+'-'+data[i].id_poli);
		id_pasien.push(bangsal+'-'+data[i].id);
		}
		$('#jml_pasien-'+bangsal).html(data.length);
		if(data.length==0){
		  $('#ant-wrapper-'+bangsal).remove();
		}
	  });
}

function createTable(id, header){
$('#content').append('<table class="ant-wrapper" id="ant-wrapper-'+id+'">'+
'<tr>'+
   '<td>'+
     '<table cellspacing="0" cellpadding="0" class="ant-header">'+
	'<tr><th align="center" colspan="5">DAFTAR PASIEN POLIKLINIK</th></tr>'+
	//'<tr><td align="center" class="no"><b>NO.</b></td><td align="center" class="no_reg"><b>NO. REG</b></td><td align="center" class="nama"><b>NAMA</b></td><td align="center" class="poli"><b>POLI</b></td><td align="center" class="alamat"><b>ALAMAT</b></td></tr>'+
	'<tr><td align="center" class="no_reg"><b>NO. REG</b></td><td align="center" class="nama"><b>NAMA</b></td><td align="center" class="poli"><b>POLI</b></td><td align="center" class="alamat"><b>ALAMAT</b></td></tr>'+
     '</table>'+
   '</td>'+
'</tr>'+
'<tr>'+
   '<td>'+
     '<table cellspacing="0" cellpadding="0" class="ant-content" id="ant-'+id+'">'+
	'<tbody>'+	 
	'<tr><td align="center" class="ant-footer" colspan="5">JUMLAH PASIEN : <span id="jml_pasien-'+id+'"></span></td></tr>'+
	 '</tbody>'+
     '</table>'+
   '</td>'+
'</tr>'+
'<tr>'+
'</tr>'+
'<tr>'+
'</table>');
}
</script>
<body>
<table width="100%">
  <tr>
      <td rowspan="3" align="right" width="15%"><img src="../<?=$set_client_logo?>" height="50%" width="50%"/></td>
	  <td align="center" width="70%"><font face="Arial" size="12"><b><?=$set_header[0];?></b></font></td>
	  <td rowspan="3" align="left" width="15%"><img src="../images/logo.png" height="100%" width="100%" /></td>
  </tr>
  <tr><td align="center"><font face="Verdana" size="6" style="text-align:center;"><?=$set_header[2];?></font></td></tr>
  <tr><td align="center"><font face="Verdana" size="6" style="text-align:center;"><?=$set_header[3];?></font></td></tr>
</table>
<div id="content"></div>
<div id="footer"><a>Copyright &copy; 2012 by<b>&nbsp;<i>One-Medic.net</i></b> - All Right Reserved</a></div>
</body>
