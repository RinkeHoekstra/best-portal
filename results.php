<?php

require_once "lib/Solr/Service.php";
require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";
require_once 'lib/class.ConceptTree.php';
require_once "config/class.Config.php";

$config = new Config();
$ct = new ConceptTree();


$solr = new Apache_Solr_Service('localhost',8983,'/solr/');
$query = $_GET["q"];
$start = $_GET["s"];

if($query == null) $query = "gemeente rhenen";
if($start == null) $start = 0;

$params['fl'] = 'score';
$results = $solr->search($query,$start,10,$params);

// print_r($results);

$sc = new SPARQLConnection();
$ns = new Namespaces();

$latest_date = "1980-05-19T00:00:00Z";


if ($results){
	// print $results->response->numFound;

	$json = "{'dateTimeFormat': 'iso8601',\n'events': [\n";	
	foreach ($results->response->docs as $doc) {
		$rnl_ljn =  $doc->rnl_ljn;
		$ljn = $doc->ljn;
		$src = $doc->metalex_src;
		$ind = utf8_decode($doc->indicatie);
		$ins = $doc->instantie;
		$rgb = $doc->rechtsgebied_rechtspraak;
		$sector = $doc->sector_toon;
		$zaaknummers = $doc->zaaknummers;
		$procedure = $doc->procedure_soort;
		$date = $doc->datum_uitspraak;
		$hrdate = substr($date,0,10);
		$score = $doc->score;
		
		
		if($ins!="") $instanties[$ins] = $doc->rnl_instantie;
		if($rgb!="") $rechtsgebieden[$rgb] = $doc->rnl_rechtsgebied_rechtspraak;
		if($sector!="") $sectoren[$sector] = $doc->rnl_sector_toon;
		if($procedure!="") $procedures[$procedure] = $doc->rnl_procedure_soort;
		
		
		$ind=nl2br($ind);
		$ind=str_replace("\n", " ", $ind);
		$ind=str_replace("\r", " ", $ind);
		$ind=str_replace("\"", "\\\"", $ind);
		$ind=str_replace("'", "\\'", $ind);



		$src=str_replace("\t", "", $src);
		$src=str_replace("\n", "", $src);
		$src=str_replace("\r", "", $src);

		// Create JSON object for the timeline
		$json .= "{'start':\"".$date."\",'title':\"".$ljn."\",'durationEvent':false, 'link':\"".$src."\"";
		if($ind != null){ $json .= ", 'description': \"".$ind."\""; }
		$json .= "},\n";

		// if($doc->datum_gepubliceerd!=null){	
		// 	$json .= "{'start':\"".$doc->datum_gepubliceerd."\",'title':\"".$doc->ljn." (publicatie)\",'durationEvent':false},\n";
		// }
		
		// Set the latest date to the latest publication date (for orienting the timeline)
		if($doc->datum_gepubliceerd > $latest_date) $latest_date = $doc->datum_gepubliceerd;
		
		// Get all places 'mentioned' in the uitspraak
		$q = $ns->sparql." select ?n ?lat ?long where { <".$rnl_ljn."> rnl:locatie ?p . ?p gn:name ?n . ?p geo:lat ?lat . ?p geo:long ?long .}";
		
		// print $q;
		
		$rows = $sc->query($q, 'rows');
		// print_r($rows);
				// exit(0);
		foreach ($rows as $row) {
			$places[$row['n']]['lat'] = $row['lat'];
			$places[$row['n']]['long'] = $row['long'];
			$places[$row['n']]['ljns'][$ljn]['src'] = $src;
			$places[$row['n']]['ljns'][$ljn]['ins'] = str_replace('\'','\\\'',$ins);
			$places[$row['n']]['ljns'][$ljn]['ind'] = $ind;
		}
		
		
		// Get all places where the court case took place.
		$q = $ns->sparql." select ?n ?lat ?long ?zpn ?zplat ?zplong where { <".$rnl_ljn."> rnl:instantie ?i. ?i <http://dbpedia.org/ontology/locationCity> ?p . ?p gn:name ?n . ?p geo:lat ?lat . ?p geo:long ?long . OPTIONAL { <http://www.ljn.nl/".$ljn."> rnl:zittingsplaats ?zp . ?zp gn:name ?zpn . ?zp geo:lat ?zplat . ?zp geo:long ?zplong .}}";
		
		// print $q;
		
		$rows = $sc->query($q, 'rows');

		// print_r($rows);
		// 
		// exit(0);
		
		foreach ($rows as $row) {
			if($row['zpn'] == null ){
				$courts[$row['n']]['lat'] = $row['lat'];
				$courts[$row['n']]['long'] = $row['long'];
				$courts[$row['n']]['ljns'][$ljn]['src'] = $src;
				$courts[$row['n']]['ljns'][$ljn]['ins'] = str_replace('\'','\\\'',$ins);
				$courts[$row['n']]['ljns'][$ljn]['ind'] = $ind;
			} else {
				$courts[$row['zpn']]['lat'] = $row['zplat'];
				$courts[$row['zpn']]['long'] = $row['zplong'];
				$courts[$row['zpn']]['ljns'][$ljn]['src'] = $src;
				$courts[$row['zpn']]['ljns'][$ljn]['ins'] = str_replace('\'','\\\'',$ins);
				$courts[$row['zpn']]['ljns'][$ljn]['ind'] = $ind;
			}
		}		
		
		
		
		$resultstext .= "\n<div id=".$ljn.">\n";
		$resultstext .= "<table style='border: 0px; width: 100%; padding-bottom: 2em; padding-right: 1em;'>\n";
		$resultstext .= "<tr><td class='resultheader' style='width: 100px' colspan='2'><span style='padding-left: 74px; vertical-align: top;'>LJN ".$ljn."</span><span style='float: right; font-weight: normal;'>".number_format($score,2)."%</span><td></tr>\n";
		$resultstext .= "<tr<td class='result_attribute' style='width: 100px'>Datum</td><td class='result_value' >".$hrdate."</td></tr>\n";
		$resultstext .= "<tr><td class='result_attribute' style='width: 100px'>Beschrijving</td><td class='result_value'>
		Uitspraak in <a href='results.php?q=".$query." AND procedure_soort:\"".$procedure."\"'>".$procedure."</a> van <a href='results.php?q=".$query." AND instantie:\"".$ins."\"'>".$ins."</a>";
		if($sector!=null){
			$resultstext .= " (<a href='results.php?q=".$query." AND sector_toon:\"".$sector."\"'>".$sector."</a>)";
		}
		$resultstext .= " binnen het rechtsgebied <a href='results.php?q=".$query." AND rechtsgebied_rechtspraak:\"".$rgb."\"'>".$rgb."</a></td></tr>\n";
		

		if($ind != null) {
			$resultstext .= "<tr><td class='result_attribute' style='width: 100px'>Indicatie</td><td class='result_value'>".$ind."</td></tr>\n";
		}
		
		$resultstext .= "<tr><td></td><td style='padding-top: 1ex;'><a href='show.php?q=".$query."&ljn=".$ljn."' target='_new'>Volledige Tekst</a></td></tr>\n";
		
		$resultstext .= "</table></div>";
	}

	$json .= "]}";

	$fbox = "";
	$fbox .= getSelect($instanties,"Instantie","instantie");
	$fbox .= getSelect($rechtsgebieden,"Rechtsgebied","rechtsgebied");
	$fbox .= getSelect($sectoren,"Sector","sector_toon");
	$fbox .= getSelect($procedures,"Procedure","procedure_soort");

	
	foreach ($places as $n=>$p) {
		$content = "<div><h3>".$n."</h3><div style=\'text-align: left;\'><strong>Uitspraken waarin deze plaatsnaam voorkomt: </strong><ul>";
		foreach ($p['ljns'] as $ljn=>$attrs){
			$content .= "<li><a href=\"show.php?q=".$n."&ljn=".$ljn."\" target=\"_new\">".$ljn."</a><br/><strong>Instantie: </strong>".$attrs['ins']."<br/><div style=\'font-size: smaller\'>".$attrs['ind']."</div></li>";
		}
		$content .= "</ul></div></div>";
		// $content = htmlentities($content);
		$pas[] = "['".$n."', ".$p['lat'].", ".$p['long'].", '".$content."']";
	}
	
	foreach ($courts as $n=>$p) {
		$content = "<div><h3>".$n."</h3><div style=\'text-align: left;\'><strong>Uitspraken gedaan in deze plaats: </strong><ul>";
		foreach ($p['ljns'] as $ljn=>$attrs){
			$content .= "<li><a href=\"show.php?q=".$n."&ljn=".$ljn."\" target=\"_new\">".$ljn."</a><br/><strong>Instantie: </strong>".$attrs['ins']."<br/><div style=\'font-size: smaller\'>".$attrs['ind']."</div></li>";
		}
		$content .= "</ul></div></div>";
		// $content = htmlentities($content);
		$cos[] = "['".$n."', ".$p['lat'].", ".$p['long'].", '".$content."']";
	}
	
}

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






