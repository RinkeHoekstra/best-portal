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


print "<div id=\"mappedTerms\">
	<h2>
		Mapping
	</h2>
	<div id=\"mapping\">";



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


print "</div></div>";

// print "<pre>".htmlentities($sparql_query)."</pre>";
	print "<fieldset id='queries'>";
	print "<legend>Generated Queries</legend>\n";
	print "<div id='querybuttons' class='yui-buttongroup'>";
	print "<p>Click a button to paste the corresponding query to the Solr Query area for inspection.</p>";
	$cl->getQueryString($sparql_query,"tqowl","Tort Query (OWL)");

	
	$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ".$skos_mapped_concepts_partial_query." ?concept skos:inScheme tv:tort-scheme .  {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";

	// print "<pre>".htmlentities($sparql_query)."</pre>";

		$cl->getQueryString($sparql_query,"tqskos","Tort Query (SKOS)");


	$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label ?weight WHERE { ?concept best:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme .  ?concept to:fingerprint ?fp . ?fp to:value ?label . ?fp to:weight ?weight .}";


		$cl->getWeightedQueryString($sparql_query,"wtqowl","Weighed Tort Query (OWL)");


		$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label ?weight WHERE { ".$skos_mapped_concepts_partial_query." ?concept skos:inScheme tv:tort-scheme .  ?concept to:fingerprint ?fp . ?fp to:value ?label . ?fp to:weight ?weight .}";


			$cl->getWeightedQueryString($sparql_query,"wtqskos", "Weighed Tort Query (SKOS)");



	$sparql_query = $ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept best:describes ".$query_instance." . {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";

	// print "<pre>".htmlentities($sparql_query)."</pre>";

		$cl->getQueryString($sparql_query,"mq","Mixed Query");


	print "</div>";
	print "</fieldset>";

	
	$vl = new VerdictList();

$sparql_query = $ns->sparql."SELECT DISTINCT ?verdict ?src ?ljn WHERE { ?concept best:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme . ?concept best:describes ?verdict . ?verdict a rnl:Uitspraak ; metalex:src ?src ; rnl:ljn ?ljn .}";

	print "<fieldset><legend>Direct Results</legend>";
	print "<p>Click a button to inspect the text of the corresponding verdict on <a href='http://www.rechtspraak.nl'>http://www.rechtspraak.nl</a>.</p>";
	// print "<pre>".htmlentities($sparql_query)."</pre>";
	$vl->getVerdicts($sparql_query);
	print "</fieldset>";
	print "</div>";

	
	
?>