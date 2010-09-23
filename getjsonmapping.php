<?php

require_once "lib/class.ConceptList.php";
require_once "lib/class.VerdictList.php";
require_once "lib/class.Namespaces.php";
require_once "lib/class.SPARQLConnection.php";


$roles = array_keys($_POST);
// Remove the 'type' key from the array of roles.
unset($roles[0]);
unset($roles[1]);
$jm = new JSONMapping($roles,$_POST);

// $now = time();
// $timestamp = date(DATE_ATOM,$now);
// 
// $query_instance = "query:q-".date('Ymd-His',$now);

$query_instance = urldecode($_POST['qi']);

$json = '{"mapping":';
$json .= $jm->getMapping($query_instance,$timestamp);
$json .= ',"mappingclasses":';
$json .= $jm->getMappingClass($query_instance);
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
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?subject ?label ?note WHERE { ?subject bm:describes ".$query_instance." . ?subject skos:inScheme ".$this->ns->tort_scheme_new." . ?subject skos:prefLabel ?label . OPTIONAL {?subject skos:note ?note }}";

		return $this->getJSONList($sparql_query);
		
		
	}
	
	
	public function getMappingClass($query_instance){
		
		// Get all mappings acquired through OWL-Based mapping
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?subject ?label ?note WHERE { ".$query_instance." a ?subject. ?subject rdfs:subClassOf bm:Mapping . ?subject skos:prefLabel ?label . OPTIONAL {?subject skos:scopeNote ?note .} }";

		return $this->getJSONList($sparql_query);
		
	}
	
	
	public function getQuery($query_instance) {
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?subject ?label ?weight ?distance WHERE { ?subject bm:describes ".$query_instance." . ?subject skos:inScheme ".$this->ns->tort_scheme_new." .  ?subject to:fingerprint ?fp . ?fp to:value ?label . ?fp to:weight ?weight . OPTIONAL {?fp to:distance ?distance .}}";
		
		return $this->getJSONQueryString($sparql_query);
	}	
	
	public function getJSONList($sparql_query) {
		
		$rows = $this->connection->query($sparql_query, 'rows');
		$oldvalue = "";
		$jsonl = '[';
		if(count($rows)>0){
			foreach($rows as $row) {
				$label = $row['label'];
				$value = $row['subject'];
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
				$value = $row['subject'];
				$weight = $row['weight']/100;
				
				$label=str_replace(":", " ", $label);
				
				if($row['distance']!= null){
					$distance = $row['distance'];
					
					if($value == $oldvalue || $oldvalue == "") {
						$qs .= "(\\\"".$label."\\\"~".$distance.")^".$weight." OR ";
						$oldvalue = $value;
					} else {
						$qs = rtrim($qs,"OR ");
						$qs .= ") OR ((\\\"".$label."\\\"~".$distance.")^".$weight." OR ";
						$oldvalue = $value;
					}
				} else {
					if($value == $oldvalue || $oldvalue == "") {
						$qs .= "\\\"".$label."\\\"^".$weight." OR ";
						$oldvalue = $value;
					} else {
						$qs = rtrim($qs,"OR ");
						$qs .= ") OR (\\\"".$label."\\\"^".$weight." OR ";
						$oldvalue = $value;
					}
				}
				
				// Remove colons as the JSON parser does not swallow them, resulting in HTTP 500 responses

			

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