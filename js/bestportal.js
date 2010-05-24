	


	
	
var div = document.getElementById('log_res');




function showComplexMapping(target) {
	var div = document.getElementById(target);
	
	var handleSuccess = function(o){

		if(o.responseText !== undefined){
			// div.innerHTML = "Transaction id: " + o.tId;
			// div.innerHTML += "HTTP status: " + o.status;
			// div.innerHTML += "Status code message: " + o.statusText;
			// div.innerHTML += "<li>HTTP headers: <ul>" + o.getAllResponseHeaders + "</ul></li>";
			div.innerHTML = o.responseText;
			// alert(o.responseText);
			// div.innerHTML += "Argument object: " + o.argument;
		}
	}

	var handleFailure = function(o){

		if(o.responseText !== undefined){
			div.innerHTML += "Transaction id: " + o.tId;
			div.innerHTML += "HTTP status: " + o.status;
			div.innerHTML += "Status code message: " + o.statusText;
			div.innerHTML += "<li>HTTP headers: <ul>" + o.getAllResponseHeaders + "</ul></li>";
			div.innerHTML += "Response text: " + o.responseText;
			div.innerHTML += "Argument object: " + o.argument;
		}
	}

	var callback =
	{
	  success: handleSuccess,
	  failure: handleFailure,
	  argument: ['foo','bar']
	};		
	
	
	// var bSubmit = window.confirm("Are you sure you want to submit this mapping?");
	// YAHOO.util.Event.preventDefault(p_oEvent);
	var lcDiv = document.getElementById('lc');
	// var tcDiv = document.getElementById('tc');

	
	var postData='type=complex';
	

	
	for (var i = 0; i < lcDiv.childNodes.length; i++) { 
		
	    var lc = lcDiv.childNodes[i];
		
		if(lc.nodeType == 1){//element of type html-object/tag
		  if(lc.tagName=="DIV"){
		    postData += '&'+lc.getAttribute("property")+'[]='+lc.getAttribute("uri");
		  }
		}

	}


	// This example facilitates a POST transaction.  The POST data(HTML form)
	// are initialized when calling setForm(), and it is automatically
	// included when calling asyncRequest.
	window.setTimeout(function() {
    		// YAHOO.util.Connect.setForm(formObject);
			
			var request = YAHOO.util.Connect.asyncRequest('POST', <?php print "'".$config->portal_url."'"; ?>+'/getmapping.php', callback, postData);
		}, 200);
}



function showOptionInfo(id,index){
	var div = document.getElementById('info');
	var select = document.getElementById(id);
	var option = select.options[index];
	var text = option.getAttribute('alt');
	div.innerHTML=text;
}

function showInfo(id){
	var div = document.getElementById('info');
	var src = document.getElementById(id);
	var text = src.getAttribute('alt');
	div.innerHTML=text;
}

function showQuery(text){
	var div = document.getElementById('q');
	div.innerHTML=text;
}

function addConcept(lctc,id){
	var div = document.getElementById(lctc);
	var index = document.getElementById(id).selectedIndex;
	if(index==0) return;
	
	var newdiv = document.createElement('div');
	var option = document.getElementById(id).options[index];
	var divIdName = option.value;
	var divURIFrag = option.title;
	var divLabel = option.label;
	var divNote = option.getAttribute('alt');
	
	if(!document.getElementById(divURIFrag)) {
		newdiv.setAttribute('uri',divIdName);
		newdiv.setAttribute('id',divURIFrag);
		newdiv.setAttribute('property',id);
		newdiv.setAttribute('class','concept');
		newdiv.innerHTML =  divLabel +' (<i>'+ id +"</i>) <a style=\"float: right;\" onClick=\"removeConcept(\'"+lctc+'\', \''+divURIFrag+'\')\">[x]</a>';
		if(divNote!=null&&divNote!=''){
			newdiv.innerHTML += '<a style=\"float: right;\" onClick=\"showOptionInfo(\''+id+'\',\''+index+'\')\">[?]</a>';
		}
		
		div.appendChild(newdiv);
	}
}

function removeConcept(lctc,id){
	var div = document.getElementById(lctc);
	var olddiv = document.getElementById(id);
	div.removeChild(olddiv);
}

