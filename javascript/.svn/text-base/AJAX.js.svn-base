var xmlHttp;

function stateChanged() 
{ 
    if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
     { 
        document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
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