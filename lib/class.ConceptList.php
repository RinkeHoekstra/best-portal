<?php



// require_once "namespaces.php";
require_once "arc/ARC2.php";
require_once "class.Namespaces.php";
// require_once "class.RepositoryConnection.php";

class ConceptList {
	
	private $connection;
	private $ns;
	private $rc;
	
	function __construct($c){
		$this->ns = new Namespaces();
		$this->connection = $c;
	}
	
	
	public function makeList($scheme,$onChange,$name='conceptlist'){
		$sparql_query = $this->ns->sparql."SELECT DISTINCT ?concept ?label WHERE {?concept a skos:Concept . ?concept skos:prefLabel ?label . ?concept skos:inScheme ".$scheme." . } ORDER BY ?label ";
		
		
		$this->getSelectBlock($sparql_query,$onChange,$name);
		
	}

	
	
	function getSelectBlock($sparql_query,$onChange,$name='conceptlist',$value_fn='concept',$label_fn='label') {
		// print "<pre>".htmlentities($sparql_query)."</pre>";
		
		$rows = $this->connection->query($sparql_query, 'rows');
		
		
		

			print "<select multiple size='10' class='conceptList' name='".$name."' onchange='".$onChange."'>\n";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				print "<option value='".urlencode($value)."'>".$label."</option>\n";
			}		
			print "</select>";
		
	}

	public function getDiv($sparql_query,$value_fn='concept',$label_fn='label') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');

			print "<div class='resultlist'>";
			if(count($rows)>0){
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				print "<div class='concept' id='".$value."'>".$label."</div>\n";
			}		
			} else { print "[none]"; }
			print "</div>";

		
	}

	public function getQueryString($sparql_query,$id,$name,$value_fn='concept',$label_fn='label') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');


			if(count($rows)>0){
			print "<button value='(";
			$qs = "";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\"".$label."\" OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") AND (\"".$label."\" OR ";
					$oldvalue = $value;
				}
			}		
			
			$qs = rtrim($qs,"OR ");
			$qs .=  ")'";
			print $qs." name='".$id."' id='".$id."' >".$name."</button>";
			} else { print "<button name='".$id."' id='".$id."'  disabled>".$name."</button>";} 
			

		
	}
	
	
	public function getWeightedQueryString($sparql_query,$id,$name,$value_fn='concept',$label_fn='label',$weight_fn='weight') {
		// print "<p>".$sparql_query."</p>";
		
		$rows = $this->connection->query($sparql_query, 'rows');


			if(count($rows)>0){
			print "<button value='(";
			$qs = "";
			$oldvalue = "";
			foreach($rows as $row) {
				$label = $row[$label_fn];
				$value = $row[$value_fn];
				$weight = $row[$weight_fn]/100;
				
				if($value == $oldvalue || $oldvalue == "") {
					$qs .= "\"".$label."\"^".$weight." OR ";
					$oldvalue = $value;
				} else {
					$qs = rtrim($qs,"OR ");
					$qs .= ") AND (\"".$label."\"^".$weight." OR ";
					$oldvalue = $value;
				}
			}		
			
			$qs = rtrim($qs,"OR ");
			$qs .=  ")'";
			print $qs." name='".$id."' id='".$id."' >".$name."</button>";
			} else { print "<button name='".$id."' id='".$id."'  disabled>".$name."</button>";} 

			

		
	}	
	
	
}

?>
