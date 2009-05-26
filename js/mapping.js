var xmlhttp;


function showMapping(id)
{
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
	  alert ("Browser does not support HTTP Request");
	  return;
	  }	
	var url="getmapping.php";
	url=url+"?q=";
	for (var j=0; j<id.options.length; j++)
				if (id.options[j].selected)
					url += id.options[j].value+',';
		
	url=url+"&sid="+Math.random();
	xmlhttp.onreadystatechange=stateChanged;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);

	        // sndReq('common/saveInd.php'+q,'sd'+form.count.value); // this would be a call to an ajax request
	
}


function stateChanged()
{
if (xmlhttp.readyState==4)
{
document.getElementById("mapping").innerHTML=xmlhttp.responseText;
}
}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}