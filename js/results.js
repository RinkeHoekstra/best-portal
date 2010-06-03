

start = 0;
timeline = '';
filter = {"procedure_soort":null,"instantie":null,"sector_toon":null,"rechtsgebied_rechtspraak":null,"set":false};
mr = {"mapping":[],"query":null};
docs = '';
hl = '';
map = {};
markersArray = [];

function getResults(){
    doReset();
    var div = document.getElementById('results');
    
    printSearching();
    
    var handleSuccess = function(o){
        if(o.responseText !== undefined) {
            doReset();
            doUpdate(o.responseText);
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
	  argument: ['test','xml']
	};
	
	if(mr.query!=null) {
        var postData = 'q='+unescape(mr.query);
    } else {
        var postData = 'q=';
    }
	window.setTimeout(function() {
			var request = YAHOO.util.Connect.asyncRequest('POST', 'getresults.php', callback, postData);
		}, 200);
}

function doReset() {
    var resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = '';
}

function doUpdateFilter(fstring) {
    doReset();
    fstring = unescape(fstring);
	var newFilter = JSON.parse(fstring);
	if(newFilter.procedure_soort != null) filter.procedure_soort = newFilter.procedure_soort;
	if(newFilter.instantie != null) filter.instantie = newFilter.instantie;
	if(newFilter.sector_toon != null) filter.sector_toon = newFilter.sector_toon;
	if(newFilter.rechtsgebied_rechtspraak != null) filter.rechtsgebied_rechtspraak = newFilter.rechtsgebied_rechtspraak;
	if(filter.procedure_soort == null && filter.instantie == null && filter.sector_toon == null & filter.rechtsgebied_rechtspraak == null ) {
	    filter.set = false;
	} else { 
	    filter.set = true;
	}
	printFilter();
    updateResults();
}

function resetFilter(){
    filter = {"procedure_soort":null,"instantie":null,"sector_toon":null,"rechtsgebied_rechtspraak":null,"set":false};
    printFilter();
    updateResults();
}


function printFilter(){
    var filterDiv = document.getElementById('filter');
    filterDiv.innerHTML = '';
    if(filter.set) {
    	if(filter.procedure_soort != null) {
    	    f = document.createElement('div');
    	    f.setAttribute('class','inlinefilter');
    	    f.innerHTML = filter.procedure_soort;
    	    filterDiv.appendChild(f);
    	}
    	if(filter.instantie != null) {
    	    f = document.createElement('div');
    	    f.setAttribute('class','inlinefilter');
    	    f.innerHTML = filter.instantie;
    	    filterDiv.appendChild(f);	    
    	}
    	if(filter.sector_toon != null) {
    	    f = document.createElement('div');
    	    f.setAttribute('class','inlinefilter');
    	    f.innerHTML = filter.sector_toon;
    	    filterDiv.appendChild(f);	    
        }
    	if(filter.rechtsgebied_rechtspraak != null) {
    	    f = document.createElement('div');
    	    f.setAttribute('class','inlinefilter');
    	    f.innerHTML = filter.rechtsgebied_rechtspraak;
    	    filterDiv.appendChild(f);	    
    	}
	    resetDiv = document.createElement('div');
	    resetDiv.setAttribute('id','filterreset');
	    resetDiv.setAttribute('style','display:inline');
	    resetDiv.innerHTML = '[<a onclick=\'resetFilter();\'>x</a>]';
	    filterDiv.appendChild(resetDiv);
    }
	
}

function doUpdate(resultsText) {
    var cases = JSON.parse(resultsText);
    
    if(cases.query != null) {
        docs = cases.solr.response.docs;
        hl = cases.solr.highlighting;
        updateResults();
        updateTimeline(cases.timeline,cases.latestdate);
        updateMap(cases.places,cases.courts);
    } else {
        docs = [];
        hl = {};
	    filter = {"procedure_soort":null,"instantie":null,"sector_toon":null,"rechtsgebied_rechtspraak":null,"set":false};
	    printFilter();        
        printNoResults();
        updateTimeline(null,null);
        updateMap(null,null);
    }
}


function printNoResults(){
    var resultsDiv = document.getElementById('results');
    var sDiv = document.createElement('div');
    sDiv.setAttribute('class','noresult');
    var h = document.createElement('h4');
    h.innerHTML = '(Nog) geen uitspraken gevonden ...';
    var p = document.createElement('div');
    p.setAttribute('class','noresultexplanation');
    p.innerHTML = 'Dit komt waarschijnlijk door een onvolledige beschrijving van uw zaak. Selecteer de factoren die meespelen in uw zaak. Zodra er een vertaling naar juridisch vocabulair beschikbaar is, zal gezocht worden naar relevante uitspraken.'
    sDiv.appendChild(h);
    sDiv.appendChild(p);
    resultsDiv.appendChild(sDiv);
}


function printSearching(){
    var div = document.getElementById('results');
    var sDiv = document.createElement('div');
    sDiv.setAttribute('class','searching');
    var h = document.createElement('h4');
    h.innerHTML = 'Bezig met zoeken ...';
    var p = document.createElement('div');
    p.setAttribute('class','searchexplanation');
    p.innerHTML = 'Dit kan enige tijd duren aangezien alle zoekresultaten in uw browser worden geladen.';
    sDiv.appendChild(h);
    sDiv.appendChild(p);
    div.appendChild(sDiv);
}


function printLaymanConcepts(concepts) {
    var lcDiv = document.getElementById('laymanconcepts');    
    
    lcDiv.innerHTML = concepts;
}

function getLaymanConcepts(){
    var div = document.getElementById('laymanconcepts');    
    var handleSuccess = function(o){
        if(o.responseText !== undefined) {
            printLaymanConcepts(o.responseText);
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
	  argument: ['test','xml']
	};
	
	
	window.setTimeout(function() {
			var request = YAHOO.util.Connect.asyncRequest('POST', 'getlaymanconcepts.php', callback, '');
		}, 200);
}

function updateResults(){
    doReset();
    var resultsDiv = document.getElementById('results');
	if (docs.length == 0) {
	    printNoResults();
	} else {
        for (var i = 0; i < docs.length; i++) { 
    		// docs is a global variable
    	    var doc = docs[i];
		
    		if(!filter.set){
        		var nd = document.createElement('div');
        		nd.setAttribute('id',doc.ljn);
        		nd.innerHTML = getDescription(doc);
        		resultsDiv.appendChild(nd);
    	    } else {
    	        if(doc.procedure_soort == filter.procedure_soort || filter.procedure_soort == null) {
        	        if(doc.instantie == filter.instantie || filter.instantie == null) {
        	            if(doc.sector_toon == filter.sector_toon || filter.sector_toon == null) {
            	            if(doc.rechtsgebied_rechtspraak == filter.rechtsgebied_rechtspraak || filter.rechtsgebied_rechtspraak == null) {
                        		var nd = document.createElement('div');
                        		nd.setAttribute('id',doc.ljn);
        		                nd.innerHTML = getDescription(doc);
                        		resultsDiv.appendChild(nd);
                	        } 
            	        } 
        	        } 
    	        } 
    	    }
    	}	
    }
}


function getDescription(doc) {
    
    var c = "<table style=\'border: 0px; width: 100%; padding-bottom: 2em; padding-right: 1em;\'>\n";
    // Show LJN number
	c += "<tr><td class=\'resultheader\' style=\'width: 100px\' colspan=\'2\'><span style=\'padding-left: 74px; vertical-align: top;\'>LJN "+doc.ljn+"</span>";
	// Show RDF logo + link
    c += "<span style=\'display: inline; vertical-align: top; padding-left: 5px;\'><a class=\'imglink\' target=\'_blank\' href=\'"+doc.rnl_ljn+"\'><img src=\'img/rdf_flyer.png\'/></a></span>";
	// Show Marbles logo + link http://www5.wiwiss.fu-berlin.de/marbles?lang=en&uri=
    c += "<span style=\'display: inline; vertical-align: top; padding-left: 5px;\'><a class=\'imglink\' target=\'_blank\' href=\'http://www5.wiwiss.fu-berlin.de/marbles?lang=en&uri="+doc.rnl_ljn+"\'><img src=\'img/marbles.png\'/></a></span>";
	// Show Rechtspraak logo + link to LJN index
    c += "<span style=\'display: inline; vertical-align: top; padding-left: 5px;\'><a class=\'imglink\' target=\'_blank\' href=\'http://www.ljn.nl/"+doc.ljn+"\'><img src=\'img/rechtspraak.png\'/></a></span>";
    // Show Score
	c += "<span style=\'float: right; font-weight: normal;\'>"+doc.score.toFixed(2)+"</span><td></tr>\n";
	// Show Data
	c += "<tr<td class=\'result_attribute\' style=\'width: 100px\'>Datum</td><td class=\'result_value\' >"+doc.datum_uitspraak+"</td></tr>\n";
	// Show Description
	c += "<tr><td class=\'result_attribute\' style=\'width: 100px\'>Kenmerken</td><td class=\'result_value\'>Uitspraak in <a onClick=\"doUpdateFilter(\'"+escape('{\"procedure_soort\":\"'+doc.procedure_soort+'\"}')+"\')\">"+doc.procedure_soort+"</a> van <a onClick=\"doUpdateFilter(\'"+escape('{\"instantie\":\"'+doc.instantie+'\"}')+"\')\">"+doc.instantie+"</a>";
    if (doc.sector_toon != null) {
        c += " (<a onClick=\"doUpdateFilter(\'"+escape('{\"sector_toon\":\"'+doc.sector_toon+'\"}')+"\')\">"+doc.sector_toon+"</a>)";
    }
    c += " binnen het rechtsgebied <a onClick=\"doUpdateFilter(\'"+escape('{\"rechtsgebied_rechtspraak\":\"'+doc.rechtsgebied_rechtspraak+'\"}')+"\')\">"+doc.rechtsgebied_rechtspraak+"</a></td></tr>\n";
	// Show Highlight
	c += "<tr><td class=\'result_attribute\' style=\'width: 100px\'>Relevante tekst</td><td class=\'result_value\'>"+hl[doc.ljn].uitspraak_anoniem[0]+"</td></tr>\n"
	// Show Indicatie (note)
	if (doc.indicatie != null) {
	    c += "<tr><td class=\'result_attribute\' style=\'width: 100px\'>Beschrijving</td><td class=\'result_value\'>"+doc.indicatie+"</td></tr>\n";
	}
	// Show link to full text
	c += "<tr><td></td><td style=\'padding-top: 1ex; text-align: right;\'><a href=\'show.php?q="+mr.query+"&ljn="+doc.ljn+"' target=\'_blank\'>Volledige Tekst</a></td></tr>\n";
	c += "</table></div>";
	
	return c;
}

function addInlineConcept(id){
	var div = document.getElementById('lc');
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
		newdiv.setAttribute('class','inlineconcept');
		newdiv.innerHTML =  divLabel +' (<i>'+ id +'</i>) [<a onClick="removeInlineConcept(\''+divURIFrag+'\')">x</a>]';
		if(divNote!=null&&divNote!=''){
			newdiv.innerHTML += '[<a onClick=\"showOptionInfo(\''+id+'\',\''+index+'\')\">?</a>]';
		}
		
		div.appendChild(newdiv);
	}
	
	document.getElementById(id).selectedIndex = 0;
}

function removeInlineConcept(id){
	var div = document.getElementById('lc');
	var olddiv = document.getElementById(id);
	div.removeChild(olddiv);
	getMapping();
}



function getMapping() {
	var div = document.getElementById('tc');
	
	var handleSuccess = function(o){

		if(o.responseText !== undefined){
		    
		    // only update if the mapping returns new results
		    if(o.responseText != JSON.stringify(mr)) {
		        mr = JSON.parse(o.responseText);
                showMapping();
                if(mr.query != null ) {
                    getResults();
                } else {
                    doReset();
                    docs = [];
                    hl = [];
            	    filter = {"procedure_soort":null,"instantie":null,"sector_toon":null,"rechtsgebied_rechtspraak":null,"set":false};
            	    printFilter();
                    clearMap();
                    clearTimeline();
                    printNoResults();
                }
            }   
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

	
	var postData='type=lwcomplex';
	

	
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
			
			var request = YAHOO.util.Connect.asyncRequest('POST', 'getjsonmapping.php', callback, postData);
		}, 200);
}

function showMapping() {
    var tcDiv = document.getElementById('tc');
    tcDiv.innerHTML = '';
    tcs = mr.mapping;
    
    for(i = 0; i<tcs.length; i++){
        tc = tcs[i];
        var d = document.createElement('div');
        d.setAttribute('id',tc.id);
        d.setAttribute('class','inlinelegalconcept');
        d.innerHTML = tc.label;
        if(tc.note != null) {
            d.innerHTML += ' <a title="'+tc.note+'">?</a>';
        }
        tcDiv.appendChild(d);
    }
    
}


function clearTimeline(){
    updateTimeline(null,null)
}

function updateTimeline(events,latestdate){
    if(events==null) {
        tlDiv = document.getElementById("timeline");
        tlDiv.innerHTML = "";
        return;
    }
    var eventSource = new Timeline.DefaultEventSource();
    
    var bandInfos = [
      Timeline.createBandInfo({
          eventSource:    eventSource,
          date:           latestdate,
          width:          "70%", 
          intervalUnit:   Timeline.DateTime.MONTH, 
          intervalPixels: 100
      }),
      Timeline.createBandInfo({
          overview:       true,
          eventSource:    eventSource,
          date:           latestdate,
          width:          "30%", 
          intervalUnit:   Timeline.DateTime.YEAR, 
          intervalPixels: 110
      })
    ];
    bandInfos[1].syncWith = 0;
    bandInfos[1].highlight = true;
    
    var url = '.';
    
    tl = Timeline.create(document.getElementById("timeline"), bandInfos);
    eventSource.loadJSON(events, url);
    tl.layout();
}

function initMap() {
    var myOptions = {
      zoom: 6,
      center: new google.maps.LatLng(52, 5.5),
      mapTypeId: google.maps.MapTypeId.TERRAIN
    }
    map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
}

function updateMap(places, courts){
    clearMap();
    setMarkers(map, places, 'img/place.png');
    setMarkers(map, courts, 'img/court.png');
}

function clearMap(){
    for(var i=0; i<markersArray.length; i++){
        markersArray[i].setMap(null);
    }
    markersArray = new Array();
}

function setMarkers(map, locations, img) {
  // Add markers to the map
  if(locations == null) {
      return;
  }
  for (var i = 0; i < locations.length; i++) {
    var loc = locations[i];
    var myLatLng = new google.maps.LatLng(loc[1], loc[2]);
	var contentString = loc[3];
	var infowindow = new google.maps.InfoWindow({
	    content: contentString
	});
	var image = new google.maps.MarkerImage(img,
	    // This marker is 20 pixels wide by 32 pixels tall.
	    new google.maps.Size(26, 26),
	    // The origin for this image is 0,0.
	    new google.maps.Point(0,0),
	    // The anchor for this image is its center.
	    new google.maps.Point(13, 13));

	createMarker(map, loc, myLatLng, infowindow, image);
  }
}

function createMarker(map, place, myLatLng, infowindow, image){
	var marker = new google.maps.Marker({
        position: myLatLng,
		icon: image,
        map: map,
        title: place[0],
    });
    markersArray.push(marker); 
	google.maps.event.addListener(marker, 'click', function() {
	  infowindow.open(map,marker);
	});
}

resizeTimerID = null;
function onResize() {
    if (resizeTimerID == null) {
        resizeTimerID = window.setTimeout(function() {
            resizeTimerID = null;
            tl.layout();
        }, 500);
    }
}

