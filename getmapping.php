<?php

require_once "lib/class.RepositoryConnection.php";
require_once "lib/class.ConceptList.php";
require_once "lib/class.VerdictList.php";
require_once "lib/class.Namespaces.php";

$concepts=$_GET["q"];


// print "<pre>".$concepts."</pre>";

$qs = explode(',',$concepts);

$sc = new RepositoryConnection("http://localhost:8080/openrdf-sesame/repositories/best");
$cl = new ConceptList();
$ns = new Namespaces();

$skos_mapped_concepts_partial_query = "";

foreach($qs as $q){
	if($q != ""){
		$skos_mapped_concepts_partial_query .=" { <".$q."> skos:broadMatch ?concept . } UNION { <".$q."> skos:exactMatch ?concept . } UNION ";
	}
}
$skos_mapped_concepts_partial_query = rtrim($skos_mapped_concepts_partial_query,"UNION ");

$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ";
$sparql_query .= $skos_mapped_concepts_partial_query;
$sparql_query .= " ?concept skos:inScheme tv:tort-scheme . ?concept skos:prefLabel ?label .} ";

	print "<h5>Legal Terms (SKOS-Based Mapping)</h5>\n";
$cl->getDiv($sparql_query);


// Create query instance for OWL-Based mappings
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



// Get all legal terms acquired through OWL-Based mapping
$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept best:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme . ?concept skos:prefLabel ?label .}";

// print "<pre>".htmlentities($sparql_query)."</pre>";
	print "<h5>Legal Terms (OWL-Based Mapping)</h5>\n";
$cl->getDiv($sparql_query);


$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept best:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme .  {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";

// print "<pre>".htmlentities($sparql_query)."</pre>";
	print "<h5>Generated Queries</h5>\n";
	print "<div class='resultlist'>";
	print "<div class='solrquery'>";
	$cl->getQueryString($sparql_query,"tort query (OWL)");
	print "</div>";
	
	$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ".$skos_mapped_concepts_partial_query." ?concept skos:inScheme tv:tort-scheme .  {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";

	// print "<pre>".htmlentities($sparql_query)."</pre>";
		print "<div class='solrquery'>";
		$cl->getQueryString($sparql_query,"tort query (SKOS)");
		print "</div>";

	$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label ?weight WHERE { ?concept best:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme .  ?concept to:fingerprint ?fp . ?fp to:value ?label . ?fp to:weight ?weight .}";

		print "<div class='solrquery'>";
		$cl->getWeightedQueryString($sparql_query,"weighed tort query (OWL)");
		print "</div>";

		$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label ?weight WHERE { ".$skos_mapped_concepts_partial_query." ?concept skos:inScheme tv:tort-scheme .  ?concept to:fingerprint ?fp . ?fp to:value ?label . ?fp to:weight ?weight .}";

			print "<div class='solrquery'>";
			$cl->getWeightedQueryString($sparql_query,"weighed tort query (SKOS)");
			print "</div>";


	$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept best:describes ".$query_instance." . {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";

	// print "<pre>".htmlentities($sparql_query)."</pre>";
		print "<div class='solrquery'>";
		$cl->getQueryString($sparql_query,"mixed query");
		print "</div>";


	print "</div>";

	
	$vl = new VerdictList();

$sparql_query = $ns->sparql."SELECT DISTINCT ?verdict ?src ?ljn WHERE { ?concept best:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme . ?concept best:describes ?verdict . ?verdict a rnl:Uitspraak ; metalex:src ?src ; rnl:ljn ?ljn .}";

	print "<h5>Direct Results</h5>";
	// print "<pre>".htmlentities($sparql_query)."</pre>";
	$vl->getVerdicts($sparql_query);

	
	
?>