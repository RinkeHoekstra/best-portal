<?php



require_once "namespaces.php";
require_once "arc/ARC2.php";
require_once "class.Namespaces.php";
// require_once "class.RepositoryConnection.php";

class ConceptList {
	
	// Repository is the url of the repository we want to connect to
	private $repository = 'http://localhost:8080/openrdf-sesame/repositories/best';
	private $ns;
	private $rc;
	private $store;
	
	function __construct(){
		$this->ns = new Namespaces();
		// $this->rc = new RepositoryConnection($this->repository,'sesame');
		
		/* configuration */ 
		$config = array(
		  /* remote endpoint */
		  'remote_store_endpoint' => $this->repository ,
		);

		/* instantiation */
		$this->store = ARC2::getRemoteStore($config);
	}
	
	
	public function makeList($scheme,$onChange,$name='conceptlist'){
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept a skos:Concept . ?concept skos:prefLabel ?label . ?concept skos:inScheme ".$scheme." . } ORDER BY ?label ";
		
		
		$this->getSelectBlock($sparql_query,$onChange,$name);
		
	}
	
	// function test(){
	// 	
	// 	$c_query = $this->ns->sparql."CONSTRUCT { ?x best:described_by lv:dierobject } WHERE {?x a best:Query }";
	// 	
	// 	print "<pre>".htmlentities($c_query)."</pre>";
	// 	
	// 	$c_query_enc = urlencode($c_query);
	// 	
	// 	$graph_query = "<".$this->repository."?query=".$c_query_enc.">";
	// 	
	// 	$sparql_query = $this->ns->sparql."SELECT ?concept ?label WHERE { GRAPH ".$graph_query." { ?x a best:Query . ?x best:described_by ?concept . } } ?concept rdfs:label ?label . ?concept skos:inScheme tv:tort-scheme . ?lvconcept skos:inScheme lv:laymen-scheme.}";
	// 	
	// 	print "<pre>".htmlentities($sparql_query)."</pre>";
	// 	
	// 	$this->makeList($sparql_query);
	// 	
	// }
	
	
	function getSelectBlock($sparql_query,$onChange,$name='conceptlist',$value_fn='concept',$label_fn='label') {
		// print "<pre>".htmlentities($sparql_query)."</pre>";
		
		$rows = $this->store->query($sparql_query, 'rows');
		
		
		
		if (!$this->store->getErrors()) {
			print "<select multiple size='10' class='conceptList' name='".$name."' onchange='".$onChange."'>\n";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				print "<option value='".urlencode($value)."'>".$label."</option>\n";
			}		
			print "</select>";
		} else {
			foreach($this->store->getErrors() as $error) {
				print $error."<br/>";
			}
			throw new Exception("Errors! ".$this->store->getErrors());
		}
		
	}

	public function getDiv($sparql_query,$value_fn='concept',$label_fn='label') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->store->query($sparql_query, 'rows');

		if (!$this->store->getErrors()) {
			print "<div class='resultlist'>";
			if(count($rows)>0){
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				print "<div class='concept' id='".$value."'>".$label."</div>\n";
			}		
			} else { print "[none]"; }
			print "</div>";
		} else {
			foreach($this->store->getErrors() as $error) {
				print $error."<br/>";
			}
			throw new Exception("Errors! ".$this->store->getErrors());
		}
		
	}

	public function getQueryString($sparql_query,$name,$value_fn='concept',$label_fn='label') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->store->query($sparql_query, 'rows');

		if (!$this->store->getErrors()) {
			if(count($rows)>0){
			print "[<a href='javascript:pasteQuery(\"(";
			$qs = "";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\\\"".$label."\\\" OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") AND (\\\"".$label."\\\" OR ";
					$oldvalue = $value;
				}
			}		
			
			$qs = rtrim($qs,"OR ");
			$qs .=  ")\"";
			print $qs.");' class='queryString'>".$name."</a>]";
			} else { print "[".$name."]";} 
			
		} else {
			foreach($this->store->getErrors() as $error) {
				print $error."<br/>";
			}
			throw new Exception("Errors! ".$this->store->getErrors());
		}
		
	}
	
	
	public function getWeightedQueryString($sparql_query,$name,$value_fn='concept',$label_fn='label',$weight_fn='weight') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->store->query($sparql_query, 'rows');

		if (!$this->store->getErrors()) {

			if(count($rows)>0){
			print "[<a href='javascript:pasteQuery(\"(";
			$qs = "";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				$weight = $row[$weight_fn]/100;
				
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\\\"".$label."\\\"^".$weight." OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") AND (\\\"".$label."\\\"^".$weight." OR ";
					$oldvalue = $value;
				}
			}		
			
			$qs = rtrim($qs,"OR ");
			$qs .=  ")\"";
			print $qs.");' class='queryString'>".$name."</a>]";
			} else { print "[".$name."]";} 

			
		} else {
			foreach($this->store->getErrors() as $error) {
				print $error."<br/>";
			}
			throw new Exception("Errors! ".$this->store->getErrors());
		}
		
	}	
	
	
}

?>
