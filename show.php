<?php

require_once "lib/Solr/Service.php";
require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";

$solr = new Apache_Solr_Service('localhost',8983,'/solr/');
$query = $_GET["q"];
$ljn = $_GET["ljn"];

// if($query == null) $query = "(\"gevaarzetting\"^1 OR \"gevaar\"^0.12 OR \"gevaarzetting\"^1 OR \"beperking\"^0.11 OR \"voortbestaan\"^0.18 OR \"nalaten\"^0.1 OR \"waarschuwing\"^0.15 OR \"gevaarlijke toestand\"^1 OR \"voorschrift\"^0.12 OR \"voorkoming\"^0.15 OR \"gevaarlijk\"^0.25 OR \"veiligheid\"^0.15 OR \"in stand houden\"^0.23) OR (\"medebezitter roerende zaak\"^1)";
// if($ljn == null) $ljn = "AY8447";

$params['fl'] = 'score';
$params['hl'] = 'on';
$params['hl.fl']= 'uitspraak_anoniem';
$params['hl.fragsize']= '50000';
$params['hl.snippets'] = '50';
$params['hl.simple.pre'] = "<span class='hl'>";
$params['hl.simple.post'] = '</span>';

if($query != null) {
	$query = $query." AND ljn:".$ljn;
} else {
	$query = "ljn:".$ljn;
}
$results = $solr->search($query,0,1,$params);

// print_r($results);

if(count((array) $results->response->docs)==0){
	$query = "ljn:".$ljn;
	$results = $solr->search($query,0,1,$params);
}



$sc = new SPARQLConnection();
$ns = new Namespaces();

$latest_date = "1980-05-19T00:00:00Z";

$hl_array = (array) $results->highlighting;


if ($results){
	$hla = (array) $hl_array[$ljn];
	if($hla != null){
		$hls = $hla['uitspraak_anoniem'];
		foreach ($hls as $t){
			$t = utf8_decode($t);
			// $t = htmlentities($t);
			$t = nl2br($t);
		}
	} else {
		$docs = (array) $results->response->docs;
		$doc = $docs[0];
		$t = $doc->uitspraak_anoniem;
		$t = utf8_decode($t);
		$t = nl2br($t);
	}

}

?>

<html>
<head>
		<link rel="stylesheet" href="style.css" type="text/css" media="screen">
</head>
<body style='background: #004;'>
	<div style='text-align:left; width: 815px; margin-left: 200px; padding: 2em; background: white;'>
		<div id="banner" style='margin-bottom: 2em;'>
		<img src="<?php print $config->portal_url;?>img/best-logo-96dpi-80px.png" width="80" align="right" alt="BEST logo" valign="top"/>
	    <div class="bannerheading">Uitspraak <?php print $ljn; ?></div>
		<div class="bannersubheading">Voor de oorspronkelijke publicatie van deze tekst op Rechtspraak.nl zie <a href='http://www.rechtspraak.nl/ljn.asp?ljn=<?php print $ljn;?>'>hier</a>.</div>
		<div class="copyrightnotice">
			BestPortal is Copyright (c) 2010, Rinke Hoekstra, Vrije Universiteit Amsterdam
		</div>
		</div>
	<?php print $t;?>
	</div>
</body>