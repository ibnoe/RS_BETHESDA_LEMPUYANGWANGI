var xmlhttp = createRequestObject();
function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}

function dinamis_group_tbag(combobox,id)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'getdata/getsubsatu.php?tt='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById(id).innerHTML = xmlhttp.responseText;
			 
        }
		return false;
		
    }
    xmlhttp.send(null);
}

function dinamis_group_ttindakan(combobox,id)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'getdata/getsubdua.php?tt='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById(id).innerHTML = xmlhttp.responseText;
			  
        }
		return false;
     
    }
    xmlhttp.send(null);
}

function unit_medis(combobox,id)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'getdata/getsubtiga.php?tt='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById(id).innerHTML = xmlhttp.responseText;
			  
        }
		return false;
     
    }
    xmlhttp.send(null);
}

function rl4()
{
    
    xmlhttp.open('get', 'includes/test.php', true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("rl4").innerHTML = xmlhttp.responseText;
			
        }
		else
		{
		document.getElementById("rl4").innerHTML = "<div align='center'><table border='0' width='100%'><tr><td><p align='center'><img border='0' src='spinner.gif' width='60' height='60'><h2 align='center'>Mohon Tunggu Sejenak...</h2><h2 align='center'>Sedang Mempersiapkan Laporan</h2></td></tr></table></div>";
		}
		return false;
       
    }
    xmlhttp.send(null);
}

function ambilpangkat(combobox,id)
{
var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'getdata/getsubempat.php?tt='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById(id).innerHTML = xmlhttp.responseText;
			  
        }
		return false;
     
    }
    xmlhttp.send(null);
	}

