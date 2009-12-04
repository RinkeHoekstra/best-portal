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
// 
// print_r($_POST);
// print_r($_GET);

$type = $_POST["type"];

if($type!="complex"){

	$concepts=$_GET["q"];

	$gm = new GetMapping($concepts);

	print "<div id=\"mappedTerms\">
		<h2>
			Mapping
		</h2>
		<div id=\"mapping\">";


	$skos_q = $gm->getSKOSMapping();

	$now = time();
	$timestamp = date(DATE_ATOM,$now);
	$query_instance = "query:q-".date('Ymd-His',$now);

	$gm->getOWLMapping($query_instance,$timestamp);

	print "</div></div>";

	$gm->getQueryButtons($query_instance, $skos_q);
	$gm->getVerdictButtons($query_instance);

	print "</div>";
} else {

	$roles = array_keys($_POST);
	// Remove the 'type' key from the array of roles.
	unset($roles[0]);
	$gcm = new GetComplexMapping($roles,$_POST);
	
	$now = time();
	$timestamp = date(DATE_ATOM,$now);
	$query_instance = "query:q-".date('Ymd-His',$now);
	
	$gcm->getOWLMapping($query_instance,$timestamp);
	$gcm->getQueryLinks($query_instance);
	
}


class GetComplexMapping{
	
	private $connection;
	private $roles;
	private $values;
	private $ns;
	private $cl;
	
	function __construct($roles,$values){
		$this->connection = new SPARQLConnection();
		$this->cl = new ConceptList($this->connection);
		$this->ns = new Namespaces();
		
	 	$this->roles = $roles;
		$this->values = $values;
	}
	
	public function getOWLMapping($query_instance,$timestamp){
		// Create query instance for OWL-Based mappings
		$turtle = $query_instance;
		foreach ($this->roles as $r) {
			foreach($this->values[$r] as $c) {
				$turtle .=" best:".$r." <".urldecode($c).">; ";
			}
		}
		$turtle .= " a best:Query; best:timestamp \"".$timestamp."\"^^xsd:dateTime .";

		// Add prefixes
		$sparql_update_query = $this->ns->sparql."INSERT { ".$turtle." }";
		
		// Send update query
		$this->connection->update($sparql_update_query);

		// Get all legal terms acquired through OWL-Based mapping
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label ?note WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme . ?concept skos:prefLabel ?label . OPTIONAL {?concept skos:note ?note .}}";

		print "<h5>Legal Terms</h5>\n";
		$this->cl->getDiv($sparql_query, 'concept');
		
		// Get all legal terms acquired through OWL-Based mapping
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?mapping ?label ?note WHERE { ".$query_instance." a ?mapping. ?mapping rdfs:subClassOf bm:Mapping . ?mapping skos:prefLabel ?label . OPTIONAL {?mapping skos:note ?note .}}";

		print "<h5>Applicable Mappings</h5>\n";
		$this->cl->getDiv($sparql_query, 'mapping', 'mapping');
	}
	
	
	public function getQueryLinks($query_instance) {
		print "<h5>Applicable Queries</h5>\n";


		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label ?weight WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme .  ?concept to:fingerprint ?fp . ?fp to:value ?label . ?fp to:weight ?weight .}";
		
		print "<div class='query'>";
		$this->cl->getPlainWeightedQueryString($sparql_query, "wtqowl", "Weighed Tort Query (OWL)");
		print "</div>";
		// Laymen query

		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme lv:laymen-scheme . {?concept skos:altLabel ?label} UNION {?concept skos:prefLabel ?label} .}";

		print "<div class='query'>";
		$this->cl->getPlainQueryString($sparql_query, "mq", "Laymen Query");
		print "</div>";

	}	
	
	
}


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
		print "<div class='resultlist'>";
		$this->cl->getDiv($sparql_query);
		print "</div>";

		return $skos_mapped_concepts_partial_query;
	}


	/**
	 *
	 *
	 * @param unknown $query_instance
	 */
	public function getOWLMapping($query_instance,$timestamp) {
		// Create query instance for OWL-Based mappings

		$turtle = $query_instance;
		foreach ($this->qs as $q) {
			if ($q != "") {
				$turtle .=" bm:about <".$q.">; ";
			}
		}
		$turtle .= " a best:Query; best:timestamp \"".$timestamp."\"^^xsd:dateTime .";

		// Add prefixes
		$sparql_update_query = $this->ns->sparql."INSERT { ".$turtle." }";

		// Send update query
		$this->connection->update($sparql_update_query);

		// Get all legal terms acquired through OWL-Based mapping
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme tv:tort-scheme . ?concept skos:prefLabel ?label .}";

		print "<h5>Legal Terms (OWL-Based Mapping)</h5>\n";
		print "<div class='resultlist'>";
		$this->cl->getDiv($sparql_query);
		print "</div>";
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
