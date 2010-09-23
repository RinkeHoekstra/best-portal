<?php 

require_once "lib/Solr/Service.php";
require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";

$solr = new Apache_Solr_Service('localhost',8983,'/solr/');
$query = $_POST["q"];


if($query == null) {
	print '{"query":null,"solr":null,"timeline":null,"latestdate":null,"places":null,"courts":null}';	
	exit(0);
} 



$params['fl'] = 'score,rnl_ljn,ljn,datum_uitspraak,indicatie,instantie,procedure_soort,rechtsgebied_rechtspraak,rnl_instantie,rnl_procedure_soort,rnl_rechtsgebied_rechtspraak,rnl_status,sector_toon,zaaknummers,src';
$params['hl'] = 'on';
$params['hl.fl']= 'uitspraak_anoniem';
$params['hl.simple.pre'] = "<span class='hllight'>";
$params['hl.simple.post'] = '</span>';

$solr_results = $solr->search($query,0,20,$params);

$json_solr_results = $solr_results->getRawResponse();

$sc = new SPARQLConnection();
$ns = new Namespaces();

$latest_date = "1980-05-19T00:00:00Z";


if ($solr_results){
	// print $results->response->numFound;

	$times = array();
	foreach ($solr_results->response->docs as $doc) {
		$rnl_ljn =  $doc->rnl_ljn;
		$ljn = $doc->ljn;
		$src = $doc->src;
		$ind = utf8_decode($doc->indicatie);
		$ins = $doc->instantie;
		// $rgb = $doc->rechtsgebied_rechtspraak;
		// $sector = $doc->sector_toon;
		// $zaaknummers = $doc->zaaknummers;
		// $procedure = $doc->procedure_soort;
		$date = $doc->datum_uitspraak;
		// $hrdate = substr($date,0,10);
		// $score = $doc->score;
		
		
		// if($ins!="") $instanties[$ins] = $doc->rnl_instantie;
		// if($rgb!="") $rechtsgebieden[$rgb] = $doc->rnl_rechtsgebied_rechtspraak;
		// if($sector!="") $sectoren[$sector] = $doc->rnl_sector_toon;
		// if($procedure!="") $procedures[$procedure] = $doc->rnl_procedure_soort;
		
		
		// $ind=nl2br($ind);
		$ind=str_replace("\n", " ", $ind);
		$ind=str_replace("\r", " ", $ind);
		$ind=str_replace("\t", " ", $ind);
		$ind=str_replace("\r", " ", $ind);
		$ind=str_replace("\"", "'", $ind);
		$ind=str_replace("\\", "\\\\", $ind);
		$ind=str_replace("/", "\/", $ind);
		
		$src=str_replace("\t", "", $src);
		$src=str_replace("\n", "", $src);
		$src=str_replace("\r", "", $src);


		// Get all places 'mentioned' in the uitspraak
		$q = $ns->sparql." select ?n ?lat ?long where { <".$rnl_ljn."> rnl:locatie ?p . ?p gn:name ?n . ?p geo:lat ?lat . ?p geo:long ?long .}";
		$rows = $sc->query($q, 'rows');

		foreach ($rows as $row) {
			$places[$row['n']]['lat'] = $row['lat'];
			$places[$row['n']]['long'] = $row['long'];
			$places[$row['n']]['ljns'][$ljn]['src'] = $src;
			$places[$row['n']]['ljns'][$ljn]['ins'] = str_replace('\'','\\\'',$ins);
			$places[$row['n']]['ljns'][$ljn]['ind'] = $ind;
		}
		
		
		// Get all places where the court case took place.
		$q = $ns->sparql." select ?n ?lat ?long ?zpn ?zplat ?zplong where { <".$rnl_ljn."> rnl:instantie ?i. ?i <http://dbpedia.org/ontology/locationCity> ?p . ?p gn:name ?n . ?p geo:lat ?lat . ?p geo:long ?long . OPTIONAL { <http://linkeddata.few.vu.nl/rechtspraak/uitspraak/".$ljn."> rnl:zittingsplaats ?zp . ?zp gn:name ?zpn . ?zp geo:lat ?zplat . ?zp geo:long ?zplong .}}";
		$rows = $sc->query($q, 'rows');
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

		// Set the latest date to the latest publication date (for orienting the timeline)
		if($doc->datum_uitspraak > $latest_date) $latest_date = $doc->datum_uitspraak;
		
		// Create JSON object for the timeline
		$times[] = array('start'=>$date,'title'=>$ljn,'durationEvent'=>false,'link'=>'show.php?ljn='.$ljn,'description'=>$ind);

	}
	$timeline = array('dateTimeFormat'=>'iso8601','events'=>$times);
	$json_timeline = json_encode($timeline);

	$places_array = array();
	foreach ($places as $n=>$p) {
		$content = "<div><h3>".$n."</h3><div style='text-align: left;'><strong>Uitspraken waarin deze plaatsnaam voorkomt: </strong><ul>";
		foreach ($p['ljns'] as $ljn=>$attrs){
			$content .= "<li><a href='show.php?q=".$n."&ljn=".$ljn."' target='_new'>".$ljn."</a><br/><strong>Instantie: </strong>".$attrs['ins']."<br/><div style='font-size: smaller'>".$attrs['ind']."</div></li>";
		}
		$content .= "</ul></div></div>";
		// $content = htmlentities($content);
		$places_array[] = array($n,$p['lat'],$p['long'],$content);
	}
	
	$json_places = json_encode($places_array);
	
	$courts_array = array();
	foreach ($courts as $n=>$p) {
		$content = "<div><h3>".$n."</h3><div style='text-align: left;'><strong>Uitspraken gedaan in deze plaats: </strong><ul>";
		foreach ($p['ljns'] as $ljn=>$attrs){
			$content .= "<li><a href='show.php?ljn=".$ljn."' target='_new'>".$ljn."</a><br/><strong>Instantie: </strong>".$attrs['ins']."<br/><div style='font-size: smaller'>".$attrs['ind']."</div></li>";
		}
		$content .= "</ul></div></div>";
		// $content = htmlentities($content);
		$courts_array[] = array($n,$p['lat'],$p['long'],$content);

	}
	$json_courts = json_encode($courts_array);
	
}

$json_results = '{"query":"'.urlencode($query).'","solr":'.$json_solr_results.',"timeline":'.$json_timeline.',"latestdate":"'.$latest_date.'","places":'.$json_places.',"courts":'.$json_courts.'}';
print $json_results;
?>