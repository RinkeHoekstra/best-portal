<?php

require_once "config/class.Config.php";

$config = new Config();
?>

<html> 
<head> 
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
<title>Best Portal - Zoekresultaten</title> 

<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/fonts/fonts-min.css" />

<script type="text/javascript" src="js/mapping.js"></script>


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
	q = "<?php print $_GET['q']; ?>";
	getResults();
	getLaymanConcepts();
}

</script> 


</head>
<body style="margin:0px; padding:0px;" onload="initialize()"> 
	<div id="smallbanner" style='width: 100%;'>
		<img src="<?php print $config->portal_url;?>/img/best-logo-96dpi-40px.png" width="40" align="right" alt="BEST logo" valign="top" style="padding-right: 15px;"/>
	    <div class="smallbannerheading">BestPortal</div>
		<div class="smallbannersubheading">Juridisch zoeken voor de normale mens...</div>
		<div class="copyrightnotice">
			Copyright (c) 2010, Rinke Hoekstra, Vrije Universiteit Amsterdam
		</div>
	</div>
	
	<div class="colmasklm leftmenu"> 
	    <div class="colrightlm"> 
	        <div class="col1wraplm"> 
	            <div class="col1lm"> 
					<table>
					<tr><td style='width: 60px;'>Normaal</td><td>:<div id='lc' style='display: inline;'></div></td></tr>
					<tr><td style='width: 60px;'>Juridisch</td><td>:<div id='tc' style='display: inline'><div id='tc_query' style='display: none;'></div></div></td></tr>
					</table>
	            </div> 
	        </div> 
	        <div class="col2lm"> 
				<h4>Beschrijving</h4>
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
				<h4>Inperking</h4>
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
					<!-- <h4>Eigenschappen</h4>
					Eigenschappenselectie -->
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