<script type="text/javascript">
YAHOO.namespace("example.container");


// Include using PHP otherwise it does not work... strangely enough.
<?php include "js/bestportal.js"; ?>

// YAHOO.util.Event.addListener(window, "load", init);

</script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 

<script>
Timeline_ajax_url="http://localhost/best-portal/js/timeline_ajax/simile-ajax-api.js";
Timeline_urlPrefix='http://localhost/best-portal/js/timeline_js/';
Timeline_parameters='bundle=true';
</script>
<script src="http://localhost/best-portal/js/timeline_js/timeline-api.js" type="text/javascript">
</script>


<script type="text/javascript"> 

var tl;

function initialize() {
  var myOptions = {
    zoom: 6,
    center: new google.maps.LatLng(52, 5.5),
    mapTypeId: google.maps.MapTypeId.TERRAIN
  }
  var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
 
  setMarkers(map, places, 'img/place.png');
  setMarkers(map, courts, 'img/court.png');
  var eventSource = new Timeline.DefaultEventSource();


  var bandInfos = [
    Timeline.createBandInfo({
        eventSource:    eventSource,
        date:           "<?php print $latest_date;?>",
        width:          "70%", 
        intervalUnit:   Timeline.DateTime.MONTH, 
        intervalPixels: 100
    }),
    Timeline.createBandInfo({
        overview:       true,
        eventSource:    eventSource,
        date:           "<?php print $latest_date;?>",
        width:          "30%", 
        intervalUnit:   Timeline.DateTime.YEAR, 
        intervalPixels: 110
    })
  ];
  bandInfos[1].syncWith = 0;
  bandInfos[1].highlight = true;

  var json = <?php print $json; ?>;
  var url = '.';

  tl = Timeline.create(document.getElementById("timeline"), bandInfos);
  eventSource.loadJSON(json, url);
  tl.layout();

  // tl.loadJSON(json, function(json, url) {
  //     eventSource.loadJSON(json, url);
  // });


}
 
