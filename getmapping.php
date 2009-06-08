<?php

require_once "lib/class.RepositoryConnection.php";
require_once "lib/class.ConceptList.php";
require_once "lib/class.VerdictList.php";
require_once "lib/class.Namespaces.php";

$concepts=$_GET["q"];


// print "<pre>".$concepts."</pre>";

$qs = explode(',',$concepts);

$sc = new RepositoryConnection("http://localhost:8080/openrdf-sesame/repositories/best");

$query_instance = "query:q-".date('Ymd-His');

$turtle = $prefixes.$query_instance;

foreach($qs as $q){
	if($q != ""){
		$turtle .=" best:described_by <".$q.">; ";
	}
}

$turtle .= " a best:Query.";

// print "<pre>".htmlentities($turtle)."</pre>";

$context = "<http://query.context>";

$sc->tellSesame($turtle,$context,'turtle');

$cl = new ConceptList();
$ns = new Namespaces();

$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept best:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme . ?concept skos:prefLabel ?label .}";

// print "<pre>".htmlentities($sparql_query)."</pre>";
	print "<h5>Legal Terms</h5>\n";
$cl->getDiv($sparql_query);


$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept best:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme .  {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";

// print "<pre>".htmlentities($sparql_query)."</pre>";
	print "<h5>Generated Queries</h5>\n";
	print "<div class='resultlist'>";
	print "<div class='solrquery'>";
	$cl->getQueryString($sparql_query,"tort query");
	print "</div>";
	
$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept best:describes ".$query_instance." . {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";

// print "<pre>".htmlentities($sparql_query)."</pre>";
	print "<div class='solrquery'>";
	$cl->getQueryString($sparql_query,"mixed query");
	print "</div>";

$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label ?weight WHERE { ?concept best:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme .  ?concept to:fingerprint ?fp . ?fp to:value ?label . ?fp to:weight ?weight .}";

	print "<div class='solrquery'>";
	$cl->getWeightedQueryString($sparql_query,"weighed tort query");
	print "</div>";
	print "</div>";
	
	$vl = new VerdictList();

$sparql_query = $ns->sparql."SELECT DISTINCT ?verdict ?src ?ljn WHERE { ?concept best:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme . ?concept best:describes ?verdict . ?verdict a rnl:Uitspraak ; metalex:src ?src ; rnl:ljn ?ljn .}";

	print "<h5>Direct Results</h5>";
	// print "<pre>".htmlentities($sparql_query)."</pre>";
	$vl->getVerdicts($sparql_query);

	
	
?>