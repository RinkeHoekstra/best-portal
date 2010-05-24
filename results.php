<?php

require_once "lib/Solr/Service.php";
require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";

$solr = new Apache_Solr_Service('localhost',8983,'/solr/');
$query = $_GET["q"];
// $query = "dier";
$results = $solr->search($query,0,100);

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
		$ins = $doc->instantie;
		$ind = $doc->indicatie;
		$rgb = $doc->rechtsgebied_rechtspraak;
		
		$ind=nl2br($ind);
		$ind=str_replace("\n", " ", $ind);
		$ind=str_replace("\r", " ", $ind);
		$ind=str_replace("\"", "\\\"", $ind);
		$ind=str_replace("'", "\\'", $ind);
		
		$src=str_replace("\t", "", $src);
		$src=str_replace("\n", "", $src);
		$src=str_replace("\r", "", $src);
		
		
		$q = $ns->sparql." select ?n ?lat ?long where { <".$rnl_ljn."> rnl:locatie ?p . ?p gn:name ?n . ?p geo:lat ?lat . ?p geo:long ?long .}";
		$rows = $sc->query($q, 'rows');

		$json .= "{'start':\"".$doc->datum_uitspraak."\",'title':\"".$doc->ljn."\",'durationEvent':false, 'link':\"".$src."\"";
		if($ind != null){ $json .= ", 'description': \"".$ind."\""; }
		$json .= "},\n";	
		// if($doc->datum_gepubliceerd!=null){	
		// 	$json .= "{'start':\"".$doc->datum_gepubliceerd."\",'title':\"".$doc->ljn." (publicatie)\",'durationEvent':false},\n";
		// }

		if($doc->datum_gepubliceerd > $latest_date) $latest_date = $doc->datum_gepubliceerd;
		foreach ($rows as $row) {
			$places[$row['n']]['lat'] = $row['lat'];
			$places[$row['n']]['long'] = $row['long'];
			$places[$row['n']]['ljns'][$ljn]['src'] = $src;
			$places[$row['n']]['ljns'][$ljn]['ins'] = str_replace('\'','\\\'',$ins);
			$places[$row['n']]['ljns'][$ljn]['ind'] = $ind;
		}
		
		$resultstext .= "\n<div id=".$ljn.">\n";
		$resultstext .= "<table style='border: 0px; width: 100%; padding-bottom: 1em;'>\n";
		$resultstext .= "<tr><td style='width: 100px'><strong>LJN:</strong></td><td><a href='".$src."'>".$ljn."</a><td></tr>\n";
		$resultstext .= "<tr><td style='width: 100px'><strong>Instantie</strong></td><td>".$ins."</td></tr>\n";
		$resultstext .= "<tr><td style='width: 100px'><strong>Rechtsgebied</strong></td><td>".$rgb."</td></tr>\n";
		$resultstext .= "<tr><td style='width: 100px'><strong>Indicatie</strong></td><td>".$ind."</td></tr>\n";
		$resultstext .= "</table></div>";
	}
	$json .= "]}";
	// $json = json_encode($json);
	
	foreach ($places as $n=>$p) {
		$content = "<div><h3>".$n."</h3><div style=\'text-align: left;\'><strong>Uitspraken waarin deze plaatsnaam voorkomt: </strong><ul>";
		foreach ($p['ljns'] as $ljn=>$attrs){
			$content .= "<li><a href=\"".$src."\">".$ljn."</a><br/><strong>Instantie: </strong>".$attrs['ins']."<br/><div style=\'font-size: smaller\'>".$attrs['ind']."</div></li>";
		}
		$content .= "</ul></div></div>";
		// $content = htmlentities($content);
		$pas[] = "['".$n."', ".$p['lat'].", ".$p['long'].", '".$content."']";
	}
	
}

?>

<html> 
<head> 
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
<title>Best Portal - Query Results</title> 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script src="http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=true" type="text/javascript"></script>

<script type="text/javascript"> 

var tl;

function initialize() {
  var myOptions = {
    zoom: 7,
    center: new google.maps.LatLng(52, 5.5),
    mapTypeId: google.maps.MapTypeId.TERRAIN
  }
  var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
 
  setMarkers(map, places);
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


 
function setMarkers(map, locations) {
  // Add markers to the map

  for (var i = 0; i < (locations.length-1); i++) {
    var beach = locations[i];
    var myLatLng = new google.maps.LatLng(beach[1], beach[2]);
	var contentString = beach[3];
	var infowindow = new google.maps.InfoWindow({
	    content: contentString
	});
	var image = new google.maps.MarkerImage('img/hammer.png',
	    // This marker is 20 pixels wide by 32 pixels tall.
	    new google.maps.Size(26, 26),
	    // The origin for this image is 0,0.
	    new google.maps.Point(0,0),
	    // The anchor for this image is its center.
	    new google.maps.Point(13, 13));

	createMarker(map, beach, myLatLng, infowindow, image);
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
	<link rel="stylesheet" href="style.css" type="text/css" media="screen">
</head> 
<body style="margin:0px; padding:0px;" onload="initialize()"> 
  <div>
  <div style="width: 60%; height: 60%; float: left; overflow: auto;">
	<?php print $resultstext; ?>
  </div>
  <div id="map_canvas" style="width:40%; height:60%"></div> 
  </div>
  <div id="timeline" style="width:100%; height:40%"></div>
</body> 
</html>
<?php


?>