/**
 * Data for the markers consisting of a name, a LatLng and a zIndex for
 * the order in which these markers should display on top of each
 * other.
 */
var places = [
<?php
foreach ($pas as $pa) {
	print $pa.",\n";
}
print "['bla',0,0,'empty']\n";
?>
];

var courts = [
<?php
foreach ($cos as $co) {
	print $co.",\n";
}
print "['bla',0,0,'empty']\n";
?>
];


 
function setMarkers(map, locations, img) {
  // Add markers to the map

  for (var i = 0; i < (locations.length-1); i++) {
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

function createMarker(map, beach, myLatLng, infowindow, image){
	var marker = new google.maps.Marker({
        position: myLatLng,
		icon: image,
        map: map,
        title: beach[0],
    });

	google.maps.event.addListener(marker, 'click', function() {
	  infowindow.open(map,marker);
	});
}

var resizeTimerID = null;
function onResize() {
    if (resizeTimerID == null) {
        resizeTimerID = window.setTimeout(function() {
            resizeTimerID = null;
            tl.layout();
        }, 500);
    }
}



</script> 


</head> 
<body style="margin:0px; padding:0px;" onload="initialize()"> 

	<div id="smallbanner" style='width: 100%;'>
		<img src="<?php print $config->portal_url;?>/img/best-logo-96dpi-40px.png" width="40" align="right" alt="BEST logo" valign="top" style="padding-right: 15px;"/>
	    <div class="smallbannerheading">BestPortal</div>
		<div class="smallbannersubheading">Totaal <?php print $results->response->numFound;?> resultaten, <?php print $start." t/m ".($start+10)."."; ?></div>
		<!-- <div class="copyrightnotice">
			Copyright (c) 2010, Rinke Hoekstra, Vrije Universiteit Amsterdam
		</div> -->
		<div class="casedescription">
			<div style='width: 170px; float: left; padding: 5px 15px 0 15px; background: #ddd;'><h4>Beschrijving</h4></div>
			<div style='float: left; text-align: left; padding: 5px 15px 0 15px; font-size: small;'>
				<table>
				<tr><td style='width: 100px;'>Normaal: </td><td><div id='lc' style='display: inline;'></div></td></tr>
				<tr><td style='width: 100px;'>Juridisch: </td><td><div id='tc' style='display: inline'></div></td></tr>
				</table>
			</div>
		</div>
		<div class="casedescription">
			<div style='width: 170px; float: left; padding: 5px 15px 0 15px; background: #ddd;'><h4>Filters</h4></div>
			<div style='float: left; text-align: left; padding: 5px 15px 0 15px; font-size: small;'>
				<table>
				<tr><td style='width: 100px;'>Selectie: </td><td><div id='filter' style='display: inline;'></div></td></tr>
				</table>
			</div>
		</div>
	</div>
	<div class="colmask holygrail">
		<div class="colmid">
			<div class="colleft">
				<div class="col1wrap">
					<div class="col1" id="results">

						<h4 style='text-align: left;'>Gevonden Uitspraken</h4>
						<div style='text-align: right; '><a href='results.php?q=<?php print $query."&s=".($start+10); ?>'>Volgende --></a></div>
						<?php print $resultstext; ?>
					</div>
				</div>
				<div class="col2" style='text-align: left;'>
					<?php printLaymanConcepts($ct,$config); ?>
					<h4>Eigenschappen</h4>
					<?php print $fbox; ?>
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


<?php
	function printLaymanConcepts($ct,$config){
		print "<h4 style='text-align: left;'>Factoren</h4>";
		print "<table><tr><td valign='top' width='300'>";


		while($role = current($config->layman_roles)){
			$key = key($config->layman_roles);
			print "<div style='font-size: small;'>\n";
			print "<h6>".$role[0]." <a style='font-weight: normal;' title='".$role[2]."'>?</a></h6>\n";	
			// print "<div style='font-size: small;'>".$role[2]."</div>";
			print "<select id='".$key."' onChange=\"addInlineConcept('lc','tc','".$key."'); doUpdate(); \" style='width: 170px;'>\n";
			print "<option class='concept' value='none' selected>(none)</option>\n";

			$ct->makeCustomTree('',$role[1],'&nbsp;&nbsp;&nbsp;','option','value');

			print  "</select>\n";
			print "</div>\n";
			next($config->layman_roles);
		}

		print "</td></tr></table>";

	}

	function getSelect($array,$title,$id){
		if($array) {
			$fbox .= "<div style='font-size: small;'>\n";
			$fbox .= "<h6>".$title."</h6>\n";
			$fbox .= "<select id='".$id."' onChange=\"addFilter('filter','".$id."'); doUpdate(); \" style='width: 170px;'>\n";
		 	$fbox .= "<option class='concept' value='none' selected>(none)</option>\n";
			foreach($array as $a=>$rnla) {
				$fbox .= "<option class='concept' value='".$rnla."' title='".$a."' label='".$a."'>".$a."</option>\n";
			}
			$fbox .= "</select>\n</div>\n";
			return $fbox;
		}		
		return "";
		
	}



?>