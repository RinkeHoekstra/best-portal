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


function onButtonClick(p_oEvent){
	document.getElementById('q').value = this.get("value");
}

function stateChanged()
{
if (xmlhttp.readyState==4)
{
	document.getElementById("results").innerHTML=xmlhttp.responseText;
	var oTQOWLButton = new YAHOO.widget.Button("tqowl");
	oTQOWLButton.on("click", onButtonClick);
	var oTQSKOSButton = new YAHOO.widget.Button("tqskos");
	oTQSKOSButton.on("click", onButtonClick);
	var oWTQOWLButton = new YAHOO.widget.Button("wtqowl");
	oWTQOWLButton.on("click", onButtonClick);
	var oWTQSKOSButton = new YAHOO.widget.Button("wtqskos");
	oWTQSKOSButton.on("click", onButtonClick);
	var oMQButton = new YAHOO.widget.Button("mq");
	oMQButton.on("click", onButtonClick);
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