
	
	
<?php

require_once "config/class.Config.php";

$config = new Config();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
      xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
      xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
      xmlns:cc="http://creativecommons.org/ns#"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:foaf="http://xmlns.com/foaf/0.1/">
<head profile="http://www.w3.org/1999/xhtml/vocab"> 
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
<title>JustFind/BestPortal - (c) 2010 Rinke Hoekstra, Vrije Universiteit Amsterdam</title> 

<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/fonts/fonts-min.css" />

<!-- <script type="text/javascript" src="js/mapping.js"></script> -->
<script type="text/javascript" src="js/dateformat.js"></script>


<link rel="stylesheet" type="text/css" href="js/yui/container/assets/skins/sam/container.css" />
<link rel="stylesheet" type="text/css" href="js/yui/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="js/yui/tabview/assets/skins/sam/tabview.css" />
<link rel="stylesheet" href="style.css" type="text/css" media="screen">


<script type="text/javascript" src="js/yui/yahoo/yahoo-min.js"></script>
<script type="text/javascript" src="js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="js/yui/event/event-min.js"></script>
<script type="text/javascript" src="js/yui/connection/connection-min.js"></script>
<script type="text/javascript" src="js/yui/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="js/yui/container/container-min.js"></script>
<script type="text/javascript" src="js/yui/element/element-min.js"></script>
<script type="text/javascript" src="js/yui/button/button-min.js"></script>
<script type="text/javascript" src="js/yui/tabview/tabview-min.js"></script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 

<script type="text/javascript">
Timeline_ajax_url="http://localhost/best-portal/js/timeline_ajax/simile-ajax-api.js";
Timeline_urlPrefix='http://localhost/best-portal/js/timeline_js/';
Timeline_parameters='bundle=true';
</script>
<script src="http://localhost/best-portal/js/timeline_js/timeline-api.js" type="text/javascript">
</script>

<script type="text/javascript" src="js/results.js"></script>


<script type="text/javascript">
YAHOO.namespace("example.container");

function initialize() {
	initMap();
	getResults();
	getLaymanConcepts();
}

</script> 


</head>
<body style="margin:0px; padding:0px;" onload="initialize()" onunload="saveTrace()"> 
	<div id="smallbanner" style='width: 100%;'>
		<img src="<?php print $config->portal_url;?>/img/best-logo-96dpi-40px.png" width="40" align="right" alt="BEST logo" valign="top" style="padding-right: 15px;"/>
	    <div class="smallbannerheading">JustFind</div>
		<div class="smallbannersubheading">Juridisch zoeken voor de normale mens...</div>
		<div class="copyrightnoticesmall">
			JustFind/BestPortal is copyright (c) 2010, Rinke Hoekstra, Vrije Universiteit Amsterdam
		</div>
	</div>
	
	<div class="colmasklm leftmenu"> 
	    <div class="colrightlm"> 
	        <div class="col1wraplm"> 
	            <div class="col1lm"> 
					<table id='qtable'>
					<tr><td style='width: 60px;'>Normaal</td><td>:<div id='lc' style='display: inline;'></div></td></tr>
					<tr><td style='width: 60px;'>Juridisch</td><td>:<div id='tc' style='display: inline'><div id='tc_query' style='display: none;'></div></div></td></tr>
					</table>
	            </div> 
	        </div> 
	        <div class="col2lm"> 
				<h4>Beschrijving gebeurtenis</h4>
	        </div> 
	    </div> 
	</div>
	<div class="colmasklm leftmenu"> 
	    <div class="colrightlm"> 
	        <div class="col1wraplm"> 
	            <div class="col1lm"> 
					<table>
					<tr><td style='width: 60px;'>Filters</td><td>:<div id='filter' style='display: inline;'></div></td></tr>
					</table>
	            </div> 
	        </div> 
	        <div class="col2lm"> 
				<h4>Inperking zoekvraag</h4>
	        </div> 
	    </div> 
	</div>
	
	<div class="colmask holygrail">
		<div class="colmid">
			<div class="colleft">
				<div class="col1wrap">
					<div class="col1" id="results">
						<h4 style='text-align: left;'>Gevonden Uitspraken</h4>
					</div>
				</div>
				<div class="col2" style='text-align: left;'>
					<div id="laymanconcepts">
					</div>
				</div>
				<div class="col3">
					<h4 style='text-align: left;'>Zoekresultaten op de Kaart</h4>
					<div id="map_canvas" style="height:300px"></div> 
					<h4 style='text-align: left; padding-top: 15px;'>Zoekresultaten in de Tijd</h4>
					<div id="timeline" style="height:300px;"></div>
				</div>
		</div>
	</div>

</body> 
</html>