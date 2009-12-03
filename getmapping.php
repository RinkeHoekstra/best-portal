<?php
/**
 * getmapping.php
 *
 * @package default
 */


require_once "lib/class.ConceptList.php";
require_once "lib/class.VerdictList.php";
require_once "lib/class.Namespaces.php";
require_once "lib/class.SPARQLConnection.php";

$concepts=$_GET["q"];

$gm = new GetMapping($concepts);

print "<div id=\"mappedTerms\">
	<h2>
		Mapping
	</h2>
	<div id=\"mapping\">";


$skos_q = $gm->getSKOSMapping();

$query_instance = "query:q-".date('Ymd-His');

$gm->getOWLMapping($query_instance);

print "</div></div>";

$gm->getQueryButtons($query_instance, $skos_q);
$gm->getVerdictButtons($query_instance);

print "</div>";



class GetMapping {

	private $qs;
	private $connection;
	private $cl;
	private $ns;





	/**
	 *
	 *
	 * @param unknown $concepts
	 */
	function __construct($concepts) {
		$this->qs = explode(',', $concepts);

		$this->connection = new SPARQLConnection();
		$this->cl = new ConceptList($this->connection);
		$this->vl = new VerdictList($this->connection);
		$this->ns = new Namespaces();



	}



	/**
	 *
	 *
	 * @return unknown
	 */
	public function getSKOSMapping() {
		// Build query for SKOS mapping
		$skos_mapped_concepts_partial_query = "";

		foreach ($this->qs as $q) {
			if ($q != "") {
				$skos_mapped_concepts_partial_query .=" { <".$q."> skos:broadMatch ?concept . } UNION { <".$q."> skos:exactMatch ?concept . } UNION ";
			}
		}
		$skos_mapped_concepts_partial_query = rtrim($skos_mapped_concepts_partial_query, "UNION ");

		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ";
		$sparql_query .= $skos_mapped_concepts_partial_query;
		$sparql_query .= " ?concept skos:inScheme tv:tort-scheme . ?concept skos:prefLabel ?label .} ";

		print "<h5>Legal Terms (SKOS-Based Mapping)</h5>\n";
		$this->cl->getDiv($sparql_query);

		return $skos_mapped_concepts_partial_query;
	}


	/**
	 *
	 *
	 * @param unknown $query_instance
	 */
	public function getOWLMapping($query_instance) {
		// Create query instance for OWL-Based mappings

		$turtle = $query_instance;
		foreach ($this->qs as $q) {
			if ($q != "") {
				$turtle .=" bm:about <".$q.">; ";
			}
		}
		$turtle .= " a best:Query.";

		// Add prefixes
		$sparql_update_query = $this->ns->sparql."INSERT { ".$turtle." }";

		// Send update query
		$this->connection->update($sparql_update_query);

		// Get all legal terms acquired through OWL-Based mapping
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme . ?concept skos:prefLabel ?label .}";

		print "<h5>Legal Terms (OWL-Based Mapping)</h5>\n";
		$this->cl->getDiv($sparql_query);
	}


	/**
	 *
	 *
	 * @param unknown $query_instance
	 * @param unknown $skos_q
	 */
	public function getQueryButtons($query_instance, $skos_q) {
		print "<fieldset id='queries'>";
		print "<legend>Generated Queries</legend>\n";
		print "<div id='querybuttons' class='yui-buttongroup'>";
		print "<p>Click a button to paste the corresponding query to the Solr Query area for inspection.</p>";
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme .  {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";


		$this->cl->getQueryString($sparql_query, "tqowl", "Tort Query (OWL)");


		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ".$skos_q." ?concept skos:inScheme tv:tort-scheme .  {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";

		// print "<pre>".htmlentities($sparql_query)."</pre>";

		$this->cl->getQueryString($sparql_query, "tqskos", "Tort Query (SKOS)");


		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label ?weight WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme .  ?concept to:fingerprint ?fp . ?fp to:value ?label . ?fp to:weight ?weight .}";


		$this->cl->getWeightedQueryString($sparql_query, "wtqowl", "Weighed Tort Query (OWL)");


		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label ?weight WHERE { ".$skos_q." ?concept skos:inScheme tv:tort-scheme .  ?concept to:fingerprint ?fp . ?fp to:value ?label . ?fp to:weight ?weight .}";


		$this->cl->getWeightedQueryString($sparql_query, "wtqskos", "Weighed Tort Query (SKOS)");


		// Laymen query

		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme lv:laymen-scheme . {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";


		$this->cl->getQueryString($sparql_query, "mq", "Laymen Query");



		print "</div>";
		print "</fieldset>";
	}





	/**
	 *
	 *
	 * @param unknown $query_instance
	 */
	public function getVerdictButtons($query_instance) {

		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?verdict ?src ?ljn WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme . ?concept bm:describes ?verdict . ?verdict a rnl:Uitspraak ; metalex:src ?src ; rnl:ljn ?ljn .}";

		print "<fieldset><legend>Direct Results</legend>";
		print "<p>Click a button to inspect the text of the corresponding verdict on <a href='http://www.rechtspraak.nl'>http://www.rechtspraak.nl</a>.</p>";
		// print "<pre>".htmlentities($sparql_query)."</pre>";
		$this->vl->getVerdicts($sparql_query);
		print "</fieldset>";
	}


}

















?>