function onFormSubmitACM() {
	var div = document.getElementById('log_res');
	
	var handleSuccess = function(o){

		if(o.responseText !== undefined){
			// div.innerHTML = "Transaction id: " + o.tId;
			// div.innerHTML += "HTTP status: " + o.status;
			// div.innerHTML += "Status code message: " + o.statusText;
			// div.innerHTML += "<li>HTTP headers: <ul>" + o.getAllResponseHeaders + "</ul></li>";
			div.innerHTML += o.responseText;
			// alert(o.responseText);
			// div.innerHTML += "Argument object: " + o.argument;
		}
	}

	var handleFailure = function(o){

		if(o.responseText !== undefined){
			div.innerHTML += "Transaction id: " + o.tId;
			div.innerHTML += "HTTP status: " + o.status;
			div.innerHTML += "Status code message: " + o.statusText;
			div.innerHTML += "<li>HTTP headers: <ul>" + o.getAllResponseHeaders + "</ul></li>";
			div.innerHTML += "Response text: " + o.responseText;
			div.innerHTML += "Argument object: " + o.argument;
		}
	}

	var callback =
	{
	  success: handleSuccess,
	  failure: handleFailure,
	  argument: ['foo','bar']
	};		
	
	
	// var bSubmit = window.confirm("Are you sure you want to submit this mapping?");
	// YAHOO.util.Event.preventDefault(p_oEvent);
	var lcDiv = document.getElementById('lc');
	var tcDiv = document.getElementById('tc');
	var cDiv = document.getElementById('comment');

	
	var postData='type=complex';
	
	for (var i = 0; i < lcDiv.childNodes.length; i++) { 
		
	    var lc = lcDiv.childNodes[i];
		
		if(lc.nodeType == 1){//element of type html-object/tag
		  if(lc.tagName=="DIV"){
		    postData += '&'+lc.getAttribute("property")+'[]='+lc.getAttribute("uri");
		  }
		}

	}
	for (var i = 0; i < tcDiv.childNodes.length; i++) { 
		
	    var tc = tcDiv.childNodes[i];
		
		if(tc.nodeType == 1){//element of type html-object/tag
		  if(tc.tagName=="DIV"){
		    postData += '&'+tc.getAttribute("property")+'[]='+tc.getAttribute("uri");
		  }
		}

	}

	postData += '&comment='+cDiv.value;



	// This example facilitates a POST transaction.  The POST data(HTML form)
	// are initialized when calling setForm(), and it is automatically
	// included when calling asyncRequest.
	window.setTimeout(function() {
    		// YAHOO.util.Connect.setForm(formObject);
			
			var request = YAHOO.util.Connect.asyncRequest('POST', <?php print "'".$config->portal_url."'"; ?>+'/addmapping.php', callback, postData);
		}, 200);
}




	function onFormSubmitAM(p_oEvent) {
		var handleSuccess = function(o){

			if(o.responseText !== undefined){
				// div.innerHTML = "Transaction id: " + o.tId;
				// div.innerHTML += "HTTP status: " + o.status;
				// div.innerHTML += "Status code message: " + o.statusText;
				// div.innerHTML += "<li>HTTP headers: <ul>" + o.getAllResponseHeaders + "</ul></li>";
				div.innerHTML += o.responseText;
				// div.innerHTML += "Argument object: " + o.argument;
			}
		}

		var handleFailure = function(o){

			if(o.responseText !== undefined){
				div.innerHTML = "Transaction id: " + o.tId;
				div.innerHTML += "HTTP status: " + o.status;
				div.innerHTML += "Status code message: " + o.statusText;
				div.innerHTML += "<li>HTTP headers: <ul>" + o.getAllResponseHeaders + "</ul></li>";
				div.innerHTML += "Response text: " + o.responseText;
				div.innerHTML += "Argument object: " + o.argument;
			}
		}

		var callback =
		{
		  success: handleSuccess,
		  failure: handleFailure,
		  argument: ['foo','bar']
		};		
		
		
		var bSubmit = window.confirm("Are you sure you want to submit this mapping?");
		YAHOO.util.Event.preventDefault(p_oEvent);

		var div = document.getElementById('log_res');
		// argument formId can be the id or name attribute value of the
		// HTML form, or an HTML form object.
		var formObject = document.getElementById('mappingForm');

		// This example facilitates a POST transaction.  The POST data(HTML form)
		// are initialized when calling setForm(), and it is automatically
		// included when calling asyncRequest.
		window.setTimeout(function() {
        		YAHOO.util.Connect.setForm(formObject);
				var request = YAHOO.util.Connect.asyncRequest('POST', <?php print "'".$config->portal_url."'"; ?>+'/addmapping.php', callback);
			}, 200);
	}
	


function init() {
	
	var tabView = new YAHOO.widget.TabView('besttabs');
	


        // Create a Button using an existing <input> element as a data source

	var oMappingSubmitButton = new YAHOO.widget.Button("submitbutton", { value: "Create Mapping" });
	 
	YAHOO.util.Event.on("mappingForm", "submit", onFormSubmitAM);
    
	
    
    YAHOO.example.container.explanationmm = new YAHOO.widget.Panel("explmm", {
        visible: false,
        fixedcenter: true,
		width: "500px",
        constraintoviewport:true
    });
    YAHOO.example.container.explanationmm.render();
    
    
    YAHOO.example.container.explanationref = new YAHOO.widget.Panel("explref", {
        visible: false,
        fixedcenter: true,
		width: "500px",
        constraintoviewport:true
    });
    YAHOO.example.container.explanationref.render();


    YAHOO.example.container.explanationform = new YAHOO.widget.Panel("explform", {
        visible: false,
        fixedcenter: true,
		width: "500px",
        constraintoviewport:true
    });
    YAHOO.example.container.explanationform.render();


	var oPushButtonMM = new YAHOO.widget.Button("showexplmm");
	var oPushButtonRef = new YAHOO.widget.Button("showexplref");
	var oPushButtonForm = new YAHOO.widget.Button("showexplform");

    YAHOO.util.Event.addListener("showexplmm", "click", YAHOO.example.container.explanationmm.show, YAHOO.example.container.explanationmm, true);
    YAHOO.util.Event.addListener("hideexplmm", "click", YAHOO.example.container.explanationmm.hide, YAHOO.example.container.explanationmm, true);
    
    YAHOO.util.Event.addListener("showexplref", "click", YAHOO.example.container.explanationref.show, YAHOO.example.container.explanationref, true);
    YAHOO.util.Event.addListener("hideexplref", "click", YAHOO.example.container.explanationref.hide, YAHOO.example.container.explanationref, true);

    YAHOO.util.Event.addListener("showexplform", "click", YAHOO.example.container.explanationform.show, YAHOO.example.container.explanationform, true);
    YAHOO.util.Event.addListener("hideexplform", "click", YAHOO.example.container.explanationform.hide, YAHOO.example.container.explanationform, true);

	var oSubmitButton = new YAHOO.widget.Button("search");


	
	var oMappingmodeButtonGroup = new YAHOO.widget.ButtonGroup("mappingmode"); 
	var oConjDisjButtonGroup = new YAHOO.widget.ButtonGroup("conjunctiondisjunction");
	var oNarrowerButton = new YAHOO.widget.Button("includenarrower");

}
