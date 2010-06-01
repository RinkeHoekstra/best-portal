<?php

require_once "lib/class.ConceptList.php";
require_once "lib/class.VerdictList.php";
require_once "lib/class.Namespaces.php";
require_once "lib/class.SPARQLConnection.php";


$roles = array_keys($_POST);
// Remove the 'type' key from the array of roles.
unset($roles[0]);
$jm = new JSONMapping($roles,$_POST);

$now = time();
$timestamp = date(DATE_ATOM,$now);

$query_instance = "query:q-".date('Ymd-His',$now);

$json = '{"mapping":';
$json .= $jm->getMapping($query_instance,$timestamp);
$json .= ',"query":';
$json .= $jm->getQuery($query_instance);
$json .= '}';

print $json;

class JSONMapping{
	
	private $connection;
	private $roles;
	private $values;
	private $ns;
	
	function __construct($roles,$values){
		$this->connection = new SPARQLConnection();
		$this->ns = new Namespaces();
		
	 	$this->roles = $roles;
		$this->values = $values;
	}
	
	public function getMapping($query_instance,$timestamp){
		// Create query instance for OWL-Based mappings
		
		$turtle = $query_instance;
		foreach ($this->roles as $r) {
			foreach($this->values[$r] as $c) {
				$turtle .=" ".$r." <".urldecode($c).">; ";
			}
		}
		$turtle .= " a best:Query; best:timestamp \"".$timestamp."\"^^xsd:dateTime .";

		// Add prefixes
		$sparql_update_query = $this->ns->sparql."INSERT { ".$turtle." }";
		
		// Send update query
		$this->connection->update($sparql_update_query);

		// Get all legal terms acquired through OWL-Based mapping
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label ?note WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme ".$this->ns->tort_scheme_new." . ?concept skos:prefLabel ?label . OPTIONAL {?concept skos:note ?note }}";

		return $this->getJSONList($sparql_query);
	}
	
	
	public function getQuery($query_instance) {
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label ?weight WHERE { ?concept bm:describes ".$query_instance." . ?concept skos:inScheme ".$this->ns->tort_scheme_new." .  ?concept to:fingerprint ?fp . ?fp to:value ?label . ?fp to:weight ?weight .}";
		
		return $this->getJSONQueryString($sparql_query);
	}	
	
	public function getJSONList($sparql_query) {
		
		$rows = $this->connection->query($sparql_query, 'rows');
		$oldvalue = "";
		$jsonl = '[';
		if(count($rows)>0){
			foreach($rows as $row) {
				$label = $row['label'];
				$value = $row['concept'];
				// Avoid duplicate entries
				if($value != $oldvalue){
					$jsonl .= '{"id":"'.$value.'","label":"'.$label.'"';
					if($row['note']!= null){
						$jsonl .= ',"note":"'.$row['note'].'"';
					} 
					if($row['weight']!= null){
						$jsonl .= ',"weight":"'.$row['weight'].'"';
					}
					$jsonl .= '},';
					$oldvalue = $value;
				}
			}	
			$jsonl = rtrim($jsonl,",");	
		} 
		$jsonl .= ']';
		return $jsonl;
	}
	
	
	public function getJSONQueryString($sparql_query) {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');

		if(count($rows)>0){
			$qs = "(";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row['label'];
				$value = $row['concept'];
				$weight = $row['weight']/100;
			
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\\\"".$label."\\\"^".$weight." OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") OR (\\\"".$label."\\\"^".$weight." OR ";
					$oldvalue = $value;
				}
			}		
		
			$qs = rtrim($qs,"OR ");
			$qs .= ")";
			return '"'.$qs.'"';
		} else {
			return 'null';
		}
		
	}
	
}


?>