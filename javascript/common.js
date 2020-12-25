var i = 0;
var xmlHttp;
 
 function start(tablename)
 {
    var table = document.getElementsByTagName("table")[1]; 
    var zeile = -1;
    
    var row = table.insertRow(zeile); // -0 = am ende
    var cell;
    i = i + 1;
    if (i <= 5) {
        cell = row.insertCell(zeile);
        cell.innerHTML = "Titel:";

        cell = row.insertCell(zeile);
        cell.innerHTML = "<input type='text' name='Titel'>";

        row = table.insertRow(zeile);

        cell = row.insertCell(zeile);
        cell.innerHTML = "Bild:";

        cell = row.insertCell(zeile);
        cell.innerHTML = "<input type='file' name='Bild[]' accept='image/*' size='60'>";
    }
    else {
        alert("Es können nicht mehr als 5 Dateien gleichzeitig hochgeladen werden!\nBitte benutzen Sie für mehr Uploads die ZIP-Funktionalität!");
    }
}
function getAJAXData(Site, Param, Value){
    $.get(Site, { overview: Value } );
}

function showHint(str)
{
if (str.length==0)
  { 
	  document.getElementById("txtHint").innerHTML="";
	  hideshowLayer("searchResult", "hidden");
      hideshowLayer("txtHint", "hidden");
	  return;
  }
	xmlHttp=GetXmlHttpObject();
  if (xmlHttp==null)
  {
	  alert ("Browser does not support HTTP Request");
	  return;
  } 
	var url="content/search.php";
	url=url+"?q="+str;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
} 

function stateChanged() 
{ 
var closeButton = "<div align='right'><img src='images/icons/close.png' width='16px' height='16px' onClick='hideshowLayer(\"searchResult\",\"hidden\"); hideshowLayer(\"txtHint\",\"hidden\");'></div>";
    if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
     { 
        hideshowLayer("searchResult", "visible");
        hideshowLayer("txtHint", "visible");
        
        document.getElementById("txtHint").innerHTML=closeButton+xmlHttp.responseText;
     } 
}

function GetXmlHttpObject()
{
    var xmlHttp=null;
    try
    {
        // Firefox, Opera 8.0+, Safari
        xmlHttp=new XMLHttpRequest();
    }
    catch (e)
    {
        // Internet Explorer
        try
        {
            xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e)
        {
            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xmlHttp;
}


function SetCookie(Name, Wert, expiredays) {

    var ablauf = new Date();
    var infuenfTagen = ablauf.getTime() + (1 * 24 * 60 * 60 * 1000);
    

    ablauf.setTime(infuenfTagen);
    document.cookie = Name+"="+Wert+"; expires=" + ablauf.toGMTString();
}


/* jQuery Scripts */
$(document).ready(function() {
    $("#tabs").tabs();
    $(function() {
        $("#datepicker").datepicker({
            showOn: 'button',
            buttonImage: 'images/icons/calendar.png',
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        });
        $("#locale").change(function() { });
        $('#saving').dialog({
            autoOpen: false,
            modal: true,
            resizable: false,
            draggable: false,
            title: 'Einstellungen werden gespeichert...'
        });
        $('#login').dialog({
            autoOpen: false,

            modal: true,
            resizable: false,
            draggable: false,
            title: 'Login'
        });
        $('#upload').dialog({
            autoOpen: false,
            modal: true,
            resizable: false,
            draggable: false,
            title: 'Upload läuft...'
        });
    });
});
/* JQuery Scripts Ende */
    
    