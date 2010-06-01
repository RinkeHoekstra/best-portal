<?php

require_once "lib/Solr/Service.php";
require_once "lib/class.SPARQLConnection.php";
require_once "lib/class.Namespaces.php";

$solr = new Apache_Solr_Service('localhost',8983,'/solr/');
$query = $_GET["q"];
$ljn = $_GET["ljn"];

if($query == null) $query = "eigen energie";
if($ljn == null) $ljn = "BM4553";

$params['fl'] = 'score';
$params['hl'] = 'on';
$params['hl.fl']= 'uitspraak_anoniem';
$params['hl.fragsize']= '50000';
$params['hl.snippets'] = '50';
$params['hl.simple.pre'] = "<span class='hl'>";
$params['hl.simple.post'] = '</span>';

$query = $query." AND ljn:".$ljn;
$results = $solr->search($query,$start,$start+10,$params);

// print_r($results);

$sc = new SPARQLConnection();
$ns = new Namespaces();

$latest_date = "1980-05-19T00:00:00Z";

$hl_array = (array) $results->highlighting;


if ($results){
	$hla = (array) $hl_array[$ljn];
	$hls = $hla['uitspraak_anoniem'];
	
	foreach ($hls as $t){
		$t = utf8_decode($t);
		// $t = htmlentities($t);
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
			Copyright (c) 2010, Rinke Hoekstra, Vrije Universiteit Amsterdam
		</div>
		</div>
	<?php print $t;?>
	</div>
</body>